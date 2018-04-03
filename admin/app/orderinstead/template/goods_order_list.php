<?php
$thisurl = ADMIN_URL.'Instead.php'; 
?>
  <style type="text/css">
    table th {white-space:nowrap;}
    table td {white-space:nowrap;}
    </style>
<div class="contentbox" style="overflow:auto;width:100%;" >
     <table cellspacing="2" cellpadding="5" style="empty-cells:show;margin:0 auto; width:100%;">
	 <tr>
		<th colspan="19" align="left">计划列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		选择时间：<input type="text" id="EntTime1" name="EntTime1" value="<?php if(isset($_GET['add_time1'])){ echo $_GET['add_time1'];}?>" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" value="<?php if(isset($_GET['add_time2'])){ echo $_GET['add_time2'];}?>" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
        
       <!-- <input type="hidden"  name="pages" id="pages" value="<?php if(isset($_GET['page'])){ echo $_GET['page'];}?>">-->
        
         <input value="查询流水" class="liushui_search" type="button" >
         <span id="liushui"></span>
        
        <span style="float:right;"><a href="javascript:history.go(-1);"><input value=" 返回 "  type="button"></a></span>
        
		</th>
   
		
	</tr>
	<tr><th colspan="19" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		商户编号/姓名/手机号<input name="user_info"  size="15" type="text" value="<?php echo isset($_GET['user_info']) ? $_GET['user_info'] : "";?>">
        &nbsp;计划号<input type="text" name="plan_no" size="30" value="<?php echo isset($_GET['plan_no']) ? $_GET['plan_no'] : "";?>"/>
        &nbsp;结账状态
        <select name="jz_status" id="jz_status" class="pwt">
					<option value="0">请选择</option>
                    <option <?php if(isset($_GET['jz_status']) && $_GET['jz_status'] == 3){?>selected="selected"<?php }?> value="3">已结账</option>
                    <option <?php if(isset($_GET['jz_status']) && $_GET['jz_status'] == 2){?>selected="selected"<?php }?> value="2">未结账</option>
                    
                    </select>
                    
                     &nbsp;交易状态
        <select name="pay_status" id="pay_status" class="pwt">
					<option value="0">请选择</option>
                    <option <?php if(isset($_GET['pay_status']) && $_GET['pay_status'] == 1){?>selected="selected"<?php }?> value="1">成功</option>
                    <option <?php if(isset($_GET['pay_status']) && $_GET['pay_status'] == -1){?>selected="selected"<?php }?> value="-1">失败</option>
                  
                    
                    </select>
                    
                     &nbsp;代付状态
        <select name="df_status" id="df_status" class="pwt">
					<option value="0">请选择</option>
                    <option <?php if(isset($_GET['df_status']) && $_GET['df_status'] == 1){?>selected="selected"<?php }?> value="1">已代付</option>
                    <option <?php if(isset($_GET['df_status']) && $_GET['df_status'] == -1){?>selected="selected"<?php }?> value="-1">未代付</option>
                    
                    </select>
                    
		<input value=" 搜索 " class="order_search" type="button">
        
	
	</th></tr>
    <tr><th colspan="19" align="left">
    <input value=" 终止所有用户计划 " class="stop_plans" type="button">
    <input name="button" id="stopplan" value="终止选择计划" class="bathop" disabled="true"  type="button">
	</th></tr>
    
    <tr>
	   <th><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
       <th>计划号</th>
	   <th>姓名</th>
	   <th>手机号</th>
	   <th>银行名称</th>
       <th>卡号</th>
	   <th>交易金额</th>
	   <th>手续费</th>
       <th>结算金额</th>
       <th>扣款时间</th>
       <th>交易状态</th>
       <th>支付订单号</th>
       
       <th>代付金额</th>
       <th>代付时间</th>
       <th>代付订单号</th>
       
       <th>结账状态</th>
       <th>代付状态</th>
       <th>代付系统返回信息</th>
       
       
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt['lists'])){ 
	foreach($rt['lists'] as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/></td>
    <td><a href="Instead.php?type=planslist&plan_id=<?php echo $row['id'];?>" title="信用卡计划"><?php echo $row['plan_no'];?></a></td>
	<td><?php echo $row['uname'];?></td>
	<td><?php echo $row['mobile'];?></td>
	<td><?php echo $row['bankname'];?></td>
    <td><?php echo $row['bank_no'];?></td>
	<td><?php echo $row['kou_money'];?></td>
    <td><?php echo $row['Instead_sxf'];?></td>
    <td><?php echo $row['huan_money'];?></td>
	<td><?php echo  !empty($row['order_instead']['pay_time'])?date('Y-m-d H:i:s',$row['order_instead']['pay_time']):'';?></td>
    <td><?php if($row['order_instead']['pay_status'] == 1){echo "已支付";}else{echo "未支付";}?><?php echo !empty($row['order_instead']['orderdesc'])?'/'.$row['order_instead']['orderdesc']:'';?></td>
    <td><?php echo $row['order_instead']['order_sn'];?></td>
    <td><?php echo $row['draworder_instead']['amount']; ?></td>
    <td><?php echo !empty($row['draworder_instead']['paytime']) ? date('Y-m-d H:i:s', $row['draworder_instead']['paytime']) : ''; ?></td>
    <td><?php echo $row['draworder_instead']['order_sn']; ?></td>
    <td><a href="<?php echo ADMIN_URL.'Instead.php?type=change_state&state='.$row['draworder_instead']['state'].'&id='.$row['draworder_instead']['id'];?>"><?php echo $row['draworder_instead']['state']?"已结账":"未结账"; ?></a><br><?php  echo !empty($row['draworder_instead']['upstate_time']) ? '时间:'.date('Y-m-d H:i:s', $row['draworder_instead']['upstate_time']) : ''; ?></td>
    
     <td><?php  if($row['draworder_instead']['INFO_RET_CODE'] === "0000" ){echo "代付已发送";}else{echo "代付未发送"; }?></td>
    <td><?php echo $row['draworder_instead']['RET_DETAILS_ERR_MSG'] ;?></td>
    
   
    <td >
                    <?php if($row['draworder_instead']['id'] > 0){?>
                        <?php if($row['draworder_instead']['id'] > 116){?>
                       <a href="javascript:void(0);" onclick="ajax_hljc_pay_search(<? echo $row['draworder_instead']['id'];?>)">查询</a>
                        
                       <?php if($row['draworder_instead']['state'] != 1){?>
                         <a href="javascript:void(0);" onclick="ajax_hljc_pay_daifu_old(<? echo $row['draworder_instead']['id'];?>)">提现</a>
                         
                       <?php }}else{?>
                        <a href="javascript:void(0);" onclick="ajax_pay_search(<? echo $row['draworder_instead']['id'];?>)">查询</a>
                         
                       <?php if($row['draworder_instead']['state'] != 1){?>
                         <a href="javascript:void(0);" onclick="ajax_hljc_pay_daifu_old(<? echo $row['draworder_instead']['id'];?>)">提现</a>
                         
                       <?php }}?>
                      
                      <?php }?>
                      
                    </td>
                   
	</tr>
	<?php
	 } }?>
	
	 </table>
	 <?php $this->element('page',array('pagelink'=>$rt['pages']));?>
