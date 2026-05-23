@extends('layouts.app')

@section('title', 'Fil d\'actualité')

@section('content')
<div class="main-container">
    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                     SECTION HERO PREMIUM                                ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="hero-panel" style="margin-bottom: 2rem;">
        <div style="position:relative;z-index:1;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:2rem;margin-bottom:2rem;flex-wrap:wrap;">
                <div style="flex:1;min-width:280px;">
                    <span class="hero-tag" style="margin-bottom:1rem;display:inline-block;">
                        <i class="bi bi-stars"></i> Bienvenue
                    </span>
                    <h1 style="font-size:2.5rem;margin-bottom:0.5rem;color:var(--brown-950);">
                        Fil d'actualité Universitaire
                    </h1>
                    <p style="font-size:1rem;color:var(--brown-600);line-height:1.6;max-width:500px;">
                        Découvrez les dernières publications, événements et annonces de la Faculté des Sciences de Ngaoundéré.
                    </p>
                </div>
                <a href="{{ route('posts.create') }}" class="btn-primary" style="white-space:nowrap;margin-top:0.5rem;">
                    <i class="bi bi-pencil-square"></i> Partager
                </a>
            </div>

            {{-- Stats Grid Premium --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="stat-number">{{ method_exists($posts, 'total') ? $posts->total() : count($posts) }}</div>
                    <div class="stat-label">Publications</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-calendar2-event"></i>
                    </div>
                    <div class="stat-number">{{ $upcomingEvents ? $upcomingEvents->count() : 0 }}</div>
                    <div class="stat-label">Événements</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div class="stat-number">{{ $announcements ? $announcements->count() : 0 }}</div>
                    <div class="stat-label">Annonces</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                  SECTION CRÉER UNE PUBLICATION                          ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <div class="create-post-section" style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
            @php
                $me = Auth::user();
                $myAvatar = $me->avatar ?? null;
                $myInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $me->nom ?? 'U'), 0, 2)));
            @endphp

            @if($myAvatar)
                <img src="{{ $myAvatar }}" class="avatar" style="width:48px;height:48px;">
            @else
                <div class="avatar-placeholder" style="width:48px;height:48px;">
                    {{ $myInitials }}
                </div>
            @endif

            <a href="{{ route('posts.create') }}" class="create-post-input" 
               style="text-decoration:none;color:var(--brown-400);">
                À quoi penses-tu, {{ explode(' ', $me->nom ?? 'Utilisateur')[0] }} ?
            </a>
        </div>

        <div class="create-options">
            <a href="{{ route('posts.create') }}?type=image" class="option-btn">
                <i class="bi bi-image"></i> <span>Photo</span>
            </a>
            <a href="{{ route('posts.create') }}?type=video" class="option-btn">
                <i class="bi bi-camera-video"></i> <span>Vidéo</span>
            </a>
            <a href="{{ route('posts.create') }}?type=document" class="option-btn">
                <i class="bi bi-file-earmark-pdf"></i> <span>Document</span>
            </a>
            <a href="{{ route('posts.create') }}?type=poll" class="option-btn">
                <i class="bi bi-graph-up"></i> <span>Sondage</span>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                    SECTION FEED DES PUBLICATIONS                        ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom:2rem;">
        @forelse($posts as $post)
            @include('partials._post_card', ['post' => $post])
        @empty
            <div style="text-align:center;padding:3rem 2rem;background:white;border:2px dashed var(--brown-100);border-radius:var(--radius-xl);">
                <div style="font-size:4rem;margin-bottom:1rem;">📭</div>
                <h3 style="font-family:var(--font-display);font-size:1.5rem;color:var(--brown-900);margin-bottom:0.5rem;">
                    Aucune publication pour le moment
                </h3>
                <p style="color:var(--brown-600);font-size:15px;line-height:1.6;margin-bottom:1.5rem;max-width:400px;margin-left:auto;margin-right:auto;">
                    Soyez le premier à partager une publication ou à interagir avec la communauté de la Faculté des Sciences !
                </p>
                <a href="{{ route('posts.create') }}" class="btn-primary">
                    <i class="bi bi-pencil-square"></i> Créer une publication
                </a>
            </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if(method_exists($posts, 'links') && $posts->hasPages())
        <div style="background:white;border:1px solid var(--brown-100);border-radius:var(--radius-lg);padding:1.5rem 2rem;">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif

</div>

<style>
    .main-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        max-width: 100%;
    }

    @media (max-width: 768px) {
        .hero-panel {
            padding: 1.5rem !important;
        }

        .hero-panel h1 {
            font-size: 1.75rem !important;
        }

        .stat-card {
            padding: 1rem !important;
        }

        .stat-number {
            font-size: 1.5rem !important;
        }

        .post-card {
            padding: 1rem !important;
        }

        .create-post-section {
            padding: 1rem !important;
        }
    }
</style>
@endsection