<?php
// Mendekodekan JSON dari request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['qr_code'])) {
    $qrCode = $data['qr_code'];

    // Contoh respons (atau simpan ke database)
    echo "QR Code diterima: " . htmlspecialchars($qrCode);
} else {
    echo "QR Code tidak ditemukan.";
}
?>