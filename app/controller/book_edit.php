<?php
include '../connection.php';
mysqli_query($conn, "USE library");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $category = $_POST["inputCategory"];
    $author = $_POST["author"];
    $quantity = $_POST["quantity"];
    $description = $_POST["description"];
    $img_path = $_POST["img_path"];

    // Check if a new image is selected
    if ($_FILES['avatar']['name'] !== '') {
        $folderPath = '../uploads';
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $file_name = $_FILES['avatar']['name'];
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_dest = '../uploads/' . $file_name;

        move_uploaded_file($file_tmp, $file_dest);

        $img_path = $file_name;
    }

    // Update the database
    $sql = "UPDATE `BOOKS` SET
            `NAME` = '$name',
            CATEGORY = '$category',
            AUTHOR = '$author',
            QUANTITY = '$quantity',
            `DESCRIPTION` = '$description'";

    // Check if a new image is selected, and update the query accordingly
    if ($_FILES['avatar']['name'] !== '') {
        $sql .= ", IMG_PATH = '$img_path'";
    }

    $sql .= " WHERE ID = $id";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Truy vấn thất bại: " . mysqli_error($conn);
    } else {
        echo "Cập nhật thành công sách $name";
    }
}

mysqli_close($conn);
?>
