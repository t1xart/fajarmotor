<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','fajarmotor — Bengkel Motor Profesional')</title>
<script>if(localStorage.getItem('theme')==='dark'||(!localStorage.getItem('theme')&&window.matchMedia('(prefers-color-scheme:dark)').matches)){document.documentElement.classList.add('dark')}</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --p:#6366f1;--pd:#4f46e5;--pl:#818cf8;--pll:#ede9fe;
  --acc:#f59e0b;--grn:#10b981;--red:#ef4444;
  --dark:#0f172a;--dark2:#1e293b;--dark3:#334155;
  --text:#1e293b;--muted:#64748b;--light:#f8fafc;--white:#fff;
  --border:#e2e8f0;
  --r:.75rem;--r2:1.25rem;--r3:1.75rem;
  --sh:0 4px 24px rgba(0,0,0,.06);
  --sh2:0 12px 48px rgba(0,0,0,.1);
  --sh3:0 24px 80px rgba(0,0,0,.15);
  --trans:.25s cubic-bezier(.4,0,.2,1);
}
html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;color:var(--text);background:var(--white);overflow-x:hidden;-webkit-font-smoothing:antialiased}

/* ── DARK MODE ── */
html.dark{
  --text:#e2e8f0;--muted:#94a3b8;--white:#05080f;--light:#0d1526;
  --border:rgba(255,255,255,0.08);--dark:#f1f5f9;--dark2:#e2e8f0;
  --pll:rgba(99,102,241,0.15);
}
html.dark body{background:#05080f;color:var(--text)}
html.dark #navbar.scrolled{background:rgba(5,8,15,.92);box-shadow:0 1px 0 rgba(255,255,255,.05),0 4px 24px rgba(0,0,0,.4)}
html.dark .nav-logo-text{color:#f1f5f9}
html.dark .nav-links a{color:#94a3b8}
html.dark .nav-links a:hover{color:#a5b4fc;background:rgba(99,102,241,.15)}
html.dark .btn-ghost{border-color:rgba(255,255,255,.12);color:#e2e8f0}
html.dark .btn-ghost:hover{border-color:var(--p);color:#a5b4fc;background:rgba(99,102,241,.12)}
html.dark .section-title{color:#f1f5f9}
html.dark .section-chip{background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.25)}
html.dark .section-desc{color:#94a3b8}

/* dark toggle btn */
#landingDarkToggle{
  width:34px;height:34px;border-radius:.55rem;
  border:1.5px solid rgba(255,255,255,.12);
  background:transparent;
  display:flex;align-items:center;justify-content:center;
  color:#94a3b8;cursor:pointer;font-size:.8rem;
  transition:all .25s;
}
#landingDarkToggle:hover{background:rgba(99,102,241,.12);color:#a5b4fc;border-color:rgba(99,102,241,.3)}
::-webkit-scrollbar{width:4px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:linear-gradient(var(--p),var(--pl));border-radius:99px}

/* ── CURSOR GLOW ── */
#cursor-glow{pointer-events:none;position:fixed;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.06) 0%,transparent 70%);transform:translate(-50%,-50%);z-index:0;transition:opacity .3s}

/* ── NAVBAR ── */
#navbar{position:fixed;top:0;left:0;right:0;z-index:1000;padding:1.1rem 0;transition:all .4s cubic-bezier(.4,0,.2,1)}
#navbar.scrolled{background:rgba(255,255,255,.88);backdrop-filter:blur(20px) saturate(180%);-webkit-backdrop-filter:blur(20px) saturate(180%);box-shadow:0 1px 0 rgba(0,0,0,.06),0 4px 24px rgba(0,0,0,.04);padding:.7rem 0}
.nav-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between}
.nav-logo{display:flex;align-items:center;gap:.65rem;text-decoration:none}
.nav-logo-icon{width:40px;height:40px;background:linear-gradient(135deg,var(--p),var(--pl));border-radius:.65rem;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;box-shadow:0 4px 12px rgba(99,102,241,.35);transition:transform var(--trans)}
.nav-logo:hover .nav-logo-icon{transform:rotate(-8deg) scale(1.05)}
.nav-logo-text{font-weight:800;font-size:1.05rem;color:var(--dark);line-height:1.2}
.nav-logo-sub{font-size:.62rem;color:var(--muted);font-weight:400}
.nav-links{display:flex;align-items:center;gap:.25rem;list-style:none}
.nav-links a{text-decoration:none;color:var(--muted);font-size:.84rem;font-weight:500;padding:.45rem .85rem;border-radius:.5rem;transition:all var(--trans);position:relative}
.nav-links a::after{content:'';position:absolute;bottom:0;left:50%;right:50%;height:2px;background:var(--p);border-radius:99px;transition:all var(--trans)}
.nav-links a:hover{color:var(--p);background:var(--pll)}
.nav-links a:hover::after{left:.85rem;right:.85rem}
.nav-cta{display:flex;align-items:center;gap:.65rem}
.btn-ghost{padding:.48rem 1.1rem;border:1.5px solid var(--border);border-radius:.55rem;font-size:.8rem;font-weight:600;color:var(--text);text-decoration:none;transition:all var(--trans);background:transparent}
.btn-ghost:hover{border-color:var(--p);color:var(--p);background:var(--pll)}
.btn-solid{padding:.48rem 1.25rem;background:linear-gradient(135deg,var(--p),var(--pd));border-radius:.55rem;font-size:.8rem;font-weight:600;color:#fff;text-decoration:none;transition:all var(--trans);box-shadow:0 4px 14px rgba(99,102,241,.35);position:relative;overflow:hidden}
.btn-solid::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);opacity:0;transition:opacity var(--trans)}
.btn-solid:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(99,102,241,.45)}
.btn-solid:hover::before{opacity:1}
.hamburger{display:none;background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--dark);padding:.4rem;border-radius:.45rem;transition:background var(--trans)}
.hamburger:hover{background:var(--light)}

/* ── MOBILE MENU ── */
#mobileMenu{display:none;position:fixed;inset:0;z-index:999;background:rgba(15,23,42,.97);backdrop-filter:blur(20px);flex-direction:column;align-items:center;justify-content:center;gap:1.5rem}
#mobileMenu.open{display:flex}
#mobileMenu a{color:#e2e8f0;text-decoration:none;font-size:1.15rem;font-weight:600;padding:.5rem 1.5rem;border-radius:.65rem;transition:all var(--trans)}
#mobileMenu a:hover{background:rgba(99,102,241,.15);color:#a5b4fc}
.mm-close{position:absolute;top:1.5rem;right:1.5rem;background:rgba(255,255,255,.08);border:none;color:#fff;font-size:1.1rem;cursor:pointer;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:background var(--trans)}
.mm-close:hover{background:rgba(255,255,255,.15)}

/* ── CONTAINER ── */
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
section{padding:5.5rem 0}
.section-chip{display:inline-flex;align-items:center;gap:.4rem;background:var(--pll);color:var(--p);font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:.3rem .9rem;border-radius:99px;margin-bottom:.9rem;border:1px solid rgba(99,102,241,.2)}
.section-title{font-size:clamp(1.65rem,3vw,2.3rem);font-weight:800;color:var(--dark);margin-bottom:.8rem;line-height:1.2}
.section-title span{background:linear-gradient(135deg,var(--p),var(--pl));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.section-desc{color:var(--muted);font-size:.9rem;line-height:1.85;max-width:560px}
.tc{text-align:center}.tc .section-desc{margin:0 auto}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
@keyframes pulse-ring{0%{transform:scale(.95);box-shadow:0 0 0 0 rgba(99,102,241,.4)}70%{transform:scale(1);box-shadow:0 0 0 12px rgba(99,102,241,0)}100%{transform:scale(.95);box-shadow:0 0 0 0 rgba(99,102,241,0)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
@keyframes gradient-shift{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}
@keyframes spin-slow{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@keyframes count-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

.reveal{opacity:0;transform:translateY(32px);transition:opacity .7s cubic-bezier(.4,0,.2,1),transform .7s cubic-bezier(.4,0,.2,1)}
.reveal.visible{opacity:1;transform:translateY(0)}
.reveal-left{opacity:0;transform:translateX(-32px);transition:opacity .7s cubic-bezier(.4,0,.2,1),transform .7s cubic-bezier(.4,0,.2,1)}
.reveal-left.visible{opacity:1;transform:translateX(0)}
.reveal-right{opacity:0;transform:translateX(32px);transition:opacity .7s cubic-bezier(.4,0,.2,1),transform .7s cubic-bezier(.4,0,.2,1)}
.reveal-right.visible{opacity:1;transform:translateX(0)}
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}.d4{transition-delay:.4s}

/* ── FOOTER ── */
footer{background:var(--dark);color:#94a3b8;padding:4.5rem 0 1.5rem;position:relative;overflow:hidden}
footer::before{content:'';position:absolute;top:-1px;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--p),transparent)}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:3rem}
.footer-brand-name{color:#f1f5f9;font-size:1.05rem;font-weight:800}
.footer-brand p{font-size:.82rem;line-height:1.85;margin-top:.75rem;color:#e2e8f0}
.footer-col h4{color:#e2e8f0;font-size:.82rem;font-weight:700;margin-bottom:1rem;text-transform:uppercase;letter-spacing:.06em}
.footer-col ul{list-style:none}
.footer-col ul li{margin-bottom:.5rem}
.footer-col ul li a{color:#e2e8f0;text-decoration:none;font-size:.82rem;transition:color var(--trans);display:flex;align-items:center;gap:.4rem}
.footer-col ul li a:hover{color:#a5b4fc}
.footer-bottom{border-top:1px solid #1e293b;padding-top:1.5rem;display:flex;justify-content:space-between;align-items:center;font-size:.78rem;color:#e2e8f0}
.social-links{display:flex;gap:.6rem;margin-top:1.25rem}
.sl{width:36px;height:36px;border-radius:.5rem;background:#1e293b;color:#475569;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:.8rem;transition:all var(--trans);border:1px solid #1e293b}
.sl:hover{background:var(--p);color:#fff;border-color:var(--p);transform:translateY(-2px);box-shadow:0 4px 12px rgba(99,102,241,.35)}

@media(max-width:1024px){.footer-grid{grid-template-columns:1fr 1fr}}
@media(max-width:768px){
  .nav-links,.nav-cta{display:none}
  .hamburger{display:block}
  .footer-grid{grid-template-columns:1fr;gap:2rem}
  .footer-bottom{flex-direction:column;gap:1rem;text-align:center}
}
</style>
@stack('styles')
</head>
<body>

<div id="cursor-glow"></div>

<div id="mobileMenu">
  <button class="mm-close" id="closeMobileMenu"><i class="fa fa-times"></i></button>
  <a href="#layanan" class="mm-link">Layanan</a>
  <a href="#sparepart" class="mm-link">Sparepart</a>
  <a href="#bengkel" class="mm-link">Bengkel</a>
  <a href="#tim" class="mm-link">Tim Kami</a>
  <a href="#testimoni" class="mm-link">Testimoni</a>
  <a href="{{ route('login') }}" class="mm-link" style="color:#a5b4fc;margin-top:.5rem"><i class="fa fa-sign-in-alt" style="margin-right:.5rem"></i>Masuk Sistem</a>
</div>

<nav id="navbar">
  <div class="nav-inner">
    <a href="{{ route('landing') }}" class="nav-logo">
      <div class="nav-logo-icon"><i class="fa fa-motorcycle"></i></div>
      <div><div class="nav-logo-text">fajarmotor</div><div class="nav-logo-sub">Bengkel & Sparepart Motor</div></div>
    </a>
    <ul class="nav-links">
      <li><a href="#layanan">Layanan</a></li>
      <li><a href="#sparepart">Sparepart</a></li>
      <li><a href="#bengkel">Bengkel</a></li>
      <li><a href="#tim">Tim Kami</a></li>
      <li><a href="#testimoni">Testimoni</a></li>
    </ul>
    <div class="nav-cta">
      @auth
        <span style="font-size:.78rem;color:#64748b;display:flex;align-items:center;gap:.4rem">
          <span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;box-shadow:0 0 6px #10b981"></span>
          {{ auth()->user()?->name }}
        </span>
        <a href="{{ route('dashboard') }}" class="btn-solid"><i class="fa fa-gauge-high" style="margin-right:.4rem"></i>Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
        <a href="{{ route('login') }}" class="btn-solid"><i class="fa fa-motorcycle" style="margin-right:.4rem"></i>Kelola Bengkel</a>
      @endauth
    </div>
    <button id="landingDarkToggle" title="Toggle Dark Mode"><i class="fa fa-moon"></i></button>
    <button class="hamburger" id="openMobileMenu"><i class="fa fa-bars"></i></button>
  </div>
</nav>

@yield('content')

<footer id="kontak">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-brand-name"><i class="fa fa-motorcycle" style="color:var(--p);margin-right:.5rem"></i>fajarmotor</div>
        <p>Sistem manajemen bengkel motor terpadu. Kelola sparepart, servis, penjualan, dan laporan dalam satu platform modern yang mudah digunakan.</p>
        <div class="social-links">
          <a href="#" class="sl"><i class="fab fa-instagram"></i></a>
          <a href="#" class="sl"><i class="fab fa-facebook"></i></a>
          <a href="#" class="sl"><i class="fab fa-whatsapp"></i></a>
          <a href="#" class="sl"><i class="fab fa-youtube"></i></a>
          <a href="#" class="sl"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Layanan</h4>
        <ul>
          <li><a href="#layanan"><i class="fa fa-wrench fa-fw"></i>Servis Reguler</a></li>
          <li><a href="#layanan"><i class="fa fa-box fa-fw"></i>Ganti Sparepart</a></li>
          <li><a href="#layanan"><i class="fa fa-cog fa-fw"></i>Tune Up</a></li>
          <li><a href="#layanan"><i class="fa fa-bolt fa-fw"></i>Servis Kelistrikan</a></li>
          <li><a href="#layanan"><i class="fa fa-circle-dot fa-fw"></i>Rem & Suspensi</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Sistem</h4>
        <ul>
          <li><a href="{{ route('login') }}"><i class="fa fa-sign-in-alt fa-fw"></i>Login Admin</a></li>
          <li><a href="{{ route('login') }}"><i class="fa fa-warehouse fa-fw"></i>Manajemen Stok</a></li>
          <li><a href="{{ route('login') }}"><i class="fa fa-cash-register fa-fw"></i>POS Kasir</a></li>
          <li><a href="{{ route('login') }}"><i class="fa fa-wrench fa-fw"></i>Work Order</a></li>
          <li><a href="{{ route('login') }}"><i class="fa fa-chart-bar fa-fw"></i>Laporan</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Kontak</h4>
        <ul>
          <li><a href="#"><i class="fa fa-map-marker-alt fa-fw"></i>Jl. Raya Motor No. 1, Jakarta</a></li>
          <li><a href="tel:08001234567"><i class="fa fa-phone fa-fw"></i>0800-1234-567</a></li>
          <li><a href="mailto:info@spareparts.id"><i class="fa fa-envelope fa-fw"></i>info@spareparts.id</a></li>
          <li><a href="#"><i class="fa fa-clock fa-fw"></i>Senin–Sabtu, 08.00–17.00</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© {{ date('Y') }} fajarmotor. All rights reserved.</span>
      <span>Dibuat dengan <i class="fa fa-heart" style="color:#ef4444;margin:0 .25rem"></i> untuk bengkel Indonesia</span>
    </div>
  </div>
</footer>

<script>
// Cursor glow
const glow = document.getElementById('cursor-glow');
document.addEventListener('mousemove', e => {
  if (glow) {
    glow.style.left = e.clientX + 'px';
    glow.style.top = e.clientY + 'px';
  }
});

// Mobile Menu
const mobileMenu = document.getElementById('mobileMenu');
const openBtn = document.getElementById('openMobileMenu');
const closeBtn = document.getElementById('closeMobileMenu');
const mmLinks = document.querySelectorAll('.mm-link');

const toggleMM = (open) => mobileMenu?.classList.toggle('open', open);

openBtn?.addEventListener('click', () => toggleMM(true));
closeBtn?.addEventListener('click', () => toggleMM(false));
mmLinks.forEach(l => l.addEventListener('click', () => toggleMM(false)));

// Navbar scroll
window.addEventListener('scroll', () => {
  const navbar = document.getElementById('navbar');
  if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 60);
});

// Reveal on scroll
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.08 });
document.querySelectorAll('.reveal,.reveal-left,.reveal-right').forEach(el => obs.observe(el));

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const targetId = a.getAttribute('href');
    if (targetId === '#') return;
    const t = document.querySelector(targetId);
    if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});

// Counter animation
function animateCounter(el) {
  const target = parseInt(el.dataset.target);
  if (isNaN(target)) return;
  const duration = 1800;
  const step = target / (duration / 16);
  let current = 0;
  const timer = setInterval(() => {
    current = Math.min(current + step, target);
    el.textContent = Math.floor(current).toLocaleString('id-ID');
    if (current >= target) clearInterval(timer);
  }, 16);
}
const counterObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.querySelectorAll('[data-target]').forEach(animateCounter);
      counterObs.unobserve(e.target);
    }
  });
}, { threshold: 0.3 });
document.querySelectorAll('.stats-section').forEach(el => counterObs.observe(el));

// Dark mode
const _html = document.documentElement;
const _btn  = document.getElementById('landingDarkToggle');
const _icon = _btn?.querySelector('i');
function applyDark(dark) {
  _html.classList.toggle('dark', dark);
  if (_icon) _icon.className = dark ? 'fa fa-sun' : 'fa fa-moon';
  localStorage.setItem('theme', dark ? 'dark' : 'light');
}
const _saved = localStorage.getItem('theme');
applyDark(_saved ? _saved === 'dark' : window.matchMedia('(prefers-color-scheme:dark)').matches);
_btn?.addEventListener('click', () => applyDark(!_html.classList.contains('dark')));
</script>
@stack('scripts')
</body>
</html>
