@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ── HERO ── */
.dash-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #312e81 100%);
    border: 1px solid var(--border); border-radius: var(--radius);
    padding: 24px 28px; position: relative; overflow: hidden; margin-bottom: 20px;
}
.dash-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 10% 50%, rgba(99,102,241,.15), transparent 50%),
        radial-gradient(ellipse at 90% 10%, rgba(139,92,246,.1), transparent 50%);
    pointer-events: none;
}
.dash-hero-grid {
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,.015) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,.015) 1px, transparent 1px);
    background-size: 40px 40px; pointer-events: none;
}
.hero-content { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.3);
    border-radius: 20px; padding: 3px 12px;
    font-size: 10px; font-weight: 700; color: #a5b4fc;
    letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px;
}
.hero-eyebrow .dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
.hero-title { font-size: 22px; font-weight: 900; color: #f8fafc; margin-bottom: 4px; }
.hero-title span { background: linear-gradient(90deg, #818cf8, #c4b5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.hero-sub { color: #94a3b8; font-size: 13px; margin-bottom: 16px; }
.hero-kpi { display: flex; gap: 0; background: rgba(0,0,0,.3); border: 1px solid rgba(255,255,255,.08); border-radius: 10px; overflow: hidden; width: fit-content; }
.hero-kpi-item { padding: 10px 20px; text-align: center; border-right: 1px solid rgba(255,255,255,.08); }
.hero-kpi-item:last-child { border-right: none; }
.kpi-val { font-size: 18px; font-weight: 800; }
.kpi-lbl { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 1px; }
.hero-moto { position: absolute; right: 28px; top: 50%; transform: translateY(-50%); font-size: 90px; opacity: .06; pointer-events: none; }

/* ── ROLE BANNER ── */
.role-banner {
    border-radius: var(--radius); padding: 14px 18px;
    display: flex; align-items: center; gap: 14px;
    border: 1px solid; margin-bottom: 20px;
}
.role-banner .rb-ico { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.role-banner .rb-title { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
.role-banner .rb-desc { font-size: 11px; color: var(--muted); }

/* ── QUICK ACTIONS ── */
.qa-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.qa-btn {
    background: rgba(255,255,255,.03); border: 1px solid var(--border);
    border-radius: 12px; padding: 16px 10px;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    cursor: pointer; transition: all .2s; text-decoration: none; text-align: center;
}
.qa-btn:hover { transform: translateY(-2px); background: rgba(255,255,255,.06); border-color: var(--primary); }
.qa-btn .qa-ico { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.qa-btn span { font-size: 11px; font-weight: 600; color: var(--muted); }
@media(max-width:600px) { .qa-grid { grid-template-columns: repeat(2,1fr); } }
</style>
@endpush


@section('content')

{{-- HERO --}}
<div class="dash-hero animate-in">
    <div class="dash-hero-grid"></div>
    <div class="hero-content">
        <div class="hero-eyebrow"><span class="dot"></span> Live Dashboard</div>
        <div class="hero-title">Selamat Datang, <span>{{ auth()->user()?->name }}</span> 👋</div>
        <div class="hero-sub">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} &nbsp;·&nbsp; FajarMotor Workshop System</div>
        <div class="hero-kpi">
            <div class="hero-kpi-item"><div class="kpi-val" style="color:#6366f1" id="kpi-prod">—</div><div class="kpi-lbl">Produk</div></div>
            <div class="hero-kpi-item"><div class="kpi-val" style="color:#10b981" id="kpi-rev">—</div><div class="kpi-lbl">Penjualan Hari Ini</div></div>
            <div class="hero-kpi-item"><div class="kpi-val" style="color:#f59e0b" id="kpi-low">—</div><div class="kpi-lbl">Stok Menipis</div></div>
            <div class="hero-kpi-item"><div class="kpi-val" style="color:#ef4444" id="kpi-po">—</div><div class="kpi-lbl">PO Pending</div></div>
            <div class="hero-kpi-item"><div class="kpi-val" style="color:#8b5cf6" id="kpi-wo">—</div><div class="kpi-lbl">WO Aktif</div></div>
        </div>
    </div>
    <div class="hero-moto">🏍️</div>
</div>

{{-- ROLE BANNER --}}
@hasrole('admin')
<div class="role-banner animate-in" style="background:rgba(99,102,241,.06);border-color:rgba(99,102,241,.2)">
    <div class="rb-ico" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="fa fa-shield-halved"></i></div>
    <div class="flex-grow-1">
        <div class="rb-title" style="color:var(--text)">Mode Admin — Akses Penuh</div>
        <div class="rb-desc">Kelola semua fitur: produk, stok, penjualan, laporan, dan pengguna sistem.</div>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-sm" style="background:rgba(99,102,241,.15);color:#6366f1;border:none;white-space:nowrap;flex-shrink:0">
        <i class="fa fa-users-gear me-1"></i>Kelola Pengguna
    </a>
</div>
@endhasrole
@hasrole('cashier')
<div class="role-banner animate-in" style="background:rgba(16,185,129,.06);border-color:rgba(16,185,129,.2)">
    <div class="rb-ico" style="background:rgba(16,185,129,.15);color:#10b981"><i class="fa fa-cash-register"></i></div>
    <div class="flex-grow-1">
        <div class="rb-title" style="color:var(--text)">Mode Kasir</div>
        <div class="rb-desc">Akses ke POS Kasir, riwayat penjualan, dan work order.</div>
    </div>
    @can('sales.create')
    <a href="{{ route('sales.pos') }}" class="btn btn-sm" style="background:rgba(16,185,129,.15);color:#10b981;border:none;white-space:nowrap;flex-shrink:0">
        <i class="fa fa-cash-register me-1"></i>Buka Kasir
    </a>
    @endcan
</div>
@endhasrole
@hasrole('warehouse')
<div class="role-banner animate-in" style="background:rgba(245,158,11,.06);border-color:rgba(245,158,11,.2)">
    <div class="rb-ico" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="fa fa-warehouse"></i></div>
    <div class="flex-grow-1">
        <div class="rb-title" style="color:var(--text)">Mode Gudang</div>
        <div class="rb-desc">Akses ke manajemen stok, produk, dan purchase order.</div>
    </div>
    @can('stock.view')
    <a href="{{ route('stock.index') }}" class="btn btn-sm" style="background:rgba(245,158,11,.15);color:#f59e0b;border:none;white-space:nowrap;flex-shrink:0">
        <i class="fa fa-warehouse me-1"></i>Lihat Stok
    </a>
    @endcan
</div>
@endhasrole

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card" style="border-color:rgba(99,102,241,.2)">
            <div class="d-flex align-items-start justify-content-between">
                <div class="stat-icon" style="background:#ede9fe;color:#6366f1"><i class="fa fa-box"></i></div>
                <span class="badge badge-soft-purple">Aktif</span>
            </div>
            <div class="stat-value" style="color:#6366f1" id="stat-total-products">
                <span class="placeholder-glow"><span class="placeholder col-4"></span></span>
            </div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-sub"><i class="fa fa-layer-group" style="color:#6366f1"></i> Semua kategori</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card" style="border-color:rgba(245,158,11,.2)">
            <div class="d-flex align-items-start justify-content-between">
                <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fa fa-triangle-exclamation"></i></div>
                <span class="badge" id="stat-low-stock-badge">...</span>
            </div>
            <div class="stat-value" style="color:#f59e0b" id="stat-low-stock">
                <span class="placeholder-glow"><span class="placeholder col-4"></span></span>
            </div>
            <div class="stat-label">Stok Menipis</div>
            <div class="stat-sub"><i class="fa fa-bell" style="color:#f59e0b"></i> Perlu perhatian</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card" style="border-color:rgba(16,185,129,.2)">
            <div class="d-flex align-items-start justify-content-between">
                <div class="stat-icon" style="background:#d1fae5;color:#10b981"><i class="fa fa-cash-register"></i></div>
                <span class="badge badge-soft-success">Hari Ini</span>
            </div>
            <div class="stat-value" style="color:#10b981;font-size:1.2rem" id="stat-sales-today">
                <span class="placeholder-glow"><span class="placeholder col-6"></span></span>
            </div>
            <div class="stat-label">Penjualan Hari Ini</div>
            <div class="stat-sub"><i class="fa fa-clock" style="color:#10b981"></i> Update real-time</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card" style="border-color:rgba(59,130,246,.2)">
            <div class="d-flex align-items-start justify-content-between">
                <div class="stat-icon" style="background:#dbeafe;color:#3b82f6"><i class="fa fa-chart-line"></i></div>
                <span class="badge badge-soft-info">Bulan Ini</span>
            </div>
            <div class="stat-value" style="color:#3b82f6;font-size:1.2rem" id="stat-sales-month">
                <span class="placeholder-glow"><span class="placeholder col-6"></span></span>
            </div>
            <div class="stat-label">Penjualan Bulan Ini</div>
            <div class="stat-sub"><i class="fa fa-calendar" style="color:#3b82f6"></i> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY') }}</div>
        </div>
    </div>
</div>

{{-- PENDING ALERTS --}}
<div id="pending-alerts" class="mb-4" style="display:none">
    <div class="row g-3">
        <div class="col-sm-6" id="pending-po-wrap" style="display:none">
            <a id="btn-pending-po" href="{{ route('purchase-orders.index') }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:var(--radius)">
                <div style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,.15);color:#ef4444;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fa fa-file-invoice"></i></div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text)"><span id="pending-po-count">0</span> Purchase Order Pending</div>
                    <div style="font-size:11px;color:var(--muted)">Menunggu konfirmasi atau penerimaan</div>
                </div>
                <i class="fa fa-arrow-right ms-auto" style="color:#ef4444"></i>
            </a>
        </div>
        <div class="col-sm-6" id="pending-wo-wrap" style="display:none">
            <a id="btn-pending-wo" href="{{ route('work-orders.index') }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background:rgba(139,92,246,.06);border:1px solid rgba(139,92,246,.2);border-radius:var(--radius)">
                <div style="width:38px;height:38px;border-radius:10px;background:rgba(139,92,246,.15);color:#8b5cf6;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fa fa-wrench"></i></div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text)"><span id="pending-wo-count">0</span> Work Order Aktif</div>
                    <div style="font-size:11px;color:var(--muted)">Sedang dalam proses pengerjaan</div>
                </div>
                <i class="fa fa-arrow-right ms-auto" style="color:#8b5cf6"></i>
            </a>
        </div>
    </div>
</div>


{{-- QUICK ACTIONS --}}
<div class="card animate-in mb-4">
    <div class="card-header">
        <div class="icon-badge" style="background:#ede9fe;color:#6366f1"><i class="fa fa-bolt"></i></div>
        Aksi Cepat
    </div>
    <div class="card-body">
        <div class="qa-grid">
            @can('products.create')
            <a href="{{ route('products.create') }}" class="qa-btn" style="border-color:rgba(99,102,241,.2)">
                <div class="qa-ico" style="background:rgba(99,102,241,.12);color:#6366f1"><i class="fa fa-plus"></i></div>
                <span>Produk Baru</span>
            </a>
            @endcan
            @can('stock.in')
            <a href="{{ route('stock.index') }}" class="qa-btn" style="border-color:rgba(16,185,129,.2)">
                <div class="qa-ico" style="background:rgba(16,185,129,.12);color:#10b981"><i class="fa fa-arrow-down"></i></div>
                <span>Stok Masuk</span>
            </a>
            @endcan
            @can('po.create')
            <a href="{{ route('purchase-orders.create') }}" class="qa-btn" style="border-color:rgba(59,130,246,.2)">
                <div class="qa-ico" style="background:rgba(59,130,246,.12);color:#3b82f6"><i class="fa fa-file-invoice"></i></div>
                <span>Buat PO</span>
            </a>
            @endcan
            @can('wo.create')
            <a href="{{ route('work-orders.create') }}" class="qa-btn" style="border-color:rgba(245,158,11,.2)">
                <div class="qa-ico" style="background:rgba(245,158,11,.12);color:#f59e0b"><i class="fa fa-wrench"></i></div>
                <span>Work Order</span>
            </a>
            @endcan
            @can('sales.create')
            <a href="{{ route('sales.pos') }}" class="qa-btn" style="border-color:rgba(239,68,68,.2)">
                <div class="qa-ico" style="background:rgba(239,68,68,.12);color:#ef4444"><i class="fa fa-cash-register"></i></div>
                <span>Buka Kasir</span>
            </a>
            @endcan
            @can('reports.view')
            <a href="{{ route('reports.sales') }}" class="qa-btn" style="border-color:rgba(139,92,246,.2)">
                <div class="qa-ico" style="background:rgba(139,92,246,.12);color:#8b5cf6"><i class="fa fa-chart-bar"></i></div>
                <span>Laporan</span>
            </a>
            @endcan
        </div>
    </div>
</div>

{{-- TABLES --}}
<div class="row g-3">
    <div class="col-lg-7 animate-in">
        <div class="card h-100">
            <div class="card-header">
                <div class="icon-badge" style="background:#ede9fe;color:#6366f1"><i class="fa fa-receipt"></i></div>
                Transaksi Terbaru
                <a href="{{ route('sales.index') }}" class="btn btn-sm ms-auto" style="background:var(--bg);border:1px solid var(--border);color:var(--muted);font-size:.75rem">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Invoice</th><th>Pelanggan</th><th>Total</th><th>Kasir</th><th>Waktu</th></tr></thead>
                    <tbody id="recent-sales-body">
                        <tr><td colspan="5" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm" style="color:var(--primary)" role="status"></div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5 animate-in">
        <div class="card h-100">
            <div class="card-header">
                <div class="icon-badge" style="background:#fef3c7;color:#f59e0b"><i class="fa fa-triangle-exclamation"></i></div>
                Stok Menipis
                <a href="{{ route('stock.index') }}" class="btn btn-sm ms-auto" style="background:var(--bg);border:1px solid var(--border);color:var(--muted);font-size:.75rem">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Produk</th><th>Stok</th><th>Min</th></tr></thead>
                    <tbody id="low-stock-body">
                        <tr><td colspan="3" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm" style="color:#f59e0b" role="status"></div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const fmt   = n => new Intl.NumberFormat('id-ID').format(n);
    const fmtRp = n => 'Rp ' + fmt(n);

    function renderSales(sales) {
        const tbody = document.getElementById('recent-sales-body');
        if (!sales.length) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4" style="color:var(--muted)">
                <i class="fa fa-receipt fa-2x d-block mb-2" style="opacity:.2"></i>Belum ada transaksi</td></tr>`;
            return;
        }
        tbody.innerHTML = sales.map(s => `
            <tr>
                <td><a href="/sales/${s.id}" class="fw-semibold text-decoration-none" style="color:var(--primary)">${s.invoice_number}</a></td>
                <td style="color:var(--text)">${s.customer}</td>
                <td class="fw-semibold" style="color:#10b981">${fmtRp(s.total)}</td>
                <td style="color:var(--muted)">${s.cashier ?? '-'}</td>
                <td><span class="badge badge-soft-gray">${s.time_ago}</span></td>
            </tr>`).join('');
    }

    function renderLowStock(products) {
        const tbody = document.getElementById('low-stock-body');
        if (!products.length) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-4" style="color:var(--muted)">
                <i class="fa fa-check-circle fa-2x d-block mb-2" style="color:#10b981;opacity:.5"></i>Semua stok aman</td></tr>`;
            return;
        }
        tbody.innerHTML = products.map(p => `
            <tr>
                <td>
                    <a href="/products/${p.id}" class="text-decoration-none fw-semibold" style="color:var(--text)">${p.name.substring(0,24)}${p.name.length>24?'…':''}</a>
                    <div style="font-size:.7rem;color:var(--muted)">${p.sku}</div>
                </td>
                <td><span class="badge badge-soft-danger">${p.total_stock}</span></td>
                <td style="color:var(--muted)">${p.min_stock}</td>
            </tr>`).join('');
    }

    function renderStats(stats) {
        document.getElementById('stat-total-products').textContent = fmt(stats.total_products);
        document.getElementById('kpi-prod').textContent = fmt(stats.total_products);

        const lb = document.getElementById('stat-low-stock-badge');
        lb.textContent = stats.low_stock > 0 ? 'Perlu Restock' : 'Aman';
        lb.className = 'badge ' + (stats.low_stock > 0 ? 'badge-soft-warning' : 'badge-soft-success');
        document.getElementById('stat-low-stock').textContent = fmt(stats.low_stock);
        document.getElementById('kpi-low').textContent = fmt(stats.low_stock);

        document.getElementById('stat-sales-today').textContent = fmtRp(stats.sales_today);
        document.getElementById('kpi-rev').textContent = fmtRp(stats.sales_today);
        document.getElementById('stat-sales-month').textContent = fmtRp(stats.sales_month);

        document.getElementById('kpi-po').textContent = fmt(stats.pending_po ?? 0);
        document.getElementById('kpi-wo').textContent = fmt(stats.pending_wo ?? 0);

        // Show pending PO alert
        if (stats.pending_po > 0) {
            document.getElementById('pending-po-wrap').style.display = '';
            document.getElementById('pending-po-count').textContent = stats.pending_po;
            document.getElementById('pending-alerts').style.display = '';
        }

        // Show pending WO alert
        if (stats.pending_wo > 0) {
            document.getElementById('pending-wo-wrap').style.display = '';
            document.getElementById('pending-wo-count').textContent = stats.pending_wo;
            document.getElementById('pending-alerts').style.display = '';
        }
    }

    fetch('{{ route("dashboard.data") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        renderStats(data.stats);
        renderSales(data.recent_sales);
        renderLowStock(data.low_stock_products);
    })
    .catch(err => {
        console.error('Dashboard data error:', err);
        document.getElementById('recent-sales-body').innerHTML =
            '<tr><td colspan="5" class="text-center py-3" style="color:var(--danger)"><i class="fa fa-exclamation-circle me-2"></i>Gagal memuat data</td></tr>';
        document.getElementById('low-stock-body').innerHTML =
            '<tr><td colspan="3" class="text-center py-3" style="color:var(--danger)"><i class="fa fa-exclamation-circle me-2"></i>Gagal memuat data</td></tr>';
    });
})();
</script>
@endpush
