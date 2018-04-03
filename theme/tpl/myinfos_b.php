<div id="wrap">
	<div class="clear7"></div>
	<?php $this->element('user_menu');?>
  <div class="m_right"  >
		<h2 class="con_title">银行卡号资料</h2>
    	<div class="updatepass">
				 <form name="USERINFO" id="USERINFO" action="" method="post">
					<table width="500" border="0" cellpadding="0" cellspacing="0">
					  <tr height="30">
						<td align="right" width="100">开户行：</td>
						<td><input  value="<?php echo isset($rts['bankname']) ? $rts['bankname'] : '';?>" name="bankname" type="text"  class="pw"/></td>
					  </tr>
					  <tr height="30">
						<td align="right">手机号：</td>
						<td><input value="<?php echo isset($rts['bankaddress']) ? $rts['bankaddress'] : '';?>" name="bankaddress"  type="text"  class="pw"/></td>
					  </tr>
					  <tr height="30">
						<td align="right">户名：</td>
						<td><input value="<?php echo isset($rts['uname']) ? $rts['uname'] : '';?>" name="uname"  type="text"  class="pw"/></td>
					  </tr>
                                            <tr height="30">
						<td align="right">卡号：</td>
						<td><input  value="<?php echo isset($rts['banksn']) ? $rts['banksn'] : '';?>" name="banksn"  type="text"  class="pw"/></td>
					  </tr>
                                            <tr height="30">
						<td align="right">支付宝：</td>
						<td><input  value="<?php echo isset($rts['alipay']) ? $rts['alipay'] : '';?>" name="alipay"  type="text"  class="pw"/></td>
					  </tr>
                                            <tr height="30">
						<td align="right">微信钱包：</td>
						<td><input  value="<?php echo isset($rts['weixin']) ? $rts['weixin'] : '';?>" name="weixin"   type="text"  class="pw"/></td>
					  </tr>
                                       <tr height="30">
						<td><span class="returnmes2" style="padding-left:10px; color:#FF0000"></span></td>
						<td>
						</td>
					  </tr>
					  <tr height="30">
						<td> </td>
						<td>
						<input type="button" value=""  style=" overflow:hidden ; border:none; background:none; cursor:pointer; background:url(<?php echo $this->img('submit.gif');?>) no-repeat 0 0 ; width:75px; height:24px; " onclick="return update_user_bank();"/></td>
					  </tr>
					</table>

				</form>
		 </div>
   </div>
    <div class="clear"></div>
</div>

<div class="clear7"></div>
<script type="text/javascript">
function update_user_bank(){
	//passs = $('input[name="ppass"]').val();
	banknames = $('input[name="bankname"]').val();
	bankaddresss = $('input[name="bankaddress"]').val();
	unames = $('input[name="uname"]').val();
	banksns = $('input[name="banksn"]').val();
        alipay = $('input[name="alipay"]').val();
        weixin = $('input[name="weixin"]').val();

	if( (banknames=="" || unames=="" || banksns=="") &&alipay=='' && weixin==''){
		$('.returnmes2').html('请输入完整信息');
		return false;
	}

	if(confirm('确认修改吗')){
		//createwindow();
		
		$.post('<?php echo SITE_URL;?>user.php',{action:'update_user_bank',bankname:banknames,bankaddress:bankaddresss,uname:unames,banksn:banksns,alipay:alipay,weixin:weixin},function(data){ 
			$('.returnmes2').html(data);
			//removewindow();
		});
	}
	return false;
}

function update_user_pass2(){
	passs = $('input[name="pass"]').val();
	newpasss = $('input[name="newpass"]').val();
	rpnewpasss = $('input[name="rpnewpass"]').val();
	if(passs=="" || newpasss=="" || newpasss==""){
		$('.returnmes').html('请输入完整信息');
		return false;
	}
	if(newpasss!=rpnewpasss){
		$('.returnmes').html('密码与确认密码不一致');
		return false;
	}
	if(confirm('确认修改吗')){
		createwindow();
		
		$.post('<?php echo SITE_URL;?>daili.php',{action:'update_user_pass',pass:passs,newpass:newpasss,rpnewpass:rpnewpasss},function(data){ 
			$('.returnmes').html(data);
			removewindow();
		});
	}
	return false;
}
function ajax_open_dailiapply(tt){
	if(tt==true){
		ty = '1';
	}else{
		ty = '2';
	}
	$.post('<?php echo SITE_URL;?>daili.php',{action:'ajax_open_dailiapply',ty:ty},function(data){ 
		
	});
}
</script>