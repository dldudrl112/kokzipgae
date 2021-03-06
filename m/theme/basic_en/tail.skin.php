<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

	</div>
	<span class="btn_top fa fa-chevron-up"></span>
	<span class="btn_bottom fa fa-chevron-down"></span>

	<?php
	if($default['de_insta_access_token']) { // Instagram
	   $userId = explode(".", $default['de_insta_access_token']);
	?>
	<script src="<?php echo TB_JS_URL; ?>/instafeed.min.js"></script>
	<script>
		var userFeed = new Instafeed({
			get: 'user',
			userId: "<?php echo $userId[0]; ?>",
			limit: 6,
			template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></li>',
			accessToken: "<?php echo $default['de_insta_access_token']; ?>"
		});
		userFeed.run();
	</script>

	<div class="insta">
		<h2 class="tac"><i class="fa fa-instagram"></i> INSTAGRAM<a href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>" target="_blank">@ <?php echo $default['de_insta_url']; ?></a></h2>
		<ul id="instafeed">
		</ul>
	</div>
	<?php } ?>

	<div class="sns_wrap">
		<?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
		<?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
		<?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
		<?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
		<?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
		<?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
		<?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
		<?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
	</div>

	<footer id="ft">
		<ul class="ft_menu">
			<?php if(TB_DEVICE_BUTTON_DISPLAY && TB_IS_MOBILE) { ?>
			<li><a href="<?php echo TB_URL; ?>/index.php?device=pc">PCVersion</a></li>
			<?php } ?>
			<?php if($config['partner_reg_yes']) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/partner_reg.php">Shopping mall distribution application</a></li>
			<?php } ?>
			<?php if($config['seller_reg_yes']) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/seller_reg.php">Online entry application</a></li>
			<?php } ?>
			<li><a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');">Confirm carrier information</a></li>
		</ul>
		<dl class="ft_cs">
			<dt>Customer Center / Account Information</dt>
			<dd class="tel"><?php echo $config['company_tel']; ?></dd>
			<dd>consulting : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
			<dd>lunch : <?php echo $config['company_lunch']; ?></dd>
			<?php $bank = unserialize($default['de_bank_account']); ?>
			<dd><?php echo $bank[0]['name']; ?> <span class="bank_num"><?php echo $bank[0]['account']; ?></span> Account Holder : <?php echo $bank[0]['holder']; ?></dd>
		</dl>
		<dl class="ft_address">
			<dd><strong><?php echo $config['company_name']; ?></strong> <span class="marl15">representative : <?php echo $config['company_owner']; ?></span></dd>
			<dd><?php echo $config['company_addr']; ?></dd>
			<dd>Customer Service : <?php echo $config['company_tel']; ?> <span class="marl15">FAX : <?php echo $config['company_fax']; ?></span></dd>
			<dd>Business license number : <?php echo $config['company_saupja_no']; ?></dd>
			<dd>Communication sales business report : <?php echo $config['tongsin_no']; ?></dd>
			<dd>E-mail : <?php echo $super['email']; ?></dd>
			<dd>Personal Information Protection Officer : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)</dd>
		</dl>
		<p class="ft_crt">COPYRIGHT © <?php echo $config['company_name']; ?> ALL RIGHTS RESERVED.</p>
	</footer>
</div>

<script>
$(function() {
	// Move to Upper
	$(".btn_top").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 300);
	});
	// Move To Lower
    $(".btn_bottom").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 300);
    });

	$(window).scroll(function () {
		if($(this).scrollTop() > 0) {
			$(".btn_top, .btn_bottom").fadeIn(300);
		} else {
			$(".btn_top, .btn_bottom").fadeOut(300);
		}
	});

	// When scrolling the top menu fixed
	var adheight = $(".top_ad").height() + $("#gnb").height();
	$(window).scroll(function () {
		if($(this).scrollTop() > adheight) {
			$("#header").addClass('active');
			$("#container").addClass('padt45');
		} else {
			$("#header").removeClass('active');
			$("#container").removeClass('padt45');
		}
	});

	// List horizontal count control
	$('.sct_li_type > a').on('click', function() {
		var this_type = $(this).closest('.sct_li_type');
		var this_a = $(this);
		var listtype = $(this).attr('href');

		// Link > Image Initialization
		this_type.find('a').each(function() {
			var imgSrc = $(this).find('img').attr('src').replace('<?php echo TB_MTHEME_URL; ?>/img/', '').split('.');
			$(this).find('img').attr('src', '<?php echo TB_MTHEME_URL; ?>/img/'+imgSrc[0].replace('_on','')+'.'+imgSrc[1]);
		});

		// Selected Link > Attach Image _on
		var img_src = this_a.find('img').attr('src').replace('<?php echo TB_MTHEME_URL; ?>/img/', '').split('.');
		this_a.find('img').attr('src', '<?php echo TB_MTHEME_URL; ?>/img/'+img_src[0]+'_on'+'.'+img_src[1]);

		this_type.next('ul').removeClass('wli1 wli2 wli3');
		this_type.next('ul').addClass(listtype);

		return false;
	});
});
</script>
