<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function index(Request $request)
    {
        $movements = InventoryMovement::with(['product', 'user'])
            ->when($request->product_name, function ($query) use ($request) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->product_name . '%');
                });
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax()) {
            return view('inventory.partials.table', compact('movements'));
        }

        return view('inventory.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        InventoryMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'movement_type' => 'entrada',
            'quantity' => $request->quantity,
        ]);
        $product->increment('stock', $request->quantity);

        return redirect()->route('inventory.index')
            ->with('success', 'Entrada registrada correctamente.');
    }
}
