<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FacSci Ngaoundéré') — Réseau Universitaire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700;900&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* ══════════════════════════════════════════════
           DESIGN TOKENS — Palette Marron × Ambre × Crème
        ══════════════════════════════════════════════ */
        :root {
            --brown-950: #1C0D00;
            --brown-900: #2D1400;
            --brown-800: #4A2000;
            --brown-700: #6B3100;
            --brown-600: #8B4513;
            --brown-500: #A0522D;
            --brown-400: #C06030;
            --brown-300: #D4845A;
            --brown-200: #E8B898;
            --brown-100: #F5E0D0;
            --brown-50:  #FDF6F0;

            --amber-600: #D97706;
            --amber-500: #F59E0B;
            --amber-400: #FBBF24;
            --amber-300: #FCD34D;
            --amber-100: #FEF3C7;
            --amber-50:  #FFFBEB;

            --cream-100: #FEFAF5;
            --cream-200: #FDF4E8;
            --white:     #FFFFFF;

            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'DM Sans', sans-serif;
            --font-mono:    'DM Mono', monospace;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-2xl:28px;

            --shadow-xs: 0 1px 3px rgba(44,20,0,.06);
            --shadow-sm: 0 2px 8px rgba(44,20,0,.08);
            --shadow-md: 0 4px 20px rgba(44,20,0,.10);
            --shadow-lg: 0 8px 40px rgba(44,20,0,.14);
            --shadow-xl: 0 20px 60px rgba(44,20,0,.18);

            --sidebar-w: 260px;
            --topbar-h:  64px;
            --transition: 0.2s cubic-bezier(.4,0,.2,1);
        }

        /* ══════════════════════════════════════════════
           RESET + BASE
        ══════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: var(--cream-100);
            color: var(--brown-900);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        button { font-family: inherit; cursor: pointer; }

        /* ══════════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════════ */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: var(--white);
            border-bottom: 2px solid var(--amber-400);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            z-index: 1000;
            box-shadow: 0 2px 16px rgba(44,20,0,.08);
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            flex-shrink: 0;
        }
        .topbar-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--brown-800), var(--brown-600));
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            color: var(--amber-400);
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(44,20,0,.25);
        }
        .topbar-name {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brown-900);
            letter-spacing: -.02em;
        }
        .topbar-name span { color: var(--amber-600); }

        .topbar-search {
            flex: 1;
            max-width: 380px;
            margin-left: auto;
        }
        .search-wrapper {
            position: relative;
        }
        .search-wrapper i {
            position: absolute;
            left: 0.875rem;
            top: 50%; transform: translateY(-50%);
            color: var(--brown-400);
            font-size: 14px;
        }
        .search-input {
            width: 100%;
            background: var(--brown-50);
            border: 1.5px solid var(--brown-100);
            border-radius: 50px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-family: var(--font-body);
            font-size: 14px;
            color: var(--brown-900);
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        .search-input::placeholder { color: var(--brown-300); }
        .search-input:focus {
            outline: none;
            border-color: var(--amber-400);
            box-shadow: 0 0 0 3px rgba(251,191,36,.15);
            background: var(--white);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1rem;
        }
        .icon-btn {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: none;
            background: var(--brown-50);
            color: var(--brown-700);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            transition: background var(--transition), color var(--transition), transform var(--transition);
            position: relative;
        }
        .icon-btn:hover {
            background: var(--amber-100);
            color: var(--amber-600);
            transform: scale(1.08);
        }
        .icon-btn .badge {
            position: absolute;
            top: 2px; right: 2px;
            width: 16px; height: 16px;
            background: var(--amber-500);
            color: var(--white);
            border-radius: 50%;
            font-size: 9px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--white);
        }
        .topbar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 2.5px solid var(--amber-400);
            overflow: hidden;
            cursor: pointer;
            transition: border-color var(--transition), box-shadow var(--transition);
            flex-shrink: 0;
        }
        .topbar-avatar:hover {
            border-color: var(--amber-500);
            box-shadow: 0 0 0 3px rgba(251,191,36,.2);
        }
        .topbar-avatar-placeholder {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brown-600), var(--brown-400));
            border: 2.5px solid var(--amber-400);
            display: flex; align-items: center; justify-content: center;
            color: var(--white);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: box-shadow var(--transition);
        }
        .topbar-avatar-placeholder:hover {
            box-shadow: 0 0 0 3px rgba(251,191,36,.25);
        }

        /* ══════════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: var(--topbar-h);
            left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--topbar-h));
            background: var(--white);
            border-right: 1.5px solid var(--brown-100);
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.25rem 0;
            z-index: 900;
            display: flex;
            flex-direction: column;
            scrollbar-width: thin;
            scrollbar-color: var(--brown-100) transparent;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--brown-100); border-radius: 4px; }

        .nav-section {
            padding: 0 0.875rem;
            margin-bottom: 0.5rem;
        }
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--brown-300);
            padding: 0 0.5rem;
            margin-bottom: 0.375rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: var(--radius-lg);
            color: var(--brown-600);
            font-size: 14.5px;
            font-weight: 500;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }
        .nav-link:hover {
            background: var(--amber-50);
            color: var(--brown-900);
        }
        .nav-link.active {
            background: linear-gradient(135deg, var(--amber-50), var(--amber-100));
            color: var(--brown-900);
            font-weight: 600;
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--amber-500);
            border-radius: 0 3px 3px 0;
        }
        .nav-link i {
            font-size: 17px;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }
        .nav-link .nav-badge {
            margin-left: auto;
            background: var(--amber-400);
            color: var(--brown-950);
            font-size: 10px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 50px;
            font-family: var(--font-mono);
        }
        .nav-divider {
            height: 1px;
            background: var(--brown-50);
            margin: 0.625rem 1.25rem;
        }

        .sidebar-user-card {
            margin: auto 0.875rem 0.875rem;
            background: linear-gradient(135deg, var(--brown-50), var(--amber-50));
            border: 1.5px solid var(--amber-100);
            border-radius: var(--radius-xl);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name {
            font-weight: 700;
            font-size: 13.5px;
            color: var(--brown-900);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user-role {
            font-size: 11px;
            color: var(--brown-400);
            font-weight: 500;
        }

        /* ══════════════════════════════════════════════
           MAIN CONTENT AREA
        ══════════════════════════════════════════════ */
        .page-wrapper {
            margin-top: var(--topbar-h);
            margin-left: var(--sidebar-w);
            min-height: calc(100vh - var(--topbar-h));
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 0;
        }
        .main-content {
            padding: 2rem;
            max-width: 740px;
            width: 100%;
        }
        .right-panel {
            padding: 2rem 1.5rem 2rem 0;
            position: sticky;
            top: var(--topbar-h);
            height: calc(100vh - var(--topbar-h));
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--brown-100) transparent;
        }

        /* ══════════════════════════════════════════════
           SHARED CARD COMPONENTS
        ══════════════════════════════════════════════ */
        .card {
            background: var(--white);
            border: 1.5px solid var(--brown-100);
            border-radius: var(--radius-2xl);
            padding: 1.5rem;
            box-shadow: var(--shadow-xs);
            transition: box-shadow var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-sm); }

        .card-title {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brown-900);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .card-title i { color: var(--amber-500); font-size: 1rem; }

        /* ══════════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--brown-800), var(--brown-600));
            color: var(--white);
            box-shadow: 0 3px 12px rgba(44,20,0,.25);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(44,20,0,.3);
        }
        .btn-amber {
            background: linear-gradient(135deg, var(--amber-500), var(--amber-400));
            color: var(--brown-950);
            box-shadow: 0 3px 12px rgba(217,119,6,.2);
        }
        .btn-amber:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(217,119,6,.3);
        }
        .btn-ghost {
            background: var(--brown-50);
            color: var(--brown-700);
            border: 1.5px solid var(--brown-100);
        }
        .btn-ghost:hover {
            background: var(--amber-50);
            border-color: var(--amber-200);
            color: var(--brown-900);
        }
        .btn-sm { padding: 0.375rem 0.875rem; font-size: 13px; }
        .btn-xs { padding: 0.25rem 0.625rem; font-size: 12px; }

        /* ══════════════════════════════════════════════
           AVATARS
        ══════════════════════════════════════════════ */
        .avatar {
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--amber-100);
        }
        .avatar-placeholder {
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brown-600), var(--brown-400));
            color: var(--white);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 13px;
            border: 2px solid var(--amber-100);
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════════════
           HERO PANEL
        ══════════════════════════════════════════════ */
        .hero-panel {
            background: linear-gradient(135deg, var(--brown-900) 0%, var(--brown-700) 50%, var(--brown-800) 100%);
            border-radius: var(--radius-2xl);
            padding: 2rem;
            color: var(--white);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .hero-panel::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(251,191,36,.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 20%;
            width: 280px; height: 180px;
            background: radial-gradient(ellipse, rgba(251,191,36,.08) 0%, transparent 70%);
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: rgba(251,191,36,.2);
            border: 1px solid rgba(251,191,36,.35);
            color: var(--amber-300);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .04em;
        }

        /* ══════════════════════════════════════════════
           STAT CARDS
        ══════════════════════════════════════════════ */
        .stat-card {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius-xl);
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            text-align: center;
        }
        .stat-icon {
            width: 38px; height: 38px;
            background: rgba(251,191,36,.2);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            color: var(--amber-300);
            font-size: 16px;
            margin: 0 auto 0.625rem;
        }
        .stat-number {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            color: rgba(255,255,255,.6);
            margin-top: 0.25rem;
            font-weight: 500;
            letter-spacing: .04em;
        }

        /* ══════════════════════════════════════════════
           CREATE POST SECTION
        ══════════════════════════════════════════════ */
        .create-post-section {
            background: var(--white);
            border: 1.5px solid var(--brown-100);
            border-radius: var(--radius-2xl);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-xs);
        }
        .create-post-input {
            flex: 1;
            background: var(--brown-50);
            border: 1.5px solid var(--brown-100);
            border-radius: 50px;
            padding: 0.75rem 1.25rem;
            color: var(--brown-400);
            font-size: 14.5px;
            transition: all var(--transition);
            display: block;
        }
        .create-post-input:hover {
            background: var(--amber-50);
            border-color: var(--amber-200);
            color: var(--brown-600);
        }
        .create-options {
            display: flex;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1.5px solid var(--brown-50);
            flex-wrap: wrap;
        }
        .option-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--brown-600);
            background: var(--brown-50);
            border: 1.5px solid transparent;
            transition: all var(--transition);
        }
        .option-btn:hover {
            background: var(--amber-50);
            border-color: var(--amber-200);
            color: var(--amber-600);
            transform: translateY(-1px);
        }
        .option-btn i { font-size: 15px; }

        /* ══════════════════════════════════════════════
           POST CARDS
        ══════════════════════════════════════════════ */
        .post-card {
            background: var(--white);
            border: 1.5px solid var(--brown-100);
            border-radius: var(--radius-2xl);
            padding: 1.5rem;
            box-shadow: var(--shadow-xs);
            transition: box-shadow var(--transition), transform var(--transition);
        }
        .post-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        .post-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
            gap: 0.75rem;
        }
        .post-author-info { display: flex; align-items: center; gap: 0.75rem; }
        .post-meta {
            font-size: 12px;
            color: var(--brown-400);
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        .post-content {
            font-size: 15px;
            color: var(--brown-800);
            line-height: 1.7;
            margin-bottom: 1rem;
        }
        .post-image {
            width: 100%;
            border-radius: var(--radius-xl);
            max-height: 320px;
            object-fit: cover;
            margin-top: 0.75rem;
        }
        .post-actions {
            display: flex;
            gap: 0.25rem;
            padding-top: 0.875rem;
            border-top: 1.5px solid var(--brown-50);
        }
        .post-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--brown-500);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            flex: 1;
            justify-content: center;
        }
        .post-action-btn:hover {
            background: var(--amber-50);
            color: var(--amber-600);
        }
        .post-action-btn i { font-size: 16px; }

        /* ══════════════════════════════════════════════
           FORM STYLES
        ══════════════════════════════════════════════ */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--brown-700);
            margin-bottom: 0.375rem;
            letter-spacing: .01em;
        }
        .form-control {
            width: 100%;
            background: var(--brown-50);
            border: 1.5px solid var(--brown-100);
            border-radius: var(--radius-lg);
            padding: 0.75rem 1rem;
            font-family: var(--font-body);
            font-size: 14.5px;
            color: var(--brown-900);
            transition: all var(--transition);
        }
        .form-control::placeholder { color: var(--brown-300); }
        .form-control:focus {
            outline: none;
            border-color: var(--amber-400);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(251,191,36,.12);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }
        .form-select {
            width: 100%;
            background: var(--brown-50);
            border: 1.5px solid var(--brown-100);
            border-radius: var(--radius-lg);
            padding: 0.75rem 1rem;
            font-family: var(--font-body);
            font-size: 14.5px;
            color: var(--brown-900);
            transition: all var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238B4513' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }
        .form-select:focus {
            outline: none;
            border-color: var(--amber-400);
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(251,191,36,.12);
        }

        /* ══════════════════════════════════════════════
           BADGE / TAG
        ══════════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.625rem;
            border-radius: 50px;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: .02em;
        }
        .badge-amber { background: var(--amber-100); color: var(--amber-600); }
        .badge-brown { background: var(--brown-100); color: var(--brown-700); }
        .badge-green { background: #D1FAE5; color: #065F46; }
        .badge-red   { background: #FEE2E2; color: #991B1B; }
        .badge-blue  { background: #DBEAFE; color: #1D4ED8; }

        /* ══════════════════════════════════════════════
           PAGE HEADER
        ══════════════════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .page-title {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--brown-950);
            letter-spacing: -.02em;
        }
        .page-subtitle {
            font-size: 14px;
            color: var(--brown-400);
            margin-top: 0.125rem;
        }

        /* ══════════════════════════════════════════════
           ALERTS
        ══════════════════════════════════════════════ */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius-lg);
            font-size: 14px;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
        }
        .alert-success { background: #D1FAE5; color: #065F46; border-left: 3px solid #10B981; }
        .alert-error   { background: #FEE2E2; color: #991B1B; border-left: 3px solid #EF4444; }
        .alert-warning { background: var(--amber-100); color: var(--amber-600); border-left: 3px solid var(--amber-400); }
        .alert-info    { background: #DBEAFE; color: #1D4ED8; border-left: 3px solid #3B82F6; }

        /* ══════════════════════════════════════════════
           TABLE STYLES
        ══════════════════════════════════════════════ */
        .table-wrapper {
            background: var(--white);
            border: 1.5px solid var(--brown-100);
            border-radius: var(--radius-2xl);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
        }
        table { width: 100%; border-collapse: collapse; }
        thead {
            background: var(--brown-50);
            border-bottom: 2px solid var(--brown-100);
        }
        th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--brown-500);
        }
        td {
            padding: 1rem 1.25rem;
            font-size: 14px;
            color: var(--brown-800);
            border-bottom: 1px solid var(--brown-50);
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--brown-50); }

        /* ══════════════════════════════════════════════
           SCROLLBAR GLOBAL
        ══════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--brown-100); border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brown-200); }

        /* ══════════════════════════════════════════════
           ANIMATIONS
        ══════════════════════════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .4s ease both; }
        .delay-1 { animation-delay: .05s; }
        .delay-2 { animation-delay: .1s; }
        .delay-3 { animation-delay: .15s; }
        .delay-4 { animation-delay: .2s; }

        /* ══════════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════════ */
        @media (max-width: 1100px) {
            .page-wrapper { grid-template-columns: 1fr; }
            .right-panel { display: none; }
        }
        @media (max-width: 768px) {
            :root { --sidebar-w: 0px; }
            .sidebar { transform: translateX(-100%); width: 260px; transition: transform .3s ease; }
            .sidebar.open { transform: translateX(0); --sidebar-w: 260px; }
            .page-wrapper { margin-left: 0; }
            .main-content { padding: 1.25rem; }
            .hero-panel { padding: 1.5rem; }
            .hero-panel h1 { font-size: 1.5rem !important; }
        }
    </style>
