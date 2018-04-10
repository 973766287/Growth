<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>软键盘</title>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">  
<script type="text/javascript" src="jquery-1.7.js"></script>
<style type="text/css">
.softkeyboard{ display:inline-block;}
.softkeyboard td{ padding:4px;}
.c_panel{ background-color:#333; text-align:center; padding:15px;}
.input_cur{ border:1px solid #f00;}
.i_button{ border:none; height:40px; width:50px; font-size:16px; font-family:"微软雅黑"; background-color:#666; color:#fff;}
.i_button:active{ background-color:#999;}
.i_button_num{}
.i_button_btn{ float:right; width:88px;}
.i_button_bs{ float:right; width:148px;}
</style>
</head>
<body>

<form action="<?php echo ADMIN_URL;?>mycart.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">

订单号：<input type="text" name="ordersn" value="<? echo "WX".date('Ymd').time()?>" readonly>   <div>订单详情</div>
<input type="text" name="text1" class="shuru input_cur" ><br><br><br>
<script>
//定义当前是否大写的状态 
var CapsLockValue=0; 
var curEditName;
var check; 
//document.write (' <DIV align=center id=\"softkeyboard\" name=\"softkeyboard\" style=\"position:absolute; left:300px; top:320px; width:517px; z-index:180;display:none\">'); 
document.write (' <DIV class=\"softkeyboard\" id=\"softkeyboard\" name=\"softkeyboard\" style=\"display:; \">'); 

//document.write (' ------数字----'); 
document.write (' <div class=\"c_panel shuzi\" id="shuzi">'); 

document.write ('<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">');

document.write (' <tr> '); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'7\');\" value=\" 7 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'8\');\" value=\" 8 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'9\');\" value=\" 9 \"></td>'); 
document.write (' </tr>'); 

document.write (' <tr> '); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'4\');\" value=\" 4 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'5\');\" value=\" 5 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'6\');\" value=\" 6 \"></td>'); 
document.write (' </tr>'); 

document.write (' <tr> '); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'1\');\" value=\" 1 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'2\');\" value=\" 2 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'3\');\" value=\" 3 \"></td>'); 
document.write (' </tr>'); 

document.write (' <tr> '); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'0\');\" value=\" 0 \"></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button onclick=\"addValue(\'.\');\"  value=\" . \" ></td>'); 
document.write (' <td><input class="i_button i_button_num" type=button value=\" ←\" onclick=\"backspace();\"></td>'); 
document.write (' </tr>'); 

document.write (' </table>'); 
 
document.write ('</DIV>'); 
//document.write ('--------------------------------------------'); 



//给输入的密码框添加新值 
function addValue(newValue) 
{ 
	CapsLockValue==0?$(".input_cur").val($(".shuru").val()+ newValue):$(".input_cur").val($(".shuru").val()+ newValue.toUpperCase())
	
	
} 
//清空 
function clearValue() 
{ 
	$(".input_cur").val(""); 
} 
//实现BackSpace键的功能 
function backspace() 
{ 
	var longnum=$(".input_cur").val().length; 
	var num ;
	num=$(".input_cur").val().substr(0,longnum-1); 
	$(".input_cur").val(num); 
} 
function changePanl(oj){
	$("#"+oj).siblings("div").hide();
	$("#"+oj).show();
}

window.onload=function(){
//	changePanl("zimu");
}


</script>



<input value="微信支付" type="submit" align="absmiddle" onclick="return checkvar()" style="width:110px; height:30px; line-height:30px; background:#32a000; font-size:18px; color:#FFFFFF; font-weight:bold; text-align:center; cursor:pointer; float:left"/>

</form>

<script>
function checkvar(){
	pp = $('input[name="text1"]').val(); 
	if(typeof(pp)=='undefined' || pp ==""){
		alert("请输入金额！");
		return false;
	}
	
	

	return true;
}

</script>
</body>
</html>
