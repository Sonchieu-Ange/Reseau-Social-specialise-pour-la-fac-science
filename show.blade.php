@extends('layouts.app')
@section('title', $groupe->nom)

@section('content')

@php
    $estMembre = $groupe->membres->contains('utilisateur_id', Auth::id());
    $membreActuel = $groupe->membres->firstWhere('utilisateur_id', Auth::id());
    $estAdmin = $membreActuel && $membreActuel->role === 'admin';
    $estCreateur = $groupe->createur_id == Auth::id();
@endphp

<!-- Carte d'informations du groupe -->
<div class="card border-0 shadow-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-start">
            <div class="col-md-8">
                <h4 class="fw-bold text-success mb-2" style="font-size: 1.5rem;">{{ $groupe->nom }}</h4>
                
                @if($groupe->description)
                    <p class="text-muted mb-3" style="line-height: 1.6;">{{ $groupe->description }}</p>
                @endif
                
                @if($groupe->communaute)
                    <span class="badge bg-success mb-2" style="border-radius: 12px; font-weight: 400;">
                        <i class="bi bi-people me-1"></i>{{ $groupe->communaute->nom }}
                    </span>
                @endif
                
                <div class="text-muted small d-flex align-items-center gap-2 flex-wrap">
                    <span>
                        Créé par 
                        <a href="{{ route('profil.show', $groupe->createur_id) }}" class="text-success text-decoration-none fw-medium">
                            {{ $groupe->createur->nom }} {{ $groupe->createur->prenom }}
                        </a>
                    </span>
                    <span>·</span>
                    <span><i class="bi bi-person me-1"></i>{{ $groupe->membres->count() }} membre(s)</span>
                </div>
            </div>
            <div class="col-md-4 text-end mt-3 mt-md-0">
                @auth
                    @if(!$estMembre)
                        <form method="POST" action="{{ route('groupes.join', $groupe->_id) }}">
                            @csrf
                            <button class="btn btn-success px-4 py-2" style="border-radius: 24px;">
                                <i class="bi bi-person-plus me-1"></i>Rejoindre le groupe
                            </button>
                        </form>
                    @else
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            @if($estAdmin || $estCreateur)
                                <a href="{{ route('groupes.edit', $groupe->_id) }}" class="btn btn-outline-success px-3 py-2" style="border-radius: 24px;">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                            @endif
                            
                            @if($estCreateur)
                                <button type="button" class="btn btn-outline-danger px-3 py-2" data-bs-toggle="modal" data-bs-target="#deleteGroupeModal" style="border-radius: 24px;">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            @endif
                            
                            <form method="POST" action="{{ route('groupes.leave', $groupe->_id) }}">
                                @csrf
                                <button class="btn btn-outline-danger px-3 py-2" style="border-radius: 24px;">
                                    <i class="bi bi-box-arrow-right me-1"></i>Quitter
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression du groupe -->
@if($estCreateur)
<div class="modal fade" id="deleteGroupeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer le groupe <strong>"{{ $groupe->nom }}"</strong> ?</p>
                <p class="text-muted small">Cette action est irréversible. Tous les messages et membres seront supprimés.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 20px;">Annuler</button>
                <form method="POST" action="{{ route('groupes.destroy', $groupe->_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-3" style="border-radius: 20px;">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Carte des membres -->
<div class="card border-0 shadow-card mb-4">
    <div class="card-header bg-success text-white fw-bold d-flex align-items-center">
        <i class="bi bi-person me-2"></i>Membres ({{ $groupe->membres->count() }})
    </div>
    <div class="card-body p-3">
        <div class="d-flex flex-wrap gap-2">
            @foreach($groupe->membres as $membre)
                <a href="{{ route('profil.show', $membre->utilisateur->_id) }}" 
                   class="badge bg-light text-dark px-3 py-2 text-decoration-none rounded-pill border border-light">
                    <div class="d-flex align-items-center gap-1">
                        <span class="text-truncate" style="max-width: 120px;">
                            {{ $membre->utilisateur->nom }} {{ $membre->utilisateur->prenom }}
                        </span>
                        @if($membre->role === 'admin')
                            <span class="badge bg-success ms-1" style="border-radius: 12px; font-weight: 400;">admin</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Carte des messages du groupe -->
