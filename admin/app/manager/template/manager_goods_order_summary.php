<?php
$thisurl = ADMIN_URL.'Instead.php'; 
?>
  <style type="text/css">
    table th {white-space:nowrap;}
    table td {white-space:nowrap;}
    </style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%" >
	 <tr>
		<th colspan="11" align="left">计划列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        代理商：
        <select name="dl_id">
        <option>请选择</option>
        <?php if($daili_list){foreach($daili_list as $dl){?>
        <option <?php if($_GET['dl_id'] == $dl['adminid']){?>selected="selected"<?php }?> value="<?php echo $dl['adminid'];?>"><?php echo $dl['adminname'];?></option>
        <?php }}?>
        </select>
        
        
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
        
        
        <span style="float:right;"><a href="javascript:history.go(-1);"><input value=" 返回 "  type="button"></a></span>
       <input value=" 查询 " class="order_summary" type="button">
        <input value=" 查询明细 " class="order_search" type="button">
		</th>
   
		
	</tr>
	
  
    
    <tr>
       <th>代理</th>
	   <th>交易总笔数</th>
	   <th>交易日期</th>
       <th>交易总金额</th>
       <th>分润总金额</th>
     

       
       
	</tr>
	<?php 
	if(!empty($lists)){ 
	?>
	<tr>
     <td><?php echo $lists['adminname'];?></td>
	<td><?php echo $lists['bishu'];?></td>
    <td><?php echo $_GET['add_time1'];?>/<?php echo $_GET['add_time2'];?></td>
    <td><?php echo $lists['zong_order_amount'];?></td>
	<td><?php echo $lists['zong_fenrun'];?></td>
    
   
  
                   
	</tr>
	<?php
	  }?>
	
	 </table>
	 <?php $this->element('page',array('pagelink'=>$rt['pages']));?>
</div>
<script type="text/javascript">

	
	//sous
	$('.order_search').click(function(){
		
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		dl_id = $('select[name="dl_id"]').val();	
		
	
		
		
		location.href='<?php echo ADMIN_URL;?>manager.php?type=insteadorder&add_time1='+time1+'&add_time2='+time2+'&dl_id='+dl_id;
	});
	
	
	$('.order_summary').click(function(){
		
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		dl_id = $('select[name="dl_id"]').val();	
	
		
		
		location.href='<?php echo ADMIN_URL;?>manager.php?type=insteadorder_summary&add_time1='+time1+'&add_time2='+time2+'&dl_id='+dl_id;
	});
	
		
</script>