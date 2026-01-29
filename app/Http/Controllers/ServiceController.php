<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\VehicleType;
use App\Models\ServicePrice;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Aplicamos middleware para proteger todo este controlador
     * Solo los usuarios autenticados con rol "admin" podrán usarlo.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $services = Service::with('prices')->orderBy('name')->get();
        $vehicleTypes = VehicleType::orderBy('name')->get();
        return view('services.index', compact('services', 'vehicleTypes'));
    }

    public function create()
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
        return view('services.create', compact('vehicleTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'prices_vehicles' => 'nullable|array',
            'prices_vehicles.*' => 'nullable|numeric|min:0',
            'prices_generic' => 'nullable|array',
            'prices_generic.*.label' => 'required_with:prices_generic.*.price|string|max:255',
            'prices_generic.*.price' => 'required_with:prices_generic.*.label|numeric|min:0',
        ]);

        $vehiclePrices = collect($request->input('prices_vehicles', []))
            ->filter(fn ($price) => $price !== null && $price !== '');
        $genericPrices = collect($request->input('prices_generic', []))
            ->filter(fn ($row) => filled($row['label'] ?? '') || filled($row['price'] ?? ''))
            ->values();

        if ($vehiclePrices->isEmpty() && $genericPrices->isEmpty()) {
            return back()->withErrors(['prices_vehicles' => 'Debes ingresar al menos un precio.'])->withInput();
        }

        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->boolean('active')
        ]);

        foreach ($vehiclePrices as $vehicleTypeId => $price) {
            ServicePrice::create([
                'service_id' => $service->id,
                'vehicle_type_id' => $vehicleTypeId,
                'price' => $price
            ]);
        }

        foreach ($genericPrices as $row) {
            ServicePrice::create([
                'service_id' => $service->id,
                'label' => $row['label'],
                'price' => $row['price'],
            ]);
        }

        return redirect()->route('services.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    public function edit(Service $service)
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
        $vehiclePrices = $service->prices()
            ->whereNotNull('vehicle_type_id')
            ->pluck('price', 'vehicle_type_id');
        $genericPrices = $service->prices()
            ->whereNull('vehicle_type_id')
            ->get(['label', 'price'])
            ->map(fn ($price) => ['label' => $price->label, 'price' => $price->price])
            ->values();

        return view('services.edit', compact('service', 'vehicleTypes', 'vehiclePrices', 'genericPrices'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'description' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'prices_vehicles' => 'nullable|array',
            'prices_vehicles.*' => 'nullable|numeric|min:0',
            'prices_generic' => 'nullable|array',
            'prices_generic.*.label' => 'required_with:prices_generic.*.price|string|max:255',
            'prices_generic.*.price' => 'required_with:prices_generic.*.label|numeric|min:0',
        ]);

        $vehiclePrices = collect($request->input('prices_vehicles', []))
            ->filter(fn ($price) => $price !== null && $price !== '');
        $genericPrices = collect($request->input('prices_generic', []))
            ->filter(fn ($row) => filled($row['label'] ?? '') || filled($row['price'] ?? ''))
            ->values();

        if ($vehiclePrices->isEmpty() && $genericPrices->isEmpty()) {
            return back()->withErrors(['prices_vehicles' => 'Debes ingresar al menos un precio.'])->withInput();
        }

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->boolean('active')
        ]);

        $service->prices()->delete();

        foreach ($vehiclePrices as $vehicleTypeId => $price) {
            ServicePrice::create([
                'service_id' => $service->id,
                'vehicle_type_id' => $vehicleTypeId,
                'price' => $price,
            ]);
        }

        foreach ($genericPrices as $row) {
            ServicePrice::create([
                'service_id' => $service->id,
                'label' => $row['label'],
                'price' => $row['price'],
            ]);
        }

        return redirect()->route('services.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}
