<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>マイページ<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fcoupon" id="fcoupon" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fcoupon_submit(this);" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<p class="fc_e06 marb7">
		※ クーポン番号認証完了後のポイントがリアルタイム加算され、すぐに使用することができます。
	</p>
	<p class="cp_txt_bx bt">
		1. クーポンは現金に交換及び払い戻しが不可能です.<br>
		2. クーポン番号は大文字/小文字を区別することができますので、受信した番号のまま入力してください。
	</p>

	<p class="cp_txt_bx tac">
		<input type="text" name="gi_num1" required itemname="クーポン番号" maxlength="4" class="frm_cp required" onkeyup="if(this.value.length==4) document.fcoupon.gi_num2.focus();">
		<span>-</span>
		<input type="text" name="gi_num2" required itemname="クーポン番号" maxlength="4" class="frm_cp required" onkeyup="if(this.value.length==4) document.fcoupon.gi_num3.focus();">
		<span>-</span>
		<input type="text" name="gi_num3" required itemname="クーポン番号" maxlength="4" class="frm_cp required" onkeyup="if(this.value.length==4) document.fcoupon.gi_num4.focus();">
		<span>-</span>
		<input type="text" name="gi_num4" required itemname="クーポン番号" maxlength="4" class="frm_cp required">
		<input type="submit" value="認証" class="btn_lsmall wset">
	</p>
	</form>

	<script>
	function fcoupon_submit(f) {
		if(confirm("認証する場合は、 /'確認'ボタンをクリックしてください!") == false)
			return false;

		return true;
	}
	</script>

	<?php
	$sql_common = " from shop_gift ";
	$sql_search = " where mb_id = '$member[id]' ";

	if($stx) {
		$sql_search .= " and gi_num like '$stx%' ";
	}

	$sql = " select count(*) as cnt $sql_common $sql_search ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 30;
	$total_page = ceil($total_count / $rows); // 全ページ計算
	if($page == "") { $page = 1; } // ページが存在しない場合、最初のページ（1ページ）
	$from_record = ($page - 1) * $rows; // 開始列を求める
	$num = $total_count - (($page-1)*$rows);

	$sql = " select * $sql_common $sql_search order by no desc limit $from_record, $rows ";
	$result = sql_query($sql);
	?>

	<div class="top_sch mart20">
		<form name="fsearch2" id="fsearch2" method="post">
		<p class="fl padt10">全体 <b class="fc_255"><?php echo number_format($total_count); ?></b>件のクーポン内容があります。</p>
		<p class="fr">
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" placeholder="クーポン番号">
			<input type="submit" value="検索" class="btn_small grey">
		</p>
		</form>
	</div>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="6%">
			<col>
			<col width="12%">
			<col width="11%">
			<col width="11%">
			<col width="8%">
			<col width="18%">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">番号</th>
			<th scope="col">クーポン番号</th>
			<th scope="col">金額</th>
			<th scope="col">開始日</th>
			<th scope="col">終了日</th>
			<th scope="col">認証状態</th>
			<th scope="col">登録日</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if(is_null_time($row['mb_wdate'])) {
				$row['mb_wdate'] = '';
			}

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="tac"><?php echo $num--; ?></td>
			<td><?php echo $row['gi_num']; ?></td>
			<td class="tac"><?php echo display_price($row['gr_price']); ?></td>
			<td class="tac"><?php echo $row['gr_sdate']; ?></td>
			<td class="tac"><?php echo $row['gr_edate']; ?></td>
			<td class="tac"><?php echo $row['gi_use']?'yes':''; ?></td>
			<td class="tac"><?php echo $row['mb_wdate']; ?></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="7" class="empty_list">内訳がありません。</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&page=');
	?>
</div>
