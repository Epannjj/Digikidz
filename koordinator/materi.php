<h3>Materi</h3>
<div class="conten" style="display:flex;flex-direction: row;">
    <div class="formpresensi">
        <form action="" method="post">
            <label for="nama">Nama Materi:</label><br>
            <input type="text" id="nama_materi" name="nama_materi" required><br>
            <label for="nama">program:</label><br>
            <input type="text" id="program" name="program" required><br>
            <label for="nama">level:</label><br>
            <input type="text" id="level" name="level" required><br>
            <label for="nama">pertemuan:</label><br>
            <input type="text" id="pertemuan" name="pertemuan" required><br>
            <label for="nama">modul:</label><br>
            <input type="file" id="modul" name="modul" required><br>
            <input type="submit" value="Submit" name="submit">
        </form>
    </div>
    <?php
    include "../db.php";
    if (isset($_POST['submit'])) {
        $q1 = mysqli_query($db, "SELECT count(id_materi) FROM materi MAX");
        if ($q1 == 0) {
            $id = 1;
        } else {
            $id = $q1 + 1;
        }
        $nama_materi = $_POST['nama_materi'];
        $program = $_POST['program'];
        $level = $_POST['level'];
        $pertemuan = $_POST['pertemuan'];
        $modul = $_FILES['modul']['name'];
        $lokasi_file = $_FILES['modul']['tmp_name'];
        move_uploaded_file($lokasi_file, "modul/$modul");
        $sql = mysqli_query($db, "INSERT INTO materi (id_materi, judul_materi, program, `level`, pertemuan, modul) VALUES ('$id', '$nama_materi', '$program', '$level', '$pertemuan', '$modul')");
        if ($sql) {
            echo "Materi berhasil ditambahkan";
        } else {
            echo "Materi gagal ditambahkan";
        }
    }
    ?>
    <div class="sort" style="display:flex;flex-direction: column;">
        <h3>Sortir Materi</h3>
        <form method="get" action="">
            <label for="sort_program">Pilih Program:</label>
            <select name="sort_program" id="sort_program">
                <option value="">Semua Program</option>
                <option value="Program B">Program B</option>
                <option value="Program A">Program A</option>
                <!-- Tambahkan opsi sesuai kebutuhan -->
            </select>

            <label for="sort_level">Pilih Level:</label>
            <select name="sort_level" id="sort_level">
                <option value="">Semua Level</option>
                <option value="Level 1">Level 1</option>
                <option value="Level 2">Level 2</option>
                <!-- Tambahkan opsi sesuai kebutuhan -->
            </select>

            <input type="submit" value="Sortir">
        </form>

        <?php
        // Filter input GET
        $sort_program = isset($_GET['sort_program']) ? $_GET['sort_program'] : '';
        $sort_level = isset($_GET['sort_level']) ? $_GET['sort_level'] : '';

        // Buat query dasar
        $query = "SELECT * FROM materi WHERE 1=1";

        // Tambahkan kondisi jika ada filter program
        if (!empty($sort_program)) {
            $query .= " AND program = '$sort_program'";
        }

        // Tambahkan kondisi jika ada filter level
        if (!empty($sort_level)) {
            $query .= " AND `level` = '$sort_level'";
        }

        // Jalankan query
        $sql = mysqli_query($db, $query);
        ?>
        <div
            style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
            <table border="1">
                <tr>
                    <th>ID Materi</th>
                    <th>Nama Materi</th>
                    <th>Program</th>
                    <th>Level</th>
                    <th>Pertemuan</th>
                    <th>Modul</th>
                </tr>
                <?php while ($row = mysqli_fetch_array($sql)) { ?>
                    <tr>
                        <td><?php echo $row['id_materi']; ?></td>
                        <td><?php echo $row['judul_materi']; ?></td>
                        <td><?php echo $row['program']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td><?php echo $row['pertemuan']; ?></td>
                        <td><a href="modul/<?php echo $row['modul']; ?>">Download</a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>