@extends('layouts.app')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg rounded-4 p-4 bg-white">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Registrasi Administrator</h3>
                <p class="text-muted small">Buat otorisasi akun baru untuk mengelola platform</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.register.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nama Lengkap Admin</label>
                    <input type="text" name="name" class="form-control" placeholder="Administrator System" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Email Official</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@platform.com" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-danger">Kode Rahasia Otorisasi Admin</label>
                    <input type="password" name="admin_secret" class="form-control border-danger" placeholder="Gunakan: ADMIN-SECRET-KEY-2026" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 mb-3">
                    Daftar Sebagai Admin
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="text-decoration-none small text-muted fw-semibold">
                    Sudah memiliki akun admin? <span class="text-primary">Login Di Sini</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection