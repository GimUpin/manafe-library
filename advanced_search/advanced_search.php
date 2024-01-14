<?php
// Kết nối tới cơ sở dữ liệu MySQL
$servername = "localhost";
$username = "root";
$password = "";
$database = "library";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

// Lấy thông tin từ Database
$sql = "SELECT book_transactions.book_id, book_transactions.user_id, 
        CASE 
            WHEN book_transactions.return_actual_date IS NULL AND CURRENT_DATE <= book_transactions.return_plan_date THEN 'Đang mượn'
            WHEN book_transactions.return_actual_date IS NOT NULL THEN 'Đã trả'
            WHEN book_transactions.return_actual_date IS NULL AND DATEDIFF(CURRENT_DATE, book_transactions.return_plan_date) <= 1 THEN 'Dưới 1 ngày'
            WHEN book_transactions.return_actual_date IS NULL AND DATEDIFF(CURRENT_DATE, book_transactions.return_plan_date) >= 5 AND DATEDIFF(CURRENT_DATE, book_transactions.return_plan_date) <= 10 THEN 'Từ 6 - 10 ngày'
        END AS tinh_trang_muon
        FROM book_transactions";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // In kết quả
    echo "<table>
            <tr>
                <th>Số thứ tự</th>
                <th>Tên sách</th>
                <th>Người dùng</th>
                <th>Tình trạng mượn</th>
            </tr>";
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $count . "</td>
                <td>" . $row['book_id'] . "</td>
                <td>" . $row['user_id'] . "</td>
                <td>" . $row['tinh_trang_muon'] . "</td>
            </tr>";
        $count++;
    }
    echo "</table>";
} else {
    echo "Không tìm thấy kết quả.";
}

?>