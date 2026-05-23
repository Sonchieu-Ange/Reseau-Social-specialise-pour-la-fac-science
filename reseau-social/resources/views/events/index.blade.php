@extends('layouts.app')

@section('title', 'Événements')

@section('content')

{{-- ══════════════════ HEADER ══════════════════ --}}
<div class="page-header fade-up">
    <div>
        <div class="page-title">📅 Événements</div>
        <div class="page-subtitle">Agenda de la Faculté des Sciences de Ngaoundéré</div>
    </div>
    @can('create', App\Models\Event::class)
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="bi bi-calendar-plus"></i> Créer un événement
        </a>
    @endcan
</div>

{{-- ══════════════════ VUE MOIS + FILTRES ══════════════════ --}}
<div class="card fade-up delay-1" style="margin-bottom:1.5rem;padding:1rem 1.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;">
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            @foreach(['Tous','Conférence','Soutenance','Atelier','Sport','Culturel','Autre'] as $type)
                <a href="?type={{ strtolower($type) }}"
                   class="btn btn-ghost btn-sm {{ (request('type','tous')===strtolower($type)) ? 'btn-amber' : '' }}"
                   style="border-radius:50px;">
                    {{ $type }}
                </a>
            @endforeach
        </div>
        <div style="display:flex;gap:0.5rem;">
            <button class="btn btn-ghost btn-sm" id="view-list" onclick="setView('list')">
                <i class="bi bi-list-ul"></i> Liste
            </button>
            <button class="btn btn-ghost btn-sm" id="view-grid" onclick="setView('grid')">
                <i class="bi bi-grid"></i> Grille
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════ ÉVÉNEMENTS À VENIR ══════════════════ --}}
@php
    $upcoming = $events->where('date_debut', '>=', now());
    $past     = $events->where('date_debut', '<', now());
@endphp

@if($upcoming->count())
    <div style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--brown-400);margin-bottom:0.875rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="bi bi-clock-fill" style="color:var(--amber-500);"></i> À venir
    </div>

    <div id="events-container" style="display:flex;flex-direction:column;gap:1rem;margin-bottom:2rem;">
        @foreach($upcoming as $event)
            @php
                $colorMap = [
                    'conférence' => ['bg'=>'#DBEAFE','text'=>'#1D4ED8','icon'=>'bi-mic-fill'],
                    'soutenance' => ['bg'=>'#D1FAE5','text'=>'#065F46','icon'=>'bi-award-fill'],
                    'atelier'    => ['bg'=>'#FEF3C7','text'=>'#D97706','icon'=>'bi-tools'],
                    'sport'      => ['bg'=>'#FCE7F3','text'=>'#9D174D','icon'=>'bi-trophy-fill'],
                    'culturel'   => ['bg'=>'#EDE9FE','text'=>'#5B21B6','icon'=>'bi-palette-fill'],
                ];
                $typeKey = strtolower($event->type ?? 'autre');
                $col = $colorMap[$typeKey] ?? ['bg'=>'#F5E0D0','text'=>'#6B3100','icon'=>'bi-calendar-fill'];
                $start = \Carbon\Carbon::parse($event->date_debut);
                $isToday = $start->isToday();
                $isTomorrow = $start->isTomorrow();
            @endphp

            <div class="card fade-up delay-2 event-card" style="padding:0;overflow:hidden;{{ $isToday ? 'border-color:var(--amber-400);box-shadow:0 0 0 3px rgba(251,191,36,.1);' : '' }}">
                <div style="display:flex;align-items:stretch;">
                    {{-- Date sidebar --}}
                    <div style="width:80px;flex-shrink:0;background:{{ $col['bg'] }};display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.25rem 0.5rem;border-right:1.5px solid {{ $col['bg'] }};">
                        <i class="bi {{ $col['icon'] }}" style="color:{{ $col['text'] }};font-size:18px;margin-bottom:0.5rem;"></i>
                        <span style="font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:{{ $col['text'] }};opacity:.7;">{{ $start->format('M') }}</span>
                        <span style="font-size:2rem;font-weight:900;color:{{ $col['text'] }};line-height:1;">{{ $start->format('d') }}</span>
                        <span style="font-size:11px;font-weight:600;color:{{ $col['text'] }};opacity:.8;">{{ $start->format('Y') }}</span>
                    </div>

                    {{-- Content --}}
                    <div style="flex:1;padding:1.25rem 1.5rem;display:flex;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                        <div style="flex:1;min-width:200px;">
                            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.375rem;flex-wrap:wrap;">
                                @if($isToday)
                                    <span class="badge badge-amber"><i class="bi bi-lightning-fill"></i> Aujourd'hui</span>
                                @elseif($isTomorrow)
                                    <span class="badge badge-green">Demain</span>
                                @endif
                                <span class="badge" style="background:{{ $col['bg'] }};color:{{ $col['text'] }};">{{ ucfirst($event->type ?? 'Autre') }}</span>
                            </div>
                            <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--brown-950);margin-bottom:0.375rem;line-height:1.3;">
                                {{ $event->titre }}
                            </h3>
                            <p style="font-size:13.5px;color:var(--brown-600);line-height:1.6;margin-bottom:0.625rem;">
                                {{ \Illuminate\Support\Str::limit($event->description, 120) }}
                            </p>
                            <div style="display:flex;flex-wrap:wrap;gap:0.875rem;font-size:12.5px;color:var(--brown-500);">
                                <span><i class="bi bi-clock"></i> {{ $start->format('H:i') }}
                                    @if($event->date_fin) → {{ \Carbon\Carbon::parse($event->date_fin)->format('H:i') }} @endif
                                </span>
                                @if($event->lieu)
                                    <span><i class="bi bi-geo-alt"></i> {{ $event->lieu }}</span>
                                @endif
                                @if($event->capacite)
                                    <span><i class="bi bi-people"></i> {{ $event->inscrits_count ?? 0 }}/{{ $event->capacite }} places</span>
                                @endif
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:0.5rem;align-items:flex-end;flex-shrink:0;">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">
                                Voir <i class="bi bi-arrow-right"></i>
                            </a>
                            @if(!$event->isUserRegistered(auth()->user()))
                                <form action="{{ route('events.register', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-amber btn-sm">
                                        <i class="bi bi-calendar-check"></i> S'inscrire
                                    </button>
                                </form>
                            @else
                                <span class="badge badge-green" style="padding:.4rem .8rem;"><i class="bi bi-check-circle-fill"></i> Inscrit</span>
                            @endif
                            @can('update', $event)
                                <a href="{{ route('events.edit', $event) }}" class="icon-btn" style="width:30px;height:30px;font-size:13px;"><i class="bi bi-pencil"></i></a>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- Progress bar capacité --}}
                @if($event->capacite && $event->capacite > 0)
                    @php $pct = min(100, round(($event->inscrits_count ?? 0) / $event->capacite * 100)); @endphp
                    <div style="height:4px;background:var(--brown-50);">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $pct >= 90 ? '#EF4444' : ($pct >= 60 ? '#F59E0B' : '#10B981') }};transition:width .6s ease;border-radius:0 0 4px 0;"></div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- ══════════════════ ÉVÉNEMENTS PASSÉS ══════════════════ --}}
