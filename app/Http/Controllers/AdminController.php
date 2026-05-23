<?php


namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Utilisateur;
use App\Models\Publication;
use App\Models\Groupe;
use App\Models\Evenement;
use App\Models\Annonce;
use App\Models\Communaute;
use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(AdminMiddleware::class);
    }

    public function dashboard()
    {
        $stats = [
            'utilisateurs' => Utilisateur::count(),
            'publications' => Publication::count(),
            'groupes' => Groupe::count(),
            'evenements' => Evenement::count(),
            'annonces' => Annonce::count(),
            'communautes' => Communaute::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = Utilisateur::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => "required|email|max:150|unique:utilisateurs,email,{$id},_id",
            'role' => 'in:etudiant,enseignant,admin',
        ]);
        $user->update($request->only(['nom', 'prenom', 'email', 'role', 'filiere', 'departement', 'competences', 'centres_interet']));
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié.');
    }

    public function destroyUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}