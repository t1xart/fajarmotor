@extends('layouts.app')
@section('title', 'POS — Kasir')
@push('styles')
<style>
    .page-content { padding: 1rem; }
    .product-card {
        cursor: pointer;
        border: 1.5px solid #e2e8f0;
        border-radius: .65rem;
        padding: .75rem;
        text-align: center;
        transition: all .18s;
        background: #fff;
        height: 100%;
    }
    .product-card:hover { border-color: #6366f1; transform: translateY(-2px); box-shadow: 0 4px 16px rgba(99,102,241,.15); }
    .product-card.out-of-stock { opacity: .45; pointer-events: none; }
    .product-card .thumb {
        width: 52px; height: 52px;
        border-radius: .5rem;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .5rem;
        overflow: hidden;
    }
    .product-card .thumb img { width: 52px; height: 52px; object-fit: cover; }
    .product-card .p-name { font-size: .75rem; font-weight: 600; color: #0f172a; line-height: 1.3; }
    .product-card .p-price { font-size: .78rem; font-weight: 700; color: #6366f1; margin-top: .2rem; }
    .product-card .p-stock { font-size: .65rem; margin-top: .25rem; }

    .cart-item {
        display: flex; align-items: center; gap: .65rem;
        padding: .6rem .75rem;
        border-radius: .5rem;
        background: #f8fafc;
        margin-bottom: .4rem;
        transition: background .15s;
    }
    .cart-item:hover { background: #f1f5f9; }
    .qty-btn {
        width: 24px; height: 24px;
        border-radius: .35rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: .7rem; color: #64748b;
        transition: all .15s;
    }
    .qty-btn:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
    #cartScroll { max-height: calc(100vh - 430px); overflow-y: auto; }
</style>
@endpush
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-bold mb-0" style="font-size:1rem"><i class="fa fa-cash-register me-2" style="color:#6366f1"></i>Point of Sale</h4>
    <a href="{{ route('sales.index') }}" class="btn btn-sm" style="background:#f1f5f9;border:none;color:#64748b"><i class="fa fa-history me-1"></i>Riwayat</a>
</div>

<div class="row g-3" style="height:calc(100vh - 120px)">
    {{-- Products --}}
    <div class="col-lg-7 d-flex flex-column" style="height:100%">
        <div class="card flex-grow-1 d-flex flex-column overflow-hidden">
            <div class="card-body pb-2 pt-3 px-3 flex-shrink-0">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" id="productSearch" class="form-control" placeholder="Cari produk atau scan barcode...">
                </div>
            </div>
            <div class="overflow-auto flex-grow-1 px-3 pb-3">
                <div class="row g-2" id="productGrid">
                    @foreach($products as $product)
                    @php $stock = $product->totalStock(); @endphp
                    <div class="col-6 col-md-4 col-xl-3 product-item {{ $stock <= 0 ? 'out-of-stock' : '' }}"
                         data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku }}"
                         data-price="{{ $product->sell_price }}" data-stock="{{ $stock }}" data-unit="{{ $product->unit }}">
                        <div class="product-card">
                            <div class="thumb">
                                @if($product->primaryImage)
                                    <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="">
                                @else
                                    <i class="fa fa-box text-muted" style="font-size:.9rem"></i>
                                @endif
                            </div>
                            <div class="p-name">{{ str($product->name)->limit(28) }}</div>
                            <div class="p-price">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                            <div class="p-stock">
                                <span class="badge {{ $stock <= 0 ? 'badge-soft-danger' : 'badge-soft-success' }}">{{ $stock }} {{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Cart --}}
    <div class="col-lg-5 d-flex flex-column" style="height:100%">
        <div class="card flex-grow-1 d-flex flex-column overflow-hidden">
            <div class="card-header flex-shrink-0">
                <div class="icon-badge" style="background:#ede9fe;color:#6366f1"><i class="fa fa-shopping-cart"></i></div>
                Keranjang
                <button class="btn btn-sm ms-auto" style="background:#fee2e2;border:none;color:#dc2626;font-size:.72rem" id="clearCart">
                    <i class="fa fa-trash me-1"></i>Kosongkan
                </button>
            </div>

            <div id="cartScroll" class="flex-grow-1 p-3">
                <div id="emptyCart" class="text-center py-5 text-muted">
                    <i class="fa fa-shopping-cart fa-3x d-block mb-3 opacity-20"></i>
                    <div style="font-size:.82rem">Pilih produk untuk ditambahkan</div>
                </div>
            </div>

            <div class="flex-shrink-0 border-top p-3">
                <div class="d-flex justify-content-between mb-2" style="font-size:.82rem">
                    <span class="text-muted">Subtotal</span>
                    <span id="subtotalDisplay" class="fw-semibold">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.82rem">
                    <span class="text-muted">Diskon</span>
                    <div class="input-group" style="width:130px">
                        <span class="input-group-text" style="font-size:.75rem">Rp</span>
                        <input type="number" id="discountInput" class="form-control form-control-sm text-end" value="0" min="0">
                    </div>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top">
                    <span class="fw-bold">Total</span>
                    <span id="totalDisplay" class="fw-bold" style="font-size:1.1rem;color:#6366f1">Rp 0</span>
                </div>
            </div>

            <div class="flex-shrink-0 p-3 pt-0">
                <button class="btn btn-primary w-100 py-2 fw-semibold" id="checkoutBtn" disabled>
                    <i class="fa fa-check-circle me-2"></i>Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Checkout Modal --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="z-index: 1061;">
        <div class="modal-content" style="border-radius:1rem;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);position:relative;z-index:1061;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Proses Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('sales.store') }}" id="checkoutForm">
                @csrf
                <div class="modal-body pt-2">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <div class="d-flex gap-2">
                                @foreach(['cash'=>['Tunai','fa-money-bill'],'transfer'=>['Transfer','fa-university'],'qris'=>['QRIS','fa-qrcode']] as $val => [$label, $icon])
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="payment_method" id="pm_{{ $val }}" value="{{ $val }}" @checked($val==='cash')>
                                    <label class="btn w-100 py-2" style="border:1.5px solid #e2e8f0;font-size:.78rem;border-radius:.6rem" for="pm_{{ $val }}">
                                        <i class="fa {{ $icon }} d-block mb-1" style="font-size:1rem"></i>{{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Jumlah Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text fw-semibold">Rp</span>
                                <input type="number" name="paid_amount" id="paidAmount" class="form-control" style="font-size:1.1rem;font-weight:700" required min="0">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background:#f0fdf4">
                                <span style="font-size:.82rem;color:#065f46">Kembalian</span>
                                <strong id="changeDisplay" style="color:#059669;font-size:1.1rem">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="discount" id="discountHidden">
                    <div id="cartItemsHidden"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn" style="background:#f1f5f9;border:none;color:#64748b;cursor:pointer;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="cursor:pointer;"><i class="fa fa-check me-2"></i>Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let cart = {};
const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
const getDiscount = () => parseFloat(document.getElementById('discountInput').value) || 0;

function updateCart() {
    const items = Object.values(cart);
    const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
    const total = Math.max(0, subtotal - getDiscount());
    document.getElementById('subtotalDisplay').textContent = fmt(subtotal);
    document.getElementById('totalDisplay').textContent = fmt(total);
    document.getElementById('checkoutBtn').disabled = items.length === 0;
    document.getElementById('emptyCart').style.display = items.length ? 'none' : 'block';

    document.querySelectorAll('.cart-item').forEach(e => e.remove());
    const scroll = document.getElementById('cartScroll');
    items.forEach(item => {
        const el = document.createElement('div');
        el.className = 'cart-item';
        el.innerHTML = `
            <div class="flex-grow-1">
                <div style="font-size:.78rem;font-weight:600;color:#0f172a">${item.name}</div>
                <div style="font-size:.72rem;color:#6366f1">${fmt(item.price)}</div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <div class="qty-btn" onclick="changeQty(${item.id},-1)"><i class="fa fa-minus"></i></div>
                <span style="font-size:.82rem;font-weight:700;min-width:20px;text-align:center">${item.qty}</span>
                <div class="qty-btn" onclick="changeQty(${item.id},1)"><i class="fa fa-plus"></i></div>
            </div>
            <div style="font-size:.78rem;font-weight:700;color:#10b981;min-width:70px;text-align:right">${fmt(item.price*item.qty)}</div>
            <div class="qty-btn" onclick="removeItem(${item.id})" style="color:#ef4444"><i class="fa fa-times"></i></div>`;
        scroll.insertBefore(el, document.getElementById('emptyCart'));
    });
}

function addToCart(el) {
    const {id, name, price, stock} = el.dataset;
    if (cart[id]) { if (cart[id].qty < +stock) cart[id].qty++; }
    else cart[id] = {id, name, price: +price, qty: 1, stock: +stock};
    updateCart();
}
function changeQty(id, d) { if (cart[id]) { cart[id].qty = Math.max(1, Math.min(cart[id].stock, cart[id].qty + d)); updateCart(); } }
function removeItem(id) { delete cart[id]; updateCart(); }

document.addEventListener('DOMContentLoaded', function() {
    // Setup cart event listeners
    document.querySelectorAll('.product-item:not(.out-of-stock)').forEach(el => el.addEventListener('click', function(e) { 
        addToCart(this);
    }, false));
    
    const clearCartBtn = document.getElementById('clearCart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() { cart = {}; updateCart(); }, false);
    }
    
    const discountInput = document.getElementById('discountInput');
    if (discountInput) {
        discountInput.addEventListener('input', updateCart, false);
    }
    
    // Setup checkout form
    const checkoutForm = document.getElementById('checkoutForm');
    const checkoutModal = document.getElementById('checkoutModal');
    const checkoutBtn = document.getElementById('checkoutBtn');
    
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (Object.keys(cart).length === 0) {
                alert('Keranjang masih kosong');
                return false;
            }
            
            // Populate hidden fields
            const discountValue = getDiscount();
            document.getElementById('discountHidden').value = discountValue;
            
            const itemsContainer = document.getElementById('cartItemsHidden');
            itemsContainer.innerHTML = '';
            Object.values(cart).forEach((item, idx) => {
                const input1 = document.createElement('input');
                input1.type = 'hidden';
                input1.name = `items[${idx}][product_id]`;
                input1.value = item.id;
                
                const input2 = document.createElement('input');
                input2.type = 'hidden';
                input2.name = `items[${idx}][quantity]`;
                input2.value = item.qty;
                
                const input3 = document.createElement('input');
                input3.type = 'hidden';
                input3.name = `items[${idx}][price]`;
                input3.value = item.price;
                
                itemsContainer.appendChild(input1);
                itemsContainer.appendChild(input2);
                itemsContainer.appendChild(input3);
            });
            
            // Show modal
            const modal = new bootstrap.Modal(checkoutModal);
            modal.show();
        }, false);
    }
    
    // Update change on input
    const paidAmountInput = document.getElementById('paidAmount');
    if (paidAmountInput) {
        paidAmountInput.addEventListener('input', function() {
            const total = Math.max(0, Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0) - getDiscount());
            const paid = parseFloat(this.value) || 0;
            const change = Math.max(0, paid - total);
            document.getElementById('changeDisplay').textContent = fmt(change);
        }, false);
    }
});

