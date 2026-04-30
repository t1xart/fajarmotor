@extends('layouts.app')
@section('title', 'Buat Akun Baru')

@push('styles')
<style>
.reg-wrap { max-width: 680px; margin: 0 auto; }
.pw-str { margin-top: 5px; display: none; }
.pw-bar { height: 3px; background: var(--border); border-radius: 99px; overflow: hidden; margin-bottom: 3px; }
.pw-fill { height: 100%; border-radius: 99px; transition: width .3s, background .3s; width: 0; }
.pw-txt { font-size: 10px; color: var(--text3); }
.role-card {
    border: 2px solid var(--border); border-radius: 10px; padding: 14px;
    cursor: pointer; transition: all .2s; position: relative;
}
.role-card:hover { border-color: var(--primary); background: rgba(99,102,241,.04); }
.role-card.selected { border-color: var(--primary); background: rgba(99,102,241,.08); }
.role-card.selected::after {
    content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
    position: absolute; top: 10px; right: 12px; color: var(--primary); font-size: 12px;
}
.role-ico { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 15px; margin-bottom: 8px; }
.role-name { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
.role-desc { font-size: 11px; color: var(--text3); line-height: 1.5; }
</style>
@endpush

@section('content')
<div class="reg-wrap">
    <div class="page-header animate-in">
        <div>
            <h4>Buat Akun Baru</h4>
            <div class="sub">Tambahkan pengguna baru ke sistem FajarMotor</div>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i>Kelola Pengguna
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger animate-in mb-3">
        <i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}
    </div>
    @endif

    <div class="card animate-in">
        <div class="card-header">
            <div class="ch-icon" style="background:rgba(99,102,241,.15);color:var(--primary)"><i class="fa fa-user-plus"></i></div>
            Informasi Akun
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.register.store') }}" id="regForm">
                @csrf

                {{-- Name + Email --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
                    <div>
                        <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                        <div class="fi-wrap">
                            <i class="fa fa-user fi-ico"></i>
                            <input type="text" name="name" value="{{ old('name') }}" class="fi-reg"
                                placeholder="Nama lengkap pengguna" required>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Alamat Email <span style="color:var(--danger)">*</span></label>
                        <div class="fi-wrap">
                            <i class="fa fa-envelope fi-ico"></i>
                            <input type="email" name="email" value="{{ old('email') }}" class="fi-reg"
                                placeholder="email@domain.com" required>
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px">
                    <div>
                        <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Password <span style="color:var(--danger)">*</span></label>
                        <div class="fi-wrap">
                            <i class="fa fa-lock fi-ico"></i>
                            <input type="password" id="pw" name="password" class="fi-reg"
                                placeholder="Min. 8 karakter" required style="padding-right: 2.5rem;">
                            <button type="button" class="tpw-btn" data-target="pw" data-icon="pwi">
                                <i class="fa fa-eye" id="pwi"></i>
                            </button>
                        </div>
                        <div class="pw-str" id="pwStr">
                            <div class="pw-bar"><div class="pw-fill" id="pwFill"></div></div>
                            <div class="pw-txt" id="pwTxt"></div>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Konfirmasi Password <span style="color:var(--danger)">*</span></label>
                        <div class="fi-wrap">
                            <i class="fa fa-lock fi-ico"></i>
                            <input type="password" id="pw2" name="password_confirmation" class="fi-reg"
                                placeholder="Ulangi password" required style="padding-right: 2.5rem;">
                            <button type="button" class="tpw-btn" data-target="pw2" data-icon="pw2i">
                                <i class="fa fa-eye" id="pw2i"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Role Selection --}}
                <div style="margin-bottom:20px">
                    <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:10px">Hak Akses (Role) <span style="color:var(--danger)">*</span></label>
                    <input type="hidden" name="role" id="roleInput" value="{{ old('role') }}" required>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                        @foreach($roles as $role)
                        @php
                        $meta = [
                            'admin'     => ['ico'=>'fa-shield-halved','color'=>'rgba(99,102,241,.15)','text'=>'var(--primary)','desc'=>'Akses penuh ke semua fitur sistem'],
                            'cashier'   => ['ico'=>'fa-cash-register','color'=>'rgba(16,185,129,.15)','text'=>'var(--success)','desc'=>'POS kasir, penjualan, work order'],
                            'warehouse' => ['ico'=>'fa-warehouse','color'=>'rgba(245,158,11,.15)','text'=>'var(--warning)','desc'=>'Stok, produk, purchase order'],
                        ][$role->name] ?? ['ico'=>'fa-user','color'=>'rgba(99,102,241,.15)','text'=>'var(--primary)','desc'=>'Akses standar'];
                        @endphp
                        <div class="role-card {{ old('role') === $role->name ? 'selected' : '' }}" data-role="{{ $role->name }}">
                            <div class="role-ico" style="background:{{ $meta['color'] }};color:{{ $meta['text'] }}">
                                <i class="fa {{ $meta['ico'] }}"></i>
                            </div>
                            <div class="role-name">{{ ucfirst($role->name) }}</div>
                            <div class="role-desc">{{ $meta['desc'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Branch --}}
                @if($branches->count())
                <div style="margin-bottom:20px">
                    <label style="font-size:12px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px">Cabang</label>
                    <div class="fi-wrap">
                        <i class="fa fa-building fi-ico"></i>
                        <select name="branch_id" class="fi-sel">
                            <option value="">— Pilih Cabang —</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div style="display:flex;gap:10px;padding-top:4px">
                    <button type="submit" id="submitBtn" class="btn-reg-sub">
                        <i class="fa fa-user-plus"></i>Buat Akun
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn-reg-can">
                        <i class="fa fa-times"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.fi-wrap { position:relative; }
.fi-ico { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); color:var(--text3); font-size:.8rem; pointer-events:none; }
.fi-reg { width:100%; padding:9px 14px 9px 2.5rem; border:1.5px solid var(--border); border-radius:9px; font-size:13px; font-family:'Inter',sans-serif; color:var(--text); background:var(--bg); outline:none; transition:all .2s; }
.fi-reg:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(99,102,241,.1); background:var(--surface); }
.fi-sel { width:100%; padding:9px 14px 9px 2.5rem; border:1.5px solid var(--border); border-radius:9px; font-size:13px; font-family:'Inter',sans-serif; color:var(--text); background:var(--bg); outline:none; appearance:none; transition:all .2s; }
.fi-sel:focus { border-color:var(--primary); background:var(--surface); }
.tpw-btn { position:absolute; right:.9rem; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text3); cursor:pointer; font-size:.8rem; }
.btn-reg-sub { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:#fff; border:none; border-radius:9px; padding:10px 24px; font-size:13px; font-weight:700; font-family:'Inter',sans-serif; cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:7px; }
.btn-reg-sub:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.4); }
.btn-reg-can { background:transparent; color:var(--text2); border:1.5px solid var(--border); border-radius:9px; padding:10px 20px; font-size:13px; font-weight:600; font-family:'Inter',sans-serif; cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:7px; text-decoration:none; }
.btn-reg-can:hover { border-color:var(--primary); color:var(--primary); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleInput = document.getElementById('roleInput');
    const roleCards = document.querySelectorAll('.role-card');
    const regForm   = document.getElementById('regForm');
    const submitBtn = document.getElementById('submitBtn');

    // Role Selection
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            roleCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            if (roleInput) roleInput.value = this.dataset.role;
        });
    });

    // Password Toggle
    document.querySelectorAll('.tpw-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const iconId = this.dataset.icon;
            const input = document.getElementById(targetId);
            const icon = document.getElementById(iconId);
            
            if (input && icon) {
                const isPw = input.type === 'password';
                input.type = isPw ? 'text' : 'password';
                icon.className = isPw ? 'fa fa-eye-slash' : 'fa fa-eye';
            }
        });
    });

    // Password Strength
    const pwInput = document.getElementById('pw');
    pwInput?.addEventListener('input', function() {
        const val = this.value;
        const strEl = document.getElementById('pwStr');
        const fill = document.getElementById('pwFill');
        const txt = document.getElementById('pwTxt');

        if (!strEl) return;
        if (!val) { strEl.style.display = 'none'; return; }
        
        strEl.style.display = 'block';
        let s = 0;
        if (val.length >= 8) s++;
        if (/[A-Z]/.test(val)) s++;
        if (/[0-9]/.test(val)) s++;
        if (/[^A-Za-z0-9]/.test(val)) s++;
        
        const levels = [
            {w:'25%',bg:'#ef4444',t:'Lemah'},
            {w:'50%',bg:'#f59e0b',t:'Cukup'},
            {w:'75%',bg:'#3b82f6',t:'Kuat'},
            {w:'100%',bg:'#10b981',t:'Sangat Kuat'}
        ];
        const lvl = levels[Math.max(0, s - 1)];
        
        if (fill) { fill.style.width = lvl.w; fill.style.background = lvl.bg; }
        if (txt) { txt.textContent = 'Kekuatan: ' + lvl.t; txt.style.color = lvl.bg; }
    });

    // Form Submission
    regForm?.addEventListener('submit', function(e) {
        if (roleInput && !roleInput.value) {
            e.preventDefault();
            alert('Pilih hak akses (role) terlebih dahulu.');
            return;
        }
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Membuat...';
        }
    });
});
</script>
@endpush
