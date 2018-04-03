<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>



<div class="list-div" style="margin-bottom: 5px">
<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <th colspan="4">结算单编号：<? echo $rebate['sign'];?>&nbsp;&nbsp;&nbsp;&nbsp;佣金时间范围：<? echo $rebate['rebate_paytime_start'];?>----<? echo $rebate['rebate_paytime_end'];?></th>
  </tr>
  <? if ($money_info){?>
  <tr>
	  <table width='100%' cellpadding="3" cellspacing="1">
	  <? foreach ($money_info as $key => $money){?>
	  <tr>
      <? if ($key == 'online'){?>
	  <td>线上货款：</td><td><? echo $money['allmoney'];?></td><td>佣金比例：</td><td><? echo $money['supplier_rebate'];?>%</td><td>佣金：</td><td><? echo $money['rebatemoney'];?></td><th>分成佣金：</th><td><? echo $money['fcyj'];?></td>
      <? }?>
	  </tr>
	 <? }?>
	  </table>
  </tr>
 <? }?>
 
  <?php /*?><tr>
	  <table width="100%" cellpadding="3" cellspacing="1">
	  <tr>
	  <td><a href="supplier_order.php?act=view&rid=<? echo $rebate['rebate_id'];?>">妥投订单</a></td><td><a href="supplier_order.php?act=view&rid=<? echo $rebate['rebate_id'];?>&otype=2">退货订单</a></td>
	  </tr>
	  </table>
  </tr><?php */?>
  
  <? if ($order_type == 2){?>
  <tr>
		<table width="100%" cellpadding="3" cellspacing="1">
		<tr>
		<td>退货总货款：<? echo $back_money['all'];?>元（线上货款：<? echo $back_money['online'];?>元，货到付款：<? echo $back_money['onout'];?>元）， 已完成退货货款：<? echo $back_money['finish'];?>元， 申请中退货货款：<? echo $back_money['nofinish'];?>元</td>
		</tr>
		</table>
  </tr>
 <? }?>
  <tr>
    <form action="" name="searchForm">
    <input type="hidden" name="rid" id="rid" value="<? echo $rid;?>"/>
	<table width="100%" cellpadding="3" cellspacing="1">
	<tr><td>
		订单编号：<input type='text' name='order_sn' id='order_sn' value=''>
		<img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
		下单时间：
		<input name="add_time_start" type="text" id="add_time_start" size="15"><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('add_time_start', 'y-mm-dd');" value="选择时间" class="button"/> - <input name="add_time_end" type="text" id="add_time_end" size="15"><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('add_time_end', 'y-mm-dd');" value="选择时间" class="button"/>
		<input type="submit" value="搜索" class="button" />
	</td></tr>
	</table>
	</form>
  </tr>
  <tr>
  <div id='listDiv'>

	<? if ($order_type == 2){?>
		<form action="supplier_order.php" method="post" name="theForm" onsubmit="return check(<? echo $rebate['status'];?>)">
	<table width="100%" cellpadding="3" cellspacing="1">
	  <tr>
	  <td>订单编号</td>
	  <td>下单时间</td>
	  <td>申请退货时间</td>
	  <td>货款</td>
	  <td>订单状态</td>
	  <td>操作</td>
	  </tr>
	  <? foreach ($order_list as $order){?>
	  <tr>
	  <td><? echo $order['order_sn'];?></td>
	  <td><? echo $order['short_order_time'];?></td>
	  <td><? echo $order['short_back_add_time'];?></td>
	  <td><? echo $order['total_fee'];?></td>
	  <td><?php echo $order['status'];echo !empty($order['sn_id']) ? '&nbsp;<font color=blue>&nbsp;['.$order['shoppingname'].']物流单:<a style="color:#fe0000" href="http://m.kuaidi100.com/index_all.html?type='.$order['shipping_code'].'&postid='.$order['sn_id'].'" target="_blank">'.$order['sn_id'].'</a></font>' : '';?></td>
	  <td><a href="order.php?act=info&order_id=<? echo $order['order_id'];?>">查看原订单</a></td>
	  </tr>
	 <? }?>
	 
	</table>
</form>
	<? }else{?>
    
    
		<form action="supplier_order.php" method="post" name="theForm" onsubmit="return check(<? echo $rebate['status'];?>)">
	<table width="100%" cellpadding="3" cellspacing="1">
	  <tr>
	  <td><? if ($rebate['status'] == 0){?>
	  <input type="checkbox" class="quxuanall" value="checkbox" />
		  <? }?>订单编号
	  </td>
	  <td>下单时间</td>
	  <td>计费时间</td>
	  <td>货款</td>
	  <td>佣金</td>
	  <td>订单状态</td>
	  <td>操作</td>
	  </tr>
	  <? foreach ($order_list as $order){?>
	  <tr>
	  <td>
	  <? if ($order['is_rebeat'] && $rebate['status'] == 0){?>
		<input type="checkbox" name="quanxuan" value="<?php echo $order['order_sn'];?>" class="gids"/>
	 <? }?>
	  <? echo $order['order_sn'];?>
	  </td>
	  <td><? echo $order['short_order_time'];?></td>
	  <td>计费时间</td>
	  <td><? echo $order['formated_total_fee'];?></td>
	  <td><? echo $order['formated_rebate_fee'];?></td>
      
	  <td><?php echo $order['status'];echo !empty($order['sn_id']) ? '&nbsp;<font color=blue>&nbsp;['.$order['shoppingname'].']物流单:<a style="color:#fe0000" href="http://m.kuaidi100.com/index_all.html?type='.$order['shipping_code'].'&postid='.$order['sn_id'].'" target="_blank">'.$order['sn_id'].'</a></font>' : '';?></td>
      
	  <td><a href="goods_order.php?type=order_info&id=<? echo $order['order_id'];?>">查看订单</a></td>
	  </tr>
	 <? }?>
     
     
     
	  <tr>
	  <? if ($rebate['status'] == 0){?><!-- 冻结 -->
	    <td align="left" nowrap="true" colspan="7">
		<input type="hidden" name="act" value="operate1">
		<input type="hidden" name="rid" value="<? echo $rebate['rebate_id'];?>">
		<? if ($rebate['isdo']){?>
		<? foreach ($rebate['caozuo'] as $do){?>
		<input type="submit" value="<? echo $do['name'];?>">
		<? }}else{?>
		 <input name="button" id="bathconfirm" value="临时测试用的结算佣金" class="bathop" disabled="true"  type="button">
	<!--	<input type="submit" id="bathconfirm" value="临时测试用的结算佣金">-->
		<input type="button" value="距离结算还有<? echo $rebate['chadata'];?>天">
		<? }?>
		</td>
	  <? }elseif ($rebate['status'] == 1){?>
		<td align="left" nowrap="true" colspan="7">
		<input type="hidden" name="act" value="operate2">
		<input type="hidden" name="rid" value="<? echo $rebate['rebate_id'];?>">
		<input type="submit" value="撤销全部佣金">
		</td>
	 <? }else{?>
	    <td align="left" nowrap="true" colspan="7">
		</td>
	 <? }?>
	  </tr>
      
      
      
      
	</table>
<input name="order_id" type="hidden" value="" />
</form>
	<? }?>
    
     <?php $this->element('page', array('pagelink' => $pagelink)); ?>
  </div>
  </tr>
