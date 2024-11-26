<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: rgb(148, 37, 179);
            background: linear-gradient(90deg, rgba(148, 37, 179, 1) 0%, rgba(148, 37, 179, 1) 35%, rgba(68, 131, 129, 1) 100%);
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 250px;
            text-align: center;
        }

        h2 {
            color: whitesmoke;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #666;
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            box-shadow: #5b9bd5 5px 5px 5px;

        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #5b9bd5;
            outline: none;
            box-shadow: #666 3px 3px 3px;

        }

        input[type="submit"] {
            border-style: solid;
            border-color: #5b9bd5;
            display: inline-block;
            margin: 5px 0;
            padding: 9px;
            border-radius: 10px;
            color: #5b9bd5;
            text-decoration: none;
        }

        input[type="submit"]:active {
            color: floralwhite;
            background-color: #5b9bd5;
        }

        h3 {
            color: floralwhite;
        }

        img {
            height: 70px;
        }

        @media (max-width: 600px) {
            body {
                padding: 30px;
            }

            form {
                width: 100%;
                margin: 30px;
            }
        }
    </style>
</head>

<body>
    <img src="logo.png" alt="logo" srcset="">
    <h2>Halaman Login</h2>
    <form action="" method="post">
        <label for="username">Username / id :</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" name="login" value="Login"><br>
    </form>
    <!-- proses login -->
    <?php
    include 'db.php';
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = mysqli_query($db, "SELECT * from karyawan where username = '$username' AND `password` = '$password'");
        $ambil = mysqli_fetch_array($query);
        if ($username == $ambil['username'] && $password == $ambil['password']) {
            if ($ambil['jabatan'] == 'admin') {
                header("location: koordinator/koordinator.php");
            } elseif ($ambil['jabatan'] == 'guru') {
                header("location: guru/guru.php");
            }
        } else {
            echo "<h3>Username atau Password Salah</h3>";
        }
    }
    ?>
</body>
<script src="script.js"></script>

</html>