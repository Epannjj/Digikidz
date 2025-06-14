<?php session_start(); ?>
<style>
    <?php
    include "../styles.css";
    include "../edit.css";
    ?>
</style>

<div class="sidebar-placeholder">
    <?php
    include "sidebar2.php";
    include "../db.php";
    include "../notification.php";

    // EDIT siswa
    if (isset($_POST['edit'])) {
        $id_siswa = $_POST['id_siswa'];
        $nama = $_POST['nama'];
        $password = $_POST['password'];

        $update = mysqli_query($db, "UPDATE siswa SET nama='$nama', password='$password' WHERE id_siswa='$id_siswa'");
        if ($update) {
            showNotification("Data siswa berhasil diubah", "success");
        } else {
            showNotification("Gagal mengubah data siswa: " . mysqli_error($db), "error");
        }
    }

    // HAPUS siswa
    if (isset($_POST['hapus'])) {
        $id_siswa = $_POST['id_siswa'];
        $program = $_POST['program'];
        $delete = mysqli_query($db, "DELETE FROM ambilprogram WHERE id_siswa='$id_siswa' AND program='$program'");
        if ($delete) {
            showNotification("Data siswa berhasil dihapus", "success");
        } else {
            showNotification("Gagal menghapus data siswa: " . mysqli_error($db), "error");
        }
    }

    // SIMPAN dengan program alternatif (untuk nama duplikat)
    if (isset($_POST['simpan_alternatif'])) {
        $nama = mysqli_real_escape_string($db, $_POST['nama_alternatif']);
        $program = mysqli_real_escape_string($db, $_POST['program_alternatif']);
        $tanggal = date("Y-m-d");
        // Ambil ID siswa yang sudah ada berdasarkan nama
        $ambil_id_siswa = mysqli_query($db, "SELECT id_siswa FROM siswa WHERE nama = '$nama'");
        $data_siswa = mysqli_fetch_array($ambil_id_siswa);

        if ($data_siswa) {
            $id_siswa_existing = $data_siswa['id_siswa'];

            // Cek apakah kombinasi siswa dan program sudah ada
            $cek_program = mysqli_query($db, "SELECT * FROM ambilprogram WHERE id_siswa = '$id_siswa_existing' AND program = '$program'");

            if (mysqli_num_rows($cek_program) > 0) {
                showNotification("Siswa sudah terdaftar di program ini!", "error");
            } else {
                // Ambil tagihan dari tabel program
                $sqltagihan = mysqli_query($db, "SELECT harga FROM program WHERE program = '$program'");
                $ctagihan = mysqli_fetch_array($sqltagihan);

                if ($ctagihan) {
                    $tagihan = $ctagihan['harga'];
                } else {
                    showNotification("Program tidak ditemukan di tabel program!", "error");
                    $tagihan = 0;
                }

                // Generate ID untuk ambilprogram
                $idambil = 'p' . $id_siswa_existing . '_' . time(); // Menambahkan timestamp untuk uniqueness
    
                // Simpan ke ambilprogram saja (tidak ke tabel siswa)
                $sql2 = mysqli_query($db, "INSERT INTO ambilprogram (id_ambil, id_siswa, program, tagihan, tanggal) VALUES ('$idambil','$id_siswa_existing', '$program', '$tagihan', '$tanggal')");

                if ($sql2) {
                    showNotification("Program berhasil ditambahkan untuk siswa $nama", "success");
                } else {
                    showNotification("Gagal menambahkan program: " . mysqli_error($db), "error");
                }
            }
        } else {
            showNotification("Data siswa tidak ditemukan!", "error");
        }
    }
    ?>
</div>

