<nav class="sidebar">
    <div class="sidebar-inner">
        <header class="sidebar-header">
            <div class="logo">
                <button type="button" class="sidebar-burger" onclick="toggleSidebar()"></button>
                <img src="../logo.png" class="sidebar-logo" />
            </div>
            <div class="profil">
                <button>
                    <img src="../<?= $_SESSION['user']; ?>png" alt="User" class="user-img">
                    <span class="user-nama"><?= $_SESSION['user']; ?></span>
                </button>
            </div>
        </header>
        <nav class="sidebar-menu">
            <button>
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#presensi">Presensi</a></span>
            </button>
            <button>
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#jadwal">jadwal</a></span>
            </button>
            <button>
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#materi">Materi</a></span>
            </button>
            <button>
                <span><a href="../logout.php">Log out</a></span>
            </button>
        </nav>
    </div>
</nav>
<script type="text/javascript">
    const toggleSidebar = () => document.body.classList.toggle("open");
</script>