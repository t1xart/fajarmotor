@extends('layouts.app')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="page-header animate-in">
    <div>
        <h4>Kelola Pengguna</h4>
        <div class="sub">Manajemen akun pengguna sistem FajarMotor</div>
    </div>
    <a href="{{ route('admin.register') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-user-plus me-1"></i>Buat Akun Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success animate-in mb-3"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger animate-in mb-3"><i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
@endif

<div class="card animate-in">
    <div class="card-header">
        <div class="ch-icon" style="background:rgba(99,102,241,.15);color:var(--primary)"><i class="fa fa-users"></i></div>
        Daftar Pengguna
        <span class="badge badge-soft-info ms-1">{{ $users->count() }} akun</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover">
                            @else
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#818cf8);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-size:13px;font-weight:600">{{ $user->name }}
                                    @if($user->id === auth()->id())
                                    <span style="font-size:10px;background:rgba(99,102,241,.12);color:var(--primary);border-radius:20px;padding:1px 7px;margin-left:4px">Anda</span>
                                    @endif
                                </div>
                                <div style="font-size:11px;color:var(--text3)">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $role = $user->roles->first()?->name; @endphp
                        @if($role)
                        <span class="badge {{ $role==='admin' ? 'badge-soft-purple' : ($role==='cashier' ? 'badge-soft-success' : 'badge-soft-warning') }}">
                            {{ ucfirst($role) }}
                        </span>
                        @else
                        <span style="color:var(--text3);font-size:12px">—</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text2)">{{ $user->branch?->name ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                        <span class="badge badge-soft-success"><i class="fa fa-circle" style="font-size:7px;margin-right:4px"></i>Aktif</span>
                        @else
                        <span class="badge badge-soft-danger"><i class="fa fa-circle" style="font-size:7px;margin-right:4px"></i>Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:11px;color:var(--text3)">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display:inline" class="toggle-form">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle-user"
                                data-confirm="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?"
                                style="background:{{ $user->is_active ? 'rgba(239,68,68,.1)' : 'rgba(16,185,129,.1)' }};color:{{ $user->is_active ? 'var(--danger)' : 'var(--success)' }};border:1px solid {{ $user->is_active ? 'rgba(239,68,68,.2)' : 'rgba(16,185,129,.2)' }};border-radius:7px;padding:4px 12px;font-size:11px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:all .2s">
                                <i class="fa fa-{{ $user->is_active ? 'ban' : 'check' }}" style="margin-right:4px"></i>
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.table');
    table?.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-toggle-user');
        if (btn) {
            if (!confirm(btn.dataset.confirm)) {
                e.preventDefault();
            } else {
                const btnContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Memproses...';
            }
        }
    });
});
</script>
@endpush
