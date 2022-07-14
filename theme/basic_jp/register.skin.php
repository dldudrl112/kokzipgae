<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form  name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<div><img src="<?php echo TB_IMG_URL; ?>/register_1.gif"></div>

<?php if($default['de_certify_use']) { // 実名認証使用時 ?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
<?php } ?>

<?php if($default['de_sns_login_use']) { ?>
<div class="sns_box mart20">
	<h3>SNS アカウントで加入</h3>
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

<section id="fregister_term">
	<h2>会員加入約款</h2>
	<textarea readonly><?php echo $config['shop_provision']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">会員加入約款の内容に同意します。</label>
	</fieldset>
</section>

<section id="fregister_private">
	<h2>個人情報の収集や利用</h2>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th>目的</th>
			<th>項目</th>
			<th>保有期間</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>利用者識別と本人確認</td>
			<td>ID,名前,パスワード</td>
			<td>会員脱退時まで</td>
		</tr>
		<tr>
			<td>顧客サービスの利用に関する通知,CS対応のための利用者の識別</td>
			<td>連絡先(Eメール,携帯番号)</td>
			<td>会員脱退時まで</td>
		</tr>
		</tbody>
		</table>
	</div>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21">
		<label for="agree21">個人情報収集及び利用内容に同意します.</label>
	</fieldset>
</section>

<?php if($default['de_certify_use']) { // 実名認証使用時 ?>
<section>
	<div class="agree_txt">
		<i class="fa fa-exclamation-circle"></i> 改正情報通信法第23条によって会員加入時には住民登録番号を収集しません。
		<span class="bold marl20">
			<input type="radio" value="1" name="chkplus" id="chkplus11">
			<label for="chkplus11" class="marr10">携帯認証</label>
			<input type="radio" value="0" name="chkplus" id="chkplus10">
			<label for="chkplus10">アイピン認証</label>
		</span>
	</div>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="確認" class="btn_large wset">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">キャンセル</a>
</div>
</form>

<script>
window.name ="Parent_window";
function fnPopup(val){
	switch(val){
		case 1: //携帯電話認証
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // アイピン認証
			window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.target = "popupIPIN2";
			document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
			document.fregister.submit();
			break;
	}
}

function fregister_submit(f)
{
	if(!f.agree.checked) {
		alert("会員加入の約款内容に同意しなければ会員加入できません。");
		f.agree.focus();
		return false;
	}

	if(!f.agree2.checked) {
		alert("個人情報の収集や利用内容に同意しなければ会員加入できません。");
		f.agree2.focus();
		return false;
	}

	<?php if($default['de_certify_use']) { ?>
    var chkplus = document.getElementsByName("chkplus");
    if(!chkplus[0].checked && !chkplus[1].checked) {
        alert("携帯電話認証およびアイピン認証後,会員加入が可能です。");
        return false;
    }
	if(chkplus[0].checked) {
        fnPopup(1);
		return false;
    }
	if(chkplus[1].checked) {
        fnPopup(0);
		return false;
    }
	<?php } else { ?>
	return true;
	<?php } ?>
}
</script>
