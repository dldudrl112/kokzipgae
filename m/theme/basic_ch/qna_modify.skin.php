<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">

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
				<select name="catename" class="wfull">
					<option value="">请选择欲咨询的类型。</option>
					<?php
					$sql = "select * from shop_qa_cate where isuse='Y'";
					$res = sql_query($sql);
					while($row=sql_fetch_array($res)) {
						echo "<option ".get_selected($qa['catename'],$row['catename'])." value='$row[catename]'>$row[catename]</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>题目</th>
			<td><input type="text" name="subject" value="<?php echo $qa['subject']; ?>"></td>
		</tr>
		<tr>
			<th>内容</th>
			<td><textarea name="memo" rows="7"><?php echo $qa['memo']; ?></textarea></td>
		</tr>
		<tr>
			<th>电子邮件</th>
			<td>
				<input type="text" name="email" value="<?php echo $qa['email']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">请用邮件收下答复内容。?</span>
					<input type="checkbox" name="email_send_yes" value="1" <?php echo get_checked($qa['email_send_yes'],'1'); ?> id="email_send_yes" class="css-checkbox lrg"> <label for="email_send_yes" class="css-label">예</label>
				</p>
			</td>
		</tr>
		<tr>
			<th>手机</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $qa['cellphone']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">你想在信中收到答案吗?</span>
					<input type="checkbox" name="sms_send_yes" value="1" <?php echo get_checked($qa['sms_send_yes'],'1'); ?> id="sms_send_yes" class="css-checkbox lrg"> <label for="sms_send_yes" class="css-label">例子</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">	
		<input type="submit" value="修整" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">取消</a>
	</div>	
</div>
</form>

<script>
function fqaform_submit(f) {
	if(confirm("确定要修改吗?") == false)
		return false;

	return true;
}
</script>
