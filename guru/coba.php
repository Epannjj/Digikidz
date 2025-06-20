<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Presensi QR (HP Support)</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        #reader {
            width: 300px;
            margin: auto;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h2>Scan QR Code</h2>
    <div id="reader"></div>
    <div id="result"></div>
    <button id="stop-btn">Stop</button>

    <script>
        let scanner = new Html5Qrcode("reader");

        function onScanSuccess(decodedText) {
            document.getElementById("result").innerHTML = `<p>QR terbaca: <strong>${decodedText}</strong></p>`;
            scanner.stop().catch(err => console.error(err));
        }

        function onScanFailure(error) {
            // silent
        }

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                const config = { fps: 10, qrbox: 250 };
                scanner.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
                    .catch(err => {
                        document.getElementById("result").innerHTML = `<p class="error">Gagal akses kamera: ${err}</p>`;
                    });
            } else {
                document.getElementById("result").innerHTML = "<p class='error'>Tidak ada kamera terdeteksi.</p>";
            }
        }).catch(err => {
            document.getElementById("result").innerHTML = `<p class="error">Error: ${err}</p>`;
        });

        document.getElementById("stop-btn").addEventListener("click", () => {
            scanner.stop().catch(err => console.error(err));
        });
    </script>
</body>

</html>