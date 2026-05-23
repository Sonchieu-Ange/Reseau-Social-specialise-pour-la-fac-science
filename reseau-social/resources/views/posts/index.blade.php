@extends('layouts.app')

@section('title', "Fil d'actualité")

@section('content')

{{-- ══════════════════ HERO PANEL ══════════════════ --}}
<div class="hero-panel fade-up" style="margin-bottom:1.75rem;">
    <div style="position:relative;z-index:1;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1.5rem;margin-bottom:2rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
                <span class="hero-tag" style="margin-bottom:0.875rem;display:inline-flex;">
                    <i class="bi bi-stars"></i>&nbsp;Bienvenue sur FacSci NG
                </span>
                <h1 style="font-family:var(--font-display);font-size:2.2rem;font-weight:900;color:var(--white);margin-bottom:0.5rem;line-height:1.15;letter-spacing:-.03em;">
                    Fil d'actualité<br>
                    <span style="color:var(--amber-300);">Universitaire</span>
                </h1>
                <p style="font-size:14px;color:rgba(255,255,255,.65);line-height:1.6;max-width:420px;">
                    Découvrez les dernières publications, événements et annonces de la Faculté des Sciences de Ngaoundéré.
                </p>
            </div>
            <a href="{{ route('posts.create') }}" class="btn btn-amber" style="align-self:flex-start;">
                <i class="bi bi-pencil-square"></i> Partager
            </a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.875rem;">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-file-text-fill"></i></div>
                <div class="stat-number">{{ method_exists($posts, 'total') ? $posts->total() : count($posts) }}</div>
                <div class="stat-label">Publications</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-calendar2-event-fill"></i></div>
                <div class="stat-number">{{ $upcomingEvents?->count() ?? 0 }}</div>
                <div class="stat-label">Événements</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-megaphone-fill"></i></div>
                <div class="stat-number">{{ $announcements?->count() ?? 0 }}</div>
                <div class="stat-label">Annonces</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div class="stat-number">{{ $totalUsers ?? '—' }}</div>
                <div class="stat-label">Membres</div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════ CRÉER UNE PUBLICATION ══════════════════ --}}
<div class="create-post-section fade-up delay-1" style="margin-bottom:1.5rem;">
    @php
        $me = Auth::user();
        $myAvatar = $me->avatar ?? null;
        $myInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $me->nom ?? 'U'), 0, 2)));
        $firstName = explode(' ', $me->nom ?? 'Utilisateur')[0];
    @endphp

    <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1rem;">
        @if($myAvatar)
            <img src="{{ $myAvatar }}" class="avatar" style="width:46px;height:46px;">
        @else
            <div class="avatar-placeholder" style="width:46px;height:46px;font-size:14px;">{{ $myInitials }}</div>
        @endif
        <a href="{{ route('posts.create') }}" class="create-post-input">
            À quoi penses-tu, {{ $firstName }} ?
        </a>
    </div>

    <div class="create-options">
        <a href="{{ route('posts.create') }}?type=image" class="option-btn">
            <i class="bi bi-image" style="color:#F59E0B;"></i> Photo
        </a>
        <a href="{{ route('posts.create') }}?type=video" class="option-btn">
            <i class="bi bi-camera-video-fill" style="color:#8B5CF6;"></i> Vidéo
        </a>
        <a href="{{ route('posts.create') }}?type=document" class="option-btn">
            <i class="bi bi-file-earmark-pdf-fill" style="color:#EF4444;"></i> Document
        </a>
        <a href="{{ route('posts.create') }}?type=poll" class="option-btn">
            <i class="bi bi-bar-chart-fill" style="color:#10B981;"></i> Sondage
        </a>
        <a href="{{ route('posts.create') }}?type=event" class="option-btn">
            <i class="bi bi-calendar-plus-fill" style="color:#3B82F6;"></i> Événement
        </a>
    </div>
</div>

