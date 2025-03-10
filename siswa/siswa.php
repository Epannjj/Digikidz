<!DOCTYPE html>
<html>

<head>
    <title>Siswa</title>
</head>

<body>
    <?php include "../db.php";
    session_start();
    if (isset($_SESSION['nama'])) {
        echo "Selamat datang, " . $_SESSION['nama'];
        $nama = $_SESSION['nama'];
        $sql = "SELECT * FROM siswa WHERE nama = '$nama'";
        $result = mysqli_query($db, $sql);
        $data = mysqli_fetch_assoc($result);
        echo "<br>Program: " . $data['program'];
        echo "<br>Level: " . $data['level'];
        "<h1>Progres kelas</h1>";
        $sql_progres = "SELECT MAx(`pertemuan`) as progres FROM hasil_presensi where nama = '$nama'";
        $result_progres = mysqli_query($db, $sql_progres);
        $progres = mysqli_fetch_assoc($result_progres);
        if ($progres === " ") {
            echo "belom ada pertemuan";
        } else {
            echo "<br>Progres: " . $progres['progres'] . " pertemuan";
            $persen = ($progres['progres'] / 16) * 100;
            echo "<br>Persentase: " . round($persen, 2) . "%";
        }
        echo "<br><a href='ubah_password.php'>Ubah Password</a>";
        echo "<br><a href='../logout.php'>Logout</a>";


    } else {
        echo "Anda belum login.";
    }
    ?>
</body>

</html>