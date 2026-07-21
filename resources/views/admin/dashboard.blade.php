@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Admin Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i> Admin Control Center
            </h2>
            <p class="text-muted mb-0">Manajemen akses terpusat, pengawasan multi-API, dan konfigurasi API Key secara live.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 rounded-3">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} (Admin)
            </span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger fw-bold rounded-3 btn-sm px-3 py-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Widget Ringkasan Sistem -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="summary-card shadow-sm border-0 p-3 bg-white rounded-4 d-flex align-items-center gap-3">
                <div class="icon bg-primary text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold">Total Pengguna</small>
                    <h4 class="fw-bold text-dark mb-0">12 User</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card shadow-sm border-0 p-3 bg-white rounded-4 d-flex align-items-center gap-3">
                <div class="icon bg-success text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-cpu-fill fs-4"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold">Status Multi-API</small>
                    <h4 class="fw-bold text-dark mb-0">6 Connected</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card shadow-sm border-0 p-3 bg-white rounded-4 d-flex align-items-center gap-3">
                <div class="icon bg-warning text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-anchor fs-4"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold">Pelabuhan Terpantau</small>
                    <h4 class="fw-bold text-dark mb-0">1,240 Ports</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card shadow-sm border-0 p-3 bg-white rounded-4 d-flex align-items-center gap-3">
                <div class="icon bg-danger text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-database-check fs-4"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold">Kondisi Cache</small>
                    <h4 class="fw-bold text-dark mb-0">Optimal</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM DYNAMIC API KEYS MANAGER -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-key-fill text-warning me-2"></i> Pengaturan API Key Dinamis Platform
            </h5>
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2">Live Dynamic Config</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.settings.apikeys') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">GNews API Key</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0 bg-light"><i class="bi bi-newspaper"></i></span>
                            <input type="text" name="gnews_api_key" class="form-control border-start-0" value="{{ $apiKeys['gnews_api_key'] ?? '' }}" placeholder="Masukkan GNews API Key">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Marine Traffic API Key</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0 bg-light"><i class="bi bi-tsunami"></i></span>
                            <input type="text" name="marinetraffic_api_key" class="form-control border-start-0" value="{{ $apiKeys['marinetraffic_api_key'] ?? '' }}" placeholder="Masukkan Marine Traffic API Key">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">REST Countries API Key (Premium Key)</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0 bg-light"><i class="bi bi-globe"></i></span>
                            <input type="text" name="restcountries_api_key" class="form-control border-start-0" value="{{ $apiKeys['restcountries_api_key'] ?? '' }}" placeholder="Masukkan REST Countries API Key">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">ExchangeRate API Key</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0 bg-light"><i class="bi bi-currency-exchange"></i></span>
                            <input type="text" name="exchangerate_api_key" class="form-control border-start-0" value="{{ $apiKeys['exchangerate_api_key'] ?? '' }}" placeholder="Masukkan ExchangeRate API Key">
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-3">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan API Key
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL LOG AKTIVITAS SISTEM (DINAMIS) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header border-0 bg-transparent pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-activity text-primary me-2"></i> System API Activity Logs
            </h5>
            <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill">
                {{ count($apiLogs ?? []) }} Realtime Logs
            </span>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-muted small fw-semibold">WAKTU EXEC</th>
                            <th scope="col" class="text-muted small fw-semibold">TARGET API SERVICE</th>
                            <th scope="col" class="text-muted small fw-semibold">STATUS REQUEST</th>
                            <th scope="col" class="text-muted small fw-semibold text-center">RESPONSE CODE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($apiLogs as $log)
                            <tr>
                                <td class="text-muted small font-monospace">
                                    {{ is_object($log->created_at ?? null) ? $log->created_at->format('Y-m-d H:i:s') : ($log->created_at ?? date('Y-m-d H:i:s')) }}
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold">
                                        {{ $log->target_service ?? 'External Engine' }}
                                    </span>
                                </td>
                                <td class="text-dark fw-medium small">
                                    {{ $log->status_request ?? 'API Telemetry Sync' }}
                                </td>
                                <td class="text-center">
                                    @if(($log->response_code ?? 200) == 200)
                                        <span class="badge bg-success fw-bold px-3 py-1">200 OK</span>
                                    @else
                                        <span class="badge bg-danger fw-bold px-3 py-1">{{ $log->response_code }} ERR</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                                    Belum ada log aktivitas sistem terdeteksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection