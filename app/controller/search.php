<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Quả Tìm Kiếm</title>
</head>
<body>

<?php
// Xử lý tìm kiếm và hiển thị kết quả ở đây
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedBookId = $_POST["book"];
    $selectedUserId = $_POST["user"];

    // Thực hiện xử lý tìm kiếm dựa trên $selectedBookId và $selectedUserId
    // Ví dụ: truy xuất cơ sở dữ liệu và lấy kết quả
    $results = performSearch($selectedBookId, $selectedUserId);

    if (count($results) > 0) {
        echo "<h2>Số bản ghi tìm thấy: " . count($results) . "</h2>";
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Tên sách</th>
                        <th>Người mượn</th>
                        <th>Tình trạng mượn</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($results as $key => $result) {
            echo "<tr>
                    <td>($key + 1)</td>
                    <td>{$result['book_name']}</td>
                    <td>{$result['user_name']}</td>
                    <td>{$result['status']}</td>
                    <td>";
            if ($result['status'] === 'Đang mượn' || $result['status'] === 'Quá hạn') {
                echo "<button onclick='confirmTraSach({$result['id']})'>Trả sách</button>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Không có kết quả phù hợp.</p>";
    }
    echo ">Quay lại Trang Tìm Kiếm</a></p>";
}
?>

<script>
    function confirmTraSach(recordId) {
        var confirmResult = confirm("Bạn có muốn trả sách?");
        if (confirmResult) {
            // Thực hiện cập nhật trạng thái và refresh trang
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Sau khi cập nhật, làm mới trang
                    location.reload();
                }
            };
            xhttp.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("returnRecordId=" + recordId);
        }
    }
</script>

</body>
</html>

<?php
// Xử lý cập nhật dữ liệu khi có yêu cầu từ JavaScript
if (isset($_POST["returnRecordId"])) {
    $recordId = $_POST["returnRecordId"];
    // Thực hiện cập nhật record trong book_transactions với rule sau
    performUpdate($recordId);
    // Trả kết quả thành công
    echo "Update success!";
    exit;
}

// Hàm giả để thực hiện cập nhật
function performUpdate($recordId) {
    // Kết nối đến cơ sở dữ liệu
    $conn = new mysqli("localhost", "username", "password", "database");

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }

    // Sử dụng Prepared Statement để tránh SQL Injection
    $sql = "UPDATE book_transactions SET return_actual_datetime = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recordId);

    // Thực hiện câu lệnh SQL
    if ($stmt->execute()) {
        echo "Cập nhật thành công!";
    } else {
        echo "Cập nhật thất bại: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>
