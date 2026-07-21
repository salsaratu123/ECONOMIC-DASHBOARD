<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Global Supply Chain Intelligence</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            width: 100%;
            max-width: 450px;
        }
        .form-control {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #f8fafc;
        }
        .form-control:focus {
            background-color: #0f172a;
            border-color: #3b82f6;
            color: #f8fafc;
            box-shadow: none;
        }
    </style>
</head>
<body>

<div class="register-card p-4 p-sm-5 shadow-lg my-5">
    <div class="text-center mb-4">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
            <i class="bi bi-person-plus-fill fs-3"></i>
        </div>
        <h4 class="fw-bold text-white mb-1">Registrasi Admin</h4>
        <p class="text-secondary small">Buat akun pengelola sistem platform intelijen</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small rounded-3 mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.register.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label text-light small font-medium">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Administrator" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label text-light small font-medium">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="admin@domain.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-light small font-medium">Kata Sandi</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
        </div>

        <div class="mb-4">
            <label class="form-label text-light small font-medium">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 font-semibold rounded-3 mb-3">
            <i class="bi bi-person-check-fill me-1"></i> Daftar Sekarang
        </button>

        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-secondary text-decoration-none small">
                Sudah punya akun? <span class="text-primary fw-bold">Login di sini</span>
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>