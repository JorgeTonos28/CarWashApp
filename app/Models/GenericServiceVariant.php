<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericServiceVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'generic_service_id',
        'name',
        'price',
        'active',
    ];

    public function service()
    {
        return $this->belongsTo(GenericService::class, 'generic_service_id');
    }

    public function genericService()
    {
        return $this->belongsTo(GenericService::class, 'generic_service_id');
    }
}
