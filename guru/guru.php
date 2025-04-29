<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi QR</title>
    <script src="../qrcode/html5-qrcode.min.js"></script>
    <style>
        <?php include "../styles.css"; ?>
    </style>
</head>

<body>
    <?php
    session_start();
    $teacher = $_SESSION['user'];
    ?>
    <div class="sidebar-placeholder">
        <?php include "navbar-guru.php"; ?>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>Sistem Absensi QR</h2>
        </div>

        <div class="content">
            <div class="section">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PERTEMUAN</th>
                            <th>Materi</th>
                            <th>WAKTU</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-list">
                        <!-- Data presensi akan muncul di sini -->
                        <?php
                        if (isset($_POST['lihat'])) {
                            include "../db.php";
                            $nama = htmlspecialchars($_POST['lihat']);

                            // Cek data siswa
                            $cek_siswa = mysqli_query($db, "SELECT * FROM siswa WHERE nama = '$nama'");
                            if (mysqli_num_rows($cek_siswa) > 0) {
                                $data_siswa = mysqli_fetch_assoc($cek_siswa);

                                // Tampilkan riwayat presensi siswa (opsional)
                                $riwayat = mysqli_query($db, "SELECT * FROM hasil_presensi WHERE nama = '$nama' ORDER BY pertemuan DESC");
                                $no = 1;
                                echo "<h4> " . $nama . " </h4>";

                                if (mysqli_num_rows($riwayat) > 0) {
                                    while ($row = mysqli_fetch_assoc($riwayat)) {
                                        echo "<tr>";
                                        echo "<td>" . $no . "</td>";
                                        echo "<td>Ke " . $row['pertemuan'] . "</td>";
                                        echo "<td>" . $row['materi'] . "</td>";
                                        echo "<td>" . date('d-m-Y H:i', strtotime($row['tanggal'] . ' ')) . "</td>";
                                        echo '            <td>';

                                        // Cek apakah ada foto yang sudah tersimpan
                                        if (!empty($row['hasil_karya']) && file_exists("../uploads/" . $row['hasil_karya'])) {
                                            echo "<a href='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' target='_blank'>
                <img src='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' width='60' height='80' style='object-fit:cover; cursor:pointer;'>
              </a>";
                                        } else {
                                            // Jika belum ada foto, tampilkan tombol upload
                                            echo "<form action='../foto.php' method='post' target='_blank'>
                <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                <input type='submit' value='Foto' style='width:60px;height: 80px;'>
              </form>";
                                        }

                                        echo '</td>';
                                        echo "</tr>";
                                        $no++;

                                    }
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <div id="result" class="presensi-info">
                    <!-- Area Hasil Scan -->
                    <?php
                    if (isset($_POST['hasil'])) {
                        include "../db.php";
                        $nama = htmlspecialchars($_POST['hasil']);

                        // Cek apakah sudah presensi hari ini
                        $today = date('Y-m-d');
                        $cek_today = mysqli_query($db, "SELECT * FROM hasil_presensi 
                                   WHERE nama = '$nama' 
                                   AND tanggal = '$today'");
                        if (mysqli_num_rows($cek_today) > 0) {
                            echo "<div class='error'>";
                            echo "<h3>Siswa sudah melakukan presensi hari ini!</h3>";
                            echo "<form action='' method='post'>
                            <input type='hidden' name='lihat' value='$nama'>
                            <button type='submit' class='button'>Lihat Riwayat Presensi</button>
                        </form>
        ";

                            echo "</div>";
                        } else {
                            // Cek pertemuan terakhir
                            $cek_pertemuan = mysqli_query($db, "SELECT MAX(pertemuan) as last_pertemuan 
                                           FROM hasil_presensi 
                                           WHERE nama = '$nama'");
                            $data_pertemuan = mysqli_fetch_assoc($cek_pertemuan);
                            $pertemuan = ($data_pertemuan['last_pertemuan'] === null) ? 1 : $data_pertemuan['last_pertemuan'] + 1;
                            // ambil data nama program 
                            $ambil = mysqli_query($db, "SELECT program FROM siswa WHERE nama = '$nama'");
                            $data = mysqli_fetch_assoc($ambil);
                            $program = $data['program'];
                            if ($program) {
                                // Hasilkan Front ID
                                $frontId = substr($program, offset: 0, length: 2);
                                $tgl = date('dm');
                                //id siswa
                                // Hasilkan ID
                                $id = $frontId . $tgl . $pertemuan;
                            } else
                                echo 'Error';
                            // tgl
                            if ($pertemuan) {
                                $tanggal = date('Y-m-d');
                                $waktu = date('H:i:s');

                                // Simpan ke tabel hasil_presensi
                                $simpan = mysqli_query($db, "INSERT INTO hasil_presensi (id, nama, program,pertemuan, materi, tanggal, hasil_karya,teacher) ;
                                       VALUES ('$id','$nama','$program', '$pertemuan',' ', '$tanggal', ' ','$teacher')");

                                if ($simpan) {
                                    // Ambil data siswa
                                    $ambil = mysqli_query($db, "SELECT program FROM siswa WHERE nama = '$nama'");
                                    if (mysqli_num_rows($ambil) > 0) {
                                        $data = mysqli_fetch_assoc($ambil);
                                        echo "<div class='success'>";
                                        echo "<h3>Presensi Berhasil!</h3>";
                                        echo "<form action='' method='post'>
                    <input type='hidden' name='lihat' value='$nama'>
                    <button type='submit' class='button'>Lihat Riwayat Presensi</button>
                </form>
";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p class='error'>Gagal menyimpan presensi!</p>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="section" style="max-width: 300px;max-height: 250px;display: flex;justify-content: center;">
                <div class="scanner-container">
                    <div id="reader"></div>
                    <button id="stop-scanning" class="button">Stop Scanning</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let html5QrcodeScanner;

        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code Terbaca:", decodedText);
            document.getElementById("result").innerHTML = `
                <h3>Data Terdeteksi</h3>
                <p>Nama: ${decodedText}</p>
                <form action="" method="post">
                    <input type="hidden" name="hasil" value="${decodedText}">
                    <button type="submit" class="button">Konfirmasi Presensi</button>
                </form>
            `;

            // Hentikan pemindaian setelah berhasil membaca kode QR
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop();
            }
        }

        function onScanFailure(error) {
            // Tidak perlu menampilkan error di console
        }

        document.addEventListener("DOMContentLoaded", function () {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                html5QrcodeScanner = new Html5Qrcode("reader");

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

                // Tombol untuk menghentikan pemindaian
                document.getElementById("stop-scanning").addEventListener("click", function () {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.stop().then(() => {
                            console.log("Pemindaian dihentikan");
                        }).catch(err => {
                            console.error("Gagal menghentikan pemindaian:", err);
                        });
                    }
                });
            } else {
                document.getElementById("result").innerHTML = "Perangkat tidak mendukung akses kamera.";
            }
        });
    </script>
</body>

</html>