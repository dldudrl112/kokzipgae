<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">关闭窗口</a>
</h2>

<form name="forderreview" id="sit_review" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return forderreview_submit(this);">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="me_id" value="<?php echo $me_id; ?>">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<table class="tbl_review">
<colgroup>
	<col width="80px">
	<col>
</colgroup>
<tbody>
<tr>
	<td class="image"><?php echo get_it_image($gs_id, $gs['simg1'], 80, 80); ?></td>
	<td class="gname">
		<?php echo get_text($gs['gname']); ?>
		<p class="bold mart5"><?php echo mobile_price($gs_id); ?></p>
	</td>
</tr>
</tbody>
</table>

<h2 class="anc_tit mart20">购买后记用</h2>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col width="80px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="wr_content">内容</label></th>
		<td><textarea name="wr_content" id="wr_content" class="frm_textbox wufll"><?php echo $wr_content; ?></textarea></td>
	</tr>
	<tr>
		<th scope="row"><label for="wr_score">评分</label></th>
		<td>
			<select name="wr_score" id="wr_score">
				<option value="">评分 选择</option>
				<option value="5"<?php echo get_selected($wr_score, '5'); ?>><?php echo $gw_star[5]; ?></option>
				<option value="4"<?php echo get_selected($wr_score, '4'); ?>><?php echo $gw_star[4]; ?></option>
				<option value="3"<?php echo get_selected($wr_score, '3'); ?>><?php echo $gw_star[3]; ?></option>
				<option value="2"<?php echo get_selected($wr_score, '2'); ?>><?php echo $gw_star[2]; ?></option>
				<option value="1"<?php echo get_selected($wr_score, '1'); ?>><?php echo $gw_star[1]; ?></option>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<button type="submit" class="btn_medium">확인</button>
	<button type="button" onclick="window.close();" class="btn_medium bx-white">取消</button>
</div>
</form>

<script>
function forderreview_submit(f) {
	if(!f.wr_content.value) {
		alert(/'请输入内容。');
		f.wr_content.focus();
		return false;
	}

	if(!getSelectVal(f["wr_score"])){
		alert(/'请选择评分。');
		f.wr_score.focus();
		return false;
	}

	if(confirm("确定要登记吗?") == false)
		return false;

    return true;
}
</script>