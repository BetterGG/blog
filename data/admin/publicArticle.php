<?php	
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	
	@$id = $_REQUEST['article_id'];

	$sql = "UPDATE blog_article SET article_isPublic=true,article_public_date=CURRENT_TIMESTAMP WHERE article_id=$id";

	$result = mysqli_query($conn,$sql);
	if($result){
		echo json_encode(["code"=>200,"msg"=>"发布成功！"]);
	}else{
		echo json_encode(["code"=>500,"msg"=>"可能出了点问题，发布失败！"]);
	}
?>