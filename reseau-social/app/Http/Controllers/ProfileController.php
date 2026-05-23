<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Afficher le profil public d'un utilisateur avec ses publications
     */
    public function show($userId)
    {
        // 🚀 Optimisation MongoDB : on charge l'utilisateur ET ses posts en une seule fois (Eager Loading)
        $user = User::with('posts')->findOrFail($userId);
        
        // On récupère le profil associé
        $profile = $user->profile;

        return view('profiles.show', compact('user', 'profile'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        return view('profiles.edit', compact('user', 'profile'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('profile_picture')) {
            // Stocker l'image dans le storage public
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        // updateOrCreate gère magnifiquement l'absence initiale de profil dans MongoDB
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Obtenir les informations basiques du profil en JSON (Utile pour l'API ou du JS)
     */
    public function getProfileData($userId)
    {
        $user = User::with('posts')->findOrFail($userId);
        $profile = $user->profile;

        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'profile' => $profile,
        ]);
    }
}