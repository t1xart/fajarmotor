<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'fajarmotor')</title>
    <script>if(localStorage.getItem('theme')==='dark'||(!localStorage.getItem('theme')&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.classList.add('dark')}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root {
            --sidebar-w: 255px;
            --topbar-h: 60px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #e0e7ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --bg: #f8fafc;
            --surface: #ffffff;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text2: #64748b;
            --text3: #94a3b8;
            --muted: #64748b;
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(99,102,241,.15);
            --sidebar-active: rgba(99,102,241,.2);
            --radius: .75rem;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow: 0 4px 16px rgba(0,0,0,.08);
            --shadow-lg: 0 10px 40px rgba(0,0,0,.12);
            --transition: .2s cubic-bezier(.4,0,.2,1);
        }

        /* ── DARK MODE ── */
        html.dark {
            --bg: #05080f;
            --surface: #0d1526;
            --card: #0d1526;
            --border: rgba(255,255,255,0.08);
            --text: #f1f5f9;
            --text2: #94a3b8;
            --text3: #475569;
            --muted: #94a3b8;
            --sidebar-bg: #080d18;
            --sidebar-hover: rgba(99,102,241,.18);
            --sidebar-active: rgba(99,102,241,.22);
            --shadow-sm: 0 1px 3px rgba(0,0,0,.4);
            --shadow: 0 4px 16px rgba(0,0,0,.5);
            --shadow-lg: 0 10px 40px rgba(0,0,0,.6);
        }
        html.dark body { background: var(--bg); color: var(--text); }
        html.dark .card,
        html.dark .stat-card { background: var(--surface); border-color: var(--border); }
        html.dark .card-header { background: transparent; border-color: var(--border); }
        html.dark #topbar { background: rgba(8,13,24,.97); border-color: var(--border); }
        html.dark .table th { background: rgba(255,255,255,.03); color: #64748b; border-color: var(--border) !important; }
        html.dark .table td { border-color: rgba(255,255,255,.04) !important; color: var(--text); }
        html.dark .table-hover tbody tr:hover td { background: rgba(255,255,255,.03) !important; }
        html.dark .form-control,
        html.dark .form-select,
        html.dark .input-group-text { background: rgba(255,255,255,.05); border-color: var(--border); color: var(--text); }
        html.dark .form-control::placeholder { color: #475569; }
        html.dark .form-select option { background: #0d1526; }
        html.dark .dropdown-menu { background: #0d1526; border-color: var(--border); }
        html.dark .dropdown-item { color: #94a3b8; }
        html.dark .dropdown-item:hover { background: rgba(255,255,255,.07); color: var(--text); }
        html.dark .badge-soft-gray { background: rgba(255,255,255,.07); color: #94a3b8; }
        html.dark .alert-success { background: rgba(16,185,129,.12); color: #34d399; border-color: rgba(16,185,129,.2); }
        html.dark .alert-danger  { background: rgba(239,68,68,.12);  color: #f87171; border-color: rgba(239,68,68,.2); }
        html.dark .page-link { background: rgba(255,255,255,.04); border-color: var(--border); color: #94a3b8; }
        html.dark .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }
        html.dark .modal-content { background: #0d1526; border-color: var(--border); }
        html.dark .user-pill { background: rgba(255,255,255,.05); border-color: var(--border); }
        html.dark .topbar-btn { background: rgba(255,255,255,.05); border-color: var(--border); color: #94a3b8; }
        html.dark .btn-sm[style] { filter: brightness(.85); }
        html.dark hr, html.dark .dropdown-divider { border-color: var(--border); }
        html.dark .placeholder { background-color: rgba(255,255,255,.1); }
        html.dark .text-muted { color: #64748b !important; }

        /* toggle button */
        #darkToggle {
            width: 34px; height: 34px;
            border-radius: .5rem;
            border: 1px solid var(--border);
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); cursor: pointer;
            transition: all var(--transition); font-size: .8rem;
        }
        #darkToggle:hover { background: var(--bg); color: var(--text); border-color: #cbd5e1; }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 13.5px;
            line-height: 1.6;
            margin: 0;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: transform var(--transition);
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            flex-shrink: 0;
        }
        .sidebar-brand .logo {
            display: flex;
            align-items: center;
            gap: .65rem;
        }
        .sidebar-brand .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            border-radius: .6rem;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand .logo-text { color: #f1f5f9; font-weight: 700; font-size: .95rem; line-height: 1.2; }
        .sidebar-brand .logo-sub { color: #64748b; font-size: .7rem; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: .5rem 0 1rem; }

        .nav-section-label {
            color: #475569;
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .1em;
            padding: .9rem 1.25rem .3rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem 1.25rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 0;
            transition: all var(--transition);
            font-size: .825rem;
            font-weight: 500;
            position: relative;
            margin: 1px .5rem;
            border-radius: .5rem;
        }
        .sidebar-link .icon {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: .4rem;
            font-size: .8rem;
            flex-shrink: 0;
            transition: all var(--transition);
        }
        .sidebar-link:hover {
            color: #e2e8f0;
            background: var(--sidebar-hover);
        }
        .sidebar-link:hover .icon { background: rgba(99,102,241,.2); color: #a5b4fc; }
        .sidebar-link.active {
            color: #fff;
            background: var(--sidebar-active);
        }
        .sidebar-link.active .icon { background: var(--primary); color: #fff; }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -.5rem; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        /* ── MAIN ── */
        #main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        #topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
            gap: 1rem;
        }
        .topbar-title {
            font-weight: 600;
            font-size: .9rem;
            color: var(--text);
            flex: 1;
        }
        .topbar-actions { display: flex; align-items: center; gap: .75rem; }

        .topbar-btn {
            width: 34px; height: 34px;
            border-radius: .5rem;
            border: 1px solid var(--border);
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted);
            cursor: pointer;
            transition: all var(--transition);
            font-size: .8rem;
        }
        .topbar-btn:hover { background: var(--bg); color: var(--text); border-color: #cbd5e1; }

        .user-pill {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .3rem .75rem .3rem .3rem;
            border: 1px solid var(--border);
            border-radius: 99px;
            cursor: pointer;
            transition: all var(--transition);
            background: var(--surface);
        }
        .user-pill:hover { background: var(--bg); border-color: #cbd5e1; }
        .user-avatar {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
        }
        .user-name { font-size: .78rem; font-weight: 600; color: var(--text); }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 1.5rem; flex: 1; }

        /* ── CARDS ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            transition: box-shadow var(--transition);
        }
        .card:hover { box-shadow: var(--shadow); }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: .9rem 1.25rem;
            font-weight: 600;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .card-header .icon-badge {
            width: 28px; height: 28px;
            border-radius: .4rem;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem;
        }
        .card-body { padding: 1.25rem; }

        /* ── STAT CARDS ── */
        .stat-card {
            padding: 1.25rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--surface);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
            overflow: hidden;
            position: relative;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: .06;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: .65rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1.2; margin-top: .75rem; }
        .stat-card .stat-label { font-size: .75rem; color: var(--muted); margin-top: .15rem; }
        .stat-card .stat-change { font-size: .72rem; margin-top: .4rem; }

        /* ── TABLES ── */
        .table { font-size: .825rem; }
        .table th { font-weight: 600; color: var(--muted); font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid var(--border); padding: .65rem 1rem; background: #f8fafc; }
        .table td { padding: .75rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover td { background: #f8fafc; }

        /* ── BADGES ── */
        .badge { font-size: .68rem; font-weight: 600; padding: .3em .65em; border-radius: .35rem; }
        .badge-soft-success { background: #d1fae5; color: #065f46; }
        .badge-soft-warning { background: #fef3c7; color: #92400e; }
        .badge-soft-danger  { background: #fee2e2; color: #991b1b; }
        .badge-soft-info    { background: #dbeafe; color: #1e40af; }
        .badge-soft-purple  { background: #ede9fe; color: #5b21b6; }
        .badge-soft-gray    { background: #f1f5f9; color: #475569; }

        /* ── BUTTONS ── */
        .btn { font-size: .825rem; font-weight: 500; border-radius: .5rem; padding: .45rem .9rem; transition: all var(--transition); }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,.35); }
        .btn-sm { padding: .3rem .65rem; font-size: .775rem; }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: .45rem; }

        /* ── FORMS ── */
        .form-control, .form-select {
            font-size: .825rem;
            border-radius: .5rem;
            border: 1px solid var(--border);
            padding: .5rem .85rem;
            transition: all var(--transition);
            background: var(--surface);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .form-label { font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
        .input-group-text { font-size: .825rem; border-radius: .5rem; border-color: var(--border); background: #f8fafc; }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .page-header h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text);
        }
        .page-header .breadcrumb {
            font-size: .75rem;
            margin: 0;
            color: var(--muted);
        }

        /* ── ALERTS ── */
        .alert { border-radius: var(--radius); border: none; font-size: .825rem; padding: .75rem 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }

        /* ── PAGINATION ── */
        .pagination { gap: .2rem; }
        .page-link { border-radius: .45rem !important; border: 1px solid var(--border); color: var(--muted); font-size: .8rem; padding: .35rem .65rem; }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* ── DROPDOWN ── */
        .dropdown-menu { border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-lg); font-size: .825rem; padding: .4rem; }
        .dropdown-item { border-radius: .4rem; padding: .45rem .75rem; }
        .dropdown-item:hover { background: var(--bg); }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
            #main { margin-left: 0; }
            .page-content { padding: 1rem; }
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp .3s ease both; }
        .animate-in:nth-child(2) { animation-delay: .05s; }
        .animate-in:nth-child(3) { animation-delay: .1s; }
        .animate-in:nth-child(4) { animation-delay: .15s; }

        /* ── SIDEBAR OVERLAY ── */
        #sidebarOverlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 1040;
        }
        #sidebarOverlay.show { display: block; }

        /* ── COMPATIBILITY — inline styles used across other views ── */
        .text-muted { color: var(--muted) !important; }
        .fw-semibold { font-weight: 600 !important; }
        .fw-bold { font-weight: 700 !important; }
        a { color: var(--primary); }
        a:hover { color: var(--primary-dark); }
        .table > :not(caption) > * > * { background-color: transparent; }
        
        /* ── MODAL FIX ── */
        .modal { z-index: 1060; }
        .modal-backdrop { z-index: 1055; }
        .modal.show { z-index: 1060; pointer-events: auto; }
        .modal.show .modal-backdrop { z-index: 1055; }
        .modal-dialog { position: relative; z-index: 1061; }
        .modal-content { position: relative; z-index: 1061; }
        .modal-header .btn-close { cursor: pointer; opacity: 0.6; transition: opacity var(--transition); }
        .modal-header .btn-close:hover { opacity: 1; }
        .modal-body input, .modal-body select, .modal-body textarea {
            position: relative;
            z-index: 1;
        }
        .modal-footer button {
            position: relative;
            z-index: 1;
            cursor: pointer;
        }
        .modal-footer .btn { pointer-events: auto; cursor: pointer; }
        
        .spinner-border-sm { width: 1rem; height: 1rem; }
        .placeholder { background-color: #e2e8f0; }
        select.form-select option { background: #fff; color: var(--text); }
        .input-group > .form-control:not(:first-child),
        .input-group > .form-select:not(:first-child) { border-left: 0; }
        .input-group-text + .form-control { border-left: 0; }
        hr { border-color: var(--border); opacity: 1; }
        .dropdown-divider { border-color: var(--border); }
        code { background: #f1f5f9; color: #e11d48; padding: .1em .4em; border-radius: .3rem; font-size: .85em; }
        .text-success { color: var(--success) !important; }
        .text-danger  { color: var(--danger) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-info    { color: var(--info) !important; }
        .text-primary { color: var(--primary) !important; }
        .bg-white { background: var(--surface) !important; }
        .border { border-color: var(--border) !important; }
        .rounded { border-radius: var(--radius) !important; }
        .shadow-sm { box-shadow: var(--shadow-sm) !important; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div id="sidebarOverlay"></div>

{{-- SIDEBAR --}}
<aside id="sidebar">
    <div class="sidebar-brand">
        <div class="logo">
            <div class="logo-icon"><i class="fa fa-motorcycle"></i></div>
            <div>
                <div class="logo-text">fajarmotor</div>
                <div class="logo-sub">{{ auth()->user()?->branch?->name ?? 'Semua Cabang' }}</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link @active('dashboard')">
            <span class="icon"><i class="fa fa-gauge-high"></i></span> Dashboard
        </a>

        <div class="nav-section-label">Produk & Stok</div>
        @can('products.view')
        <a href="{{ route('products.index') }}" class="sidebar-link @active('products.*')">
            <span class="icon"><i class="fa fa-box"></i></span> Produk
        </a>
        @endcan
        @can('stock.view')
        <a href="{{ route('stock.index') }}" class="sidebar-link @active('stock.*')">
            <span class="icon"><i class="fa fa-warehouse"></i></span> Stok
        </a>
        @endcan

        <div class="nav-section-label">Transaksi</div>
        @can('sales.create')
        <a href="{{ route('sales.pos') }}" class="sidebar-link @active('sales.pos')">
            <span class="icon"><i class="fa fa-cash-register"></i></span> POS / Kasir
        </a>
        @endcan
        @can('sales.view')
        <a href="{{ route('sales.index') }}" class="sidebar-link @active('sales.index')">
            <span class="icon"><i class="fa fa-receipt"></i></span> Penjualan
        </a>
        @endcan
        @can('wo.view')
        <a href="{{ route('work-orders.index') }}" class="sidebar-link @active('work-orders.*')">
            <span class="icon"><i class="fa fa-wrench"></i></span> Work Order
        </a>
        @endcan

        <div class="nav-section-label">Pembelian</div>
        @can('po.view')
        <a href="{{ route('purchase-orders.index') }}" class="sidebar-link @active('purchase-orders.*')">
            <span class="icon"><i class="fa fa-file-invoice"></i></span> Purchase Order
        </a>
        @endcan
        @can('suppliers.view')
        <a href="{{ route('suppliers.index') }}" class="sidebar-link @active('suppliers.*')">
            <span class="icon"><i class="fa fa-truck"></i></span> Supplier
        </a>
        @endcan

        <div class="nav-section-label">Laporan</div>
        @can('reports.view')
        <a href="{{ route('reports.sales') }}" class="sidebar-link @active('reports.sales')">
            <span class="icon"><i class="fa fa-chart-bar"></i></span> Lap. Penjualan
        </a>
        <a href="{{ route('reports.stock') }}" class="sidebar-link @active('reports.stock')">
            <span class="icon"><i class="fa fa-chart-pie"></i></span> Lap. Stok
        </a>
        <a href="{{ route('reports.profit') }}" class="sidebar-link @active('reports.profit')">
            <span class="icon"><i class="fa fa-coins"></i></span> Lap. Profit
        </a>
        @endcan

        @hasrole('admin')
        <div class="nav-section-label">Admin</div>
        <a href="{{ route('admin.users') }}" class="sidebar-link @active('admin.*')">
            <span class="icon"><i class="fa fa-users-gear"></i></span> Kelola Pengguna
        </a>
        @endhasrole
    </nav>
</aside>

{{-- MAIN --}}
<div id="main">
    {{-- TOPBAR --}}
    <header id="topbar">
        <button class="topbar-btn d-md-none" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>
        <div class="topbar-title d-none d-md-block">@yield('title', 'Dashboard')</div>
        <div class="topbar-actions ms-auto">
            <span class="text-muted" style="font-size:.75rem"><i class="fa fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM YYYY') }}</span>
            <button id="darkToggle" title="Toggle Dark Mode">
                <i class="fa fa-moon"></i>
            </button>
            <div class="dropdown">
                <div class="user-pill dropdown-toggle" data-bs-toggle="dropdown" style="list-style:none">
                    @if(auth()->user()?->avatar)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}" alt="avatar" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:2px solid var(--primary-light)">
                    @else
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 2)) }}</div>
                    @endif
                    <span class="user-name d-none d-sm-inline">{{ auth()->user()?->name }}</span>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2">
                        <div class="fw-semibold" style="font-size:.8rem">{{ auth()->user()?->name }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ auth()->user()?->getRoleNames()?->first() ?? 'User' }}</div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fa fa-user-circle me-2"></i>Edit Profil</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="fa fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animate-in mb-3" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show animate-in mb-3" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });

    // ── DARK MODE ──
    const html = document.documentElement;
    const btn  = document.getElementById('darkToggle');
    const icon = btn.querySelector('i');

    function applyDark(dark) {
        html.classList.toggle('dark', dark);
        icon.className = dark ? 'fa fa-sun' : 'fa fa-moon';
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    }

    // Load saved preference, fallback to system preference
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyDark(saved ? saved === 'dark' : prefersDark);

    btn.addEventListener('click', () => applyDark(!html.classList.contains('dark')));
</script>
@stack('scripts')
</body>
</html>
