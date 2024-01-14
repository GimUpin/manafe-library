<?php
include '../connection.php';
mysqli_query($conn, "USE library");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $sql = "SELECT * FROM BOOKS WHERE ID = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $book = mysqli_fetch_assoc($result);
        echo json_encode($book, JSON_UNESCAPED_UNICODE);
        mysqli_free_result($result);
    } else {
        echo "Truy vấn thất bại: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
