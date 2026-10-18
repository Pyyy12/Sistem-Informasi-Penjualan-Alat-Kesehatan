
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - MedikaStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        :root {
            --primary: #087f8c;
            --primary-dark: #066874;
            --sidebar: #102d40;
            --page-bg: #f4f7fb;
            --text: #1e293b;
            --muted: #8491a3;
            --border: #e9eef4;
            --sidebar-width: 258px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--text);
            background: var(--page-bg);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            font-size: 14px;
        }

        a { text-decoration: none; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1040;
            width: var(--sidebar-width);
            padding: 25px 16px;
            color: #d7e5ef;
            background: var(--sidebar);
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px 30px;
            color: #fff;
        }

        .brand-logo {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: var(--primary);
            font-size: 23px;
        }

        .brand-name { font-size: 20px; font-weight: 800; }
        .brand-sub { color: #a5bdcc; font-size: 11px; }

        .menu-label {
            margin: 15px 12px 10px;
            color: #8ba6b8;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #c1d1de;
            transition: background .2s, color .2s, transform .2s;
        }

        .sidebar-link i { font-size: 18px; }

        .sidebar-link:hover {
            color: #fff;
            background: #21465b;
            transform: translateX(3px);
        }

        .sidebar-link.active {
            color: #fff;
            background: var(--primary);
            box-shadow: 0 5px 16px rgba(0, 127, 140, .22);
        }

        .main-wrapper {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            min-height: 76px;
            padding: 14px 30px;
            background: rgba(255,255,255,.96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .topbar-title { font-weight: 750; font-size: 17px; }
        .topbar-subtitle { color: var(--muted); font-size: 12px; }

        .main-content { padding: 30px; }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 25px;
        }

        .page-heading h1 {
            margin: 0 0 6px;
            font-size: clamp(22px, 2vw, 29px);
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .muted { color: var(--muted) !important; }

        .panel, .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 23px;
            box-shadow: 0 4px 16px rgba(20, 45, 75, .025);
        }

        .stat-card {
            height: 100%;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(20, 45, 75, .08);
        }

        .stat-number {
            margin: 13px 0 7px;
            font-size: clamp(23px, 2.3vw, 30px);
            font-weight: 800;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
        }

        .stat-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            font-size: 21px;
        }

        .btn {
            border-radius: 9px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 5px 15px rgba(8, 127, 140, .2);
            transform: translateY(-1px);
        }

        .form-control, .form-select {
            min-height: 43px;
            border-color: #dce4ec;
            border-radius: 9px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(8,127,140,.1);
        }

        .table { vertical-align: middle; margin-bottom: 0; }

        .table thead th {
            padding: 14px 12px;
            color: #6c7a8b;
            background: #f7f9fc;
            border-bottom: 1px solid var(--border);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 15px 12px;
            border-color: #eef2f6;
        }

        .table tbody tr:hover { background: #f9fbfd; }

        .alert { border-radius: 10px; }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1035;
            background: rgba(8, 24, 40, .55);
        }

        .mobile-overlay.show { display: block; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { min-height: 66px; padding: 12px 18px; }
            .main-content { padding: 20px 16px; }
            .panel, .stat-card { padding: 18px; }
        }

        @media (max-width: 575.98px) {
            .main-content { padding: 16px 11px; }
            .panel, .stat-card { padding: 15px; border-radius: 12px; }
            .page-heading { align-items: stretch; }
            .page-heading > .btn,
            .page-heading > a { width: 100%; text-align: center; }
            .topbar-title { font-size: 15px; }
            .stat-number { font-size: 24px; }
        }

        @media print {
            @page { size: auto; margin: 12mm; }

            body { background: #fff !important; font-size: 10pt; }
            .sidebar, .topbar, .mobile-overlay, .no-print,
            .btn, .pagination, form { display: none !important; }

            .main-wrapper { margin: 0 !important; }
            .main-content { padding: 0 !important; }
            .panel, .stat-card {
                padding: 10px !important;
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                break-inside: avoid;
            }

            .table-responsive { overflow: visible !important; }
            .table { width: 100% !important; }
            .table th, .table td {
                padding: 6px !important;
                color: #000 !important;
                font-size: 9pt !important;
            }

            a { color: #000 !important; }
            canvas { max-width: 100% !important; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <div class="brand-logo">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div>
                <div class="brand-name">MedikaStore</div>
                <div class="brand-sub">HEALTHCARE MANAGEMENT</div>
            </div>
        </a>

        <div class="menu-label">MENU UTAMA</div>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="menu-label">MANAJEMEN DATA</div>

        <a href="{{ route('categories.index') }}"
           class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> Kategori
        </a>

        <a href="{{ route('products.index') }}"
           class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Produk
        </a>

        <a href="{{ route('customers.index') }}"
           class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Pelanggan
        </a>

        <div class="menu-label">TRANSAKSI</div>

        <a href="{{ route('sales.index') }}"
           class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> Penjualan
        </a>

        <a href="{{ route('reports.index') }}"
           class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Laporan
        </a>

        <div class="mt-5 pt-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-2 px-2 mb-3">
                <div class="brand-logo" style="width:38px;height:38px;font-size:18px">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-semibold text-truncate">
                        {{ auth()->user()->name }}
                    </div>
                    <small class="brand-sub">Administrator</small>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none"
                        id="sidebarToggle" type="button"
                        aria-label="Buka menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('heading', 'Dashboard')</div>
                    <div class="topbar-subtitle">Sistem Penjualan Alat Kesehatan</div>
                </div>
            </div>

            <div class="d-none d-sm-flex align-items-center gap-2">
                <i class="bi bi-calendar3 muted"></i>
                <span>{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </header>

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Periksa kembali data yang kamu masukkan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

            <footer class="text-center muted py-4 small">
                © {{ date('Y') }} MedikaStore · Healthcare Management System
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const toggle = document.getElementById('sidebarToggle');

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        toggle?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) closeSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>
