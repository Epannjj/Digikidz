<?php
// Debug: Tampilkan semua data yang diterima
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- DEBUG INFO -->";
echo "<!-- POST data: " . print_r($_POST, true) . " -->";
echo "<!-- FILES data: " . print_r($_FILES, true) . " -->";

include "../db.php";

$id_presensi = null;
$upload_success = false;
$error_message = "";

// Cek apakah ada ID yang dikirim
if (isset($_POST['id_presensi']) && !empty($_POST['id_presensi'])) {
    $id_presensi = $_POST['id_presensi'];
    echo "<!-- ID Presensi diterima: $id_presensi -->";
}

// Proses upload foto jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
    $id_presensi = $_POST['id_presensi'];
    $photo = $_FILES['photo'];

    echo "<!-- Memproses upload untuk ID: $id_presensi -->";

    // Cek error upload
    if ($photo['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_types)) {
            $filename = time() . "_" . $id_presensi . "." . $ext;
            $upload_dir = "../uploads/";

            // Buat direktori jika belum ada
            if (!is_dir($upload_dir)) {
                if (mkdir($upload_dir, 0777, true)) {
                    echo "<!-- Direktori uploads berhasil dibuat -->";
                } else {
                    echo "<!-- Gagal membuat direktori uploads -->";
                }
            }

            $target_file = $upload_dir . $filename;

            echo "<!-- Target file: $target_file -->";

            if (move_uploaded_file($photo['tmp_name'], $target_file)) {
                // Update database
                $stmt = $db->prepare("UPDATE hasil_presensi SET hasil_karya = ? WHERE id_presensi = ?");
                if ($stmt) {
                    $stmt->bind_param("ss", $filename, $id_presensi);
                    if ($stmt->execute()) {
                        $upload_success = true;
                        echo "<!-- Database berhasil diupdate -->";
                    } else {
                        $error_message = "Gagal update database: " . $stmt->error;
                        echo "<!-- Error database: " . $stmt->error . " -->";
                    }
                    $stmt->close();
                } else {
                    $error_message = "Gagal prepare statement: " . $db->error;
                }
            } else {
                $error_message = "Gagal move uploaded file ke: $target_file";
                echo "<!-- Error move file -->";
            }
        } else {
            $error_message = "Format file tidak didukung: $ext";
        }
    } else {
        $error_message = "Upload error code: " . $photo['error'];
    }
}

// Ambil data hasil karya yang sudah ada (jika ada)
$existing_photo = null;
if ($id_presensi) {
    $stmt = $db->prepare("SELECT hasil_karya FROM hasil_presensi WHERE id_presensi = ?");
    if ($stmt) {
        $stmt->bind_param("s", $id_presensi);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $existing_photo = $row['hasil_karya'];
        }
        $stmt->close();
    }
}
?>

<div style="padding: 10px;">
    <?php if ($upload_success): ?>
        <div
            style="color: green; margin-bottom: 15px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
            ✅ Foto berhasil diupload!
        </div>
        <script>
            // Refresh parent window setelah 2 detik
            setTimeout(function () {
                if (window.parent) {
                    window.parent.location.reload();
                }
            }, 2000);
        </script>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div
            style="color: red; margin-bottom: 15px; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;">
            ❌ Error: <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($existing_photo && file_exists("../uploads/" . $existing_photo)): ?>
        <div style="margin-bottom: 15px;">
            <p><strong>Foto yang sudah ada:</strong></p>
            <img src="../uploads/<?php echo htmlspecialchars($existing_photo); ?>"
                style="max-width: 200px; border: 1px solid #ddd; border-radius: 5px;">
            <p style="margin-top: 10px;">Ingin mengganti dengan foto baru?</p>
        </div>
    <?php else: ?>
        <p><strong>Belum ada foto. Silakan upload:</strong></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" style="margin-top: 15px;">
        <input type="hidden" name="id_presensi" value="<?php echo htmlspecialchars($id_presensi); ?>">

        <div style="margin-bottom: 15px;">
            <label for="photo" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Pilih Foto:
            </label>
            <input type="file" id="photo" name="photo" accept="image/*" required style="margin-bottom: 5px;">
            <br>
            <small style="color: #666;">Format: JPG, JPEG, PNG, GIF</small>
        </div>

        <button type="submit"
            style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            📤 Upload Foto
        </button>
    </form>

    <p style="margin-top: 15px; font-size: 12px; color: #666;">
        ID Presensi: <?php echo htmlspecialchars($id_presensi); ?>
    </p>
</div>