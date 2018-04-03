<header class="top_header">商家认证</header>
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
   <dt>店铺名称</dt>
   <dd class="grey"><?php  echo $rts['s_name'];?></dd>
  </dl>
  <dl>
   <dt>所在行业</dt>
   <dd class="grey"><?php  echo $rts['s_hangye'];?></dd>
  </dl>
 
  <dl>
   <dt>店铺地址</dt>
   <dd class="grey"><?php  echo $rts['s_address'];?></dd>
  </dl>
  <dl>
   <dt>审核状态</dt>
   <dd  class="grey">
   <?php  if($rts['status'] == 1){echo "<img src='img/rz2_04.jpg'/>商家认证成功！";}else if($rts['status'] == 2){?>
   
   <span style='color:#F00;'><? echo "认证中";?></span><? }else{?>
   
   <span style='color:#F00;'><? echo $rts['yijian']?$rts['yijian']:"审核未通过";?></span>
   <? }?>
   </dd>
  </dl>
</div>


<div class="real_sub"><?php  if($rts['status'] == 1){echo "认证已通,如要修改信息请联系客服";}else{?><a href="user.php?act=sj_renzheng_info" >修改信息</a><? }?></div>

