<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>マイページ<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<nav id="tab_cate">
		<ul id="tab_cate_ul">
			<li<?php echo $selected1; ?>><a href="<?php echo TB_SHOP_URL; ?>/coupon.php">使用可能なクーポン</a></li>
			<li<?php echo $selected2; ?>><a href="<?php echo TB_SHOP_URL; ?>/coupon.php?sca=1">使用完了および期限満了クーポン</a></li>
		</ul>
	</nav>

	<p class="pg_cnt">
		<em>全体 <?php echo number_format($total_count); ?>件</em>のクーポン内訳があります。
	</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w50">
			<col>
			<col>
			<col>
			<col>
			<col class="w140">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">番号</th>
			<th scope="col">割引クーポン</th>
			<th scope="col">割引金額(率)</th>
			<th scope="col">使用可能対象</th>
			<th scope="col">使用期限</th>
			<th scope="col">取得日付</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {

			// 割引金額(率)
			if($row['cp_sale_type'] == '0') {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(最大 ".display_price($row['cp_sale_amt_max']).")";
				else
					$cp_sale_amt_max = "";

				$sale_amt = $row['cp_sale_percent']. '%' . $cp_sale_amt_max;
			} else {
				$sale_amt = display_price($row['cp_sale_amt']);
			}

			// クーポン使用期限
			if($row['cp_inv_type'] == '0') {
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = /'無制限';
				else $cp_inv_sdate = date_conv($row['cp_inv_sdate'],4);

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = /'無制限';
				else $cp_inv_edate = date_conv($row['cp_inv_edate'],4);

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$inv_date = /'無制限';
				else
					$inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
			} else {
				$inv_date = /'ダウンロード後 ' . $row['cp_inv_day']. /'日刊';
			}

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="tac"><?php echo $num--; ?></td>
			<td><a href='<?php echo TB_SHOP_URL; ?>/coupon_goods.php?lo_id=<?php echo $row['lo_id']; ?>' onclick="win_open(this,'coupon_goods','800','800','yes');return false;"><?php echo get_text(cut_str($row['cp_subject'],44)); ?></a></td>
			<td><?php echo $sale_amt; ?></td>
			<td class="tac"><?php echo $u_part[$row['cp_use_part']]; ?></td>
			<td class="tac"><?php echo $inv_date; ?></td>
			<td class="tac"><?php echo $row['cp_wdate']; ?></td>
		</tr>
		<?php
		}
		if($i == 0)
			echo '<tr><td colspan="6" class="empty_list">資料がありません。</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&page=');
	?>
</div>
