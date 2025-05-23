<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiKidz Sidebar Navigation</title>
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
        }

        .logo-container {
            padding: 20px;
            background-color: #7830e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            height: 40px;
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

        .logout {
            position: absolute;
            bottom: 20px;
            left: 20px;
            cursor: pointer;
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

            .logo-container .logo {
                opacity: 0;
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
                <a><span class="user-nama"><?= $_SESSION['user']; ?></span></a>
            </div>
        </div>

        <a href="guru.php" class="menu-item" data-page="presensi">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Presensi</span>
        </a>

        <a href="liatjadwal.php" class="menu-item" data-page="jadwal">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Jadwal</span>
        </a>

        <a href="materi.html" class="menu-item" data-page="materi">
            <span class="icon"><img src="../robot.png" alt="robot"></span>
            <span>Materi</span>
        </a>

        <div class="logout">
            <a href="../logout.php">Log out</a>
        </div>
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