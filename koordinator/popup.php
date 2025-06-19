<!-- Popup untuk Edit Siswa -->
<div id="editOverlay" class="overlay">
    <div class="popup">
        <h2>Edit Siswa</h2>
        <form id="editForm" action="" method="POST">
            <input type="hidden" id="edit_id_siswa" name="id_siswa">
            <div class="form-group">
                <label for="edit_nama">Nama Siswa:</label>
                <input type="text" id="edit_nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="edit_password">password:</label>
                <input type="text" id="edit_password" name="password" required>
            </div>
            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideEditForm()">Batal</button>
                <button type="submit" name="edit" class="btn-confirm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Popup untuk Konfirmasi Hapus -->
<div id="deleteOverlay" class="overlay">
    <div class="popup">
        <h2>Konfirmasi Hapus</h2>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus siswa ini?</p>
        <form id="deleteForm" action="#tabel" method="POST">
            <input type="hidden" id="delete_id_siswa" name="id_siswa">
            <input type="hidden" id="delete_nama" name="nama">
            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideDeleteConfirm()">Batal</button>
                <button type="submit" name="hapus" class="btn-delete-confirm">Hapus</button>
            </div>
        </form>
    </div>
</div>
<!-- Popup untuk Edit Program Siswa -->
<div id="editOverlayp" class="overlay">
    <div class="popup">
        <h2>Edit Program Siswa</h2>
        <form id="editForm" action="" method="POST">
            <input type="hidden" id="edit_id_ambil" name="id_ambil">
            <div class="form-group">
                <label for="edit_nama">Nama Siswa:</label>
                <input type="text" id="edit_namap" name="nama" required>
            </div>
            <div class="form-group">
                <label for="edit_program">program:</label>
                <input type="text" id="edit_program" name="program" required>
            </div>
            <div class="form-group">
                <label for="edit_tagihan">tagihan:</label>
                <input type="text" id="edit_tagihan" name="tagihan" required>
            </div>
            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideEditFormp()">Batal</button>
                <button type="submit" name="editp" class="btn-confirm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Popup untuk Konfirmasi Hapus -->
<div id="deleteOverlayp" class="overlay">
    <div class="popup">
        <h2>Konfirmasi Hapus Program Siswa</h2>
        <p id="deleteMessagep">Apakah Anda yakin ingin menghapus siswa ini?</p>
        <form id="deleteForm" action="#tabelProgramSiswa" method="POST">
            <input type="hidden" id="delete_id_ambil" name="id_ambil">
            <input type="hidden" id="delete_namap" name="nama">
            <input type="hidden" id="delete_program" name="program">
            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideDeleteConfirmp()">Batal</button>
                <button type="submit" name="hapusp" class="btn-delete-confirm">Hapus</button>
            </div>
        </form>
    </div>
</div>

<!-- Popup untuk Nama Duplikat - Pilih Program Lain -->
<div id="duplicateOverlay" class="overlay">
    <div class="popup">
        <h2>Nama Sudah Ada</h2>
        <p id="duplicateMessage">Nama siswa sudah terdaftar. Pilih program tambahan untuk siswa ini:
        </p>
        <form id="duplicateForm" action="" method="POST">
            <input type="hidden" id="duplicate_nama" name="nama_alternatif">
            <div class="form-group">
                <label for="duplicate_program">Pilih Program Lain:</label>
                <select name="program_alternatif" id="duplicate_program" required>
                    <option value="">-- Pilih Program --</option>
                    <?php
                    $sql_program = mysqli_query($db, "SELECT * FROM program ORDER BY PROGRAM");
                    while ($row_program = mysqli_fetch_array($sql_program)) {
                        echo "<option value='" . $row_program['PROGRAM'] . "'>" . $row_program['PROGRAM'] . "</option>";
                    }
                    ?>
                </select>
                <label for="tanggal">Tanggal daftar</label>
                <input type="date" id="tanggal" name="tanggal" required>
            </div>
            <div class="popup-buttons">
                <button type="button" class="btn-cancel" onclick="hideDuplicateForm()">Batal</button>
                <button type="submit" name="simpan_alternatif" class="btn-confirm">Tambah
                    Program</button>
            </div>
        </form>
    </div>
</div>