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
            justify-content: start;
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

        .form {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            gap: 40px;
            position: relative;
            width: 100%;
            max-width: 600px;
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
            color: #5b9bd5;
        }

        img {
            height: 70px;
        }

        /* Tambahan style untuk toggle */
        .toggle-buttons {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .toggle-btn {
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background-color: white;
            color: #9425b3;
            font-weight: bold;
        }

        .guru,
        .siswa {
            transition: all 0.5s ease;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            visibility: hidden;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .visible {
            opacity: 1;
            visibility: visible;
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
    <div class="logo">
        <img src="logo.png" alt="logo" srcset="">
    </div>

    <div class="toggle-buttons">
        <button class="toggle-btn active" id="guruBtn">Guru</button>
        <button class="toggle-btn" id="siswaBtn">Siswa</button>
    </div>

    <div class="form">
        <div class="guru visible" id="guruForm">
            <?php
            include "login.php"; ?>
        </div>
        <div class="siswa" id="siswaForm">
            <div class="guru visible" id="guruForm">
                <?php
                include "logins.php"; ?>
            </div>
        </div>
    </div>

    <script>
        // Get the elements
        const guruBtn = document.getElementById('guruBtn');
        const siswaBtn = document.getElementById('siswaBtn');
        const guruForm = document.getElementById('guruForm');
        const siswaForm = document.getElementById('siswaForm');

        // Add event listeners
        guruBtn.addEventListener('click', function () {
            // Show guru form, hide siswa form
            guruForm.classList.add('visible');
            siswaForm.classList.remove('visible');

            // Update button states
            guruBtn.classList.add('active');
            siswaBtn.classList.remove('active');
        });

        siswaBtn.addEventListener('click', function () {
            // Show siswa form, hide guru form
            siswaForm.classList.add('visible');
            guruForm.classList.remove('visible');

            // Update button states
            siswaBtn.classList.add('active');
            guruBtn.classList.remove('active');
        });
    </script>
</body>

</html>