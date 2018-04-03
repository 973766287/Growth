<header class="top_header">收银台</header>

<? if($result['isSuccess'] && !empty($result['data'])){?>
	<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>mycart.php?type=xj_kj_confirm" method="post">
    <input type="hidden" name="bank_no" id="bank_no"  value="<?php echo $rt['bank_no'];?>"/>
    <input type="hidden" name="pay_id" id="pay_id"  value="<?php echo $rt['pay_id'];?>"/>
    <input type="hidden" name="orderNo" id="orderNo"  value="<?php echo $result['data']['orderNo'];?>"/>
<div class="real_box">
  
  
  <dl>
   <dt style="width:120px;">金额</dt>
   <dd><?php echo $result['data']['totalFee']/100;?></dd>
  </dl>
  
  <dl>
   <dt style="width:120px;">银行卡号</dt>
   <dd><?php  echo substr_replace($rt['bank_no'],"****",4,8);?></dd>
  </dl>

  
   <dl>
   <dt style="width:120px;">短信验证码</dt>
   <dd><input style=" width:75%;" name="p_code" id="p_code"  type="text" value=""   placeholder="请输入短信验证码"/></dd>
   
  
   
  </dl>
</div>


<div class="real_sub" id="kj"><a id="payment">确认支付</a></div>
</form>

<script>
 
 
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

<?php }else{?>
	
	<dl>
   <dt style="width:120px;">支付信息</dt>
   <dd><?php echo $result['message'];?></dd>
  
  </dl>
	<?php }?>
