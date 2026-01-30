<?php

namespace App\Http\Controllers;

use App\Models\TicketWash;
use App\Models\Vehicle;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class VehicleRegistryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function index()
    {
        $vehicles = Vehicle::with(['vehicleType', 'ticketWashes.ticket'])
            ->orderBy('plate')
            ->get()
            ->map(function (Vehicle $vehicle) {
                $lastWash = $vehicle->ticketWashes
                    ->filter(fn ($wash) => $wash->ticket)
                    ->sortByDesc(fn ($wash) => $wash->ticket->created_at)
                    ->first();

                $vehicle->last_visit = $lastWash?->ticket?->created_at;
                $vehicle->visits_count = $vehicle->ticketWashes
                    ->filter(fn ($wash) => $wash->ticket)
                    ->unique('ticket_id')
                    ->count();

                return $vehicle;
            });

        return view('vehicle-registry.index', [
            'vehicles' => $vehicles,
        ]);
    }

    public function show(Request $request, Vehicle $vehicle)
    {
        [$start, $end, $timeframe] = $this->resolveTimeframe($request->input('timeframe', '6m'));

        $ticketWashes = TicketWash::with('ticket')
            ->where('vehicle_id', $vehicle->id)
            ->whereHas('ticket')
            ->get();

        $tickets = $ticketWashes
            ->map(fn ($wash) => $wash->ticket)
            ->filter()
            ->unique('id')
            ->sortByDesc('created_at')
            ->values();

        $visitsInRange = TicketWash::where('vehicle_id', $vehicle->id)
            ->whereHas('ticket')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as total')
            ->groupBy('visit_date')
            ->pluck('total', 'visit_date');

        $period = CarbonPeriod::create($start, '1 day', $end);
        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $label = $date->format('Y-m-d');
            $labels[] = $label;
            $data[] = (int) ($visitsInRange[$label] ?? 0);
        }

        return view('vehicle-registry.show', [
            'vehicle' => $vehicle,
            'tickets' => $tickets,
            'chartLabels' => $labels,
            'chartData' => $data,
            'timeframe' => $timeframe,
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
