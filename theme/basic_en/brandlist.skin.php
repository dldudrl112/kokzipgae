﻿<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'br_id='.$br_id.'&page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'br_id='.$br_id.'&page_rows='.$page_rows;
$qstr3 = 'br_id='.$br_id.'&sort='.$sort.'&sortodr='.$sortodr;

$sort_str = '';
for($i=0; $i<count($gw_psort); $i++) {
	list($tsort, $torder, $tname) = $gw_psort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	$active = '';
	if($sort == $tsort && $sortodr == $torder)
		$active = ' class="active"';
	if($i==0 && !($sort && $sortodr))
		$active = ' class="active"';

	$sort_str .= '<li><a href="'.$sct_sort_href.'"'.$active.'>'.$tname.'</a></li>'.PHP_EOL;
}
?>

<h2 class="pg_tit">
	<span>Brand shop</span>
	<p class="pg_nav">HOME<i>&gt;</i>Brand shop<i>&gt;</i><?php echo $tb['title']; ?></p>
</h2>

<h2 class="br_view_tit">
	<span class="tit_txt"><?php echo $tb['title']; ?></span>
	<span class="tit_logo"><img src="<?php echo $br_logo; ?>" title="<?php echo $tb['title']; ?>"></span>
</h2>

<div class="tab_sort">
	<span class="total">Total Products <b class="fc_90" id="total"><?php echo number_format($total_count); ?></b>the number</span>
	<ul>
		<?php echo $sort_str; // Tap Menu ?>
	</ul>
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$qstr3}";?>&page_rows='+this.value;">
		<?php echo option_selected(($mod*5),  $page_rows, '5page rows'); ?>
		<?php echo option_selected(($mod*10), $page_rows, '10page rows'); ?>
		<?php echo option_selected(($mod*15), $page_rows, '15page rows'); ?>
	</select>
</div>

<div class="pr_desc wli4">
	<ul>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], 235, 235);
		$it_name = cut_str($row['gname'], 100);
		$it_price = get_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		// (a market price - sale prices) / a market price X 100 = discount rate%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
			$it_sprice = display_price2($row['normal_price']);
		}
	?>
		<li>
			<a href="<?php echo $it_href; ?>">
			<dl>
				<dt><?php echo $it_image; ?></dt>
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
			</dl>
			</a>
			<p class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></p>
		</li>
	<?php } ?>
	</ul>
</div>

<?php if(!$total_count) { ?>
<div class="empty_list bb">We don't have any data.</div>
<?php } ?>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
?>
