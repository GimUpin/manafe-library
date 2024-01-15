<?php
include $_SERVER['DOCUMENT_ROOT'].'/manage-library/session.php';
include '../model/MuonSachModel.php';
include "../model/BookTransactionModel.php";

$conn = new PDO("mysql:host=localhost;dbname=library", "root", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
    if (basename($_SERVER['PHP_SELF']) === 'MuonSachView.php') {
        $bookId = $_POST['sach'];
        $userId = $_POST['nguoi_dung'];
        $borrowedDatetime = $_POST['tu_ngay'];
        $returnPlanDatetime = $_POST['den_ngay'];
		
        $result = MuonSachModel::muonSach($bookId, $userId, $borrowedDatetime, $returnPlanDatetime, $conn);
        if ($result) {
           
           header("Location: ../view/MuonSachView.php");
           exit();
        }else{
			
		}
    } elseif (isset($_POST['lich_su_muon'])) {
        header("Location: ../view/Sach_LichSuMuon-view.php");
        exit();
    }
}

//include '../view/MuonSachView.php';
?>
