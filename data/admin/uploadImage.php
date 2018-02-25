<?php  
    Header("Content-Type:application/json;charset=utf-8");
    require('../init.php');
    if(!empty($_FILES["mypic"])){
        $picname = $_FILES["mypic"]["name"];
        $picsize = $_FILES["mypic"]["size"];
        if($picsize>2*1024*1024){
            echo "图片大小不能超过2M";
            exit;
        }
        $type = strstr($picname,".");
        if($type !=".gif" && $type != ".png" && $type != ".jpg" ){
            echo "图片格式不正确";
            exit;
        }
        $pics = time().rand(1,9999).$type;
		$src = $_FILES["mypic"]["tmp_name"];
		$des = "../upload/".$pics;
        move_uploaded_file($src,$des);
        echo json_encode(["errno"=>0,"data"=>[
                '127.0.0.1:8080/blog/data/upload/'.$pics
                ]]);
	}
?>