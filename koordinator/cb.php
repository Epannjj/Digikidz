<?php
include "../db.php";
$uploadsDir = '../uploads';
$zipFileName = 'uploads_' . date('Ymd_His') . '.zip';

if (isset($_POST['download_zip'])) {
    $zip = new ZipArchive();

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        $files = glob($uploadsDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        $zip->close();

        if (file_exists($zipFileName)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
            header('Content-Length: ' . filesize($zipFileName));
            flush();
            readfile($zipFileName);
            unlink($zipFileName); // hapus file zip setelah diunduh
            exit;
        } else {
            echo "ZIP file tidak dapat dibuat.";
        }
    } else {
        echo "Gagal membuat file ZIP.";
    }
}

if (isset($_POST['delete_files'])) {
    $files = glob($uploadsDir . '/*');
    $deleted = 0;

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }

    echo "Berhasil menghapus $deleted file.";
}
?>

<h3>CB</h3>
<div class="conten" style="display:flex;flex-direction: row;">
    <div class="formpresensi">
        <form action="#pembayaran" method="post" enctype="multipart/form-data">
            <label>Nama Siswa:</label><br>
            <select name="nama_siswa" id="nama_siswa" required>
                <option value="">-- Pilih Siswa --</option>
                <?php
                    $result = mysqli_query($db, "SELECT * FROM siswa");
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id_siswa'] . '" 
                                data-nama="' . $row['nama'] . '"
                                data-program="' . $row['program'] . '">' 
                                . $row['nama'] . '</option>';
                        }
                    } else {
                        echo '<option value="">(Belum ada siswa terdaftar)</option>';
                    }
                ?>
            </select><br>

            <!-- Hidden inputs -->
            <input type="hidden" name="nama_siswa_text" id="nama_siswa_text" required>
            <input type="hidden" name="program" id="program" readonly>
            <input type="hidden" name="level" id="level" readonly>

            <label>Tanggal Pembayaran:</label><br>
            <input type="date" name="tanggal" required><br>

            <label>Jumlah Bayar (Rp):</label><br>
            <input type="number" name="jumlah_bayar" id="jumlah_bayar" required readonly><br>

            <!-- Tagihan otomatis ditampilkan -->
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

            $nama_siswa = $_POST['nama_siswa_text'];
            $program = $_POST['program'];
            $level = $_POST['level'];
            $tanggal = $_POST['tanggal'];
            $jumlah_bayar = $_POST['jumlah_bayar'];

            // Hitung tagihan dari tabel harga
            $totalTagihan = 0;
            $hargaQuery = mysqli_query($db, "SELECT level, harga FROM harga WHERE program = '$program'");
            while ($h = mysqli_fetch_assoc($hargaQuery)) {
                if ($h['level'] == 'Registrasi' || $h['level'] == $level) {
                    $totalTagihan += $h['harga'];
                }
            }
            $status = ($jumlah_bayar >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';

            // Upload file bukti
            $bukti = time() . '_' . basename($_FILES["bukti"]['name']);
            $lokasi_file = $_FILES['bukti']['tmp_name'];
            move_uploaded_file($lokasi_file, "../uploads/$bukti");

            $sql = mysqli_query($db, "INSERT INTO pembayaran 
                (id_pembayaran, nama_siswa, program, tanggal, jumlah_bayar, status, bukti)
                VALUES ('$id', '$nama_siswa', '$program', '$tanggal', '$jumlah_bayar', '$status', '$bukti')");

            echo $sql ? "✅ Pembayaran berhasil ditambahkan" : "❌ Gagal menyimpan data";
        }
        ?>
    </div>

    <!-- Bagian Sortir Pembayaran -->
    <div class="sort" style="display:flex;flex-direction: column;">
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

            <input type="submit" value="Sortir">
        </form>

        <?php
        $sort_program = $_GET['sort_program'] ?? '';
        $sort_level = $_GET['sort_level'] ?? '';
        $query = "SELECT * FROM pembayaran WHERE 1=1";
        if (!empty($sort_program)) $query .= " AND program = '$sort_program'";
        if (!empty($sort_level)) $query .= " AND level = '$sort_level'";
        $sql = mysqli_query($db, $query);
        ?>

        <div style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
            <table border="1">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Program</th>
                    <th>Tanggal</th>
                    <th>Jumlah Bayar</th>
                    <th>Status</th>
                    <th>Bukti</th>
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
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

    <h2>Manajemen File Uploads</h2>

    <form method="post">
        <button type="submit" name="download_zip">Download Semua Gambar (.zip)</button>
    </form>

    <br>

    <form method="post" onsubmit="return confirm('Yakin ingin menghapus semua gambar di folder uploads?');">
        <button type="submit" name="delete_files">Hapus Semua Gambar</button>
    </form>

<script>
    const hargaData = <?php
        $hargaQuery = mysqli_query($db, "SELECT program, level, harga FROM harga");
        $hargaArr = [];
        while ($row = mysqli_fetch_assoc($hargaQuery)) {
            $hargaArr[] = $row;
        }
        echo json_encode($hargaArr);
    ?>;

    document.getElementById('nama_siswa').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const program = selectedOption.getAttribute('data-program');
        const level = selectedOption.getAttribute('data-level');
        const nama = selectedOption.getAttribute('data-nama');

        // Set hidden inputs
        document.getElementById('program').value = program;
        document.getElementById('level').value = level;
        document.getElementById('nama_siswa_text').value = nama;

        // Hitung harga berdasarkan data harga
        let hargaRegistrasi = 0;
        let hargaLevel = 0;

        hargaData.forEach(item => {
            if (item.program === program && item.level === "Registrasi") {
                hargaRegistrasi = parseInt(item.harga);
            }
            if (item.program === program && item.level === level) {
                hargaLevel = parseInt(item.harga);
            }
        });

        const totalBayar = hargaRegistrasi + hargaLevel;
        document.getElementById('jumlah_bayar').value = totalBayar;

        // Tampilkan info tagihan
        const infoTagihan = document.getElementById('info_tagihan');
        infoTagihan.innerHTML = `
            Tagihan untuk program <strong>${program}</strong> level <strong>${level}</strong>:<br>
            - Registrasi: Rp${hargaRegistrasi.toLocaleString('id-ID')}<br>
            - Level: Rp${hargaLevel.toLocaleString('id-ID')}<br>
            <strong>Total: Rp${totalBayar.toLocaleString('id-ID')}</strong>
        `;
    });
</script>
