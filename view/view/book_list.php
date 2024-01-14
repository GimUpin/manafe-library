
<?php 
    include '../view/header.php';
    echo '<style>';
        include '../view/style3.css'; // Include nội dung của file CSS
    echo '</style>';
    ?>

<body>
    <div class="container">
        <form id="searchForm">
            <label for="inputCategory" class="input_name">Thể loại</label>
            <select  name="inputCategory" id="inputCategory" class="entering" required>
                <?php
                $category = array(
                    "",
                    "Tất cả",
                    "Khoa học",
                    "Tiểu thuyết",
                    "Manga",
                    "Sách giáo khoa"

                );
                foreach ($category as $tl) {
                    echo "<option value='" . $tl . "'>" . $tl . "</option>";
                }
                ?>
            </select>
                <br><br>
        </form>
        <form id="keywordForm">
            <label for="inputKeyword" class="input_name">Từ khoá</label>
            <input type="text" name="inputKeyword" id="inputKeyword" class="entering" required><br><br>
        </form>

        <form>
            <button type="button" class="button-container" id="nonsubmitButton" onclick="searchBooks()"> Tìm kiếm </button>
            <button type="button" class="back-button" id="backButton" onclick="window.location.href='home.php'"> Về trang chủ</button>
        </form>

        <p class="title">Số quyển sách tìm thấy : </p>
        <div class="bookTableContainer">
            <table>
                <thead>
                    <tr>
                        <th>No</th>  
                        <!-- <th>Id</th>   -->
                        <th>Tên sách</th>
                        <th>Số lượng</th>
                        <th>Thể loại</th>
                        <th>Mô tả chi tiết</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="bookTableBody">
                    <?php
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                    error_reporting(E_ALL);
                    include '../connection.php';
                    // Kiểm tra xem đã nhận yêu cầu tìm kiếm chưa
                    $conn->close();
                    ?>
                </tbody> 
            </table>
        </div>
    </div>

    <script src="../booklist_handle.js"></script>
</body>

</html>
