<h3>Input Data Siswa</h3>
<div class="formpresensi">
    <form action="#siswa" method="post">
        <label for="nama">Nama Siswa:</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="nama">Program</label><br>
        <select name="program" id="program">
            <option value="Coding">Coding</option>
            <option value="Art">Art</option>
            <option value="Robotik">Robotik</option>
        </select><br>
        <label for="nama">Level</label><br>
        <select name="level" id="level">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select><br>
        <input type="submit" value="Simpan" name="simpan">
    </form>

    <?php
    include "../db.php";
    if (isset($_POST['simpan'])) {
        $nama = $_POST['nama'];
        $program = $_POST['program'];
        $level = $_POST['level'];
        $cekid = mysqli_query($db, "SELECT MAX(id_siswa) FROM siswa");
        $ambilid = mysqli_fetch_array($cekid)[0];
        $id = $ambilid + 1;
        //create password randomly
        $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $sql = mysqli_query($db, "INSERT INTO siswa (id_siswa, nama, program, `level`,`password`) VALUES ('$id', '$nama', '$program', '$level','$password')");
        if ($sql) {
            echo "Data siswa berhasil ditambahkan";
        } else {
            echo "Data gagal ditambahkan";
        }
    }
    ?>
</div>
<div>
    <h3>Data Siswa</h3>
    <table border="1">
        <tr>
            <th>ID Siswa</th>
            <th>Nama Siswa</th>
            <th>Password</th>
            <th>Program</th>
            <th>Level</th>
            <th>Aksi</th>
            <th>QR Code</th>
        </tr>
        <?php

        include "../pagination.php";      
        $result_total = mysqli_query($db, "SELECT COUNT(*) AS total FROM siswa");
        $total_data = mysqli_fetch_assoc($result_total)['total'];
        $total_pages = ceil($total_data / $limit);           
        $data = mysqli_query($db, "SELECT * FROM siswa LIMIT $start, $limit");
        while ($row = mysqli_fetch_array($data)) { ?>
            <tr>
                <td><?php echo $row['id_siswa'] ?></td>
                <td><?php echo $row['nama'] ?></td>
                <td><?php echo $row['password'] ?></td>
                <td><?php echo $row['program'] ?></td>
                <td><?php echo $row['level'] ?></td>
                <td>
                    <a href="edit_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Edit</a> |
                    <a href="hapus_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Hapus</a>
                </td>
                <td>
                    <form action="../qrcode/generate_qr.php" method="post" target="_blank">
                        <input type="hidden" name="nama" value="<?php echo $row['nama']; ?>">
                        <input type="hidden" name="level" value="<?php echo $row['level']; ?>">
                        <input type="hidden" name="program" value="<?php echo $row['program']; ?>">
                        <input type="hidden" name="password" value="<?php echo $row['password']; ?>">
                        <input type="submit" value="Generate QR">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php include "../pagination2.html"; ?>   
</div>