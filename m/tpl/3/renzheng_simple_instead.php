<style>
.real_box dl dt{
	width:100px;
	}
	.real_box dl dd input {
    border: 0px;
    background: none;
    width: 100%;
    font-size: 15px;
	}
	.real_box dl dd{
	width:40%;
	}
</style>
<header class="top_header">商户认证(网页)</header>
	<form name="USERINFO2" id="USERINFO2" action="" method="post">
  
<div class="real_box">

<dl>
   <dt>店铺名称</dt>
   <dd><input name="shop_name" id="shop_name"   type="text"  value="<?php  echo $rts['shop_name'];?>" placeholder="显示给付款人"></dd>
  </dl>
  
  <!---<dl>
   <dt>店铺地址</dt>
   <dd><input name="address" id="address"   type="text"  value="<?php  echo $rts['address'];?>" placeholder="店铺实际营业地址"></dd>
  </dl>
  -->
  
  <dl>
   <dt>法人姓名</dt>
   <dd><input name="name" id="name"   type="text"  value="<?php  echo $rts['uname'];?>" placeholder="中文姓名"></dd>
  </dl>

  <dl>
   <dt>身份证号码</dt>
   <dd><input name="card_no" id="card_no"   type="text" value="<?php  echo $rts['idcard'];?>"  <?php if(!empty($rts['idcard'])){?>readonly="readonly"<?php }?>   placeholder="法人有效身份证号码"></dd>
  </dl>
  
   <dl>
   <dt>结算卡号</dt>
   <dd><input name="bank_no" id="bank_no"   type="text" value="<?php  echo $rts['banksn'];?>"   placeholder="收款结算银行卡号"></dd>
  </dl>
  
   <dl>
   <dt>开户行</dt>
   <dd><select style="border:0;" class="real_select" name="bank_code" id="bank_code">
    <?  foreach($bank as $row){?>
            <option class="opt" value="<? echo $row['id']?>" <? if ($rts['bank'] == $row['id']){?>selected <? }?>><? echo $row['name'];?></option>
          
            <? }?>

   </select></dd>
  </dl>
  
  <dl>
   <dt>手机号码</dt>
   <dd><input name="mobile" id="mobile" maxlength="11" type="text" value="<?php  echo $rts['mobile'];?>" placeholder="银行卡预留手机号"></dd>
  </dl>
  
  
 
 
  		   <dl>
   <dt>短信验证码</dt>
   <dd><input id="yz_code" name="yz_code"   type="yz_code"  placeholder="请输入手机验证码" ></dd>
   <dd><input id="get_yz_code" type="button"  style="background: #0099e5;width: 90%;font-size: 12px;border: 0;border-radius: 5px;color: #fff;height: 30px;text-align: center; cursor:pointer;" value="获取验证码"  /></dd>
  </dl>
  			   
</div>


<div class="real_sub" id="save">提交审核</div>
</form>

<script>



    $('#save').click(function () {
		
		var shop_name = document.getElementById('shop_name').value;
		var address = "默认地址";
		 var name = document.getElementById('name').value;
        var card_no = document.getElementById('card_no').value;
        var bank_no = document.getElementById('bank_no').value;
		 var bank_code = document.getElementById('bank_code').value;
        var mobile = document.getElementById('mobile').value;
        var yz_code = document.getElementById('yz_code').value;
     
			
		
		var aCity={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"} 

    var iSum=0 ;
    var info="" ;
  

		if(shop_name.length < 1)
        {
            alert('请填写店铺名称！');
            return false;
        }  
		else if (address.length < 1)
        {
            alert('请填写店铺地址！');
            return false;
        }
		else if (name.length < 1)
        {
            alert('请填写姓名！');
            return false;
        }
        else if (card_no.length < 15)
        {
            alert('请填写身份证号码！');
            return false;
        }
	
			  if(!/^\d{17}(\d|x)$/i.test(card_no)){
				   alert('你输入的身份证长度或格式错误！');
				   return false; 
			  }
    card_nos=card_no.replace(/x$/i,"a"); 
    if(aCity[parseInt(card_nos.substr(0,2))]==null){
		 alert('你的身份证地区非法！');
		 return false; 
	}
    sBirthday=card_nos.substr(6,4)+"-"+Number(card_nos.substr(10,2))+"-"+Number(card_nos.substr(12,2)); 
    var d=new Date(sBirthday.replace(/-/g,"/")) ;
	
    if(sBirthday!=(d.getFullYear()+"-"+ (d.getMonth()+1) + "-" + d.getDate())){
		 alert('身份证上的出生日期非法！');
		return false; 
	}
	
    for(var i = 17;i>=0; i--) iSum += (Math.pow(2,i) % 11) * parseInt(card_nos.charAt(17 - i),11) ;
	
    if(iSum%11!=1){
		 alert('你输入的身份证号非法！');
		 return false; 
	}
	
	
		
		
	
        else if (bank_no.length < 10)
        {
            alert('请填写银行卡号！');
            return false;
        }
        else if (mobile.length < 10)
        {
            alert('请填写手机号码！');
            return false;
        }
		else if (yz_code.length < 6)
        {
            alert('请填写手机验证码！');
            return false;
        }
		else{
		
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_bank_simple_instead',shop_name:shop_name,address:address,name:name,card_no:card_no,bank_no:bank_no,bank_code:bank_code,mobile:mobile,yz_code:yz_code},function(data){ 
		//alert(data);
			if(data == "提交成功"){	
			
			  alert("审核通过！");
			window.location.href='<?php echo ADMIN_URL;?>user.php?act=Instead';
			}else{
				alert(data);
				}
		});
	
	
	
        }
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
