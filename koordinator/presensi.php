<style>
    <?php include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">
    <?php
    session_start();
    include "sidebar2.php";
    include "../db.php";
    ?>
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
                $no = 1;
                while ($row = mysqli_fetch_array($data)) {
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
                        echo "<button class='foto-btn' data-id='" . htmlspecialchars($row['id']) . "' style='width:60px;height:80px;'>Foto</button>";
                    }

                    echo "</td></tr>";
                    $no++;
                }
                ?>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk upload foto -->
<div id="fotoModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.6); z-index:1000;">
    <div class="modal-content" style="background:white; margin:5% auto; padding:20px; border-radius:10px; width:90%; max-width:400px; position:relative;">
        <span class="close" style="position:absolute; top:10px; right:20px; cursor:pointer; font-weight:bold;">&times;</span>
        <div id="modal-body">Memuat formulir...</div>
    </div>
</div>

<!-- JavaScript AJAX untuk modal -->
<script>
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
    });
});

// Tutup modal
document.querySelector('.close').addEventListener('click', function () {
    document.getElementById('fotoModal').style.display = 'none';
});
</script>
