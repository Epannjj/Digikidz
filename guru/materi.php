<h3>Sortir Materi</h3>
<form method="get" action="">
    <label for="sort_program">Pilih Program:</label>
    <select name="sort_program" id="sort_program">
        <option value="">Semua Program</option>
        <option value="Program B">Program B</option>
        <option value="Program A">Program A</option>
    </select>

    <label for="sort_level">Pilih Level:</label>
    <select name="sort_level" id="sort_level">
        <option value="">Semua Level</option>
        <option value="Level 1">Level 1</option>
        <option value="Level 2">Level 2</option>
    </select>

    <input type="submit" value="Sortir">
</form>

<?php
include "../db.php";
$sort_program = isset($_GET['sort_program']) ? $_GET['sort_program'] : '';
$sort_level = isset($_GET['sort_level']) ? $_GET['sort_level'] : '';
$query = "SELECT * FROM materi WHERE 1=1";
if (!empty($sort_program)) {
    $query .= " AND program = '$sort_program'";
}
if (!empty($sort_level)) {
    $query .= " AND `level` = '$sort_level'";
}
$sql = mysqli_query($db, $query);
?>
<div
    style="width: 100%; max-height: 100vh; overflow-y: auto; margin-top: 10px; padding: 5px; box-shadow: steelblue 2px 2px 2px;">
    <table border="1">
        <tr>
            <th>ID Materi</th>
            <th>Nama Materi</th>
            <th>Program</th>
            <th>Level</th>
            <th>Pertemuan</th>
            <th>Modul</th>
        </tr>
        <?php while ($row = mysqli_fetch_array($sql)) { ?>
            <tr>
                <td><?php echo $row['id_materi']; ?></td>
                <td><?php echo $row['judul_materi']; ?></td>
                <td><?php echo $row['program']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td><?php echo $row['pertemuan']; ?></td>
                <td><a href="modul/<?php echo $row['modul']; ?>">Download</a></td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>