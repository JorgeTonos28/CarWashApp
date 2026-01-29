<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function index()
    {
        $settings = AppSetting::firstOrCreate([], [
            'allow_mobile_access' => false,
            'default_low_stock' => 5,
        ]);

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_low_stock' => 'required|integer|min:0',
        ]);

        $settings = AppSetting::firstOrCreate([], [
            'allow_mobile_access' => false,
            'default_low_stock' => 5,
        ]);

        $settings->update([
            'allow_mobile_access' => $request->has('allow_mobile_access'),
            'default_low_stock' => $request->default_low_stock,
        ]);

        return back()->with('success', 'Ajustes actualizados correctamente.');
    }
}
