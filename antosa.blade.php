<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Antosa Architect')</title>

    <!-- Tablar core -->

    @vite('resources/js/app.js')

    

    <!-- DataTables & custom style -->

    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.3.2/fc-5.0.4/fh-4.0.3/datatables.min.css"/>

    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Figtree', sans-serif;
        }
        .sidebar {
            width: 240px;
            background: #fff;
            border-right: 1px solid #eee;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
        }
        .sidebar .logo {
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .sidebar .nav-item {
            margin-bottom: 0.5rem;
        }
        .sidebar .nav-item a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.9rem;
            border-radius: 10px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar .nav-item.active a {
            background-color: #e5e7eb;
        }
        .nav-link {
            color: #333;
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: 0.2s;
        }
        .nav-link:hover {
            background-color: #f1f1f1;
        }
        .nav-item.active .nav-link {
            background-color: #000;
            color: #fff;
        }
        .content-wrapper {
            margin-left: 240px;
            padding: 2rem;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary-rounded {
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 0.5rem 1.25rem;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .table th {
            font-size: 0.9rem;
            font-weight: 600;
            color: #555;
            white-space: nowrap;
        }
        .table td {
            font-size: 0.9rem;
            color: #333;
        }
        .pagination {
            justify-content: center;
        }
        .pagination .page-item .page-link {
            border-radius: 8px;
            margin: 0 2px;
            color: #000;
        }
        .pagination .page-item.active .page-link {
            background-color: #000;
            color: #fff;
            border: none;
        }
    </style>

    @stack('css')
</head>
<body class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar">
    <div class="logo mb-4">
        <img src="{{ asset('logo.png') }}" alt="Logo" height="40" class="me-2">
        <strong>ANTOSA ARCHITECT</strong>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="nav-link">
                <i class="ti ti-user"></i> Akun
            </a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-briefcase"></i> SDM</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-users"></i> Recruitment</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-building-bank"></i> Finance</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-megaphone"></i> Marketing</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-user-star"></i> Customer</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-link"></i> Affiliate</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="ti ti-users-group"></i> Partner</a></li>
    </ul>
</div>


    <!-- Content -->
    <div class="flex-grow-1 ms-5">
        <div class="content-wrapper p-4">
            <div class="topbar">
                <h2 class="fw-semibold">@yield('page_title')</h2>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('users.create') }}" class="btn btn-primary-rounded">+ Tambah Akun</a>
                    <div class="user-info">
                        <i class="ti ti-user-circle" style="font-size: 1.5rem;"></i>
                        <div>
                            <div class="fw-semibold">Super Admin</div>
                            <small class="text-muted">superadmin@gmail.com</small>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.2/fc-5.0.4/fh-4.0.3/datatables.min.js"></script>

    @stack('scripts')
</body>
</html>
