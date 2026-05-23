<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\GroupeMembre;
use App\Models\MessageGroupe;
use App\Models\Communaute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $groupes = Groupe::with(['createur', 'communaute'])->paginate(10);
        return view('groupes.index', compact('groupes'));
    }

    public function create()
    {
        $communautes = Communaute::orderBy('nom', 'asc')->get();
        return view('groupes.create', compact('communautes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
            'communaute_id' => 'nullable|exists:communautes,_id',
        ]);

        $groupe = Groupe::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'communaute_id' => $request->communaute_id ?: null, 
            'createur_id' => Auth::id(),
        ]);

        GroupeMembre::create([
            'groupe_id' => $groupe->_id,
            'utilisateur_id' => Auth::id(),
            'role' => 'admin',
        ]);

        return redirect()->route('groupes.show', $groupe->_id);
    }

    public function show($id)
    {
        $groupe = Groupe::with([
            'createur',
            'communaute',
            'membres.utilisateur',
            'messages' => function($query) {
                $query->orderBy('cree_le', 'desc');
            },
            'messages.auteur',
        ])->findOrFail($id);

        return view('groupes.show', compact('groupe'));
    }

    public function edit($id)
    {
        $groupe = Groupe::findOrFail($id);
        
        $membre = GroupeMembre::where('groupe_id', $id)
            ->where('utilisateur_id', Auth::id())->first();
            

        $communautes = Communaute::orderBy('nom', 'asc')->get();
        
        return view('groupes.edit', compact('groupe', 'communautes'));
    }

     public function update(Request $request, $id)
    {
        $groupe = Groupe::findOrFail($id);
        
        $membre = GroupeMembre::where('groupe_id', $id)
            ->where('utilisateur_id', Auth::id())->first();
            
        
        $request->validate([
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
            'communaute_id' => 'nullable|exists:communautes,_id',
        ]);
        
        $groupe->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'communaute_id' => $request->communaute_id ?: null,
        ]);

        return redirect()->route('groupes.show', $id);
    }

    public function destroy($id)
    {
        $groupe = Groupe::findOrFail($id);
        if ($groupe->createur_id != Auth::id()) {
            abort(403);
        }
        $groupe->delete();

        return redirect()->route('groupes.index');
    }

    public function join($id)
    {
        $existe = GroupeMembre::where('groupe_id', $id)
            ->where('utilisateur_id', Auth::id())->exists();
        if ($existe) {
            return back()->with('error', 'Déjà membre.');
        }
        GroupeMembre::create([
            'groupe_id' => $id,
            'utilisateur_id' => Auth::id(),
            'role' => 'membre',
        ]);

        return back();
    }

    public function leave($id)
    {
        GroupeMembre::where('groupe_id', $id)
            ->where('utilisateur_id', Auth::id())->delete();

        return back();
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['contenu' => 'required|string']);
        $membre = GroupeMembre::where('groupe_id', $id)
            ->where('utilisateur_id', Auth::id())->exists();
        if (! $membre) {
            abort(403);
        }

        MessageGroupe::create([
            'groupe_id' => $id,
            'auteur_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return back();
    }

    
    public function updateMessage(Request $request, $groupeId, $messageId)
    {
        $request->validate(['contenu' => 'required|string']);

        $message = MessageGroupe::where('_id', $messageId)
            ->where('groupe_id', $groupeId)
            ->firstOrFail();

        if ($message->auteur_id != Auth::id()) {
            abort(403, 'Vous ne pouvez modifier que vos propres messages.');
        }

        $message->update([
            'contenu' => $request->contenu,
            'modifie_le' => now(),
        ]);

        return back();
    }

    
    public function destroyMessage($groupeId, $messageId)
    {
        $message = MessageGroupe::where('_id', $messageId)
            ->where('groupe_id', $groupeId)
            ->firstOrFail();

        $estAuteur = $message->auteur_id == Auth::id();
        $estAdmin = GroupeMembre::where('groupe_id', $groupeId)
            ->where('utilisateur_id', Auth::id())
            ->where('role', 'admin')
            ->exists();

        if (! $estAuteur && ! $estAdmin) {
            abort(403, 'Vous ne pouvez pas supprimer ce message.');
        }

        $message->delete();

        return back();
    }
}
