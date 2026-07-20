@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Include navbar agar pencarian dan status LIVE tetap aktif secara global -->
    @include('dashboard.navbar')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-columns-gap text-primary me-2"></i> Country Comparison Engine</h2>
            <p class="text-muted mb-0">Komparasi matriks risiko rantai pasok dan indikator ekonomi makro antar negara secara simultan.</p>
        </div>
    </div>

    <!-- Form Pemilihan Perbandingan -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-secondary small">Negara Acuan (Benchmark)</label>
                    <select class="form-select border rounded-3" id="compareCountryA">
                        <option value="IDN" selected>Indonesia</option>
                    </select>
                </div>
                <div class="col-md-2 text-center mt-4">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-circle p-2 fs-6">VS</span>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-secondary small">Negara Pembanding (Target)</label>
                    <select class="form-select border rounded-3" id="compareCountryB">
                        <option value="SGP" selected>Singapore</option>
                        <option value="MYS">Malaysia</option>
                        <option value="CHN">China</option>
                        <option value="USA">United States</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Matriks Perbandingan Komparatif -->
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 30%;">Metrik Rantai Pasok</th>
                            <th style="width: 35%;" id="nameHeaderA">Indonesia</th>
                            <th style="width: 35%;" id="nameHeaderB">Singapore</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start fw-semibold text-secondary">Total Skor Risiko Intelijen</td>
                            <td><span class="badge bg-success px-3 py-2 fs-6">32 PTS (LOW)</span></td>
                            <td><span class="badge bg-warning px-3 py-2 fs-6">48 PTS (MODERATE)</span></td>
                        </tr>
                        <tr>
                            <td class="text-start fw-semibold text-secondary">Skor Risiko Cuaca (Open-Meteo)</td>
                            <td>20 / 100</td>
                            <td>15 / 100</td>
                        </tr>
                        <tr>
                            <td class="text-start fw-semibold text-secondary">Tingkat Inflasi (World Bank)</td>
                            <td>2.8 %</td>
                            <td>2.1 %</td>
                        </tr>
                        <tr>
                            <td class="text-start fw-semibold text-secondary">Kepadatan Maritim Satelit</td>
                            <td>46% (Tanjung Priok)</td>
                            <td>68% (Port of Singapore)</td>
                        </tr>
                        <tr>
                            <td class="text-start fw-semibold text-secondary">Stabilitas Nilai Tukar (FX)</td>
                            <td>Mata Uang Domestik (IDR)</td>
                            <td>Mata Uang Kuat (SGD)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection