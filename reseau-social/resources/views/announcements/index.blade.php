@extends('layouts.app')

@section('title', 'Annonces')

@section('content')

{{-- ══════════════════ HEADER ══════════════════ --}}
<div class="page-header fade-up">
    <div>
        <div class="page-title">📢 Annonces</div>
        <div class="page-subtitle">Toutes les annonces officielles de la faculté</div>
    </div>
    @can('create', App\Models\Announcement::class)
        <a href="{{ route('announcements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouvelle annonce
        </a>
    @endcan
</div>

{{-- ══════════════════ FILTRES ══════════════════ --}}
<div class="card fade-up delay-1" style="margin-bottom:1.5rem;padding:1rem 1.5rem;">
    <div style="display:flex;flex-wrap:wrap;gap:0.625rem;align-items:center;">
        <span style="font-size:13px;font-weight:600;color:var(--brown-600);">Filtrer :</span>
        @foreach(['Tout', 'Urgent', 'Académique', 'Administratif', 'Événement'] as $f)
            <a href="?categorie={{ strtolower($f) }}" 
               class="btn btn-ghost btn-sm {{ (request('categorie', 'tout') === strtolower($f)) ? 'btn-amber' : '' }}"
               style="border-radius:50px;">
                {{ $f }}
            </a>
        @endforeach
        <div class="search-wrapper" style="flex:1;min-width:200px;max-width:300px;margin-left:auto;">
            <i class="bi bi-search"></i>
            <input type="text" class="search-input" placeholder="Rechercher une annonce…" value="{{ request('q') }}"
                   oninput="filterAnnouncements(this.value)">
        </div>
    </div>
</div>

