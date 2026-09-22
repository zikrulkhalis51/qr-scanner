<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Library untuk membuat QR Code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .header {
            background: #173b70;
            color: white;
            padding: 20px;
        }

        .container-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.06);
            margin-top: 30px;
        }

        .table thead {
            background: #173b70;
            color: white;
        }

        #qrcode {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #modalQR,
            #modalQR * {
                visibility: visible;
            }

            #modalQR {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .modal-header button,
            .modal-footer {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<div class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Data Mahasiswa</h4>
        <a href="/" class="btn btn-light">Kembali ke Dashboard</a>
    </div>
</div>

<div class="container">
    <div class="container-box">

        <h3>Daftar Mahasiswa</h3>

        <p class="text-muted">
            Data mahasiswa yang tersimpan di Google Sheets.
        </p>

        @if(isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-4">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kelas</th>
                        <th>QR Code</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mahasiswa as $mhs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $mhs['nim'] ?? '' }}</td>

                            <td>{{ $mhs['nama'] ?? '' }}</td>

                            <td>{{ $mhs['kelas'] ?? '' }}</td>

                            <td>
                                @if(!empty($mhs['nim']))
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-sm"
                                        onclick="tampilkanQR(
                                            @js((string) $mhs['nim']),
                                            @js((string) ($mhs['nama'] ?? '')),
                                            @js((string) ($mhs['kelas'] ?? ''))
                                        )">
                                        Lihat QR
                                    </button>
                                @else
                                    <span class="text-muted">NIM tidak tersedia</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada data mahasiswa atau data gagal dimuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

<!-- Modal untuk menampilkan QR Code -->
<div class="modal fade" id="modalQR" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">

            <div class="modal-header">
                <h5 class="modal-title">QR Code Mahasiswa</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>
            </div>

            <div class="modal-body">

                <h5 id="namaMahasiswaQR"></h5>

                <p id="nimMahasiswaQR"></p>

                <p id="kelasMahasiswaQR"></p>

                <div id="qrcode"></div>

                <p class="text-muted">
                    Tunjukkan QR Code ini kepada petugas untuk melakukan absensi.
                </p>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Tutup
                </button>

                <button type="button"
                        class="btn btn-success"
                        onclick="window.print()">
                    Cetak QR
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function tampilkanQR(nim, nama, kelas) {
        document.getElementById('namaMahasiswaQR').textContent = nama;
        document.getElementById('nimMahasiswaQR').textContent = 'NIM: ' + nim;
        document.getElementById('kelasMahasiswaQR').textContent = 'Kelas: ' + kelas;

        const tempatQR = document.getElementById('qrcode');
        tempatQR.innerHTML = '';

        if (typeof QRCode === 'undefined') {
            alert('Library QR Code gagal dimuat. Periksa koneksi internet.');
            return;
        }

        // QR Code berisi NIM mahasiswa
        new QRCode(tempatQR, {
            text: nim,
            width: 200,
            height: 200,
            correctLevel: QRCode.CorrectLevel.H
        });

        const modalElement = document.getElementById('modalQR');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

        modal.show();
    }
</script>

</body>
</html>