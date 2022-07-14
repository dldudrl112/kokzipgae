<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>客户中心<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
	<input type="hidden" name="mode" value="w">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<div class="tbl_frm02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">提问类型</th>
			<td>
				<select name="catename" required itemname="提问类型">
					<option value="">请选择欲咨询的类型。</option>
					<?php
					$sql = "select * from shop_qa_cate where isuse='Y'";
					$res = sql_query($sql);
					while($row=sql_fetch_array($res)) {
						echo "<option value='$row[catename]'>$row[catename]</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">题目</th>
			<td><input type="text" name="subject" required itemname="题目" class="frm_input wfull required"></td>
		</tr>
		<tr>
			<th scope="row">内容</th>
			<td><textarea name="memo" required itemname="内容" class="frm_textbox wfull required"></textarea></td>
		</tr>
		<tr>
			<th scope="row">电子邮件</th>
			<td class="td_label">
				<input type="text" name="email" value="<?php echo $member['email']; ?>" class="frm_input">
				<p class="mart7">
					<span class="marr10">请用邮件收下答复内容。?</span>
					<label><input type="radio" name="email_send_yes" value="1"> 例子</label>
					<label><input type="radio" name="email_send_yes" value="0" checked> 不</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">手机</th>
			<td class="td_label">
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" class="frm_input">
				<p class="mart7">
					<span class="marr10">是否答复,请用短信收下。?</span>
					<label><input type="radio" name="sms_send_yes" value="1"> 例子</label>
					<label><input type="radio" name="sms_send_yes" value="0" checked> 不</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="写作" class="btn_lsmall">
		<a href="javascript:history.go(-1);" class="btn_lsmall bx-white">取消</a>
	</div>
	</form>
</div>

<script>
function fqaform_submit(f) {
	if(confirm("确定要登记吗?") == false)
		return false;

	return true;
}
</script>
