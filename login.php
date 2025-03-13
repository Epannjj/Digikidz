<h2>Login karyawan</h2>
<div class="container-k">
    <form action="" method="post">
        <label for="username">Username / id :</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" name="login-g" value="Login"><br>
    </form>
</div>
<!-- proses login -->
<?php
include 'db.php';
if (isset($_POST['login-g'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = mysqli_query($db, "SELECT * from karyawan where username = '$username' AND `password` = '$password'");
    $ambil = mysqli_fetch_array($query);
    if ($username == $ambil['username'] && $password == $ambil['password']) {
        if ($ambil['jabatan'] == 'admin') {
            $_SESSION['user'] = $username;
            header("location: koordinator/koordinator.php");
        } elseif ($ambil['jabatan'] == 'guru') {
            $_SESSION['user'] = $username;
            header("location: guru/guru.php");
        }
    } else {
        echo "<h3>Username atau Password Salah</h3>";
    }
}
?>