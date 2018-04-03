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
<?php echo '<script> var SITE_URL="'.ADMIN_URL.'";</script>'."\n";?>
<?php echo $this->js(array('jquery.min.js','jquery_dialog.js','common.js?v=21','fastclick.js'));?>
<script src="jquery-1.7.2.min.js" type="text/javascript"></script>
<style>
.black_overlay{
      		/*display:none;*/
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 130%;
            background-color:#ededed;
            z-index:9999;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=50);
}

/*弹出框的css*/
.openwindow{ position:absolute;filter:alpha(opacity=50); -moz-opacity:0.8; -khtml-opacity:0.8;opacity:0.8;}
.openwindow{    z-index: 99999; left:30%; top:70%;padding-left:20px;text-align:left; background:url(images/loading.png) no-repeat;padding-top:18px;height:52px; width:240px;}


</style>
</head>

<body class="addBody">

<form name="myform" id="myform" action="" method="post">
<ul class="addFrame" style="margin-top: 10px; ">
    <h2 class="bBor">请输入验证短信</h2>

    
    <input type="hidden" name="bank" id="bank" value="0"/>
    <!--有效期限-->
    <li class="addValid bBor">
        <h1>开始时间</h1>
        <input type="text" name="startdate" id="startdate" value="<?php echo $plan_list['kou']?>" />
    </li>
    <!--安全码-->
    <li class="addSafe bBor">
        <h1>结束时间</h1>
        <input type="text" name="enddate" id="enddate" value="<?php echo $plan_list['huan']?>" />
    </li>
    <!--短信验证-->
    <li class="addValidation bBor">
        <h1>短信验证</h1>
        <input type="text" name="yz_code" id="yz_code" placeholder="输入短信验证码"/>
        <!-- <input id="get_yz_code" type="button"  style="width:26%;height:26px;position:absolute;right:5%;top:12px;
    line-height: 26px !important;
    background-color: #2cd792;
    border-radius: 13px;
    font-size: 12px;
    text-align: center;
    color: #fff;" value="获取验证码"  /> -->
       <input type="text" id="smsSeq" value="<?php echo $smsSeq?>" style="display: none;" />
       <input type="text" id="orderNo" value="<?php echo $orderNo?>" style="display: none;" />
       <input type="text" id="card_id" value="<?php echo $card_id?>" style="display: none;" />
    </li>
    </li>
    <!--我已同意XXXX《服务协议》-->
    <li class="addAgree">
        <input type="radio" checked value=""><p>我已同意收银服务<a href="new.php?id=23"><span>《服务协议》</span></a></p>
    </li>
    <h3><input value="确认生成还款计划" type="button"  id="save"></h3>
</ul>
</form>

<script>
 $('#save').click(function () {

	
	var smsSeq  = document.getElementById('smsSeq').value;
	var orderNo = document.getElementById('orderNo').value;
	var yz_code = document.getElementById('yz_code').value;
    var card_id = document.getElementById('card_id').value;
		
	
		if (yz_code.length < 1)
        {
            alert('请填写手机验证码！');
            return false;
        }

        if (smsSeq.length < 1)
        {
            alert('短信流水号不能为空');
            return false;
        }
		
        if (orderNo.length < 1)
        {
            alert('订单号不能为空');
            return false;
        }

		$.post('<?php echo ADMIN_URL;?>user.php',{

            action:'kft_verify_code',

            yz_code:yz_code,

            smsSeq:smsSeq,

            orderNo:orderNo,

            card_id:card_id

        },function(data){ 

            var data = JSON.parse(data);

    		if(data.status == 1){

    	        location.href="<?php echo ADMIN_URL;?>user.php?act=Instead_plan&card_id="+data.message;
    		}else{
    			console.log(data);
                alert(data);
    			 return false;
    		}
		});
		
	
	//	}	
 });

</script>

<script>
// $("#get_yz_code").click(function(){

// 	sendMobileCode($("#mobile"), $("#yz_code"), $("#get_yz_code"));

// 		});
/**
 * 发送验证码
 * 
 * @param mobileObj
 *            手机号对象
 * @param mobileCodeObj
 *            短信验证码对象
 * @param sendButton
 *            点击发送短信证码的按钮对象，用于显示倒计时信息
 */
// function sendMobileCode(mobileObj, mobileCodeObj, sendButton) {
// 	        countdown(sendButton);
// 			// 发送邮件
// 			var url = 'user.php?act=getcodes';
// 			$.post(url, {
// 				mobile: mobileObj.val()
// 			}, function(result) {
// 				if (result == 'ok') {
// 					// 倒计时
// 					/*countdown(sendButton);*/
// 				} else {
// 					alert(result);
// 				}
// 			}, 'text');
// }
// var wait = 60;
// function countdown(obj, msg) {
// 	obj = $(obj);

// 	if (wait == 0) {
// 		obj.removeAttr("disabled");
// 		obj.val(msg);
// 		wait = 60;
// 	} else {
// 		if (msg == undefined || msg == null) {
// 			msg = obj.val();
// 		}
// 		obj.attr("disabled", "disabled");
// 		obj.val(wait + "秒后重新获取");
// 		wait--;
// 		setTimeout(function() {
// 			countdown(obj, msg)
// 		}, 1000)
// 	}
// }


</script>
</body>
</html>
