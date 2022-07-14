<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- クイックメニューの左側の翼を開始 { -->
<div id="qcl">
	<?php echo display_banner_rows(90, $pt_id); ?>
</div>
<!-- } 
クイックメニューの左側翼端 -->

<!-- クイックメニューの右の翼を開始 { -->
<div id="qcr">
	<ul>
		<li class="tit">最近見た商品</li>
		<li>
			<?php
			$pr_tmp = get_cookie('ss_pr_idx');
			$pr_idx = explode('|',$pr_tmp);
			$pr_tot_count = 0;
			$k = 0;
			$mod = 3;
			foreach($pr_idx as $idx)
			{
				$rowx = get_goods($idx, 'simg1');
				if(!$rowx['index_no'])
					continue;

				$href = TB_SHOP_URL.'/view.php?index_no='.$idx;

				if($pr_tot_count % $mod == 0) $k++;

				$pr_tot_count++;
			?>
			<p class="dn c<?php echo $k; ?>">
				<a href="<?php echo $href; ?>"><?php echo get_it_image($idx, $rowx['simg1'], 60, 60); ?></a>
			</p>
			<?php
			}
			if(!$pr_tot_count)
				echo '<p class="no_item">없음</p>'
			?>
		</li>
		<?php if($pr_tot_count > 0){ ?>
		<li class="stv_wrap">
			<img src="<?php echo TB_IMG_URL; ?>/bt_qcr_prev.gif" id="up">
			<span id="stv_pg"></span>
			<img src="<?php echo TB_IMG_URL; ?>/bt_qcr_next.gif" id="down">
		</li>
		<?php } ?>
	</ul>
</div>
<!-- } クイックメニューの右の翼端 -->

<div class="qbtn_bx">
	<button type="button" id="anc_up">TOP</button>
	<button type="button" id="anc_dw">DOWN</button>
</div>

<script>
$(function() {
	var itemQty = <?php echo $pr_tot_count; ?>; // 総アイテム数量
	var itemShow = <?php echo $mod; ?>; // 一度に見せるアイテム数量
	var Flag = 1; // ページ
	var EOFlag = parseInt(itemQty/itemShow); // すべてのリストを分けてページの最大値を求め,
	var itemRest = parseInt(itemQty%itemShow); // 残りの値を求めた後、
	if(itemRest > 0) // 残りの値がある場合
	{
		EOFlag++; //ページ最大値を1増加させる。
	}
	$('.c'+Flag).css('display','block');
	$('#stv_pg').text(Flag+'/'+EOFlag); // ページの初期出力値
	$('#up').click(function() {
		if(Flag == 1)
		{
			alert(/'リストの最初のです。');
		} else {
			Flag--;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag+1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // ページの値をリセット
	})
	$('#down').click(function() {
		if(Flag == EOFlag)
		{
			alert(/'これ以上のリストがありません。');
		} else {
			Flag++;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag-1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // ページの値をリセット
	});

	$(window).scroll(function () {
		var pos = $("#ft").offset().top - $(window).height();
		if($(this).scrollTop() > 0) {
			$(".qbtn_bx").fadeIn(300);
			if($(this).scrollTop() > pos) {
				$(".qbtn_bx").addClass('active');
			}else{
				$(".qbtn_bx").removeClass('active');
			}
		} else {
			$(".qbtn_bx").fadeOut(300);
		}
	});

	// クイックメニューの上部へ戻る
    $("#anc_up").click(function(){
        $("html, body").animate({ scrollTop: 0 }, 400);
    });

	// 下位に移動
    $("#anc_dw").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 400);
    });

	// 左/右クイックメニューの高さを自動調整
	<?php if(defined('_INDEX_')) { ?>
	var Theight = $("#header").height() - $("#gnb").height();
	var ptop = 30;
	<?php } else { ?>
	var Theight = $("#header").height() - $("#gnb").height();
	var ptop = 20;
	<?php } ?>
	$("#qcr, #qcl").css({'top':ptop + 'px'});

	$(window).scroll(function () {
		if($(this).scrollTop() > Theight) {
			$("#qcr, #qcl").css({'position':'fixed','top':'67px','z-index':'999'});
		} else {
			$("#qcr, #qcl").css({'position':'absolute','top':ptop + 'px'});
		}
	});
});
</script>
<!-- } 右側クイックメニュー終了 -->
