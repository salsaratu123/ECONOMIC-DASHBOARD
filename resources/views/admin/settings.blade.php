@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Pengontrol Tampilan User (Frontend CMS)</h3>
            <p class="text-muted mb-0">Ubah teks, judul, banner pengumuman, dan konfigurasi API langsung dari dashboard.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Judul Platform (Site Title)</label>
                    <input type="text" name="site_title" value="{{ $settings['site_title'] }}" class="form-control rounded-3">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Teks Running Announcement / Alert User</label>
                    <input type="text" name="announcement_bar" value="{{ $settings['announcement_bar'] }}" class="form-control rounded-3">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Heading Utama (Hero Section)</label>
                    <input type="text" name="hero_heading" value="{{ $settings['hero_heading'] }}" class="form-control rounded-3">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sub-Heading Utama</label>
                    <textarea name="hero_subheading" rows="3" class="form-control rounded-3">{{ $settings['hero_subheading'] }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">ShipFinder API Key (Live Marine Traffic)</label>
                    <input type="text" name="shipfinder_key" value="{{ $settings['shipfinder_key'] }}" class="form-control rounded-3">
                </div>

                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 font-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan Tampilan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection