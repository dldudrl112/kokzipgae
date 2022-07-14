<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<div class="mb_login">
	<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
	<input type="hidden" name="url" value="<?php echo $login_url; ?>">
	<section class="login_fs">
		<p class="mart15">
			<label for="login_id" class="sound_only">会员ID</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="用户名">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">密码</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="密码">		
		</p>	
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">自动登录</label>
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">登录</button></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium wfull bx-white">注册会员</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">ID/寻找密码</a></span></p>
	</section>
	<?php if($default['de_sns_login_use']) { ?>
	<p class="sns_btn">
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
	<?php } ?>
	</form>

	<?php if(preg_match("/orderform.php/", $url)) { ?>
	<section class="mb_login_od">
		<h3>非会员购买</h3>
		<p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">以非会员身份购买</a></p>
	</section>
	<?php } else if(preg_match("/orderinquiry.php$/", $url)) { ?>
	<form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
	<section class="mb_login_od">
		<h3>非会员订单查询</h3>
		<p class="mart15">
			<label for="od_id" class="sound_only">订单号码</label>
            <input type="text" name="od_id" id="od_id" placeholder="订单号码">			
		</p>
		<p class="mart3">
			<label for="od_pwd" class="sound_only">密码</label>
            <input type="password" name="od_pwd" id="od_pwd" placeholder="密码">		
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">确认</button></p>
	</section>
	</form>
	<?php } ?>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("使用自动登录后无需输入会员账号和密码。\n\n公共场所个人信息可能会泄露,请勿使用。确定要使用\n\n自动登录?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert(/'请输入ID。');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert(/'请输入密码。');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert(/'请输入订单编号。');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert(/'请输入密码。');
		f.od_pwd.focus();
		return false;
	}

    return true;
}
</script>
