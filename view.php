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


$sql_query = "select * from members where idx=".$_GET['view_no'];
$result = mysqli_query($connect, $sql_query);

$row = mysqli_fetch_array($result);
// 사용할 데이터가 $row에 들어있는 것이다.

// print_r($row);


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>보기</title>
</head>
<body>
	<table border='1'>
		<tr>
			<th>아이디</th>
			<td>
				<?php echo $row['user_id']; ?>
			</td>
		</tr>
		<tr>
			<th>비밀번호</th>
			<td>
				<?php echo $row['user_pw']; ?>
			</td>
		</tr>
		<tr>
			<th>회원명</th>
			<td>
				<?php echo $row['name']; ?>
			</td>
		</tr>
		<tr>
			<th>나이</th>
			<td>
				<?php echo $row['age']; ?>
			</td>
		</tr>
		<tr>
			<th>성별</th>
			<td>
				<?php echo $row['gender']; ?>
			</td>
		</tr>
		<tr>
			<th>사진</th>
			<td>
				<img src="<?php echo $row['img_path']."/".$row['img_name']; ?>" width="300" />
			</td>
		</tr>
		<tr>
			<td>
				<a href='./index.php'>목록</a>
			</td>
			<td>
				<a href='./edit.php?edit_no=<?php echo $_GET['view_no'];?>'>수정</a>
			</td>
		</tr>
	</table>

	<script>
		function CheckForm()
		{
			if(frm.name.value == '')
			{
				frm.name.focus();
				alert('이름을 입력해주세요');
				return false;
			} else if(frm.age.value == '')
			{
				frm.age.focus();
				alert('나이를 입력해주세요');
				return false;
			}
		}
	</script>
</body>
</html>