<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:150'],
            'mot_de_passe' => ['required', 'string', 'min:6'],
        ],
            [
                'email.required' => 'L\'adresse e-mail est obligatoire.',
                'email.email' => 'L\'adresse e-mail doit être valide.',
                'email.max' => 'L\'adresse e-mail ne peut pas dépasser 150 caractères.',
                'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
                'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]
        );

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['mot_de_passe']])) {
            $request->session()->regenerate();

            return redirect()->intended('/publications');
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:utilisateurs'],
            'mot_de_passe' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['in:etudiant,enseignant'],
        ]);

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => $request->mot_de_passe,
            'role' => $request->role ?? 'etudiant',
            'filiere' => $request->filiere,
            'departement' => $request->departement,
            'competences' => $request->competences,
            'centres_interet' => $request->centres_interet,
        ]);

        

        return redirect('/register')->with('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
