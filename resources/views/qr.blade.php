<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generator QR Code Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width: 500px;">
        <div class="card-body text-center p-4">

            <h3 class="mb-3">Generator QR Code Mahasiswa</h3>

            <p class="text-muted">
                Masukkan NIM mahasiswa untuk membuat QR Code otomatis.
            </p>

            <div class="mb-3">
                <input type="text"
                       id="nim"
                       class="form-control"
                       placeholder="Masukkan NIM mahasiswa">
            </div>

            <button class="btn btn-primary w-100" onclick="buatQR()">
                Buat QR Code
            </button>

            <div id="hasil" class="mt-4"></div>

            <button id="tombolDownload"
                    class="btn btn-success mt-3"
                    style="display:none"
                    onclick="downloadQR()">
                Simpan QR Code
            </button>

            <a href="{{ route('scan') }}" class="btn btn-secondary mt-3">
                Kembali ke Scan Absensi
            </a>

        </div>
    </div>
</div>

<script>
    let qrCode;

    function buatQR() {
        const nim = document.getElementById("nim").value.trim();
        const hasil = document.getElementById("hasil");

        if (nim === "") {
            alert("Silakan masukkan NIM mahasiswa!");
            return;
        }

        hasil.innerHTML = "";

        const judul = document.createElement("h5");
        judul.textContent = "QR Code NIM: " + nim;
        hasil.appendChild(judul);

        const tempatQR = document.createElement("div");
        tempatQR.id = "qrcode";
        tempatQR.className = "d-flex justify-content-center mt-3";
        hasil.appendChild(tempatQR);

        qrCode = new QRCode(tempatQR, {
            text: nim,
            width: 220,
            height: 220,
            correctLevel: QRCode.CorrectLevel.H
        });

        document.getElementById("tombolDownload").style.display = "inline-block";
    }

    function downloadQR() {
        const qr = document.querySelector("#qrcode canvas");

        if (!qr) {
            alert("Buat QR Code terlebih dahulu!");
            return;
        }

        const link = document.createElement("a");
        link.href = qr.toDataURL("image/png");
        link.download = "QR_Mahasiswa_" +
            document.getElementById("nim").value.trim() + ".png";
        link.click();
    }
</script>

</body>
</html>