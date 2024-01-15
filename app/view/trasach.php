<?php
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sổ Cái Mượn Trả Sách</title>
</head>
<body>

<h1>Sổ Cái Mượn Trả Sách</h1>

<form method="post" action="../controller/search.php">
    <label for="book">Tên sách:</label>
    <select name="book" id="book">
        <?php
        $books = BookModel::getAllBooks();
        foreach ($books as $book) {
            echo "<option value='{$book['id']}'>{$book['name']}</option>";
        }
        ?>
    </select>

    <br>

    <label for="user">Tên người dùng:</label>
    <select name="user" id="user">
        <?php
        $users = UserModel::getAllUsers();
        foreach ($users as $user) {
            echo "<option value='{$user['id']}'>{$user['name']}</option>";
        }
        ?>
    </select>

    <br>

    <button type="submit">Tìm Kiếm</button>
</form>

</body>
</html>
