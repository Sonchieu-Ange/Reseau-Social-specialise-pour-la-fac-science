{{-- Post Card Partial - Moderne et Responsive --}}
<article class="post-card" style="margin-bottom:1.5rem;">
    {{-- ─── HEADER POST ─── --}}
    <div class="post-header">
        @php
            $user = $post->user ?? null;
            $userName = $user->nom ?? 'Utilisateur';
            $userAvatar = $user->avatar ?? null;
            $userRole = $user->role ?? 'etudiant';
            $userId = $user->_id ?? $user->id ?? '#';
            $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $userName), 0, 2)));
            $postTime = $post->created_at ? $post->created_at->diffForHumans() : '—';
        @endphp

        <div class="post-author-info">
            @if($userAvatar)
                <img src="{{ $userAvatar }}" class="avatar" style="width:44px;height:44px;border:2px solid var(--amber-300);">
            @else
                <div class="avatar-placeholder" style="width:44px;height:44px;font-size:13px;">
                    {{ $initials }}
                </div>
            @endif

            <div>
                <div style="font-weight:600;color:var(--brown-900);font-size:15px;">
                    <a href="{{ route('profile.show', $userId) }}" style="color:inherit;text-decoration:none;">
                        {{ $userName }}
                    </a>
                </div>
                <div class="post-meta" style="display:flex;gap:0.5rem;align-items:center;">
                    <i class="bi bi-clock" style="font-size:12px;"></i>
                    {{ $postTime }}
                </div>
            </div>
        </div>

        {{-- Menu Actions --}}
        <div style="display:flex;gap:0.5rem;">
            @if(Auth::check() && (Auth::id() == $userId || Auth::user()->role === 'admin'))
                <a href="{{ route('posts.edit', $post->id ?? $post->_id) }}" class="post-action-btn" style="padding:0.5rem;" title="Modifier">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('posts.destroy', $post->id ?? $post->_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette publication ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="post-action-btn" style="padding:0.5rem;border:none;background:none;cursor:pointer;" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ─── CONTENU TEXT ─── --}}
    <div class="post-content">
        <p style="margin:0;white-space:pre-wrap;word-break:break-word;">
            {{ \Illuminate\Support\Str::limit($post->content ?? $post->contenu, 500) }}
        </p>
    </div>

    {{-- ─── MÉDIAS ─── --}}
    @if(($post->media && is_array($post->media) && count($post->media) > 0) || $post->url_media)
        <div style="margin-top:1rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:0.75rem;">
            @php
                $mediaItems = $post->media ?? ($post->url_media ? [$post->url_media] : []);
                if (!is_array($mediaItems)) $mediaItems = [];
            @endphp
            
            @foreach($mediaItems as $mediaFile)
                @php
                    $ext = strtolower(pathinfo($mediaFile, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $isVideo = in_array($ext, ['mp4', 'webm', 'mov']);
                @endphp
                @if($isImage)
                    <img src="{{ asset('storage/' . $mediaFile) }}" style="width:100%;border-radius:var(--radius-lg);max-height:300px;object-fit:cover;" loading="lazy">
                @elseif($isVideo)
                    <video style="width:100%;border-radius:var(--radius-lg);max-height:300px;object-fit:cover;" controls>
                        <source src="{{ asset('storage/' . $mediaFile) }}">
                    </video>
                @else
                    <a href="{{ asset('storage/' . $mediaFile) }}" target="_blank" style="display:flex;align-items:center;gap:0.75rem;padding:1rem;background:var(--brown-50);border:1px solid var(--brown-200);border-radius:var(--radius-md);text-decoration:none;color:var(--brown-700);font-weight:500;transition:all var(--transition);" onmouseover="this.style.background='var(--amber-50)';this.style.borderColor='var(--amber-300)';" onmouseout="this.style.background='var(--brown-50)';this.style.borderColor='var(--brown-200)';">
                        <i class="bi bi-file-earmark"></i>
                        <span>{{ pathinfo($mediaFile, PATHINFO_FILENAME) }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    @endif

    {{-- ─── ACTIONS ─── --}}
    <div class="post-actions">
        @php
            $likesCount = isset($post->likes) && is_array($post->likes) ? count($post->likes) : 0;
            $isLiked = Auth::check() && isset($post->likes) && is_array($post->likes) && in_array(Auth::id(), $post->likes);
        @endphp

        {{-- Like Button --}}
        @if(Auth::check())
            <button onclick="toggleLike(this, '{{ $post->id ?? $post->_id }}')" 
                    class="post-action-btn" 
                    style="color:{{ $isLiked ? 'var(--amber-600)' : 'var(--brown-600)' }};"
                    title="{{ $isLiked ? 'Retirer le like' : 'J\'aime' }}">
                <i class="bi bi-hand-thumbs-up{{ $isLiked ? '-fill' : '' }}"></i>
                <span>{{ $likesCount > 0 ? $likesCount : '' }} J'aime</span>
            </button>
        @endif

        {{-- Comment Button --}}
        <button class="post-action-btn" onclick="toggleComments('{{ $post->id ?? $post->_id }}');" title="Commenter">
            <i class="bi bi-chat"></i>
            <span>{{ isset($post->comments) && is_array($post->comments) ? count($post->comments) : 0 }} Commentaires</span>
        </button>

        {{-- Share Button --}}
        <button class="post-action-btn" title="Partager" onclick="sharePost('{{ route('posts.show', $post->id ?? $post->_id) }}');">
            <i class="bi bi-share"></i>
            <span>Partager</span>
        </button>

        {{-- View Post Link --}}
        <a href="{{ route('posts.show', $post->id ?? $post->_id) }}" class="post-action-btn" title="Afficher le post complet" style="margin-left:auto;">
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    {{-- ─── SECTION COMMENTAIRES ─── --}}
    <div id="comments-{{ $post->id ?? $post->_id }}" style="display:none;border-top:1px solid var(--brown-50);">
        @include('partials._comment_section', ['post' => $post])
    </div>
</article>

<script>
function toggleLike(btn, postId) {
    fetch(`/posts/${postId}/toggle-like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const icon = btn.querySelector('i');
        icon.classList.toggle('bi-hand-thumbs-up');
        icon.classList.toggle('bi-hand-thumbs-up-fill');
        btn.querySelector('span').textContent = (data.likes > 0 ? data.likes : '') + ' J\'aime';
        btn.style.color = data.liked ? 'var(--amber-600)' : 'var(--brown-600)';
    })
    .catch(err => console.error('Erreur:', err));
}

function toggleComments(postId) {
    const el = document.getElementById('comments-' + postId);
    if (el) {
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
}

function sharePost(url) {
    if (navigator.share) {
        navigator.share({ url: url });
    } else {
        const el = document.createElement('input');
        el.value = url;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert('Lien copié !');
    }
}
</script>