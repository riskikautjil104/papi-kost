@extends('layouts.admin')

@section('title', 'Laporan Tahunan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan Tahunan {{ $year }}</h1>
        <div>
            <a href="{{ route('admin.reports.user') }}" class="btn btn-info">
                <i class="fas fa-users"></i> Laporan per User
            </a>
            <form method="GET" action="{{ route('admin.reports.annual') }}" class="d-inline ms-2">
                <select name="year" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('admin.reports.annual.pdf', ['year' => $year]) }}" class="btn btn-danger ms-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.reports.annual.excel', ['year' => $year]) }}" class="btn btn-success ms-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Pemasukan</h5>
                    <h3>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Total Pengeluaran</h5>
                    <h3>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Saldo Akhir</h5>
                    <h3>Rp {{ number_format($totalBalance, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Rincian Bulanan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
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
                        $monthNames = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        @endphp
                        @foreach($monthlyData as $month => $data)
                        <tr>
                            <td>{{ $monthNames[$month] }}</td>
                            <td class="text-end text-success">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger">Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($data['balance'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-dark">
                            <td><strong>TOTAL</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalExpense, 0, ',', '.') }}</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalBalance, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Expense by Category -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pengeluaran per Kategori</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        @endphp
                        @forelse($expenseByCategory as $category)
                        <tr>
                            <td>{{ $categoryLabels[$category->category] ?? ucfirst($category->category) }}</td>
                            <td class="text-end">Rp {{ number_format($category->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data pengeluaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Payment Summary -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Ringkasan Pembayaran per User</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kamar</th>
                            <th class="text-end">Iuran/Bulan</th>
                            <th class="text-end">Total Dibayar</th>
                            <th class="text-end">Tunggakan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userPayments as $user)
                        <tr>
                            <td>{{ $user->user->name }}</td>
                            <td>{{ $user->room_number ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</td>
                            <td class="text-end text-success">Rp {{ number_format($user->total_paid, 0, ',', '.') }}</td>
                            <td class="text-end {{ $user->remaining > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($user->remaining, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($user->remaining <= 0)
                                    <span class="badge bg-success">LUNAS</span>
                                @else
                                    <span class="badge bg-warning">BELUM LUNAS</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
