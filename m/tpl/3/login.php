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
<link rel="stylesheet" href="./css/reset.css" type="text/css">
<link rel="stylesheet" href="./css/css.css" type="text/css">
</head> -->


<body>
<div class="loginBox">
    <div class="login_logo"><img src="./images/login.png"></div>
    <div class="login">
        <div id="lg_name">
            <span class="bBor">
            <input placeholder="请输入手机号" type="text" id="user">
            </span>
        </div>
        <div id="lg_password">
            <span class="bBor">
            <input placeholder="请输入密码" type="password" id="psd">
            </span>
        </div>
        <div id="login">
            <span>
            <input value="立即登录" type="submit" id="sub">
            <p><a href="<?php echo ADMIN_URL;?>user.php?act=findPsd">忘记密码？</a><br>没有账号？<a href="<?php echo ADMIN_URL;?>user.php?act=register">立即注册</a></p>
            </span>
        </div>
    </div>
    <div id="loginBg"></div>
</div>
<script type="text/javascript">
    $("#sub").click(function(){
        var user = $("#user").val();
        var psd  = $("#psd").val();
        // console.log(psd.length);
        if(user.length == 0){
            alert("用户名不能为空");
            return; 
        }
        if(psd.length == 0){
            alert("密码不能为空");
            return; 
        }
        var data = new Array();
        data['username'] = user;
        data['password'] = psd;
        data = JSON.stringify(data);
        $.post('<?php echo ADMIN_URL;?>user.php?act=ajax_user_login_instead&username='+encodeURIComponent(user) +'&password='+encodeURIComponent(psd),function(info){
            var data = JSON.parse(info);
            alert(data.message);
            if(data.status == 1){
                // if(data.shiming >0){
                //     location.href="<?php echo ADMIN_URL;?>user.php?act=baoming";
                // }else{
                //     location.href="<?php echo ADMIN_URL;?>user.php?act=renzheng";
                // }
                location.href="<?php echo ADMIN_URL;?>user.php?act=baoming";
            }
        });
    
    });
   
</script>
</body>
<!-- </html> -->
