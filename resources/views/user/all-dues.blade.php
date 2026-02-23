@extends('layouts.user')

@section('title', 'Tunggakan Semua User')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Tunggakan Semua User</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama User</th>
                                    <th>Kamar</th>
                                    <th>Iuran Bulanan</th>
                                    <th>Total Dibayar</th>
                                    <th>Bulan Sudah Bayar</th>
                                    <th>Bulan Tunggakan</th>
                                    <th>Tunggakan (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="align-middle">{{ $user->user->name }}</td>
                                    <td class="align-middle">{{ $user->room_number }}</td>
                                    <td class="align-middle">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</td>
                                    <td class="align-middle">Rp {{ number_format($user->total_paid, 0, ',', '.') }}</td>
                                    <td class="align-middle">
                                        @if(count($user->paid_months))
                                            <span class="badge bg-success">{{ implode(', ', $user->paid_months) }}</span>
                                        @else
                                            <span class="text-danger">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if(count($user->unpaid_months))
                                            <span class="badge bg-danger">{{ implode(', ', $user->unpaid_months) }}</span>
                                        @else
                                            <span class="text-success">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="align-middle fw-bold {{ $user->remaining > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $user->remaining > 0 ? 'Rp ' . number_format($user->remaining, 0, ',', '.') : 'LUNAS' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
