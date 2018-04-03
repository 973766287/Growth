<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="http://ws.weishuapay.com/admin/css/calendar.css" media="all">
<script type="text/javascript" src="http://ws.weishuapay.com/admin/js/calendar.js"></script>
<script type="text/javascript" src="http://ws.weishuapay.com/admin/js/calendar-setup.js"></script>
<script type="text/javascript" src="http://ws.weishuapay.com/admin/js/calendar-zh.js"></script>
<title>自动结算明细</title>
</head>

<body>
<header class="top_header">自动结算明细</header>
<table cellspacing="2" cellpadding="5" width="100%">
     
	 <tr>
	   <th colspan="10" align="left">
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
	
		</th>
		
	</tr>
	<tr><th colspan="10" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	       子商户号<input name="mobile"  size="15" type="text" value="">
	
		<input value=" 搜索 " class="order_search" type="button">
           
                </form>
           
	</th></tr>
    <tr>
	  
             <th> 序号 </th>
	   <th>提现金额</th>
       <th>提现状态</th>
	   <th>完成时间</th>
	   
	</tr>
	<?php 
	if(!empty($list)){ 
           $i=0;
		   $msg = array(
		   '0' => '初始',
		    '1' => '成功',
			 '2' => '失败',
			  '3' => '未知',
			   '4' => '待查询',
			    '5' => '银行已成功',
				 '6' => '等待批处理',
				  '7' => '提交银行处理',
				   '8' => '拆批重发',
				    '9' => '等待用户校验',
					
		   );
		   
		   if ($totalElements > 1) {
	foreach($list as $row){
            $i++;
	?>
	<tr>

        <td><?php echo $i;?></td>
	<td><?php echo $row['amount'];?></td>
	<td><?php echo $msg[$row['status']];?></td>
    <td><?php echo $row['finishTime'];?></td>
	
	</tr>
	<?php
	 } }else{
		 $i++;
		 ?>
<tr>

        <td><?php echo $i;?></td>
	<td><?php echo $list['amount'];?></td>
	<td><?php echo $msg[$list['status']];?></td>
    <td><?php echo $list['finishTime'];?></td>
	
	</tr>
		<?php }} ?>
	 </table>
     
     <script>
	 $('.order_search').click(function(){
		
		
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		mobile = $('input[name="mobile"]').val();

		
		location.href='<?php echo $thisurl;?>?act=daifu_detail&add_time1='+time1+'&add_time2='+time2+'&mobile='+mobile;
	});
	 </script>
</body>
</html>
