<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traiter la demande de connexion
    public function login(Request $request)
    {
        // Validation basique des entrées
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative d'authentification de l'utilisateur
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Sécurise la session contre la fixation de session

            return redirect()->intended('/'); // Redirige vers le fil d'actualité
        }

        // Si la connexion échoue, on revient en arrière avec une erreur
        return back()->withErrors([
            'login_error' => 'Identifiants académiques incorrects ou compte introuvable.',
        ])->onlyInput('email');
    }

    // Afficher le formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traiter la demande d'inscription
    public function register(Request $request)
    {
        // Validation stricte incluant la restriction sur le domaine de la faculté
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@univ-ngaoundere\.cm$/' // Sécurité nom de domaine
            ],
            'role' => 'required|in:student,teacher',
            'department' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'email.regex' => 'Vous devez utiliser une adresse email officielle de l\'Université de Ngaoundéré.',
            'email.unique' => 'Cette adresse email est déjà enregistrée sur Phoenix.'
        ]);

        // 1. Création de l'utilisateur dans la collection MongoDB 'users'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), // Hachage Bcrypt obligatoire
        ]);

        // 2. Création automatique de son profil lié dans la collection 'profiles'
        Profile::create([
            'user_id' => $user->id, // Liaison NoSQL
            'department' => $request->department,
            'bio' => 'Nouvel utilisateur de la Faculté des Sciences.',
        ]);

        // 3. Connexion immédiate de l'étudiant après son inscription
        Auth::login($user);

        return redirect()->route('home');
    }

    // Traiter la déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Nettoyage complet du token CSRF

        return redirect()->route('login');
    }
}