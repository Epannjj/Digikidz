<h3>Harga Program</h3>
<div class="conten" style="display:flex;flex-direction: row;">
    <div class="formpresensi">
        <form action="#harga" method="post">
            <label for="program">Program:</label><br>
            <select name="program" id="program" required>
                <option value="">-- Pilih Program --</option>
                <option value="Computer">Computer</option>
                <option value="Art">Art</option>
                <option value="Robotik">Robotik</option>
            </select><br>

            <label for="level">Level:</label><br>
            <select name="level" id="level" required>
                <option value="">-- Pilih Level --</option>
                <option value="Level 1">Level 1</option>
                <option value="Level 2">Level 2</option>
                <option value="Registrasi">Registrasi</option>
            </select><br>

            <label for="harga">Harga (Rp):</label><br>
            <input type="number" name="harga" required><br><br>

            <input type="submit" value="Submit" name="submit_harga">
        </form>

        <?php
        include "../db.php";
        if (isset($_POST['submit_harga'])) {
            $q1 = mysqli_query($db, "SELECT count(id_harga) FROM harga_program");
            $cekid = mysqli_fetch_array($q1);
            $id = ($cekid[0] == 0) ? 1 : $cekid[0] + 1;

            $program = $_POST['program'];
            $level = $_POST['level'];
            $harga = $_POST['harga'];

            $sql = mysqli_query($db, "INSERT INTO harga (id_harga, program, level, harga) 
                                      VALUES ('$id', '$program', '$level', '$harga')");

            echo $sql ? "✅ Harga berhasil ditambahkan" : "❌ Gagal menyimpan data";
        }
        ?>
    </div>

    <div class="sort" style="display:flex;flex-direction: column;">
        <h3>Sortir Harga</h3>
        <form method="get" action="#harga">
            <label for="sort_program">Pilih Program:</label>
            <select name="sort_program" id="sort_program">
                <option value="">Semua Program</option>
                <option value="Computer">Computer</option>
                <option value="Art">Art</option>
                <option value="Robotik">Robotik</option>
            </select>

            <label for="sort_level">Pilih Level:</label>
            <select name="sort_level" id="sort_level">
                <option value="">Semua Level</option>
                <option value="Level 1">Level 1</option>
                <option value="Level 2">Level 2</option>
                <option value="Registrasi">Registrasi</option>
            </select>

            <input type="submit" value="Sortir">
        </form>

        <?php
        $sort_program = isset($_GET['sort_program']) ? $_GET['sort_program'] : '';
        $sort_level = isset($_GET['sort_level']) ? $_GET['sort_level'] : '';
        $query = "SELECT * FROM harga WHERE 1=1";
        if (!empty($sort_program)) $query .= " AND program = '$sort_program'";
        if (!empty($sort_level)) $query .= " AND level = '$sort_level'";
        $sql = mysqli_query($db, $query);
        ?>

        <div style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
            <table border="1">
                <tr>
                    <th>No</th>
                    <th>Program</th>
                    <th>Level</th>
                    <th>Harga</th>
                </tr>
                <?php while ($row = mysqli_fetch_array($sql)) { ?>
                    <tr>
                        <td><?php echo $row['id_harga']; ?></td>
                        <td><?php echo $row['program']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
