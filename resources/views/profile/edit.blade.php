@extends('layouts.app')
@section('title', 'Edit Profil')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
.profile-wrap { max-width: 760px; margin: 0 auto; }

/* ── PROFILE HERO ── */
.profile-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%);
    border: 1px solid var(--border); border-radius: var(--radius);
    padding: 28px 28px 0; position: relative; overflow: hidden; margin-bottom: 0;
}
.profile-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 0%, rgba(99,102,241,.2), transparent 60%);
    pointer-events: none;
}
.hero-inner { position: relative; z-index: 1; display: flex; align-items: flex-end; gap: 20px; }
.avatar-wrap { position: relative; flex-shrink: 0; margin-bottom: -20px; }
.avatar-circle {
    width: 100px; height: 100px; border-radius: 50%;
    border: 4px solid rgba(255,255,255,.15);
    box-shadow: 0 8px 32px rgba(0,0,0,.4);
    cursor: pointer; position: relative; overflow: hidden;
    transition: box-shadow .2s;
}
.avatar-circle:hover { box-shadow: 0 8px 32px rgba(99,102,241,.5); }
.avatar-circle img { width: 100%; height: 100%; object-fit: cover; display: block; }
.avatar-initials {
    width: 100%; height: 100%; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #818cf8);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 800; color: #fff;
}
.avatar-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.6);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 3px; opacity: 0; transition: opacity .2s; color: #fff; font-size: 10px; font-weight: 600;
}
.avatar-circle:hover .avatar-overlay { opacity: 1; }
.avatar-overlay i { font-size: 18px; }
.hero-info { padding-bottom: 24px; flex: 1; }
.hero-name { font-size: 18px; font-weight: 800; color: #f1f5f9; margin-bottom: 3px; }
.hero-email { font-size: 12px; color: #94a3b8; margin-bottom: 8px; }
.hero-role {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(99,102,241,.2); border: 1px solid rgba(99,102,241,.3);
    border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600; color: #a5b4fc;
}

/* ── TABS ── */
.tabs-bar {
    display: flex; gap: 0;
    background: var(--card); border: 1px solid var(--border);
    border-top: none; border-radius: 0 0 var(--radius) var(--radius);
    padding: 0 4px; margin-bottom: 20px; overflow-x: auto;
}
.tab-btn {
    padding: 13px 18px; font-size: 13px; font-weight: 600;
    color: var(--text2); background: none; border: none;
    border-bottom: 2px solid transparent; cursor: pointer;
    transition: all .2s; white-space: nowrap; display: flex; align-items: center; gap: 7px;
    font-family: 'Inter', sans-serif;
}
.tab-btn:hover { color: var(--text); }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-btn i { font-size: 12px; }

/* ── TAB PANELS ── */
.tab-panel { display: none; }
.tab-panel.active { display: block; animation: fadeUp .25s ease; }
@keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

/* ── FORM ELEMENTS ── */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media(max-width:600px){ .form-row { grid-template-columns: 1fr; } }
.fg { margin-bottom: 14px; }
.fl { font-size: 12px; font-weight: 600; color: var(--text2); margin-bottom: 5px; display: block; }
.fi {
    width: 100%; padding: 9px 14px; border: 1.5px solid var(--border);
    border-radius: 9px; font-size: 13px; font-family: 'Inter', sans-serif;
    color: var(--text); background: var(--bg); transition: all .2s; outline: none;
}
.fi:focus { border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.fi:disabled { opacity: .6; cursor: not-allowed; }
.fi-icon-wrap { position: relative; }
.fi-icon-wrap .fi { padding-left: 2.5rem; }
.fi-icon-wrap i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--text3); font-size: .8rem; pointer-events: none; }
.fi-icon-wrap:focus-within i { color: var(--primary); }

/* ── AVATAR UPLOAD ── */
.drop-zone {
    border: 2px dashed var(--border); border-radius: var(--radius);
    padding: 24px 20px; text-align: center; cursor: pointer;
    transition: all .2s; background: rgba(99,102,241,.02);
}
.drop-zone:hover, .drop-zone.drag-over { border-color: var(--primary); background: rgba(99,102,241,.06); }
.drop-zone i { font-size: 26px; color: var(--text3); margin-bottom: 8px; display: block; }

/* ── STRENGTH BAR ── */
.pw-str { margin-top: 5px; display: none; }
.pw-bar { height: 3px; background: var(--border); border-radius: 99px; overflow: hidden; margin-bottom: 3px; }
.pw-fill { height: 100%; border-radius: 99px; transition: width .3s, background .3s; width: 0; }
.pw-txt { font-size: 10px; color: var(--text3); }

/* ── BUTTONS ── */
.btn-p {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff; border: none; border-radius: 9px;
    padding: 10px 20px; font-size: 13px; font-weight: 700;
    font-family: 'Inter', sans-serif; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-p:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
.btn-p:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-d {
    background: transparent; color: var(--danger); border: 1.5px solid var(--danger);
    border-radius: 9px; padding: 10px 20px; font-size: 13px; font-weight: 600;
    font-family: 'Inter', sans-serif; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-d:hover { background: var(--danger); color: #fff; }
.btn-s {
    background: rgba(255,255,255,.06); color: var(--text2);
    border: 1.5px solid var(--border); border-radius: 9px;
    padding: 10px 20px; font-size: 13px; font-weight: 600;
    font-family: 'Inter', sans-serif; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-s:hover { border-color: var(--primary); color: var(--primary); }

/* ── SECURITY ITEMS ── */
.sec-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 0; border-bottom: 1px solid var(--border);
}
.sec-item:last-child { border-bottom: none; }
.sec-ico { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
.sec-info { flex: 1; }
.sec-title { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
.sec-desc { font-size: 11px; color: var(--text3); }
.badge-ok { background: rgba(16,185,129,.12); color: var(--success); border: 1px solid rgba(16,185,129,.2); font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
.badge-warn { background: rgba(245,158,11,.12); color: var(--warning); border: 1px solid rgba(245,158,11,.2); font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 20px; }

/* ── TOAST ── */
.toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    padding: 12px 18px; border-radius: 11px; font-size: 13px; font-weight: 600;
    display: flex; align-items: center; gap: 8px;
    box-shadow: 0 8px 32px rgba(0,0,0,.2);
    transform: translateY(80px); opacity: 0;
    transition: all .35s cubic-bezier(.4,0,.2,1); pointer-events: none;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast.ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.toast.err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── CROP MODAL ── */
.crop-modal { 
    display: none; position: fixed; inset: 0; z-index: 2000; background: rgba(0,0,0,.75); 
    backdrop-filter: blur(6px); align-items: center; justify-content: center; 
    animation: fadeInBackdrop .2s ease;
}
.crop-modal.open { display: flex; }
@keyframes fadeInBackdrop { from { background: rgba(0,0,0,0); } to { background: rgba(0,0,0,.75); } }
.crop-box { 
    background: var(--surface); border-radius: var(--radius); padding: 22px; 
    width: 90%; max-width: 480px; box-shadow: 0 32px 80px rgba(0,0,0,.5);
    position: relative; z-index: 2001; animation: slideUp .3s ease;
}
@keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.crop-box h3 { font-size: 14px; font-weight: 700; margin-bottom: 14px; display: flex; align-items: center; gap: 7px; }
.crop-wrap { max-height: 320px; overflow: hidden; border-radius: 8px; background: #000; }
.crop-wrap img { max-width: 100%; display: block; }
.crop-actions { display: flex; gap: 8px; margin-top: 14px; justify-content: flex-end; }

/* file info */
.file-info { display: none; margin-top: 10px; padding: 9px 12px; background: rgba(99,102,241,.06); border: 1px solid rgba(99,102,241,.2); border-radius: 9px; font-size: 12px; align-items: center; gap: 8px; }
.file-err { display: none; margin-top: 8px; padding: 9px 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 9px; font-size: 12px; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="profile-wrap">

{{-- ALERTS --}}
@if(session('success'))
<div class="alert alert-success animate-in mb-3"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger animate-in mb-3"><i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
@endif

{{-- HERO --}}
<div class="profile-hero animate-in">
    <div class="hero-inner">
        <div class="avatar-wrap">
            <div class="avatar-circle" onclick="document.getElementById('avatarInput').click()" title="Ganti foto">
                @if(auth()->user()?->avatar)
                    <img id="avatarPreview" src="{{ Storage::url(auth()->user()->avatar) }}" alt="avatar">
                @else
                    <div class="avatar-initials" id="avatarInitials">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}</div>
                    <img id="avatarPreview" src="" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:50%" alt="avatar">
                @endif
                <div class="avatar-overlay"><i class="fa fa-camera"></i>Ganti</div>
            </div>
        </div>
        <div class="hero-info">
            <div class="hero-name">{{ auth()->user()?->name }}</div>
            <div class="hero-email">{{ auth()->user()?->email }}</div>
            <div class="hero-role"><i class="fa fa-shield-halved" style="font-size:10px"></i>{{ auth()->user()?->getRoleNames()?->first() ?? 'User' }}</div>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="tabs-bar animate-in">
    <button class="tab-btn active" onclick="switchTab('photo',this)"><i class="fa fa-camera"></i>Foto Profil</button>
    <button class="tab-btn" onclick="switchTab('info',this)"><i class="fa fa-user"></i>Informasi</button>
    <button class="tab-btn" onclick="switchTab('security',this)"><i class="fa fa-shield-halved"></i>Keamanan</button>
</div>

{{-- TAB: PHOTO --}}
<div class="tab-panel active" id="tab-photo">
    <div class="card animate-in">
        <div class="card-header">
            <div class="ch-icon" style="background:rgba(99,102,241,.15);color:var(--primary)"><i class="fa fa-camera"></i></div>
            Foto Profil
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" id="photoForm">
                @csrf
                <div class="drop-zone" id="dropZone" onclick="document.getElementById('avatarInput').click()">
                    <i class="fa fa-cloud-arrow-up"></i>
                    <div style="font-size:13px;font-weight:600;margin-bottom:4px">Drag & drop atau klik untuk pilih foto</div>
                    <div style="font-size:11px;color:var(--text3)"><i class="fa fa-circle-info" style="margin-right:4px"></i>Format: JPG, PNG &nbsp;|&nbsp; Maks: 2MB</div>
                </div>
                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png" style="display:none">
                <div class="file-info" id="fileInfo">
                    <i class="fa fa-file-image" style="color:var(--primary)"></i>
                    <span id="fileName"></span><span id="fileSize" style="color:var(--text3)"></span>
                    <button type="button" onclick="clearFile()" style="margin-left:auto;background:none;border:none;color:var(--text3);cursor:pointer"><i class="fa fa-times"></i></button>
                </div>
                <div class="file-err" id="fileErr"><i class="fa fa-circle-exclamation me-2"></i><span id="fileErrMsg"></span></div>
                <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap">
                    <button type="submit" class="btn-p" id="savePhotoBtn" disabled><i class="fa fa-floppy-disk"></i>Simpan Foto</button>
                    @if(auth()->user()?->avatar)
                    <button type="button" class="btn-d" onclick="if(confirm('Hapus foto profil?'))document.getElementById('deleteForm').submit()"><i class="fa fa-trash"></i>Hapus Foto</button>
                    @endif
                    <button type="button" class="btn-s" onclick="document.getElementById('avatarInput').click()"><i class="fa fa-upload"></i>Pilih File</button>
                </div>
            </form>
            <form method="POST" action="{{ route('profile.photo.delete') }}" id="deleteForm" style="display:none">@csrf @method('DELETE')</form>
        </div>
    </div>
</div>

{{-- TAB: INFO --}}
<div class="tab-panel" id="tab-info">
    <div class="card animate-in">
        <div class="card-header">
            <div class="ch-icon" style="background:rgba(16,185,129,.15);color:var(--success)"><i class="fa fa-user"></i></div>
            Informasi Akun
        </div>
        <div class="card-body">
            {{-- Read-only info --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;padding-bottom:20px;border-bottom:1px solid var(--border);margin-bottom:20px">
                <div>
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Nama</div>
                    <div style="font-size:13px;font-weight:600">{{ auth()->user()?->name }}</div>
                </div>
                <div>
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Email Aktif</div>
                    <div style="font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px">
                        {{ auth()->user()?->email }}
                        <span style="font-size:10px;background:rgba(16,185,129,.12);color:var(--success);border:1px solid rgba(16,185,129,.2);border-radius:20px;padding:2px 8px;font-weight:700">
                            <i class="fa fa-check" style="margin-right:3px"></i>Terverifikasi
                        </span>
                    </div>
                </div>
                <div>
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Role</div>
                    <div style="font-size:13px;font-weight:600">{{ auth()->user()?->getRoleNames()?->first() ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px">Cabang</div>
                    <div style="font-size:13px;font-weight:600">{{ auth()->user()?->branch?->name ?? '-' }}</div>
                </div>
            </div>

            <div style="font-size:13px;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:7px">
                <i class="fa fa-envelope" style="color:var(--primary)"></i> Ubah Email
            </div>

            @if(session('success') && !session('otp_sent'))
            <div class="alert alert-success mb-3"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:rgba(99,102,241,.04);border:1px solid rgba(99,102,241,.15);border-radius:10px">
                <div>
                    <div style="font-size:13px;font-weight:600">Ganti alamat email akun</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px">Verifikasi melalui kode OTP yang dikirim ke email baru</div>
                </div>
                <a href="{{ route('profile.email') }}"
                    style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:none;border-radius:9px;padding:8px 18px;font-size:12px;font-weight:700;font-family:'Inter',sans-serif;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:6px;text-decoration:none;white-space:nowrap;flex-shrink:0">
                    <i class="fa fa-pen"></i>Ubah Email
                </a>
            </div>
            </div>
        </div>
    </div>
</div>

{{-- TAB: SECURITY --}}
<div class="tab-panel" id="tab-security">
    <div class="card animate-in">
        <div class="card-header">
            <div class="ch-icon" style="background:rgba(245,158,11,.15);color:var(--warning)"><i class="fa fa-shield-halved"></i></div>
            Keamanan Akun
        </div>
        <div class="card-body">
            <div class="sec-item">
                <div class="sec-ico" style="background:rgba(16,185,129,.12);color:var(--success)"><i class="fa fa-envelope-circle-check"></i></div>
                <div class="sec-info">
                    <div class="sec-title">Verifikasi Email</div>
                    <div class="sec-desc">{{ auth()->user()?->email }}</div>
                </div>
                <span class="badge-ok"><i class="fa fa-check" style="margin-right:3px"></i>Terverifikasi</span>
            </div>
            <div class="sec-item">
                <div class="sec-ico" style="background:rgba(99,102,241,.12);color:var(--primary)"><i class="fa fa-key"></i></div>
                <div class="sec-info">
                    <div class="sec-title">Password</div>
                    <div class="sec-desc">Terakhir diubah: tidak diketahui</div>
                </div>
                <button class="btn-s" style="padding:6px 14px;font-size:12px" onclick="showPwForm()"><i class="fa fa-pen"></i>Ubah</button>
            </div>
            <div class="sec-item">
                <div class="sec-ico" style="background:rgba(245,158,11,.12);color:var(--warning)"><i class="fa fa-clock-rotate-left"></i></div>
                <div class="sec-info">
                    <div class="sec-title">Sesi Aktif</div>
                    <div class="sec-desc">Login terakhir dari browser ini</div>
                </div>
                <span class="badge-ok">Aktif</span>
            </div>
        </div>
    </div>

    {{-- Change Password Form --}}
    <div class="card animate-in mt-3" id="pwForm" style="display:none">
        <div class="card-header">
            <div class="ch-icon" style="background:rgba(99,102,241,.15);color:var(--primary)"><i class="fa fa-lock"></i></div>
            Ubah Password
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password.update') }}" id="pwChangeForm">
                @csrf @method('PUT')
                <div class="fg">
                    <label class="fl">Password Saat Ini</label>
                    <div class="fi-icon-wrap"><i class="fa fa-lock"></i><input type="password" name="current_password" class="fi" placeholder="••••••••" required></div>
                </div>
                <div class="form-row">
                    <div class="fg">
                        <label class="fl">Password Baru</label>
                        <div class="fi-icon-wrap"><i class="fa fa-lock"></i><input type="password" name="password" id="newPw" class="fi" placeholder="Min. 8 karakter" required oninput="checkStr(this.value)"></div>
                        <div class="pw-str" id="pwStr"><div class="pw-bar"><div class="pw-fill" id="pwFill"></div></div><div class="pw-txt" id="pwTxt"></div></div>
                    </div>
                    <div class="fg">
                        <label class="fl">Konfirmasi Password</label>
                        <div class="fi-icon-wrap"><i class="fa fa-lock"></i><input type="password" name="password_confirmation" class="fi" placeholder="Ulangi password baru" required></div>
                    </div>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button type="submit" class="btn-p" id="savePwBtn"><i class="fa fa-check"></i>Simpan Password</button>
                    <button type="button" class="btn-s" onclick="hidePwForm()"><i class="fa fa-times"></i>Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

{{-- Crop Modal --}}
<div class="crop-modal" id="cropModal">
    <div class="crop-box">
        <h3><i class="fa fa-crop" style="color:var(--primary)"></i>Crop Foto (1:1)</h3>
        <div class="crop-wrap"><img id="cropImg" src="" alt="crop"></div>
        <div class="crop-actions">
            <button type="button" class="btn-s" id="cropCancelBtn" style="cursor:pointer;">Batal</button>
            <button type="button" class="btn-p" id="cropApplyBtn" style="cursor:pointer;"><i class="fa fa-check"></i>Terapkan</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

{{-- Data bridge for JS --}}
<div id="page-data"
    data-switch-info="{{ session('otp_sent') || $errors->has('otp') || $errors->has('new_email') ? '1' : '0' }}"
    data-success="{{ session('success') ? addslashes(session('success')) : '' }}"
    data-error="{{ $errors->first() ? addslashes($errors->first()) : '' }}"
    style="display:none"></div>
@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

// ── TABS ──
function switchTab(id, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}

// Auto-switch to info tab using data bridge
const _pd = document.getElementById('page-data');
if (_pd && _pd.dataset.switchInfo === '1') {
    const infoBtn = document.querySelector('[data-tab-info="1"]');
    if (infoBtn) switchTab('info', infoBtn);
}

// Loading states for OTP forms
const emailReqForm = document.getElementById('emailReqForm');
if (emailReqForm) {
    emailReqForm.addEventListener('submit', function() {
        const btn = document.getElementById('sendOtpBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim...'; }
    });
}

const otpForm = document.getElementById('otpForm');
if (otpForm) {
    otpForm.addEventListener('submit', function() {
        const btn = document.getElementById('verifyOtpBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memverifikasi...'; }
    });
}

// ── CROP ──
let cropper = null;
const MAX = 2 * 1024 * 1024;
const avatarInput = document.getElementById('avatarInput');
const dropZone    = document.getElementById('dropZone');
const fileInfo    = document.getElementById('fileInfo');
const fileErr     = document.getElementById('fileErr');
const saveBtn     = document.getElementById('savePhotoBtn');
const cropModal   = document.getElementById('cropModal');
const cropCancelBtn = document.getElementById('cropCancelBtn');
const cropApplyBtn = document.getElementById('cropApplyBtn');

if (avatarInput) {
    avatarInput.addEventListener('change', function(e) { handleFile(e.target.files[0]); });
}

if (dropZone) {
    dropZone.addEventListener('dragover', function(e) { 
        e.preventDefault(); 
        dropZone.classList.add('drag-over'); 
    });
    dropZone.addEventListener('dragleave', function() { 
        dropZone.classList.remove('drag-over'); 
    });
    dropZone.addEventListener('drop', function(e) { 
        e.preventDefault(); 
        dropZone.classList.remove('drag-over'); 
        handleFile(e.dataTransfer.files[0]); 
    });
}

if (cropCancelBtn) {
    cropCancelBtn.addEventListener('click', closeCrop);
}

if (cropApplyBtn) {
    cropApplyBtn.addEventListener('click', applyCrop);
}

function handleFile(file) {
    if (fileErr) fileErr.style.display = 'none';
    if (!file) return;
    if (!['image/jpeg','image/png'].includes(file.type)) { showErr('Format harus JPG atau PNG.'); return; }
    if (file.size > MAX) { showErr('Ukuran file melebihi 2MB.'); return; }
    const reader = new FileReader();
    reader.onload = e => openCrop(e.target.result);
    reader.readAsDataURL(file);
}

function openCrop(src) {
    const cropImg = document.getElementById('cropImg');
    if (cropImg) cropImg.src = src;
    if (cropModal) cropModal.classList.add('open');
    setTimeout(() => {
        if (cropper) cropper.destroy();
        const img = document.getElementById('cropImg');
        if (img) {
            cropper = new Cropper(img, { aspectRatio: 1, viewMode: 1, autoCropArea: 1 });
        }
    }, 100);
}

function closeCrop() {
    if (cropModal) cropModal.classList.remove('open');
    if (cropper) { cropper.destroy(); cropper = null; }
    if (avatarInput) avatarInput.value = '';
}

function applyCrop() {
    if (!cropper) return;
    cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob(blob => {
        const url = URL.createObjectURL(blob);
        const prev = document.getElementById('avatarPreview');
        const ini  = document.getElementById('avatarInitials');
        if (prev) { prev.src = url; prev.style.display = 'block'; }
        if (ini) ini.style.display = 'none';
        const dt = new DataTransfer();
        dt.items.add(new File([blob], 'avatar.jpg', { type: 'image/jpeg' }));
        if (avatarInput) avatarInput.files = dt.files;
        const mb = (blob.size / 1024 / 1024).toFixed(2);
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        if (fileName) fileName.textContent = 'avatar.jpg';
        if (fileSize) fileSize.textContent = ` (${mb} MB)`;
        if (fileInfo) fileInfo.style.display = 'flex';
        if (saveBtn) saveBtn.disabled = false;
        closeCrop();
    }, 'image/jpeg', 0.92);
}

function clearFile() {
    if (avatarInput) avatarInput.value = ''; 
    if (fileInfo) fileInfo.style.display = 'none'; 
    if (saveBtn) saveBtn.disabled = true;
    // Reset avatar preview logic here based on backend data
}

function showErr(msg) { 
    const fileErrMsg = document.getElementById('fileErrMsg');
    if (fileErrMsg) fileErrMsg.textContent = msg; 
    if (fileErr) fileErr.style.display = 'block'; 
    if (avatarInput) avatarInput.value = ''; 
}

const photoForm = document.getElementById('photoForm');
if (photoForm) {
    photoForm.addEventListener('submit', function() {
        if (saveBtn) { saveBtn.disabled = true; saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menyimpan...'; }
    });
}

// ── PASSWORD FORM ──
window.showPwForm = function() { 
    const pwForm = document.getElementById('pwForm');
    if (pwForm) { pwForm.style.display = 'block'; pwForm.scrollIntoView({ behavior: 'smooth', block: 'start' }); } 
};

window.hidePwForm = function() { 
    const pwForm = document.getElementById('pwForm');
    if (pwForm) pwForm.style.display = 'none'; 
};

window.checkStr = function(val) {
    const el = document.getElementById('pwStr'), fill = document.getElementById('pwFill'), txt = document.getElementById('pwTxt');
    if (!el) return;
    if (!val) { el.style.display = 'none'; return; }
    el.style.display = 'block';
    let s = 0;
    if (val.length >= 8) s++; if (/[A-Z]/.test(val)) s++; if (/[0-9]/.test(val)) s++; if (/[^A-Za-z0-9]/.test(val)) s++;
    const lvl = [{w:'25%',bg:'#ef4444',t:'Lemah'},{w:'50%',bg:'#f59e0b',t:'Cukup'},{w:'75%',bg:'#3b82f6',t:'Kuat'},{w:'100%',bg:'#10b981',t:'Sangat Kuat'}][Math.max(0,s-1)];
    if (fill) { fill.style.width = lvl.w; fill.style.background = lvl.bg; }
    if (txt) { txt.textContent = 'Kekuatan: ' + lvl.t; txt.style.color = lvl.bg; }
};

const pwChangeForm = document.getElementById('pwChangeForm');
if (pwChangeForm) {
    pwChangeForm.addEventListener('submit', function() {
        const btn = document.getElementById('savePwBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menyimpan...'; }
    });
}

// ── TOAST ──
window.showToast = function(msg, type = 'ok') {
    const t = document.getElementById('toast');
    if (!t) return;
    t.className = `toast ${type}`;
    t.innerHTML = `<i class="fa fa-${type==='ok'?'check-circle':'exclamation-circle'}"></i> ${msg}`;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
};

// Toast from session via data bridge
if (_pd) {
    if (_pd.dataset.success) showToast(_pd.dataset.success, 'ok');
    else if (_pd.dataset.error) showToast(_pd.dataset.error, 'err');
}

}); // DOMContentLoaded
</script>
@endpush
