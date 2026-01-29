<?php

namespace App\Http\Controllers;

use App\Models\ServicePrice;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_types,name',
        ]);

        VehicleType::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Tipo de vehículo agregado correctamente.');
    }

    public function destroy(VehicleType $vehicleType)
    {
        $hasPrices = ServicePrice::where('vehicle_type_id', $vehicleType->id)->exists();

        if ($hasPrices) {
            return back()->with('error', 'No puedes eliminar este tipo porque tiene precios de servicio asociados.');
        }

        $vehicleType->delete();

        return back()->with('success', 'Tipo de vehículo eliminado correctamente.');
    }
}
