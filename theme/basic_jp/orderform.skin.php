<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- 注文書作成のスタート { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ ご注文の商品の内訳に <em>数量および注文金額</em>この間違えないのか必ず確認してください。
</p>

<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w120">
		<col>
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">イメージ</th>
		<th scope="col">商品/オプション情報</th>
		<th scope="col">数量</th>
		<th scope="col">商品金額</th>
		<th scope="col">小計</th>
		<th scope="col">ポイント</th>
		<th scope="col">配送費</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$tot_point = 0;
	$tot_sell_price = 0;
	$tot_opt_price = 0;
	$tot_sell_qty = 0;
	$tot_sell_amt = 0;
	$seller_id = array();

	$sql = " select *
			   from shop_cart
			  where index_no IN ({$ss_cart_id})
				and ct_select = '0'
			  group by gs_id
			  order by index_no ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);

		// 合計金額計算
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
		                SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_supply_price) * ct_qty))) as supply_price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
				   from shop_cart
				  where gs_id = '$row[gs_id]'
				    and ct_direct = '$set_cart_id'
				    and ct_select = '0'";
		$sum = sql_fetch($sql);

		$it_name = stripslashes($gs['gname']);
		$it_options = print_item_options($row['gs_id'], $set_cart_id);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}

		if($is_member) {
			$point = $sum['point'];
		}

		$supply_price = $sum['supply_price'];
		$sell_price = $sum['price'];
		$sell_opt_price = $sum['opt_price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// 配送費
		if($gs['use_aff'])
			$sr = get_partner($gs['mb_id']);
		else
			$sr = get_seller_cd($gs['mb_id']);

		$info = get_item_sendcost($sell_price);
		$item_sendcost[] = $info['pattern'];

		$seller_id[$i] = $gs['mb_id'];

		$href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="tac">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
			<?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?>
		</td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($sell_qty); ?></td>
		<td class="tar"><?php echo number_format($sell_amt); ?></td>
		<td class="tar"><?php echo number_format($sell_price); ?></td>
		<td class="tar"><?php echo number_format($point); ?></td>
		<td class="tar"><?php echo number_format($info['price']); ?></td>
	</tr>
	<?php
		$tot_point += (int)$point;
		$tot_sell_price += (int)$sell_price;
		$tot_opt_price += (int)$sell_opt_price;
		$tot_sell_qty += (int)$sell_qty;
		$tot_sell_amt += (int)$sell_amt;
	}

	// 配送費検査
	$send_cost = 0;
	$com_send_cost = 0;
	$sep_send_cost = 0;
	$max_send_cost = 0;

	$k = 0;
	$condition = array();
	foreach($item_sendcost as $key) {
		list($userid, $bundle, $price) = explode('|', $key);
		$condition[$userid][$bundle][$k] = $price;
		$k++;
	}

	$com_array = array();
	$val_array = array();
	foreach($condition as $key=>$value) {
		if($condition[$key][/'束']) {
			$com_send_cost += array_sum($condition[$key][/'束']); // まとめ配送合算
			$max_send_cost += max($condition[$key][/'束']); // 最大の配送費の合計
			$com_array[] = max(array_keys($condition[$key][/'束'])); // max key
			$val_array[] = max(array_values($condition[$key][/'束']));// max value
		}
		if($condition[$key][/'個別']) {
			$sep_send_cost += array_sum($condition[$key][/'個別']); // おまとめ梱包不可合算
			$com_array[] = array_keys($condition[$key][/'個別']); // すべての配列 key
			$val_array[] = array_values($condition[$key][/'個別']); // すべての配列 value
		}
	}

	$baesong_price = get_tune_sendcost($com_array, $val_array);

	$send_cost = $com_send_cost + $sep_send_cost; // 総送料合計
	$tot_send_cost = $max_send_cost + $sep_send_cost; // 最終送料
	$tot_final_sum = $send_cost - $tot_send_cost; // 送料割引
	$tot_price = $tot_sell_price + $tot_send_cost; // 決済予定金額
	?>
	</tbody>
	</table>
</div>

