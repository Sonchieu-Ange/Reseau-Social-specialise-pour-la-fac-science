@extends('layouts.app')
@section('title', 'Groupes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0" style="font-size: 1.4rem;">
        <i class="bi bi-people-fill me-2"></i>Groupes
    </h4>
    <a href="{{ route('groupes.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
        <i class="bi bi-plus-circle me-1"></i>Créer un groupe
    </a>
</div>

<div class="row g-3">
    @forelse($groupes as $groupe)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-card h-100">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">{{ $groupe->nom }}</h5>
                    @if($groupe->communaute)
                        <span class="badge bg-success" style="border-radius: 12px; font-weight: 400;">
                            {{ $groupe->communaute->nom }}
                        </span>
                    @else
                        <span class="badge bg-light text-dark" style="border-radius: 12px; font-weight: 400;">
                            Groupe indépendant
                        </span>
                    @endif
                </div>
                
                <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">
                    {{ $groupe->description ? Str::limit($groupe->description, 100) : 'Aucune description' }}
                </p>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <div>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            Par {{ $groupe->createur ? $groupe->createur->prenom . ' ' . $groupe->createur->nom : 'Inconnu' }}
                        </small>
                        <div class="small text-muted" style="font-size: 0.7rem;">
                            {{ $groupe->membres->count() }} membre(s)
                        </div>
                    </div>
                    <a href="{{ route('groupes.show', $groupe->_id) }}" class="btn btn-success btn-sm px-3" style="border-radius: 20px;">
                        Voir le groupe
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-card">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Aucun groupe pour le moment.</p>
                <a href="{{ route('groupes.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
                    Créer mon premier groupe
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($groupes->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $groupes->links() }}
</div>
@endif

@endsection