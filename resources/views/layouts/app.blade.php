@php
    use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ Setting::get('site_title', 'Global Supply Chain Risk Intelligence Platform') }}</title>
    
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            align-items: stretch;
            width: 100%;
        }
        .main {
            min-height: 100vh;
            width: 100%;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .content {
            padding: 1.5rem;
            flex: 1;
        }
        #map {
            height: 450px;
            width: 100%;
            border-radius: 12px;
            z-index: 1;
        }
        .transition-hover {
            transition: all 0.3s ease;
        }
        .transition-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }
        .loading-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dashboard-footer {
            background: #ffffff;
            border-top: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #6e707e;
            margin-top: 2rem;
            border-radius: 12px;
        }
    </style>

    @vite(['resources/js/app.js'])
</head>
<body>

<div class="wrapper">
    @include('dashboard.sidebar')

    <div class="main d-flex flex-column w-100">
        <!-- Running Alert Announcement Bar Dinamis dari Admin -->
        @if($announcement = Setting::get('announcement_bar'))
            <div class="bg-warning text-dark px-3 py-2 text-center font-semibold text-sm shadow-sm border-bottom border-warning">
                <i class="bi bi-megaphone-fill me-2"></i> {{ $announcement }}
            </div>
        @endif

        <div class="content">
            @yield('content')
            
            @include('layouts.footer')
        </div>
    </div>
</div>

<div id="loadingOverlay" class="loading-overlay d-none">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Memuat Data Intelijen...</span>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 10000;">
    <div id="appToast" class="toast border-0 shadow-lg rounded-4" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-dark text-white rounded-top-4 py-2">
            <i class="bi bi-bell-fill me-2 text-warning"></i>
            <strong class="me-auto" id="toastTitle">Sistem Notifikasi</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-white text-dark rounded-bottom-4 fw-medium" id="toastMessage">
            Sistem Siap Memantau Rantai Pasok Global.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stack('scripts')

</body>
</html>