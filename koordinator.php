<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>koor</title>
    <style>
        <?php
        include "styles.css";
        ?>
    </style>
</head>

<body>
    <div class="sidebar">
        <?php
        include "sidebar2.php";
        ?>
    </div>
    <section id="karyawan">
        <h3>Input Data Karyawan</h3>
        <div class="formpresensi">
            <form action="" method="post">
                <label for="nama">Nama karyawan:</label><br>
                <input type="text" id="nama" name="nama" required><br>
                <label for="nama">Password:</label><br>
                <input type="text" id="password" name="password" required><br>
                <input type="button" value="Submit">
                <!-- php -->
            </form>
        </div>
    </section>
    <section id="siswa">
        <h3>Input Data Siswa</h3>
        <div class="formpresensi">
            <form action="" method="post">
                <label for="nama">Nama Siswa:</label><br>
                <input type="text" id="nama" name="nama" required><br>
                <label for="nama">Program</label><br>
                <select name="program" id="program">
                    <option value="">1</option>
                    <option value="">2</option>
                    <option value="">3</option>
                    <option value="">4</option>
                </select><br>
                <label for="nama">level</label><br>
                <select name="level" id="level">
                    <option value="">1</option>
                    <option value="">2</option>
                    <option value="">3</option>
                    <option value="">4</option>
                </select><br>
                <!-- <input type="file" name="foto" id="foto"><br> -->
                <input type="button" value="Submit">
            </form>
        </div>
    </section>
    <section id="liat">
        <h3>Hasil presensi </h3>
        <div class="table">
            <table border="1">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>program & level</th>
                    <th>materi</th>
                    <th>pertemuan</th>
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
        </div>
    </section>
    <section id="materi">
        <h3>Materi</h3>
        <div class="formpresensi">
            <form action="" method="post">
                <label for="nama">Nama Materi:</label><br>
                <input type="text" id="nama_materi" name="nama_materi" required><br>
                <label for="nama">program:</label><br>
                <input type="text" id="program" name="program" required><br>
                <label for="nama">level:</label><br>
                <input type="text" id="level" name="level" required><br>
                <label for="nama">pertemuan:</label><br>
                <input type="text" id="pertemuan" name="pertemuan" required><br>
                <label for="nama">modul:</label><br>
                <input type="file" id="modul" name="modul" required><br>
                <input type="button" value="Submit">
            </form>
        </div>
    </section>
</body>

</html>