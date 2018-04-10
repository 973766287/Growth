<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title>邀请码</title>
<link rel="stylesheet" href="Instead/css/css.css" type="text/css">
<script src="jquery-1.7.2.min.js" type="text/javascript"></script>

</head>

<body class="myBody">
<ul class="addFrame">
    <li class="addName bBor">
        <div class="add_name">
       <input type="text" name="code"  style="width: 82%;"/>
        </div>
       
    </li>

           
</ul>
<div class="addCard"><span id="activation">激活</span></div>

<script>
$("#activation").click(function (){
	var code = $("input[name=code]").val();
	$.post('<?php echo ADMIN_URL;?>InviteCode.php',{action:'activation',code:code},function(data){ 
		if(data == 'success'){
document.location.href="<?php echo ADMIN_URL;?>user.php?act=register_instead";
			}else{
				alert(data);
				 return false;
				}
		});
	});
</script>
</body>
</html>
