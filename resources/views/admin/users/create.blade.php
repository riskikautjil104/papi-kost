@extends('layouts.admin')

@section('title', 'Tambah Penghuni')

@section('content')
<div class="page-header">
    <h1>Tambah Penghuni Baru</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Penghuni</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Formulir Tambah Penghuni</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <!-- Informasi Akun -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-circle me-2"></i>Informasi Akun
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                                @error('password_confirmation')
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
                                       id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact" class="form-label">Kontak Darurat</label>
                                <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror"
                                       id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="08xxxxxxxxxx">
                                @error('emergency_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" placeholder="Alamat lengkap penghuni">{{ old('address') }}</textarea>
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
                                       id="room_number" name="room_number" value="{{ old('room_number') }}" placeholder="A-101">
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="monthly_fee" class="form-label">Iuran Bulanan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror"
                                           id="monthly_fee" name="monthly_fee" value="{{ old('monthly_fee') }}" placeholder="500000" required>
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
                                       id="join_date" name="join_date" value="{{ old('join_date') }}" required>
                                @error('join_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contract_end_date" class="form-label">Tanggal Berakhir Kontrak <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('contract_end_date') is-invalid @enderror"
                                       id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date') }}" required>
                                @error('contract_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                      id="notes" name="notes" rows="3" placeholder="Catatan khusus tentang penghuni (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-admin">
                            <i class="fas fa-save me-1"></i> Simpan Penghuni
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Preview Panel -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Preview Profil
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="avatar mx-auto mb-3" id="preview-avatar" style="background: linear-gradient(135deg, #4f46e5, #818cf8); width: 80px; height: 80px; font-size: 32px;">
                    ?
                </div>
                <h6 class="mb-1" id="preview-name">Nama Penghuni</h6>
                <p class="text-muted mb-2" id="preview-email">email@contoh.com</p>

                <div class="mb-3">
                    <span class="badge bg-success fs-6">Aktif</span>
                </div>

                <div class="small text-muted">
                    <div class="row mb-2">
                        <div class="col-5"><strong>Kamar:</strong></div>
                        <div class="col-7" id="preview-room">-</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Iuran:</strong></div>
                        <div class="col-7" id="preview-fee">Rp 0</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Masuk:</strong></div>
                        <div class="col-7" id="preview-join">-</div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-5"><strong>Berakhir:</strong></div>
                        <div class="col-7" id="preview-end">-</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Panduan Pengisian
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 small">
                    <li><strong>Email</strong> harus unik dan aktif</li>
                    <li><strong>Telepon</strong> gunakan format Indonesia (08xxx)</li>
                    <li><strong>Iuran bulanan</strong> dapat bervariasi per orang</li>
                    <li><strong>Tanggal kontrak</strong> akan menentukan masa sewa</li>
                    <li><strong>Password</strong> minimal 8 karakter</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time preview
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const roomInput = document.getElementById('room_number');
    const feeInput = document.getElementById('monthly_fee');
    const joinInput = document.getElementById('join_date');
    const endInput = document.getElementById('contract_end_date');

    function updatePreview() {
        // Update avatar initial
        const name = nameInput.value || '?';
        document.getElementById('preview-avatar').textContent = name.charAt(0).toUpperCase();

        // Update name and email
        document.getElementById('preview-name').textContent = nameInput.value || 'Nama Penghuni';
        document.getElementById('preview-email').textContent = emailInput.value || 'email@contoh.com';

        // Update room
        document.getElementById('preview-room').textContent = roomInput.value || '-';

        // Update fee
        const fee = feeInput.value ? 'Rp ' + new Intl.NumberFormat('id-ID').format(feeInput.value) : 'Rp 0';
        document.getElementById('preview-fee').textContent = fee;

        // Update dates
        document.getElementById('preview-join').textContent = joinInput.value ? new Date(joinInput.value).toLocaleDateString('id-ID') : '-';
        document.getElementById('preview-end').textContent = endInput.value ? new Date(endInput.value).toLocaleDateString('id-ID') : '-';
    }

    // Add event listeners
    [nameInput, emailInput, roomInput, feeInput, joinInput, endInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    // Format number input
    feeInput.addEventListener('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        updatePreview();
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
    const today = new Date().toISOString().split('T')[0];
    joinInput.setAttribute('max', today);

    // Set minimum date for contract_end_date based on join_date
    joinInput.addEventListener('change', function() {
        endInput.setAttribute('min', this.value);
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    function checkPasswordMatch() {
        if (passwordInput.value && confirmInput.value) {
            if (passwordInput.value === confirmInput.value) {
                confirmInput.classList.remove('is-invalid');
                confirmInput.classList.add('is-valid');
            } else {
                confirmInput.classList.remove('is-valid');
                confirmInput.classList.add('is-invalid');
            }
        } else {
            confirmInput.classList.remove('is-valid', 'is-invalid');
        }
    }

    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);

    // Initialize preview
    updatePreview();
});
</script>
@endpush
