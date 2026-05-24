@extends('layouts.app')
@section('title', 'Messages')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0" style="font-size: 1.4rem;">
        <i class="bi bi-chat-dots me-2"></i>Conversations
    </h4>
    <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#newConversationModal" style="border-radius: 24px;">
        <i class="bi bi-plus-circle me-1"></i>Nouvelle conversation
    </button>
</div>

<div class="list-group shadow-card rounded-3 overflow-hidden">
    @forelse($contacts as $contact)
        <a href="{{ route('messages.conversation', $contact->_id) }}" 
           class="list-group-item list-group-item-action border-0 border-bottom border-light py-3 px-4 d-flex align-items-center gap-3">
            <div class="flex-shrink-0">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                     style="width:48px;height:48px;font-weight:600;font-size:1.1rem;">
                    {{ substr($contact->prenom, 0, 1) }}{{ substr($contact->nom, 0, 1) }}
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <strong class="fw-semibold">{{ $contact->nom }} {{ $contact->prenom }}</strong>
                    @if($contact->dernier_message)
                        <small class="text-muted" style="font-size: 0.7rem;">
                            {{ $contact->dernier_message->created_at?->diffForHumans() }}
                        </small>
                    @endif
                </div>
                @if($contact->dernier_message)
                    <div class="text-muted small mt-1">
                        @if($contact->dernier_message->expediteur_id == Auth::id())
                            <span class="text-muted">Vous :</span>
                        @endif
                        {{ Str::limit($contact->dernier_message->contenu, 50) }}
                    </div>
                @endif
            </div>
        </a>
    @empty
        <div class="text-center py-5">
            <div class="text-muted mb-3" style="font-size: 3rem;">📭</div>
            <p class="text-muted mb-0">Aucune conversation pour le moment.</p>
        </div>
    @endforelse
</div>

<!-- Modal Nouvelle conversation -->
<div class="modal fade" id="newConversationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-semibold"><i class="bi bi-person-plus me-2"></i>Nouvelle conversation</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="userSearch" class="form-control mb-3" placeholder="Rechercher un utilisateur..." style="border-radius: 24px; padding: 0.5rem 1rem;">
                <div id="userList" class="list-group rounded-3 border-0 shadow-sm">
                    @php
                        $allUsers = \App\Models\Utilisateur::where('_id', '!=', Auth::id())->get();
                    @endphp
                    @foreach($allUsers as $user)
                        <a href="{{ route('messages.conversation', $user->_id) }}" 
                           class="list-group-item list-group-item-action user-item border-0 border-bottom border-light py-2 d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                 style="width:32px;height:32px;font-weight:600;font-size:0.8rem;">
                                {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                            </div>
                            {{ $user->nom }} {{ $user->prenom }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('userSearch').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.user-item').forEach(function(item) {
            let name = item.textContent.toLowerCase();
            item.style.display = name.includes(filter) ? '' : 'none';
        });
    });
</script>

@endsection