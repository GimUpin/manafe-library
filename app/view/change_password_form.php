<!-- change_password_form.php -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    form {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 300px;
        margin-top: 20px; /* Add margin to separate the header row from the form */
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #3498db;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<!-- Form -->
<form method="post" action="../controller/change_password.php">
    <?php
    session_start();
    $loginId = $_SESSION['login_id'];
    session_destroy();
    ?>
    <label for="login_id">Login ID:</label>
    <input type="text" name="login_id" value="<?php echo $loginId; ?>" readonly>

    <label for="new_password">Mật khẩu mới:</label>
    <input type="password" name="new_password" required minlength="6">

    <button type="submit">Reset</button>
</form>
