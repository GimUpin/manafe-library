<?php
    include '../view/header.php';
    echo '<style>';
        include '../view/style1.css'; // Include nội dung của file CSS
    echo '</style>';
    session_start();

    $avatarFile = ""; // Initialize $avatarFile
    
    // Upload ảnh
    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["name"] !== "") {
        $folderPath = '../view/avatar_temp';
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $avatarFile = $_FILES["avatar"]["name"];
        $avatarTemp = $_FILES["avatar"]["tmp_name"];
        $targetFile = '../view/avatar/' . $avatarFile;
        $uploadOk = 1;
        $avatarFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($avatarTemp);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    
        // Check if the file already exists in the same path
        if (file_exists($targetFile)) {
            $uploadOk = 0;
        }
    
        // Check for uploaded file formats and allow only jpg, png, jpeg, and gif
        if ($avatarFileType != "jpg" && $avatarFileType != "png" && $avatarFileType != "jpeg" && $avatarFileType != "gif") {
            $uploadOk = 0;
        }
    
        if ($uploadOk == 1) {
            if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == 0) {
                move_uploaded_file($avatarTemp, $targetFile);
            }
        }
        $_SESSION["targetFile"] = $targetFile;
        $_SESSION["fileName"] = $avatarFile;
        $_SESSION["ID"] = $_POST["id"];
    } else {
        $avatarFile = $_SESSION["img_path"];
        $_SESSION["fileName"] = $avatarFile;
    }
    
    ?>
   
    <div class="container">
        <div class="text-center">
            <h4>
                XÁC NHẬN THÔNG TIN SÁCH
            </h4>
        </div>
        <?php
        include '../connection.php';
        mysqli_query($conn, "USE library");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $author = mysqli_real_escape_string($conn, $_POST['author']);
            $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $avatarFile = $_SESSION["fileName"];
            $_SESSION["ID"] = $_POST["id"];
            $_SESSION["img_path"] = $_SESSION["fileName"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmButton"])) {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $current_datetime = date("Y-m-d H:i:s");
                $id = $_SESSION["ID"];
                // echo "test id: ". $id;
                // Sử dụng prepared statements để tránh SQL injection
                $sql = "UPDATE `books` SET `name` = ?, `category` = ?, `author` = ?, `quantity` = ?, `description` = ?, `avatar` = ?, `updated` = ?
                        WHERE `id` = ?";


                $stmt = mysqli_prepare($conn, $sql);

                $avatar = $_SESSION["fileName"];
                mysqli_stmt_bind_param($stmt, "sssssssi", $name, $category, $author, $quantity, $description, $avatar, $current_datetime, $id);

                if (mysqli_stmt_execute($stmt)) {
                    // Hiển thị thông báo thành công bằng SweetAlert
                    echo '<script>';
                    echo 'Swal.fire({
                            icon: "success",
                            title: "Thành công",
                            text: "Bạn đã sửa thành công sách!",
                            confirmButtonText: "Quay lại trang tìm kiếm!"
                        }).then(function() {
                            window.location.href = "../view/home.php";
                        });';
                    echo '</script>';
                } else {
                    echo "Lỗi: " . mysqli_error($conn);
                }
        }
        ?>



        <form method="POST" action="" enctype="multipart/form-data">
            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label'>Tên sách</span>
                <div class='col-sm-5'>
                    <?php
                    echo $name;
                    echo "<input type='hidden' name='id' id='id' class='d-none' value='" . $_SESSION['ID'] . "'>";
                    echo "<input type='hidden' name='name' value='$name'>";
                    ?>  
                </div>
            </div>

            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label'>Thể loại</span>
                <div class='col-sm-5'>
                    <?php
                    echo $category;
                    echo "<input type='hidden' name='category' value='$category'>" 
                    ?>
                </div>
            </div>

            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label'>Tác giả</span>
                <div class='col-sm-5'>
                    <?php
                    echo $author;
                    echo "<input type='hidden' name='author' value='$author'>" 
                    ?>
                </div>
            </div>

            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label'>Số lượng</span>
                <div class='col-sm-5'>
                    <?php
                    echo $quantity;
                    echo "<input type='hidden' name='quantity' value='$quantity'>" 
                    ?>
                </div>
            </div>

            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label'>Mô tả</span>
                <div class='col-sm-5'>
                    <?php
                    echo $description;
                    echo "<input type='hidden' name='description' value='$description'>"
                    ?>
                </div>
            </div>

            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label' style='height:30%'>Hình ảnh</span>
                <div class='col-sm-5'>
                    <?php
                    echo "<img src='avatar/$avatarFile' alt='Uploaded Image' class='img-fluid'>";
                    echo "<input type='hidden' name='img_path' value='$avatarFile'>" 
                    ?>
                </div>
                <div class='text-center'>
                    <button type="button" name='backButton' class='submit btn btn-success mb-3 pe-5 ps-5 mt-3' onclick="ReturnClick()">Quay lại </button>
                    <button type='submit' name='confirmButton' class='submit btn btn-success mb-3 pe-5 ps-5 mt-3'>Xác nhận</button>
                </div>
            </div>
        </form>
    </div>
    
    <script src="../booklist_handle.js"></script>
    <script>
        function ReturnClick(){
            history.back();

        }
    </script>

</html>
