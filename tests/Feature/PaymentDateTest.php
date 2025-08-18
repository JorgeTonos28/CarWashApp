<?php
namespace Tests\Feature;

use App\Models\{User, Washer, Service, VehicleType, Ticket, TicketDetail};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PaymentDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_payment_date_affects_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $washer = Washer::create(['name'=>'Lavador','pending_amount'=>0,'active'=>true]);
        $vehicleType = VehicleType::create(['name'=>'Car']);
        $service = Service::create(['name'=>'Lavado','description'=>'','active'=>true]);

        $date = Carbon::yesterday();

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'washer_id' => $washer->id,
            'vehicle_type_id' => $vehicleType->id,
            'vehicle_id' => null,
            'customer_name' => 'Cliente',
            'customer_cedula' => null,
            'total_amount' => 200,
            'paid_amount' => 0,
            'change' => 0,
            'discount_total' => 0,
            'payment_method' => 'efectivo',
            'bank_account_id' => null,
            'washer_pending_amount' => 0,
            'canceled' => false,
            'cancel_reason' => null,
            'pending' => true,
            'paid_at' => null,
            'created_at' => $date,
        ]);

        TicketDetail::create([
            'ticket_id' => $ticket->id,
            'type' => 'service',
            'service_id' => $service->id,
            'product_id' => null,
            'drink_id' => null,
            'quantity' => 1,
            'unit_price' => 200,
            'discount_amount' => 0,
            'subtotal' => 200,
        ]);

        $this->actingAs($user)->post(route('tickets.pay',$ticket),[
            'payment_method' => 'efectivo',
            'bank_account_id' => null,
            'paid_amount' => 200,
            'payment_date' => $date->toDateString(),
        ]);

        $ticket->refresh();
        $this->assertEquals($date->toDateString(), $ticket->paid_at->toDateString());

        $response = $this->actingAs($user)
            ->get('/dashboard?start='.$date->toDateString().'&end='.$date->toDateString());
        $this->assertEquals(200, $response->viewData('cashTotal'));
    }
}
