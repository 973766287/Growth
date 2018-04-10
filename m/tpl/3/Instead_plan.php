<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title>智能还款</title>
<link rel="stylesheet" href="Instead/css/reset.css" type="text/css">
<link rel="stylesheet" href="Instead/css/css.css" type="text/css">
</head>


<body class="qrBody">
<div class="scAttention">
	<h2>系统根据您的卡余额自动生成最优还款计划</h2>
    <div class="scCard">
    	<div class="scCard_number bBor">
            <h1><?php echo $cardinfo['bank_name']?><span>（尾号<?php echo $cardinfo['bank_no_sort'];?>）</span></h1>
            <p><?php echo $cardinfo['mobile_sort'];?></p>
            <div class="scCard_pos"><img src="../<?php echo $cardinfo['bank_pic'];?>"></div>
        </div>
        <ul class="schuan">
        <li class="rBor fl">
        <h3>还款金额</h3>
        <p><?php echo $Instead_info['Instead_money'];?></p>
        </li>
        
        <li class="rBor fl">
        <h3>卡余额</h3>
        <p><?php echo $Instead_info['Over_money'];?></p>
        </li>
        
        <li class="fr">
        <h3>还款次数</h3>
        <p><?php echo $Instead_info['cishu'];?>期</p>
        </li>
        </ul>
    </div>
</div>

<form action="<?php echo ADMIN_URL;?>user.php?act=Instead_verify_code" method="post" >
<input type="hidden" name="card_id" value="<?php echo $cardinfo['id'];?>" />
<input type="hidden" name="user_id" value="<?php echo $cardinfo['uid'];?>" />
<input type="hidden" name="Instead_money" value="<?php echo $Instead_info['Instead_money'];?>" />
<input type="hidden" name="Over_money" value="<?php echo $Instead_info['Over_money'];?>" />
<input type="hidden" name="Bill_day" value="<?php echo $Instead_info['Bill_day'];?>" />
<input type="hidden" name="Instead_day" value="<?php echo $Instead_info['Instead_day'];?>" />
<div class="planList">
	<ul>
    
    <?php if (!empty($thisplan_list)){?>
   <?php $i=1; foreach($thisplan_list as $plan){?>
    <li class="bBor">
    <div class="planList_top">
    	<h3 class="fl"><b><?php echo $i;?></b>/<?php echo $Instead_info['cishu'];?>期</h3>
        <span class="fr">代还时间 <?php echo date('Y-m-d H:i:s',$plan['huan_time']);?></span>
    </div>
    <div class="planList_bottom">
    	<h4 class="fl"><?php echo $plan['jiaoyi'];?><b>（含手续费<?php echo $plan['shouxufei'];?>）</b></h4>
        <a href=""><span class="fr">等待还款</span></a>
    </div>
    </li>
        <?php $i++;}?>
    <?php }?>
   
    </ul>
    <h1><input value="确认还款计划" type="submit"></h1>
    <div class="qrAgree">
    	<input type="radio" checked  value=""><p>我已同意收银服务<a href="new.php?id=23"><span>《服务协议》</span></a></p>
    </div>
   <!-- <h5>每次代还会把结果发至手机：<?php echo $cardinfo['mobile_sort'];?></h5>-->
</div>
</form>

</body>
</html>
