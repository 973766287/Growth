<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>

    <td   <?php if($_GET['t']==1){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?>><a href="<?php echo SITE_URL.'user.php?act=myuser&t=1'?>">我的一级会员</a></td>
    <td <?php if($_GET['t']==2){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?>><a href="<?php echo SITE_URL.'user.php?act=myuser&t=2'?>">我的二级会员</a></td>
    <td <?php if($_GET['t']==3){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?>><a href="<?php echo SITE_URL.'user.php?act=myuser&t=3'?>">我的三级会员</a></td>

  </tr>
</table>
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>

    <td width="160" bgcolor="#f9f9f9">用户名</td>
    <td width="51" bgcolor="#f9f9f9">资金</td>
    <td width="51" bgcolor="#f9f9f9">积分</td>
    <td width="51" bgcolor="#f9f9f9">邀请</td>
    <!--<td width="76" bgcolor="#f9f9f9">总积分</td>-->
    <td bgcolor="#f9f9f9">等级</td>
  </tr>
  <?php
   if(!empty($rt['lists'])){
   foreach($rt['lists'] as $k=>$row){
   ++$k;
  ?>
    <tr>

    <td><?php echo $row['nickname'];?></td>
    <td class="cr2">￥<?php echo $row['money_ucount'];?></td>
    <td class="cr2"><?php echo $row['points_ucount'];?>分</td>
    <td class="cr2"><?php echo $row['share_ucount'];?></td>
    <!--<td class="cr2">105</td>-->
    <td><?php echo $row['level_name'];?></td>
  </tr>
  <?php } } ?>
  <tr>
  <td  colspan="6" style="text-align:left;" class="pagesmoney">
  <style>
  .pagesmoney a{ margin-right:5px; color:#FFF; background-color:#b70000; text-decoration:none; float:left; display:inherit; padding-left:5px; padding-right:5px; text-align:center}
  .pagesmoney a:hover{ text-decoration:underline}
  </style>
  <?php
            if (!empty($rt['pages'])) {
                echo $rt['pages']['showmes'];
                echo $rt['pages']['first'];
                echo $rt['pages']['previ'];
                echo $rt['pages']['next'];
                echo $rt['pages']['Last'];
            }
            ?>
  </td>
  </tr>
</table>
