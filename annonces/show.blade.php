@extends('layouts.app')
@section('title', $annonce->titre)

@section('content')

<div class="card border-0 shadow-card">
    <div class="card-body p-4">
        <h4 class="fw-bold text-success mb-2" style="font-size: 1.5rem;">{{ $annonce->titre }}</h4>
        
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                 style="width:36px;height:36px;font-weight:600;font-size:0.9rem;">
                {{ substr($annonce->createur->prenom, 0, 1) }}{{ substr($annonce->createur->nom, 0, 1) }}
            </div>
            <div>
                <small class="text-muted d-block">
                    Par {{ $annonce->createur->nom }} {{ $annonce->createur->prenom }}
                </small>
                <small class="text-muted" style="font-size: 0.75rem;">
                    {{ $annonce->created_at?->format('d/m/Y') }}
                </small>
            </div>
        </div>
        
        <div class="mb-4" style="line-height: 1.8;">
            {{ $annonce->contenu }}
        </div>
        
        <div class="d-flex gap-2 flex-wrap">
            @if (Auth::id() == $annonce->createur_id || in_array(Auth::user()->role, ['admin']))
                <a href="{{ route('annonces.edit', $annonce->_id) }}" class="btn btn-outline-warning px-3 py-2" style="border-radius: 24px;">
                    <i class="bi bi-pencil me-1"></i>Modifier
                </a>
                <form method="POST" action="{{ route('annonces.destroy', $annonce->_id) }}"
                    onsubmit="return confirm('Supprimer cette annonce ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger px-3 py-2" style="border-radius: 24px;">
                        <i class="bi bi-trash me-1"></i>Supprimer
                    </button>
                </form>
            @endif
            <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 24px;">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>
</div>

@endsection