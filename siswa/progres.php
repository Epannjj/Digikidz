<?php include "../db.php";
if (isset($_SESSION['nama'])) {
    $nama = $_SESSION['nama'];
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM ambilprogram WHERE id_siswa = '$id'";
    $result = mysqli_query($db, $sql);
    $data = mysqli_fetch_array($result);
    if (!$data) {
        echo "<p>Anda belum mengambil program.</p>";
    } else {
        echo "<p>Program: " . $data['program'] . "</p>";
        echo "<h2>Progres Kelas</h2>";
        $sql_progres = "SELECT count(`pertemuan`) as progres FROM hasil_presensi WHERE nama = '$nama'";
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
        if ($progres['progres'] == 16) {
            echo "<input type='button' value='Progres Report' class='btn-selesai'onclick=\"location.href='progres_report.php'\">";
        } else {
            echo "<input type='button' value='Progres Report' class='btn-belumelesai' disabled>";
        }
    }
} else {
    echo "<p>Anda belum login.</p>";
}
?>