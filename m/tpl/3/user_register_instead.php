<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" /><meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta name="format-detection" content="telephone=no"/>
<title>注册</title>
<?php echo '<script> var SITE_URL="'.ADMIN_URL.'";</script>'."\n";?>
<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_URL;?>js/jquery.json-1.3.js"></script>
<script>
//提交注册数据
function submit_register_data_instead(na){
	   if(na==null || na=="" || typeof(na)=='undefined'){ alert("确认表单是否存在！"); return false;}
	   var fromAttr        = new Object();  //一个商品的所有属性
	   var form      = document.forms[na]; //表单
	   
	   // 检查注册表单的属性
	   if (form)
	   {
	   		fromAttr = getFromAttributes(form);
			
	   }
	   else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
		//$('.register_mes').html('正在注册，请耐心等待。。。');
		//createwindow();
		
		$.ajax({
		   type: "POST",
		   url: SITE_URL+"user.php?action=register_instead",
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){ 
		   		//removewindow();
				if(data.error==0){
					alert(data.message);
					window.location.href=SITE_URL+'user.php?act=login_instead'; //注册成功
					return false;
				}else{
					alert(data.message);
				}
		   },
		   error: function(error){
			  // removewindow();
			   alert("意外错误");
			}
		});
		return false;
}


function getFromAttributes(formAttr)
{
  var obj = new Object();
  var j = 0;

  for (i = 0; i < formAttr.elements.length; i ++ )
  { 
    if(((formAttr.elements[i].type == 'radio' || formAttr.elements[i].type == 'checkbox') && formAttr.elements[i].checked) || formAttr.elements[i].tagName == 'SELECT' || formAttr.elements[i].type=='text' || formAttr.elements[i].type=='textarea' ||  formAttr.elements[i].type == 'hidden' || formAttr.elements[i].type == 'password')
    { 
	  obj[formAttr.elements[i].name] = formAttr.elements[i].value;
      j++ ;
    }
  }
return obj;
}
</script>

</head>
<body>
<?php $this->element('3/top',array('lang'=>$lang)); ?>
<style type="text/css">
.pw{
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
</style>
<div id="main" style="padding:10px; min-height:300px">
	<form id="REGISTER1" name="REGISTER1" method="post" action="">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<th align="left">手机号码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input type="text" name="home_phone" id="mobile" style="width:95%; height:30px; line-height:normal;" class="pw"/></td>
			</tr>
			<tr>
				<th align="left">用户密码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input type="password" name="password" style="width:95%; height:30px; line-height:normal;" class="pw"/></td>
			</tr>
			<tr>
				<th align="left">确认密码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input type="password" name="rp_pass" style="width:95%; height:30px; line-height:normal;" class="pw"/></td>
			</tr>
            
            <tr>
				<th align="left">短信验证码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input id="yz_code" name="yz_code"   type="yz_code" class="pw"   style="width:60%; height:30px; line-height:normal;">&nbsp;&nbsp;<input id="get_yz_code" type="button"  style="background: #0099e5;width: 30%;font-size: 12px;border: 0;border-radius: 5px;color: #fff;height: 30px;text-align: center; cursor:pointer;" value="获取验证码"  /></td>
			</tr>
            
            
  
			<tr>
				<td align="center" width="100%">
				<input name="" value="注册" type="button" id="submit" tabindex="6" data-disabled="false" style=" padding:5px; background:#0099e5; color:#fff;width:100%; line-height:25px; cursor:pointer; font-weight:bold; border:none" class="pw" onclick="return submit_register_data_instead('REGISTER1');" />
			  </td>
			</tr>
            
          
            
			</table> 
			 </form>
		
</div>


<script>
$("#get_yz_code").click(function(){

	sendMobileCode($("#mobile"), $("#yz_code"), $("#get_yz_code"));

		});
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
	countdown(sendButton);
			// 发送邮件
			var url = 'user.php?act=getcodes';
			$.post(url, {
				mobile: mobileObj.val()
			}, function(result) {
				if (result == 'ok') {
					// 倒计时
					/*countdown(sendButton);*/
				} else {
					alert(result);
				}
			}, 'text');
}
var wait = 60;
function countdown(obj, msg) {
	obj = $(obj);

	if (wait == 0) {
		obj.removeAttr("disabled");
		obj.val(msg);
		wait = 60;
	} else {
		if (msg == undefined || msg == null) {
			msg = obj.val();
		}
		obj.attr("disabled", "disabled");
		obj.val(wait + "秒后重新获取");
		wait--;
		setTimeout(function() {
			countdown(obj, msg)
		}, 1000)
	}
}


</script>

</body>
</html>