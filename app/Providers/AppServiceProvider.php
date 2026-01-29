<?php

namespace App\Providers;

use App\Models\AppearanceSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('appearance_settings')) {
            $appearance = AppearanceSetting::first();
            if (! $appearance) {
                $appearance = AppearanceSetting::create([
                    'business_name' => 'CarWash App',
                ]);
            }
            View::share('appearance', $appearance);
        }
    }
}
