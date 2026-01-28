<?php

namespace App\Http\Controllers;

use App\Models\AppearanceSetting;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        return view('appearance.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_address' => 'nullable|string|max:500',
            'tax_id' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'login_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_description' => 'nullable|string|max:50',
        ]);

        $settings = AppearanceSetting::firstOrCreate([], [
            'business_name' => 'CarWash App',
        ]);

        $data = [
            'business_name' => $request->business_name,
            'business_address' => $request->business_address,
            'tax_id' => $request->tax_id,
            'qr_description' => $request->qr_description,
        ];

        if ($request->hasFile('logo')) {
            $request->file('logo')->storeAs('public/images', 'logo.png');
            $data['logo_updated_at'] = now();
        }

        if ($request->hasFile('login_logo')) {
            $request->file('login_logo')->storeAs('public/images', 'login_logo.png');
            $data['login_logo_updated_at'] = now();
        }

        if ($request->hasFile('qr_code')) {
            $request->file('qr_code')->storeAs('public/images', 'qr_code.png');
            $data['qr_code_updated_at'] = now();
        }

        $settings->update($data);

        return back()->with('success', 'Configuración actualizada.');
    }
}
