@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="page-header">
    <h1>Manajemen Penghuni</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Penghuni</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <h5 class="mb-0">Daftar Penghuni Kontrakan</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end gap-2">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-admin btn-sm">
                        <i class="fas fa-plus me-1"></i> 
                        <span class="d-none d-sm-inline">Tambah Penghuni</span>
                        <span class="d-inline d-sm-none">Tambah</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Filter & Search -->
        <form method="GET" class="row g-2 mb-4">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, email, atau telepon..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="all">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Diblokir</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-search"></i> <span class="d-none d-md-inline">Cari</span>
                </button>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-redo"></i> <span class="d-none d-md-inline">Reset</span>
                </a>
            </div>
        </form>
        
        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="d-none d-md-table-cell">No</th>
                        <th>Nama</th>
                        <th class="d-none d-lg-table-cell">Kontak</th>
                        <th class="d-none d-xl-table-cell">Kamar</th>
                        <th class="d-none d-lg-table-cell">Iuran Bulanan</th>
                        <th class="d-none d-xl-table-cell">Tgl Berakhir</th>
                        <th class="d-none d-md-table-cell">Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="d-none d-md-table-cell align-middle">{{ $users->firstItem() + $index }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                @if($user->profile_photo_url)
                                    <img src="{{ $user->profile_photo_url }}" 
                                         alt="{{ $user->user->name }}" 
                                         class="rounded-circle me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="avatar me-2" style="background: linear-gradient(135deg, #4f46e5, #818cf8);">
                                        {{ substr($user->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $user->user->name }}</strong>
                                    <small class="d-block text-muted">{{ $user->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-lg-table-cell align-middle">
                            <i class="fas fa-phone text-muted me-1"></i> {{ $user->phone }}<br>
                            <small class="text-muted">Masuk: {{ date('d/m/Y', strtotime($user->join_date)) }}</small>
                        </td>
                        <td class="d-none d-xl-table-cell align-middle">{{ $user->room_number ?? '-' }}</td>
                        <td class="d-none d-lg-table-cell align-middle">Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</td>
                        <td class="d-none d-xl-table-cell align-middle">
                            {{ date('d/m/Y', strtotime($user->contract_end_date)) }}
                            @if(now()->diffInDays($user->contract_end_date) < 30)
                            <span class="badge bg-warning ms-1">Segera Berakhir</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell align-middle">
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : ($user->status === 'blocked' ? 'danger' : 'secondary')) }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus penghuni ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada penghuni kontrakan</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-admin mt-2">
                                <i class="fas fa-plus me-1"></i> Tambah Penghuni Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} penghuni
            </small>
            {{ $users->appends(request()->query())->links() }}
        </div>
</div>
@endsection
