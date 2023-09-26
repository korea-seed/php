<?php


if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id']))
{
	echo "로그인을 해야 이용할 수 있는 페이지 입니다";
	echo "<br />";
	echo "<a href='login.php'>로그인</a>";
	exit;
}

require_once("config/db_conn.php");


// print_r($_GET);

$sql_query = "delete from members where idx = '".$_GET['del_no']."'";
$result = mysqli_query($connect, $sql_query);

if($result)
{
	echo "정상적으로 삭제";
} else {
	echo "삭제 실패";
}

echo "<a href='index.php'> 홈으로 </a>";
?>