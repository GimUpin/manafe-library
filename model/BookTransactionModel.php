<?php

class BookTransactionModel {

    public static function User_searchTransactions($bookId, $userId) {
        $pdo = new PDO("mysql:host=localhost;dbname=library", "root", "");
        $query = "
        SELECT
            users.name AS user_name,
            COUNT(book_transactions.id) AS num_borrows,
            GROUP_CONCAT(book_transactions.borrowed_date ORDER BY book_transactions.borrowed_date) AS borrowed_datetime,
            GROUP_CONCAT(book_transactions.return_plan_date ORDER BY book_transactions.return_plan_date) AS return_plan_datetime,
            GROUP_CONCAT(book_transactions.return_actual_date ORDER BY book_transactions.return_actual_date) AS return_actual_datetime,
            GROUP_CONCAT(books.name ORDER BY book_transactions.borrowed_date) AS book_name
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

    $query .= " GROUP BY users.id";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $history = array();
        // Chuyển chuỗi nối thành mảng giá trị
        foreach ($results as &$result) {
            $time_borrowed = explode(',', $result['borrowed_datetime']);
            $time_borrowed_plan = explode(',', $result['return_plan_datetime']);
            $time_book_return = explode(',', $result['return_actual_datetime']);
            $book_names = explode(',', $result['book_name']);
            $records = array();
            for ($i = 0; $i < count($time_borrowed); $i++) {
                $record = array(
                    'time_borrow_plan' => isset($time_borrowed[$i], $time_borrowed_plan[$i]) ?
                        date("H:i d/m/Y", strtotime($time_borrowed[$i])) . ' ~ ' . date("H:i d/m/Y", strtotime($time_borrowed_plan[$i])) :
                        'N/A',
                    'time_book_return' => isset($time_book_return[$i]) ?
                        date("H:i d/m/Y", strtotime($time_book_return[$i])) :
                        'N/A',
                    'book_name' => isset($user_names[$i]) ? $book_names[$i] : 'N/A',
                );
                $records[] = $record;
            }

            $result['history'] = $records;
            $history[] = $result;

        }

        return $history;
    }


    public static function searchTransactions($bookId, $userId) {
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

    $query .= " GROUP BY books.id";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $history = array();
        // Chuyển chuỗi nối thành mảng giá trị
        foreach ($results as &$result) {
            $time_borrowed = explode(',', $result['borrowed_datetime']);
            $time_borrowed_plan = explode(',', $result['return_plan_datetime']);
            $time_book_return = explode(',', $result['return_actual_datetime']);
            $user_names = explode(',', $result['user_name']);

            $records = array();
            for ($i = 0; $i < count($time_borrowed); $i++) {
                $record = array(
                    'time_borrow_plan' => isset($time_borrowed[$i], $time_borrowed_plan[$i]) ?
                        date("H:i d/m/Y", strtotime($time_borrowed[$i])) . ' ~ ' . date("H:i d/m/Y", strtotime($time_borrowed_plan[$i])) :
                        'N/A',
                        'time_book_return' => isset($time_book_return[$i]) && $time_book_return[$i] ?
                        date("H:i d/m/Y", strtotime($time_book_return[$i])) :
                        'N/A',
                    'user_name' => isset($user_names[$i]) ? $user_names[$i] : 'N/A',
                );
                $records[] = $record;
            }


            $result['history'] = $records;
            $history[] = $result;
        }

        return $history;
    }
}
