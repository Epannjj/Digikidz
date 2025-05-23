<?php
session_start();
include "../db.php";
include "../notification.php";
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    <?php include "../edit.css"; ?>
</style>
<div class="sidebar">
    <div class="profile" onclick="toggleDropdown()">
        <img src="path-to-profile-image.jpg" alt="Profile" class="profile-img">
        <div class="profile-info">
            <?php if (isset($_SESSION['nama'])): ?>
                <span class="profile-name"><?= htmlspecialchars($_SESSION['nama']) ?></span>
            <?php else: ?>
                <span class="profile-name">Guest</span>
            <?php endif; ?>
            <span class="profile-role">Siswa</span>
        </div>
        <!-- ambil data siswa -->
        <?php
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            // Using prepared statement to prevent SQL injection
            $sql = "SELECT * FROM siswa WHERE id_siswa = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);
        }
        ?>

        <!-- Dropdown Menu -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="detail_profil.php">Detail Profil</a>
            <?php if (isset($_SESSION['id'])): ?>
                <a onclick="showEditForm('<?= htmlspecialchars($row['id_siswa']) ?>')">Ubah Password</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    if (isset($_POST['edit'])) {
        $id_siswa = $_POST['id_siswa'];
        $password_lama = $_POST['passwordlama'];
        $password_baru = $_POST['passwordbaru'];

        // Validation
        if (empty($password_lama) || empty($password_baru)) {
            showNotification('Password tidak boleh kosong.', 'error');
        } else {
            // Ambil password lama dari database dengan prepared statement
            $query = "SELECT password FROM siswa WHERE id_siswa = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "s", $id_siswa);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                $password_db = $data['password'];

                // Verifikasi password lama dengan perbandingan langsung
                if ($password_lama == $password_db) {
                    // Update ke password baru dengan prepared statement
                    $update = "UPDATE siswa SET password = ? WHERE id_siswa = ?";
                    $stmt = mysqli_prepare($db, $update);
                    mysqli_stmt_bind_param($stmt, "ss", $password_baru, $id_siswa);

                    if (mysqli_stmt_execute($stmt)) {
                        showNotification('Password berhasil diperbarui.', 'success');
                    } else {
                        showNotification('Gagal memperbarui password.', 'error');
                    }
                } else {
                    showNotification('Password lama salah.', 'error');
                }
            } else {
                showNotification('Data siswa tidak ditemukan.', 'error');
            }
        }
    }
    ?>

    <div id="editOverlay" class="overlay">
        <div class="popup">
            <h2>Ubah Password</h2>
            <form id="editForm" action="" method="POST">
                <input type="hidden" id="edit_id_siswa" name="id_siswa">
                <div class="form-group">
                    <label for="password_lama">Password lama:</label>
                    <input type="password" id="password_lama" name="passwordlama" required>
                </div>
                <div class="form-group">
                    <label for="password_baru">Password baru:</label>
                    <input type="password" id="password_baru" name="passwordbaru" required>
                </div>
                <div class="popup-buttons">
                    <button type="button" class="btn-cancel" onclick="hideEditForm()">Batal</button>
                    <button type="submit" name="edit" class="btn-confirm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav-menu">
        <li class="nav-item <?= $current_page == 'siswa.php' ? 'active' : '' ?>">
            <a href="siswa.php">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item <?= $current_page == 'detail_histori.php' ? 'active' : '' ?>">
            <a href="detail_histori.php">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>History</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="../logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profileDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Optional: klik di luar untuk menutup dropdown
    window.onclick = function (event) {
        const profile = document.querySelector(".profile");
        const dropdown = document.getElementById("profileDropdown");
        if (!profile.contains(event.target)) {
            dropdown.style.display = "none";
        }
    };

    function showEditForm(id) {
        document.getElementById('edit_id_siswa').value = id;
        document.getElementById('editOverlay').style.display = 'flex';
    }

    function hideEditForm() {
        document.getElementById('editOverlay').style.display = 'none';
    }
</script>