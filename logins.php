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

    $stmt = $db->prepare("SELECT * FROM siswa WHERE nama = ? AND password = ?");
    $stmt->bind_param('ss', $nama, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($ambil = $result->fetch_array()) {
        $_SESSION["nama"] = $nama;
        header("location: siswa/siswa.php");
        exit();
    } else {
        echo "<h3>Nama atau Password Salah</h3>";
    }
}
?>