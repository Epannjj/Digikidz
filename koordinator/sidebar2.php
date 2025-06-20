<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>koordinator</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #6a26cd;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        }

        .logo-container {
            padding: 20px 15px;
            background-color: #7830e0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            height: 60px;
            margin-bottom: 10px;
        }

        .profil {
            text-align: center;
        }

        .welcome-text {
            font-size: 14px;
            opacity: 0.8;
        }

        .username {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0 0;
        }

        .user-icon {
            height: 30px;
        }

        .menu-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            color: white;
        }

        .menu-item:hover {
            background-color: #5a1cb0;
        }

        .menu-item.active {
            background-color: #4a169a;
            border-left: 4px solid yellow;
        }

        .menu-item .icon img.active {
            opacity: 30%;
        }

        .menu-icon {
            margin-right: 15px;
            width: 20px;
            height: 20px;
        }

        .icon {
            color: yellow;
            font-size: 24px;
            display: inline-block;
            margin-right: 15px;
        }

        .icon img {
            width: 30px;
            height: 30px;
        }

        .menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            background-color: floralwhite;
            border: none;
            border-radius: 5px;
            padding: 10px;
            display: none;
        }

        .menu-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: #6a26cd;
            margin: 5px 0;
        }

        @media screen and (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                left: -250px;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 75%;
                height: 100%;
                display: none;
                z-index: 998;
            }

            .sidebar.open {
                display: block;
            }
        }
    </style>
</head>

<body>

    <button class="menu-toggle" aria-label="Toggle Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="sidebar">
        <div class="logo-container">
            <img src="../logo.png" alt="DigiKidz" class="logo">
            <div class="profil">
                <span class="welcome-text">Selamat Datang Mr/Ms </span>
                <h4 class="username"><?php echo $_SESSION['user']; ?></h4>
            </div>
        </div>
        <a href="karyawan.php" class="menu-item" data-page="karyawan">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Data karyawan</span>
        </a>

        <a href="siswa.php" class="menu-item" data-page="siswa">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Data siswa</span>
        </a>

        <a href="presensi.php" class="menu-item" data-page="presensi">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Hasil Presensi</span>
        </a>
        <!-- Belom fiks ada pa ga -->
        <a href="program.php" class="menu-item" data-page="program">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Program</span>
        </a>
        <a href="pembayaran.php" class="menu-item" data-page="pembayaran">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>pembayaran</span>
        </a>
        <a href="harga.php" class="menu-item" data-page="harga">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Harga Registrasi</span>
        </a>
        <a href="hasilkarya.php" class="menu-item" data-page="hasilkarya">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Hasil karya</span>
        </a>
        <a href="../logout.php" title="Logout" class="menu-item logout">
            <span class="icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M16 17L21 12L16 7" stroke="#333" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M21 12H9" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M12 19V20C12 21.1046 11.1046 22 10 22H5C3.89543 22 3 21.1046 3 20V4C3 2.89543 3.89543 2 5 2H10C11.1046 2 12 2.89543 12 4V5"
                        stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span>Logout</span>
        </a>
    </div>

    <script>
        function setActiveMenuItem() {
            const currentPage = window.location.pathname;
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                const itemHref = item.getAttribute('href');
                const itemPage = item.getAttribute('data-page');

                item.classList.remove('active');

                if (currentPage.includes(itemHref) || currentPage.includes(itemPage)) {
                    item.classList.add('active');
                }
            });

            if (!document.querySelector('.menu-item.active') &&
                (currentPage === '/' || currentPage.endsWith('index.html'))) {
                menuItems[0].classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setActiveMenuItem();

            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar-placeholder');
            const sidebarOverlay = document.querySelector('.sidebar');
            const menuItems = document.querySelectorAll('.menu-item');

            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('open');
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('open');
            });

            menuItems.forEach(item => {
                item.addEventListener('click', function () {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');

                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('open');
                        sidebarOverlay.classList.remove('open');
                    }
                });
            });
        });
    </script>
</body>

</html>