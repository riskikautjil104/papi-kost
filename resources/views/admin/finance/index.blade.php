@extends('layouts.admin')

@section('title', 'Manajemen Keuangan')

@section('content')
<div class="page-header mb-4">
    <h1 class="h3 mb-0">Manajemen Keuangan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Keuangan</li>
        </ol>
    </nav>
</div>

<!-- Mobile Stats Summary -->
<div class="d-md-none mb-3">
    <div class="card">
        <div class="card-body">
            <div class="row g-2 text-center">
                <div class="col-4">
                    <small class="text-muted d-block">Pemasukan</small>
                    <strong class="d-block text-success">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="col-4">
                    <small class="text-muted d-block">Pengeluaran</small>
                    <strong class="d-block text-danger">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</strong>
                </div>
                <div class="col-4">
                    <small class="text-muted d-block">Saldo</small>
                    <strong class="d-block text-primary">Rp {{ number_format($walletBalance, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Stats Cards -->
<div class="row g-3 mb-4 d-none d-md-flex">
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Pemasukan {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-arrow-up stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Pengeluaran {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-arrow-down stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Saldo Saat Ini</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($walletBalance, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-wallet stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Actions -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label small mb-1">Tahun</label>
                <select name="year" class="form-select form-select-sm">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small mb-1">Bulan</label>
                <select name="month" class="form-select form-select-sm">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-3">
                <button type="submit" class="btn btn-admin btn-sm w-100">
                    <i class="fas fa-filter me-1"></i> 
                    <span class="d-none d-md-inline">Filter</span>
                </button>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.finance.expense.create') }}" class="btn btn-outline-danger btn-sm w-100">
                    <i class="fas fa-minus-circle me-1"></i> 
                    <span class="d-none d-md-inline">Catat Pengeluaran</span>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    <!-- Expenses List -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        <span class="d-none d-md-inline">Riwayat Pengeluaran</span>
                        <span class="d-inline d-md-none">Pengeluaran</span>
                    </h5>
                    <span class="badge bg-primary">{{ date('M Y', mktime(0, 0, 0, $month, 1, $year)) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Tanggal</th>
                                <th class="border-top-0">Kategori</th>
                                <th class="border-top-0 d-none d-md-table-cell">Deskripsi</th>
                                <th class="border-top-0">Jumlah</th>
                                <th class="border-top-0 d-none d-xl-table-cell">Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr>
                                <td class="align-middle">
                                    <span class="d-block">{{ date('d/m', strtotime($expense->expense_date)) }}</span>
                                    <small class="text-muted d-md-none">{{ date('Y', strtotime($expense->expense_date)) }}</small>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-info">{{ $expense->category_label }}</span>
                                </td>
                                <td class="align-middle d-none d-md-table-cell">
                                    {{ Str::limit($expense->description, 30) ?? '-' }}
                                </td>
                                <td class="align-middle fw-bold text-danger">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="align-middle d-none d-xl-table-cell">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2" style="width: 25px; height: 25px; font-size: 0.8rem;">
                                            {{ substr($expense->creator->name, 0, 1) }}
                                        </div>
                                        <span>{{ Str::limit($expense->creator->name, 10) }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada pengeluaran bulan ini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($expenses, 'hasPages') && $expenses->hasPages())
                <div class="card-footer bg-white border-top-0">
                    {{ $expenses->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar Content -->
    <div class="col-lg-4">
        <div class="row g-3">
            <!-- Expense Breakdown -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Pengeluaran per Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(count($expenseByCategory) > 0)
                            @foreach($expenseByCategory as $category => $amount)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="flex-grow-1 me-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">{{ ucfirst($category) }}</span>
                                        <small class="fw-bold">Rp {{ number_format($amount, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: {{ ($amount / array_sum($expenseByCategory)) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-pie fa-3x mb-3"></i>
                            <p>Belum ada data</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Export Actions -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Export Laporan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.finance.report.pdf', ['year' => $year, 'month' => $month]) }}" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i> Download PDF
                            </a>
                            <a href="{{ route('admin.finance.report.excel', ['year' => $year, 'month' => $month]) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-file-excel me-1"></i> Download Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Summary Card for Mobile -->
            <div class="col-12 d-lg-none">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Ringkasan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Total Items</small>
                                    <strong class="d-block fs-5">{{ $expenses->count() }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <small class="text-muted d-block">Kategori</small>
                                    <strong class="d-block fs-5">{{ count($expenseByCategory) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>
                        Pemasukan vs Pengeluaran {{ $year }}
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart');
    if (!ctx) return;
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const incomeData = @json($monthlyComparison->pluck('income')->pad(12, 0));
    const expenseData = @json($monthlyComparison->pluck('expense')->pad(12, 0));
    
    // Responsive chart configuration
    const isMobile = window.innerWidth < 768;
    
    new Chart(ctx, {
        type: isMobile ? 'line' : 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Pemasukan',
                data: incomeData,
                backgroundColor: isMobile ? 'transparent' : '#22c55e',
                borderColor: '#22c55e',
                borderWidth: isMobile ? 2 : 0,
                tension: isMobile ? 0.4 : 0,
                fill: isMobile,
                borderRadius: isMobile ? 0 : 4
            }, {
                label: 'Pengeluaran',
                data: expenseData,
                backgroundColor: isMobile ? 'transparent' : '#ef4444',
                borderColor: '#ef4444',
                borderWidth: isMobile ? 2 : 0,
                tension: isMobile ? 0.4 : 0,
                fill: isMobile,
                borderRadius: isMobile ? 0 : 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'top',
                    labels: {
                        boxWidth: isMobile ? 12 : 20,
                        padding: isMobile ? 15 : 10,
                        font: {
                            size: isMobile ? 11 : 12
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: isMobile ? 10 : 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: isMobile ? 10 : 11
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            elements: {
                point: {
                    radius: isMobile ? 3 : 4,
                    hoverRadius: isMobile ? 5 : 6
                }
            }
        }
    });
    
    // Auto submit filter on mobile
    if (window.innerWidth < 768) {
        const filterSelects = document.querySelectorAll('select[name="month"], select[name="year"]');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    /* Mobile optimizations */
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
        
        .stat-value {
            font-size: 1.25rem;
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
            width: 25px;
            height: 25px;
            font-size: 0.8rem;
        }
        
        .btn-sm {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .chart-container {
            height: 200px !important;
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
        
        .form-control-sm, .form-select-sm {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .d-grid .btn {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
    }
    
    /* Print styles */
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
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6;
        }
        
        .table {
            font-size: 12px;
        }
    }
</style>
@endpush
@endsection