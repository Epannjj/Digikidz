<?php
include "../phpqrcode/qrlib.php";

if (isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $level = $_POST['level'];

    // Set the QR code content
    $qrContent = "Nama Siswa: " . $nama . " dengan level = " . $level;

    // Generate and display the QR code
    header('Content-Type: image/png');
    QRcode::png($qrContent);
}
?>