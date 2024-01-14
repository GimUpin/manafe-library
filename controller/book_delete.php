<?php
// book_delete.php
include '../connection.php';
// Check if the request contains the book name
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    echo $name;

    // Include your database connection file
    // Prepare the delete query (assuming your table name is BOOKS)
    $sql = "DELETE FROM BOOKS WHERE ID = '$name'";

    // Execute the delete query
    if ($conn->query($sql) === TRUE) {
        // Deletion successful

        // Retrieve the category of the book being deleted
        $sql_category = "SELECT category FROM BOOKS WHERE name = '$name'";
        $result_category = $conn->query($sql_category);
        $category = "";
        if ($result_category->num_rows > 0) {
            $row_category = $result_category->fetch_assoc();
            $category = $row_category['category'];
        }

        // Fetch the updated list of books in the same category
        $sql_updated = "SELECT name, quantity, category, description FROM BOOKS WHERE category = '$category'";
        $result_updated = $conn->query($sql_updated);

        $i = 1;
        if ($result_updated->num_rows > 0) {
            while ($row = $result_updated->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>" . $row["category"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>";
                echo '<button class="button-container deleteButton" id="deleteButton">Xóa</button>';
                echo '<button class="button-container editButton" id="editButton">Sửa</button>';
                echo "</td>";
                echo "</tr>";
                $i++;
            }
        } else {
            echo "<tr>";
            echo "<td colspan='6'>Không có sách phù hợp</td>";
            echo "</tr>";
        }
    } else {
        echo "Error: " . $conn->error;
    }
    include "../view/book_list.php";
    $conn->close();
}
?>