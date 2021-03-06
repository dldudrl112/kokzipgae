<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<script src="<?php echo TB_MJS_URL; ?>/shop.js"></script>

<form name="fbuyform" method="post">
<input type="hidden" name="gs_id[]" value="<?php echo $gs_id; ?>">
<input type="hidden" id="it_price" value="<?php echo get_sale_price($gs_id); ?>">
<input type="hidden" name="ca_id" value="<?php echo $ca['gcate']; ?>">
<input type="hidden" name="sw_direct">

<div class="sp_wrap">
	<div class="sp_sub_wrap">
		<div class="v_cont">
			<ul class="v_horiz">
				<li><?php echo get_it_image($gs_id, $gs['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx'], 'name="slideshow"'); ?></li>
			</ul>
		</div>
		<a class="sp_b_a fa fa-angle-left" href="javascript:chgimg(-1)"></a>
		<a class="sp_b_a fa fa-angle-right" href="javascript:chgimg(1)"></a>
	</div>
	<div class="subject">
		<?php echo get_text($gs['gname']); ?>
		<?php if($gs['explan']) { ?>
		<p class="sub_txt"><?php echo get_text($gs['explan']); ?></p>
		<?php } ?>
	</div>

	<div class="sp_sns">
		<?php echo $sns_share_links; ?>
	</div>
	<div class="sp_sns">
		満足度 : <?php echo $aver_score; ?>% <span class="hline"></span>レビュー : <?php echo number_format($item_use_count); ?>件
	</div>

	<?php if($is_social_end) { ?>
	<div class="sp_tol">
		<div class="sp_fpg">
			<span class="sp_s_n"> <?php echo $is_social_txt; ?> </span>
		</div>
	</div>
	<?php } ?>

	<?php if($is_social_ing) { ?>
	<div class="sp_tol">
		<div class="social">
			<?php include_once(M_TIMESALE); ?>
		</div>
	</div>
	<?php } ?>

	<?php if(!$is_only) { ?>
	<div class="sp_tbox">
		<?php if(!$is_pr_msg && !$is_buy_only && !$is_soldout && $gs['normal_price']) { ?>
		<ul>
			<li class='tlst'>市中価格</li>
			<li class='trst fc_137 tl'><?php echo display_price2($gs['normal_price']); ?></li>
		</ul>
		<?php } ?>
		<ul class="mart3">
			<li class='tlst padt8'>販売価格</li>
			<li class='trst'>
				<div class='trst-amt'><?php echo mobile_price($gs_id); ?></div>
			</li>
		</ul>
		<?php if(is_partner($member['id']) && $config['pf_payment_yes']) { ?>
		<ul class="mart3">
			<li class='tlst'>販売収入</li>
			<li class="trst"><?php echo display_price2(get_payment($gs_id)); ?></li>
		</ul>
		<?php } ?>
	</div>
	<?php } ?>
	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $gpoint) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>ポイント</li>
			<li class='trst strong'><?php echo $gpoint; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $cp_used) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>クーポン発給</li>
			<li class='trst-cp'><?php echo $cp_btn; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['brand_nm']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>ブランド</li>
			<li class='trst'><?php echo $gs['brand_nm']; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['model']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>モデル名</li>
			<li class='trst'><?php echo $gs['model']; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['odr_min']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>最小購入数量</li>
			<li class='trst'><?php echo display_qty($gs['odr_min']); ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['odr_max']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>最大購買数量</li>
			<li class='trst'><?php echo display_qty($gs['odr_max']); ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php
	$sc_class = "sp_tbox";
	if(in_array($gs['sc_type'], array('2','3')) && $gs['sc_method'] == '2') {
		$sc_class = "sp_obox";
	}
	?>
	<div class="<?php echo $sc_class; ?>">
		<ul>
			<li class='tlst'>配送費</li>
			<li class='trst'><?php echo mobile_sendcost_amt(); ?></li>
		</ul>
	</div>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>配送可能エリア</li>
			<li class='trst padt2'><?php echo $gs['zone']; ?> <?php echo $gs['zone_msg']; ?></li>
		</ul>
	</div>

	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout) { ?>
	<?php if($option_item) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst strong'>注文オプション</li>
			<li class='trst fs11 padt2'>下オプションは必須選択オプションです</li>
		</ul>
	</div>
	<?php echo $option_item; ?>
	<?php } ?>

	<?php if($supply_item) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst strong'>追加構成</li>
			<li class='trst fs11 padt2'>追加購買がご希望でしたらお選びください。</li>
		</ul>
	</div>
	<?php echo $supply_item; ?>
	<?php } ?>

	<!-- 選択されたオプションの始まり { -->
	<div id="option_set_list">
		<?php if(!$option_item) { ?>
		<ul id="option_set_added">
			<li class="sit_opt_list">
				<div class="sp_tbox">
				<input type="hidden" name="io_type[<?php echo $gs_id; ?>][]" value="0">
				<input type="hidden" name="io_id[<?php echo $gs_id; ?>][]" value="">
				<input type="hidden" name="io_value[<?php echo $gs_id; ?>][]" value="<?php echo $gs['gname']; ?>">
				<input type="hidden" class="io_price" value="0">
				<input type="hidden" class="io_stock" value="<?php echo $gs['stock_qty']; ?>">
					<ul>
						<li class='tlst padt5'>
							<span class="sit_opt_subj">数量</span>
							<span class="sit_opt_prc"></span>
						</li>
						<li class='trst'>
							<dl>
								<dt class='fl padr3'><button type="button" class="btn_small grey">減少</button></dt>
								<dt class='fl padr3'><input type="text" name="ct_qty[<?php echo $gs_id; ?>][]"
								value="<?php echo $odr_min; ?>" title="数量設定"></dt>
								<dt class='fl padr3'><button type="button" class="btn_small grey">増加</button><dt>
								<dt class='fl padt4 tx_small'> (残りの数量 : <?php echo $gs['stock_mod'] ? $gs['stock_qty']./'個' : /'無制限'; ?>)</dt>
							</dl>
						</li>
					</ul>
				</div>
			</li>
		</ul>
		<script>
		$(function() {
			price_calculate();
		});
		</script>
		<?php } ?>
	</div>
	<!-- } 選択されたオプションの末 -->

	<!-- 総購買額 -->
	<div id="sit_tot_views" class="dn">
		<div class="sp_tot">
			<ul>
				<li class='tlst strong'>総合計金額</li>
				<li class='trst'><span id="sit_tot_price" class="trss-amt"></span><span class="trss-amt">원</span></li>
			</ul>
		</div>
	</div>
	<?php } ?>

	<?php if(!$is_pr_msg) { ?>
	<div class="sp_vbox tac">
		<?php echo mobile_buy_button($script_msg, $gs_id); ?>
		<?php if($naverpay_button_js) { ?>
		<div class="naverpay-item"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
		<?php } ?>
	</div>
	<?php } ?>

	<div class="sp_tab">
		<nav role="navigation">
			<ul>
				<li id='d1' class="active"> <a href="javascript:chk_tab(1);">商品情報</a> </li>
				<li id='d2'> <a href="javascript:chk_tab(2);">購買後記</a> </li>
				<li id='d3'> <a href="javascript:chk_tab(3);">Q&A</a> </li>
				<li id='d4'> <a href="javascript:chk_tab(4);">返品/交換</a> </li>
			</ul>
		</nav>
	</div>

	<div class="sp_msgt">下の商品情報はオプションや謝恩品情報など実際の商品と差があり得ます</div>
	<div id="v1">
		<div class="sp_vbox">
			<ul>
				<li class='tlst'>&#183;&nbsp;&nbsp;商品番号</li>
				<li class='trst'><?php echo $gs['gcode']; ?></li>
			</ul>
			<ul>
				<li class='tlst padt2'>&#183;&nbsp;&nbsp;メーカー</li>
				<li class='trst padt2'><?php echo $gs['maker']; ?></li>
			</ul>
			<ul>
				<li class='tlst padt2'>&#183;&nbsp;&nbsp;原産国（生産）</li>
				<li class='trst padt2'><?php echo $gs['origin']; ?></li>
			</ul>
			<ul>
				<li class='tlst padt2'>&#183;&nbsp;&nbsp;A/S 可能かどうか</li>
				<li class='trst padt2'><?php echo $gs['repair']; ?></li>
			</ul>
		</div>
		<div class="sp_vbox_mr">
			<ul>
				<li class='tlst'>電子商取引などでの商品情報提供告示</li>
				<li class='trst'><a href="javascript:chk_show('extra');" id="extra">見本 <span class='im im_arr'></span></a></li>
			</ul>
		</div>

		<?php
		if($gs['info_value']) {
			$info_data = unserialize(stripslashes($gs['info_value']));
			if(is_array($info_data)) {
				$gubun = $gs['info_gubun'];
				$info_array = $item_info[$gubun]['article'];
		?>
		<div class="sp_vbox" id="ids_extra" style="display:none;">
			<?php
			foreach($info_data as $key=>$val) {
				$ii_title = $info_array[$key][0];
				$ii_value = $val;
			?>
			<ul>
				<li class='tlst<?php echo $pd_t2; ?>'>&#183;&nbsp;&nbsp;<?php echo $ii_title; ?></li>
				<li class='trst<?php echo $pd_t2; ?>'><?php echo $ii_value; ?></li>
			</ul>
			<?php
				$pd_t2 = ' padt2';
			} //foreach
			?>
		</div>
		<?php
			} //array
		} //if
		?>

		<div class="sp_vbox">
			<?php echo get_image_resize($gs['memo']); ?>
		</div>

		<?php
		$sql = " select b.*
				   from shop_goods_relation a left join shop_goods b ON (a.gs_id2=b.index_no)
				  where a.gs_id = '{$gs_id}'
					and b.shop_state = '0'
					and b.isopen < 3 ";
		$res = sql_query($sql);
		$rel_count = sql_num_rows($res);
		if($rel_count > 0) {
		?>
		<div class="sp_rel">
			<h3><span>現在商品と連関した商品</span></h3>
			<div>
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
					$it_name = cut_str($row['gname'], 50);
					$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], 400, 400);
					$it_price = mobile_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (市中価格 - 割引販売価格) / 市中価格 X 100 = 割引率%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
						$it_sprice = display_price2($row['normal_price']);
					}
				?>
				<dl>
				<a href="<?php echo $it_href; ?>">
					<dt><img src="<?php echo $it_imageurl; ?>"></dt>
					<dd class="pname"><?php echo $it_name; ?></dd>
					<?php 
					if($row['info_color']) {
						echo "<dd class=\"op_color\">\n";
						$arr = explode(",", trim($row['info_color']));
						for($g=0; $g<count($arr); $g++) {
							echo get_color_boder(trim($arr[$g]), 1);
						}
						echo "</dd>\n";
					} 
					?>	
					<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
				</a>
				</dl>
				<?php } ?>
			</div>
			<?php if($rel_count > 3) { ?>
			<script>
			$(document).ready(function(){
				$('.sp_rel div').slick({
					autoplay: false,
					dots: false,
					arrows: true,
					infinite: false,
					slidesToShow: 3,
					slidesToScroll: 1
				});
			});
			</script>
			<?php } ?>
		</div>
		<?php } ?>
	</div>

	<div id="v2" style="display:none;">
		<?php echo mobile_goods_review("購買後記", $item_use_count, $gs_id); ?>
	</div>

	<div id="v3" style="display:none;">
		<?php echo mobile_goods_qa("Q&A", $itemqa_count, $gs_id); ?>
	</div>

	<div id="v4" style="display:none;">
		<div class="sp_vbox">
			<?php echo get_policy_content($gs_id); ?>
		</div>
	</div>
