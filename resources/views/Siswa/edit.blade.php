@extends('layouts.layout')

@section('title', 'Edit Data Siswa - SDN Sawotratap 1')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Data Siswa
                        </h4>
                        <p class="mb-0 mt-1 opacity-75">Edit data siswa: {{ $siswa->nama_lengkap }}</p>
                    </div>
                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Show success/error messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan:</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data" id="formEditSiswa" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Data Pribadi --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-user me-2"></i> Data Pribadi
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nisn') is-invalid @enderror" 
                                       id="nisn" 
                                       name="nisn" 
                                       value="{{ old('nisn', $siswa->nisn) }}" 
                                       placeholder="Masukkan NISN (10 digit)" 
                                       maxlength="10" 
                                       required>
                                @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">NISN harus terdiri dari 10 digit angka</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       id="nama_lengkap" 
                                       name="nama_lengkap" 
                                       value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" 
                                       placeholder="Masukkan nama lengkap" 
                                       required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                       id="tempat_lahir" 
                                       name="tempat_lahir" 
                                       value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" 
                                       placeholder="Masukkan tempat lahir" 
                                       required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       id="tanggal_lahir" 
                                       name="tanggal_lahir" 
                                       value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}" 
                                       required>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="warga_negara" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                                <select class="form-control @error('warga_negara') is-invalid @enderror" 
                                        id="warga_negara" 
                                        name="warga_negara" 
                                        required>
                                    <option value="">Pilih Kewarganegaraan</option>
                                    <option value="WNI" {{ old('warga_negara', $siswa->warga_negara) == 'WNI' ? 'selected' : '' }}>
                                        WNI (Warga Negara Indonesia)
                                    </option>
                                    <option value="WNA" {{ old('warga_negara', $siswa->warga_negara) == 'WNA' ? 'selected' : '' }}>
                                        WNA (Warga Negara Asing)
                                    </option>
                                </select>
                                @error('warga_negara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nilai_ujian_nasional" class="form-label">Nilai Ujian Nasional</label>
                                <input type="number" 
                                       class="form-control @error('nilai_ujian_nasional') is-invalid @enderror" 
                                       id="nilai_ujian_nasional" 
                                       name="nilai_ujian_nasional" 
                                       value="{{ old('nilai_ujian_nasional', $siswa->nilai_ujian_nasional) }}" 
                                       placeholder="Masukkan nilai (0-100)" 
                                       min="0" 
                                       max="100" 
                                       step="0.01">
                                @error('nilai_ujian_nasional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto" class="form-label">Foto Siswa</label>
                                <input type="file" 
                                       class="form-control @error('foto') is-invalid @enderror" 
                                       id="foto" 
                                       name="foto" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif">
                                <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Current photo preview --}}
                                @if($siswa->foto)
                                    <div class="mt-3" id="currentPhoto">
                                        <p class="mb-2 text-muted fw-semibold">Foto saat ini:</p>
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ route('siswa.photo', $siswa->id) }}" 
                                                 alt="Foto {{ $siswa->nama_lengkap }}" 
                                                 class="img-thumbnail border shadow-sm" 
                                                 style="max-width: 200px; max-height: 200px; object-fit: cover;"
                                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRkZGRkZGIi8+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRTlFQ0VGIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8Y2lyY2xlIGN4PSIxMDAiIGN5PSI4MCIgcj0iMzAiIGZpbGw9IiM2Qzc1N0QiLz4KPGVsbGlwc2UgY3g9IjEwMCIgY3k9IjE0MCIgcng9IjQwIiByeT0iMzAiIGZpbGw9IiM2Qzc1N0QiLz4KPHRleHQgeD0iMTAwIiB5PSIxNzAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzQ5NTA1NyI+VGlkYWsgQWRhIEZvdG88L3RleHQ+Cjwvc3ZnPgo=';">
                                            <div class="position-absolute top-0 end-0 m-1">
                                                <span class="badge bg-success">Tersimpan</span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Klik "Choose File" di atas untuk mengganti foto
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3" id="noPhoto">
                                        <div class="text-center p-4 border rounded bg-light">
                                            <i class="fas fa-camera text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada foto</p>
                                            <small class="text-muted">Upload foto siswa di atas</small>
                                        </div>
                                    </div>
                                @endif

                                {{-- New photo preview --}}
                                <div class="mt-3" id="imagePreview" style="display: none;">
                                    <p class="mb-2 text-muted fw-semibold">Preview foto baru:</p>
                                    <div class="position-relative d-inline-block">
                                        <img id="preview" 
                                             src="#" 
                                             alt="Preview" 
                                             class="img-thumbnail border shadow-sm" 
                                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <span class="badge bg-warning">Belum disimpan</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearNewPhoto()">
                                            <i class="fas fa-times me-1"></i>Hapus Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kontak & Alamat --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt me-2"></i> Kontak & Alamat
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="3" 
                                          placeholder="Masukkan alamat lengkap" 
                                          required>{{ old('alamat', $siswa->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $siswa->email) }}" 
                                       placeholder="contoh@email.com" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nomor_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nomor_hp') is-invalid @enderror" 
                                       id="nomor_hp" 
                                       name="nomor_hp" 
                                       value="{{ old('nomor_hp', $siswa->nomor_hp) }}" 
                                       placeholder="08xxxxxxxxxx" 
                                       maxlength="15" 
                                       required>
                                @error('nomor_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="asal_tk" class="form-label">Asal TK <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('asal_tk') is-invalid @enderror" 
                                       id="asal_tk" 
                                       name="asal_tk" 
                                       value="{{ old('asal_tk', $siswa->asal_tk) }}" 
                                       placeholder="Masukkan nama TK asal" 
                                       required>
                                @error('asal_tk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Data Orang Tua --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-users me-2"></i> Data Orang Tua
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_ayah') is-invalid @enderror" 
                                       id="nama_ayah" 
                                       name="nama_ayah" 
                                       value="{{ old('nama_ayah', $siswa->nama_ayah) }}" 
                                       placeholder="Masukkan nama ayah" 
                                       required>
                                @error('nama_ayah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_ibu') is-invalid @enderror" 
                                       id="nama_ibu" 
                                       name="nama_ibu" 
                                       value="{{ old('nama_ibu', $siswa->nama_ibu) }}" 
                                       placeholder="Masukkan nama ibu" 
                                       required>
                                @error('nama_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="penghasilan_ortu" class="form-label">Penghasilan Orang Tua</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('penghasilan_ortu') is-invalid @enderror" 
                                           id="penghasilan_ortu" 
                                           name="penghasilan_ortu" 
                                           value="{{ old('penghasilan_ortu', $siswa->penghasilan_ortu) }}" 
                                           placeholder="0" 
                                           min="0">
                                    @error('penghasilan_ortu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Masukkan dalam rupiah (opsional)</small>
                            </div>
                        </div>
                    </div>

                    {{-- Data Akun --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-key me-2"></i> Data Akun
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username', $siswa->username) }}" 
                                       placeholder="Masukkan username" 
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Username akan digunakan untuk login ke sistem</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Ulangi password baru">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Status Pendaftaran --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-clipboard-check me-2"></i> Status Pendaftaran
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status_pendaftaran" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status_pendaftaran') is-invalid @enderror" 
                                        id="status_pendaftaran" 
                                        name="status_pendaftaran" 
                                        required>
                                    <option value="pending" {{ old('status_pendaftaran', $siswa->status_pendaftaran) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="diterima" {{ old('status_pendaftaran', $siswa->status_pendaftaran) == 'diterima' ? 'selected' : '' }}>
                                        Diterima
                                    </option>
                                    <option value="ditolak" {{ old('status_pendaftaran', $siswa->status_pendaftaran) == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                                @error('status_pendaftaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" 
                                          name="keterangan" 
                                          rows="3" 
                                          placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $siswa->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <div>
                            <button type="reset" class="btn btn-warning me-2">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Update Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.section-title {
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.invalid-feedback {
    display: block;
}

.alert {
    border: none;
    border-radius: 0.5rem;
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
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Toggle confirm password visibility
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmField = document.getElementById('password_confirmation');
    
    if (togglePasswordConfirm && passwordConfirmField) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmField.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // NISN validation (only numbers, max 10 digits)
    const nisnField = document.getElementById('nisn');
    if (nisnField) {
        nisnField.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });
    }

    // Phone number validation
    const phoneField = document.getElementById('nomor_hp');
    if (phoneField) {
        phoneField.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }

    // Photo preview functionality
    const fotoField = document.getElementById('foto');
    const imagePreview = document.getElementById('imagePreview');
    const preview = document.getElementById('preview');
    const currentPhoto = document.getElementById('currentPhoto');

    if (fotoField) {
        fotoField.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file size (2MB max)
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file harus JPG, JPEG, PNG, atau GIF');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    
                    // Hide current photo when showing new preview
                    if (currentPhoto) {
                        currentPhoto.style.opacity = '0.5';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                clearNewPhoto();
            }
        });
    }

    // Format currency input
    const penghasilanField = document.getElementById('penghasilan_ortu');
    if (penghasilanField) {
        penghasilanField.addEventListener('input', function() {
            // Remove non-numeric characters except for backspace
            let value = this.value.replace(/[^0-9]/g, '');
            this.value = value;
        });

        // Format display on blur
        penghasilanField.addEventListener('blur', function() {
            if (this.value) {
                // Format number with thousands separator
                let num = parseInt(this.value);
                if (!isNaN(num)) {
                    // Store raw value for form submission
                    this.setAttribute('data-raw-value', num);
                }
            }
        });
    }

    // Form validation before submit
    const form = document.getElementById('formEditSiswa');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Validate NISN length
            const nisnField = document.getElementById('nisn');
            if (nisnField && nisnField.value) {
                const nisn = nisnField.value.trim();
                if (nisn.length !== 10 || !/^\d{10}$/.test(nisn)) {
                    alert('NISN harus terdiri dari 10 digit angka');
                    nisnField.focus();
                    nisnField.classList.add('is-invalid');
                    isValid = false;
                }
            }

            // Validate email format
            const emailField = document.getElementById('email');
            if (emailField && emailField.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    alert('Format email tidak valid');
                    emailField.focus();
                    emailField.classList.add('is-invalid');
                    isValid = false;
                }
            }

            // Validate phone number
            const phoneField = document.getElementById('nomor_hp');
            if (phoneField && phoneField.value) {
                const phone = phoneField.value.trim();
                if (phone.length < 10 || phone.length > 15) {
                    alert('Nomor HP harus antara 10-15 digit');
                    phoneField.focus();
                    phoneField.classList.add('is-invalid');
                    isValid = false;
                }
            }

            // Validate password match (only if password is filled)
            const passwordField = document.getElementById('password');
            const passwordConfirmField = document.getElementById('password_confirmation');
            
            if (passwordField && passwordConfirmField) {
                const password = passwordField.value;
                const confirmPassword = passwordConfirmField.value;
                
                if (password || confirmPassword) {
                    if (password !== confirmPassword) {
                        alert('Password dan konfirmasi password harus sama');
                        passwordField.focus();
                        passwordField.classList.add('is-invalid');
                        passwordConfirmField.classList.add('is-invalid');
                        isValid = false;
                    } else if (password.length < 8) {
                        alert('Password minimal 8 karakter');
                        passwordField.focus();
                        passwordField.classList.add('is-invalid');
                        isValid = false;
                    }
                }
            }

            // Validate date of birth (not future date)
            const birthDateField = document.getElementById('tanggal_lahir');
            if (birthDateField && birthDateField.value) {
                const birthDate = new Date(birthDateField.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (birthDate >= today) {
                    alert('Tanggal lahir tidak boleh di masa depan');
                    birthDateField.focus();
                    birthDateField.classList.add('is-invalid');
                    isValid = false;
                }
                
                // Check minimum age (e.g., 5 years old)
                const minAge = new Date();
                minAge.setFullYear(minAge.getFullYear() - 5);
                if (birthDate > minAge) {
                    alert('Umur minimal 5 tahun');
                    birthDateField.focus();
                    birthDateField.classList.add('is-invalid');
                    isValid = false;
                }
            }

            // If validation fails, re-enable submit button
            if (!isValid) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Data';
                e.preventDefault();
                
                // Scroll to first invalid field
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
                return false;
            }

            // Show loading state
            const loadingToast = document.createElement('div');
            loadingToast.className = 'toast-container position-fixed top-0 end-0 p-3';
            loadingToast.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-header">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        <strong class="me-auto">Menyimpan Data</strong>
                    </div>
                    <div class="toast-body">
                        Data siswa sedang diproses...
                    </div>
                </div>
            `;
            document.body.appendChild(loadingToast);

            // Form will be submitted normally
            return true;
        });
    }

    // Auto-save draft functionality (optional)
    let autoSaveTimer;
    const formFields = form ? form.querySelectorAll('input, select, textarea') : [];
    
    formFields.forEach(function(field) {
        if (field.type !== 'file' && field.type !== 'password') {
            field.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    // Could implement auto-save to localStorage here
                    // localStorage.setItem('edit_form_draft', JSON.stringify(getFormData()));
                }, 2000);
            });
        }
    });

    // Warn user about unsaved changes
    let hasUnsavedChanges = false;
    
    formFields.forEach(function(field) {
        field.addEventListener('input', function() {
            hasUnsavedChanges = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    });

    // Mark as saved when form is submitted
    if (form) {
        form.addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });
    }

    // Reset form functionality
    const resetBtn = document.querySelector('button[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Yakin ingin mereset semua perubahan? Data akan kembali ke nilai awal.')) {
                // Reset form
                form.reset();
                
                // Clear validation states
                form.querySelectorAll('.is-invalid').forEach(function(field) {
                    field.classList.remove('is-invalid');
                });
                
                // Clear photo preview
                clearNewPhoto();
                
                // Reset unsaved changes flag
                hasUnsavedChanges = false;
                
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-info alert-dismissible fade show';
                successAlert.innerHTML = `
                    <i class="fas fa-info-circle me-2"></i>
                    Form telah direset ke nilai awal
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                const cardBody = document.querySelector('.card-body');
                if (cardBody) {
                    cardBody.insertBefore(successAlert, cardBody.firstChild);
                }
            }
        });
    }
});

// Function to clear new photo preview
function clearNewPhoto() {
    const fotoField = document.getElementById('foto');
    const imagePreview = document.getElementById('imagePreview');
    const currentPhoto = document.getElementById('currentPhoto');
    
    if (fotoField) {
        fotoField.value = '';
    }
    
    if (imagePreview) {
        imagePreview.style.display = 'none';
    }
    
    if (currentPhoto) {
        currentPhoto.style.opacity = '1';
    }
}

// Function to get form data (for auto-save feature)
function getFormData() {
    const form = document.getElementById('formEditSiswa');
    if (!form) return {};
    
    const formData = {};
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(function(input) {
        if (input.type !== 'file' && input.type !== 'password' && input.name) {
            formData[input.name] = input.value;
        }
    });
    
    return formData;
}

// Function to restore form data (for auto-save feature)
function restoreFormData(data) {
    Object.keys(data).forEach(function(name) {
        const field = document.querySelector(`[name="${name}"]`);
        if (field && field.type !== 'file' && field.type !== 'password') {
            field.value = data[name];
        }
    });
}

// Initialize tooltips if Bootstrap is available
if (typeof bootstrap !== 'undefined') {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}
</script>
@endpush