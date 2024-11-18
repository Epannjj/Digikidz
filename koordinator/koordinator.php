<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>koor</title>
    <style>
        <?php
        include "../styles.css";
        ?>
    </style>
</head>

<body>
    <header class="sidebar">
        <?php
        include "sidebar2.php";
        ?>
    </header>
    <main>
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
                        <option value="Coding">Coding</option>
                        <option value="Art">Art</option>
                        <option value="Robotik">Robotik</option>
                    </select><br>
                    <label for="nama">Level</label><br>
                    <select name="level" id="level">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select><br>
                    <input type="submit" value="Simpan" name="simpan">
                </form>

                <?php
                include "../db.php";
                if (isset($_POST['simpan'])) {
                    $nama = $_POST['nama'];
                    $program = $_POST['program'];
                    $level = $_POST['level'];
                    $cekid = mysqli_query($db, "SELECT MAX(id_siswa) FROM siswa");
                    $ambilid = mysqli_fetch_array($cekid)[0];
                    $id = $ambilid + 1;
                    $sql = mysqli_query($db, "INSERT INTO siswa (id_siswa, nama, program, `level`) VALUES ('$id', '$nama', '$program', '$level')");
                    if ($sql) {
                        echo "Data siswa berhasil ditambahkan";
                    } else {
                        echo "Data gagal ditambahkan";
                    }
                }
                ?>
            </div>
            <div>
                <h3>Data Siswa</h3>
                <table border="1">
                    <tr>
                        <th>ID Siswa</th>
                        <th>Nama Siswa</th>
                        <th>Program</th>
                        <th>Level</th>
                        <th>Aksi</th>
                        <th>QR Code</th>
                    </tr>
                    <?php
                    $data = mysqli_query($db, "SELECT * FROM siswa");
                    while ($row = mysqli_fetch_array($data)) { ?>
                        <tr>
                            <td><?php echo $row['id_siswa'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['program'] ?></td>
                            <td><?php echo $row['level'] ?></td>
                            <td>
                                <a href="edit_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Edit</a> |
                                <a href="hapus_siswa.php?id_siswa=<?php echo $row['id_siswa'] ?>">Hapus</a>
                            </td>
                            <td>
                                <form action="../qrcode/generate_qr.php" method="post" target="_blank">
                                    <input type="hidden" name="nama" value="<?php echo $row['nama']; ?>">
                                    <input type="hidden" name="level" value="<?php echo $row['level']; ?>">
                                    <input type="hidden" name="program" value="<?php echo $row['program']; ?>">
                                    <input type="submit" value="Generate QR">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
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
                        <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequatur magnam
                            reprehenderit
                            molestiae, eligendi ut recusandae, culpa cum eaque corrupti aliquid deserunt odit,
                            unde ea
                            dolore eveniet nulla dicta nostrum provident!</td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet odio provident qui?
                            Maiores
                            cum
                            blanditiis consequuntur sint dignissimos quidem quia nesciunt eaque deserunt
                            suscipit
                            architecto, tenetur omnis magnam ex reiciendis.</td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, temporibus
                            quos
                            laborum
                            odit recusandae similique modi aperiam eos sequi, aliquam expedita tempore ipsum
                            ullam quam
                            earum, numquam inventore tempora quis!
                        </td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit id dolores fugit
                            quaerat
                            eveniet rerum, aspernatur, itaque perferendis quibusdam maiores tempora, laboriosam
                            dolore
                            vero.
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
        <section id="jadwal">
            <?php include "jadwal.php"; ?>
        </section>
    </main>
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
                    <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequatur magnam
                        reprehenderit
                        molestiae, eligendi ut recusandae, culpa cum eaque corrupti aliquid deserunt odit, unde
                        ea
                        dolore eveniet nulla dicta nostrum provident!</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet odio provident qui?
                        Maiores cum
                        blanditiis consequuntur sint dignissimos quidem quia nesciunt eaque deserunt suscipit
                        architecto, tenetur omnis magnam ex reiciendis.</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, temporibus quos
                        laborum
                        odit recusandae similique modi aperiam eos sequi, aliquam expedita tempore ipsum ullam
                        quam
                        earum, numquam inventore tempora quis!
                    </td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit id dolores fugit
                        quaerat
                        eveniet rerum, aspernatur, itaque perferendis quibusdam maiores tempora, laboriosam
                        dolore vero.
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
    <section id="jadwal">
        <?php include "jadwal.php"; ?>
    </section>
</body>

</html>