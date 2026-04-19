<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function indexConversations()
    {
        $userId = Auth::id();
        // Récupérer les IDs des utilisateurs avec qui on a échangé
        $sentTo = Message::where('expediteur_id', $userId)->distinct('destinataire_id')->pluck('destinataire_id');
        $receivedFrom = Message::where('destinataire_id', $userId)->distinct('expediteur_id')->pluck('expediteur_id');
        $contactIds = $sentTo->merge($receivedFrom)->unique();

        $contacts = Utilisateur::whereIn('_id', $contactIds)->get();
        return response()->json($contacts);
    }

    public function conversation($userId)
    {
        $currentUserId = Auth::id();
        $messages = Message::where(function($query) use ($currentUserId, $userId) {
            $query->where('expediteur_id', $currentUserId)
                  ->where('destinataire_id', $userId);
        })->orWhere(function($query) use ($currentUserId, $userId) {
            $query->where('expediteur_id', $userId)
                  ->where('destinataire_id', $currentUserId);
        })->orderBy('date_envoi')->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:utilisateurs,_id',
            'contenu' => 'required|string',
        ]);

        $message = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $request->destinataire_id,
            'contenu' => $request->contenu,
        ]);

        return response()->json($message, 201);
    }
}