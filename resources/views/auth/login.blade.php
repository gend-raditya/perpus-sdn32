<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Pustaka SDN 32 Lubuk Alung</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --dark: #0f172a;
            --bg-main: #f5f7fb;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Soft Mesh Gradient senada dengan Hero */
        .bg-mesh {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.08) 0, transparent 50%),
                        radial-gradient(at 100% 100%, rgba(124, 58, 237, 0.08) 0, transparent 50%);
            z-index: -1;
        }

        .login-card {
            border: 1px solid rgba(15, 23, 42, 0.05);
            border-radius: 24px;
            background: #ffffff;
            padding: 40px;
            box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.05);
            width: 100%;
            max-width: 450px;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border-color: rgba(15, 23, 42, 0.1);
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary-custom:hover {
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            transform: translateY(-1px);
            color: white;
        }

        .back-link {
            text-decoration: none;
            color: #64748b;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>

<body>
    <div class="bg-mesh"></div>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="login-card" data-aos="fade-up" data-aos-duration="800">

            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-3 p-2 d-inline-flex align-items-center justify-content-center mb-3" style="width: 45px; height: 45px;">
                    <i class="bi bi-book-half fs-4"></i>
                </div>
                <h4 class="fw-bold mb-1"><span class="text-dark">Pustaka</span><span style="color: var(--primary);">SDN32</span></h4>
                <p class="text-muted small">Masuk sebagai Admin / Petugas Perpustakaan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if (session('status'))
                    <div class="alert alert-success rounded-3 small mb-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-muted">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@sdn32.com" style="border-radius: 0 12px 12px 0;">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required placeholder="••••••••" style="border-radius: 0 12px 12px 0;">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 small">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-input-label text-muted" for="remember_me">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                </button>

                <div class="text-center">
                    <a href="/" class="back-link"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true
        });
    </script>
</body>

</html>
