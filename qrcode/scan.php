<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>QR Code Scanner with Camera</title>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="html5-qrcode.min.js"></script>

</head>

<body>
    <h2>QR Code Scanner</h2>

    <!-- Area Kamera -->
    <div id="reader" style="width: 300px; height: 290px;"></div>

    <!-- Area Hasil Scan -->
    <div id="result">Hasil QR akan muncul di sini</div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code Terbaca:", decodedText);
            document.getElementById("result").innerHTML = `Isi QR Code: ${decodedText}`;

            // Hentikan pemindaian setelah berhasil membaca kode QR
            html5QrcodeScanner.clear();
        }

        function onScanFailure(error) {
            console.warn(`QR Code tidak terbaca: ${error}`);
        }

        document.addEventListener("DOMContentLoaded", function () {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                let html5QrcodeScanner = new Html5Qrcode("reader");

                html5QrcodeScanner.start(
                    { facingMode: "environment" }, // Menggunakan kamera belakang
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    onScanFailure
                ).catch(err => {
                    console.error("Tidak dapat mengakses kamera:", err);
                    document.getElementById("result").innerHTML = "Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.";
                });
            } else {
                document.getElementById("result").innerHTML = "Perangkat tidak mendukung akses kamera.";
            }
        });
    </script>
</body>

</html>
s