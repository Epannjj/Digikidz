<?php session_start(); ?>
<style>
    <?php
    include "../styles.css";
    include "../edit.css";
    ?>
</style>

<div class="sidebar-placeholder">
    <?php
    include "sidebar2.php";
    include "../db.php";
    include "../notification.php";

    // EDIT siswa
    if (isset($_POST['edit'])) {
        $id_siswa = $_POST['id_siswa'];
        $nama = $_POST['nama'];
        $password = $_POST['password'];

        $update = mysqli_query($db, "UPDATE siswa SET nama='$nama', password='$password' WHERE id_siswa='$id_siswa'");
        if ($update) {
            showNotification("Data siswa berhasil diubah", "success");
        } else {
            showNotification("Gagal mengubah data siswa: " . mysqli_error($db), "error");
        }
    }

    // HAPUS siswa
    if (isset($_POST['hapus'])) {
        $id_siswa = $_POST['id_siswa'];

        // Cek apakah siswa memiliki data di tabel ambilprogram
        $cek = mysqli_query($db, "SELECT COUNT(*) AS jumlah FROM ambilprogram WHERE id_siswa='$id_siswa'");
        $data = mysqli_fetch_assoc($cek);

        if ($data['jumlah'] > 0) {
            // Jika ada program, gunakan transaksi untuk hapus kedua tabel
            mysqli_begin_transaction($db);

            try {
                // Hapus dari ambilprogram
                $delete2 = mysqli_query($db, "DELETE FROM ambilprogram WHERE id_siswa='$id_siswa'");
                if (!$delete2) {
                    throw new Exception("Gagal menghapus dari tabel ambilprogram: " . mysqli_error($db));
                }

                // Hapus dari siswa
                $delete = mysqli_query($db, "DELETE FROM siswa WHERE id_siswa='$id_siswa'");
                if (!$delete) {
                    throw new Exception("Gagal menghapus dari tabel siswa: " . mysqli_error($db));
                }

                mysqli_commit($db);
                showNotification("Data siswa dan program berhasil dihapus", "success");

            } catch (Exception $e) {
                mysqli_rollback($db);
                showNotification("Gagal menghapus data siswa: " . $e->getMessage(), "error");
            }

        } else {
            // Jika tidak ada program, hapus hanya dari siswa
            $delete = mysqli_query($db, "DELETE FROM siswa WHERE id_siswa='$id_siswa'");
            if ($delete) {
                showNotification("Data siswa berhasil dihapus (tidak terdaftar di program)", "success");
            } else {
                showNotification("Gagal menghapus data siswa: " . mysqli_error($db), "error");
            }
        }
    }
    // Edit ambilprogram
    if (isset($_POST['edit_program'])) {
        $id_ambil = $_POST['id_ambil'];
        $program = mysqli_real_escape_string($db, $_POST['program']);
        $tanggal = date("Y-m-d");

        // Cek apakah program sudah ada untuk siswa ini
        $cek_program = mysqli_query($db, "SELECT * FROM ambilprogram WHERE id_ambil != '$id_ambil' AND program = '$program'");
        if (mysqli_num_rows($cek_program) > 0) {
            showNotification("Program sudah terdaftar untuk siswa ini!", "error");
        } else {
            // Update ambilprogram
            $update_program = mysqli_query($db, "UPDATE ambilprogram SET program='$program', tanggal='$tanggal' WHERE id_ambil='$id_ambil'");
            if ($update_program) {
                showNotification("Program berhasil diubah", "success");
            } else {
                showNotification("Gagal mengubah program: " . mysqli_error($db), "error");
            }
        }
    }
    //edit ambilprogram
    if (isset($_POST['editp'])) {
        $id_ambil = $_POST['id_ambil'];
        $nama = $_POST['nama'];
        $program = $_POST['program'];
        $tagihan = $_POST['tagihan'];

        // Update ambilprogram
        $update_program = mysqli_query($db, "UPDATE ambilprogram SET program='$program', tagihan='$tagihan' WHERE id_ambil='$id_ambil' AND nama='$nama'");
        if ($update_program) {
            showNotification("Program siswa berhasil diubah", "success");
        } else {
            showNotification("Gagal mengubah program siswa: " . mysqli_error($db), "error");
        }
    }
    // HAPUS ambilprogram 
    if (isset($_POST['hapusp'])) {
        $id_ambil = $_POST['id_ambil'];
        $delete = mysqli_query($db, "DELETE FROM ambilprogram WHERE id_ambil='$id_ambil'");
        if ($delete) {
            showNotification("Data siswa berhasil dihapus", "success");
        } else {
            showNotification("Gagal menghapus data siswa: " . mysqli_error($db), "error");
        }
    }
    // SIMPAN dengan program alternatif (untuk nama duplikat)
    if (isset($_POST['simpan_alternatif'])) {
        $nama = mysqli_real_escape_string($db, $_POST['nama_alternatif']);
        $program = mysqli_real_escape_string($db, $_POST['program_alternatif']);
        $tanggal = date("Y-m-d");
        // Ambil ID siswa yang sudah ada berdasarkan nama
        $ambil_id_siswa = mysqli_query($db, "SELECT id_siswa FROM siswa WHERE nama = '$nama'");
        $data_siswa = mysqli_fetch_array($ambil_id_siswa);

        if ($data_siswa) {
            $id_siswa_existing = $data_siswa['id_siswa'];

            // Cek apakah kombinasi siswa dan program sudah ada
            $cek_program = mysqli_query($db, "SELECT * FROM ambilprogram WHERE id_siswa = '$id_siswa_existing' AND program = '$program'");

            if (mysqli_num_rows($cek_program) > 0) {
                showNotification("Siswa sudah terdaftar di program ini!", "error");
            } else {
                // Ambil tagihan dari tabel program
                $sqltagihan = mysqli_query($db, "SELECT harga FROM program WHERE program = '$program'");
                $ctagihan = mysqli_fetch_array($sqltagihan);

                if ($ctagihan) {
                    $tagihan = $ctagihan['harga'];
                } else {
                    showNotification("Program tidak ditemukan di tabel program!", "error");
                    $tagihan = 0;
                }

                // Generate ID untuk ambilprogram
                $idambil = 'p' . $id_siswa_existing . '_' . time(); // Menambahkan timestamp untuk uniqueness
    
                // Simpan ke ambilprogram saja (tidak ke tabel siswa)
                $sql2 = mysqli_query($db, "INSERT INTO ambilprogram (id_ambil, id_siswa, program, tagihan, tanggal) VALUES ('$idambil','$id_siswa_existing', '$program', '$tagihan', '$tanggal')");

                if ($sql2) {
                    showNotification("Program berhasil ditambahkan untuk siswa $nama", "success");
                } else {
                    showNotification("Gagal menambahkan program: " . mysqli_error($db), "error");
                }
            }
        } else {
            showNotification("Data siswa tidak ditemukan!", "error");
        }
    }
    ?>
