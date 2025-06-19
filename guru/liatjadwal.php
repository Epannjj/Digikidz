<?php
session_start();
include "../db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Presensi</title>
    <style>
        <?php include "../styles.css"; ?>
    </style>
</head>

<body>
    <div class="sidebar-placeholder">
        <?php include "navbar-guru.php"; ?>
    </div>

    <div class="main-container">
        <div class="header">
            <h2>Hasil Presensi Ms/Mr <?= $_SESSION['user']; ?></h2>
        </div>

        <div class="content">
            <div class="section">
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Siswa</th>
                            <th>Program</th>
                            <th>Materi</th>
                            <th>Tanggal</th>
                            <th>Hasil karya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($db, "SELECT * FROM hasil_presensi WHERE teacher = '" . $_SESSION['user'] . "' ORDER BY tanggal DESC");
                        $id = 1;
                        while ($row = mysqli_fetch_array($query)) {
                            echo "<tr>";
                            echo "<td>" . $id . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['program']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['materi']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                            echo "<td>";

                            if (!empty($row['hasil_karya']) && file_exists("../uploads/" . $row['hasil_karya'])) {
                                echo "<a href='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' target='_blank'>
                                    <img src='../uploads/" . htmlspecialchars($row['hasil_karya']) . "' width='60' height='80' style='object-fit:cover; cursor:pointer;'>
                                  </a>";
                            } else {
                                echo "<button class='foto-btn' data-id='" . htmlspecialchars($row['id_presensi']) . "' style='width:60px;height:80px;'>Foto</button>";
                            }

                            echo "</td>";
                            echo "</tr>";
                            $id++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk upload foto -->
    <div id="fotoModal" class="modal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.6); z-index:1000;">
        <div class="modal-content"
            style="background:white; margin:5% auto; padding:20px; border-radius:10px; width:90%; max-width:400px; position:relative;">
            <span class="close"
                style="position:absolute; top:10px; right:20px; cursor:pointer; font-weight:bold; font-size:20px;">&times;</span>
            <div id="modal-body">Memuat formulir...</div>
        </div>
    </div>

    <!-- JavaScript AJAX untuk modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event listener untuk tombol foto
            document.querySelectorAll('.foto-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const modal = document.getElementById('fotoModal');
                    const modalBody = document.getElementById('modal-body');

                    console.log('Button clicked, ID:', id); // Debug

                    modal.style.display = 'block';
                    modalBody.innerHTML = 'Memuat formulir...';

                    const formData = new FormData();
                    formData.append('id_presensi', id);
                    fetch('../koordinator/foto.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(res => {
                            console.log('Response status:', res.status); // Debug
                            return res.text();
                        })
                        .then(data => {
                            console.log('Response data:', data); // Debug
                            modalBody.innerHTML = data;
                        })
                        .catch(err => {
                            console.error('Fetch error:', err); // Debug
                            modalBody.innerHTML = "Terjadi kesalahan saat memuat data: " + err.message;
                        });
                });
            });

            // Tutup modal
            document.querySelector('.close').addEventListener('click', function () {
                document.getElementById('fotoModal').style.display = 'none';
            });

            // Tutup modal jika klik di luar modal
            document.getElementById('fotoModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>