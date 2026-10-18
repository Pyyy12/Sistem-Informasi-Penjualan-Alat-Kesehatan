@extends('layouts.app')
@section('title', 'Pelanggan')
@section('heading', 'Data Pelanggan')

@section('content')
<div class="page-heading">
    <div>
        <h1>Data Pelanggan</h1>
        <p class="muted">Kelola data pelanggan dan institusi kesehatan.</p>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Pelanggan
    </a>
</div>

<div class="panel">
    <form method="GET" class="row g-2 mb-4">
        <div class="col-12 col-md-9">
            <input name="search" class="form-control"
                   value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau telepon">
        </div>
        <div class="col-6 col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>
        <div class="col-6 col-md-1">
            <a href="{{ route('customers.index') }}" class="btn btn-light w-100">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr><th>No.</th><th>Nama</th><th>Telepon</th><th>Email</th><th>Alamat</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customers->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $customer->name }}</td>
                    <td>{{ $customer->phone ?: '-' }}</td>
                    <td>{{ $customer->email ?: '-' }}</td>
                    <td>{{ $customer->address ?: '-' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('customers.destroy', $customer) }}"
                                  onsubmit="return confirm('Hapus pelanggan ini?')">
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
                <tr><td colspan="6" class="text-center py-4">Belum ada pelanggan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $customers->links() }}</div>
</div>
@endsection