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
		<th colspan="11" align="left">订单列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
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
       <th>代理/激活码</th>
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
       
       
	</tr>
	<?php 
	if(!empty($rt['lists'])){ 
	foreach($rt['lists'] as $row){
	?>
	<tr>
     <td><?php echo empty($row['dailiname'])?"无":$row['dailiname'];?><?php echo "/".$row['InviteCode'];?></td>
	<td><?php echo $row['order_amount'];?></td>
    <td><?php echo round(($row['order_amount']*$row['feilv']/10000),2);?></td>
    <td><?php echo $row['order_amount']-round(($row['order_amount']*$row['feilv']/10000),2);?></td>
	<td><?php echo  !empty($row['pay_time'])?date('Y-m-d H:i:s',$row['pay_time']):'';?></td>
    <td><?php if($row['pay_status'] == 1){echo "已支付";}else{echo "未支付";}?><?php echo !empty($row['orderdesc'])?'/'.$row['orderdesc']:'';?></td>
    <td><?php echo $row['order_sn'];?></td>
    <td><?php echo $row['draworder_instead']['amount']; ?></td>
    <td><?php echo !empty($row['draworder_instead']['addtime']) ? date('Y-m-d H:i:s', $row['draworder_instead']['addtime']) : ''; ?></td>
    <td><?php echo $row['draworder_instead']['order_sn']; ?></td>
    <td><?php echo $row['draworder_instead']['state']?"已结账":"未结账"; ?></td>
    
    
   
  
                   
	</tr>
	<?php
	 } }?>
	
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