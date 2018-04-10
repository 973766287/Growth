<!-- <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title>智能还</title>
<link rel="stylesheet" href="css/reset.css" type="text/css">
<link rel="stylesheet" href="css/css.css" type="text/css">
</head> -->

<body>
    <style type="text/css">
        .btn{
            height: 40px;
            line-height: 40px;
            position: relative;
            width: 40%;
            float: left;
            background-color: #0099E5;
            font-size: 14px;
            color: #fff;
            text-align: center;
            border: 0;
        }
    </style>
<div class="registrationBox">
	<div class="registration">
    	<div id="reg_name">
        	<span class="bBor">
            <input placeholder="请输入11位电话号码" type="text" id="mobile">
            </span>
        </div>
        <div id="reg_password">
        	<span class="bBor">
            <input placeholder="请输入新密码" id="password" type="text">
            </span>
        </div>
        <div id="reg_code">
        	<span class="bBor">
            <input placeholder="请输入验证码" id="yz_code" type="text">
            </span>
            <button  class="btn" id="get_yz_code">获取验证码</button>
        </div>
        <div id="reg_sub"><input value="提交" type="submit"></div>
    </div>
    <div id="loginBg"></div>
</div>
<script type="text/javascript">
    $("#get_yz_code").click(function(){

    sendMobileCode($("#mobile"), $("#yz_code"), $("#get_yz_code"));

        });
    $("#reg_sub").click(function(){
        var mobile   = $("#mobile").val();
        var password = $("#password").val();
        var yz_code  = $("#yz_code").val();
        if(mobile.length == 0){
            alert("手机号不能为空");
            return; 
        }
        if(yz_code.length == 0){
            alert("验证码不能为空");
            return; 
        }
        if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(mobile))){ 
          alert("手机号非法");
          return false; 
         } 
        if(password.length == 0){
            alert("密码不能为空");
            return; 
        }
        var data = new Object();
        data['mobile']   = mobile;
        data['password'] = password;
        data['yz_code']  = yz_code;
        var json_str = JSON.stringify(data);
        $.post("<?php echo ADMIN_URL;?>user.php?act=findPassword&data="+encodeURIComponent(json_str),function(data){
            var data = JSON.parse(data);
            alert(data.message);
            if(data.status == 1){
                location.href="<?php echo ADMIN_URL;?>user.php?act=login";
            }
        })
    })
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
function sendMobileCode(mobileObj, mobileCodeObj, sendButton) {
        //发送邮件
        var url = 'user.php?act=getcodes';
        $.post(url, {
            mobile: mobileObj.val()
        }, function(result) {
            if (result == 'ok') {
                // 倒计时
                countdown(sendButton);
            } else {
                alert(result);
            }
        }, 'text');
}
var wait = 60;
function countdown(obj, msg) {
    obj = $(obj);
    console.log(obj);
    if (wait == 0) {
        obj.removeAttr("disabled");
        obj.text("重新获取");
        wait = 60;
    } else {
        if (msg == undefined || msg == null) {
            msg = obj.val();
        }
        obj.css('background','#fffff');
        obj.attr("disabled", "disabled");
        obj.text(wait + "秒后重新获取");
        wait--;
        setTimeout(function() {
            countdown(obj, msg)
        }, 1000)
    }
}
</script>
</body>

<!-- </html> -->
