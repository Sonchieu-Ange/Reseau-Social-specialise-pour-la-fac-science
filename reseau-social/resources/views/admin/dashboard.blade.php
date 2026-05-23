@extends('layouts.app')

@section('title', 'Administration')

@section('content')

{{-- ══════════════════ PAGE HEADER ══════════════════ --}}
<div class="page-header fade-up">
    <div>
        <div class="page-title">🛡️ Dashboard Admin</div>
        <div class="page-subtitle">Gestion complète de la plateforme FacSci NG</div>
    </div>
    <div style="display:flex;gap:0.625rem;flex-wrap:wrap;">
        <a href="{{ route('announcements.create') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-megaphone"></i> Annonce
        </a>
        <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-calendar-plus"></i> Événement
        </a>
    </div>
</div>

{{-- ══════════════════ KPI CARDS ══════════════════ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
    @php
        $kpis = [
            ['icon' => 'bi-people-fill',         'val' => $totalUsers ?? 0,   'label' => 'Utilisateurs',   'color' => '#6B3100', 'bg' => '#F5E0D0'],
            ['icon' => 'bi-file-text-fill',       'val' => $totalPosts ?? 0,   'label' => 'Publications',   'color' => '#D97706', 'bg' => '#FEF3C7'],
            ['icon' => 'bi-calendar2-event-fill', 'val' => $totalEvents ?? 0,  'label' => 'Événements',     'color' => '#3B82F6', 'bg' => '#DBEAFE'],
            ['icon' => 'bi-megaphone-fill',       'val' => $totalAnnouncements ?? 0, 'label' => 'Annonces', 'color' => '#10B981', 'bg' => '#D1FAE5'],
            ['icon' => 'bi-people',               'val' => $totalGroups ?? 0,  'label' => 'Groupes',        'color' => '#8B5CF6', 'bg' => '#EDE9FE'],
            ['icon' => 'bi-flag-fill',            'val' => $totalReports ?? 0, 'label' => 'Signalements',   'color' => '#EF4444', 'bg' => '#FEE2E2'],
        ];
    @endphp
    @foreach($kpis as $i => $kpi)
        <div class="card fade-up delay-{{ min($i+1, 4) }}" style="display:flex;flex-direction:column;gap:0.5rem;">
            <div style="width:40px;height:40px;border-radius:var(--radius-md);background:{{ $kpi['bg'] }};display:flex;align-items:center;justify-content:center;">
                <i class="bi {{ $kpi['icon'] }}" style="color:{{ $kpi['color'] }};font-size:17px;"></i>
            </div>
            <div style="font-family:var(--font-display);font-size:2rem;font-weight:900;color:var(--brown-950);line-height:1;">
                {{ number_format($kpi['val']) }}
            </div>
            <div style="font-size:13px;color:var(--brown-500);font-weight:500;">{{ $kpi['label'] }}</div>
        </div>
    @endforeach
</div>

{{-- ══════════════════ UTILISATEURS RÉCENTS + SIGNALEMENTS ══════════════════ --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
    {{-- Utilisateurs récents --}}
    <div class="card fade-up delay-2" style="padding:0;overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1.5px solid var(--brown-50);display:flex;align-items:center;justify-content:space-between;">
            <div class="card-title" style="margin:0;"><i class="bi bi-person-plus-fill"></i> Nouveaux membres</div>
            <a href="{{ route('admin.users') }}" style="font-size:12.5px;color:var(--amber-600);font-weight:600;">Voir tout</a>
        </div>
        <div style="padding:0 0.5rem;">
            @forelse($recentUsers ?? [] as $user)
                @php
                    $uAvatar = $user->avatar ?? null;
                    $uInit = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $user->nom ?? 'U'), 0, 2)));
                @endphp
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1rem;border-bottom:1px solid var(--brown-50);">
                    @if($uAvatar)
                        <img src="{{ $uAvatar }}" class="avatar" style="width:38px;height:38px;">
                    @else
                        <div class="avatar-placeholder" style="width:38px;height:38px;font-size:12px;">{{ $uInit }}</div>
                    @endif
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13.5px;font-weight:600;color:var(--brown-900);">{{ $user->nom }}</div>
                        <div style="font-size:12px;color:var(--brown-400);">{{ $user->email }}</div>
                    </div>
                    <span class="badge {{ $user->role === 'admin' ? 'badge-amber' : 'badge-brown' }}">
                        {{ ucfirst($user->role ?? 'étudiant') }}
                    </span>
                    <div style="display:flex;gap:0.25rem;">
                        <a href="{{ route('admin.users.edit', $user) }}" class="icon-btn" style="width:28px;height:28px;font-size:13px;"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn" style="width:28px;height:28px;font-size:13px;color:#EF4444;" onclick="return confirm('Supprimer cet utilisateur ?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="text-align:center;padding:2rem;color:var(--brown-400);font-size:13.5px;">Aucun utilisateur récent</p>
            @endforelse
        </div>
    </div>

    {{-- Signalements --}}
    <div class="card fade-up delay-3" style="padding:0;overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1.5px solid var(--brown-50);display:flex;align-items:center;justify-content:space-between;">
            <div class="card-title" style="margin:0;"><i class="bi bi-flag-fill" style="color:#EF4444;"></i> Signalements</div>
            <span class="badge badge-red">{{ $pendingReports ?? 0 }} en attente</span>
        </div>
        <div style="padding:0 0.5rem;">
            @forelse($reports ?? [] as $report)
                <div style="padding:0.875rem 1rem;border-bottom:1px solid var(--brown-50);">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;margin-bottom:0.375rem;">
                        <span style="font-size:13.5px;font-weight:600;color:var(--brown-900);">{{ \Illuminate\Support\Str::limit($report->raison, 45) }}</span>
                        <span class="badge {{ $report->statut === 'résolu' ? 'badge-green' : 'badge-red' }}">{{ $report->statut }}</span>
                    </div>
                    <div style="font-size:12px;color:var(--brown-400);">
                        Signalé par {{ $report->reporter->nom ?? '?' }} · {{ $report->created_at->diffForHumans() }}
                    </div>
                    <div style="display:flex;gap:0.5rem;margin-top:0.625rem;">
                        <button class="btn btn-ghost btn-xs"><i class="bi bi-eye"></i> Voir</button>
                        <button class="btn btn-xs" style="background:#D1FAE5;color:#065F46;"><i class="bi bi-check-lg"></i> Résoudre</button>
                    </div>
                </div>
            @empty
                <p style="text-align:center;padding:2rem;color:var(--brown-400);font-size:13.5px;">✅ Aucun signalement en attente</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════ PUBLICATIONS RÉCENTES + ACTIONS RAPIDES ══════════════════ --}}
