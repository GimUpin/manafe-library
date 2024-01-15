<?php

require_once '../model/BookTransactionModel.php';
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";

if (isset($_POST['search'])) {
    // Xử lý tìm kiếm
    $bookId = $_POST['book'];
    $userId = $_POST['user'];
    $searchResults = BookTransactionModel::searchTransactions(empty($bookId) ? null : $bookId, empty($userId) ? null : $userId);
} elseif (isset($_POST['reset'])) {
    $searchResults = BookTransactionModel::searchTransactions(null, null);
}
elseif (isset($_POST['turnback'])) {
    header("Location: ../view/home.php");
}

include '../view/Sach_Lichsumuon-view.php';
?>

