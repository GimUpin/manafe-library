
<?php
    include '../view/header.php';
    echo '<style>';
        include '../view/style1.css'; // Include nội dung của file CSS
    echo '</style>';
    session_start();
    // Upload ảnh
    if (isset($_FILES["avatar"])) {
        $folderPath = '../view/avatar_temp';
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        $avatarFile = $_FILES["avatar"]["name"];
        $avatarTemp = $_FILES["avatar"]["tmp_name"];
        $targetFile =__DIR__ . '/avatar/' . $avatarFile;
        $uploadOk = 1;
        $avatarFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if ($avatarTemp !== null) {
            $check = getimagesize($avatarTemp);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        

        // Check if the file already exists in the same path
        if (file_exists($targetFile)) {
            $uploadOk = 0;
        }

        // Check for uploaded file formats and allow only jpg, png, jpeg and gif
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

    } else {

        $avatarFile = $_POST["avatar"];
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
            
        }

        if (isset($_POST["confirmButton"])) {
                $avatarFile = ""; // Giả sử biến $avatarFile đã được xử lý từ tệp tải lên
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $current_datetime = date("Y-m-d H:i:s");

                // Check if a record with the same name, category, and author already exists
                $checkDuplicateSql = "SELECT COUNT(*) FROM BOOKS WHERE name = ? AND category = ? AND author = ?";
                $checkDuplicateStmt = mysqli_prepare($conn, $checkDuplicateSql);
                mysqli_stmt_bind_param($checkDuplicateStmt, "sss", $name, $category, $author);
                mysqli_stmt_execute($checkDuplicateStmt);
                mysqli_stmt_bind_result($checkDuplicateStmt, $count);
                mysqli_stmt_fetch($checkDuplicateStmt);
                mysqli_stmt_close($checkDuplicateStmt);

                if ($count > 0) {
                    // Duplicate record found, handle accordingly (e.g., show an error message)
                    echo '<script>';
                    echo 'Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            text: "Dữ liệu đã tồn tại trong cơ sở dữ liệu.",
                            confirmButtonText: "Quay về trang đăng ký"
                        }).then(function() {
                            window.location.href = "book_register.php";
                        });';
                    echo '</script>';
                } else {
                    // No duplicate found, proceed with the insertion
                    $sql = "INSERT INTO BOOKS (name, category, author, quantity, description, avatar, created, updated) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ssssssss", $name, $category, $author, $quantity, $description, $_SESSION["fileName"], $current_datetime, $current_datetime);

                    if (mysqli_stmt_execute($stmt)) {
                        // Hiển thị thông báo thành công bằng SweetAlert
                        echo '<script>';
                        echo 'Swal.fire({
                                icon: "success",
                                title: "Thành công",
                                text: "Bạn đã đăng ký thành công sách!",
                                confirmButtonText: "Quay lại trang chủ"
                            }).then(function() {
                                window.location.href = "home.php";
                            });';
                        echo '</script>';
                    } else {
                        echo "Lỗi: " . mysqli_error($conn);
                    }
    
                    mysqli_stmt_close($stmt);
                }
                session_destroy();
                    // // Close the statement
                    // mysqli_stmt_close($stmt);
                }
        ?>



        <form method="POST" action="" enctype="multipart/form-data">
            <div class='mb-3 row'>
                <span class='col-sm-2 col-form-label'>Tên sách</span>
                <div class='col-sm-5'>
                    <?php
                    echo $name;
                    echo "<input type='hidden' name='name' value='$name'>" 
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
                    echo "<img src='../view/avatar/$avatarFile' alt='Uploaded Image' class='img-fluid'>";
                    echo "<input type='hidden' name='avatar' value='$avatarFile'>" 
                    ?>
                </div>
                <div class='text-center'>
                    <button type="button" name='confirmButton' class='submit btn btn-success mb-3 pe-5 ps-5 mt-3' onclick="ReturnClick()">Quay lại </button>
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
