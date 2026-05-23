<?php

namespace App\Http\Controllers;


use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}

    public function index()
    {
        $annonces = Annonce::with('createur')->orderBy('date_publication', 'desc')->paginate(10);
        return view('annonces.index', compact('annonces'));
    }

    public function create()
    {
        return view('annonces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string',
        ]);

        Annonce::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'createur_id' => Auth::id(),
        ]);

        return redirect()->route('annonces.index');
    }

    public function show($id)
    {
        $annonce = Annonce::with('createur')->findOrFail($id);
        return view('annonces.show', compact('annonce'));
    }

    public function edit($id)
    {
        $annonce = Annonce::findOrFail($id);
        if ($annonce->createur_id != Auth::id()) abort(403);
        return view('annonces.edit', compact('annonce'));
    }

    public function update(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        if ($annonce->createur_id != Auth::id()) abort(403);
        $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string',
        ]);
        $annonce->update($request->only(['titre', 'contenu']));
        return redirect()->route('annonces.show', $id);
    }

    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        if ($annonce->createur_id != Auth::id()) abort(403);
        $annonce->delete();
        return redirect()->route('annonces.index');
    }
}