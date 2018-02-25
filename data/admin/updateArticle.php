<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	$id = $_REQUEST['article_id'];
	$title = $_REQUEST['article_title'];
	$author = $_REQUEST['article_author'];
	$family = $_REQUEST['article_family'];
	$category = $_REQUEST['article_category'];
	$recommend = $_REQUEST['article_recommend'];
	$content = $_REQUEST['article_content'];
	$text = $_REQUEST['article_text'];

	//字符串处理
	$content = str_replace("'","\'",$content);
	$text =  str_replace("'","\'",$text);

	$sql = "UPDATE blog_article SET article_title='$title',article_author='$author',article_family=$family,article_category=$category,article_recommend=$recommend,article_content='$content',article_text='$text' WHERE article_id=$id";
	$result = mysqli_query($conn,$sql);
	if($result){
		echo json_encode(["code"=>200,"msg"=>"保存成功"]);
	}else{
		echo json_encode(["code"=>500,"msg"=>"保存失败"]);
	}
?>