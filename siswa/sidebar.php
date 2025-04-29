<?php
include "../db.php";
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="profile">
        <img src="path-to-profile-image.jpg" alt="Profile" class="profile-img">
        <div class="profile-info">
            <?php if (isset($_SESSION['nama'])): ?>
                <span class="profile-name"><?= htmlspecialchars($_SESSION['nama']) ?></span>
            <?php else: ?>
                <span class="profile-name">Guest</span>
            <?php endif; ?>
            <span class="profile-role">Siswa</span>
        </div>
    </div>

    <ul class="nav-menu">
        <li class="nav-item <?= $current_page == 'siswa.php' ? 'active' : '' ?>">
            <a href="siswa.php">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item <?= $current_page == 'detail_histori.php' ? 'active' : '' ?>">
            <a href="detail_histori.php">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>History</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="../logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>