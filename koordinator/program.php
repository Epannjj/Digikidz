<?php session_start(); ?>
<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">
    <?php
    include "sidebar2.php";
    include "../db.php";
    include "../notification.php";

    // EDIT PROGRAM
    if (isset($_POST['edit_program'])) {
        $program_lama = $_POST['program_lama'];
        $program_baru = $_POST['program_baru'];
        $kategori = $_POST['kategori'];
        $week_progress = $_POST['week_progress'];
        $jumlah_pertemuan = $_POST['jumlah_pertemuan'];
        $month_certificate = $_POST['month_certificate'];
        $harga = $_POST['harga'];

        $update = mysqli_query($db, "UPDATE program SET 
            PROGRAM='$program_baru', 
            category='$kategori',
            `MONTH OF PROGRESS REPORT`='$week_progress', 
            `JUMLAH PERTEMUAN (WEEK)`='$jumlah_pertemuan',
            `MONTH OF CERTIFICATED`='$month_certificate',
            harga='$harga'
            WHERE PROGRAM='$program_lama'");

        if ($update) {
            showNotification("Program berhasil diubah", "success");
        } else {
            showNotification("Gagal mengubah program: " . mysqli_error($db), "error");
        }
    }

    // HAPUS PROGRAM
    if (isset($_POST['hapus_program'])) {
        $program = $_POST['program_hapus'];
        $delete = mysqli_query($db, "DELETE FROM program WHERE PROGRAM='$program'");

        if ($delete) {
            showNotification("Program berhasil dihapus", "success");
        } else {
            showNotification("Gagal menghapus program: " . mysqli_error($db), "error");
        }
    }
    ?>
</div>

<div class="main-container">
    <div class="header">
        <h3>Manajemen Program</h3>
    </div>
    <div class="conten">
        <div class="section">
            <div class="formpresensi">
                <h4>Tambah Program Baru</h4>
                <form action="#program" method="post">
                    <label for="nama_program">Nama Program:</label><br>
                    <input type="text" id="nama_program" name="nama_program" required><br>

                    <label for="kategori">Kategori:</label><br>
                    <select name="kategori" id="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Coding">computer</option>
                        <option value="Art">Art</option>
                        <option value="Robotik">Robotik</option>
                    </select><br>

                    <label for="week_progress">Month of Progress Report:</label><br>
                    <input type="number" id="week_progress" name="week_progress" min="1" required><br>

                    <label for="jumlah_pertemuan">Jumlah Pertemuan (Week):</label><br>
                    <input type="number" id="jumlah_pertemuan" name="jumlah_pertemuan" min="1" required><br>

                    <label for="month_certificate">Month of Certificate:</label><br>
                    <input type="number" id="month_certificate" name="month_certificate" min="1" required><br>

                    <label for="harga">Harga:</label><br>
                    <input type="number" id="harga" name="harga" min="0" required><br>

                    <input type="submit" value="Tambah Program" class="submit-btn" name="submit_program">
                </form>

                <?php
                if (isset($_POST['submit_program'])) {
                    $nama_program = mysqli_real_escape_string($db, $_POST['nama_program']);
                    $kategori = mysqli_real_escape_string($db, $_POST['kategori']);
                    $week_progress = mysqli_real_escape_string($db, $_POST['week_progress']);
                    $jumlah_pertemuan = mysqli_real_escape_string($db, $_POST['jumlah_pertemuan']);
                    $month_certificate = mysqli_real_escape_string($db, $_POST['month_certificate']);
                    $harga = mysqli_real_escape_string($db, $_POST['harga']);

                    // Cek apakah program sudah ada
                    $cek_program = mysqli_query($db, "SELECT * FROM program WHERE PROGRAM='$nama_program'");

                    if (mysqli_num_rows($cek_program) > 0) {
                        showNotification("Program dengan nama '$nama_program' sudah ada!", "error");
                    } else {
                        // Generate ID program
                        $q1 = mysqli_query($db, "SELECT MAX(id_program) as max_id FROM program");
                        $cekid = mysqli_fetch_array($q1);
                        $id = ($cekid['max_id'] == null) ? 1 : $cekid['max_id'] + 1;

                        $sql = mysqli_query($db, "INSERT INTO program (id_program, PROGRAM, kategori, `MONTH OF PROGRESS REPORT`, `JUMLAH PERTEMUAN (WEEK)`, `MONTH OF CERTIFICATED`, harga) 
                                VALUES ('$id', '$nama_program', '$kategori', '$week_progress', '$jumlah_pertemuan', '$month_certificate', '$harga')");

                        if ($sql) {
                            showNotification("Program berhasil ditambahkan", "success");
                        } else {
                            showNotification("Program gagal ditambahkan: " . mysqli_error($db), "error");
                        }
                    }
                }
                ?>
            </div>
        </div>

        <div class="section">
            <div class="sort" style="display:flex;flex-direction: column;">
                <h3>Daftar Program</h3>
                <form method="get" action="#table-program">
                    <label for="sort_program">Filter berdasarkan Kategori:</label>
                    <select name="sort_kategori" id="sort_kategori">
                        <option value="">-- Semua Kategori --</option>
                        <option value="computer" <?php echo (isset($_GET['sort_kategori']) && $_GET['sort_kategori'] == 'computer ') ? 'selected' : ''; ?>>computer</option>
                        <option value="Art" <?php echo (isset($_GET['sort_kategori']) && $_GET['sort_kategori'] == 'Art') ? 'selected' : ''; ?>>Art</option>
                        <option value="Robotik" <?php echo (isset($_GET['sort_kategori']) && $_GET['sort_kategori'] == 'Robotik') ? 'selected' : ''; ?>>Robotik</option>
                    </select>
                    <input type="submit" class="submit-btn" value="Filter">
                </form>

                <?php
                $sort_kategori = isset($_GET['sort_kategori']) ? $_GET['sort_kategori'] : '';
                $query = "SELECT * FROM program WHERE 1=1";
                if (!empty($sort_kategori)) {
                    $query .= " AND category = '$sort_kategori'";
                }
                $query .= " ORDER BY PROGRAM ASC";
                $sql = mysqli_query($db, $query);
                ?>

                <div
                    style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                    <table border="1" id="table-program">
                        <tr>
                            <th>No</th>
                            <th>Nama Program</th>
                            <th>Kategori</th>
                            <th>Progress Report (Month)</th>
                            <th>Jumlah Pertemuan (Week)</th>
                            <th>Sertifikat (Month)</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row['PROGRAM']; ?></td>
                                <td><?php echo $row['category']; ?></td>
                                <td><?php echo $row['MONTH OF PROGRESS REPORT']; ?></td>
                                <td><?php echo $row['JUMLAH PERTEMUAN (WEEK)']; ?></td>
                                <td><?php echo $row['MONTH OF CERTIFICATED']; ?></td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <button class="btn-edit"
                                        onclick="showEditForm('<?php echo $row['PROGRAM']; ?>', '<?php echo $row['category']; ?>', '<?php echo $row['MONTH OF PROGRESS REPORT']; ?>', '<?php echo $row['JUMLAH PERTEMUAN (WEEK)']; ?>', '<?php echo $row['MONTH OF CERTIFICATED']; ?>', '<?php echo $row['harga']; ?>')">Edit</button>
                                    <button class="btn-hapus"
                                        onclick="showDeleteConfirm('<?php echo $row['PROGRAM']; ?>')">Hapus</button>
                                </td>
                            </tr>
                            <?php $no++;
                        } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popup untuk Edit Program -->
<div id="editOverlay" class="overlay">
    <div class="popup">
        <h2>Edit Program</h2>
        <form id="editForm" action="" method="POST">
            <input type="hidden" id="edit_program_lama" name="program_lama">

            <div class="form-group">
                <label for="edit_program">Nama Program:</label>
                <input type="text" id="edit_program" name="program_baru" required>
            </div>

            <div class="form-group">
                <label for="edit_kategori">Kategori:</label>
                <input type="text" name="kategori" id="edit_kategori" required>
            </div>

            <div class="form-group">
                <label for="edit_week_progress">Month of Progress Report:</label>
                <input type="number" id="edit_week_progress" name="week_progress" min="1" required>
            </div>

            <div class="form-group">
                <label for="edit_jumlah_pertemuan">Jumlah Pertemuan (Week):</label>
                <input type="number" id="edit_jumlah_pertemuan" name="jumlah_pertemuan" min="1" required>
            </div>

            <div class="form-group">
                <label for="edit_month_certificate">Month of Certificate:</label>
                <input type="number" id="edit_month_certificate" name="month_certificate" min="1" required>
            </div>

            <div class="form-group">
                <label for="edit_harga">Harga:</label>
                <input type="number" id="edit_harga" name="harga" min="0" required>
            </div>

            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideEditForm()">Batal</button>
                <button type="submit" name="edit_program" class="btn-confirm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Popup untuk Konfirmasi Hapus -->
<div id="deleteConfirmOverlay" class="overlay">
    <div class="popup">
        <h2>Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus program <strong><span id="deleteProgramName"></span></strong>?</p>
        <p class="warning-text">Menghapus program akan menghapus semua data terkait program ini!</p>
        <form id="deleteForm" action="" method="POST">
            <input type="hidden" id="delete_program" name="program_hapus">
            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideDeleteConfirm()">Batal</button>
                <button type="submit" name="hapus_program" class="btn-delete-confirm">Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showEditForm(program, category, weekProgress, jumlahPertemuan, monthCertificate, harga) {
        document.getElementById('edit_program_lama').value = program;
        document.getElementById('edit_program').value = program;
        document.getElementById('edit_kategori').value = category;
        document.getElementById('edit_week_progress').value = weekProgress;
        document.getElementById('edit_jumlah_pertemuan').value = jumlahPertemuan;
        document.getElementById('edit_month_certificate').value = monthCertificate;
        document.getElementById('edit_harga').value = harga;
        document.getElementById('editOverlay').style.display = 'flex';
    }

    function hideEditForm() {
        document.getElementById('editOverlay').style.display = 'none';
    }

    function showDeleteConfirm(program) {
        document.getElementById('deleteProgramName').innerText = program;
        document.getElementById('delete_program').value = program;
        document.getElementById('deleteConfirmOverlay').style.display = 'flex';
    }

    function hideDeleteConfirm() {
        document.getElementById('deleteConfirmOverlay').style.display = 'none';
    }

    // Close popups when clicking outside
    window.onclick = function (event) {
        if (event.target === document.getElementById('editOverlay')) {
            hideEditForm();
        }
        if (event.target === document.getElementById('deleteConfirmOverlay')) {
            hideDeleteConfirm();
        }
    }
</script>

<style>
    .warning-text {
        color: #dc3545;
        font-weight: bold;
        font-size: 14px;
        margin: 10px 0;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .popup-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .btn-cancel,
    .btn-confirm,
    .btn-delete-confirm {
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-cancel {
        background-color: #6c757d;
        color: white;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
    }

    .btn-confirm {
        background-color: #007bff;
        color: white;
    }

    .btn-confirm:hover {
        background-color: #0056b3;
    }

    .btn-delete-confirm {
        background-color: #dc3545;
        color: white;
    }

    .btn-delete-confirm:hover {
        background-color: #c82333;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .popup {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .popup h2 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
</style>