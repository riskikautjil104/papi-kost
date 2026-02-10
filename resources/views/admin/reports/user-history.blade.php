@extends('layouts.admin')

@section('title', 'Riwayat Pembayaran - ' . $user->user->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Riwayat Pembayaran</h1>
        <div>
            <a href="{{ route('admin.reports.annual') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Laporan Tahunan
            </a>
            <a href="{{ route('admin.reports.user') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-users"></i> Semua User
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi User</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>Nama:</strong><br>{{ $user->user->name }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Email:</strong><br>{{ $user->user->email }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>No. Telepon:</strong><br>{{ $user->phone }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Kamar:</strong><br>{{ $user->room_number ?? '-' }}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <p><strong>Iuran/Bulan:</strong><br>Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Tanggal Masuk:</strong><br>{{ $user->join_date->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Kontrak Habis:</strong><br>{{ $user->contract_end_date->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Status:</strong><br>
                        <span class="badge bg-{{ $user->status_badge }}">{{ ucfirst($user->status) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6>Total Dibayar</h6>
                    <h4>Rp {{ number_format($totalPaid, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h6>Menunggu Konfirmasi</h6>
                    <h4>Rp {{ number_format($totalPending, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6>Bulan Lunas</h6>
                    <h4>{{ $monthsPaid }} / 12</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $remaining > 0 ? 'bg-danger' : 'bg-success' }} text-white">
                <div class="card-body text-center">
                    <h6>{{ $remaining > 0 ? 'Tunggakan' : 'Status' }}</h6>
                    <h4>{{ $remaining > 0 ? 'Rp ' . number_format($remaining, 0, ',', '.') : 'LUNAS' }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Year Filter & Export -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" class="d-flex align-items-center">
            <label class="me-2">Tahun:</label>
            <select name="year" class="form-select w-auto" onchange="this.form.submit()">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
        <div>
            <a href="{{ route('admin.reports.user.pdf', ['user' => $user, 'year' => $year]) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.reports.user.excel', ['user' => $user, 'year' => $year]) }}" class="btn btn-success ms-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Monthly Status Grid -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Status Pembayaran per Bulan {{ $year }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                $monthNames = [
                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                    5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
                    9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                ];
                $statusBadges = [
                    'approved' => 'success',
                    'pending' => 'warning',
                    'rejected' => 'danger',
                    'unpaid' => 'secondary'
                ];
                $statusLabels = [
                    'approved' => 'Lunas',
                    'pending' => 'Pending',
                    'rejected' => 'Ditolak',
                    'unpaid' => 'Belum'
                ];
                @endphp
                @foreach($monthlyStatus as $month => $status)
                <div class="col-md-2 col-4 mb-3">
                    <div class="card text-center">
                        <div class="card-header bg-light">
                            <strong>{{ $monthNames[$month] }}</strong>
                        </div>
                        <div class="card-body">
                            <span class="badge bg-{{ $statusBadges[$status['status']] }} fs-6">
                                {{ $statusLabels[$status['status']] }}
                            </span>
                            @if($status['amount'] > 0)
                                <small class="d-block mt-2">
                                    Rp {{ number_format($status['amount'], 0, ',', '.') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Payment History Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detail Riwayat Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Disetujui Oleh</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->month_name }} {{ $payment->year }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->status_badge }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $payment->approver ? $payment->approver->name : '-' }}</td>
                            <td>{{ $payment->description ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada riwayat pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
