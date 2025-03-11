    document.addEventListener("DOMContentLoaded", function() {
        // Cek jika ada posisi scroll yang tersimpan
        if (sessionStorage.getItem("scrollPosition")) {
            window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
        }

        // Simpan posisi scroll sebelum halaman direfresh
        window.addEventListener("beforeunload", function() {
            sessionStorage.setItem("scrollPosition", window.scrollY);
        });
    });