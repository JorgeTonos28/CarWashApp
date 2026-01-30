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
            'vehicle_ready_subject' => 'Tu vehículo está listo para retirar',
            'vehicle_ready_greeting' => 'Hola {cliente},',
            'vehicle_ready_body' => 'Tu vehículo {placa} - {modelo} ya está listo para retirar.',
            'vehicle_ready_footer' => 'Gracias por confiar en nosotros.',
        ]);

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_low_stock' => 'required|integer|min:0',
            'vehicle_ready_subject' => 'nullable|string|max:255',
            'vehicle_ready_greeting' => 'nullable|string|max:255',
            'vehicle_ready_body' => 'nullable|string',
            'vehicle_ready_footer' => 'nullable|string|max:255',
        ]);

        $settings = AppSetting::firstOrCreate([], [
            'allow_mobile_access' => false,
            'default_low_stock' => 5,
            'vehicle_ready_subject' => 'Tu vehículo está listo para retirar',
            'vehicle_ready_greeting' => 'Hola {cliente},',
            'vehicle_ready_body' => 'Tu vehículo {placa} - {modelo} ya está listo para retirar.',
            'vehicle_ready_footer' => 'Gracias por confiar en nosotros.',
        ]);

        $settings->update([
            'allow_mobile_access' => $request->has('allow_mobile_access'),
            'default_low_stock' => $request->default_low_stock,
            'vehicle_ready_subject' => $request->vehicle_ready_subject,
            'vehicle_ready_greeting' => $request->vehicle_ready_greeting,
            'vehicle_ready_body' => $request->vehicle_ready_body,
            'vehicle_ready_footer' => $request->vehicle_ready_footer,
        ]);

        return back()->with('success', 'Ajustes actualizados correctamente.');
    }
}
