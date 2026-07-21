<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Pengaturan Tampilan Utama
        $settings = [
            'site_title'       => Setting::get('site_title', 'Global Supply Chain Risk Intelligence Platform'),
            'hero_heading'     => Setting::get('hero_heading', 'Real-Time Maritime & Supply Chain Intelligence'),
            'hero_subheading'  => Setting::get('hero_subheading', 'Pantau lalu lintas kapal dan risiko pasokan secara presisi.'),
            'announcement_bar' => Setting::get('announcement_bar', 'Sistem berjalan normal. Semua data live terhubung.'),
        ];

        // 2. Ambil Susunan Grafik Hasil Pengaturan Admin
        $defaultCharts = [
            ['id' => 'vessel_traffic', 'name' => 'Lalu Lintas Kapal Global', 'type' => 'line', 'order' => 1, 'visible' => true],
            ['id' => 'port_congestion', 'name' => 'Kepadatan Pelabuhan Utama', 'type' => 'bar', 'order' => 2, 'visible' => true],
            ['id' => 'supply_chain_risk', 'name' => 'Indeks Risiko Rantai Pasok', 'type' => 'pie', 'order' => 3, 'visible' => true],
        ];

        $chartConfig = Setting::get('admin_charts_config', json_encode($defaultCharts));
        $charts = json_decode($chartConfig, true) ?? $defaultCharts;

        // Filter grafik yang visible & urutkan berdasar order
        $charts = array_filter($charts, fn($c) => $c['visible'] ?? true);
        usort($charts, fn($a, $b) => $a['order'] <=> $b['order']);

        return view('dashboard.index', compact('settings', 'charts'));
    }
}