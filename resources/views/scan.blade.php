<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Absensi Online - Scan QR</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #0d6efd;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .scanner-card {
            max-width: 600px;
            margin: 35px auto;
            padding: 25px;
            background: white;
            border-radius: 15px;
        }

        #reader {
            width: 100%;
        }

        #hasil {
            display: none;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            Absensi Online
        </a>

        <a href="/" class="btn btn-light">
            Dashboard
        </a>
    </div>
</nav>

<div class="container">

    <div class="scanner-card">

        <h3 class="text-center mb-3">
            Scan QR Code Mahasiswa
        </h3>

        <p class="text-center text-muted">
            Arahkan kamera ke QR Code yang berisi NIM mahasiswa.
        </p>

        <div id="reader"></div>

        <div class="text-center mt-3">

            <button id="mulai" class="btn btn-primary">
                Mulai Scan
            </button>

            <button id="berhenti" class="btn btn-danger" disabled>
                Berhenti
            </button>

        </div>

        <div id="pesan" class="alert mt-3" style="display:none"></div>

        <div id="hasil" class="card mt-4 p-3">

            <h5 class="text-center text-success">
                Data Mahasiswa
            </h5>

            <hr>

            <p><strong>NIM:</strong> <span id="nim"></span></p>

            <p><strong>Nama:</strong> <span id="nama"></span></p>

            <p><strong>Kelas:</strong> <span id="kelas"></span></p>

            <button id="simpan" class="btn btn-success">
                Simpan Absensi
            </button>

        </div>

    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const scanner = new Html5Qrcode("reader");

const tombolMulai = document.getElementById("mulai");
const tombolBerhenti = document.getElementById("berhenti");
const tombolSimpan = document.getElementById("simpan");

const hasil = document.getElementById("hasil");
const pesan = document.getElementById("pesan");

let sedangScan = false;
let nimMahasiswa = "";
let sedangMemproses = false;

function tampilkanPesan(teks, jenis = "info") {
    pesan.className = "alert alert-" + jenis + " mt-3";
    pesan.innerText = teks;
    pesan.style.display = "block";
}

async function berhentiScan() {
    if (sedangScan) {
        try {
            await scanner.stop();
        } catch (error) {
            console.error(error);
        }

        sedangScan = false;
        tombolMulai.disabled = false;
        tombolBerhenti.disabled = true;
    }
}

tombolMulai.addEventListener("click", async function () {

    pesan.style.display = "none";
    hasil.style.display = "none";

    try {

        await scanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },

            async function (teks) {

                if (sedangMemproses) return;

                sedangMemproses = true;
                nimMahasiswa = teks.trim();

                await berhentiScan();

                tampilkanPesan("Mencari data mahasiswa...", "info");

                try {

                    const response = await fetch(
                        "{{ route('cari.mahasiswa') }}",
                        {
                            method: "POST",

                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content,
                                "Accept": "application/json"
                            },

                            body: JSON.stringify({
                                nim: nimMahasiswa
                            })
                        }
                    );

                    const data = await response.json();

                    if (data.status === "success") {

                        document.getElementById("nim").innerText = data.nim;
                        document.getElementById("nama").innerText = data.nama;
                        document.getElementById("kelas").innerText = data.kelas;

                        hasil.style.display = "block";

                        tampilkanPesan(
                            "Mahasiswa ditemukan. Silakan simpan absensi.",
                            "success"
                        );

                    } else {

                        tampilkanPesan(
                            data.pesan || "Mahasiswa tidak ditemukan.",
                            "danger"
                        );

                    }

                } catch (error) {

                    tampilkanPesan(
                        "Gagal menghubungi server Laravel.",
                        "danger"
                    );

                }

                sedangMemproses = false;

            },

            function () {}

        );

        sedangScan = true;

        tombolMulai.disabled = true;
        tombolBerhenti.disabled = false;

    } catch (error) {

        tampilkanPesan(
            "Kamera tidak dapat diaktifkan. Periksa izin kamera.",
            "danger"
        );

    }

});

tombolBerhenti.addEventListener("click", berhentiScan);

tombolSimpan.addEventListener("click", async function () {

    if (!nimMahasiswa) {
        tampilkanPesan("Silakan scan QR Code terlebih dahulu.", "warning");
        return;
    }

    tombolSimpan.disabled = true;

    tampilkanPesan("Menyimpan absensi...", "info");

    try {

        const response = await fetch(
            "{{ route('simpan.absensi') }}",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "Accept": "application/json"
                },

                body: JSON.stringify({
                    nim: nimMahasiswa
                })
            }
        );

        const data = await response.json();

        if (data.status === "success") {

            tampilkanPesan(
                "Absensi berhasil disimpan untuk " + data.nama +
                " pada " + data.tanggal + " pukul " + data.waktu,
                "success"
            );

            hasil.style.display = "none";
            nimMahasiswa = "";

        } else {

            tampilkanPesan(
                data.pesan || "Absensi gagal disimpan.",
                "danger"
            );

        }

    } catch (error) {

        tampilkanPesan(
            "Terjadi kesalahan saat menyimpan absensi.",
            "danger"
        );

    }

    tombolSimpan.disabled = false;

});

</script>

</body>
</html>