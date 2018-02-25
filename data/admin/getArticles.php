<?php	
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	
	@$family = $_REQUEST['article_family'];
	@$kw = $_REQUEST['kw'];
	@$size = $_REQUEST['article_size'];
	@$pno = $_REQUEST['pno'];
	$articles = [];
	$sql = '';

	if(!$pno){
		$pno = 1;
	}
	if(!$size){
		$size = 10;
	}

	$start = ($pno-1) * $size;


	if(array_key_exists("article_family",$_REQUEST)){
		$sql = "SELECT article_id,article_title,SUBSTRING(article_text,1,200) as article_text,family_name,category_name,article_public_date FROM blog_article JOIN blog_article_family ON article_family=family_id JOIN blog_article_category ON article_category=category_id WHERE article_isPublic=true AND article_show=true";
		if(!array_key_exists("kw",$_REQUEST)){
			$sql .= " AND article_family=$family";
		}
	}else{
		$sql = "SELECT article_id,article_title,article_family,article_category,article_click_count,article_recommend,article_update_date,article_show,family_name,category_name FROM blog_article JOIN blog_article_family ON article_family=family_id JOIN blog_article_category ON article_category=category_id WHERE article_isPublic=true";	
	}
	if($kw){
		$sql .= " AND article_title LIKE '%$kw%'";
	}
	$articles["pageCount"] = ceil( count(sql_execute($sql)) / $size);

	$sql .= " ORDER BY article_public_date DESC LIMIT $start,$size";
	$articles["data"] = sql_execute($sql);

	echo json_encode($articles);
	// echo $sql;
?>