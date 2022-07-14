﻿<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="cont_wrap">
	<!-- Start Best Shopping Specials and Banners { -->
	<div class="best_wrap">
		<div class="bnr1"><?php echo display_banner(3, $pt_id); ?></div>
		<div class="bnr2"><?php echo display_banner(4, $pt_id); ?></div>
		<div class="bnr3"><?php echo display_banner(5, $pt_id); ?></div>
		<div class="best_rol_slide">
			<h2>Special price for shopping</h2>
			<?php
			$res = display_itemtype($pt_id, 1, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="best_rol">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 190, 190);
					$it_name = cut_str($row['gname'], 100);
					$it_price = get_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (a market price - a bargain sale price) / a market price X 100 = discount rate%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<dd class="sale">'.number_format($sett,0).'%</dd>';
						$it_sprice = display_price2($row['normal_price']);
					}
				?>
				<dl>
					<?php echo $sale; ?>
					<a href="<?php echo $it_href; ?>">
						<dt class="pimg"><?php echo $it_image; ?></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
					</a>
					<dd class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></dd>
				</dl>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php if($type1_count) { ?>
		<script>
		$(document).ready(function(){
			$('.best_rol').slick({
				autoplay: true,
				dots: false
			});
		});
		</script>
		<?php } ?>
	</div>
	<!-- } Best shopping special price and end of banner -->
</div>

<!-- Best Start by Category {-->
<div class="cont_wrap">
	<?php
	if($default['de_maintype_best']) {
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		$list_count = count($list_best);
		$tab_width = (float)(100 / $list_count);
	?>
	<h2 class="mtit mart65"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<ul class="bestca_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<li data-tab="bstab_c<?php echo $i; ?>" style="width:<?php echo $tab_width; ?>%"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
		<?php } ?>
	</ul>
	<div class="bestca">
		<?php echo get_listtype_cate($list_best, '209', '209'); ?>
	</div>
	<script>
	$(document).ready(function(){
		$(".bestca_tab>li:eq(0)").addClass('active');
		$("#bstab_c0").show();

		$(".bestca_tab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".bestca_tab>li").removeClass('active');
			$(".bestca ul").hide();
			$(this).addClass('active');
			$("#"+activeTab).fadeIn(250);
		});
	});
	</script>
	<?php } ?>

	<div class="wide_bn mart40"><?php echo display_banner(6, $pt_id); ?></div>
</div>
<!-- } Best end by category -->

<!-- Start Best Product {-->
<div class="cont_bg mart40">
	<h2 class="mtit"><span>bestseller</span></h2>
	<?php echo get_listtype_best("2", '400', '400', '7', 'mart20'); ?>
</div>
<!-- } the end of a best product -->

<!-- Start a new product { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>New product</span></h2>
	<?php echo get_listtype_skin("3", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } the end of a new product -->

<!--Start large banner background and phrase { -->
<?php echo mask_banner(7, $pt_id); ?>
<!-- } Large banner background and end of phrase -->

<!-- Start Popularity { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>Popular product</span></h2>
	<?php echo get_listtype_skin("4", '315', '315', '6', 'wli3 mart5'); ?>
</div>
<!-- } End of Popularity -->

<!-- Medium Banner Zone { -->
<ul class="mmd_bn mart60">
	<li class="bnr1"><?php echo display_banner(8, $pt_id); ?></li>
	<li class="bnr2"><?php echo display_banner(9, $pt_id); ?></li>
	<li class="bnr3"><?php echo display_banner(10, $pt_id); ?></li>
	<li class="bnr4"><?php echo display_banner(11, $pt_id); ?></li>
</ul>
<!-- } Intermediate Banner Area End -->

<!-- Start Recommendation { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>Recommended product</span></h2>
	<?php echo get_listtype_skin("5", '190', '190', '10', 'wli5 mart5'); ?>
</div>
<!-- } End of Recommendation -->
