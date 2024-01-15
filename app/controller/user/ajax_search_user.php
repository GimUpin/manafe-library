<?php
	include '../../model/UserModel.php';
	try{
		$keyword = trim($_GET["keyword"], " \n\r\t\v\x00");
		$type =  trim($_GET["type"], " \n\r\t\v\x00");
		$user_model = new UserModel();
		echo $user_model->search($keyword, $type);
	}catch(Exception $e){
		echo $e;
	}
 ?>