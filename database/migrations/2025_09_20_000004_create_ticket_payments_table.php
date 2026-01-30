<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['efectivo', 'tarjeta', 'transferencia']);
            $table->decimal('amount', 10, 2);
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->timestamps();
        });

        if (Schema::hasTable('tickets')) {
            $tickets = DB::table('tickets')
                ->whereNotNull('payment_method')
                ->where('paid_amount', '>', 0)
                ->get(['id', 'payment_method', 'paid_amount', 'bank_account_id', 'paid_at', 'created_at', 'updated_at']);

            foreach ($tickets as $ticket) {
                $method = $ticket->payment_method === 'mixto' ? 'efectivo' : $ticket->payment_method;
                if (!in_array($method, ['efectivo', 'tarjeta', 'transferencia'], true)) {
                    continue;
                }
                DB::table('ticket_payments')->insert([
                    'ticket_id' => $ticket->id,
                    'payment_method' => $method,
                    'amount' => $ticket->paid_amount,
                    'bank_account_id' => $method === 'transferencia' ? $ticket->bank_account_id : null,
                    'created_at' => $ticket->paid_at ?? $ticket->created_at,
                    'updated_at' => $ticket->updated_at ?? $ticket->created_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_payments');
    }
};
