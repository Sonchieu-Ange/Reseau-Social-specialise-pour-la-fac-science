@extends('layouts.app')

@section('title', 'Créer une publication')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">

    {{-- ═══════════════════════════════════════════════════════════════════════════
        ║                        SECTION HEADER                                   ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--brown-600); font-weight: 500; margin-bottom: 1rem; text-decoration: none; transition: color var(--transition);">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <h1 style="font-family: var(--font-display); font-size: 2rem; color: var(--brown-950); margin: 0;">
            <i class="bi bi-pencil-square" style="color: var(--amber-500); margin-right: 0.5rem;"></i>
            Partager une publication
        </h1>
        <p style="color: var(--brown-600); margin-top: 0.5rem;">Exprimez-vous auprès de la communauté de la Faculté des Sciences</p>
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
        ║                    FORMULAIRE DE CRÉATION                               ║
        ════════════════════════════════════════════════════════════════════════════ --}}
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm" style="background: white; border: 2px solid var(--brown-100); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-md);">
        @csrf

        {{-- Profil Utilisateur --}}
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--brown-50);">
            @php
                $user = Auth::user();
                $avatar = $user->avatar ?? null;
                $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $user->nom ?? 'U'), 0, 2)));
            @endphp

            @if($avatar)
                <img src="{{ $avatar }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 3px solid var(--amber-300);">
            @else
                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--amber-200), var(--brown-200)); display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--brown-700); border: 3px solid var(--amber-300);">
                    {{ $initials }}
                </div>
            @endif

            <div>
                <div style="font-weight: 600; color: var(--brown-900); font-size: 15px;">{{ $user->nom }}</div>
                <div style="font-size: 14px; color: var(--brown-600);">Partage publique</div>
            </div>
        </div>

        {{-- Zone de texte principale --}}
        <div style="margin-bottom: 2rem;">
            <textarea 
                name="content"
                id="postContent"
                placeholder="À quoi penses-tu ? Partage tes réflexions, tes nouvelles ou tes questions..."
                style="width: 100%; min-height: 150px; padding: 1.25rem; background: var(--cream); border: 2px solid var(--brown-100); border-radius: var(--radius-lg); color: var(--brown-900); font-family: var(--font-body); font-size: 15px; resize: vertical; transition: all var(--transition);"
                onfocus="this.style.borderColor='var(--amber-300)'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.1)';"
                onblur="this.style.borderColor='var(--brown-100)'; this.style.boxShadow='none';">{{ old('content') }}</textarea>
            
            @error('content')
                <div style="color: #dc2626; font-size: 14px; margin-top: 0.5rem;">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror

            <div style="color: var(--brown-500); font-size: 13px; margin-top: 0.5rem; text-align: right;">
                <span id="charCount">0</span> / 5000 caractères
            </div>
        </div>

        {{-- Section Upload Médias --}}
        <div style="margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(165, 89, 48, 0.05)); border: 2px dashed var(--amber-300); border-radius: var(--radius-lg);">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; color: var(--brown-700); margin-bottom: 0.75rem;">
                    <i class="bi bi-image"></i> Ajouter des médias
                </label>
                <p style="color: var(--brown-600); font-size: 14px; margin: 0;">
                    Image, vidéo ou document (Max 10 MB chacun)
                </p>
            </div>

            <input 
                type="file" 
                name="media[]" 
                id="mediaInput" 
                multiple 
                accept="image/*,video/*,.pdf,.doc,.docx"
                style="display: none;">

            <button 
                type="button" 
                onclick="document.getElementById('mediaInput').click();"
                style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; border: 2px solid var(--amber-400); color: var(--amber-600); border-radius: var(--radius-md); font-weight: 600; cursor: pointer; transition: all var(--transition);"
                onmouseover="this.style.background='var(--amber-50)'; this.style.borderColor='var(--amber-500)';"
                onmouseout="this.style.background='white'; this.style.borderColor='var(--amber-400)';">
                <i class="bi bi-cloud-arrow-up"></i> Choisir fichiers
            </button>

            {{-- Prévisualisation des médias --}}
            <div id="mediaPreview" style="margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem;"></div>
        </div>

        {{-- Options de Confidentialité --}}
        <div style="margin-bottom: 2rem; padding: 1rem; background: var(--brown-50); border-radius: var(--radius-md); border-left: 4px solid var(--amber-500);">
            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; font-weight: 500; color: var(--brown-700);">
                <i class="bi bi-globe"></i>
                <span>Visible à tous les utilisateurs</span>
            </label>
            <p style="color: var(--brown-600); font-size: 13px; margin: 0.5rem 0 0 2rem;">Ta publication sera affichée sur le fil d'actualité public</p>
        </div>

        {{-- Actions --}}
        <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--brown-50);">
            <a href="{{ route('home') }}" 
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
                <i class="bi bi-send-fill"></i> Publier
            </button>
        </div>
    </form>

</div>

<style>
    /* Gestion dynamique du compteur de caractères */
    #postContent {
        font-size: 15px;
        line-height: 1.6;
    }

    #mediaPreview img, #mediaPreview video {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: transform var(--transition);
    }

    #mediaPreview img:hover, #mediaPreview video:hover {
        transform: scale(1.05);
    }

    .media-item {
        position: relative;
    }

    .media-remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: background var(--transition);
    }

    .media-remove-btn:hover {
        background: #b91c1c;
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

    // Gestion du upload de médias avec prévisualisation
    const mediaInput = document.getElementById('mediaInput');
    const mediaPreview = document.getElementById('mediaPreview');

    mediaInput.addEventListener('change', function() {
        mediaPreview.innerHTML = '';
        const files = Array.from(this.files);

        if (files.length > 5) {
            alert('Maximum 5 fichiers autorisés');
            this.value = '';
            return;
        }

        files.forEach((file, index) => {
            if (file.size > 10 * 1024 * 1024) {
                alert(`Le fichier "${file.name}" dépasse 10 MB`);
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const isImage = file.type.startsWith('image/');
                const isVideo = file.type.startsWith('video/');

                let preview;
                if (isImage) {
                    preview = document.createElement('img');
                    preview.src = e.target.result;
                } else if (isVideo) {
                    preview = document.createElement('video');
                    preview.src = e.target.result;
                    preview.style.objectFit = 'cover';
                } else {
                    preview = document.createElement('div');
                    preview.style.cssText = 'width: 100%; height: 100px; background: var(--brown-50); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--brown-600); font-weight: 600;';
                    preview.textContent = '📄 ' + file.name.split('.').pop().toUpperCase();
                }

                const container = document.createElement('div');
                container.className = 'media-item';
                container.style.position = 'relative';
                container.appendChild(preview);

                const removeBtn = document.createElement('button');
                removeBtn.className = 'media-remove-btn';
                removeBtn.type = 'button';
                removeBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
                removeBtn.onclick = function(e) {
                    e.preventDefault();
                    container.remove();
                    mediaInput.value = '';
                };
                container.appendChild(removeBtn);

                mediaPreview.appendChild(container);
            };
            reader.readAsDataURL(file);
        });
    });

    // Désactiver le bouton pendant la soumission
    document.getElementById('createPostForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
        submitBtn.style.cursor = 'not-allowed';
    });
</script>
@endsection