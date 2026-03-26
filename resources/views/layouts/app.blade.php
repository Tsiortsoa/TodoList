<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager') — TaskFlow</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        /* ─── TOKENS ─────────────────────────────────────────── */
        :root {
            --sidebar-w: 260px;
            --bg: #f0f2f7;
            --surface: #ffffff;
            --sidebar-bg: #0d1117;
            --sidebar-border: rgba(255,255,255,.06);
            --accent: #5b6cf9;
            --accent-hover: #4a5af0;
            --accent-glow: rgba(91,108,249,.25);
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --success: #22c55e;
            --warning: #f59e0b;
            --info: #3b82f6;
            --radius: 14px;
            --radius-sm: 8px;
            --shadow: 0 4px 24px rgba(15,23,42,.08);
            --shadow-lg: 0 8px 40px rgba(15,23,42,.14);
            --transition: .22s cubic-bezier(.4,0,.2,1);
        }

        /* ─── RESET / BASE ───────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            font-size: .9375rem;
            background: var(--bg);
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            display: flex;
        }

        /* ─── SIDEBAR ─────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
            transition: transform var(--transition);
        }

        /* subtle noise texture */
        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .sidebar > * { position: relative; z-index: 1; }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 24px 22px 20px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: .8rem;
            color: #fff;
            box-shadow: 0 0 16px var(--accent-glow);
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: -.01em;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-section-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.28);
            padding: 12px 10px 6px;
            margin-top: 6px;
        }

        .nav-item { margin-bottom: 2px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,.55);
            font-size: .875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background var(--transition), color var(--transition);
            position: relative;
            overflow: hidden;
        }

        .nav-link .nav-icon {
            width: 18px;
            text-align: center;
            flex-shrink: 0;
            font-size: .85rem;
            transition: transform var(--transition);
        }

        .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }
        .nav-link:hover .nav-icon { transform: scale(1.15); }

        .nav-link.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 16px var(--accent-glow);
        }
        .nav-link.active .nav-icon { transform: none; }

        .nav-badge {
            margin-left: auto;
            background: rgba(255,255,255,.15);
            color: rgba(255,255,255,.75);
            font-size: .7rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 99px;
        }
        .nav-link.active .nav-badge {
            background: rgba(255,255,255,.25);
            color: #fff;
        }

        .nav-pending {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: var(--warning);
            font-size: .875rem;
            font-weight: 500;
            background: rgba(245,158,11,.08);
        }

        /* Sidebar divider */
        .sidebar-divider {
            height: 1px;
            background: var(--sidebar-border);
            margin: 10px 12px;
        }

        /* Logout button */
        .sidebar-footer {
            padding: 12px 12px 20px;
            border-top: 1px solid var(--sidebar-border);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            background: transparent;
            border: none;
            color: rgba(255,255,255,.4);
            font-size: .875rem;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background var(--transition), color var(--transition);
            text-align: left;
        }
        .btn-logout:hover {
            background: rgba(239,68,68,.12);
            color: #fc8181;
        }

        /* ─── MAIN ────────────────────────────────────────────── */
        main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            padding: 32px 36px;
            transition: margin var(--transition);
        }

        /* ─── ALERTS ──────────────────────────────────────────── */
        .alert {
            border: none;
            border-radius: var(--radius);
            font-size: .875rem;
            font-weight: 500;
            padding: 14px 18px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        .alert-success {
            background: #dcfce7;
            color: #14532d;
        }
        .alert-danger {
            background: #fee2e2;
            color: #7f1d1d;
        }

        /* ─── MOBILE TOGGLER ──────────────────────────────────── */
        .navbar-toggler {
            position: fixed;
            top: 16px; left: 16px;
            z-index: 1100;
            background: var(--sidebar-bg);
            border: 1px solid var(--sidebar-border);
            color: rgba(255,255,255,.7);
            width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background var(--transition);
        }
        .navbar-toggler:hover { background: rgba(255,255,255,.1); }

        /* ─── RESPONSIVE ──────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(0,0,0,.4);
            }
            main { margin-left: 0; padding: 24px 20px; }
            .navbar-toggler { display: flex; }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Mobile toggler -->
    <button class="navbar-toggler" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars fa-sm"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-check-double"></i>
            </div>
            <span class="brand-name">TaskFlow</span>
        </div>

        <!-- Nav -->
        <nav class="sidebar-nav">
            @auth
                @if(auth()->user()->isApproved())
                    <div class="nav-section-label">Navigation</div>

                    <div class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                            Dashboard
                        </a>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}"
                           href="{{ route('tasks.index') }}">
                            <span class="nav-icon"><i class="fas fa-layer-group"></i></span>
                            Mes Tâches
                        </a>
                    </div>

                    @can('viewAny', App\Models\User::class)
                        <div class="nav-section-label">Administration</div>

                        <div class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                               href="{{ route('admin.users.index') }}">
                                <span class="nav-icon"><i class="fas fa-users-gear"></i></span>
                                Gérer les utilisateurs
                            </a>
                        </div>

                        <div class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}"
                               href="{{ route('admin.tags.index') }}">
                                <span class="nav-icon"><i class="fas fa-tags"></i></span>
                                Gérer les tags
                            </a>
                        </div>
                    @endcan

                @else
                    <div class="nav-item mt-2">
                        <span class="nav-pending">
                            <span class="nav-icon"><i class="fas fa-hourglass-half"></i></span>
                            En attente d'approbation
                        </span>
                    </div>
                @endif
            @endauth
        </nav>

        <!-- Logout -->
        @auth
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="btn-logout">
                    <span style="width:18px;text-align:center">
                        <i class="fas fa-right-from-bracket fa-sm"></i>
                    </span>
                    Déconnexion
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <!-- Main content -->
    <main>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/all.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => sidebar.classList.toggle('active'));
        }

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 &&
                sidebar.classList.contains('active') &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });

        // Email domain validation
        const regEmail = document.getElementById('reg_email');
        if (regEmail) {
            regEmail.addEventListener('input', function() {
                const domain = this.value.split('@')[1];
                const allowed = 'gmail.com';
                const errorDiv = document.getElementById('regEmailError');
                if (domain && domain !== allowed) {
                    if (errorDiv) errorDiv.textContent = 'Email must be from ' + allowed;
                    this.classList.add('is-invalid');
                } else {
                    if (errorDiv) errorDiv.textContent = '';
                    this.classList.remove('is-invalid');
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>