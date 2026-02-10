@extends('layouts.admin')

@section('title', 'Detail Penghuni')

@section('content')
<div class="page-header">
    <h1>Detail Penghuni</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Penghuni</a></li>
            <li class="breadcrumb-item active">{{ $user->user->name }}</li>
        </ol>
    </nav>
</div>

<div class="row g-3">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Penghuni</h5>
            </div>
            <div class="card-body text-center">
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->user->name }}" 
                         class="rounded-circle mx-auto mb-3 d-block"
                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #4f46e5;">
                @else
                    <div class="avatar mx-auto mb-3" style="background: linear-gradient(135deg, #4f46e5, #818cf8); width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->user->name, 0, 1) }}
                    </div>
                @endif
                <h5 class="mb-1">{{ $user->user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->user->email }}</p>

                <div class="mb-3">
                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : ($user->status === 'blocked' ? 'danger' : 'secondary')) }} fs-6">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Yakin ingin menghapus penghuni ini?')">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistik Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stat-item">
                            <h4 class="text-primary">{{ $user->paymentProofs->where('status', 'approved')->count() }}</h4>
                            <small class="text-muted">Pembayaran<br>Diterima</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <h4 class="text-warning">{{ $user->paymentProofs->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Menunggu<br>Approval</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <div class="stat-item">
                            <h5 class="text-success">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h5>
                            <small class="text-muted">Total Dibayar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <p class="mb-0">{{ $user->user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0">{{ $user->user->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Telepon</label>
                            <p class="mb-0">{{ $user->phone }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kontak Darurat</label>
                            <p class="mb-0">{{ $user->emergency_contact ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Kamar</label>
                            <p class="mb-0">{{ $user->room_number ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Iuran Bulanan</label>
                            <p class="mb-0 text-success fw-bold">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Masuk</label>
                            <p class="mb-0">{{ date('d F Y', strtotime($user->join_date)) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kontrak Berakhir</label>
                            <p class="mb-0">
                                {{ date('d F Y', strtotime($user->contract_end_date)) }}
                                @if(now()->diffInDays($user->contract_end_date) < 30)
                                    <span class="badge bg-warning ms-2">Segera Berakhir</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if($user->address)
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Lengkap</label>
                    <p class="mb-0">{{ $user->address }}</p>
                </div>
                @endif

                @if($user->notes)
                <div class="mb-0">
                    <label class="form-label fw-bold">Catatan</label>
                    <p class="mb-0">{{ $user->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Pembayaran</h5>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary active" onclick="filterPayments('all')">Semua</button>
                    <button type="button" class="btn btn-outline-success" onclick="filterPayments('approved')">Diterima</button>
                    <button type="button" class="btn btn-outline-warning" onclick="filterPayments('pending')">Pending</button>
                    <button type="button" class="btn btn-outline-danger" onclick="filterPayments('rejected')">Ditolak</button>
                </div>
            </div>
            <div class="card-body">
                @if($paymentHistory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="paymentTable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Periode</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentHistory as $payment)
                                <tr class="payment-row" data-status="{{ $payment->status }}">
                                    <td>{{ date('d/m/Y H:i', strtotime($payment->created_at)) }}</td>
                                    <td class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>{{ $payment->month_name }} {{ $payment->year }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->proof_image)
                                            <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @endif
                                        @if($payment->status === 'pending')
                                            <div class="btn-group btn-group-sm">
                                                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $paymentHistory->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada riwayat pembayaran</h5>
                        <p class="text-muted">Penghuni belum melakukan pembayaran apapun</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterPayments(status) {
    const rows = document.querySelectorAll('.payment-row');
    const buttons = document.querySelectorAll('.btn-group-sm .btn');

    // Remove active class from all buttons
    buttons.forEach(btn => btn.classList.remove('active'));

    // Add active class to clicked button
    event.target.classList.add('active');

    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.status === status ? '' : 'none';
        }
    });
}

// Initialize - show all payments
document.addEventListener('DOMContentLoaded', function() {
    filterPayments('all');
});
</script>
@endpush
