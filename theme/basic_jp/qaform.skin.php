<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="sit_qa_write" class="new_win">
    <h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<form name="fitemqa" id="fitemqa" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fitemqa_submit(this);">
	<input type="hidden" name="w" value="<?php echo $w; ?>">
	<input type="hidden" name="mb_id" value="<?php echo $member['id']; ?>">
	<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">	
	<input type="hidden" name="gs_use_aff" value="<?php echo $gs['use_aff']; ?>">
	<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">	
	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<input type="hidden" name="iq_id" value="<?php echo $iq_id; ?>">

	<div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="w90">
            <col>
        </colgroup>
		<tbody>
		<tr>
			<th scope="row">商品名</th>
			<td><?php echo $gs['gname']; ?></td>
		</tr>
		<tr>
			<th scope="row">オプション</th>
			<td>
				<select name="iq_ty" required itemname="文意類型">
					<option value=""<?php echo get_selected($iq_ty, ""); ?>>お問い合わせ有形(選択)</option>
					<option value="商品"<?php echo get_selected($iq_ty, "商品"); ?>>商品</option>
					<option value="配送"<?php echo get_selected($iq_ty, "配送"); ?>>배송</option>
					<option value="返品/払い戻しの/キャンセル"<?php echo get_selected($iq_ty, "返品/払い戻しの/キャンセル"); ?>>返品/払い戻しの/キャンセル</option>
					<option value="交換/変更"<?php echo get_selected($iq_ty, "交換/変更"); ?>>交換/変更</option>
					<option value="その他"<?php echo get_selected($iq_ty, "その他"); ?>>その他</option>
				</select>
				<input id="iq_secret" type="checkbox" name="iq_secret" value="1"
				<?php echo get_checked($iq_secret, '1'); ?> class="marl7">
				<label for="iq_secret">秘密文</label>
			</td>
		</tr>
		<tr>
			<th scope="row">姓名</th>
			<td><input type="text" name="iq_name" value="<?php echo $iq_name; ?>" required itemname="姓名" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Eメール</th>
			<td><input type="text" name="iq_email" value="<?php echo $iq_email; ?>" required email itemname="Eメール" class="frm_input required" size="30"></td>
		</tr>
		<tr>
			<th scope="row">携帯電話</th>
			<td><input type="text" name="iq_hp" value="<?php echo $iq_hp; ?>" required itemname="携帯電話" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">題目</th>
			<td><input type="text" name="iq_subject" value="<?php echo $iq_subject; ?>" required itemname="題目" class="frm_input wfull required"></td>
		</tr>
		<tr>
			<th scope="row">質問</th>
			<td><textarea name="iq_question" rows="10" required itemname="質問" class="frm_textbox wufll required"><?php echo $iq_question; ?></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

    <div class="win_btn">
        <input type="submit" value="作成完了" class="btn_lsmall">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">ウィンドウを閉じる</a>
    </div>
	</form>
</div>

<script>
function fitemqa_submit(f) {
	if(confirm("登録しますか?") == false)
		return false;

    return true;
}
</script>
