<?php

/*
if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id']))
{
	echo "로그인을 해야 이용할 수 있는 페이지 입니다";
	echo "<br />";
	echo "<a href='login.php'>로그인</a>";
	exit;
}
*/
require_once("config/db_conn.php");

$sql_query = "select * from members where user_id='".$_POST['user_id']."' ";
$result = mysqli_query($connect, $sql_query);
$row = mysqli_fetch_array($result);

if(isset($row['user_id']) && !$row['user_id'])
{
	echo "해당 아이디가 존재하지 않습니다.";
	exit;
}

$sql_query = "select * from members where user_id='".$_POST['user_id']."' AND user_pw='".md5($_POST['user_pw'])."' ";
$result = mysqli_query($connect, $sql_query);
$row = mysqli_fetch_array($result);

if(isset($row['user_id']) && !$row['user_id'])
{
	echo "정확한 정보가 아닙니다.";
	exit;
}

if(isset($row['user_id']))
{
	$_SESSION['user_id'] = $row['user_id'];
}

echo "정상 로그인 되었습니다.";
echo "<br />";
echo "<a href='/'>홈으로</a>";
?>