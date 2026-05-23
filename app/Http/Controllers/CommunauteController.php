<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Controller;
use App\Models\Communaute;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunauteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $communautes = Communaute::with('createur')->paginate(10);
        return view('communautes.index', compact('communautes'));
    }

    public function create()
    {
        return view('communautes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        Communaute::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'createur_id' => Auth::id(),
        ]);

        return redirect()->route('communautes.index');
    }

    public function show($id)
    {
        $communaute = Communaute::with(['createur', 'groupes'])->findOrFail($id);
        // Récupérer les groupes qui ne sont pas déjà associés à cette communauté
        $groupesDisponibles = Groupe::where('communaute_id', '!=', $id)
            ->orWhereNull('communaute_id')
            ->get();
        return view('communautes.show', compact('communaute', 'groupesDisponibles'));
    }

    public function edit($id)
    {
        $communaute = Communaute::findOrFail($id);
        if ($communaute->createur_id != Auth::id()) abort(403);
        return view('communautes.edit', compact('communaute'));
    }

    public function update(Request $request, $id)
    {
        $communaute = Communaute::findOrFail($id);
        if ($communaute->createur_id != Auth::id()) abort(403);
        $request->validate([
            'nom' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);
        $communaute->update($request->only(['nom', 'description']));
        return redirect()->route('communautes.show', $id);
    }

    public function destroy($id)
    {
        $communaute = Communaute::findOrFail($id);
        if ($communaute->createur_id != Auth::id()) abort(403);
        $communaute->delete();
        return redirect()->route('communautes.index');
    }

   
    public function associerGroupe(Request $request, $id)
    {
        $communaute = Communaute::findOrFail($id);
        
        if ($communaute->createur_id != Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'groupe_id' => 'required|exists:groupes,_id',
        ]);
        
        $groupe = Groupe::findOrFail($request->groupe_id);
        $groupe->update(['communaute_id' => $id]);
        
        return back();
    }


    public function desassocierGroupe($communauteId, $groupeId)
    {
        $communaute = Communaute::findOrFail($communauteId);
        
        if ($communaute->createur_id != Auth::id()) {
            abort(403);
        }
        
        $groupe = Groupe::where('_id', $groupeId)
            ->where('communaute_id', $communauteId)
            ->firstOrFail();
        
        $groupe->update(['communaute_id' => null]);
        
        return back();
    }
}