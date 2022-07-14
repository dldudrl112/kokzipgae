<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="fseller_result">
	<h3 class="anc_tit">公司信息</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">提供产品</th>
			<td><?php echo $seller['seller_item']; ?></td>
		</tr>
		<tr>
			<th scope="row">企业(法人)名</th>
			<td><?php echo $seller['company_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">代表人名</th>
			<td><?php echo $seller['company_owner']; ?></td>
		</tr>
		<tr>
			<th scope="row">营业执照号</th>
			<td><?php echo $seller['company_saupja_no']; ?></td>
		</tr>
		<tr>
			<th scope="row">业态</th>
			<td><?php echo $seller['company_item']; ?></td>
		</tr>
		<tr>
			<th scope="row">项目</th>
			<td><?php echo $seller['company_service']; ?></td>
		</tr>
		<tr>
			<th scope="row">电话号码</th>
			<td><?php echo $seller['company_tel']; ?></td>
		</tr>
		<tr>
			<th scope="row">传真号码</th>
			<td><?php echo $seller['company_fax']; ?></td>
		</tr>
		<tr>
			<th scope="row">营业场所地址</th>
			<td><?php echo print_address($seller['company_addr1'], $seller['company_addr2'], $seller['company_addr3'], $seller['company_addr_jibeon']); ?></td>
		</tr>
		<?php if($seller['company_hompage']) { ?>
		<tr>
			<th scope="row">网页</th>
			<td><?php echo $seller['company_hompage']; ?></td>
		</tr>
		<?php } ?>
		<?php if($seller['memo']) { ?>
		<tr>
			<th scope="row">传达事项</th>
			<td><?php echo conv_content($seller['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">汇款账户信息</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">银行名称</th>
			<td><?php echo $seller['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">账号</th>
			<td><?php echo $seller['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">存款主名</th>
			<td><?php echo $seller['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">负责人信息</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">负责人名</th>
			<td><?php echo $seller['info_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">负责人手机</th>
			<td><?php echo $seller['info_tel']; ?></td>
		</tr>
		<tr>
			<th scope="row">负责人电邮</th>
			<td><?php echo $seller['info_email']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_MURL; ?>" class="btn_medium wset">确认</a>
	</div>
</div>
