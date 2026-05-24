@extends('layouts.app')
@section('title', $communaute->nom)

@section('content')

<!-- Carte d'informations de la communauté -->
<div class="card border-0 shadow-card mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-8">
                <h4 class="fw-bold text-success mb-2" style="font-size: 1.5rem;">{{ $communaute->nom }}</h4>
                
                @if($communaute->description)
                    <p class="mb-3" style="line-height: 1.6;">{{ $communaute->description }}</p>
                @endif
                
                <p class="text-muted small mb-3">
                    <i class="bi bi-person me-1"></i>Créée par 
                    <a href="{{ route('profil.show', $communaute->createur_id) }}" class="text-success fw-medium text-decoration-none">
                        {{ $communaute->createur->nom }} {{ $communaute->createur->prenom }}
                    </a>
                </p>
                
                @if(Auth::id() == $communaute->createur_id)
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('communautes.edit', $communaute->_id) }}" class="btn btn-outline-warning px-3 py-2" style="border-radius: 24px;">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <form method="POST" action="{{ route('communautes.destroy', $communaute->_id) }}" onsubmit="return confirm('Supprimer cette communauté ?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger px-3 py-2" style="border-radius: 24px;">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Carte des groupes associés -->
<div class="card border-0 shadow-card mb-4">
    <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-people me-2"></i>Groupes ({{ $communaute->groupes->count() }})
        </div>
        @if(Auth::id() == $communaute->createur_id)
            <button type="button" class="btn btn-light btn-sm px-3" data-bs-toggle="modal" data-bs-target="#associerGroupeModal" style="border-radius: 20px;">
                <i class="bi bi-plus-circle me-1"></i>Associer un groupe
            </button>
        @endif
    </div>
    <div class="card-body p-3">
        @if($communaute->groupes->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($communaute->groupes as $groupe)
                    <div class="list-group-item border-0 border-bottom border-light py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-semibold mb-0 text-dark">{{ $groupe->nom }}</h6>
                            <small class="text-muted">{{ Str::limit($groupe->description, 80) }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('groupes.show', $groupe->_id) }}" class="btn btn-outline-success btn-sm px-3" style="border-radius: 20px;">
                                Voir
                            </a>
                            @if(Auth::id() == $communaute->createur_id)
                                <form method="POST" action="{{ route('communautes.desassocierGroupe', ['communaute' => $communaute->_id, 'groupe' => $groupe->_id]) }}" onsubmit="return confirm('Désassocier ce groupe ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm px-3" style="border-radius: 20px;">
                                        Désassocier
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-3">
                <p class="text-muted mb-0">Aucun groupe associé pour le moment.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal pour associer un groupe -->
@if(Auth::id() == $communaute->createur_id)
<div class="modal fade" id="associerGroupeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('communautes.associerGroupe', $communaute->_id) }}">
                @csrf
                <div class="modal-header border-0">
                    <h6 class="modal-title fw-semibold"><i class="bi bi-plus-circle me-2"></i>Associer un groupe existant</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($groupesDisponibles->count() > 0)
                        <div class="mb-3">
                            <label for="groupe_id" class="form-label fw-medium">Sélectionner un groupe</label>
                            <select name="groupe_id" id="groupe_id" class="form-select" required>
                                <option value="">-- Choisir un groupe --</option>
                                @foreach($groupesDisponibles as $groupe)
                                    <option value="{{ $groupe->_id }}">{{ $groupe->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucun groupe disponible à associer.</p>
                        <p class="small text-muted">Tous les groupes sont déjà associés ou vous n'avez pas créé de groupes.</p>
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 20px;">Annuler</button>
                    @if($groupesDisponibles->count() > 0)
                        <button type="submit" class="btn btn-success btn-sm px-3" style="border-radius: 20px;">Associer</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<div class="mt-3">
    <a href="{{ route('communautes.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 20px;">
        <i class="bi bi-arrow-left me-1"></i>Retour aux communautés
    </a>
</div>

@endsection