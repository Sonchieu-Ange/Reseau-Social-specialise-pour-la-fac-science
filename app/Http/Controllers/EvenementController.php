<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\EvenementParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $evenements = Evenement::with('createur')->orderBy('date_debut')->paginate(10);

        return view('evenements.index', compact('evenements'));
    }

    public function create()
    {
        return view('evenements.create');
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

        Evenement::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'lieu' => $request->lieu,
            'createur_id' => Auth::id(),
        ]);

        return redirect()->route('evenements.index')->with('success', 'Événement créé.');
    }

    public function show($id)
    {
        $evenement = Evenement::with(['createur', 'participants.utilisateur'])->findOrFail($id);

        return view('evenements.show', compact('evenement'));
    }

    public function edit($id)
    {
        $evenement = Evenement::findOrFail($id);
        if ($evenement->createur_id != Auth::id()) {
            abort(403);
        }

        return view('evenements.edit', compact('evenement'));
    }

    public function update(Request $request, $id)
    {
        $evenement = Evenement::findOrFail($id);
        if ($evenement->createur_id != Auth::id()) {
            abort(403);
        }
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:200',
        ]);
        $evenement->update($request->only(['titre', 'description', 'date_debut', 'date_fin', 'lieu']));

        return redirect()->route('evenements.show', $id)->with('success', 'Événement mis à jour.');
    }

    public function destroy($id)
    {
        $evenement = Evenement::findOrFail($id);
        if ($evenement->createur_id != Auth::id()) {
            abort(403);
        }
        $evenement->delete();

        return redirect()->route('evenements.index')->with('success', 'Événement supprimé.');
    }

    public function participate($id)
    {
        $existe = EvenementParticipant::where('evenement_id', $id)
            ->where('utilisateur_id', Auth::id())->exists();
        if ($existe) {
            return back()->with('error', 'Déjà inscrit.');
        }
        EvenementParticipant::create([
            'evenement_id' => $id,
            'utilisateur_id' => Auth::id(),
        ]);

        return back()->with('success', 'Inscription confirmée.');
    }

    public function cancelParticipation($id)
    {
        EvenementParticipant::where('evenement_id', $id)
            ->where('utilisateur_id', Auth::id())->delete();

        return back()->with('success', 'Inscription annulée.');
    }
}
