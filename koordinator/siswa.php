<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">
    <?php
    session_start();
    include "sidebar2.php";
    include "../db.php";
    include "../notification.php";
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
                            $potong = explode(".", $row['PROGRAM']);
                            echo "<option value='" . $row['PROGRAM'] . "'>" . $potong[1] . "</option>";
                        }
                        ?>
                    </select><br>

                    <input type="submit" value="Simpan" class="submit-btn" name="simpan">
                </form>

                <?php
                if (isset($_POST['simpan'])) {
                    $nama = mysqli_real_escape_string($db, $_POST['nama']);
                    $program = mysqli_real_escape_string($db, $_POST['program']);

                    // Cek nama duplikat
                    $ceknama = mysqli_query($db, "SELECT nama FROM siswa WHERE nama='$nama'");
                    if (mysqli_fetch_array($ceknama)) {
                        showNotification("Nama Siswa sudah ada, mohon ganti dengan nama lain", "error");
                    } else {
                        // Buat ID Siswa
                        $filter1 = explode(".", $program);
                        $filter2 = $filter1[1] ?? $program; // fallback jika tidak ada titik
                        $filteredWord = preg_replace('/[^A-Z0-9]/', '', $filter2);
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
                            $sqltagihan = mysqli_query($db, "SELECT harga FROM program WHERE program = '$program'");
                            $ctagihan = mysqli_fetch_array($sqltagihan);

                            if ($ctagihan) {
                                $tagihan = $ctagihan['harga'];
                            } else {
                                echo "<script>alert('Program tidak ditemukan di tabel program!');</script>";
                                $tagihan = 0;
                            }

                            // Simpan ke ambilprogram
                            $sql2 = mysqli_query($db, "INSERT INTO ambilprogram (id_ambil, id_siswa, program, tagihan) VALUES ('$idambil','$id', '$program', '$tagihan')");
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
                        <th>ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th>Password</th>
                        <th>Aksi</th>
                        <th>QR Code</th>
                    </tr>
                    <?php
                    include "../pagination.php";
                    $result_total = mysqli_query($db, "SELECT COUNT(*) AS total FROM siswa");
                    $total_data = mysqli_fetch_assoc($result_total)['total'];
                    $total_pages = ceil($total_data / $limit);
                    $data = mysqli_query($db, "SELECT * FROM siswa LIMIT $start, $limit");

                    while ($row = mysqli_fetch_array($data)) { ?>
                        <tr>
                            <td><?php echo $row['id_siswa']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td>
                                <a href="edit_siswa.php?id_siswa=<?php echo $row['id_siswa']; ?>">Edit</a> |
                                <a href="hapus_siswa.php?id_siswa=<?php echo $row['id_siswa']; ?>">Hapus</a>
                            </td>
                            <td>
                                <form action="../qrcode/generate_qr.php" method="post" target="_blank">
                                    <input type="hidden" name="nama" value="<?php echo $row['nama']; ?>">
                                    <input type="hidden" name="password" value="<?php echo $row['password']; ?>">
                                    <input type="submit" value="Generate QR">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <?php include "../pagination2.html"; ?>
            </div>
        </div>
    </div>
</div>