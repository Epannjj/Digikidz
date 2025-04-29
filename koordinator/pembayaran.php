<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">

    <?php
    session_start();
    include "sidebar2.php";

    include "../db.php" ?>

</div>
<div class="main-container">
    <div class="header">
        <h3>Pembayaran</h3>
    </div>
    <div class="conten">
        <div class="section">
            <form action="#pembayaran" method="post" enctype="multipart/form-data">
                <label>Nama Siswa:</label><br>
                <select name="nama_siswa" id="nama_siswa" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php
                    $result = mysqli_query($db, "SELECT * FROM siswa");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id_siswa'] . '" 
                            data-nama="' . $row['nama'] . '"
                            data-program="' . $row['program'] . '" 
                            data-level="' . $row['level'] . '">'
                            . $row['nama'] . '</option>';
                    }
                    ?>
                    <input type="hidden" name="nama_siswa_text" id="nama_siswa_text" required>
                    <input type="hidden" name="program" id="program" readonly>
                    <input type="hidden" name="level" id="level" readonly> <br>

                    <label>Tanggal Pembayaran:</label><br>
                    <input type="date" name="tanggal" required><br>

                    <label>Jumlah Bayar (Rp):</label><br>
                    <input type="number" name="jumlah_bayar" required><br>

                    <label>Upload Bukti Pembayaran:</label><br>
                    <input type="file" name="bukti" required><br><br>

                    <input type="submit" value="Submit" class="submit-btn" name="submit_pembayaran">
            </form>

            <?php
            if (isset($_POST['submit_pembayaran'])) {
                $q1 = mysqli_query($db, "SELECT count(id_pembayaran) FROM pembayaran");
                $cekid = mysqli_fetch_array($q1);
                $id = ($cekid[0] == 0) ? 1 : $cekid[0] + 1;

                // Ambil data dari form
                $nama_siswa = $_POST['nama_siswa_text']; // Nama siswa yang terpilih
                $program = $_POST['program'];
                $level = $_POST['level'];
                $tanggal = $_POST['tanggal'];
                $jumlah_bayar = $_POST['jumlah_bayar'];

                $tagihan = 500000; //contoh
                $tagihansetelah = $tagihan - $jumlah_bayar;
                $status = ($jumlah_bayar >= $tagihansetelah) ? 'Lunas' : 'Belum Lunas';

                // Proses upload file bukti pembayaran
                $bukti = time() . '_' . basename($_FILES["bukti"]['name']);
                $lokasi_file = $_FILES['bukti']['tmp_name'];
                move_uploaded_file($lokasi_file, "../uploads/$bukti");

                // Perubahan di sini: Menggunakan nama siswa, bukan ID siswa
                $sql = mysqli_query($db, "INSERT INTO pembayaran 
                (id_pembayaran, nama_siswa, program, level, tanggal, jumlah_bayar, status, bukti)
                VALUES ('$id', '$nama_siswa', '$program', '$level', '$tanggal', '$jumlah_bayar', '$status', '$bukti')");

                echo $sql ? "✅ Pembayaran berhasil ditambahkan" : "❌ Gagal menyimpan data";
            }
            ?>
        </div>

        <div class="section">
            <h3>Sortir Pembayaran</h3>
            <form method="get" action="#pembayaran">
                <label for="sort_program">Pilih Program:</label>
                <select name="sort_program" id="sort_program">
                    <option value="">Semua Program</option>
                    <option value="Coding">Coding</option>
                    <option value="Art">Art</option>
                    <option value="Robotik">Robotik</option>
                </select>

                <label for="sort_level">Pilih Level:</label>
                <select name="sort_level" id="sort_level">
                    <option value="">Semua Level</option>
                    <option value="Level 1">Level 1</option>
                    <option value="Level 2">Level 2</option>
                </select>

                <input type="submit" class="submit-btn" value="Sortir">
            </form>

            <?php
            $sort_program = isset($_GET['sort_program']) ? $_GET['sort_program'] : '';
            $sort_level = isset($_GET['sort_level']) ? $_GET['sort_level'] : '';

            $query = "SELECT * FROM pembayaran WHERE 1=1";
            if (!empty($sort_program))
                $query .= " AND program = '$sort_program'";
            if (!empty($sort_level))
                $query .= " AND level = '$sort_level'";

            $sql = mysqli_query($db, $query);
            ?>

            <div
                style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <table border="1">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Program</th>
                        <th>Level</th>
                        <th>Tanggal</th>
                        <th>Jumlah Bayar</th>
                        <th>Status</th>
                        <th>Bukti</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($sql)) { ?>
                        <tr>
                            <td><?php echo $row['id_pembayaran']; ?></td>
                            <td><?php echo $row['nama_siswa']; ?></td>
                            <td><?php echo $row['program']; ?></td>
                            <td><?php echo $row['level']; ?></td>
                            <td><?php echo $row['tanggal']; ?></td>
                            <td>Rp<?php echo number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><a href="../uploads/<?php echo $row['bukti']; ?>" target="_blank">Lihat Bukti</a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('nama_siswa').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        var program = selectedOption.getAttribute('data-program');
        var level = selectedOption.getAttribute('data-level');
        var nama = selectedOption.getAttribute('data-nama');

        document.getElementById('program').value = program;
        document.getElementById('level').value = level;
        document.getElementById('nama_siswa_text').value = nama;
    });
</script>