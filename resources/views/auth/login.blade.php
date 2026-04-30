<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta name="robots" content="noindex, nofollow">
<title>Masuk — FajarMotor</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --p:#6366f1;--pd:#4f46e5;--pl:#818cf8;
  --bg:#f1f5f9;--surface:#fff;
  --text:#0f172a;--muted:#64748b;--border:#e2e8f0;
  --success:#10b981;--danger:#ef4444;
}
html.dark{
  --bg:#05080f;--surface:#0d1526;
  --text:#f1f5f9;--muted:#94a3b8;--border:rgba(255,255,255,.08);
}
html,body{height:100%;overflow:hidden}
body{font-family:'Inter',sans-serif;display:flex;background:var(--bg);-webkit-font-smoothing:antialiased;transition:background .3s}

/* ── ANIMATED BG ── */
.bg-anim{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none}
.orb{position:absolute;border-radius:50%;filter:blur(80px);animation:drift 14s ease-in-out infinite alternate}
.orb1{width:500px;height:500px;background:radial-gradient(circle,rgba(99,102,241,.18),transparent 70%);top:-100px;left:-100px}
.orb2{width:400px;height:400px;background:radial-gradient(circle,rgba(139,92,246,.14),transparent 70%);bottom:-80px;right:-80px;animation-delay:-7s}
.orb3{width:300px;height:300px;background:radial-gradient(circle,rgba(6,182,212,.1),transparent 70%);top:40%;left:40%;animation-delay:-3s}
@keyframes drift{0%{transform:translate(0,0) scale(1)}100%{transform:translate(40px,30px) scale(1.08)}}

/* ── LAYOUT ── */
.wrap{position:relative;z-index:1;display:flex;width:100%;height:100vh}

