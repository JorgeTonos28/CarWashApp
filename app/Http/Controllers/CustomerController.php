<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function index()
    {
        $customers = Customer::withCount('tickets')
            ->orderBy('name')
            ->get();

        return view('customers.index', [
            'customers' => $customers,
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

    public function show(Customer $customer)
    {
        $customer->load(['tickets' => function ($query) {
            $query->latest();
        }]);

        return view('customers.show', [
            'customer' => $customer,
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
}
