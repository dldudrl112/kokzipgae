<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>マイページ<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<h3>サイト利用情報入力</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">会員名</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" <?php echo $readonly; ?> class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">ID</th>
			<td><input type="text" name="id" value="<?php echo $member['id']; ?>" <?php echo $readonly; ?> class="frm_input" size="20" minlength="3" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">現在暗証番号</th>
			<td><input type="password" name="dbpasswd" required itemname="現在暗証番号" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">新しい暗証番号</th>
			<td><input type="password" name="passwd" class="frm_input" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">新しいパスワード確認</th>
			<td><input type="password" name="repasswd" class="frm_input" size="20" minlength="4" maxlength="20"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="mart30">個人情報の入力</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">生年月日</th>
			<td>
				<div class="ini_wrap">
				<table>
				<tr>
					<td><input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" required itemname="生年月日" class="frm_input required" size="8" maxlength="4"> 年</td>
					<td class="padl5"><input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" required itemname="生年月日" class="frm_input required" size="4" maxlength="2"> 月</td>
					<td class="padl5"><input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" required itemname="生年月日" class="frm_input required" size="4" maxlength="2"> 日</td>
					<td class="padl5">
						<select name="gender">
						<option value="">性別</option>
						<option value="M"<?php echo get_selected($member['gender'],"M"); ?>>男</option>
						<option value="F"<?php echo get_selected($member['gender'],"F"); ?>>女</option>
						</select>
					</td>
					<td class="padl5">
						<select name="birth_type">
						<option value="S"<?php echo get_selected($member['birth_type'],"S"); ?>>陽暦</option>
						<option value="L"<?php echo get_selected($member['birth_type'],"L"); ?>>旧暦</option>
						</select>
					</td>
				</tr>
				</table>
				</div>
			</td>
		</tr>
		<?php if($config['register_use_tel']) { ?>
		<tr>
			<th scope="row">電話番号</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>"<?php echo $config['register_req_tel']?' required':''; ?> itemname="電話番号" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20"></td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_hp']) { ?>
		<tr>
			<th scope="row">携帯電話</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>"<?php echo $config['register_req_hp']?' required':''; ?> itemname="携帯電話" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20">
				<input type="checkbox" value="Y" name="smsser" class="marl7"<?php echo $member['smsser'] == 'Y'?' checked':''; ?>> SMS を受信します。
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_email']) { ?>
		<tr>
			<th scope="row">Eメール</th>
			<td>
				<input type="text" name="email" value="<?php echo $member['email']; ?>"<?php echo $config['register_req_email']?' required':''; ?> email itemname="Eメール" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
				<input type="checkbox" value="Y" name="mailser" class="marl7"<?php echo $member['mailser'] == 'Y'?' checked':''; ?>> E-mailを受信します。
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_addr']) { ?>
		<tr>
			<th scope="row">住所</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="郵便番号" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
					<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">住所検索</a>
				</div>
				<div class="mart5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="住所" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> 基本アドレス
				</div>
				<div class="mart5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 詳細住所
				</div>
				<div class="mart5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60"> 参考項目
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="情報修正" id="btn_submit" class="btn_large wset" accesskey="s">
		<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">キャンセル</a>
	</div>
	</form>
</div>

<script>
function fregisterform_submit(f)
{
	if(f.passwd.value) {
		// パスワード検査
		if(f.passwd.value.length < 4) {
			alert("パスワードを4文字以上入力してください.");
			f.passwd.focus();
			return false;
		}

		if(f.passwd.value != f.repasswd.value) {
			alert("パスワードが同じではありません。");
			f.repasswd.focus();
			return false;
		}

		if(f.passwd.value.length > 0) {
			if(f.repasswd.value.length < 4) {
				alert("パスワードを4文字以上入力してください.");
				f.repasswd.focus();
				return false;
			}
		}
	}

	<?php if($config['register_use_email']) { ?>
	// 使用できないE-mailドメイン
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'は(は)お使いいただけないメールです。");
		f.email.focus();
		return false;
	}
	<?php } ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

// 禁止メールドメインチェック
function prohibit_email_check(email)
{
	email = email.toLowerCase();

	var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
	var s = prohibit_email.split(",");
	var tmp = email.split("@");
	var domain = tmp[tmp.length - 1]; // メールのドメインを得ている

	for(i=0; i<s.length; i++) {
		if(s[i] == domain)
			return domain;
	}
	return "";
}
</script>
