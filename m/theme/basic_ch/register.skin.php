<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<form  name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<?php if($default['de_certify_use']) { // 使用实名认证时 ?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
<?php } ?>

<div class="s_cont">
	<?php if($default['de_sns_login_use']) { ?>
	<div class="sns_box">
		<h3 class="fr_tit">SNS 以账户注册</h3>
		<p>
			<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
			<?php echo get_login_oauth('naver', 1); ?>
			<?php } ?>
			<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
			<?php echo get_login_oauth('facebook', 1); ?>
			<?php } ?>
			<?php if($default['de_kakao_rest_apikey']) { ?>
			<?php echo get_login_oauth('kakao', 1); ?>
			<?php } ?>
		</p>
	</div>
	<?php } ?>
	<div class="fregister_agree">
		<h3 class="fr_tit">会员加入条款<a href="javascript:win_open('<?php echo TB_MBBS_URL; ?>/provision.php','pop_provision');" class="btn_small bx-white">查看全文</a></h3>
		<div class="agree_txt"><?php echo nl2br($config['shop_provision']); ?></div>
		<p class="agree_chk"><input name="agree1" type="checkbox" value="1" id="agree11" class="css-checkbox lrg"><label for="agree11" class="css-label">同意会员加入条款的内容</label></p>
	</div>
	<div class="fregister_agree">
		<h3 class="fr_tit">个人信息收集及使用<a href="javascript:win_open('<?php echo TB_MBBS_URL; ?>/policy.php','pop_policy');" class="btn_small bx-white">전문보기</a></h3>
		<div class="agree_txt"><?php echo nl2br($config['shop_private']); ?></div>
		<p class="agree_chk"><input name="agree2" type="checkbox" value="1" id="agree22" class="css-checkbox lrg"><label for="agree22" class="css-label">同意个人信息收集及使用内容。</label></p>
	</div>
	<div class="btn_confirm">
		<?php if($default['de_certify_use']) { ?>
		<button type="button" onclick="fnPopup(1);" class="btn_medium bx-white">手机认证</button>
		<button type="button" onclick="fnPopup(0);" class="btn_medium bx-white">I-PIN 认证</button>
		<?php } else { ?>
		<input type="submit" value="확인" class="btn_medium wset">
		<button type="button" onclick="history.go(-1);" class="btn_medium bx-white">取消</button>
		<?php } ?>
	</div>
</div>
</form>

<script language="javascript">
window.name ="Parent_window";
function fnPopup(val){
	var f = document.fregister;
	if(!f.agree1.checked) {
        alert("必须同意会员加入条款.");
        return false;
    }

	if(!f.agree2.checked) {
        alert("您同意收集和使用您的个人信息。");
        return false;
    }

	switch(val){
		case 1: //手机认证
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // Ipin认证
			window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.target = "popupIPIN2";
			document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
			document.fregister.submit();
			break;
	}
}

function fregister_submit(f)
{
    if(!f.agree1.checked) {
        alert("必须同意加入会员条款。");
        return false;
    }

	if(!f.agree2.checked) {
        alert("个人信息的搜集及使用内容表示同意。");
        return false;
    }

	return true;
}
</script>
