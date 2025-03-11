<?php
include "db.php";
if (isset($_POST['id'])) {
    echo $id = $_POST['id'];

    // Ambil data foto berdasarkan id siswa
    $stmt = $db->prepare("SELECT hasil_karya FROM hasil_presensi WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Tampilkan foto jika ada
    if (!empty($row['foto'])) {
        echo "<img src='uploads/" . $row['foto'] . "' width='200' height='200'><br><br>";
    } else {
        echo "<p>Masukan foto hasil karya di sini</p>";
    }
}

// Proses unggah foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
    $ext = pathinfo($photo['name'], PATHINFO_EXTENSION); // Ambil ekstensi file
    $filename = time() . "_$id." . $ext; // Format nama file: timestamp_id.ekstensi
    $target_dir = "uploads/";
    $target_file = $target_dir . $filename;

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($photo['tmp_name'], $target_file)) {
        // Simpan id file ke database
        $stmt = $db->prepare("UPDATE hasil_presensi SET hasil_karya = ? WHERE id = ?");
        $stmt->bind_param("ss", $filename, $id);
        $stmt->execute();
        echo "Foto berhasil diunggah!";
        echo "<br><img src='$target_file' width='200' height='200'>";
    } else {
        echo "Gagal mengunggah foto.";
    }
}

$db->close();
?>

<!-- Form Upload Foto -->
<form action="foto.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <input type="file" name="photo" accept="image/*" capture="camera" required>
    <br><br>
    <input type="submit" value="Upload Foto">
</form>
