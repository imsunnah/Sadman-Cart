<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        // Handle Site Logo
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('uploads/settings', 'public');
            Setting::where('key', 'site_logo')->update(['value' => \Illuminate\Support\Facades\Storage::disk('public')->url($path)]);
        }

        // Handle Slider Images (Appending new ones)
        if ($request->hasFile('slider_upload')) {
            $currentSliders = json_decode(Setting::where('key', 'slider_images')->first()->value ?? '[]', true);
            foreach ($request->file('slider_upload') as $file) {
                $path = $file->store('uploads/settings/sliders', 'public');
                $currentSliders[] = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
            }
            Setting::where('key', 'slider_images')->update(['value' => json_encode($currentSliders)]);
        }

        // Handle other settings
        $settings = ['site_name', 'footer_about', 'footer_address', 'footer_phone', 'footer_email', 'delivery_charge_inside_dhaka', 'delivery_charge_outside_dhaka'];
        foreach ($settings as $key) {
            if ($request->has($key)) {
                Setting::where('key', $key)->update(['value' => $request->get($key)]);
            }
        }

        // Handle Slider Deletion (if requested)
        if ($request->has('slider_images')) {
            Setting::where('key', 'slider_images')->update(['value' => json_encode($request->get('slider_images'))]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
