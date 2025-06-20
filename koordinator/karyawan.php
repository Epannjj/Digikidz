<?php session_start(); ?>

<style>
    <?php include "../styles.css";
    include "../edit.css"; ?>
</style>
<div class="sidebar-placeholder">
    <?php include "sidebar2.php";
    include "../db.php";
    include "../notification.php";

    ?>
</div>
<?php
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $jabatan = $_POST['jabatan'];

    $sqledit = mysqli_query($db, "UPDATE karyawan SET nama='$nama', username='$username', password='$password', jabatan='$jabatan' WHERE id='$id'");
    if ($sqledit) {
        showNotification("Data karyawan berhasil diubah", "success");
    } else {
        showNotification("Data gagal diubah: " . mysqli_error($db), "error");
    }
}
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $sql = mysqli_query($db, "DELETE FROM karyawan WHERE id='$id'");
    if ($sql) {
        showNotification("Data karyawan berhasil dihapus", "success");
    } else {
        showNotification("Data gagal dihapus: " . mysqli_error($db), "error");
    }
}
?>

<div class="main-container">
    <div class="header">
        <h3>Input Data Karyawan</h3>
    </div>
    <div class="conten">
        <div class="section">
            <form action="" method="post">
                <label for="nama">Nama karyawan:</label><br>
                <input type="text" id="nama" name="nama" required><br>
                <label for="username">username :</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="nama">Password:</label><br>
                <input type="text" id="password" name="password" required><br>
                <label for="jabatan">Jabatan</label><br>
                <select name="jabatan" id="jabatan">
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                </select><br>
                <input type="submit" class="submit-btn" value="Submit" name="submit">
                <!-- php -->
            </form>
        </div>
        <?php
        include "../db.php";
        if (isset($_POST["submit"])) {
            $username = $_POST["username"];
            $q1 = mysqli_query($db, "SELECT username FROM karyawan WHERE username='$username'");
            $cekuser = mysqli_fetch_array($q1);
            if ($cekuser) {
                echo "<script>alert('Username " . $cekuser[0] . " sudah tersedia, mohon ganti dengan username lain');</script>";
            } else {
                $nama = $_POST['nama'];
                $password = $_POST["password"];
                $jabatan = $_POST["jabatan"];
                $cekid = mysqli_query($db, "SELECT max(id) FROM karyawan");
                $iid = mysqli_num_rows($cekid);
                if ($iid == 0) {
                    $iid = 1;
                } else {
                    $row = mysqli_fetch_array($cekid);
                    explode("K", $row[0]);
                    $row[0] = str_replace("K", "", $row[0]);
                    $iid = $row[0] + 1;
                }
                $id = "K" . sprintf("%03d", $iid);
                $sql = mysqli_query($db, "INSERT INTO karyawan (id, username,`nama`,`password`,jabatan) VALUES ('$id','$username','$nama','$password','$jabatan')");
                if ($sql) {
                    showNotification("Data Karyawan berhasil ditambahkan", "success");
                } else {
                    showNotification("Data gagal ditambahkan: " . mysqli_error($db), "error");
                }

            }
        } ?>
        <!-- // Data karyawan -->
        <div class="section">

            <h3>Data karyawan</h3>
            <div class="tabel"
                style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <table>
                    <thead <tr>
                        <th>no</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>jabatan</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-list">
                        <?php
                        $id = 1;
                        $data = mysqli_query($db, "SELECT * FROM karyawan");
                        while ($row = mysqli_fetch_array($data)) { ?>
                            <td><?= $id ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['password'] ?></td>
                            <td><?= $row['jabatan'] ?></td>
                            <td>
                                <button class="btn-edit"
                                    onclick="showEditForm('<?php echo $row['id']; ?>','<?php echo $row['nama']; ?>', '<?php echo $row['username']; ?>','<?php echo $row['password']; ?>','<?php echo $row['jabatan']; ?>')">Edit</button>
                                <button class="btn-hapus"
                                    onclick="showDeleteConfirm('<?php echo $row['id']; ?>', '<?php echo $row['username']; ?>')">Hapus</button>
                            </td>
                            </tr>
                            <?php
                            $id++;
                        } ?>
                    </tbody>
                </table>
            </div>
            <div id="editOverlay" class="overlay">
                <div class="popup">
                    <h2>Edit karyawan</h2>
                    <form id="editForm" action="karyawan.php" method="POST">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_nama">Nama karyawan:</label>
                            <input type="text" id="edit_nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_username">username:</label>
                            <input type="text" id="edit_username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_password">password:</label>
                            <input type="text" id="edit_password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jabatan">jabatan:</label>
                            <input type="text" id="edit_jabatan" name="jabatan" required>
                        </div>
                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideEditForm()">Batal</button>
                            <button type="submit" name="edit" class="btn-confirm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="deleteOverlay" class="overlay">
                <div class="popup">
                    <h2>Konfirmasi Hapus</h2>
                    <p id="deleteMessage">Apakah Anda yakin ingin menghapus karyawan ini?</p>
                    <form id="deleteForm" action=" " method="POST">
                        <input type="hidden" id="delete_id" name="id">
                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideDeleteConfirm()">Batal</button>
                            <button type="submit" name="hapus" class="btn-delete-confirm">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>  function showEditForm(id, nama, username, password, jabatan) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_password').value = password;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_jabatan').value = jabatan;
        document.getElementById('editOverlay').style.display = 'flex';
    }

    function hideEditForm() {
        document.getElementById('editOverlay').style.display = 'none';
    }

    // Delete Confirmation Functions
    function showDeleteConfirm(id, nama) {
        document.getElementById('delete_id').value = id;
        document.getElementById('deleteMessage').textContent =
            'Apakah Anda yakin ingin menghapus karyawan "' + nama + '" (' + id + ')?';
        document.getElementById('deleteOverlay').style.display = 'flex';
    }

    function hideDeleteConfirm() {
        document.getElementById('deleteOverlay').style.display = 'none';
    }

    // Close popups if user clicks outside the popup content
    window.onclick = function (event) {
        if (event.target === document.getElementById('editOverlay')) {
            hideEditForm();
        }
        if (event.target === document.getElementById('deleteOverlay')) {
            hideDeleteConfirm();
        }
    }</script>
</body>