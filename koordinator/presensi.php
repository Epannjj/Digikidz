<?php
echo " <div>
                <h3>Hasil presensi </h3>
                <div class='table-wrapper' style='max-height: 85vh;
            /* Sesuaikan tinggi maksimal */
            overflow-y: auto;
            /* Mengaktifkan scroll secara vertikal */
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            box-shadow: steelblue 2px 2px 2px;'>
                    <table border='1' style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Program & Level</th>
                            <th>Materi</th>
                            <th>Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Hasil Karya</th>
                        </tr>";

$data = mysqli_query($db, "SELECT * FROM hasil_presensi");
$no = 1; // Tambahkan nomor urut
while ($row = mysqli_fetch_array($data)) {
    echo "<tr>
            <td>{$no}</td>
            <td>" . htmlspecialchars($row['nama']) . "</td>
            <td>" . htmlspecialchars($row['program']) . " - " . htmlspecialchars($row['level']) . "</td>
            <td>" . htmlspecialchars($row['materi']) . "</td>
            <td>" . htmlspecialchars($row['pertemuan']) . "</td>
            <td>" . htmlspecialchars($row['tanggal']) . "</td>
            <td>";

    // Cek apakah ada foto yang sudah tersimpan
    if (!empty($row['hasil_karya']) && file_exists("../uploads/" . $row['hasil_karya'])) {
        echo "<a href='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' target='_blank'>
                <img src='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' width='60' height='80' style='object-fit:cover; cursor:pointer;'>
              </a>";
    } else {
        // Jika belum ada foto, tampilkan tombol upload
        echo "<form action='../foto.php' method='post' target='_blank'>
                <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                <input type='submit' value='Foto' style='width:60px;height: 80px;'>
              </form>";
    }

    echo "</td></tr>";

    $no++;
}

echo "
                    </table>
                </div>
            </div>";
?>