</table>
</div>


<? $thisurl = ADMIN_URL.'supplier_order.php'; ?>

<script language="javascript"> 

//function check(status)
//{
//	if(status <= 0){//冻结状态下结算佣金验证
//		var snArray = new Array();
//		var eles = document.forms['theForm'].elements;
//		for (var i=0; i<eles.length; i++)
//		{
//			if (eles[i].tagName == 'INPUT' && eles[i].type == 'checkbox' && eles[i].checked && eles[i].value != 'on')
//			{
//			  snArray.push(eles[i].value);
//			}
//		}
//		if (snArray.length == 0)
//		{
//			alert('请选择要结算的订单!');
//			return false;
//		}
//		else
//		{
//			eles['order_id'].value = snArray.toString();
//			return true;
//		}
//	}
//	else if(status == 1){//可结算状态下撤销全部佣金
//		if(confirm('撤销后，佣金状态由可结算将回归到冻结状态')){
//			return true;
//		}else{
//			return false;
//		}
//	}
//}


  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		//document.getElementById("bathdel").disabled = !checked;
		document.getElementById("bathconfirm").disabled = !checked;
	//	document.getElementById("bathcancel").disabled = !checked;
//		document.getElementById("bathinvalid").disabled = !checked;
//		document.getElementById("printorder").disabled = !checked;
  });

//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		// document.getElementById("bathdel").disabled = false;
//		 document.getElementById("bathinvalid").disabled = false;
//		 document.getElementById("bathcancel").disabled = false;
		 document.getElementById("bathconfirm").disabled = false;
//		 document.getElementById("printorder").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		// document.getElementById("bathdel").disabled = true;
//		 document.getElementById("bathinvalid").disabled = true;
//		 document.getElementById("bathcancel").disabled = true;
		 document.getElementById("bathconfirm").disabled = true;
//		 document.getElementById("printorder").disabled = true;
	  }
  });


 //批量删除
   $('.bathop').click(function (){
   		if(confirm("确定操作吗？操作后，佣金状态由冻结状态将变为可结算状态'")){
			rid = document.getElementById("rid").value;
			if(typeof(rid)=='undefined' || rid==""){ return false;}
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'jiesuan',rid:rid,ids:str},function(data){
				removewindow();
				if(data == ""){
					location.href='supplier.php?type=supplier_rebate_list&is_pay_ok=0';
				}else{
					alert(data);
					//location.reload();
				}
			});
		}else{
			return false;
		}
   });
   
   
</script>
