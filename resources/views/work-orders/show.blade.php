@extends('layouts.app')
@section('title', 'Detail WO')
@section('content')

<div class="page-header animate-in">
    <div>
        <h4>{{ $workOrder->wo_number }}</h4>
        <div class="text-muted mt-1" style="font-size:.78rem">Detail Work Order</div>
    </div>
    <a href="{{ route('work-orders.index') }}" class="btn" style="background:#f1f5f9;border:none;color:#64748b"><i class="fa fa-arrow-left me-2"></i>Kembali</a>
</div>

@php $sc = ['pending'=>'badge-soft-warning','in_progress'=>'badge-soft-info','done'=>'badge-soft-success','delivered'=>'badge-soft-purple','cancelled'=>'badge-soft-danger']; @endphp

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3 animate-in">
            <div class="card-body row">
                <div class="col-md-6">
                    <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Pelanggan</div>
                    <div class="fw-semibold">{{ $workOrder->customer_name }}</div>
                    @if($workOrder->customer_phone)<div class="text-muted" style="font-size:.78rem">{{ $workOrder->customer_phone }}</div>@endif
                </div>
                <div class="col-md-6">
                    <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Kendaraan</div>
                    <div class="fw-semibold">{{ $workOrder->vehicleType?->brand?->name }} {{ $workOrder->vehicleType?->name }} {{ $workOrder->vehicle_year }}</div>
                    @if($workOrder->vehicle_plate)<div class="text-muted font-monospace" style="font-size:.78rem">{{ $workOrder->vehicle_plate }}</div>@endif
                </div>
                @if($workOrder->complaint)
                <div class="col-12 mt-3">
                    <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Keluhan</div>
                    <div style="font-size:.82rem">{{ $workOrder->complaint }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="card animate-in">
            <div class="card-header">
                <div class="icon-badge" style="background:#d1fae5;color:#059669"><i class="fa fa-list"></i></div>
                Item Pekerjaan
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Deskripsi</th><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    @forelse($workOrder->items as $item)
                        <tr>
                            <td class="fw-semibold" style="font-size:.82rem">{{ $item->description }}</td>
                            <td class="text-muted" style="font-size:.78rem">{{ $item->product->name ?? '—' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada item</td></tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                        <tr><td colspan="4" class="text-end text-muted py-2">Biaya Jasa</td><td class="py-2">Rp {{ number_format($workOrder->service_fee, 0, ',', '.') }}</td></tr>
                        <tr><td colspan="4" class="text-end text-muted py-2">Total Parts</td><td class="py-2">Rp {{ number_format($workOrder->parts_total, 0, ',', '.') }}</td></tr>
                        <tr style="background:#f8fafc"><td colspan="4" class="text-end fw-bold py-3">TOTAL</td><td class="fw-bold py-3" style="color:#6366f1">Rp {{ number_format($workOrder->total, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3 animate-in">
            <div class="card-body">
                <div class="text-muted mb-2" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Status</div>
                <span class="badge {{ $sc[$workOrder->status] ?? 'badge-soft-gray' }}" style="font-size:.82rem;padding:.4em .8em">{{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}</span>
                @if($workOrder->started_at)<div class="text-muted mt-3" style="font-size:.75rem">Mulai: {{ $workOrder->started_at->format('d/m/Y H:i') }}</div>@endif
                @if($workOrder->finished_at)<div class="text-muted" style="font-size:.75rem">Selesai: {{ $workOrder->finished_at->format('d/m/Y H:i') }}</div>@endif
            </div>
        </div>

        @can('wo.update')
        @if(!in_array($workOrder->status, ['delivered', 'cancelled']))
        <div class="card animate-in">
            <div class="card-header">
                <div class="icon-badge" style="background:#ede9fe;color:#6366f1"><i class="fa fa-pen"></i></div>
                Update Status
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('work-orders.status', $workOrder) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending'=>'Pending','in_progress'=>'Dikerjakan','done'=>'Selesai','delivered'=>'Diserahkan','cancelled'=>'Dibatalkan'] as $val => $label)
                            <option value="{{ $val }}" @selected($workOrder->status == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
        @endif
        @endcan
    </div>
</div>
@endsection
