@extends('layouts.app')

@section('title', 'Messages')

@section('content')

{{-- ══════════════════ MESSAGERIE LAYOUT ══════════════════ --}}
<div style="display:grid;grid-template-columns:320px 1fr;gap:0;height:calc(100vh - var(--topbar-h) - 4rem);background:var(--white);border:1.5px solid var(--brown-100);border-radius:var(--radius-2xl);overflow:hidden;box-shadow:var(--shadow-md);">

    {{-- ═══ PANNEAU GAUCHE : LISTE DES CONVERSATIONS ═══ --}}
    <div style="border-right:1.5px solid var(--brown-50);display:flex;flex-direction:column;background:var(--white);">

        {{-- Header Conversations --}}
        <div style="padding:1.25rem 1.25rem 0.875rem;border-bottom:1.5px solid var(--brown-50);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.875rem;">
                <h2 style="font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--brown-950);">Messages</h2>
                <div style="display:flex;gap:0.25rem;">
                    <button class="icon-btn" style="width:30px;height:30px;font-size:13px;" title="Nouvelle conversation">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="icon-btn" style="width:30px;height:30px;font-size:13px;" title="Filtrer">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" class="search-input" placeholder="Rechercher une conversation…" oninput="filterConversations(this.value)">
            </div>
            <div style="display:flex;gap:0.375rem;margin-top:0.625rem;">
                <button onclick="setTab('all',this)" class="tab-btn active-tab btn btn-ghost btn-xs" style="border-radius:50px;">Tout</button>
                <button onclick="setTab('unread',this)" class="tab-btn btn btn-ghost btn-xs" style="border-radius:50px;">Non lus <span class="badge badge-amber" style="margin-left:4px;">{{ $unreadCount ?? 0 }}</span></button>
                <button onclick="setTab('groups',this)" class="tab-btn btn btn-ghost btn-xs" style="border-radius:50px;">Groupes</button>
            </div>
        </div>

        {{-- Liste conversations --}}
        <div style="flex:1;overflow-y:auto;scrollbar-width:thin;scrollbar-color:var(--brown-100) transparent;" id="conversations-list">
            @forelse($conversations as $conv)
                @php
                    $other = $conv->getOtherUser(auth()->user());
                    $otherAvatar = $other?->avatar ?? null;
                    $otherInit   = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $other?->nom ?? '?'), 0, 2)));
                    $isActive    = request('conversation') == $conv->id;
                    $hasUnread   = ($conv->unread_count ?? 0) > 0;
                    $isGroup     = $conv->type === 'group';
                @endphp
                <a href="{{ route('messages.show', $conv) }}"
                   class="conv-item {{ $isActive ? 'conv-active' : '' }}"
                   data-name="{{ strtolower($other?->nom ?? $conv->nom ?? '') }}"
                   style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1.25rem;border-bottom:1px solid var(--brown-50);text-decoration:none;transition:background var(--transition);{{ $isActive ? 'background:var(--amber-50);' : '' }}">

                    {{-- Avatar --}}
                    <div style="position:relative;flex-shrink:0;">
                        @if($isGroup)
                            <div style="width:44px;height:44px;border-radius:var(--radius-md);background:linear-gradient(135deg,var(--brown-700),var(--brown-500));display:flex;align-items:center;justify-content:center;color:white;font-size:17px;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        @elseif($otherAvatar)
                            <img src="{{ $otherAvatar }}" class="avatar" style="width:44px;height:44px;">
                        @else
                            <div class="avatar-placeholder" style="width:44px;height:44px;font-size:13px;">{{ $otherInit }}</div>
                        @endif
                        @if(!$isGroup && ($other?->online ?? false))
                            <div style="position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:#10B981;border:2px solid var(--white);"></div>
                        @endif
                    </div>

                    {{-- Infos --}}
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2px;">
                            <span style="font-size:14px;font-weight:{{ $hasUnread ? '700' : '600' }};color:var(--brown-{{ $hasUnread ? '950' : '800' }});white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;">
                                {{ $isGroup ? ($conv->nom ?? 'Groupe') : ($other?->nom ?? 'Inconnu') }}
                            </span>
                            <span style="font-size:11px;color:var(--brown-400);flex-shrink:0;">
                                {{ $conv->lastMessage?->created_at->format('H:i') ?? '' }}
                            </span>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.25rem;">
                            <span style="font-size:12.5px;color:var(--brown-{{ $hasUnread ? '600' : '400' }});font-weight:{{ $hasUnread ? '500' : '400' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                {{ $conv->lastMessage ? \Illuminate\Support\Str::limit($conv->lastMessage->contenu, 38) : 'Démarrez la conversation…' }}
                            </span>
                            @if($hasUnread)
                                <span style="flex-shrink:0;min-width:18px;height:18px;background:var(--amber-500);color:var(--brown-950);border-radius:50%;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;font-family:var(--font-mono);">
                                    {{ $conv->unread_count }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div style="padding:3rem 1.5rem;text-align:center;">
                    <div style="font-size:3rem;margin-bottom:0.875rem;">💬</div>
                    <p style="font-size:13.5px;color:var(--brown-400);">Aucune conversation pour l'instant</p>
                    <button class="btn btn-amber btn-sm" style="margin-top:0.875rem;border-radius:50px;">
                        <i class="bi bi-person-plus"></i> Nouveau message
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══ PANNEAU DROIT : FENÊTRE DE CONVERSATION ═══ --}}
    <div style="display:flex;flex-direction:column;background:var(--cream-100);">
        @if(isset($activeConversation))
            @php
                $other = $activeConversation->getOtherUser(auth()->user());
                $otherAvatar = $other?->avatar ?? null;
                $otherInit   = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $other?->nom ?? '?'), 0, 2)));
            @endphp

            {{-- Chat Header --}}
            <div style="padding:1rem 1.5rem;background:var(--white);border-bottom:1.5px solid var(--brown-50);display:flex;align-items:center;gap:0.875rem;">
                <div style="position:relative;">
                    @if($otherAvatar)
                        <img src="{{ $otherAvatar }}" class="avatar" style="width:40px;height:40px;">
                    @else
                        <div class="avatar-placeholder" style="width:40px;height:40px;font-size:13px;">{{ $otherInit }}</div>
                    @endif
                    @if($other?->online ?? false)
                        <div style="position:absolute;bottom:0;right:0;width:10px;height:10px;border-radius:50%;background:#10B981;border:2px solid var(--white);"></div>
                    @endif
                </div>
                <div style="flex:1;">
                    <div style="font-weight:700;color:var(--brown-950);font-size:15px;">{{ $other?->nom ?? 'Inconnu' }}</div>
                    <div style="font-size:12px;color:{{ ($other?->online ?? false) ? '#10B981' : 'var(--brown-400)' }};">
                        {{ ($other?->online ?? false) ? 'En ligne' : 'Hors ligne' }}
                    </div>
                </div>
                <div style="display:flex;gap:0.25rem;">
                    <button class="icon-btn" style="width:34px;height:34px;font-size:15px;" title="Appel audio"><i class="bi bi-telephone"></i></button>
                    <button class="icon-btn" style="width:34px;height:34px;font-size:15px;" title="Appel vidéo"><i class="bi bi-camera-video"></i></button>
                    <button class="icon-btn" style="width:34px;height:34px;font-size:15px;" title="Infos"><i class="bi bi-info-circle"></i></button>
                </div>
            </div>

            {{-- Zone messages --}}
            <div id="chat-messages" style="flex:1;overflow-y:auto;padding:1.25rem;display:flex;flex-direction:column;gap:0.625rem;scrollbar-width:thin;scrollbar-color:var(--brown-100) transparent;">
                @php $lastDate = null; @endphp
                @foreach($messages as $msg)
                    @php
                        $isMe = $msg->user_id === auth()->id();
                        $msgDate = $msg->created_at->format('d/m/Y');
                    @endphp

                    @if($msgDate !== $lastDate)
                        <div style="text-align:center;margin:0.5rem 0;">
                            <span style="background:var(--white);border:1px solid var(--brown-100);border-radius:50px;padding:0.25rem 0.875rem;font-size:11.5px;color:var(--brown-400);font-weight:600;">
                                {{ $msg->created_at->isToday() ? "Aujourd'hui" : ($msg->created_at->isYesterday() ? 'Hier' : $msgDate) }}
                            </span>
                        </div>
                        @php $lastDate = $msgDate; @endphp
                    @endif

                    <div style="display:flex;align-items:flex-end;gap:0.5rem;justify-content:{{ $isMe ? 'flex-end' : 'flex-start' }};">
                        @if(!$isMe)
                            @if($otherAvatar)
                                <img src="{{ $otherAvatar }}" class="avatar" style="width:28px;height:28px;flex-shrink:0;">
                            @else
                                <div class="avatar-placeholder" style="width:28px;height:28px;font-size:9px;flex-shrink:0;">{{ $otherInit }}</div>
                            @endif
                        @endif

                        <div style="max-width:65%;">
                            <div style="
                                padding:0.625rem 0.875rem;
                                border-radius:{{ $isMe ? 'var(--radius-xl) var(--radius-xl) 4px var(--radius-xl)' : 'var(--radius-xl) var(--radius-xl) var(--radius-xl) 4px' }};
                                background:{{ $isMe ? 'linear-gradient(135deg,var(--brown-700),var(--brown-600))' : 'var(--white)' }};
                                color:{{ $isMe ? 'var(--white)' : 'var(--brown-900)' }};
                                font-size:14px;
                                line-height:1.5;
                                box-shadow:var(--shadow-xs);
                                border:{{ $isMe ? 'none' : '1.5px solid var(--brown-50)' }};
                                word-break:break-word;
                            ">
                                {{ $msg->contenu }}
                                @if($msg->fichier)
                                    <div style="margin-top:0.375rem;padding:0.375rem 0.625rem;background:rgba(255,255,255,.15);border-radius:var(--radius-md);font-size:12px;display:flex;align-items:center;gap:0.375rem;">
                                        <i class="bi bi-paperclip"></i>
                                        <a href="{{ $msg->fichier }}" style="color:inherit;text-decoration:underline;">Pièce jointe</a>
                                    </div>
                                @endif
                            </div>
                            <div style="font-size:10.5px;color:var(--brown-400);margin-top:3px;{{ $isMe ? 'text-align:right' : '' }}">
                                {{ $msg->created_at->format('H:i') }}
                                @if($isMe)
                                    <i class="bi bi-check{{ $msg->lu ? '2 text-primary' : '' }}" style="font-size:11px;{{ $msg->lu ? 'color:var(--amber-500);' : '' }}"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Zone de saisie --}}
            <div style="padding:1rem 1.25rem;background:var(--white);border-top:1.5px solid var(--brown-50);">
                <form action="{{ route('messages.store', $activeConversation) }}" method="POST" id="message-form" style="display:flex;align-items:flex-end;gap:0.625rem;">
                    @csrf
                    <div style="display:flex;gap:0.375rem;flex-shrink:0;">
                        <button type="button" class="icon-btn" style="width:36px;height:36px;font-size:16px;" title="Émoji">
                            <i class="bi bi-emoji-smile"></i>
                        </button>
                        <label class="icon-btn" style="width:36px;height:36px;font-size:16px;cursor:pointer;" title="Pièce jointe">
                            <i class="bi bi-paperclip"></i>
                            <input type="file" name="fichier" style="display:none;">
                        </label>
                    </div>

                    <div style="flex:1;position:relative;">
                        <textarea name="contenu" id="msg-input" rows="1" placeholder="Écrire un message…"
                                  style="width:100%;background:var(--brown-50);border:1.5px solid var(--brown-100);border-radius:var(--radius-xl);padding:0.625rem 1.125rem;font-family:var(--font-body);font-size:14.5px;color:var(--brown-900);resize:none;max-height:140px;overflow-y:auto;line-height:1.5;transition:all var(--transition);"
                                  onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm" style="border-radius:50px;height:38px;padding:0 1rem;flex-shrink:0;">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>

        @else
            {{-- Aucune conversation sélectionnée --}}
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1rem;padding:2rem;">
                <div style="width:80px;height:80px;background:var(--brown-50);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;">
                    💬
                </div>
                <div style="text-align:center;">
                    <h3 style="font-family:var(--font-display);font-size:1.4rem;color:var(--brown-900);margin-bottom:0.375rem;">Vos messages</h3>
                    <p style="font-size:14px;color:var(--brown-400);line-height:1.6;max-width:280px;">
                        Sélectionnez une conversation pour commencer à discuter avec vos collègues.
                    </p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Nouveau message
                </button>
            </div>
        @endif
    </div>
