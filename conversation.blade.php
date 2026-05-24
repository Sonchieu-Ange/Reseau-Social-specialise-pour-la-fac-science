@extends('layouts.app')
@section('title', 'Conversation avec ' . $contact->nom . ' ' . $contact->prenom)
@section('content')

<div class="card border-0 shadow-card d-flex flex-column" style="min-height: 70vh; max-height: 80vh;">
    <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center" 
                 style="width:36px;height:36px;font-weight:600;font-size:0.9rem;">
                {{ substr($contact->prenom, 0, 1) }}{{ substr($contact->nom, 0, 1) }}
            </div>
            <span>{{ $contact->nom }} {{ $contact->prenom }}</span>
        </div>
        <a href="{{ route('messages.index') }}" class="btn btn-light btn-sm px-3" style="border-radius: 20px;">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>
    <div class="card-body d-flex flex-column flex-grow-1 p-3" style="overflow-y: auto; background-color: #f8f9fa;">
        <div class="flex-grow-1">
            @forelse($messages as $msg)
                <div class="mb-3 d-flex {{ $msg->expediteur_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                    @if($msg->expediteur_id != Auth::id())
                        <div class="flex-shrink-0 me-2">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                 style="width:32px;height:32px;font-weight:600;font-size:0.8rem;">
                                {{ substr($contact->prenom, 0, 1) }}{{ substr($contact->nom, 0, 1) }}
                            </div>
                        </div>
                    @endif
                    <div class="{{ $msg->expediteur_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark' }} rounded-3 p-2 shadow-sm" 
                         style="max-width: 75%; border-radius: 16px !important; padding: 0.5rem 0.8rem !important;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <strong class="small">{{ $msg->expediteur_id == Auth::id() ? 'Vous' : $contact->prenom }}</strong>
                            
                            @if($msg->expediteur_id == Auth::id())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link {{ $msg->expediteur_id == Auth::id() ? 'text-white-50' : 'text-muted' }} p-0 ms-2" 
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                               onclick="showEditForm('{{ $msg->_id }}')">
                                                <i class="bi bi-pencil me-2"></i>Modifier
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="confirmDeleteMessage('{{ $msg->_id }}')">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        
                        <p class="mb-0 small message-content" id="content-{{ $msg->_id }}">{{ $msg->contenu }}</p>
                        
                        @if($msg->expediteur_id == Auth::id())
                            <div class="edit-form mt-2" id="edit-{{ $msg->_id }}" style="display: none;">
                                <form method="POST" action="{{ route('messages.update', $msg->_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="contenu" class="form-control form-control-sm" 
                                               value="{{ $msg->contenu }}" required maxlength="1000">
                                        <button type="submit" class="btn btn-success btn-sm">OK</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                onclick="hideEditForm('{{ $msg->_id }}')">X</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-end align-items-center gap-1 mt-1">
                            <small class="{{ $msg->expediteur_id == Auth::id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.65rem;">
                                {{ $msg->created_at?->format('d/m H:i') }}
                                @if($msg->updated_at && $msg->updated_at != $msg->created_at)
                                    · modifié
                                @endif
                            </small>
                            @if($msg->expediteur_id == Auth::id())
                                <small class="text-white-50" style="font-size: 0.65rem;">✓</small>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="text-muted mb-3" style="font-size: 3rem;">💬</div>
                    <p class="text-muted mb-0">Aucun message. Commencez la conversation !</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer bg-white border-top p-3">
        <form method="POST" action="{{ route('messages.send') }}" class="d-flex gap-2">
            @csrf
            <input type="hidden" name="destinataire_id" value="{{ $contact->_id }}">
            <input type="text" name="contenu" class="form-control" 
                   placeholder="Votre message..." required style="border-radius: 24px; padding: 0.5rem 1rem;">
            <button type="submit" class="btn btn-success px-4" style="border-radius: 24px;">
                <i class="bi bi-send"></i>
            </button>
        </form>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-semibold">Supprimer le message</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Voulez-vous vraiment supprimer ce message ?</p>
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
    
    function confirmDeleteMessage(messageId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteMessageModal'));
        const deleteForm = document.getElementById('deleteMessageForm');
        deleteForm.action = '/messages/' + messageId;
        modal.show();
    }
</script>

@endsection