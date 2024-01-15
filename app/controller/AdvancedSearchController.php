<?php

require_once '../model/AdvancedSearchModel.php';
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";

if (isset($_POST['search'])) {
    // Xử lý tìm kiếm
    $bookId = $_POST['book'];
    $userId = $_POST['user'];
    $status = $_POST['status']; // Lấy giá trị từ trường "Tình trạng mượn"
    $daysOverdue = $_POST['days_overdue']; // Lấy giá trị từ trường "Số ngày quá hạn"

    $searchResults = AdvancedSearchModel::advancedSearchTransactions(
        empty($bookId) ? null : $bookId,
        empty($userId) ? null : $userId,
        empty($status) ? null : $status,
        empty($daysOverdue) ? null : $daysOverdue
    );
} elseif (isset($_POST['reset'])) {
    $searchResults = AdvancedSearchModel::advancedSearchTransactions(null, null, null, null);
}

include '../view/AdvancedSearchView.php';
?>