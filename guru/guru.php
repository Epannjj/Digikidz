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
    <section id="materi">
        <h3>Materi</h3>
        <div class="table">
            <table border="1">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Program & level</th>
                    <th>Judul</th>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Hasil karya</th>
                </tr>
                <!-- Data presensi -->
                <tr>
                    <td>1</td>
                    <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequatur magnam reprehenderit
                        molestiae, eligendi ut recusandae, culpa cum eaque corrupti aliquid deserunt odit, unde ea
                        dolore eveniet nulla dicta nostrum provident!</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet odio provident qui? Maiores cum
                        blanditiis consequuntur sint dignissimos quidem quia nesciunt eaque deserunt suscipit
                        architecto, tenetur omnis magnam ex reiciendis.</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, temporibus quos laborum
                        odit recusandae similique modi aperiam eos sequi, aliquam expedita tempore ipsum ullam quam
                        earum, numquam inventore tempora quis!
                    </td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit id dolores fugit quaerat
                        eveniet rerum, aspernatur, itaque perferendis quibusdam maiores tempora, laboriosam dolore vero.
                        Et temporibus voluptatum nobis sunt quam.</td>
                    <td>2-2-222</td>
                    <td> <input type="button" value="Foto" style="width:60px;height: 80px;"><br>
                    </td>
                </tr>
            </table>
    </section>
</body>

</html>