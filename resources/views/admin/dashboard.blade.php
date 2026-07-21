@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- Flash Message Notification -->
    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 border-0 text-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 font-bold mb-1 text-dark">Admin Control Center</h2>
            <p class="text-secondary mb-0">Kelola tata letak grafik, batas parameter API, dan status sistem secara real-time.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.charts.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset grafik ke kondisi default API?')">
                @csrf
                <button type="submit" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 font-bold">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Grafik (API Default)
                </button>
            </form>
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1 font-bold" data-bs-toggle="modal" data-bs-target="#addChartModal">
                <i class="bi bi-plus-lg"></i> Tambah Widget Grafik
            </button>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block font-bold">Total Pelabuhan</span>
                        <h3 class="mb-0 font-bold text-dark">{{ $portsCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 rounded text-info">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block font-bold">Rute Aktif</span>
                        <h3 class="mb-0 font-bold text-success">{{ $routesCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded text-success">
                        <i class="bi bi-signpost-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block font-bold">Total Widget Grafik</span>
                        <h3 class="mb-0 font-bold text-warning">{{ count($charts) }}</h3>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 rounded text-warning">
                        <i class="bi bi-pie-chart fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white border shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block font-bold">Status API Engine</span>
                        <h3 class="mb-0 font-bold text-primary">Connected</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded text-primary">
                        <i class="bi bi-hdd-network fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Configuration Table -->
    <div class="card bg-white border shadow-sm mb-4">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title mb-0 h6 font-bold text-dark">
                <i class="bi bi-sliders me-2 text-primary"></i>Pengaturan Posisi & Tampilan Grafik Dashboard
            </h5>
            <small class="text-muted">Ubah angka pada urutan untuk menaikkan/menurunkan posisi grafik di halaman utama.</small>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('admin.charts.update') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark text-uppercase small">
                            <tr>
                                <th style="width: 100px;" class="text-center">Urutan</th>
                                <th>Nama Widget Grafik</th>
                                <th>Tipe Visualisasi</th>
                                <th>Status Tampil</th>
                                <th class="text-end pe-4">Aksi / Informasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($charts as $index => $chart)
                                <tr>
                                    <td class="text-center px-3">
                                        <input type="number" name="charts[{{ $index }}][order]" value="{{ $chart['order'] }}" class="form-control form-control-sm border-secondary text-dark fw-bold text-center bg-light">
                                    </td>
                                    <td>
                                        <input type="hidden" name="charts[{{ $index }}][id]" value="{{ $chart['id'] }}">
                                        <input type="text" name="charts[{{ $index }}][name]" value="{{ $chart['name'] }}" class="form-control form-control-sm border-secondary text-dark fw-bold bg-light">
                                    </td>
                                    <td>
                                        <span class="badge bg-dark text-white text-uppercase px-2 py-1">
                                            <i class="bi bi-graph-up me-1"></i>{{ $chart['type'] }}
                                        </span>
                                        <input type="hidden" name="charts[{{ $index }}][type]" value="{{ $chart['type'] }}">
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="charts[{{ $index }}][visible]" value="1" {{ !empty($chart['visible']) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark small font-bold">
                                                {{ !empty($chart['visible']) ? 'Aktif' : 'Disembunyikan' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="text-muted small">Ubah angka order lalu simpan.</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada grafik. Klik reset untuk memuat default API.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-top text-end py-3">
                    <button type="submit" class="btn btn-success font-bold px-4">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Tampilan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Modal Tambah Grafik Baru -->
<div class="modal fade" id="addChartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.charts.add') }}" method="POST" class="modal-content bg-white text-dark border">
            @csrf
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title h6 font-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Widget Grafik Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label text-dark font-bold small">Judul Widget Grafik</label>
                    <input type="text" name="chart_name" class="form-control border-secondary text-dark" required placeholder="Misal: Indeks Kemacetan Terusan Suez">
                </div>
                <div class="mb-3">
                    <label class="form-label text-dark font-bold small">Tipe Grafik</label>
                    <select name="chart_type" class="form-select border-secondary text-dark">
                        <option value="line">Line Chart (Garis Trend)</option>
                        <option value="bar">Bar Chart (Batang Komparasi)</option>
                        <option value="pie">Pie Chart (Proporsi Risiko)</option>
                        <option value="donut">Donut Chart</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm px-3">Tambah Grafik</button>
            </div>
        </form>
    </div>
</div>
@endsection