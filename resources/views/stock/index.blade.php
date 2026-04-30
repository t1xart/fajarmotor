@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('content')

<div class="page-header animate-in">
    <div>
        <h4>Manajemen Stok</h4>
        <div class="text-muted mt-1" style="font-size:.78rem">Pantau dan kelola stok per gudang</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.mutations') }}" class="btn btn-sm" style="background:#f1f5f9;border:none;color:#64748b">
            <i class="fa fa-history me-1"></i>Mutasi Stok
        </a>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#adjustModal">
            <i class="fa fa-sliders me-2"></i>Sesuaikan Stok
        </button>
    </div>
</div>

<div class="card mb-3 animate-in">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari produk atau SKU..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="warehouse_id" class="form-select">
                    <option value="">Semua Gudang</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }} — {{ $wh->branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="fa fa-search"></i></button>
                <a href="{{ route('stock.index') }}" class="btn" style="background:#f1f5f9;border:none;color:#64748b"><i class="fa fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Produk</th><th>Kategori</th><th>Gudang</th><th>Stok</th><th>Min</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($stocks as $rec)
                    <tr>
                        <td>
                            <div class="fw-semibold" style="font-size:.83rem">{{ $rec->product->name }}</div>
                            <div class="text-muted font-monospace" style="font-size:.7rem">{{ $rec->product->sku }}</div>
                        </td>
                        <td><span class="badge badge-soft-purple">{{ $rec->product->category->name ?? '—' }}</span></td>
                        <td>
                            <div style="font-size:.82rem">{{ $rec->warehouse->name }}</div>
                            <div class="text-muted" style="font-size:.72rem">{{ $rec->warehouse->branch->name }}</div>
                        </td>
                        <td>
                            <span class="fw-bold {{ $rec->quantity <= $rec->product->min_stock ? 'text-danger' : 'text-success' }}" style="font-size:1rem">
                                {{ $rec->quantity }}
                            </span>
                            <span class="text-muted" style="font-size:.72rem"> {{ $rec->product->unit }}</span>
                        </td>
                        <td class="text-muted">{{ $rec->product->min_stock }}</td>
                        <td>
                            @if($rec->quantity <= 0)
                                <span class="badge badge-soft-danger">Habis</span>
                            @elseif($rec->quantity <= $rec->product->min_stock)
                                <span class="badge badge-soft-warning">Menipis</span>
                            @else
                                <span class="badge badge-soft-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa fa-warehouse fa-3x d-block mb-3 opacity-20"></i>Tidak ada data stok
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stocks->hasPages())
    <div class="card-body border-top py-3">{{ $stocks->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

{{-- Adjust Modal --}}
<div class="modal fade" id="adjustModal" tabindex="-1" style="z-index: 1060;" @if($errors->hasAny(['product_id', 'warehouse_id', 'type', 'quantity', 'to_warehouse_id'])) data-bs-show="true" @endif>
    <div class="modal-dialog modal-dialog-centered" style="z-index: 1061;">
        <div class="modal-content" style="border-radius:1rem;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);position:relative;z-index:1061;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Sesuaikan Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="stockAdjustForm" method="POST" action="{{ route('stock.adjust') }}">
                @csrf
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Produk</label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">— Pilih Produk —</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->name }} ({{ $p->sku }})</option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gudang</label>
                        <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">— Pilih Gudang —</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" @selected(old('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" id="adjustType" required>
                            <option value="">— Pilih Tipe —</option>
                            @foreach(['in' => 'Stok Masuk', 'out' => 'Stok Keluar', 'adjustment' => 'Penyesuaian', 'transfer' => 'Transfer Gudang'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('type') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" placeholder="0" value="{{ old('quantity') }}" required min="0">
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 {{ old('type') === 'transfer' ? '' : 'd-none' }}" id="toWarehouseField" style="margin-top:0;">
                        <label class="form-label">Gudang Tujuan</label>
                        <select name="to_warehouse_id" class="form-select @error('to_warehouse_id') is-invalid @enderror">
                            <option value="">— Pilih Gudang —</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" @selected(old('to_warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('to_warehouse_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" placeholder="Opsional...">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn" style="background:#f1f5f9;border:none;color:#64748b;cursor:pointer;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="cursor:pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const adjustTypeSelect = document.getElementById('adjustType');
    const toWarehouseField = document.getElementById('toWarehouseField');
    const form = document.getElementById('stockAdjustForm');
    const modalEl = document.getElementById('adjustModal');
    
    // Re-open modal if there were errors
    if (modalEl && modalEl.dataset.bsShow === 'true') {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
    
    // Handle type change
    if (adjustTypeSelect && toWarehouseField) {
        adjustTypeSelect.addEventListener('change', function() {
            if (this.value === 'transfer') {
                toWarehouseField.classList.remove('d-none');
            } else {
                toWarehouseField.classList.add('d-none');
            }
        });
    }
    
    // Client-side validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const type = adjustTypeSelect.value;
            if (type === 'transfer') {
                const toWh = form.querySelector('[name="to_warehouse_id"]').value;
                const fromWh = form.querySelector('[name="warehouse_id"]').value;
                
                if (!toWh) {
                    e.preventDefault();
                    alert('Gudang tujuan harus dipilih untuk transfer');
                    return false;
                }
                
                if (toWh === fromWh) {
                    e.preventDefault();
                    alert('Gudang tujuan tidak boleh sama dengan gudang sumber');
                    return false;
                }
            }
        });
    }
});
</script>
@endpush
