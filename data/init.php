<?php
Header("Content-Type:application/json;charset=utf-8");
header('Access-Control-Allow-Origin:*');

$db_host = 'qdm172705523.my3w.com';
$db_user = 'qdm172705523';
$db_password = 'zhuwenhan99';
$db_database = 'qdm172705523_db';
$db_port=3306;
$db_charset='UTF8';





$conn = mysqli_connect(

  $db_host, $db_user, $db_password, $db_database, 3306);

mysqli_query($conn,"SET NAMES UTF8;");

function sql_execute($sql){

  global $conn;

  $result = mysqli_query($conn, $sql);


  if(!$result){

    return ["code"=>-1,"msg"=>"查询失败"];

  }else {
    $posts = array();
    while($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    return $posts;
  }

}

?>