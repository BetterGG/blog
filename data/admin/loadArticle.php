<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	
	@$id = $_REQUEST['article_id'];
	
	if(!$id){
		$count = 0;
		$show = true;
		$isPublic = false;

		$sql = "INSERT INTO blog_article(article_id) VALUES(NULL)";

		mysqli_query($conn,$sql);

		$sql = "SELECT MAX(article_id) from blog_article";

		$id = sql_execute($sql)[0]['MAX(article_id)'];
	}
	$sql = "SELECT * FROM blog_article JOIN blog_article_family ON article_family=family_id JOIN blog_article_category ON article_category=category_id WHERE article_id=$id";

	echo json_encode(sql_execute($sql));
?>