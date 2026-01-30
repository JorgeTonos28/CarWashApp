<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
    ];

    public function variants()
    {
        return $this->hasMany(GenericServiceVariant::class);
    }
}
