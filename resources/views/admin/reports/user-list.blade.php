@extends('layouts.admin')

@section('title', 'Laporan per User')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Laporan per User</h1>
        <div>
            <a href="{{ route('admin.reports.annual') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Laporan Tahunan
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pilih User untuk Lihat Riwayat Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Kamar</th>
                            <th>Iuran/Bulan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->user->name }}</td>
                            <td>{{ $user->user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->room_number ?? '-' }}</td>
                            <td>Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.reports.user.history', $user) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-history"></i> Lihat Riwayat
                                </a>
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
