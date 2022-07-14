<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="fseller_result">
	<h3 class="anc_tit">事業者情報</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">提供商品</th>
			<td><?php echo $seller['seller_item']; ?></td>
		</tr>
		<tr>
			<th scope="row">メーカー（法人）人</th>
			<td><?php echo $seller['company_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">代表者名</th>
			<td><?php echo $seller['company_owner']; ?></td>
		</tr>
		<tr>
			<th scope="row">事業者登録番号</th>
			<td><?php echo $seller['company_saupja_no']; ?></td>
		</tr>
		<tr>
			<th scope="row">業態</th>
			<td><?php echo $seller['company_item']; ?></td>
		</tr>
		<tr>
			<th scope="row">種目</th>
			<td><?php echo $seller['company_service']; ?></td>
		</tr>
		<tr>
			<th scope="row">電話番号</th>
			<td><?php echo $seller['company_tel']; ?></td>
		</tr>
		<tr>
			<th scope="row">ファクス番号</th>
			<td><?php echo $seller['company_fax']; ?></td>
		</tr>
		<tr>
			<th scope="row">事業場住所</th>
			<td><?php echo print_address($seller['company_addr1'], $seller['company_addr2'], $seller['company_addr3'], $seller['company_addr_jibeon']); ?></td>
		</tr>
		<?php if($seller['company_hompage']) { ?>
		<tr>
			<th scope="row">ホームページ</th>
			<td><?php echo $seller['company_hompage']; ?></td>
		</tr>
		<?php } ?>
		<?php if($seller['memo']) { ?>
		<tr>
			<th scope="row">伝達事項</th>
			<td><?php echo conv_content($seller['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">入金口座情報</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">銀行名</th>
			<td><?php echo $seller['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">口座番号</th>
			<td><?php echo $seller['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">預金主名</th>
			<td><?php echo $seller['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">担当者情報</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">担当者名</th>
			<td><?php echo $seller['info_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">担当者携帯電話</th>
			<td><?php echo $seller['info_tel']; ?></td>
		</tr>
		<tr>
			<th scope="row">担当者メール</th>
			<td><?php echo $seller['info_email']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_MURL; ?>" class="btn_medium wset">確認</a>
	</div>
</div>
