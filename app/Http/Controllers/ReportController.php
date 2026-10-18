<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $start = $validated['start_date']
            ?? now()->startOfMonth()->toDateString();

        $end = $validated['end_date']
            ?? now()->toDateString();

        if ($start > $end) {
            throw ValidationException::withMessages([
                'end_date' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            ]);
        }

        $salesQuery = Sale::with(['customer', 'user'])
            ->where('payment_status', 'lunas')
            ->whereBetween('sale_date', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ]);

        $omzet = (clone $salesQuery)->sum('total');

        $jumlahTransaksi = (clone $salesQuery)->count();

        $sales = $salesQuery
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        $produkTerlaris = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'sale_items.product_id',
                DB::raw('SUM(sale_items.quantity) as total_terjual'),
                DB::raw('SUM(sale_items.subtotal) as total_pendapatan')
            )
            ->where('sales.payment_status', 'lunas')
            ->whereBetween('sales.sale_date', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ])
            ->groupBy('sale_items.product_id')
            ->orderByDesc('total_terjual')
            ->with('product')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'start',
            'end',
            'omzet',
            'jumlahTransaksi',
            'sales',
            'produkTerlaris'
        ));
    }
}