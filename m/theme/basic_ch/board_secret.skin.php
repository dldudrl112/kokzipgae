<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<form name="frm" action="<?php echo TB_MBBS_URL; ?>/board_read.php" method="get" onsubmit="return frm_submit(this);">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="boardid" value="<?php echo $boardid;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="m_bo_pop">
	<h2>密码确认</h2>
	<p>这篇文章是秘密文章。<br>写作时请输入输入的密码。</p>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="password" name="inpasswd" placeholder="密码">
	</div>
	<p class="btn_area" style="margin-top:8px;">
		<input type='submit' value='확인' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">向后走</a>
	</p>
</div>
</form>

<script>
function frm_submit(f)
{
	if(!f.inpasswd.value)
	{
		alert(/'请输入密码。');
		f.inpasswd.focus();
		return false;
	}

	if(answer==true)
	{	return true;  }
	else
	{	return false; }
}
</script>
