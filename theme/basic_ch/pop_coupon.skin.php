<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="new_win">
    <h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<div class="win_desc marb10">
		<p class="bx-danger">
			* 购买该商品时可使用的优惠券。 下载后订购时使用。!<br>
			* 发行的优惠券是 <b>[我的页面 > 优惠券管理]</b> 可在 中确认。
		</p>
	</div>
		
	<div class="tbl_head01 tbl_wrap bt_nolne">
		<table>
		<colgroup>
			<col class="w80">
			<col>
			<col class="w70">
		</colgroup>
		<tbody>
		<tr>
			<td class="tac"><?php echo get_it_image($gs['index_no'], $gs['simg1'], 60, 60); ?></td>
			<td class="td_name"><?php echo get_text($gs['gname']); ?></td>
			<td class="tac"><?php echo get_price($gs['index_no'])?></td>
		</tr>
		</tbody>
		</table>
	</div>	

	<div class="win_desc mart20">
		<p class="pg_cnt">
			<em>全体 <?php echo number_format($total_count); ?>个</em> 早会
		</p>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table>
		<colgroup>
			<col>
			<col class="w90">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">优惠券名</th>
			<th scope="col">受到券</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$cp_id = $row['cp_id'];

			$str  = "";
			$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";
			if($row['cp_explan'])
				$str .= "<div class='part5 fc_197'>&#183; ".get_text($row['cp_explan'])."</div>";

			// 是否同时使用
			$str .= "<div class='part5 fc_eb7'>&#183; ";
			if(!$row['cp_dups']) {
				$str .= /'相同订单可与其他优惠券同时使用';
			} else {
				$str .= /'同一订单不能与其他优惠券同时使用 (仅此优惠券可使用)';
			}
			$str .= "</div>";

			// 优惠券时间
			$str .= "<div class='part5'>&#183; 优惠券发行期间 : ";
			if($row['cp_type'] != '3') {
				if($row['cp_pub_sdate'] == '9999999999') $cp_pub_sdate = '';
				else $cp_pub_sdate = $row['cp_pub_sdate'];

				if($row['cp_pub_edate'] == '9999999999') $cp_pub_edate = '';
				else $cp_pub_edate = $row['cp_pub_edate'];

				if($row['cp_pub_sdate'] == '9999999999' && $row['cp_pub_edate'] == '9999999999')
					$str .= "无限制";
				else
					$str .= $cp_pub_sdate." ~ ".$cp_pub_edate;

				// 优惠券发行日期
				if($row['cp_type'] == '1') {
					$str .= "&nbsp;-&nbsp;每周 (".$row['cp_week_day'].")";
				}
			} else {
				$str .= "生日 (".$row['cp_pub_sday']."一天 ~ ".$row['cp_pub_eday']."直到之后)";
			}
			$str .= "</div>";


			// 优惠券有效期
			$str .= "<div class='part5'>&#183; 优惠券有效期 : ";
			if(!$row['cp_inv_type']) {
				// 日期
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
				else $cp_inv_sdate = $row['cp_inv_sdate'];

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
				else $cp_inv_edate = $row['cp_inv_edate'];

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$str .= '无限制';
				else
					$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate ;

				// 时间段
				$str .= "&nbsp;(时间段 : ";
				if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
				else $cp_inv_shour1 = $row['cp_inv_shour1'] . "从几点开始";

				if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
				else $cp_inv_shour2 = $row['cp_inv_shour2'] . "到几点为止";

				if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour2'] == '99') $str .= '无限制';
				else $str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
				$str .= ")";
			} else {
				$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",time()));
				$str .= '下载完成后 ' . $row['cp_inv_day']. /'可每日使用, 到期日('.$cp_inv_day.')';
			}
			$str .= "</div>";

			// 혜택
			$str .= "<div class='part5'>&#183; ";
			if(!$row['cp_sale_type']) {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(最大 ".display_price($row['cp_sale_amt_max'])."到为止打折)";
				else
					$cp_sale_amt_max = "";

				$str .= $row['cp_sale_percent']. '% 折扣' . $cp_sale_amt_max;
			} else {
				$str .= display_price($row['cp_sale_amt']). /' 折扣';
			}
			$str .= "</div>";

			// 最大金额
			if($row['cp_low_amt'] > 0) {
				$str .= "<div class='part5'>&#183; ".display_price($row['cp_low_amt'])." 以上购买时</div>";
			}

			// 可使用对象
			$str .= "<div class='part5'>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

			$s_upd = "<a href=\"javascript:post_update('".TB_SHOP_URL."/pop_coupon_update.php', '$cp_id');\" class=\"btn_small red\">下载</a>";

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td><?php echo $str; ?></td>
			<td class="tac"><?php echo $s_upd; ?></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="2" class="empty_list">没有资料。</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>

    <div class="win_btn">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">关窗</a>
    </div>
</div>

<script>
function post_update(action_url, val) {
	var f = document.fpost;
	f.cp_id.value = val;
	f.action = action_url;
	f.submit();
}
</script>

<form name="fpost" method="post">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="cp_id" value="">
</form>
