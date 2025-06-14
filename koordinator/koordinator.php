<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>koor</title>
    <style>
        <?php
        // session_start();
        include "../styles.css";
        ?>
    </style>
</head>

<body>
    <header class="sidebar">
        <?php
        include "sidebar2.php";
        ?>
    </header>
    <section id="karyawan">
        <?php include "karyawan.php"; ?>
    </section>
    <section id="siswa">
        <?php include "siswa.php"; ?>
    </section>
    <section id="liat">
        <?php include "presensi.php"; ?>
    </section>
    <section id="materi">
        <?php
        include "materi.php";
        ?>
    </section>
    <section id="jadwal">
        <?php include "jadwal.php"; ?>
    </section>
    <section id="pembayaran">
        <?php include "pembayaran.php"; ?>
    </section>
    <section id="harga">
        <?php include "harga.php"; ?>
    </section>
    <section id="hasilkarya">
        <?php include "hasilkarya.php"; ?>
    </section>
</body>

</html>
<script src="../jsrefresh.js"></script>