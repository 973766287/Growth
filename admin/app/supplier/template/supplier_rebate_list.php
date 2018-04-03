<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>

<!-- 供货商搜索 -->
<div class="form-div">
<input type="hidden" name="is_pay_ok" value="<? echo $_GET['is_pay_ok'];?>"/>
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    时间段：
	<input name="rebate_paytime_start" type="text" id="rebate_paytime_start" size="15"><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('rebate_paytime_start', 'y-mm-dd');" value="选择时间" class="button"/> - <input name="rebate_paytime_end" type="text" id="rebate_paytime_end" size="15"><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('rebate_paytime_end', 'y-mm-dd');" value="选择时间" class="button"/>
  
	<? if ($_GET['is_pay_ok'] == 0){?>
	<select name='status'>
	<option value='-1' selected>佣金状态选择</option>
    <? foreach ($statusinfo as $key=>$value ){?>
	<option value='<? echo $key;?>'><? echo $value;?></option>
	<? }?>
	</select>
	<? }?>
    <input type="submit" value="搜索" class="rebate_search" />
    <!-- <a href="order.php?act=list&composite_status={$os_unconfirmed}">待确认</a>
    <a href="order.php?act=list&composite_status={$cs_await_pay}">待付款</a>
    <a href="order.php?act=list&composite_status={$cs_await_ship}">待发货</a> -->
 
</div>




<form method="post" action="" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
<div class="list-div" id="listDiv">


  <table cellpadding="3" cellspacing="1">
    <tr>
	  <th>编号</th>
      <th>入驻商</th>
      <th>时间段</th>
      <th>总营业额</th>
	  <th>佣金</th>
	  <th>应结金额</th>
	  <th>实结金额</th>
	  <th>返佣状态</th>
	  <th>返佣日期</th>
	   <th>操作员</th>
      <th>操作</th>
    </tr>
    <? if($supplier_list){ foreach ($supplier_list as $supplier){?>
    <tr>
	  <td><? echo $supplier['sign'];?></td>
      <td class="first-cell" style="padding-left:10px;" ><? echo $supplier['supplier_name'];?></td>
      <td ><? echo $supplier['rebate_paytime_start'];?>---<? echo $supplier['rebate_paytime_end'];?> </td>
      <td><? echo $supplier['all_money_formated'];?></td>
	  <td align="center"><? echo $supplier['rebate_money_formated'];?></td>
	  <td align="center"><? echo $supplier['pay_money_formated'];?></td>
	  <td align="center"><? echo $supplier['payable_price'];?></td>
	  <td align="center"><? echo $supplier['status_name'];?></td>
	  <td align="center"><? echo $supplier['pay_time'];?></td>
	  <td align="center"><? echo $supplier['user'];?></td>
      <td align="center">
	  <? foreach ($supplier['caozuo'] as $do){?>
	  <a href="<? echo $do['url'];?>"><? echo $do['name'];?></a><br>
	  <? }?>
        <!-- <a href="supplier_rebate.php?act=view&is_pay_ok={$smarty.get.is_pay_ok}&id={$supplier.rebate_id}" title="计算此时间段内金额给商家">处理</a><br><a href="supplier_order.php?act=list&rebateid={$supplier.rebate_id}" title="查看相关商家此时间段内订单">{$lang.view}</a> -->
	  </td>
    </tr>
  <? }}else{?>
    <tr><td class="no-records" colspan="15">没有找到任何记录</td></tr>
   <? }?>
  </table>

 <?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
</form>




<?php  $thisurl = ADMIN_URL.'supplier.php'; ?>
<script>

	function del_supplier(suppid){
		var url = "supplier.php?type=delete&id="+suppid;
		if(confirm('删除后，相关商品，佣金及其它店铺信息将永久删除，确定删除？')){
			self.location.href = url;
		}
	}

</script>

<script>

	$('.rebate_search').click(function(){
		
		is_pay_ok =$('input[name="is_pay_ok"]').val();
			if(is_pay_ok == 0){
		status = $('select[name="status"]').val();
			}
        rebate_paytime_start = $('input[name="rebate_paytime_start"]').val();
		rebate_paytime_end = $('input[name="rebate_paytime_end"]').val();
		
		
		
		if(is_pay_ok == 0){
		location.href='<?php echo $thisurl;?>?type=supplier_rebate_list&status='+status+'&rebate_paytime_start='+rebate_paytime_start+'&rebate_paytime_end='+rebate_paytime_end+'&is_pay_ok='+is_pay_ok;
		}else{
			location.href='<?php echo $thisurl;?>?type=supplier_rebate_list&rebate_paytime_start='+rebate_paytime_start+'&rebate_paytime_end='+rebate_paytime_end+'&is_pay_ok='+is_pay_ok;
			}
	});

</script>