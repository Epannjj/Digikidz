<!DOCTYPE html>
<html>

<head>
    <title>Siswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        <?php include "siswa.css"; ?>
    </style>
</head>

<body>
    <?php
    include "sidebar.php"; ?>
    <main>
        <h2 style="margin-left:15px;">Dashboard siswa</h2>
        <div class="container">
            <div class="menu">
                <div class="conten progres">
                    <?php include "progres.php"; ?>
                </div>
                <div class="conten pembayaran">
                    <!-- tabel -->
                    <h2>Riwayat Pembayaran</h2>
                    <?php include "pembayaran.php"; ?>
                </div>
            </div>
            <div class="history">
                <h2>Riwayat Presensi</h2>
                <?php include "histori.php"; ?>
            </div>
        </div>
    </main>
</body>
<script>
</script>

</html>