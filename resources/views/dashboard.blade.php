<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Absensi Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #173b70;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 35px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #28538e;
        }

        .content {
            padding: 30px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .menu-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.06);
            transition: 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
        }

        .menu-icon {
            font-size: 35px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .content {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <h4>ABSENSI<br>MAHASISWA</h4>

            <a href="/" class="active">🏠 Dashboard</a>
            <a href="/mahasiswa">👨‍🎓 Data Mahasiswa</a>
            <a href="/absensi">📝 Absensi Manual</a>
            <a href="/kehadiran">📋 Data Kehadiran</a>
            <a href="/laporan">📊 Laporan</a>
            <a href="/scan">📷 Scan QR</a>
        </div>

        <!-- Konten utama -->
        <div class="col-md-9 col-lg-10 content">

            <div class="welcome">
                <h2>Dashboard</h2>
                <p class="text-muted mb-0">
                    Selamat datang di Sistem Informasi Absensi Mahasiswa.
                    Kelola data mahasiswa dan kehadiran dengan mudah.
                </p>
            </div>

            <h4 class="mb-4">Menu Utama</h4>

            <div class="row g-4">

                <div class="col-md-6 col-xl-4">
                    <div class="card menu-card">
                        <div class="card-body text-center p-4">
                            <div class="menu-icon">👨‍🎓</div>
                            <h5>Data Mahasiswa</h5>
                            <p class="text-muted">
                                Melihat daftar mahasiswa yang terdaftar.
                            </p>
                            <a href="/mahasiswa" class="btn btn-primary">
                                Buka Data
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card menu-card">
                        <div class="card-body text-center p-4">
                            <div class="menu-icon">📝</div>
                            <h5>Absensi Manual</h5>
                            <p class="text-muted">
                                Melakukan absensi dengan memasukkan NIM.
                            </p>
                            <a href="/absensi" class="btn btn-success">
                                Mulai Absensi
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card menu-card">
                        <div class="card-body text-center p-4">
                            <div class="menu-icon">📋</div>
                            <h5>Data Kehadiran</h5>
                            <p class="text-muted">
                                Melihat daftar kehadiran mahasiswa.
                            </p>
                            <a href="/kehadiran" class="btn btn-info text-white">
                                Lihat Kehadiran
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card menu-card">
                        <div class="card-body text-center p-4">
                            <div class="menu-icon">📊</div>
                            <h5>Laporan Absensi</h5>
                            <p class="text-muted">
                                Melihat rekap data kehadiran mahasiswa.
                            </p>
                            <a href="/laporan" class="btn btn-warning">
                                Buka Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card menu-card">
                        <div class="card-body text-center p-4">
                            <div class="menu-icon">📷</div>
                            <h5>Scan QR Code</h5>
                            <p class="text-muted">
                                Pemindaian QR Code untuk absensi mahasiswa.
                            </p>
                            <a href="{{ route('scan') }}" class="btn btn-secondary">
                                Buka Scanner
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center text-muted mt-5">
                <small>© 2026 Sistem Informasi Absensi Mahasiswa</small>
            </div>

        </div>
    </div>
</div>

</body>
</html>