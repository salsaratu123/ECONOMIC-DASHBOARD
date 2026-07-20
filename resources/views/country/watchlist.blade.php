@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @include('dashboard.navbar')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-star-fill text-warning me-2"></i> Watchlist Matrix</h2>
            <p class="text-muted mb-0">Daftar pantauan khusus negara dengan tingkat volatilitas risiko logistik tertinggi.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Negara</th>
                            <th>Kode ISO</th>
                            <th>Indeks Kerawanan</th>
                            <th>Status Tren Krisis</th>
                            <th>Aksi Operasional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold text-dark">Indonesia</td>
                            <td><span class="badge bg-secondary-subtle text-secondary font-monospace">IDN</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-success">Low Risk (32)</span>
                                </div>
                            </td>
                            <td><span class="text-success fw-medium"><i class="bi bi-arrow-down-right me-1"></i> Stabil</span></td>
                            <td><button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i> Hapus</button></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-dark">China</td>
                            <td><span class="badge bg-secondary-subtle text-secondary font-monospace">CHN</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-warning">Moderate (55)</span>
                                </div>
                            </td>
                            <td><span class="text-warning fw-medium"><i class="bi bi-arrow-right me-1"></i> Fluktuatif</span></td>
                            <td><button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i> Hapus</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection