<?php
include "../phpqrcode/qrlib.php";

if (isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $level = $_POST['level'];
    $program = $_POST['program'];

    // Set the QR code content
    $qrContent = "Nama Siswa: " . $nama . "| Program = " . $program . "| level = " . $level;

    // Generate and display the QR code
    header('Content-Type: image/png');
    QRcode::png($qrContent);
}
?>