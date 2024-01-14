<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng tới trang index.php
if(!isset($_COOKIE["login_id"])) {
    header("Location: index.php");
    exit();
}
$login_name = $_COOKIE["login_id"];
date_default_timezone_set('Asia/Ho_Chi_Minh');
$login_time = date("Y-m-d H:i");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            color:black;
            padding: 10px;
            text-align: center;
        }

        .container {
            border: 1px solid black;
            padding: 30px;
            margin: 30px;
            max-width: 800px;
            margin: 0 auto;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            float: left;
        }
        .logout-container{
            float: right;
        }
        .section-container {
            white-space: nowrap;
        }

        .section {
            margin-top: 20px;
            display: inline-block;
            margin-right: 100px;
        }

        .section h3 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .link-button a {
            display: block;
            padding: 5px 10px;
            /*text-decoration: none;*/
            border-radius: 3px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<h1>HOME</h1>
<div class="container">

    <header>
        <div class="logout-container">
            <a href="logout.php">Logout</a> </div>
        <h2>Tên login: <?php echo $login_name ?></h2>
        <p>Thời gian login:<?php echo $login_time ?></p>
    </header>

    <div class="section-container">
        <div class="section">
            <h3>Người dùng</h3>
            <div class="link-button">
                <a href="search_user.php">Tìm kiếm</a>
                <a href="user_add_input.php">Thêm mới</a>
                <a href="User_LichSuMuon.php">Lịch sử mượn</a>
            </div>
        </div>

        <div class="section">
            <h3>Sách</h3>
            <div class="link-button">
                <a href="book_list.php">Tìm kiếm </a>
                <a href="book_register.php">Thêm mới</a>
                <a href="AdvancedSearchView.php">Sổ cái mượn trả sách</a>
            </div>
        </div>

        <div class="section">
            <h3>Sách Mượn/Trả sách</h3>
            <div class="link-button">
                <a href="MuonSachView.php">Mượn sách</a>
                <a href="Sach_Lichsumuon-view.php">LichsumuonSach</a>
            </div>
        </div>
    </div>

</div>
</body>
</html>
