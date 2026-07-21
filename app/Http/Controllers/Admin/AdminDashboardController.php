<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $apiKeys = [
            'gnews_api_key' => Setting::get('gnews_api_key', env('GNEWS_API_KEY')),
            'shipfinder_key' => Setting::get('shipfinder_key', env('SHIPFINDER_API_KEY')),
        ];

        return view('admin.dashboard', compact('apiKeys'));
    }

    public function settings()
    {
        $settings = [
            'site_title'       => Setting::get('site_title', 'Global Supply Chain Risk Intelligence Platform'),
            'hero_heading'     => Setting::get('hero_heading', 'Real-Time Maritime & Supply Chain Intelligence'),
            'hero_subheading'  => Setting::get('hero_subheading', 'Pantau lalu lintas kapal dan risiko pasokan secara presisi.'),
            'announcement_bar' => Setting::get('announcement_bar', 'Sistem berjalan normal. Semua data live terhubung.'),
            'shipfinder_key'   => Setting::get('shipfinder_key', env('SHIPFINDER_API_KEY')),
            'gnews_api_key'    => Setting::get('gnews_api_key', env('GNEWS_API_KEY')),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Tampilan User Berhasil Diperbarui!');
    }

    // Method khusus untuk update API Keys dari form dashboard.blade.php
    public function updateApiKeys(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'API Keys Dinamis Berhasil Diperbarui!');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function ports()
    {
        return view('admin.ports');
    }

    public function articles()
    {
        return view('admin.articles');
    }
}