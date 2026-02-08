@extends('layouts.user')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Pembayaran</h5>
                    <a href="{{ route('user.payments.create', $user->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Bayar
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->month_name }} {{ $payment->year }}</span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ ucfirst($payment->payment_method) }}
                                        @if($payment->bank_name)
                                        <small class="d-block text-muted">{{ $payment->bank_name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->isPending())
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($payment->isApproved())
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $payment->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        @if($payment->proof_image)
                                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Bukti">
                                            <i class="fas fa-image"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($payments->hasPages())
                    <div class="card-footer bg-white">
                        {{ $payments->links() }}
                    </div>
                    @endif
                    
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Pembayaran</h5>
                        <p class="text-muted">Anda belum melakukan pembayaran apapun.</p>
                        <a href="{{ route('user.payments.create', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Lakukan Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Ringkasan</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-warning mb-0">{{ $payments->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Menunggu</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success mb-0">{{ $payments->where('status', 'approved')->count() }}</h4>
                            <small class="text-muted">Disetujui</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary mb-0">Rp {{ number_format($payments->where('status', 'approved')->sum('amount'), 0, ',', '.') }}</h4>
                            <small class="text-muted">Total Dibayar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
