<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	$articles = [];

	$rules = ['','article_recommend','article_click_count'];


	$sql = "SELECT article_id,article_title,SUBSTRING(article_text,1,100) as article_text,family_name,category_name,article_public_date FROM blog_article JOIN blog_article_family ON article_family=family_id JOIN blog_article_category ON article_category=category_id WHERE article_isPublic=true AND article_show=true ORDER BY article_public_date DESC LIMIT 0,6";
	$articles['article_update_date'] = sql_execute($sql);
	
	
	$sql = "SELECT article_id,article_title FROM blog_article WHERE article_isPublic=true AND article_show=true ORDER BY article_recommend DESC LIMIT 0,6";
	$articles['article_recommend'] = sql_execute($sql);

	$sql = "SELECT article_id,article_title FROM blog_article WHERE article_isPublic=true AND article_show=true ORDER BY article_click_count DESC LIMIT 0,6";
	$articles['article_click_count'] = sql_execute($sql);

	echo json_encode($articles);
?>