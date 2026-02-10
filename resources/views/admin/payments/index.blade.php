@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="page-header mb-4">
    <h1 class="h3 mb-0">Manajemen Pembayaran</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pembayaran</li>
        </ol>
    </nav>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body py-3">
                <i class="fas fa-clock fa-lg text-warning mb-2"></i>
                <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body py-3">
                <i class="fas fa-check-circle fa-lg text-success mb-2"></i>
                <h4 class="mb-0">{{ $stats['approved'] }}</h4>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body py-3">
                <i class="fas fa-times-circle fa-lg text-danger mb-2"></i>
                <h4 class="mb-0">{{ $stats['rejected'] }}</h4>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="card text-center h-100">
            <div class="card-body py-3">
                <i class="fas fa-money-bill-wave fa-lg text-primary mb-2"></i>
                <h4 class="mb-0">Rp {{ number_format($stats['total_approved'], 0, ',', '.') }}</h4>
                <small class="text-muted">Total Diterima</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h5 class="mb-0">Daftar Pembayaran</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end gap-2">
                    <a href="{{ route('admin.payments.pending') }}" class="btn btn-warning position-relative btn-sm">
                        <i class="fas fa-exclamation-circle me-1"></i> 
                        <span class="d-none d-md-inline">Pending</span>
                        @if($stats['pending'] > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $stats['pending'] }}
                        </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-4">
            <div class="col-12 col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="month" class="form-select form-select-sm">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="year" class="form-select form-select-sm">
                    <option value="">Semua Tahun</option>
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-search me-1"></i> 
                    <span class="d-none d-md-inline">Filter</span>
                </button>
            </div>
            <div class="col-6 col-md-1">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset Filter">
                    <i class="fas fa-redo"></i>
                    <span class="d-none d-md-inline">Reset</span>
                </a>
            </div>
        </form>
        
        <!-- Payments Table -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">No</th>
                        <th class="border-top-0">Nama Penghuni</th>
                        <th class="border-top-0 d-none d-lg-table-cell">Bulan/Tahun</th>
                        <th class="border-top-0">Jumlah</th>
                        <th class="border-top-0 d-none d-md-table-cell">Metode</th>
                        <th class="border-top-0">Status</th>
                        <th class="border-top-0 d-none d-xl-table-cell">Tanggal</th>
                        <th class="border-top-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                    <tr>
                        <td class="align-middle">{{ $payments->firstItem() + $index }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                @if($payment->userExtended?->profile_photo_url)
                                    <img src="{{ $payment->userExtended->profile_photo_url }}" 
                                         alt="{{ $payment->userExtended?->user?->name ?? 'User' }}" 
                                         class="rounded-circle me-2 d-none d-sm-flex"
                                         style="width: 35px; height: 35px; object-fit: cover;">
                                @else
                                    <div class="avatar me-2 d-none d-sm-flex" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                        {{ substr($payment->userExtended?->user?->name ?? 'N/A', 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <strong class="d-block">{{ $payment->userExtended?->user?->name ?? 'User Tidak Ditemukan' }}</strong>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-door fa-xs me-1"></i>{{ $payment->userExtended?->room_number ?? '-' }}
                                    </small>
                                    <small class="text-muted d-lg-none">
                                        <i class="fas fa-calendar fa-xs me-1"></i>{{ $payment->month_name }} {{ $payment->year }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle d-none d-lg-table-cell">
                            {{ $payment->month_name }} {{ $payment->year }}
                        </td>
                        <td class="align-middle fw-bold">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="align-middle d-none d-md-table-cell">
                            {{ ucfirst($payment->payment_method) }}
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }} p-2">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="align-middle d-none d-xl-table-cell">
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($payment->status === 'pending')
                                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Setujui" onclick="return confirm('Setujui pembayaran ini?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak" onclick="return confirm('Tolak pembayaran ini?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada pembayaran ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <small class="text-muted mb-2 mb-md-0">
                Menampilkan {{ $payments->firstItem() ?? 0 }} - {{ $payments->lastItem() ?? 0 }} dari {{ $payments->total() }}
            </small>
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

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
        
        .card-header h5 {
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
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
        }
        
        .avatar {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
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
        
        .form-control-sm, .form-select-sm {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 480px) {
        .col-6 {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        
        .gap-1 > * {
            margin-right: 0.25rem;
        }
        
        .gap-1 > *:last-child {
            margin-right: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto submit filter on change for mobile
        if (window.innerWidth < 768) {
            const filterSelects = document.querySelectorAll('select[name="status"], select[name="month"], select[name="year"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        }
        
        // Confirm actions for approve/reject
        const confirmForms = document.querySelectorAll('form[action*="approve"], form[action*="reject"]');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const actionType = this.action.includes('approve') ? 'menyetujui' : 'menolak';
                if (!confirm(`Apakah Anda yakin ingin ${actionType} pembayaran ini?`)) {
                    e.preventDefault();
                }
            });
        });
        
        // Tooltip for mobile
        if (window.innerWidth < 768) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover focus'
                });
            });
        }
    });
</script>
@endpush
