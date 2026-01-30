<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_id',
        'vehicle_type_id',
        'plate',
        'brand',
        'model',
        'color',
        'year',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketWashes()
    {
        return $this->hasMany(TicketWash::class);
    }
}
