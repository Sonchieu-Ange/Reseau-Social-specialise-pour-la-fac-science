<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()
    {
        return Utilisateur::all();
    }

    public function show($id)
    {
        $user = Utilisateur::with(['publications', 'groupes'])->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        // Vérification d'autorisation à ajouter (ex: Auth::id() == $user->id)
        $user->update($request->only(['nom', 'prenom', 'filiere', 'departement', 'competences', 'centres_interet']));
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = Utilisateur::findOrFail($id);
        // Autorisation
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}