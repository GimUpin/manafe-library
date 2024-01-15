<?php
    include '../../model/UserModel.php';
	try{
		$id = trim($_GET["id"], " \n\r\t\v\x00");
		
		$user_model = new UserModel();
		$user_model->del($id);
	}catch(Exception $e){
		echo $e;
	}
 ?>