@extends('layouts.user')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran</h5>
                </div>
                <div class="card-body">
                    <!-- User Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $user->user->name }}</strong>
                                <p class="mb-0 small">Kamar: {{ $user->room_number ?? '-' }} | Iuran: Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}/bulan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status Card -->
                    <div class="card mb-4 border-primary">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Status Pembayaran</h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="small text-muted">Iuran Bulanan</div>
                                    <div class="fw-bold">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="small text-muted">Sudah Dibayar</div>
                                    <div class="fw-bold text-success" id="paid-amount">Rp 0</div>
                                </div>
                                <div class="col-4">
                                    <div class="small text-muted">Sisa Bayar</div>
                                    <div class="fw-bold text-danger" id="remaining-amount">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <span id="payment-status-badge" class="badge bg-warning">Belum Lunas</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.payments.store', $user->id) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Periode -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="month" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <select id="month" name="month" class="form-select @error('month') is-invalid @enderror" required>
                                    @foreach($months as $key => $monthName)
                                        <option value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>
                                            {{ $monthName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" id="year" name="year" class="form-control @error('year') is-invalid @enderror" 
                                       value="{{ $currentYear }}" required min="2020" max="2100">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Pembayaran (Rp) <span class="text-danger">*</span></label>
                            <input type="number" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                   value="{{ old('amount', $user->monthly_fee) }}" required min="0" step="0.01">
                            <div class="form-text">
                                Iuran bulanan: Rp {{ number_format($user->monthly_fee, 0, ',', '.') }} | 
                                Sisa yang harus dibayar: <span class="text-danger fw-bold" id="sisa-display">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</span>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select id="payment_method" name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Tunai</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Info (conditional) -->
                        <div id="bank-info" class="mb-3">
                            <label for="bank_name" class="form-label">Nama Bank</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                                   value="{{ old('bank_name') }}" placeholder="Contoh: BCA, Mandiri, BNI">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_number" class="form-label">Nomor Rekening</label>
                            <input type="text" id="account_number" name="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                                   value="{{ old('account_number') }}" placeholder="Nomor rekening pengirim">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bukti Pembayaran -->
                        <div class="mb-3">
                            <label for="proof_image" class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                            <input type="file" id="proof_image" name="proof_image" accept="image/*" 
                                   class="form-control @error('proof_image') is-invalid @enderror" required>
                            <div class="form-text">Format: JPEG, PNG, JPG. Maksimal 2MB.</div>
                            @error('proof_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview -->
                        <div id="image-preview" class="mb-3 d-none">
                            <label class="form-label">Preview:</label>
                            <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan (Opsional)</label>
                            <textarea id="description" name="description" rows="2" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Tambahkan keterangan jika perlu...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('duplicate')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="d-flex gap-2">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('proof_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });

    // Show/hide bank info based on payment method
    document.getElementById('payment_method').addEventListener('change', function() {
        const bankInfo = document.getElementById('bank-info');
        if (this.value === 'transfer') {
            bankInfo.style.display = 'block';
        } else {
            bankInfo.style.display = 'none';
        }
    });

    // Payment status tracking for multi-payment
    const monthlyFee = {{ $user->monthly_fee }};
    const existingPayments = @json($existingPayments ?? []);
    
    function updatePaymentStatus() {
        const month = parseInt(document.getElementById('month').value);
        const year = parseInt(document.getElementById('year').value);
        
        // Calculate total paid for selected month/year
        let totalPaid = 0;
        existingPayments.forEach(payment => {
            if (payment.month == month && payment.year == year && payment.status === 'approved') {
                totalPaid += parseFloat(payment.amount);
            }
        });
        
        const remaining = Math.max(0, monthlyFee - totalPaid);
        const isFullyPaid = remaining <= 0;
        
        // Update display
        document.getElementById('paid-amount').textContent = 'Rp ' + totalPaid.toLocaleString('id-ID');
        document.getElementById('remaining-amount').textContent = 'Rp ' + remaining.toLocaleString('id-ID');
        document.getElementById('sisa-display').textContent = 'Rp ' + remaining.toLocaleString('id-ID');
        
        // Update badge
        const badge = document.getElementById('payment-status-badge');
        if (isFullyPaid) {
            badge.className = 'badge bg-success';
            badge.textContent = 'Lunas';
        } else if (totalPaid > 0) {
            badge.className = 'badge bg-warning';
            badge.textContent = 'Sebagian (' + ((totalPaid/monthlyFee)*100).toFixed(0) + '%)';
        } else {
            badge.className = 'badge bg-danger';
            badge.textContent = 'Belum Bayar';
        }
        
        // Update amount input placeholder and max
        const amountInput = document.getElementById('amount');
        amountInput.placeholder = 'Sisa: Rp ' + remaining.toLocaleString('id-ID');
        amountInput.max = remaining;
        
        // Auto-fill with remaining amount if not manually changed
        if (!amountInput.dataset.manual) {
            amountInput.value = remaining;
        }
    }
    
    // Track manual changes to amount
    document.getElementById('amount').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
    
    // Update on month/year change
    document.getElementById('month').addEventListener('change', updatePaymentStatus);
    document.getElementById('year').addEventListener('change', updatePaymentStatus);
    
    // Initial update
    updatePaymentStatus();
</script>
@endpush
@endsection
