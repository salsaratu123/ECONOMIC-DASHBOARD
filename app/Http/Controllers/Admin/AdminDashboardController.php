<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan Halaman Login Admin
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        // Dipanggil sesuai struktur folder: resources/views/admin/auth/login.blade.php
        if (view()->exists('admin.auth.login')) {
            return view('admin.auth.login');
        }

        if (view()->exists('admin.login')) {
            return view('admin.login');
        }

        return view('auth.login');
    }

    /**
     * Proses Login Admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang dimasukkan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan Halaman Register Admin
     */
    public function showRegisterForm()
    {
        // Dipanggil sesuai struktur folder: resources/views/admin/auth/register.blade.php
        if (view()->exists('admin.auth.register')) {
            return view('admin.auth.register');
        }

        if (view()->exists('admin.register')) {
            return view('admin.register');
        }

        return view('auth.register');
    }

    /**
     * Proses Register Admin
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Logout Admin / User
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Dashboard Utama Admin
     */
    public function index()
    {
        $apiKeys = [
            'gnews_api_key'  => Setting::get('gnews_api_key', env('GNEWS_API_KEY')),
            'shipfinder_key' => Setting::get('shipfinder_key', env('SHIPFINDER_API_KEY')),
        ];

        $apiLogs = [
            (object) [
                'created_at'     => now(),
                'target_service' => 'ShipFinder API',
                'status_request' => 'Live Marine Sync Successful',
                'response_code'  => 200,
            ],
            (object) [
                'created_at'     => now()->subMinutes(15),
                'target_service' => 'GNews Engine',
                'status_request' => 'News Fetch Completed',
                'response_code'  => 200,
            ],
        ];

        return view('admin.dashboard', compact('apiKeys', 'apiLogs'));
    }

    /**
     * Tampilkan Halaman Form Pengaturan Tampilan User
     */
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

    /**
     * Update Pengaturan Tampilan User
     */
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

    /**
     * Update API Keys Dinamis
     */
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