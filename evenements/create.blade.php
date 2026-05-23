@extends('layouts.app')
@section('title', 'Créer un événement')

@section('content')
<div class="card border-0 shadow-card">
    <div class="card-body p-4">
        <h4 class="fw-bold text-success mb-4" style="font-size: 1.4rem;">
            <i class="bi bi-plus-circle me-2"></i>Nouvel événement
        </h4>
        <form method="POST" action="{{ route('evenements.store') }}">
            @csrf
            <div class="mb-3">
                <label for="titre" class="form-label fw-medium">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" 
                       value="{{ old('titre') }}" required placeholder="Ex: Soutenance de thèse">
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label fw-medium">Description</label>
                <textarea name="description" rows="3" class="form-control" 
                          placeholder="Décrivez l'événement...">{{ old('description') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date_debut" class="form-label fw-medium">Début <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror" 
                           value="{{ old('date_debut') }}" required>
                    @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_fin" class="form-label fw-medium">Fin</label>
                    <input type="datetime-local" name="date_fin" class="form-control" 
                           value="{{ old('date_fin') }}">
                </div>
            </div>
            <div class="mb-3">
                <label for="lieu" class="form-label fw-medium">Lieu</label>
                <input type="text" name="lieu" class="form-control" value="{{ old('lieu') }}" 
                       placeholder="Ex: Amphithéâtre 1, FS-UN">
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success px-4 py-2"><i class="bi bi-check-lg me-1"></i>Créer</button>
                <a href="{{ route('evenements.index') }}" class="btn btn-outline-secondary px-4 py-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection