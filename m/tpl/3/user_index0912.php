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
<div class="set_con">
  <dl>
    <dt><img src="img/s1.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=fenrun">结算</a>分润</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['fenrun'];?></span><br>累计收入</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['fenrun'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s2.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=yongjin">结算</a>佣金</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['yongjin'];?></span><br>累计收入</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['yongjin'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s3.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=tuiguang">结算</a>升级奖励</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['tuiguang'];?></span><br>累计收入</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['tuiguang'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s4.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=yinlian">结算</a>银联支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['yinlian'];?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['yinlian'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s5.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=weixin">结算</a>微信支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['weixin'];?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['weixin'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  
   <dl>
    <dt><img src="img/s8.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=duanxin">结算</a>支付宝支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['zhifubao'];?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['zhifubao'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  
  <dl>
    <dt><img src="img/s6.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=baidu">结算</a>百度支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['baidu'];?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['baidu'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
  <dl>
    <dt><img src="img/s7.jpg"></dt>
    <dd>
      <h2><a href="daili.php?act=postmoney&key=jingdong">结算</a>京东支付</h2>
      <div class="set_con_body">
        <div class="set_con_body_con"><span class="red">¥<? echo $rts['usermoney']['jingdong'];?></span><br>
        总额</div>
        <div class="set_con_body_line"></div>
        <div class="set_con_body_con"><span class="org">¥<? echo $rt['userinfo']['jingdong'];?></span><br>可结算金额</div>
      </div>
    </dd>
  </dl>
 
</div>
</body>
</html>
