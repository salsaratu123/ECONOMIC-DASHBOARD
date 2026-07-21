<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Port;
use App\Models\Route as MarineRoute;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        if (view()->exists('admin.auth.login')) {
            return view('admin.auth.login');
        }

        return view('admin.login');
    }

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

    public function showRegisterForm()
    {
        if (view()->exists('admin.auth.register')) {
            return view('admin.auth.register');
        }

        return view('admin.register');
    }

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
            'role'     => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

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

        // Konfigurasi Grafik Dinamis (Disimpan di Settings)
        $defaultCharts = [
            ['id' => 'vessel_traffic', 'name' => 'Lalu Lintas Kapal Global', 'type' => 'line', 'order' => 1, 'visible' => true],
            ['id' => 'port_congestion', 'name' => 'Kepadatan Pelabuhan Utama', 'type' => 'bar', 'order' => 2, 'visible' => true],
            ['id' => 'supply_chain_risk', 'name' => 'Indeks Risiko Rantai Pasok', 'type' => 'pie', 'order' => 3, 'visible' => true],
        ];

        $chartConfig = Setting::get('admin_charts_config', json_encode($defaultCharts));
        $charts = json_decode($chartConfig, true) ?? $defaultCharts;

        usort($charts, fn($a, $b) => $a['order'] <=> $b['order']);

        $apiLogs = [
            (object) ['created_at' => now(), 'target_service' => 'ShipFinder API', 'status_request' => 'Live Marine Sync Successful', 'response_code' => 200],
            (object) ['created_at' => now()->subMinutes(15), 'target_service' => 'GNews Engine', 'status_request' => 'News Fetch Completed', 'response_code' => 200],
        ];

        $portsCount = Port::count();
        $routesCount = MarineRoute::count();

        return view('admin.dashboard', compact('apiKeys', 'apiLogs', 'charts', 'portsCount', 'routesCount'));
    }

    /**
     * Update Urutan / Visibility Grafik Admin
     */
    public function updateChartsConfig(Request $request)
    {
        $charts = $request->input('charts', []);
        
        Setting::updateOrCreate(
            ['key' => 'admin_charts_config'],
            ['value' => json_encode($charts)]
        );

        return redirect()->back()->with('success', 'Susunan grafik berhasil diperbarui!');
    }

    /**
     * Reset Grafik ke Default Sesuai Data API
     */
    public function resetChartsConfig()
    {
        $defaultCharts = [
            ['id' => 'vessel_traffic', 'name' => 'Lalu Lintas Kapal Global (Live API)', 'type' => 'line', 'order' => 1, 'visible' => true],
            ['id' => 'port_congestion', 'name' => 'Kepadatan Pelabuhan Utama (Live API)', 'type' => 'bar', 'order' => 2, 'visible' => true],
            ['id' => 'supply_chain_risk', 'name' => 'Indeks Risiko Rantai Pasok (Live API)', 'type' => 'pie', 'order' => 3, 'visible' => true],
        ];

        Setting::updateOrCreate(
            ['key' => 'admin_charts_config'],
            ['value' => json_encode($defaultCharts)]
        );

        return redirect()->back()->with('success', 'Konfigurasi grafik telah di-reset sesuai data API!');
    }

    /**
     * Tambah Grafik Baru Secara Dinamis
     */
    public function addChart(Request $request)
    {
        $validated = $request->validate([
            'chart_name' => 'required|string|max:255',
            'chart_type' => 'required|in:line,bar,pie,donut',
        ]);

        $currentConfig = json_decode(Setting::get('admin_charts_config', '[]'), true);
        $newOrder = count($currentConfig) + 1;

        $newChart = [
            'id'      => 'custom_' . time(),
            'name'    => $validated['chart_name'],
            'type'    => $validated['chart_type'],
            'order'   => $newOrder,
            'visible' => true,
        ];

        $currentConfig[] = $newChart;

        Setting::updateOrCreate(
            ['key' => 'admin_charts_config'],
            ['value' => json_encode($currentConfig)]
        );

        return redirect()->back()->with('success', 'Grafik baru berhasil ditambahkan!');
    }

    /**
     * Manajemen Pelabuhan & Rute
     */
    public function ports()
    {
        $ports = Port::withCount(['originRoutes', 'destinationRoutes'])->latest()->get();
        $routes = MarineRoute::with(['originPort', 'destinationPort'])->latest()->get();

        return view('admin.ports', compact('ports', 'routes'));
    }

    /**
     * Simpan Pelabuhan Baru
     */
    public function storePort(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:10|unique:ports,code',
            'country'   => 'required|string|max:255',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'status'    => 'required|in:active,congested,closed',
        ]);

        Port::create($validated);

        return redirect()->back()->with('success', 'Pelabuhan baru berhasil ditambahkan!');
    }

    /**
     * Simpan Rute Laut Baru
     */
    public function storeRoute(Request $request)
    {
        $validated = $request->validate([
            'route_name'             => 'required|string|max:255',
            'origin_port_id'         => 'required|exists:ports,id',
            'destination_port_id'    => 'required|exists:ports,id|different:origin_port_id',
            'estimated_transit_days' => 'required|integer|min:1',
            'risk_level'             => 'required|in:low,medium,high,critical',
        ]);

        MarineRoute::create($validated);

        return redirect()->back()->with('success', 'Rute maritim berhasil ditambahkan!');
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
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('success', 'Tampilan User Berhasil Diperbarui!');
    }

    public function updateApiKeys(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('success', 'API Keys Dinamis Berhasil Diperbarui!');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function articles()
    {
        return view('admin.articles');
    }
}