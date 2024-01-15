<?php
    session_start();

    // Hủy bỏ session và chuyển hướng về trang login
    session_destroy();
    header("Location: ../../index.php");
    exit();
?>
