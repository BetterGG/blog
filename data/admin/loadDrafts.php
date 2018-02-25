<?php
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	
	@$pno = $_REQUEST['pno'];
	@$draftSize = $_REQUEST['draftSize'];

	$output = [];

	if(!$pno){
		$pno = 1;
	}
	if(!$draftSize){
		$draftSize = 6;
	}
	$start = ($pno-1) * $draftSize;

	$sql = "SELECT article_id,article_title,article_text,article_update_date FROM blog_article WHERE article_isPublic=false ORDER BY article_update_date DESC LIMIT $start,$draftSize";

	$output["data"] = sql_execute($sql);

	$sql = "SELECT COUNT(*) FROM blog_article WHERE article_isPublic=false";

	$output["pageCount"] = ceil(sql_execute($sql)[0]["COUNT(*)"] / $draftSize);

	echo json_encode($output);
?>