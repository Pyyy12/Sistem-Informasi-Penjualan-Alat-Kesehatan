
@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard Overview')

@section('content')
<div class="page-heading">
    <div>
        <h1>Selamat datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="muted mb-0">Ringkasan bisnis alat kesehatan kamu hari ini.</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-primary px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Transaksi Baru
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="muted">Omzet Hari Ini</span>
                <div class="stat-icon" style="background:#e0f7f4;color:#087f8c">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-number">
                Rp{{ number_format($totalOmzet, 0, ',', '.') }}
            </div>
            <small class="muted">Transaksi lunas hari ini</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="muted">Transaksi Hari Ini</span>
                <div class="stat-icon" style="background:#e7edff;color:#526ce0">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <div class="stat-number">{{ number_format($totalTransaksi) }}</div>
            <small class="muted">Semua status pembayaran</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="muted">Total Produk</span>
                <div class="stat-icon" style="background:#fff1d9;color:#d99015">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-number">{{ number_format($totalProduk) }}</div>
            <small class="muted">Jenis produk terdaftar</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="muted">Total Pelanggan</span>
                <div class="stat-icon" style="background:#f3e8ff;color:#8b5cc7">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-number">{{ number_format($totalPelanggan) }}</div>
            <small class="muted">Pelanggan terdaftar</small>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="panel h-100">
            <div class="mb-4">
                <h5 class="fw-bold mb-1">Grafik Penjualan</h5>
                <p class="muted small mb-0">Omzet tujuh hari terakhir</p>
            </div>
            <div style="height:310px;position:relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="panel h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Stok Menipis</h5>
                    <small class="muted">Perlu diperiksa</small>
                </div>
                <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
            </div>

            @forelse($stokMenipis as $product)
                <div class="d-flex justify-content-between align-items-center gap-2 py-3 border-bottom">
                    <div class="min-w-0">
                        <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                        <small class="muted">{{ $product->code }}</small>
                    </div>
                    <span class="badge text-bg-warning">
                        {{ $product->stock }} {{ $product->unit }}
                    </span>
                </div>
            @empty
                <div class="text-center py-5 muted">
                    <i class="bi bi-check-circle fs-2 d-block mb-2"></i>
                    Semua stok dalam batas aman.
                </div>
            @endforelse

            <a href="{{ route('products.index') }}"
               class="btn btn-outline-secondary btn-sm w-100 mt-3">
                Lihat Semua Produk
            </a>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <div class="panel">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Transaksi Terbaru</h5>
                    <small class="muted">Riwayat transaksi terakhir</small>
                </div>
                <a href="{{ route('sales.index') }}"
                   class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Invoice</th><th>Pelanggan</th><th>Total</th><th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($penjualanTerbaru as $sale)
                        <tr>
                            <td>
                                <a class="fw-semibold" href="{{ route('sales.show', $sale) }}">
                                    {{ $sale->invoice_number }}
                                </a>
                                <div class="small muted">
                                    {{ $sale->sale_date->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td>{{ $sale->customer?->name ?? 'Umum' }}</td>
                            <td class="text-nowrap">
                                Rp{{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge {{ $sale->payment_status === 'lunas' ? 'text-bg-success' : 'text-bg-warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 muted">
                            Belum ada transaksi.
                        </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="panel">
            <h5 class="fw-bold mb-1">Produk Terbaru</h5>
            <small class="muted">Katalog alat kesehatan</small>

            @forelse($produkTerbaru as $product)
                <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                    <div class="stat-icon" style="background:#e0f7f4;color:#087f8c">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                        <small class="muted">{{ $product->category->name }}</small>
                    </div>
                    <div class="fw-bold text-nowrap small">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <p class="muted text-center py-4">Belum ada produk.</p>
            @endforelse

            <a href="{{ route('products.create') }}"
               class="btn btn-primary w-100 mt-3">
                <i class="bi bi-plus-lg me-2"></i> Tambah Produk
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Omzet',
            data: @json($dataOmzet),
            borderColor: '#087f8c',
            backgroundColor: 'rgba(8,127,140,.12)',
            fill: true,
            tension: .38,
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: context => ' Rp' +
                        new Intl.NumberFormat('id-ID').format(context.parsed.y)
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp' +
                        new Intl.NumberFormat('id-ID', {
                            notation: 'compact'
                        }).format(value)
                },
                grid: { color: '#edf1f5' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
