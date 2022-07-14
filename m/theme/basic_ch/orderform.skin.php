<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- 开始制作订货单 { -->
<div id="sod_approval_frm">
<?php
ob_start();
?>
    <p>请确认欲订购的商品。</p>

    <ul class="sod_list">
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

			// 合计金额计算
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

			$it_name = '<strong>'.stripslashes($gs['gname']).'</strong>';
			$it_options = mobile_print_item_options($row['gs_id'], $set_cart_id);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}

			$point = $sum['point'];
			$supply_price = $sum['supply_price'];
			$sell_price = $sum['price'];		
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// 非会员积分初始化
			if(!$is_member) $point = 0;

			// 配送费
			if($gs['use_aff'])
				$sr = get_partner($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

			$seller_id[$i] = $gs['mb_id'];

			$href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		?>

        <li class="sod_li">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">		
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
            
			<div class="li_name">
                <?php echo $it_name; ?>
                <div class="li_mod" style="padding-left:100px;"></div>
                <span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span>
            </div>
            <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>售价</span>
				<?php echo number_format($sell_amt); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>数量</span>
				<?php echo number_format($sell_qty); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>配送费</span>
				<?php echo number_format($info['price']); ?></span>
            </div>
            <div class="li_total">
                <span class="total_price total_span"><span>小计</span>
				<strong><?php echo number_format($sell_price); ?></strong></span>
                <span class="total_point total_span"><span>累积积分</span>
				<strong><?php echo number_format($point); ?></strong></span>
            </div>
        </li>

        <?php
			$tot_point += (int)$point;
			$tot_sell_price += (int)$sell_price;
			$tot_opt_price += (int)$sell_opt_price;
			$tot_sell_qty += (int)$sell_qty;
			$tot_sell_amt += (int)$sell_amt;
        } // for 尽头

		// 配送费检查
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
			if($condition[$key][/'捆']) {
				$com_send_cost += array_sum($condition[$key][/'捆']); // 捆绑运费总计
				$max_send_cost += max($condition[$key][/'捆']); // 最大的运费合计
				$com_array[] = max(array_keys($condition[$key][/'捆'])); // max key
				$val_array[] = max(array_values($condition[$key][/'捆']));// max value
			}
			if($condition[$key][/'个别']) {
				$sep_send_cost += array_sum($condition[$key][/'个别']); // 不可捆绑发货合计
				$com_array[] = array_keys($condition[$key][/'个别']); // 所有方案 key
				$val_array[] = array_values($condition[$key][/'个别']); // 所有方案 value
			}
		}

		$baesong_price = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // 总配送费合计
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 最终运费
		$tot_final_sum = $send_cost - $tot_send_cost; // 配送费折扣
		$tot_price = $tot_sell_price + $tot_send_cost; // 预定结算金额

        if($i == 0) {
            alert(/'菜篮子空着。', TB_MSHOP_URL.'/cart.php');
        }
        ?>
    </ul>

    <dl id="sod_bsk_tot">
        <dt class="sod_bsk_sell"><span>咒语</span></dt>
        <dd class="sod_bsk_sell"><strong><?php echo number_format($tot_sell_price); ?> 韩元</strong></dd>
        <dt class="sod_bsk_dvr"><span>配送费</span></dt>
        <dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> 韩元</strong></dd>
        <dt class="sod_bsk_cnt"><span>总计</span></dt>
        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?> 韩元</strong></dd>
        <dt class="sod_bsk_point"><span>点</span></dt>
        <dd class="sod_bsk_point"><strong><?php echo number_format($tot_point); ?> P</strong></dd>
    </dl>

<?php
$content = ob_get_contents();
ob_end_clean();
?>
</div>

