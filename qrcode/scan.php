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
    </style>
</head>

<body>
    <h2>Presensi dengan QR</h2>
    <div class="conten" style="display:flex;justify-content:center;align-items:center;
    flex-direction: column;gap:9px;">
        <!-- Area Kamera -->
        <div id="reader" style="width: 200px; height: 150px;z-index:-1;"></div>
        <!-- Area Hasil Scan -->
        <div id="result">Nama : </div>
        <form action="" method="post">
            <input type="hidden" name="hasil" id="hiddenResult">
            <button type="submit">Submit</button>
        </form>

        <!-- PHP untuk menampilkan hasil dan dropdown -->
        <?php
        if (isset($_POST['hasil'])) {
            include "../db.php";
            $nama = htmlspecialchars($_POST['hasil']);

            // Query untuk mengambil data program dan level berdasarkan nama
            $ambil = mysqli_query($db, "SELECT program, `level` FROM siswa WHERE nama = '$nama'");

            // Debugging untuk melihat hasil query
            include "../db.php";

            $nama = htmlspecialchars($_POST['hasil']);

            // Query untuk mengambil data program dan level berdasarkan nama
            $ambil = mysqli_query($db, "SELECT program, level FROM siswa WHERE nama = '$nama'");

            // Debugging jika query gagal
            if (!$ambil) {
                die("Query Error: " . mysqli_error($db));
            }

            // Periksa apakah ada data
            if (mysqli_num_rows($ambil) > 0) {
                // Simpan data hasil query ke array
                $data = [];
                while ($row = mysqli_fetch_assoc($ambil)) {
                    $data[] = $row;
                }

                // Tampilkan form dengan dropdown
                echo "<div style='margin-top: 2px;'>
                        <h3>Hasil Presensi:</h3>
                        <p>Nama: $nama</p>
                        
                        <label for='dropdown1'>Program</label>
                        <select id='dropdown1' name='dropdown1'>";
                foreach ($data as $row) {
                    $program = htmlspecialchars($row['program']);
                    echo "<option value='$program'>$program</option>";
                }
                echo "</select>
                      <br>
                      
                      <label for='dropdown2'>Level</label>
                      <select id='dropdown2' name='dropdown2'>";
                foreach ($data as $row) {
                    $level = htmlspecialchars($row['level']);
                    echo "<option value='$level'>$level</option>";
                }
                echo "</select>
                      <br>
                      
                      <input type='submit' name='simpan' value='Simpan'>
                    </div>";
            } else {
                // Jika tidak ada data
                echo "<p>Data tidak ditemukan untuk nama: $nama</p>";
            }
        }
        ?>
    </div>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code Terbaca:", decodedText);
            document.getElementById("result").innerHTML = `Nama : ${decodedText}`;
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