<style>
    <?php
    session_start();
    include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">

    <?php
    include "sidebar2.php";

    include "../db.php" ?>

</div>
<div class="main-container">
    <div class="header">
        <h3>Harga Registrasi</h3>
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
            <div
                style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>category</th>
                        <th>Harga</th>
                        <th>aksi</th>
                    </tr>
                    <?php
                    $sql = mysqli_query($db, "SELECT * FROM category ORDER BY id_harga DESC");
                    while ($row = mysqli_fetch_array($sql)) { ?>
                        <tr>
                            <td><?php echo $row['id_harga']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td> <a href="edit_siswa.php?=<?php echo $row['id_harga'] ?>"><button
                                        class="btn-edit">Edit</button></a>
                                <a href="hapus_siswa.php?=<?php echo $row['id_harga'] ?>"><button
                                        class="btn-hapus">Hapus</button></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>