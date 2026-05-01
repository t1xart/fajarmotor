@extends('layouts.app')
@section('title', 'Work Order')
@section('content')

<div class="page-header animate-in">
    <div>
        <h4>Work Order</h4>
        <div class="text-muted mt-1" style="font-size:.78rem">Kelola servis dan pekerjaan bengkel</div>
    </div>
    @can('wo.create')
    <a href="{{ route('work-orders.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Buat WO</a>
    @endcan
</div>

<div class="card mb-3 animate-in">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama pelanggan / plat nomor..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Pending','in_progress'=>'Dikerjakan','done'=>'Selesai','delivered'=>'Diserahkan','cancelled'=>'Dibatalkan'] as $v => $l)
                        <option value="{{ $v }}" @selected(request('status') == $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="fa fa-search"></i></button>
                <a href="{{ route('work-orders.index') }}" class="btn" style="background:#f1f5f9;border:none;color:#64748b"><i class="fa fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card animate-in">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>No. WO</th><th>Pelanggan</th><th>Kendaraan</th><th>Total</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
            @forelse($orders as $wo)
                @php $sc = ['pending'=>'badge-soft-warning','in_progress'=>'badge-soft-info','done'=>'badge-soft-success','delivered'=>'badge-soft-purple','cancelled'=>'badge-soft-danger']; @endphp
                <tr>
                    <td><span class="fw-semibold font-monospace" style="color:#6366f1;font-size:.8rem">{{ $wo->wo_number }}</span></td>
                    <td>
                        <div class="fw-semibold" style="font-size:.82rem">{{ $wo->customer_name }}</div>
                        <div class="text-muted" style="font-size:.72rem">{{ $wo->customer_phone }}</div>
                    </td>
                    <td>
                        <div style="font-size:.78rem">{{ $wo->vehicleType?->brand?->name }} {{ $wo->vehicleType?->name }}</div>
                        <div class="text-muted font-monospace" style="font-size:.72rem">{{ $wo->vehicle_plate }}</div>
                    </td>
                    <td class="fw-semibold">Rp {{ number_format($wo->total, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $sc[$wo->status] ?? 'badge-soft-gray' }}">{{ ucfirst(str_replace('_', ' ', $wo->status)) }}</span></td>
                    <td class="text-muted" style="font-size:.75rem">{{ $wo->created_at->format('d/m/Y') }}</td>
                    <td><a href="{{ route('work-orders.show', $wo) }}" class="btn btn-sm btn-icon" style="background:#f1f5f9;border:none;color:#64748b"><i class="fa fa-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="fa fa-wrench fa-3x d-block mb-3 opacity-20"></i>Tidak ada data work order
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-body border-top py-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
