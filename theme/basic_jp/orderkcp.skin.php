<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_kcp.inc.php');

// 決済代行会社別のコード include (スクリプトなど)
require_once(TB_SHOP_PATH.'/kcp/orderform.1.php');
?>

<!-- kcp決済開始 { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ ご注文商品の内訳に <em>数量および注文金額</em>この間違えないのか必ず確認してください。
</p>

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
	$goods = '';
	$good_info = '';
	$goods_count = -1;

	$sql = " select *
			   from shop_cart
			  where od_id = '$od_id'
				and ct_select = '0'
			  group by gs_id
			  order by index_no ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$rw = get_order($row['od_no']);
		$gs = get_goods($row['gs_id'], 'gname,simg1');

		if(!$goods)
			$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

		$goods_count++;

		// エスクロ商品情報
		if($default['de_escrow_use']) {
			if($i>0)
				$good_info .= chr(30);
			$good_info .= "seq=".($i+1).chr(31);
			$good_info .= "ordr_numb={$od_id}_".sprintf("%04d", ($i+1)).chr(31);
			$good_info .= "good_name=".addslashes($gs['gname']).chr(31);
			$good_info .= "good_cntx=".$rw['sum_qty'].chr(31);
			$good_info .= "good_amtx=".$rw['goods_price'].chr(31);
		}

		$it_name = stripslashes($gs['gname']);
		$it_options = print_complete_options($row['gs_id'], $od_id);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}
	?>
	<tr>
		<td class="tac"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($rw['sum_qty']); ?></td>
		<td class="tar"><?php echo number_format($rw['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['use_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['sum_point']); ?></td>
		<td class="tar"><?php echo number_format($rw['baesong_price']); ?></td>
	</tr>
	<?php
	}

	if($goods_count) $goods .= /' 他 '.$goods_count./'件 ';

	// 複合課税処理
	$comm_tax_mny  = 0; // 課税金額
	$comm_vat_mny  = 0; // 付加税
	$comm_free_mny = 0; // 免税金額
	if($default['de_tax_flag_use']) {
		$info = comm_tax_flag($od_id);
		$comm_tax_mny  = $info['comm_tax_mny'];
		$comm_vat_mny  = $info['comm_vat_mny'];
		$comm_free_mny = $info['comm_free_mny'];
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td class="tar" colspan="7">
			(商品金額 : <strong><?php echo display_price($stotal['price']); ?></strong> +
			配送費 : <strong><?php echo display_price($stotal['baesong']); ?></strong>) -
			(クポンハルイン : <strong><?php echo display_price($stotal['coupon']); ?></strong> +
			ポイント決済 : <strong><?php echo display_price($stotal['usepoint']); ?></strong>) =
			総計 : <strong class="fc_red"><?php echo display_price($stotal['useprice']); ?></strong>
		</td>
	</tr>
	</tfoot>
	</table>
</div>

<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

<?php
// 決済代行社別コード include (決済代行情報フィールド)
require_once(TB_SHOP_PATH.'/kcp/orderform.2.php');
?>

<section id="sod_fin_orderer">
	<h2 class="anc_tit">注文の方</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">名前</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">電話番号</th>
			<td><?php echo $od['telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">携帯電話</th>
			<td><?php echo $od['cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">住所</th>
			<td><?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?></td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><?php echo $od['email']; ?></td>
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
			<th scope="row">名前</th>
			<td><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">電話番号</th>
			<td><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">携帯電話</th>
			<td><?php echo $od['b_cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">住所</th>
			<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
		</tr>
		<?php if($od['memo']) { ?>
		<tr>
			<th scope="row">ジョンハシル言葉</th>
			<td><?php echo conv_content($od['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">決済情報</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">決済方法</th>
			<td><?php echo $od['paymethod']; ?></td>
		</tr>
		<tr>
			<th scope="row">決済金額</th>
			<td class="fs14 bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		</table>
	</div>
</section>

<div id="display_pay_button" class="btn_confirm">
	<input type="button" value="決済する" onclick="forderform_check(this.form);" class="btn_large wset">
    <a href="<?php echo TB_URL; ?>" class="btn_large bx-white">キャンセル</a>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo TB_IMG_URL; ?>/ajax-loader.gif" alt="">
    <span>注文完了中です。 少々お待ちください。</span>
</div>

</form>

<script>
function forderform_check(f)
{
    // 金額チェック
    if(!payment_check(f))
        return false;

	f.site_cd.value = f.def_site_cd.value;
    f.payco_direct.value = "";
    switch(f.od_settle_case.value)
    {
        case "振込み":
            f.pay_method.value   = "010000000000";
            break;
        case "仮想口座":
            f.pay_method.value   = "001000000000";
            break;
        case "決済する":
            f.pay_method.value   = "000010000000";
            break;
        case "決定する":
            f.pay_method.value   = "100000000000";
            break;
        case "簡便決済":
            <?php if($default['de_card_test']) { ?>
            f.site_cd.value      = "S6729";
            <?php } ?>
            f.pay_method.value   = "100000000000";
            f.payco_direct.value = "Y";
            break;
    }

    f.buyr_name.value = f.od_name.value;
    f.buyr_mail.value = f.od_email.value;
    f.buyr_tel1.value = f.od_tel.value;
    f.buyr_tel2.value = f.od_hp.value;
    f.rcvr_name.value = f.od_b_name.value;
    f.rcvr_tel1.value = f.od_b_tel.value;
    f.rcvr_tel2.value = f.od_b_hp.value;
    f.rcvr_mail.value = f.od_email.value;
    f.rcvr_zipx.value = f.od_b_zip.value;
    f.rcvr_add1.value = f.od_b_addr1.value;
    f.rcvr_add2.value = f.od_b_addr2.value;

    jsf__pay( f );
}

// 決済チェック
function payment_check(f)
{
    var tot_price = parseInt(f.good_mny.value);

	if(f.od_settle_case.value == /'振込み') {
		if(tot_price < 150) {
			alert("口座振込みは150ウォン以上の決済が可能です。");
			return false;
		}
	}

    if(f.od_settle_case.value == /'クレジットカード') {
		if(tot_price < 1000) {
			alert("クレジットカードは1000ウォン以上の決済が可能です。");
			return false;
		}
    }

	if(f.od_settle_case.value == /'携帯電話') {
		if(tot_price < 350) {
			alert("携帯電話は350ウォン以上の決済が可能です。");
			return false;
		}
    }

    return true;
}
</script>
<!-- } kcp決済の終り -->
