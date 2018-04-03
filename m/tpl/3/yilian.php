<?php $this->element('3/top',array('lang'=>$lang)); ?>
<div id="main" style="padding-top:0px; min-height:300px">
	<div class="checkout">
	
		
		<div class="real_box">
        <? if($rt['mobile']){?>
  <dl>
   <dt>手机号</dt>
   <dd class="grey"><? echo $rt['mobile'];?></dd>
  </dl>
  <? }?>
   <? if($rt['consignee']){?>
  <dl>
   <dt>姓名</dt>
   <dd class="grey"><? echo $rt['consignee'];?></dd>
  </dl>
  <? }?>
   <? if($rt['address']){?>
  <dl>
   <dt>地址   </dt>
   <dd class="grey"><? echo $rt['address'];?></dd>
  </dl>
  <? }?>
    <dl>
   <dt>金额   </dt>
   <dd class="grey"><? echo $rt['order_amount'];?>元</dd>
  </dl>
  
  <dl>
   <dt>支付方式</dt>
   <dd class="grey"><? echo $rt['pay_name'];?></dd>
  </dl>
  
  

<? if($pay_id == 4){?>

<form action="https://epay.gaohuitong.com:8443/entry.do" target="_blank" method="post" name="CONSIGNEE_ADDRESSWIN" id="CONSIGNEE_ADDRESSWIN">

  <table style="display:none;">
<tbody>
<tr><td align="right">业务编码(支付)：</td><td><input type="hidden" id="busi_code" name="busi_code" value="PAY" size="110" maxlength="20"></td> </tr>
<tr><td align="right">商户编号：</td><td><input type="hidden" id="merchant_no" name="merchant_no" value="<? echo $rts['pay_no'];?>" size="110" maxlength="20"></td> </tr>
<tr><td align="right">终端编号：</td><td><input type="hidden" id="terminal_no" name="terminal_no" value="<? echo $rts['pay_idt'];?>" size="110" maxlength="20"></td> </tr>
<tr><td align="right">商家订单号：</td><td><input type="hidden" id="order_no" name="order_no" value="<? echo $rt['order_sn'];?>" size="110"></td> </tr>
<tr><td align="right">订单金额：</td><td><input type="hidden" id="amount" name="amount" value="<? echo $rt['order_amount'];?>" size="110" maxlength="20"></td> </tr>
<!--<tr><td align="right">支付网关地址：</td><td><input type="hidden" id="gateway_url" name="gateway_url" value="https://epay.gaohuitong.com:8443/entry.do" size="110" maxlength="200"></td>  </tr>-->
<tr><td align="right">即时返回URL：</td><td><input type="hidden" id="return_url" name="return_url" value="<? echo $rts['pay_address'];?>" size="110" maxlength="200"></td>  </tr>
<tr><td align="right">异步通知URL：</td><td><input type="hidden" id="notify_url" name="notify_url" value="<? echo $rts['pay_address'];?>" size="110" maxlength="200"></td>  </tr>
<tr><td align="right">签名算法：</td><td><select id="sign_type" name="sign_type" size="1"><option selected="" value="SHA256">SHA256</option><option value=""></option></select></td></tr>



<tr><td align="right">商品名称：</td><td><input type="hidden" id="product_name" name="product_name" value="人人嘀支付" size="110" maxlength="60"></td> </tr> 
<!--<tr><td align="right">商品类型：</td><td><input type="hidden" id="product_type" name="product_type" value="图书" size="110" maxlength="60"></td> </tr>--> 
<tr><td align="right">商品描述：</td><td><input type="hidden" id="product_desc" name="product_desc" value="人人嘀" size="110" maxlength="60"></td> </tr> 



<tr><td align="right">签名：</td><td><input type="hidden" name="sign" 
value="<? echo hash('sha256', 'amount='.$rt['order_amount'].'&busi_code=PAY&currency_type=CNY&merchant_no='.$rts['pay_no'].'&notify_url='.$rts['pay_address'].'&order_no='.$rt['order_sn'].'&product_desc=人人嘀&product_name=人人嘀支付&return_url='.$rts['pay_address'].'&sett_currency_type=CNY&sign_type=SHA256&terminal_no='.$rts['pay_idt'].'&key='.$rts['pay_code']);?>"/></td> </tr> 

<!--<tr><td align="right">银行卡号：</td><td><input type="hidden" id="user_bank_card_no" name="user_bank_card_no" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">备注：</td><td><input type="hidden" id="memo" name="memo" value="隐藏的画册" size="110" maxlength="60"></td> </tr> 

<tr><td align="right">用户名称：</td><td><input type="hidden" id="user_name" name="user_name" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">用户证件类型：</td><td><input type="hidden" id="user_cert_type" name="user_cert_type" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">用户证件号码：</td><td><input type="hidden" id="user_cert_no" name="user_cert_no" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">用户手机号：</td><td><input type="hidden" id="user_mobile" name="user_mobile" value="" size="110" maxlength="60"></td> </tr> 
-->
<tr><td align="right">币种：</td><td>
<select id="currency_type" name="currency_type" size="1">
<option selected="" value="CNY">人民币</option> 
<option value="HKD">港币</option>
<option value="USD">美元</option>
<option value="">不指定</option>
</select>
</td></tr>
<tr><td align="right">清算币种：</td><td>
<select id="sett_currency_type" name="sett_currency_type" size="1">
<option selected="" value="CNY">人民币</option> 
<option value="HKD">港币</option>
<option value="USD">美元</option>
<option value="">不指定</option>  
</select>
</td></tr>

 </tbody></table>
 
 
	</form>











<? }else{?>

<form action="https://epay.gaohuitong.com:8443/entry.do" target="_blank" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">

  <table style="display:none;">
<tbody>
<tr><td align="right">业务编码(支付)：</td><td><input type="hidden" id="busi_code" name="busi_code" value="PAY" size="110" maxlength="20"></td> </tr>
<tr><td align="right">商户编号：</td><td><input type="hidden" id="merchant_no" name="merchant_no" value="<? echo $rts['pay_no'];?>" size="110" maxlength="20"></td> </tr>
<tr><td align="right">终端编号：</td><td><input type="hidden" id="terminal_no" name="terminal_no" value="<? echo $rts['pay_idt'];?>" size="110" maxlength="20"></td> </tr>
<tr><td align="right">商家订单号：</td><td><input type="hidden" id="order_no" name="order_no" value="<? echo $rt['order_sn'];?>" size="110"></td> </tr>
<tr><td align="right">订单金额：</td><td><input type="hidden" id="amount" name="amount" value="<? echo $rt['order_amount'];?>" size="110" maxlength="20"></td> </tr>
<!--<tr><td align="right">支付网关地址：</td><td><input type="hidden" id="gateway_url" name="gateway_url" value="https://epay.gaohuitong.com:8443/entry.do" size="110" maxlength="200"></td>  </tr>-->
<tr><td align="right">即时返回URL：</td><td><input type="hidden" id="return_url" name="return_url" value="<? echo $rts['pay_address'];?>" size="110" maxlength="200"></td>  </tr>
<tr><td align="right">异步通知URL：</td><td><input type="hidden" id="notify_url" name="notify_url" value="<? echo $rts['pay_address'];?>" size="110" maxlength="200"></td>  </tr>
<tr><td align="right">签名算法：</td><td><select id="sign_type" name="sign_type" size="1"><option selected="" value="SHA256">SHA256</option><option value=""></option></select></td></tr>



<tr><td align="right">商品名称：</td><td><input type="hidden" id="product_name" name="product_name" value="人人嘀支付" size="110" maxlength="60"></td> </tr> 
<!--<tr><td align="right">商品类型：</td><td><input type="hidden" id="product_type" name="product_type" value="图书" size="110" maxlength="60"></td> </tr>--> 
<tr><td align="right">商品描述：</td><td><input type="hidden" id="product_desc" name="product_desc" value="人人嘀" size="110" maxlength="60"></td> </tr> 



<tr><td align="right">签名：</td><td><input type="hidden" name="sign" 
value="<? echo hash('sha256', 'amount='.$rt['order_amount'].'&busi_code=PAY&currency_type=CNY&merchant_no='.$rts['pay_no'].'&notify_url='.$rts['pay_address'].'&order_no='.$rt['order_sn'].'&product_desc=人人嘀&product_name=人人嘀支付&return_url='.$rts['pay_address'].'&sett_currency_type=CNY&sign_type=SHA256&terminal_no='.$rts['pay_idt'].'&key='.$rts['pay_code']);?>"/></td> </tr> 

<!--<tr><td align="right">银行卡号：</td><td><input type="hidden" id="user_bank_card_no" name="user_bank_card_no" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">备注：</td><td><input type="hidden" id="memo" name="memo" value="隐藏的画册" size="110" maxlength="60"></td> </tr> 

<tr><td align="right">用户名称：</td><td><input type="hidden" id="user_name" name="user_name" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">用户证件类型：</td><td><input type="hidden" id="user_cert_type" name="user_cert_type" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">用户证件号码：</td><td><input type="hidden" id="user_cert_no" name="user_cert_no" value="" size="110" maxlength="60"></td> </tr> 
<tr><td align="right">用户手机号：</td><td><input type="hidden" id="user_mobile" name="user_mobile" value="" size="110" maxlength="60"></td> </tr> 
-->
<tr><td align="right">币种：</td><td>
<select id="currency_type" name="currency_type" size="1">
<option selected="" value="CNY">人民币</option> 
<option value="HKD">港币</option>
<option value="USD">美元</option>
<option value="">不指定</option>
</select>
</td></tr>
<tr><td align="right">清算币种：</td><td>
<select id="sett_currency_type" name="sett_currency_type" size="1">
<option selected="" value="CNY">人民币</option> 
<option value="HKD">港币</option>
<option value="USD">美元</option>
<option value="">不指定</option>  
</select>
</td></tr>

<!--<tr><td align="right">B2C银行卡类型：</td><td>
<select id="service_type" name="service_type" size="1">
<option value="">不指定</option>
<option value="B00101">储蓄卡</option>
<option value="B00102" selected="selected">信用卡</option>
</select>
</td></tr>-->

<!--<tr valign="top"><td align="right">指定银行：</td><td>
<select id="bank_code" name="bank_code" size="10">
<option selected="" value="">不指定</option>
<option value="ICBC">工商银行</option>
<option value="CMB">招商银行</option>
<option value="ABC">农业银行</option>
<option value="CCB">建设银行</option>
<option value="COMM">交通银行</option>
<option value="BOC">中国银行</option>
<option value="SPDB">浦东发展银行</option>
<option value="CMBC">民生银行</option>
<option value="SPABANK">深圳发展银行</option>
<option value="GDB">广东发展银行</option>
<option value="CIB">兴业银行</option>
<option value="CEBBANK">光大银行</option>
<option value="CITIC">中信银行</option>
<option value="HXBANK">华夏银行</option>
<option value="SPABANK">平安银行</option>
<option value="POSTGC">中国邮政储蓄银行</option>
<option value="SHB">上海银行</option>
<option value="BCCB">北京银行</option>
<option value="BJRCB">北京农村商业银行</option>

<option value="ICBCB2B">工商银行企业网银</option>
<option value="BOCB2B">中国银行企业网银</option>
<option value="ABCB2B">农行银行企业网银</option>
<option value="CCBB2B">建行银行企业网银</option>
<option value="CMBB2B">招行银行企业网银</option>
<option value="CEBB2B">光大银行企业网银</option>
<option value="SPABANKB2B">平安银行企业网银</option>
<option value="SPDBB2B">上海浦东发展银行企业网银</option>
<option value="COMMB2B">交通银行企业网银</option>

<option value="ICBCQBY">工商银行快捷</option>
<option value="CMBQBY" selected="selected">招商银行快捷</option>
<option value="ABCQBY">农业银行快捷</option>
<option value="CCBQBY">建设银行快捷</option>
<option value="COMMQBY">交通银行快捷</option>
<option value="BOCQBY">中国银行快捷</option>
<option value="SPDBQBY">浦东发展银行快捷</option>
<option value="CMBCQBY">民生银行快捷</option>
<option value="SPABANKQBY">深圳发展银行快捷</option>
<option value="GDBQBY">广东发展银行快捷</option>
<option value="CIBQBY">兴业银行快捷</option>
<option value="CEBBANKQBY">光大银行快捷</option>

<option value="ALIPAY">支付宝</option>

</select>
 </td></tr>-->

 </tbody></table>
 
 
	</form>
  <? }?>
 
<div class="real_sub"><a href="#" onclick="paySubmit()">确认</a></div>
</div>
        
        
        
 
	</div>
</div>
<script>
function paySubmit(){
	
	if(<? echo $pay_id;?> == 4){
	document.CONSIGNEE_ADDRESSWIN.submit();
	}else{
		document.CONSIGNEE_ADDRESS.submit();
		}
	}
</script>
