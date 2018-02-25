<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	$id = $_REQUEST['article_id'];
	$isShow = $_REQUEST['article_show'];

	$sql = "UPDATE blog_article SET article_show=$isShow WHERE article_id=$id";
	$result = mysqli_query($conn,$sql);
	if($result){
		echo json_encode(["code"=>200,"msg"=>"更新成功"]);
	}else{
		echo json_encode(["code"=>500,"msg"=>"更新失败"]);
	}
?>