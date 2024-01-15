<?php
include $_SERVER['DOCUMENT_ROOT'].'/manage-library/session.php';
require_once "../model/BookModel.php";
require_once "../model/UserModel.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php include './common/styles/common.php'?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>Sổ Cái Mượn Trả Sách</title>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'].'/manage-library/view/common/main_header.php' ?>
<h1>Sổ Cái Mượn Trả Sách</h1>

<div class="container">
	<form id="form-va" method="get">
	<div class="row justify-content-md-center my-3">
		<div class="col col-lg-2 col-label">
			<label for="sach">Sách:</label>
		</div>
		
		<div class="col col-lg-3">
			<select id="sach" class="form-control fs-5" name="sach">
				<?php
				$danhSachSach = BookModel::getAllBooks();
				echo "<option value=''>Tất cả</option>";
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
		<label  class="col col-lg-2 col-label" for="nguoi_dung">Người dùng:</label>
		<div class="col col-lg-3">
			<select id="nguoi_dung" class="w-100 form-control" name="nguoi_dung">
				<?php
				$danhSachNguoiDung = UserModel::getAllUsers();
				echo "<option value=''>Tất cả</option>";
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
	
	<div class="row justify-content-md-center my-4">
		<div class="col col-md-auto"><button id="btn-search" type="button" class="btn btn-info">Tìm kiếm</button</div>
	</div>
	</form>
</div>
<div id="total-record" class="flex-grow-1"></div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">No</th>
	  <th scope="col">Tên Sách</th>
      <th scope="col">Tên người dùng</th>
      <th scope="col">Tình trạng</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id="rows-view">
  </tbody>
</table>

<script id="TPL_ROW" type="text/x-jsrender">
	<tr>
	  <th scope="row">{{:i}}</th>
	  <td>{{:book_name}}</td>
	  <td>{{:user_name}}</td>
	  <td>{{:status_trans}}</td>
	  
	  
	  <td>
		{{if !is_returned}}
			<button type="button" class="btn-return btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{:id}}">Trả</button>
		 {{/if}}
	  </td>
	  
	</tr>
</script>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Bạn có muốn trả sách?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button id="btn-return-submit" type="button" class="btn btn-primary">Xác nhận</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsrender/1.0.13/jsrender.min.js" integrity="sha512-T93uOawQ+FrEdyCPaWrQtppurbLm8SISu2QnHyddM0fGXKX9Amyirwibe1wGYbsW2F8lLzhOM/2+d3Zo94ljRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
	$(document).ready(() =>{
		search();
		
		$('#btn-search').click(() => {
		search();
		})
		
	})
	
	
	function search(){
			$('#rows-view').empty()
			$('#total-record').html('Số kết quả đã tìm thấy: 0')
			var id_book = $('#sach').val()
			var id_user = $('#nguoi_dung').val()
						
			$.get( "/manage-library/controller/book_transaction/ajax_search_history_book.php", { id_book: id_book, id_user: id_user } )
			  .done(function(r) {
				  if(r){
					const resp = JSON.parse(r);
				
					if(resp.success){
						resp.data.forEach((e, index) => {
							var myTmpl = $.templates("#TPL_ROW");
							var html = myTmpl.render({
								i : index + 1,
								id : e.id,
								book_name : e.book_name,
								user_name : e.user_name,
								status_trans : e.status_trans,
								is_returned : e.is_returned
							});
							$('#rows-view').append(html)
						})
						$('.btn-return').on('click', (e) => {
							$('#exampleModal').modal('show')
							return_trans(e.target.getAttribute("data-id"))
						})
						$('#total-record').html(`Số kết quả đã tìm thấy: ${resp.data.length}`)
					}
				  }
				
			  });
			  
			 
		}
		
		function return_trans(id){
			$('#btn-return-submit').off('click');
			$('#btn-return-submit').on('click', () => {
				
				$.ajax({
				  method: "GET",
				  url: "/manage-library/controller/book_transaction/ajax_return_book.php",
				  data: {id:id}
				})
				  .done(function( msg ) {
					window.location.reload();
				  });
			})
		}
</script>
</body>
</html>
