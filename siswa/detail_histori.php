<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>History</title>
    <style>
        <?php include "siswa.css"; ?>
    </style>

</head>

<body>
    <?php
    include "sidebar.php";
    ?>
    <main>
        <table class="tabel" style="background-color: #fff; border-radius: 10px;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pertemuan</th>
                    <th>Materi</th>
                    <th>teacher</th>
                    <th>hasil karya</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "../db.php";
                $nama = $_SESSION['nama'];
                $query = mysqli_query($db, "SELECT * FROM hasil_presensi WHERE nama = '$nama'");
                if (!$query) {
                    die("Query Error: " . mysqli_error($db));
                }
                if (mysqli_num_rows($query) == 0) {
                    echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                }
                while ($row = mysqli_fetch_array($query)) {
                    echo "<tr>";
                    echo "<td>" . $row['tanggal'] . "</td>";
                    echo "<td>" . $row['pertemuan'] . "</td>";
                    echo "<td>" . $row['materi'] . "</td>";
                    echo "<td>" . $row['teacher'] . "</td>";
                    echo "<td><a href='hasil_karya/" . $row['hasil_karya'] . "'>Download</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>

</html>