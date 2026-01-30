<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\Ticket;
use App\Models\TicketWash;
use App\Models\PettyCashExpense;
use App\Models\PettyCashSetting;
use App\Models\WasherPayment;
use App\Models\BankAccount;
use App\Models\Washer;
use App\Models\WasherMovement;
use App\Models\Product;
use App\Models\TicketPayment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $request->validate([
            'start' => ['nullable', 'date', 'before_or_equal:end'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
        ]);
        $start = $request->input('start', now()->toDateString());
        $end = $request->input('end', now()->toDateString());

        $ticketQuery = Ticket::with('details')
            ->where('canceled', false)
            ->where('pending', false)
            ->whereDate('paid_at', '>=', $start)
            ->whereDate('paid_at', '<=', $end);

        $tickets = $ticketQuery->get();

        $serviceTotal = 0;
        $productTotal = 0;
        $drinkTotal = 0;
        $genericServiceTotal = 0;

        foreach ($tickets as $ticket) {
            foreach ($ticket->details as $detail) {
                $subtotal = $detail->subtotal;
                switch ($detail->type) {
                    case 'service':
                        $serviceTotal += $subtotal;
                        break;
                    case 'product':
                        $productTotal += $subtotal;
                        break;
                    case 'drink':
                        $drinkTotal += $subtotal;
                        break;
                    case 'generic_service':
                        $genericServiceTotal += $subtotal;
                        break;
                }
            }
        }

        $paymentQuery = TicketPayment::whereHas('ticket', function ($q) use ($start, $end) {
            $q->where('canceled', false)
                ->where('pending', false)
                ->whereDate('paid_at', '>=', $start)
                ->whereDate('paid_at', '<=', $end);
        });

        $cashPayments = (clone $paymentQuery)
            ->where('payment_method', 'efectivo')
            ->sum('amount');

        $cardPayments = (clone $paymentQuery)
            ->where('payment_method', 'tarjeta')
            ->sum('amount');

        $transferTotal = (clone $paymentQuery)
            ->where('payment_method', 'transferencia')
            ->sum('amount');

        $pettyCashAmount = PettyCashSetting::amountForDate($start);
        $pettyCashExpenses = PettyCashExpense::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->latest()
            ->get();
        $pettyCashTotal = $pettyCashExpenses->sum('amount');

        $totalFacturado = $cashPayments + $cardPayments + $transferTotal + $pettyCashAmount - $pettyCashTotal;
        $invoicedTotal = $cashPayments + $cardPayments + $transferTotal;

        $washerPayments = WasherPayment::whereDate('payment_date', '>=', $start)
            ->whereDate('payment_date', '<=', $end)
            ->sum('amount_paid');

        $cashTotal = $cashPayments - $washerPayments - $pettyCashTotal;

        $bankAccountTotals = TicketPayment::selectRaw('bank_account_id, SUM(amount) as total')
            ->with('bankAccount')
            ->where('payment_method', 'transferencia')
            ->whereHas('ticket', function ($q) use ($start, $end) {
                $q->where('canceled', false)
                    ->where('pending', false)
                    ->whereDate('paid_at', '>=', $start)
                    ->whereDate('paid_at', '<=', $end);
            })
            ->groupBy('bank_account_id')
            ->get();

        $tipTotal = TicketWash::whereHas('ticket', function($q) use ($start, $end) {
                $q->where('canceled', false)
                  ->where('pending', false)
                  ->whereDate('paid_at', '>=', $start)
                  ->whereDate('paid_at', '<=', $end);
            })
            ->sum('tip');

        $extraCommission = TicketWash::whereHas('ticket', function($q) use ($start, $end) {
                $q->where('canceled', true)
                  ->where('keep_commission_on_cancel', true)
                  ->where('pending', false)
                  ->whereDate('paid_at', '>=', $start)
                  ->whereDate('paid_at', '<=', $end);
            })
            ->whereNotNull('washer_id')
            ->sum('commission_amount');

        $extraTip = TicketWash::whereHas('ticket', function($q) use ($start, $end) {
                $q->where('canceled', true)
                  ->where('keep_tip_on_cancel', true)
                  ->where('pending', false)
                  ->whereDate('paid_at', '>=', $start)
                  ->whereDate('paid_at', '<=', $end);
            })
            ->sum('tip');

        $paidCommissionTotal = TicketWash::whereHas('ticket', function($q) use ($start, $end) {
                $q->where('canceled', false)
                  ->where('pending', false)
                  ->whereDate('paid_at', '>=', $start)
                  ->whereDate('paid_at', '<=', $end);
            })
            ->sum('commission_amount');

        $washerPayTotal = $paidCommissionTotal + $tipTotal + $extraCommission + $extraTip;

        $pendingTickets = Ticket::with('details')
            ->where('canceled', false)
            ->where('pending', true)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        $accountsReceivable = $pendingTickets->sum('total_amount');

        $washerDebts = WasherMovement::with('washer')
            ->where('amount', '<', 0)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();
        $accountsReceivable += $washerDebts->sum(fn($m) => abs($m->amount));

        $unassignedCommission = Ticket::where('canceled', false)
            ->where('washer_pending_amount', '>', 0)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->sum('washer_pending_amount');

        $assignedPendingCommission = TicketWash::whereHas('ticket', function($q) use ($start, $end) {
                $q->where('pending', true)
                  ->where('canceled', false)
                  ->whereDate('created_at', '>=', $start)
                  ->whereDate('created_at', '<=', $end);
            })
            ->whereNotNull('washer_id')
            ->selectRaw('SUM(commission_amount + tip) as total')
            ->value('total') ?? 0;

        $lastExpenses = $pettyCashExpenses->take(5);

        [$vehicleLabels, $vehicleData] = $this->buildVehicleChart($start, $end);

        $washStatuses = [TicketWash::STATUS_PENDING, TicketWash::STATUS_READY];
        $ticketWashes = TicketWash::with(['vehicle', 'ticket.customer', 'washer'])
            ->whereIn('status', $washStatuses)
            ->whereNotNull('vehicle_id')
            ->whereHas('ticket', function ($q) use ($start, $end) {
                $q->where('canceled', false)
                    ->whereDate('created_at', '>=', $start)
                    ->whereDate('created_at', '<=', $end);
            })
            ->orderBy('created_at')
            ->get();

        $movements = [];
        foreach ($tickets as $t) {
            $movements[] = [
                'description' => 'Ticket '.$t->id,
                'date' => $t->paid_at->format('d/m/Y h:i A'),
                'amount' => $t->total_amount,
            ];
        }
        foreach ($pettyCashExpenses as $e) {
            $movements[] = [
                'description' => 'Gasto: '.$e->description,
                'date' => $e->created_at->format('d/m/Y h:i A'),
                'amount' => -$e->amount,
            ];
        }
        foreach (WasherPayment::whereDate('payment_date', '>=', $start)->whereDate('payment_date', '<=', $end)->get() as $p) {
            $movements[] = [
                'description' => 'Pago Lavador '.$p->washer->name,
                'date' => $p->payment_date->format('d/m/Y h:i A'),
                'amount' => -$p->amount_paid,
            ];
        }
        usort($movements, fn($a,$b)=>strcmp($b['date'],$a['date']));

        $washerPayDue = 0;
        foreach (Washer::all() as $w) {
            $wq = $w->ticketWashes()
                ->whereHas('ticket', function($q) use ($start, $end) {
                    $q->whereDate('created_at', '>=', $start)
                        ->whereDate('created_at', '<=', $end);
                })
                ->where(function($q) {
                    $q->whereHas('ticket', fn($t) => $t->where('canceled', false))
                        ->orWhere(function($sub) {
                            $sub->whereHas('ticket', fn($t) => $t->where('canceled', true))
                                ->where(function($cond) {
                                    $cond->whereHas('ticket', fn($t) => $t->where('keep_commission_on_cancel', true))
                                        ->orWhere('washer_paid', true);
                                });
                        });
                });
            $ticketsTotal = $wq->sum('commission_amount');

            $mq = $w->movements();
            $mq->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
            $movementTotal = $mq->sum('amount');

            $pq = $w->payments();
            $pq->whereDate('payment_date', '>=', $start)->whereDate('payment_date', '<=', $end);
            $paymentTotal = $pq->sum('amount_paid');

            $washerPayDue += $ticketsTotal + $movementTotal - $paymentTotal;
        }
        $washerPayDue += $unassignedCommission;

        $washerDebtAmount = $washerDebts->sum(fn($m) => abs($m->amount));

        $grossProfit = $totalFacturado - $pettyCashAmount - $washerPayTotal;
        $grossProfit -= $washerDebtAmount + $assignedPendingCommission;

        if ($request->ajax()) {
            return view('dashboard.partials.summary', compact(
                'totalFacturado',
                'invoicedTotal',
                'cashTotal',
                'cardPayments',
                'transferTotal',
                'bankAccountTotals',
                'washerPayDue',
                'serviceTotal',
                'productTotal',
                'drinkTotal',
                'genericServiceTotal',
                'grossProfit',
                'pettyCashTotal',
                'lastExpenses',
                'movements',
                'accountsReceivable',
                'pendingTickets',
                'washerDebts',
                'pettyCashAmount',
                'vehicleLabels',
                'vehicleData',
                'ticketWashes'
            ));
        }

        $defaultThreshold = AppSetting::defaultLowStock();
        $lowStockProducts = Product::lowStock($defaultThreshold)->orderBy('name')->get();

        return view('dashboard', [
            'filters' => ['start' => $start, 'end' => $end],
            'totalFacturado' => $totalFacturado,
            'invoicedTotal' => $invoicedTotal,
            'cashTotal' => $cashTotal,
            'cardPayments' => $cardPayments,
            'transferTotal' => $transferTotal,
            'bankAccountTotals' => $bankAccountTotals,
            'washerPayDue' => $washerPayDue,
            'serviceTotal' => $serviceTotal,
            'productTotal' => $productTotal,
            'drinkTotal' => $drinkTotal,
            'genericServiceTotal' => $genericServiceTotal,
            'grossProfit' => $grossProfit,
            'lastExpenses' => $lastExpenses,
            'movements' => $movements,
            'pettyCashTotal' => $pettyCashTotal,
            'accountsReceivable' => $accountsReceivable,
            'pendingTickets' => $pendingTickets,
            'washerDebts' => $washerDebts,
            'pettyCashAmount' => $pettyCashAmount,
            'lowStockProducts' => $lowStockProducts,
            'vehicleChartLabels' => $vehicleLabels,
            'vehicleChartData' => $vehicleData,
            'ticketWashes' => $ticketWashes,
        ]);
    }

    private function buildVehicleChart(string $start, string $end): array
    {
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->endOfDay();

        $diffDays = $startDate->diffInDays($endDate);
        $labels = [];
        $data = [];

        if ($diffDays === 0) {
            $endHour = Carbon::now()->min($endDate)->hour;
            $counts = TicketWash::whereHas('ticket', fn ($q) => $q->where('canceled', false))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('HOUR(created_at) as visit_hour, COUNT(*) as total')
                ->groupBy('visit_hour')
                ->pluck('total', 'visit_hour');

            for ($hour = 0; $hour <= $endHour; $hour++) {
                $labels[] = str_pad((string) $hour, 2, '0', STR_PAD_LEFT).':00';
                $data[] = (int) ($counts[$hour] ?? 0);
            }

            return [$labels, $data];
        }

        if ($diffDays <= 31) {
            $counts = TicketWash::whereHas('ticket', fn ($q) => $q->where('canceled', false))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as total')
                ->groupBy('visit_date')
                ->pluck('total', 'visit_date');

            $period = CarbonPeriod::create($startDate, '1 day', $endDate);
            foreach ($period as $date) {
                $label = $date->format('Y-m-d');
                $labels[] = $label;
                $data[] = (int) ($counts[$label] ?? 0);
            }

            return [$labels, $data];
        }

        if ($diffDays <= 730) {
            $counts = TicketWash::whereHas('ticket', fn ($q) => $q->where('canceled', false))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as visit_month, COUNT(*) as total")
                ->groupBy('visit_month')
                ->pluck('total', 'visit_month');

            $period = CarbonPeriod::create($startDate->copy()->startOfMonth(), '1 month', $endDate->copy()->startOfMonth());
            foreach ($period as $date) {
                $label = $date->format('Y-m');
                $labels[] = $label;
                $data[] = (int) ($counts[$label] ?? 0);
            }

            return [$labels, $data];
        }

        $counts = TicketWash::whereHas('ticket', fn ($q) => $q->where('canceled', false))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as visit_year, COUNT(*) as total')
            ->groupBy('visit_year')
            ->pluck('total', 'visit_year');

        $period = CarbonPeriod::create($startDate->copy()->startOfYear(), '1 year', $endDate->copy()->startOfYear());
        foreach ($period as $date) {
            $label = $date->format('Y');
            $labels[] = $label;
            $data[] = (int) ($counts[$label] ?? 0);
        }

        return [$labels, $data];
    }

    public function download(Request $request)
    {
        $request->validate([
            'start' => ['nullable', 'date', 'before_or_equal:end'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
        ]);
        $start = $request->input('start', now()->toDateString());
        $end = $request->input('end', now()->toDateString());

        return new \App\Exports\DashboardExport($start, $end);
    }
}
