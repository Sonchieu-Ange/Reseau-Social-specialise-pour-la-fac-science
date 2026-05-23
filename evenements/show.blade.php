@extends('layouts.app')
@section('title', $evenement->titre)

@section('content')

<div class="card border-0 shadow-card mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-8">
                <h4 class="fw-bold text-success mb-3" style="font-size: 1.5rem;">{{ $evenement->titre }}</h4>
                
                @if($evenement->description)
                    <p class="mb-3" style="line-height: 1.6;">{{ $evenement->description }}</p>
                @endif
                
                <div class="mb-3 text-muted small">
                    <p class="mb-1"><i class="bi bi-calendar me-2"></i><strong>Début :</strong> {{ $evenement->date_debut->format('d/m/Y H:i') }}</p>
                    <p class="mb-1"><i class="bi bi-calendar2 me-2"></i><strong>Fin :</strong> {{ $evenement->date_fin ? $evenement->date_fin->format('d/m/Y H:i') : 'Non définie' }}</p>
                    <p class="mb-1"><i class="bi bi-geo-alt me-2"></i><strong>Lieu :</strong> {{ $evenement->lieu ?: 'Non défini' }}</p>
                </div>
                
                <p class="mb-3">
                    <strong>Organisateur :</strong> 
                    <a href="{{ route('profil.show', $evenement->createur_id) }}" class="text-success fw-medium text-decoration-none">
                        {{ $evenement->createur->nom }} {{ $evenement->createur->prenom }}
                    </a>
                </p>
                
                <div class="d-flex gap-2 flex-wrap mb-3">
                    @php
                        $estParticipant = $evenement->participants->contains('utilisateur_id', Auth::id());
                    @endphp
                    
                    @if(!$estParticipant)
                        <form method="POST" action="{{ route('evenements.participate', $evenement->_id) }}">
                            @csrf
                            <button class="btn btn-success px-3 py-2" style="border-radius: 24px;">
                                <i class="bi bi-check-circle me-1"></i>Participer
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('evenements.cancelParticipation', $evenement->_id) }}">
                            @csrf
                            <button class="btn btn-outline-danger px-3 py-2" style="border-radius: 24px;">
                                <i class="bi bi-x-circle me-1"></i>Annuler
                            </button>
                        </form>
                    @endif
                    
                    @if(Auth::id() == $evenement->createur_id)
                        <a href="{{ route('evenements.edit', $evenement->_id) }}" class="btn btn-outline-warning px-3 py-2" style="border-radius: 24px;">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <form method="POST" action="{{ route('evenements.destroy', $evenement->_id) }}" onsubmit="return confirm('Supprimer cet événement ?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger px-3 py-2" style="border-radius: 24px;">
                                <i class="bi bi-trash me-1"></i>Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-card">
    <div class="card-header bg-success text-white fw-bold d-flex align-items-center">
        <i class="bi bi-person me-2"></i>Participants ({{ $evenement->participants->count() }})
    </div>
    <div class="card-body p-3">
        @if($evenement->participants->count() > 0)
            <div class="d-flex flex-wrap gap-2">
                @foreach($evenement->participants as $participation)
                    <a href="{{ route('profil.show', $participation->utilisateur->_id) }}" 
                       class="badge bg-light text-dark px-3 py-2 text-decoration-none rounded-pill border border-light">
                        <div class="d-flex align-items-center gap-1">
                            <span class="text-truncate" style="max-width: 120px;">
                                {{ $participation->utilisateur->nom }} {{ $participation->utilisateur->prenom }}
                            </span>
                            @if($participation->utilisateur_id == $evenement->createur_id)
                                <span class="badge bg-success ms-1" style="border-radius: 12px; font-weight: 400;">Organisateur</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-3">
                <p class="text-muted mb-0">Aucun participant pour le moment.</p>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('evenements.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 20px;">
        <i class="bi bi-arrow-left me-1"></i>Retour aux événements
    </a>
</div>

@endsection