</div>
</form>

<script>
// 商品保管
function item_wish(f)
{
	f.action = "./wishupdate.php";
	f.submit();
}

function fsubmit_check(f)
{
    // 販売価格が0より小さいものであれば
    if (document.getElementById("it_price").value < 0) {
        alert("電話で問い合わせしてくだされば感謝します。");
        return false;
    }

	if($(".sit_opt_list").size() < 1) {
		alert("注文オプションを選択してください。");
		return false;
	}

    var val, io_type, result = true;
    var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("数量を入力してください。");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("数量は数字で入力してください。");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("数量は1以上入力してください。");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
		alert("注文オプション個数合計 "+number_format(String(min_qty))+"犬以上注文してください.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
		alert("注文オプションの個数総合 "+number_format(String(max_qty))+"犬以下で注文してください.");
        return false;
    }

    return true;
}

// 今すぐ購入, カートフォーム送信
function fbuyform_submit(sw_direct)
{
	var f = document.fbuyform;
	f.sw_direct.value = sw_direct;

	if(sw_direct == "cart") {
		f.sw_direct.value = 0;
	} else { // 今すぐ購入
		f.sw_direct.value = 1;
	}

	if($(".sit_opt_list").size() < 1) {
		alert("注文オプションを選択してください.");
		return;
	}

	var val, io_type, result = true;
	var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
	var $el_type = $("input[name^=io_type]");

	$("input[name^=ct_qty]").each(function(index) {
		val = $(this).val();

		if(val.length < 1) {
			alert("数量を入力してください.");
			result = false;
			return;
		}

		if(val.replace(/[0-9]/g, "").length > 0) {
			alert("数量は数字で入力してください。");
			result = false;
			return;
		}

		if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
			alert("数量は1以上入力してください.");
			result = false;
			return;
		}

		io_type = $el_type.eq(index).val();
		if(io_type == "0")
			sum_qty += parseInt(val);
	});

	if(!result) {
		return;
	}

	if(min_qty > 0 && sum_qty < min_qty) {
		alert("注文オプション数合計 "+number_format(String(min_qty))+"本以上のご注文してください。");
		return;
	}

	if(max_qty > 0 && sum_qty > max_qty) {
		alert("注文オプション数合計 "+number_format(String(max_qty))+"以下に注文してください。");
		return;
	}

	f.action = "./cartupdate.php";
	f.submit();
}

