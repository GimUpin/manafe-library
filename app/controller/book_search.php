<?php
// Kết nối đến cơ sở dữ liệu
include '../connection.php';

// Lấy dữ liệu từ AJAX request
$category = $_POST['category'];
$keyword = $_POST['keyword'];

// Chuỗi truy vấn SQL
$sql = "";
if (!empty($category) && $category != 'Tất cả' && !empty($keyword)) {
    // Tìm kiếm theo cả thể loại và từ khoá
    $sql = "SELECT * FROM books WHERE category = '$category' AND name LIKE '%$keyword%'";
} elseif (!empty($category) && $category != 'Tất cả') {
    // Tìm kiếm theo thể loại
    $sql = "SELECT * FROM books WHERE category = '$category'";
} elseif (!empty($keyword)) {
    // Tìm kiếm theo từ khoá
    $sql = "SELECT * FROM books WHERE name LIKE '%$keyword%'";
} else {
    // Hiển thị tất cả sách nếu không có thể loại hoặc từ khoá được chọn
    $sql = "SELECT * FROM books";
}

// Thực hiện truy vấn
$result = $conn->query($sql);

// Xây dựng HTML cho kết quả
$output = "";
$i = 1;
while ($row = $result->fetch_assoc()) {
    $output .= "<tr>";
    $output .= "<td>" . $i . "</td>";
    $output .= "<td class='hidden-id' style='display: none;'>" . $row["id"]. "</td>";
    $output .= "<td>" . $row["name"] . "</td>";
    $output .= "<td>" . $row["quantity"] . "</td>";
    $output .= "<td>" . $row["category"] . "</td>";
    $output .= "<td>" . $row["description"] . "</td>";
    $output .= "<td>";
    $output .= '<button class="button-container deleteButton" id="tableButton">Xóa</button>';
    $output .= '<button class="button-container editButton" id="tableButton">Sửa</button>';
    $output .= "</td>";
    $output .= "</tr>";
    $i++;
}

// Trả về kết quả dưới dạng HTML
echo $output;

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>