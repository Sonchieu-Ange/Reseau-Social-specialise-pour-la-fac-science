<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Afficher le fil d'actualité central
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Récupérer les posts du fil d'actualité
        $posts = $this->getTimelinePosts($user, 15);

        // Récupérer les annonces principales
        // 🚨 Note : Assure-toi que ton modèle Announcement hérite bien aussi de MongoDB\Laravel\Eloquent\Model !
        $announcements = Announcement::published()
            ->take(5)
            ->get();

        // Récupérer les événements à venir
        $upcomingEvents = Event::where('status', 'approved')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        // Récupérer les statistiques de l'utilisateur
        $stats = $this->getUserStats($user);

        return view('home', compact(
            'posts',
            'announcements',
            'upcomingEvents',
            'stats'
        ));
    }

    /**
     * Obtenir le fil d'actualité pour un utilisateur
     */
    private function getTimelinePosts(User $user, int $perPage = 15)
    {
        return Post::where(function ($query) use ($user) {
            // Ses propres posts
            $query->where('user_id', $user->id);
        })
        ->orWhere(function ($query) use ($user) {
            // 🟢 Correction MongoDB : On extrait l'ID natif (_id) sans préfixe de table SQL
            $groupIds = $user->groups()->pluck('_id');
            if ($groupIds->isNotEmpty()) {
                $query->whereIn('group_id', $groupIds->toArray());
            } else {
                // Évite un bug si aucun groupe : force une condition impossible
                $query->where('group_id', 'none');
            }
        })
        ->with('user', 'comments.user')
        ->latest()
        ->paginate($perPage);
    }

    /**
     * Obtenir les statistiques de l'utilisateur
     */
    private function getUserStats(User $user): array
    {
        return [
            'total_posts' => $user->posts()->count(),
            'total_followers' => 0, 
            'total_following' => 0,
            'groups_count' => $user->groups()->count(),
            'events_count' => $user->registeredEvents()->count(),
        ];
    }

    /**
     * Afficher le tableau de bord personnalisé
     */
    public function dashboard()
    {
        $user = Auth::user();

        $data = [
            'recent_posts' => $user->posts()->latest()->take(5)->get(),
            'recent_comments' => $user->comments()->latest()->take(5)->get(),
            'my_events' => $user->registeredEvents()->where('event_date', '>=', now())->take(5)->get(),
            'my_groups' => $user->groups()->take(5)->get(),
            'unread_messages' => $user->receivedMessages()->whereNull('read_at')->count(),
        ];

        return view('home.dashboard', $data);
    }

    /**
     * Afficher le profil public de l'utilisateur connecté
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $posts = $user->posts()->latest()->paginate(15);

        return view('home.profile', compact('user', 'profile', 'posts'));
    }

    /**
     * Afficher les notifications
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = collect([]);

        return view('home.notifications', compact('notifications'));
    }

    /**
     * Afficher l'explorateur de contenu
     */
    public function explore()
    {
        // 🟢 Correction MongoDB : Utilisation de withCount() compatible NoSQL
        $trendingPosts = Post::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(20)
            ->get();

        $popularEvents = Event::where('status', 'approved')
            ->withCount('participants')
            ->orderBy('participants_count', 'desc')
            ->take(10)
            ->get();

        $popularGroups = \App\Models\Group::withCount('members')
            ->orderBy('members_count', 'desc')
            ->take(10)
            ->get();

        $activeUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('home.explore', compact(
            'trendingPosts',
            'popularEvents',
            'popularGroups',
            'activeUsers'
        ));
    }

    /**
     * Rechercher du contenu (Adapté MongoDB Regex)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'all'); 

        $results = [];

        // 🟢 Correction MongoDB : Utilisation de 'regex' au lieu de 'like' pour ignorer la casse ('i')
        if ($type === 'all' || $type === 'posts') {
            $results['posts'] = Post::where('content', 'regex', new \MongoDB\BSON\Regex($query, 'i'))
                ->with('user')
                ->limit(10)
                ->get();
        }

        if ($type === 'all' || $type === 'users') {
            $results['users'] = User::where('name', 'regex', new \MongoDB\BSON\Regex($query, 'i'))
                ->orWhere('email', 'regex', new \MongoDB\BSON\Regex($query, 'i'))
                ->limit(10)
                ->get();
        }

        if ($type === 'all' || $type === 'groups') {
            $results['groups'] = \App\Models\Group::where('name', 'regex', new \MongoDB\BSON\Regex($query, 'i'))
                ->orWhere('description', 'regex', new \MongoDB\BSON\Regex($query, 'i'))
                ->with('president')
                ->limit(10)
                ->get();
        }

        if ($type === 'all' || $type === 'events') {
            $results['events'] = Event::where('status', 'approved')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'regex', new \MongoDB\BSON\Regex($query, 'i'))
                      ->orWhere('description', 'regex', new \MongoDB\BSON\Regex($query, 'i'));
                })
                ->with('organizer')
                ->limit(10)
                ->get();
        }

        return view('home.search', compact('results', 'query', 'type'));
    }

    /**
     * Afficher les statistiques globales du réseau
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'total_events' => Event::where('status', 'approved')->count(),
            'total_groups' => \App\Models\Group::count(),
            'total_comments' => \App\Models\Comment::count(),
            'active_users_today' => User::where('updated_at', '>=', now()->subDay())->count(),
            'new_users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('home.statistics', compact('stats'));
    }

    public function about() { return view('home.about'); }
    public function terms() { return view('home.terms'); }
    public function privacy() { return view('home.privacy'); }
    public function help() { return view('home.help'); }
}