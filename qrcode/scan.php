<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi QR</title>
    <script src="html5-qrcode.min.js"></script>
    <style>
        select {
            width: 100px;
            padding: 5px;
            border: 1px solid steelblue;
            box-shadow: steelblue 2px 2px 2px;
            box-sizing: border-box;
        }

        option {
            padding: 5px;
        }

        .table-wrapper {
            max-height: 180px;
            /* Sesuaikan tinggi maksimal */
            overflow-y: auto;
            /* Mengaktifkan scroll secara vertikal */
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            box-shadow: steelblue 2px 2px 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h2>Presensi dengan QR</h2>
    <div class="conten" style="display:flex;justify-content:space-evenly;align-items:center;
    flex-direction: row;gap:9px;">
        <!-- Area Kamera -->
        <div id="reader" style="margin-left:5%; width: 200px; height: 150px;z-index:0;"></div>
        <!-- Area Hasil Scan -->
        <div id="result" style="display:flex;justify-content:center;align-items:center;flex-direction:column;">Nama :

            <!-- PHP untuk menampilkan hasil dan dropdown -->
            <?php
            if (isset($_POST['hasil'])) {
                include "../db.php";
                $nama = htmlspecialchars($_POST['hasil']);
                $ambil = mysqli_query($db, "SELECT program, level FROM siswa WHERE nama = '$nama'");
                if (!$ambil) {
                    die("Query Error: " . mysqli_error($db));
                }
                if (mysqli_num_rows($ambil) > 0) {
                    $data = [];
                    while ($row = mysqli_fetch_assoc($ambil)) {
                        $data[] = $row;
                    }
                }
            }
            ?>
        </div>
        <script>
            function onScanSuccess(decodedText, decodedResult) {
                console.log("QR Code Terbaca:", decodedText);
                document.getElementById("result").innerHTML = `Nama : ${decodedText} <br>            <form action="#presensi" method="post">
                <input type="hidden" name="hasil" id="hiddenResult">
                <button type="submit">Submit</button>
            </form>
`;
                document.getElementById("hiddenResult").value = decodedText;

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
                            qrbox: { width: 200, height: 200 }
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