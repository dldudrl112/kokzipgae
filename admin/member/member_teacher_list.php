<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_teacher ";
$sql_search = " where (1) ";

//선생님 권한일시
if($member["grade"] == 7){
	$sql_search .= "  and mb_code =  '".$member["index_no"]."'";
}

$sql_order  = " order by index_no desc ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./member.php?code=teacher_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 선생님 등록</a>
EOF;
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('name', $sfl, '선생님명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fplanlist" id="fplanlist" method="post" action="./member/member_teacher_update.php" onsubmit="return flist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w100">
		<col class="w100">
		<col>
		<col class="w3z`00">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col">노출</th>
		<th scope="col">선생님명</th>
		<th scope="col">상담구분</th>
		<th scope="col">상담분야</th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$s_upd = "<a href=\"./member.php?code=teacher_form_update&w=c&counseling_type=1&index_no={$row['index_no']}$qstr&page=$page\" class=\"btn_small\">상담가능</a>&nbsp;&nbsp;&nbsp;
						<a href=\"./member.php?code=teacher_form_update&w=c&counseling_type=3&index_no={$row['index_no']}$qstr&page=$page\" class=\"btn_small\">상담종료</a>&nbsp;&nbsp;&nbsp;
						<a href=\"./member.php?code=teacher_form&w=u&index_no={$row['index_no']}$qstr&page=$page\" class=\"btn_small\">수정</a>";		

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);

		if($row['counseling_type'] == 1){
			$counseling_type	=	"상담가능";
		}else if($row['counseling_type'] == 2){
			$counseling_type	=	"상담중";
		}else{
			$counseling_type	=	"상담종료";		
		}
	?>
	<tr class="<?php echo $bg; ?>">
		<td>			
			<input type="hidden" name="tc_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="checkbox" name="tc_use[<?php echo $i; ?>]" value="1"<?php echo get_checked($row['use_type'], 1); ?>></td>
		<td><?php echo $row['name']; ?></td>
		<td><?php echo $counseling_type; ?></td>
		<td><?php echo $row['counseling']; ?></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function flist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>
