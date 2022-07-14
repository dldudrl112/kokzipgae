<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 買出し { -->
<script src="<?php echo TB_MJS_URL; ?>/shop.js"></script>

<div class="stit_txt">
	※ 총 <?php echo number_format($cart_count); ?>~商品が入っています。
</div>

<div id="sod_bsk">
	<form name="frmcartlist" id="sod_bsk_list" method="post" action="<?php echo $cart_action_url; ?>">	

    <?php if($cart_count) { ?>
    <div id="sod_chk">        
        <input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked">
		<label for="ct_all">全商品選択</label>
    </div>
    <?php } ?>

    <ul class="sod_list">
		<?php
		$tot_point		= 0;
		$tot_sell_price = 0;
		$tot_opt_price	= 0;
		$tot_sell_qty	= 0;
		$tot_sell_amt	= 0;

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs = get_goods($row['gs_id']);

			// 合計金額計算
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as opt_price
						from shop_cart
					   where gs_id = '$row[gs_id]'
						 and ct_direct = '$set_cart_id'
						 and ct_select = '0'";
			$sum = sql_fetch($sql);

			if($i==0) { // 継続ショッピング
				$continue_ca_id = $row['ca_id'];
			}

			$it_options = mobile_print_item_options($row['gs_id'], $set_cart_id);

			$point = $sum['point'];
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

			$href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		?>
        <li class="sod_li">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
            <div class="li_chk">
                <label for="ct_chk_<?php echo $i; ?>" class="sound_only">商品</label>
                <input type="checkbox" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked="checked">
            </div>
            <div class="li_name">
                <a href="<?php echo $href; ?>"><?php echo stripslashes($gs['gname']); ?></a>
				<?php if($it_options) { ?>
				<div class="sod_opt"><?php echo $it_options; ?></div>
				<?php } ?>
                <span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span> 
				<div class="li_mod" style="padding-left:100px;">
					<?php if($it_options) { ?>
					<button type="button" id="mod_opt_<?php echo $row['gs_id']; ?>" class="mod_btn mod_options">オプション変更/追加</button>
					<?php } ?>
				</div>				
            </div>
            <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>販売価</span>
				<?php echo number_format($sell_amt); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>数量</span>
				<?php echo number_format($sell_qty); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>配送費</span>
				<?php echo number_format($info['price']); ?></span>
            </div>
            <div class="li_total">
                <span class="total_price total_span"><span>小計</span>
				<strong><?php echo number_format($sell_price); ?></strong></span>
                <span class="total_point total_span"><span>積立ポイント</span>
				<strong><?php echo number_format($point); ?></strong></span>
            </div>
        </li>
		<?php 
			$tot_point		+= $point;
			$tot_sell_price += $sell_price;
			$tot_opt_price	+= $sell_opt_price;
			$tot_sell_qty	+= $sell_qty;
			$tot_sell_amt	+= $sell_amt;

			if(!$is_member) {
				$tot_point = 0;
			}
		} // for 

		// 配送費検査
		$send_cost = 0;
		$com_send_cost = 0;
		$sep_send_cost = 0;
		$max_send_cost = 0;

		if($i > 0) {
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
					$max_send_cost += max($condition[$key][/'束']); // 最大送料合算
					$com_array[] = max(array_keys($condition[$key][/'束'])); // max key
					$val_array[] = max(array_values($condition[$key][/'束']));// max value
				}
				if($condition[$key][/'個別']) {
					$sep_send_cost += array_sum($condition[$key][/'個別']); // まとめ配送不可含ま
					$com_array[] = array_keys($condition[$key][/'個別']); // すべての配列 key
					$val_array[] = array_values($condition[$key][/'個別']); // すべての配列 value
				}
			}

			$tune = get_tune_sendcost($com_array, $val_array);

			$send_cost = $com_send_cost + $sep_send_cost; // 総送料合計
			$tot_send_cost = $max_send_cost + $sep_send_cost; // 最終送料
			$tot_final_sum = $send_cost - $tot_send_cost; // 送料割引
			$tot_price = $tot_sell_price + $tot_send_cost; // 支払い予定額
		}

		if($i == 0) {
			echo '<li class="empty_list">買い物かごに入っている商品がありません。</li>';
		}
		?>
    </ul>

    <?php if($i > 0) { ?>
    <dl id="sod_bsk_tot">
        <?php if($tot_send_cost > 0) { // 送料が0より大きければ（ある場合） ?>
        <dt class="sod_bsk_dvr"><span>配送費</span></dt>
        <dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> ウォン</strong></dd>
        <?php } ?>

        <?php if($tot_price > 0) { ?>
        <dt class="sod_bsk_cnt"><span>総計</span></dt>
        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?> ウォン</strong></dd>
        <dt><span>ポイント</span></dt>
        <dd><strong><?php echo number_format($tot_point); ?> P</strong></dd>
        <?php } ?>
    </dl>
    <?php } ?>

    <div id="sod_bsk_act" class="btn_confirm">
        <?php if($i == 0) { ?>
        <a href="<?php echo TB_MURL; ?>" class="btn_medium bx-black">買い物を続ける</a>
        <?php } else { ?>
        <input type="hidden" name="url" value="<?php echo TB_MSHOP_URL; ?>/orderform.php">
        <input type="hidden" name="act" value="">
        <input type="hidden" name="records" value="<?php echo $i; ?>">
        <a href="<?php echo TB_MSHOP_URL; ?>/list.php?ca_id=<?php echo $continue_ca_id; ?>" class="btn_medium bx-black">買い物を続ける</a>
        <button type="button" onclick="return form_check('buy');" class="btn_medium wset">オーダ</button>
        <div><button type="button" onclick="return form_check('seldelete');" class="btn01">選択削除</button>
        <button type="button" onclick="return form_check('alldelete');" class="btn01">空け</button></div>
        <?php if($naverpay_button_js) { ?>
        <div class="naverpay-cart"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
        <?php } ?>
        <?php } ?>
    </div>
    </form>