<div id="sod_bsk_tot">
	<table class="wfull">
	<tr>
		<td class="w50p">
			<h2 class="anc_tit">買い物籠の商品統計</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">ポイント</th>
					<td class="tar">積立ポイント</td>
					<td class="tar bl"><?php echo display_point($tot_point); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">商品</th>
					<td class="tar">商品金額合計</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_amt); ?></td>
				</tr>
				<tr>
					<td class="tar">オプション金額合計</td>
					<td class="tar bl"><?php echo display_price2($tot_opt_price); ?></td>
				</tr>
				<tr>
					<td class="tar">注文数量合計</td>
					<td class="tar bl"><?php echo display_qty($tot_sell_qty); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">現在ポイントの保有残高</td>
					<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="w50p">
			<h2 class="anc_tit">決済予想金額の統計</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">注文</th>
					<td class="tar">(A) 注文金額合計</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_price); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">配送費</th>
					<td class="tar">商品別送料合計</td>
					<td class="tar bl"><?php echo display_price2($send_cost); ?></td>
				</tr>
				<tr>
					<td class="tar">送料割引</td>
					<td class="tar bl">(-) <?php echo display_price2($tot_final_sum); ?></td>
				</tr>
				<tr>
					<td class="tar">(B) 最終配送費</td>
					<td class="tar bl"><?php echo display_price2($tot_send_cost); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">決済予定金額 (A+B)</td>
					<td class="list2 tar bold fc_red"><?php echo display_price2($tot_price); ?></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
</div>

<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
<input type="hidden" name="pt_id" value="<?php echo $mb_recommend; ?>">
<input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="coupon_total" value="0">
<input type="hidden" name="coupon_price" value="">
<input type="hidden" name="coupon_lo_id" value="">
<input type="hidden" name="coupon_cp_id" value="">
<input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
<input type="hidden" name="baesong_price2" value="0">
<input type="hidden" name="org_price" value="<?php echo $tot_price; ?>">
<?php if(!$is_member || !$config['usepoint_yes']) { ?>
<input type="hidden" name="use_point" value="0">
<?php } ?>

