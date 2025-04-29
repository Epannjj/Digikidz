<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">

    <?php
    session_start();
    include "sidebar2.php";

    include "../db.php" ?>

</div>

<div class="main-container">
    <div class="header">
        <h3>Hasil presensi</h3>
    </div>
    <div class="conten">
        <div class="session">
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Program & Level</th>
                    <th>Materi</th>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Hasil Karya</th>
                </tr>
                <?php
                $data = mysqli_query($db, "SELECT * FROM hasil_presensi");
                $no = 1; // Tambahkan nomor urut
                while ($row = mysqli_fetch_array($data)) {
                    echo "<tr>
            <td>{$no}</td>
            <td>" . htmlspecialchars($row['nama']) . "</td>
            <td>" . htmlspecialchars($row['program']) . "</td>
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
                ?>
            </table>
        </div>
    </div>
</div>
</div>