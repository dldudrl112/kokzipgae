<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<div id="slideMenu">
	<dl class="top_btn">
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/logout.php" class="btn_medium">ログアウト</a>
			<?php } else { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="btn_medium">ログイン</a>
			<?php } ?>
		</dd>
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/register_form.php?w=u" class="btn_medium bx-white">情報修正</a>
			<?php } else { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white">会員加入</a>
			<?php } ?>
		</dd>
	</dl>
	<ul class="sm_icbt">
		<li><a href="<?php echo TB_MSHOP_URL; ?>/cart.php"><i class="ionicons ion-android-cart"></i> カート</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php"><i class="ionicons ion-ios-list-outline"></i> 注文/配送</a></li>
		<li><a href="<?php echo TB_MBBS_URL; ?>/review.php"><i class="ionicons ion-android-camera"></i> 購買後記</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/mypage.php"><i class="ionicons ion-android-contact"></i> マイページ</a></li>
	</ul>
	<ul class="smtab">
		<li data-tab="shop_cate">カテゴリ</li>
		<li data-tab="custom">顧客センター</li>
		<li data-tab="mypage">マイページ</li>
	</ul>
	<div id="shop_cate" class="sm_body">
		<?php
		$r = sql_query_cgy('all', 'COUNT');
		if($r['cnt'] > 0){
		?>
		<ul>
			<?php
			$res = sql_query_cgy('all');
			while($row = sql_fetch_array($res)) {
				$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
			?>
			<li class="bm"><?php echo $row['catename'];?></li>
			<li class="subm">
				<ul>
					<li><a href="<?php echo $href;?>">全体</a></li>
					<?php
					$res2 = sql_query_cgy($row['catecode']);
					while($row2 = sql_fetch_array($res2)) {
						$href2 = TB_MSHOP_URL.'/list.php?ca_id='.$row2['catecode'];
					?>
					<li><a href="<?php echo $href2;?>"><?php echo $row2['catename']; ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<p class="sct_noitem">登録された分類がありません。</p>
		<?php } ?>
	</div>
	<div id="custom" class="sm_body">
		<ul>			
			<?php
			$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
			$res = sql_query($sql);
			for($i=0; $row=sql_fetch_array($res); $i++) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
			<?php } ?>	
			<li><a href="<?php echo TB_MBBS_URL; ?>/review.php">購買後記</a></li>
			<li><a href="<?php echo TB_MBBS_URL; ?>/qna_list.php">1:1 相談お問い合わせ</a></li>
			<li><a href="<?php echo TB_MBBS_URL; ?>/faq.php">よくある質問</a></li>			
		</ul>
	</div>
	<div id="mypage" class="sm_body">
		<ul>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php">注文/配送照会</a></li>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/point.php">ポイント照会</a></li>
			<?php if($config['gift_yes']) { ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/gift.php">クーポン認証</a></li>
			<?php } ?>
			<?php if($config['coupon_yes']) { ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php">クーポン管理</a></li>
			<?php } ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/wish.php">お気に入り商品</a></li>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/today.php">最近見た商品</a></li>
			<?php if($is_member) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/leave_form.php">会員脱会</a></li>
			<?php } ?>
		</ul>
	</div>
	<dl class="sm_cs">
		<dt>顧客センター</dt>
		<dd class="cs_tel"><?php echo $config['company_tel']; ?></dd>
		<dd>相談 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
		<dd>お昼 : <?php echo $config['company_lunch']; ?></dd>
	</dl>
	<dl class="sm_cs">
		<dt>\振込口座のご案内</dt>
		<?php $bank = unserialize($default['de_bank_account']); ?>
		<dd><?php echo $bank[0]['name']; ?> <b><?php echo $bank[0]['account']; ?></b></dd>
		<dd>イェグムジュミョン : <?php echo $bank[0]['holder']; ?></dd>
	</dl>
	<p class="mart20"><a href="tel:<?php echo $config['company_tel']; ?>" class="btn_medium grey wfull">お客様センターの電話接続</a></p>
</div>
<script>
$(function(){
	// 左スライドメニューのサブメニュー動作
	$('#slideMenu .subm').hide();
	$('#slideMenu .bm').click(function(){
		if($(this).hasClass('active')){
			$(this).next().slideUp(250);
			$(this).removeClass('active');
		} else {
			$('#slideMenu .bm').removeClass('active');
			$('#slideMenu .subm').slideUp(250);
			$(this).addClass('active');
			$(this).next().slideDown(250);
		}
	});

	// 上段メニューボタンクリック時,メニューページのスライド
	$(".btn_sidem").click(function () {
		$("#slideMenu, #wrapper, .page_cover, html").addClass("m_open");
		window.location.hash = "#Menu";
		$("#wrapper, html").css({
			height: $(window).height()
		});
	});
	window.onhashchange = function () {
		if(location.hash != "#Menu") {
			$("#slideMenu, #wrapper, .page_cover, html").removeClass("m_open");
			$("#wrapper, html").css({
				height:'100%'
			});
		}
	};

	//タップ機能
	$(document).ready(function(){
		$(".smtab>li:eq(0)").addClass('active');
		$("#shop_cate").addClass('active');

		$(".smtab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".smtab>li").removeClass('active');
			$(".sm_body").removeClass('active');
			$(this).addClass('active');
			$("#"+activeTab).addClass('active');
		});
	});
});
</script>
