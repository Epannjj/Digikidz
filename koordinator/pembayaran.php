<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">

    <?php
    session_start();
    include "sidebar2.php";
    include "../db.php" 
    ?>

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
                                    SUM(ambilprogram.tagihan) AS total_tagihan, ambilprogram.tanggal AS tanggal_display,
                                    program.`MONTH OF CERTIFICATED`as bulan,
                                    category.category, category.harga as biaya_registrasi
                                    FROM siswa  JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa 
                                                JOIN program ON ambilprogram.program = program.PROGRAM
                                                JOIN category ON program.category = category.category
                                    GROUP BY siswa.id_siswa;
                                ");

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                    $programList = $row['program_list'];
                                    $programs = explode(",", $programList);
                                    $displayPrograms = array_map(function($prog) {
                                        $parts = explode(".", $prog);
                                        return isset($parts[1]) ? $parts[1] : $prog;
                                    }, $programs);
                                    $tanggalbulan = $row['bulan'];
                                    $pecah = explode("-", $tanggalbulan);
                                    $ambilbulan = (int)$pecah[1];
                                    echo '<option value="' . $row['id_siswa'] . '" 
                                        data-nama="' . $row['nama'] . '"
                                        data-program="' . $programList . '"
                                        data-tagihan="' . $row['total_tagihan'] . '"
                                        data-ambilbulan="' . $row['bulan'] . '"
                                        data-tanggal_display="' . $row['tanggal_display'] . '"
                                        >' 
                                        . $row['nama'] . '</option>';
                                }


                        } else {
                            echo '<option value="">(Belum ada siswa terdaftar)</option>';
                        }



                    ?>
                </select><br>
                    <input type="hidden" name="nama_siswa_text" id="nama_siswa_text" required>
                    <input type="hidden" name="program" id="program" readonly>
                    <input type="hidden" name="ambilbulan" id="ambilbulan" readonly>
                    <input type="hidden" name="tanggal_display" id="tanggal_display" readonly>
                    <input type="hidden" name="bulansaja" id="bulansaja" readonly>
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
            if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0) {
    $file_name = basename($_FILES["bukti"]["name"]);
    $target_dir = "../uploads/";
    $target_file = $target_dir . $file_name;

    // Pastikan hanya JPG, JPEG, PNG, GIF yang diizinkan
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
            $bukti = $file_name;
        } else {
            echo "❌ Gagal mengunggah file.";
            $bukti = '';
        }
    } else {
        echo "❌ Format file tidak didukung.";
        $bukti = '';
    }
} else {
    $bukti = '';
}

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
        $sppBulanan = 0;
        $bulanRegis = 6; // Ambil dari data tanggal atau simpan dari input hidden
        $rentangBulan = [];

        $sqlProgram = mysqli_query($db, "SELECT tagihan, program, tanggal FROM ambilprogram 
            WHERE id_siswa = '$id_siswa' ORDER BY program ASC LIMIT 1");

        if ($rp = mysqli_fetch_assoc($sqlProgram)) {
            $totalTagihan = (int)$rp['tagihan'];
            $ambilBulan = (int)$_POST['ambilbulan'];
            $bulanRegis = (int)$_POST['bulansaja'];
            $sppBulanan = $totalTagihan / $ambilBulan;

            // Hitung berapa bulan lunas
            $bulanTerbayar = floor($jumlah_bayar / $sppBulanan);
            $nama_bulan = [
                1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei",
                6 => "Juni", 7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober",
                11 => "November", 12 => "Desember"
            ];

            $bulan_sisa = [];
            for ($i = $bulanTerbayar; $i < $ambilBulan; $i++) {
                $index = ($bulanRegis + $i - 1) % 12 + 1;
                $bulan_sisa[] = $nama_bulan[$index];
            }

            $bulan_sisa_str = implode(" - ", $bulan_sisa);
            echo "<script>alert('Sisa Tagihan Rp " . ($totalTagihan - $jumlah_bayar) .
                "\\nSpp Bulanan (" . ($bulan_sisa_str ? $bulan_sisa_str : "Semua Lunas") . ")');</script>";
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
                    (id_pembayaran, nama_siswa, program, bulan_bayar, tanggal, jumlah_bayar, status, bukti)
                    VALUES ('$id', '$nama_siswa', '$program', '$bulan_bayar', '$tanggal', '$dibayar', '$status', '$bukti')");
                
                $id++;
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

            <label>Bulan:</label><br>
            <input type="text" value="<?= $data['bulan_bayar'] ?>" disabled><br>

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
    $bulan_bayar = $data_old['bulan_$bulan_bayar'];
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
        $bulan_bayar = $data['bula$bulan_bayar'];
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

                <label for="sort_lunas">Pilih Lunas:</label>
                <select name="sort_lunas" id="sort_lunas">
                    <option value="">Semua</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                </select>

                <input type="submit" class="submit-btn" value="Sortir">
            </form>

            
            <?php
            $sort_program = $_GET['sort_program'] ?? '';
            $sort_lunas = $_GET['sort_lunas'] ?? '';
            $query = "SELECT pembayaran.*, program.category FROM pembayaran JOIN program ON pembayaran.program = program.program WHERE 1=1";
            if (!empty($sort_program)) 
            $query .= " AND program.category = '$sort_program'";
            if (!empty($sort_lunas)) {
            $query .= " AND pembayaran.status = '$sort_lunas'";
            }
            $sql = mysqli_query($db, $query);
            ?>

            <div
                style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <table border="1">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Program</th>                        
                        <th>Bulan</th>
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
                            <td><?= $row['bulan_bayar']; ?></td>
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
<script src="../jsrefresh.js"></script>
<script>
    document.getElementById('nama_siswa').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const program = selectedOption.getAttribute('data-program');
        const nama = selectedOption.getAttribute('data-nama');
        const tagihan = selectedOption.getAttribute('data-tagihan');
        const ABulan = selectedOption.getAttribute('data-ambilbulan');
        const sppbulanan = tagihan/ABulan;
        const tanggalDisplay = selectedOption.getAttribute('data-tanggal_display');
        const bulansaja = parseInt(tanggalDisplay.split("-")[1]);
        const nama_bulan = [
            "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        const namaBulan = nama_bulan[bulansaja];
        const bulanList = [];
        for (let i = 0; i < ABulan; i++) {
            let index = (bulansaja + i - 1) % 12 + 1; // +1 karena array dimulai dari 1
            bulanList.push(nama_bulan[index]);
        }
        const rentangBulan = bulanList.join(" - ");
        const infoTagihan = document.getElementById('info_tagihan');

        // Jika belum memilih siswa
        if (this.value === "") {
            document.getElementById('program').value = "";
            document.getElementById('nama_siswa_text').value = "";
            document.getElementById('tanggal_display').value = "";
            document.getElementById('ambilbulan').value = "";
            document.getElementById('bulansaja').value = "";
            infoTagihan.innerHTML = "<span style='color:red;'>Kamu belum memilih siswa</span>";
            return;
        }

        // Jika siswa dipilih
        document.getElementById('program').value = program;
        document.getElementById('nama_siswa_text').value = nama;
        document.getElementById('ambilbulan').value = ABulan;
        document.getElementById('bulansaja').value = bulansaja;
        document.getElementById('tanggal_display').value = tanggalDisplay;
        infoTagihan.innerHTML = `
            Program : ${program}<br>
            Rentang Bulan : ${rentangBulan}<br>
            Bulan Regis : ${namaBulan}<br>
            Total Tagihan : Rp${parseInt(tagihan).toLocaleString('id-ID')}<br>
            Spp bulanan : Rp${parseInt(sppbulanan).toLocaleString('id-ID')}<br>
            Tanggal Registrasi : ${tanggalDisplay}
        `;
    });
    document.getElementById('jumlah_bayar').addEventListener('input', function () {
    const bayar = parseInt(this.value);
    if (isNaN(bayar) || bayar <= 0) return;

    const tagihan = parseInt(document.getElementById('nama_siswa').selectedOptions[0].getAttribute('data-tagihan'));
    const ABulan = parseInt(document.getElementById('ambilbulan').value);
    const bulansaja = parseInt(document.getElementById('bulansaja').value);
    const sppbulanan = tagihan / ABulan;

    const bulanTerbayar = Math.floor(bayar / sppbulanan);
    const sisaTagihan = tagihan - bayar;

    const nama_bulan = [
        "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const bulanList = [];
    for (let i = bulanTerbayar; i < ABulan; i++) {
        let index = (bulansaja + i - 1) % 12 + 1;
        bulanList.push(nama_bulan[index]);
    }

    const sisaBulan = bulanList.join(" - ") || "Semua Lunas";
    const infoTagihan = document.getElementById('info_tagihan');

    infoTagihan.innerHTML += `
        <br><strong>Pembayaran Saat Ini:</strong><br>
        Dibayar : Rp${bayar.toLocaleString('id-ID')}<br>
        Sisa Tagihan : Rp${sisaTagihan.toLocaleString('id-ID')}<br>
        Spp Bulanan (${sisaBulan})
    `;
});

    </script>