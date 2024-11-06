<h3>Input Data Siswa</h3>
<div class="formpresensi">
    <form action="" method="post">
        <label for="nama">Nama Siswa:</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="nama">Program</label><br>
        <select name="program" id="program">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
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
    use Picqer\Barcode\BarcodeGeneratorHTML;

    if (isset($_POST['simpan'])) {
        $nama = $_POST['nama'];
        $program = $_POST['program'];
        $level = $_POST['level'];
        $cekid = mysqli_query($db, "SELECT MAX(id_siswa) FROM siswa");
        $ambilid = mysqli_fetch_array($cekid)[0];
        $id = $ambilid + 1;
        $sql = mysqli_query($db, "INSERT INTO siswa (id_siswa, nama, program, `level`) VALUES ('$id', '$nama', '$program', '$level')");
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
            <th>Program</th>
            <th>Level</th>
            <th>Barcode</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data = mysqli_query($db, "SELECT * FROM siswa");
        while ($row = mysqli_fetch_array($data)) {
            // Generate barcode
            $generator = new BarcodeGeneratorHTML();
            $barcode = $generator->getBarcode($row['nama'], $generator::TYPE_CODE_128);
            ?>
            <tr>
                <td><?php echo $row['id_siswa'] ?></td>
                <td><?php echo $row['nama'] ?></td>
                <td><?php echo $row['program'] ?></td>
                <td><?php echo $row['level'] ?></td>
                <td><?php echo $barcode ?></td>
                <td>
                    <a href="edit_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Edit</a> |
                    <a href="hapus_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>