/* ── LEFT ── */
.left{flex:1;background:linear-gradient(145deg,#020617 0%,#0f172a 40%,#1e1b4b 75%,#312e81 100%);display:flex;flex-direction:column;justify-content:space-between;padding:3rem;overflow:hidden;position:relative}
.left::after{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");pointer-events:none}
.left-top{position:relative;z-index:1}
.brand{display:flex;align-items:center;gap:.75rem;margin-bottom:3rem}
.brand-ico{width:42px;height:42px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#fff;box-shadow:0 8px 24px rgba(99,102,241,.4)}
.brand-name{color:#f1f5f9;font-size:1.05rem;font-weight:800;letter-spacing:.5px}
.brand-sub{color:#475569;font-size:.68rem;letter-spacing:1.5px;text-transform:uppercase}
.left-h{color:#f8fafc;font-size:clamp(1.6rem,2.5vw,2.3rem);font-weight:900;line-height:1.15;margin-bottom:1rem}
.left-h .grad{background:linear-gradient(135deg,#818cf8,#c4b5fd,#f0abfc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-size:200%;animation:gshift 5s ease infinite}
@keyframes gshift{0%,100%{background-position:0%}50%{background-position:100%}}
.left-p{color:#94a3b8;font-size:.875rem;line-height:1.85;max-width:380px;margin-bottom:2.5rem}
.feats{display:flex;flex-direction:column;gap:.7rem}
.feat{display:flex;align-items:center;gap:.85rem;color:#cbd5e1;font-size:.82rem;animation:fadeUp .6s ease both}
.feat:nth-child(1){animation-delay:.1s}.feat:nth-child(2){animation-delay:.2s}.feat:nth-child(3){animation-delay:.3s}.feat:nth-child(4){animation-delay:.4s}
.feat-ico{width:32px;height:32px;background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.25);border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#a5b4fc;font-size:.75rem;flex-shrink:0}
.left-bottom{position:relative;z-index:1}
.quote{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:1rem;padding:1.25rem;animation:fadeUp .6s .5s ease both;opacity:0;animation-fill-mode:forwards}
.quote p{color:#cbd5e1;font-size:.82rem;line-height:1.75;font-style:italic;margin-bottom:.75rem}
.quote-author{display:flex;align-items:center;gap:.65rem}
.qa{width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700;flex-shrink:0}
.qn{color:#e2e8f0;font-size:.78rem;font-weight:600}
.qr{color:#64748b;font-size:.7rem}

/* ── RIGHT ── */
.right{width:480px;min-width:480px;display:flex;align-items:center;justify-content:center;padding:2.5rem;background:var(--surface);position:relative;transition:background .3s}
.right-inner{width:100%;max-width:380px}

/* dark toggle */
.dark-toggle{position:absolute;top:1.25rem;right:1.25rem;width:34px;height:34px;border-radius:.5rem;border:1px solid var(--border);background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);font-size:.8rem;transition:all .2s}
.dark-toggle:hover{background:rgba(99,102,241,.08);color:var(--p);border-color:rgba(99,102,241,.3)}

/* status */
.status-pill{display:inline-flex;align-items:center;gap:.45rem;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:99px;padding:.3rem .85rem;font-size:.72rem;color:#059669;margin-bottom:1.5rem}
.status-dot{width:6px;height:6px;background:#10b981;border-radius:50%;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
html.dark .status-pill{background:rgba(16,185,129,.1);color:#34d399}

/* header */
.login-h{font-size:1.5rem;font-weight:800;color:var(--text);margin-bottom:.3rem;transition:color .3s}
.login-sub{color:var(--muted);font-size:.875rem;margin-bottom:1.75rem;transition:color .3s}

/* alerts */
.alert-e{background:#fef2f2;border:1px solid #fecaca;border-radius:.65rem;padding:.7rem 1rem;font-size:.8rem;color:#991b1b;margin-bottom:1.25rem;display:flex;align-items:flex-start;gap:.5rem;animation:fadeUp .3s ease}
.alert-s{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.65rem;padding:.7rem 1rem;font-size:.8rem;color:#166534;margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;animation:fadeUp .3s ease}
html.dark .alert-e{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.2);color:#fca5a5}
html.dark .alert-s{background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.2);color:#6ee7b7}

/* form */
.fg{margin-bottom:1.1rem}
.fl{display:flex;align-items:center;justify-content:space-between;font-size:.78rem;font-weight:600;color:var(--text);margin-bottom:.4rem;transition:color .3s}
.fl a{font-size:.75rem;color:var(--p);text-decoration:none;font-weight:500}
.fl a:hover{text-decoration:underline}
.iw{position:relative}
.ii{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.8rem;pointer-events:none;transition:color .2s}
.iw:focus-within .ii{color:var(--p)}
.fi{width:100%;padding:.72rem .9rem .72rem 2.5rem;border:1.5px solid var(--border);border-radius:.65rem;font-size:.875rem;font-family:'Inter',sans-serif;color:var(--text);background:var(--bg);transition:all .2s;outline:none}
.fi:focus{border-color:var(--p);background:var(--surface);box-shadow:0 0 0 3px rgba(99,102,241,.12)}
.fi.err{border-color:var(--danger)}
.fi.err:focus{box-shadow:0 0 0 3px rgba(239,68,68,.1)}
html.dark .fi{background:rgba(255,255,255,.04);color:var(--text)}
html.dark .fi:focus{background:rgba(255,255,255,.07)}
.tpw{position:absolute;right:.9rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;font-size:.8rem;padding:.2rem;transition:color .2s}
.tpw:hover{color:var(--p)}

/* remember */
.rem{display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem}
.rem input{width:15px;height:15px;accent-color:var(--p);cursor:pointer}
.rem label{font-size:.8rem;color:var(--muted);cursor:pointer;user-select:none;transition:color .3s}

/* submit */
.btn-sub{width:100%;padding:.8rem;background:linear-gradient(135deg,var(--p),var(--pd));color:#fff;border:none;border-radius:.7rem;font-size:.9rem;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:.5rem;position:relative;overflow:hidden}
.btn-sub::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.12),transparent);opacity:0;transition:opacity .25s}
.btn-sub:hover{transform:translateY(-1px);box-shadow:0 8px 28px rgba(99,102,241,.45)}
.btn-sub:hover::before{opacity:1}
.btn-sub:active{transform:translateY(0)}
.btn-sub:disabled{opacity:.65;cursor:not-allowed;transform:none;box-shadow:none}

/* footer */
.lf{margin-top:1.5rem;text-align:center;font-size:.75rem;color:var(--muted)}
.lf a{color:var(--p);text-decoration:none}
.lf a:hover{text-decoration:underline}
.sec-row{display:flex;align-items:center;justify-content:center;gap:1rem;margin-top:.65rem;flex-wrap:wrap}
.si{display:flex;align-items:center;gap:.3rem;font-size:.7rem;color:var(--muted)}
.si i{color:var(--success);font-size:.65rem}

@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.fade-in{animation:fadeUp .5s ease both}
.fade-in:nth-child(2){animation-delay:.05s}
.fade-in:nth-child(3){animation-delay:.1s}
.fade-in:nth-child(4){animation-delay:.15s}
.fade-in:nth-child(5){animation-delay:.2s}

@media(max-width:900px){.left{display:none}.right{width:100%;min-width:unset}}
</style>
</head>
<body>
<div class="bg-anim"><div class="orb orb1"></div><div class="orb orb2"></div><div class="orb orb3"></div></div>
<div class="wrap">

{{-- LEFT --}}
<div class="left">
  <div class="left-top">
    <div class="brand">
      <div class="brand-ico"><i class="fa fa-motorcycle"></i></div>
      <div><div class="brand-name">FajarMotor</div><div class="brand-sub">Workshop System</div></div>
    </div>
    <h1 class="left-h">Kelola Bengkel<br>dengan <span class="grad">Lebih Cerdas</span></h1>
    <p class="left-p">Platform manajemen bengkel motor terpadu — stok, servis, kasir, dan laporan dalam satu sistem modern.</p>
    <div class="feats">
      <div class="feat"><div class="feat-ico"><i class="fa fa-box"></i></div>Manajemen stok & sparepart real-time</div>
      <div class="feat"><div class="feat-ico"><i class="fa fa-cash-register"></i></div>POS Kasir terintegrasi</div>
      <div class="feat"><div class="feat-ico"><i class="fa fa-screwdriver-wrench"></i></div>Work Order & jadwal servis</div>
      <div class="feat"><div class="feat-ico"><i class="fa fa-chart-line"></i></div>Laporan penjualan & profit otomatis</div>
    </div>
  </div>
  <div class="left-bottom">
    <div class="quote">
      <p>"FajarMotor mengubah cara kami mengelola bengkel. Stok tidak pernah habis mendadak lagi."</p>
      <div class="quote-author">
        <div class="qa">BH</div>
        <div><div class="qn">Budi Hartono</div><div class="qr">Pemilik Bengkel, Jambi</div></div>
      </div>
    </div>
  </div>
</div>

{{-- RIGHT --}}
<div class="right">
  <button class="dark-toggle" id="dt" title="Toggle dark mode"><i class="fa fa-moon" id="dti"></i></button>
  <div class="right-inner">

    <div class="status-pill fade-in"><span class="status-dot"></span>Sistem aktif &amp; aman</div>

    <div class="login-h fade-in">Welcome Back 👋</div>
    <div class="login-sub fade-in">Login menggunakan email pribadi Anda</div>

    @if(session('status'))
    <div class="alert-s fade-in"><i class="fa fa-circle-check"></i>{{ session('status') }}</div>
    @endif
    @if($errors->any())
    <div class="alert-e fade-in"><i class="fa fa-circle-exclamation" style="margin-top:.1rem;flex-shrink:0"></i><span>{{ $errors->first() }}</span></div>
    @endif
    @if(session('login_attempts') && session('login_attempts') >= 3)
    <div class="alert-e fade-in"><i class="fa fa-triangle-exclamation"></i>{{ 5 - session('login_attempts') }} percobaan tersisa sebelum akun dikunci.</div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="lf" autocomplete="off" novalidate>
      @csrf
      <div style="display:none"><input type="text" name="website" tabindex="-1"></div>

      <div class="fg fade-in">
        <div class="fl"><span>Alamat Email</span></div>
        <div class="iw">
          <i class="fa fa-envelope ii"></i>
          <input type="email" name="email" class="fi {{ $errors->has('email') ? 'err' : '' }}"
            value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="username" maxlength="255">
        </div>
      </div>

      <div class="fg fade-in">
        <div class="fl">
          <span>Password</span>
          <a href="{{ route('password.request') }}">Lupa password?</a>
        </div>
        <div class="iw">
          <i class="fa fa-lock ii"></i>
          <input type="password" id="pw" name="password" class="fi {{ $errors->has('password') ? 'err' : '' }}"
            placeholder="••••••••" required autocomplete="current-password" maxlength="128">
          <button type="button" class="tpw" id="tpwBtn"><i class="fa fa-eye" id="pwi"></i></button>
        </div>
      </div>

      <div class="rem fade-in">
        <input type="checkbox" id="rem" name="remember">
        <label for="rem">Ingat saya di perangkat ini</label>
      </div>

      <button type="submit" class="btn-sub fade-in" id="sb">
        <i class="fa fa-sign-in-alt"></i><span id="sbt">Masuk ke Dashboard</span>
      </button>
    </form>

    <div class="lf fade-in">
      <a href="{{ route('landing') }}"><i class="fa fa-arrow-left" style="margin-right:.3rem"></i>Kembali ke halaman utama</a>
      <div class="sec-row">
        <span class="si"><i class="fa fa-shield-halved"></i>CSRF Protected</span>
        <span class="si"><i class="fa fa-lock"></i>Rate Limited</span>
        <span class="si"><i class="fa fa-eye-slash"></i>Encrypted</span>
      </div>
    </div>
  </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dark mode
    const html = document.documentElement;
    const dt = document.getElementById('dt');
    const dti = document.getElementById('dti');
    
    function applyDark(d) {
        html.classList.toggle('dark', d);
        if (dti) dti.className = d ? 'fa fa-sun' : 'fa fa-moon';
        localStorage.setItem('theme', d ? 'dark' : 'light');
    }

    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme:dark)').matches;
    applyDark(savedTheme ? savedTheme === 'dark' : prefersDark);

    dt?.addEventListener('click', () => applyDark(!html.classList.contains('dark')));

    // Toggle password
    const pwInput = document.getElementById('pw');
    const pwToggle = document.getElementById('tpwBtn');
    const pwIcon = document.getElementById('pwi');

    pwToggle?.addEventListener('click', function() {
        if (!pwInput || !pwIcon) return;
        const isPw = pwInput.type === 'password';
        pwInput.type = isPw ? 'text' : 'password';
        pwIcon.className = isPw ? 'fa fa-eye-slash' : 'fa fa-eye';
    });

    // Submit
    const loginForm = document.getElementById('lf');
    const submitBtn = document.getElementById('sb');

    loginForm?.addEventListener('submit', function(e) {
        const hp = this.querySelector('[name="website"]');
        if (hp && hp.value) {
            e.preventDefault();
            return;
        }
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
        }
    });

    @if($errors->any()) 
    if (pwInput) pwInput.value = ''; 
    @endif
});
</script>
</body>
</html>
