@extends('layouts.landing')
@section('title','fajarmotor — Bengkel Motor Profesional')

@push('styles')
<style>
/* ══ HERO ══════════════════════════════════════════════════════════ */
#hero{min-height:100vh;position:relative;display:flex;align-items:center;overflow:hidden;padding:7rem 0 5rem}
.hero-bg{position:absolute;inset:0;background:linear-gradient(145deg,#020617 0%,#0f172a 40%,#1e1b4b 75%,#312e81 100%)}
.hero-bg-photo{position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1800&q=85') center/cover no-repeat;opacity:.08;transform:scale(1.05);transition:transform 8s ease}
.hero-particles{position:absolute;inset:0;overflow:hidden}
.particle{position:absolute;border-radius:50%;animation:float-particle linear infinite;opacity:0}
@keyframes float-particle{0%{transform:translateY(100vh) rotate(0deg);opacity:0}10%{opacity:.6}90%{opacity:.6}100%{transform:translateY(-100px) rotate(720deg);opacity:0}}
.hero-gradient-orb{position:absolute;border-radius:50%;filter:blur(80px);animation:orb-drift 12s ease-in-out infinite alternate}
.orb1{width:600px;height:600px;background:radial-gradient(circle,rgba(99,102,241,.18),transparent 70%);top:-200px;right:-100px}
.orb2{width:400px;height:400px;background:radial-gradient(circle,rgba(139,92,246,.12),transparent 70%);bottom:-100px;left:-100px;animation-delay:-6s}
.orb3{width:300px;height:300px;background:radial-gradient(circle,rgba(245,158,11,.08),transparent 70%);top:30%;left:30%;animation-delay:-3s}
@keyframes orb-drift{0%{transform:translate(0,0) scale(1)}100%{transform:translate(30px,20px) scale(1.1)}}
.hero-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center;position:relative;z-index:2}
.hero-eyebrow{display:inline-flex;align-items:center;gap:.5rem;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.25);border-radius:99px;padding:.35rem 1rem;font-size:.72rem;font-weight:700;color:#a5b4fc;margin-bottom:1.5rem;animation:fadeUp .8s ease both}
.hero-eyebrow .dot{width:6px;height:6px;background:#10b981;border-radius:50%;animation:pulse-ring 2s infinite}
.hero-h1{font-size:clamp(2.2rem,4.5vw,3.6rem);font-weight:900;color:#f8fafc;line-height:1.1;margin-bottom:1.5rem;animation:fadeUp .8s .1s ease both}
.hero-h1 .grad{background:linear-gradient(135deg,#818cf8 0%,#c4b5fd 50%,#f0abfc 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-size:200% auto;animation:gradient-shift 4s ease infinite}
.hero-desc{color:#94a3b8;font-size:.95rem;line-height:1.9;margin-bottom:2.25rem;animation:fadeUp .8s .2s ease both;max-width:480px}
.hero-actions{display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:3rem;animation:fadeUp .8s .3s ease both}
.hero-cta-primary{display:inline-flex;align-items:center;gap:.65rem;padding:.9rem 2rem;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:.75rem;font-size:.9rem;font-weight:700;color:#fff;text-decoration:none;box-shadow:0 8px 32px rgba(99,102,241,.45),inset 0 1px 0 rgba(255,255,255,.15);transition:all .25s;position:relative;overflow:hidden}
.hero-cta-primary::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.12),transparent);opacity:0;transition:opacity .25s}
.hero-cta-primary:hover{transform:translateY(-2px);box-shadow:0 16px 48px rgba(99,102,241,.55)}
.hero-cta-primary:hover::before{opacity:1}
.hero-cta-secondary{display:inline-flex;align-items:center;gap:.65rem;padding:.9rem 2rem;border:1.5px solid rgba(255,255,255,.15);border-radius:.75rem;font-size:.9rem;font-weight:600;color:#cbd5e1;text-decoration:none;transition:all .25s;backdrop-filter:blur(8px)}
.hero-cta-secondary:hover{border-color:rgba(255,255,255,.4);color:#fff;background:rgba(255,255,255,.05)}
.hero-trust{display:flex;align-items:center;gap:1rem;animation:fadeUp .8s .4s ease both}
.trust-avatars{display:flex}
.trust-avatars img{width:32px;height:32px;border-radius:50%;border:2px solid #1e1b4b;margin-left:-8px;object-fit:cover}
.trust-avatars img:first-child{margin-left:0}
.trust-text{font-size:.78rem;color:#64748b}
.trust-text strong{color:#94a3b8}

/* Hero Visual */
.hero-visual{position:relative;animation:fadeUp .8s .2s ease both}
.hv-main{border-radius:1.5rem;overflow:hidden;position:relative;box-shadow:0 32px 80px rgba(0,0,0,.5),0 0 0 1px rgba(255,255,255,.06)}
.hv-main img{width:100%;height:380px;object-fit:cover;display:block;transition:transform 8s ease}
.hv-main:hover img{transform:scale(1.03)}
.hv-main-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.7) 0%,transparent 50%)}
.hv-badge{position:absolute;top:1.25rem;left:1.25rem;background:rgba(15,23,42,.75);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.1);border-radius:.85rem;padding:.85rem 1.1rem}
.hv-badge-val{color:#10b981;font-size:1.15rem;font-weight:800;line-height:1}
.hv-badge-lbl{color:#64748b;font-size:.68rem;margin-top:.2rem}
.hv-badge2{position:absolute;bottom:1.25rem;right:1.25rem;background:rgba(15,23,42,.75);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.1);border-radius:.85rem;padding:.85rem 1.1rem;display:flex;align-items:center;gap:.65rem}
.hv-badge2-icon{width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem}
.hv-badge2-val{color:#f1f5f9;font-size:.85rem;font-weight:700;line-height:1.2}
.hv-badge2-lbl{color:#64748b;font-size:.68rem}
.hv-cards{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;margin-top:.85rem}
.hv-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:1rem;padding:1rem;backdrop-filter:blur(8px);transition:all .25s}
.hv-card:hover{background:rgba(255,255,255,.07);border-color:rgba(99,102,241,.3);transform:translateY(-2px)}
.hv-card-icon{width:32px;height:32px;border-radius:.45rem;display:flex;align-items:center;justify-content:center;font-size:.8rem;margin-bottom:.65rem}
.hv-card-val{color:#f1f5f9;font-size:1rem;font-weight:700;line-height:1}
.hv-card-lbl{color:#64748b;font-size:.68rem;margin-top:.2rem}

/* ══ STATS ══════════════════════════════════════════════════════════ */
.stats-section{background:linear-gradient(135deg,#6366f1,#4f46e5);padding:3.5rem 0;position:relative;overflow:hidden}
.stats-section::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.04'%3E%3Cpath d='M20 20.5V18H0v5h5v5H0v5h20v-9.5zm-2 4.5h-1v-1h1v1zm-1-7h1v1h-1v-1zM0 0h20v2H0V0zm0 8h20v2H0V8z'/%3E%3C/g%3E%3C/svg%3E")}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;position:relative;z-index:1}
.stat-item{text-align:center}
.stat-num{font-size:2.5rem;font-weight:900;color:#fff;line-height:1;display:flex;align-items:baseline;justify-content:center;gap:.15rem}
.stat-num .suffix{font-size:1.5rem;color:rgba(255,255,255,.7)}
.stat-lbl{color:rgba(255,255,255,.65);font-size:.8rem;margin-top:.4rem;font-weight:500}
.stat-divider{width:1px;background:rgba(255,255,255,.15);margin:auto}

@media(max-width:768px){
  .hero-inner{grid-template-columns:1fr;gap:3rem}
  .hero-visual{display:none}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .stat-divider{display:none}
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<section id="hero">
  <div class="hero-bg"></div>
  <div class="hero-bg-photo" id="heroBgPhoto"></div>
  <div class="hero-particles" id="particles"></div>
  <div class="hero-gradient-orb orb1"></div>
  <div class="hero-gradient-orb orb2"></div>
  <div class="hero-gradient-orb orb3"></div>

  <div class="hero-inner">
    <div>
      <div class="hero-eyebrow">
        <span class="dot"></span>
        Sistem Bengkel Motor #1 Indonesia
      </div>
      <h1 class="hero-h1">
        Bengkel Motor<br>
        <span class="grad">Profesional</span><br>
        Harga Hemat
      </h1>
      <p class="hero-desc">Kelola bengkel motor Anda dengan sistem terpadu — sparepart, servis kendaraan, kasir POS, hingga laporan profit. Semua dalam satu platform modern.</p>
      <div class="hero-actions">
        @auth
          <a href="{{ route('dashboard') }}" class="hero-cta-primary">
            <i class="fa fa-gauge-high"></i> Buka Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="hero-cta-primary">
            <i class="fa fa-rocket"></i> Mulai Sekarang — Gratis
          </a>
        @endauth
        <a href="#layanan" class="hero-cta-secondary">
          <i class="fa fa-play-circle"></i> Lihat Layanan
        </a>
      </div>
      <div class="hero-trust">
        <div class="trust-avatars">
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&q=80" alt="">
          <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=64&q=80" alt="">
          <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=64&q=80" alt="">
          <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&q=80" alt="">
        </div>
        <div class="trust-text">Dipercaya <strong>500+ bengkel</strong> di seluruh Indonesia</div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hv-main">
        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900&q=85" alt="Bengkel Motor Profesional">
        <div class="hv-main-overlay"></div>
        <div class="hv-badge">
          <div class="hv-badge-val">Rp 4.250.000</div>
          <div class="hv-badge-lbl">Pendapatan Hari Ini</div>
        </div>
        <div class="hv-badge2">
          <div class="hv-badge2-icon"><i class="fa fa-check"></i></div>
          <div>
            <div class="hv-badge2-val">24 Servis</div>
            <div class="hv-badge2-lbl">Selesai hari ini</div>
          </div>
        </div>
      </div>
      <div class="hv-cards">
        <div class="hv-card">
          <div class="hv-card-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="fa fa-box"></i></div>
          <div class="hv-card-val">500+</div>
          <div class="hv-card-lbl">Jenis Sparepart</div>
        </div>
        <div class="hv-card">
          <div class="hv-card-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="fa fa-triangle-exclamation"></i></div>
          <div class="hv-card-val" style="color:#f59e0b">5 Item</div>
          <div class="hv-card-lbl">Stok Menipis</div>
        </div>
        <div class="hv-card">
          <div class="hv-card-icon" style="background:rgba(99,102,241,.15);color:#818cf8"><i class="fa fa-wrench"></i></div>
          <div class="hv-card-val">7 Motor</div>
          <div class="hv-card-lbl">WO Aktif</div>
        </div>
        <div class="hv-card">
          <div class="hv-card-icon" style="background:rgba(239,68,68,.15);color:#f87171"><i class="fa fa-receipt"></i></div>
          <div class="hv-card-val">24 Nota</div>
          <div class="hv-card-lbl">Transaksi Hari Ini</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ STATS ══ --}}
<div class="stats-section">
  <div class="container stats-grid">
    <div class="stat-item reveal">
      <div class="stat-num"><span data-target="500">0</span><span class="suffix">+</span></div>
      <div class="stat-lbl">Jenis Sparepart Tersedia</div>
    </div>
    <div class="stat-item reveal d1">
      <div class="stat-num"><span data-target="50">0</span><span class="suffix">+</span></div>
      <div class="stat-lbl">Merek Motor Didukung</div>
    </div>
    <div class="stat-item reveal d2">
      <div class="stat-num"><span data-target="1200">0</span><span class="suffix">+</span></div>
      <div class="stat-lbl">Pelanggan Puas</div>
    </div>
    <div class="stat-item reveal d3">
      <div class="stat-num"><span data-target="3">0</span></div>
      <div class="stat-lbl">Cabang Aktif</div>
    </div>
  </div>
</div>


{{-- ══ LAYANAN ══ --}}
<section id="layanan" style="background:var(--light)">
  <div class="container">
    <div class="tc reveal">
      <span class="section-chip"><i class="fa fa-star"></i> Layanan Kami</span>
      <h2 class="section-title">Solusi Lengkap untuk <span>Bengkel Motor</span> Anda</h2>
      <p class="section-desc">Dari servis harian hingga penggantian sparepart besar, kami siap melayani semua jenis motor dengan teknisi berpengalaman.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem">
      @php
      $svcs=[
        ['Servis Reguler','Servis standar motor sesuai rekomendasi pabrikan. Ganti oli, filter, busi, dan pengecekan menyeluruh untuk performa optimal.','https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80','fa-wrench','#ede9fe','#6366f1'],
        ['Sparepart Lengkap','Ribuan jenis sparepart original & aftermarket untuk semua merek motor tersedia dengan harga kompetitif dan terjamin keasliannya.','https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=600&q=80','fa-box','#d1fae5','#059669'],
        ['Tune Up & Overhaul','Penyetelan mesin menyeluruh untuk mengembalikan performa motor ke kondisi prima. Dikerjakan teknisi bersertifikat.','https://images.unsplash.com/photo-1615906655593-ad0386982a0f?w=600&q=80','fa-cog','#dbeafe','#2563eb'],
        ['Servis Kelistrikan','Perbaikan aki, CDI, lampu, starter, dan sistem injeksi dengan alat diagnosa modern dan akurat.','https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=600&q=80','fa-bolt','#fef3c7','#d97706'],
        ['Rem & Suspensi','Penggantian kampas rem, cakram, shock absorber, dan per suspensi untuk keselamatan berkendara optimal.','https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=600&q=80','fa-circle-dot','#fee2e2','#dc2626'],
        ['Sistem Manajemen','Platform digital untuk kelola stok, transaksi, work order, dan laporan keuangan secara real-time dan akurat.','https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&q=80','fa-chart-bar','#f0fdf4','#16a34a'],
      ];
      @endphp
      @foreach($svcs as $i=>[$t,$d,$img,$ic,$bg,$col])
      <div class="svc-card reveal d{{ ($i%3)+1 }}">
        <div class="svc-img-wrap">
          <img src="{{ $img }}" alt="{{ $t }}" loading="lazy">
          <div class="svc-overlay"></div>
        </div>
        <div class="svc-body">
          <div class="svc-icon" style="background:{{ $bg }};color:{{ $col }}"><i class="fa {{ $ic }}"></i></div>
          <h3 class="svc-title">{{ $t }}</h3>
          <p class="svc-desc">{{ $d }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ SPAREPART GALLERY ══ --}}
<section id="sparepart" style="background:#fff">
  <div class="container">
    <div class="tc reveal">
      <span class="section-chip"><i class="fa fa-box"></i> Koleksi Sparepart</span>
      <h2 class="section-title">Sparepart <span>Lengkap & Berkualitas</span></h2>
      <p class="section-desc">Tersedia ribuan jenis sparepart original dan aftermarket dengan harga kompetitif untuk semua jenis motor.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.1rem;margin-top:3.5rem">
      @php
      $parts=[
        ['Piston & Ring','https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=400&q=80','Mesin'],
        ['Filter Oli','https://images.unsplash.com/photo-1615906655593-ad0386982a0f?w=400&q=80','Mesin'],
        ['Kampas Rem','https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=400&q=80','Rem'],
        ['Aki Motor','https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=400&q=80','Kelistrikan'],
        ['Rantai & Sproket','https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=400&q=80','Transmisi'],
        ['Busi NGK','https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80','Mesin'],
        ['Shock Absorber','https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80','Suspensi'],
        ['Oli Mesin','https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&q=80','Pelumas'],
      ];
      @endphp
      @foreach($parts as $i=>[$name,$img,$cat])
      <div class="part-card reveal d{{ ($i%4)+1 }}">
        <div class="part-img-wrap">
          <img src="{{ $img }}" alt="{{ $name }}" loading="lazy">
          <div class="part-cat">{{ $cat }}</div>
        </div>
        <div class="part-body">
          <div class="part-name">{{ $name }}</div>
          <div class="part-status">
            <span class="status-dot"></span>Stok Tersedia
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div style="text-align:center;margin-top:3rem" class="reveal">
      <a href="{{ route('login') }}" class="btn-all-parts">
        <i class="fa fa-box"></i> Lihat Semua Sparepart di Sistem
      </a>
    </div>
  </div>
</section>

{{-- ══ FOTO BENGKEL ══ --}}
<section id="bengkel" style="background:var(--light)">
  <div class="container">
    <div class="tc reveal">
      <span class="section-chip"><i class="fa fa-building"></i> Fasilitas Bengkel</span>
      <h2 class="section-title">Bengkel <span>Modern & Nyaman</span></h2>
      <p class="section-desc">Dilengkapi peralatan modern, ruang tunggu nyaman, dan sistem manajemen digital untuk pengalaman servis terbaik.</p>
    </div>
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:260px 260px;gap:1rem;margin-top:3.5rem">
      <div class="gallery-main reveal-left">
        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900&q=85" alt="Area Servis Utama" loading="lazy">
        <div class="gallery-overlay"></div>
        <div class="gallery-caption">
          <div class="caption-title">Area Servis Utama</div>
          <div class="caption-sub">Kapasitas 10 motor sekaligus</div>
        </div>
      </div>
      @foreach([
        ['https://images.unsplash.com/photo-1615906655593-ad0386982a0f?w=500&q=80','Gudang Sparepart'],
        ['https://images.unsplash.com/photo-1609630875171-b1321377ee65?w=500&q=80','Rak Stok Terorganisir'],
        ['https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=500&q=80','Area Kelistrikan'],
        ['https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&q=80','Ruang Tunggu'],
      ] as $i=>[$img,$label])
      <div class="gallery-item reveal d{{ $i+1 }}">
        <img src="{{ $img }}" alt="{{ $label }}" loading="lazy">
        <div class="gallery-item-overlay"></div>
        <div class="gallery-item-label">{{ $label }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ══ TIM KARYAWAN ══ --}}
<section id="tim" style="background:#fff">
  <div class="container">
    <div class="tc reveal">
      <span class="section-chip"><i class="fa fa-users"></i> Tim Kami</span>
      <h2 class="section-title">Teknisi <span>Berpengalaman</span> & Bersertifikat</h2>
      <p class="section-desc">Tim kami terdiri dari teknisi profesional dengan pengalaman bertahun-tahun di bidang otomotif motor.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.75rem;margin-top:3.5rem">
      @php
      $team=[
        ['Budi Santoso','Kepala Mekanik','15 tahun','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80'],
        ['Ahmad Fauzi','Teknisi Senior','10 tahun','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80'],
        ['Rizky Pratama','Teknisi Kelistrikan','8 tahun','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&q=80'],
        ['Siti Rahayu','Kasir & Admin','5 tahun','https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80'],
      ];
      @endphp
      @foreach($team as $i=>[$n,$r,$exp,$ph])
      <div class="team-card reveal d{{ $i+1 }}">
        <div class="team-avatar-wrap">
          <div class="team-avatar">
            <img src="{{ $ph }}" alt="{{ $n }}" loading="lazy">
          </div>
          <div class="team-verified">
            <i class="fa fa-check"></i>
          </div>
        </div>
        <div class="team-name">{{ $n }}</div>
        <div class="team-role">{{ $r }}</div>
        <div class="team-exp">{{ $exp }} pengalaman</div>
        <div class="team-rating">
          @for($s=0;$s<5;$s++)<i class="fa fa-star"></i>@endfor
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ TESTIMONI ══ --}}
<section id="testimoni" style="background:var(--light)">
  <div class="container">
    <div class="tc reveal">
      <span class="section-chip"><i class="fa fa-quote-left"></i> Testimoni</span>
      <h2 class="section-title">Dipercaya <span>Ribuan Pelanggan</span></h2>
      <p class="section-desc">Kepuasan pelanggan adalah prioritas utama kami. Lihat apa kata mereka tentang layanan kami.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem">
      @php
      $testi=[
        ['Doni Kusuma','Pelanggan Setia, Jakarta','https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&q=80','Servis cepat dan hasilnya memuaskan! Motor saya yang sudah lama bermasalah akhirnya bisa normal kembali. Harga juga sangat terjangkau.'],
        ['Rina Wulandari','Pemilik Motor, Bandung','https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80','Sparepart lengkap dan original. Saya tidak perlu khawatir lagi soal keaslian barang. Teknisinya juga ramah dan profesional.'],
        ['Hendra Wijaya','Pemilik Bengkel, Surabaya','https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&q=80','Sistem manajemennya luar biasa! Stok terpantau real-time, laporan otomatis. Sangat membantu operasional bengkel saya.'],
      ];
      @endphp
      @foreach($testi as $i=>[$n,$r,$ph,$txt])
      <div class="testi-card reveal d{{ $i+1 }}">
        <div class="testi-stars">
          @for($s=0;$s<5;$s++)<i class="fa fa-star"></i>@endfor
        </div>
        <p class="testi-text">"{{ $txt }}"</p>
        <div class="testi-user">
          <img src="{{ $ph }}" alt="{{ $n }}" loading="lazy">
          <div>
            <div class="user-name">{{ $n }}</div>
            <div class="user-role">{{ $r }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ MEREK MOTOR ══ --}}
<section style="background:#fff;padding:3.5rem 0">
  <div class="container">
    <div class="tc reveal" style="margin-bottom:2.5rem">
      <p style="color:var(--muted);font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.12em">Kompatibel dengan semua merek motor populer Indonesia</p>
    </div>
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1rem;align-items:center" class="reveal">
      @foreach(['Honda','Yamaha','Suzuki','Kawasaki','TVS','Bajaj','Royal Enfield'] as $brand)
      <div class="brand-tag">
        {{ $brand }}
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ CTA ══ --}}
<section style="background:linear-gradient(145deg,#020617,#0f172a,#1e1b4b);padding:6rem 0;text-align:center;position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1800&q=80') center/cover no-repeat;opacity:.06"></div>
  <div style="position:absolute;inset:0;background:radial-gradient(circle at 50% 50%,rgba(99,102,241,.08),transparent 70%)"></div>
  <div class="container reveal" style="position:relative;z-index:1">
    <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.25);border-radius:99px;padding:.35rem 1rem;font-size:.72rem;font-weight:700;color:#a5b4fc;margin-bottom:1.5rem">
      <i class="fa fa-rocket"></i> Mulai Kelola Bengkel Anda
    </div>
    <h2 style="font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;color:#f8fafc;margin-bottom:.85rem;line-height:1.2">Siap Tingkatkan Efisiensi<br>Bengkel Anda?</h2>
    <p style="color:#94a3b8;font-size:.95rem;margin-bottom:2.5rem;max-width:520px;margin-left:auto;margin-right:auto">Masuk ke sistem dan mulai kelola sparepart, servis, dan keuangan bengkel dengan lebih mudah dan profesional.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      @auth
        <a href="{{ route('dashboard') }}" class="cta-primary">
          <i class="fa fa-gauge-high"></i> Buka Dashboard
        </a>
      @else
        <a href="{{ route('login') }}" class="cta-primary">
          <i class="fa fa-sign-in-alt"></i> Masuk ke Sistem
        </a>
      @endif
      <a href="#kontak" class="cta-secondary">
        <i class="fa fa-phone"></i> Hubungi Kami
      </a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
// Particle effect
const pc=document.getElementById('particles');
if (pc) {
  for(let i=0;i<30;i++){
    const p=document.createElement('div');
    p.className='particle';
    p.style.left=Math.random()*100+'%';
    p.style.width=p.style.height=(Math.random()*3+1)+'px';
    p.style.background=`rgba(${Math.random()>0.5?'99,102,241':'139,92,246'},${Math.random()*.3+.2})`;
    p.style.animationDuration=(Math.random()*15+10)+'s';
    p.style.animationDelay=Math.random()*5+'s';
    pc.appendChild(p);
  }
}

// Hero bg parallax
window.addEventListener('scroll',()=>{
  const s=scrollY;
  const bg=document.getElementById('heroBgPhoto');
  if(bg) bg.style.transform=`scale(1.05) translateY(${s*.3}px)`;
});

// Counter animation on stats
const statsObs=new IntersectionObserver(e=>{
  e.forEach(x=>{
    if(x.isIntersecting){
      x.target.querySelectorAll('[data-target]').forEach(el=>{
        const target=parseInt(el.dataset.target);
        const duration=1800;
        const step=target/(duration/16);
        let current=0;
        const timer=setInterval(()=>{
          current=Math.min(current+step,target);
          el.textContent=Math.floor(current).toLocaleString('id-ID');
          if(current>=target)clearInterval(timer);
        },16);
      });
      statsObs.unobserve(x.target);
    }
  });
},{threshold:.3});
document.querySelectorAll('.stats-section').forEach(el=>statsObs.observe(el));
</script>
@endpush

@push('styles')
<style>
/* ══ HOVER EFFECTS (REPLACING INLINE JS) ══════════════════════════ */
.svc-card { background:#fff;border-radius:1.25rem;border:1px solid var(--border);overflow:hidden;transition:all .3s cubic-bezier(.4,0,.2,1);cursor:default; }
.svc-card:hover { transform:translateY(-6px);box-shadow:0 20px 60px rgba(0,0,0,.1);border-color:#c7d2fe; }
.svc-img-wrap { height:190px;overflow:hidden;position:relative; }
.svc-img-wrap img { width:100%;height:100%;object-fit:cover;transition:transform .5s ease; }
.svc-card:hover .svc-img-wrap img { transform:scale(1.07); }
.svc-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.5),transparent); }
.svc-body { padding:1.5rem; }
.svc-icon { width:44px;height:44px;border-radius:.65rem;display:flex;align-items:center;justify-content:center;font-size:1rem;margin-bottom:1rem; }
.svc-title { font-size:.95rem;font-weight:700;color:var(--dark);margin-bottom:.45rem; }
.svc-desc { font-size:.82rem;color:var(--muted);line-height:1.75; }

.part-card { border-radius:1rem;overflow:hidden;border:1px solid var(--border);background:#fff;transition:all .3s cubic-bezier(.4,0,.2,1); }
.part-card:hover { transform:translateY(-5px);box-shadow:0 16px 48px rgba(99,102,241,.12);border-color:#c7d2fe; }
.part-img-wrap { height:150px;overflow:hidden;position:relative; }
.part-img-wrap img { width:100%;height:100%;object-fit:cover;transition:transform .5s ease; }
.part-card:hover .part-img-wrap img { transform:scale(1.1); }
.part-cat { position:absolute;top:.6rem;right:.6rem;background:rgba(99,102,241,.9);color:#fff;font-size:.62rem;font-weight:700;padding:.2rem .55rem;border-radius:99px; }
.part-body { padding:.9rem 1rem; }
.part-name { font-size:.82rem;font-weight:700;color:var(--dark); }
.part-status { font-size:.72rem;color:var(--muted);margin-top:.2rem;display:flex;align-items:center;gap:.3rem; }
.status-dot { width:6px;height:6px;background:#10b981;border-radius:50%;display:inline-block; }

.btn-all-parts { display:inline-flex;align-items:center;gap:.65rem;padding:.85rem 2.25rem;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:.75rem;color:#fff;text-decoration:none;font-weight:700;font-size:.875rem;box-shadow:0 8px 24px rgba(99,102,241,.35);transition:all .25s; }
.btn-all-parts:hover { transform:translateY(-2px);box-shadow:0 16px 40px rgba(99,102,241,.45); color: #fff; }

.gallery-main { grid-row:1/3;border-radius:1.5rem;overflow:hidden;position:relative;box-shadow:var(--sh2); }
.gallery-main img { width:100%;height:100%;object-fit:cover;transition:transform .6s ease; }
.gallery-main:hover img { transform:scale(1.04); }
.gallery-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.75) 0%,transparent 50%); }
.gallery-caption { position:absolute;bottom:1.5rem;left:1.5rem; }
.caption-title { font-weight:800;font-size:1.05rem;color:#fff; }
.caption-sub { font-size:.78rem;color:#94a3b8;margin-top:.25rem; }

.gallery-item { border-radius:1.1rem;overflow:hidden;position:relative;box-shadow:var(--sh); }
.gallery-item img { width:100%;height:100%;object-fit:cover;transition:transform .5s ease; }
.gallery-item:hover img { transform:scale(1.06); }
.gallery-item-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.6),transparent); }
.gallery-item-label { position:absolute;bottom:.85rem;left:.85rem;color:#fff;font-size:.78rem;font-weight:600; }

.team-card { text-align:center;background:#fff;border-radius:1.25rem;border:1px solid var(--border);padding:1.75rem 1.25rem;transition:all .3s; }
.team-card:hover { transform:translateY(-6px);box-shadow:0 16px 48px rgba(99,102,241,.12);border-color:#c7d2fe; }
.team-avatar-wrap { position:relative;display:inline-block;margin-bottom:1.25rem; }
.team-avatar { width:120px;height:120px;border-radius:50%;overflow:hidden;border:4px solid var(--pll);margin:0 auto;box-shadow:0 8px 24px rgba(99,102,241,.2);position:relative; }
.team-avatar img { width:100%;height:100%;object-fit:cover;transition:transform .4s; }
.team-card:hover .team-avatar img { transform:scale(1.08); }
.team-verified { position:absolute;bottom:2px;right:2px;width:28px;height:28px;background:#10b981;border-radius:50%;border:3px solid #fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(16,185,129,.4); }
.team-verified i { color:#fff;font-size:.55rem; }
.team-name { font-weight:700;font-size:.95rem;color:var(--dark); }
.team-role { font-size:.8rem;color:var(--p);font-weight:600;margin:.25rem 0; }
.team-exp { font-size:.72rem;color:var(--muted); }
.team-rating { display:flex;justify-content:center;gap:.3rem;margin-top:.85rem;color:#f59e0b;font-size:.68rem; }

.testi-card { background:#fff;border-radius:1.25rem;border:1px solid var(--border);padding:1.75rem;transition:all .3s; }
.testi-card:hover { boxShadow:0 12px 40px rgba(0,0,0,.08);border-color:#c7d2fe;transform:translateY(-4px); }
.testi-stars { color:#f59e0b;font-size:.82rem;margin-bottom:.9rem; }
.testi-text { font-size:.85rem;color:var(--muted);line-height:1.85;margin-bottom:1.5rem;font-style:italic; }
.testi-user { display:flex;align-items:center;gap:.85rem; }
.testi-user img { width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--pll);box-shadow:0 4px 12px rgba(99,102,241,.15); }
.testi-user .user-name { font-size:.85rem;font-weight:700;color:var(--dark); }
.testi-user .user-role { font-size:.72rem;color:var(--muted); }

.brand-tag { background:var(--light);border:1.5px solid var(--border);border-radius:.65rem;padding:.6rem 1.75rem;font-size:.85rem;font-weight:700;color:var(--muted);transition:all .25s;cursor:default; }
.brand-tag:hover { border-color:#6366f1;color:#6366f1;background:#ede9fe;transform:translateY(-2px); }

.cta-primary { display:inline-flex;align-items:center;gap:.65rem;padding:.9rem 2rem;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:.75rem;font-size:.9rem;font-weight:700;color:#fff;text-decoration:none;box-shadow:0 8px 32px rgba(99,102,241,.5);transition:all .25s; }
.cta-primary:hover { transform:translateY(-2px);box-shadow:0 16px 48px rgba(99,102,241,.6); color: #fff; }
.cta-secondary { display:inline-flex;align-items:center;gap:.65rem;padding:.9rem 2rem;border:1.5px solid rgba(255,255,255,.15);border-radius:.75rem;font-size:.9rem;font-weight:600;color:#cbd5e1;text-decoration:none;transition:all .25s;backdrop-filter:blur(8px); }
.cta-secondary:hover { border-color:rgba(255,255,255,.4);color:#fff;background:rgba(255,255,255,.05); }

@media(max-width:1024px){
  #layanan>div>div:nth-child(2){grid-template-columns:repeat(2,1fr)!important}
  #sparepart>div>div:nth-child(2){grid-template-columns:repeat(3,1fr)!important}
  #bengkel>div>div:nth-child(2){grid-template-columns:1fr 1fr!important;grid-template-rows:auto!important}
  #bengkel>div>div:nth-child(2)>div:first-child{grid-row:auto!important}
  #tim>div>div:nth-child(2){grid-template-columns:repeat(2,1fr)!important}
  #testimoni>div>div:nth-child(2){grid-template-columns:1fr!important}
}
@media(max-width:768px){
  #layanan>div>div:nth-child(2){grid-template-columns:1fr!important}
  #sparepart>div>div:nth-child(2){grid-template-columns:repeat(2,1fr)!important}
  #bengkel>div>div:nth-child(2){grid-template-columns:1fr!important}
  #tim>div>div:nth-child(2){grid-template-columns:repeat(2,1fr)!important}
}
</style>
@endpush
