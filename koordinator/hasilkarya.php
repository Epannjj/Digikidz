<style>
    <?php
    session_start();
    include "../styles.css"; ?>
</style>

<div class="sidebar-placeholder">

    <?php
    include "sidebar2.php";

    include "../db.php" ?>

</div>
<?php
$uploadsDir = '../uploads';
$zipFileName = 'uploads_' . date('Ymd_His') . '.zip';

if (isset($_POST['download_zip'])) {
    $zip = new ZipArchive();

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        $files = glob($uploadsDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        $zip->close();

        if (file_exists($zipFileName)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
            header('Content-Length: ' . filesize($zipFileName));
            flush();
            readfile($zipFileName);
            unlink($zipFileName); // hapus file zip setelah diunduh
            exit;
        } else {
            echo "ZIP file tidak dapat dibuat.";
        }
    } else {
        echo "Gagal membuat file ZIP.";
    }
}

if (isset($_POST['delete_files'])) {
    $files = glob($uploadsDir . '/*');
    $deleted = 0;

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }

    echo "Berhasil menghapus $deleted file.";
}
?>

</div>
<div class="main-container">
    <div class="header">
        <h3>Foto hasil karya</h3>
    </div>
    <hr>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <?php
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $files = glob($uploadsDir . '/*');

        if ($files) {
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed_ext)) {
                    $filename = basename($file);
                    echo '<div style="text-align:center; margin:10px;">';
                    echo '<img src="' . $uploadsDir . '/' . $filename . '" alt="' . $filename . '" style="max-width:150px; max-height:150px; display:block;">';
                    echo '<small>' . $filename . '</small>';
                    echo '</div>';
                }
            }
        } else {
            echo "<p>Tidak ada gambar di folder uploads.</p>";
        }
        ?>
    </div>


    <form method="post">
        <button type="submit" name="download_zip">Download Semua Gambar (.zip)</button>
    </form> <br>
    <form method="post" onsubmit="return confirm('Yakin ingin menghapus semua gambar di folder uploads?');">
        <button type="submit" name="delete_files">Hapus Semua Gambar</button>
    </form>
    <p
        style="background-color: #fff3cd; border-left: 6px solid #ffc107; padding: 10px; font-size: 14px; border-radius: 5px; margin-top: 10px;">
        <strong>📢 Penting!</strong> Jangan lupa untuk <strong>mendownload backup gambar</strong> terlebih dahulu
        sebelum menghapusnya. agar menghemat ruang penyimpanan server.!
    </p>