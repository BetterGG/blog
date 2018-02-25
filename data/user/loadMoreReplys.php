<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	@$comment_id = $_REQUEST["comment_id"];


	$sql = "SELECT COUNT(*) FROM blog_reply WHERE reply_comment_id=$comment_id";

	$end = sql_execute($sql)[0]["COUNT(*)"];

	$sql = "SELECT * FROM blog_reply WHERE reply_comment_id=$comment_id LIMIT 3,$end";

	echo json_encode(sql_execute($sql));
?>