<div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;margin-bottom:2rem;">
    {{-- Publications récentes --}}
    <div class="card fade-up delay-3" style="padding:0;overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1.5px solid var(--brown-50);display:flex;align-items:center;justify-content:space-between;">
            <div class="card-title" style="margin:0;"><i class="bi bi-file-text-fill"></i> Publications récentes</div>
            <a href="{{ route('admin.posts') }}" style="font-size:12.5px;color:var(--amber-600);font-weight:600;">Modérer</a>
        </div>
        <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
            <table>
                <thead>
                    <tr>
                        <th>Auteur</th>
                        <th>Contenu</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPosts ?? [] as $post)
                        <tr>
                            <td>
                                <span style="font-weight:600;color:var(--brown-900);">{{ $post->user->nom ?? '—' }}</span>
                            </td>
                            <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ \Illuminate\Support\Str::limit($post->contenu, 50) }}
                            </td>
                            <td style="font-size:12px;color:var(--brown-400);white-space:nowrap;">
                                {{ $post->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <div style="display:flex;gap:0.25rem;">
                                    <a href="{{ route('posts.show', $post) }}" class="icon-btn" style="width:28px;height:28px;font-size:13px;"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="icon-btn" style="width:28px;height:28px;font-size:13px;color:#EF4444;" onclick="return confirm('Supprimer ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:var(--brown-400);">Aucune publication</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div style="display:flex;flex-direction:column;gap:0.875rem;">
        <div class="card fade-up delay-4">
            <div class="card-title"><i class="bi bi-lightning-fill" style="color:var(--amber-500);"></i> Actions rapides</div>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm" style="justify-content:center;border-radius:var(--radius-lg);">
                    <i class="bi bi-megaphone"></i> Créer une annonce
                </a>
                <a href="{{ route('events.create') }}" class="btn btn-amber btn-sm" style="justify-content:center;border-radius:var(--radius-lg);">
                    <i class="bi bi-calendar-plus"></i> Ajouter un événement
                </a>
                <a href="{{ route('groups.create') }}" class="btn btn-ghost btn-sm" style="justify-content:center;border-radius:var(--radius-lg);">
                    <i class="bi bi-people"></i> Créer un groupe
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-ghost btn-sm" style="justify-content:center;border-radius:var(--radius-lg);">
                    <i class="bi bi-person-plus"></i> Ajouter un membre
                </a>
            </div>
        </div>

        <div class="card fade-up delay-4">
            <div class="card-title"><i class="bi bi-activity"></i> Activité récente</div>
            @foreach($recentActivity ?? [] as $act)
                <div style="display:flex;gap:0.625rem;align-items:flex-start;padding:0.5rem 0;border-bottom:1px solid var(--brown-50);">
                    <div style="width:6px;height:6px;border-radius:50%;background:var(--amber-400);margin-top:7px;flex-shrink:0;"></div>
                    <p style="font-size:12.5px;color:var(--brown-600);line-height:1.4;">{{ $act }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection