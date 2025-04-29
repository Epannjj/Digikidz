<table class="tabel">
    <tr>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Status</th>
    </tr>
    <?php
    include "../db.php";
    $nama = $_SESSION['nama'];
    $query = mysqli_query($db, "SELECT * FROM pembayaran WHERE nama_siswa = '$nama'");
    if (!$query) {
        die("Query Error: " . mysqli_error($db));
    }
    if (mysqli_num_rows($query) == 0) {
        echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
    }
    while ($row = mysqli_fetch_array($query)) {
        echo "<tr>";
        echo "<td>" . $row['tanggal'] . "</td>";
        echo "<td>" . $row['jumlah_bayar'] . "</td>";
        if ($row['status'] == 'Belum Lunas') {
            echo "<td><span class='status-badge unpaid'>" . $row['status'] . "</span></td>";
        } else if ($row['status'] == 'Lunas') {
            echo "<td><span class='status-badge paid'>" . $row['status'] . "</span></td>";
        } else {
            echo "<td><span class='status-badge'>" . $row['status'] . "</span></td>";
        }
        echo "</tr>";
    }
    ?>
</table>