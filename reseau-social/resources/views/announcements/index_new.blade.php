@extends('layouts.app')

@section('title', 'Annonces')

@section('content')
<div style="display:flex;flex-direction:column;gap:1.75rem;">

    {{-- Hero Banner --}}
    <div class="hero-panel">
        <div style="position:relative;z-index:2;">
            <span class="hero-tag"><i class="bi bi-megaphone-fill"></i> Information officielle</span>
            <h1 style="font-size:2.25rem;margin:0.5rem 0 0;color:var(--white);">Annonces officielles</h1>
            <p style="margin:0.75rem 0 0;opacity:0.85;max-width:480px;color:rgba(255,255,255,.85);font-size:14px;line-height:1.6;">Restez informé des décisions clés, des messages importants et des actualités de la vie universitaire de la Faculté des Sciences.</p>
        </div>
    </div>

    {{-- Announcements List --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">
        @forelse($announcements as $announcement)
            <div class="card" style="padding:1.75rem;border:1px solid var(--brown-100);transition:var(--transition);overflow:hidden;position:relative;">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    {{-- Header with date --}}
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                        <div style="flex:1;min-width:0;">
                            <h2 style="font-family:var(--font-display);font-size:1.25rem;font-weight:700;color:var(--brown-900);margin:0;line-height:1.3;">
                                {{ $announcement->title }}
                            </h2>
                            <div style="display:flex;align-items:center;gap:0.5rem;margin-top:0.75rem;flex-wrap:wrap;">
                                <span style="display:inline-flex;align-items:center;gap:0.35rem;font-size:13px;color:var(--brown-500);">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ $announcement->published_at?->format('d M Y') ?? 'Date non définie' }}
                                </span>
                                <span style="display:inline-block;width:4px;height:4px;background:var(--brown-300);border-radius:50%;"></span>
                                <span style="font-size:13px;color:var(--brown-500);">
                                    <i class="bi bi-clock"></i> 
                                    {{ $announcement->published_at?->diffForHumans() ?? 'Récemment' }}
                                </span>
                            </div>
                        </div>
                        <span style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.85rem;background:var(--amber-100);color:var(--brown-700);font-size:12px;font-weight:600;border-radius:var(--radius-xl);white-space:nowrap;">
                            <i class="bi bi-star-fill"></i> Important
                        </span>
                    </div>

                    {{-- Content preview --}}
                    <p style="color:var(--brown-700);line-height:1.7;margin:0;font-size:14px;">
                        {{ $announcement->content ?? 'Aucun contenu disponible.' }}
                    </p>

                    {{-- Action buttons --}}
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--brown-50);">
                        <a href="{{ route('announcements.show', $announcement->id) }}" class="btn-primary" style="font-size:12px;padding:0.5rem 1rem;">
                            <i class="bi bi-arrow-right"></i> Lire la suite
                        </a>
                        <button style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.45rem 0.85rem;border-radius:var(--radius-md);font-size:13px;color:var(--brown-500);background:transparent;border:none;cursor:pointer;transition:var(--transition);" onmouseover="this.style.background='var(--brown-50)';this.style.color='var(--brown-700)';" onmouseout="this.style.background='transparent';this.style.color='var(--brown-500)';">
                            <i class="bi bi-bookmark"></i> Marquer
                        </button>
                    </div>
                </div>

                {{-- Left accent bar --}}
                <div style="position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,var(--amber-500),var(--amber-400));"></div>
            </div>
        @empty
            <div style="text-align:center;padding:3rem 1.5rem;background:var(--white);border:2px dashed var(--brown-100);border-radius:var(--radius-lg);">
                <div style="font-size:4.5rem;margin-bottom:1rem;">📢</div>
                <h3 style="font-family:var(--font-display);font-size:1.35rem;color:var(--brown-700);margin:0 0 0.5rem;">Aucune annonce pour le moment</h3>
                <p style="color:var(--brown-400);font-size:13.5px;margin:0;line-height:1.6;max-width:320px;margin-left:auto;margin-right:auto;">
                    Les annonces officielles relatives à la Faculté des Sciences apparaîtront ici.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(method_exists($announcements, 'links') && $announcements->hasPages())
        <div style="background:var(--white);border:1px solid var(--brown-100);border-radius:var(--radius-lg);padding:1rem 1.25rem;display:flex;justify-content:center;">
            {{ $announcements->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
