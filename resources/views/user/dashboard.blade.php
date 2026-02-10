@extends('layouts.user')

@section('title', 'User Dashboard')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Dashboard User</h1>
        <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileStats">
            <i class="fas fa-chart-bar"></i>
        </button>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Home</li>
        </ol>
    </nav>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('user.dashboard') }}" class="row g-3 align-items-end">
            <div class="col-md-4 col-6">
                <label class="form-label small text-muted mb-1">Bulan</label>
                <select name="month" class="form-select form-select-sm">
                    <option value="all" {{ request('month') == 'all' || !request('month') ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach($availableMonths as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-6">
                <label class="form-label small text-muted mb-1">Tahun</label>
                <select name="year" class="form-select form-select-sm">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('year', $currentYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-12">
                <button type="submit" class="btn btn-admin btn-sm w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Mobile Stats Collapse -->
<div class="collapse d-lg-none mb-4" id="mobileStats">
    <div class="card">
        <div class="card-body py-2">
            <div class="row g-2">
                <div class="col-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Iuran Bulanan</small>
                        <strong class="d-block">Rp {{ number_format($user->usersExtended->monthly_fee ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Total Dibayar</small>
                        <strong class="d-block">Rp {{ number_format($totalPaid ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Sisa Bulan Ini</small>
                        <strong class="d-block">{{ $remaining <= 0 ? 'LUNAS' : 'Rp ' . number_format($remaining, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keuangan Kontrakan Stats -->
<div class="row g-4 d-none d-lg-flex mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Pemasukan</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-arrow-down stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Pengeluaran</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-arrow-up stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Uang Kas</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($walletBalance ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-university stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card {{ $remaining <= 0 ? 'success' : 'warning' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Tunggakan Saya</p>
                        <h3 class="stat-value mb-0">{{ $remaining <= 0 ? 'LUNAS' : 'Rp ' . number_format($remaining, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas {{ $remaining <= 0 ? 'fa-check' : 'fa-exclamation-triangle' }} stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Personal Payment Stats -->
<div class="row g-4 d-none d-lg-flex">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Iuran Bulanan</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($user->usersExtended->monthly_fee ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-wallet stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Dibayar</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($totalPaid ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-check-circle stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card {{ $remaining <= 0 ? 'info' : 'warning' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Sisa Bulan Ini</p>
                        <h3 class="stat-value mb-0">{{ $remaining <= 0 ? 'LUNAS' : 'Rp ' . number_format($remaining, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas {{ $remaining <= 0 ? 'fa-check' : 'fa-clock' }} stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2 mt-lg-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Pembayaran</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Bulan/Tahun</th>
                                <th class="border-top-0">Jumlah</th>
                                <th class="border-top-0 d-none d-md-table-cell">Metode</th>
                                <th class="border-top-0">Status</th>
                                <th class="border-top-0 d-none d-lg-table-cell">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td class="align-middle">{{ $payment->month_name }} {{ $payment->year }}</td>
                                <td class="align-middle fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="align-middle d-none d-md-table-cell">{{ ucfirst($payment->payment_method) }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }} p-2">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="align-middle d-none d-lg-table-cell">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada pembayaran</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- @if($payments->hasPages())
                <div class="card-footer bg-white border-top-0">
                    {{ $payments->links() }}
                </div>
                @endif --}}
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profil Saya</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($user->usersExtended?->profile_photo_url)
                            <img src="{{ $user->usersExtended->profile_photo_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle mx-auto mb-3 d-block"
                                 style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #0066FF;">
                        @else
                            <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        
                        @if($user->usersExtended)
                        <div class="border-top pt-3">
                            <div class="row g-2 text-start">
                                <div class="col-12">
                                    <p class="mb-2"><i class="fas fa-phone text-muted me-2"></i>{{ $user->usersExtended->phone }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-2"><i class="fas fa-door text-muted me-2"></i>Kamar: {{ $user->usersExtended->room_number ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-0"><i class="fas fa-calendar-check text-muted me-2"></i>Kontrak: {{ $user->usersExtended->contract_end_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($remaining > 0)
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-money-bill me-2"></i>Bayar Iuran</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Segera lakukan pembayaran iuran bulan ini!
                        </div>
                        <a href="{{ route('user.payments.create', $user->usersExtended->id) }}" class="btn btn-admin w-100 py-2">
                            <i class="fas fa-upload me-1"></i> Upload Bukti Bayar
                        </a>
                        <p class="text-muted small mt-2 mb-0 text-center">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload bukti transfer untuk konfirmasi admin
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Status Pembayaran Bulanan -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Status Pembayaran {{ $filterYear }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($availableMonths as $monthNum => $monthName)
                                @php
                                    $isPaid = in_array($monthNum, $paidMonths ?? []);
                                    $isCurrentMonth = $monthNum == $currentMonth && $filterYear == $currentYear;
                                @endphp
                                <div class="col-3 col-md-4 col-lg-3">
                                    <div class="text-center p-2 rounded {{ $isPaid ? 'bg-success text-white' : ($isCurrentMonth ? 'bg-warning' : 'bg-light') }}">
                                        <small class="d-block" style="font-size: 0.7rem;">{{ substr($monthName, 0, 3) }}</small>
                                        <i class="fas {{ $isPaid ? 'fa-check' : 'fa-times' }}"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 d-flex justify-content-between small">
                            <span><i class="fas fa-check-circle text-success me-1"></i> Sudah Bayar: {{ count($paidMonths ?? []) }} bulan</span>
                            <span><i class="fas fa-exclamation-circle text-warning me-1"></i> Belum Bayar: {{ 12 - count($paidMonths ?? []) }} bulan</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($payments->isNotEmpty())
            <div class="col-12 d-lg-none">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Ringkasan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Total Transaksi</small>
                                    <strong class="d-block fs-5">{{ $payments->count() }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Status Lunas</small>
                                    <strong class="d-block fs-5">{{ $payments->where('status', 'approved')->count() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .breadcrumb {
            font-size: 0.875rem;
        }
        
        .card-header h5, .card-header h6 {
            font-size: 1rem;
        }
        
        .table th, .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .avatar {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .btn-admin {
            padding: 0.5rem 1rem;
        }
        
        /* Mobile Stats */
        #mobileStats .col-4 {
            border-right: 1px solid #dee2e6;
        }
        
        #mobileStats .col-4:last-child {
            border-right: none;
        }
    }
    
    @media (max-width: 576px) {
        .main-content {
            padding: 1rem !important;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .table-responsive {
            margin: -1rem;
            width: calc(100% + 2rem);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .mobile-hide {
            display: none !important;
        }
    }
    
    /* Print Optimizations */
    @media print {
        .bottom-nav, 
        .mobile-menu-toggle,
        .dropdown,
        .btn {
            display: none !important;
        }
        
        .sidebar-wrapper {
            display: none;
        }
        
        .main-wrapper {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide mobile stats collapse after 5 seconds on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth < 992) {
            const mobileStats = document.getElementById('mobileStats');
            if (mobileStats) {
                setTimeout(() => {
                    const bsCollapse = new bootstrap.Collapse(mobileStats, {
                        toggle: false
                    });
                    bsCollapse.hide();
                }, 5000);
            }
        }
        
        // Adjust table columns on resize
        window.addEventListener('resize', function() {
            adjustTableColumns();
        });
        
        function adjustTableColumns() {
            const table = document.querySelector('.table');
            if (table && window.innerWidth < 768) {
                // Hide/show columns based on screen size
                const headers = table.querySelectorAll('th');
                const cells = table.querySelectorAll('td');
                
                // You can add more logic here to handle responsive tables
            }
        }
        
        adjustTableColumns();
    });
</script>
@endpush
