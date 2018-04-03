<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" /><meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta name="format-detection" content="telephone=no"/>
<title>登录</title>
<?php echo '<script> var SITE_URL="'.ADMIN_URL.'";</script>'."\n";?>
<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/jquery.json-1.3.js"></script>
<script>
function submit_login_data_instead(){
		
		home_phone = $('input[name="home_phone"]').val();
		pas = $('input[name="password"]').val();

		if(home_phone == "" || pas == "" ){ alert("请输入完整信息！"); return false; }
		$.post(SITE_URL+'user.php',{action:'user_login_instead',username:home_phone,password:pas},function(data){
			if(data != ""){
				alert(data);
			}else{
				location.href='<?php echo ADMIN_URL;?>user.php?act=Instead'; 
			}
		});
}
</script>
</head>
<body>

<?php $this->element('3/top',array('lang'=>$lang)); ?>
<style type="text/css">
.pw2{background-color: #fff;}
.pw{
border: 1px solid #ddd;
border-radius: 5px;
padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
</style>

<div id="main" style="padding:10px; min-height:300px">
	<form id="LOGIN" name="LOGIN" method="post" action="">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<th width="100%" align="left">登录账号：</th>			
			</tr>
			<tr>
				<td width="100%" align="center"><input placeholder="手机号码" type="text" name="home_phone" style="width:98%; height:30px; line-height:30px;" class="pw pw2"/></td>
			</tr>
			<tr>
				<th align="left">用户密码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input placeholder="输入密码" type="password" name="password" style="width:98%; height:30px; line-height:30px;" class="pw pw2"/></td>
			</tr>
			
			<tr>
				<td align="center" width="100%">
				<input name="" value="登录" type="button" id="submit" tabindex="6" data-disabled="false" class="pw loginbut" onclick="return submit_login_data_instead()">
				</td>
			</tr>
			
			<tr>
				<td align="center" width="100%">
				<a href="<?php echo ADMIN_URL;?>user.php?act=register_instead"><input  value="注册" type="button"  class="pw loginbut"></a>
				</td>
			</tr>
			
			</table>   
			 </form>
		
</div>
</body>
</html>

