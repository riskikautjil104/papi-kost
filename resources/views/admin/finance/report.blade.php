@extends('layouts.admin')

@section('title', 'Laporan Keuangan Tahunan')

@section('content')
<div class="page-header">
    <h1>Laporan Keuangan Tahunan {{ $year }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.finance.index') }}">Keuangan</a></li>
            <li class="breadcrumb-item active">Laporan Tahunan</li>
        </ol>
    </nav>
</div>

<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Pemasukan Tahun {{ $year }}</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($annualIncome, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-arrow-up stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Total Pengeluaran Tahun {{ $year }}</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($annualExpense, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-arrow-down stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 mb-1">Saldo Bersih Tahun {{ $year }}</p>
                        <h3 class="stat-value mb-0">Rp {{ number_format($annualIncome - $annualExpense, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-wallet stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Monthly Breakdown -->
        <div class="col-lg-8">
            <div class="card table-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Rincian Bulanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Pemasukan</th>
                                    <th class="text-end">Pengeluaran</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $months = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                @endphp
                                @foreach($monthlyBreakdown as $data)
                                    <tr>
                                        <td class="fw-medium">{{ $months[$data->month] ?? 'Bulan ' . $data->month }}</td>
                                        <td class="text-end text-success fw-bold">Rp {{ number_format($data->income, 0, ',', '.') }}</td>
                                        <td class="text-end text-danger fw-bold">Rp {{ number_format($data->expense, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold {{ $data->income - $data->expense >= 0 ? 'text-primary' : 'text-danger' }}">
                                            Rp {{ number_format($data->income - $data->expense, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Pengeluaran per Kategori</h5>
                </div>
                <div class="card-body">
                    @php
                        $categoryLabels = [
                            'listrik' => 'Listrik',
                            'air' => 'Air',
                            'internet' => 'Internet',
                            'kebersihan' => 'Kebersihan',
                            'perbaikan' => 'Perbaikan',
                            'perlengkapan' => 'Perlengkapan',
                            'lainnya' => 'Lainnya'
                        ];
                        $categoryColors = [
                            'listrik' => 'bg-warning',
                            'air' => 'bg-info',
                            'internet' => 'bg-primary',
                            'kebersihan' => 'bg-success',
                            'perbaikan' => 'bg-danger',
                            'perlengkapan' => 'bg-secondary',
                            'lainnya' => 'bg-dark'
                        ];
                    @endphp
                    @forelse($categoryBreakdown as $category)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <span class="badge {{ $categoryColors[$category->category] ?? 'bg-secondary' }} me-2">&nbsp;</span>
                                <span>{{ $categoryLabels[$category->category] ?? ucfirst($category->category) }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">Rp {{ number_format($category->total, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ number_format(($category->total / $annualExpense) * 100, 1) }}%</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-pie fa-3x mb-3"></i>
                            <p>Belum ada data pengeluaran</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
