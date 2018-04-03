<?php
$thisurl = ADMIN_URL.'goods_order.php'; 
if(isset($_GET['asc'])){
$oi = $thisurl.'?type=list&desc=order_id';
$os = $thisurl.'?type=list&desc=order_sn';
$tprice = $thisurl.'?type=list&desc=goods_amount';
$own = $thisurl.'?type=list&desc=consignee';
$dt = $thisurl.'?type=list&desc=add_time';
}else{
$oi = $thisurl.'?type=list&asc=order_id';
$os = $thisurl.'?type=list&asc=order_sn';
$tprice = $thisurl.'?type=list&asc=goods_amount';
$own = $thisurl.'?type=list&asc=consignee';
$dt = $thisurl.'?type=list&asc=add_time';
}
?>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
     
	 <tr>
	   <th colspan="8" align="left">订单列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <input type="hidden" name="supplier" value="<?php echo isset($_GET['supplier']) ? $_GET['supplier'] : "";?>" />
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
	
		</th>
		
	</tr>
	<tr><th colspan="8" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	       商户号<input name="user_id"  size="15" type="text" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : "";?>">
		订单号<input name="order_sn"  size="15" type="text" value="<?php echo isset($_GET['order_sn']) ? $_GET['order_sn'] : "";?>">
		
		法人姓名<input name="uname"  size="15" type="text" value="<?php echo isset($_GET['uname']) ? $_GET['uname'] : "";?>">
		手机号<input name="mobile"  size="15" type="text" value="<?php echo isset($_GET['mobile']) ? $_GET['mobile'] : "";?>">
		身份证号<input name="idcard"  size="15" type="text" value="<?php echo isset($_GET['idcard']) ? $_GET['idcard'] : "";?>">
	
		支付方式
		<select name="pay_id" id="pay_id" class="pwt">
					<option value="-1">请选择</option>
			<?php 
	if(!empty($payres)){ 
	foreach($payres as $row){
	?>
	<option value="<?php echo $row['pay_id'];?>"<?php echo  isset($_GET['pay_id'])&&$_GET['pay_id']==$row['pay_id'] ? ' selected="selected"' : '';?>><?php echo $row['pay_name'];?></option>			
						<?php } ?>
						
		<?php } ?>
				</select>
					
		订单状态 
		<?php 
		$status_option[-1] = '请选择';
		$status_option[11] = '未付款';
	
		$status_option[210] = '已付款';
		//$status_option[222] = '已发货';
	
		//$status_option[1] = '取消';
		//$status_option[4] = '无效';
		//$status_option[3] = '退货';
		//$status_option[2] = '退款';
		?>  
		 <select name="status" >
		 <!--2:确认订单 0:没支付 0:没发货-->
	        <?php 
			$se = 'selected="selected"';
			foreach($status_option as $k=>$var){
				echo '<option value="'.$k.'" '.($k==$_GET['status']&&isset($_GET['status']) ? $se : "").'>'.$var.'</option>';
			}
			?>
		  </select>
		<input value=" 搜索 " class="order_search" type="button">
	
	</th></tr>
    <tr>
	  
             <th> 序号 </th>
	   <th><a href="<?php echo $os;?>">订单号</a></th>
	   <th><a href="<?php echo $dt;?>">下单时间</a></th>
	   <th><a href="<?php echo $own;?>">[微信昵称]</a></th>
	   <th><a href="<?php echo $tprice;?>">总金额</a></th>
	   <th>订单状态</th>
	   <th>支付方式</th>
	</tr>
	<?php 
	if(!empty($orderlist)){ 
           $i=0;
	foreach($orderlist as $row){
            $i++;
	?>
	<tr>

        <td><?php echo $i;?></td>
	<td><?php echo $row['order_sn'];?></td>
	<td><?php echo $row['add_time'];?></td>
	<td><font color="#FF0000">[<?php echo $row['nickname'];?>]</font><?php echo $row['consignee'];?>[真实姓名:<?php echo $row['uname'];?>]</td>
	<td><?php echo $row['tprice'];?></td>
	<td><?php echo $row['status'];?></td>
    <td><?php echo $row['pay_name'];?></td>
	
	</tr>
	<?php
	 } ?>

		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function ajax_import_order_data(obj){
	ps = $('input[name="pagestart"]').val();
	pe = $('input[name="pageend"]').val();
	
	supplier = $('input[name="supplier"]').val();
	
	window.open('<?php echo ADMIN_URL;?>goods_order.php?type=ajax_import_order_data&pagestart='+ps+'&pageend='+pe+'&supplier='+supplier);
}

//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
		 document.getElementById("bathinvalid").disabled = false;
		 document.getElementById("bathcancel").disabled = false;
		 document.getElementById("bathconfirm").disabled = false;
		 document.getElementById("printorder").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
		 document.getElementById("bathinvalid").disabled = true;
		 document.getElementById("bathcancel").disabled = true;
		 document.getElementById("bathconfirm").disabled = true;
		 document.getElementById("printorder").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
		document.getElementById("bathconfirm").disabled = !checked;
		document.getElementById("bathcancel").disabled = !checked;
		document.getElementById("bathinvalid").disabled = !checked;
		document.getElementById("printorder").disabled = !checked;
  });
  
  //批量删除
   $('.bathop').click(function (){
   		if(confirm("确定操作吗？")){
			optype = $(this).attr('id');
			if(typeof(optype)=='undefined' || optype==""){ return false;}
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'bathop',type:optype,ids:str},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					alert(data);
					//location.reload();
				}
			});
		}else{
			return false;
		}
   });
 
   $('.delorder').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'bathop',type:'bathdel',ids:ids},function(data){
				removewindow();
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
   });
   
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		gid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,gid:gid,type:type},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo $this->img('yes.gif');?>';
				}else{
					id = 1;
					src = '<?php echo $this->img('no.gif');?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
	//sous
	$('.order_search').click(function(){
		
		supplier = $('input[name="supplier"]').val();
		
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		o_sn = $('input[name="order_sn"]').val();
		
		//own = $('input[name="consignee"]').val();
		
		sts = $('select[name="status"]').val();
		
		payids = $('select[name="pay_id"]').val();
		
		uid = $('input[name="user_id"]').val();
		
		uname = $('input[name="uname"]').val();
		mobile = $('input[name="mobile"]').val();
		idcard = $('input[name="idcard"]').val();
		
		location.href='<?php echo $thisurl;?>?type=list&add_time1='+time1+'&add_time2='+time2+'&user_id='+uid+'&order_sn='+o_sn+'&uname='+uname+'&mobile='+mobile+'&idcard='+idcard+'&pay_id='+payids+'&status='+sts+'&supplier='+supplier;
	});
	
	
	//打印订单
	function printorder(){
		if(confirm("你确定打印吗？点击按钮之后将会更改为已打印状态！")){
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('-');
			//window.location.href = '<?php echo ADMIN_URL;?>goods_order.php?type=orderprint&ids='+str;
			window.open('<?php echo ADMIN_URL;?>goods_order.php?type=orderprint&ids='+str);
		}
		return false;
	}
</script>