{{--
    Partial: _comment_section.blade.php
    Expects: $post
--}}
<div style="padding:.75rem 1.25rem 1rem;">
    {{-- Existing comments --}}
    @php $comments = $post->comments ?? collect(); @endphp

    @forelse($comments as $comment)
    @php
        $cUser   = $comment->user ?? null;
        $cName   = $cUser->nom ?? 'Utilisateur';
        $cAvatar = $cUser->avatar ?? null;
        $cId     = $cUser->_id ?? null;
        $cInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $cName), 0, 2)));
        $cDate = $comment->date_creation
                    ? \Carbon\Carbon::parse($comment->date_creation)->diffForHumans()
                    : '—';
    @endphp
    <div style="display:flex;gap:.65rem;margin-bottom:.85rem;">
        @if($cAvatar)
            <img src="{{ $cAvatar }}" class="avatar" style="width:32px;height:32px;flex-shrink:0;">
        @else
            <div class="avatar-placeholder" style="width:32px;height:32px;font-size:11px;flex-shrink:0;">{{ $cInitials }}</div>
        @endif

        <div style="flex:1;min-width:0;">
            <div style="background:var(--brown-50);border-radius:0 var(--radius-md) var(--radius-md) var(--radius-md);padding:.55rem .85rem;">
                <div style="font-size:12.5px;font-weight:600;color:var(--brown-800);margin-bottom:.15rem;">
                    <a href="{{ route('profile.show', $cId ?? '#') }}" style="color:inherit;">{{ $cName }}</a>
                </div>
                <div style="font-size:13.5px;color:var(--brown-700);">{{ $comment->contenu }}</div>
            </div>
            <div style="font-size:11px;color:var(--brown-300);margin-top:.2rem;padding-left:.25rem;">
                {{ $cDate }}
                @auth
                @if(Auth::user()->_id == $cId || Auth::user()->role === 'admin')
                · <form method="POST" action="{{ route('comments.destroy', $comment->_id) }}" style="display:inline;"
                        onsubmit="return confirm('Supprimer ce commentaire ?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:#DC2626;font-size:11px;cursor:pointer;padding:0;">
                        Supprimer
                    </button>
                  </form>
                @endif
                @endauth
            </div>
        </div>
    </div>
    @empty
        <p style="text-align:center;font-size:13px;color:var(--brown-300);padding:.5rem 0;">
            Aucun commentaire. Soyez le premier !
        </p>
    @endforelse

    {{-- Add comment form --}}
    @auth
    <form method="POST" action="{{ route('comments.store', $post->_id) }}" style="display:flex;gap:.65rem;align-items:flex-start;margin-top:.5rem;">
        @csrf
        @php
            $me = Auth::user();
            $myAvatar = $me->avatar ?? null;
            $myInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $me->nom), 0, 2)));
        @endphp

        @if($myAvatar)
            <img src="{{ $myAvatar }}" class="avatar" style="width:32px;height:32px;flex-shrink:0;">
        @else
            <div class="avatar-placeholder" style="width:32px;height:32px;font-size:11px;flex-shrink:0;">{{ $myInitials }}</div>
        @endif

        <div style="flex:1;display:flex;gap:.5rem;">
            <input type="text"
                   name="contenu"
                   placeholder="Écrire un commentaire…"
                   required
                   style="flex:1;padding:.5rem .85rem;background:var(--brown-50);border:1.5px solid var(--brown-100);border-radius:var(--radius-xl);font-family:var(--font-body);font-size:13.5px;color:var(--brown-900);outline:none;transition:.18s ease;"
                   onfocus="this.style.borderColor='var(--amber-400)';this.style.background='var(--white)';"
                   onblur="this.style.borderColor='var(--brown-100)';this.style.background='var(--brown-50)';">
            <button type="submit"
                    style="padding:.5rem .85rem;background:var(--amber-500);color:var(--brown-900);border:none;border-radius:var(--radius-xl);font-weight:600;font-size:13px;cursor:pointer;transition:.18s ease;white-space:nowrap;"
                    onmouseover="this.style.background='var(--amber-400)'"
                    onmouseout="this.style.background='var(--amber-500)'">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </form>
    @else
        <div style="text-align:center;padding:.5rem;font-size:13px;color:var(--brown-400);">
            <a href="{{ route('login') }}" style="color:var(--amber-600);font-weight:600;">Connectez-vous</a> pour commenter.
        </div>
    @endauth
</div>