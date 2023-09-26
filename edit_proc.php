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


// echo $_POST['edit_no'];
// exit;

$user_id = trim($_POST['user_id']);		// trim은 공백제거하는 기능임
$user_pw = trim($_POST['user_pw']);

$name = trim($_POST['name']);		// trim은 공백제거하는 기능임
$age = trim($_POST['age']);


if($_POST['gender'] == '' && empty($_POST['gender']))
{
	$gender = '선택안함';
} else {
	$gender = $_POST['gender'];

}


// 변수 초기화
$file_name = $upload_dir = "";

if( $_FILES['photo'] )
{
	// 파일 업로드 추가
	$upload_dir = "./uploads";
	$ext_chk = array("jpg", "jpeg", "png", "gif");


	$err = $_FILES['photo']['error'];
	$file_name = $_FILES['photo']['name'];
	// echo $file_name;
	// echo "<br />";
	$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
	// $file_ext = array("산", "jpg");




	if( !in_array($file_ext, $ext_chk) )
	{
		echo "허용되지 않는 파일입니다. <br />";
		echo "<a href='index.php'> 홈으로 </a>";
		exit;
	}

	move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir."/".$file_name);

	$sql_query = "update members set user_id='".$user_id."', user_pw='".md5($user_pw)."', name='".$name."', age='".$age."', img_path='".$upload_dir."', img_name='".$file_name."', gender='".$gender."' where idx = ".$_POST['edit_no'];
	$result = mysqli_query($connect, $sql_query);
} else
{
	$sql_query = "update members set name=user_id='".$user_id."', user_pw='".md5($user_pw)."', '".$name."', age='".$age."', gender='".$gender."' where idx = ".$_POST['edit_no'];
	$result = mysqli_query($connect, $sql_query);
}



if($result)
{
	echo "정상적으로 수정";
} else {
	echo "수정 실패";
}

echo "<a href='index.php'> 홈으로 </a>";




?>