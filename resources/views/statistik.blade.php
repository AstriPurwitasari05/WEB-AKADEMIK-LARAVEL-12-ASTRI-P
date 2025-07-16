<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Statistik Siswa' }} - SDN Sawotratap 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8fafc;
            --card-shadow: 0 15px 35px rgba(0,0,0,0.1);
            --hover-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
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

        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: height 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--hover-shadow);
        }

        .stats-card:hover::before {
            height: 6px;
        }

        .stats-card.primary::before {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stats-card.success::before {
            background: linear-gradient(90deg, var(--success-color), #20c997);
        }

        .stats-card.warning::before {
            background: linear-gradient(90deg, var(--warning-color), #e0a800);
        }

        .stats-card.info::before {
            background: linear-gradient(90deg, var(--info-color), #20c997);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .stats-icon {
            font-size: 4rem;
            opacity: 0.1;
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .chart-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }

        .chart-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--primary-color);
            text-align: center;
            position: relative;
        }

        .chart-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, -20px) rotate(180deg); }
        }

        .page-title {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .breadcrumb {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .breadcrumb-item a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-item.active {
            color: white;
            font-weight: 500;
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

        .btn-refresh {
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-refresh:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.2));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            color: white;
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(200, 35, 51, 0.1) 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            height: 100%;
        }

        .info-card h5 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .info-card ul li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-size: 1.1rem;
        }

        .info-card ul li:last-child {
            border-bottom: none;
        }

        .info-card ul li strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            padding: 20px 0;
            margin-top: 50px;
        }

        .chart-wrapper {
            position: relative;
            height: 450px;
            margin: 1rem 0;
        }

        .chart-wrapper canvas {
            border-radius: 12px;
        }

        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #667eea;
        }

        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23667eea' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 768px) {
            .stats-number {
                font-size: 2.5rem;
            }
            .page-title {
                font-size: 2.5rem;
            }
            .chart-container {
                padding: 2rem;
            }
            .stats-card {
                padding: 2rem;
            }
            .chart-wrapper {
                height: 350px;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                padding: 3rem 0;
            }
            .page-title {
                font-size: 2rem;
            }
            .stats-number {
                font-size: 2rem;
            }
            .chart-container {
                padding: 1.5rem;
            }
            .chart-wrapper {
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <strong>SDN Sawotratap 1</strong>
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
                        <a class="nav-link" href="{{ route('siswa.index') }}">
                            <i class="fas fa-users me-1"></i>Data Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('statistik') }}">
                            <i class="fas fa-chart-bar me-1"></i>Statistik
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                                <a class="dropdown-item text-danger" href="{{ route('login') }}">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="fas fa-chart-line me-3"></i>
                        Statistik Siswa
                    </h1>
                    <p class="page-subtitle mb-0">
                        Analisis data dan statistik siswa secara komprehensif
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-refresh" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>
                        Refresh Data
                    </button>
                </div>
            </div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mt-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('beranda') }}">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Statistik</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <!-- Alert Messages -->
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

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <!-- Total Siswa -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card primary position-relative">
                    <div class="stats-number">
                        {{ number_format($stats['total_siswa'] ?? 0) }}
                    </div>
                    <div class="stats-label">Total Siswa Terdaftar</div>
                    <i class="fas fa-users stats-icon" style="color: var(--primary-color);"></i>
                </div>
            </div>

            <!-- Rata-rata Nilai -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card info position-relative">
                    <div class="stats-number">
                        {{ number_format($stats['rata_rata_nilai'] ?? 0, 1) }}
                    </div>
                    <div class="stats-label">Rata-rata Nilai UN</div>
                    <i class="fas fa-graduation-cap stats-icon" style="color: var(--info-color);"></i>
                </div>
            </div>

            <!-- Persentase Kelulusan -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card success position-relative">
                    <div class="stats-number">
                        {{ number_format($stats['persentase_kelulusan'] ?? 0) }}%
                    </div>
                    <div class="stats-label">Persentase Kelulusan</div>
                    <i class="fas fa-trophy stats-icon" style="color: var(--success-color);"></i>
                </div>
            </div>

            <!-- Siswa Diterima -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card warning position-relative">
                    <div class="stats-number">
                        {{ number_format($stats['performa_akademik']['diterima'] ?? 0) }}
                    </div>
                    <div class="stats-label">Siswa Diterima</div>
                    <i class="fas fa-check-circle stats-icon" style="color: var(--warning-color);"></i>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Distribusi Nilai Chart -->
            <div class="col-lg-7 mb-4">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Distribusi Nilai Ujian Nasional
                    </h3>
                    <div class="chart-wrapper">
                        <canvas id="nilaiChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Pendaftaran Chart -->
            <div class="col-lg-5 mb-4">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        Status Pendaftaran
                    </h3>
                    <div class="chart-wrapper">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="row">
            <div class="col-12">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Statistik
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-chart-line me-2"></i>Distribusi Nilai:</h5>
                                <ul class="list-unstyled">
                                    @if(isset($stats['distribusi_nilai']))
                                        @foreach($stats['distribusi_nilai'] as $range => $count)
                                        <li><strong>{{ $range }}:</strong> {{ $count }} siswa</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-clipboard-list me-2"></i>Status Pendaftaran:</h5>
                                <ul class="list-unstyled">
                                    @if(isset($stats['performa_akademik']))
                                    <li><strong>Diterima:</strong> {{ $stats['performa_akademik']['diterima'] }} siswa</li>
                                    <li><strong>Ditolak:</strong> {{ $stats['performa_akademik']['ditolak'] }} siswa</li>
                                    <li><strong>Menunggu:</strong> {{ $stats['performa_akademik']['menunggu'] }} siswa</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="logo me-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" style="color: var(--primary-color);">SDN Sawotratap 1</h6>
                            <small class="text-muted">Sistem Pendaftaran Siswa Baru</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        © {{ date('Y') }} SDN Sawotratap 1. <br class="d-md-none">
                        Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js Configuration -->
    <script>
        // Data from Laravel Controller
        const distribusiNilai = @json($stats['distribusi_nilai'] ?? []);
        const performaAkademik = @json($stats['performa_akademik'] ?? []);

        // Distribusi Nilai Chart
        const nilaiCtx = document.getElementById('nilaiChart').getContext('2d');
        
        const createGradient = (ctx, color1, color2) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        };

        const nilaiChart = new Chart(nilaiCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(distribusiNilai),
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: Object.values(distribusiNilai),
                    backgroundColor: [
                        createGradient(nilaiCtx, '#667eea', '#764ba2'),
                        createGradient(nilaiCtx, '#28a745', '#20c997'),
                        createGradient(nilaiCtx, '#ffc107', '#e0a800'),
                        createGradient(nilaiCtx, '#17a2b8', '#20c997'),
                        createGradient(nilaiCtx, '#dc3545', '#c82333')
                    ],
                    borderColor: [
                        '#667eea',
                        '#28a745',
                        '#ffc107',
                        '#17a2b8',
                        '#dc3545'
                    ],
                    borderWidth: 3,
                    borderRadius: 12,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 15
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#6c757d'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.08)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6c757d'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Status Pendaftaran Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Diterima', 'Ditolak', 'Menunggu'],
                datasets: [{
                    data: [
                        performaAkademik.diterima || 0,
                        performaAkademik.ditolak || 0,
                        performaAkademik.menunggu || 0
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107'
                    ],
                    borderColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107'
                    ],
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 25,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#374151d',
                    font: {
                        size: 14,
                        weight: '600'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.2)',
                borderWidth: 1,
                cornerRadius: 12,
                padding: 15,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return label + ': ' + value + ' siswa (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Animation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });

    // Animate chart containers
    const chartContainers = document.querySelectorAll('.chart-container');
    chartContainers.forEach((container, index) => {
        container.style.opacity = '0';
        container.style.transform = 'translateY(30px)';
        setTimeout(() => {
            container.style.transition = 'all 0.6s ease';
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
        }, (statsCards.length * 150) + (index * 200));
    });
});

