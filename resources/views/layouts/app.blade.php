<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpus SDN 32</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }

        /* Sidebar Styling */
        .sidebar {
            background: #212529;
            min-height: 100vh;
            color: white;
            transition: all 0.3s;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #343a40;
            color: white;
            border-left: 4px solid #0d6efd;
        }

        /* Responsif buat Mobile */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                width: 100%;
                position: relative;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark d-md-none shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Perpus SDN 32</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow-sm p-0">
                <div class="py-4 text-center d-none d-md-block border-bottom border-secondary mb-3">
                    <h5 class="fw-bold">Perpus SDN 32</h5>
                </div>

                <div class="nav flex-column">
                    <a href="/dashboard" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('books.index') }}" class="{{ request()->is('books*') ? 'active' : '' }}">
                        <i class="bi bi-book me-2"></i> Katalog Buku
                    </a>
                    <a href="{{ route('grants.index') }}" class="{{ request()->is('grants*') ? 'active' : '' }}">
                        <i class="bi bi-heart me-2"></i> Modul Hibah
                    </a>
                    <a href="{{ route('transactions.create') }}" class="{{ request()->is('transactions*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right me-2"></i> Transaksi
                    </a>
                    <a href="{{ route('members.index') }}" class="{{ request()->is('members*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Anggota
                    </a>
                    <hr class="mx-3 text-secondary">
                    <a href="#" class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
