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
            background-color: #f0f0f5;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        h2 {
            color: #333;
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
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #5b9bd5;
            outline: none;
        }

        a {
            border-style: solid;
            border-color: #5b9bd5;
            display: inline-block;
            margin: 5px 0;
            padding: 9px;
            border-radius: 10px;
            color: #5b9bd5;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        h3 {
            color: #5b9bd5;
        }
    </style>
</head>

<body>
    <h2>Halaman Login</h2>
    <form action="proses_login.php" method="post">
        <label for="username">Username / id :</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <a href="guru.php">Login</a><br>
    </form>
</body>
<script src="script.js"></script>

</html>