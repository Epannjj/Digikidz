<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">

    <?php
    session_start();
    include "sidebar2.php";

    include "../db.php" ?>

</div>
<div class="main-container">
    <div class="header">
        <h4>Tambah jadwal</h4>
    </div>
    <div class="conten">
        <div class="section">
            <div class="form">
                <form action="#jadwal" method="post">
                    <label for="nama_siswa">Nama Siswa:</label><br>
                    <input type="text" id="nama_siswa" name="nama_siswa" required><br>
                    <label for="nama_guru">Guru :</label><br>
                    <input type="text" id="nama_guru" name="nama_guru" required><br>
                    <label for="program">Program :</label><br>
                    <input type="text" id="program" name="program" required><br>
                    <label for="level">level :</label><br>
                    <select name="level" id="level">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select><br>
                    <label for="pertemuan">pertemuan :</label><br>
                    <input type="text" id="pertemuan" name="pertemuan" required><br>
                    <label for="tgl_jam">Tanggal dan Jam :</label><br>
                    <input type="date" name="tanggal" id="tanggal">
                    <input type="time" id="tgl_jam" name="tgl_jam" required><br>
                    <input type="submit" class="submit-btn" value="Tambah Jadwal" name="submit_jadwal">
                </form>
            </div>
            <?php
            include '../db.php';

            if (isset($_POST['submit_jadwal'])) {
                $nama_siswa = $_POST['nama_siswa'];
                $nama_guru = $_POST['nama_guru'];
                $program = $_POST['program'];
                $level = $_POST['level'];
                $pertemuan = $_POST['pertemuan'];
                $tanggal = $_POST['tanggal'];
                $tgl_jam = $_POST['tgl_jam'];
                $tgl = $tanggal . $tgl_jam;
                $q1 = mysqli_query($db, "SELECT count(id) from jadwal MAX");
                $max_id = mysqli_fetch_array($q1)[0];
                if ($max_id == 0) {
                    $id = 1;
                } else {
                    $id = $max_id + 1;
                }
                $query = "INSERT INTO jadwal (id, nama_siswa, program, `level`,pertemuan, teacher, tanggal) VALUES ('$id','$nama_siswa', '$program', '$level', '$pertemuan' ,'$nama_guru','$tgl')";
                $result = mysqli_query($db, $query);
                if ($result) {
                    echo "Jadwal berhasil ditambahkan";
                } else {
                    echo "Jadwal gagal ditambahkan";
                }
            }
            ?>

            <div class="tabel" style="display:flex;flex-direction: column;">
                <h4>Jadwal</h4>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Nama Siswa</th>
                        <th>Program</th>
                        <th>Level</th>
                        <th>Pertemuan</th>
                        <th>Materi</th>
                        <th>Tanggal</th>
                        <th>Guru</th>
                        <th>aksi</th>
                    </tr>
                    <?php
                    $query = mysqli_query($db, "SELECT * ,materi.judul_materi as judul FROM jadwal ,materi WHERE jadwal.program = materi.program AND jadwal.level = materi.level AND jadwal.pertemuan = materi.pertemuan");
                    $id = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td>" . $id . "</td>";
                        echo "<td>" . $row['nama_siswa'] . "</td>";
                        echo "<td>" . $row['program'] . "</td>";
                        echo "<td>" . $row['level'] . "</td>";
                        echo "<td>" . $row['pertemuan'] . "</td>";
                        echo "<td>" . $row['judul'] . "</td>";
                        echo "<td>" . $row['tanggal'] . "</td>";
                        echo "<td>" . $row['teacher'] . "</td>"; ?>
                        <td> <a href="edit_siswa.php?=<?php echo $row['id'] ?>"><button class="btn-edit">Edit</button></a>
                            <a href="hapus_siswa.php?=<?php echo $row['id'] ?>"><button class="btn-hapus">Hapus</button></a>
                        </td>
                        <?php
                        echo "</tr>";
                        $id++;
                    }
                    ?>
            </div>
        </div>
    </div>
</div>