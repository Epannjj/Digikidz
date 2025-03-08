<?php
include "../phpqrcode/qrlib.php";

if (isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $level = $_POST['level'];
    $program = $_POST['program'];
    $password = $_POST['password'];

    $qrContent = $nama;

    // Define paths for temporary QR code, final name card image, background image, and logo image
    $tempDir = "temp/";
    $qrFileName = $tempDir . "qr_" . $nama . ".png";
    $cardFileName = $tempDir . "namecard_" . $nama . ".png";
    $backgroundFile = "gradient.png"; // Path to the background image
    $logoFile = "image.png"; // Path to your logo image

    // Ensure directory exists
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    // Generate QR code and save it as a temporary file
    QRcode::png($qrContent, $qrFileName);

    // Load the background image (adjust for your format: png, jpg, gif, etc.)
    $background = imagecreatefrompng($backgroundFile); // Use imagecreatefromjpeg() if your background is a JPG

    // Get the dimensions of the background image
    $bgWidth = imagesx($background);
    $bgHeight = imagesy($background);

    $cardWidth = 300;
    $cardHeight = 300;

    // Create a new true color image for the name card with transparency enabled
    $image = imagecreatetruecolor($cardWidth, $cardHeight);

    // Enable transparency for the name card
    imagealphablending($image, false);
    imagesavealpha($image, true);

    // Resize and copy the background image to fit the name card size
    imagecopyresampled($image, $background, 0, 0, 0, 0, $cardWidth, $cardHeight, $bgWidth, $bgHeight);

    // Load the logo image (with transparency)
    $logo = imagecreatefrompng($logoFile); // Use imagecreatefromjpeg() if your logo is a JPG
    $logoWidth = 50; // Define the logo width (adjust as needed)
    $logoHeight = 30; // Define the logo height (adjust as needed)

    // Resize the logo to fit the card
    $resizedLogo = imagecreatetruecolor($logoWidth, $logoHeight);

    // Enable transparency for the logo
    imagealphablending($resizedLogo, false);
    imagesavealpha($resizedLogo, true);

    // Copy the logo onto the resized logo image
    imagecopyresampled($resizedLogo, $logo, 0, 0, 0, 0, $logoWidth, $logoHeight, imagesx($logo), imagesy($logo));

    // Copy the logo onto the name card (position at the top-left corner)
    imagecopy($image, $resizedLogo, 10, 10, 0, 0, $logoWidth, $logoHeight);

    // Load the QR code image
    $qrImage = imagecreatefrompng($qrFileName);
    $qrSize = 150; // QR code size

    // Copy the QR code onto the name card
    imagecopyresampled($image, $qrImage, 80, 40, 0, 0, $qrSize, $qrSize, imagesx($qrImage), imagesy($qrImage));

    // Set text color
    $black = imagecolorallocate($image, 0, 0, 0);

    // Add text to the name card
    $fontPath = __DIR__ . '/arial.ttf'; // Path to a .ttf font file
    imagettftext($image, 18, 0, 130, 230, $black, $fontPath, $nama);
    imagettftext($image, 18, 0, 130, 260, $black, $fontPath, $password);

    // Save the final name card image
    imagepng($image, $cardFileName);

    // Clear memory
    imagedestroy($image);
    imagedestroy($qrImage);
    imagedestroy($background);
    imagedestroy($logo);
    imagedestroy($resizedLogo);

    // Display the name card image and download button
    echo '<div style="text-align: center; font-family: Arial, sans-serif;">';
    echo '<h2>Preview Name Card</h2>';
    echo '<img src="' . $cardFileName . '" alt="Name Card" style="border: 1px solid #ddd; border-radius: 10px;">';
    echo '<br><br>';
    echo '<a href="' . $cardFileName . '" download="namecard_' . $nama . '.png" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Download Name Card</a>';
    echo '</div>';
}
?>