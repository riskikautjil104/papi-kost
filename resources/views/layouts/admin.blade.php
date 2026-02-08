<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - D-TECT Kontrakan</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/dtect-style.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #64748b;
            --success-color: #22c55e;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --sidebar-width: 260px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        
        /* Desktop Sidebar */
        .sidebar-wrapper {
            width: var(--sidebar-width);
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index: 10;
        }
        
        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .sidebar-brand small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            display: block;
            margin-top: 0.25rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
            font-size: 0.95rem;
        }
        
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(79, 70, 229, 0.15);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        .sidebar-nav .nav-link .badge {
            margin-left: auto;
        }
        
        /* Mobile Bottom Navigation - Hidden by default */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 0.5rem 0;
        }
        
        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            text-decoration: none;
            color: #64748b;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .bottom-nav-item i {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }
        
        .bottom-nav-item span {
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .bottom-nav-item.active {
            color: var(--primary-color);
        }
        
        .bottom-nav-item .badge {
            position: absolute;
            top: 0.25rem;
            right: 1rem;
            min-width: 18px;
            height: 18px;
            padding: 0 0.35rem;
            border-radius: 50%;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary-color);
            color: #fff;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            cursor: pointer;
        }
        
        .mobile-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .mobile-sidebar-overlay.show {
            display: block;
        }
        
        /* Main Content */
        .main-wrapper {
            flex: 1;
            min-height: 100vh;
            background-color: #f1f5f9;
            overflow-x: hidden;
        }
        
        .main-content {
            padding: 2rem;
            max-width: 100%;
        }
        
        /* Top Bar */
        .top-bar {
            background: #fff;
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .top-bar h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            color: #fff;
            border-radius: 12px;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #22c55e, #4ade80);
        }
        
        .stat-card.danger {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }
        
        .stat-card.info {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
        }
        
        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
        
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        /* Tables */
        .table-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f1f5f9;
            border: none;
            font-weight: 600;
            color: #475569;
            padding: 1rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Badges */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        /* Buttons */
        .btn-admin {
            background: var(--primary-color);
            color: #fff;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            background: #4338ca;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        /* Chart */
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* List Group */
        .list-group-item {
            border: none;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
        }
        
        /* Alert */
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        /* Dropdown */
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
            margin-top: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f1f5f9;
        }
        
        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            :root {
                --sidebar-width: 220px;
            }
            
            .sidebar-brand h4 {
                font-size: 1.25rem;
            }
            
            .main-content {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            /* Hide desktop sidebar */
            .sidebar-wrapper {
                position: fixed;
                left: -100%;
                top: 0;
                width: 280px;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            
            .sidebar-wrapper.show {
                left: 0;
            }
            
            /* Show mobile menu toggle */
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Show bottom navigation */
            .bottom-nav {
                display: flex;
            }
            
            /* Adjust main content */
            .main-content {
                padding: 1rem;
                padding-bottom: 80px; /* Space for bottom nav */
            }
            
            .top-bar {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start !important;
                margin-top: 60px; /* Space for menu toggle */
            }
            
            .top-bar h1 {
                font-size: 1.25rem;
            }
            
            .stat-card .stat-value {
                font-size: 1.5rem;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            /* Hide some sidebar items from bottom nav */
            .sidebar-nav .nav-item.desktop-only {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .bottom-nav-item span {
                font-size: 0.65rem;
            }
            
            .bottom-nav-item i {
                font-size: 1.1rem;
            }
            
            .stat-card .stat-value {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar-wrapper" id="sidebar">
            <div class="sidebar-brand">
                <h4><i class="fas fa-home me-2"></i>D-TECT</h4>
                <small>Kontrakan Management</small>
            </div>
            
            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Pembayaran</span>
                        @php $pendingCount = isset($pendingPayments) ? (is_int($pendingPayments) ? $pendingPayments : $pendingPayments->total()) : 0; @endphp
                        @if($pendingCount > 0)
                        <span class="badge bg-danger">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.finance.*') && !request()->routeIs('admin.finance.report') ? 'active' : '' }}" href="{{ route('admin.finance.index') }}">
                        <i class="fas fa-wallet"></i>
                        <span>Keuangan</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.finance.report') ? 'active' : '' }}" href="{{ route('admin.finance.report') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                
                <li class="nav-item mt-4 desktop-only">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Lihat Website</span>
                    </a>
                </li>
                
                <li class="nav-item desktop-only">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start bg-transparent border-0">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        
        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <main class="main-content">
                <!-- Top Bar -->
                <div class="top-bar d-flex justify-content-between align-items-center">
                    <h1>@yield('title', 'Admin Dashboard')</h1>
                    
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->name ?? 'Admin' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>User</span>
        </a>
        
        <a href="{{ route('admin.payments.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Pembayaran</span>
            @php $pendingCountMobile = isset($pendingPayments) ? (is_int($pendingPayments) ? $pendingPayments : $pendingPayments->total()) : 0; @endphp
            @if($pendingCountMobile > 0)
            <span class="badge bg-danger">{{ $pendingCountMobile }}</span>
            @endif
        </a>
        
        <a href="{{ route('admin.finance.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>Keuangan</span>
        </a>
        
        <button class="bottom-nav-item" id="mobileMoreMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
            <span>Lainnya</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileMoreMenu">
            <li><a class="dropdown-item" href="{{ route('admin.finance.report') }}"><i class="fas fa-chart-bar me-2"></i>Laporan</a></li>
            <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="fas fa-external-link-alt me-2"></i>Lihat Website</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu Script -->
    <script>
        // Mobile sidebar toggle
        const menuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Close sidebar when clicking a link on mobile
        if (window.innerWidth <= 768) {
            const sidebarLinks = sidebar.querySelectorAll('.nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            });
        }
    </script>
    
    <!-- Stack for page scripts -->
    @stack('scripts')
</body>
</html>
