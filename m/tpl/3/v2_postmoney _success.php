<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<?php $this->element('3/top',array('lang'=>$lang)); ?>

<style type="text/css">
.pw,.pwt{
height:26px; line-height:normal;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pws{ background:#ededed}
</style>
<div id="main" style="min-height:300px">
	<div style="background:#f5f5f5; border-bottom:1px solid #d1d1d1;padding:10px;">
	<form name="USERINFO2" id="USERINFO2" action="<?php echo ADMIN_URL;?>daili.php" method="post">
    <input type="hidden" name="key" value="<? echo $key?>"/>
    <input type="hidden" name="token" value="<? echo $token;?>"/>
    <input type="hidden" name="id" value="<? echo $rts['id'];?>"/>
    <input type="hidden" name="action" value="ajax_postmoney"/>
  
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:30px;">
                       <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">户名：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input readonly="" type="text" value="<?php echo isset($rts['uname']) ? $rts['uname'] : '';?>" name="uname"  class="pw pws"/></td>
		  </tr>
                  
                  
		   <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">开户行：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input readonly="" type="text" value="<?php echo isset($rts['bankname']) ? $rts['bankname'] : '';?>" name="bankname"  class="pw pws"/></td>
		  </tr>
                    <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">卡号：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input readonly="" type="text" value="<?php echo isset($rts['banksn']) ? $rts['banksn'] : '';?>" name="banksn"  class="pw pws"/></td>
		  </tr>
                    <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">身份证号：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input readonly="" type="text" value="<?php echo isset($rts['idcard']) ? $rts['idcard'] : '';?>" name="idcard"  class="pw pws"/></td>
		  </tr>
		  <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">手机号：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input readonly="" type="text" value="<?php echo isset($rts['mobile']) ? $rts['mobile'] : '';?>" name="bankaddress"  class="pw pws"/></td>
		  </tr>
		
		 
		  <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">你的余额：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input readonly="" type="text" value="<?php echo isset($mymoney) ? floor($mymoney*100)/100 : '0.00';?>元" name="banksn"  class="pw pws"/></td>
		  </tr>

		  <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> 提款资金：</td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input type="text" value="" name="money"  class="pw" style="width:50%"/>元</td>
		  </tr>
		  	  <tr>
		  	  <td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> 提现须知：</td>
		  	  <td width="75%" align="left" style="padding-bottom:2px;">
		  	  <span style="color:#FF0000">提现将会扣除1元手续费，<br/>请务必等待系统自动跳转，<br/>不要返回或者刷新页面</span>
		  	  </td>
		  	  </tr>

		  <tr>
			<td align="center" style="padding-top:10px;" colspan="2">
			<a id="next" href="javascript:;" onclick="ajax_postmoney();" style="border-radius:5px;display:block;background:#3083CE;cursor:pointer;width:140px; height:25px; line-height:25px; font-size:14px; color:#FFF">确认提交</a><span class="returnmes2" style="padding-left:10px; color:#FF0000"></span>
			</td>
		  </tr>
		</table>
	</form>
	</div>

</div>
<script type="text/javascript">
function ajax_postmoney(){
	//passs = $('input[name="pass"]').val();
	money = $('input[name="money"]').val();
		if(money <= 1 ){
		$('.returnmes2').html('提款金额必须大于1');
		return false;
	}
	
	if(<?php echo isset($mymoney) ? $mymoney : '0';?> < money){
		$('.returnmes2').html('提现金额大于您的最大可提现金额，请重新输入！');
		return false;
	}
	if(money=="" ){
		$('.returnmes2').html('请输入提款金额');
		return false;
	}
  document.getElementById('next').style.display='none';
$('form').submit();
	/*
	if(confirm('确认信息无误提款吗')){
		createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_postmoney',money:money,id:'<?php echo $rts['id'];?>',key:'<? echo $key;?>'},function(data){ 
			window.location.href="<?php echo ADMIN_URL;?>user.php";
			removewindow();
		});
	}
	return false;*/
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
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_pass',pass:passs,newpass:newpasss,rpnewpass:rpnewpasss},function(data){ 
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
	$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_open_dailiapply',ty:ty},function(data){ 
		
	});
}
</script>
