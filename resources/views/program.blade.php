<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Unggulan - SDN Sawotratap 1</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: #667eea !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .nav-link {
            color: #667eea !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: #764ba2 !important;
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            color: #764ba2 !important;
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .hover-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .program-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
        }
        
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 15px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('beranda') }}">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <strong>SDN SAWOTRATAP 1</strong>
                    <div style="font-size: 0.8em; opacity: 0.8;">Sistem Pendaftaran</div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('beranda') }}">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('program') }}">
                            <i class="fas fa-book me-1"></i>Program
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('statistik') }}">
                            <i class="fas fa-chart-bar me-1"></i>Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siswa.index') }}">
                            <i class="fas fa-users me-1"></i>Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pendaftaran') }}">
                            <i class="fas fa-user-plus me-1"></i>Pendaftaran
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="btn btn-logout" onclick="handleLogout()">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary">
                    <div class="card-body text-white text-center py-5">
                        <h1 class="display-4 fw-bold mb-3">Program Unggulan</h1>
                        <p class="lead mb-0">Mengembangkan Potensi Siswa Melalui Program Berkualitas</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Section --}}
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-primary">8</h3>
                        <p class="text-muted mb-0">Program Unggulan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-success">15</h3>
                        <p class="text-muted mb-0">Mata Pelajaran</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-warning">25</h3>
                        <p class="text-muted mb-0">Guru Profesional</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-info">10</h3>
                        <p class="text-muted mb-0">Fasilitas</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Programs Grid --}}
        <div class="row">
            @foreach($programs as $index => $program)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="program-icon">
                                <i class="{{ $program['icon'] }} fa-2x text-primary"></i>
                            </div>
                            <span class="badge bg-success">{{ $program['status'] }}</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">{{ $program['name'] }}</h5>
                        <p class="card-text text-muted mb-3">{{ $program['description'] }}</p>
                        <div class="mb-3">
                            @foreach($program['tags'] as $tag)
                            <span class="badge bg-light text-dark me-1 mb-1">
                                <i class="fas fa-tag me-1"></i>{{ $tag }}
                            </span>
                            @endforeach
                        </div>
                        <div class="d-grid">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle me-2"></i>Detail Program
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Kurikulum Section --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h3 class="fw-bold text-primary mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Struktur Kurikulum
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Mata Pelajaran Wajib --}}
                            <div class="col-lg-6 mb-4">
                                <h5 class="fw-bold text-success mb-3">
                                    <i class="fas fa-star me-2"></i>Mata Pelajaran Wajib
                                </h5>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Pendidikan Agama dan Budi Pekerti</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">4 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Pendidikan Pancasila dan Kewarganegaraan</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">5 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Bahasa Indonesia</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">8 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Matematika</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">6 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Ilmu Pengetahuan Alam</h6>
                                            <small class="text-muted">Kelas 4-6</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">3 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Ilmu Pengetahuan Sosial</h6>
                                            <small class="text-muted">Kelas 4-6</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">3 jam/minggu</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Mata Pelajaran Pengembangan --}}
                            <div class="col-lg-6 mb-4">
                                <h5 class="fw-bold text-warning mb-3">
                                    <i class="fas fa-seedling me-2"></i>Mata Pelajaran Pengembangan
                                </h5>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Seni Budaya dan Prakarya</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">4 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Pendidikan Jasmani, Olahraga & Kesehatan</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">4 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Bahasa Inggris</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">2 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Teknologi Informasi</h6>
                                            <small class="text-muted">Kelas 3-6</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">2 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Bahasa Daerah (Jawa)</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">2 jam/minggu</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                        <div>
                                            <h6 class="mb-1">Ekstrakurikuler</h6>
                                            <small class="text-muted">Kelas 1-6</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">Sesuai minat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleLogout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                sessionStorage.clear();
                window.location.href = '{{ route("login") }}';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling for internal links
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

            // Add loading animation for cards
            const cards = document.querySelectorAll('.hover-card');
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
    </script>
</body>
</html>