<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literasi SDN 32 Lubuk Alung</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            /* ---- Palet "Kartu Perpustakaan" ---- */
            --paper: #FAF3E4;
            --paper-alt: #F0E7D3;
            --ink: #1E2A22;
            --ink-soft: #52604F;
            --teal: #0C6B68;
            --teal-dark: #083F3E;
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
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Baloo 2', sans-serif;
            color: var(--ink);
        }

        a { text-decoration: none; }

        /* subtle dotted "paper" texture on the whole page */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: radial-gradient(var(--line) 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: .35;
            pointer-events: none;
            z-index: 0;
        }

        .navbar, header, section, footer { position: relative; z-index: 1; }

        /* ---------- NAVBAR ---------- */
        .navbar {
            background: rgba(250, 243, 228, 0.85) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 2px dashed var(--line);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--teal);
            box-shadow: 3px 3px 0 var(--gold);
        }

        .navbar-brand span.font-display {
            font-weight: 700;
            font-size: 1.15rem;
        }

        .nav-link-custom {
            font-weight: 600;
            color: var(--ink-soft) !important;
        }

        .btn-admin {
            border: 1.5px solid var(--ink);
            color: var(--ink);
            border-radius: 999px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: .875rem;
            transition: all .25s ease;
        }

        .btn-admin:hover { background: var(--ink); color: var(--paper); }

        /* ---------- HERO ---------- */
        .hero-section {
            padding: 170px 0 100px;
        }

        .tag-strip {
            display: inline-block;
            background: var(--gold);
            color: var(--teal-dark);
            font-weight: 700;
            font-size: .8rem;
            padding: 7px 18px;
            border-radius: 4px;
            transform: rotate(-2deg);
            box-shadow: 2px 3px 0 rgba(30,42,34,0.15);
        }

        .hero-title {
            font-weight: 800;
            letter-spacing: -0.01em;
            line-height: 1.1;
            font-size: 3.2rem;
        }

        .hero-underline {
            position: relative;
            color: var(--berry);
            white-space: nowrap;
        }

        .hero-underline svg {
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 100%;
            height: 14px;
        }

        .btn-primary-custom {
            background: var(--teal);
            color: var(--paper);
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 4px 4px 0 var(--teal-dark);
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            color: var(--paper);
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--teal-dark);
        }

        .btn-secondary-custom {
            background: transparent;
            color: var(--ink);
            border: 2px solid var(--ink);
            padding: 12px 26px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--ink);
            color: var(--paper);
        }

        .hero-photo-wrap {
            position: relative;
            display: inline-block;
            padding: 14px;
            background: #fff;
            border-radius: 6px;
            transform: rotate(2deg);
            box-shadow: 0 18px 40px -12px rgba(30,42,34,0.35);
        }

        .hero-photo-wrap img {
            border-radius: 3px;
            max-height: 420px;
            object-fit: cover;
            width: 100%;
        }

        .stamp-badge {
            position: absolute;
            width: 96px;
            height: 96px;
            background: var(--berry);
            color: var(--paper);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: .7rem;
            text-align: center;
            line-height: 1.2;
            border: 3px dashed rgba(250,243,228,0.6);
            bottom: -20px;
            left: -20px;
            transform: rotate(-8deg);
            box-shadow: 0 10px 20px -6px rgba(189,63,92,0.5);
        }

        .stamp-badge i { font-size: 1.4rem; margin-bottom: 2px; }

        /* ---------- STAT CARDS ---------- */
        .stat-card {
            border: 2px dashed var(--line);
            border-radius: 18px;
            background: #fff;
            padding: 30px 26px;
            position: relative;
        }

        .stat-card .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 18px;
            color: #fff;
        }

        .stat-card h2 {
            font-weight: 800;
            font-size: 2.4rem;
            margin-bottom: 2px;
        }

        .stat-card p {
            font-size: .78rem;
            letter-spacing: .06em;
        }

        /* ---------- DONATUR SECTION ---------- */
        .section-donatur {
            background: var(--paper-alt);
            padding: 90px 0;
            border-top: 2px dashed var(--line);
            border-bottom: 2px dashed var(--line);
        }

        .eyebrow {
            color: var(--berry);
            font-weight: 800;
            font-size: .78rem;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .donatur-card {
            border: 1px solid var(--line);
            border-left: 5px solid var(--teal);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 6px 16px -8px rgba(30,42,34,0.12);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .donatur-card:nth-of-type(3n+2) { border-left-color: var(--gold); }
        .donatur-card:nth-of-type(3n)   { border-left-color: var(--berry); }

        .donatur-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 24px -10px rgba(30,42,34,0.18);
        }

        .avatar-circle {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: var(--teal);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .verified-stamp {
            border: 1px dashed var(--gold);
            color: var(--teal-dark);
            background: rgba(239,165,59,0.12);
            font-weight: 700;
        }

        /* ---------- CATALOG ---------- */
        .search-wrap {
            background: #fff;
            border: 2px dashed var(--line);
            border-radius: 16px;
        }

        .search-wrap input:focus { box-shadow: none; }

        .book-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fff;
            padding: 14px;
            height: 100%;
            transition: transform .3s ease, box-shadow .3s ease;
            position: relative;
        }

        .book-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 26px -10px rgba(30,42,34,0.18);
        }

        .book-tape {
            position: absolute;
            top: -8px;
            left: 16px;
            background: var(--gold);
            color: var(--teal-dark);
            font-weight: 700;
            font-size: .68rem;
            padding: 4px 12px;
            border-radius: 3px;
            transform: rotate(-3deg);
            box-shadow: 1px 2px 0 rgba(30,42,34,0.15);
            z-index: 2;
        }

        .book-cover {
            height: 180px;
            border-radius: 8px;
            background: linear-gradient(135deg, #eef0e6, #dfe3d3);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: 10px;
        }

        .book-cover img { width: 100%; height: 100%; object-fit: cover; }

        .book-title { font-family: 'Baloo 2', sans-serif; font-weight: 600; }

        .stock-pill {
            font-size: .68rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 4px 10px;
        }

        .stock-pill.tersedia { background: rgba(110,146,104,0.15); color: var(--sage); }
        .stock-pill.dipinjam { background: rgba(189,63,92,0.12); color: var(--berry); }

        /* ---------- FOOTER ---------- */
        .footer {
            background: var(--teal-dark);
            color: rgba(250,243,228,0.65);
            padding: 70px 0 34px;
            border-top: 4px dashed var(--gold);
        }

        .footer h5 { color: var(--paper); }

        .footer a {
            color: rgba(250,243,228,0.65) !important;
            transition: color .2s;
        }

        .footer a:hover { color: var(--gold) !important; }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 767px) {
            .hero-title { font-size: 2.2rem; }
            .hero-section { padding: 140px 0 70px; }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <div class="brand-icon text-white me-2 d-flex align-items-center justify-content-center">
                    <i class="bi bi-book-half fs-5"></i>
                </div>
                <span class="font-display"><span style="color: var(--ink);">Pustaka</span><span
                        style="color: var(--teal);">SDN32</span></span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="#katalog" class="nav-link nav-link-custom d-none d-md-block me-2">Katalog</a>
                <a href="{{ route('login') }}" class="btn-admin">Admin Login</a>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-center text-lg-start" data-aos="fade-right">
                    <span class="tag-strip mb-4 d-inline-block">✦ #GerakanLiterasiLubukAlung</span>
                    <h1 class="hero-title mb-4">Ubah Dunia Mereka Lewat<br>
                        <span class="hero-underline">Satu Buku
                            <svg viewBox="0 0 200 14" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10 Q 50 2, 100 8 T 198 6" stroke="var(--berry)" stroke-width="4"
                                    fill="none" stroke-linecap="round" />
                            </svg>
                        </span>
                    </h1>
                    <p class="mb-5" style="font-size: 1.1rem; line-height: 1.7; color: var(--ink-soft);">Setiap
                        halaman yang Anda hibahkan adalah investasi besar bagi masa depan siswa SDN 32 Lubuk
                        Alung.</p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-3 flex-wrap">
                        <a href="{{ route('public.grants.create') }}" class="btn btn-primary-custom">
                            <i class="bi bi-heart-fill me-2"></i>Mulai Hibah Buku
                        </a>
                        <a href="#katalog" class="btn btn-secondary-custom">
                            Jelajahi Katalog
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center" data-aos="fade-left">
                    <div class="hero-photo-wrap">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=600"
                            alt="Perpustakaan">
                        <div class="stamp-badge">
                            <i class="bi bi-mortarboard-fill"></i>
                            Untuk<br>Siswa Kita
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5 position-relative">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card d-flex align-items-start gap-4">
                        <div>
                            <div class="icon-box" style="background: var(--teal);">
                                <i class="bi bi-bookshelf"></i>
                            </div>
                            <h2>500+</h2>
                            <p class="mb-0 text-uppercase" style="color: var(--ink-soft);">Total Koleksi Buku</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card d-flex align-items-start gap-4">
                        <div>
                            <div class="icon-box" style="background: var(--sage);">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h2>120+</h2>
                            <p class="mb-0 text-uppercase" style="color: var(--ink-soft);">Buku Telah Dihibahkan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card d-flex align-items-start gap-4">
                        <div>
                            <div class="icon-box" style="background: var(--gold);">
                                <i class="bi bi-people"></i>
                            </div>
                            <h2>80+</h2>
                            <p class="mb-0 text-uppercase" style="color: var(--ink-soft);">Siswa Pembaca Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-donatur">
        <div class="container">
            <div class="row align-items-end mb-5" data-aos="fade-up">
                <div class="col-md-8">
                    <span class="eyebrow d-block mb-2">Daftar Apresiasi</span>
                    <h2 class="fw-bold mb-3" style="font-size: 2.2rem;">Pahlawan Literasi Terkini</h2>
                    <p class="mb-0" style="color: var(--ink-soft);">Terima kasih yang sebesar-besarnya kepada para
                        donatur yang telah bergabung minggu ini.</p>
                </div>
                <div class="col-md-4 text-md-end d-none d-md-block">
                    <a href="{{ route('public.grants.create') }}" class="btn btn-primary-custom px-4 py-3">Ikut
                        Berdonasi</a>
                </div>
            </div>

            <div class="row g-4">
                {{-- AMBIL DATA DARI CONTROLLER --}}
                @forelse($recentGrants as $grant)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="donatur-card p-4 h-100 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-circle flex-shrink-0">
                                    {{ strtoupper(substr($grant->nama_donatur, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="font-family:'Baloo 2',sans-serif;">
                                        {{ $grant->nama_donatur }}</h6>
                                    <p class="small mb-0" style="color: var(--ink-soft);">Menghibahkan: <span
                                            class="fw-semibold" style="color: var(--teal);">{{ $grant->judul_buku }}</span></p>
                                </div>
                            </div>
                            <div class="pt-3 mt-3 d-flex align-items-center justify-content-between"
                                style="border-top: 1px dashed var(--line);">
                                <span style="font-size: 0.75rem; color: var(--ink-soft);">
                                    <i class="bi bi-clock me-1"></i>{{ $grant->created_at->diffForHumans() }}
                                </span>
                                <span class="verified-stamp badge rounded-pill px-2 py-1"
                                    style="font-size: 0.65rem;">Verified</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3" style="color: var(--ink-soft);"><i class="bi bi-inbox fs-1 opacity-40"></i></div>
                        <p class="fs-6" style="color: var(--ink-soft);">Belum ada data hibah terbaru minggu ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <section id="katalog" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="eyebrow d-block mb-2">E-Katalog</span>
                <h2 class="fw-bold mb-3" style="font-size: 2.2rem;">Koleksi Buku Perpustakaan</h2>
                <p class="max-w-md mx-auto" style="color: var(--ink-soft);">Cari dan cek ketersediaan buku cerita,
                    pelajaran, atau komik favoritmu dari rumah!</p>
            </div>

            <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('welcome') }}#katalog" method="GET">
                        <div class="input-group search-wrap p-2">
                            <span class="input-group-text bg-transparent border-0" style="color: var(--ink-soft);"><i
                                    class="bi bi-search fs-5"></i></span>
                            <input type="text" name="search"
                                class="form-control border-0 bg-transparent py-2 shadow-none"
                                placeholder="Ketik judul buku, penulis, atau kategori..."
                                value="{{ request('search') }}">
                            @if (request('search'))
                                <a href="{{ route('welcome') }}#katalog"
                                    class="btn btn-light rounded-3 me-2 d-flex align-items-center"><i
                                        class="bi bi-x-lg"></i></a>
                            @endif
                            <button type="submit" class="btn btn-primary-custom py-2 px-4"
                                style="border-radius: 10px;">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 mb-2" data-aos="fade-up">
                    @if (request('search'))
                        <p class="small" style="color: var(--ink-soft);">Menampilkan hasil pencarian untuk: <strong
                                style="color: var(--berry);">"{{ request('search') }}"</strong> (Ditemukan
                            {{ $books->count() }} buku)</p>
                    @else
                        <p class="small" style="color: var(--ink-soft);"><i class="bi bi-info-circle me-1"></i>
                            Menampilkan <strong style="color: var(--ink);">8 Buku Terbaru</strong>. Gunakan kolom di
                            atas untuk mencari koleksi buku lainnya.</p>
                    @endif
                </div>

                @forelse($books as $book)
                    <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up">
                        <div class="book-card">
                            <span class="book-tape">{{ $book->kategori ?? 'Umum' }}</span>

                            <div class="book-cover">
                                @if ($book->foto)
                                    <img src="{{ asset('storage/' . $book->foto) }}"
                                        alt="Sampul {{ $book->judul }}">
                                @else
                                    <div class="text-center" style="color: var(--ink-soft);">
                                        <i class="bi bi-book fs-1 opacity-25 d-block mb-1"></i>
                                        <span style="font-size: 11px;" class="opacity-50">No Image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex flex-column justify-content-between flex-grow-1 pt-3">
                                <div>
                                    <h6 class="book-title mb-1 text-truncate-2" title="{{ $book->judul }}">
                                        {{ $book->judul }}</h6>
                                    <p class="small mb-2" style="color: var(--ink-soft);"><i
                                            class="bi bi-person me-1"></i>{{ $book->penulis ?? 'Anonim' }}</p>
                                </div>

                                <div class="pt-2 mt-2 d-flex align-items-center justify-content-between"
                                    style="border-top: 1px dashed var(--line);">
                                    <span class="small" style="color: var(--ink-soft);">Stok:
                                        <strong style="color: var(--ink);">{{ $book->stok_tersedia ?? 0 }}</strong>
                                    </span>

                                    @if (($book->stok_tersedia ?? 0) > 0)
                                        <span class="stock-pill tersedia">Tersedia</span>
                                    @else
                                        <span class="stock-pill dipinjam">Dipinjam</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3" style="color: var(--ink-soft);"><i class="bi bi-journal-x fs-1 opacity-30"></i></div>
                        <p class="fs-6" style="color: var(--ink-soft);">Buku yang dicari tidak ditemukan, coba cari
                            kata kunci lain ya dek.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row g-4 justify-content-between align-items-center text-center text-md-start">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-2">Literasi SDN 32 Lubuk Alung</h5>
                    <p class="small mb-0 opacity-75">Project Tugas Akhir Mahasiswa Teknologi Informasi Politeknik
                        Negeri Padang</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3 mb-3">
                        <a href="#" class="fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="opacity: .15;">
            <div class="text-center">
                <p class="mb-0 small opacity-50">&copy; 2026 Perpustakaan Digital SDN 32 Lubuk Alung. All rights
                    reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 900,
            once: true
        });
    </script>
</body>

</html>
