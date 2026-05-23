<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();

        $allMessages = Message::where('expediteur_id', $userId)
            ->orWhere('destinataire_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $contactIds = $allMessages->map(function ($message) use ($userId) {
            return $message->expediteur_id == $userId
                ? $message->destinataire_id
                : $message->expediteur_id;
        })->unique()->filter()->values();

        $contacts = collect();

        if ($contactIds->isNotEmpty()) {
            $contacts = Utilisateur::whereIn('_id', $contactIds->toArray())->get();

            $contacts = $contacts->map(function ($contact) use ($userId, $allMessages) {
                $dernierMessage = $allMessages->first(function ($message) use ($contact, $userId) {
                    return ($message->expediteur_id == $userId && $message->destinataire_id == $contact->_id) ||
                           ($message->expediteur_id == $contact->_id && $message->destinataire_id == $userId);
                });

                $contact->dernier_message = $dernierMessage;

                return $contact;
            });
        }

        return view('messages.index', compact('contacts'));
    }
    public function conversation($userId)
    {
        $currentUserId = Auth::id();
        $messages = Message::where(function ($q) use ($currentUserId, $userId) {
            $q->where('expediteur_id', $currentUserId)
                ->where('destinataire_id', $userId);
        })->orWhere(function ($q) use ($currentUserId, $userId) {
            $q->where('expediteur_id', $userId)
                ->where('destinataire_id', $currentUserId);
        })->orderBy('date_envoi')->get();

        $contact = Utilisateur::findOrFail($userId);

        return view('messages.conversation', compact('messages', 'contact'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:utilisateurs,_id',
            'contenu' => 'required|string',
        ]);

        Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $request->destinataire_id,
            'contenu' => $request->contenu,
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        $message = Message::findOrFail($id);

        if ($message->expediteur_id != Auth::id()) {
            abort(403);
        }

        $message->update(['contenu' => $request->contenu]);

        return back();
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->expediteur_id != Auth::id()) {
            abort(403);
        }

        $message->delete();

        return back();
    }
}
