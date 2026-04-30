@extends('layouts.app')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('content')

<div class="page-header animate-in">
    <div>
        <h4>{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h4>
        <div class="text-muted mt-1" style="font-size:.78rem">
            {{ isset($product) ? 'Perbarui informasi produk ' . $product->name : 'Isi detail produk sparepart baru' }}
        </div>
    </div>
    <a href="{{ route('products.index') }}" class="btn" style="background:#f1f5f9;border:none;color:#64748b">
        <i class="fa fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form method="POST" action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <h6>Kesalahan Validasi:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="card mb-3 animate-in">
                <div class="card-header">
                    <div class="icon-badge" style="background:#ede9fe;color:#6366f1"><i class="fa fa-info-circle"></i></div>
                    Informasi Dasar
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name ?? '') }}" placeholder="Contoh: Piston Honda Beat 110cc" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">— Pilih Kategori —</option>
                                @foreach($categories as $cat)
                                    @if(!$cat->parent_id)
                                        <optgroup label="{{ $cat->name }}">
                                            @foreach($categories->where('parent_id', $cat->id) as $child)
                                                <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id ?? '') == $child->id)>{{ $child->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                @foreach(['pcs'=>'PCS','set'=>'SET','liter'=>'LITER','meter'=>'METER','roll'=>'ROLL'] as $v => $l)
                                    <option value="{{ $v }}" @selected(old('unit', $product->unit ?? 'pcs') == $v)>{{ $l }}</option>
                                @endforeach
                            </select>
                            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kode Rak</label>
                            <input type="text" name="shelf_code" class="form-control" value="{{ old('shelf_code', $product->shelf_code ?? '') }}" placeholder="A-01-02">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi produk, spesifikasi, dll.">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="card mb-3 animate-in">
                <div class="card-header">
                    <div class="icon-badge" style="background:#d1fae5;color:#059669"><i class="fa fa-tag"></i></div>
                    Harga & Stok
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="buy_price" class="form-control @error('buy_price') is-invalid @enderror" value="{{ old('buy_price', $product->buy_price ?? 0) }}" min="0" required>
                            </div>
                            @error('buy_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="sell_price" class="form-control @error('sell_price') is-invalid @enderror" value="{{ old('sell_price', $product->sell_price ?? 0) }}" min="0" required>
                            </div>
                            @error('sell_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', $product->min_stock ?? 5) }}" min="0" required>
                            @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Garansi (hari)</label>
                            <input type="number" name="warranty_days" class="form-control" value="{{ old('warranty_days', $product->warranty_days ?? 0) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Berat (gram)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight', $product->weight ?? '') }}" min="0" step="0.01">
                        </div>
                        <div class="col-md-4 d-flex align-items-end pb-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                       @checked(old('is_active', $product->is_active ?? true))>
                                <label class="form-check-label" for="isActive">Produk Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Compatibility --}}
            <div class="card animate-in">
                <div class="card-header">
                    <div class="icon-badge" style="background:#dbeafe;color:#2563eb"><i class="fa fa-motorcycle"></i></div>
                    Kompatibilitas Kendaraan
                </div>
                <div class="card-body">
                    @foreach($vehicleTypes as $brand => $types)
                    <div class="mb-3">
                        <div class="fw-semibold mb-2" style="font-size:.8rem;color:#6366f1">
                            <i class="fa fa-chevron-right me-1" style="font-size:.65rem"></i>{{ $brand }}
                        </div>
                        <div class="row g-1">
                            @foreach($types as $type)
                            <div class="col-md-3 col-6">
                                <label class="compatibility-label d-flex align-items-center gap-2 p-2 rounded cursor-pointer">
                                    <input type="checkbox" name="compatibility[]" value="{{ $type->id }}"
                                           @checked(in_array($type->id, old('compatibility', $selected ?? [])))>
                                    {{ $type->name }}
                                    <span class="badge badge-soft-gray ms-auto">{{ $type->cc }}cc</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">
            {{-- Images --}}
            <div class="card mb-3 animate-in">
                <div class="card-header">
                    <div class="icon-badge" style="background:#fef3c7;color:#d97706"><i class="fa fa-image"></i></div>
                    Foto Produk
                </div>
                <div class="card-body">
                    @isset($product)
                    @if($product->images->isNotEmpty())
                    <div class="row g-2 mb-3">
                        @foreach($product->images as $img)
                        <div class="col-6 position-relative">
                            <img src="{{ Storage::url($img->image_path) }}" class="img-fluid rounded" style="height:90px;object-fit:cover;width:100%">
                            <form method="POST" action="{{ route('products.images.destroy', $img) }}" class="position-absolute top-0 end-0 m-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-delete-img" style="background:rgba(239,68,68,.9);border:none;color:#fff;width:22px;height:22px;font-size:.6rem"><i class="fa fa-times"></i></button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endisset
                    <div id="dropZone" class="upload-zone border-2 border-dashed rounded p-3 text-center">
                        <i class="fa fa-cloud-upload-alt fa-2x text-muted mb-2 d-block opacity-50"></i>
                        <div style="font-size:.78rem;color:#64748b">Klik untuk upload foto</div>
                        <div style="font-size:.7rem;color:#94a3b8">JPG, PNG — maks 2MB</div>
                    </div>
                    <input type="file" id="imgInput" name="images[]" class="d-none" multiple accept="image/*">
                    <div id="imgPreview" class="row g-2 mt-2"></div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="card animate-in">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fa fa-save me-2"></i>{{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}
                    </button>
                    <a href="{{ route('products.index') }}" class="btn w-100 mt-2" style="background:#f1f5f9;border:none;color:#64748b">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@push('styles')
<style>
.compatibility-label { border: 1px solid #e2e8f0; font-size: .78rem; transition: .15s; }
.compatibility-label:hover { border-color: #6366f1; }
.upload-zone { border: 2px dashed #e2e8f0; cursor: pointer; transition: all .2s; }
.upload-zone:hover { border-color: #6366f1; background: rgba(99,102,241,.04); }
.btn-delete-img:hover { background: #ef4444 !important; transform: scale(1.1); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imgInput = document.getElementById('imgInput');
    const dropZone = document.getElementById('dropZone');
    const preview  = document.getElementById('imgPreview');

    if (dropZone) {
        dropZone.addEventListener('click', () => imgInput?.click());
    }

    if (imgInput) {
        imgInput.addEventListener('change', function() {
            if (!preview) return;
            preview.innerHTML = '';
            [...this.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const col = document.createElement('div');
                    col.className = 'col-6';
                    col.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="height:80px;object-fit:cover;width:100%">`;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Confirmation for image deletion
    document.querySelectorAll('.btn-delete-img').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Hapus foto ini?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
