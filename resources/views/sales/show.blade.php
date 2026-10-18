@extends('layouts.app')
@section('title', 'Invoice')
@section('heading', 'Detail Transaksi')

@section('content')
<div class="page-heading no-print">
    <div>
        <h1>Detail Transaksi</h1>
        <p class="muted">{{ $sale->invoice_number }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('sales.index') }}" class="btn btn-light">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Cetak Invoice
        </button>
    </div>
</div>

<div class="panel" id="invoice">
    <div class="d-flex justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-heart-pulse-fill fs-2" style="color:#087f8c"></i>
                <h3 class="fw-bold mb-0">MedikaStore</h3>
            </div>
            <div class="muted">Penjualan Alat Kesehatan</div>
        </div>
        <div class="text-md-end">
            <h4 class="fw-bold">INVOICE</h4>
            <strong>{{ $sale->invoice_number }}</strong>
            <div class="muted">{{ $sale->sale_date->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <hr>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="muted small">Pelanggan</div>
            <strong>{{ $sale->customer?->name ?? 'Pelanggan Umum' }}</strong>
            @if($sale->customer?->phone)
                <div>{{ $sale->customer->phone }}</div>
            @endif
            @if($sale->customer?->address)
                <div>{{ $sale->customer->address }}</div>
            @endif
        </div>
        <div class="col-12 col-md-6">
            <div class="muted small">Pembayaran</div>
            <div>Metode: <strong>{{ strtoupper($sale->payment_method) }}</strong></div>
            <div>Status: <strong>{{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}</strong></div>
            <div>Kasir: {{ $sale->user->name }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr><th>No.</th><th>Produk</th><th>Harga</th><th>Jumlah</th><th class="text-end">Subtotal</th></tr>
            </thead>
            <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $item->product->name }}</strong>
                        <div class="small muted">{{ $item->product->code }}</div>
                    </td>
                    <td class="text-nowrap">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-end text-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" class="text-end">TOTAL</th>
                <th class="text-end text-nowrap fs-5" style="color:#087f8c">
                    Rp{{ number_format($sale->total, 0, ',', '.') }}
                </th>
            </tr>
            </tfoot>
        </table>
    </div>

    @if($sale->notes)
        <div class="mt-4"><strong>Catatan:</strong> {{ $sale->notes }}</div>
    @endif

    <div class="text-center mt-5 pt-3 border-top">
        <strong>Terima kasih telah berbelanja!</strong>
        <div class="muted small">Simpan invoice ini sebagai bukti transaksi.</div>
    </div>
</div>
@endsection