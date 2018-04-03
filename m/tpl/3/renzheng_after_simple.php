<style>
.real_box dl dt{
	width:100px;
	}
</style>
<header class="top_header">商户认证</header>

<div class="real_box">
  <dl>
   <dt>手机号码</dt>
   <dd class="grey"><?php  echo substr_replace($rts['mobile'],"****",7,4);?></dd>
  </dl>
  <dl>
   <dt>法人姓名</dt>
   <dd class="grey"><?php  echo $rts['uname'];?></dd>
  </dl>

  <dl>
   <dt>银行卡号</dt>
   <dd class="grey"><?php  echo substr_replace($rts['banksn'],"****",10,4);?></dd>
  </dl>
  <dl>
   <dt>身份证号码</dt>
   <dd class="grey"><?php  echo substr_replace($rts['idcard'],"****",14,4);?></dd>
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


<a href="user.php?act=renzheng_info_simple" ><div class="real_sub">修改信息</div></a>

