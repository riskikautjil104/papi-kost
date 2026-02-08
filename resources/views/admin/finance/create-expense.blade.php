@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="page-header">
    <h1>Tambah Pengeluaran</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.finance.index') }}">Keuangan</a></li>
            <li class="breadcrumb-item active">Tambah Pengeluaran</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.finance.expense.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori Pengeluaran <span class="text-danger">*</span></label>
                        <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount') }}" required min="0" step="0.01" placeholder="Masukkan jumlah pengeluaran">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" id="expense_date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" 
                               value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Jelaskan detail pengeluaran...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bukti/Kwitansi -->
                    <div class="mb-3">
                        <label for="receipt_image" class="form-label">Bukti/Kwitansi (Opsional)</label>
                        <input type="file" id="receipt_image" name="receipt_image" accept="image/*" 
                               class="form-control @error('receipt_image') is-invalid @enderror">
                        <div class="form-text">Format: JPEG, PNG, JPG. Maksimal 2MB.</div>
                        @error('receipt_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Preview Image -->
                    <div id="image-preview" class="mb-3 d-none">
                        <label class="form-label">Preview:</label>
                        <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-admin">
                            <i class="fas fa-save me-1"></i> Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">
                    Pengeluaran yang dicatat akan langsung mengurangi saldo wallet dan tercatat dalam laporan keuangan.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('receipt_image').addEventListener('change', function(e) {
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
</script>
@endpush
@endsection
