<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');

	@$kw = $_REQUEST["kw"];
	@$pno = $_REQUEST["pno"];
	@$size = $_REQUEST["size"];

	$output = [];

	$start = ($pno - 1) * $size; 

	$sql = "SELECT comment_id,comment_person_name,comment_person_web,comment_person_mail,comment_content,comment_date,article_id,article_title FROM blog_comment JOIN blog_article ON comment_article_id=article_id";

	if($kw){
		$sql .= " WHERE comment_person_name LIKE '%$kw%' OR comment_content LIKE '%$kw%'";
	}

	$output["pageCount"] = ceil(count(sql_execute($sql)) / $size);

	$sql .= " ORDER BY comment_date DESC LIMIT $start,$size";

	$output["data"] = sql_execute($sql);

	

	echo json_encode($output);

?>
