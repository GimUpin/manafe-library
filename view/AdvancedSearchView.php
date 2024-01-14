<?php
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Advanced Search</title>
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
    select,
    input[type="submit"] {
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
    <h1>Sổ cái tìm kiếm nâng cao</h1>

    <!-- Form Search -->
    <form action="../controller/AdvancedSearchController.php" method="post">

        <label for="book">Tên sách:</label>
        <select name="book" id="book">
            <option value="">Tất cả</option>
            <?php
            $books = BookModel::getAllBooks();
            foreach ($books as $book) {
                echo "<option value='{$book['id']}'>{$book['name']}</option>";
            }
            ?>
        </select>

        <label for="user">Người dùng:</label>
        <select name="user" id="user">
            <option value="">Tất cả</option>
            <?php
            $users = UserModel::getAllUsers();
            foreach ($users as $user) {
                echo "<option value='{$user['id']}'>{$user['name']}</option>";
            }
            ?>
        </select>
        
        <label for="status">Tình trạng mượn:</label>
        <select name="status" id="status">
            <option value="">Tất cả</option>
            <option value="Đang mượn">Đang mượn</option>
            <option value="Quá hạn">Quá hạn</option>
            <option value="Đã trả">Đã trả</option>
        </select>

        <label for="daysOverdue">Số ngày quá hạn:</label>
        <select name="status" id="status">
            <option value="">Tất cả</option>
            <option value="Dưới 1 ngày">Dưới 1 ngày</option>
            <option value="Từ 2-5 ngày">Từ 2-5 ngày</option>
            <option value="Từ 6-10 ngày">Từ 6-10 ngày</option>
            <option value="Trên 10 ngày">Trên 10 ngày</option>
        </select>

        <input type="submit" value="Tìm kiếm" name="search">
        <input type="submit" value="Reset" name="reset">
    </form>

    <!-- Kết Quả Tìm Kiếm -->
    <div>
        <?php if (!empty($searchResults)): ?>
            <h2>Số kết quả tìm kiếm:
                <?= count($searchResults); ?>
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tên sách</th>
                        <th>Người dùng</th>
                        <th>Tình trạng mượn</th>
                        <th>Số ngày quá hạn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $index => $result): ?>
                        <tr>
                            <td>
                                <?= $index + 1; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($result['book_name']); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($result['user_name']); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($historyRecord['status']); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($historyRecord['days_overdue']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có kết quả tìm kiếm.</p>
        <?php endif; ?>
    </div>
</body>

</html>