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
</head>

<body class="scBody">
<div class="scAttention">
	<h2>用卡余额循环交易帮你还款，请准确输入卡信息</h2>
    <div class="scCard">
    	<div class="scCard_number">
            <h1><?php echo $cardinfo['bank_name']?><span>（尾号<?php echo $cardinfo['bank_no_sort'];?>）</span></h1>
            <p><?php echo $cardinfo['mobile'];?></p>
            <div class="scCard_pos"><img src="../<?php echo $cardinfo['bank_pic'];?>"></div>
        </div>
    </div>
</div>
<form>
<input type="hidden" name="card_id" value="<?php echo $cardinfo['id'];?>"/>
<input type="hidden" name="user_id" value="<?php echo $user['uid'];?>"/>
<div class="scCon">
	<ul>
    <li class="hk_money bBor">
    	<h1>还款金额</h1>
        <input type="text" name="Instead_money" value="<?php echo $cardsetting['Instead_money']?>" onkeyup="clearNoNum(this)" placeholder="输入需还款金额"/>
    </li>
    <li class="yu_money bBor">
    	<h1>卡&nbsp;余&nbsp;额</h1>
        <input type="text" name="Over_money" value="<?php echo $cardsetting['Over_money']?>" onkeyup="clearNoNum(this)" placeholder="输入卡余额（剩余额度）"/>
    </li>
    <!--账单日-->
    <li class="zhangdan bBor">
        <h1>账&nbsp;单&nbsp;日</h1>
        <select name="Bill_day">
        <option value="0">选填</option>
        <?php
		 for($i=1;$i<29;$i++){
        ?>
        <option value="<?php echo $i;?>" <?php if($i == $cardsetting['Bill_day']){echo "selected";}?>><?php echo $i;?></option>
       <?php }?>
        </select>
    </li>
    
    <!--还款日-->
    <li class="zhangdan bBor">
        <h1>还&nbsp;款&nbsp;日</h1>
        <select name="Instead_day">
        <option value="0">选填</option>
        <?php
		 for($i=1;$i<=$days;$i++){
        ?>
        <option value="<?php echo $i;?>" <?php if($i == $cardsetting['Instead_day']){echo "selected";}?>><?php echo $i;?></option>
       <?php }?>
        </select>
    </li>
    </ul>
    <h3><input value="生成还款计划" type="button" id="save"></h3>
    <div class="scIntro">
    	<span>为确保还款成功请准确输入相关内容，18:00前确认还款计划可当日开始还款，18:00后确认次日开始还款。</span>
    </div>
    <div class="strategy"><a href="http://mp.weixin.qq.com/mp/homepage?__biz=MzI3NzQwNTQ5NQ==&hid=2&sn=c0d6ec237a47dc80752c1c908ca69a26#wechat_redirect"><span>代还款攻略></span></a></div>
</div>
</form>


<script>

function clearNoNum(obj){
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数  
    if(obj.value.indexOf(".")< 0 && obj.value !=""){//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额 
        obj.value= parseFloat(obj.value);
		if(obj.value == 'NaN'){
			obj.value = 0;
			}
    } 
} 

 $('#save').click(function () {
        var card_id = $("input[name='card_id']").val();
		var user_id = $("input[name='user_id']").val();
		var Instead_money = $("input[name='Instead_money']").val();
		var Over_money = $("input[name='Over_money']").val();
		var Bill_day = $("select[name='Bill_day']").val();
		var Instead_day = $("select[name='Instead_day']").val();
		
		    if(!card_id){
			alert("绑卡id不正确");
			return false;
			}
			if(!user_id){
			alert("会员id不正确");
			return false;
			}
			if(Instead_money < 500){
			alert("还款金额必须大于500");
			return false;
			}
			if((Over_money < Instead_money*0.05) || (Over_money <500)){
			alert("卡余额必须大于总还款金额的5%,且大于500");
			return false;
			}
		/*	if(Over_money <=500){
			alert("卡余额必须大于500");
			return false;
			}
			*/
			if(Bill_day <=0){
			alert("账单日不正确");
			return false;
			}
			if(Instead_day <= 0){
			alert("还款日不正确");
			return false;
			}
			
			/*createwindow();*/
		
		$.post('<?php echo ADMIN_URL;?>user.php',{action:'ajax_add_instead_setting',card_id:card_id,user_id:user_id,Instead_money:Instead_money,Over_money:Over_money,Bill_day:Bill_day,Instead_day:Instead_day},function(data){ 
		
			/*removewindow();*/
			if(data == "success"){
				document.location.href="<?php echo ADMIN_URL;?>user.php?act=Instead_plan&card_id="+card_id;
			}else{
				alert(data);
				}
		});
	
 });
</script>

</body>
</html>
