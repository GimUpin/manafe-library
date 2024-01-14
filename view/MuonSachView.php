<?php
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";
?>
<form method="post" action="../controller/MuonSachController.php" onsubmit=showConfirmation()>
    <div>
        <label for="sach">Sách:</label>
        <select id="sach" name="sach" required>
            <?php
            $danhSachSach = BookModel::getAllBooks();
            foreach ($danhSachSach as $sach) {
                echo "<option value='" . $sach['id'] . "'>" . $sach['name'] . "</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label for="nguoi_dung">Người dùng:</label>
        <select id="nguoi_dung" name="nguoi_dung" required>
            <?php
            $danhSachNguoiDung = UserModel::getAllUsers();
            foreach ($danhSachNguoiDung as $nguoiDung) {
                echo "<option value='" . $nguoiDung['id'] . "'>" . $nguoiDung['name'] . "</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label for="tu_ngay">Từ ngày:</label>
        <input type="datetime-local" id="tu_ngay" name="tu_ngay" required>
    </div>

    <div>
        <label for="den_ngay">Đến ngày:</label>
        <input type="datetime-local" id="den_ngay" name="den_ngay" required>
    </div>

    <div>
        <input type="submit" name="muon_sach" value='Mượn sách'>

    </div>


</form>

<script>
    function showConfirmation() {
        alert('Sách đã được mượn thành công!');
        return true; // Returning true allows the form to be submitted
    }
</script>

<!-- Form lịch sử mượn -->
<form method="post" action="../view/Sach_Lichsumuon-view.php">
    <div>
        <button type="submit" name="lich_su_muon">Xem lịch sử mượn</button>
    </div>
    <div>
        <a href="home.php"> Quay lại home</a>
    </div>
</form>