{{-- ══════════════════ ANNONCES ÉPINGLÉES ══════════════════ --}}
@php $pinnedAnnouncements = $announcements->where('epingle', true); @endphp
@if($pinnedAnnouncements->count())
    <div style="margin-bottom:1.75rem;">
        <div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brown-400);margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="bi bi-pin-fill" style="color:var(--amber-500);"></i> Épinglées
        </div>
        @foreach($pinnedAnnouncements as $ann)
            <div class="card fade-up" style="margin-bottom:1rem;border-left:4px solid var(--amber-400);background:linear-gradient(135deg,var(--amber-50),var(--white));">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.5rem;flex-wrap:wrap;">
                            <span class="badge badge-amber"><i class="bi bi-pin-fill"></i> Épinglée</span>
                            @if($ann->urgente ?? false)
                                <span class="badge badge-red"><i class="bi bi-exclamation-circle-fill"></i> Urgent</span>
                            @endif
                            <span class="badge badge-brown">{{ $ann->categorie ?? 'Général' }}</span>
                        </div>
                        <h3 style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--brown-950);margin-bottom:0.5rem;line-height:1.3;">
                            {{ $ann->titre }}
                        </h3>
                        <p style="font-size:14px;color:var(--brown-600);line-height:1.7;">
                            {{ \Illuminate\Support\Str::limit($ann->contenu, 200) }}
                        </p>
                        <div style="display:flex;align-items:center;gap:1rem;margin-top:0.875rem;font-size:12.5px;color:var(--brown-400);">
                            <span><i class="bi bi-person"></i> {{ $ann->user->nom ?? 'Administration' }}</span>
                            <span><i class="bi bi-clock"></i> {{ $ann->created_at->diffForHumans() }}</span>
                            @if($ann->date_expiration)
                                <span style="color:#EF4444;"><i class="bi bi-calendar-x"></i> Expire le {{ \Carbon\Carbon::parse($ann->date_expiration)->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;align-items:flex-end;">
                        <a href="{{ route('announcements.show', $ann) }}" class="btn btn-amber btn-sm">
                            <i class="bi bi-arrow-right-circle"></i> Lire
                        </a>
                        @can('update', $ann)
                            <a href="{{ route('announcements.edit', $ann) }}" class="btn btn-ghost btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ══════════════════ TOUTES LES ANNONCES ══════════════════ --}}
<div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brown-400);margin-bottom:0.75rem;">
    Toutes les annonces
</div>

<div id="announcements-list">
    @forelse($announcements->where('epingle', false) as $ann)
        <div class="card fade-up delay-2 announcement-item" style="margin-bottom:1rem;" data-title="{{ strtolower($ann->titre) }}">
            <div style="display:flex;align-items:flex-start;gap:1.25rem;">
                {{-- Date badge --}}
                <div style="flex-shrink:0;width:52px;height:52px;background:var(--brown-50);border:1.5px solid var(--brown-100);border-radius:var(--radius-lg);display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <span style="font-size:10px;font-weight:700;color:var(--brown-400);letter-spacing:.04em;">{{ strtoupper($ann->created_at->format('M')) }}</span>
                    <span style="font-size:1.3rem;font-weight:900;color:var(--brown-900);line-height:1;">{{ $ann->created_at->format('d') }}</span>
                </div>

                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.375rem;flex-wrap:wrap;">
                        @if($ann->urgente ?? false)
                            <span class="badge badge-red"><i class="bi bi-exclamation-triangle-fill"></i> Urgent</span>
                        @endif
                        <span class="badge badge-brown">{{ $ann->categorie ?? 'Général' }}</span>
                    </div>
                    <h3 style="font-family:var(--font-display);font-size:1.05rem;font-weight:700;color:var(--brown-950);margin-bottom:0.375rem;line-height:1.4;">
                        <a href="{{ route('announcements.show', $ann) }}" style="transition:color var(--transition);"
                           onmouseover="this.style.color='var(--amber-600)'" onmouseout="this.style.color=''">
                            {{ $ann->titre }}
                        </a>
                    </h3>
                    <p style="font-size:13.5px;color:var(--brown-600);line-height:1.6;">
                        {{ \Illuminate\Support\Str::limit($ann->contenu, 150) }}
                    </p>
                    <div style="display:flex;align-items:center;gap:1rem;margin-top:0.625rem;font-size:12px;color:var(--brown-400);">
                        <span><i class="bi bi-person"></i> {{ $ann->user->nom ?? 'Administration' }}</span>
                        <span><i class="bi bi-eye"></i> {{ $ann->vues ?? 0 }} vues</span>
                        <span>{{ $ann->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:0.375rem;align-items:flex-end;flex-shrink:0;">
                    <a href="{{ route('announcements.show', $ann) }}" class="btn btn-ghost btn-sm">
                        Lire <i class="bi bi-arrow-right"></i>
                    </a>
                    @can('update', $ann)
                        <a href="{{ route('announcements.edit', $ann) }}" class="icon-btn" style="width:30px;height:30px;font-size:13px;">
                            <i class="bi bi-pencil"></i>
                        </a>
                    @endcan
                    @can('delete', $ann)
                        <form action="{{ route('announcements.destroy', $ann) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn" style="width:30px;height:30px;font-size:13px;color:#EF4444;" onclick="return confirm('Supprimer cette annonce ?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="text-align:center;padding:3rem 2rem;border-style:dashed;">
            <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
            <h3 style="font-family:var(--font-display);font-size:1.3rem;color:var(--brown-900);margin-bottom:0.5rem;">
                Aucune annonce
            </h3>
            <p style="color:var(--brown-400);font-size:14px;">Il n'y a aucune annonce pour le moment.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if(method_exists($announcements, 'links') && $announcements->hasPages())
    <div class="card" style="padding:1rem 1.5rem;">
        {{ $announcements->appends(request()->query())->links() }}
    </div>
@endif

<script>
function filterAnnouncements(q) {
    const items = document.querySelectorAll('.announcement-item');
    q = q.toLowerCase();
    items.forEach(item => {
        item.style.display = item.dataset.title.includes(q) ? '' : 'none';
    });
}
</script>

@endsection

@section('sidebar')
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-title"><i class="bi bi-bar-chart-fill"></i> Statistiques</div>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach([['label'=>'Ce mois','val'=>$monthlyCount??0],['label'=>'Urgentes','val'=>$urgentCount??0],['label'=>'Total','val'=>$announcements->total()??0]] as $s)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.625rem 0;border-bottom:1px solid var(--brown-50);">
                    <span style="font-size:13.5px;color:var(--brown-600);">{{ $s['label'] }}</span>
                    <span style="font-family:var(--font-display);font-size:1.25rem;font-weight:700;color:var(--brown-900);">{{ $s['val'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-title"><i class="bi bi-funnel-fill"></i> Catégories</div>
        @foreach(['Académique','Administratif','Événement','Urgent','Autre'] as $cat)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid var(--brown-50);">
                <a href="?categorie={{ strtolower($cat) }}" style="font-size:13.5px;color:var(--brown-700);font-weight:500;transition:color var(--transition);"
                   onmouseover="this.style.color='var(--amber-600)'" onmouseout="this.style.color='var(--brown-700)'">
                    {{ $cat }}
                </a>
                <span class="badge badge-brown">{{ $categoryCounts[strtolower($cat)] ?? 0 }}</span>
            </div>
        @endforeach
    </div>
@endsection