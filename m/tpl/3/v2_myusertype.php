<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<title>推广明细</title>
</head>

<body>
<header class="top_header">推广明细</header>
<div class="mx_top">
 <div class="mx_top_body"><img src="img/mx_03.jpg" >推荐合计<br><span><?php echo $rt['zcount'];?>人</span></div>
 <div class="mx_top_body"><img src="img/mx_03-02.jpg"  >累计升级基金<br><span><?php echo floor($rt['tuiguang']*100)/100;?>元</span></div>
</div>
<ul class="mx_body">
  <li><a class="react" href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=12';?>"><span><?php echo $rt['hehuo'];?>人<img class="jt" src="img/mx_23.png"></span><img class="tx" src="img/mx_10.png">投资合伙人</a></li>
  <li><a class="react" href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=11';?>"><span><?php echo $rt['huangguan'];?>人<img class="jt" src="img/mx_23.png"></span><img class="tx" src="img/mx_11.png">皇冠</a></li>
  <li><a class="react" href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=10';?>"><span><?php echo $rt['zuanshi'];?>人<img class="jt" src="img/mx_23.png"></span><img class="tx" src="img/mx_13.png">钻石</a></li>
  <li ><a  href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=9';?>"><span><?php echo $rt['jinpai'];?>人<img class="jt" src="img/mx_23.png"></span><img class="tx" src="img/mx_14.png">金牌</a></li>
  <li ><a  href="<?php echo ADMIN_URL;?>user.php?act=make_qrcode"><div style="margin: auto;width: 72px;">我的二维码</div></a></li>
</ul>

<div class="fixed tBor">

  <ul>

    <a href="<?php echo ADMIN_URL;?>user.php?act=baoming"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-home-g.png" height="25"><p>会员中心</p></li></a>

    <a href="<?php echo ADMIN_URL;?>daili.php?act=myusertype"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-promote-g.png" height="25"><p >推广</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php?act=Instead"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>还款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>mycart.php?type=shoukuan"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>收款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-member-b.png" height="25"><p class="on">我的</p></li></a>

    </ul>

</div>
</body>
</html>
