
<?php
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Transactions Search</title>
</head>
<style>
    /* Style for the form container */
    form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    /* Style for form labels */
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    /* Style for form inputs */
    select, input[type="submit"] {
        margin-bottom: 15px;
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
    }

    /* Style for the submit buttons */
    input[type="submit"] {
        background-color: #3498db;
        color: #fff;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    input[type="submit"]:hover {
        background-color: #2980b9;
    }
</style>
<body>
    <h1>Người dùng Lịch sử mượn</h1>

    <!-- Form Search -->
        <form action="../controller/User_LichSuMuonController.php" method="post">
            <label for="book">Tên sách:</label>
            <select name="book" id="book" >
            <option value="" >Tất cả</option>
                <?php
                $books = BookModel::getAllBooks();
                foreach ($books as $book) {
                    echo "<option value='{$book['id']}'>{$book['name']}</option>";
                }
                ?>
            </select>

            <label for="user">Tên người dùng:</label>
            <select name="user" id="user">
            <option value="" >Tất cả</option>
                <?php
                $users = UserModel::getAllUsers();
                foreach ($users as $user) {
                    echo "<option value='{$user['id']}'>{$user['name']}</option>";
                }
                ?>
            </select>

            <input type="submit" value="Tìm kiếm" name="search">
            <input type="submit" value="Reset" name="reset">
            <input type="submit" value="Quay lại" name="turnback">
        </form>

    <!-- Kết Quả Tìm Kiếm -->
    <div>
        <?php if (isset($searchResults) && !empty($searchResults)) : ?>
            <h2>Số kết quả tìm kiếm: <?= count($searchResults); ?></h2>
            <table >
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Người dùng</th>
                        <th>Số lần mượn</th>
                        <th>Thời gian dự kiến mượn</th>
                        <th>Thời điểm trả</th>
                        <th>Tên sách</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $index => $result) : ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($result['user_name']); ?></td>
                            <td><?= $result['num_borrows']; ?></td>

                        <?php foreach ($result['history'] as $historyRecord) : ?>
                                <td><?= nl2br($historyRecord['time_borrow_plan']) ?></td>
                                <td><?= nl2br($historyRecord['time_book_return']) ?></td>
                                <td><?= nl2br($historyRecord['book_name']) ?></td>
                                <?php if ($historyRecord !== end($result['history'])): ?>
            </tr><tr>
            <td></td>
            <td></td>
            <td></td>
        <?php endif; ?>
                        <?php endforeach; ?></tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        <?php else : ?>
            <p>Không có kết quả tìm kiếm.</p>
        <?php endif; ?>
    </div>
</body>

</html>
