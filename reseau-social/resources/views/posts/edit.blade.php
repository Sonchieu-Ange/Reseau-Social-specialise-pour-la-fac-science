@extends('layouts.app')

@section('title', 'Modifier une publication')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">

    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                        SECTION HEADER                                   ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('posts.show', $post->id ?? $post->_id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--brown-600); font-weight: 500; margin-bottom: 1rem; text-decoration: none; transition: color var(--transition);">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <h1 style="font-family: var(--font-display); font-size: 2rem; color: var(--brown-950); margin: 0;">
            <i class="bi bi-pencil-square" style="color: var(--amber-500); margin-right: 0.5rem;"></i>
            Modifier la publication
        </h1>
        <p style="color: var(--brown-600); margin-top: 0.5rem;">Mettez à jour votre publication</p>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                      AFFICHE LES ERREURS                                ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    @if ($errors->any())
        <div style="background: #fee2e2; border: 2px solid #fecaca; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <i class="bi bi-exclamation-circle" style="color: #dc2626; font-size: 1.25rem; flex-shrink: 0; margin-top: 0.25rem;"></i>
                <div>
                    <h3 style="color: #991b1b; font-weight: 600; margin: 0 0 0.5rem 0;">Erreurs de validation</h3>
                    <ul style="color: #7f1d1d; list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li style="padding: 0.25rem 0;">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                    FORMULAIRE D'ÉDITION                                 ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <form action="{{ route('posts.update', $post->id ?? $post->_id) }}" method="POST" enctype="multipart/form-data" id="editPostForm" style="background: white; border: 2px solid var(--brown-100); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-md);">
        @csrf @method('PUT')

        {{-- Zone de texte principale --}}
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; color: var(--brown-700); margin-bottom: 0.75rem;">Contenu</label>
            <textarea 
                name="content"
                id="postContent"
                placeholder="Modifiez votre publication..."
                style="width: 100%; min-height: 150px; padding: 1.25rem; background: var(--cream); border: 2px solid var(--brown-100); border-radius: var(--radius-lg); color: var(--brown-900); font-family: var(--font-body); font-size: 15px; resize: vertical; transition: all var(--transition);"
                onfocus="this.style.borderColor='var(--amber-300)'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                onblur="this.style.borderColor='var(--brown-100)'; this.style.boxShadow='none';">{{ $post->content ?? $post->contenu }}</textarea>
            
            @error('content')
                <div style="color: #dc2626; font-size: 14px; margin-top: 0.5rem;">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror

            <div style="color: var(--brown-500); font-size: 13px; margin-top: 0.5rem; text-align: right;">
                <span id="charCount">{{ strlen($post->content ?? $post->contenu) }}</span> / 5000 caractères
            </div>
        </div>

        {{-- Note sur les médias --}}
        <div style="margin-bottom: 2rem; padding: 1rem; background: var(--amber-50); border: 1px solid var(--amber-200); border-radius: var(--radius-md); border-left: 4px solid var(--amber-500);">
            <p style="margin: 0; color: var(--brown-700); font-size: 14px;">
                <i class="bi bi-info-circle"></i> <strong>Note :</strong> Les médias ne peuvent pas être modifiés lors de l'édition. Supprimez et recréez la publication si vous avez besoin de changer les médias.
            </p>
        </div>

        {{-- Actions --}}
        <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--brown-50);">
            <a href="{{ route('posts.show', $post->id ?? $post->_id) }}" 
               style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; border: 2px solid var(--brown-200); color: var(--brown-600); border-radius: var(--radius-lg); font-weight: 600; text-decoration: none; transition: all var(--transition); cursor: pointer;"
               onmouseover="this.style.borderColor='var(--brown-400)'; this.style.background='var(--brown-50)';"
               onmouseout="this.style.borderColor='var(--brown-200)'; this.style.background='white';">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
            <button 
                type="submit" 
                id="submitBtn"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 2rem; background: linear-gradient(135deg, var(--amber-500), var(--amber-600)); color: white; border: none; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; transition: all var(--transition); box-shadow: var(--shadow-md);"
                onmouseover="this.style.boxShadow='var(--shadow-lg)';"
                onmouseout="this.style.boxShadow='var(--shadow-md)';">
                <i class="bi bi-save"></i> Enregistrer les modifications
            </button>
        </div>
    </form>

</div>

<style>
    #postContent {
        font-size: 15px;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        form {
            padding: 1.5rem !important;
        }

        h1 {
            font-size: 1.5rem !important;
        }
    }
</style>

<script>
    // Compteur de caractères
    const textarea = document.getElementById('postContent');
    const charCount = document.getElementById('charCount');

    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        if (this.value.length > 5000) {
            this.value = this.value.substring(0, 5000);
            charCount.textContent = 5000;
        }
    });

    // Désactiver le bouton pendant la soumission
    document.getElementById('editPostForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
        submitBtn.style.cursor = 'not-allowed';
    });
</script>
@endsection
