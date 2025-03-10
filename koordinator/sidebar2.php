<nav class="sidebar">
    <div class="sidebar-inner">
        <header class="sidebar-header">
            <button type="button" class="sidebar-burger" onclick="toggleSidebar()"></button>
            <img src="../logo.png" class="sidebar-logo" />
        </header>
        <nav class="sidebar-menu">
            <button href="#presensi">
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#karyawan">karyawan</a></span>
            </button>
            <button href="#presensi">
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#siswa">siswa</a></span>
            </button>
            <button href="#presensi">
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#liat">Liat Presensi</a></span>
            </button>
            <button href="#presensi">
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#materi">Tambah Materi</a></span>
            </button>
            <button href="#jadwal">
                <img src="../pisang.png" alt="a" srcset="">
                <span><a href="#jadwal">Buat jadwal</a></span>
            </button>
            <button href="#presensi">
                <span><a href="../logout.php">Log out</a></span>
            </button>
        </nav>
    </div>
</nav>
<script type="text/javascript">
    const toggleSidebar = () => document.body.classList.toggle("open");
</script>