{{-- ══════════════════ FEED DES PUBLICATIONS ══════════════════ --}}
@forelse($posts as $post)
    @php
        $author = $post->user;
        $authorAvatar = $author->avatar ?? null;
        $authorInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $author->nom ?? 'U'), 0, 2)));
    @endphp

    <div class="post-card fade-up delay-2" style="margin-bottom:1.25rem;">
        <div class="post-header">
            <div class="post-author-info">
                @if($authorAvatar)
                    <img src="{{ $authorAvatar }}" class="avatar" style="width:44px;height:44px;">
                @else
                    <div class="avatar-placeholder" style="width:44px;height:44px;font-size:13px;">{{ $authorInitials }}</div>
                @endif
                <div>
                    <div style="font-weight:700;color:var(--brown-950);font-size:14.5px;">{{ $author->nom }}</div>
                    <div class="post-meta">
                        <i class="bi bi-clock" style="font-size:11px;"></i>
                        {{ $post->created_at->diffForHumans() }}
                        @if($post->type ?? false)
                            <span class="badge badge-amber" style="font-size:10px;padding:.15rem .5rem;">{{ ucfirst($post->type) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:0.25rem;">
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}" class="icon-btn" style="width:32px;height:32px;font-size:14px;" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn" style="width:32px;height:32px;font-size:14px;color:var(--brown-400);" title="Supprimer"
                                onclick="return confirm('Supprimer cette publication ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endcan
                <button class="icon-btn" style="width:32px;height:32px;font-size:14px;" title="Options">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
        </div>

        <div class="post-content">
            {!! nl2br(e(\Illuminate\Support\Str::limit($post->contenu, 400))) !!}
            @if(strlen($post->contenu) > 400)
                <a href="{{ route('posts.show', $post) }}" style="color:var(--amber-600);font-weight:600;font-size:13.5px;"> Lire plus</a>
            @endif

            @if($post->image)
                <img src="{{ $post->image }}" class="post-image">
            @endif
        </div>

        {{-- Réactions --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--brown-50);">
            <div style="display:flex;align-items:center;gap:0.25rem;">
                <div style="display:flex;gap:2px;">
                    <span style="width:22px;height:22px;border-radius:50%;background:var(--amber-100);display:flex;align-items:center;justify-content:center;font-size:11px;">👍</span>
                    <span style="width:22px;height:22px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;font-size:11px;">❤️</span>
                </div>
                <span style="font-size:13px;color:var(--brown-400);margin-left:4px;">{{ $post->likes_count ?? rand(2,48) }}</span>
            </div>
            <span style="font-size:13px;color:var(--brown-400);">{{ $post->comments_count ?? rand(0,12) }} commentaires</span>
        </div>

        <div class="post-actions">
            <button class="post-action-btn" onclick="toggleLike(this)">
                <i class="bi bi-hand-thumbs-up"></i> J'aime
            </button>
            <a href="{{ route('posts.show', $post) }}" class="post-action-btn">
                <i class="bi bi-chat"></i> Commenter
            </a>
            <button class="post-action-btn">
                <i class="bi bi-share"></i> Partager
            </button>
        </div>
    </div>
@empty
    <div class="card fade-up" style="text-align:center;padding:3rem 2rem;border-style:dashed;border-color:var(--brown-100);">
        <div style="font-size:3.5rem;margin-bottom:1rem;">📭</div>
        <h3 style="font-family:var(--font-display);font-size:1.4rem;color:var(--brown-900);margin-bottom:0.5rem;">
            Aucune publication pour le moment
        </h3>
        <p style="color:var(--brown-400);font-size:14px;line-height:1.7;margin-bottom:1.5rem;max-width:380px;margin-inline:auto;">
            Soyez le premier à partager quelque chose avec la communauté de la Faculté des Sciences !
        </p>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Créer une publication
        </a>
    </div>
@endforelse

{{-- ── Pagination ── --}}
@if(method_exists($posts, 'links') && $posts->hasPages())
    <div class="card" style="padding:1rem 1.5rem;">
        {{ $posts->appends(request()->query())->links() }}
    </div>
@endif

<script>
function toggleLike(btn) {
    btn.classList.toggle('liked');
    if (btn.classList.contains('liked')) {
        btn.style.color = 'var(--amber-600)';
        btn.style.background = 'var(--amber-50)';
    } else {
        btn.style.color = '';
        btn.style.background = '';
    }
}
</script>
@endsection

@section('sidebar')
    {{-- Événements à venir --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-title"><i class="bi bi-calendar2-event-fill"></i> Événements à venir</div>
        @forelse($upcomingEvents ?? [] as $event)
            <div style="display:flex;gap:0.875rem;align-items:flex-start;padding:0.625rem 0;border-bottom:1px solid var(--brown-50);">
                <div style="min-width:42px;height:42px;background:var(--amber-100);border-radius:var(--radius-md);display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:11px;font-weight:700;color:var(--amber-600);letter-spacing:.04em;">{{ strtoupper(\Carbon\Carbon::parse($event->date_debut)->format('M')) }}</span>
                    <span style="font-size:16px;font-weight:900;color:var(--brown-900);line-height:1;">{{ \Carbon\Carbon::parse($event->date_debut)->format('d') }}</span>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13.5px;font-weight:600;color:var(--brown-900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $event->titre }}</div>
                    <div style="font-size:12px;color:var(--brown-400);margin-top:1px;">{{ $event->lieu ?? 'FacSci' }}</div>
                </div>
            </div>
        @empty
            <p style="font-size:13px;color:var(--brown-400);text-align:center;padding:1rem 0;">Aucun événement à venir</p>
        @endforelse
        <a href="{{ route('events.index') }}" style="display:block;text-align:center;font-size:13px;color:var(--amber-600);font-weight:600;margin-top:0.75rem;">
            Voir tous les événements →
        </a>
    </div>

    {{-- Annonces récentes --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-title"><i class="bi bi-megaphone-fill"></i> Annonces</div>
        @forelse($announcements ?? [] as $ann)
            <div style="padding:0.625rem 0;border-bottom:1px solid var(--brown-50);">
                <div style="font-size:13.5px;font-weight:600;color:var(--brown-900);">{{ \Illuminate\Support\Str::limit($ann->titre, 50) }}</div>
                <div style="font-size:12px;color:var(--brown-400);margin-top:2px;">{{ $ann->created_at->diffForHumans() }}</div>
            </div>
        @empty
            <p style="font-size:13px;color:var(--brown-400);text-align:center;padding:1rem 0;">Aucune annonce récente</p>
        @endforelse
        <a href="{{ route('announcements.index') }}" style="display:block;text-align:center;font-size:13px;color:var(--amber-600);font-weight:600;margin-top:0.75rem;">
            Toutes les annonces →
        </a>
    </div>

    {{-- Groupes suggérés --}}
    <div class="card">
        <div class="card-title"><i class="bi bi-people-fill"></i> Groupes suggérés</div>
        @forelse($suggestedGroups ?? [] as $group)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid var(--brown-50);">
                <div style="width:36px;height:36px;border-radius:var(--radius-md);background:linear-gradient(135deg,var(--brown-600),var(--brown-400));display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($group->nom, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13.5px;font-weight:600;color:var(--brown-900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $group->nom }}</div>
                    <div style="font-size:11.5px;color:var(--brown-400);">{{ $group->membres_count ?? 0 }} membres</div>
                </div>
                <a href="{{ route('groups.show', $group) }}" class="btn btn-ghost btn-xs">Rejoindre</a>
            </div>
        @empty
            <p style="font-size:13px;color:var(--brown-400);text-align:center;padding:1rem 0;">Aucun groupe suggéré</p>
        @endforelse
    </div>
@endsection