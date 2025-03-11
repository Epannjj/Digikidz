<style>
.pagination {
    margin: 20px 0;
    text-align: center;
}

.pagination a {
    display: inline-block;
    padding: 10px 15px;
    margin: 5px;
    text-decoration: none;
    color: black;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 5px;
    transition: 0.3s;
}

.pagination a.active {
    background: rgba(0, 0, 0, 0.7);
    color: white;
}

.pagination a:hover {
    background: rgba(255, 255, 255, 0.7);
}
</style>
<?php
$limit = 3; // Maksimal 10 data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$no = $start + 1;
$no++
?>
