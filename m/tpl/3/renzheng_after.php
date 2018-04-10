<header class="top_header">实名认证</header>

<?php if($rts['uid'] == 42){?>

<header class="top_header"><a href="user.php?act=sj_1">商户入驻</a></header>


<?php }?>
<div class="real_box">
  <dl>
   <dt>手机号</dt>
   <dd class="grey"><?php  echo $rts['mobile'];?></dd>
  </dl>
  <dl>
   <dt>真实姓名</dt>
   <dd class="grey"><?php  echo $rts['uname'];?></dd>
  </dl>
  <dl>
   <dt>证件类型   </dt>
   <dd>身份证</dd>
  </dl>
  <dl>
   <dt>证件号码</dt>
   <dd class="grey"><?php  echo substr_replace($rts['idcard'],"****",14,4);?></dd>
  </dl>
  <dl>
   <dt>开户行</dt>
   <dd class="grey"><img src="<?php echo SITE_URL.$bank['pic'];?>"><?php  echo $bank['name'];?>   </dd>
  </dl>
 
  <dl>
   <dt>审核状态</dt>
   <dd  class="grey">
   <?php  if($rts['status'] == 1){echo "<img src='img/rz2_04.jpg'/>实名认证成功！";}else if($rts['status'] == 2){?>
   
   <span style='color:#F00;'><? echo "认证中";?></span><? }else{?>
   
   <span style='color:#F00;'><? echo $rts['yijian']?$rts['yijian']:"审核未通过";?></span>
   <? }?>
   </dd>
  </dl>
</div>


<div class="real_sub"><?php  if($rts['status'] == 1){echo "认证已通过,如要修改信息请联系客服";}else{?><a href="user.php?act=renzheng_info" >修改信息</a><? }?></div>

<? if($shop){?>



<header class="top_header" style="background:#DA0101;">商家认证</header>
<div class="real_box">
  <dl>
   <dt>店铺名称</dt>
   <dd class="grey"><?php  echo $shop['s_name'];?></dd>
  </dl>
  <dl>
   <dt>所在行业</dt>
   <dd class="grey"><?php  echo $shop['s_hangye'];?></dd>
  </dl>
 
  <dl>
   <dt>店铺地址</dt>
   <dd class="grey"><?php  echo $shop['s_address'];?></dd>
  </dl>
  <dl>
   <dt>审核状态</dt>
   <dd  class="grey">
   <?php  if($shop['status'] == 1){echo "<img src='img/rz2_04.jpg'/>商家认证成功！";}else if($shop['status'] == 2){?>
   
   <span style='color:#F00;'><? echo "认证中";?></span><? }else{?>
   
   <span style='color:#F00;'><? echo $shop['beizhu']?$shop['beizhu']:"审核未通过";?></span>
   <? }?>
   </dd>
  </dl>
</div>


<div class="real_sub" style="background:#DA0101;"><?php  if($shop['status'] == 1){echo "认证已通过,如要修改信息请联系客服";}else{?><a href="user.php?act=sj_renzheng_info" >修改信息</a><? }?></div>

<? }else{?>

<div class="real_sub" style="background:#DA0101; width:30%;"><a href="user.php?act=sj_renzheng" >商家认证</a></div>

<? }?>
