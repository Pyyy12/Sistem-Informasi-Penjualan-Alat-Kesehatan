@extends('layouts.app')
@section('title', 'Penjualan')
@section('heading', 'Transaksi Penjualan')

@section('content')
<div class="page-heading">
    <div>
        <h1>Riwayat Penjualan</h1>
        <p class="muted">Daftar seluruh invoice penjualan.</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
    </a>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Metode</th><th>Total</th><th>Status</th><th>Detail</th></tr>
            </thead>
            <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td class="fw-semibold">{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->customer?->name ?? 'Umum' }}</td>
                    <td>{{ strtoupper($sale->payment_method) }}</td>
                    <td class="text-nowrap">Rp{{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $sale->payment_status === 'lunas' ? 'text-bg-success' : 'text-bg-warning' }}">
                            {{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $sales->links() }}</div>
</div>
@endsection