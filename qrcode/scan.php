<script src="html5-qrcode.min.js"></script>

</head>

<body>
    <h2>Presensi dengan QR</h2>
    <div class="conten" style="display:flex;justify-content:flex-start;">
        <!-- Area Kamera -->
        <div id="reader" style="width: 300px; height: 290px;margin-right:50px;"></div>
        <!-- Area Hasil Scan -->
        <div id="result">Hasil Presensi :</div>
    </div>
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