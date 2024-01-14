<html>
<head>
    <meta charset="UTF-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	
</head>
<style>
	body {
	   margin:40px 0px;
	   padding: 0px;
	}
		
</style>
<body>
	<div class="container">
		<div class="row justify-content-md-center my-3">
			<div class="col col-lg-1">
				<span>Khoa</span>
			</div>
			
			<div class="col col-lg-2">
				<select id="select-type" class="form-select">
					<?php
						$types = array(""=>"--Chọn phân loại--" ,"1"=>"Giáo viên", "2"=>"Học sinh");
						
						foreach($types as $value => $text) {
							echo "<option value='$value'>$text</option>";
						}
					?>
				</select>
			</div>
			</div>
		<div class="row justify-content-md-center">
			<span class="col col-lg-1">Từ khoá</span>
			<div class="col col-lg-2">
				<input id="input-keyword" class="w-100"/>
			</div>
		</div>
		<div class="row justify-content-md-center my-4">
			<div class="col col-md-auto"><button id="btn-search" class="btn btn-info">Tìm</button</div>
		</div>
	</div>
	<div class="d-flex">
		<div id="total-record" class="flex-grow-1">
		</div>
		<div class="p-2">
			<a class="btn btn-secondary" href="user_add_input.php">Thêm</a>
		</div>
        <div class="p-2">
            <a class="btn btn-secondary" href="home.php">Quay lại</a>
        </div>
	</div>
	<table class="table">
  <thead>
    <tr>
      <th scope="col">No</th>
	  <th scope="col">ID</th>
      <th scope="col">Tên người dùng</th>
      <th scope="col">Phân loại</th>
	  <th scope="col">Mô tả</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id="rows-view">
  </tbody>
</table>
<script id="TPL_ROW" type="text/x-jsrender">
	<tr>
	  <th scope="row">{{:i}}</th>
	  <td>{{:sub_id}}</td>
	  <td>{{:name}}</td>
	  <td>{{:type}}</td>
	  <td>{{:description}}</td>
	  <td>
		<a href="user_edit_input.php/{{:sub_id}}" class="btn-edit btn btn-primary">Sửa</a>
		<button type="button" class="btn-del btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-del" data-id="{{:id}}">Xoá</button>
	  </td>
	</tr>
</script>

<div class="modal fade" id="modal-del" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title text-danger" id="staticBackdropLabel">Cảnh bảo</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body fs-5">
			Bạn muốn xóa người dùng này?
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
			<button id="submit-del" type="button" class="btn btn-danger">Xoá</button>
		  </div>
		</div>
	  </div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jsrender/1.0.13/jsrender.min.js" integrity="sha512-T93uOawQ+FrEdyCPaWrQtppurbLm8SISu2QnHyddM0fGXKX9Amyirwibe1wGYbsW2F8lLzhOM/2+d3Zo94ljRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		window.addEventListener("load", (event) => {
		  init();
		  search()
		  
		});
		
		function init(){
			$('#btn-search').click(() =>{
				search();
			})
			
			
		}
		
		function search(){
			$('#rows-view').empty()
			$('#total-record').html('Số người dùng đã tìm thấy: 0')
			const keyword = $('#input-keyword').val()
			const type = $('#select-type').val()
						
			$.get( "../controller/user/ajax_search_user.php", { keyword: keyword, type: type } )
			  .done(function(r) {
				  if(r){
					const resp = JSON.parse(r);
				
					if(resp.success){
						resp.data.forEach((e, index) => {
							var myTmpl = $.templates("#TPL_ROW");
							var html = myTmpl.render({
								i : index + 1,
								id : e.id,
								sub_id : e.user_id,
								name : e.name,
								type : e.type,
								description:e.description
							});
							$('#rows-view').append(html)
						})
						$('.btn-del').on('click', (e) => {
							onclick_submit_del(e.target.getAttribute("data-id"))
						})
						$('#total-record').html(`Số người dùng đã tìm thấy: ${resp.data.length}`)
					}
				  }
				
			  });
			  
			 
		}
		
		function onclick_submit_del(id){
			$('#submit-del').off('click');
			$('#submit-del').on('click', () => {
				
				$.ajax({
				  method: "GET",
				  url: "../controller/user/ajax_delete_user.php",
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
