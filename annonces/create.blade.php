@extends('layouts.app')
@section('title', 'Nouvelle annonce')

@section('content')
<div class="card border-0 shadow-card">
    <div class="card-body p-4">
        <h4 class="fw-bold text-success mb-4" style="font-size: 1.4rem;">
            <i class="bi bi-megaphone me-2"></i>Publier une annonce
        </h4>
        <form method="POST" action="{{ route('annonces.store') }}">
            @csrf
            <div class="mb-3">
                <label for="titre" class="form-label fw-medium">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" 
                       value="{{ old('titre') }}" required placeholder="Ex: Inscription aux examens">
                @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="contenu" class="form-label fw-medium">Contenu <span class="text-danger">*</span></label>
                <textarea name="contenu" rows="5" class="form-control @error('contenu') is-invalid @enderror" 
                          required placeholder="Détaillez votre annonce...">{{ old('contenu') }}</textarea>
                @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success px-4 py-2"><i class="bi bi-check-lg me-1"></i>Publier</button>
                <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary px-4 py-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection