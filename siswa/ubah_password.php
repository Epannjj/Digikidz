<form method="POST">
    <label for="old_password">Password Lama:</label>
    <input type="password" name="old_password" id="old_password" required><br>
    <label for="new_password">Password Baru:</label>
    <input type="password" name="new_password" id="new_password" required><br>
    <label for="confirm_password">Konfirmasi Password Baru:</label>
    <input type="password" name="confirm_password" id="confirm_password" required><br>
    <input type="submit" name="submit" value="Ubah Password">
    <?php
    session_start();
    include '../db.php';

    if (!isset($_SESSION["nama"])) {
        header("Location: login.php");
        exit();
    }
    if (isset($_POST['submit'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $username = $_SESSION["nama"];
        $query = mysqli_query($db, "SELECT * FROM siswa WHERE nama = '$username'");
        $user = mysqli_fetch_assoc($query);

        if ($old_password === $user['password']) {
            if ($new_password == $confirm_password) {
                $hashed_new_password = $new_password;
                $update_query = mysqli_query($db, "UPDATE siswa SET password = '$hashed_new_password' WHERE nama = '$username'");

                if ($update_query) {
                    echo "Password berhasil diubah!";
                    header("Location:siswa.php");
                } else {
                    echo "Terjadi kesalahan, coba lagi.";
                }
            } else {
                echo "Password baru dan konfirmasi password tidak cocok!";
            }
        } else {
            echo "Password lama salah!";
        }
    }
    ?>
</form>