@extends('layouts.layout')
@section('title', 'Data Siswa - SDN Sawotratap 1')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-users me-2"></i>
                                    Data Siswa
                                </h4>
                                <p class="mb-0 mt-1 opacity-75 small">Kelola data siswa SDN Sawotratap 1</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            @if($isAuthenticated)
                            <a href="{{ route('siswa.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Tambah Siswa
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i>
                                    Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('siswa.export') }}">Export Semua</a></li>
                                    <li><a class="dropdown-item" href="{{ route('siswa.export', ['status' => 'pending']) }}">Export Pending</a></li>
                                    <li><a class="dropdown-item" href="{{ route('siswa.export', ['status' => 'diterima']) }}">Export Diterima</a></li>
                                    <li><a class="dropdown-item" href="{{ route('siswa.export', ['status' => 'ditolak']) }}">Export Ditolak</a></li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Search and Filter Form --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('siswa.index') }}" class="row g-3">
                                <div class="col-lg-6 col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control" name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="Cari nama, NISN, email, atau username...">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6">
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-12 col-6">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-1"></i>
                                        Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Bulk Actions (Admin Only) --}}
                    @if($isAuthenticated)
                    <div class="row mb-3" id="bulkActions" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                <span class="mb-2 mb-md-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="selectedCount">0</span> siswa dipilih
                                </span>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-success" onclick="bulkUpdateStatus('diterima')">
                                        <i class="fas fa-check me-1"></i>
                                        Terima
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="bulkUpdateStatus('pending')">
                                        <i class="fas fa-clock me-1"></i>
                                        Pending
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="bulkUpdateStatus('ditolak')">
                                        <i class="fas fa-times me-1"></i>
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Statistics Cards --}}
                    @if($isAuthenticated)
                    <div class="row mb-4">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                            <div class="card bg-primary text-white h-100 border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1 fw-normal">Total Siswa</h6>
                                            <h4 class="mb-0 fw-bold">{{ $siswa->total() }}</h4>
                                        </div>
                                        <i class="fas fa-users fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                            <div class="card bg-warning text-white h-100 border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1 fw-normal">Pending</h6>
                                            <h4 class="mb-0 fw-bold">{{ $siswa->where('status_pendaftaran', 'pending')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                            <div class="card bg-success text-white h-100 border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1 fw-normal">Diterima</h6>
                                            <h4 class="mb-0 fw-bold">{{ $siswa->where('status_pendaftaran', 'diterima')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                            <div class="card bg-danger text-white h-100 border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1 fw-normal">Ditolak</h6>
                                            <h4 class="mb-0 fw-bold">{{ $siswa->where('status_pendaftaran', 'ditolak')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Data Table --}}
                    @if($siswa->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    @if($isAuthenticated)
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                    @endif
                                    <th width="80">No</th>
                                    <th width="140">NISN</th>
                                    <th>Nama Lengkap</th>
                                    <th width="120">Nilai Ujian</th>
                                    <th width="120" class="text-center">Status</th>
                                    <th width="140" class="text-center">Tanggal Daftar</th>
                                    <th width="160" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa as $index => $item)
                                <tr>
                                    @if($isAuthenticated)
                                    <td>
                                        <input type="checkbox" class="form-check-input siswa-checkbox" value="{{ $item->id }}">
                                    </td>
                                    @endif
                                    <td class="text-center">
                                        <span class="text">{{ $siswa->firstItem() + $index }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $item->nisn }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $item->nama_lengkap }}</strong>
                                            <small class="text-muted">{{ $item->username }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-break">{{ $item->nilai_ujian_nasional }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning',
                                            'diterima' => 'bg-success',
                                            'ditolak' => 'bg-danger'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'fas fa-clock',
                                            'diterima' => 'fas fa-check-circle',
                                            'ditolak' => 'fas fa-times-circle'
                                        ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$item->status_pendaftaran] ?? 'bg-secondary' }} px-2 py-1">
                                            <i class="{{ $statusIcons[$item->status_pendaftaran] ?? 'fas fa-question' }} me-1"></i>
                                            {{ ucfirst($item->status_pendaftaran) }}
                                        </span>
                                        @if($item->keterangan)
                                        <br>
                                        <small class="text-muted d-block mt-1" title="{{ $item->keterangan }}">
                                            {{ Str::limit($item->keterangan, 20) }}
                                        </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="small">
                                            <strong>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</strong><br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            {{-- Detail Button --}}
                                            <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-info btn-sm" title="Lihat Detail" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- Edit Button --}}
                                            <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit Data" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- Delete Button - FIXED --}}
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Data" data-bs-toggle="tooltip" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_lengkap) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="mb-2 mb-md-0">
                            <p class="text-muted mb-0 small">
                                Menampilkan {{ $siswa->firstItem() }} - {{ $siswa->lastItem() }} dari {{ $siswa->total() }} data
                            </p>
                        </div>
                        <div>
                            {{ $siswa->appends(request()->query())->links() }}
                        </div>
                    </div>

                    @else
                    {{-- Enhanced Empty State --}}
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-users fa-4x text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-3">Tidak ada data siswa</h5>
                        <div class="text-muted">
                            @if(request('search') || request('status'))
                            <p class="mb-3">Tidak ditemukan siswa dengan kriteria yang dicari.</p>
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-undo me-2"></i>
                                Kembali ke Semua Siswa
                            </a>
                            @else
                            <p class="mb-3">Belum ada siswa yang terdaftar dalam sistem.</p>
                            @if($isAuthenticated)
                            <a href="{{ route('siswa.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Tambah Siswa Pertama
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Status Update Modal --}}
@if($isAuthenticated)
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="fas fa-edit me-2"></i>
                    Update Status Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pendaftaran</label>
                        <select name="status_pendaftaran" id="statusSelect" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bulk Status Update Form --}}
