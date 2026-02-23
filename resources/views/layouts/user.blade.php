<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard') - D-TECT Kontrakan</title>
    
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
            --text-muted: #7B8FA3;
            --border-color: #003A6B;
            --sidebar-bg: linear-gradient(180deg, #001F3F 0%, #000814 100%);
            --sidebar-text: rgba(255, 255, 255, 0.85);
            --sidebar-active: rgba(0, 168, 255, 0.15);
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
            --hover-shadow: 0 8px 25px rgba(0, 168, 255, 0.2);
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
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
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
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            margin-bottom: 0;
            min-width: 600px;
        }
        
        .table thead th {
            background: #f1f5f9;
            border: none;
            font-weight: 600;
            color: #475569;
            padding: 0.75rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 0.75rem;
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
        
        /* Dark Mode Additional Styles */
        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            transition: all 0.3s ease;
        }
        
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
        }
        
        .bottom-nav {
            background: var(--bg-secondary);
            transition: background 0.3s ease;
        }
        
        .bottom-nav-item {
            color: var(--text-secondary);
        }
        
        .main-wrapper {
            background-color: var(--bg-primary);
            transition: background-color 0.3s ease;
        }
        
        .card {
            background: var(--bg-secondary);
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
        }
        
        .card:hover {
            box-shadow: var(--hover-shadow);
        }
        
        .card-header {
            background: var(--bg-tertiary) !important;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        .table-card {
            background: var(--bg-secondary);
            transition: background 0.3s ease;
        }
        
        .table thead th {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        .table tbody td {
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: border-color 0.3s ease, color 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: var(--bg-tertiary);
        }
        
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .form-control, .form-select {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
            transition: background 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--text-primary);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .dropdown-menu {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            box-shadow: var(--card-shadow);
            transition: background 0.3s ease, border-color 0.3s ease;
        }
        
        .dropdown-item {
            color: var(--text-primary);
        }
        
        .dropdown-item:hover {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }
        
        .breadcrumb-item,
        .breadcrumb-item a {
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item.active {
            color: var(--text-primary);
        }
        
        .page-header h1 {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .bg-light {
            background-color: var(--bg-tertiary) !important;
        }
        
        [data-theme="dark"] .bg-white {
            background-color: var(--bg-secondary) !important;
        }
        
        [data-theme="dark"] .text-dark {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .border {
            border-color: var(--border-color) !important;
        }
        
        .list-group-item {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        .top-bar {
            background: var(--bg-secondary);
            box-shadow: var(--card-shadow);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }
        
        .top-bar h1 {
            color: var(--text-primary);
        }
        
        /* Fix text colors in dark mode */
        [data-theme="dark"] .text-black,
        [data-theme="dark"] h1, 
        [data-theme="dark"] h2, 
        [data-theme="dark"] h3, 
        [data-theme="dark"] h4, 
        [data-theme="dark"] h5, 
        [data-theme="dark"] h6,
        [data-theme="dark"] p,
        [data-theme="dark"] span,
        [data-theme="dark"] label,
        [data-theme="dark"] .fw-bold,
        [data-theme="dark"] strong {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-secondary {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] small,
        [data-theme="dark"] .small {
            color: var(--text-secondary) !important;
        }
        
        /* Ensure buttons have proper contrast */
        [data-theme="dark"] .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .btn-outline-secondary:hover {
            background: var(--bg-tertiary);
            border-color: var(--text-secondary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        [data-theme="dark"] .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        /* Alert styling in dark mode */
        [data-theme="dark"] .alert {
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .alert-success {
            background-color: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        [data-theme="dark"] .alert-warning {
            background-color: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.3);
        }
        
        [data-theme="dark"] .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        [data-theme="dark"] .alert-info {
            background-color: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            border-color: rgba(6, 182, 212, 0.3);
        }
        
        /* Table styling in dark mode */
        [data-theme="dark"] .table {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .table thead th {
            color: var(--text-primary) !important;
            border-color: var(--border-color);
            background-color: var(--bg-tertiary);
        }
        
        [data-theme="dark"] .table tbody td {
            color: var(--text-primary) !important;
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .table tbody td.align-middle {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .table tbody td .fw-bold {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .table-hover tbody tr:hover {
            background-color: var(--bg-tertiary);
        }
        
        [data-theme="dark"] .table-hover tbody tr:hover td {
            color: var(--text-primary) !important;
        }
        
        /* Text muted in dark mode */
        [data-theme="dark"] .text-muted {
            color: var(--text-secondary) !important;
        }
        
        /* Card headers in dark mode */
        [data-theme="dark"] .card-header.bg-white,
        [data-theme="dark"] .card-footer.bg-white {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .card-header h5,
        [data-theme="dark"] .card-header h6 {
            color: var(--text-primary) !important;
        }
        
        /* Fix Bootstrap color utilities in dark mode */
        [data-theme="dark"] .text-success {
            color: #4ade80 !important;
        }
        
        [data-theme="dark"] .text-warning {
            color: #fbbf24 !important;
        }
        
        [data-theme="dark"] .text-danger {
            color: #f87171 !important;
        }
        
        [data-theme="dark"] .text-info {
            color: #22d3ee !important;
        }
        
        [data-theme="dark"] .text-primary {
            color: var(--primary-color) !important;
        }
        
        /* Badges in dark mode */
        [data-theme="dark"] .badge {
            color: white !important;
        }
        
        /* Small text elements */
        [data-theme="dark"] small,
        [data-theme="dark"] .small,
        [data-theme="dark"] td small {
            color: var(--text-secondary) !important;
        }
        
        /* Button outline info in dark mode */
        [data-theme="dark"] .btn-outline-info {
            color: #22d3ee;
            border-color: #22d3ee;
        }
        
        [data-theme="dark"] .btn-outline-info:hover {
            background-color: #22d3ee;
            color: white;
        }
        
        /* Breadcrumb in dark mode */
        [data-theme="dark"] .breadcrumb-item {
            color: var(--text-secondary);
        }
        
        [data-theme="dark"] .breadcrumb-item.active {
            color: var(--text-primary);
        }
        
        /* Form elements in dark mode */
        [data-theme="dark"] .form-select,
        [data-theme="dark"] .form-control {
            background-color: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-select:focus,
        [data-theme="dark"] .form-control:focus {
            background-color: var(--bg-tertiary);
            border-color: var(--primary-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-label {
            color: var(--text-primary);
        }
        
        /* Stat cards always have white text (they have gradient backgrounds) */
        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .stat-card p,
        [data-theme="dark"] .stat-card h3,
        [data-theme="dark"] .stat-card .stat-value {
            color: white !important;
        }
        
        [data-theme="dark"] .stat-card .text-white-50 {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Table light header in dark mode */
        [data-theme="dark"] .table-light {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .table-light th {
            color: var(--text-primary) !important;
            background-color: var(--bg-tertiary) !important;
        }
        
        [data-theme="dark"] .table-light td {
            color: var(--text-primary) !important;
        }
        
        /* Force all table content to be visible */
        [data-theme="dark"] table tbody tr td {
            color: var(--text-primary) !important;
            background-color: transparent !important;
        }
        
        [data-theme="dark"] table tbody tr:hover td {
            background-color: var(--bg-tertiary) !important;
        }
        
        [data-theme="dark"] .table tbody tr {
            background-color: transparent !important;
        }
        
        /* Page header in dark mode */
        [data-theme="dark"] .page-header h1,
        [data-theme="dark"] .page-header h2,
        [data-theme="dark"] .page-header h3 {
            color: var(--text-primary) !important;
        }
        
        /* Card body text color */
        [data-theme="dark"] .card-body {
            color: var(--text-primary);
        }
        
        /* Avatar in dark mode */
        [data-theme="dark"] .avatar {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }
        
        /* Ensure all text in cards is visible - but don't override layout */
        [data-theme="dark"] .card p:not(.mb-0):not(.mb-1):not(.mb-2):not(.mb-3),
        [data-theme="dark"] .card-body p:not(.mb-0):not(.mb-1):not(.mb-2):not(.mb-3) {
            color: var(--text-primary);
        }
        
        /* Links in dark mode */
        [data-theme="dark"] a {
            color: var(--primary-color);
        }
        
        [data-theme="dark"] a:hover {
            color: #5b8def;
        }
        
        /* Form control option elements */
        [data-theme="dark"] .form-select option {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }
    </style>
</head>
<body class="user-dashboard">
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
                <small>User Panel</small>
            </div>
            
            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.all-dues') ? 'active' : '' }}" href="{{ route('user.all-dues') }}">
                        <i class="fas fa-users"></i>
                        <span>Tunggakan Semua User</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.payments.*') ? 'active' : '' }}" href="{{ route('user.payments.history', auth()->user()->usersExtended->id ?? 0) }}">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Pembayaran</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.receipts.*') ? 'active' : '' }}" href="{{ route('user.receipts.index') }}">
                        <i class="fas fa-receipt"></i>
                        <span>Kwitansi</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>
                
                <li class="nav-item mt-4">
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
                    <h1>@yield('title', 'User Dashboard')</h1>
                    
                    <div class="d-flex align-items-center gap-2">
                        <!-- Dark Mode Toggle -->
                        <button class="btn btn-outline-secondary" id="darkModeToggle" title="Toggle Dark Mode">
                            <i class="fas fa-moon" id="darkModeIcon"></i>
                        </button>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->usersExtended?->profile_photo_url)
                                    <img src="{{ Auth::user()->usersExtended->profile_photo_url }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="rounded-circle"
                                         style="width: 28px; height: 28px; object-fit: cover;">
                                @else
                                    <div class="avatar d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
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
        <a href="{{ route('user.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('user.all-dues') }}" class="bottom-nav-item {{ request()->routeIs('user.all-dues') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Tunggakan</span>
        </a>

        <a href="{{ route('user.payments.history', auth()->user()->usersExtended->id ?? 0) }}" class="bottom-nav-item {{ request()->routeIs('user.payments.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>

        <a href="{{ route('user.profile') }}" class="bottom-nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>

        <button class="bottom-nav-item" id="mobileMoreMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
            <span>Lainnya</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileMoreMenu">
            <li>
                <a class="dropdown-item" href="{{ route('user.receipts.index') }}">
                    <i class="fas fa-receipt me-2"></i>Kwitansi
                </a>
            </li>
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
    </nav>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Dark Mode & Mobile Menu Script -->
    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        const html = document.documentElement;
        
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', currentTheme);
        updateDarkModeIcon(currentTheme);
        
        darkModeToggle.addEventListener('click', () => {
            const theme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            updateDarkModeIcon(theme);
        });
        
        function updateDarkModeIcon(theme) {
            if (theme === 'dark') {
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            } else {
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
            }
        }
        
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
