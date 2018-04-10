<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<title>结算页面</title>
</head>

<body>
<header class="top_header">结算</header>
<div class="set_con" style="margin-bottom: 54px;">
  <dl>
    <dt><img src="img/s1.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=fenrun">结算</a>分润</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['fenrun']), 0, -1));?></span><br>累计收入</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['fenrun']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s2.jpg"></dt>
    <dd>
      <h2><a href="javascript:void(0);" onClick="tixian();">结算</a>佣金</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['yongjin']), 0, -1));?></span><br>累计收入</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['yongjin']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s3.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=tuiguang">结算</a>升级奖励</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['tuiguang']), 0, -1));?></span><br>累计收入</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['tuiguang']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s4.jpg"></dt>
    <dd>
      <h2><a href="javascript:void(0);">已结算</a>银联支付(商旅类)</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['yinlian']), 0, -1));?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['yinlian']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  
  <dl>
    <dt><img src="img/s4.jpg"></dt>
    <dd>
      <h2><a href="javascript:void(0);">已结算</a>银联支付(缴费类)</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['yinlian_h5']), 0, -1));?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['yinlian_h5']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  
  <dl>
    <dt><img src="img/s5.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=weixin">结算</a>微信支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['weixin']), 0, -1));?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['weixin']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  
   <dl>
    <dt><img src="img/s8.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=zhifubao">结算</a>支付宝支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['zhifubao']), 0, -1));?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['zhifubao']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  
  <dl>
    <dt><img src="img/s6.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=haiwai">结算</a>海外支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['haiwai']), 0, -1));?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['haiwai']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s7.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=jingdong">结算</a>京东支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rts['usermoney']['jingdong']), 0, -1));?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['jingdong']), 0, -1));?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
 
</div>
<div class="fixed tBor">

  <ul>

    <a href="<?php echo ADMIN_URL;?>user.php?act=baoming"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-home-g.png" height="25"><p>会员中心</p></li></a>

    <a href="<?php echo ADMIN_URL;?>daili.php?act=myusertype"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-promote-g.png" height="25"><p >推广</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php?act=Instead"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>还款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>mycart.php?type=shoukuan"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>收款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-member-b.png" height="25"><p class="on">我的</p></li></a>

    </ul>

</div>
<script>
function tixian(){
		
	if(<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['yongjin']), 0, -1));?> < 500){
		
			alert("佣金须大于500元方可结算！");
			
			return false;
		}else if(<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['yongjin']), 0, -1));?> == 500){
		
			alert("该项资金仅限用于会员升级！");
			
			return false;
		}else if(<? echo sprintf("%.2f",substr(sprintf("%.3f", $rt['userinfo']['yongjin']), 0, -1));?> > 500){
		
			alert("该项资金仅限用于会员升级！");
			
			return false;
		}else{
			window.location.href="daili.php?act=postmoney&key=yongjin";
			
			}

	
	
	
	
	}
	
	
	function yl_tixian(){
		
		alert("银联代付系统升级维护中，提现业务暂时关闭！");
		
			//window.location.href="daili.php?act=postmoney&key=yinlian";
		}
</script>

</body>
</html>
