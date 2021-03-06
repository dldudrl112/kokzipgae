<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可

include_once("./_head.php");
?>

<form name="flogin" action="<?php echo TB_HTTPS_MBBS_URL; ?>/login_check.php" onsubmit="return flogin_submit(this);" method="post">
<div class="mb_login">
	<section class="login_fs">
		<h2 class="log_tit">MEMBER <strong>LOGIN</strong></h2>
		<p class="mart15">
			<label for="login_id" class="sound_only">会員ID</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="아이디">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">パスワード</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="パスワード">
		</p>
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">自動ログイン</label>
		</p>
		<p class="mart10"><input type="submit" value="ログイン" class="btn_large wset wfull"></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white wfull">会員加入</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">ID/パスワード探し</a></span></p>
	</section>
</div>
</form>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("自動ログインを使用すると,次から会員IDとパスワードを入力する必要はありません。\n\n公共の場所では個人情報が流出することがありますので,ご使用はご遠慮ください。.\n\n自動ログインを使います?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value){
		alert(/'IDを入力してください。');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value){
		alert(/'暗証番号を入力してください。');
		f.mb_password.focus();
		return false;
	}

	return true;
}
</script>

<?php
include_once("./_tail.php");
?>