<form id="bulkStatusForm" method="POST" action="{{ route('siswa.bulk-update-status') }}" style="display: none;">
    @csrf
    <input type="hidden" name="status_pendaftaran" id="bulkStatus">
    <div id="bulkSiswaIds"></div>
</form>
@endif

@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    font-size: 0.9rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.img-thumbnail {
    border: 2px solid #dee2e6;
}

@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .card-header .d-flex > div:last-child {
        margin-top: 1rem;
        width: 100%;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-group-sm > .btn {
        padding: 0.2rem 0.4rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .table th, .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    @if($isAuthenticated)
    // Checkbox functionality
    const checkAll = document.getElementById('checkAll');
    const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    // Check all functionality
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            siswaCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Individual checkbox functionality
    siswaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.siswa-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = count;
        } else {
            bulkActions.style.display = 'none';
        }

        // Update check all status
        if (checkAll) {
            checkAll.checked = count === siswaCheckboxes.length;
            checkAll.indeterminate = count > 0 && count < siswaCheckboxes.length;
        }
    }
    @endif

    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

// ==================== QUICK STATUS UPDATE FUNCTION ==================== 
function quickUpdateStatus(siswaId, newStatus) {
    const statusClasses = {
        'pending': 'bg-warning',
        'diterima': 'bg-success',
        'ditolak': 'bg-danger'
    };
    
    const statusIcons = {
        'pending': 'fas fa-clock',
        'diterima': 'fas fa-check-circle',
        'ditolak': 'fas fa-times-circle'
    };

    const statusTexts = {
        'pending': 'Pending',
        'diterima': 'Diterima',
        'ditolak': 'Ditolak'
    };

    Swal.fire({
        title: 'Konfirmasi Update Status',
        text: `Apakah Anda yakin ingin mengubah status menjadi ${statusTexts[newStatus]}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Add loading state to the row
            const row = document.getElementById(`siswa-row-${siswaId}`);
            const statusBadge = document.getElementById(`status-badge-${siswaId}`);
            
            if (statusBadge) {
                statusBadge.classList.add('status-updating');
            }

            return fetch(`/siswa/${siswaId}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status_pendaftaran: newStatus,
                    keterangan: `Status diubah menjadi ${statusTexts[newStatus]} melalui quick update`
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the badge in real-time
                    updateStatusBadge(siswaId, newStatus, statusClasses, statusIcons, statusTexts);
                    return data;
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat mengupdate status');
                }
            })
            .catch(error => {
                // Remove loading state on error
                if (statusBadge) {
                    statusBadge.classList.remove('status-updating');
                }
                throw error;
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Berhasil!',
                text: result.value.message || 'Status berhasil diupdate',
                icon: 'success',
                confirmButtonColor: '#28a745',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
    }).catch((error) => {
        console.error('Quick update error:', error);
        Swal.fire({
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat mengupdate status',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Function to update status badge in real-time
function updateStatusBadge(siswaId, newStatus, statusClasses, statusIcons, statusTexts) {
    const statusBadge = document.getElementById(`status-badge-${siswaId}`);
    
    if (statusBadge) {
        // Remove loading state
        statusBadge.classList.remove('status-updating');
        
        // Remove old status classes
        Object.values(statusClasses).forEach(cls => {
            statusBadge.classList.remove(cls);
        });
        
        // Add new status class
        statusBadge.classList.add(statusClasses[newStatus]);
        
        // Update content
        statusBadge.innerHTML = `
            <i class="${statusIcons[newStatus]} me-1"></i>
            ${statusTexts[newStatus]}
        `;
        
        // Add animation
        statusBadge.classList.add('status-updated');
        setTimeout(() => {
            statusBadge.classList.remove('status-updated');
        }, 500);
    }
}

// ==================== DELETE FUNCTION - FIXED IMPLEMENTATION ==================== 
function confirmDelete(siswaId, siswaName) {
    Swal.fire({
        title: 'Konfirmasi Hapus Data',
        html: ` 
            <div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p class="mb-2">Apakah Anda yakin ingin menghapus data siswa:</p>
                <strong class="text-danger">${siswaName}</strong>
                <p class="text-muted mt-2 small">Data yang sudah dihapus tidak dapat dikembalikan!</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
        reverseButtons: true,
        focusCancel: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Show loading state
            Swal.showLoading();
            
            // Create proper form for Laravel DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/siswa/${siswaId}`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);
            
            // Add METHOD override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Append form to body
            document.body.appendChild(form);
            
            // Submit form and handle response
            return new Promise((resolve, reject) => {
                // Convert form submission to fetch for better control
                const formData = new FormData(form);
                
                fetch(`/siswa/${siswaId}`, {
                    method: 'POST', // Laravel uses POST with _method override
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Remove form after submission
                    document.body.removeChild(form);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${originalResponse.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    resolve(data);
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    reject(error);
                });
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value && result.value.success) {
                // Success - show success message then reload
                Swal.fire({
                    title: 'Berhasil!',
                    text: result.value.message || `Data siswa ${siswaName} berhasil dihapus`,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    // Reload page to refresh data
                    window.location.reload();
                });
            } else {
                // Error from server
                Swal.fire({
                    title: 'Gagal!',
                    text: result.value?.message || 'Terjadi kesalahan saat menghapus data',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            }
        }
    }).catch((error) => {
        console.error('SweetAlert error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    });
}

// Show success/error messages
@if(session('success'))
Swal.fire({
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    icon: 'success',
    confirmButtonColor: '#28a745',
    timer: 3000,
    timerProgressBar: true
});
@endif

@if(session('error'))
Swal.fire({
    title: 'Error!',
    text: '{{ session('error') }}',
    icon: 'error',
    confirmButtonColor: '#dc3545'
});
@endif
</script>
@endpush