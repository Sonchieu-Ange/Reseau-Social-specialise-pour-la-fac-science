@extends('layouts.app')

@section('title', 'Groupes')

@section('content')

{{-- ══════════════════ HEADER ══════════════════ --}}
<div class="page-header fade-up">
    <div>
        <div class="page-title">👥 Groupes</div>
        <div class="page-subtitle">Communautés et cercles d'étude de la faculté</div>
    </div>
    <a href="{{ route('groups.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Créer un groupe
    </a>
</div>

{{-- ══════════════════ MES GROUPES ══════════════════ --}}
@if(isset($myGroups) && $myGroups->count())
    <div style="margin-bottom:2rem;">
        <div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brown-400);margin-bottom:0.875rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="bi bi-person-check-fill" style="color:var(--amber-500);"></i> Mes groupes
        </div>
        <div style="display:flex;gap:0.875rem;overflow-x:auto;padding-bottom:0.5rem;scrollbar-width:thin;">
            @foreach($myGroups as $group)
                @php
                    $colors = ['#8B4513','#A0522D','#6B3100','#D97706','#4A2000'];
                    $color  = $colors[$group->id % count($colors)];
                @endphp
                <a href="{{ route('groups.show', $group) }}" style="flex-shrink:0;width:140px;text-decoration:none;">
                    <div style="background:linear-gradient(135deg,{{ $color }},{{ $color }}88);border-radius:var(--radius-xl);padding:1.25rem 1rem;text-align:center;margin-bottom:0.5rem;position:relative;overflow:hidden;height:90px;display:flex;align-items:center;justify-content:center;">
                        @if($group->image)
                            <img src="{{ $group->image }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:var(--radius-xl);">
                        @endif
                        <span style="position:relative;z-index:1;font-size:2rem;font-weight:900;color:rgba(255,255,255,.9);">{{ strtoupper(substr($group->nom,0,2)) }}</span>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:var(--brown-900);text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $group->nom }}</div>
                    <div style="font-size:11.5px;color:var(--brown-400);text-align:center;">{{ $group->membres_count ?? 0 }} membres</div>
                </a>
            @endforeach
        </div>
    </div>
@endif

{{-- ══════════════════ FILTRES ══════════════════ --}}
<div class="card fade-up delay-1" style="margin-bottom:1.5rem;padding:1rem 1.5rem;">
    <div style="display:flex;align-items:center;flex-wrap:wrap;gap:0.625rem;">
        <div class="search-wrapper" style="flex:1;min-width:200px;max-width:340px;">
            <i class="bi bi-search"></i>
            <input type="text" class="search-input" placeholder="Rechercher un groupe…" oninput="filterGroups(this.value)">
        </div>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-left:auto;">
            @foreach(['Tous','Académique','Projet','Entraide','Loisir'] as $cat)
                <a href="?categorie={{ strtolower($cat) }}"
                   class="btn btn-ghost btn-sm {{ request('categorie','tous')===strtolower($cat)?'btn-amber':'' }}"
                   style="border-radius:50px;">{{ $cat }}</a>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════ GRILLE DES GROUPES ══════════════════ --}}
