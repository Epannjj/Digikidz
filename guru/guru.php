<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guru</title>
    <style>
        <?php
        include "styles.css";
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
        <h3>Presensi</h3>
        <div class="formpresensi">
            <form action="" method="post">
                <label for="nama">Nama Siswa:</label><br>
                <input type="text" id="nama" name="nama" required><br>
                <!-- <label for="nama">Materi:</label><br>
                <input type="text" id="nama" name="nama" required><br> -->
                <select name="" id="">
                    <option value="">Materi</option>
                    <option value=""></option>
                    <option value="">3</option>
                    <option value="">3</option>
                    <option value="">3</option>
                </select><br>
                <input type="button" value="Foto" style="width:60px;height: 80px;"><br>
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