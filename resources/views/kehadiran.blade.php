<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Kehadiran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Data Kehadiran Mahasiswa</h4>
        </div>

        <div class="card-body">

            <a href="/" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            @if(isset($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endif

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
                                <td>
                                    <span class="badge bg-success">
                                        {{ $data['status'] ?? '-' }}
                                    </span>
                                </td>
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