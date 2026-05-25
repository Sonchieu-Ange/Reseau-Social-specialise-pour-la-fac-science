@extends('layouts.app')
@section('title', 'Modifier un groupe')

@section('content')
<div class="card border-0 shadow-card">
    <div class="card-body p-4">
        <h4 class="fw-bold text-success mb-4" style="font-size: 1.4rem;">
            <i class="bi bi-pencil-square me-2"></i>Modifier le groupe
        </h4>
        <form method="POST" action="{{ route('groupes.update', $groupe->_id) }}">
            @csrf @method('PUT')
            
            <div class="mb-3">
                <label for="nom" class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                <input type="text" 
                       name="nom" 
                       id="nom"
                       class="form-control @error('nom') is-invalid @enderror" 
                       value="{{ old('nom', $groupe->nom) }}" 
                       required
                       maxlength="150"
                       placeholder="Nom du groupe">
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label fw-medium">Description</label>
                <textarea name="description" 
                          id="description"
                          rows="3" 
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Décrivez le but de votre groupe...">{{ old('description', $groupe->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="communaute_id" class="form-label fw-medium">Communauté liée</label>
                <select name="communaute_id" 
                        id="communaute_id" 
                        class="form-select @error('communaute_id') is-invalid @enderror">
                    <option value="">-- Aucune (groupe indépendant) --</option>
                    @foreach($communautes as $communaute)
                        <option value="{{ $communaute->_id }}" 
                                {{ old('communaute_id', $groupe->communaute_id) == $communaute->_id ? 'selected' : '' }}>
                            {{ $communaute->nom }}
                        </option>
                    @endforeach
                </select>
                @error('communaute_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success px-4 py-2">Enregistrer les modifications</button>
                <a href="{{ route('groupes.show', $groupe->_id) }}" class="btn btn-outline-secondary px-4 py-2">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection