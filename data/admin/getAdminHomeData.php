<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	$count = [];

	$sql = "SELECT COUNT(*) AS count FROM blog_article WHERE article_isPublic=true";
	$count["articles_count"] = sql_execute($sql)[0]["count"];

	$sql = "SELECT COUNT(*) AS count FROM blog_article WHERE article_isPublic=false";
	$count["drafts_count"] = sql_execute($sql)[0]["count"];

	echo json_encode($count);
?>