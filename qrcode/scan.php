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
                    echo "<div style='margin-top: 2px;'>
                    <form action='' method='post'>
                        <h3>Hasil Presensi:</h3>
                        <p>Nama: $nama</p>
                        <input type='hidden' name='nama' id='hiddenResult' value='$nama'>
                        <label for='program'>Program</label>
                        <select id='program' name='program'>";
                    foreach ($data as $row) {
                        $program = htmlspecialchars($row['program']);
                        echo "<option value='$program'>$program</option>";
                    }
                    echo "</select>
                      <br>
                      
                      <label for='level'>Level</label>
                      <select id='level' name='level'>";
                    foreach ($data as $row) {
                        $level = htmlspecialchars($row['level']);
                        echo "<option value='$level'>$level</option>";
                    }
                    echo "</select>
                      <br>
                      
                      <input type='submit' name='simpan' value='Simpan'>
                      
                    </div>
                    </form>";
                } else {
                    // Jika tidak ada data
                    echo "<p>Data tidak ditemukan untuk nama: $nama</p>";
                }
            }
            ?>
        </div>
    </div>
    <?php
    include "../db.php";
    if (isset($_POST['simpan'])) {
        $nama1 = $_POST['nama'];
        $program1 = $_POST['program'];
        $level1 = $_POST['level'];
        $q = mysqli_query($db, "SELECT count(nama) as pertemuanke FROM hasil_presensi where nama = '$nama1' AND program='$program1' ");
        $cek = mysqli_fetch_array($q);
        $cekid = $cek['pertemuanke'];
        $pertemuan = $cekid + 1;
        $q1 = mysqli_query($db, "SELECT judul_materi FROM materi WHERE pertemuan = '$pertemuan' AND program = '$program1'");
        $cekmateri = mysqli_fetch_array($q1);
        if ($cekmateri) {
            $materi = $cekmateri['judul_materi'];
        } else {
            $materi = "materi tidak ditemukan";
        }
        $tgl = new DateTime;
        $tanggal = date('d-m-Y');
        $q2 = mysqli_query($db, "SELECT count(id) FROM hasil_presensi MAX ");
        $cekid = mysqli_fetch_array($q2)[0];
        if ($cekid == 0) {
            $id = 1;
        } else {
            $id = $cekid + 1;
        }
        $sql = mysqli_query($db, "INSERT INTO hasil_presensi (id ,nama, program, `level`, materi, pertemuan, tanggal) VALUES ('$id', '$nama1', '$program1', '$level1', '$materi', '$pertemuan', '$tanggal')");
        if ($sql) {
            echo "Data presensi berhasil ditambahkan<br>
            <div>
                <h3>Hasil presensi </h3>
                <div class='table-wrapper'>
                    <table border='1'>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Program & Level</th>
                            <th>Materi</th>
                            <th>Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Hasil Karya</th>
                        </tr>";

            $data = mysqli_query($db, "SELECT * FROM hasil_presensi WHERE nama = '$nama1' AND program = '$program1'");
            $no = 1; // Tambahkan nomor urut
            while ($row = mysqli_fetch_array($data)) {
                echo "
                    <tr>
                        <td>{$no}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['program']} - {$row['level']}</td>
                        <td>{$row['materi']}</td>
                        <td>{$row['pertemuan']}</td>
                        <td>{$row['tanggal']}</td>
                        <td><input type='button' value='Foto' style='width:60px;height: 80px;'></td>
                    </tr>";
                $no++;
            }

            echo "
                    </table>
                </div>
            </div>";
        } else {
            echo "Data presensi gagal ditambahkan";
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