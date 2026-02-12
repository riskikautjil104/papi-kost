@extends('layouts.user')

@section('title', 'Kwitansi Digital')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
                <div>
                    <h4 class="mb-1"><i class="fas fa-receipt me-2"></i>Kwitansi Digital</h4>
                    <p class="text-muted mb-0">Kwitansi pembayaran iuran Anda</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm mt-2 mt-sm-0">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-3">
                            <i class="fas fa-file-invoice text-primary fa-lg mb-1"></i>
                            <h5 class="mb-0">{{ $receipts->total() }}</h5>
                            <small class="text-muted">Total Kwitansi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-3">
                            <i class="fas fa-money-bill-wave text-success fa-lg mb-1"></i>
                            <h5 class="mb-0">Rp {{ number_format($receipts->sum('amount'), 0, ',', '.') }}</h5>
                            <small class="text-muted">Total Dibayar</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center py-3">
                            <i class="fas fa-door-open text-info fa-lg mb-1"></i>
                            <h5 class="mb-0">Kamar {{ $userExtended->room_number ?? '-' }}</h5>
                            <small class="text-muted">{{ $userExtended->user->name }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipts List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Kwitansi</h5>
                </div>
                <div class="card-body p-0">
                    @if($receipts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Kwitansi</th>
                                    <th>Periode</th>
                                    <th>Jumlah</th>
                                    <th class="d-none d-md-table-cell">Metode</th>
                                    <th class="d-none d-lg-table-cell">Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($receipts as $receipt)
                                <tr>
                                    <td class="align-middle">
                                        <strong class="text-primary">{{ $receipt->receipt_number }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-info">{{ $receipt->period }}</span>
                                    </td>
                                    <td class="align-middle fw-bold text-success">
                                        Rp {{ number_format($receipt->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $receipt->payment_method_label }}
                                    </td>
                                    <td class="align-middle d-none d-lg-table-cell">
                                        <small>{{ $receipt->issued_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('user.receipts.preview', $receipt) }}" 
                                               class="btn btn-outline-info" title="Preview" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.receipts.download', $receipt) }}" 
                                               class="btn btn-outline-success" title="Download">
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
                        <p class="text-muted">Kwitansi akan otomatis dibuat saat pembayaran Anda disetujui admin.</p>
                        <a href="{{ route('user.payments.create', $userExtended->id) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Lakukan Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
