@extends('layouts.admin')

@section('title', 'Edit Penghuni')

@section('content')
<div class="page-header">
    <h1>Edit Penghuni</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Penghuni</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Formulir Edit Penghuni</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Akun -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-circle me-2"></i>Informasi Akun
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Kontak -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-address-book me-2"></i>Informasi Kontak
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact" class="form-label">Kontak Darurat</label>
                                <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror"
                                       id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" placeholder="08xxxxxxxxxx">
                                @error('emergency_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" placeholder="Alamat lengkap penghuni">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informasi Kontrakan -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-home me-2"></i>Informasi Kontrakan
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="room_number" class="form-label">Nomor Kamar</label>
                                <input type="text" class="form-control @error('room_number') is-invalid @enderror"
                                       id="room_number" name="room_number" value="{{ old('room_number', $user->room_number) }}" placeholder="A-101">
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="monthly_fee" class="form-label">Iuran Bulanan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror"
                                           id="monthly_fee" name="monthly_fee" value="{{ old('monthly_fee', $user->monthly_fee) }}" placeholder="500000" required>
                                    @error('monthly_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="join_date" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('join_date') is-invalid @enderror"
                                       id="join_date" name="join_date" value="{{ old('join_date', $user->join_date) }}" required>
                                @error('join_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contract_end_date" class="form-label">Tanggal Berakhir Kontrak <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('contract_end_date') is-invalid @enderror"
                                       id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date', $user->contract_end_date) }}" required>
                                @error('contract_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="blocked" {{ old('status', $user->status) === 'blocked' ? 'selected' : '' }}>Diblokir</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-sticky-note me-2"></i>Catatan Tambahan
                        </h6>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3" placeholder="Catatan khusus tentang penghuni (opsional)">{{ old('notes', $user->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-admin">
                            <i class="fas fa-save me-1"></i> Update Penghuni
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Info Panel -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Penghuni
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar mx-auto mb-2" style="background: linear-gradient(135deg, #4f46e5, #818cf8); width: 60px; height: 60px; font-size: 24px;">
                        {{ substr($user->user->name, 0, 1) }}
                    </div>
                    <h6 class="mb-1">{{ $user->user->name }}</h6>
                    <small class="text-muted">{{ $user->user->email }}</small>
                </div>

                <hr>

                <div class="small">
                    <div class="row mb-2">
                        <div class="col-5"><strong>Kamar:</strong></div>
                        <div class="col-7">{{ $user->room_number ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Iuran:</strong></div>
                        <div class="col-7">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Status:</strong></div>
                        <div class="col-7">
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : ($user->status === 'blocked' ? 'danger' : 'secondary')) }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Masuk:</strong></div>
                        <div class="col-7">{{ date('d/m/Y', strtotime($user->join_date)) }}</div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-5"><strong>Berakhir:</strong></div>
                        <div class="col-7">{{ date('d/m/Y', strtotime($user->contract_end_date)) }}</div>
                    </div>
                </div>

                <hr>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Perhatian:</h6>
                    <ul class="mb-0 small">
                        <li>Perubahan akan langsung tersimpan</li>
                        <li>Email dan telepon harus unik</li>
                        <li>Pastikan data kontrak akurat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number input
    const monthlyFeeInput = document.getElementById('monthly_fee');
    monthlyFeeInput.addEventListener('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Auto format phone number
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        // Remove non-numeric characters except +
        this.value = this.value.replace(/[^0-9]/g, '');
        // Add 0 prefix if not present
        if (this.value && !this.value.startsWith('0')) {
            this.value = '0' + this.value;
        }
    });

    // Set minimum date for join_date
    const joinDateInput = document.getElementById('join_date');
    const today = new Date().toISOString().split('T')[0];
    joinDateInput.setAttribute('max', today);

    // Set minimum date for contract_end_date based on join_date
    joinDateInput.addEventListener('change', function() {
        const contractEndInput = document.getElementById('contract_end_date');
        contractEndInput.setAttribute('min', this.value);
    });
});
</script>
@endpush
