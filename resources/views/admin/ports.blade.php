@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-white font-bold">Manajemen Pelabuhan & Rute Laut</h2>
        <div>
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addPortModal">
                <i class="bi bi-geo-alt-fill me-1"></i> Tambah Pelabuhan
            </button>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addRouteModal">
                <i class="bi bi-signpost-split-fill me-1"></i> Tambah Rute Maritim
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Daftar Pelabuhan -->
        <div class="col-lg-6">
            <div class="card bg-dark text-white border-secondary h-100">
                <div class="card-header border-secondary fw-bold">
                    <i class="bi bi-building me-2"></i>Daftar Pelabuhan Aktif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Negara</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ports as $port)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $port->code }}</span></td>
                                        <td>{{ $port->name }}</td>
                                        <td>{{ $port->country }}</td>
                                        <td>
                                            @if($port->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($port->status == 'congested')
                                                <span class="badge bg-warning text-dark">Padat</span>
                                            @else
                                                <span class="badge bg-danger">Tutup</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data pelabuhan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Rute Marine -->
        <div class="col-lg-6">
            <div class="card bg-dark text-white border-secondary h-100">
                <div class="card-header border-secondary fw-bold">
                    <i class="bi bi-map me-2"></i>Rute Maritim
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Rute</th>
                                    <th>Asal -> Tujuan</th>
                                    <th>Estimasi</th>
                                    <th>Tingkat Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($routes as $route)
                                    <tr>
                                        <td>{{ $route->route_name }}</td>
                                        <td>{{ $route->originPort->code ?? '-' }} &rarr; {{ $route->destinationPort->code ?? '-' }}</td>
                                        <td>{{ $route->estimated_transit_days }} Hari</td>
                                        <td>
                                            <span class="badge bg-{{ $route->risk_level == 'critical' ? 'danger' : ($route->risk_level == 'high' ? 'warning' : 'info') }}">
                                                {{ ucfirst($route->risk_level) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada rute terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelabuhan -->
<div class="modal fade" id="addPortModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.ports.store') }}" method="POST" class="modal-content bg-dark text-white border-secondary">
            @csrf
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Tambah Pelabuhan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Pelabuhan</label>
                    <input type="text" name="name" class="form-control bg-secondary text-white border-0" required placeholder="Port of Tanjung Priok">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Unik</label>
                        <input type="text" name="code" class="form-control bg-secondary text-white border-0" required placeholder="IDTPP">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Negara</label>
                        <input type="text" name="country" class="form-control bg-secondary text-white border-0" required placeholder="Indonesia">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="any" name="latitude" class="form-control bg-secondary text-white border-0" required placeholder="-6.1000">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="any" name="longitude" class="form-control bg-secondary text-white border-0" required placeholder="106.8800">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Operasional</label>
                    <select name="status" class="form-select bg-secondary text-white border-0">
                        <option value="active">Aktif</option>
                        <option value="congested">Padat / Congested</option>
                        <option value="closed">Tutup</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-primary">Simpan Pelabuhan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Rute -->
<div class="modal fade" id="addRouteModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.routes.store') }}" method="POST" class="modal-content bg-dark text-white border-secondary">
            @csrf
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Tambah Rute Maritim Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Rute</label>
                    <input type="text" name="route_name" class="form-control bg-secondary text-white border-0" required placeholder="Jalur Selat Malaka - SG">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pelabuhan Asal</label>
                        <select name="origin_port_id" class="form-select bg-secondary text-white border-0" required>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}">{{ $port->name }} ({{ $port->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pelabuhan Tujuan</label>
                        <select name="destination_port_id" class="form-select bg-secondary text-white border-0" required>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}">{{ $port->name }} ({{ $port->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estimasi Transit (Hari)</label>
                        <input type="number" name="estimated_transit_days" class="form-control bg-secondary text-white border-0" min="1" required placeholder="5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tingkat Risiko</label>
                        <select name="risk_level" class="form-select bg-secondary text-white border-0">
                            <option value="low">Rendah (Low)</option>
                            <option value="medium">Sedang (Medium)</option>
                            <option value="high">Tinggi (High)</option>
                            <option value="critical">Kritis (Critical)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-success">Simpan Rute</button>
            </div>
        </form>
    </div>
</div>
@endsection