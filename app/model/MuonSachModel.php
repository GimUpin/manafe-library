<?php
if (!class_exists('MuonSachModel')) {
    class MuonSachModel
    {
        public static function muonSach($bookId, $userId, $borrowedDatetime, $returnPlanDatetime, $conn)
        {
            try {
                // Validate input
                $errors = array();

                if (empty($bookId) || empty($userId) || empty($borrowedDatetime) || empty($returnPlanDatetime)) {
                    $errors[] = "Vui lòng điền đầy đủ thông tin.";
                }

                // Kiểm tra số lượng sách trong kho
                $quantityAvailable = self::getAvailableQuantity($bookId, $conn);

                if ($quantityAvailable <= 0) {
                    $errors[] = "Sách đã hết, không thể mượn.";
                }

                if (!empty($errors)) {
                    // Xử lý hiển thị lỗi
                    foreach ($errors as $error) {
                        echo "<p style='color: red;'>$error</p>";
                    }
                    return false;
                }

                // Thực hiện mượn sách
                $conn->beginTransaction();
                $queryInsert = "INSERT INTO book_transactions (book_id, user_id, borrowed_date, return_plan_date)
                            VALUES (:bookId, :userId, :borrowedDatetime, :returnPlanDatetime)";
                $stmtInsert = $conn->prepare($queryInsert);
                $stmtInsert->bindParam(':bookId', $bookId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':borrowedDatetime', $borrowedDatetime);
                $stmtInsert->bindParam(':returnPlanDatetime', $returnPlanDatetime);
                $stmtInsert->execute();

                // Giảm số lượng sách trong kho
                $queryUpdate = "UPDATE books SET quantity = quantity - 1 WHERE id = :bookId";
                $stmtUpdate = $conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':bookId', $bookId, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $conn->commit();

                echo "<p style='color: green;'>Mượn sách thành công!</p>";
                return true;
            } catch (Exception $e) {
                $conn->rollBack();
                echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
                return false;
            }
        }

        public static function getLichSuMuon($conn)
        {
            $query = "
            SELECT books.name AS ten_sach, COUNT(book_transactions.id) AS so_lan_muon,
                   MIN(book_transactions.borrowed_date) AS thoi_gian_muon_du_kien,
                   MAX(book_transactions.return_actual_date) AS thoi_diem_tra,
                   users.name AS nguoi_muon
            FROM book_transactions
            JOIN books ON book_transactions.book_id = books.id
            JOIN users ON book_transactions.user_id = users.id
            GROUP BY books.name, users.name
        ";

            $result = $conn->query($query);

            $history = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $history[] = $row;
            }

            return $history;
        }

        public static function getAvailableQuantity($bookId, $conn)
        {
            // Lấy số lượng sách có sẵn trong kho
            $query = "SELECT quantity FROM books WHERE id = :bookId";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
            $stmt->execute();

            // Lấy giá trị
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($row['quantity']) ? $row['quantity'] : 0;
        }

    
    }
}
?>