</div>

<div class="main-container">
    <div class="header">
        <h3>Input Data Siswa</h3>
    </div>
    <div class="conten">
        <div class="section">
            <?php
            include "popup.php";
            ?>
            <div class="form">
                <form action="#siswa" method="post">
                    <label for="nama">Nama Siswa:</label><br>
                    <input type="text" id="nama" name="nama" required><br>

                    <label for="program">Program</label><br>
                    <div class="sort" style="display:flex;flex-direction: column;">
                        <label for="sort_kategori">Pilih Program</label>

                        <!-- Custom Searchable Select -->
                        <div class="custom-select">
                            <input type="text" class="select-input" id="searchableSelectSiswa"
                                placeholder="-- Pilih Program --" readonly
                                value="<?php echo isset($_GET['selected_text']) ? htmlspecialchars($_GET['selected_text']) : '-- Semua Program --'; ?>">
                            <input type="hidden" name="sort_program" id="hiddenInputSiswa"
                                value="<?php echo isset($_GET['sort_program']) ? htmlspecialchars($_GET['sort_program']) : ''; ?>">
                            <input type="hidden" name="selected_text" id="selectedTextSiswa"
                                value="<?php echo isset($_GET['selected_text']) ? htmlspecialchars($_GET['selected_text']) : ' '; ?>">

                            <div class="select-dropdown" id="selectDropdownSiswa"></div>
                        </div>
                    </div>
                    <input type="submit" value="Simpan" class="submit-btn" name="simpan">
                </form>

                <?php
                if (isset($_POST['simpan'])) {
                    $nama = mysqli_real_escape_string($db, $_POST['nama']);
                    $program = mysqli_real_escape_string($db, $_POST['selected_text']);
                    $tanggal = date("Y-m-d");

                    // Cek nama duplikat dan program yang sudah diambil
                    $ceknama = mysqli_query($db, "SELECT siswa.id_siswa, siswa.nama, ambilprogram.program
                                                FROM siswa 
                                                JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa 
                                                WHERE siswa.nama='$nama'");

                    if ($row_duplikat = mysqli_fetch_array($ceknama)) {
                        // Jika nama sudah ada, cek apakah program yang dipilih sama
                        $program_sudah_ada = false;

                        // Reset pointer untuk mengecek semua program yang sudah diambil
                        mysqli_data_seek($ceknama, 0);
                        while ($check_program = mysqli_fetch_array($ceknama)) {
                            if ($check_program['program'] == $program) {
                                $program_sudah_ada = true;
                                break;
                            }
                        }

                        if ($program_sudah_ada) {
                            showNotification("Siswa $nama sudah terdaftar di program $program!", "error");
                        } else {
                            // Nama sama tapi program beda, tampilkan popup untuk menambah program baru
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showDuplicateForm('$nama', '$program');
                                });
                            </script>";
                        }
                    } else {
                        // Nama belum ada, lanjutkan proses normal
                        // Buat ID Siswa
                        $filteredWord = preg_replace('/[^A-Z0-9]/', '', $program);
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
                            $program = mysqli_real_escape_string($db, $_POST['sort_program']);
                            $sqltagihan = mysqli_query($db, "SELECT harga, `MONTH OF CERTIFICATED` FROM program WHERE program = '$program'");
                            $ctagihan = mysqli_fetch_array($sqltagihan);
                            if ($ctagihan) {
                                $tagihan = $ctagihan['harga'];
                                $brpbulan = $ctagihan['MONTH OF CERTIFICATED'];
                            } else {
                                echo "<script>alert('Program tidak ditemukan di tabel program!');</script>";
                                $tagihan = 0;
                            }
                            $totaltagihan = $tagihan * $brpbulan;
                            // Simpan ke ambilprogram
                            $sql2 = mysqli_query($db, "INSERT INTO ambilprogram (id_ambil, id_siswa, program, tagihan, status, tanggal) VALUES ('$idambil', '$id', '$program', '$totaltagihan', 'aktif', '$tanggal')");
                            echo $sql2 ? showNotification("Data Siswa berhasil ditambahkan", "success")
                                : showNotification("Data tagihan gagal ditambahkan", "error");
                        } else {
                            showNotification("Data gagal ditambahkan: " . mysqli_error($db), "error");
                        }
                    }
                }
                ?>
            </div>
            <div class="menu-op" style="text-align: center; margin-bottom: 30px;">
                <button class="menu-btn active" onclick="showDataSiswa()">Data Siswa</button>
                <button class="menu-btn" onclick="showDataSiswaprogram()">Data Program Siswa</button>
                <?php
                // Query untuk mendapatkan data progress
                $data_progress = mysqli_query($db, "
    SELECT 
        siswa.nama, 
        ambilprogram.program, 
        COUNT(DISTINCT hasil_presensi.pertemuan) AS pertemuan_hadir,
        (
            SELECT `JUMLAH PERTEMUAN (WEEK)`
            FROM program 
            WHERE program.program = ambilprogram.program
        ) AS total_pertemuan
    FROM siswa
    JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa
    LEFT JOIN hasil_presensi ON siswa.nama = hasil_presensi.nama AND ambilprogram.program = hasil_presensi.program
    GROUP BY siswa.id_siswa, ambilprogram.program;
");

                // Simpan data dalam array dan hitung badge sekaligus
                $progress_data = [];
                $progress_report_count = 0;
                $sertifikat_count = 0;

                while ($row = mysqli_fetch_array($data_progress)) {
                    $total_pertemuan = $row['total_pertemuan'] > 0 ? $row['total_pertemuan'] : 1;
                    $progress_percentage = ($row['pertemuan_hadir'] / $total_pertemuan) * 100;

                    // Simpan data untuk digunakan nanti
                    $progress_data[] = [
                        'nama' => $row['nama'],
                        'program' => $row['program'],
                        'pertemuan_hadir' => $row['pertemuan_hadir'],
                        'total_pertemuan' => $total_pertemuan,
                        'progress_percentage' => round($progress_percentage, 2)
                    ];

                    // Hitung untuk badge
                    if (round($progress_percentage, 2) == 50) {
                        $progress_report_count++;
                    } else if (round($progress_percentage, 2) == 100) {
                        $sertifikat_count++;
                    }
                }
                ?>

                <!-- Button dengan badge -->
                <button class="menu-btn" onclick="showProgressSiswa()">
                    Progress Siswa
                    <?php if ($progress_report_count > 0): ?>
                        <span class="notification-badge badge-blue"><?= $progress_report_count ?></span>
                    <?php endif; ?>
                    <?php if ($sertifikat_count > 0): ?>
                        <span class="notification-badge badge-red"><?= $sertifikat_count ?></span>
                    <?php endif; ?>
                </button>
            </div>
            <!-- Tabel Data Siswa -->
            <div id="tabelDataSiswa" class="table-container fade-in">
                <h3>Data Siswa</h3>
                <form method="GET" action="#tabel">
                    <input type="text" name="search" placeholder="Cari nama siswa..."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="submit-btn">Cari</button>
                </form>
                <?php
                $search = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';

                $query = "
    SELECT siswa.*, 
           (SELECT COUNT(*) FROM ambilprogram WHERE ambilprogram.id_siswa = siswa.id_siswa) AS jumlah_program 
    FROM siswa
";

                if (!empty($search)) {
                    $query .= " WHERE siswa.nama LIKE '%$search%'";
                }

                $query .= " ORDER BY siswa.nama";

                $data = mysqli_query($db, $query);
                ?>
                <div class="table"
                    style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                    <table border="1" id="tabel">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Password</th>
                            <th>jumlah Program</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_array($data)) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['password']; ?></td>
                                <td><?php echo $row['jumlah_program']; ?></td>
                                <td>
                                    <button class="btn-edit"
                                        onclick="showEditForm('<?php echo $row['id_siswa']; ?>', '<?php echo $row['nama']; ?>','<?php echo $row['password']; ?>')">Edit</button>
                                    <button class="btn-hapus"
                                        onclick="showDeleteConfirm('<?php echo $row['id_siswa']; ?>', '<?php echo $row['nama']; ?>')">Hapus</button>
                                </td>
                            </tr>
                            <?php $no++;
                        } ?>
                    </table>
                </div>
            </div>
            <!-- Tabel Data Program Siswa -->
            <div id="tabelProgramSiswa" class="table-container fade-in hidden">
                <h3>Data Program Siswa</h3>
                <!-- Program Dropdown -->
                <div class="sort" style="display:flex;flex-direction: column;">
                    <form method="get" action="#tabel">
                        <label for="sort_kategori">Pilih Program</label>

                        <!-- Custom Searchable Select -->
                        <div class="custom-select">
                            <input type="text" class="select-input" id="searchableSelect"
                                placeholder="-- Pilih Program --" readonly
                                value="<?php echo isset($_GET['selected_text']) ? htmlspecialchars($_GET['selected_text']) : '-- Semua Program --'; ?>">
                            <input type="hidden" name="sort_program" id="hiddenInput"
                                value="<?php echo isset($_GET['sort_program']) ? htmlspecialchars($_GET['sort_program']) : ''; ?>">
                            <input type="hidden" name="selected_text" id="selectedText"
                                value="<?php echo isset($_GET['selected_text']) ? htmlspecialchars($_GET['selected_text']) : '-- Semua Program --'; ?>">

                            <div class="select-dropdown" id="selectDropdown"></div>
                        </div>
                        <input type="submit" class="submit-btn" value="Filter">
                    </form>
                </div>
                <?php
                $sort_program = $_GET['sort_program'] ?? '';

                $query = "SELECT siswa.nama, ambilprogram.*, siswa.id_siswa FROM siswa JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa WHERE 1=1 ";
                if ($sort_program !== '') {
                    $query .= " AND ambilprogram.program = '" . mysqli_real_escape_string($db, $sort_program) . "'";
                }

                $query .= " ORDER BY siswa.nama ASC";
                $sql = mysqli_query($db, $query);
                ?>
                <div class="table"
                    style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Program</th>
                            <th>Tanggal Daftar</th>
                            <th>Tagihan PerBulan</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        $no_program = 1;
                        while ($row_program = mysqli_fetch_array($sql)) { ?>
                            <tr>
                                <td><?php echo $no_program; ?></td>
                                <td><?php echo $row_program['nama']; ?></td>
                                <td><?php echo $row_program['program']; ?></td>
                                <?php if ($row_program["tanggal"] == null) {
                                    $tgll = "-";
                                } else {
                                    $tgll = $row_program["tanggal"];
                                } ?>
                                <td><?php echo $tgll; ?></td>
                                <td><?php echo $row_program['tagihan']; ?></td>
                                <td>
                                    <button class=" btn-edit"
                                        onclick="showEditFormp('<?php echo $row_program['id_ambil']; ?>','<?php echo $row_program['nama']; ?>','<?php echo $row_program['program']; ?>','<?php echo $row_program['tagihan']; ?>')">Edit</button>
                                    <button class="btn-hapus"
                                        onclick="showDeleteConfirmp('<?php echo $row_program['id_ambil']; ?>','<?php echo $row_program['nama']; ?>','<?php echo $row_program['program']; ?>')">Hapus</button>
                                </td>
                            </tr>
                            <?php $no_program++;
                        } ?>
                    </table>
                </div>
            </div>
            <div id="tabelProgressSiswa" class="table-container fade-in hidden"
                style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
                <h3>Progress Siswa</h3>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Program</th>
                        <th>Progress</th>
                    </tr>
                    <tr>
                        <?php
                        $data_progress = mysqli_query($db, "
        SELECT 
            siswa.nama, 
            ambilprogram.program, 
            COUNT(DISTINCT hasil_presensi.pertemuan) AS pertemuan_hadir,
            (
                SELECT `JUMLAH PERTEMUAN (WEEK)`
                FROM program 
                WHERE program.program = ambilprogram.program
            ) AS total_pertemuan
        FROM siswa
        JOIN ambilprogram ON siswa.id_siswa = ambilprogram.id_siswa
        LEFT JOIN hasil_presensi ON siswa.nama = hasil_presensi.nama AND ambilprogram.program = hasil_presensi.program
        GROUP BY siswa.id_siswa, ambilprogram.program;
    ");

                        $no_progress = 1;
                        while ($row_progress = mysqli_fetch_array($data_progress)) {
                            $total_pertemuan = $row_progress['total_pertemuan'] > 0 ? $row_progress['total_pertemuan'] : 1;
                            $progress_percentage = ($row_progress['pertemuan_hadir'] / $total_pertemuan) * 100;
                            if (round($progress_percentage, 2) == 50) {
                                echo "<tr>
            <td>{$no_progress}</td>
            <td>{$row_progress['nama']}</td>
            <td>{$row_progress['program']}</td>
            <td>
                <button class='btn-blue' onclick=\"showProgressrptForm('{$row_progress['nama']}', '{$row_progress['program']}')\">
                    Upload Progress Report
                </button>
            </td>
        </tr>";
                            } else if (round($progress_percentage, 2) == 100) {
                                echo "<tr>
            <td>{$no_progress}</td>
            <td>{$row_progress['nama']}</td>
            <td>{$row_progress['program']}</td>
            <td>
                <button class='btn-blue' onclick=\"showSertifikatForm('{$row_progress['nama']}', '{$row_progress['program']}')\">
                    Upload Sertifikat
                </button>
            </td>
        </tr>";
                            } else {
                                echo "<tr>
            <td>{$no_progress}</td>
            <td>{$row_progress['nama']}</td>
            <td>{$row_progress['program']}</td>
            <td>
                <div class='progress-bar' style='width:100%; background:#eee; border-radius:6px; height:22px; position:relative;'>
                    <div class='progress-fill' style='width:" . round($progress_percentage, 2) . "%; background:#4caf50; height:100%; border-radiu  s:6px;'></div>
                    <div class='progress-text' style='position:absolute; left:0; right:0; top:0; bottom:0; display:flex; align-items:center; justify-content:center; font-weight:bold;'>
                        " . round($progress_percentage, 2) . "%
                    </div>
                </div>
            </td>
        </tr>";
                            }
                            $no_progress++;
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <?php
            // UPLOAD PROGRESS REPORT
            if (isset($_POST['upload_progress_report'])) {
                $nama = mysqli_real_escape_string($db, $_POST['progressrpt_nama']);
                $program = mysqli_real_escape_string($db, $_POST['progressrpt_program']);
                $link_progress = mysqli_real_escape_string($db, $_POST['link_progress']);

                // Update atau insert progress report
                $check_existing = mysqli_query($db, "SELECT * FROM progress_report WHERE nama='$nama' AND program='$program'");

                if (mysqli_num_rows($check_existing) > 0) {
                    // Update existing record
                    $update_progress = mysqli_query($db, "UPDATE progress_report SET link_progress='$link_progress', tanggal_upload=NOW() WHERE nama='$nama' AND program='$program'");
                    if ($update_progress) {
                        showNotification("Progress Report berhasil diupdate untuk $nama", "success");
                    } else {
                        showNotification("Gagal mengupdate Progress Report: " . mysqli_error($db), "error");
                    }
                } else {
                    // Insert new record
                    $insert_progress = mysqli_query($db, "INSERT INTO progress_report (nama, program, link_progress, tanggal_upload) VALUES ('$nama', '$program', '$link_progress', NOW())");
                    if ($insert_progress) {
                        showNotification("Progress Report berhasil diupload untuk $nama", "success");
                    } else {
                        showNotification("Gagal mengupload Progress Report: " . mysqli_error($db), "error");
                    }
                }
            }

            // UPLOAD SERTIFIKAT
            if (isset($_POST['upload_sertifikat'])) {
                $nama = mysqli_real_escape_string($db, $_POST['sertifikat_nama']);
                $program = mysqli_real_escape_string($db, $_POST['sertifikat_program']);
                $link_sertifikat = mysqli_real_escape_string($db, $_POST['link_sertifikat']);

                // Update atau insert sertifikat
                $check_existing = mysqli_query($db, "SELECT * FROM sertifikat WHERE nama='$nama' AND program='$program'");

                if (mysqli_num_rows($check_existing) > 0) {
                    // Update existing record
                    $update_sertifikat = mysqli_query($db, "UPDATE sertifikat SET link_sertifikat='$link_sertifikat', tanggal_upload=NOW() WHERE nama='$nama' AND program='$program'");
                    if ($update_sertifikat) {
                        showNotification("Sertifikat berhasil diupdate untuk $nama", "success");
                    } else {
                        showNotification("Gagal mengupdate Sertifikat: " . mysqli_error($db), "error");
                    }
                } else {
                    // Insert new record
                    $insert_sertifikat = mysqli_query($db, "INSERT INTO sertifikat (nama, program, link_sertifikat, tanggal_upload) VALUES ('$nama', '$program', '$link_sertifikat', NOW())");
                    if ($insert_sertifikat) {
                        showNotification("Sertifikat berhasil diupload untuk $nama", "success");
                    } else {
                        showNotification("Gagal mengupload Sertifikat: " . mysqli_error($db), "error");
                    }
                }
            }
            ?>

            <!-- Popup untuk Upload Progress Report -->
            <div id="progressrptOverlay" class="overlay">
                <div class="popup">
                    <h2>Upload Progress Report</h2>
                    <form action="" method="POST">
                        <input type="hidden" id="progressrpt_nama" name="progressrpt_nama">
                        <input type="hidden" id="progressrpt_program" name="progressrpt_program">

                        <div class="form-group">
                            <label for="siswa_info_progress">Siswa:</label>
                            <p id="siswa_info_progress" class="info-text"></p>
                        </div>

                        <div class="form-group">
                            <label for="program_info_progress">Program:</label>
                            <p id="program_info_progress" class="info-text"></p>
                        </div>

                        <div class="form-group">
                            <label for="link_progress">Link Drive Progress Report:</label>
                            <input type="url" id="link_progress" name="link_progress"
                                placeholder="https://drive.google.com/..." required>
                        </div>

                        <div class="form-group">
                            <small class="help-text">
                                Pastikan file sudah di-share dengan akses "Anyone with the link can view"
                            </small>
                        </div>

                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideProgressrptForm()">Batal</button>
                            <button type="submit" name="upload_progress_report" class="btn-confirm">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Popup untuk Upload Sertifikat -->
            <div id="sertifikatOverlay" class="overlay">
                <div class="popup">
                    <h2>Upload Sertifikat</h2>
                    <form action="" method="POST">
                        <input type="hidden" id="sertifikat_nama" name="sertifikat_nama">
                        <input type="hidden" id="sertifikat_program" name="sertifikat_program">

                        <div class="form-group">
                            <label for="siswa_info_sertifikat">Siswa:</label>
                            <p id="siswa_info_sertifikat" class="info-text"></p>
                        </div>

                        <div class="form-group">
                            <label for="program_info_sertifikat">Program:</label>
                            <p id="program_info_sertifikat" class="info-text"></p>
                        </div>

                        <div class="form-group">
                            <label for="link_sertifikat">Link Drive Sertifikat:</label>
                            <input type="url" id="link_sertifikat" name="link_sertifikat"
                                placeholder="https://drive.google.com/..." required>
                        </div>

                        <div class="form-group">
                            <small class="help-text">
                                Pastikan file sudah di-share dengan akses "Anyone with the link can view"
                            </small>
                        </div>

                        <div class="popup-buttons">
                            <button type="button" class="btn-cancel" onclick="hideSertifikatForm()">Batal</button>
                            <button type="submit" name="upload_sertifikat" class="btn-confirm">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Edit Form Functions
    function showEditForm(id_siswa, nama, password) {
        document.getElementById('edit_id_siswa').value = id_siswa;
        document.getElementById('edit_password').value = password;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('editOverlay').style.display = 'flex';
    }

    function hideEditForm() {
        document.getElementById('editOverlay').style.display = 'none';
    }

    // Delete Confirmation Functions
    function showDeleteConfirm(id_siswa, nama) {
        document.getElementById('delete_id_siswa').value = id_siswa;
        document.getElementById('delete_nama').value = nama;
        document.getElementById('deleteMessage').textContent =
            'Apakah Anda yakin ingin menghapus siswa "' + nama + ' ?"';
        document.getElementById('deleteOverlay').style.display = 'flex';
    }

    function hideDeleteConfirm() {
        document.getElementById('deleteOverlay').style.display = 'none';
    }
    // Edit Program
    function showEditFormp(id_ambil, nama, program, tagihan) {
        document.getElementById('edit_id_ambil').value = id_ambil;
        document.getElementById('edit_namap').value = nama;
        document.getElementById('edit_program').value = program;
        document.getElementById('edit_tagihan').value = tagihan;
        document.getElementById('editOverlayp').style.display = 'flex';
    }

    function hideEditFormp() {
        document.getElementById('editOverlayp').style.display = 'none';
    }

    // Delete Confirmation Functions
    function showDeleteConfirmp(id_ambil, nama, program) {
        document.getElementById('delete_id_ambil').value = id_ambil;
        document.getElementById('delete_namap').value = nama;
        document.getElementById('delete_program').value = program;
        document.getElementById('deleteMessagep').textContent =
            'Apakah Anda yakin ingin menghapus siswa "' + nama + ' Dengan Program : ' + program + ' ?"';
        document.getElementById('deleteOverlayp').style.display = 'flex';
    }

    function hideDeleteConfirmp() {
        document.getElementById('deleteOverlayp').style.display = 'none';
    }

    // Duplicate Name Functions
    function showDuplicateForm(nama, currentProgram) {
        document.getElementById('duplicate_nama').value = nama;
        document.getElementById('duplicateMessage').innerHTML =
            'Nama siswa "<strong>' + nama + '</strong>" sudah terdaftar. Pilih program tambahan untuk siswa ini:';

        // Disable program yang sudah dipilih sebelumnya
        var selectElement = document.getElementById('duplicate_program');
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].value === currentProgram) {
                selectElement.options[i].disabled = true;
                selectElement.options[i].text = selectElement.options[i].text + ' (Sudah Terdaftar)';
            }
        }

        document.getElementById('duplicateOverlay').style.display = 'flex';
    }

    function hideDuplicateForm() {
        document.getElementById('duplicateOverlay').style.display = 'none';

        // Reset disabled options
        var selectElement = document.getElementById('duplicate_program');
        for (var i = 0; i < selectElement.options.length; i++) {
            selectElement.options[i].disabled = false;
            selectElement.options[i].text = selectElement.options[i].text.replace(' (Sudah Terdaftar)', '');
        }
    }

    // Close popups if user clicks outside the popup content
    window.onclick = function (event) {
        if (event.target === document.getElementById('editOverlay')) {
            hideEditForm();
        }
        if (event.target === document.getElementById('deleteOverlay')) {
            hideDeleteConfirm();
        }
        if (event.target === document.getElementById('duplicateOverlay')) {
            hideDuplicateForm();
        }
    }
    function showDataSiswa() {
        document.getElementById('tabelProgressSiswa').classList.add('hidden');
        document.getElementById('tabelProgramSiswa').classList.add('hidden');
        document.getElementById('tabelDataSiswa').classList.remove('hidden');
        document.getElementById('tabelDataSiswa').classList.add('fade-in');

        updateButtonStatus('data');
    }

    function showDataSiswaprogram() {
        document.getElementById('tabelProgressSiswa').classList.add('hidden');
        document.getElementById('tabelDataSiswa').classList.add('hidden');
        document.getElementById('tabelProgramSiswa').classList.remove('hidden');
        document.getElementById('tabelProgramSiswa').classList.add('fade-in');

        updateButtonStatus('program');
    }

    function showProgressSiswa() {
        document.getElementById('tabelDataSiswa').classList.add('hidden');
        document.getElementById('tabelProgramSiswa').classList.add('hidden');
        document.getElementById('tabelProgressSiswa').classList.remove('hidden');
        document.getElementById('tabelProgressSiswa').classList.add('fade-in');

        updateButtonStatus('progress');
    }

    function updateButtonStatus(activeTab) {
        const buttons = document.querySelectorAll('.menu-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        if (activeTab === 'data') {
            buttons[0].classList.add('active');
        } else if (activeTab === 'program') {
            buttons[1].classList.add('active');
        } else if (activeTab === 'progress') {
            buttons[2].classList.add('active');
        }
    }

    // Initialize with Data Siswa view
    document.addEventListener('DOMContentLoaded', function () {
        showDataSiswa();
    });
    function showSertifikatForm(nama, program) {
        document.getElementById("sertifikat_nama").value = nama;
        document.getElementById("sertifikat_program").value = program;
        document.getElementById("sertifikatOverlay").style.display = "flex";
    }

    function hideSertifikatForm() {
        document.getElementById("sertifikatOverlay").style.display = "none";
    }
    function showProgressrptForm(nama, program) {
        document.getElementById("progressrpt_nama").value = nama;
        document.getElementById("progressrpt_program").value = program;
        document.getElementById("progressrptOverlay").style.display = "flex";
    }

    function hideprogressrptForm() {
        document.getElementById("progressrptOverlay").style.display = "none";
    }
    <?php include '../select.php'; ?>
</script>