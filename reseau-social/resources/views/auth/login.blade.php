@extends('layouts.auth')

@section('title', 'Connexion')

@section('auth-content')
<h2 class="auth-form-title">Bon retour !</h2>
<p class="auth-form-subtitle">Connectez-vous à votre espace Phoenix</p>

@if($errors->any())
<div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:13.5px;color:#991B1B;display:flex;align-items:flex-start;gap:.6rem;">
    <i class="bi bi-exclamation-circle-fill" style="margin-top:1px;flex-shrink:0;"></i>
    <div>
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group-ph">
        <label class="form-label-ph" for="email">
            <i class="bi bi-envelope"></i> Adresse email
        </label>
        <input type="email"
               id="email"
               name="email"
               class="form-control-ph {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email') }}"
               placeholder="prenom.nom@univ-ngaoundere.cm"
               autocomplete="email"
               required autofocus>
        @error('email')
            <div class="invalid-feedback-ph"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="form-group-ph">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem;">
            <label class="form-label-ph" for="password" style="margin:0;">
                <i class="bi bi-lock"></i> Mot de passe
            </label>
            
           

        </div>
        <div style="position:relative;">
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control-ph {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Votre mot de passe"
                   autocomplete="current-password"
                   required>
            <button type="button" onclick="togglePwd('password')"
                    style="position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--brown-400);cursor:pointer;font-size:15px;padding:0;">
                <i class="bi bi-eye" id="pwd-icon"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback-ph"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
        @enderror
    </div>

    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <input type="checkbox" id="remember" name="remember"
               style="width:16px;height:16px;accent-color:var(--amber-500);cursor:pointer;">
        <label for="remember" style="font-size:13px;color:var(--brown-600);cursor:pointer;margin:0;">
            Se souvenir de moi
        </label>
    </div>

    <button type="submit" class="btn-auth btn-auth-amber">
        <i class="bi bi-box-arrow-in-right"></i> Se connecter
    </button>
</form>

<div class="auth-divider">ou</div>

<p style="text-align:center;font-size:14px;color:var(--brown-500);">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="auth-link">Créer un compte</a>
</p>

@push('scripts')
<script>
function togglePwd(fieldId) {
    const input = document.getElementById(fieldId);
    const icon  = document.getElementById('pwd-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush
@endsection