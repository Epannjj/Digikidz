<div class="tabel" style="display:flex;flex-direction: column;">
    <h4>Jadwal</h4>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Siswa</th>
            <th>Program</th>
            <th>Level</th>
            <th>Materi</th>
            <th>Tanggal</th>
            <th>Guru</th>
        </tr>
        <?php
        $query = mysqli_query($db, "SELECT * ,materi.judul_materi as judul FROM jadwal ,materi WHERE jadwal.program = materi.program AND jadwal.level = materi.level AND jadwal.pertemuan = materi.pertemuan");
        $id = 1;
        while ($row = mysqli_fetch_array($query)) {
            echo "<tr>";
            echo "<td>" . $id . "</td>";
            echo "<td>" . $row['nama_siswa'] . "</td>";
            echo "<td>" . $row['program'] . "</td>";
            echo "<td>" . $row['level'] . "</td>";
            echo "<td>" . $row['judul'] . "</td>";
            echo "<td>" . $row['tanggal'] . "</td>";
            echo "<td>" . $row['teacher'] . "</td>";
            echo "</tr>";
            $id++;
        }
        ?>
    </table>
</div>