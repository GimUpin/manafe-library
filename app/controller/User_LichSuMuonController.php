<?php
require_once '../model/BookTransactionModel.php';

if (isset($_POST['search'])) {
    // Xử lý tìm kiếm
    $bookId = $_POST['book'];
    $userId = $_POST['user'];
    $searchResults = BookTransactionModel::User_searchTransactions(empty($bookId) ? null : $bookId, empty($userId) ? null : $userId);
} elseif (isset($_POST['reset'])) {
    $searchResults = BookTransactionModel::User_searchTransactions(null, null);
} elseif (isset($_POST['turnback'])) {
    header("Location: ../view/home.php");
}

include '../view/User_LichSuMuon.php';



