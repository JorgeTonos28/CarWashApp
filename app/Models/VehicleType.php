<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
