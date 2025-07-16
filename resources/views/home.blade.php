<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - SDN Sawotratap 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Pendaftaran Siswa Baru Online SDN Sawotratap 1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Bootstrap CSS from Cloudflare --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Tailwind CSS from Cloudflare --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    
    {{-- Font Awesome from Cloudflare --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SweetAlert2 CSS from Cloudflare --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.css">
    
    {{-- Animate.css for animations --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    {{-- AOS (Animate On Scroll) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <style>
        /* Custom styles combining Bootstrap, Tailwind, and custom design */
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #ed8936;
            --info-color: #4299e1;
            --light-color: #f7fafc;
            --dark-color: #2d3748;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        /* Glassmorphism navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
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
            color: var(--primary-color) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 10px 16px !important;
            border-radius: 12px;
            margin: 0 4px;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
            transform: translateY(-2px);
            background: rgba(102, 126, 234, 0.1);
        }

        .nav-link.active {
            color: white !important;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        /* Hero section with advanced animations */
        .hero {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 120px 0;
            text-align: center;
            border-radius: 30px;
            margin: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 12s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            animation: float 15s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 24px;
            text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        .hero p {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            margin-bottom: 40px;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }

        /* Modern buttons */
        .btn-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-logout {
            background: linear-gradient(135deg, var(--danger-color) 0%, #e53e3e 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.4);
            color: white;
        }

        /* Info cards with advanced styling */
        .info-section {
            padding: 100px 0;
            position: relative;
        }

        .info-section h2 {
            color: white;
            font-weight: 800;
            margin-bottom: 60px;
            font-size: clamp(2rem, 4vw, 3rem);
            text-align: center;
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .info-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 35px;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: height 0.3s ease;
        }

        .info-card:hover::before {
            height: 8px;
        }

        .info-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .info-card h5 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.4rem;
        }

        .info-card p {
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 20px;
            font-size: 1.05rem;
        }

        .info-card .badge {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
            color: var(--primary-color);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            border: 1px solid rgba(102, 126, 234, 0.2);
            font-size: 0.9rem;
        }

        /* Username display */
        .username-display {
            color: var(--primary-color);
            font-weight: 600;
            background: rgba(102, 126, 234, 0.1);
            padding: 10px 20px;
            border-radius: 25px;
            margin-right: 15px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .username-display:hover {
            background: rgba(102, 126, 234, 0.15);
            transform: translateY(-1px);
        }

        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            padding: 80px 0 40px;
            margin-top: 60px;
        }

        footer h5 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.3rem;
        }

        footer p, footer li {
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        footer a:hover {
            color: var(--secondary-color);
            transform: translateX(5px);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .hero {
                padding: 80px 20px;
                margin: 30px 15px;
                border-radius: 20px;
            }

            .info-section {
                padding: 60px 0;
            }

            .info-card {
                margin-bottom: 30px;
                padding: 25px;
            }

            .username-display {
                margin-right: 10px;
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .navbar-brand .logo {
                width: 40px;
                height: 40px;
            }
        }

        /* Loading animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Particle background */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float-particles 20s infinite linear;
        }

        @keyframes float-particles {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Custom SweetAlert2 styling */
        .swal2-popup {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .swal2-title {
            color: var(--primary-color);
            font-weight: 700;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .swal2-cancel {
            background: linear-gradient(135deg, var(--danger-color) 0%, #e53e3e 100%);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    {{-- Loading Overlay --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    {{-- Particle Background --}}
    <div class="particles" id="particles"></div>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('beranda') }}" data-aos="fade-right">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <strong>SDN SAWOTRATAP 1</strong>
                    <div style="font-size: 0.8em; opacity: 0.8; font-weight: 400;">Sistem Pendaftaran Siswa Baru</div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('beranda') }}" data-aos="fade-down" data-aos-delay="100">
                            <i class="fas fa-home me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('program') }}" data-aos="fade-down" data-aos-delay="200">
                            <i class="fas fa-book me-2"></i>Program
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('statistik') }}" data-aos="fade-down" data-aos-delay="300">
                            <i class="fas fa-chart-bar me-2"></i>Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.index') }}" data-aos="fade-down" data-aos-delay="400">
                            <i class="fas fa-users me-2"></i>Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pendaftaran') }}" data-aos="fade-down" data-aos-delay="500">
                            <i class="fas fa-user-plus me-2"></i>Pendaftaran
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center">
                        <span class="username-display" id="username" data-aos="fade-left" data-aos-delay="600">
                            <i class="fas fa-user"></i>
                            <span id="username-text">Admin</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-logout" onclick="handleLogout()" data-aos="fade-left" data-aos-delay="700">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <div class="container">
        <div class="hero" data-aos="fade-up" data-aos-duration="1000">
            <div class="hero-greeting animate__animated animate__fadeInUp">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Selamat Datang</span>
                    <i class="fas fa-graduation-cap"></i>
                </div>
                
                <div class="hero-divider"></div>
                
                <h1 class="hero-title animate__animated animate__fadeInUp animate__delay-1s">
                    PSB SDN Sawotratap 1
                </h1>
            <p class="animate__animated animate__fadeInUp animate__delay-1s">
                <i class="fas fa-star me-2"></i>Sistem Pendaftaran Siswa Baru Online - Bergabunglah dengan Sekolah Dasar Unggulan
            </p>
            </div>
        </div>
    </div>

    {{-- Informasi Terbaru --}}
    <section class="info-section">
        <div class="container">
            <h2 data-aos="fade-up">
                <i class="fas fa-newspaper me-3"></i>Informasi Terbaru
            </h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-card">
                        <h5><i class="fas fa-user-graduate me-2 text-primary"></i>Pendaftaran Siswa Baru 2025/2026</h5>
                        <p>Pendaftaran siswa baru untuk tahun ajaran 2025/2026 telah dibuka. Segera daftarkan putra/putri Anda untuk menjadi bagian dari keluarga besar SDN Sawotratap 1.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge">
                                <i class="fas fa-calendar-alt me-1"></i>Batas: 30 Mei 2025
                            </span>
                            <a href="{{ route('pendaftaran') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="fas fa-arrow-right me-1"></i>Daftar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-card">
                        <h5><i class="fas fa-palette me-2 text-success"></i>Pameran Karya Siswa</h5>
                        <p>SDN Sawotratap 1 akan mengadakan pameran karya siswa terbuka untuk umum. Nikmati kreativitas dan bakat anak-anak kami dalam berbagai bidang seni dan teknologi.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge">
                                <i class="fas fa-calendar-check me-1"></i>25 April 2025
                            </span>
                            <a href="#" class="btn btn-sm btn-outline-success rounded-pill">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-card">
                        <h5><i class="fas fa-trophy me-2 text-warning"></i>Prestasi Siswa Terbaru</h5>
                        <p>Selamat kepada tim olimpiade matematika SDN Sawotratap 1 yang meraih juara 1 pada tingkat kabupaten. Tim terdiri dari 5 siswa berbakat dari kelas IV dan V.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge">
                                <i class="fas fa-medal me-1"></i>Prestasi Terbaru
                            </span>
                            <a href="#" class="btn btn-sm btn-outline-warning rounded-pill">
                                <i class="fas fa-star me-1"></i>Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <h5><i class="fas fa-school me-2"></i>Tentang Kami</h5>
                    <p>SDN Sawotratap 1 adalah sekolah dasar yang berfokus pada pengembangan siswa secara holistik untuk mempersiapkan generasi yang unggul, berkarakter, dan siap menghadapi tantangan masa depan.</p>
                    <div class="mt-3">
                        <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h5><i class="fas fa-link me-2"></i>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('beranda') }}"><i class="fas fa-home me-2"></i>Beranda</a></li>
                        <li><a href="{{ route('program') }}"><i class="fas fa-book me-2"></i>Program Akademik</a></li>
                        <li><a href="{{ route('pendaftaran') }}"><i class="fas fa-user-plus me-2"></i>Pendaftaran</a></li>
                        <li><a href="{{ route('siswa.index') }}"><i class="fas fa-users me-2"></i>Data Siswa</a></li>
                        <li><a href="{{ route('statistik') }}"><i class="fas fa-chart-bar me-2"></i>Statistik</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <h5><i class="fas fa-phone me-2"></i>Kontak</h5>
                    <p>
                        <i class="fas fa-map-marker-alt me-2"></i>Jl. Sawotratap No. 123<br>
                        Sidoarjo, 61254<br>
                        Jawa Timur, Indonesia
                    </p>
                    <p>
                        <i class="fas fa-phone me-2"></i>Telepon: (031) 1234-5678<br>
                        <i class="fas fa-envelope me-2"></i>Email: info@sdnsawotratap1.sch.id<br>
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp: +62 812-3456-7890
                    </p>
                </div>
            </div>
            <hr style="border-color: rgba(102, 126, 234, 0.2); margin: 40px 0 20px;">
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        © {{ date('Y') }} SDN Sawotratap 1. Sistem Pendaftaran Siswa Baru - Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                        <br>
                        Dikembangkan dengan <i class="fas fa-heart text-danger"></i> untuk pendidikan yang lebih baik
                    </small>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS from Cloudflare --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    {{-- SweetAlert2 JS from Cloudflare --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.all.min.js"></script>
    
    {{-- AOS (Animate On Scroll) JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    {{-- Custom JavaScript --}}
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Hide loading overlay after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingOverlay').classList.add('hidden');
            }, 500);
        });

        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
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

        // Enhanced username display function
        function displayUsername() {
            let username = null;
            
            // Priority 1: Get from sessionStorage (login data)
            username = sessionStorage.getItem('currentUser') || sessionStorage.getItem('username');
            
            // Priority 2: Get from localStorage (persistent data)
            if (!username) {
                username = localStorage.getItem('currentUser') || localStorage.getItem('username');
            }
            
            // Priority 3: Check from server-side Laravel auth (if available)
            @if(auth()->check())
                if (!username) {
                    username = '{{ auth()->user()->username ?? auth()->user()->name }}';
                }
            @endif
            
            // Priority 4: Check from URL parameters (if login redirect with parameter)
            if (!username) {
                const urlParams = new URLSearchParams(window.location.search);
                username = urlParams.get('user') || urlParams.get('username');
            }
            
            // Default if still no username
            if (!username || username === 'null' || username === '') {
                username = 'Admin';
            }
            
            // Display username in navbar
            const usernameElement = document.getElementById('username-text');
            if (usernameElement) {
                usernameElement.textContent = username;
            }
            
            // Save to sessionStorage for consistency
            if (username !== 'Admin') {
                sessionStorage.setItem('currentUser', username);
            }
            
            console.log('Current username displayed:', username);
        }

        // Enhanced logout function with SweetAlert2
        function handleLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#f56565',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Ya, Logout',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                reverseButtons: true,
                backdrop: true,
                allowOutsideClick: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Mohon tunggu sebentar',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Clear all data
                    sessionStorage.clear();
                    localStorage.clear();
                    
                    // Clear cookies if any
                    document.cookie.split(";").forEach(function(c) { 
                        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
                    });
                    
                    // Simulate logout process
                    setTimeout(function() {
                        // Success message
                        Swal.fire({
                            title: 'Logout Berhasil!',
                            text: 'Anda telah berhasil keluar dari sistem',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            showClass: {
                                popup: 'animate__animated animate__fadeInUp animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutDown animate__faster'
                            }
                        }).then(() => {
                            // Redirect to login page
                            @if(Route::has('login'))
                                window.location.href = "{{ route('login') }}";
                            @else
                                window.location.href = '/login';
                            @endif
                        });
                    }, 1000);
                }
            });
        }

        // Function to set current user
        function setCurrentUser(username) {
            if (username && username !== '') {
                sessionStorage.setItem('currentUser', username);
                localStorage.setItem('currentUser', username);
                displayUsername();
                
                // Show welcome message
                Swal.fire({
                    title: 'Selamat Datang!',
                    text: `Halo ${username}, selamat datang di sistem PSB SDN Sawotratap 1`,
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showClass: {
                        popup: 'animate__animated animate__fadeInRight animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutRight animate__faster'
                    }
                });
            }
        }

        // Show notification function
        function showNotification(title, text, type = 'info') {
            Swal.fire({
                title: title,
                text: text,
                icon: type,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight animate__faster'
                }
            });
        }

        // Form validation helper
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            let firstInvalidInput = null;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            });

            if (!isValid && firstInvalidInput) {
                firstInvalidInput.focus();
                showNotification('Perhatian', 'Mohon lengkapi semua field yang wajib diisi', 'warning');
            }

            return isValid;
        }

        // Auto-hide alerts
        function autoHideAlerts() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('animate__animated', 'animate__fadeOutUp');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Create particles
            createParticles();
            
            // Display username
            displayUsername();
            
            // Auto-hide alerts
            autoHideAlerts();
            
            // Check for login parameter in URL
            const urlParams = new URLSearchParams(window.location.search);
            const loginUser = urlParams.get('user') || urlParams.get('username');
            if (loginUser) {
                setCurrentUser(loginUser);
            }

            // Add click effects to buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.5);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.info-card').forEach(card => {
                observer.observe(card);
            });

            // Add typing effect to hero title
            const heroTitle = document.querySelector('.hero h1');
            if (heroTitle) {
                const originalText = heroTitle.textContent;
                heroTitle.textContent = '';
                let index = 0;
                
                function typeText() {
                    if (index < originalText.length) {
                        heroTitle.textContent += originalText.charAt(index);
                        index++;
                        setTimeout(typeText, 50);
                    }
                }
                
                setTimeout(typeText, 1000);
            }

            console.log('Page initialized successfully');
        });

        // Prevent back button after logout
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Update username periodically
        setInterval(displayUsername, 5000);

        // Listen for storage changes (sync across tabs)
        window.addEventListener('storage', function(e) {
            if (e.key === 'currentUser' || e.key === 'username') {
                displayUsername();
            }
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + H = Home
            if (e.altKey && e.key === 'h') {
                e.preventDefault();
                window.location.href = "{{ route('beranda') }}";
            }
            
            // Alt + P = Pendaftaran
            if (e.altKey && e.key === 'p') {
                e.preventDefault();
                window.location.href = "{{ route('pendaftaran') }}";
            }
            
            // Alt + S = Statistik
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                window.location.href = "{{ route('statistik') }}";
            }
            
            // Alt + L = Logout
            if (e.altKey && e.key === 'l') {
                e.preventDefault();
                handleLogout();
            }
        });

        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .animate__fadeInUp {
                animation-duration: 0.8s;
            }
            
            .animate__fadeOutUp {
                animation-duration: 0.5s;
            }
            
            .animate__fadeInRight {
                animation-duration: 0.6s;
            }
            
            .animate__fadeOutRight {
                animation-duration: 0.4s;
            }
        `;
        document.head.appendChild(style);

        // Debug functions (exposed to global scope)
        window.displayUsername = displayUsername;
        window.setCurrentUser = setCurrentUser;
        window.showNotification = showNotification;
        window.validateForm = validateForm;
        
        // Check user data function for debugging
        function checkUserData() {
            console.log('=== User Data Debug ===');
            console.log('SessionStorage currentUser:', sessionStorage.getItem('currentUser'));
            console.log('SessionStorage username:', sessionStorage.getItem('username'));
            console.log('LocalStorage currentUser:', localStorage.getItem('currentUser'));
            console.log('LocalStorage username:', localStorage.getItem('username'));
            console.log('Current displayed username:', document.getElementById('username-text')?.textContent);
            console.log('=====================');
        }
        
        window.checkUserData = checkUserData;

        // Show system info on console
        console.log(`
        ╔══════════════════════════════════════════════════════════════╗
        ║                    SDN SAWOTRATAP 1                          ║
        ║              Sistem Pendaftaran Siswa Baru                   ║
        ║                                                              ║
        ║  Framework: Laravel + Bootstrap + Tailwind + SweetAlert2     ║
        ║  Version: 1.0.0                                              ║
        ║  Build: ${new Date().toLocaleDateString()}                                        ║
        ╚══════════════════════════════════════════════════════════════╝
        `);
    </script>
</body>
</html>