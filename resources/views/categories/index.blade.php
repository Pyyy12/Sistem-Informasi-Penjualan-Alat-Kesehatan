@extends('layouts.app')
@section('title', 'Kategori')
@section('heading', 'Kategori Produk')

@section('content')
<div class="page-heading">
    <div>
        <h1>Kategori Alat Kesehatan</h1>
        <p class="muted">Kelola kelompok produk.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>No.</th><th>Nama</th><th>Deskripsi</th>
                <th>Jumlah Produk</th><th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $categories->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $category->name }}</td>
                    <td>{{ $category->description ?: '-' }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus kategori ini?')">
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
                <tr><td colspan="5" class="text-center py-4">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection