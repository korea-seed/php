<?php


if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id']))
{
	echo "로그인을 해야 이용할 수 있는 페이지 입니다";
	echo "<br />";
	echo "<a href='login.php'>로그인</a>";
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>등록</title>
</head>
<body>
	<form name='frm' action='write_proc.php' method='post' onSubmit='return CheckForm();' enctype='multipart/form-data'>
		<table border='1'>
			<tr>
				<th>아이디</th>
				<td>
					<input type='text' name='user_id' value='' />
				</td>
			</tr>
			<tr>
				<th>비번</th>
				<td>
					<input type='text' name='user_pw' value='' />
				</td>
			</tr>			
			<tr>
				<th>회원명</th>
				<td>
					<input type='text' name='name' value='' />
				</td>
			</tr>
			<tr>
				<th>나이</th>
				<td>
					<input type='text' name='age' value='' />
				</td>
			</tr>
			<tr>
				<th>성별</th>
				<td>
					<label>
						<input type='radio' name='gender' value='MAN' /> 남
					</label>
					<label>
						<input type='radio' name='gender' value='WOMAN' /> 여
					</label>
				</td>
			</tr>
			<tr>
				<th>사진</th>
				<td>
					<input type='file' name='photo' value=''>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
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