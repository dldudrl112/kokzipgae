<?php
include_once("./_common.php");

if($is_member) {
    alert_close('이미 로그인중입니다.');
}

/*
if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert_close("잘못된 접근 입니다.");
	exit;
}

*/


$cellphone	=	trim($_POST['phone1'])."-".trim($_POST['phone2'])."-".trim($_POST['phone3']);

if(!$cellphone)
    alert_close('잘못된 정보입니다.');

$sql = " select count(*) as cnt from shop_member where cellphone = '$cellphone' ";
$row = sql_fetch($sql);
if($row['cnt'] > 1)
    alert_close('동일한 연락처가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.');

$sql = " select index_no, id, name, email from shop_member where cellphone = '$cellphone' ";
$mb = sql_fetch($sql);
if(!$mb['id'])
    alert_close('존재하지 않는 회원입니다.');


include_once(TB_THEME_PATH.'/find_id_check.skin.php');

?>