<div id="sod_frm">
	<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
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

	<?php echo $content; ?>

	<section id="sod_frm_orderer">
		<h2 class="anc_tit">订购者</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<?php if(!$is_member) { // 非会员的话 ?>
			<tr>
				<th scope="row">密码</th>
				<td>
					<input type="password" name="od_pwd" required class="frm_input required" maxlength="20">
					<span class="frm_info">영,숫자 3~20자 (查询订单时需要)</span>
				</td>
			</tr>
			<?php } ?>
            <tr>
				<th scope="row">名</th>
                <td><input type="text" name="name" value="<?php echo $member['name']; ?>" required class="frm_input required" maxlength="20"></td>
            </tr>
			<tr>
				<th scope="row">手机</th>
				<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required class="frm_input required" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">电话号码</th>
				<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">住址</th>
				<td>
                    <input type="text" name="zip" value="<?php echo $member['zip']; ?>" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">地址检索</button><br>
                    <input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required class="frm_input frm_address required"><br>   
                    <input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input frm_address"><br>
                    <input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input frm_address" readonly><br>
                    <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><input type="text" name="email" value="<?php echo $member['email']; ?>" required class="frm_input required wfull"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_frm_taker">
		<h2 class="anc_tit">收件人</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">选择配送地</th>
				<td>
					<input type="radio" name="ad_sel_addr" value="1" id="sel_addr1" class="css-checkbox lrg">
					<label for="sel_addr1" class="css-label padr5">与订购者相同</label><br>
					<input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
					<label for="sel_addr2" class="css-label">新配送地</label>
					<?php if($is_member) { ?>
					<br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3" class="css-checkbox lrg">
					<label for="sel_addr3" class="css-label">配送地目录</label>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row">名</th>
				<td><input type="text" name="b_name" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">手机</th>
				<td><input type="text" name="b_cellphone" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">电话号码</th>
				<td><input type="text" name="b_telephone" class="frm_input"></td>
			</tr>
			<tr>
				<th scope="row">住址</th>
				<td>
                    <input type="text" name="b_zip" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">地址检索</button><br>
                    <input type="text" name="b_addr1" required class="frm_input frm_address required"><br>   
                    <input type="text" name="b_addr2" class="frm_input frm_address"><br>
                    <input type="text" name="b_addr3" class="frm_input frm_address" readonly><br>
					<input type="hidden" name="b_addr_jibeon" value="">				
				</td>
			</tr>
			<tr>
				<th scope="row">要传达的话</th>
				<td>
					<select name="sel_memo" class="wfull">
						<option value="">选择要求事项</option>
						<option value="不在的话请交给警卫室。">不在的话请交给警卫室。</option>
						<option value="请尽快配送。">请尽快配送。</option>
						<option value="不在时请用手机联系。">不在时请用手机联系。</option>
						<option value="配送前请联系。">配送前请联系。</option>
					</select>
					<div class="padt5">
						<textarea name="memo" id="memo" class="frm_textbox"></textarea>
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php
	$escrow_title = "";
	if($default['de_escrow_use']) {
		$escrow_title = "埃斯克罗 ";
	}

	$multi_settle = '';
	if($is_kakaopay_use)
		$multi_settle .= "<option value='KAKAOPAY'>可可豆</option>\n";
	if($default['de_bank_use'])
		$multi_settle .= "<option value=/'无存折'>无折存款</option>\n";
	if($default['de_card_use'])
		$multi_settle .= "<option value=/'信用卡'>信用卡</option>\n";
	if($default['de_hp_use'])
		$multi_settle .= "<option value=/'手机'>手机</option>\n";
	if($default['de_iche_use'])
		$multi_settle .= "<option value=/'转账'>".$escrow_title."转账</option>\n";	
	if($default['de_vbank_use'])
		$multi_settle .= "<option value=/'虚拟账户'>".$escrow_title."虚拟账户</option>\n";
	if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point']))
		$multi_settle .= "<option value=/'点'>积分结算</option>\n";

	// PG 简便结算
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
		$multi_settle .= "<option value=/'简便结算'>{$pg_easy_pay_name}</option>\n";
	}

	// 只有使用Enisis的时候 三星支付可结算
	if($default['de_samsung_pay_use'] && ($default['de_pg_service'] == 'inicis')) {
		$multi_settle .= "<option value=/'三星支付'>三星支付</option>\n";
	}
	?>

	<section id="sod_frm_pay">
		<h2 class="anc_tit">输入结算信息</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">结算方法</th>
				<td>
					<select name="paymethod" onchange="calculate_paymethod(this.value);" class="wfull">
						<option value="">选择</option>
						<?php echo $multi_settle; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">合计</th>
				<td><strong><?php echo display_price($tot_price); ?></strong></td>
			</tr>
			<tr>
				<th scope="row">追加运费</th>
				<td>
					<strong><span id="send_cost2">0</span>韩元</strong>
					<span class="fc_999">(按地区追加的配送费)</span>
				</td>
			</tr>
			<?php
			if($is_member && $config['coupon_yes']) { // 保有的优惠券
				$sp_count = get_cp_precompose($member['id']);
			?>
			<tr>
				<th scope="row">优惠券</th>
				<td>
					<span id="dc_coupon"><a href="javascript:window.open('<?php echo TB_MSHOP_URL; ?>/ordercoupon.php');" class="btn_small bx-red">可用优惠券 <?php echo $sp_count[3]; ?>章</a>&nbsp;</span>(-)&nbsp;&nbsp;<strong><span id="dc_amt">0</strong>韩元&nbsp;<span id="dc_cancel" style="display:none;"><a href="javascript:coupon_cancel();" class="btn_small grey">删除</a></span></span>
				</td>
			</tr>
			<?php } ?>
			<?php 
			if($is_member && $config['usepoint_yes']) { ?>
			<tr>
				<th scope="row">点付款</th>
				<td>
					<input type="text" name="use_point" value="0" onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);" class="frm_input w100"> 点付款
					<div>余额 : <b><?php echo display_point($member['point']); ?></b> (<?php echo display_point($config['usepoint']); ?> ~开始可使用)</div>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<th scope="row">总结算金额</th>
				<td>
					<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input w100" readonly style="background:#f1f1f1;color:red;font-weight:bold;"> 韩元
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="bank_section" style="display:none;">
		<h2 class="anc_tit">要汇款的账户</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">无折存款</th>
				<td>
					<?php echo mobile_bank_account("bank"); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">汇款人名</th>
				<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input w100"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="taxsave_section" style="display:none;">
		<h2 class="anc_tit">凭单签发</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">现金收据</th>
				<td>
					<select name="taxsave_yes" onchange="tax_save(this.value);" class="wfull">
						<option value="N">未发行</option>
						<option value="Y">个人所得抵扣用</option>
						<option value="S">公司支出证明</option>
					</select>
					<div id="taxsave_fld_1" style="display:none;">
						<input type="text" name="tax_hp" class="frm_input frm_address" placeholder="手机号码">
					</div>
					<div id="taxsave_fld_2" style="display:none;">
						<input type="text" name="tax_saupja_no" class="frm_input frm_address" placeholder="营业执照号">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">税单</th>
				<td>
					<select name="taxbill_yes" onchange="tax_bill(this.value);" class="wfull">
						<option value="N">未发行/option>
						<option value="Y">发行要求</option>
					</select>
					<div id="taxbill_section" style="display:none;">
						<input type="text" name="company_saupja_no" class="frm_input frm_address" placeholder="营业执照号"><br>
						<input type="text" name="company_name" class="frm_input frm_address" placeholder="商号(法人名)"><br>
						<input type="text" name="company_owner" class="frm_input frm_address" placeholder="代表人名"><br>
						<input type="text" name="company_addr" class="frm_input frm_address" placeholder="营业场所地址"><br>
						<input type="text" name="company_item" class="frm_input frm_address" placeholder="业态"><br>
						<input type="text" name="company_service" class="frm_input frm_address" placeholder="项目">
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php if(!$is_member) { ?>
    <section id="guest_privacy">
		<h2 class="anc_tit">非会员购买</h2>
		<div class="tbl_head01 tbl_wrap">
			<table>
			<thead>
			<tr>
				<th>目的</th>
				<th>项目</th>
				<th>保有期</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>用户识别及本人确认</td>
				<td>姓名,密码</td>
				<td>5年(关于电子商务等消费者保护的法律)</td>
			</tr>
			<tr>
				<td>为配送及CS对应的用户识别</td>
				<td>地址,联系方式(电子邮件,手机号码)</td>
				<td>5年(关于电子商务等消费者保护的法律)</td>
			</tr>
			</tbody>
			</table>
		</div>

		<div id="guest_agree">
			<input type="checkbox" id="agree" value="1" class="css-checkbox lrg">
			<label for="agree" class="css-label">阅读了个人信息收集及使用内容,同意。</label>
		</div>
	</section>
	<?php } ?>

	<div class="btn_confirm">		
		<input type="submit" value="订购" class="btn_medium wset">
		<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_medium bx-white">取消订单</a>
	</div>
