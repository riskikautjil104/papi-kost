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
    
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #64748b;
            --success-color: #22c55e;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --sidebar-width: 260px;
            
            /* Light Mode Colors */
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --hover-bg: #f1f5f9;
            --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            --sidebar-text: rgba(255, 255, 255, 0.7);
            --sidebar-active: rgba(79, 70, 229, 0.15);
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Dark Mode Colors */
        [data-theme="dark"] {
            --bg-primary: #000814;
            --bg-secondary: #001F3F;
            --bg-tertiary: #002F5F;
            --text-primary: #FFFFFF;
            --text-secondary: #B8C5D6;
            --text-muted: #8B9DB5;
            --border-color: #1a3a5c;
            --hover-bg: #002F5F;
            --sidebar-bg: linear-gradient(180deg, #000814 0%, #001020 100%);
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Desktop Sidebar */
        .sidebar-wrapper {
            width: var(--sidebar-width);
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            background: var(--sidebar-bg);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease;
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
            background: var(--sidebar-bg);
            z-index: 10;
            transition: background 0.3s ease;
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
            background-color: var(--bg-primary);
            overflow-x: hidden;
            transition: background-color 0.3s ease;
        }
        
        .main-content {
            padding: 2rem;
            max-width: 100%;
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--bg-secondary);
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }
        
        .top-bar h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }
        
        /* Cards */
        .card {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease, color 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .card-body {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), #818cf8) !important;
            color: #fff !important;
            border-radius: 12px;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #22c55e, #4ade80) !important;
        }
        
        .stat-card.danger {
            background: linear-gradient(135deg, #ef4444, #f87171) !important;
        }
        
        .stat-card.info {
            background: linear-gradient(135deg, #06b6d4, #22d3ee) !important;
        }
        
        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            color: #fff !important;
        }
        
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff !important;
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
            background: transparent !important;
        }
        
        .stat-card p,
        .stat-card .text-white-50,
        .stat-card h2,
        .stat-card h3,
        .stat-card h4,
        .stat-card h5,
        .stat-card i {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .stat-card .stat-value {
            color: #fff !important;
        }
        
        /* Tables */
        .table-card {
            background: var(--bg-secondary) !important;
            border-radius: 12px;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }
        
        .table {
            margin-bottom: 0;
            color: var(--text-primary) !important;
            background: transparent !important;
        }
        
        .table thead th {
            background: var(--bg-primary) !important;
            border: none;
            font-weight: 600;
            color: var(--text-primary) !important;
            padding: 1rem;
            font-size: 0.875rem;
            white-space: nowrap;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .table tbody {
            background: var(--bg-secondary) !important;
        }
        
        .table tbody tr {
            background: var(--bg-secondary) !important;
            transition: background-color 0.3s ease;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            background: transparent !important;
            transition: color 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none !important;
        }
        
        .table tbody tr:hover {
            background-color: var(--hover-bg) !important;
        }
        
        .table tbody tr:hover td {
            background: transparent !important;
        }
        
        /* Badges */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff !important;
        }
        
        .badge.bg-success {
            background-color: #22c55e !important;
            color: #fff !important;
        }
        
        .badge.bg-danger {
            background-color: #ef4444 !important;
            color: #fff !important;
        }
        
        .badge.bg-warning {
            background-color: #f59e0b !important;
            color: #1e293b !important;
        }
        
        .badge.bg-info {
            background-color: #06b6d4 !important;
            color: #fff !important;
        }
        
        .badge.bg-primary {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }
        
        .badge.bg-secondary {
            background-color: #64748b !important;
            color: #fff !important;
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
        
        /* Card Header */
        .card-header {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1rem 1.5rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        .card-header h5 {
            color: var(--text-primary) !important;
            margin-bottom: 0;
        }
        
        .card-header i {
            color: var(--text-primary) !important;
        }
        
        /* List Group */
        .list-group-item {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: none;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
        }
        
        .list-group-item:hover {
            background: var(--hover-bg);
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
        
        /* Dark Mode Specific Styles */
        [data-theme="dark"] {
            color-scheme: dark;
        }
        
        [data-theme="dark"] .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        [data-theme="dark"] .card-body {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .bg-white {
            background-color: var(--bg-secondary) !important;
        }
        
        [data-theme="dark"] .bg-light {
            background-color: var(--bg-tertiary) !important;
        }
        
        [data-theme="dark"] .border {
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .border-top {
            border-top-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .card-footer {
            background-color: var(--bg-secondary) !important;
            border-top-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .card-footer.bg-white {
            background-color: var(--bg-secondary) !important;
        }
        
        [data-theme="dark"] .table-borderless td {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .receipt-preview-box {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .receipt-desc-box {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .table-responsive {
            background: transparent;
        }
        
        [data-theme="dark"] .table thead th {
            background: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .table tbody td {
            color: var(--text-primary) !important;
            border-bottom-color: var(--border-color) !important;
        }
        
        [data-theme="dark"] .table tbody tr:hover {
            background-color: var(--hover-bg) !important;
        }
        
        [data-theme="dark"] strong,
        [data-theme="dark"] .fw-bold {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] small,
        [data-theme="dark"] .small {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }
        
        [data-theme="dark"] .page-header h1,
        [data-theme="dark"] .page-header h2,
        [data-theme="dark"] .page-header h3 {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .breadcrumb {
            background: transparent;
        }
        
        [data-theme="dark"] .breadcrumb-item,
        [data-theme="dark"] .breadcrumb-item a {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .breadcrumb-item.active {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        [data-theme="dark"] .btn-outline-primary:hover {
            background: var(--primary-color);
            color: #fff;
        }
        
        [data-theme="dark"] .btn-outline-info {
            color: var(--info-color);
            border-color: var(--info-color);
        }
        
        [data-theme="dark"] .btn-outline-info:hover {
            background: var(--info-color);
            color: #fff;
        }
        
        [data-theme="dark"] .btn-outline-danger {
            color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        [data-theme="dark"] .btn-outline-danger:hover {
            background: var(--danger-color);
            color: #fff;
        }
        
        [data-theme="dark"] .btn-outline-success {
            color: var(--success-color);
            border-color: var(--success-color);
        }
        
        [data-theme="dark"] .btn-outline-success:hover {
            background: var(--success-color);
            color: #fff;
        }
        
        [data-theme="dark"] .modal-content {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .modal-header {
            background: var(--bg-primary);
            border-bottom-color: var(--border-color);
        }
        
        [data-theme="dark"] .modal-footer {
            border-top-color: var(--border-color);
        }
        
        [data-theme="dark"] .btn-close {
            filter: invert(1);
        }
        
        [data-theme="dark"] .dropdown-menu {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }
        
        [data-theme="dark"] .dropdown-item {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .dropdown-item:hover {
            background: var(--hover-bg);
        }
        
        [data-theme="dark"] .btn-outline-secondary {
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .btn-outline-secondary:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: var(--bg-primary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background: var(--bg-primary);
            color: var(--text-primary);
            border-color: var(--primary-color);
        }
        
        [data-theme="dark"] .alert {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
        
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6 {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] p,
        [data-theme="dark"] span,
        [data-theme="dark"] label,
        [data-theme="dark"] .text-muted {
            color: var(--text-secondary) !important;
        }
        
        /* Dark Mode Toggle Button */
        .theme-toggle-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .theme-toggle-btn:hover {
            background: var(--hover-bg);
        }
        
        .theme-toggle-btn i {
            font-size: 1rem;
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
<body class="admin-dashboard">
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
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.annual') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.receipts.*') ? 'active' : '' }}" href="{{ route('admin.receipts.index') }}">
                        <i class="fas fa-receipt"></i>
                        <span>Kwitansi</span>
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
                    
                    <div class="d-flex align-items-center gap-3">
                        <!-- Dark Mode Toggle -->
                        <button class="theme-toggle-btn" id="themeToggle" aria-label="Toggle dark mode">
                            <i class="fas fa-moon" id="themeIcon"></i>
                            <span id="themeText">Dark</span>
                        </button>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->usersExtended?->profile_photo_url)
                                    <img src="{{ Auth::user()->usersExtended->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                @else
                                    <div class="avatar d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
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
            <li><a class="dropdown-item" href="{{ route('admin.reports.annual') }}"><i class="fas fa-chart-bar me-2"></i>Laporan</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.receipts.index') }}"><i class="fas fa-receipt me-2"></i>Kwitansi</a></li>
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
        
        // Dark Mode Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const htmlElement = document.documentElement;
        
        // Check for saved theme preference or default to 'light'
        const currentTheme = localStorage.getItem('admin-theme') || 'light';
        
        // Apply saved theme on page load
        if (currentTheme === 'dark') {
            htmlElement.setAttribute('data-theme', 'dark');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
            themeText.textContent = 'Light';
        } else {
            htmlElement.setAttribute('data-theme', 'light');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
            themeText.textContent = 'Dark';
        }
        
        // Toggle theme on button click
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            
            if (currentTheme === 'light') {
                htmlElement.setAttribute('data-theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                themeText.textContent = 'Light';
                localStorage.setItem('admin-theme', 'dark');
            } else {
                htmlElement.setAttribute('data-theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                themeText.textContent = 'Dark';
                localStorage.setItem('admin-theme', 'light');
            }
        });
    </script>
    
    <!-- Stack for page scripts -->
    @stack('scripts')
</body>
</html>
