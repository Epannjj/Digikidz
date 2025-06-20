<style>
    <?php
    session_start();
    include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">
    <?php
    include "sidebar2.php";
    include "../db.php";
    ?>
</div>

<div class="main-container">
    <div class="header">
        <h3>Hasil Presensi</h3>
    </div>

    <div class="section">
        <h3>Sortir Hasil Presensi</h3>
        <form method="get" action="">

            <!-- Nama Dropdown -->
            <label for="sort_nama">Pilih Nama:</label>
            <select name="sort_nama" id="sort_nama">
                <option value="">Semua Nama</option>
                <?php
                $nama_query = mysqli_query($db, "SELECT DISTINCT nama FROM hasil_presensi ORDER BY nama ASC");
                while ($nama_row = mysqli_fetch_assoc($nama_query)) {
                    $selected = ($_GET['sort_nama'] ?? '') == $nama_row['nama'] ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($nama_row['nama']) . "' $selected>" . htmlspecialchars($nama_row['nama']) . "</option>";
                }
                ?>
            </select>

            <!-- Program Dropdown -->
            <div class="sort" style="display:flex;flex-direction: column;">
                <form method="get" action="#tabel">
                    <label for="sort_kategori">Pilih Program</label>

                    <!-- Custom Searchable Select -->
                    <div class="custom-select">
                        <input type="text" class="select-input" id="searchableSelect" placeholder="-- Pilih Program --"
                            readonly
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

            $query = "SELECT * FROM hasil_presensi WHERE 1=1";
            if ($sort_program !== '') {
                $query .= " AND program = '" . mysqli_real_escape_string($db, $sort_program) . "'";
            }
            if (!empty($sort_nama)) {
                $query .= " AND nama = '" . mysqli_real_escape_string($db, $sort_nama) . "'";
            }
            $sql = mysqli_query($db, $query);
            ?>

            <div class="conten">
                <div class="session"
                    style="width: 100%; max-height: 60vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">

                    <table id="tabel">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Program</th>
                            <th>Materi</th>
                            <th>Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Hasil Karya</th>
                        </tr>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<tr>
                            <td>{$no}</td>
                            <td>" . htmlspecialchars($row['nama']) . "</td>
                            <td>" . htmlspecialchars($row['program']) . "</td>
                            <td>" . htmlspecialchars($row['materi']) . "</td>
                            <td>" . htmlspecialchars($row['pertemuan']) . "</td>
                            <td>" . htmlspecialchars($row['tanggal']) . "</td>
                            <td>";

                            if (!empty($row['hasil_karya']) && file_exists("../uploads/" . $row['hasil_karya'])) {
                                echo "<a href='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' target='_blank'>
                                <img src='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' width='60' height='80' style='object-fit:cover; cursor:pointer;'>
                              </a>";
                            } else {
                                echo "<button class='foto-btn' data-id='" . htmlspecialchars($row['id_presensi']) . "' style='width:60px;height:80px;'>Foto</button>";
                            }

                            echo "</td></tr>";
                            $no++;
                        }
                        ?>
                    </table>
                </div>
            </div>
    </div>
</div>

<div id="fotoModal" class="modal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.6); z-index:1000;">
    <div class="modal-content"
        style="background:white; margin:5% auto; padding:20px; border-radius:10px; width:90%; max-width:400px; position:relative;">
        <span class="close"
            style="position:absolute; top:10px; right:20px; cursor:pointer; font-weight:bold;">&times;</span>
        <!-- Modal Upload Foto -->
        <div id="fotoModal" class="modal"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.6); z-index:1000;">
            <div class="modal-content"
                style="background:white; margin:5% auto; padding:20px; border-radius:10px; width:90%; max-width:400px; position:relative;">
                <span class="close"
                    style="position:absolute; top:10px; right:20px; cursor:pointer; font-weight:bold;">&times;</span>
                >>>>>>>>> Temporary merge branch 2
                <div id="modal-body">Memuat formulir...</div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.foto-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const modal = document.getElementById('fotoModal');
                    const modalBody = document.getElementById('modal-body');

                    modal.style.display = 'block';
                    // Buka modal
                    document.querySelectorAll('.foto-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const id = this.getAttribute('data-id');
                            const modal = document.getElementById('fotoModal');
                            const modalBody = document.getElementById('modal-body');
                            modal.style.display = 'block';
                            const formData = new FormData();
                            formData.append('id', id);

                            fetch('foto.php', {
                                method: 'POST',
                                body: formData
                            })
                                .then(res => res.text())
                                .then(data => {
                                    modalBody.innerHTML = data;
                                })
                                .catch(err => {
                                    modalBody.innerHTML = "Terjadi kesalahan saat memuat data.";
                                });
                            fetch('foto.php', {
                                method: 'POST',
                                body: formData
                            })
                                .then(res => res.text())
                                .then(data => {
                                    modalBody.innerHTML = data;
                                })
                                .catch(() => {
                                    modalBody.innerHTML = "Terjadi kesalahan saat memuat data.";
                                });
                        });

                        // Tutup modal
                        document.querySelector('.close').addEventListener('click', function () {
                            document.getElementById('fotoModal').style.display = 'none';
                        });
                    });
                });
            });
            <?php include "../select.php"; ?>
        </script>