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
                     $result = mysqli_query($db, "SELECT siswa.id_siswa, siswa.nama, 
                                    GROUP_CONCAT(ambilprogram.program) AS program_list,
                                    SUM(ambilprogram.tagihan) AS total_tagihan
                                    FROM siswa JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa GROUP BY siswa.id_siswa
                                ");

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                    $programList = $row['program_list'];
                                    $programs = explode(",", $programList);

                                    // Hapus "C." dari setiap program
                                    $displayPrograms = array_map(function($prog) {
                                        $parts = explode(".", $prog);
                                        return isset($parts[1]) ? $parts[1] : $prog;
                                    }, $programs);
                                    
                                    echo '<option value="' . $row['id_siswa'] . '" 
                                        data-nama="' . $row['nama'] . '"
                                        data-program="' . $programList . '"
                                        data-tagihan="' . $row['total_tagihan'] . '">' 
                                        . $row['nama'] . '</option>';
                                }


                        } else {
                            echo '<option value="">(Belum ada siswa terdaftar)</option>';
                        }
                    ?>
                </select><br>
                    <input type="hidden" name="nama_siswa_text" id="nama_siswa_text" required>
                    <input type="hidden" name="program" id="program" readonly>

                    <label>Tanggal Pembayaran:</label><br>
                    <input type="date" name="tanggal" required><br>

                    <label>Jumlah Bayar (Rp):</label><br>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar"><br>

                    <div id="info_tagihan" style="margin: 10px 0; font-weight: bold;"></div>

                    <label>Upload Bukti Pembayaran:</label><br>
                    <input type="file" name="bukti" required><br><br>

                    <input type="submit" value="Submit" name="submit_pembayaran">
                </form>

            <?php
        if (isset($_POST['submit_pembayaran'])) {
            $q1 = mysqli_query($db, "SELECT count(id_pembayaran) FROM pembayaran");
            $cekid = mysqli_fetch_array($q1);
            $id = ($cekid[0] == 0) ? 1 : $cekid[0] + 1;

            $id_siswa = $_POST['nama_siswa'];
            $nama_siswa = $_POST['nama_siswa_text'];
            $program = $_POST['program'];
            $tanggal = $_POST['tanggal'];
            $jumlah_bayar = (int) $_POST['jumlah_bayar'];
            $sisa_bayar = $jumlah_bayar;
            $status = 'Lunas';
            $sql = false;

            // Ambil semua program dengan tagihan > 0
            $ambilPrograms = mysqli_query($db, "SELECT id_ambil, program, tagihan 
                FROM ambilprogram 
                WHERE id_siswa = '$id_siswa' AND tagihan > 0 
                ORDER BY program ASC
            ");

            if (mysqli_num_rows($ambilPrograms) === 0) {
                echo "Maaf, siswa ini tidak memiliki tagihan.";
            }

            while ($row = mysqli_fetch_assoc($ambilPrograms)) {
                $id_ambil = $row['id_ambil'];
                $program = $row['program'];
                $tagihan = (int) $row['tagihan'];

                if ($sisa_bayar <= 0) break;

                if ($sisa_bayar >= $tagihan) {
                    mysqli_query($db, "UPDATE ambilprogram SET tagihan = 0 WHERE id_ambil = '$id_ambil'");
                    $dibayar = $tagihan;
                    $sisa_bayar -= $tagihan;
                } else {
                    mysqli_query($db, "UPDATE ambilprogram SET tagihan = tagihan - $sisa_bayar 
                        WHERE id_ambil = '$id_ambil'");
                    $dibayar = $sisa_bayar;
                    $sisa_bayar = 0;
                    $status = 'Belum Lunas';
                }

                // Simpan pembayaran
                $sql = mysqli_query($db, "INSERT INTO pembayaran 
                    (id_pembayaran, nama_siswa, program, tanggal, jumlah_bayar, status, bukti)
                    VALUES ('$id', '$nama_siswa', '$program', '$tanggal', '$dibayar', '$status', '$bukti')");
                
                $id++;
            }

            if ($sql) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
        ?>

    <?php
    if (isset($_GET['edit_id'])) {
        $id = $_GET['edit_id'];
        $sql = mysqli_query($db, "SELECT * FROM pembayaran WHERE id_pembayaran = '$id'");
        $data = mysqli_fetch_assoc($sql);

        if ($data) {
         ?>
        <h3>Edit Pembayaran ID <?= $id ?></h3>
        <form method="post">
            <input type="hidden" name="update_id" value="<?= $id ?>">
            <label>Nama Siswa:</label><br>
            <input type="text" value="<?= $data['nama_siswa'] ?>" disabled><br>

            <label>Program:</label><br>
            <input type="text" value="<?= $data['program'] ?>" disabled><br>

            <label>Tanggal:</label><br>
            <input type="date" value="<?= $data['tanggal'] ?>" disabled><br>

            <label>Jumlah Bayar (Rp):</label><br>
            <input type="number" name="jumlah_bayar" value="<?= $data['jumlah_bayar'] ?>" required><br>

            <label>Status:</label><br>
            <select name="status" required>
                <option value="Lunas" <?= $data['status'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                <option value="Belum Lunas" <?= $data['status'] == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
            </select><br><br>

            <input type="submit" name="update" value="Update">
        </form>
    <?php
        } else {
            echo "<p>❌ Data tidak ditemukan untuk diedit.</p>";
        }
    }
    ?>


    <?php
    if (isset($_POST['update'])) {
    $id = $_POST['update_id'];
    $jumlah_bayar_baru = (int)$_POST['jumlah_bayar'];
    $status = $_POST['status'];

    // Ambil data lama terlebih dahulu
    $sql_old = mysqli_query($db, "SELECT * FROM pembayaran WHERE id_pembayaran = '$id'");
    $data_old = mysqli_fetch_assoc($sql_old);

    $jumlah_bayar_lama = (int)$data_old['jumlah_bayar'];
    $program = $data_old['program'];
    $nama_siswa = $data_old['nama_siswa'];

    // Ambil ID siswa berdasarkan nama
    $get_id_siswa = mysqli_query($db, "SELECT id_siswa FROM siswa WHERE nama = '$nama_siswa'");
    $data_siswa = mysqli_fetch_assoc($get_id_siswa);
    $id_siswa = $data_siswa['id_siswa'];

    // Kembalikan dulu tagihan sebelumnya
    mysqli_query($db, "UPDATE ambilprogram 
        SET tagihan = tagihan + $jumlah_bayar_lama 
        WHERE id_siswa = '$id_siswa' AND program = '$program'
    ");

    // Update data pembayaran
    $update = mysqli_query($db, "UPDATE pembayaran 
        SET jumlah_bayar = '$jumlah_bayar_baru', status = '$status' 
        WHERE id_pembayaran = '$id'
    ");

    // Kurangi lagi tagihan sesuai nilai baru
    mysqli_query($db, "UPDATE ambilprogram 
        SET tagihan = tagihan - $jumlah_bayar_baru 
        WHERE id_siswa = '$id_siswa' AND program = '$program'
    ");

    if($update) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "❌ Gagal memperbarui data.";
    }
}

    // Handle hapus
   if (isset($_POST['hapus_id'])) {
    $id = $_POST['hapus_id'];

    // Ambil data pembayaran yang akan dihapus
    $sql = mysqli_query($db, "SELECT * FROM pembayaran WHERE id_pembayaran = '$id'");
    $data = mysqli_fetch_assoc($sql);

    if ($data) {
        $jumlah_bayar = (int)$data['jumlah_bayar'];
        $program = $data['program'];
        $nama_siswa = $data['nama_siswa'];
        $bukti = $data['bukti'];

        // Ambil ID siswa
        $get_id_siswa = mysqli_query($db, "SELECT id_siswa FROM siswa WHERE nama = '$nama_siswa'");
        $data_siswa = mysqli_fetch_assoc($get_id_siswa);
        $id_siswa = $data_siswa['id_siswa'];

        // Kembalikan jumlah bayar ke tagihan
        mysqli_query($db, "UPDATE ambilprogram 
            SET tagihan = tagihan + $jumlah_bayar 
            WHERE id_siswa = '$id_siswa' AND program = '$program'
        ");

        // Hapus file bukti jika ada
        if (!empty($bukti) && file_exists("../uploads/" . $bukti)) {
            unlink("../uploads/" . $bukti);
        }

        // Hapus data dari tabel pembayaran
        $delete = mysqli_query($db, "DELETE FROM pembayaran WHERE id_pembayaran = '$id'");

        if ($delete) {
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "❌ Gagal menghapus data.";
        }
    }
}
    ?>
        </div>

        <div class="section">
            <h3>Sortir Pembayaran</h3>
            <form method="get" action="#pembayaran">
                <label for="sort_program">Pilih Program:</label>
                <select name="sort_program" id="sort_program">
                    <option value="">Semua Program</option>
                    <option value="computer">Computer</option>
                    <option value="art">Art</option>
                    <option value="robotik">Robotik</option>
                </select>

                <input type="submit" class="submit-btn" value="Sortir">
            </form>

            
            <?php
            $sort_program = $_GET['sort_program'] ?? '';
            $query = "SELECT pembayaran.*, program.category FROM pembayaran JOIN program ON pembayaran.program = program.program WHERE 1=1";
            if (!empty($sort_program)) $query .= " AND program.category = '$sort_program'";
            $sql = mysqli_query($db, $query);
            ?>

            <div
                style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <table border="1">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Program</th>
                        <th>Tanggal</th>
                        <th>Jumlah Bayar</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($sql)) { ?>
                        <tr>
                            <td><?= $row['id_pembayaran']; ?></td>
                            <td><?= $row['nama_siswa']; ?></td>
                            <td><?= $row['program']; ?></td>
                            <td><?= $row['tanggal']; ?></td>
                            <td>Rp<?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                            <td><?= $row['status']; ?></td>
                            <td><a href="../uploads/<?= $row['bukti']; ?>" target="_blank">Lihat Bukti</a></td>
                            <td>
                                <form method="get" style="display:inline;">
                                    <input type="hidden" name="edit_id" value="<?= $row['id_pembayaran']; ?>">
                                    <button type="submit">✏️ Edit</button>
                                </form>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    <input type="hidden" name="hapus_id" value="<?= $row['id_pembayaran']; ?>">
                                    <button type="submit">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } $db->close(); ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('nama_siswa').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const program = selectedOption.getAttribute('data-program');
        const nama = selectedOption.getAttribute('data-nama');
        const tagihan = selectedOption.getAttribute('data-tagihan');

        const infoTagihan = document.getElementById('info_tagihan');

        // Jika belum memilih siswa
        if (this.value === "") {
            document.getElementById('program').value = "";
            document.getElementById('nama_siswa_text').value = "";
            infoTagihan.innerHTML = "<span style='color:red;'>Kamu belum memilih siswa</span>";
            return;
        }

        // Jika siswa dipilih
        document.getElementById('program').value = program;
        document.getElementById('nama_siswa_text').value = nama;

        infoTagihan.innerHTML = `
            Program : ${program}<br>
            Total Tagihan: Rp${parseInt(tagihan).toLocaleString('id-ID')}
        `;
    });
    </script>
    <script src="../jsrefresh.js"></script>