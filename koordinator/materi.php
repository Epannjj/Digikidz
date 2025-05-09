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
        <h3>Materi</h3>
    </div>
    <div class="conten">
        <div class="section">
            <div class="formpresensi">
                <form action="#materi" method="post" enctype="multipart/form-data">
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
                    <input type="submit" value="Submit" class="submit-btn" name="submit_materi">
                </form>
                <?php
                include "../db.php";
                if (isset($_POST['submit_materi'])) {
                    $q1 = mysqli_query($db, "SELECT count(id_materi) FROM materi MAX");
                    $cekid = mysqli_fetch_array($q1);
                    if ($cekid[0] == 0) {
                        $id = 1;
                    } else {
                        $id = $cekid[0] + 1;
                    }
                    $nama_materi = $_POST['nama_materi'];
                    $program = $_POST['program'];
                    $level = $_POST['level'];
                    $pertemuan = $_POST['pertemuan'];
                    $modul = time() . '_' . basename($_FILES["modul"]['name']);
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
            </div>
        </div>
        <div class="section">
            <div class="sort" style="display:flex;flex-direction: column;">
                <h3>Sortir Materi</h3>
                <form method="get" action="#materi">
                    <label for="sort_program">Pilih Program:</label>
                    <select name="sort_program" id="sort_program">
                        <option value="">Semua Program</option>
                        <option value="Coding">Coding</option>
                        <option value="Art">Art</option>
                        <option value="Robotik">Robotik</option>
                    </select>

                    <label for="sort_level">Pilih Level:</label>
                    <select name="sort_level" id="sort_level">
                        <option value="">Semua Level</option>
                        <option value="Level 1">Level 1</option>
                        <option value="Level 2">Level 2</option>
                    </select>

                    <input type="submit" class="submit-btn" value="Sortir">
                </form>

                <?php
                $sort_program = isset($_GET['sort_program']) ? $_GET['sort_program'] : '';
                $sort_level = isset($_GET['sort_level']) ? $_GET['sort_level'] : '';
                $query = "SELECT * FROM materi WHERE 1=1";
                if (!empty($sort_program)) {
                    $query .= " AND program = '$sort_program'";
                }
                if (!empty($sort_level)) {
                    $query .= " AND `level` = '$sort_level'";
                }
                $sql = mysqli_query($db, $query);
                ?>
                <div
                    style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                    <table border="1">
                        <tr>
                            <th>No Materi</th>
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
    </div>
</div>