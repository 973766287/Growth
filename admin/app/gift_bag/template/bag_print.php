
<table width="100%" style="margin:0 auto" border="1" cellpadding="1" cellspacing="0" background="#ccc">
    <tr>
    <td height="35" colspan="4" align="center" bgcolor="#FFFFFF"><h1>礼包领取记录<?php echo !empty($rt['create_time']) ? date('Y-m-d', $rt['create_time']) : "无知"; ?></h1></td>
  </tr>

 <tr>
    <td height="25" align="center" bgcolor="#FFFFFF">编号</td>
    <td height="25" colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['oid'];?></td>
  </tr>
  <tr>
    <td height="25" align="center" bgcolor="#FFFFFF">礼包名称</td>
    <td height="25" colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['bag_name'];?></td>
  </tr>
  <tr>
    <td height="25" align="center" bgcolor="#FFFFFF">领取会员昵称</td>
    <td height="25" colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['nickname'];?></td>
  </tr>
  <tr>
    <td height="25" align="center" bgcolor="#FFFFFF">领取人</td>
    <td height="25" colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['consignee'];?></td>
  </tr>
    <tr>
    <td height="25" align="center" bgcolor="#FFFFFF">手机</td>
    <td height="25" colspan="3" align="center" bgcolor="#FFFFFF"><?php echo $rt['tel'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rt['mobile'];?></td>
  </tr>
  <tr>
    <td height="25" align="center" bgcolor="#FFFFFF">地址</td>
    <td height="25" colspan="3" align="center" bgcolor="#FFFFFF"> <?php echo $rt['consignee'] . $rt['province'] . $rt['city'] . $rt['district'] . $rt['address']; ?></td>
  </tr>



</table>