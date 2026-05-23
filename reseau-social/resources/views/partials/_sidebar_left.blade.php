<aside id="sidebar-left">
    {{-- Profile mini-card --}}
    @auth
    <a href="{{ route('profile.show', Auth::user()->_id) }}"
       style="display:flex;align-items:center;gap:.75rem;padding:.75rem;border-radius:var(--radius-md);background:var(--brown-50);margin-bottom:.75rem;text-decoration:none;transition:var(--transition);"
       onmouseover="this.style.background='var(--amber-100)'" onmouseout="this.style.background='var(--brown-50)'">
        <img
            src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nom).'&background=6B2D08&color=FCD34D&size=80' }}"
            alt="{{ Auth::user()->nom }}"
            class="avatar"
            style="width:40px;height:40px;border:2px solid var(--amber-300);">
        <div style="min-width:0;">
            <div style="font-size:13.5px;font-weight:600;color:var(--brown-900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->nom }}</div>
            <div style="font-size:11px;color:var(--brown-400);">
                @if(Auth::user()->role === 'etudiant') <i class="bi bi-mortarboard"></i> Étudiant
                @elseif(Auth::user()->role === 'enseignant') <i class="bi bi-person-workspace"></i> Enseignant
                @else <i class="bi bi-shield-check"></i> Admin @endif
            </div>
        </div>
    </a>
    @endauth

    {{-- Navigation principale --}}
    <span class="sidebar-section-label">Navigation</span>

    <a href="{{ route('home') }}" class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }}"></i>
        Fil d'actualité
    </a>

    <a href="{{ route('groups.index') }}" class="sidebar-item {{ request()->routeIs('groups.*') ? 'active' : '' }}">
        <i class="bi bi-people{{ request()->routeIs('groups.*') ? '-fill' : '' }}"></i>
        Groupes &amp; Clubs
    </a>

    <a href="{{ route('events.index') }}" class="sidebar-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-event{{ request()->routeIs('events.*') ? '-fill' : '' }}"></i>
        Événements
    </a>

    <a href="{{ route('announcements.index') }}" class="sidebar-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
        <i class="bi bi-megaphone{{ request()->routeIs('announcements.*') ? '-fill' : '' }}"></i>
        Annonces
    </a>

    <a href="{{ route('messages.index') }}" class="sidebar-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
        <i class="bi bi-chat-dots{{ request()->routeIs('messages.*') ? '-fill' : '' }}"></i>
        Messages
        {{-- @if($unreadMessages > 0) <span class="s-badge">{{ $unreadMessages }}</span> @endif --}}
    </a>

    {{-- Section perso --}}
    <span class="sidebar-section-label">Mon Espace</span>

    @auth
    <a href="{{ route('profile.show', Auth::user()->_id) }}" class="sidebar-item {{ request()->routeIs('profile.show', Auth::user()->_id ?? '') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        Mon Profil
    </a>
    @endauth

    <a href="{{ route('posts.create') }}" class="sidebar-item">
        <i class="bi bi-pencil-square"></i>
        Nouvelle Publication
    </a>

    {{-- Admin only --}}
    @auth
    @if(Auth::user()->role === 'admin')
    <span class="sidebar-section-label">Administration</span>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        Tableau de bord
    </a>
    <a href="{{ route('admin.reports') }}" class="sidebar-item">
        <i class="bi bi-flag"></i>
        Signalements
    </a>
    @endif
    @endauth

    {{-- Footer sidebar --}}
    <div style="margin-top:auto;padding-top:1rem;border-top:1px solid var(--brown-100);">
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item w-100" style="background:none;border:none;text-align:left;cursor:pointer;color:var(--brown-500);">
                <i class="bi bi-box-arrow-right"></i>
                Déconnexion
            </button>
        </form>
        @endauth
    </div>
</aside>