</head>
<body>

{{-- ── TOP BAR ── --}}
<header class="topbar">
    <div class="topbar-brand">
        <div class="topbar-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <span class="topbar-name">FacSci <span>NG</span></span>
    </div>

    <div class="topbar-search">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="search-input" placeholder="Rechercher…">
        </div>
    </div>

    <div class="topbar-actions">
        <button class="icon-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            <span class="badge">3</span>
        </button>
        <button class="icon-btn" title="Messages">
            <i class="bi bi-chat-dots"></i>
            <span class="badge">7</span>
        </button>
        @php
            $me = Auth::user();
            $myAvatar = $me->avatar ?? null;
            $myInitials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $me->nom ?? 'U'), 0, 2)));
        @endphp
        @if($myAvatar)
            <img src="{{ $myAvatar }}" class="topbar-avatar">
        @else
            <div class="topbar-avatar-placeholder">{{ $myInitials }}</div>
        @endif
    </div>
</header>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar" id="sidebar">
    @php $route = request()->routeIs(...[]) ? '' : request()->route()?->getName(); @endphp

    <div class="nav-section">
        <div class="nav-section-label">Principal</div>
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> Accueil
        </a>
        <a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Groupes
        </a>
        <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-event-fill"></i> Événements
        </a>
        <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i> Annonces
            <span class="nav-badge">5</span>
        </a>
        <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
            <i class="bi bi-chat-fill"></i> Messages
            <span class="nav-badge">7</span>
        </a>
    </div>

    <div class="nav-divider"></div>

    <div class="nav-section">
        <div class="nav-section-label">Découvrir</div>
        <a href="#" class="nav-link"><i class="bi bi-bookmark-fill"></i> Enregistrés</a>
        <a href="#" class="nav-link"><i class="bi bi-trophy-fill"></i> Classement</a>
        <a href="#" class="nav-link"><i class="bi bi-journal-richtext"></i> Ressources</a>
    </div>

    @if(auth()->user()?->role === 'admin')
    <div class="nav-divider"></div>
    <div class="nav-section">
        <div class="nav-section-label">Administration</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
            <i class="bi bi-shield-fill-check"></i> Dashboard Admin
        </a>
    </div>
    @endif

    <div class="nav-divider"></div>
    <div class="nav-section">
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Paramètres</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
            <i class="bi bi-box-arrow-left"></i> Déconnexion
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>

    <div class="sidebar-user-card">
        @if($myAvatar)
            <img src="{{ $myAvatar }}" class="avatar" style="width:38px;height:38px;">
        @else
            <div class="avatar-placeholder" style="width:38px;height:38px;">{{ $myInitials }}</div>
        @endif
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ $me->nom ?? 'Utilisateur' }}</div>
            <div class="sidebar-user-role">{{ ucfirst($me->role ?? 'Étudiant') }}</div>
        </div>
        <a href="#" style="color:var(--brown-300);font-size:15px;"><i class="bi bi-three-dots-vertical"></i></a>
    </div>
</aside>

{{-- ── PAGE WRAPPER ── --}}
<div class="page-wrapper">
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success fade-up">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error fade-up">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <aside class="right-panel">
        @yield('sidebar')
    </aside>
</div>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', () => {
    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .4s'; setTimeout(() => el.remove(), 400); }, 4000);
    });
});
</script>
</body>
</html>