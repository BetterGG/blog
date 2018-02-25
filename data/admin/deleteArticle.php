<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	@$id = $_REQUEST['article_id'];
	@$idList = json_decode($_REQUEST['article_id_list']);
	$sql = "";

	if($id){
		$sql = "DELETE FROM blog_article WHERE article_id=$id";
	}

	if($idList){
		$sql = "DELETE FROM blog_article WHERE article_id=$idList[0]";
		for($i=1;$i<count($idList);$i++){
			$sql .= " OR article_id=$idList[$i]";
		}
	}
	$result = mysqli_query($conn,$sql);
	if($result){
		echo json_encode(["code"=>200,"msg"=>"删除成功"]);
	}else{
		echo json_encode(["code"=>500,"msg"=>"删除失败"]);
	}
?>