</div>

<script>
// Auto-scroll to bottom
window.addEventListener('load', () => {
    const el = document.getElementById('chat-messages');
    if (el) el.scrollTop = el.scrollHeight;
});

// Auto-resize textarea
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 140) + 'px';
}

// Enter to send (Shift+Enter for new line)
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        const form = document.getElementById('message-form');
        const input = document.getElementById('msg-input');
        if (input.value.trim()) form.submit();
    }
}

// Filter conversations
function filterConversations(q) {
    const items = document.querySelectorAll('.conv-item');
    q = q.toLowerCase();
    items.forEach(i => { i.style.display = i.dataset.name.includes(q) ? '' : 'none'; });
}

// Tab switching
function setTab(tab, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('btn-amber'));
    btn.classList.add('btn-amber');
    const items = document.querySelectorAll('.conv-item');
    items.forEach(item => {
        if (tab === 'all') item.style.display = '';
        else if (tab === 'unread') item.style.display = item.querySelector('[style*="amber"]') ? '' : 'none';
        else if (tab === 'groups') item.style.display = 'none'; // Adapter selon la logique
    });
}

// Hover effect on conv items
document.querySelectorAll('.conv-item:not(.conv-active)').forEach(el => {
    el.addEventListener('mouseenter', () => el.style.background = 'var(--brown-50)');
    el.addEventListener('mouseleave', () => el.style.background = '');
});
</script>

@endsection