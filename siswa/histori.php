<table class="tabel">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Pertemuan</th>
            <th>Materi</th>
            <th>teacher</th>
            <th>hasil karya</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include "../db.php";
        $nama = $_SESSION['nama'];
        $query = mysqli_query($db, "SELECT * FROM hasil_presensi WHERE nama = '$nama'");
        if (!$query) {
            die("Query Error: " . mysqli_error($db));
        }
        if (mysqli_num_rows($query) == 0) {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        while ($row = mysqli_fetch_array($query)) {
            echo "<tr>";
            echo "<td>" . $row['tanggal'] . "</td>";
            echo "<td>" . $row['pertemuan'] . "</td>";
            echo "<td>" . $row['materi'] . "</td>";
            echo "<td>" . $row['teacher'] . "</td>";
            echo "<td><a href='hasil_karya/" . $row['hasil_karya'] . "'>Download</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>