// Add hover effects for stats cards
document.querySelectorAll('.stats-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Auto refresh data every 5 minutes
setInterval(() => {
    const refreshBtn = document.querySelector('.btn-refresh');
    if (refreshBtn) {
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt fa-spin me-2"></i> Auto Refresh...';
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
}, 300000); // 5 minutes

// Add loading state to refresh button
document.querySelector('.btn-refresh').addEventListener('click', function() {
    this.innerHTML = '<i class="fas fa-sync-alt fa-spin me-2"></i> Memuat...';
    this.disabled = true;
});

// Print functionality
function printStatistics() {
    window.print();
}

// Export chart as image
function exportChart(chartId, filename) {
    const canvas = document.getElementById(chartId);
    const url = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.download = filename + '.png';
    link.href = url;
    link.click();
}

// Add export buttons functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add export buttons to chart containers if needed
    const chartContainers = document.querySelectorAll('.chart-container');
    chartContainers.forEach((container, index) => {
        const chartId = index === 0 ? 'nilaiChart' : 'statusChart';
        const filename = index === 0 ? 'distribusi-nilai' : 'status-pendaftaran';
        
        // Create export button
        const exportBtn = document.createElement('button');
        exportBtn.className = 'btn btn-sm btn-outline-primary position-absolute';
        exportBtn.style.top = '20px';
        exportBtn.style.right = '20px';
        exportBtn.innerHTML = '<i class="fas fa-download me-1"></i> Export';
        exportBtn.onclick = () => exportChart(chartId, filename);
        
        container.style.position = 'relative';
        container.appendChild(exportBtn);
    });
});

// Responsive chart resize
window.addEventListener('resize', function() {
    nilaiChart.resize();
    statusChart.resize();
});
</script>

</body>
</html>