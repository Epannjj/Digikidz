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
        <h3>program</h3>
    </div>
    <div class="conten">
        <div class="section">
            <div class="formpresensi">
                <form action="#program" method="post" enctype="multipart/form-data">
                    <label for="nama">Nama program:</label><br>
                    <input type="text" id="nama_program" name="nama_program" required><br>
                    <label for="Kategori">Kategori</label><br>
                    <select name="kategori" id="kategori">
                        <option value="Coding">Coding</option>
                        <option value="Art">Art</option>
                        <option value="Robotik">Robotik</option>
                    </select>
                    <label for="week_opr">Week Of Progress Report:</label><br>
                    <input type="text" id="week_report" name="week_report" required><br>
                    <label for="week_opr">Week Of Progress Report:</label><br>
                    <input type="text" id="week_report" name="week_report" required><br>
                    <label for="week_opr">Week Of Progress Report:</label><br>
                    <input type="text" id="week_report" name="week_report" required><br>
                    <input type="submit" value="Submit" class="submit-btn" name="submit_program">
                </form>
                <?php
                include "../db.php";
                if (isset($_POST['submit_program'])) {
                    $q1 = mysqli_query($db, "SELECT count(id_program) FROM program MAX");
                    $cekid = mysqli_fetch_array($q1);
                    if ($cekid[0] == 0) {
                        $id = 1;
                    } else {
                        $id = $cekid[0] + 1;
                    }
                    $nama_program = $_POST['nama_program'];
                    $program = $_POST['program'];
                    $level = $_POST['level'];
                    $pertemuan = $_POST['pertemuan'];
                    $modul = time() . '_' . basename($_FILES["modul"]['name']);
                    $lokasi_file = $_FILES['modul']['tmp_name'];
                    move_uploaded_file($lokasi_file, "modul/$modul");
                    $sql = mysqli_query($db, "INSERT INTO program (id_program, judul_program, program, `level`, pertemuan, modul) VALUES ('$id', '$nama_program', '$program', '$level', '$pertemuan', '$modul')");
                    if ($sql) {
                        echo "program berhasil ditambahkan";
                    } else {
                        echo "program gagal ditambahkan";
                    }
                }
                ?>
            </div>
        </div>
        <div class="section">
            <div class="sort" style="display:flex;flex-direction: column;">
                <h3>Sortir program</h3>
                <form method="get" action="#program">
                    <label for="sort_program">Pilih Program:</label>
                    <select name="sort_program" id="sort_program">
                        <?php
                        $program_query = mysqli_query($db, "SELECT DISTINCT program FROM program ORDER BY program ASC");
                        while ($program_row = mysqli_fetch_assoc($program_query)) {
                            $selected = ($_GET['sort_program'] ?? '') == $program_row['program'] ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($program_row['program']) . "' $selected>" . htmlspecialchars($program_row['program']) . "</option>";
                        }
                        ?>
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
                $query = "SELECT * FROM program WHERE 1=1";
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
                            <th>No program</th>
                            <th>Nama program</th>
                            <th>Progress Report (month)</th>
                            <th>jumlah pertemuan (week)</th>
                            <th>Sertifikat (month)</th>
                            <th>Harga</th>
                        </tr>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row['PROGRAM']; ?></td>
                                <td><?php echo $row['MONTH OF PROGRESS REPORT']; ?></td>
                                <td><?php echo $row['JUMLAH PERTEMUAN (WEEK)']; ?></td>
                                <td><?php echo $row['MONTH OF CERTIFICATED']; ?></td>
                                <td><?php echo $row['harga']; ?></td>
                            </tr>
                        <?php }
                        $no++; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>