<section id="sod_fin_orderer">
	<h2 class="anc_tit">ご注文の方</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<?php if(!$is_member) { // 非会員なら ?>
		<tr>
			<th scope="row">パスワード</th>
			<td>
				<input type="password" name="od_pwd" required itemname="パスワード" class="frm_input required" size="20">
				<span class="frm_info">ヤング、数字3~20字 (注文書照会の際は必要)</span>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">名前</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname="名前" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">電話番号</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">携帯電話</th>
			<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname="携帯電話" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">住所</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>" required itemname="郵便番号" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">住所検索</a>
				</div>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required itemname="住所" class="frm_input required" size="60" readonly> 基本アドレス
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 詳細住所
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60" readonly> 参考項目
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><input type="text" name="email" value="<?php echo $member['email']; ?>" required email itemname="E-mail" class="frm_input required" size="30"></td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_receiver">
	<h2 class="anc_tit">お受け取りの方</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">配送地選択</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> 注文者と同一</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> 新規配送地</label>
				<?php if($is_member) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> 配送地目録</label>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">名前</th>
			<td><input type="text" name="b_name" required itemname="名前" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">電話番号</th>
			<td><input type="text" name="b_telephone" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">携帯電話</th>
			<td><input type="text" name="b_cellphone" required itemname="携帯電話" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">住所</th>
			<td>
				<div>
					<input type="text" name="b_zip" required itemname="郵便番号" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">アドレス検索</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" required itemname="住所" class="frm_input required" size="60" readonly> 基本アドレス
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" class="frm_input" size="60"> 詳細住所
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" class="frm_input" size="60" readonly> 参考項目
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">お伝言</th>
			<td>
				<select name="sel_memo">
					<option value="">要請事項の選択</option>
					<option value="不在のときは警備室に預けてください。">不在のときは警備室に預けてください。</option>
					<option value="早急配送お願いします。">早急配送お願いします。</option>
					<option value="不在の時は携帯に連絡ください。">不在の時は携帯に連絡ください。</option>
					<option value="配送前の連絡お願いします。">配送前の連絡お願いします。</option>
				</select>
				<textarea name="memo" class="frm_textbox h60 mart5" rows="3"></textarea>
				<span class="frm_info"><strong class="fc_red">"宅配社員"</strong>言付けを書いてください~!<br>C/S関連のお問い合わせはお客様センターに作成してください。 ここに残された場合は確認できません。</span>
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">決済情報入力</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">決済方法</th>
			<td class="td_label">
				<?php
				$escrow_title = "";
				if($default['de_escrow_use']) {
					$escrow_title = "エスクロ ";
				}

				if($is_kakaopay_use) {
					echo '<input type="radio" name="paymethod" id="paymethod_kakaopay" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"> <label for="paymethod_kakaopay" class="kakaopay_icon">カカオペイ</label>'.PHP_EOL;
				}
				if($default['de_bank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_bank" value="無通帳" onclick="calculate_paymethod(this.value);"> <label for="paymethod_bank">無通帳入金</label>'.PHP_EOL;
				}
				if($default['de_card_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_card" value="クレジットカード" onclick="calculate_paymethod(this.value);"> <label for="paymethod_card">クレジットカード</label>'.PHP_EOL;
				}
				if($default['de_hp_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_hp" value="携帯" onclick="calculate_paymethod(this.value);"> <label for="paymethod_hp">携帯</label>'.PHP_EOL;
				}
				if($default['de_iche_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_iche" value="振込み" onclick="calculate_paymethod(this.value);"> <label for="paymethod_iche">'.$escrow_title./'振込み</label>'.PHP_EOL;
				}
				if($default['de_vbank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_vbank" value="仮想口座" onclick="calculate_paymethod(this.value);"> <label for="paymethod_vbank">'.$escrow_title./'仮想口座</label>'.PHP_EOL;
				}
				if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point'])) {
					echo '<input type="radio" name="paymethod" id="paymethod_point" value="ポイント" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point">ポイント決済</label>'.PHP_EOL;
				}

				// PG 簡便決済
				if($default['de_easy_pay_use']) {
					switch($default['de_pg_service']) {
						case 'lg':
							$pg_easy_pay_name = 'PAYNOW';
							break;
						case 'inicis':
							$pg_easy_pay_name = 'KPAY';
							break;
						case 'kcp':
							$pg_easy_pay_name = 'PAYCO';
							break;
					}

					echo '<input type="radio" name="paymethod" id="paymethod_easy_pay" value="簡便決済" onclick="calculate_paymethod(this.value);"><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'">'.$pg_easy_pay_name.'</label>'.PHP_EOL;
				}
				?>
			</td>
		</tr>
		<tr>
			<th scope="row">合計</th>
			<td class="bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		<tr>
			<th scope="row">追加配送費</th>
			<td>
				<strong><span id="send_cost2">0</span>ウォン</strong>
				<span class="fc_999">(地域によって追加される導線料等の配送費です。)</span>
			</td>
		</tr>
		<?php
		if($is_member && $config['coupon_yes']) { // 保有クーポン
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th scope="row">割引クーポン</th>
			<td>(-) <strong><span id="dc_amt">0</span>ウォン <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></strong>
			<span id="dc_coupon"><a href="<?php echo TB_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false"><span class='fc_197 tu'>使用可能クーポン <?php echo $cp_count[3]; ?>章</a> </span></span></td>
		</tr>
		<?php } ?>
		<?php
		if($is_member && $config['usepoint_yes']) { ?>
		<tr>
			<th scope="row">ポイント決済</th>
			<td>
				<input type="text" name="use_point" value="0" class="frm_input" size="12" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> ワン保有ポイント : <?php echo display_point($member['point']); ?>
				<?php if($config['usepoint']) { ?>
				(<strong><?php echo display_point($config['usepoint']); ?></strong> から使用可能)
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">総支払い額</th>
			<td>
				<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input" size="12" readonly style="font-weight:bold;color:#ec0e03;"> ウォン
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="bank_section" style="display:none;">
	<h2 class="anc_tit">入金することが口座</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">振込口座を選択</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr>
			<th scope="row">入金者名</th>
			<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input" size="12"></td>
		</tr>
		</table>
	</div>
</section>

<?php if(!$config['company_type']) { ?>
<section id="tax_section" style="display:none;">
	<h2 class="anc_tit">証拠書類の発給を要請</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">現金領収証発行</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">個人所得控除用</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">事業者支出の裏付け用</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">未発行</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">携帯電話番号</th>
			<td>
				<input type="text" name="tax_hp" class="frm_input" size="20">
				<span class="frm_info">
					現金領収証は1ウォン以上の現金購入の際、発給が可能です.<br>
					現金領収証は購買代金入金確認日の翌日発給されます。<br>
					現金領収証のホームページ :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">事業者登録番号</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">税金計算書発行</th>
			<td class="td_label">
				<input type="radio" id="taxbill_1" name="taxbill_yes" value="Y" onclick="tax_bill(4);">
				<label for="taxbill_1">発行要請</label>
				<input type="radio" id="taxbill_2" name="taxbill_yes" value="N" onclick="tax_bill(5);" checked>
				<label for="taxbill_2">未発行</label>
			</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">事業者登録番号</td>
			<td><input type="text" name="company_saupja_no" size="20" class="frm_input"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">商号(法人名)</th>
			<td><input type="text" name="company_name" class="frm_input" size="20"> 例 : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">代表者</th>
			<td><input type="text" name="company_owner" class="frm_input" size="20"> 例 : 洪吉童</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">事業場住所</th>
			<td><input type="text" name="company_addr" class="frm_input" size="60"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">業態</th>
			<td><input type="text" name="company_item" class="frm_input" size="20"> 例 : 卸売り</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">種目</th>
			<td><input type="text" name="company_service" class="frm_input" size="20"> 例 : 電子部品</td>
		</tr>
		</table>
	</div>
</section>
<?php } ?>

<?php if(!$is_member) { ?>
<section id="guest_privacy">
	<h3 class="anc_tit">個人情報の収集や利用</h3>
	<p>非会員として注文する際,ポイント積立および追加特典は受けられません。</p>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th scope="col">目的</th>
			<th scope="col">項目</th>
			<th scope="col">保有期間</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>利用者の識別および本人確認</td>
			<td>名前,暗証番号</td>
			<td>5年(電子商取引などでの消費者保護に関する法律)</td>
		</tr>
		<tr>
			<td>配送及びCS対応のための利用者識別</td>
			<td>住所、連絡先(電子メールや携帯電話番号)</td>
			<td>5年(電子商取引などでの消費者保護に関する法律)</td>
		</tr>
		</tbody>
		</table>
	</div>

	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">個人情報の収集および利用内容を読み,これに同意します。</label>
	</fieldset>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="オーダ" class="btn_large wset">
	<a href="<?php echo TB_SHOP_URL; ?>/cart.php" class="btn_large bx-white">キャンセル</a>
</div>

</form>

<script>
$(function() {
    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);
        calculate_sendcost(code);
    });

	// 配送先選択
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open(tb_shop_url+'/orderaddress.php','win_address', 600, 600, 'yes');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("textarea[name=memo]").val($(this).val());
    });
});

// 図書/山間配送費検査
function calculate_sendcost(code) {
    $.post(
        tb_shop_url+"/ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=baesong_price2]").val(data);
            $("#send_cost2").text(number_format(String(data)));

            calculate_order_price();
        }
    );
}

function calculate_order_price() {
    var sell_price = parseInt($("input[name=org_price]").val()); // 合計金額
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 追送費
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // クーポン割引
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //ポイント決済
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var min_point	= parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point	= parseInt(no_comma(f.use_point.value));
	var sell_price	= parseInt(f.org_price.value);
	var send_cost2	= parseInt(f.baesong_price2.value);
	var mb_coupon	= parseInt(f.coupon_total.value);
	var mb_point	= parseInt(f.mb_point.value);
	var tot_price	= sell_price + send_cost2 - mb_coupon;

	if(f.use_point.value == '') {
		alert(/'ポイント使用金額を入力してください。 使用を望まない場合、0を入力してください。');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert(/'ポイント使用金額は現在保有ポイントより大きいわけではありません。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert(/'ポイント使用金額は最終決済金額より大きいわけではありません。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < min_point)) {
		alert(/'ポイント使用の金額は '+number_format(String(min_point))+/'原から使用可能です。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	var paymethod_check = false;
	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "paymethod" && f.elements[i].checked==true){
			paymethod_check = true;
		}
	}

    if(!paymethod_check) {
        alert("決済方法を選択してください。");
        return false;
    }

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "会員じゃなくた場合、注文書の照会に必要な暗証番号を3桁以上入力してください。");
    }

	if(getRadioVal(f.paymethod) == /'無通帳') {
		check_field(f.bank, "入金口座を選んでください。");
		check_field(f.deposit_name, "入金者名を入力してください。");
	}

	<?php if(!$config['company_type']) { ?>
	if(getRadioVal(f.paymethod) == '無通帳' && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "携帯番号を入力してください。");
	}

	if(getRadioVal(f.paymethod) == /'無通帳' && getRadioVal(f.taxsave_yes) == 'S') {
		check_field(f.tax_saupja_no, "事業者番号を入力してください。");
	}

	if(getRadioVal(f.paymethod) == /'無通帳' && getRadioVal(f.taxbill_yes) == 'Y') {
		check_field(f.company_saupja_no, "事業者番号を入力してください。");
		check_field(f.company_name, "相互名を入力してください。");
		check_field(f.company_owner, "代表者名を入力してください");
		check_field(f.company_addr, "事業場所在地を入力してください。");
		check_field(f.company_item, "業態を入力してください");
		check_field(f.company_service, "種目を入力してください");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getRadioVal(f.paymethod) == /'振込み') {
		if(tot_price < 150) {
			alert("口座振込みは150ウォン以上の決済が可能です.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == /'クレジットカード') {
		if(tot_price < 1000) {
			alert("クレジットカードは1000ウォン以上の決済が可能です。");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == /'携帯電話') {
		if(tot_price < 350) {
			alert("携帯電話は350ウォン以上の決済が可能です。");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("個人情報の収集や利用内容を読み,これに同意しなければなりません。");
			return false;
		}
	}

	if(!confirm("注文内訳が正確で,注文なさいますか。?"))
		return false;

	f.use_point.value = no_comma(f.use_point.value);
	f.tot_price.value = no_comma(f.tot_price.value);

	return true;
}

function calculate_temp_point(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert(/'ポイントの使用額は数字でなければなりません。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
	}
}

function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // 合計金額
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 追加配送費
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // クポンハルイン
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 保有ポイント
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// ポイント残高が足りないか?
	if( type == /'ポイント' && mb_point < tot_price ) {
		alert(/'ポイント残高が足りません。');

		$("#paymethod_bank").attr("checked", true);
		$("#bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#tax_section").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case /'無通帳':
			$("#bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").show();
			<?php } ?>
			break;
		case /'ポイント':
			$("#bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		default: // 他の決済手段
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}

function tax_bill(val) {
	switch(val) {
		case 1:
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 2:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 3:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
		case 4:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").show();
			$("#taxsave_3").attr("checked", true);
			break;
		case 5:
			$(".taxbill_fld").hide();
			break;
	}
}

function coupon_cancel() {
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.tot_price.value)); // 最終決済金額
	var mb_coupon  = parseInt(f.coupon_total.value); // クーポン割引
	var tot_price  = sell_price + mb_coupon;

	$("#dc_amt").text(0);
	$("#dc_cancel").hide();
	$("#dc_coupon").show();

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=coupon_total]").val(0);
	$("input[name=coupon_price]").val("");
	$("input[name=coupon_lo_id]").val("");
	$("input[name=coupon_cp_id]").val("");
}

// 購入者情報と同一です。
function gumae2baesong(checked) {
    var f = document.buyform;

    if(checked == true) {
		f.b_name.value			= f.name.value;
		f.b_cellphone.value		= f.cellphone.value;
		f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
		f.b_telephone.value		= '';
		f.b_zip.value			= '';
		f.b_addr1.value			= '';
		f.b_addr2.value			= '';
		f.b_addr3.value			= '';
		f.b_addr_jibeon.value	= '';

		calculate_sendcost('');
    }
}

gumae2baesong(true);
</script>
<!-- } 注文書作成の終わり -->
