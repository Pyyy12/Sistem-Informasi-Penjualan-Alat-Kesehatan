@extends('layouts.app')
@section('title', 'Transaksi Baru')
@section('heading', 'Transaksi Baru')

@section('content')
<div class="page-heading">
    <div>
        <h1>Buat Transaksi</h1>
        <p class="muted">Pilih produk dan jumlah barang yang dibeli.</p>
    </div>
    <a href="{{ route('sales.index') }}" class="btn btn-light">Kembali</a>
</div>

<form action="{{ route('sales.store') }}" method="POST" id="saleForm">
    @csrf

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="panel mb-3">
                <h5 class="fw-bold mb-3">Informasi Transaksi</h5>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pelanggan</label>
                        <select name="customer_id" class="form-select">
                            <option value="">Pelanggan Umum</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    @selected(old('customer_id') == $customer->id)>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Daftar Produk</h5>
                    <button type="button" id="addItem" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Item
                    </button>
                </div>

                <div id="itemsContainer"></div>

                <template id="productOptions">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}">
                            {{ $product->code }} — {{ $product->name }}
                            (Stok: {{ $product->stock }} {{ $product->unit }})
                        </option>
                    @endforeach
                </template>

                @if($products->isEmpty())
                    <div class="alert alert-warning">
                        Belum ada produk dengan stok tersedia. Tambahkan stok produk terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="panel" style="position:sticky;top:95px">
                <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>

                <div class="d-flex justify-content-between mb-3">
                    <span class="muted">Jenis produk</span>
                    <strong id="itemCount">0</strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="muted">Jumlah barang</span>
                    <strong id="totalQuantity">0</strong>
                </div>

                <hr>

                <small class="muted">Total pembayaran</small>
                <div id="grandTotal" class="fw-bold my-3"
                     style="font-size:30px;color:#087f8c">Rp0</div>

                <button type="submit" class="btn btn-primary w-100 py-3"
                    {{ $products->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-check-circle me-2"></i> Simpan Transaksi
                </button>

                <a href="{{ route('sales.index') }}" class="btn btn-light w-100 mt-2">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
(() => {
    const container = document.getElementById('itemsContainer');
    const optionsHTML = document.getElementById('productOptions').innerHTML;
    const money = n => 'Rp' + new Intl.NumberFormat('id-ID').format(n);
    let counter = 0;

    function updateTotals() {
        let total = 0, quantity = 0, count = 0;

        container.querySelectorAll('.sale-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const qty = row.querySelector('.quantity-input');
            const selected = select.selectedOptions[0];
            const price = Number(selected?.dataset.price || 0);
            const stock = Number(selected?.dataset.stock || 0);
            const amount = Number(qty.value || 0);

            qty.max = stock;
            row.querySelector('.subtotal').textContent = money(price * amount);

            total += price * amount;
            quantity += amount;
            if (select.value) count++;
        });

        document.getElementById('grandTotal').textContent = money(total);
        document.getElementById('itemCount').textContent = count;
        document.getElementById('totalQuantity').textContent = quantity;
    }

    function addRow(productId = '', amount = 1) {
        const row = document.createElement('div');
        const index = counter++;

        row.className = 'sale-row border rounded-3 p-3 mb-3';
        row.innerHTML = `
            <div class="d-flex justify-content-between mb-3">
                <strong class="item-number">Item</strong>
                <button type="button" class="btn btn-sm btn-outline-danger remove">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
            <div class="mb-3">
                <label class="form-label">Produk *</label>
                <select name="items[${index}][product_id]"
                        class="form-select product-select" required>
                    <option value="">Pilih produk</option>
                    ${optionsHTML}
                </select>
            </div>
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label">Jumlah *</label>
                    <input name="items[${index}][quantity]" type="number"
                           class="form-control quantity-input"
                           value="${amount}" min="1" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Subtotal</label>
                    <div class="form-control bg-light subtotal">Rp0</div>
                </div>
            </div>`;

        container.appendChild(row);

        const select = row.querySelector('.product-select');
        select.value = productId;
        select.addEventListener('change', updateTotals);
        row.querySelector('.quantity-input').addEventListener('input', updateTotals);

        row.querySelector('.remove').addEventListener('click', () => {
            row.remove();
            renumber();
            updateTotals();
        });

        renumber();
        updateTotals();
    }

    function renumber() {
        container.querySelectorAll('.sale-row').forEach((row, index) => {
            row.querySelector('.item-number').textContent = `Item ${index + 1}`;
        });
    }

    document.getElementById('addItem').addEventListener('click', () => addRow());

    document.getElementById('saleForm').addEventListener('submit', event => {
        const rows = [...container.querySelectorAll('.sale-row')];
        const ids = rows.map(row => row.querySelector('.product-select').value);

        if (!rows.length || ids.some(id => !id) ||
            ids.some((id, i) => ids.indexOf(id) !== i)) {
            event.preventDefault();
            alert('Tambahkan produk dan pastikan tidak ada produk duplikat.');
            return;
        }

        const invalid = rows.some(row => {
            const select = row.querySelector('.product-select');
            const qty = Number(row.querySelector('.quantity-input').value);
            const stock = Number(select.selectedOptions[0].dataset.stock);
            return !Number.isInteger(qty) || qty < 1 || qty > stock;
        });

        if (invalid) {
            event.preventDefault();
            alert('Jumlah barang tidak valid atau melebihi stok.');
        }
    });

    @if(old('items'))
        @foreach(old('items') as $item)
            addRow(@json($item['product_id'] ?? ''), @json($item['quantity'] ?? 1));
        @endforeach
    @else
        @if($products->isNotEmpty())
            addRow();
        @endif
    @endif
})();
</script>
@endpush