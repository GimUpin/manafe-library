<?php
// Create connection
class UserModel {
    public static function getAllUsers() {
        // Giả sử $pdo là đối tượng PDO kết nối đến cơ sở dữ liệu
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");

        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLoginId($loginId) {
        // Giả sử $pdo là đối tượng PDO kết nối đến cơ sở dữ liệu
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE login_id = :login_id");
        $stmt->bindParam(':login_id', $loginId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Sử dụng fetch thay vì fetchAll vì bạn chỉ mong đợi một dòng dữ liệu
    }

    public static function login($loginId, $password) {

        $user = UserModel::getLoginId($loginId);

        if ($user) {
            $hashedPassword = md5($password);

            // So sánh mật khẩu
            if ($hashedPassword === $user['password']) {
                return true; // Đăng nhập thành công
            }
        }

        return false; // Đăng nhập thất bại
    }

    public static function authenticateUser($username, $password, $userType) {
        if ($userType == "user") {

            $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return true; // Successful login
            }
            return false;
        } elseif ($userType == "admin") {
            return self::login($username,$password);
        }
        return false;
    }

    public static function loginIdExists($loginId) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE login_id = :login_id");
        $stmt->bindParam(':login_id', $loginId);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public static function updateResetToken($loginId, $resetToken) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $stmt = $pdo->prepare("UPDATE admins SET reset_password_token = :reset_token WHERE login_id = :login_id");
        $stmt->bindParam(':reset_token', $resetToken);
        $stmt->bindParam(':login_id', $loginId);
        $stmt->execute();
    }

    public static function getLoginIdByResetToken($loginId) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $stmt = $pdo->prepare("SELECT reset_password_token FROM admins WHERE login_id = :login_id");
        $stmt->bindParam(':login_id', $loginId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updatePassword($loginId, $hashedPassword) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $updatedAt = date("Y-m-d H:i:s");
        $stmt = $pdo->prepare("UPDATE admins SET password = :hashed_password, updated = :updated_at WHERE login_id = :login_id");
        $stmt->bindParam(':hashed_password', $hashedPassword);
        $stmt->bindParam(':updated_at', $updatedAt);
        $stmt->bindParam(':login_id', $loginId);
        $stmt->execute();
    }

    public static function clearResetToken($loginId) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $stmt = $pdo->prepare("UPDATE admins SET reset_password_token = '' WHERE login_id = :login_id");
        $stmt->bindParam(':login_id', $loginId);
        $stmt->execute();
    }
    public function add_user() {
        $conn = new mysqli("localhost", "root", "", "library");

        include('../connection.php');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_datetime = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO users (name, type, user_id, avatar, description, created) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $_SESSION["username"], $_SESSION["selected_type"], $_SESSION["user_id"], $_SESSION["target_file"], $_SESSION["description"], $current_datetime);

        if ($stmt->execute()) {
            echo "Đã đăng kí thành công";
        } else {
            echo "Lỗi: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }

    public function edit_user($user_id) {
        $conn = new mysqli("localhost", "root", "", "library");

        include('/WEB/manage-library/connection.php');

        $stmt = $conn->prepare("UPDATE users SET name = ?, type = ?, avatar = ?, description = ?, updated = ? WHERE user_id = ?");
        $stmt->bind_param("ssssss", $_SESSION["username"], $_SESSION["selected_type"], $_SESSION["target_file"], $_SESSION["description"], date("Y-m-d H:i:s"), $user_id);
        if ($stmt->execute()) {
            echo "Đã đăng kí thành công";
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
    public function get_user($user_id) {
        $conn = new mysqli("localhost", "root", "", "library");

        include('../connection.php');
        $stmt = $conn->prepare("SELECT `id`, `type`, `name`, `user_id`, `avatar`, `description`, `updated`, `created` FROM users WHERE `user_id` = ?");

        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        return $row;
    }


    public function search($keyword, $type) {
        $conn = new mysqli("localhost", "root", "", "library");

        include('../../connection.php');
        $req = "select * from `users` where (user_id like '%$keyword%')";
        if($type !== ''){
            $req = $req." and `type` = '$type'";
        }
        $resp = array("success"=>FALSE);
        try{
            $result = $conn->query($req);

            $resp["data"] = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $type = "";
                    if($row["type"] == 1){
                        $type = "Giáo viên";
                    }else if($row["type"] == 2){
                        $type = "Học sinh";
                    }

                    $student = array(
                        "id"=>$row["id"],
                        "user_id" => $row["user_id"],
                        "name"=>$row["name"],
                        "type"=>$type,
                        "description" => $row["description"]
                    );
                    array_push($resp["data"],$student);
                }
                $resp["success"] = TRUE;
                return json_encode($resp, JSON_UNESCAPED_UNICODE);
            }
        } catch(Exception $e){
            $resp["success"] = FALSE;
            return json_encode($resp, JSON_UNESCAPED_UNICODE);
        }
        $conn->close();

    }

    public function del($id){
        $conn = new mysqli("localhost", "root", "", "library");

        include('/WEB/manage-library/connection.php');
        $id = $conn -> real_escape_string($id);

        $sql = "DELETE FROM users WHERE id=$id";

        echo $conn->query($sql);
        $conn->close();
    }


}
?>