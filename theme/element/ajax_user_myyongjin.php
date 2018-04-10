<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
     <tr>

    <td   bgcolor="#f9f9f9"><a href="<?php echo SITE_URL.'user.php?act=mymoneydata&status=tongguo';?>"><i></i>审核通过的<span><?php echo !empty($rt['pay5']) ? $rt['pay5'] : '0.00';?>元</span></a></td>

  </tr>
    <tr>

    <td   bgcolor="#f9f9f9"><a href="<?php echo SITE_URL.'user.php?act=mymoneydata&status=weifu';?>"><i></i>未付款订单<span><?php echo !empty($rt['pay1']) ? $rt['pay1'] : '0.00';?>元</span></a></td>

  </tr>
   <tr>

   	  <td   bgcolor="#f9f9f9">	<a href="<?php echo SITE_URL.'user.php?act=mymoneydata&status=yifu';?>"><i></i>已付款订单<span><?php echo !empty($rt['pay2']) ? $rt['pay2'] : '0.00';?>元</span></a></td>

  </tr>
   <tr>

    <td   bgcolor="#f9f9f9"><a href="<?php echo SITE_URL.'user.php?act=mymoneydata&status=shouhuo';?>"><i></i>已收货订单<span><?php echo !empty($rt['pay3']) ? $rt['pay3'] : '0.00';?>元</span></a></td>

  </tr>
   <tr>

    <td   bgcolor="#f9f9f9"><a href="<?php echo SITE_URL.'user.php?act=mymoneydata&status=quxiao';?>"><i></i>已取消作废<span><?php echo !empty($rt['pay4']) ? $rt['pay4'] : '0.00';?>元</span></a></td>

  </tr>
  
</table>

