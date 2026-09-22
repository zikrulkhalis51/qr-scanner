<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Absensi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Laporan Absensi Mahasiswa</h3>

        <div class="no-print">
            <a href="/" class="btn btn-secondary">Kembali</a>
            <button onclick="window.print()" class="btn btn-primary">
                Cetak Laporan
            </button>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif

    @php
        $jumlahMahasiswa = count($mahasiswa);
        $jumlahHadir = count($kehadiran);
        $jumlahBelumHadir = max(0, $jumlahMahasiswa - $jumlahHadir);
    @endphp

    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Jumlah Mahasiswa</h6>
                    <h2>{{ $jumlahMahasiswa }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h6 class="text-muted">Jumlah Data Kehadiran</h6>
                    <h2>{{ $jumlahHadir }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <h6 class="text-muted">Belum Hadir</h6>
                    <h2>{{ $jumlahBelumHadir }}</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Rekap Data Kehadiran</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($kehadiran as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['nim'] ?? '-' }}</td>
                                <td>{{ $data['nama'] ?? '-' }}</td>
                                <td>{{ $data['kelas'] ?? '-' }}</td>
                                <td>{{ $data['tanggal'] ?? '-' }}</td>
                                <td>{{ $data['waktu'] ?? '-' }}</td>
                                <td>{{ $data['status'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada data kehadiran.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

</body>
</html>