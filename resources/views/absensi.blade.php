<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Absensi Manual Mahasiswa</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

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

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>

<div class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Absensi Manual</h4>
        <a href="/" class="btn btn-light">Kembali</a>
    </div>
</div>

<div class="container py-5">
    <div class="card mx-auto" style="max-width: 550px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-3">Form Absensi Mahasiswa</h3>

            <p class="text-muted text-center">
                Masukkan NIM untuk mencari data mahasiswa.
            </p>

            <form id="formCari">
                <div class="mb-3">
                    <label class="form-label">NIM Mahasiswa</label>
                    <input type="text"
                           id="nim"
                           class="form-control"
                           placeholder="Masukkan NIM"
                           required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Cari Mahasiswa
                </button>
            </form>

            <div id="pesan" class="mt-3"></div>

            <div id="dataMahasiswa" class="mt-4" style="display:none;">
                <hr>

                <h5>Data Mahasiswa</h5>

                <p><strong>NIM:</strong> <span id="hasilNim"></span></p>
                <p><strong>Nama:</strong> <span id="hasilNama"></span></p>
                <p><strong>Kelas:</strong> <span id="hasilKelas"></span></p>

                <button id="tombolAbsen" class="btn btn-success w-100">
                    Simpan Absensi
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const formCari = document.getElementById('formCari');
    const inputNim = document.getElementById('nim');
    const pesan = document.getElementById('pesan');
    const dataMahasiswa = document.getElementById('dataMahasiswa');
    const tombolAbsen = document.getElementById('tombolAbsen');

    let nimDitemukan = '';

    formCari.addEventListener('submit', async function(event) {
        event.preventDefault();

        const nim = inputNim.value.trim();

        pesan.innerHTML = '';
        dataMahasiswa.style.display = 'none';
        nimDitemukan = '';

        if (!nim) {
            pesan.innerHTML = '<div class="alert alert-warning">Masukkan NIM terlebih dahulu.</div>';
            return;
        }

        pesan.innerHTML = '<div class="alert alert-info">Sedang mencari mahasiswa...</div>';

        try {
            const response = await fetch("{{ route('cari.mahasiswa') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ nim: nim })
            });

            const hasil = await response.json();

            if (hasil.status === 'success') {
                nimDitemukan = hasil.nim;

                document.getElementById('hasilNim').textContent = hasil.nim;
                document.getElementById('hasilNama').textContent = hasil.nama;
                document.getElementById('hasilKelas').textContent = hasil.kelas;

                pesan.innerHTML = '<div class="alert alert-success">Mahasiswa ditemukan.</div>';
                dataMahasiswa.style.display = 'block';
            } else {
                pesan.innerHTML = '<div class="alert alert-danger">' +
                    (hasil.pesan || 'Mahasiswa tidak ditemukan.') +
                    '</div>';
            }
        } catch (error) {
            pesan.innerHTML = '<div class="alert alert-danger">Gagal menghubungi server.</div>';
        }
    });

    tombolAbsen.addEventListener('click', async function() {
        if (!nimDitemukan) {
            return;
        }

        tombolAbsen.disabled = true;
        pesan.innerHTML = '<div class="alert alert-info">Sedang menyimpan absensi...</div>';

        try {
            const response = await fetch("{{ route('simpan.absensi') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ nim: nimDitemukan })
            });

            const hasil = await response.json();

            if (hasil.status === 'success') {
                pesan.innerHTML = '<div class="alert alert-success">' +
                    hasil.pesan + '<br>Nama: ' + hasil.nama +
                    '<br>Tanggal: ' + hasil.tanggal +
                    '<br>Waktu: ' + hasil.waktu +
                    '</div>';

                dataMahasiswa.style.display = 'none';
                inputNim.value = '';
                nimDitemukan = '';
            } else {
                pesan.innerHTML = '<div class="alert alert-warning">' +
                    (hasil.pesan || 'Absensi gagal disimpan.') +
                    '</div>';
            }
        } catch (error) {
            pesan.innerHTML = '<div class="alert alert-danger">Gagal menyimpan absensi.</div>';
        } finally {
            tombolAbsen.disabled = false;
        }
    });
</script>

</body>
</html>