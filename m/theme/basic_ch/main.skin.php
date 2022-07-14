<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<?php if($slider1 = mobile_slider(0, $pt_id)) { ?>
<!-- 启动主横幅 { -->
<div id="main_bn">
	<?php echo $slider1; ?>
</div>
<script>
$(document).on('ready', function() {
	$('#main_bn').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		fade: true
	});
});
</script>
<!-- } 主要横幅结束 -->
<?php } ?>

<!-- 从主横幅的底部开始 { -->
<ul class="mbm_bn01">
	<li class="bnr1"><?php echo mobile_banner(2, $pt_id); ?></li>
	<li class="bnr2"><?php echo mobile_banner(3, $pt_id); ?></li>
	<li class="bnr3"><?php echo mobile_banner(4, $pt_id); ?></li>
</ul>
<!-- } 主横幅的底部 尽头 -->

<!-- 购物特价开始 { -->
<div class="pr_slide" id="type5">
	<?php echo mobile_slide_goods('5', '20', /'购物特价', 'slider'); ?>
	<script>
    $(document).on('ready', function() {
      $("#type5 .slider").slick({
		autoplay: true,
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1
      });
    });
	</script>
</div>
<!-- } 购物特价 尽头 -->

<?php
if($default['de_maintype_best']) {
	$list_best = unserialize(base64_decode($default['de_maintype_best']));
	$list_count = count($list_best);
?>
<!-- 各类别马甲 开始 {-->
<div class="bscate mart30">
	<h2 class="mtit"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<div class="bscate_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<a><span><?php echo trim($list_best[$i]['subj']); ?></span></a>
		<?php } ?>
	</div>
	<div class="bscate_li">
		<?php echo mobile_listtype_cate($list_best); ?>
	</div>
	<script>
	$(document).ready(function(){
		$('.bscate_li').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			infinite: false,
			asNavFor: '.bscate_tab'
		});
		$(".bscate_tab").slick({
			autoplay: false,
			dots: false,
			infinite: false,
			centerMode: true,
			variableWidth: true,
			slidesToScroll: 1,
			asNavFor: '.bscate_li',
			focusOnSelect: true
		});
	});
	</script>
</div>
<!-- } 各类别马甲 尽头 -->
<?php } ?>

<?php if($banner = mobile_banner(5, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 畅销书 开始 {-->
<div class="mart30">
	<?php echo mobile_display_goods('2', '6', /'畅销书', 'pr_desc wli2'); ?>
</div>
<!-- } 畅销书 尽头 -->

<?php if($banner = mobile_banner(6, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 新产品 开始 { -->
<div class="mart30">
	<?php echo mobile_display_goods('3', '6', /'新产品', 'pr_desc wli2'); ?>
</div>
<!-- } 新产品 尽头 -->

<?php if($banner = mobile_banner(7, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 热门商品 开始 { -->
<div class="mart30">
	<?php echo mobile_display_goods('4', '6', /'热门商品', 'pr_desc wli2'); ?>
</div>
<!-- } 热门商品 尽头 -->

<?php if($banner = mobile_banner(8, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 推荐商品 开始 { -->
<div class="mart30">
	<?php echo mobile_display_goods('5', '6', /'推荐商品', 'pr_desc wli2'); ?>
</div>
<!-- } 推荐商品 尽头 -->
