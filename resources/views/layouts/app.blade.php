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

        /* Sidebar */
        .sidebar {
            background: #ffffff;
            min-height: 100vh;
            border-right: 2px dashed var(--line);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem 1rem;
        }

        .sidebar-brand .brand-icon {
            width: 40px;
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
            padding: 0 1.5rem;
        }

        .nav-link-custom {
            color: var(--ink-soft);
            text-decoration: none;
            padding: 11px 22px;
            display: flex;
            align-items: center;
            margin: 3px 14px;
            border-radius: 11px;
            font-weight: 600;
            font-size: .92rem;
            transition: all 0.2s;
        }

        .nav-link-custom i {
            font-size: 1.15rem;
            margin-right: 12px;
            width: 20px;
            text-align: center;
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

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                width: 250px;
            }

            .sidebar.show {
                left: 0;
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

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow-sm p-0">
                <div class="sidebar-brand d-none d-md-block">
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
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('books.index') }}"
                        class="nav-link-custom {{ request()->is('books*') ? 'active' : '' }}">
                        <i class="bi bi-collection-fill"></i> Katalog Buku
                    </a>
                    <a href="{{ route('grants.index') }}"
                        class="nav-link-custom {{ request()->is('grants*') ? 'active' : '' }}">
                        <i class="bi bi-gift-fill"></i> Modul Hibah
                    </a>

                    <small class="nav-section-label mt-4 mb-2 d-block">Layanan</small>
                    <a href="{{ route('transactions.create') }}"
                        class="nav-link-custom {{ request()->is('transactions*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> Transaksi
                    </a>
                    <a href="{{ route('members.index') }}"
                        class="nav-link-custom {{ request()->is('members*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill"></i> Anggota
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <div class="mt-5 px-4 pt-5">
                        <a href="#" class="btn btn-logout w-100 fw-bold btn-sm"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </a>
                    </div>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
            // Ganti dari '#bookTable' menjadi class '.datatable-init' yang lebih umum
            if ($('.datatable-init').length) {
                $('.datatable-init').DataTable({
                    "responsive": true,
                    "pageLength": 10,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                    }
                });
            }
        });
    </script>
</body>

</html>
