<?php 
include '../view/header.php'?>

<body>
    <div class="container mt-5">
        <form method="POST" enctype="multipart/form-data">
            <?php
            include_once '../controller/user_add_edit.php';
            include_once '../model/UserModel.php';
            $username = $_SESSION["username"];
            $user_type = $_SESSION["user_type"];
            $user_id = $_SESSION["user_id"];
            $description = $_SESSION["description"];
            $target_file = $_SESSION["target_file"];
            echo "
                <table class='table table-borderless'>
                    <tbody>
                        <tr>
                            <th scope='row'>Họ và tên</th>
                            <td>$username</td>
                        </tr>
                        <tr>
                            <th scope='row'>Phân loại</th>
                            <td>$user_type</td>
                        </tr>
                        <tr>
                            <th scope='row'>ID</th>
                            <td>$user_id</td>
                        </tr>
                        <tr>
                            <th scope='row'>Avatar</th>
                            <td>
                            <div class='avatar_container'>
                              <img src='$target_file' class='img-fluid rounded border' alt='avatar'>
                            </div>
                          </td>
                        </tr>
                        <tr>
                            <th scope='row'>Mô tả thêm</th>
                            <td><textarea class='form-control' disabled>$description</textarea></td>
                        </tr>
                    </tbody>
                </table>
                <div class='form-group text-center'>
                <div class='d-flex justify-content-center'>
                    <form method='POST'>
                        <input type='hidden' name='username' value='$username'>
                        <input type='hidden' name='user_type' value='$user_type'>
                        <input type='hidden' name='user_id' value='$user_id'>
                        <input type='hidden' name='description' value='$description'>
                        <input type='hidden' name='avatar' value='$target_file'>
                        <input type='submit' class='btn btn-primary btn-lg mr-2' value='Trở lại' name='back'>
                    </form>
                    <input type='submit' class='btn btn-primary btn-lg' id='registration-form' value='Đăng kí' name='confirm'>
                </div>
            </div>";

            ?>
        </form>
    </div>
</body>

</html>