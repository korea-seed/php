<?php


if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id']))
{
	echo "로그인을 해야 이용할 수 있는 페이지 입니다";
	echo "<br />";
	echo "<a href='login.php'>로그인</a>";
	exit;
}

require_once("config/db_conn.php");

/*
print_r($_POST);
Array ( [name] => 23324 [age] => 45 [gender] => 남 )
*/

$user_id = trim($_POST['user_id']);		// trim은 공백제거하는 기능임
$user_pw = trim($_POST['user_pw']);


var_dump( md5($user_pw));
exit;

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
}

$sql_query = "insert into members (user_id, user_pw, name, age, gender, img_path, img_name, regdate) values ('".$user_id."', '".md5($user_pw)."', '".$name."', '".$age."', '".$gender."', '".$upload_dir."', '".$file_name."', now())";
$result = mysqli_query($connect, $sql_query);

if($result)
{
	echo "정상적으로 처리되었습니다.";
} else {
	echo "실패 하였습니다.";
}

echo "<a href='index.php'> 홈으로 </a>";




?>