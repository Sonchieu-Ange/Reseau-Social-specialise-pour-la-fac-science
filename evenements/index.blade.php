@extends('layouts.app')
@section('title', 'Événements')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0" style="font-size: 1.4rem;">
        <i class="bi bi-calendar-event me-2"></i>Événements
    </h4>
    @if (in_array(Auth::user()->role, ['enseignant', 'admin']))
        <a href="{{ route('evenements.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
            <i class="bi bi-plus-circle me-1"></i>Créer un événement
        </a>
    @endif
</div>

<div class="row g-3">
    @forelse($evenements as $event)
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">{{ $event->titre }}</h6>
                    @if($event->date_debut->isFuture())
                        <span class="badge bg-success" style="border-radius: 12px; font-weight: 400;">À venir</span>
                    @else
                        <span class="badge bg-secondary" style="border-radius: 12px; font-weight: 400;">Passé</span>
                    @endif
                </div>
                
                <p class="text-muted small mb-2">
                    <i class="bi bi-geo-alt me-1"></i>{{ $event->lieu ?: 'Lieu non défini' }}
                </p>
                <p class="text-muted small mb-2">
                    <i class="bi bi-clock me-1"></i>{{ $event->date_debut->format('d/m/Y H:i') }}
                </p>
                <p class="text-muted small mb-3">
                    <i class="bi bi-person me-1"></i>{{ $event->participants->count() }} participant(s)
                </p>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('evenements.show', $event->_id) }}" class="btn btn-outline-success btn-sm px-3" style="border-radius: 20px;">
                        Détails
                    </a>
                    @if (Auth::id() == $event->createur_id)
                        <a href="{{ route('evenements.edit', $event->_id) }}" class="btn btn-outline-warning btn-sm px-3" style="border-radius: 20px;">
                            Modifier
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-card">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Aucun événement pour le moment.</p>
                @if (in_array(Auth::user()->role, ['enseignant', 'admin']))
                    <a href="{{ route('evenements.create') }}" class="btn btn-success px-4 py-2" style="border-radius: 24px;">
                        Créer un événement
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $evenements->links() }}
</div>

@endsection