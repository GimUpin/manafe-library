<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 400px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        margin: 50px auto 0;
    }

    .login-form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 8px;
    }

    input {
        padding: 8px;
        margin-bottom: 16px;
    }

    button {
        padding: 10px;
        background-color: #3498db;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }

    button:hover {
        background-color: #2980b9;
    }

    .error {
        color: #e74c3c;
        margin-top: 5px;
    }
    </style>
<body>

<h1>LOGIN</h1>
<div class="container">
    <form action="./app/controller/LoginController.php" method="post" class="login-form">

        <label for="login_id">Người dùng</label>
        <input id="login_id" type="text" name="login_id" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <?php
        // Display errors
        if (!empty($errors)) {
            echo "<div class='error'>";
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            echo "</div>";
        }
        ?>
        <a href="reset_password_form.php">Quên password</a>
        <button type="submit">Đăng nhập</button>

    </form>
</div>
</body>
</html>
