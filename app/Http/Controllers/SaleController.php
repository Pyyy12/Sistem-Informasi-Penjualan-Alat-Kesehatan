<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['customer', 'user'])
            ->latest('sale_date')
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();

        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'payment_method' => ['required', 'in:tunai,transfer,qris'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);

        try {
            $sale = DB::transaction(function () use ($validated) {
                $items = collect($validated['items']);

                $productIds = $items
                    ->pluck('product_id')
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->sort()
                    ->values();

                // Mengunci produk untuk mencegah dua transaksi
                // menghabiskan stok yang sama secara bersamaan.
                $products = Product::whereIn('id', $productIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($products->count() !== $productIds->count()) {
                    throw ValidationException::withMessages([
                        'items' => 'Salah satu produk tidak ditemukan.',
                    ]);
                }

                $total = 0;
                $preparedItems = [];

                foreach ($items as $item) {
                    $productId = (int) $item['product_id'];
                    $quantity = (int) $item['quantity'];
                    $product = $products->get($productId);

                    if ($product->stock < $quantity) {
                        throw ValidationException::withMessages([
                            'items' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                        ]);
                    }

                    $price = (float) $product->price;
                    $subtotal = round($price * $quantity, 2);

                    $total += $subtotal;

                    $preparedItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                $sale = Sale::create([
                    'invoice_number' => 'INV-' . now()->format('YmdHis')
                        . '-' . Str::upper(Str::random(5)),
                    'customer_id' => $validated['customer_id'] ?? null,
                    'user_id' => auth()->id(),
                    'sale_date' => now(),
                    'total' => round($total, 2),
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'lunas',
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($preparedItems as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product']->id,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['product']->decrement('stock', $item['quantity']);
                }

                return $sale;
            }, 3);

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Transaksi gagal disimpan. Periksa data dan coba kembali.');
        }
    }

    public function show(Sale $sale): View
    {
        $sale->load(['customer', 'user', 'items.product']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale): RedirectResponse
    {
        return redirect()
            ->route('sales.show', $sale)
            ->with('info', 'Transaksi tersimpan tidak dapat diedit melalui form ini.');
    }

    public function update(Request $request, Sale $sale): RedirectResponse
    {
        return redirect()
            ->route('sales.show', $sale)
            ->with('error', 'Pengubahan transaksi belum diaktifkan.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        return back()->with(
            'error',
            'Transaksi tidak dapat dihapus langsung karena memengaruhi audit stok. Gunakan prosedur retur atau pembatalan yang tercatat.'
        );
    }
}