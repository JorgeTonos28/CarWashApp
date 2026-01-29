<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $guarded = [];

    public static function defaultLowStock(): int
    {
        $settings = static::first();

        return $settings ? (int) $settings->default_low_stock : 5;
    }

    public static function allowMobileAccess(): bool
    {
        $settings = static::first();

        return $settings ? (bool) $settings->allow_mobile_access : false;
    }
}
