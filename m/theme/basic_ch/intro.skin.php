<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问

include_once("./_head.php");
?>

<form name="flogin" action="<?php echo TB_HTTPS_MBBS_URL; ?>/login_check.php" onsubmit="return flogin_submit(this);" method="post">
<div class="mb_login">
	<section class="login_fs">
		<h2 class="log_tit">MEMBER <strong>LOGIN</strong></h2>
		<p class="mart15">
			<label for="login_id" class="sound_only">会员用户名</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="아이디">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">密码</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="密码">
		</p>
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">自动登录</label>
		</p>
		<p class="mart10"><input type="submit" value="登录" class="btn_large wset wfull"></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white wfull">注册会员</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">查找ID /密码</a></span></p>
	</section>
</div>
</form>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("使用自动登录无需输入会员账号和密码。个人信息可能会从\n\n工场泄露,请勿使用。确定要使用\n\n自动登录?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value){
		alert(/'请输入ID。');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value){
		alert(/'请输入密码。');
		f.mb_password.focus();
		return false;
	}

	return true;
}
</script>

<?php
include_once("./_tail.php");
?>