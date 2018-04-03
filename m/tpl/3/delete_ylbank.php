<header class="top_header">解绑银联卡</header>
	<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>mycart.php?type=delete_ylbank_confirm" method="post">
<div class="real_box">
  
  
  <dl>
   <dt style="width:120px;">持卡人姓名</dt>
   <dd><?php  echo $card['name'];?></dd>
  </dl>
  
  <dl>
   <dt style="width:120px;">身份证号</dt>
   <dd><?php  echo $card['idcard'];?></dd>
  </dl>
  
  
  
  
  <dl>
   <dt style="width:120px;">银行卡号</dt>
   <dd><?php  echo substr_replace($card['bank_no'],"****",4,8);?></dd>
  </dl>
  
  
  
  
  <dl>
   <dt style="width:120px;">预留手机号</dt>
   <dd><?php  echo substr_replace($card['mobile'],"****",3,4);?></dd>
  
  </dl>
  
   <dl>
   <dt style="width:120px;">短信验证码</dt>
   <dd><input style=" width:75%;" name="p_code" id="p_code"  type="text" value=""   placeholder="请输入6位短信验证码"/></dd>
   
   <dd style=" width: 160px;"><input id="getcode" type="button"  style="background: #0099e5;width: 90%;font-size: 12px;border: 0;border-radius: 5px;color: #fff;height: 30px;text-align: center;" value="获取验证码"  /></dd>
   
  </dl>
  
  		   
  		   
  			   
</div>


<div class="real_sub" id="save">提交</div>
</form>

<script>
  $('#getcode').click(function () {
	
	countdown($("#getcode"));
	
	 var uid = document.getElementById("uid").value;
	 var oriReqMsgId = document.getElementById("oriReqMsgId").value;
	 var merchantId = document.getElementById("merchantId").value;
	  var pay_address = document.getElementById("pay_address").value;
	$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'getcode',uid:uid,oriReqMsgId:oriReqMsgId,merchantId:merchantId,pay_address:pay_address},function(data){ 
	
	
	
	
	if (data == 'ok') {
					// 倒计时
					//countdown($("#getcode"));
				} else {
					alert(data);
				}
	
 });
 });
 
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


 $('#save').click(function () {
	
	
        var p_code = document.getElementById('p_code').value;
	
		  if (p_code.length < 1)
        {
            alert('验证码不能为空！');
            return false;
        }
		

 
	$('#myform').submit();

 });
 
 
</script>
