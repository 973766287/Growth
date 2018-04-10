<header class="top_header">商户银行卡信息登记</header>
    
    
    <div class="real_box">
  
<dl>
   <dt>银行代码：</dt>
   <dd><input type="text" id='bankCode' name='bankCode' value="102" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>账户属性：</dt>
   <dd><input type="text" id='bankaccProp' name='bankaccProp' value="0" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>持卡人姓名：</dt>
   <dd><input type="text" id='name' name='name' value="李四" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>银行卡号：</dt>
   <dd><input type="text" id='bankaccountNo' name='bankaccountNo' value="6212257777777777777" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>银行卡类型：</dt>
   <dd><input type="text" id='bankaccountType' name='bankaccountType' value="1" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>办卡证件类型：</dt>
   <dd><input type="text" id='certCode' name='certCode' value="1" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>证件号码：</dt>
   <dd><input type="text" id='certNo' name='certNo' value="460004777777777777" size=110 maxlength=20></dd>
  </dl>

  			   
</div>

<div class="real_sub" id="save">提交</div>



<script>

    


    $('#save').click(function () {
        var merchantName = document.getElementById('merchantName').value;
        var shortName = document.getElementById('shortName').value;
        var city = document.getElementById('city').value;
        var merchantAddress = document.getElementById('merchantAddress').value;
        var servicePhone = document.getElementById('servicePhone').value;
        var orgCode = document.getElementById('orgCode').value;
     
		 var merchantType = document.getElementById('merchantType').value;
		  var category = document.getElementById('category').value;
		    var corpmanName = document.getElementById('corpmanName').value;
			
			
			var corpmanId = document.getElementById('corpmanId').value;
			var corpmanPhone = document.getElementById('corpmanPhone').value;
			var corpmanMobile = document.getElementById('corpmanMobile').value;
			var corpmanEmail = document.getElementById('corpmanEmail').value;
			var bankCode = document.getElementById('bankCode').value;
			var bankName = document.getElementById('bankName').value;
			var bankaccountNo = document.getElementById('bankaccountNo').value;
			
			var bankaccountName = document.getElementById('bankaccountName').value;
			var autoCus = document.getElementById('autoCus').value;
			var remark = document.getElementById('remark').value;
			

		
				createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_sj2',merchantName:merchantName,shortName:shortName,city:city,merchantAddress:merchantAddress,servicePhone:servicePhone,orgCode:orgCode,merchantType:merchantType,category:category,corpmanName:corpmanName,corpmanId:corpmanId,corpmanPhone:corpmanPhone,corpmanMobile:corpmanMobile,corpmanEmail:corpmanEmail,bankCode:bankCode,bankName:bankName,bankaccountNo:bankaccountNo,bankaccountName:bankaccountName,autoCus:autoCus,remark:remark},function(data){ 
		alert(data);
			removewindow();
			if(data['respCode'] == "000000"){
				 if(confirm("继续商家认证，获取专属收款二维码"))
 {
 document.location.href="<?php echo ADMIN_URL;?>user.php?act=sj_2";
 }else{
				
			WeixinJSBridge.call('closeWindow');
 }
 
 
}
		});
	
	
	
       
    });
	
	
	    


</script>
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
			// 发送邮件
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
