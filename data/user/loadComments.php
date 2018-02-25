<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	@$id = $_REQUEST["article_id"];

	$comments = [
	];

	$sql = "SELECT * FROM blog_comment WHERE comment_article_id=$id";

	$result = mysqli_query($conn,$sql);

	if($result){
		while($row = mysqli_fetch_assoc($result)) {
			$comment_id = $row["comment_id"];
			$sql2 = "SELECT * FROM blog_reply WHERE reply_comment_id=$comment_id LIMIT 0,3";
			$comment = [];
			$comment["data"] = $row;
			$comment["replys"] = sql_execute($sql2);

			$sql3 = "SELECT COUNT(*) AS count FROM blog_reply WHERE reply_comment_id=$comment_id LIMIT 0,3";
			$comment["count"] = sql_execute($sql3)[0]["count"];
			array_push($comments,$comment);
	    }
	}


	echo json_encode($comments);
?>