<div class="main-container">
    <div class="header">
        <h3>Input Data Siswa</h3>
    </div>
    <div class="conten">
        <div class="section">

            <div class="formpresensi">
                <form action="#siswa" method="post">
                    <label for="nama">Nama Siswa:</label><br>
                    <input type="text" id="nama" name="nama" required><br>

                    <label for="program">Program</label><br>
                    <select name="program" id="program">
                        <?php
                        $sql = mysqli_query($db, "SELECT * FROM program");
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<option value='" . $row['PROGRAM'] . "'>" . $row['PROGRAM'] . "</option>";
                        }
                        ?>
                    </select><br>
                    <label for="tanggal">Tanggal daftar</label>
                    <input type="date" id="tanggal" name="tanggal" required>

                    <input type="submit" value="Simpan" class="submit-btn" name="simpan">
                </form>

                <?php
                if (isset($_POST['simpan'])) {
                    $nama = mysqli_real_escape_string($db, $_POST['nama']);
                    $program = mysqli_real_escape_string($db, $_POST['program']);
                    $tanggal = date("Y-m-d");

                    // Cek nama duplikat dan program yang sudah diambil
                    $ceknama = mysqli_query($db, "SELECT siswa.id_siswa, siswa.nama, ambilprogram.program
                                                FROM siswa 
                                                JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa 
                                                WHERE siswa.nama='$nama'");

                    if ($row_duplikat = mysqli_fetch_array($ceknama)) {
                        // Jika nama sudah ada, cek apakah program yang dipilih sama
                        $program_sudah_ada = false;

                        // Reset pointer untuk mengecek semua program yang sudah diambil
                        mysqli_data_seek($ceknama, 0);
                        while ($check_program = mysqli_fetch_array($ceknama)) {
                            if ($check_program['program'] == $program) {
                                $program_sudah_ada = true;
                                break;
                            }
                        }

                        if ($program_sudah_ada) {
                            showNotification("Siswa $nama sudah terdaftar di program $program!", "error");
                        } else {
                            // Nama sama tapi program beda, tampilkan popup untuk menambah program baru
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showDuplicateForm('$nama', '$program');
                                });
                            </script>";
                        }
                    } else {
                        // Nama belum ada, lanjutkan proses normal
                        // Buat ID Siswa
                        $filteredWord = preg_replace('/[^A-Z0-9]/', '', $program);
                        $frontId = substr($filteredWord, 0, 2);

                        // Hitung jumlah siswa dalam program tsb
                        $cekjumlah = mysqli_query($db, "SELECT COUNT(id_siswa) as jumlah FROM siswa");
                        $jumlah = mysqli_fetch_assoc($cekjumlah);
                        $endId = $jumlah['jumlah'] + 1;

                        $id = $frontId . sprintf("%03d", $endId);

                        // Password acak
                        $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

                        // Simpan ke siswa
                        $sql = mysqli_query($db, "INSERT INTO siswa (id_siswa, nama, `password`) VALUES ('$id', '$nama', '$password')");
                        if ($sql) {
                            // Ambil tagihan dari tabel program
                            $idambil = 'p' . $id;
                            $sqltagihan = mysqli_query($db, "SELECT harga, `MONTH OF CERTIFICATED` FROM program WHERE program = '$program'");
                            $ctagihan = mysqli_fetch_array($sqltagihan);
                            if ($ctagihan) {
                                $tagihan = $ctagihan['harga'];
                                $brpbulan = $ctagihan['MONTH OF CERTIFICATED'];
                            } else {
                                echo "<script>alert('Program tidak ditemukan di tabel program!');</script>";
                                $tagihan = 0;
                            }
                            $totaltagihan = $tagihan * $brpbulan;
                            // Simpan ke ambilprogram
                            $sql2 = mysqli_query($db, "INSERT INTO ambilprogram (id_ambil, id_siswa, program, tagihan, status, tanggal) VALUES ('$idambil', '$id', '$program', '$totaltagihan', 'aktif', '$tanggal')");
                            echo $sql2 ? showNotification("Data Siswa berhasil ditambahkan", "success")
                                : showNotification("Data tagihan gagal ditambahkan", "error");
                        } else {
                            showNotification("Data gagal ditambahkan: " . mysqli_error($db), "error");
                        }
                    }
                }
                ?>
            </div>

            <div>
                <h3>Data Siswa</h3>
                <table border="1">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Password</th>
                        <th>Program</th>
                        <th>Tanggal daftar</th>
                        <th>Aksi</th>
                        <th>QR Code</th>
                    </tr>
                    <?php
                    $data = mysqli_query($db, "SELECT siswa.*, ambilprogram.program, ambilprogram.tanggal FROM `siswa` JOIN `ambilprogram` ON siswa.id_siswa = ambilprogram.id_siswa ORDER BY siswa.nama;");
                    $no = 1;
                    while ($row = mysqli_fetch_array($data)) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['program']; ?></td>
                            <td><?php echo $row['tanggal']; ?></td>
                            <td>
                                <button class="btn-edit"
                                    onclick="showEditForm('<?php echo $row['id_siswa']; ?>', '<?php echo $row['nama']; ?>','<?php echo $row['password']; ?>')">Edit</button>
                                <button class="btn-hapus"
                                    onclick="showDeleteConfirm('<?php echo $row['id_siswa']; ?>', '<?php echo $row['nama']; ?>')">Hapus</button>
                            </td>
                            <td>
                                <form action="../qrcode/generate_qr.php" method="post" target="_blank">
                                    <input type="hidden" name="nama" value="<?php echo $row['nama']; ?>">
                                    <input type="hidden" name="password" value="<?php echo $row['password']; ?>">
                                    <input type="submit" value="Generate QR">
                                </form>
                            </td>
                        </tr>
                        <?php $no++;
                    } ?>
                </table>
            </div>

            <!-- Popup untuk Edit Siswa -->
            <div id="editOverlay" class="overlay">
                <div class="popup">
                    <h2>Edit Siswa</h2>
                    <form id="editForm" action="" method="POST">
                        <input type="hidden" id="edit_id_siswa" name="id_siswa">
                        <div class="form-group">
                            <label for="edit_nama">Nama Siswa:</label>
                            <input type="text" id="edit_nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_password">password:</label>
                            <input type="text" id="edit_password" name="password" required>
                        </div>
                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideEditForm()">Batal</button>
                            <button type="submit" name="edit" class="btn-confirm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Popup untuk Konfirmasi Hapus -->
            <div id="deleteOverlay" class="overlay">
                <div class="popup">
                    <h2>Konfirmasi Hapus</h2>
                    <p id="deleteMessage">Apakah Anda yakin ingin menghapus siswa ini?</p>
                    <form id="deleteForm" action="" method="POST">
                        <input type="hidden" id="delete_id_siswa" name="id_siswa">
                        <input type="hidden" id="delete_program" name="program">
                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideDeleteConfirm()">Batal</button>
                            <button type="submit" name="hapus" class="btn-delete-confirm">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Popup untuk Nama Duplikat - Pilih Program Lain -->
            <div id="duplicateOverlay" class="overlay">
                <div class="popup">
                    <h2>Nama Sudah Ada</h2>
                    <p id="duplicateMessage">Nama siswa sudah terdaftar. Pilih program tambahan untuk siswa ini:</p>
                    <form id="duplicateForm" action="" method="POST">
                        <input type="hidden" id="duplicate_nama" name="nama_alternatif">
                        <div class="form-group">
                            <label for="duplicate_program">Pilih Program Lain:</label>
                            <select name="program_alternatif" id="duplicate_program" required>
                                <option value="">-- Pilih Program --</option>
                                <?php
                                $sql_program = mysqli_query($db, "SELECT * FROM program ORDER BY PROGRAM");
                                while ($row_program = mysqli_fetch_array($sql_program)) {
                                    echo "<option value='" . $row_program['PROGRAM'] . "'>" . $row_program['PROGRAM'] . "</option>";
                                }
                                ?>
                            </select>
                    <label for="tanggal">Tanggal daftar</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideDuplicateForm()">Batal</button>
                            <button type="submit" name="simpan_alternatif" class="btn-confirm">Tambah Program</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Edit Form Functions
    function showEditForm(id_siswa, nama, password) {
        document.getElementById('edit_id_siswa').value = id_siswa;
        document.getElementById('edit_password').value = password;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('editOverlay').style.display = 'flex';
    }

    function hideEditForm() {
        document.getElementById('editOverlay').style.display = 'none';
    }

    // Delete Confirmation Functions
    function showDeleteConfirm(id_siswa, program, nama) {
        document.getElementById('delete_id_siswa').value = id_siswa;
        document.getElementById('delete_program').value = program;
        document.getElementById('deleteMessage').textContent =
            'Apakah Anda yakin ingin menghapus siswa "' + program + '" " (' + id_siswa + ')?';
        document.getElementById('deleteOverlay').style.display = 'flex';
    }

    function hideDeleteConfirm() {
        document.getElementById('deleteOverlay').style.display = 'none';
    }

    // Duplicate Name Functions
    function showDuplicateForm(nama, currentProgram) {
        document.getElementById('duplicate_nama').value = nama;
        document.getElementById('duplicateMessage').innerHTML =
            'Nama siswa "<strong>' + nama + '</strong>" sudah terdaftar. Pilih program tambahan untuk siswa ini:';

        // Disable program yang sudah dipilih sebelumnya
        var selectElement = document.getElementById('duplicate_program');
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === currentProgram) {
                selectElement.options[i].disabled = true;
                selectElement.options[i].text = selectElement.options[i].text + ' (Sudah Terdaftar)';
            }
        }

        document.getElementById('duplicateOverlay').style.display = 'flex';
    }

    function hideDuplicateForm() {
        document.getElementById('duplicateOverlay').style.display = 'none';

        // Reset disabled options
        var selectElement = document.getElementById('duplicate_program');
        for (var i = 0; i < selectElement.options.length; i++) {
            selectElement.options[i].disabled = false;
            selectElement.options[i].text = selectElement.options[i].text.replace(' (Sudah Terdaftar)', '');
        }
    }

    // Close popups if user clicks outside the popup content
    window.onclick = function (event) {
        if (event.target === document.getElementById('editOverlay')) {
            hideEditForm();
        }
        if (event.target === document.getElementById('deleteOverlay')) {
            hideDeleteConfirm();
        }
        if (event.target === document.getElementById('duplicateOverlay')) {
            hideDuplicateForm();
        }
    }
</script>