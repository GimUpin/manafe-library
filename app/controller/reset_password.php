<!-- reset_password.php -->
<?php
require_once '../model/UserModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $loginId = $_POST["login_id"];

    // Validate login_id
    $errors = [];
    if (empty($loginId)) {
        $errors[] = "Hãy nhập login id.";
    } elseif (strlen($loginId) < 4) {
        $errors[] = "Login id phải có ít nhất 4 ký tự.";
    } elseif (!UserModel::loginIdExists($loginId)) {
        $errors[] = "Login id không tồn tại trong hệ thống.";
    }

    if (empty($errors)) {
        // Generate reset token and update DB
        $resetToken = microtime(true);
        UserModel::updateResetToken($loginId, $resetToken);
        $_SESSION['login_id'] = $loginId;
        // Redirect to login page
        header("Location: ../view/change_password_form.php");
        exit;
    }
include "../../view/reset_password_form.php";
}
?>
