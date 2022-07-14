<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="fpartner_result">
	<h3 class="anc_tit">收款账户</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">银行名称</th>
			<td><?php echo $partner['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">帐号</th>
			<td><?php echo $partner['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">存款主名</th>
			<td><?php echo $partner['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">结算信息</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tr>
			<th scope="row">申请日期时间</th>
			<td><?php echo $partner['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">结算方法</th>
			<td><?php echo ($partner['pay_settle_case']=='1')?"无折存款":"信用卡"; ?></td>
		</tr>
		<tr>
			<th scope="row">结算金额</th>
			<td>
				<?php 
				if($partner['receipt_price'] > 0) 
					echo display_price($partner['receipt_price']);
				else
					echo /'免费';
				?>
			</td>
		</tr>
		<?php if($partner['pay_settle_case']=='1') { ?>		
		<tr>
			<th scope="row">存款账户</th>
			<td><?php echo $partner['bank_acc']; ?></td>
		</tr>
		<tr>
			<th scope="row">汇款人名</th>
			<td><?php echo $partner['deposit_name']; ?></td>
		</tr>
		<?php } ?>
		<?php if($partner['memo']) { ?>
		<tr>
			<th scope="row">传达事项</th>
			<td><?php echo conv_content($partner['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_MURL; ?>" class="btn_medium wset">确认</a>
	</div>
</div>
