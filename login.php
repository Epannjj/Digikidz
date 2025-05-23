<form action="" method="post">
    <h3>Login karyawan</h3>
    <label for="username">Username / id :</label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br>
    <input type="submit" name="login-g" value="Login"><br>
</form>
<!-- proses login -->
<?php
include 'db.php';
if (isset($_POST['login-g'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = mysqli_query($db, "SELECT * FROM karyawan WHERE username = '$username'");
    $ambil = mysqli_fetch_array($query);
    if ($ambil) {
        if ($password == $ambil['password']) {
            if ($ambil['jabatan'] == 'admin') {
                $_SESSION['user'] = $username;
                header("Location: koordinator/karyawan.php");
                exit;
            } elseif ($ambil['jabatan'] == 'teacher') {
                $_SESSION['user'] = $username;
                header("Location: guru/guru.php");
                exit;
            } else {
                echo "Belum memiliki jabatan";
            }
        } else {
            echo "<h3>Password Salah</h3>";
        }
    } else {
        echo "<h3>Username tidak tersedia";
    }
}
//     if ($username == $ambil['username'] && $password == $ambil['password']) {
//         if ($ambil['jabatan'] == 'admin') {
//             $_SESSION['user'] = $username;
//             header("location: koordinator/koordinator.php");
//         } elseif ($ambil['jabatan'] == 'guru') {
//             $_SESSION['user'] = $username;
//             header("location: guru/guru.php");
//         }
//     } else {
//         echo "<h3>Username atau Password Salah</h3>";
//     }
// } else
//     echo "Salah";
// ?>