<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="../qrcode/html5-qrcode.min.js"></script>
    <title>Guru</title>
    <style>
        <?php
        include "../styles.css";
        ?>
    </style>
</head>

<body>
    <div class="sidebar">
        <?php
        include "sidebar.php";
        ?>
    </div>
    <p>
    <H2>Halaman Guru</H2>
    </p>
    <section id="presensi">
        <?php include "../qrcode/scan.php";
        ?>
    </section>
    <section id="jadwal">
        <?php include "liatjadwal.php"; ?>
    </section>
    <section id="materi">
        <?php include "materi.php"; ?>
    </section>
</body>

</html>