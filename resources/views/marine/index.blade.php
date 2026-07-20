@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-anchor text-primary me-2"></i> Marine Port Intelligence</h2>
            <p class="text-muted mb-0">Visualisasi data pergerakan logistik maritim dan kepadatan pelabuhan global.</p>
        </div>
        <div>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold" id="liveStatus">
                <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span> LIVE MONITORING
            </span>
        </div>
    </div>

    <div class="d-none">
        <select id="countrySelect">
            <option value="IDN" selected>Indonesia</option>
        </select>
        <input id="countrySearch" type="text" value="IDN">
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            @include('dashboard.map')
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-1">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-list-stars text-warning me-2"></i> Real-time Port Shipping Matrix</h6>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pelabuhan Maritim</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Tingkat Kepadatan Kapal</th>
                            <th>Status Jalur</th>
                        </tr>
                    </thead>
                    <tbody id="marinePortTableBody">
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                Mengamankan koordinat satelit Marine Traffic...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection