@extends('layouts.app')
@section('title', 'Nouvelle communauté')

@section('content')
<div class="card border-0 shadow-card">
    <div class="card-body p-4">
        <h4 class="fw-bold text-success mb-4" style="font-size: 1.4rem;">
            <i class="bi bi-people-fill me-2"></i>Créer une communauté
        </h4>
        <form method="POST" action="{{ route('communautes.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                       value="{{ old('nom') }}" required placeholder="Ex: Informatique FS-UN">
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label fw-medium">Description</label>
                <textarea name="description" rows="3" class="form-control" 
                          placeholder="Décrivez le but de cette communauté...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success px-4 py-2"><i class="bi bi-check-lg me-1"></i>Créer</button>
                <a href="{{ route('communautes.index') }}" class="btn btn-outline-secondary px-4 py-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection