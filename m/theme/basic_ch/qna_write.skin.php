<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="m_bo_bg">
	<div class="m_bo_wrap">
		<table class="tbl03">
		<colgroup>
			<col style="width:70px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<th>提问类型</th>
			<td>
				<select name="catename" required itemname="提问类型" class="wfull">
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
			<th>题目</th>
			<td><input type="text" name="subject" required itemname="题目"></td>
		</tr>
		<tr>
			<th>提问内容</th>
			<td><textarea name="memo" required rows="7" itemname="提问内容"></textarea></td>
		</tr>
		<tr>
			<th>电子邮件</th>
			<td>
				<input type="text" name="email" value="<?php echo $member['email'];?>">
				<p class="mart5 fs12">
					<span class="marr10">请用邮件收下答复内容。?</span>
					<input type="checkbox" name="email_send_yes" value="1" id="email_send_yes" class="css-checkbox lrg"> <label for="email_send_yes" class="css-label">例子</label>
				</p>
			</td>
		</tr>
		<tr>
			<th>手机</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">能用短信收到答复吗?</span>
					<input type="checkbox" name="sms_send_yes" value="1" id="sms_send_yes" class="css-checkbox lrg"> <label for="sms_send_yes" class="css-label">例子</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="写作" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">취소</a>
	</div>	
</div>
</form>

<script>
function fqaform_submit(f) {
	if(confirm("确定要登记吗?") == false)
		return false;

	return true;
}
</script>
