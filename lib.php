<?php

session_start();
require_once("config/db_conn.php");

function paging($table="", $cnt=1)
{
	global $connect, $_GET;

	$arr = array();

	// 전체 게시물 개수
	$sql_query = "select * from ".$table." order by age desc";
	$result = mysqli_query($connect, $sql_query);
	$num = mysqli_num_rows($result);
	
	// 한페이지당 데이터 개수
	$list_num = 5;
	
	// 한 블럭당 페이지 개수
	$page_num = 3;
	
	// 현재 페이지
	$arr['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
	
	// 전체 페이지 수 = 전체 데이터 / 페이지당 데이터 개수, ceil(올림), floor(내림), round(반올림)
	$arr['total_page'] = ceil($num / $list_num);
	
	// 전체 블럭수 = 전체 페이지 수 / 블럭당 페이지 수
	$total_block = ceil($arr['total_page'] / $page_num);
	
	// 현재 블럭 번호 = 현재 페이지 번호 / 블럭당 페이지 수
	$now_block = ceil($arr['page'] / $page_num);
	
	// 블럭당 시작 페이지 번호 = ( 해당 글의 블럭 번호 - 1) * 블럭당 페이지 수 + 1
	$arr['s_pageNum'] = ($now_block - 1) * $page_num + 1;
	
	// 데이터가 0개인 경우
	if($arr['s_pageNum'] == 0)
	{
		$arr['s_pageNum'] = 1;
	}
	
	// 블럭당 마지막 페이지 번호 = 현재 블럭번호 * 블럭당 페이지 수
	$arr['e_pageNum'] = $now_block * $page_num;
	
	// 미지막 번호가 전체 페이지를 넘지 않도록
	if($arr['e_pageNum'] > $arr['total_page'])
	{
		$arr['e_pageNum'] = $arr['total_page'];
	}
	
	
	// 시작 번호 = ( 현재 페이지 번호 - 1 ) * 페이지당 보여질 데이터 수
	$start = ($arr['page'] - 1) * $list_num;
	
	// 글번호
	$arr['cnt'] = $start + 1;
	
	
	// 기존 쿼리에 페이지 개념을 도임 limit
	$sql_query = "select * from ".$table." order by age desc limit ".$start.", ".$list_num." ";
	$arr['result'] = mysqli_query($connect, $sql_query);

	return $arr;
}


function pagination($libs="")
{
	// 페이징 프론트 작업

	echo "<p>";

	// 이전페이지
	if($libs['page'] <= 1)
	{
		echo "<a href='index.php?page=1'>이전</a>";
	} else
	{
		echo "<a href='index.php?page=".($libs['page'] - 1)."'>이전</a>";
	}

	// 페이지 번호
	for($p = $libs['s_pageNum']; $p <= $libs['e_pageNum']; $p++)
	{
		echo "<a href='index.php?page=".$p."'>".$p."</a>";
	}


	// 다음 페이지
	if($libs['page'] >= $libs['total_page'])
	{
		echo "<a href='index.php?page=".$libs['total_page']."'>다음</a>";
	} else
	{
		echo "<a href='index.php?page=".($libs['page'] + 1)."'>다음</a>";
	}

	echo "</p>";

}
?>