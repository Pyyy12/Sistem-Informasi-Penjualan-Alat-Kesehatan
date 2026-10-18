
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MedikaStore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f0f5f9;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .login-shell {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: min(100%, 980px);
            min-height: 590px;
            overflow: hidden;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 70px rgba(19, 48, 70, .12);
        }

        .login-banner {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 45px;
            color: white;
            background:
                radial-gradient(circle at 90% 10%, rgba(77, 213, 205, .25), transparent 35%),
                linear-gradient(145deg, #102d40, #087f8c);
        }

        .brand { display: flex; align-items: center; gap: 12px; }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: rgba(255,255,255,.15);
            font-size: 27px;
        }

        .banner-content h1 {
            font-size: clamp(30px, 4vw, 44px);
            font-weight: 800;
            line-height: 1.2;
        }

        .banner-content p { color: #d2e8ef; line-height: 1.8; }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            color: #e3f1f4;
        }

        .feature i { color: #67e8d7; font-size: 20px; }

        .login-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
        }

        .login-form h2 { font-size: 30px; font-weight: 800; }

        .form-control {
            min-height: 49px;
            border-radius: 10px;
            border-color: #dce5ec;
        }

        .form-control:focus {
            border-color: #087f8c;
            box-shadow: 0 0 0 3px rgba(8,127,140,.1);
        }

        .btn-login {
            min-height: 49px;
            color: white;
            background: #087f8c;
            border: 0;
            border-radius: 10px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-login:hover {
            color: white;
            background: #066874;
            box-shadow: 0 8px 20px rgba(8,127,140,.22);
            transform: translateY(-1px);
        }

        @media (max-width: 767px) {
            body { padding: 12px; }
            .login-shell { grid-template-columns: 1fr; min-height: auto; }
            .login-banner { padding: 25px; }
            .banner-content { padding-top: 35px; }
            .banner-content h1 { font-size: 29px; }
            .banner-footer { margin-top: 30px; }
            .login-form { padding: 28px 23px; }
        }
    </style>
</head>
<body>
<div class="login-shell">
    <section class="login-banner">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-heart-pulse-fill"></i></div>
            <div>
                <div class="fw-bold fs-4">MedikaStore</div>
                <small class="text-white-50">Healthcare Management</small>
            </div>
        </div>

        <div class="banner-content">
            <span class="badge rounded-pill text-bg-light mb-3">
                Sistem Penjualan Alat Kesehatan
            </span>
            <h1>Kelola bisnis kesehatan dengan lebih mudah.</h1>
            <p class="mt-3">
                Pantau persediaan, kelola transaksi, dan lihat performa
                penjualan dari satu dashboard.
            </p>

            <div class="feature">
                <i class="bi bi-check-circle-fill"></i>
                <span>Monitoring stok produk</span>
            </div>
            <div class="feature">
                <i class="bi bi-check-circle-fill"></i>
                <span>Transaksi dan invoice terintegrasi</span>
            </div>
            <div class="feature">
                <i class="bi bi-check-circle-fill"></i>
                <span>Grafik serta laporan penjualan</span>
            </div>
        </div>

        <div class="banner-footer small text-white-50">
            © {{ date('Y') }} MedikaStore
        </div>
    </section>

    <section class="login-form">
        <div class="mb-4">
            <div class="text-uppercase small fw-bold mb-2" style="color:#087f8c">
                Selamat datang
            </div>
            <h2>Masuk ke akun</h2>
            <p class="text-secondary">
                Masukkan email dan password untuk melanjutkan.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email"
                           class="form-control"
                           placeholder="admin@medikastore.test"
                           value="{{ old('email') }}"
                           autocomplete="username" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password"
                           id="password" class="form-control"
                           placeholder="Masukkan password"
                           autocomplete="current-password" required>
                    <button class="btn btn-outline-secondary" type="button"
                            id="togglePassword" aria-label="Tampilkan password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox"
                       name="remember" value="1" id="remember">
                <label class="form-check-label text-secondary" for="remember">
                    Ingat saya
                </label>
            </div>

            <button class="btn btn-login w-100" type="submit">
                Masuk ke Dashboard <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

        <p class="text-secondary small text-center mt-4 mb-0">
            Akses khusus pengguna yang terdaftar.
        </p>
    </section>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const icon = this.querySelector('i');
    const visible = input.type === 'password';

    input.type = visible ? 'text' : 'password';
    icon.className = visible ? 'bi bi-eye-slash' : 'bi bi-eye';
});
</script>
</body>
</html>
