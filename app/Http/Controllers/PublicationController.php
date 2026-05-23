<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $publications = Publication::with('auteur')
            ->with(['likes', 'commentaires'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('publications.index', compact('publications'));
    }

    public function create()
    {
        return view('publications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => ['required', 'string', 'max:2000'],
            'type_media' => ['in:none,image,video,document'],
            'media_file' => ['nullable', 'file', 'max:10240', 'mimes:jpeg,png,jpg,gif,mp4,mov,avi,pdf,doc,docx'],
        ], [
            'contenu.required' => 'Le contenu de la publication est obligatoire.',
            'contenu.max' => 'Le contenu ne peut pas dépasser 2000 caractères.',
            'type_media.in' => 'Le type de média doit être none, image, video ou document.',
            'media_file.file' => 'Le fichier doit être un fichier valide.',
            'media_file.max' => 'Le fichier ne peut pas dépasser 10 Mo.',
            'media_file.mimes' => 'Les types de fichiers autorisés sont : jpeg, png, jpg, gif, mp4, mov, avi, pdf, doc, docx.',
        ]
        );

        $mediaUrl = null;
        if ($request->hasFile('media_file') && $request->type_media !== 'none') {
            $path = $request->file('media_file')->store('publications', 'public');
            $mediaUrl = Storage::url($path);
        }

        Publication::create([
            'auteur_id' => Auth::id(),
            'contenu' => $request->contenu,
            'type_media' => $request->type_media ?? 'none',
            'media_url' => $mediaUrl,
        ]);

        return redirect()->route('publications.index');
    }

    public function show($id)
    {
        $publication = Publication::with([
            'auteur',
            'commentaires' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'commentaires.auteur',
            'likes',
        ])->findOrFail($id);

        return view('publications.show', compact('publication'));
    }

    public function edit($id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->auteur_id != Auth::id()) {
            abort(403);
        }

        return view('publications.edit', compact('publication'));
    }

    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->auteur_id != Auth::id()) {
            abort(403);
        }
        $request->validate([
            'contenu' => 'required|string',
            'type_media' => 'in:none,image,video,document',
            'media_url' => 'nullable|url',
        ]);
        $publication->update($request->only(['contenu', 'type_media', 'media_url']));

        return redirect()->route('publications.show', $publication->_id);
    }

    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->auteur_id != Auth::id()) {
            abort(403);
        }
        $publication->delete();

        return redirect()->route('publications.index');
    }

    public function toggleLike($id)
    {
        $publication = Publication::findOrFail($id);
        $like = Like::where('publication_id', $id)
            ->where('utilisateur_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'publication_id' => $id,
                'utilisateur_id' => Auth::id(),
            ]);
        }

        return back();
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['contenu' => 'required|string']);
        $publication = Publication::findOrFail($id);
        $publication->commentaires()->create([
            'auteur_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return back();
    }
}
