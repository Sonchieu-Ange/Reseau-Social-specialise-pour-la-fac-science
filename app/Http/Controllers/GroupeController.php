<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\GroupeMembre;
use App\Models\MessageGroupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupeController extends Controller
{
    public function index()
    {
        $groupes = Groupe::with('createur')->get();
        return response()->json($groupes);
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
            'communaute_id' => $request->communaute_id,
            'createur_id' => Auth::id(),
        ]);

        // Ajouter le créateur comme admin
        GroupeMembre::create([
            'groupe_id' => $groupe->id,
            'utilisateur_id' => Auth::id(),
            'role' => 'admin',
        ]);

        return response()->json($groupe, 201);
    }

    public function show($id)
    {
        $groupe = Groupe::with(['createur', 'membres', 'messages.auteur'])->findOrFail($id);
        return response()->json($groupe);
    }

    public function update(Request $request, $id)
    {
        $groupe = Groupe::findOrFail($id);
        $membre = GroupeMembre::where('groupe_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->first();
        if (!$membre || $membre->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $groupe->update($request->only(['nom', 'description']));
        return response()->json($groupe);
    }

    public function destroy($id)
    {
        $groupe = Groupe::findOrFail($id);
        if ($groupe->createur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $groupe->delete();
        return response()->json(['message' => 'Groupe supprimé']);
    }

    public function join($id)
    {
        $groupe = Groupe::findOrFail($id);
        $existe = GroupeMembre::where('groupe_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->exists();
        if ($existe) {
            return response()->json(['message' => 'Déjà membre'], 400);
        }

        GroupeMembre::create([
            'groupe_id' => $id,
            'utilisateur_id' => Auth::id(),
            'role' => 'membre',
        ]);

        return response()->json(['message' => 'Adhésion réussie']);
    }

    public function leave($id)
    {
        GroupeMembre::where('groupe_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->delete();
        return response()->json(['message' => 'Vous avez quitté le groupe']);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['contenu' => 'required|string']);
        $groupe = Groupe::findOrFail($id);

        $membre = GroupeMembre::where('groupe_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->exists();
        if (!$membre) {
            return response()->json(['message' => 'Vous n\'êtes pas membre'], 403);
        }

        $message = MessageGroupe::create([
            'groupe_id' => $id,
            'auteur_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return response()->json($message->load('auteur'), 201);
    }
}