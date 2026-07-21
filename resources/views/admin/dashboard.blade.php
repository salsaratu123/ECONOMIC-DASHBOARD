@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 text-white">
    
    <!-- Flash Message Success -->
    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-20 border-0 text-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 font-bold mb-1">Admin Control Center</h2>
            <p class="text-muted mb-0">Atur semua tampilan dashboard, grafik dinamis, dan integrasi API.</p>
        </div>
        
        <!-- Action Buttons: Reset & Tambah Grafik -->
        <div class="d-flex gap-2">
            <form action="{{ route('admin.charts.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset susunan grafik sesuai data API?')">
                @csrf
                <button type="submit" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Grafik (Sesuai API)
                </button>
            </form>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addChartModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Grafik Baru
            </button>
        </div>
    </div>

    <!-- Manajemen Urutan & Tampilan Grafik -->
    <div class="card bg-dark text-white border-secondary mb-4">
        <div class="card-header border-secondary d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-bar-chart-line-fill me-2 text-info"></i>Pengaturan Grafik Dashboard</h5>
            <small class="text-muted">Ubah urutan (Naik/Turun) atau sembunyikan grafik dari tampilan umum.</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.charts.update') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-dark align-middle">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Nama Grafik</th>
                                <th>Tipe</th>
                                <th>Status Tampil</th>
                                <th>Posisi / Kontrol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($charts as $index => $chart)
                                <tr>
                                    <td style="width: 80px;">
                                        <input type="number" name="charts[{{ $index }}][order]" value="{{ $chart['order'] }}" class="form-control form-control-sm bg-secondary text-white border-0 text-center">
                                    </td>
                                    <td>
                                        <input type="hidden" name="charts[{{ $index }}][id]" value="{{ $chart['id'] }}">
                                        <input type="text" name="charts[{{ $index }}][name]" value="{{ $chart['name'] }}" class="form-control form-control-sm bg-secondary text-white border-0">
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ strtoupper($chart['type']) }}</span>
                                        <input type="hidden" name="charts[{{ $index }}][type]" value="{{ $chart['type'] }}">
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="charts[{{ $index }}][visible]" value="1" {{ !empty($chart['visible']) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">Ubah angka pada kolom 'Urutan' lalu simpan.</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Perubahan Grafik</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Grafik -->
<div class="modal fade" id="addChartModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.charts.add') }}" method="POST" class="modal-content bg-dark text-white border-secondary">
            @csrf
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Tambah Widget Grafik Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama / Judul Grafik</label>
                    <input type="text" name="chart_name" class="form-control bg-secondary text-white border-0" required placeholder="Contoh: Indeks Inflasi Laut">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe Visualisasi Grafik</label>
                    <select name="chart_type" class="form-select bg-secondary text-white border-0">
                        <option value="line">Line Chart (Garis)</option>
                        <option value="bar">Bar Chart (Batang)</option>
                        <option value="pie">Pie Chart (Lingkaran)</option>
                        <option value="donut">Donut Chart</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-primary">Tambah Grafik</button>
            </div>
        </form>
    </div>
</div>
@endsection