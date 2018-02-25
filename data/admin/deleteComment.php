<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	@$id = $_REQUEST['comment_id'];
	@$idList = json_decode($_REQUEST['comment_id_list']);
	$sql = "";

	if($id){
		$sql = "DELETE FROM blog_comment WHERE comment_id=$id";
		mysqli_query($conn,$sql);
		$sql = "DELETE FROM blog_reply WHERE reply_comment_id=$id";
		mysqli_query($conn,$sql);
	}

	if($idList){
		$sql = "DELETE FROM blog_comment WHERE comment_id=$idList[0]";
		for($i=1;$i<count($idList);$i++){
			$sql .= " OR comment_id=$idList[$i]";
		}
		mysqli_query($conn,$sql);
		
		$sql = "DELETE FROM blog_reply WHERE reply_comment_id=$idList[0]";
		for($i=1;$i<count($idList);$i++){
			$sql .= " OR reply_comment_id=$idList[$i]";
		}
		mysqli_query($conn,$sql);
	}

	
?>
