@extends('layouts.app')
@section('title', 'Communautés')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0" style="font-size: 1.4rem;">
        <i class="bi bi-people me-2"></i>Communautés
    </h4>
    <a href="{{ route('communautes.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
        <i class="bi bi-plus-circle me-1"></i>Créer une communauté
    </a>
</div>

<div class="row g-3">
    @forelse($communautes as $communaute)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">{{ $communaute->nom }}</h6>
                <p class="text-muted small mb-2" style="line-height: 1.6;">
                    {{ Str::limit($communaute->description, 100) }}
                </p>
                <small class="text-muted d-block mb-3">
                    <i class="bi bi-person me-1"></i>Créée par {{ $communaute->createur->prenom }} {{ $communaute->createur->nom }}
                </small>
                <div class="d-flex gap-2">
                    <a href="{{ route('communautes.show', $communaute->_id) }}" class="btn btn-outline-success btn-sm px-3" style="border-radius: 20px;">
                        Voir
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-card">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Aucune communauté pour le moment.</p>
                <a href="{{ route('communautes.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
                    Créer une communauté
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $communautes->links() }}
</div>

@endsection