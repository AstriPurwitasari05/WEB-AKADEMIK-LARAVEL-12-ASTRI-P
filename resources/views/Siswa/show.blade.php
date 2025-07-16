@extends('layouts.layout')

@section('title', 'Detail Siswa: ' . $siswa->nama_lengkap . ' - SDN Sawotratap 1')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        {{-- Header Card --}}
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Detail Siswa: {{ $siswa->nama_lengkap }}
                        </h4>
                        <p class="mb-0 mt-1 opacity-75">
                            NISN: {{ $siswa->nisn }} | Status:
                            <span class="badge {{ $siswa->status_badge }}">
                                <i class="{{ $siswa->status_icon }} me-1"></i>
                                {{ ucfirst($siswa->status_pendaftaran) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                        @if($isAuthenticated)
                            <div class="btn-group">
                                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $siswa->id }})">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Photo and Quick Info --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="photo-container mb-3">
                            @if($siswa->foto && $siswa->has_foto)
                                <img src="{{ route('siswa.photo', $siswa->id) }}" 
                                     alt="Foto {{ $siswa->nama_lengkap }}" 
                                     class="img-fluid rounded-circle"
                                     style="width: 200px; height: 200px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'; this.alt='Default Avatar';">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 200px; height: 200px;">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <h5 class="card-title">{{ $siswa->nama_lengkap }}</h5>
                        <p class="card-text text-muted">{{ $siswa->username }}</p>

                        {{-- Download photo button (jika ada foto) --}}
                        @if($siswa->foto && $siswa->has_foto)
                            <div class="mb-3">
                                <a href="{{ route('siswa.download-photo', $siswa->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Download Foto
                                </a>
                            </div>
                        @endif

                        {{-- Quick Stats --}}
                        <div class="row text-center mt-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="mb-0">{{ $siswa->umur }}</h6>
                                    <small class="text-muted">Tahun</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0">
                                    {{ $siswa->nilai_ujian_nasional ?? 'N/A' }}
                                </h6>
                                <small class="text-muted">Nilai UN</small>
                            </div>
                        </div>

                        {{-- Status Actions (Admin Only) --}}
                        @if($isAuthenticated)
                            <div class="mt-3">
                                <h6>Aksi Cepat</h6>
                                <div class="btn-group-vertical w-100" role="group">
                                    @if($siswa->status_pendaftaran !== 'diterima')
                                        <button type="button" class="btn btn-success btn-sm" 
                                                onclick="updateStatus({{ $siswa->id }}, 'diterima')">
                                            <i class="fas fa-check me-1"></i> Terima
                                        </button>
                                    @endif
                                    @if($siswa->status_pendaftaran !== 'ditolak')
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="updateStatus({{ $siswa->id }}, 'ditolak')">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </button>
                                    @endif
                                    @if($siswa->status_pendaftaran !== 'pending')
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="updateStatus({{ $siswa->id }}, 'pending')">
                                            <i class="fas fa-clock me-1"></i> Set Pending
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-info btn-sm" 
                                            onclick="resetPassword({{ $siswa->id }})">
                                        <i class="fas fa-key me-1"></i> Reset Password
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detailed Information --}}
            <div class="col-md-8">
                {{-- Data Pribadi --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i> Data Pribadi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">NISN</label>
                                <p class="form-control-plaintext">{{ $siswa->nisn }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <p class="form-control-plaintext">{{ $siswa->nama_lengkap }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tempat Lahir</label>
                                <p class="form-control-plaintext">{{ $siswa->tempat_lahir }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <p class="form-control-plaintext">
                                    {{ $siswa->tanggal_lahir_formatted }}
                                    <small class="text-muted">({{ $siswa->umur }} tahun)</small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kewarganegaraan</label>
                                <p class="form-control-plaintext">
                                    {{ $siswa->warga_negara === 'WNI' ? 'WNI (Warga Negara Indonesia)' : 'WNA (Warga Negara Asing)' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nilai Ujian Nasional</label>
                                <p class="form-control-plaintext">
                                    @if($siswa->nilai_ujian_nasional)
                                        <span class="badge bg-info">{{ $siswa->nilai_ujian_nasional }}</span>
                                    @else
                                        <span class="text-muted">Belum diisi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kontak & Alamat --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i> Kontak & Alamat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <p class="form-control-plaintext">{{ $siswa->alamat }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext">
                                    <a href="mailto:{{ $siswa->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i> {{ $siswa->email }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor HP</label>
                                <p class="form-control-plaintext">
                                    <a href="tel:{{ $siswa->nomor_hp }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i> {{ $siswa->nomor_hp }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Asal TK</label>
                                <p class="form-control-plaintext">{{ $siswa->asal_tk }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Orang Tua --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i> Data Orang Tua
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Ayah</label>
                                <p class="form-control-plaintext">{{ $siswa->nama_ayah }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Ibu</label>
                                <p class="form-control-plaintext">{{ $siswa->nama_ibu }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Penghasilan Orang Tua</label>
                                <p class="form-control-plaintext">{{ $siswa->penghasilan_format }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Akun & Status --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i> Status Pendaftaran & Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <p class="form-control-plaintext">{{ $siswa->username }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status Pendaftaran</label>
                                <p class="form-control-plaintext">
                                    <span class="badge {{ $siswa->status_badge }} fs-6">
                                        <i class="{{ $siswa->status_icon }} me-1"></i>
                                        {{ ucfirst($siswa->status_pendaftaran) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @if($siswa->keterangan)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Keterangan</label>
                                    <p class="form-control-plaintext">{{ $siswa->keterangan }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Pendaftaran</label>
                                <p class="form-control-plaintext">
                                    {{ $siswa->created_at->format('d F Y, H:i') }}
                                    <small class="text-muted">({{ $siswa->created_at->diffForHumans() }})</small>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Terakhir Diperbarui</label>
                                <p class="form-control-plaintext">
                                    {{ $siswa->updated_at->format('d F Y, H:i') }}
                                    <small class="text-muted">({{ $siswa->updated_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@if($isAuthenticated)
    {{-- Status Update Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Ubah Status Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status_pendaftaran" class="form-label">Status Pendaftaran</label>
                            <select class="form-select" id="status_pendaftaran" name="status_pendaftaran" required>
                                <option value="pending">Pending</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reset Password Modal --}}
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="resetPasswordForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   placeholder="Masukkan password baru" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation" 
                                   name="new_password_confirmation" placeholder="Ulangi password baru" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data siswa <strong>{{ $siswa->nama_lengkap }}</strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle image loading errors
    const images = document.querySelectorAll('img[src*="siswa.photo"]');
    
    images.forEach(function(img) {
        img.addEventListener('error', function() {
            console.log('Failed to load image:', this.src);
            this.src = '{{ asset("images/default-avatar.png") }}';
            this.alt = 'Default Avatar';
            this.classList.add('photo-error');
        });
        
        img.addEventListener('load', function() {
            this.classList.remove('photo-loading');
        });
    });

    @if($isAuthenticated)
        // Status update functionality
        window.updateStatus = function(siswaId, status) {
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            const form = document.getElementById('statusForm');
            const statusSelect = document.getElementById('status_pendaftaran');
            const keteranganTextarea = document.getElementById('keterangan');

            // Set form action
            form.action = `/siswa/${siswaId}/status`;

            // Set current status
            statusSelect.value = status;

            // Clear keterangan
            keteranganTextarea.value = '';

            // Show modal
            modal.show();
        };

        // Reset password functionality
        window.resetPassword = function(siswaId) {
            const modal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            const form = document.getElementById('resetPasswordForm');

            // Set form action
            form.action = `/siswa/${siswaId}/reset-password`;

            // Clear password fields
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';

            // Show modal
            modal.show();
        };

        // Delete confirmation functionality
        window.confirmDelete = function(siswaId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const form = document.getElementById('deleteForm');

            // Set form action
            form.action = `/siswa/${siswaId}`;

            // Show modal
            modal.show();
        };

        // Form validation for reset password
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('new_password').value;
            const confirmation = document.getElementById('new_password_confirmation').value;

            if (password !== confirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password harus sama!');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                return false;
            }
        });
    @endif
});
</script>
@endpush

@push('styles')
<style>
.section-title {
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.form-control-plaintext {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    margin-bottom: 0;
}

.badge {
    font-size: 0.875em;
}

.badge-warning {
    background-color: #ffc107 !important;
    color: #000;
}

.badge-success {
    background-color: #198754 !important;
}

.badge-danger {
    background-color: #dc3545 !important;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.btn-group-vertical .btn {
    margin-bottom: 0.25rem;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}

/* Photo Container Styling */
.photo-container {
    position: relative;
    display: inline-block;
}

.photo-container img {
    border: 3px solid #dee2e6;
    transition: all 0.3s ease;
}

.photo-container img:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

/* Loading placeholder */
.photo-loading {
    width: 200px;
    height: 200px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 50%;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Error state */
.photo-error {
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 12px;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }

    .btn-group {
        width: 100%;
    }

    .btn-group .btn {
        flex: 1;
    }

    .photo-container img,
    .photo-loading {
        width: 150px;
        height: 150px;
    }
}
</style>
@endpush