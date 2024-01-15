<?php
class AdvancedSearchModel {
    public static function advancedSearchTransactions($bookId, $userId, $status, $daysOverdue) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $query = "
    SELECT
        books.name AS book_name,
        COUNT(book_transactions.id) AS num_borrows,
        GROUP_CONCAT(book_transactions.borrowed_date ORDER BY book_transactions.borrowed_date) AS borrowed_datetime,
        GROUP_CONCAT(book_transactions.return_plan_date ORDER BY book_transactions.return_plan_date) AS return_plan_datetime,
        GROUP_CONCAT(book_transactions.return_actual_date ORDER BY book_transactions.return_actual_date) AS return_actual_datetime,
        GROUP_CONCAT(users.name ORDER BY book_transactions.borrowed_date) AS user_name
    FROM 
        book_transactions
        JOIN users ON book_transactions.user_id = users.id
        JOIN books ON book_transactions.book_id = books.id
    WHERE 1"; // Always true condition to start the WHERE clause

        $params = array();

        if ($bookId !== null) {
            $query .= " AND book_transactions.book_id = :bookId";
            $params[':bookId'] = $bookId;
        }

        if ($userId !== null) {
            $query .= " AND book_transactions.user_id = :userId";
            $params[':userId'] = $userId;
        }

        if ($status !== null) {
            switch ($status) {
                case 'Đang mượn':
                    $query .= " AND :currentDate < book_transactions.return_plan_date AND book_transactions.return_actual_date = ''";
                    break;

                case 'Quá hạn':
                    $query .= " AND :currentDate > book_transactions.return_plan_date AND book_transactions.return_actual_date = ''";
                    break;

                case 'Đã trả':
                    $query .= " AND book_transactions.return_actual_date != ''";
                    break;

                default:
                    // Nếu giá trị $status không khớp với các trạng thái trên, không thêm điều kiện gì cả.
                    break;
            }
        }

        if ($daysOverdue !== null) {
            $query .= " AND (:currentDate < book_transactions.return_plan_date OR (book_transactions.return_actual_date = '' AND :currentDate > book_transactions.return_plan_date))";
            $params[':currentDate'] = date('Y-m-d H:i:s');
        }

        $query .= " GROUP BY books.id";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $history = array();

        foreach ($results as &$result) {
            $time_borrowed = explode(',', $result['borrowed_datetime']);
            $time_borrowed_plan = explode(',', $result['return_plan_datetime']);
            $time_book_return = explode(',', $result['return_actual_datetime']);
            $user_names = explode(',', $result['user_name']);

            $records = array();
            for ($i = 0; $i < count($time_borrowed); $i++) {
                $currentDate = new DateTime();
                $returnPlanDate = new DateTime($time_borrowed_plan[$i]);

                $status = '';
                $days_overdue = 0;
                $days_overdue_label = '';

                if ($status === 'Quá hạn' && $time_book_return[$i] == '') {
                    $days_overdue = max(0, $currentDate->diff($returnPlanDate)->days);

                    if ($days_overdue < 1) {
                        $days_overdue_label = 'Dưới 1 ngày';
                    } elseif ($days_overdue >= 2 && $days_overdue <= 5) {
                        $days_overdue_label = 'Từ 2-5 ngày';
                    } elseif ($days_overdue >= 6 && $days_overdue <= 10) {
                        $days_overdue_label = 'Từ 6-10 ngày';
                    } else {
                        $days_overdue_label = 'Trên 10 ngày';
                    }
                }

                $record = array(
                    'time_borrow_plan' => isset($time_borrowed[$i], $time_borrowed_plan[$i]) ?
                        date("H:i d/m/Y", strtotime($time_borrowed[$i])) . ' ~ ' . date("H:i d/m/Y", strtotime($time_borrowed_plan[$i])) :
                        'N/A',
                    'time_book_return' => isset($time_book_return[$i]) && $time_book_return[$i] ?
                        date("H:i d/m/Y", strtotime($time_book_return[$i])) :
                        'N/A',
                    'user_name' => isset($user_names[$i]) ? $user_names[$i] : 'N/A',
                    'status' => $status,
                    'days_overdue' => $days_overdue_label,
                );
                $records[] = $record;
            }

            $result['history'] = $records;
            $history[] = $result;
        }

        return $history;
    }

}
