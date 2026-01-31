<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $customers = Customer::withCount('tickets')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('cedula', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        return view('customers.index', [
            'customers' => $customers,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cedula' => ['nullable', 'string', 'max:50', 'unique:customers,cedula'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Request $request, Customer $customer)
    {
        [$start, $end, $timeframe] = $this->resolveTimeframe($request->input('timeframe', '6m'));

        $customer->load(['tickets' => function ($query) {
            $query->latest();
        }]);

        $ticketsInRange = $customer->tickets()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as total')
            ->groupBy('visit_date')
            ->pluck('total', 'visit_date');

        $billedTotal = $customer->tickets()
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_amount');

        $period = CarbonPeriod::create($start, '1 day', $end);
        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $label = $date->format('Y-m-d');
            $labels[] = $label;
            $data[] = (int) ($ticketsInRange[$label] ?? 0);
        }
        $visitsTotal = $ticketsInRange->sum();

        return view('customers.show', [
            'customer' => $customer,
            'chartLabels' => $labels,
            'chartData' => $data,
            'timeframe' => $timeframe,
            'billedTotal' => $billedTotal,
            'visitsTotal' => $visitsTotal,
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cedula' => ['nullable', 'string', 'max:50', 'unique:customers,cedula,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }

    public function lookup(Request $request)
    {
        $data = $request->validate([
            'cedula' => ['required', 'string', 'max:50'],
        ]);

        $customer = Customer::where('cedula', $data['cedula'])->first();

        if (!$customer) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'customer' => [
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ],
        ]);
    }

    private function resolveTimeframe(string $timeframe): array
    {
        $end = Carbon::now()->endOfDay();
        $start = match ($timeframe) {
            '1m' => Carbon::now()->subMonth()->startOfDay(),
            '3m' => Carbon::now()->subMonths(3)->startOfDay(),
            '1y' => Carbon::now()->subYear()->startOfDay(),
            '2y' => Carbon::now()->subYears(2)->startOfDay(),
            '5y' => Carbon::now()->subYears(5)->startOfDay(),
            default => Carbon::now()->subMonths(6)->startOfDay(),
        };

        return [$start, $end, $timeframe ?: '6m'];
    }
}
