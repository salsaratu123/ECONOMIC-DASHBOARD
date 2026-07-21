<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Global Supply Chain Intelligence</title>
    
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
        .login-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
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

<div class="login-card p-4 p-sm-5 shadow-lg">
    <div class="text-center mb-4">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
            <i class="bi bi-shield-lock-fill fs-3"></i>
        </div>
        <h4 class="fw-bold text-white mb-1">Admin Portal</h4>
        <p class="text-secondary small">Masuk untuk mengelola platform intelijen</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label text-light small font-medium">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-secondary" style="border-color: #334155;">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0" placeholder="admin@domain.com" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label text-light small font-medium">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-secondary" style="border-color: #334155;">
                    <i class="bi bi-key"></i>
                </span>
                <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label text-secondary small" for="remember">Ingat Saya</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 font-semibold rounded-3 mb-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Dashboard
        </button>

        <div class="text-center">
            <a href="{{ route('dashboard.index') }}" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>