// 電子商取引などの商品情報提供の通知
var old = '';
function chk_show(name) {
	submenu=eval("ids_"+name+".style");

	if(old!=submenu) {
		if(old) { old.display='none'; }

		submenu.display='';
		eval("extra").innerHTML = "閉じる";
		old = submenu;

	} else {
		submenu.display='none';
		eval("extra").innerHTML = "表示";
		old = '';
	}
}

// 商品のお問い合わせ
var qa_old = '';
function qna(name){
	qa_submenu = eval("qna"+name+".style");

	if(qa_old!=qa_submenu) {
		if(qa_old) { qa_old.display='none'; }

		qa_submenu.display='block';
		qa_old=qa_submenu;

	} else {
		qa_submenu.display='none';
		qa_old='';
	}
}

// 商品のお問い合わせ、削除
$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("本当に削除しますか？\ n \ n削除した後は、元に戻すことができません.");
    });
});

// タブメニューコントロール
function chk_tab(n) {
	for(var i=1; i<=4; i++) {
		if(eval("d"+i).className == "" && i == n) {
			eval("d"+i).className = "active";
			eval("v"+i).style.display = "";
		} else {

			if(i != n) {
				eval("d"+i).className = "";
				eval("v"+i).style.display = "none";
			}
		}
	}
}

// プレビュー 画像
var num = 0;
var img_url = '<?php echo $slide_url; ?>';
var img_max = '<?php echo $slide_cnt; ?>';
var img_arr = img_url.split('|');
var slide   = new Array;
for(var i=0 ;i<parseInt(img_max);i++) {
	slide[i] = img_arr[i];
}

var cnt = slide.length-1;

function chgimg(ergfun) {
	if(document.images) {
		num = num + ergfun;
		if(num > cnt) { num = 0; }
		if(num < 0) { num = cnt; }

		document.slideshow.src = slide[num];
	}
}
</script>
