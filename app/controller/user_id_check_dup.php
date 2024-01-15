<?php
        $user_id = $_POST['user_id'];
        $conn = new mysqli("localhost", "root", "", "library");

        // Thực hiện truy vấn kiểm tra trùng
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();

        if ($result > 0) {
            echo 'duplicate';
        } else {
            echo 'not_duplicate';
        }
?>
