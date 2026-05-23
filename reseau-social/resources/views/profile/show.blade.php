@extends('layouts.app')

@section('title', $user->nom . ' · Profil')

@section('content')
<div style="display:flex;flex-direction:column;gap:1.25rem;">

    {{-- ── Profile Hero ── --}}
    <div class="card" style="overflow:hidden;">
        {{-- Cover band --}}
        <div style="height:140px;background:linear-gradient(135deg,var(--brown-800),var(--brown-600),var(--amber-600));position:relative;">
            <div style="position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"60\" height=\"60\"><circle cx=\"30\" cy=\"30\" r=\"20\" fill=\"none\" stroke=\"rgba(255,255,255,.06)\" stroke-width=\"1\"/></svg>') repeat;"></div>
        </div>

        <div style="padding:0 1.5rem 1.5rem;position:relative;">
            {{-- Avatar --}}
            <div style="position:relative;display:inline-block;margin-top:-48px;margin-bottom:.75rem;">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->nom }}"
                         style="width:96px;height:96px;border-radius:50%;border:4px solid var(--white);object-fit:cover;display:block;box-shadow:0 4px 16px rgba(0,0,0,.15);">
                @else
                    @php $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $user->nom), 0, 2))); @endphp
                    <div style="width:96px;height:96px;border-radius:50%;border:4px solid var(--white);background:var(--brown-700);color:var(--amber-300);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:2rem;font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,.15);">
                        {{ $initials }}
                    </div>
                @endif
            </div>

            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                <div>
                    <h1 style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--brown-900);margin-bottom:.3rem;">
                        {{ $user->nom }}
                    </h1>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <span class="role-badge role-{{ $user->role }}" style="font-size:12px;padding:3px 10px;">
                            @if($user->role === 'enseignant') <i class="bi bi-person-workspace"></i> Enseignant
                            @elseif($user->role === 'admin') <i class="bi bi-shield-check"></i> Administrateur
                            @else <i class="bi bi-mortarboard"></i> Étudiant
                            @endif
                        </span>
                        <span style="color:var(--brown-400);font-size:13px;">
                            <i class="bi bi-envelope"></i> {{ $user->email }}
                        </span>
                        <span style="color:var(--brown-400);font-size:13px;">
                            <i class="bi bi-calendar3"></i>
                            Membre depuis {{ $user->date_inscription ? \Carbon\Carbon::parse($user->date_inscription)->format('M Y') : 'N/A' }}
                        </span>
                    </div>

                    @if($user->bio)
                    <p style="margin-top:.75rem;font-size:14px;color:var(--brown-700);line-height:1.6;max-width:520px;">
                        {{ $user->bio }}
                    </p>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                    @auth
                    @if(Auth::user()->_id == $user->_id)
                        <a href="{{ route('profile.edit') }}" class="btn-outline">
                            <i class="bi bi-pencil"></i> Modifier le profil
                        </a>
                    @else
                        <a href="{{ route('messages.show', $user->_id) }}" class="btn-primary">
                            <i class="bi bi-chat-dots"></i> Envoyer un message
                        </a>
                    @endif
                    @endauth
                </div>
            </div>

            {{-- Stats row --}}
            <div style="display:flex;gap:2rem;margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--brown-50);">
                <div style="text-align:center;">
                    <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--brown-900);">
                        {{ $posts->count() ?? 0 }}
                    </div>
                    <div style="font-size:12px;color:var(--brown-400);">Publications</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--brown-900);">
                        {{ $user->groupesMembresCount ?? 0 }}
                    </div>
                    <div style="font-size:12px;color:var(--brown-400);">Groupes</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--brown-900);">
                        {{ $user->eventsCount ?? 0 }}
                    </div>
                    <div style="font-size:12px;color:var(--brown-400);">Événements</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Publications ── --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.85rem;">
            <h2 style="font-family:var(--font-display);font-size:1.15rem;font-weight:700;color:var(--brown-800);">
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