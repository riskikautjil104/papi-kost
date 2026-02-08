@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <h1>Profil Saya</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge bg-success">Aktif</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Penyewa</h5>
            </div>
            <div class="card-body">
                @if($user->usersExtended)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Nomor Telepon</label>
                        <p class="mb-0 fw-bold">{{ $user->usersExtended->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Nomor Kamar</label>
                        <p class="mb-0 fw-bold">{{ $user->usersExtended->room_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Iuran Bulanan</label>
                        <p class="mb-0 fw-bold">Rp {{ number_format($user->usersExtended->monthly_fee, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $user->usersExtended->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($user->usersExtended->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tanggal Bergabung</label>
                        <p class="mb-0 fw-bold">{{ $user->usersExtended->join_date->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Kontrak Berakhir</label>
                        <p class="mb-0 fw-bold">{{ $user->usersExtended->contract_end_date->format('d M Y') }}</p>
                    </div>
                    @if($user->usersExtended->address)
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Alamat</label>
                        <p class="mb-0">{{ $user->usersExtended->address }}</p>
                    </div>
                    @endif
                    @if($user->usersExtended->emergency_contact)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Kontak Darurat</label>
                        <p class="mb-0 fw-bold">{{ $user->usersExtended->emergency_contact }}</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Data penyewa belum lengkap. Silakan hubungi admin.
                </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Keamanan</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Untuk mengubah password, silakan gunakan fitur "Lupa Password" di halaman login.</p>
                <a href="{{ route('password.request') }}" class="btn btn-outline-primary">
                    <i class="fas fa-key me-1"></i> Ubah Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
