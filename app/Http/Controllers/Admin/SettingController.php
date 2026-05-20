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
            $path = $request->file('site_logo')->store('uploads/gallery', 'public');
            Setting::where('key', 'site_logo')->update(['value_en' => '/storage/' . $path]);
        } elseif ($request->filled('site_logo') && is_string($request->site_logo)) {
            Setting::where('key', 'site_logo')->update(['value_en' => $request->site_logo]);
        }

        // Handle Site Favicon
        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('uploads/gallery', 'public');
            Setting::updateOrCreate(
                ['key' => 'site_favicon'],
                ['value_en' => '/storage/' . $path, 'group' => 'general']
            );
        } elseif ($request->filled('site_favicon') && is_string($request->site_favicon)) {
            Setting::updateOrCreate(
                ['key' => 'site_favicon'],
                ['value_en' => $request->site_favicon, 'group' => 'general']
            );
        }

        // Handle Slider Images (Appending new ones)
        if ($request->hasFile('slider_upload')) {
            $currentSliders = json_decode(Setting::where('key', 'slider_images')->first()->value_en ?? '[]', true);
            foreach ($request->file('slider_upload') as $file) {
                $path = $file->store('uploads/gallery', 'public');
                $currentSliders[] = '/storage/' . $path;
            }
            Setting::where('key', 'slider_images')->update(['value_en' => json_encode($currentSliders)]);
        }

        // Handle Hero Static Image
        if ($request->hasFile('hero_static_image')) {
            $path = $request->file('hero_static_image')->store('uploads/gallery', 'public');
            Setting::updateOrCreate(
                ['key' => 'hero_static_image'],
                ['value_en' => '/storage/' . $path, 'group' => 'general']
            );
        } elseif ($request->filled('hero_static_image') && is_string($request->hero_static_image)) {
            Setting::updateOrCreate(
                ['key' => 'hero_static_image'],
                ['value_en' => $request->hero_static_image, 'group' => 'general']
            );
        }

        // Handle other settings
        $settings = [
            'site_name', 
            'footer_about', 
            'footer_address', 
            'footer_phone', 
            'footer_email', 
            'delivery_charge_inside_dhaka', 
            'delivery_charge_outside_dhaka',
            'hero_slider_text_show',
            'hero_slider_text'
        ];
        foreach ($settings as $key) {
            $data = [];
            if ($request->has($key . '_en')) {
                $data['value_en'] = $request->get($key . '_en');
                $data['group'] = 'general';
            }
            if ($request->has($key . '_bn')) {
                $data['value_bn'] = $request->get($key . '_bn');
                $data['group'] = 'general';
            }
            // Fallback for non-bilingual fields or older form submissions
            if ($request->has($key) && !isset($data['value_en'])) {
                $data['value_en'] = $request->get($key);
                $data['group'] = 'general';
            }
            
            if (!empty($data)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    $data
                );
            }
        }

        // Handle Slider Deletion (if requested)
        if ($request->has('slider_images')) {
            Setting::where('key', 'slider_images')->update(['value_en' => json_encode($request->get('slider_images'))]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
