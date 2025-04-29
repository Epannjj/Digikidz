<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        <?php include "../styles.css"; ?>
    </style>
</head>
<?php
session_start();
?>

<body>
    <div class="sidebar-placeholder">
        <?php include "navbar-guru.php";
        include "../db.php" ?>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>Jadwal</h2>
        </div>

        <div class="content">
            <div class="section">

                <thead>
                    <table border="1">
                        <tr>
                            <th>ID</th>
                            <th>Nama Siswa</th>
                            <th>Program</th>
                            <th>Level</th>
                            <th>Materi</th>
                            <th>Tanggal</th>
                            <th>Guru</th>
                        </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($db, "SELECT * ,materi.judul_materi as judul FROM jadwal ,materi WHERE jadwal.program = materi.program AND jadwal.level = materi.level AND jadwal.pertemuan = materi.pertemuan");
                    $id = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>" . $id . "</td>";
                        echo "<td>" . $row['nama_siswa'] . "</td>";
                        echo "<td>" . $row['program'] . "</td>";
                        echo "<td>" . $row['level'] . "</td>";
                        echo "<td>" . $row['judul'] . "</td>";
                        echo "<td>" . $row['tanggal'] . "</td>";
                        echo "<td>" . $row['teacher'] . "</td>";
                        echo "</tr>";
                        $id++;
                    }
                    ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</body>

</html>