</div>
</form>

<script>
$(function() {
    var zipcode = "";

    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

	// 选择配送地
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open('./orderaddress.php','win_address');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("textarea[name=memo]").val($(this).val());
    });
});

// 图书/产间配送费检查
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
    var sell_price = parseInt($("input[name=org_price]").val()); // 合计金额
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 追加运费
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 优惠券折扣
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //点缀结算
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var min_point	 = parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point   = parseInt(no_comma(f.use_point.value));
	var sell_price   = parseInt(f.org_price.value);
	var send_cost2   = parseInt(f.baesong_price2.value);
	var mb_coupon    = parseInt(f.coupon_total.value);
	var mb_point     = parseInt(f.mb_point.value);
	var tot_price    = sell_price + send_cost2 - mb_coupon;

	if(f.use_point.value == '') {
		alert(/'请输入积分使用金额。 不想使用时请输入0。');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert(/'积分使用金额不得大于现有积分。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert(/'积分使用金额不得大于最终结算金额。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < min_point)) {
		alert(/'使用积分的金额是 '+number_format(String(min_point))+/'可从一元开始使用。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(getSelectVal(f["paymethod"]) == ''){
		alert("请选择结算方法。");
		f.paymethod.focus();
		return false;
	}

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "非会员查询订单时请输入3位数以上的密码。");
    }

	if(getSelectVal(f["paymethod"]) == /'无存折'){
		check_field(f.bank, "请选择汇款账号。");
		check_field(f.deposit_name, "请输入汇款者名。");
	}

	<?php if(!$config['company_type']) { ?>
	if(getSelectVal(f["paymethod"]) == /'无存折' && getSelectVal(f["taxsave_yes"]) == 'Y') {
		check_field(f.tax_hp, "请输入手机号。");
	}

	if(getSelectVal(f["paymethod"]) == /'无存折' && getSelectVal(f["taxsave_yes"]) == 'S') {
		check_field(f.tax_saupja_no, "请输入营业执照号。");
	}

	if(getSelectVal(f["paymethod"]) == /'无存折' && getSelectVal(f["taxbill_yes"]) == 'Y') {
		check_field(f.company_saupja_no, "请输入营业执照号。");
		check_field(f.company_name, "请输入互名。");
		check_field(f.company_owner, "请输入代表人名。");
		check_field(f.company_addr, "请输入营业场所材料地。");
		check_field(f.company_item, "请输入业态。");
		check_field(f.company_service, "请输入项目。");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getSelectVal(f["paymethod"]) == /'转账') {
		if(tot_price < 150) {
			alert("转账可以结算150韩元以上。");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == /'信用卡') {
		if(tot_price < 1000) {
			alert("信用卡可以结算1000韩元以上。");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == /'手机') {
		if(tot_price < 350) {
			alert("手机可以结算350韩元以上。");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("请阅读个人信息收集及使用内容后同意。");
			return false;
		}
	}

	if(!confirm("订购明细正确后是否订购?"))
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
		alert(/'积分使用额必须是数字。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
	}
}

// 结算方法
function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // 合计金额
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 追加运费
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 优惠券折扣
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 持有点
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 积分余额是否不足?
	if( type == /'点' && mb_point < tot_price ) {
		alert(/'积分余额不足。');

		$("select[name=paymethod]").val(/'无存折');
		$("#bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false); 
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#taxsave_section").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case /'无存折':
			$("#bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").show();
			<?php } ?>
			break;
		case /'点':
			$("#bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();			
			<?php } ?>
			break;
		default: // 其他结算方式
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();			
			<?php } ?>
			break;
	}
}

// 现金收据
function tax_save(val) {
	switch(val) {
		case 'Y': // 个人所得抵扣用
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		case 'S': // 支出证明
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		default: // 未发行
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
	}
}

// 税单
function tax_bill(val) {
	switch(val) {
		case 'Y':  // 发行箱
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("select[name=taxsave_yes]").val('N');
			$("#taxbill_section").show();
			break;
		case 'N': //未发行
			$("#taxbill_section").hide();
			break;
	}
}

// 优惠券删除
function coupon_cancel() {
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.tot_price.value)); // 最终结算金额
	var mb_coupon  = parseInt(f.coupon_total.value); // 优惠券折扣
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

// 与购买者信息相同。
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
<!-- } 填写订货单 尽头 -->
