<!-- change_password.php -->
<?php
require_once '../model/UserModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginId = $_POST["login_id"];
    $newPassword = $_POST["new_password"];

    // Validate new password
    $errors = [];
    if (empty($newPassword)) {
        $errors[] = "Hãy nhập mật khẩu mới.";
    } elseif (strlen($newPassword) < 6) {
        $errors[] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    }

    if (empty($errors)) {
        // Get login_id from the URL or session
        $reset_token = UserModel::getLoginIdByResetToken($loginId);

        if ($reset_token) {
            // Update password in DB
            UserModel::updatePassword($loginId, md5($newPassword));

            // Clear reset_token in DB
            UserModel::clearResetToken($loginId);

            // Redirect to login page
            header("Location: ../view/index.php");
            exit;
        }
    }
}
?>
