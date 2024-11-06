<?php
// Memuat library phpqrcode
include('phpqrcode/qrlib.php');

// Baca file CSV
$file = fopen("nama.xlsx", "r");

// Pastikan folder output QR code ada
$output_folder = "qrcodes";
if (!file_exists($output_folder)) {
    mkdir($output_folder, 0777, true);
}

// Loop untuk membaca nama dari setiap baris CSV
while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
    $name = $data[0]; // Asumsikan nama ada di kolom pertama CSV

    // Menyimpan QR code ke folder yang ditentukan
    $file_path = $output_folder . "/" . $name . "_qrcode.png";
    QRcode::png($name, $file_path, QR_ECLEVEL_L, 10);
    echo "QR code untuk '$name' berhasil dibuat di: $file_path<br>";
}

fclose($file);
?>