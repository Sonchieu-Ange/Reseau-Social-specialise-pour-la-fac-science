<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::with('auteur')
            ->withCount(['likes', 'commentaires'])
            ->orderBy('date_publication', 'desc')
            ->paginate(10);
        return response()->json($publications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',
            'type_media' => 'in:none,image,video,document',
            'media_url' => 'nullable|string',
        ]);

        $publication = Publication::create([
            'auteur_id' => Auth::id(),
            'contenu' => $request->contenu,
            'type_media' => $request->type_media ?? 'none',
            'media_url' => $request->media_url,
        ]);

        return response()->json($publication, 201);
    }

    public function show($id)
    {
        $publication = Publication::with(['auteur', 'commentaires.auteur', 'likes'])->findOrFail($id);
        return response()->json($publication);
    }

    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->auteur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $publication->update($request->only(['contenu', 'type_media', 'media_url']));
        return response()->json($publication);
    }

    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        if ($publication->auteur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $publication->delete();
        return response()->json(['message' => 'Publication supprimée']);
    }

    public function toggleLike($id)
    {
        $publication = Publication::findOrFail($id);
        $like = Like::where('publication_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'publication_id' => $id,
                'utilisateur_id' => Auth::id(),
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $publication->likes()->count()
        ]);
    }

    public function comment(Request $request, $id)
    {
        $request->validate(['contenu' => 'required|string']);
        $publication = Publication::findOrFail($id);
        $commentaire = $publication->commentaires()->create([
            'auteur_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);
        return response()->json($commentaire->load('auteur'), 201);
    }
}