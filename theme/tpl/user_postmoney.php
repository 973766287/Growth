<?php
$week= date("w");
 $mshi=date('H');
if($week=="6" or $week=="0" or ($week=="5" and $mshi>="17") or ($week=="1" and $mshi<"09"))

{
?>
	


<script language="javascript">
alert("通知:周六周日不予提现！起始时间周五17:00点至周一9:00点！");

history.back();

</script>
<?php } ?>
<div id="wrap">
	<div class="clear7"></div>
	<?php $this->element('user_menu');?>
    <div class="m_right"  >
		<h2 class="con_title">我要提现</h2>
    	<div class="updatepass">
				 <form name="USERINFO" id="USERINFO" action="" method="post">
					<table width="500" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td align="right" width="100">开户行：</td>
						<td><input  value="<?php echo isset($rts['bankname']) ? $rts['bankname'] : '';?>" name="bankname" type="text"  class="pw"/></td>
					  </tr>
					  <tr>
						<td align="right">手机号：</td>
						<td><input value="<?php echo isset($rts['bankaddress']) ? $rts['bankaddress'] : '';?>" name="bankaddress"  type="text"  class="pw"/></td>
					  </tr>
					  <tr>
						<td align="right">户名：</td>
						<td><input value="<?php echo isset($rts['uname']) ? $rts['uname'] : '';?>" name="uname"  type="text"  class="pw"/></td>
					  </tr>
                                            <tr>
						<td align="right">卡号：</td>
						<td><input  value="<?php echo isset($rts['banksn']) ? $rts['banksn'] : '';?>" name="banksn"  type="text"  class="pw"/></td>
					  </tr>
                                            <tr>
						<td align="right">支付宝：</td>
						<td><input  value="<?php echo isset($rts['alipay']) ? $rts['alipay'] : '';?>" name="alipay"  type="text"  class="pw"/></td>
					  </tr>
                                            <tr>
						<td align="right">微信钱包：</td>
						<td><input  value="<?php echo isset($rts['weixin']) ? $rts['weixin'] : '';?>" name="weixin"   type="text"  class="pw"/></td>
					  </tr>
                                               <tr>
						<td align="right">你的余额：</td>
						<td><input  value="<?php echo isset($mymoney) ? $mymoney : '0.00';?>元" name="banksn"  type="text"  class="pw"/></td>
					  </tr>
                                             <tr>
						<td align="right">提款资金：</td>
						<td><input  name="postmoney"   type="text"  class="pw"/></td>
					  </tr>
                                            <tr>
						<td align="right"> </td>
						<td><span class="returnmes2" style="padding-left:10px; color:#FF0000"></span></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>
						<input type="button" value=""  style=" overflow:hidden ; border:none; background:none; cursor:pointer; background:url(<?php echo $this->img('submit.gif');?>) no-repeat 0 0 ; width:75px; height:24px; " onclick="ajax_postmoney()"/></td>
					  </tr>
					</table>

				</form>
		 </div>
     </div>
    <div class="clear"></div>
</div>
</div>
<div class="clear7"></div>
<script type="text/javascript">
function ajax_postmoney(){
	//passs = $('input[name="pass"]').val();
	money = $('input[name="postmoney"]').val();
	if(parseInt(<?php echo isset($mymoney) ? $mymoney : '0';?>) < 50){
		$('.returnmes2').html('暂时不能为你服务，先赚取50以上佣金再来吧！');
		return false;
	}
	if(money=="" ){
		$('.returnmes2').html('请输入提款金额');
		return false;
	}

	if(confirm('确认信息无误提款吗')){
		
		
		$.post('<?php echo SITE_URL;?>user.php',{action:'ajax_postmoney',money:money,id:'<?php echo $rts['id'];?>'},function(data){ 
			$('.returnmes2').html(data);
		
		});
	}
	return false;
}

</script>