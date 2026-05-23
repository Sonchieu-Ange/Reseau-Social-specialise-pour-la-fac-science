@extends('layouts.app')

@section('title', 'Publication')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">

    {{-- Header Navigation --}}
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--brown-600); font-weight: 500; text-decoration: none; transition: color var(--transition);">
            <i class="bi bi-arrow-left"></i> Retour au fil
        </a>
    </div>

    {{-- Post Detail --}}
    @if($post)
        @include('partials._post_card', ['post' => $post])

        {{-- ─── SECTION COMMENTAIRES ─── --}}
        <div style="margin-top: 2rem; background: white; border: 1px solid var(--brown-100); border-radius: var(--radius-lg); padding: 1.5rem;">
            <h3 style="font-family: var(--font-display); font-size: 1.25rem; color: var(--brown-900); margin-bottom: 1.5rem;">
                <i class="bi bi-chat-dots"></i> Commentaires
            </h3>

            {{-- Add Comment Form --}}
            @auth
                <form action="{{ route('comments.store', $post->id ?? $post->_id) }}" method="POST" style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--brown-50);">
                    @csrf
                    
                    <div style="display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1rem;">
                        @php
                            $user = Auth::user();
                            $avatar = $user->avatar ?? null;
                            $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $user->nom ?? 'U'), 0, 2)));
                        @endphp

                        @if($avatar)
                            <img src="{{ $avatar }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--amber-300);">
                        @else
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--amber-200), var(--brown-200)); display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--brown-700); font-size: 12px;">
                                {{ $initials }}
                            </div>
                        @endif

                        <div style="flex: 1;">
                            <textarea 
                                name="content" 
                                placeholder="Ajouter un commentaire..." 
                                style="width: 100%; padding: 0.75rem 1rem; background: var(--cream); border: 2px solid var(--brown-100); border-radius: var(--radius-lg); color: var(--brown-900); font-family: var(--font-body); font-size: 14px; resize: vertical; min-height: 80px; transition: all var(--transition);"
                                onfocus="this.style.borderColor='var(--amber-300)'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                                onblur="this.style.borderColor='var(--brown-100)'; this.style.boxShadow='none';"
                                required></textarea>
                            
                            @error('content')
                                <div style="color: #dc2626; font-size: 13px; margin-top: 0.5rem;">{{ $message }}</div>
                            @enderror

                            <button 
                                type="submit" 
                                style="display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 0.75rem; padding: 0.5rem 1rem; background: var(--amber-500); color: white; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; transition: all var(--transition);"
                                onmouseover="this.style.background='var(--amber-600)'; this.style.boxShadow='var(--shadow-md)';"
                                onmouseout="this.style.background='var(--amber-500)'; this.style.boxShadow='none';">
                                <i class="bi bi-send"></i> Commenter
                            </button>
                        </div>
                    </div>
                </form>
            @endauth

            {{-- Comments List --}}
            @php
                $comments = $post->comments ?? [];
            @endphp

            @if(count($comments) > 0)
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @foreach($comments as $comment)
                        <div style="padding: 1rem; background: var(--brown-50); border-radius: var(--radius-md); border-left: 3px solid var(--amber-300);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                <div>
                                    @php
                                        $commentAuthor = $comment->user ?? null;
                                        $commentAuthorName = $commentAuthor->nom ?? 'Utilisateur';
                                        $commentAuthorId = $commentAuthor->_id ?? $commentAuthor->id ?? '#';
                                    @endphp
                                    <a href="{{ route('profile.show', $commentAuthorId) }}" style="font-weight: 600; color: var(--brown-900); text-decoration: none;">
                                        {{ $commentAuthorName }}
                                    </a>
                                    <div style="font-size: 12px; color: var(--brown-600); margin-top: 0.25rem;">
                                        {{ isset($comment->created_at) ? $comment->created_at->diffForHumans() : 'maintenant' }}
                                    </div>
                                </div>

                                @auth
                                    @if(Auth::id() == $commentAuthorId || Auth::user()->role === 'admin')
                                        <form action="{{ route('comments.destroy', $comment->id ?? $comment->_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 16px;" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>

                            <p style="margin: 0; color: var(--brown-800); line-height: 1.5; word-break: break-word;">
                                {{ $comment->content ?? $comment->contenu }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem; color: var(--brown-600);">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💭</div>
                    <p>Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                </div>
            @endif
        </div>
    @else
        <div style="text-align: center; padding: 3rem; background: white; border: 1px solid var(--brown-100); border-radius: var(--radius-lg);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">😕</div>
            <h2 style="font-family: var(--font-display); color: var(--brown-900); margin-bottom: 0.5rem;">Publication non trouvée</h2>
            <p style="color: var(--brown-600); margin-bottom: 1.5rem;">La publication que vous cherchez n'existe pas ou a été supprimée.</p>
            <a href="{{ route('home') }}" class="btn-primary">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    @endif

</div>

<style>
    @media (max-width: 768px) {
        .post-card {
            padding: 1rem !important;
        }
    }
</style>
@endsection
                <i class="bi bi-grid-3x3-gap" style="color:var(--amber-500);"></i> Publications
            </h2>
            <span style="font-size:13px;color:var(--brown-400);">{{ $posts->count() }} publication(s)</span>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem;">
            @forelse($posts as $post)
                @include('partials._post_card', ['post' => $post])
            @empty
                <div style="text-align:center;padding:2.5rem;background:var(--white);border:1px solid var(--brown-100);border-radius:var(--radius-lg);">
                    <i class="bi bi-file-earmark-x" style="font-size:2.5rem;color:var(--brown-200);display:block;margin-bottom:.75rem;"></i>
                    <p style="color:var(--brown-400);font-size:14px;">Aucune publication pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection