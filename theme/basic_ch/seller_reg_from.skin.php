<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TB_IMG_URL; ?>/seller_reg_from.gif"></div>

<form name="fsellerform" id="fsellerform" action="<?php echo $from_action_url; ?>" onsubmit="return fsellerform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fsellerform_term">
	<h2>使用条款</h2>
	<textarea readonly><?php echo $config['seller_reg_agree']; ?></textarea>
	<fieldset class="fsellerform_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">我已阅读上述内容并同意这些条款。</label>
	</fieldset>
</div>

<h3 class="anc_tit">业主信息输入</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_seller_item">提供商品</label></th>
		<td><input type="text" name="seller_item" id="reg_seller_item" required itemname="提供商品" class="required frm_input" size="30" placeholder="例子) 家用电器"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_name">企业(法人)名</label></th>
		<td><input type="text" name="company_name" id="reg_company_name" required itemname="企业(法人)名" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_owner">代表人名</label></th>
		<td><input type="text" name="company_owner" id="reg_company_owner" required itemname="代表人名" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_saupja_no">营业执照号</label></th>
		<td><input type="text" name="company_saupja_no" id="reg_company_saupja_no" required itemname="营业执照号" class="required frm_input" size="30" placeholder="例子) 206-23-12552"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_item">业态</label></th>
		<td><input type="text" name="company_item" id="reg_company_item" required itemname="业态" class="required frm_input" size="30" placeholder="例子) 服务业"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_service">项目</label></th>
		<td><input type="text" name="company_service" id="reg_company_service" required itemname="项目" class="required frm_input" size="30" placeholder="例子) 전자상거래업"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_tel">电话号码</label></th>
		<td><input type="text" name="company_tel" id="reg_company_tel" class="frm_input" size="30" placeholder="例子) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_fax">传真号码</label></th>
		<td><input type="text" name="company_fax" id="reg_company_fax" class="frm_input" size="30" placeholder="例子) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row">营业场所地址</th>
		<td>
			<label for="reg_company_zip" class="sound_only">邮政编码</label>
			<input type="text" name="company_zip" id="reg_company_zip" required itemname="邮政编码" class="required frm_input" size="8" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fsellerform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');">地址检索</button><br>

			<label for="reg_company_addr1" class="sound_only">住址</label>
			<input type="text" name="company_addr1" id="reg_company_addr1" required itemname="基本地址" class="required frm_input frm_address" size="60" readonly> 基本地址<br>

			<label for="reg_company_addr2" class="sound_only">详细地址</label>
			<input type="text" name="company_addr2" id="reg_company_addr2" class="frm_input frm_address" size="60"> 详细地址<br>

			<label for="reg_company_addr3" class="sound_only">参考项目</label>
			<input type="text" name="company_addr3" id="reg_company_addr3" class="frm_input frm_address" size="60" readonly> 参考项目
			<input type="hidden" name="company_addr_jibeon" value="">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_hompage">网页</label></th>
		<td><input type="text" name="company_hompage" id="reg_company_hompage" class="frm_input" size="30" placeholder="例子) http://homepage.com"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_memo">传达事项</label></th>
		<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox wfull h60"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">输入入账账户信息</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_bank_name">银行名称</label></th>
		<td><input type="text" name="bank_name" id="reg_bank_name" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_account">账号</label></th>
		<td><input type="text" name="bank_account" id="reg_bank_account" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_holder">存款主名</label></th>
		<td><input type="text" name="bank_holder" id="reg_bank_holder" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">输入负责人信息</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_info_name">负责人名</label></th>
		<td><input type="text" name="info_name" id="reg_info_name" required itemname="负责人名" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_tel">负责人手机</label></th>
		<td><input type="text" name="info_tel" id="reg_info_tel" required itemname="负责人手机" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_email">负责人电邮</label></th>
		<td><input type="text" name="info_email" id="reg_info_email" required email itemname="负责人电邮" class="required frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="申请" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">取消</a>
</div>
</form>

<script>
function fsellerform_submit(f) {
	if(!f.agree.checked) {
		alert("只有同意条款才能申请.");
		f.agree.focus();
		return false;
	}

	if(confirm("请确认输入的事项是否正确。\n\n确定要申请吗??") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}
</script>
