@extends('layouts.app')
@section('title', 'Produk')
@section('heading', 'Manajemen Produk')

@section('content')
<div class="page-heading">
    <div>
        <h1>Produk Alat Kesehatan</h1>
        <p class="muted">Kelola harga dan persediaan barang.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

<div class="panel">
    <form method="GET" class="row g-2 mb-4">
        <div class="col-12 col-md-5">
            <input name="search" class="form-control"
                   value="{{ request('search') }}"
                   placeholder="Cari nama atau kode produk">
        </div>
        <div class="col-12 col-md-4">
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-1">
            <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{ route('products.index') }}" class="btn btn-light w-100">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Produk</th><th>Kode</th><th>Kategori</th>
                <th>Harga</th><th>Stok</th><th>Kedaluwarsa</th><th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        <div class="small muted">{{ $product->unit }}</div>
                    </td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td class="text-nowrap">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge {{ $product->stock <= $product->minimum_stock ? 'text-bg-warning' : 'text-bg-success' }}">
                            {{ $product->stock }} {{ $product->unit }}
                        </span>
                    </td>
                    <td>{{ $product->expiry_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('products.destroy', $product) }}"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4">Produk tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $products->links() }}</div>
</div>
@endsection