</div>
<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("stopplan").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("stopplan").disabled = true;
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
	
		document.getElementById("stopplan").disabled = !checked;
	
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
			var str=arr.join('+');
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
 
//   $('.delorder').click(function(){
//   		ids = $(this).attr('id');
//		thisobj = $(this).parent().parent();
//		if(confirm("确定删除吗？")){
//			createwindow();
//			$.post('<?php echo $thisurl;?>',{action:'bathop',type:'bathdel',ids:ids},function(data){
//				removewindow();
//				if(data == ""){
//					thisobj.hide(300);
//				}else{
//					alert(data);	
//				}
//			});
//		}else{
//			return false;	
//		}
//   });
//   
   /*	$('.activeop').live('click',function(){
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
	});*/
	
	//sous
	$('.order_search').click(function(){
		
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		user_info = $('input[name="user_info"]').val();
		
		plan_no = $('input[name="plan_no"]').val();
		
		
		jz_status = $('select[name="jz_status"]').val();
		pay_status = $('select[name="pay_status"]').val();
		df_status = $('select[name="df_status"]').val();
	
		
		
		location.href='<?php echo ADMIN_URL;?>Instead.php?add_time1='+time1+'&add_time2='+time2+'&user_info='+user_info+'&plan_no='+plan_no+'&df_status='+df_status+'&pay_status='+pay_status+'&jz_status='+jz_status;
	});
	
	
		$('.stop_plans').click(function(){
	
	if(confirm("确定终止所有用户计划吗？")){
		$.post('<?php echo $thisurl;?>',{action:'stop_plans_all'},function(data){
			
				alert(data);
		
		});
		}else{
			return false;	
			
			}
	});
	
	
	 function ajax_pay_search(id) {
		 
		
        $.post('<?php echo $thisurl; ?>', {action: 'yinlianapi_query', id: id}, function (data) {
               
                                  alert(data);

            });
			
			
			
	 }
	 
	 
	  function ajax_pay_daifu_old(id) {
		 
		
		
       $.post('<?php echo $thisurl; ?>', {action: 'yinlianapi_pay_daifu', id: id}, function (data) {
               
                                  alert(data);
								  
								
								  
								location.href='<?php echo ADMIN_URL;?>Instead.php?type=order_info&id=<?php echo $drawmoneyinfo['plan_id'];?>';

            });
			
			
	 }
	 
	 function ajax_hljc_pay_daifu_old(id){
		 
		  time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		user_info = $('input[name="user_info"]').val();
		
		plan_no = $('input[name="plan_no"]').val();
		
		pages = $('input[name="pages"]').val();
		
		jz_status = $('select[name="jz_status"]').val();
		pay_status = $('select[name="pay_status"]').val();
		df_status = $('select[name="df_status"]').val();
		
		  $.post('<?php echo $thisurl; ?>', {action: 'hljc_pay_daifu', id: id}, function (data) {
               
                                  alert(data);
								  window.location.reload();
								  /*location.href='<?php echo ADMIN_URL;?>Instead.php?add_time1='+time1+'&add_time2='+time2+'&user_info='+user_info+'&plan_no='+plan_no+'&df_status='+df_status+'&pay_status='+pay_status+'&jz_status='+jz_status='&page='+pages;*/
								  
								/*location.href='<?php echo ADMIN_URL;?>Instead.php?type=order_info&id=<?php echo $drawmoneyinfo['plan_id'];?>';*/

            });
		 
		 }
	 
	 function ajax_hljc_pay_search(id){
		   $.post('<?php echo $thisurl; ?>', {action: 'hljc_query', id: id}, function (data) {
               
                                  alert(data);
								  
							
            });
		 
		 }
	 
	 
	 $('.liushui_search').click(function(){
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
	
	
	
		
		$.post('<?php echo $thisurl;?>',{action:'liushui_search',add_time1:time1,add_time2:time2},function(data){
			
				document.getElementById("liushui").innerHTML='流水统计金额：'+data;
		});
		
		
		});
	
</script>