<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpus SDN 32 Lubuk Alung</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

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
            --line: rgba(30, 42, 34, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--paper);
            color: var(--ink);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-display {
            font-family: 'Baloo 2', sans-serif;
            color: var(--ink);
        }

        /* --- SIDEBAR HOVER AUTOMATIC (DESKTOP) --- */
        /* --- SIDEBAR HOVER AUTOMATIC (DESKTOP) --- */
        @media (min-width: 768px) {

            /* 1. Set lebar default jadi 0px (Bener-bener hilang total) */
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                width: 0px;
                /* Diubah dari 70px ke 0px agar full sembunyi */
                background: #ffffff;
                border-right: 2px dashed var(--line);
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1030;
                overflow-x: hidden;
                white-space: nowrap;
            }

            /* 2. Saat sidebar di-hover, mekar jadi 260px */
            .sidebar:hover {
                width: 260px;
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.08) !important;
            }

            /* 3. Layout Main default (saat sidebar tersembunyi) */
            main {
                margin-left: 0px;
                /* Tanpa jarak kiri */
                width: 100%;
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* 4. PENTING: Geser konten main saat sidebar di-hover */
            .sidebar:hover~main,
            body:has(.sidebar:hover) main {
                margin-left: 260px;
                width: calc(100% - 260px);
            }

            /* Teks disembunyikan saat sidebar kuncup */
            .sidebar-brand span,
            .nav-section-label,
            .nav-link-text,
            .btn-logout-text {
                opacity: 0;
                transition: opacity 0.2s ease;
            }

            /* Teks muncul saat sidebar di-hover */
            .sidebar:hover .sidebar-brand span,
            .sidebar:hover .nav-section-label,
            .sidebar:hover .nav-link-text,
            .sidebar:hover .btn-logout-text {
                opacity: 1;
            }

            .btn-logout {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 8px 0;
            }
        }

        .sidebar-brand {
            padding: 1.5rem 1rem 1rem;
        }

        .sidebar-brand .brand-icon {
            min-width: 40px;
            height: 40px;
            border-radius: 11px;
            background: var(--teal);
            box-shadow: 3px 3px 0 var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .sidebar-brand span.font-display {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.2px;
        }

        .nav-section-label {
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 800;
            letter-spacing: .1em;
            font-size: .68rem;
            padding: 0 1.2rem;
        }

        .nav-link-custom {
            color: var(--ink-soft);
            text-decoration: none;
            padding: 11px 14px;
            display: flex;
            align-items: center;
            margin: 3px 10px;
            border-radius: 11px;
            font-weight: 600;
            font-size: .92rem;
            transition: all 0.2s;
        }

        .nav-link-custom i {
            font-size: 1.25rem;
            min-width: 24px;
            text-align: center;
            margin-right: 14px;
        }

        .nav-link-custom:hover {
            color: var(--teal);
            background: var(--teal-light);
        }

        .nav-link-custom.active {
            background: var(--teal);
            color: var(--paper);
            box-shadow: 0px 10px 18px -6px rgba(12, 107, 104, 0.45);
        }

        .btn-logout {
            border: 1.5px solid var(--berry);
            color: var(--berry);
            background: transparent;
            font-weight: 700;
            border-radius: 999px;
            transition: all .2s ease;
        }

        .btn-logout:hover {
            background: var(--berry);
            color: var(--paper);
        }

        /* Content Area */
        main {
            padding: 2rem;
        }

        .card-custom {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0px 14px 30px -14px rgba(30, 42, 34, 0.18);
            transition: transform 0.2s;
        }

        /* Header bar */
        .page-header-bar {
            border-bottom: 2px dashed var(--line) !important;
        }

        .page-header-bar h4 {
            font-weight: 700;
        }

        .date-pill {
            background: #fff;
            border: 1.5px dashed var(--line);
            border-radius: 999px;
        }

        .date-pill i {
            color: var(--teal);
        }

        /* Navbar Mobile */
        .navbar-mobile {
            background: #fff;
            border-bottom: 2px dashed var(--line);
        }

        .navbar-mobile .navbar-brand {
            color: var(--teal) !important;
            font-family: 'Baloo 2', sans-serif;
        }

        /* Success alert styled like a stamp */
        .alert-success {
            background: rgba(110, 146, 104, 0.12) !important;
            border: 1.5px dashed var(--sage) !important;
            border-left: 5px solid var(--sage) !important;
            color: var(--ink) !important;
            border-radius: 14px !important;
        }

        .alert-success i {
            color: var(--sage);
        }

        /* Datatables Custom */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--teal) !important;
            border-color: var(--teal) !important;
            border-radius: 8px;
            color: var(--paper) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--teal-light) !important;
            border-color: var(--line) !important;
            color: var(--teal) !important;
        }

        /* Animasi */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-animate {
            animation: fadeIn 0.4s ease-out;
        }

        /* Tampilan Mobile */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: -100%;
                width: 250px;
                background: #fff;
                z-index: 1050;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-mobile d-md-none sticky-top py-3">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">Perpus SDN 32</span>
            <button class="btn btn-light shadow-sm" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div class="d-flex">
            <nav id="sidebarMenu" class="sidebar shadow-sm p-0">
                <div class="sidebar-brand">
                    <div class="d-flex align-items-center">
                        <div class="brand-icon me-2">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <span class="fs-5 font-display">SDN 32 LA</span>
                    </div>
                </div>

                <div class="mt-3">
                    <small class="nav-section-label mb-2 d-block">Main Menu</small>

                    <a href="/dashboard" class="nav-link-custom {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>

                    <a href="{{ route('books.index') }}"
                        class="nav-link-custom {{ request()->is('books*') ? 'active' : '' }}">
                        <i class="bi bi-collection-fill"></i>
                        <span class="nav-link-text">Katalog Buku</span>
                    </a>
                    <a href="{{ route('racks.index') }}"
                        class="nav-link-custom {{ request()->is('racks*') ? 'active' : '' }}">
                        <i class="bi bi-bookshelf"></i>
                        <span class="nav-link-text">Data Rak</span>
                    </a>

                    <a href="{{ route('grants.index') }}"
                        class="nav-link-custom {{ request()->is('grants*') ? 'active' : '' }}">
                        <i class="bi bi-gift-fill"></i>
                        <span class="nav-link-text">Modul Hibah</span>
                    </a>

                    <small class="nav-section-label mt-4 mb-2 d-block">Layanan</small>

                    <a href="{{ route('transactions.index') }}"
                        class="nav-link-custom {{ request()->is('transactions') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span class="nav-link-text">Data Peminjam</span>
                    </a>

                    <a href="{{ route('transactions.create') }}"
                        class="nav-link-custom {{ request()->is('transactions/create*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i>
                        <span class="nav-link-text">Transaksi Baru</span>
                    </a>

                    <a href="{{ route('members.index') }}"
                        class="nav-link-custom {{ request()->is('members*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span class="nav-link-text">Anggota</span>
                    </a>

                    <a href="{{ route('fines.index') }}"
                        class="nav-link-custom {{ request()->is('fines*') ? 'active' : '' }}">
                        <i class="bi bi-cash-coin"></i>
                        <span class="nav-link-text">Data Denda</span>
                    </a>

                    <a href="{{ route('reports.index') }}"
                        class="nav-link-custom {{ request()->is('reports*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        <span class="nav-link-text">Laporan</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <div class="mt-5 px-3 pt-3">
                        <a href="#" class="btn btn-logout w-100 fw-bold btn-sm"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="btn-logout-text ms-2">Keluar</span>
                        </a>
                    </div>
                </div>
            </nav>

            <main class="flex-grow-1 px-md-4">
                <div
                    class="page-header-bar d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                        <h4 class="mb-0">E-Library System</h4>
                        <p class="small mb-0" style="color: var(--ink-soft);">Pustaka Digital SDN 32 Lubuk Alung</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="d-flex align-items-center date-pill px-3 py-2">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span class="small fw-bold">{{ date('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm animate__animated animate__fadeIn">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif
                {{-- Error Alert --}}
                @if (session('error'))
                    <div class="alert alert-danger border-0 shadow-sm animate__animated animate__fadeIn mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Validation Errors Alert --}}
                @if ($errors->any())
                    <div class="alert alert-warning border-0 shadow-sm animate__animated animate__fadeIn mb-4">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> <strong>Ada beberapa kesalahan:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="content-animate">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable (Cukup 1 kali + tambahkan 'retrieve: true' biar aman dari bentrok)
            if ($('.datatable-init').length) {
                $('.datatable-init').DataTable({
                    "retrieve": true, // Mencegah error 'Cannot reinitialise DataTable'
                    "responsive": true,
                    "pageLength": 10,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                    }
                });
            }

            // --- SCRIPT AUTO HIDE ALERT (3 DETIK) ---
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 3000);
        });
    </script>
</body>

</html>
