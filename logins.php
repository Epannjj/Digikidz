<form action="" method="post">

    <h3>Login Siswa Privat</h3>
    <label for="nama">Nama:</label><br>
    <input type="text" id="nama" name="nama" required><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br>
    <input type="submit" name="login" value="Login">
</form>
<?php
include 'db.php';
if (isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $query = mysqli_query($db, "SELECT * FROM siswa WHERE nama = '$nama'");
    $ambil = mysqli_fetch_array($query);
    if ($ambil) {
        if ($password == $ambil['password']) {
            $_SESSION['nama'] = $nama;
            header("Location: siswa/siswa.php");
            exit;
        } else {
            echo "<h3>Password Salah</h3>";
        }
    } else {
        echo "<h3>Username tidak tersedia";
    }
}
?>