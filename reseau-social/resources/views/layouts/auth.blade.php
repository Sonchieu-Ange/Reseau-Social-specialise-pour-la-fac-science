<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion') · Phoenix</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --brown-950: #1C0A00;
            --brown-900: #2D1100;
            --brown-800: #4A1C02;
            --brown-700: #6B2D08;
            --brown-600: #8B3E12;
            --brown-500: #A8501E;
            --brown-400: #C46730;
            --brown-300: #D98A5A;
            --brown-200: #E8B48A;
            --brown-100: #F3D4BC;
            --brown-50:  #FAF0E8;
            --amber-500: #F59E0B;
            --amber-400: #FBBF24;
            --amber-300: #FCD34D;
            --amber-100: #FEF3C7;
            --cream:     #FDF8F3;
            --white:     #FFFFFF;
            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'DM Sans', system-ui, sans-serif;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            background: var(--brown-950);
        }

        /* Left decorative panel */
        .auth-left {
            width: 45%;
            min-height: 100vh;
            background: linear-gradient(160deg, var(--brown-900) 0%, var(--brown-800) 50%, var(--brown-700) 100%);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            padding: 3rem 3.5rem;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: rgba(251,191,36,.06);
            border-radius: 50%;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 300px; height: 300px;
            background: rgba(251,191,36,.05);
            border-radius: 50%;
        }

        .auth-left-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 3.5rem;
            position: relative; z-index: 1;
        }

        .auth-left-logo .logo-icon {
            width: 48px; height: 48px;
            background: var(--amber-500);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 700;
            color: var(--brown-900);
        }

        .auth-left-logo .brand-text {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
        }

        .auth-left-logo .brand-sub {
            font-size: 12px;
            color: var(--brown-200);
        }

        .auth-tagline {
            position: relative; z-index: 1;
        }

        .auth-tagline h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            line-height: 1.2;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .auth-tagline h2 span { color: var(--amber-400); }

        .auth-tagline p {
            color: rgba(255,255,255,.6);
            font-size: 15px;
            line-height: 1.7;
            max-width: 360px;
        }

        .auth-features {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: .85rem;
            position: relative; z-index: 1;
        }

        .auth-feature-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: 13.5px;
            color: rgba(255,255,255,.75);
        }

        .auth-feature-item .fi-icon {
            width: 28px; height: 28px;
            background: rgba(251,191,36,.15);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber-400);
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Right form panel */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--cream);
        }

        .auth-form-box {
            width: 100%;
            max-width: 440px;
        }

        .auth-form-title {
            font-family: var(--font-display);
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--brown-900);
            margin-bottom: .4rem;
        }

        .auth-form-subtitle {
            font-size: 14px;
            color: var(--brown-400);
            margin-bottom: 2rem;
        }

        .form-group-ph { margin-bottom: 1.1rem; }

        .form-label-ph {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--brown-700);
            margin-bottom: .4rem;
        }

        .form-control-ph {
            width: 100%;
            padding: .65rem .95rem;
            background: var(--white);
            border: 1.5px solid var(--brown-100);
            border-radius: 10px;
            font-family: var(--font-body);
            font-size: 14px;
            color: var(--brown-900);
            outline: none;
            transition: .18s ease;
        }

        .form-control-ph:focus {
            border-color: var(--amber-400);
            box-shadow: 0 0 0 3px rgba(251,191,36,.18);
        }

        .form-control-ph.is-invalid {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.12);
        }

        .invalid-feedback-ph {
            font-size: 12px;
            color: #DC2626;
            margin-top: .3rem;
        }

        .btn-auth {
            width: 100%;
            padding: .75rem;
            background: var(--brown-800);
            color: var(--white);
            font-family: var(--font-body);
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: .18s ease;
            margin-top: .5rem;
        }

        .btn-auth:hover {
            background: var(--brown-700);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(44,17,0,.25);
        }

        .btn-auth-amber {
            background: var(--amber-500);
            color: var(--brown-900);
        }

        .btn-auth-amber:hover {
            background: var(--amber-400);
            box-shadow: 0 4px 14px rgba(245,158,11,.35);
        }

        .auth-divider {
            text-align: center;
            margin: 1.25rem 0;
            position: relative;
            font-size: 12px;
            color: var(--brown-300);
        }

        .auth-divider::before, .auth-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 38%;
            height: 1px;
            background: var(--brown-100);
        }

        .auth-divider::before { left: 0; }
        .auth-divider::after { right: 0; }

        .auth-link {
            color: var(--amber-600);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover { color: var(--amber-500); text-decoration: underline; }

        .role-selector {
            display: flex;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .role-option {
            flex: 1;
            padding: .65rem;
            border: 2px solid var(--brown-100);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: .18s ease;
            font-size: 13px;
            font-weight: 500;
            color: var(--brown-600);
            background: var(--white);
        }

        .role-option input { display: none; }

        .role-option:hover { border-color: var(--brown-300); }

        .role-option.selected {
            border-color: var(--amber-500);
            background: var(--amber-100);
            color: var(--brown-800);
        }

        .role-option i { display: block; font-size: 22px; margin-bottom: .3rem; }

        @media (max-width: 768px) {
            .auth-left { display: none; }
            body { background: var(--cream); }
        }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="auth-left-logo">
            <div class="logo-icon">Φ</div>
            <div>
                <div class="brand-text">Phoenix</div>
                <div class="brand-sub">Université de Ngaoundéré</div>
            </div>
        </div>

        <div class="auth-tagline">
            <h2>La communauté <span>académique</span> de votre faculté</h2>
            <p>Rejoignez des centaines d'étudiants et enseignants de la Faculté des Sciences sur une plateforme dédiée et sécurisée.</p>
        </div>

        <div class="auth-features">
            <div class="auth-feature-item">
                <div class="fi-icon"><i class="bi bi-people-fill"></i></div>
                Groupes thématiques et académiques
            </div>
            <div class="auth-feature-item">
                <div class="fi-icon"><i class="bi bi-calendar-event-fill"></i></div>
                Événements et conférences en temps réel
            </div>
            <div class="auth-feature-item">
                <div class="fi-icon"><i class="bi bi-chat-square-dots-fill"></i></div>
                Messagerie privée entre membres
            </div>
            <div class="auth-feature-item">
                <div class="fi-icon"><i class="bi bi-megaphone-fill"></i></div>
                Annonces officielles de la faculté
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-box">
            @yield('auth-content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>