document.getElementById('productSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(el => {
        el.style.display = (el.dataset.name.toLowerCase().includes(q) || el.dataset.sku.toLowerCase().includes(q)) ? '' : 'none';
    });
});

document.getElementById('checkoutBtn').addEventListener('click', () => {
    const items = Object.values(cart);
    const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
    const total = Math.max(0, subtotal - getDiscount());
    document.getElementById('discountHidden').value = getDiscount();
    document.getElementById('paidAmount').value = total;
    document.getElementById('changeDisplay').textContent = fmt(0);
    const hidden = document.getElementById('cartItemsHidden');
    hidden.innerHTML = '';
    items.forEach((item, i) => {
        hidden.innerHTML += `<input type="hidden" name="items[${i}][product_id]" value="${item.id}">
            <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">
            <input type="hidden" name="items[${i}][sell_price]" value="${item.price}">
            <input type="hidden" name="items[${i}][discount]" value="0">`;
    });
    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
});

document.getElementById('paidAmount').addEventListener('input', function() {
    const items = Object.values(cart);
    const total = Math.max(0, items.reduce((s,i)=>s+i.price*i.qty,0) - getDiscount());
    document.getElementById('changeDisplay').textContent = fmt(Math.max(0, +this.value - total));
});

// Style btn-check labels
document.querySelectorAll('.btn-check').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('.btn-check').forEach(x => {
            x.nextElementSibling.style.borderColor = x.checked ? '#6366f1' : '#e2e8f0';
            x.nextElementSibling.style.background = x.checked ? '#ede9fe' : '#fff';
            x.nextElementSibling.style.color = x.checked ? '#6366f1' : '#374151';
        });
    });
});
document.getElementById('pm_cash').dispatchEvent(new Event('change'));
</script>
@endpush
