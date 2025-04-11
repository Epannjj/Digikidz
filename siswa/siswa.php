<!DOCTYPE html>
<html>

<head>
    <title>Siswa</title>
    <link rel="stylesheet" href="siswa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="header">
        <div class="kiri" style="font-size: 10px;">
            <?php include "../db.php";
            session_start();
            if (isset($_SESSION['nama'])) {
                echo "<h1>Selamat datang, " . $_SESSION['nama'] . "</h1>";
            }
            ?>
        </div>
        <div class="kanan">
            <a href='ubah_password.php' title="Ubah Password">
                <i class="fas fa-key"></i>ubah password
            </a>
            <br><a href='../logout.php'>Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="menu">
            <div class="progres">
                <?php include "../db.php";
                if (isset($_SESSION['nama'])) {
                    $nama = $_SESSION['nama'];
                    $sql = "SELECT * FROM siswa WHERE nama = '$nama'";
                    $result = mysqli_query($db, $sql);
                    $data = mysqli_fetch_assoc($result);
                    echo "<p>Program: " . $data['program'] . "</p>";
                    echo "<h2>Progres Kelas</h2>";
                    $sql_progres = "SELECT MAX(`pertemuan`) as progres FROM hasil_presensi WHERE nama = '$nama'";
                    $result_progres = mysqli_query($db, $sql_progres);
                    $progres = mysqli_fetch_assoc($result_progres);
                    if ($progres['progres'] === null) {
                        echo "<p>Belum ada pertemuan</p>";
                    } else {
                        $persen = ($progres['progres'] / 16) * 100;
                        echo "<div class='progress-bar'>
                        <div class='progress-bar-fill' style='width: " . $persen . "%;'></div>
                      </div>";
                        echo "<p class='progress-text'>" . round($persen, 2) . "%</p>";
                    }
                } else {
                    echo "<p>Anda belum login.</p>";
                }
                ?>
            </div>
            <div class="pembayaran">
                <?php
                if (isset($_SESSION['nama'])) {
                    $sql = "SELECT * FROM siswa WHERE nama = '$nama'";
                    $result = mysqli_query($db, $sql);
                    $data = mysqli_fetch_assoc($result);
                    echo "<h2>Pembayaran</h2>";
                    echo "<p>Status: " . $data['status'] . "</p>";
                    echo "<p>Jumlah Pembayaran: " . $data['jumlah_pembayaran'] . "</p>";
                } else {
                    echo "<p>Anda belum login.</p>";
                }
                ?>
            </div>
        </div>
        <div class="histori">

        </div>
    </div>
</body>

</html>