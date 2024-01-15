<?php
include('../connection.php');
class BookModel {
    public static function getAllBooks() {
        // Giả sử $pdo là đối tượng PDO kết nối đến cơ sở dữ liệu
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");

        $stmt = $pdo->prepare("SELECT * FROM books");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function checkDuplicateBook($name, $category, $author, $avatarFile) {
        $checkDuplicateQuery = "SELECT * FROM `BOOKS` WHERE `name` = '$name' AND `category` = '$category' AND `author` = '$author' AND `avatar` = '$avatarFile'";
        $result = mysqli_query($conn, $checkDuplicateQuery);
        return mysqli_num_rows($result) > 0;
    }

    function insertBook($name, $category, $author, $quantity, $description, $avatarFile) {
        $sql = "INSERT INTO `BOOKS` (`name`, `category`, `author`, `quantity`, `description`, `avatar`) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $category, $author, $quantity, $description, $avatarFile);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
}
?>