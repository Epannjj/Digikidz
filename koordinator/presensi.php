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
    echo "
                    <tr>
                        <td>{$no}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['program']} - {$row['level']}</td>
                        <td>{$row['materi']}</td>
                        <td>{$row['pertemuan']}</td>
                        <td>{$row['tanggal']}</td>
                        <td><input type='button' value='Foto' style='width:60px;height: 80px;'></td>
                    </tr>";
    $no++;
}

echo "
                    </table>
                </div>
            </div>";
?>