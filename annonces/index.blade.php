@extends('layouts.app')
@section('title', 'Annonces')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0" style="font-size: 1.4rem;">
        <i class="bi bi-megaphone me-2"></i>Annonces
    </h4>
    @if (in_array(Auth::user()->role, ['enseignant', 'admin']))
        <a href="{{ route('annonces.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
            <i class="bi bi-plus-circle me-1"></i>Publier une annonce
        </a>
    @endif
</div>

@forelse($annonces as $annonce)
<div class="card border-0 shadow-card mb-3">
    <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">{{ $annonce->titre }}</h5>
        <p class="text-muted small mb-3" style="line-height: 1.6;">
            {{ Str::limit($annonce->contenu, 150) }}
        </p>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                     style="width:32px;height:32px;font-weight:600;font-size:0.8rem;">
                    {{ substr($annonce->createur->prenom, 0, 1) }}{{ substr($annonce->createur->nom, 0, 1) }}
                </div>
                <small class="text-muted">
                    Par {{ $annonce->createur->nom }} {{ $annonce->createur->prenom }} · 
                    {{ $annonce->created_at?->diffForHumans() }}
                </small>
            </div>
            <a href="{{ route('annonces.show', $annonce->_id) }}" class="btn btn-outline-success btn-sm px-3" style="border-radius: 20px;">
                Lire
            </a>
        </div>
    </div>
</div>
@empty
<div class="card border-0 shadow-card">
    <div class="card-body text-center py-5">
        <p class="text-muted mb-3">Aucune annonce pour le moment.</p>
        @if (in_array(Auth::user()->role, ['enseignant', 'admin']))
            <a href="{{ route('annonces.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
                Publier une annonce
            </a>
        @endif
    </div>
</div>
@endforelse

<div class="d-flex justify-content-center mt-4">
    {{ $annonces->links() }}
</div>

@endsection