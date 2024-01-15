
<?php include '../../session.php' ?>
<?php
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";

require_once "../controller/MuonSachController.php";
?>
<html>
<head>

  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php include $_SERVER['DOCUMENT_ROOT'].'/manage-library/view/common/styles/common.php'?>
    <title>Mượn trả sách</title>
	<style>
		.col-label{
			max-width: 12%;
		}
	</style>
</head>

<body>
	<?php include $_SERVER['DOCUMENT_ROOT'].'/manage-library/view/common/main_header.php' ?>
	
	<div class="container">
		<form id="form-va" method="post">
		<div class="row justify-content-md-center my-3">
			<div class="col col-lg-2 col-label">
				<label for="sach">Sách</label>
			</div>
			
			<div class="col col-lg-3">
				<select id="sach" class="form-control fs-5" name="sach" required>
					<?php
					$danhSachSach = BookModel::getBooksAvailable();
					echo "<option value=''>Chọn sách</option>";
					foreach ($danhSachSach as $sach) {
						echo "<option value='" . $sach['id'] . "'>" . $sach['name'] . "</option>";
					}
					?>
				</select>
				<div class="invalid-feedback">
					Vui lòng chọn sách.
				  </div>
			</div>
			</div>
		<div class="row justify-content-md-center mb-3">
			<label  class="col col-lg-2 col-label" for="nguoi_dung">Người dùng</label>
			<div class="col col-lg-3">
				<select id="nguoi_dung" class="w-100 form-control" name="nguoi_dung" required>
					<?php
					$danhSachNguoiDung = UserModel::getUsersAvailable();
					echo "<option value=''>Chọn người mượn</option>";
					foreach ($danhSachNguoiDung as $nguoiDung) {
						echo "<option value='" . $nguoiDung['id'] . "'>" . $nguoiDung['name'] . "</option>";
					}
					?>
				</select>
				<div class="invalid-feedback">
					Vui lòng chọn người mượn.
				  </div>
			</div>
		</div>
		<div class="row justify-content-md-center mb-3">
			<div class="col col-lg-2 col-label">
				<label for="sach">Từ ngày</label>
			</div>
			<div class="col col-lg-3">
				<input class="form-control" type="datetime-local" id="tu_ngay" name="tu_ngay" required>
				<div class="invalid-feedback">
					Vui lòng chọn ngày mượn.
				  </div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col col-lg-2 col-label">
				<label for="sach">Đến ngày</label>
			</div>
			<div class="col col-lg-3">
				<input class="form-control" type="datetime-local" id="den_ngay" name="den_ngay" required>
				<div class="invalid-feedback">
					Vui lòng chọn ngày trả.
				  </div>
			</div>
		</div>
		
		<div class="row justify-content-md-center my-4">
			<div class="col col-md-auto"><button id="btn-submit" type="button"  name="muon_sach" class="btn btn-info">Mượn sách</button</div>
		</div>
		</form>
	</div>
</form>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>

	$(document).ready(function() {
		$('#btn-submit').click(() => {
			var is_valid = true
			
			const id_sach = $('#sach').val()
			if(!id_sach){
				$('#sach').addClass('is-invalid')
				is_valid = false
			}else{
				$('#sach').removeClass('is-invalid')
			}
			
			
			const id_user = $('#nguoi_dung').val()
			if(!id_user){
				$('#nguoi_dung').addClass('is-invalid')
				is_valid = false
			}else{
				$('#nguoi_dung').removeClass('is-invalid')
			}
			const start_date = $('#tu_ngay').val()
			if(!start_date){
				$('#tu_ngay').addClass('is-invalid')
				is_valid = false
			}else{
				$('#tu_ngay').removeClass('is-invalid')
				
			}
			const end_date = $('#den_ngay').val()
			if(!end_date){
				$('#den_ngay').addClass('is-invalid')
				is_valid = false
			}else{
				$('#den_ngay').removeClass('is-invalid')
			}
			
			if(is_valid){
				$('#form-va').submit()
			}
		})
	});
</script>
</body>
</html>