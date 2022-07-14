<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">ホーム <i class="ionicons ion-ios-arrow-right"></i> ログイン</p>
<h2 class="stit">LOGIN</h2>
<ul class="login_tab">
	<li data-tab="login_fld"><span>会員ログイン</span></li>
	<li data-tab="guest_fld"><span>非会員注文照会</span></li>
</ul>

<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
<input type="hidden" name="url" value="<?php echo $login_url; ?>">

<div class="login_wrap" id="login_fld">		
	<dl class="log_inner">
		<dt>会員ログイン</dt>
		<dd class="stxt">ログインすれば様々なサービスと特典が受けられます。</dd>
		<dd>
			<label for="login_id" class="sound_only">会員ID</label>
			<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="アイディ">	
		</dd>
		<dd>
			<label for="login_pw" class="sound_only">パスワード</label>
			<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="パスワード">		
		</dd>
		<dd><button type="submit" class="btn_large">ログイン</button></dd>
		<?php if(preg_match("/orderform.php/", $url)) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderform.php" class="btn_large red wfull">非会員で仕入れる</a></dd>
		<?php } ?>
		<dd class="log_op">
			<span><input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login">自動ログイン</label></span>	
			<span class="fr"><a href="<?php echo TB_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;">ID/パスワード探し</a></span>
		</dd>
	</dl>
	<?php if($default['de_sns_login_use']) { ?>
	<div class="sns_btn">
		<h3>SNS 勘定ログイン</h3>
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</div>
	<?php } ?>	
</div>
</form>

<form name="forderinquiry" method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" autocomplete="off">
<div class="login_wrap" id="guest_fld">	
	<dl class="log_inner">
		<dt>非会員注文照会</dt>
		<dd class="stxt">
			決済完了後,ご案内致しました注文番号とご注文決済時に作成した暗証番号を入力してください。
		</dd>
		<dd>
			<label for="od_id" class="sound_only">注文番号</label>
            <input type="text" name="od_id" id="od_id" class="frm_input" placeholder="注文番号">		
		</dd>
		<dd>
			<label for="od_pwd" class="sound_only">パスワード</label>
            <input type="password" name="od_pwd" id="od_pwd" class="frm_input" placeholder="パスワード">		
		</dd>
		<dd><button type="submit" class="btn_large">確認</button></dd>
	</dl>	
</div>
</form>

<div class="log_bt_box">
	会員加入して豊かな恩恵を享受してください。
	<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn_lsmall bx-white marl15">会員加入</a>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("自動ログインを使用すると,次から会員IDとパスワードを入力する必要はありません。\n\n公共の場所では個人情報が流出することがありますので,ご使用はご遠慮ください。\n\n自動ログインをお使いになりますか。?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert(/'IDを入力してください.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert(/'暗証番号を入力してください。');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert(/'注文番号を入力してください。');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert(/'暗証番号をご入力ください。');
		f.od_pwd.focus();
		return false;
	}

    return true;
}

$(document).ready(function(){
	$(".login_tab>li:eq(0)").addClass('active');
	$("#login_fld").addClass('active');

	$(".login_tab>li").click(function() {
		var activeTab = $(this).attr('data-tab');
		$(".login_tab>li").removeClass('active');
		$(".login_wrap").removeClass('active');
		$(this).addClass('active');
		$("#"+activeTab).addClass('active');
	});
});
</script>
