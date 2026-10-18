<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $totalOmzet = Sale::whereDate('sale_date', $today)
            ->where('payment_status', 'lunas')
            ->sum('total');

        $totalTransaksi = Sale::whereDate('sale_date', $today)->count();

        $totalProduk = Product::count();
        $totalPelanggan = Customer::count();
        $totalKategori = Category::count();

        $stokMenipis = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->limit(6)
            ->get();

        $produkTerbaru = Product::with('category')
            ->latest()
            ->limit(5)
            ->get();

        $penjualanTerbaru = Sale::with(['customer', 'user'])
            ->latest('sale_date')
            ->limit(7)
            ->get();

        $labels = [];
        $dataOmzet = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $labels[] = $date->translatedFormat('d M');

            $dataOmzet[] = (float) Sale::whereDate('sale_date', $date)
                ->where('payment_status', 'lunas')
                ->sum('total');
        }

        $produkTerlaris = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'sale_items.product_id',
                DB::raw('SUM(sale_items.quantity) as total_terjual'),
                DB::raw('SUM(sale_items.subtotal) as total_pendapatan')
            )
            ->where('sales.payment_status', 'lunas')
            ->whereBetween('sales.sale_date', [
                now()->subDays(29)->startOfDay(),
                now()->endOfDay(),
            ])
            ->groupBy('sale_items.product_id')
            ->orderByDesc('total_terjual')
            ->with('product')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalOmzet',
            'totalTransaksi',
            'totalProduk',
            'totalPelanggan',
            'totalKategori',
            'stokMenipis',
            'produkTerbaru',
            'penjualanTerbaru',
            'labels',
            'dataOmzet',
            'produkTerlaris'
        ));
    }
}