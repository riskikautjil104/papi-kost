@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="page-header">
    <h1>Detail Pembayaran</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Pembayaran</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Payment Details Card -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Informasi Pembayaran</h5>
                <span class="badge bg-{{ $payment->status_badge }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Penghuni</label>
                        <p class="mb-1 fw-bold">{{ $payment->userExtended?->user?->name ?? 'User Tidak Ditemukan' }}</p>
                        <small class="text-muted">{{ $payment->userExtended?->user?->email ?? '-' }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">No. Telepon</label>
                        <p class="mb-0 fw-bold">{{ $payment->userExtended?->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Periode</label>
                        <p class="mb-0 fw-bold">{{ $payment->month_name }} {{ $payment->year }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Kamar</label>
                        <p class="mb-0 fw-bold">{{ $payment->userExtended?->room_number ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Jumlah Pembayaran</label>
                        <p class="mb-0 fw-bold text-success fs-5">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Iuran Bulanan</label>
                        <p class="mb-0 fw-bold">Rp {{ number_format($payment->userExtended?->monthly_fee ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Metode Pembayaran</label>
                        <p class="mb-0 fw-bold">{{ ucfirst($payment->payment_method) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Bank</label>
                        <p class="mb-0 fw-bold">{{ $payment->bank_name ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">No. Rekening</label>
                        <p class="mb-0 fw-bold">{{ $payment->account_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tanggal Upload</label>
                        <p class="mb-0 fw-bold">{{ $payment->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($payment->description)
                <div class="mb-3">
                    <label class="text-muted small">Keterangan</label>
                    <p class="mb-0">{{ $payment->description }}</p>
                </div>
                @endif

                @if($payment->isApproved())
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Disetujui oleh:</strong> {{ $payment->approver->name ?? 'Admin' }}<br>
                    <small>{{ $payment->approved_at?->format('d M Y H:i') }}</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Proof Image -->
        @if($payment->proof_image)
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Bukti Pembayaran</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 500px;">
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Full Size
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Actions Card -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
            </div>
            <div class="card-body">
                @if($payment->isPending())
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Yakin ingin menyetujui pembayaran ini?')">
                            <i class="fas fa-check me-1"></i> Setujui Pembayaran
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-1"></i> Tolak Pembayaran
                    </button>
                </div>
                @elseif($payment->isApproved())
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle me-2"></i>Pembayaran sudah disetujui
                </div>
                @else
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-times-circle me-2"></i>Pembayaran ditolak
                </div>
                @endif

                <hr>

                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Info Penghuni</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar avatar-lg mx-auto mb-2">
                        {{ substr($payment->userExtended?->user?->name ?? 'N/A', 0, 1) }}
                    </div>
                    <h6 class="mb-0">{{ $payment->userExtended?->user?->name ?? 'User Tidak Ditemukan' }}</h6>
                    <small class="text-muted">{{ $payment->userExtended?->user?->email ?? '-' }}</small>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Kamar</span>
                        <strong>{{ $payment->userExtended?->room_number ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Telepon</span>
                        <strong>{{ $payment->userExtended?->phone ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Iuran/Bulan</span>
                        <strong>Rp {{ number_format($payment->userExtended?->monthly_fee ?? 0, 0, ',', '.') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Status</span>
                        <span class="badge bg-{{ $payment->userExtended?->status_badge ?? 'secondary' }}">
                            {{ ucfirst($payment->userExtended?->status ?? 'unknown') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($payment->isPending())
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
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
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" class="form-control" required minlength="10" placeholder="Jelaskan alasan penolakan minimal 10 karakter..."></textarea>
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
@endif
@endsection
