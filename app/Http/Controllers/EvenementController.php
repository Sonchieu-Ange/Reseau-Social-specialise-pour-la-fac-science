<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\EvenementParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    public function index()
    {
        $evenements = Evenement::with('createur')->orderBy('date_debut')->get();
        return response()->json($evenements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:200',
        ]);

        $evenement = Evenement::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'lieu' => $request->lieu,
            'createur_id' => Auth::id(),
        ]);

        return response()->json($evenement, 201);
    }

    public function show($id)
    {
        $evenement = Evenement::with(['createur', 'participants'])->findOrFail($id);
        return response()->json($evenement);
    }

    public function update(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);
        if ($evenement->createur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $evenement->update($request->only(['titre', 'description', 'date_debut', 'date_fin', 'lieu']));
        return response()->json($evenement);
    }

    public function destroy($id)
    {
        $evenement = Evenement::findOrFail($id);
        if ($evenement->createur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $evenement->delete();
        return response()->json(['message' => 'Événement supprimé']);
    }

    public function participate($id)
    {
        $evenement = Evenement::findOrFail($id);
        $existe = EvenementParticipant::where('evenement_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->exists();
        if ($existe) {
            return response()->json(['message' => 'Déjà inscrit'], 400);
        }

        EvenementParticipant::create([
            'evenement_id' => $id,
            'utilisateur_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Inscription confirmée']);
    }

    public function cancelParticipation($id)
    {
        EvenementParticipant::where('evenement_id', $id)
                    ->where('utilisateur_id', Auth::id())
                    ->delete();
        return response()->json(['message' => 'Inscription annulée']);
    }
}