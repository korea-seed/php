<?php
session_start();
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


$sql_query = "select * from members where idx=".$_GET['edit_no'];
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
	<title>수정</title>
</head>
<body>
	<form name='frm' action='edit_proc.php' method='post' onSubmit='return CheckForm();' enctype='multipart/form-data'>
		<input type='hidden' name='edit_no' value='<?php echo $_GET['edit_no']; ?>' />
		<table border='1'>
			<tr>
				<th>아이디</th>
				<td>
					<input type='text' name='user_id' value='<?php echo $row['user_id']; ?>' />
				</td>
			</tr>
			<tr>
				<th>비밀번호</th>
				<td>
					<input type='text' name='user_pw' value='<?php echo $row['user_pw']; ?>' />
				</td>
			</tr>
			<tr>
				<th>회원명</th>
				<td>
					<input type='text' name='name' value='<?php echo $row['name']; ?>' />
				</td>
			</tr>
			<tr>
				<th>나이</th>
				<td>
					<input type='text' name='age' value='<?php echo $row['age']; ?>' />
				</td>
			</tr>
			<tr>
				<th>성별</th>
				<td>
					<?php
					$checked1 = "";
					$checked2 = "";
					if($row['gender'] == 'MAN')
					{
						$checked1 = "checked";
						$checked2 = "";
					} else if($row['gender'] == 'WOMAN')
					{
						$checked1 = "";
						$checked2 = "checked";
					} else 
					{
						$checked1 = "";
						$checked2 = "";
					}
					?>

					<label>
						<input type='radio' name='gender' value='MAN' <?php echo $checked1; ?>/> 남
					</label>
					<label>
						<input type='radio' name='gender' value='WOMAN' <?php echo $checked2; ?>/> 여
					</label>
				</td>
			</tr>
			<tr>
				<th>사진</th>
				<td>
					<img src="<?php echo $row['img_path']."/".$row['img_name']; ?>" width="300" />
					<p>
						<input type='file' name='photo' value='' />
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<a href='./index.php'>목록</a>
				</td>
				<td >
					<input type='submit' value='저장' />
				</td>
			</tr>
		</table>
	</form>

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