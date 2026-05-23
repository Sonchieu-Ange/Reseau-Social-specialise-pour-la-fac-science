<aside id="sidebar-right">

    {{-- Annonces officielles --}}
    <div class="widget-card">
        <div class="widget-header">
            <i class="bi bi-megaphone-fill"></i>
            Annonces officielles
        </div>
        <div class="widget-body">
            @if(isset($announcements) && $announcements->isNotEmpty())
                @foreach($announcements->take(3) as $ann)
                <div class="widget-item">
                    <div class="widget-item-icon">
                        <i class="bi bi-broadcast-pin"></i>
                    </div>
                    <div class="widget-item-text">
                        <div class="widget-item-title">
                            <a href="{{ route('announcements.index') }}" style="color:inherit;">
                                {{ Str::limit($ann->titre, 45) }}
                            </a>
                        </div>
                        <div class="widget-item-sub">
                            <i class="bi bi-clock" style="font-size:10px;"></i>
                            {{ $ann->date_creation ? \Carbon\Carbon::parse($ann->date_creation)->diffForHumans() : '—' }}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center;padding:.75rem 0;color:var(--brown-300);font-size:13px;">
                    Aucune annonce récente
                </div>
            @endif

            <a href="{{ route('announcements.index') }}"
               style="display:block;text-align:center;margin-top:.5rem;font-size:12.5px;font-weight:600;color:var(--amber-600);padding:.35rem;">
                Voir toutes les annonces →
            </a>
        </div>
    </div>

    {{-- Événements à venir --}}
    <div class="widget-card">
        <div class="widget-header">
            <i class="bi bi-calendar-event-fill"></i>
            Événements à venir
        </div>
        <div class="widget-body">
            @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
                @foreach($upcomingEvents->take(3) as $event)
                <div class="widget-item">
                    <div class="widget-item-icon" style="background:var(--brown-50);color:var(--brown-600);">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="widget-item-text">
                        <div class="widget-item-title">
                            <a href="{{ route('events.show', $event->_id) }}" style="color:inherit;">
                                {{ Str::limit($event->titre, 40) }}
                            </a>
                        </div>
                        <div class="widget-item-sub">
                            <i class="bi bi-geo-alt" style="font-size:10px;"></i>
                            {{ $event->lieu ?? 'Campus' }} ·
                            {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M') : '—' }}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center;padding:.75rem 0;color:var(--brown-300);font-size:13px;">
                    Aucun événement prévu
                </div>
            @endif

            <a href="{{ route('events.index') }}"
               style="display:block;text-align:center;margin-top:.5rem;font-size:12.5px;font-weight:600;color:var(--amber-600);padding:.35rem;">
                Voir l'agenda complet →
            </a>
        </div>
    </div>

    {{-- Groupes suggérés --}}
    <div class="widget-card">
        <div class="widget-header">
            <i class="bi bi-people-fill"></i>
            Groupes populaires
        </div>
        <div class="widget-body">
            @if(isset($suggestedGroups) && $suggestedGroups->isNotEmpty())
                @foreach($suggestedGroups->take(4) as $group)
                <div class="widget-item" style="align-items:center;">
                    <div class="widget-item-icon" style="border-radius:var(--radius-sm);">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="widget-item-text">
                        <div class="widget-item-title">{{ Str::limit($group->nom, 28) }}</div>
                        <div class="widget-item-sub">{{ $group->membresCount ?? 0 }} membres</div>
                    </div>
                    <a href="{{ route('groups.show', $group->_id) }}"
                       style="font-size:11.5px;font-weight:600;color:var(--amber-600);white-space:nowrap;flex-shrink:0;">
                        Rejoindre
                    </a>
                </div>
                @endforeach
            @else
                <div style="text-align:center;padding:.75rem 0;color:var(--brown-300);font-size:13px;">
                    Aucun groupe disponible
                </div>
            @endif

            <a href="{{ route('groups.index') }}"
               style="display:block;text-align:center;margin-top:.5rem;font-size:12.5px;font-weight:600;color:var(--amber-600);padding:.35rem;">
                Explorer les groupes →
            </a>
        </div>
    </div>

    {{-- Footer --}}
    <div style="padding:.5rem .25rem;font-size:11px;color:var(--brown-300);line-height:1.8;">
        <div style="font-weight:600;color:var(--brown-400);margin-bottom:.25rem;">Faculté des Sciences</div>
        Université de Ngaoundéré · 2025–2026<br>
        <a href="#" style="color:var(--amber-600);">À propos</a> ·
        <a href="#" style="color:var(--amber-600);">Aide</a> ·
        <a href="#" style="color:var(--amber-600);">Contact</a>
    </div>
</aside>