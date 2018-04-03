
<table width="100%" style="margin:0 auto" border="1" cellpadding="1" cellspacing="0" background="#ccc">
    <tr>
        <td colspan="5" align="center" bgcolor="#FFFFFF"><b style="font-size:16px;">金葵花提现申请表<?php echo !empty($rt['paytime']) ? date('Y-m-d H:i:s',$rt['paytime']) : '';?></b></td>
  </tr>
  <?php if($rt['user_rank']>0 and $rt['user_rank']!=1){?>
  <tr>
    <td width="16%" rowspan="3" align="center" bgcolor="#FFFFFF">提款人信息</td>
    <td width="24%"  align="center" bgcolor="#FFFFFF">姓名</td>
    <td width="16%"  align="center" bgcolor="#FFFFFF"><?php echo $rt['tuname'];?></td>
    <td width="14%" align="center" bgcolor="#FFFFFF">手机号</td>
    <td width="14%"  align="center" bgcolor="#FFFFFF"><?php echo $rt['upne'] ;?></td>
  </tr>
  <tr>
    <td  align="center" bgcolor="#FFFFFF">等级</td>
    <td  colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['level_name'];?></td>
  </tr>
  <tr>
    <td  align="center" bgcolor="#FFFFFF">身份证</td>
    <td  colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['cardcode'];?></td>
  </tr>
<!--  <tr>
    <td  align="center" bgcolor="#FFFFFF">手机号</td>
    <td  colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['upne'];?></td>
  </tr>-->
  <?php }else{
      ?>
    <tr>
    <td width="16%" rowspan="3" align="center" bgcolor="#FFFFFF">提款人信息</td>
    <td width="24%"  align="center" bgcolor="#FFFFFF">微信昵称</td>
    <td width="16%"  align="center" bgcolor="#FFFFFF"><?php echo $rt['nickname'];?></td>
    <td width="14%" align="center" bgcolor="#FFFFFF">手机号</td>
    <td width="14%"  align="center" bgcolor="#FFFFFF"><?php echo $rt['mobile_phone'] ;?></td>
  </tr>
  <tr>
    <td  align="center" bgcolor="#FFFFFF">等级</td>
    <td  colspan="3" align="center" bgcolor="#FFFFFF">普通会员</td>
  </tr>
  <tr>
    <td  align="center" bgcolor="#FFFFFF">身份证</td>
    <td  colspan="3" align="center" bgcolor="#FFFFFF"></td>
  </tr>
<!--  <tr>
    <td  align="center" bgcolor="#FFFFFF">手机号</td>
    <td  colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['mobile_phone'];?></td>
  </tr>-->
  <?php
  } ?>
  
  <tr>
    <td  align="center" bgcolor="#FFFFFF">提款金额</td>
    <td  align="center" bgcolor="#FFFFFF">小写金额</td>
    <td  align="center" bgcolor="#FFFFFF">￥<?php echo $rt['money'];?></td>
 
    <td  align="center" bgcolor="#FFFFFF">大写金额</td>
    <td  align="center" bgcolor="#FFFFFF"><?php echo $rt['upmoney'];?></td>
  </tr>
  <tr>
    <td rowspan="4" align="center" bgcolor="#FFFFFF">提款账号信息</td>
    <td  align="center" bgcolor="#FFFFFF">姓名</td>
    <td  align="center" bgcolor="#FFFFFF"><?php echo $rt['uname'];?></td>
    <td align="center" bgcolor="#FFFFFF">手机号</td>
    <td  align="center" bgcolor="#FFFFFF"><?php echo $rt['bankaddress'] ;?></td>
  </tr>
  <tr>
      
    <td  align="center" bgcolor="#FFFFFF">开户行</td>
    <td    align="center" bgcolor="#FFFFFF"><?php echo $rt['bankname'];?></td>
        <td  align="center" bgcolor="#FFFFFF">账号</td>
    <td    align="center" bgcolor="#FFFFFF"><?php echo $rt['banksn'];?></td>
    
  </tr>
  
  <tr>
    <td  align="center" bgcolor="#FFFFFF">身份证号</td>
     <td  colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['idcard'];?></td>
  </tr>
<!--  <tr>
    <td  align="center" bgcolor="#FFFFFF">手机号</td>
     <td  colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['bankaddress'];?></td>
  </tr>-->
  <tr>
    <td  align="center" bgcolor="#FFFFFF">事由</td>
    <td  colspan="4" align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td  align="center" bgcolor="#FFFFFF">账户余额审核</td>
    <td  align="center" bgcolor="#FFFFFF">&nbsp;</td>
    <td  align="center" bgcolor="#FFFFFF">提款账户审核</td>
    <td  colspan="2" align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td  align="center" bgcolor="#FFFFFF">财务审核</td>
    <td  align="center" bgcolor="#FFFFFF">&nbsp;</td>
    <td  align="center" bgcolor="#FFFFFF">付款人付款</td>
    <td  colspan="2" align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>