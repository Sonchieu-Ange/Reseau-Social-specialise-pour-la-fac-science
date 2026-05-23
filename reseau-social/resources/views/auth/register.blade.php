@extends('layouts.auth')

@section('title', 'Inscription')

@section('auth-content')
<h2 class="auth-form-title">Rejoindre Phoenix</h2>
<p class="auth-form-subtitle">Créez votre compte étudiant ou enseignant</p>

@if($errors->any())
<div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:13.5px;color:#991B1B;display:flex;gap:.6rem;">
    <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;margin-top:1px;"></i>
    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Role selector --}}
    <div style="margin-bottom:1.1rem;">
        <label class="form-label-ph">Je suis…</label>
        <div class="role-selector">
            <label class="role-option {{ old('role') === 'etudiant' || !old('role') ? 'selected' : '' }}" id="opt-etudiant">
                <input type="radio" name="role" value="etudiant"
                       {{ old('role', 'etudiant') === 'etudiant' ? 'checked' : '' }}
                       onchange="selectRole('etudiant')">
                <i class="bi bi-mortarboard"></i>
                Étudiant
            </label>
            <label class="role-option {{ old('role') === 'enseignant' ? 'selected' : '' }}" id="opt-enseignant">
                <input type="radio" name="role" value="enseignant"
                       {{ old('role') === 'enseignant' ? 'checked' : '' }}
                       onchange="selectRole('enseignant')">
                <i class="bi bi-person-workspace"></i>
                Enseignant
            </label>
        </div>
    </div>

    {{-- Name --}}
    <div class="form-group-ph">
        <label class="form-label-ph" for="nom">
            <i class="bi bi-person"></i> Nom complet
        </label>
        <input type="text" id="nom" name="nom"
               class="form-control-ph {{ $errors->has('nom') ? 'is-invalid' : '' }}"
               value="{{ old('nom') }}"
               placeholder="Ex: Amadou Bello"
               required autocomplete="name">
        @error('nom') <div class="invalid-feedback-ph">{{ $message }}</div> @enderror
    </div>

    {{-- Email --}}
    <div class="form-group-ph">
        <label class="form-label-ph" for="email">
            <i class="bi bi-envelope"></i> Email universitaire
        </label>
        <input type="email" id="email" name="email"
               class="form-control-ph {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email') }}"
               placeholder="prenom.nom@univ-ngaoundere.cm"
               required autocomplete="email">
        @error('email') <div class="invalid-feedback-ph">{{ $message }}</div> @enderror
    </div>

    {{-- Password --}}
    <div class="form-group-ph">
        <label class="form-label-ph" for="password">
            <i class="bi bi-lock"></i> Mot de passe
        </label>
        <div style="position:relative;">
            <input type="password" id="password" name="password"
                   class="form-control-ph {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimum 8 caractères"
                   required autocomplete="new-password">
            <button type="button" onclick="togglePwd()"
                    style="position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--brown-400);cursor:pointer;font-size:15px;padding:0;">
                <i class="bi bi-eye" id="pwd-icon"></i>
            </button>
        </div>

        {{-- Strength indicator --}}
        <div style="margin-top:.5rem;height:4px;background:var(--brown-100);border-radius:99px;overflow:hidden;">
            <div id="pwd-strength-bar" style="height:100%;width:0;background:var(--brown-300);border-radius:99px;transition:.3s ease;"></div>
        </div>
        <div id="pwd-strength-label" style="font-size:11px;color:var(--brown-300);margin-top:.2rem;"></div>

        @error('password') <div class="invalid-feedback-ph">{{ $message }}</div> @enderror
    </div>

    {{-- Confirm password --}}
    <div class="form-group-ph">
        <label class="form-label-ph" for="password_confirmation">
            <i class="bi bi-lock-fill"></i> Confirmer le mot de passe
        </label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="form-control-ph"
               placeholder="Répétez le mot de passe"
               required autocomplete="new-password">
    </div>

    {{-- Terms --}}
    <div style="display:flex;align-items:flex-start;gap:.5rem;margin-bottom:1.25rem;">
        <input type="checkbox" id="terms" required
               style="width:16px;height:16px;flex-shrink:0;accent-color:var(--amber-500);margin-top:2px;cursor:pointer;">
        <label for="terms" style="font-size:12.5px;color:var(--brown-500);cursor:pointer;line-height:1.5;">
            J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a> et la <a href="#" class="auth-link">politique de confidentialité</a> de Phoenix.
        </label>
    </div>

    <button type="submit" class="btn-auth btn-auth-amber">
        <i class="bi bi-person-check"></i> Créer mon compte
    </button>
</form>

<div class="auth-divider">ou</div>

<p style="text-align:center;font-size:14px;color:var(--brown-500);">
    Vous avez déjà un compte ?
    <a href="{{ route('login') }}" class="auth-link">Se connecter</a>
</p>

@push('scripts')
<script>
function selectRole(role) {
    document.getElementById('opt-etudiant').classList.toggle('selected', role === 'etudiant');
    document.getElementById('opt-enseignant').classList.toggle('selected', role === 'enseignant');
}

function togglePwd() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('pwd-icon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}

document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('pwd-strength-bar');
    const lbl = document.getElementById('pwd-strength-label');
    let strength = 0;
    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;

    const colors  = ['#EF4444','#F97316','#EAB308','#22C55E'];
    const labels  = ['Trop faible','Faible','Moyen','Fort'];
    const widths  = ['25%','50%','75%','100%'];

    if (val.length === 0) { bar.style.width = '0'; lbl.textContent = ''; return; }
    bar.style.width      = widths[strength - 1] || '10%';
    bar.style.background = colors[strength - 1] || colors[0];
    lbl.textContent      = labels[strength - 1] || labels[0];
    lbl.style.color      = colors[strength - 1] || colors[0];
});
</script>
@endpush
@endsection