<h3>Input Data Karyawan</h3>
<div class="formpresensi">
    <form action="" method="post">
        <label for="nama">Nama karyawan:</label><br>
        <input type="text" id="nama" name="nama" required><br>
        <label for="username">username :</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="nama">Password:</label><br>
        <input type="text" id="password" name="password" required><br>
        <label for="jabatan">Jabaran</label><br>
        <select name="jabatan" id="jabatan">
            <option value="admin">Admin</option>
            <option value="teacher">Teacher</option>
        </select><br>
        <input type="submit" value="Submit" name="submit">
        <!-- php -->
    </form>
</div>
<?php
include "../db.php";
if (isset($_POST["submit"])) {
    $user = $_POST["username"];
    $q1 = mysqli_query($db, "SELECT username FROM karyawan WHERE username='$user'");
    $cekuser = mysqli_fetch_array($q1);
    if ($cekuser) {
        echo "<script>alert('Username " . $cekuser[0] . " sudah tersedia, mohon ganti dengan username lain');</script>";
    } else {
        $username = $user;
        $nama = $_POST['nama'];
        $password = $_POST["password"];
        $jabatan = $_POST["jabatan"];
        $sql = mysqli_query($db, "INSERT INTO karyawan (username,`nama`,`password`,jabatan) VALUES ('$username','$nama','$password','$jabatan')");
        if ($sql) {
            echo "Data Karyawan berhasil ditambahkan";
        } else {
            echo "Data gagal ditambahkan";
        }

    }
} ?>
<!-- // Data karyawan -->
<h3>Data karyawan</h3>
<div class='table-wrapper'
    style='width: 500px;;max-height: 100vh;overflow-y: auto;margin-top: 10px;padding: 5px;box-shadow: steelblue 2px 2px 2px;'>
    <table border="1" style="width: 500px;;">
        <tr>
            <th>no</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Password</th>
            <th>jabatan</th>
            <th>Aksi</th>
        </tr>
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
                <a href="edit_siswa.php?=<?php echo $row['username'] ?>">Edit</a> |
                <a href="hapus_siswa.php?=<?php echo $row['username'] ?>">Hapus</a>
            </td>
            </tr>
            <?php
            $id++;
        } ?>
    </table>
</div>