<?php
include '../view/header.php';
include '../model/UserModel.php';
include '../common/connection.php';
class user_add {
    public function add_user() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
            $username = $_POST["user_name"];
            $selected_type = $_POST['type'][0];
            if ($selected_type == 1) {
                $user_type = 'Giáo viên';
            } elseif ($selected_type == 2) {
                $user_type = 'Sinh viên';
            }
            $user_id = $_POST["ID"];
            $description = $_POST["description"];
    
            $tempDirectory = "../../web/avatar/tmp/";
            $mainDirectory = "../../web/avatar/";
    
            if (!file_exists($mainDirectory)) {
                mkdir($mainDirectory, 0777, true);
            }
            if ($_FILES["image"]["error"] == 0) {
                $tempFilePath = $tempDirectory . $_FILES["image"]["name"];
                $displayMainFilePath = '../../web/avatar/tmp/' . $_FILES["image"]["name"];
                move_uploaded_file($_FILES["image"]["tmp_name"], $tempFilePath);
                    session_start();
                    $_SESSION["username"] = $username;
                    $_SESSION["selected_type"] = $selected_type;
                    $_SESSION["user_type"] = $user_type;
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["description"] = $description;
                    $_SESSION["target_file"] = $displayMainFilePath;  
                    header("Location: user_add_confirm.php");
                    exit();
            }
            else {
                echo "Có lỗi xảy ra khi tải lên hình ảnh.";
            }
        }
    }
    public function confirm_add_user() {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm"])) {
    
            $tempDirectory = "../../web/avatar/tmp/";
            $mainDirectory = "../../web/avatar/";
    
            if (!file_exists($mainDirectory)) {
                mkdir($mainDirectory, 0777, true);
            }
            $fileName = basename($_SESSION["target_file"]);
            $tempFilePath = $tempDirectory . $fileName;
            $mainFilePath = $mainDirectory . $_SESSION["user_id"] . "/" . $fileName;
            $displayMainFilePath = '../../web/avatar/'.$_SESSION["user_id"]. '/'. $fileName;
            mkdir($mainDirectory . $_SESSION["user_id"], 0777, true);
            rename($tempFilePath, $mainFilePath);
            $files = scandir($tempDirectory);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $filePath = $tempDirectory . '/' . $file;
                    if (is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            $_SESSION["target_file"] = $displayMainFilePath;
            $user_model = new UserModel();
            $user_model->add_user();

            header("Location: user_add_complete.php");
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["back"])) { 
            header("Location: back_user_add_input.php");
            exit();
        }
    }
    public function back_user_add_input(){
       session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
            $username = $_POST["user_name"];
            $selected_type = $_POST['type'][0];
            if ($selected_type == 1) {
                $user_type = 'Giáo viên';
            } elseif ($selected_type == 2) {
                $user_type = 'Sinh viên';
            }
            $user_id = $_POST["ID"];
            $description = $_POST["description"];
    
            // Xác định thư mục tạm thời và thư mục chính
            $tempDirectory = "../../web/avatar/tmp/";
            $mainDirectory = "../../web/avatar/";
    
            if (!file_exists($mainDirectory)) {
                mkdir($mainDirectory, 0777, true);
            }

            if ($_FILES["image"]["error"] == 0) {
                $tempFilePath = $tempDirectory . $_FILES["image"]["name"];
                $displayMainFilePath = '../../web/avatar/tmp/' . $_FILES["image"]["name"];
                move_uploaded_file($_FILES["image"]["tmp_name"], $tempFilePath);
                $_SESSION["username"] = $username;
                $_SESSION["selected_type"] = $selected_type;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["description"] = $description;
                $_SESSION["target_file"] = $displayMainFilePath;

                $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                // Thay đổi phần cuối của URL
                $new_url = preg_replace('/user_edit_input.php\/\w+/', 'user_edit_confirm.php', $current_url);

                // Chuyển hướng đến URL mới
                header("Location: $new_url");
                exit();
            } else {
                $_SESSION["username"] = $username;
                $_SESSION["selected_type"] = $selected_type;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["description"] = $description;

                header("Location: user_add_confirm.php");
            }
        }
    }
    public function edit_user() {
        session_start();
        $current_path = $_SERVER['REQUEST_URI'];
        $path_parts = explode('/', $current_path);
        $user_id = end($path_parts);
        $user_model = new UserModel();
        $user_data = $user_model->get_user($user_id);
        $_SESSION["username"] = $user_data['name'];
        $_SESSION["selected_type"] = $user_data['type'];
        $_SESSION["user_id"] = $user_data['user_id'];
        $_SESSION["description"] = $user_data['description'];
        $_SESSION["target_file"] = $user_data['avatar'];
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
            $username = $_POST["user_name"];
            $selected_type = $_POST['type'][0];
            if ($selected_type == 1) {
                $user_type = 'Giáo viên';
            } elseif ($selected_type == 2) {
                $user_type = 'Sinh viên';
            }
            $user_id = $_POST["ID"];
            $description = $_POST["description"];
    
            // Xác định thư mục tạm thời và thư mục chính
            $tempDirectory = "../../web/avatar/tmp/";
            $mainDirectory = "../../web/avatar/";
            if (!file_exists($mainDirectory)) {
                mkdir($mainDirectory, 0777, true);
            }

            if ($_FILES["image"]["error"] == 0) {
                $tempFilePath = $tempDirectory . $_FILES["image"]["name"];
                $displayMainFilePath = '../../web/avatar/' . $user_id . "/" . $_FILES["image"]["name"];
                move_uploaded_file($_FILES["image"]["tmp_name"], $tempFilePath);
                $_SESSION["username"] = $username;
                $_SESSION["selected_type"] = $selected_type;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["description"] = $description;
                $_SESSION["old_file"] = $_SESSION["target_file"];
                $_SESSION["target_file"] = $displayMainFilePath;

                $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                // Thay đổi phần cuối của URL
                $new_url = preg_replace('/user_edit_input.php\/\w+/', 'user_edit_confirm.php', $current_url);

                // Chuyển hướng đến URL mới
                header("Location: $new_url");
                exit();
            } else {
                $_SESSION["username"] = $username;
                $_SESSION["selected_type"] = $selected_type;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["description"] = $description;
                $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                // Thay đổi phần cuối của URL
                $new_url = preg_replace('/user_edit_input.php\/\w+/', 'user_edit_confirm.php', $current_url);

                // Chuyển hướng đến URL mới
                header("Location: $new_url");
            }
        }
    }
    
    public function confirm_edit_user() {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST" &&isset($_POST["confirm"])) {
            $tempDirectory = "../../web/avatar/tmp/";
            $mainDirectory = "../../web/avatar/";
            $fileName = basename($_SESSION["target_file"]);
            $tempFilePath = $tempDirectory . $fileName;
            $mainFilePath = $mainDirectory . $_SESSION["user_id"] . "/" . $fileName;
            $displayMainFilePath = '../../web/avatar/'. $_SESSION["user_id"].'/' . $fileName;
            rename($tempFilePath, $mainFilePath);
            $oldFilePath = $_SESSION["old_file"];
            unlink($oldFilePath);
            $files = scandir($tempDirectory);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $filePath = $tempDirectory . '/' . $file;
                    if (is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            $_SESSION["target_file"] = $displayMainFilePath;
            $user_model = new UserModel();
            $user_model->edit_user($_SESSION["user_id"]);

            header("Location: user_edit_complete.php");
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["back"])) { 
            header("Location: back_user_edit_input.php/{$_SESSION['user_id']}");

            exit();
        }
    }
    public function back_user_edit_input(){
            session_start();
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
                $username = $_POST["user_name"];
                $selected_type = $_POST['type'][0];
                if ($selected_type == 1) {
                    $user_type = 'Giáo viên';
                } elseif ($selected_type == 2) {
                $user_type = 'Sinh viên';
            }
            $user_id = $_POST["ID"];
            $description = $_POST["description"];
    
            // Xác định thư mục tạm thời và thư mục chính
            $tempDirectory = "../../web/avatar/tmp/";
            $mainDirectory = "../../web/avatar/";
    
            if (!file_exists($mainDirectory)) {
                mkdir($mainDirectory, 0777, true);
            }

            if ($_FILES["image"]["error"] == 0) {
                $tempFilePath = $tempDirectory . $_FILES["image"]["name"];
                $displayMainFilePath = '../../web/avatar/tmp/' . $_FILES["image"]["name"];
                move_uploaded_file($_FILES["image"]["tmp_name"], $tempFilePath);
                $_SESSION["username"] = $username;
                $_SESSION["selected_type"] = $selected_type;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["description"] = $description;
                $_SESSION["old_file"] = $_SESSION["target_file"];
                $_SESSION["target_file"] = $displayMainFilePath;

                $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                // Thay đổi phần cuối của URL
                $new_url = preg_replace('/back_user_edit_input.php\/' . preg_quote($_SESSION["user_id"], '/') . '/', 'user_edit_confirm.php', $current_url);

                // Chuyển hướng đến URL mới
                header("Location: $new_url");
                exit();
            } else {
                $_SESSION["username"] = $username;
                $_SESSION["selected_type"] = $selected_type;
                $_SESSION["user_type"] = $user_type;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["description"] = $description;
                $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                // Thay đổi phần cuối của URL
                $new_url = preg_replace('/back_user_edit_input.php\/' . preg_quote($_SESSION["user_id"], '/') . '/', 'user_edit_confirm.php', $current_url);

                // Chuyển hướng đến URL mới
                header("Location: $new_url");
                
            }
        }
     }
}

$user_controller = new user_add();

// Kiểm tra nếu là trang xác nhận
if(basename($_SERVER['PHP_SELF']) === 'user_add_input.php'){
    echo 1;
    $user_controller->add_user();
}
if (basename($_SERVER['PHP_SELF']) === 'back_user_add_input.php') {
    echo 2;
    $user_controller->back_user_add_input();
} 
if (basename($_SERVER['PHP_SELF']) === 'user_add_confirm.php') {
    echo 3;
    $user_controller->confirm_add_user();
} 
if (strpos($_SERVER['REQUEST_URI'], 'user_edit_input.php') !== false && strpos($_SERVER['REQUEST_URI'], 'back_user_edit_input.php') == false) {
    echo 4;
    $user_controller->edit_user();
}
if (strpos($_SERVER['REQUEST_URI'], 'back_user_edit_input.php') !== false) {
    echo 5;
    $user_controller->back_user_edit_input();
} 
if(strpos($_SERVER['REQUEST_URI'], 'user_edit_confirm.php') !== false){
    echo 6;
    $user_controller->confirm_edit_user();
}

?>
