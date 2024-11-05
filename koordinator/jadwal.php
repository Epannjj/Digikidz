<h4>Tambah jadwal</h4>
<div class="form">
    <form action="" method="post">
        <label for="nama_siswa">Nama Siswa:</label><br>
        <input type="text" id="nama_siswa" name="nama_siswa" required><br>
        <label for="nama_guru">Guru :</label><br>
        <input type="text" id="nama_guru" name="nama_guru" required><br>
        <label for="program">Program :</label><br>
        <input type="text" id="program" name="program" required><br>
        <label for="level">level :</label><br>
        <select name="level" id="level">
            <option value="">1</option>
            <option value="">2</option>
            <option value="">3</option>
            <option value="">4</option>
        </select><br>
        <label for="materi">materi :</label><br>
        <input type="text" id="materi" name="materi" required><br>
        <label for="tgl_jam">Tanggal dan Jam :</label><br>
        <input type="date" name="tanggal" id="tanggal">
        <input type="time" id="tgl_jam" name="tgl_jam" required><br>
        <input type="submit" value="Tambah Jadwal">
    </form>
</div>