@extends('layouts.admin')

@section('title', 'Pembayaran Menunggu Persetujuan')

@section('content')
<div class="page-header">
    <h1>Pembayaran Menunggu Persetujuan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Pembayaran</a></li>
            <li class="breadcrumb-item active">Pending</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Daftar Pembayaran Pending</h5>
                <span class="badge bg-warning">{{ $pendingPayments->total() }} Menunggu</span>
            </div>
            <div class="card-body p-0">
                @if($pendingPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Penghuni</th>
                                <th>Periode</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Tanggal Upload</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPayments as $payment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($payment->userExtended?->profile_photo_url)
                                            <img src="{{ $payment->userExtended->profile_photo_url }}" 
                                                 alt="{{ $payment->userExtended?->user?->name ?? 'User' }}" 
                                                 class="rounded-circle me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar me-2">
                                                {{ substr($payment->userExtended?->user?->name ?? 'N/A', 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $payment->userExtended?->user?->name ?? 'User Tidak Ditemukan' }}</strong>
                                            <small class="d-block text-muted">{{ $payment->userExtended?->user?->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $payment->month_name }} {{ $payment->year }}</span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                    @if($payment->bank_name)
                                    <small class="d-block text-muted">{{ $payment->bank_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $payment->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui" onclick="return confirm('Yakin ingin menyetujui pembayaran ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" title="Tolak" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payment->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($pendingPayments->hasPages())
                <div class="card-footer bg-white">
                    {{ $pendingPayments->links() }}
                </div>
                @endif
                
                @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-muted">Tidak Ada Pembayaran Pending</h4>
                    <p class="text-muted">Semua pembayaran sudah diproses!</p>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-admin mt-2">
                        <i class="fas fa-list me-1"></i> Lihat Semua Pembayaran
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modals -->
@foreach($pendingPayments as $payment)
<div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak pembayaran dari <strong>{{ $payment->userExtended?->user?->name ?? 'Unknown' }}</strong> sebesar <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason{{ $payment->id }}" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason{{ $payment->id }}" rows="3" class="form-control" required minlength="10" placeholder="Jelaskan alasan penolakan minimal 10 karakter..."></textarea>
                        <div class="form-text">Minimal 10 karakter</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