@if($past->count())
    <details style="margin-bottom:1.5rem;">
        <summary style="display:flex;align-items:center;gap:0.625rem;cursor:pointer;padding:0.875rem 1.25rem;background:var(--white);border:1.5px solid var(--brown-100);border-radius:var(--radius-xl);font-size:14px;font-weight:600;color:var(--brown-600);list-style:none;user-select:none;">
            <i class="bi bi-clock-history"></i>
            Événements passés ({{ $past->count() }})
            <i class="bi bi-chevron-down" style="margin-left:auto;transition:transform .2s;"></i>
        </summary>
        <div style="margin-top:0.75rem;display:flex;flex-direction:column;gap:0.75rem;opacity:.7;">
            @foreach($past as $event)
                <div class="card" style="padding:1rem 1.5rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:900;color:var(--brown-300);min-width:44px;text-align:center;">
                        {{ \Carbon\Carbon::parse($event->date_debut)->format('d') }}
                        <div style="font-size:10px;color:var(--brown-300);font-weight:700;">{{ strtoupper(\Carbon\Carbon::parse($event->date_debut)->format('M Y')) }}</div>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:14px;font-weight:600;color:var(--brown-600);">{{ $event->titre }}</div>
                        <div style="font-size:12px;color:var(--brown-400);">{{ $event->lieu ?? '' }}</div>
                    </div>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-ghost btn-sm">Voir</a>
                </div>
            @endforeach
        </div>
    </details>
@endif

{{-- Pagination --}}
@if(method_exists($events, 'links') && $events->hasPages())
    <div class="card" style="padding:1rem 1.5rem;">
        {{ $events->appends(request()->query())->links() }}
    </div>
@endif

<script>
function setView(mode) {
    const container = document.getElementById('events-container');
    if (mode === 'grid') {
        container.style.display = 'grid';
        container.style.gridTemplateColumns = 'repeat(auto-fill,minmax(280px,1fr))';
    } else {
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
    }
    document.getElementById('view-'+mode).classList.add('btn-amber');
    document.getElementById('view-'+(mode==='list'?'grid':'list')).classList.remove('btn-amber');
}
</script>

@endsection

@section('sidebar')
    {{-- Mini calendrier visuel --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-title"><i class="bi bi-calendar3"></i> Ce mois</div>
        @php
            $now = now();
            $daysInMonth = $now->daysInMonth;
            $firstDay = $now->copy()->startOfMonth()->dayOfWeek;
        @endphp
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;text-align:center;margin-bottom:0.25rem;">
            @foreach(['D','L','M','M','J','V','S'] as $d)
                <div style="font-size:10px;font-weight:700;color:var(--brown-400);padding:3px 0;">{{ $d }}</div>
            @endforeach
            @for($i=0;$i<$firstDay;$i++)
                <div></div>
            @endfor
            @for($day=1;$day<=$daysInMonth;$day++)
                @php
                    $isToday = $day === (int)$now->format('d');
                    $hasEvent = ($events ?? collect())->contains(fn($e) => \Carbon\Carbon::parse($e->date_debut)->format('d') == $day);
                @endphp
                <div style="padding:4px 2px;border-radius:6px;font-size:12px;font-weight:{{ $isToday?'700':'500' }};
                    background:{{ $isToday?'var(--amber-400)':($hasEvent?'var(--brown-50)':'transparent') }};
                    color:{{ $isToday?'var(--brown-950)':($hasEvent?'var(--amber-600)':'var(--brown-600)') }};
                    position:relative;">
                    {{ $day }}
                    @if($hasEvent && !$isToday)
                        <div style="position:absolute;bottom:1px;left:50%;transform:translateX(-50%);width:4px;height:4px;border-radius:50%;background:var(--amber-400);"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <div class="card">
        <div class="card-title"><i class="bi bi-tags-fill"></i> Par type</div>
        @foreach(['Conférence','Soutenance','Atelier','Sport','Culturel'] as $t)
            <a href="?type={{ strtolower($t) }}" style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid var(--brown-50);text-decoration:none;">
                <span style="font-size:13.5px;color:var(--brown-700);font-weight:500;">{{ $t }}</span>
                <span class="badge badge-brown">{{ $typeCounts[strtolower($t)] ?? 0 }}</span>
            </a>
        @endforeach
    </div>
@endsection