</div>

<script>
$(function() {
    var close_btn_idx;

    // 選択的修正
    $(".mod_options").click(function() {
        var gs_id = $(this).attr("id").replace("mod_opt_", "");
        var $this = $(this);
        close_btn_idx = $(".mod_options").index($(this));

        $.post(
            "./cartoption.php",
            { gs_id: gs_id },
            function(data) {
                $("#mod_option_frm").remove();
                $this.after("<div id=\"mod_option_frm\"></div>");
                $("#mod_option_frm").html(data);
                price_calculate();
            }
        );
    });

    // すべて選択
    $("input[name=ct_all]").click(function() {
        if($(this).is(":checked"))
            $("input[name^=ct_chk]").attr("checked", true);
        else
            $("input[name^=ct_chk]").attr("checked", false);
    });

    // オプションの変更を閉じる
    $(document).on("click", "#mod_option_close", function() {
        $("#mod_option_frm").remove();
        $("#win_mask, .window").hide();
        $(".mod_options").eq(close_btn_idx).focus();
    });
    $("#win_mask").click(function () {
        $("#mod_option_frm").remove();
        $("#win_mask").hide();
        $(".mod_options").eq(close_btn_idx).focus();
    });

});

function fsubmit_check(f) {
    if($("input[name^=ct_chk]:checked").size() < 1) {
        alert("購入する商品を1つ以上選択してください。");
        return false;
    }

    return true;
}

function form_check(act) {
    var f = document.frmcartlist;
    var cnt = f.records.value;

    if(act == "buy")
    {
		if($("input[name^=ct_chk]:checked").size() < 1) {
			alert("注文することが商品を1つ以上選択してください。");
			return false;
		}

        f.act.value = act;
        f.submit();
    }
    else if(act == "alldelete")
    {
        f.act.value = act;
        f.submit();
    }
    else if(act == "seldelete")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("削除する商品を1つ以上選択してください。");
            return false;
        }

        f.act.value = act;
        f.submit();
    }

    return true;
}
</script>
<!-- } カート終了 -->
