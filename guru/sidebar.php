<nav class="sidebar">
    <div class="sidebar-inner">
        <header class="sidebar-header">
            <button type="button" class="sidebar-burger" onclick="toggleSidebar()"></button>
            <img src="../logo.png" class="sidebar-logo" />
        </header>
        <nav class="sidebar-menu">
            <button>
                <img src="../pisang.png" alt="a" srcset="">
                <span><a>Presensi</a></span>
            </button>
            <button>
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#liat">Liat Presensi</a></span>
            </button>
            <button>
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#materi">Materi</a></span>
            </button>
            <button>
                <span><a href="./index.php">Log out</a></span>
            </button>
        </nav>
    </div>
</nav>
<script type="text/javascript">
    const toggleSidebar = () => document.body.classList.toggle("open");
</script>