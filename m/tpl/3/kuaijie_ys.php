<header class="top_header">收银台</header>

<? if(!empty($result['response']['errCode']) && $result['response']['errCode']=='00' ){?>
	<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>mycart.php?type=ys_kj_confirm" method="post">
    
    <input type="hidden" name="order_id" id="order_id"  value="<? echo $result['request']['order_id'];?>"/>
     <input type="hidden" name="pay_id" id="pay_id"  value="<? echo $rt['pay_id'];?>"/>
    <input type="hidden" name="uid" id="uid" value="<? echo $rt['user_id'];?>"/>
    <input type="hidden" name="partner" id="partner" value="<? echo $rts['pay_idt'];?>"/>
    <input type="hidden" name="merchant_id" id="merchant_id" value="<? echo $rts['pay_no'];?>"/>
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
   <dt style="width:120px;">短信验证码</dt>
   <dd><input style=" width:75%;" name="p_code" id="p_code"  type="text" value=""   placeholder="请输入短信验证码"/></dd>
   
  
   
  </dl>
  
  <dl style="font-weight: 600;
    font-size: 16px;
    color: #0099e5;">备注：交易实时秒到无需提现，提现费2元/笔</dl>
  

  		   
  		   
  			   
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

<?php }else{
	if(!empty($result['response']['errCode'])){
	?>

<dl>
   <dt style="width:120px;">支付信息</dt>
   <dd><? echo $result['response']['errCode'];?></dd>
  
  </dl>
<?php }else{?>
	<dl>
   <dt style="width:120px;">支付信息</dt>
   <dd><? echo $result['error_msg'];?></dd>
  
  </dl>
	<?php }}?>
