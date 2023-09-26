<?php

$server_name = "127.0.0.1";
$user = "root";
$password = "";
// $port = "3306";

$database = "php";


$connect = mysqli_connect($server_name, $user, $password, $database);

mysqli_select_db($connect, $database) or die("DB 선택 실패");

?>