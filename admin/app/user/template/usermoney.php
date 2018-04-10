
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>

<?php
if($_POST['sss']!="sss"){

?>

<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="7" align="left" style="text-align:left">资金统计</th>
	</tr>
    
    <tr><td colspan="8" align="left">
                <img src="<?php echo $this->img('icon_search.gif'); ?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
                原因 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ""; ?>">
              <!--  <select name="keyword2" id="keyword2">
                    <option value="">审核状态</option>
                    <option value="1">审核通过</option>
                    <option value="0">审核未通过</option>
                    <option value="2">未认证</option>
                </select>-->
            
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
                <input value=" 搜索 " class="cate_search" type="button">
                
                 <label style="display: inline-block">
                    <form  method="post" name="dcs" action="user.php?type=usermoney"    > <input type="hidden" name="chaxun" value="<?php echo $chaxun ?>">


                        <input type="hidden" name="sss" value="sss"/>



                        <input type="submit" value="确认导出" style="cursor:pointer; padding:3px;" onclick="document.dcs.submit();">
                    </form>
                </label>
            </td></tr>
            
            
	<tr>
	  
	   <th>订单编号</th>
	   <th>昵称[购买者]</th>
	   <th>收入</th>
	   <th>原因</th>
	   <th>时间</th>
	</tr>
	<?php if(!empty($rt))foreach($rt as $row){?>
	<tr>
	
	<td><?php echo $row['order_sn'];?></td>
	<td><?php echo $row['nickname'];?><font color="#0000FF">[<?php echo $row['toname'];?>]</font></td>
	<td><?php echo $row['money'] > 0 ? '收入:<font color=blue>￥'.$row['money'].'</font>' : '支出:<font color=red>￥'.(-$row['money']).'</font>';?></td>
	<td><?php echo $row['changedesc'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$row['time']);?></td>
	
	</tr>
	<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">

function ajax_import_order_data(obj){
    ps = $('input[name="pagestart"]').val();
    pe = $('input[name="pageend"]').val();

    supplier = $('input[name="supplier"]').val();

    window.open('<?php echo ADMIN_URL;?>user.php?type=ajax_import_usermoney_data&pagestart='+ps+'&pageend='+pe+'&supplier='+supplier);
}


	
		//sous
	$('.cate_search').click(function(){
		
		
		
		keys = $('input[name="keyword"]').val();
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		location.href='<?php echo $thisurl;?>?type=usermoney&keyword='+keys+'&add_time1='+time1+'&add_time2='+time2;
	});
</script>

<? } else{


    header("Content-Type:text/html;charset=utf-8");
    header("Content-type:application/vnd.ms-excel");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Content-Disposition:filename=账户变动明细(page:".($pagestart+1)."-page:".$pageend.").xls");


    ?>
	
    <table cellspacing="2" cellpadding="5" width="100%">
	
    
            
            
	<tr>
	  
	   <th>订单编号</th>
	   <th>昵称[购买者]</th>
       <th>收入/支出</th>
	   <th>金额</th>
	   <th>原因</th>
	   <th>时间</th>
	</tr>
	<?php if(!empty($rt))foreach($rt as $row){?>
	<tr>
	
	<td><?php echo $row['order_sn'];?></td>
	<td><?php echo $row['nickname'];?><font color="#0000FF">[<?php echo $row['toname'];?>]</font></td>
    <td><?php echo $row['money'] > 0 ? '收入':'支出';?></td>
	<td><?php echo $row['money'] > 0 ? '<font color=blue>'.$row['money'].'</font>' : '<font color=red>'.(-$row['money']).'</font>';?></td>
	<td><?php echo $row['changedesc'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$row['time']);?></td>
	
	</tr>
	<?php } ?>
	 </table>
     
     <? }?>