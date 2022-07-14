<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 快菜单左翼开始 { -->
<div id="qcl">
	<?php echo display_banner_rows(90, $pt_id); ?>
</div>
<!-- } 快菜单左翼末端 -->

<!-- 快菜单右翼开始 { -->
<div id="qcr">
	<ul>
		<li class="tit">最近看到的商品</li>
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
<!-- } 快速菜单右端 -->

<div class="qbtn_bx">
	<button type="button" id="anc_up">TOP</button>
	<button type="button" id="anc_dw">DOWN</button>
</div>

<script>
$(function() {
	var itemQty = <?php echo $pr_tot_count; ?>; // 总单品数量
	var itemShow = <?php echo $mod; ?>; // 一次展示的单品数量
	var Flag = 1; // 页
	var EOFlag = parseInt(itemQty/itemShow); // 将整张清单分开,以求页面最值钱
	var itemRest = parseInt(itemQty%itemShow); // 求得剩余价格后
	if(itemRest > 0) // 如果剩下的价格
	{
		EOFlag++; // 页数增加1。
	}
	$('.c'+Flag).css('display','block');
	$('#stv_pg').text(Flag+'/'+EOFlag); // 页面初始输出值
	$('#up').click(function() {
		if(Flag == 1)
		{
			alert(/'这是列表的开头.');
		} else {
			Flag--;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag+1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // 页面价格重新设定
	})
	$('#down').click(function() {
		if(Flag == EOFlag)
		{
			alert(/'已无目录.');
		} else {
			Flag++;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag-1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // 页面价格重新设定
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

	// 快速菜单上位移
    $("#anc_up").click(function(){
        $("html, body").animate({ scrollTop: 0 }, 400);
    });

	// 低速移动
    $("#anc_dw").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 400);
    });

	// 左/右 快速菜单高度自动调节
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
<!-- } 右侧快速菜单 -->
