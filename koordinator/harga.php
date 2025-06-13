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
        <h3>Harga Program</h3>
    </div>
    <div class="conten">
        <div class="section">
            <form action="#harga" method="post">
                <label for="category">Category:</label><br>
                <select name="category" id="category" required>
                    <option value="">-- Pilih Category Program --</option>
                    <option value="Computer">Computer</option>
                    <option value="Art">Art</option>
                    <option value="Robotik">Robotik</option>
                </select><br>

                <label for="harga">Harga (Rp):</label><br>
                <input type="number" name="harga" required><br><br>

                <input type="submit" class="submit-btn" value="Submit" name="submit_harga">
            </form>

            <?php
            include "../db.php";
            if (isset($_POST['submit_harga'])) {
                $q1 = mysqli_query($db, "SELECT count(id_harga) FROM harga");
                $cekid = mysqli_fetch_array($q1);
                $id = ($cekid[0] == 0) ? 1 : $cekid[0] + 1;

                $category = $_POST['category'];
                $harga = $_POST['harga'];

                $sql = mysqli_query($db, "INSERT INTO category (id_harga, category, harga) 
                                      VALUES ('$id', '$category', '$harga')");

                echo $sql ? "✅ Harga berhasil ditambahkan" : "❌ Gagal menyimpan data";
            }
            ?>
        </div>

        <div class="section">
            <h3>Sortir Harga</h3>
            <form method="get" action="#harga">
                <label for="sort_program">Pilih Category Program:</label>
                <select name="sort_program" id="sort_program">
                    <option value="">Semua Program</option>
                    <option value="Computer">Computer</option>
                    <option value="Art">Art</option>
                    <option value="Robotik">Robotik</option>
                </select>

                <label for="sort_level">Pilih Level:</label>
                <select name="sort_level" id="sort_level">
                    <option value="">Semua Level</option>
                    <option value="1">Level 1</option>
                    <option value="2">Level 2</option>
                    <option value="3">Level 3</option>
                    <option value="Registrasi">Registrasi</option>
                </select>

                <input type="submit" class="submit-btn" value="Sortir">
            </form>

            <?php
            $sort_program = isset($_GET['sort_program']) ? $_GET['sort_program'] : '';
            $sort_level = isset($_GET['sort_level']) ? $_GET['sort_level'] : '';
            $query = "SELECT * FROM category WHERE 1=1";
            if (!empty($sort_program))
                $query .= " AND program = '$sort_program'";
            if (!empty($sort_level))
                $query .= " AND level = '$sort_level'";
            $sql = mysqli_query($db, $query);
            ?>

            <div
                style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>category</th>
                        <th>Harga</th>
                        <th>aksi</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($sql)) { ?>
                        <tr>
                            <td><?php echo $row['id_harga']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td> <a href="edit_siswa.php?=<?php echo $row['username'] ?>"><button
                                        class="btn-edit">Edit</button></a>
                                <a href="hapus_siswa.php?=<?php echo $row['username'] ?>"><button
                                        class="btn-hapus">Hapus</button></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>