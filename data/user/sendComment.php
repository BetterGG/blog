<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	@$article_id = $_REQUEST["article_id"];
	@$comment_id = $_REQUEST["comment_id"];

	@$name = $_REQUEST["name"];
	@$mail = $_REQUEST["mail"];
	@$web = $_REQUEST["web"];
	@$content = $_REQUEST["content"];
	@$replyTo = $_REQUEST["reply_to"];

	$sql = "";

	if($comment_id){
		$sql = "INSERT INTO blog_reply VALUES(NULL,$comment_id,'$name','$mail','$web','$content','$replyTo',DEFAULT)";
	}else if($article_id){
		$sql = "INSERT INTO blog_comment VALUES(NULL,'$name','$web','$mail','$content',DEFAULT,$article_id)";
	}


	$result = mysqli_query($conn,$sql);

	if($result){
		echo json_encode(["code"=>200,"msg"=>"发送成功"]);
	}else{
		echo json_encode(["code"=>500,"msg"=>"发送失败"]);
	}
?>