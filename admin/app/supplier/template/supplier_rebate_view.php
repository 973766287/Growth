<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>
<div style="margin-bottom:20px;">佣金详细信息:</div>
<div class="list-div" style="margin-bottom: 5px">

<form action="" method="post" name="theForm" enctype="multipart/form-data">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th>入驻商名称：</th>
	<td><? echo $supplier['user_name'];?></td>
	<th>结算单编号：</th>
	<td><? echo $rebate['sign'];?></td>
  </tr>
  <tr>
	<th>店铺名称：</th>
	<td><? echo $supplier['supplier_name'];?></td>
    <th>结算期间：</th>
	<td><? echo $rebate['rebate_paytime_start'];?>~<? echo $rebate['rebate_paytime_end'];?></td>
  </tr>
  <? if ($money_info){?>
  <tr>
  <td colspan='4'>
	  <table width='100%' cellpadding="3" cellspacing="1">
	  <tr>
	  <td colspan='8'>
	  结算信息
	  </td>
	  </tr>
	  <? foreach ($money_info as $key => $money){?>
	  <tr>
      <? if ($key == 'online'){?>
	  <th>线上货款：</th><td><? echo $money['allmoney'];?></td><th>佣金比例：</th><td><? echo $money['supplier_rebate'];?>%</td><th>店铺佣金：</th><td><? echo $money['rebatemoney'];?></td><th>分成佣金：</th><td><? echo $money['fcyj'];?></td>
      <? }?>
	  </tr>
	 <? }?>
	  </table>
   </td>
  </tr>
  <? }?>
  <tr>
    <td colspan='4'>
	<table width='100%' cellpadding="3" cellspacing="1">
	  <tr>
	  <td colspan='4'>
	  佣金统计
	  </td>
	  </tr>
	  <tr>
		<th>实收货款：</th>
		<td><? echo $allmoney['allmoney'];?></td>
		<th>授权调整货款：</th>
		<td><input type='text' name='rebate_all' id='rebate_all' value='<? echo $allmoney['rebate_all'];?>'></td>
	  </tr>
	  <tr>
		<th>-佣金：</th>
		<td><? echo $allmoney['allrebate'];?></td>
		<th>-授权调整佣金：</th>
		<td><input type='text' name='rebate_money' id='rebate_money' value='<? echo $allmoney['rebate_money'];?>'><input type='button' value='结算' onclick='jian();'></td>
	  </tr>
	  <tr>
     <th>=结算金额：</th>
     <td><? echo $allmoney['chamoney'];?></td><th></th><td id='payable_price'><? echo $allmoney['payable_price'];?></td>
	  </tr>
	</table>
	</td>
  </tr>
  <tr>
    <td colspan='4'>
	<table width='100%' cellpadding="3" cellspacing="1">
	  <tr>
	  <td colspan='4'>
	  按如下信息给商家付款
	  </td>
	  </tr>
	  <tr>
		<th>公司名称：</th>
		<td><? echo $supplier['company_name'];?></td>
		<th>地址：</th>
		<td><? echo $supplier['province'];?><? echo $supplier['city'];?><? echo $supplier['district'];?><? echo $supplier['address'];?></td>
	  </tr>
	  <tr>
		<th>电话：</th>
		<td><? echo $supplier['tel'];?></td>
		<th>开户行：</th>
		<td><? echo $supplier['settlement_bank_name'];?></td>
	  </tr>
	  <tr>
		<th colspan='2'>帐号：</th><td colspan='2'><? echo $supplier['settlement_bank_account_number'];?></td>
	  </tr>
	</table>
	</td>
  </tr>
  <tr>
    <td colspan='4'>
	<? if ($rebate['status'] == 4){?>
	<table width='100%' cellpadding="3" cellspacing="1">
	  <tr>
	  <td colspan='4'>
	  汇款凭证
	  </td>
	  </tr>
	  <tr>
	  <td colspan='2'><img src="../<? echo $rebate['rebate_img'];?>"></td>
	  </tr>
	  <tr>
		<th>操作备注：</th>
		<td><textarea name='remark'>如结算单发生其他意外变动情况，您可在此输入变动备注信息</textarea></td>
	  </tr>
	  <tr>
		<th>当前可操作项：</th>
		<td>
		<input type='hidden' name='id' value='<? echo $rebate['rebate_id'];?>'>
		
		<? foreach ($rebate['caozuo'] as $do){?>
		<? if ($do['type'] == 'submit'){?>
		<? echo $do['text'];?>
		<input type='hidden' name='act' value='<? echo $do['act'];?>'>
		<input type="<? echo $do['type'];?>" value="<? echo $do['name'];?>">
		<? }}?>
	
		</td>
	  </tr>
	</table>
	<? }else{?>
	<table width='100%' cellpadding="3" cellspacing="1">
	  <tr>
	  <td colspan='4'>
	  平台方审核
	  </td>
	  </tr>
	  <? if ($rebate['status'] == 3){?>
	  <tr>
	  <th>上传汇票凭证：</th>
	  <td>
      
	   <input type="hidden" name="rebate_img" id="rebate_img"  size="35" />
       <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rebate['rebate_img'])&&!empty($rebate['rebate_img'])? 'show' : '';?>&ty=rebate_img&files=<?php echo isset($rebate['rebate_img']) ? $rebate['rebate_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
	  </td>
	  </tr>
	 <? }?>
	  <tr>
		<th>操作备注：</th>
		<td><textarea name='remark'></textarea></td>
	  </tr>
	  <tr>
		<th>当前可操作项：</th>
		<td>
		<input type='hidden' name='id' value='<? echo $rebate['rebate_id'];?>'>
		
		<? foreach ($rebate['caozuo'] as $do){?>
		<? if ($do['type'] == 'submit'){?>
		<? echo $do['text'];?>
		<input type='hidden' name='act' value='<? echo $do['act'];?>'>
		<input type="<? echo $do['type'];?>" value="<? echo $do['name'];?>">
		<? }}?>

		</td>
	  </tr>
	</table>
	<? }?>
	</td>
  </tr>
<? if (!empty($logs)){?>
  <tr>
    <td colspan='4'>
	<table width='100%' cellpadding="3" cellspacing="1">
	  <tr>
		<th>操作者</th>
		<th>操作时间</th>
		<th>操作事件</th>
		<th>备注</th>
	  </tr>
	  <? foreach ($logs as $key => $log){?>
	  <tr>
		<td><? echo $log['username'];?></td>
		<td><? echo $log['addtime_dec'];?></td>
		<td><? echo $log['typedec'];?></td>
		<td><? echo $log['contents'];?></td>
	  </tr>
	 <? }?>
	</table>
	</td>
  </tr>
<? }?>
</table>
</form>
</div>








<script type="text/javascript" language="javascript">

function jian(){
	var all = parseFloat(document.getElementById('rebate_all').value);
	var rebate = parseFloat(document.getElementById('rebate_money').value);
	if(isNaN(all) || isNaN(rebate)){
		alert('输入金额不正确!');
		return false;
	}
	document.getElementById('payable_price').innerHTML = (all*100 - rebate*100)/100;
}

</script>

