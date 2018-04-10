<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="10" align="left">会员等级<span style="float:right"><a href="user.php?type=levelinfo">添加会员等级</a></span></th>
	</tr>
    <tr>
	   <th>会员等级名称</th>
       <th>信用卡还款扣率/T+0</th>
       <th>API快捷（商旅）扣率/T+0</th>
        <th>银联支付(API)扣率/T+0</th>
         <th>银联支付(H5)扣率/T+0</th>
         <th>微信支付扣率/T+0</th>
          <th>支付宝支付扣率/T+0</th>
           <th>海外支付扣率/T+0</th>
            <th>京东支付扣率/T+0</th>
            
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	//if($row['lid']!='1') continue;
	$feilv = unserialize($row['feilv']);
	?>
	<tr>
	<td><?php echo $row['level_name'];?></td>
    <td><?php echo $feilv['yinlian_instead']/100;?>%+<?php echo $row['sxf_instead'];?></td>
    <td><?php echo $feilv['yinlian_api']/100;?>%+<?php echo $row['sxf_api'];?></td>
	<td><?php echo $feilv['yinlian']/100;?>%</td>
    <td><?php echo $feilv['yinlian_h5']/100;?>%</td>
    <td><?php echo $feilv['weixin']/100;?>%</td>
    <td><?php echo $feilv['zhifubao']/100;?>%</td>
    <td><?php echo $feilv['haiwai']/100;?>%</td>
    <td><?php echo $feilv['jingdong']/100;?>%</td>
	<td>	
	<a href="user.php?type=levelinfo&id=<?php echo $row['lid'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;<?php //if($row['lid']!='11' && $row['lid']!='12'){?>
<!--	<a href="user.php?type=levellist&op=del&id=<?php echo $row['lid'];?>" title="删除" onclick="if(confirm('确定删除吗？')){ return true;}else{ return false;};"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除"/></a>&nbsp;<?php //} ?>-->
	</td>
	</tr>
	<?php
	 }
	 }
	  ?>
	 </table>
</div>
