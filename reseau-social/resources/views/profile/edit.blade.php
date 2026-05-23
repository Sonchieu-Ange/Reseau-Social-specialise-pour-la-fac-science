@extends('layouts.app')

@section('title', 'Modifier le profil')

@section('content')
<div style="max-width:680px;margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-family:var(--font-display);font-size:1.7rem;color:var(--brown-900);">Modifier mon profil</h1>
        <p style="color:var(--brown-400);font-size:14px;">Mettez à jour vos informations personnelles</p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Avatar section --}}
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="card-header-ph"><h6>Photo de profil</h6></div>
            <div class="card-body-ph">
                <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" id="avatar-preview"
                             style="width:80px;height:80px;border-radius:50%;border:3px solid var(--amber-300);object-fit:cover;">
                    @else
                        @php $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', Auth::user()->nom), 0, 2))); @endphp
                        <div id="avatar-preview"
                             style="width:80px;height:80px;border-radius:50%;background:var(--brown-700);color:var(--amber-300);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.75rem;font-weight:700;border:3px solid var(--amber-300);">
                            {{ $initials }}
                        </div>
                    @endif

                    <div>
                        <label class="btn-outline" style="cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;">
                            <i class="bi bi-camera"></i> Changer la photo
                            <input type="file" name="avatar" id="avatar-input" accept="image/*" style="display:none;"
                                   onchange="previewAvatar(event)">
                        </label>
                        <p style="font-size:12px;color:var(--brown-400);margin-top:.4rem;">
                            JPG, PNG ou GIF · Max 2 Mo
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal info --}}
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="card-header-ph"><h6>Informations personnelles</h6></div>
            <div class="card-body-ph">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div style="grid-column:1/-1;">
                        <label class="form-label-ph" for="nom">Nom complet</label>
                        <input type="text" id="nom" name="nom"
                               class="form-control-ph {{ $errors->has('nom') ? 'is-invalid' : '' }}"
                               value="{{ old('nom', Auth::user()->nom) }}" required>
                        @error('nom') <div style="font-size:12px;color:#DC2626;margin-top:.3rem;">{{ $message }}</div> @enderror
                    </div>

                    <div style="grid-column:1/-1;">
                        <label class="form-label-ph" for="email">Email</label>
                        <input type="email" id="email" name="email"
                               class="form-control-ph {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email') <div style="font-size:12px;color:#DC2626;margin-top:.3rem;">{{ $message }}</div> @enderror
                    </div>

                    <div style="grid-column:1/-1;">
                        <label class="form-label-ph" for="bio">Biographie</label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="form-control-ph {{ $errors->has('bio') ? 'is-invalid' : '' }}"
                                  placeholder="Décrivez-vous en quelques mots…">{{ old('bio', Auth::user()->bio) }}</textarea>
                        <div style="font-size:11.5px;color:var(--brown-400);margin-top:.3rem;">
                            <span id="bio-count">{{ strlen(Auth::user()->bio ?? '') }}</span>/280 caractères
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Password change --}}
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header-ph"><h6>Changer le mot de passe</h6></div>
            <div class="card-body-ph">
                <p style="font-size:13px;color:var(--brown-400);margin-bottom:1rem;">
                    Laissez vide si vous ne souhaitez pas changer votre mot de passe.
                </p>
                <div style="display:flex;flex-direction:column;gap:.85rem;">
                    <div>
                        <label class="form-label-ph">Mot de passe actuel</label>
                        <input type="password" name="current_password"
                               class="form-control-ph {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label class="form-label-ph">Nouveau mot de passe</label>
                        <input type="password" name="password"
                               class="form-control-ph {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="Minimum 8 caractères">
                    </div>
                    <div>
                        <label class="form-label-ph">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation"
                               class="form-control-ph"
                               placeholder="Répétez le mot de passe">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <a href="{{ route('profile.show', Auth::user()->_id) }}" class="btn-outline">
                Annuler
            </a>
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatar-preview');
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.id = 'avatar-preview';
            img.style = 'width:80px;height:80px;border-radius:50%;border:3px solid var(--amber-300);object-fit:cover;';
            preview.replaceWith(img);
        }
    };
    reader.readAsDataURL(file);
}

document.getElementById('bio').addEventListener('input', function() {
    document.getElementById('bio-count').textContent = this.value.length;
});
</script>
@endpush
@endsection