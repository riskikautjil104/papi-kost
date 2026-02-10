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

@if(session('status'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    <!-- Profile Card with Photo -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($user->usersExtended?->profile_photo_url)
                    <img src="{{ $user->usersExtended->profile_photo_url }}" 
                         alt="{{ $user->name }}" 
                         class="rounded-circle mx-auto mb-3 d-block"
                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #0066FF;">
                @else
                    <div class="avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge bg-success">Aktif</span>
            </div>
        </div>

        <!-- Upload Photo Form -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-camera me-2"></i>Update Foto Profil</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="profile_photo" class="form-control" accept="image/*" required>
                        <div class="form-text">Max 2MB (JPG, PNG)</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload me-1"></i> Upload Foto
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Edit Profile Form -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profil</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    @if($user->usersExtended)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->usersExtended->phone) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->usersExtended->address ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Kontak Darurat</label>
                            <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $user->usersExtended->emergency_contact_name ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon Kontak Darurat</label>
                            <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $user->usersExtended->emergency_contact ?? '') }}">
                        </div>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card (Read-only) -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Penyewa</h5>
            </div>
            <div class="card-body">
                @if($user->usersExtended)
                <div class="row">
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
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Data penyewa belum lengkap. Silakan hubungi admin.
                </div>
                @endif
            </div>
        </div>
        
        <!-- Change Password Form -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Ganti Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-1"></i> Ganti Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
