@extends('layouts.app')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 p-4 bg-white">
            <div class="text-center mb-4">
                <div class="p-3 bg-primary-subtle text-primary rounded-circle d-inline-block mb-3">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark">Admin Portal</h3>
                <p class="text-muted small">Global Supply Chain Risk Intelligence Platform</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-3">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Email Administrator</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@platform.com" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 mb-3">
                    Masuk ke Admin Console
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('admin.register') }}" class="text-decoration-none small text-muted fw-semibold">
                    Belum punya akun admin? <span class="text-primary">Daftar Admin Baru</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection