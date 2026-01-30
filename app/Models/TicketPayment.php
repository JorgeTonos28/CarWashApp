<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'payment_method',
        'amount',
        'bank_account_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
