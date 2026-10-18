@extends('layouts.app')
@section('title', 'Laporan')
@section('heading', 'Laporan Penjualan')

@section('content')
<div class="page-heading no-print">
    <div>
        <h1>Laporan Penjualan</h1>
        <p class="muted">Ringkasan transaksi lunas berdasarkan periode.</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
</div>

<div class="panel mb-4 no-print">
    <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-5">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control"
                   value="{{ $start }}" required>
        </div>
        <div class="col-12 col-md-5">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control"
                   value="{{ $end }}" required>
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<p class="muted">
    Periode {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
    – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
</p>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="stat-card">
            <div class="muted">Total Omzet Lunas</div>
            <div class="stat-number" style="color:#087f8c">
                Rp{{ number_format($omzet, 0, ',', '.') }}
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="stat-card">
            <div class="muted">Jumlah Transaksi Lunas</div>
            <div class="stat-number">{{ number_format($jumlahTransaksi) }}</div>
        </div>
    </div>
</div>

<div class="panel mb-4">
    <h5 class="fw-bold mb-3">Daftar Transaksi</h5>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr><th>No.</th><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th></tr>
            </thead>
            <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sales->firstItem() + $loop->index }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->customer?->name ?? 'Umum' }}</td>
                    <td class="text-nowrap">Rp{{ number_format($sale->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total Omzet</th>
                <th class="text-nowrap">Rp{{ number_format($omzet, 0, ',', '.') }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="mt-3 no-print">{{ $sales->links() }}</div>
</div>

<div class="panel">
    <h5 class="fw-bold mb-3">Produk Terlaris</h5>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr><th>Produk</th><th>Unit Terjual</th><th>Pendapatan</th></tr>
            </thead>
            <tbody>
            @forelse($produkTerlaris as $item)
                <tr>
                    <td>{{ $item->product?->name ?? 'Produk tidak tersedia' }}</td>
                    <td>{{ number_format($item->total_terjual) }}</td>
                    <td>Rp{{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center py-4">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection