<?php
include "../db.php";
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Ambil data foto berdasarkan id siswa
    $stmt = $db->prepare("SELECT hasil_karya FROM hasil_presensi WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmts = false;

    // Tampilkan foto jika ada
if (!empty($row['hasil_karya']) && file_exists("../uploads/" . $row['hasil_karya'])) {
        echo "<p>Hasil karya sudah diunggah:</p>";
        echo "<img src='../uploads/" . $row['hasil_karya'] . "' width='200'><br><br>";
    } else {
        echo "<p>Belum ada hasil karya. Silakan upload:</p>";
    }
}

// Proses unggah foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $id = $_POST['id'];
    $photo = $_FILES['photo'];
    $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $filename = time() . "_$id." . $ext;
    $target_dir = realpath(__DIR__ . '/../uploads') . '/';
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($photo['tmp_name'], $target_file)) {
        $stmts = $db->prepare("UPDATE hasil_presensi SET hasil_karya = ? WHERE id = ?");
        $stmts->bind_param("ss", $filename, $id);
        $stmts->execute();

        echo "Foto berhasil diunggah!<br>";
        echo "<img src='../uploads/" . $filename . "' width='200'>";
    } else {
        echo "Gagal mengunggah foto.";
    }
}
if ($stmts) {
    header("Location: /Digikidz/koordinator/presensi.php");
exit();
}
$db->close();
?>

<form action='foto.php' method='post' enctype='multipart/form-data'>
    <input type='hidden' name='id' value='<?php echo htmlspecialchars($id); ?>'>
    <input type='file' name='photo' accept='image/*' capture='camera' required><br><br>
    <input type='submit' value='Upload Foto'>
</form>
