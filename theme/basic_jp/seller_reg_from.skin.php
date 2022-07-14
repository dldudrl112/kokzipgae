<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TB_IMG_URL; ?>/seller_reg_from.gif"></div>

<form name="fsellerform" id="fsellerform" action="<?php echo $from_action_url; ?>" onsubmit="return fsellerform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fsellerform_term">
	<h2>利用規約</h2>
	<textarea readonly><?php echo $config['seller_reg_agree']; ?></textarea>
	<fieldset class="fsellerform_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">上記の内容を読んで約款に同意します。</label>
	</fieldset>
</div>

<h3 class="anc_tit">事業者情報入力</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_seller_item">提供商品</label></th>
		<td><input type="text" name="seller_item" id="reg_seller_item" required itemname="提供商品" class="required frm_input" size="30" placeholder="例) 家電製品"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_name">会社名</label></th>
		<td><input type="text" name="company_name" id="reg_company_name" required itemname="会社名" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_owner">代表者名</label></th>
		<td><input type="text" name="company_owner" id="reg_company_owner" required itemname="代表者名" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_saupja_no">事業者登録番号</label></th>
		<td><input type="text" name="company_saupja_no" id="reg_company_saupja_no" required itemname="事業者登録番号" class="required frm_input" size="30" placeholder="例) 206-23-12552"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_item">業態</label></th>
		<td><input type="text" name="company_item" id="reg_company_item" required itemname="業態" class="required frm_input" size="30" placeholder="例) サービス業"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_service">種目</label></th>
		<td><input type="text" name="company_service" id="reg_company_service" required itemname="種目" class="required frm_input" size="30" placeholder="例) 電子商取引業"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_tel">電話番号</label></th>
		<td><input type="text" name="company_tel" id="reg_company_tel" class="frm_input" size="30" placeholder="例) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_fax">ファクス番号</label></th>
		<td><input type="text" name="company_fax" id="reg_company_fax" class="frm_input" size="30" placeholder="例) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row">事業所住所</th>
		<td>
			<label for="reg_company_zip" class="sound_only">郵便番号</label>
			<input type="text" name="company_zip" id="reg_company_zip" required itemname="郵便番号" class="required frm_input" size="8" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fsellerform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');">住所検索</button><br>

			<label for="reg_company_addr1" class="sound_only">住所</label>
			<input type="text" name="company_addr1" id="reg_company_addr1" required itemname="基本アドレス" class="required frm_input frm_address" size="60" readonly> 基本アドレス<br>

			<label for="reg_company_addr2" class="sound_only">ギア島</label>
			<input type="text" name="company_addr2" id="reg_company_addr2" class="frm_input frm_address" size="60"> ギア島<br>

			<label for="reg_company_addr3" class="sound_only">参考項目</label>
			<input type="text" name="company_addr3" id="reg_company_addr3" class="frm_input frm_address" size="60" readonly> 参考項目
			<input type="hidden" name="company_addr_jibeon" value="">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_hompage">ホームページ</label></th>
		<td><input type="text" name="company_hompage" id="reg_company_hompage" class="frm_input" size="30" placeholder="例) http://homepage.com"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_memo">伝達事項</label></th>
		<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox wfull h60"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">入金口座情報入力</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_bank_name">銀行名</label></th>
		<td><input type="text" name="bank_name" id="reg_bank_name" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_account">口座番号</label></th>
		<td><input type="text" name="bank_account" id="reg_bank_account" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_holder">預金主名</label></th>
		<td><input type="text" name="bank_holder" id="reg_bank_holder" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">担当者情報入力</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_info_name">担当者名</label></th>
		<td><input type="text" name="info_name" id="reg_info_name" required itemname="担当者名" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_tel">担当者携帯電話</label></th>
		<td><input type="text" name="info_tel" id="reg_info_tel" required itemname="担当者携帯電話" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_email">担当者電子メール</label></th>
		<td><input type="text" name="info_email" id="reg_info_email" required email itemname="担当者電子メール" class="required frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="申請" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">キャンセル</a>
</div>
</form>

<script>
function fsellerform_submit(f) {
	if(!f.agree.checked) {
		alert("約款に同意しなければ申請できません。");
		f.agree.focus();
		return false;
	}

	if(confirm("入力された事項が正しいか確認してください。\n\n\n申請しますか。?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}
</script>
