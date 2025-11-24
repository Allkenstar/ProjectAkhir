<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam Booth Login</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url("/mnt/data/ilustrasi project tekweb.jpeg") no-repeat center center/cover;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 80px;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.65);
            padding: 35px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
            backdrop-filter: blur(4px);
            color: white;
        }

        .login-container h2 {
            margin: 0;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #1e90ff;
            color: white;
            font-size: 17px;
            cursor: pointer;
            margin-top: 5px;
        }

        .login-container button:hover {
            background-color: #0c6bd8;
        }

        .register {
            color: #ffffff;
            display: block;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Steam Booth</h2>
        <p>Masuk ke akun Anda</p>

        <form>
            <input type="text" placeholder="Username" required>
            <input type="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>

        <a href="#" class="register">Belum punya akun? Daftar</a>
    </div>

</body>
</html>
