@extends('layouts.layout')

@section('title', 'Tambah Siswa Baru - SDN Sawotratap 1')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Tambah Siswa Baru
                        </h4>
                        <p class="mb-0 mt-1 opacity-75">Silakan lengkapi semua data siswa dengan benar</p>
                    </div>
                    <a href="{{ route('beranda') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- FIXED: Changed from siswa.store to pendaftaran.store for public access --}}
                <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" id="formSiswa">
                    @csrf

                    {{-- Data Pribadi --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-user me-2"></i>
                            Data Pribadi
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                                       id="nisn" name="nisn" value="{{ old('nisn') }}" 
                                       placeholder="Masukkan NISN (10 digit)" maxlength="10" required>
                                @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">NISN harus terdiri dari 10 digit angka</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                       id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                       placeholder="Masukkan tempat lahir" required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="warga_negara" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
                                <select class="form-control @error('warga_negara') is-invalid @enderror" 
                                        id="warga_negara" name="warga_negara" required>
                                    <option value="">Pilih Kewarganegaraan</option>
                                    <option value="WNI" {{ old('warga_negara') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                                    <option value="WNA" {{ old('warga_negara') == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                                </select>
                                @error('warga_negara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nilai_ujian_nasional" class="form-label">Nilai Ujian Nasional</label>
                                <input type="number" class="form-control @error('nilai_ujian_nasional') is-invalid @enderror" 
                                       id="nilai_ujian_nasional" name="nilai_ujian_nasional" value="{{ old('nilai_ujian_nasional') }}" 
                                       placeholder="Masukkan nilai (0-100)" min="0" max="100" step="0.01">
                                @error('nilai_ujian_nasional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Foto Siswa</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>

                    {{-- Kontak & Alamat --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Kontak & Alamat
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" name="alamat" rows="3" 
                                          placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="contoh@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nomor_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nomor_hp') is-invalid @enderror" 
                                       id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp') }}" 
                                       placeholder="08xxxxxxxxxx" maxlength="15" required>
                                @error('nomor_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="asal_tk" class="form-label">Asal TK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('asal_tk') is-invalid @enderror" 
                                       id="asal_tk" name="asal_tk" value="{{ old('asal_tk') }}" 
                                       placeholder="Masukkan nama TK asal" required>
                                @error('asal_tk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Data Orang Tua --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-users me-2"></i>
                            Data Orang Tua
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror" 
                                       id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}" 
                                       placeholder="Masukkan nama ayah" required>
                                @error('nama_ayah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror" 
                                       id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}" 
                                       placeholder="Masukkan nama ibu" required>
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
                                    <input type="number" class="form-control @error('penghasilan_ortu') is-invalid @enderror" 
                                           id="penghasilan_ortu" name="penghasilan_ortu" value="{{ old('penghasilan_ortu') }}" 
                                           placeholder="0" min="0">
                                    @error('penghasilan_ortu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Masukkan dalam rupiah (opsional)</small>
                            </div>
                        </div>
                    </div>

                    {{-- Data Akun - FIXED: Konsisten dengan form login --}}
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-key me-2"></i>
                            Data Akun
                        </h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Penting:</strong> Username dan password ini akan digunakan untuk login ke sistem. Pastikan Anda mengingatnya!
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="Masukkan username untuk login" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Username akan digunakan untuk login ke sistem. 
                                    Minimal 3 karakter, hanya boleh huruf, angka, dan underscore (_).
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Masukkan password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Minimal 8 karakter</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Ulangi password" required>
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

                    {{-- Status Pendaftaran (Admin Only) --}}
                    @auth
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Status Pendaftaran
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status_pendaftaran" class="form-label">Status</label>
                                <select class="form-control @error('status_pendaftaran') is-invalid @enderror" 
                                        id="status_pendaftaran" name="status_pendaftaran">
                                    <option value="pending" {{ old('status_pendaftaran', 'pending') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="diterima" {{ old('status_pendaftaran') == 'diterima' ? 'selected' : '' }}>
                                        Diterima
                                    </option>
                                    <option value="ditolak" {{ old('status_pendaftaran') == 'ditolak' ? 'selected' : '' }}>
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
                                          id="keterangan" name="keterangan" rows="3" 
                                          placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endauth

                    {{-- Form Actions --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('beranda') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <div>
                            <button type="reset" class="btn btn-warning me-2">
                                <i class="fas fa-undo me-1"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // Toggle confirm password visibility
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmField = document.getElementById('password_confirmation');
    
    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmField.setAttribute('type', type);
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // NISN validation (only numbers, max 10 digits)
    const nisnField = document.getElementById('nisn');
    nisnField.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
    });

    // Phone number validation
    const phoneField = document.getElementById('nomor_hp');
    phoneField.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+]/g, '');
    });

    // Username validation - FIXED: Konsisten dengan aturan login
    const usernameField = document.getElementById('username');
    usernameField.addEventListener('input', function() {
        // Hanya boleh huruf, angka, dan underscore
        this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
        // Minimal 3 karakter
        if (this.value.length < 3) {
            this.setCustomValidity('Username minimal 3 karakter');
        } else {
            this.setCustomValidity('');
        }
    });

    // Auto-generate username from nama_lengkap (opsional)
    const namaField = document.getElementById('nama_lengkap');
    namaField.addEventListener('input', function() {
        // Hanya auto-generate jika username masih kosong
        if (!usernameField.value) {
            const username = this.value.toLowerCase()
                .replace(/[^a-z0-9]/g, '')
                .substring(0, 15);
            usernameField.value = username;
        }
    });

    // Preview uploaded photo
    const fotoField = document.getElementById('foto');
    fotoField.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                this.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                const previewContainer = document.getElementById('imagePreview');
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Format currency input
    const penghasilanField = document.getElementById('penghasilan_ortu');
    penghasilanField.addEventListener('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Form validation before submit
    const form = document.getElementById('formSiswa');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const nisnField = document.getElementById('nisn');
        const passwordField = document.getElementById('password');
        const passwordConfirmField = document.getElementById('password_confirmation');
        const usernameField = document.getElementById('username');

        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

        // Validate NISN length
        const nisn = nisnField.value;
        if (nisn.length !== 10) {
            alert('NISN harus terdiri dari 10 digit angka');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Data';
            return false;
        }

        // Validate username
        const username = usernameField.value;
        if (username.length < 3) {
            alert('Username minimal 3 karakter');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Data';
            return false;
        }

        // Validate password match
        const password = passwordField.value;
        const confirmPassword = passwordConfirmField.value;
        
        if (password !== confirmPassword) {
            alert('Password dan konfirmasi password harus sama');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Data';
            return false;
        }

        if (password.length < 8) {
            alert('Password minimal 8 karakter');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Data';
            return false;
        }

        // Submit form
        this.submit();
    });
});
</script>
@endpush