<div id="groups-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.25rem;">
    @forelse($groups as $group)
        @php
            $colors = [
                'académique' => ['from'=>'#2D1400','to'=>'#8B4513'],
                'projet'     => ['from'=>'#1E3A5F','to'=>'#3B82F6'],
                'entraide'   => ['from'=>'#064E3B','to'=>'#10B981'],
                'loisir'     => ['from'=>'#4C1D95','to'=>'#8B5CF6'],
            ];
            $cat  = strtolower($group->categorie ?? 'académique');
            $grad = $colors[$cat] ?? ['from'=>'#4A2000','to'=>'#A0522D'];
            $isMember = $group->isMember(auth()->user());
        @endphp

        <div class="card fade-up delay-2 group-card" style="padding:0;overflow:hidden;display:flex;flex-direction:column;" data-name="{{ strtolower($group->nom) }}">
            {{-- Cover --}}
            <div style="height:90px;background:linear-gradient(135deg,{{ $grad['from'] }},{{ $grad['to'] }});position:relative;overflow:hidden;">
                @if($group->cover)
                    <img src="{{ $group->cover }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                @endif
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                    <span style="font-size:2.5rem;font-weight:900;color:rgba(255,255,255,.15);font-family:var(--font-display);">{{ strtoupper(substr($group->nom,0,2)) }}</span>
                </div>
                @if($group->prive ?? false)
                    <div style="position:absolute;top:0.625rem;right:0.625rem;">
                        <span class="badge" style="background:rgba(0,0,0,.4);color:rgba(255,255,255,.9);backdrop-filter:blur(4px);">
                            <i class="bi bi-lock-fill"></i> Privé
                        </span>
                    </div>
                @endif
                {{-- Avatar --}}
                <div style="position:absolute;bottom:-22px;left:1.25rem;width:44px;height:44px;border-radius:var(--radius-md);background:linear-gradient(135deg,{{ $grad['from'] }},{{ $grad['to'] }});border:3px solid var(--white);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:15px;box-shadow:var(--shadow-sm);">
                    {{ strtoupper(substr($group->nom,0,1)) }}
                </div>
            </div>

            {{-- Body --}}
            <div style="padding:1.75rem 1.25rem 1.25rem;flex:1;display:flex;flex-direction:column;gap:0.625rem;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;">
                    <div style="flex:1;">
                        <div style="font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--brown-950);line-height:1.2;">{{ $group->nom }}</div>
                        <span class="badge badge-brown" style="margin-top:0.25rem;">{{ ucfirst($group->categorie ?? 'Général') }}</span>
                    </div>
                </div>

                <p style="font-size:13px;color:var(--brown-600);line-height:1.6;flex:1;">
                    {{ \Illuminate\Support\Str::limit($group->description, 90) }}
                </p>

                {{-- Membres avatars --}}
                <div style="display:flex;align-items:center;gap:0.625rem;">
                    <div style="display:flex;">
                        @foreach($group->membresRecents ?? [] as $i => $m)
                            @if($i < 4)
                                <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,var(--brown-600),var(--brown-400));border:2px solid var(--white);margin-left:{{ $i?'-8px':'0' }};display:flex;align-items:center;justify-content:center;color:white;font-size:9px;font-weight:700;">
                                    {{ strtoupper(substr($m->nom ?? '?',0,1)) }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <span style="font-size:12px;color:var(--brown-400);">
                        <b style="color:var(--brown-700);">{{ $group->membres_count ?? 0 }}</b> membres
                    </span>
                    @if($group->posts_count ?? false)
                        <span style="font-size:12px;color:var(--brown-400);margin-left:auto;">
                            <i class="bi bi-chat-text"></i> {{ $group->posts_count }}
                        </span>
                    @endif
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:0.5rem;padding-top:0.625rem;border-top:1px solid var(--brown-50);">
                    <a href="{{ route('groups.show', $group) }}" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;">
                        <i class="bi bi-eye"></i> Voir
                    </a>
                    @if($isMember)
                        <form action="{{ route('groups.leave', $group) }}" method="POST" style="flex:1;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;color:var(--brown-400);">
                                <i class="bi bi-box-arrow-left"></i> Quitter
                            </button>
                        </form>
                    @else
                        <form action="{{ route('groups.join', $group) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn btn-amber btn-sm" style="width:100%;justify-content:center;">
                                <i class="bi bi-person-plus"></i> Rejoindre
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column:1/-1;text-align:center;padding:3rem 2rem;border-style:dashed;">
            <div style="font-size:3.5rem;margin-bottom:1rem;">🫂</div>
            <h3 style="font-family:var(--font-display);font-size:1.3rem;color:var(--brown-900);margin-bottom:0.5rem;">Aucun groupe trouvé</h3>
            <p style="color:var(--brown-400);font-size:14px;margin-bottom:1.5rem;">Créez le premier groupe de votre communauté !</p>
            <a href="{{ route('groups.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Créer un groupe
            </a>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if(method_exists($groups, 'links') && $groups->hasPages())
    <div class="card" style="padding:1rem 1.5rem;margin-top:1.5rem;">
        {{ $groups->appends(request()->query())->links() }}
    </div>
@endif

<script>
function filterGroups(q) {
    const cards = document.querySelectorAll('.group-card');
    q = q.toLowerCase();
    cards.forEach(c => {
        c.style.display = c.dataset.name.includes(q) ? '' : 'none';
    });
}
</script>

@endsection

@section('sidebar')
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-title"><i class="bi bi-bar-chart-fill"></i> Statistiques</div>
        @foreach([
            ['label'=>'Mes groupes',  'val'=>$myGroups?->count()??0],
            ['label'=>'Total groupes','val'=>method_exists($groups,'total')?$groups->total():count($groups)],
            ['label'=>'Membres actifs','val'=>$activeMembers??0],
        ] as $s)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.625rem 0;border-bottom:1px solid var(--brown-50);">
                <span style="font-size:13.5px;color:var(--brown-600);">{{ $s['label'] }}</span>
                <span style="font-family:var(--font-display);font-size:1.25rem;font-weight:700;color:var(--brown-900);">{{ $s['val'] }}</span>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-title"><i class="bi bi-tags-fill"></i> Catégories</div>
        @foreach(['Académique','Projet','Entraide','Loisir'] as $cat)
            <a href="?categorie={{ strtolower($cat) }}" style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid var(--brown-50);text-decoration:none;">
                <span style="font-size:13.5px;color:var(--brown-700);font-weight:500;">{{ $cat }}</span>
                <span class="badge badge-brown">{{ $catCounts[strtolower($cat)]??0 }}</span>
            </a>
        @endforeach
    </div>
@endsection