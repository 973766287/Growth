<?php
$thisurl = ADMIN_URL.'Instead.php'; 
?>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="13" align="left">计划列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
        
        
        <span style="float:right;"><a href="javascript:history.go(-1);"><input value=" 返回 "  type="button"></a></span>
        
		</th>
   
		
	</tr>
	<tr><th colspan="13" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		商户编号/姓名/手机号<input name="user_info"  size="15" type="text" value="<?php echo isset($_GET['user_info']) ? $_GET['user_info'] : "";?>">
		<input value=" 搜索 " class="order_search" type="button">
	
	</th></tr>
    <tr><th colspan="13" align="left">
    <input value=" 终止所有用户计划 " class="stop_plans" type="button">
    <input name="button" id="stopplan" value="终止选择计划" class="bathop" disabled="true"  type="button">
	</th></tr>
    
    <tr>
	   <th width="80"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
      <th>计划号</th>
	   <th>商户姓名</th>
	   <th>商户手机号</th>
	   <th>信用卡名称</th>
       <th>信用卡卡号</th>
	   <th>扣款金额</th>
	   <th>扣款时间</th>
	   <th>还款金额</th>
	   <th>还款时间</th>
	   <th>手续费</th>
       <th>计划状态</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt['lists'])){ foreach($rt['lists'] as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/></td>
    <td><a href="Instead.php?type=planslist&plan_id=<?php echo $row['id'];?>" title="信用卡计划"><?php echo $row['plan_no'];?></a></td>
	<td><?php echo $row['uname'];?></td>
	<td><?php echo $row['mobile'];?></td>
	<td><?php echo $row['bankname'];?></td>
    <td><?php echo $row['bank_no'];?></td>
	<td><?php echo $row['kou_money'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$row['kou_time']);?></td>
	<td><?php echo $row['huan_money'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$row['huan_time']);?></td>
    <td><?php echo $row['Instead_sxf'];?></td>
	<td><?php if($row['stop'] == 1 && $row['status'] == 1 && $row['is_perform_auto'] == 0){echo "计划已终止";}else{ if($row['status'] == 1){echo "待还";}else if($row['status'] == 2){echo "已扣款";}else{echo "已代付";}}?></td>
	<td>
    <?php if($row['is_perform_auto'] > 0){?>
	<a href="Instead.php?type=order_info&id=<?php echo $row['id'];?>" title="详情">详情</a>&nbsp;
    <?php }?>
  
	</td>
	</tr>
	<?php
	 } ?>
	
		<?php } ?>
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
		
	
		
		
		location.href='<?php echo ADMIN_URL;?>Instead.php?add_time1='+time1+'&add_time2='+time2+'&user_info='+user_info;
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
	
	
</script>