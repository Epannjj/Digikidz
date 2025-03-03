<?php
if (isset($_POST['id'])) {
    echo $id = $_POST['id'];
}
?>
<input type="file" name="photo" accept="image/*" capture="camera" required>
<br><br>