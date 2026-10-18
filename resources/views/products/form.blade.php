<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label">Nama Produk *</label>
        <input name="name" class="form-control" required
               value="{{ old('name', $product->name) }}">
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Kode Produk *</label>
        <input name="code" class="form-control" required
               value="{{ old('code', $product->code) }}">
        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Kategori *</label>
        <select name="category_id" class="form-select" required>
            <option value="">Pilih kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $product->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Harga Jual (Rp) *</label>
        <input type="number" name="price" class="form-control"
               min="0" step="0.01" required
               value="{{ old('price', $product->price) }}">
        @error('price') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Stok *</label>
        <input type="number" name="stock" class="form-control" min="0" required
               value="{{ old('stock', $product->stock ?? 0) }}">
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Minimum Stok *</label>
        <input type="number" name="minimum_stock" class="form-control" min="0" required
               value="{{ old('minimum_stock', $product->minimum_stock ?? 5) }}">
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Satuan *</label>
        <select name="unit" class="form-select" required>
            @foreach(['pcs','box','unit','set','pack','botol','pasang'] as $unit)
                <option value="{{ $unit }}"
                    @selected(old('unit', $product->unit ?? 'pcs') === $unit)>
                    {{ ucfirst($unit) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Tanggal Kedaluwarsa</label>
        <input type="date" name="expiry_date" class="form-control"
               value="{{ old('expiry_date', $product->expiry_date?->format('Y-m-d')) }}">
    </div>

    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Produk</button>
    <a href="{{ route('products.index') }}" class="btn btn-light">Batal</a>
</div>