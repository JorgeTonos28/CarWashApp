<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'stock', 'low_stock_threshold'];

    public function scopeLowStock($query, int $defaultThreshold)
    {
        return $query->whereRaw('stock <= COALESCE(low_stock_threshold, ?)', [$defaultThreshold]);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function details()
    {
        return $this->hasMany(TicketDetail::class);
    }
}
