@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Penghuni Aktif</p>
                        <h2 class="stat-value mb-0">{{ number_format($totalUsers) }}</h2>
                    </div>
                    <i class="fas fa-users stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6 col-xl-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Pemasukan Bulan Ini</p>
                        <h2 class="stat-value mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-arrow-up stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6 col-xl-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Pengeluaran Bulan Ini</p>
                        <h2 class="stat-value mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-arrow-down stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6 col-xl-3">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Saldo Saat Ini</p>
                        <h2 class="stat-value mb-0">Rp {{ number_format($walletBalance, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-wallet stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert untuk pembayaran pending -->
@if($pendingPayments > 0)
<div class="alert alert-warning d-flex align-items-center mb-4 flex-column flex-md-row">
    <i class="fas fa-exclamation-triangle me-md-2 mb-2 mb-md-0"></i>
    <div class="flex-grow-1 text-center text-md-start">
        <strong>{{ $pendingPayments }}</strong> pembayaran menunggu persetujuan!
    </div>
    <a href="{{ route('admin.payments.pending') }}" class="btn btn-sm btn-warning mt-2 mt-md-0">Review Sekarang</a>
</div>
@endif

<div class="row g-4">
    <!-- Tunggakan Penghuni -->
    <div class="col-lg-8">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Tunggakan Penghuni</h5>
                <span class="badge bg-danger">{{ $usersWithDebt->where('is_overdue', true)->count() }} Orang</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Penghuni</th>
                                <th class="d-none d-lg-table-cell">Kamar</th>
                                <th class="d-none d-md-table-cell">Iuran Bulanan</th>
                                <th class="d-none d-xl-table-cell">Sudah Bayar</th>
                                <th>Tunggakan</th>
                                <th class="d-none d-md-table-cell">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usersWithDebt->take(10) as $user)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_photo_url)
                                            <img src="{{ $user->profile_photo_url }}" 
                                                 alt="{{ $user->user->name }}" 
                                                 class="rounded-circle me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar me-2">
                                                {{ substr($user->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $user->user->name }}</strong>
                                            <small class="d-block text-muted d-md-none">Kamar: {{ $user->room_number ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell align-middle">{{ $user->room_number ?? '-' }}</td>
                                <td class="d-none d-md-table-cell align-middle">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</td>
                                <td class="d-none d-xl-table-cell align-middle">Rp {{ number_format($user->paid_amount, 0, ',', '.') }}</td>
                                <td class="align-middle">
                                    @if($user->remaining > 0)
                                    <span class="text-danger fw-bold">Rp {{ number_format($user->remaining, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-success">Lunas</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell align-middle">
                                    @if($user->is_overdue)
                                    <span class="badge bg-danger">Belum Lunas</span>
                                    @else
                                    <span class="badge bg-success">Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada tunggakan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-lg-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Pembayaran Terbaru</h5>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentPayments as $payment)
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            @if($payment->userExtended?->profile_photo_url)
                                <img src="{{ $payment->userExtended->profile_photo_url }}" 
                                     alt="{{ $payment->userExtended?->user?->name ?? 'User' }}" 
                                     class="rounded-circle me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="avatar me-2" style="width: 40px; height: 40px;">
                                    {{ substr($payment->userExtended?->user?->name ?? 'U', 0, 1) }}
                                </div>
                            @endif
                            <div class="me-auto">
                                <div class="fw-bold">{{ $payment->userExtended?->user?->name ?? 'User Tidak Ditemukan' }}</div>
                                <small class="text-muted">{{ $payment->month_name }} {{ $payment->year }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center py-4">
                        <small class="text-muted">Belum ada pembayaran</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mt-2">
    <!-- Monthly Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Pemasukan vs Pengeluaran {{ $currentYear }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense by Category -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Pengeluaran per Kategori</h5>
            </div>
            <div class="card-body">
                @if($expenseByCategory->count() > 0)
                <div class="chart-container" style="height: 250px;">
                    <canvas id="expenseChart"></canvas>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-chart-pie fa-3x mb-3"></i>
                    <p>Belum ada data pengeluaran bulan ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-admin w-100 py-3">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                            <span class="d-none d-md-inline">Tambah Penghuni Baru</span>
                            <span class="d-inline d-md-none small">Tambah Penghuni</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.payments.pending') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                            <span class="d-none d-md-inline">Review Pembayaran</span>
                            <span class="d-inline d-md-none small">Review</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.finance.expense.create') }}" class="btn btn-outline-danger w-100 py-3">
                            <i class="fas fa-minus-circle fa-2x mb-2 d-block"></i>
                            <span class="d-none d-md-inline">Catat Pengeluaran</span>
                            <span class="d-inline d-md-none small">Pengeluaran</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.finance.report') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-file-download fa-2x mb-2 d-block"></i>
                            <span class="d-none d-md-inline">Export Laporan</span>
                            <span class="d-inline d-md-none small">Export</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get current theme
    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDarkMode ? '#FFFFFF' : '#1e293b';
    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    const incomeData = @json($monthlyStats->pluck('income')->pad(12, 0));
    const expenseData = @json($monthlyStats->pluck('expense')->pad(12, 0));
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Pemasukan',
                data: incomeData,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Pengeluaran',
                data: expenseData,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: textColor
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6
                }
            }
        }
    });
    
    // Expense Chart
    @if($expenseByCategory->count() > 0)
    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    const categories = @json($expenseLabels);
    const expenseValues = @json($expenseValues);
    
    new Chart(expenseCtx, {
        type: 'doughnut',
        data: {
            labels: categories,
            datasets: [{
                data: expenseValues,
                backgroundColor: [
                    '#f59e0b',
                    '#06b6d4',
                    '#8b5cf6',
                    '#ec4899',
                    '#14b8a6',
                    '#f97316',
                    '#64748b'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: textColor
                    }
                }
            },
            cutout: '60%'
        }
    });
    @endif
</script>
@endpush
@endsection
