<h3>Input Data Siswa</h3>
<div class="formpresensi">
    <form action="#siswa" method="post">
        <label for="nama">Nama Siswa:</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="nama">Program</label><br>
        <select name="program" id="program">
            <?php
            // while data Program dari tabel program
            $sql = mysqli_query($db, "SELECT * FROM program");
            while ($row = mysqli_fetch_array($sql)) {
                echo "<option value='" . $row['PROGRAM'] . "'>" . $row['PROGRAM'] . "</option>";
            }
            ?>
        </select><br>
        <input type="submit" value="Simpan" name="simpan">
    </form>

    <?php
    include "../db.php";
    if (isset($_POST['simpan'])) {
        $nama = $_POST['nama'];
        $program = $_POST['program'];
        if ($program) {
            $filteredWord = preg_replace('/[^A-Z0-9]/', '', $program);
            // Hasilkan Front ID
            $frontId = substr($filteredWord, offset: 0, length: 2);
            $tgl = date('dm');
            //id siswa
            $cekjumlah = mysqli_query($db, "SELECT SUM(program) as jumlah 
                                           FROM siswa 
                                           WHERE program = '$program'");
            $jumlah = mysqli_fetch_assoc($cekjumlah);
            $endId = ($jumlah['jumlah'] === null) ? 1 : $jumlah['jumlah'] + 1;
            // Hasilkan ID
            function generateIdWithPadding($frontId, $jumlah)
            {
                return $frontId . sprintf("%03d", $jumlah);
            }
            $id = generateIdWithPadding($frontId, $endId);
        } else
            echo 'Error';

        //create password randomly
        $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $sql = mysqli_query($db, "INSERT INTO siswa (id_siswa, nama, program,`password`) VALUES ('$id', '$nama', '$program','$password')");
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
                <td>
                    <a href="edit_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Edit</a> |
                    <a href="hapus_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Hapus</a>
                </td>
                <td>
                    <form action="../qrcode/generate_qr.php" method="post" target="_blank">
                        <input type="hidden" name="nama" value="<?php echo $row['nama']; ?>">
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