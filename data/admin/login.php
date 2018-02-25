<?php	
	Header("Content-Type:application/json;charset=utf-8");
	require('../init.php');
	$name = $_REQUEST["uname"];
	$pwd = $_REQUEST["upwd"];
	$result = "";

	$sql = "SELECT * FROM blog_administrator WHERE admin_name='$name' AND admin_apwd='$pwd'";
	$result = sql_execute($sql);
	if($result){
		$output = [
			"code"=>200,
			"msg"=>"登录成功！",
			"info"=>[
				"uid"=>$result[0]['admin_id'],
				"uname"=>$result[0]['admin_user_name']
			]
		];
	}else{	
		$output = ["code"=>500,"msg"=>"用户名或密码错误！"];
	}
	echo json_encode($output);
?>