@extends('layouts.admin')

@section('title', 'Kwitansi Digital')

@section('content')
<div class="page-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
    <div>
        <h1><i class="fas fa-receipt me-2"></i>Kwitansi Digital</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Kwitansi Digital</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-primary mb-1"><i class="fas fa-file-invoice fa-2x"></i></div>
                <h4 class="mb-0">{{ number_format($stats['total_receipts']) }}</h4>
                <small class="text-muted">Total Kwitansi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-success mb-1"><i class="fas fa-money-bill-wave fa-2x"></i></div>
                <h4 class="mb-0">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h4>
                <small class="text-muted">Total Nominal</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-info mb-1"><i class="fas fa-calendar-check fa-2x"></i></div>
                <h4 class="mb-0">{{ number_format($stats['this_month']) }}</h4>
                <small class="text-muted">Bulan Ini</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="text-warning mb-1"><i class="fas fa-coins fa-2x"></i></div>
                <h4 class="mb-0">Rp {{ number_format($stats['this_month_amount'], 0, ',', '.') }}</h4>
                <small class="text-muted">Nominal Bulan Ini</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.receipts.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Cari</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="No. kwitansi, nama, kamar..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="month" class="form-label">Bulan</label>
                <select name="month" id="month" class="form-select">
                    <option value="all">Semua Bulan</option>
                    @php
                    $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                    @endphp
                    @foreach($monthNames as $num => $name)
                    <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="year" class="form-label">Tahun</label>
                <select name="year" id="year" class="form-select">
                    <option value="all">Semua</option>
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.receipts.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Receipts Table -->
<div class="card">
    <div class="card-body p-0">
        @if($receipts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Kwitansi</th>
                        <th>Penghuni</th>
                        <th>Kamar</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $receipt)
                    <tr>
                        <td>
                            <a href="{{ route('admin.receipts.show', $receipt) }}" class="text-decoration-none fw-bold">
                                {{ $receipt->receipt_number }}
                            </a>
                        </td>
                        <td>{{ $receipt->tenant_name }}</td>
                        <td><span class="badge bg-info">{{ $receipt->room_number ?? '-' }}</span></td>
                        <td>{{ $receipt->period }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($receipt->amount, 0, ',', '.') }}</td>
                        <td>{{ $receipt->payment_method_label }}</td>
                        <td><small>{{ $receipt->issued_at->format('d M Y') }}</small></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.receipts.show', $receipt) }}" class="btn btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.receipts.preview', $receipt) }}" class="btn btn-outline-info" title="Preview PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('admin.receipts.download', $receipt) }}" class="btn btn-outline-success" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($receipts->hasPages())
        <div class="card-footer">
            {{ $receipts->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Belum Ada Kwitansi</h5>
            <p class="text-muted">Kwitansi akan otomatis dibuat saat pembayaran disetujui.</p>
        </div>
        @endif
    </div>
</div>
@endsection
