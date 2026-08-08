<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Pustaka SDN 32 Lubuk Alung</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --paper: #FAF3E4;
            --paper-alt: #F0E7D3;
            --ink: #1E2A22;
            --ink-soft: #52604F;
            --teal: #0C6B68;
            --teal-dark: #083F3E;
            --teal-light: rgba(12, 107, 104, 0.08);
            --gold: #EFA53B;
            --berry: #BD3F5C;
            --sage: #6E9268;
            --line: rgba(30, 42, 34, 0.14);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--paper);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Baloo 2', sans-serif;
        }

        /* Background bertekstur dot senada dengan halaman lain + mesh warna brand */
        .bg-mesh {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: radial-gradient(at 0% 0%, rgba(12, 107, 104, 0.10) 0, transparent 50%),
                        radial-gradient(at 100% 100%, rgba(239, 165, 59, 0.12) 0, transparent 50%),
                        radial-gradient(var(--line) 1px, transparent 1px);
            background-size: auto, auto, 22px 22px;
            z-index: -1;
        }

        .login-card {
            border: 2px dashed var(--line);
            border-radius: 22px;
            background: #ffffff;
            padding: 42px 40px;
            box-shadow: 0 22px 45px -18px rgba(30, 42, 34, 0.25);
            width: 100%;
            max-width: 440px;
            position: relative;
        }

        .card-corner-stamp {
            position: absolute;
            top: -18px;
            right: 28px;
            background: var(--gold);
            color: var(--teal-dark);
            font-weight: 700;
            font-size: .68rem;
            padding: 5px 14px;
            border-radius: 4px;
            transform: rotate(3deg);
            box-shadow: 1px 3px 0 rgba(30,42,34,0.15);
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: var(--teal);
            box-shadow: 3px 3px 0 var(--gold);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .brand-title { font-weight: 700; }

        .form-label {
            color: var(--ink-soft) !important;
            font-weight: 700 !important;
        }

        .input-group-text {
            background: var(--paper-alt) !important;
            border: 1.5px solid var(--line) !important;
            color: var(--teal) !important;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1.5px solid var(--line);
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 4px var(--teal-light);
        }

        .form-check-input:checked {
            background-color: var(--teal);
            border-color: var(--teal);
        }

        .btn-primary-custom {
            background: var(--teal);
            color: var(--paper);
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 3px 3px 0 var(--teal-dark);
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-primary-custom:hover {
            box-shadow: 4px 4px 0 var(--teal-dark);
            transform: translate(-1px, -1px);
            color: var(--paper);
        }

        .back-link {
            text-decoration: none;
            color: var(--ink-soft);
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--teal); }

        .alert-success {
            background: rgba(110, 146, 104, 0.12) !important;
            border: 1.5px dashed var(--sage) !important;
            border-left: 5px solid var(--sage) !important;
            color: var(--ink) !important;
        }
    </style>
</head>

<body>
    <div class="bg-mesh"></div>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="login-card" data-aos="fade-up" data-aos-duration="800">
            <span class="card-corner-stamp">✦ Kartu Akses</span>

            <div class="text-center mb-4">
                <div class="brand-icon mb-3">
                    <i class="bi bi-book-half fs-4"></i>
                </div>
                <h4 class="brand-title mb-1"><span style="color: var(--ink);">Pustaka</span><span
                        style="color: var(--teal);">SDN32</span></h4>
                <p class="small" style="color: var(--ink-soft);">Masuk sebagai Admin / Petugas Perpustakaan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if (session('status'))
                    <div class="alert alert-success rounded-3 small mb-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="email" class="form-label small">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0" style="border-radius: 10px 0 0 10px;"><i
                                class="bi bi-envelope"></i></span>
                        <input id="email" type="email"
                            class="form-control border-start-0 @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autofocus placeholder="admin@sdn32.com"
                            style="border-radius: 0 10px 10px 0;">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0" style="border-radius: 10px 0 0 10px;"><i
                                class="bi bi-lock"></i></span>
                        <input id="password" type="password"
                            class="form-control border-start-0 @error('password') is-invalid @enderror" name="password"
                            required placeholder="••••••••" style="border-radius: 0 10px 10px 0;">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 small">
                    {{-- <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-input-label" style="color: var(--ink-soft);" for="remember_me">
                            Ingat Saya
                        </label>
                    </div> --}}
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
