<header class="top_header">收银台</header>

	<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>mycart.php?type=ys_kj_confirm_diy" method="post">
    
    <input type="hidden" name="order_id" id="order_id"  value="<? echo $iid;?>"/>

<div class="real_box">
  
  
  <dl>
   <dt style="width:120px;">金额</dt>
   <dd><? echo $rt['order_amount'];?></dd>
  </dl>
  
  <dl>
   <dt style="width:120px;">银行卡号</dt>
   <dd><?php  echo substr_replace($rt['bank_no'],"****",4,8);?></dd>
  </dl>
 <dl>
   <dt style="width:120px;">预留手机号</dt>
   <dd><?php  echo substr_replace($user_bank['mobile'],"****",3,4);?></dd>
  
  </dl>
  
   <dl>
   <dt style="width:120px;">短信验证码</dt>
   <dd><input style=" width:75%;" name="p_code" id="p_code"  type="text" value=""   placeholder="请输入短信验证码"/></dd>
    <dd style=" width: 160px;"><input id="getcode" type="button"  style="background: #0099e5;width: 90%;font-size: 12px;border: 0;border-radius: 5px;color: #fff;height: 30px;text-align: center;" value="获取验证码"  /></dd>
  
   
  </dl>
  
  <dl style="font-weight: 600;
    font-size: 16px;
    color: #0099e5;">备注：交易实时秒到无需提现，提现费2元/笔</dl>
  

  		   
  		   
  			   
</div>


<div class="real_sub" id="kj"><a id="payment">确认支付</a></div>
</form>

<script>
 
 $('#getcode').click(function () {
	
	countdown($("#getcode"));

	$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'ajax_yisheng_kuaijie',oid:<?php echo $iid;?>},function(data){ 
	
	
	
	
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

 
  $('#payment').click(function () {
	
	
        var p_code = document.getElementById('p_code').value;
	
		  if (p_code.length < 1)
        {
            alert('验证码不能为空！');
            return false;
        }
		
document.getElementById("kj").style.background="#C3BFBF";
document.getElementById("kj").innerHTML="确认支付";

 
 
	$('#myform').submit();

 });
 
</script>
