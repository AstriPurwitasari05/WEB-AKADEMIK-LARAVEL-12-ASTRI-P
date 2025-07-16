<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SDN Sawotratap 1 - Sistem Pendaftaran Siswa')</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SweetAlert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="20" r="0.3" fill="rgba(255,255,255,0.05)"/><circle cx="80" cy="80" r="0.4" fill="rgba(255,255,255,0.08)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: -1;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-brand .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-link {
            color: #374151 !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 8px 16px !important;
            border-radius: 12px;
            margin: 0 4px;
        }

        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea !important;
            transform: translateY(-1px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px 24px 0 0 !important;
            padding: 32px;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-30px, -30px) rotate(180deg); }
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 16px;
            padding: 14px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: none;
            border-radius: 16px;
            padding: 14px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(107, 114, 128, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 16px;
            padding: 14px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            border-radius: 16px;
            padding: 14px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 16px;
            padding: 14px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.4);
            color: white;
        }

        .form-control {
            border-radius: 16px;
            border: 2px solid rgba(102, 126, 234, 0.1);
            padding: 14px 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .input-group-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 16px 0 0 16px;
        }

        .table {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 18px;
            font-size: 14px;
        }

        .table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: rgba(102, 126, 234, 0.1);
            font-size: 14px;
        }

        .badge {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 12px;
        }

        .badge-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .alert {
            border-radius: 16px;
            border: none;
            padding: 16px 24px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #7f1d1d;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #1e3a8a;
            border-left: 4px solid #667eea;
        }

        .dropdown-menu {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            padding: 8px;
        }

        .dropdown-item {
            padding: 12px 20px;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 2px 0;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #667eea;
            transform: translateX(4px);
        }

        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(102, 126, 234, 0.1);
            padding: 24px 0;
            margin-top: 60px;
        }

        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin-bottom: 24px;
            position: relative;
            padding-left: 24px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .loading-spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card {
                margin: 12px;
                border-radius: 20px;
            }

            .card-header {
                padding: 24px;
                border-radius: 20px 20px 0 0 !important;
            }

            .btn {
                padding: 12px 24px;
                font-size: 14px;
            }

            .navbar-brand .logo {
                width: 40px;
                height: 40px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .card {
                background: rgba(31, 41, 55, 0.95);
                color: #f9fafb;
            }
            
            .navbar {
                background: rgba(31, 41, 55, 0.95);
            }
            
            .footer {
                background: rgba(31, 41, 55, 0.95);
            }
        }
    </style>
    
    {{-- Tailwind Configuration --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#667eea',
                        secondary: '#764ba2',
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>

<body class="relative">
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand flex items-center" href="{{ route('home') }}">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <strong>SDN Sawotratap 1</strong>
                    <div class="text-sm opacity-75">Sistem Pendaftaran</div>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                            <i class="fas fa-users me-2"></i>Data Siswa
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-bar me-2"></i>Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('siswa.byStatus', 'pending') }}">
                                    <i class="fas fa-clock me-2 text-yellow-500"></i>Pendaftar Baru
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('siswa.byStatus', 'diterima') }}">
                                    <i class="fas fa-check-circle me-2 text-green-500"></i>Siswa Diterima
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('siswa.byStatus', 'ditolak') }}">
                                    <i class="fas fa-times-circle me-2 text-red-500"></i>Siswa Ditolak
                                </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('siswa.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-blue-500"></i>Dashboard
                                </a></li>
                        </ul>
                    </li>
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name ?? Auth::user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-user me-2"></i>Profil
                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i>Pengaturan
                                </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-red-600 hover:text-red-700">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('beranda') }}">
                            <i class="fas fa-home me-2"></i>Home
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="flex items-center">
                        <div class="logo me-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 font-semibold">SDN Sawotratap 1</h6>
                            <small class="text-gray-600">Sistem Pendaftaran Siswa Baru</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="text-sm text-gray-600">
                        © {{ date('Y') }} SDN Sawotratap 1. All rights reserved.
                        <br class="d-md-none">
                        <span class="text-primary">Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating Action Button --}}
    <div class="fixed bottom-8 right-8 z-50">
        <div class="group">
            <button class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
    <div class="fixed top-4 right-4 z-50 max-w-sm">
        <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm">
        <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="fixed top-4 right-4 z-50 max-w-sm">
        <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-3xl shadow-2xl text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium">Memuat data...</p>
        </div>
    </div>

    {{-- JavaScript Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Enhanced JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Enhanced form validation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Mohon lengkapi semua field yang wajib diisi!',
                            confirmButtonColor: '#667eea'
                        });
                    }
                });
            });
        });

        // Loading overlay functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Confirmation for delete actions
        function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
            return Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                return result.isConfirmed;
            });
        }

        // Enhanced SweetAlert notifications
        function showNotification(type, title, text, timer = 3000) {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: timer,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }

        // Success/Error handling from Laravel session
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#667eea',
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session("error") }}',
            confirmButtonColor: '#667eea',
            showCloseButton: true
        });
        @endif

        @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
        errorMessages += '{{ $error }}\n';
        @endforeach
        
        Swal.fire({
            icon: 'error',
            title: 'Validasi Error!',
            text: errorMessages,
            confirmButtonColor: '#667eea',
            showCloseButton: true
        });
        @endif

        // Enhanced table functionality
        function initializeDataTable() {
            const tables = document.querySelectorAll('.data-table');
            tables.forEach(table => {
                // Add search functionality
                const searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.className = 'form-control mb-3';
                searchInput.placeholder = 'Cari data...';
                searchInput.addEventListener('input', function() {
                    filterTable(table, this.value);
                });
                
                table.parentNode.insertBefore(searchInput, table);
            });
        }

        function filterTable(table, searchTerm) {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm.toLowerCase());
                row.style.display = shouldShow ? '' : 'none';
            });
        }

        // Print functionality
        function printTable(tableId) {
            const table = document.getElementById(tableId);
            const printWindow = window.open('', '_blank');
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header h1 { color: #667eea; margin: 0; }
                        .header p { margin: 5px 0; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>SDN Sawotratap 1</h1>
                        <p>Sistem Pendaftaran Siswa Baru</p>
                        <p>Laporan Data - ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                    ${table.outerHTML}
                </body>
                </html>
            `;
            
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }

        // Export to CSV functionality
        function exportToCSV(tableId, filename = 'data.csv') {
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const rowData = [];
                cols.forEach(col => {
                    rowData.push('"' + col.textContent.replace(/"/g, '""') + '"');
                });
                csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        // Form auto-save functionality
        function enableAutoSave(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData);
                    localStorage.setItem('autosave_' + formId, JSON.stringify(data));
                });
            });
            
            // Load saved data on page load
            const savedData = localStorage.getItem('autosave_' + formId);
            if (savedData) {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = data[key];
                    }
                });
            }
        }

        // Clear auto-save data
        function clearAutoSave(formId) {
            localStorage.removeItem('autosave_' + formId);
        }

        // Image preview functionality
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Enhanced modal functionality
        function openModal(modalId, data = {}) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Fill modal with data if provided
                Object.keys(data).forEach(key => {
                    const element = modal.querySelector(`[name="${key}"]`);
                    if (element) {
                        element.value = data[key];
                    }
                });
                
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            }
        }

        // Status badge helper
        function getStatusBadge(status) {
            const badges = {
                'pending': '<span class="badge badge-warning"><i class="fas fa-clock me-1"></i>Pending</span>',
                'diterima': '<span class="badge badge-success"><i class="fas fa-check-circle me-1"></i>Diterima</span>',
                'ditolak': '<span class="badge badge-danger"><i class="fas fa-times-circle me-1"></i>Ditolak</span>'
            };
            return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
        }

        // Date formatting helper
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Number formatting helper
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Tooltip initialization
        function initializeTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Initialize all enhancements
        document.addEventListener('DOMContentLoaded', function() {
            initializeDataTable();
            initializeTooltips();
            
            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + S to save form
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const form = document.querySelector('form');
                if (form) {
                    form.submit();
                }
            }
            
            // Ctrl + P to print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }
                });
            }
        });

        // Service Worker for offline functionality
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed');
                    });
            });
        }

        // Network status monitoring
        function updateNetworkStatus() {
            const status = navigator.onLine ? 'online' : 'offline';
            const statusElement = document.getElementById('networkStatus');
            
            if (statusElement) {
                statusElement.textContent = status;
                statusElement.className = status === 'online' ? 'text-success' : 'text-danger';
            }
            
            if (!navigator.onLine) {
                showNotification('warning', 'Koneksi Terputus', 'Anda sedang offline. Beberapa fitur mungkin tidak berfungsi.');
            }
        }

        window.addEventListener('online', updateNetworkStatus);
        window.addEventListener('offline', updateNetworkStatus);

        // Performance monitoring
        function measurePerformance() {
            if ('performance' in window) {
                window.addEventListener('load', function() {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                    
                    if (loadTime > 3000) {
                        console.warn('Page load time is high:', loadTime + 'ms');
                    }
                });
            }
        }

        measurePerformance();
    </script>

    @stack('scripts')
</body>
</html>