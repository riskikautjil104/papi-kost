@extends('layouts.admin')

@section('title', 'Detail Kwitansi')

@push('styles')
<style>
    .receipt-preview-box {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .receipt-desc-box {
        background-color: #ffffff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    [data-theme="dark"] .receipt-preview-box {
        background-color: var(--bg-tertiary) !important;
        border-color: var(--border-color) !important;
    }
    [data-theme="dark"] .receipt-desc-box {
        background-color: var(--bg-secondary) !important;
        border-color: var(--border-color) !important;
    }
    [data-theme="dark"] .receipt-preview-box .table-borderless td {
        color: var(--text-primary) !important;
    }
    [data-theme="dark"] .receipt-preview-box .border-top {
        border-top-color: var(--border-color) !important;
    }
    [data-theme="dark"] .receipt-preview-box .border-success {
        border-color: var(--success-color) !important;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="fas fa-receipt me-2"></i>Detail Kwitansi</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.receipts.index') }}">Kwitansi</a></li>
            <li class="breadcrumb-item active">{{ $receipt->receipt_number }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Receipt Preview Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>{{ $receipt->receipt_number }}</h5>
                <span class="badge bg-success fs-6">
                    <i class="fas fa-check-circle me-1"></i> LUNAS
                </span>
            </div>
            <div class="card-body">
                <!-- Receipt Content Preview -->
                <div class="border rounded p-4 receipt-preview-box mb-3">
                    <div class="text-center mb-4">
                        <h3 class="text-primary mb-1">KWITANSI</h3>
                        <small class="text-muted">Kontrakan D-TECT — Bukti Pembayaran Resmi</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 130px;">No. Kwitansi</td>
                                    <td>: {{ $receipt->receipt_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Diterima Dari</td>
                                    <td>: <strong>{{ $receipt->tenant_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Periode</td>
                                    <td>: {{ $receipt->period }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 130px;">Tanggal</td>
                                    <td>: {{ $receipt->issued_at->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kamar</td>
                                    <td>: {{ $receipt->room_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Metode Bayar</td>
                                    <td>: {{ $receipt->payment_method_label }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="bg-primary text-white rounded p-3 text-center mb-3">
                        <small class="text-uppercase" style="opacity: 0.8; letter-spacing: 1px;">Jumlah Pembayaran</small>
                        <h2 class="mb-1">Rp {{ number_format($receipt->amount, 0, ',', '.') }}</h2>
                        @php
                            $terbilang = App\Models\PaymentReceipt::terbilang((float) $receipt->amount) . ' Rupiah';
                        @endphp
                        <small style="opacity: 0.9;"><em>( {{ $terbilang }} )</em></small>
                    </div>

                    <div class="border rounded p-3 mb-3 receipt-desc-box">
                        <small class="text-uppercase text-muted fw-bold" style="letter-spacing: 1px;">Untuk Pembayaran</small>
                        <p class="mb-0 mt-1">{{ $receipt->description ?? 'Pembayaran iuran bulanan kontrakan' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="border border-success text-success rounded px-3 py-2 d-inline-block fw-bold" style="transform: rotate(-3deg);">
                                ✓ LUNAS
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="mt-4 pt-3 border-top d-inline-block" style="min-width: 150px;">
                                <strong>{{ $receipt->approved_by_name }}</strong><br>
                                <small class="text-muted">Pengelola Kontrakan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Proof Link -->
        @if($receipt->paymentProof)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-link me-2"></i>Bukti Pembayaran Terkait</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1"><strong>Payment #{{ $receipt->paymentProof->id }}</strong></p>
                        <small class="text-muted">
                            {{ $receipt->paymentProof->month_name }} {{ $receipt->paymentProof->year }} — 
                            Rp {{ number_format($receipt->paymentProof->amount, 0, ',', '.') }}
                        </small>
                    </div>
                    <a href="{{ route('admin.payments.show', $receipt->paymentProof) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.receipts.preview', $receipt) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-file-pdf me-1"></i> Preview PDF
                    </a>
                    <a href="{{ route('admin.receipts.download', $receipt) }}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                    @if($receipt->paymentProof)
                    <form action="{{ route('admin.receipts.regenerate', $receipt->paymentProof) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Yakin ingin generate ulang kwitansi ini?')">
                            <i class="fas fa-sync-alt me-1"></i> Generate Ulang
                        </button>
                    </form>
                    @endif
                    <hr>
                    <a href="{{ route('admin.receipts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Receipt Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">No. Kwitansi</span>
                        <strong>{{ $receipt->receipt_number }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Dibuat</span>
                        <strong>{{ $receipt->issued_at->format('d M Y H:i') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Disetujui oleh</span>
                        <strong>{{ $receipt->approved_by_name }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Penghuni</span>
                        <strong>{{ $receipt->tenant_name }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Kamar</span>
                        <span class="badge bg-info">{{ $receipt->room_number ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
