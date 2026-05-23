<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id)
    {
        $user = Utilisateur::with([
            'publications' => function ($query) {
                $query->orderBy('date_publication', 'asc');
            },
            'publications.auteur',
            'groupes',
            'evenementsParticipe',
        ])->findOrFail($id);

        return view('profil.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'filiere' => 'nullable|string|max:150',
            'departement' => 'nullable|string|max:150',
            'competences' => 'nullable|string|max:255',
            'centres_interet' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('utilisateurs', 'email')->ignore($user->_id, '_id'),
            ],
            'mot_de_passe' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only([
            'nom', 'prenom', 'filiere', 'departement', 'competences', 'centres_interet', 'email'
        ]);

        if ($request->filled('mot_de_passe')) {
            $data['mot_de_passe'] = $request->mot_de_passe;
        }

        $user->fill($data);
        $user->save();

        return redirect()->route('profil.show', $user->_id)
            ->with('success', 'Profil mis à jour avec succès.');
    }
}