<?php

namespace App\Http\Controllers;

use App\Models\PettyCashExpense;
use Illuminate\Http\Request;

class PettyCashExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function index(Request $request)
    {
        $expenses = PettyCashExpense::query()
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->latest()
            ->get();

        if ($request->ajax()) {
            return view('petty_cash.partials.table', compact('expenses'));
        }

        return view('petty_cash.index', compact('expenses'));
    }

    public function create()
    {
        return view('petty_cash.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        PettyCashExpense::create([
            'user_id' => auth()->id(),
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        return redirect()->route('petty-cash.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function destroy(PettyCashExpense $pettyCash)
    {
        $pettyCash->delete();
        return redirect()->route('petty-cash.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }
}