<div class="card border-0 shadow-card mb-4 d-flex flex-column" style="min-height: 60vh;">
    <div class="card-header bg-success text-white fw-bold d-flex align-items-center">
        <i class="bi bi-chat-square-text me-2"></i>Messages du groupe
    </div>
    <div class="card-body d-flex flex-column flex-grow-1 p-3" style="max-height: 60vh; overflow-y: auto; background-color: #f8f9fa;">
        <div class="flex-grow-1">
            @forelse($groupe->messages as $msg)
                <div class="bg-white rounded-3 p-3 mb-2 shadow-sm" style="border-radius: 12px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <a href="{{ route('profil.show', $msg->auteur_id) }}" class="text-dark fw-semibold text-decoration-none">
                                {{ $msg->auteur->nom }} {{ $msg->auteur->prenom }}
                            </a>
                            @if($msg->modifie_le)
                                <span class="badge bg-secondary small" style="border-radius: 12px; font-weight: 400;">modifié</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $msg->cree_le?->diffForHumans() }}</small>
                            
                            @if($msg->auteur_id == Auth::id() || $estAdmin)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" style="border-radius: 20px;">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                        @if($msg->auteur_id == Auth::id())
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="showEditForm('{{ $msg->_id }}')">
                                                    <i class="bi bi-pencil me-2"></i>Modifier
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $msg->_id }}')">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <p class="mb-0 small message-content" id="content-{{ $msg->_id }}" style="line-height: 1.5;">{{ $msg->contenu }}</p>
                    
                    @if($msg->auteur_id == Auth::id())
                        <div class="edit-form mt-2" id="edit-{{ $msg->_id }}" style="display: none;">
                            <form method="POST" action="{{ route('groupes.messages.update', ['groupe' => $groupe->_id, 'message' => $msg->_id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-sm">
                                    <input type="text" name="contenu" class="form-control" value="{{ $msg->contenu }}" required maxlength="1000">
                                    <button type="submit" class="btn btn-success btn-sm">OK</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="hideEditForm('{{ $msg->_id }}')">X</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <p class="text-muted mb-2">Aucun message pour le moment.</p>
                    @if($estMembre)
                        <p class="small text-muted">Soyez le premier à écrire un message.</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
    
    @auth
        @if($estMembre)
            <div class="card-footer bg-white border-top p-3">
                <form method="POST" action="{{ route('groupes.sendMessage', $groupe->_id) }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="contenu" class="form-control @error('contenu') is-invalid @enderror" 
                           placeholder="Votre message..." required maxlength="1000" style="border-radius: 24px; padding: 0.5rem 1rem;">
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 24px;">
                        Envoyer
                    </button>
                </form>
                @error('contenu')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>
        @endif
    @endauth
</div>

<!-- Modal de suppression de message -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-semibold text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Supprimer le message
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Voulez-vous vraiment supprimer ce message ?</p>
                <p class="text-muted small mt-1">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 20px;">Annuler</button>
                <form id="deleteMessageForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-3" style="border-radius: 20px;">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showEditForm(messageId) {
        document.getElementById('content-' + messageId).style.display = 'none';
        document.getElementById('edit-' + messageId).style.display = 'block';
    }
    
    function hideEditForm(messageId) {
        document.getElementById('content-' + messageId).style.display = 'block';
        document.getElementById('edit-' + messageId).style.display = 'none';
    }
    
    function confirmDelete(messageId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteMessageModal'));
        const deleteForm = document.getElementById('deleteMessageForm');
        deleteForm.action = `/groupes/{{ $groupe->_id }}/messages/${messageId}`;
        modal.show();
    }
</script>

@endsection