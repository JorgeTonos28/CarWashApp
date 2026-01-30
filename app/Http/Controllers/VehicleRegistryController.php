<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class VehicleRegistryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function index()
    {
        $vehicles = Vehicle::with(['customer', 'vehicleType', 'ticketWashes.ticket'])
            ->orderBy('plate')
            ->get()
            ->map(function (Vehicle $vehicle) {
                $lastWash = $vehicle->ticketWashes
                    ->filter(fn ($wash) => $wash->ticket)
                    ->sortByDesc(fn ($wash) => $wash->ticket->created_at)
                    ->first();

                $vehicle->last_visit = $lastWash?->ticket?->created_at;

                return $vehicle;
            });

        return view('vehicle-registry.index', [
            'vehicles' => $vehicles,
        ]);
    }
}
