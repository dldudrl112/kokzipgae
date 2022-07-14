<?php
if (!defined('_TUBEWEB_')) exit;


?>
<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가


$begin_time = get_microtime();

if (!isset($tb['title'])) {
	$tb['title'] = get_head_title('head_title', $pt_id);
	$tb_head_title = $tb['title'];
} else {
	$tb_head_title = $tb['title']; // 상태바에 표시될 제목
	$tb_head_title .= " | " . get_head_title('head_title', $pt_id);
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$tb['lo_location'] = addslashes($tb['title']);
if (!$tb['lo_location'])
	$tb['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$tb['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($tb['lo_url'], '/' . TB_ADMIN_DIR . '/') || is_admin()) $tb['lo_url'] = '';





/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">

<head>
	<meta charset="utf-8">
	<meta http-equiv="imagetoolbar" content="no">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- 테스트로 넣어봅니다. -->
	<?php
	include_once(TB_LIB_PATH . '/seometa.lib.php');

	if ($config['add_meta'])
		echo $config['add_meta'] . PHP_EOL;
	?>
	<title><?php echo $tb_head_title; ?></title>
	<link rel="stylesheet" href="<?php echo TB_CSS_URL; ?>/default.css?ver=<?php echo TB_CSS_VER; ?>">
	<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/style.css?ver=<?php echo TB_CSS_VER; ?>">
	<?php if ($ico = display_logo_url('favicon_ico')) { // 파비콘 
	?>
		<link rel="shortcut icon" href="<?php echo $ico; ?>" type="image/x-icon">
	<?php } ?>
	<!-- Font Noto Sans -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
	<script>
		var tb_url = "<?php echo TB_URL; ?>";
		var tb_bbs_url = "<?php echo TB_BBS_URL; ?>";
		var tb_shop_url = "<?php echo TB_SHOP_URL; ?>";
		var tb_mobile_url = "<?php echo TB_MURL; ?>";
		var tb_mobile_bbs_url = "<?php echo TB_MBBS_URL; ?>";
		var tb_mobile_shop_url = "<?php echo TB_MSHOP_URL; ?>";
		var tb_is_member = "<?php echo $is_member; ?>";
		var tb_is_mobile = "<?php echo TB_IS_MOBILE; ?>";
		var tb_cookie_domain = "<?php echo TB_COOKIE_DOMAIN; ?>";
	</script>
	<script src="<?php echo TB_JS_URL; ?>/jquery-1.8.3.min.js"></script>
	<script src="<?php echo TB_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
	<script src="<?php echo TB_JS_URL; ?>/common.js?ver=<?php echo TB_JS_VER; ?>"></script>
	<script src="<?php echo TB_JS_URL; ?>/slick.js"></script>
	<?php if ($config['mouseblock_yes']) { // 마우스 우클릭 방지 
	?>
		<script>
			$(document).ready(function() {
				$(document).bind("contextmenu", function(e) {
					return false;
				});
			});
			$(document).bind('selectstart', function() {
				return false;
			});
			$(document).bind('dragstart', function() {
				return false;
			});
		</script>
	<?php } ?>
	<?php
	if ($config['head_script']) { // head 내부태그
		echo $config['head_script'] . PHP_EOL;
	}

	$at    =  sql_fetch("select count(*) as re_cnt , if(isnull(round(sum(score)/count(*),1)), '0',  round(sum(score)/count(*),1)) as score from shop_goods_review where  gs_id= '" . $row['index_no'] . "'");

	$goods_list[$i]  =  $row;


	$goods_list[$i]["re_cnt"]            =  $at["re_cnt"];
	?>


</head>
<body<?php echo isset($tb['body_script']) ? $tb['body_script'] : ''; ?>>



	<?php
	if (!defined('_TUBEWEB_')) exit;

	if (defined('_INDEX_')) { // index에서만 실행
		include_once(TB_LIB_PATH . '/popup.inc.php'); // 팝업레이어
	}


	if (is_mobile() == true) {
		$filed  =  "mobile_logo";
	} else {
		$filed  =  "basic_logo";
	}

	$row = sql_fetch("select $filed from shop_logo where mb_id='$pt_id'");

	if (!$row[$filed] && $pt_id != 'admin') {
		$row = sql_fetch("select $filed from shop_logo where mb_id='admin'");
	}

	$file  =  TB_DATA_URL . '/banner/' . $row[$filed];

	?>
	<!-- Header Area Start -->
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MPFD645" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<header style="border-bottom: 1px solid #f3f3f3;">
		<div class="gnb">
			<div class="container">

				<ul>
					<?php
					$counseling_type  =  trim($_POST['counseling_type']);
					$tnb = array();

					if ($is_admin)
						$tnb[] = '<li><a href="' . $is_admin . '" target="_blank">관리자</a></li>';
					if ($member['id']) {
						$tnb[] = '<li><a href="' . TB_BBS_URL . '/logout.php">로그아웃</a></li>';
						$tnb[] = '<li><a href="' . TB_SHOP_URL . '/orderinquiry.php">마이페이지</a></li>';
					} else {
						$tnb[] = '<li><a href="' . TB_BBS_URL . '/login.php?url=' . $urlencode . '" id="login">로그인</a></li>';
						$tnb[] = '<li><a href="' . TB_BBS_URL . '/register_intro.php" id="join">회원가입</a></li>';
					}

					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
		</div>
		<div class="hd">
			<div class="container">
				<div class="logo">
					<h1><a href="/index.php#main_category" class="ir_w" style="background:url('<?= $file ?>') no-repeat 50% 50% / cover;">콕집게</a></h1>
					<h1><a href="javascript:history.back()" class="ir_m" style="background:url('/img/main_prev1.png') no-repeat 50% 50%; width:10%; background-size:24px; float:left; height:26px; margin-bottom:10px;">콕집게</a></h1>
				</div>
				<form name="fsearch" id="fsearch" method="post" action="/" autocomplete="off">
					<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
					<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="제 사주팔자를 알고싶어요." value="<?= $_REQUEST["ss_tx"] ?>">
					<button type="submit"><img src="<?php echo TB_IMG_URL; ?>/search_ico.png" alt="search_icon"></button>
				</form>
				<ul>
					<?php
					$tnb = array();
					$tnb[] = '<li><a href="/index.php#main_category"><img src="' . TB_IMG_URL . '/hd_ico1.png" alt="ico1"><p>콕집게</p></a></li>';
					$tnb[] = '<li><a href="' . TB_SHOP_URL . '/wish.php"><img src="' . TB_IMG_URL . '/hd_ico2.png" alt="ico2"><p>찜주머니</p></a></li>';
					$tnb[] = '<li><a href="' . TB_SHOP_URL . '/orderinquiry.php"><img src="' . TB_IMG_URL . '/hd_ico3.png" alt="ico3"><p>마이메뉴</p></a></li>';
					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
		</div>
		<!-- <div class="m_alarm_ico">
      <a class="share_bt"><img src="<?php echo TB_IMG_URL; ?>/m_share_ico.png" alt="quick_ico"></a>
    </div>
    <div class="m_quick_left_top">
      <a href="javascript:itemlistwish('<?php echo $index_no; ?>');" class="m_bokzzim"><img src="<?php echo TB_IMG_URL; ?>/<? if ($wish_cnt["cnt"] == 0) {
																																echo "hd_ico22.png";
																															} else {
																																echo "m_call_dibs_ico.png";
																															} ?>" alt="ico2"><img src="https://dldydrl112.cafe24.com:443/img/hd_ico22.png" alt="ico2" style="display: none;"></a>
    </div> -->
	</header><!-- Header Area End -->



	<!-- Mypage Area Start -->
	<div id="mypage" class="pd_12">

		<!-- Mem_info Area Start -->
		<? include_once(TB_THEME_PATH . '/aside_mem_info.skin.php'); ?>
		<!-- Mem_info Area End -->

		<!-- My_box Area Start -->
		<div class="my_box">
			<div class="container1">
				<!-- Left_box Area Start -->
				<? include_once(TB_THEME_PATH . '/aside_my.skin.php'); ?>
				<!-- Right_box Area Start -->
				<div class="right_box">
					<!-- Content_box Area Start -->
					<div class="content_box">
						<!-- Sub_title01 Area Start -->
						<div class="sub_title01">
							<h3>결제내역</h3>
						</div><!-- Sub_title01 Area End -->
						<div id="my_pay_list">
							<ul>
								<?php
								$upl_dir			=	TB_DATA_URL . "/member";

								for ($i = 0; $row = sql_fetch_array($result); $i++) {
									$sql = " select * from shop_cart where od_id = '$row[od_id]' ";
									$sql .= " group by gs_id order by io_type asc, index_no asc ";
									$res = sql_query($sql);
									$rowspan = sql_num_rows($res) + 1;

									for ($k = 0; $ct = sql_fetch_array($res); $k++) {

										$od	=	get_order($ct['od_no']);
										$gs	=	unserialize($od['od_goods']);
										$tc	=	sql_fetch("select a.*, b.mem_img, b.cellphone from shop_teacher a, shop_member b where a.mb_code = b.index_no and a.index_no = '" . $gs["tc_code"] . "'");

										$dlcomp	=	explode('|', trim($od['delivery']));

										$href			=	TB_SHOP_URL . '/view.php?index_no=' . $od['gs_id'];

										//전화하기 팝업 구분
										if ($od["dan"] == 2 || $od["dan"] == 3 || $od["dan"] == 4) {
											if (is_mobile() == true) {
												$act_popup	=		"<a class=\"tel_btn\" href=\"tel:" . $tc["cellphone"] . "\">전화하기</a>";
											} else {
												$act_popup	=		"<a class=\"tel_btn\" href=\"javascript:phone_box('" . $od['od_id'] . "');\">전화하기</a>";
											}
										} else {
											if ($od["dan"] == 5) {
												$act_popup	=		"<a class=\"tel_btn\" href=\"" . TB_SHOP_URL . "/orderreview.php?od_id=" . $od['od_id'] . "\">리뷰쓰기</a>";
											}
										}


								?>
										<li>
											<?php if ($od["dan"] == 2 || $od["dan"] == 3 || $od["dan"] == 4) { ?>
												<div class="phone_box" id="phone_box_<?= $od['od_id'] ?>">
													<div class="info">상담안내</div>
													<div class="infoDetail">
														<p><strong>[ <?= $gs["gname"] ?> ]</strong> 상담을 원하시면 </p>
														<p><strong>[<?= $tc["cellphone"] ?>]</strong> 으로 전화주시면 됩니다.</p>
													</div>
													<button type="button">확인</button>
												</div>
											<?php } ?>
											<div class="text_king">
												<div class="text_box">
													<div class="text_box_top">
														<div class="stat"><span><?= $tc["name"] ?> / <?= $gs["brand_nm"] ?></span></span></div>
														<div class="state"><span class="state12"><?php echo $gw_status[$od['dan']]; ?></span></div>
													</div>
												</div>
												<div class="text_box">
													<a href="<?php echo $href; ?>" class="box">
														<div class="text_box_top">
															<div class="left_box">
																<div class="img_box">
																	<?php if ($tc["mem_img"]) { ?>
																		<img src="<?php echo $upl_dir; ?>/<?= $tc["mem_img"] ?>" alt="mem">
																	<?php } else { ?>
																		<img src="<?php echo TB_IMG_URL; ?>/my_page_mem.png" alt="mem">
																	<?php } ?>
																</div>
																<div class="name"><?= $tc["name"] ?></div>
															</div>
															<div class="right_box">
																<div class="txt">상담 가격</div>
																<div class="txt1">결제 일시</div>
																<!-- <dl>
																	<dt class="date">결제 일시<?= str_replace("-", ".", substr($od["od_time"], 0, 10)) ?></dt>
																	<dd class="price"><?php echo display_price($od['use_price']); ?></dd>
																</dl> -->
															</div>
															<div class="lef_box">
																<div class="txt"><?php echo display_price($od['use_price']); ?></div>
																<div class="txt1"><?= str_replace("-", ".", substr($od["od_time"], 0, 10)) ?></div>
																<!-- <dl>
																	<dt class="date">결제 일시<?= str_replace("-", ".", substr($od["od_time"], 0, 10)) ?></dt>
																	<dd class="price"><?php echo display_price($od['use_price']); ?></dd>
																</dl> -->
															</div>
														</div>
													</a>
												</div>
											</div>
											<div class="btn">
												<?= $act_popup ?>
											</div>
										</li>
								<?php
									}
								}
								if ($i == 0)
									echo '<li><div class="empty_list">결제내역이 없습니다.</div></li>';
								?>
							</ul>
						</div>
					</div><!-- Content_box Area End -->
				</div><!-- Right_box Area End -->
			</div>
		</div><!-- My_box Area End -->
	</div><!-- Mypage Area End -->
	<script>
		function phone_box(od_id) {
			$("#phone_box_" + od_id).show();
		}
	</script>