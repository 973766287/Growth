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
<script type="text/javascript" src="Instead/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="Instead/js/zcircleMove.js"></script>
</head>


<body class="dlBody">
<div class="dl_tlt">
	<h1 class="fl"><?php echo $cardinfo['bank_name']?><span>（尾号<?php echo $cardinfo['bank_no_sort'];?>）</span></h1>
    <!--<a href=""><span class="fr dl_tlt_suc">还款成功</span></a>-->
    <div class="dl_tlt_pos"><img src="../<?php echo $cardinfo['bank_pic'];?>"></div>
</div>

<div class="dlComplete">
	<div class="surePass">
        <div class="anyield">
            <p>已还款</p>
            <p><?php echo $user_card_instead_plans_ed['huan_moneys'];?></p>
            <p><?php if($user_card_instead_plans_ed['yh_qishu'] > 0){echo "第".$user_card_instead_plans_ed['yh_qishu']."/";}?><?php echo $user_card_instead_plans_all['z_qishu'];?>期</p>
        </div>
		<canvas class="circleRun" data-run="0" id="canvasThree" amout="<?php echo $user_card_instead_plans_all['z_huan_moneys'];?>" nowData="1000"></canvas>
    </div>
    
    

<script>
	$(function(){
		var findCanvas=$("#canvasThree");
		var percents=findCanvas.attr('nowData')/findCanvas.attr('amout');
		 percents=0.25;
		//percents 为百分比的值  范围 0- 1
		runCircle(
		   { 
			obj:'canvasThree', 
			percent:<?php echo $percent;?>,
			url:'Instead/images/zstart.png',   //飞机小图地址
			imgWidth:1,
			imgHeight:1,
			circleBottomColor:"rgba(255,255,255,0.3)",//圆环底色
			outerColorStart:'#0099E5',  //外部圆环 渐变色
			outerColorMid:'#0099E5',
			outerColorEnd:'#0099E5',
			innerColorStart:'#fff',  //内部圆环 渐变色
			innerColorEnd:'#fff',
		   }
		);
		
	})
</script>
	<ul class="tBor">
    <li class="fl rBor">
    	<h3>完成还款时间</h3>
        <p><?php echo date('m-d H:i',$user_card_instead_plans_all['m_huan_time']);?></p>
    </li>
    <li class="fr">
    	<h3>代还款总额</h3>
        <p><?php echo $user_card_instead_plans_all['z_huan_moneys'];?></p>
    </li>
    </ul>
</div>

<form action="<?php echo ADMIN_URL;?>user.php?act=Instead_splans_stop" method="post" name="form" >
<input type="hidden" name="card_id" value="<?php echo $cardinfo['id'];?>"/>
<div class="planList">
	<ul>
    <?php if($thisplan_list){?>
    <?php $i=1; foreach($thisplan_list as $plan){?>
    <li class="bBor" id="<?php echo $plan['id'];?>">
    <div class="planList_top">
    	<h3 class="fl"><b><?php echo $i;?></b>&nbsp;(手续费 <?php echo $plan['Instead_sxf']?>)</h3>
        <span class="fr"><?php if($plan['status'] == 3){echo "成功还款";}else{echo "待还";}?></span>
    </div>
    <div class="planList_bottom">
        <h4 class="fl">交易金额：<?php echo $plan['kou_money'];?> </h4> <span style="float:right;">交易时间：<?php echo date('m-d',$plan['kou_time']);?> &nbsp; <?php echo date('H:i',$plan['kou_time']);?> </span> 
        
        </br>
        <h4 class="fl">代还金额：<?php echo $plan['huan_money'];?>  </h4><span style="float:right;">代还时间：<?php echo date('m-d',$plan['huan_time']);?> &nbsp; <?php echo date('H:i',$plan['huan_time']);?> </span> 
        
    
    </div>
    </li>
    <?php if($plan['status'] == 3){?>
    <div class="sss" id="info_<?php echo $plan['id'];?>" style="display:none;">
     <li class="bBor specLi">
    	<p><span><?php echo date('Y-m-d H:i:s',$plan['daifu_time']);?></span>代还款提交时间</p>
    </li>
    <li class="bBor specLi">
    	<p><span><?php echo $plan['daifu_sn'];?></span>交易单号</p>
    </li>
    </div>
    <?php }?>
    <?php $i++;}
	}?>
   
    
   
    </ul>
    <h1><input value="终止还款计划" type="submit"></h1>
</div>
</form>
<script>
$(".bBor").click(function (){
	
//	alert($(this).attr("id"));
	
if($("#info_"+$(this).attr("id")).css("display")=="none"){ 
$("#info_"+$(this).attr("id")).css("display","block"); 
/*$("#info2_"+$(this).attr("id")).css("display","block"); */

/*$("#info_"+$(this).attr("id")).siblings('sss').css("display","none");*/

} 
else{ 
$("#info_"+$(this).attr("id")).css("display","none"); 
/*$("#info2_"+$(this).attr("id")).css("display","none"); */
} 

/*var $n = $("#info1_"+$(this).attr("id"));
var $m = $("#info2_"+$(this).attr("id"));

$n.siblings(".specLi").css("display","none");
$m.siblings(".specLi").css("display","none");*/
	
	});

</script>
</body>
</html>
