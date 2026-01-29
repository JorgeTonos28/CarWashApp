<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppearanceSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'logo_updated_at' => 'datetime',
        'login_logo_updated_at' => 'datetime',
        'qr_code_updated_at' => 'datetime',
    ];
}
