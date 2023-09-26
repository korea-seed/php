
<?php

/*
mysql 기본문법

(CRUD)

- R : selelct * from 테이블명 where 조건절;
- C : insert into from 테이블명 (컬럼명) value (컬럼에 대체할 데이터)
		* insert into from 테이블명 set 컬럼명=컬럼에 대체할 데이터
- U : update from 테이블명 (컬럼명) values (컬럼에 대체할 데이터) where 조건절;
		* update from 테이블명 set 컬럼명=컬럼에 대체할 데이터 where 조건절;
- D : delete from 테이블명 where 조건절;
*/


// $sql_query = "select * from members order by age asc";
 
/*
order by 기준이 될 컬럼명 => 정렬하다
order by 컬럼명 desc -> 역순
order by 컬럼명 asc -> 순차


*/



// ./config/ == config/ 
// require_once("config/db_conn.php");

/*
$rand = rand(1, 100);
$name = "고객 ".$rand."번";
$age = "34";
$gender = "WOMAN";

$sql_query = "insert into members (name, age, gender, regdate) values ('$name', '$age', '$gender', now())";

$result = mysqli_query($connect, $sql_query);


if($result)
{
	echo "DB가 정상적으로 추가";
} else {
	echo "DB가 추가에 실패";
}

// 2022-12-12 05:06:08 = date('Y-m-d H:i:s')
*/


/*
print_r($_POST);

$sql_query = "select * from members order by age desc";
$result = mysqli_query($connect, $sql_query);

echo "<table border='1'>";
echo "<tr>";
echo "<th>NO</th>";
echo "<th>Name</th>";
echo "<th>Age</th>";
echo "<th>성별</th>";
echo "<th>등록일</th>";
echo "<th>삭제</th>";
echo "</tr>";

while($row = mysqli_fetch_array($result))
{
	
	echo "<tr>";
	echo "<td>".$row['idx']."</td>";
	echo "<td>".$row['name']."</td>";
	echo "<td>".$row['age']."</td>";
	echo "<td>".$row['gender']."</td>";
	echo "<td>".$row['regdate']."</td>";

	echo "<td>";
	// echo "<form name='frmd' action='". $_SERVER['PHP_SELF'] ."' method='post'>";
	// echo "<input type='submit' value='삭제' />";
	// echo "<input type='text' name='del_no' value='" . $row['idx'] . "'>";
	// echo "</form>";

	echo "<a href='./delete.php?del_no=".$row['idx']."'>삭제</a>";

	echo "</td>";
	echo "</tr>";
}
echo "</table>";
mysqli_close($connect);
*/

/*
form --> javascript(var check) --> mysql CRUD

index-> list(R)
delete -> D : list->삭제->js(confirm)->delete.php (process)
create -> write.php (c) -> form->등록->js(check)->write_proc.php (process)
update -> update.php (U) ->list->수정->edit.php(form)->js(check)->edit_proc.php(process)
*/


require_once("lib.php");

$libs = paging("members", 1);

echo "<a href='write.php'>등록</a>";

if(isset($_SESSION['user_id']) && $_SESSION['user_id'])
{
	echo "<a href='logout.php'>로그아웃</a>";
} else
{
	echo "<a href='login.php'>로그인</a>";
}



echo "<table border='1'>";
echo "<tr>";
echo "<th>NO</th>";
echo "<th>Name</th>";
echo "<th>Age</th>";
echo "<th>성별</th>";
echo "<th>등록일</th>";
echo "<th>삭제</th>";
echo "</tr>";

while($row = mysqli_fetch_array($libs['result']))
{
	
	echo "<tr>";
	echo "<td>".$libs['cnt']."</td>";
	echo "<td>";
	echo "<a href='./view.php?view_no=".$row['idx']."'>";
	echo $row['name'];
	echo "</a>";
	echo "</td>";
	echo "<td>".$row['age']."</td>";
	echo "<td>".$row['gender']."</td>";
	echo "<td>".$row['regdate']."</td>";

	echo "<td>";
	// echo "<form name='frmd' action='". $_SERVER['PHP_SELF'] ."' method='post'>";
	// echo "<input type='submit' value='삭제' />";
	// echo "<input type='text' name='del_no' value='" . $row['idx'] . "'>";
	// echo "</form>";

	echo "<a href='./delete_proc.php?del_no=".$row['idx']."'>삭제</a>";

	echo "</td>";
	echo "</tr>";

	$libs['cnt']++;
}
echo "</table>";

pagination($libs);

mysqli_close($connect);


?>

