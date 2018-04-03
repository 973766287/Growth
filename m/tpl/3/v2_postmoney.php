<?php $this->element('3/top',array('lang'=>$lang)); ?>

<link rel="stylesheet" href="style.css" type="text/css">
<style>
.txbox{ width:100%; margin:0px auto; overflow:hidden; background:#fff; padding:0 10px; font-size:18px;}
.txbox dl{ overflow:hidden; padding:12px 0; position:relative; border-bottom:1px solid #e6e6e6;}
.txbox dl dt{ position:absolute; width:100px; color:#333; height:30px; line-height:30px;}
.txbox dl dd{ margin-left:90px; color:#999; height:30px; line-height:30px;}
.txbox dl dd input{ border:0;}
.txred{ color:#da251c;}
.fr{ float:right;}
.txbox_con{ padding:10px; color:#999;}
.txgrey{ color:#999; width:75%;}
.tx_blank{ width:20px; height:20px; margin-right:3px; vertical-align:-3px;}
.tx_button{ padding:8px 10px;}
.tx_button a{ background:#ef2226; display:block; border-radius:50px; padding:12px 0; text-align:center; color:#fff; font-size:18px;}
.tx_button a:hover{ /*background:rgba(0,0,0,0.2); color:#999;*/}
</style>

<?php
 // $shi=date('H');
//  $fei = date('i');
// $date1 = date('"Y-m-d H:i:s"');

$shi1 = strtotime($work_time['0']);  $shi2 = strtotime($work_time['1']);
$shi = strtotime(date('H:i',time()));
if(($shi <= $shi1) or ($shi >= $shi2))

{
?>
	


<script language="javascript">
alert("通知:提现起始时间每天<? echo $work_time['0'];?>至<? echo $work_time['1'];?>！");


history.back();

</script>
<?php }else{?>

<form name="USERINFO2" id="USERINFO2" action="<?php echo ADMIN_URL;?>daili.php?action=ajax_postmoney" method="post">
    <input type="hidden" name="key" value="<? echo $key?>"/>
    <input type="hidden" name="token" value="<? echo $token;?>"/>
    <input type="hidden" name="id" value="<? echo $rts['id'];?>"/>
    
<div class="txbox">
 <dl>
   <dt>账户余额</dt>
   <dd><span class="fr txred">元</span><span class="txred"><input style="width:80%;" readonly="" type="text" value="<?php echo isset($mymoney) ? floor($mymoney*100)/100 : '0.00';?>" /></span></dd>
 </dl>
 <dl style="border:0;">
   <dt>提现金额</dt>
   <dd><span class="fr">元</span><input style="width:80%;" name="money"  type="text"   value="<?php echo isset($mymoney) ? floor($mymoney*100)/100 : '0.00';?>" size="30" placeholder="<?php echo isset($mymoney) ? floor($mymoney*100)/100 : '0.00';?>" style="color:#999" /></dd>
 </dl>
</div>
<div class="txbox_con">提现到银行卡</div>
<div class="txbox">
 <dl>
   <dt>用户名</dt>
   <dd><input style="width:80%;" readonly="" type="text" value="<?php echo isset($rts['uname']) ? $rts['uname'] : '';?>" name="uname"  class="pw pws"/></dd>
 </dl>
 <dl style="border:0;">
   <dt style="width:40%;"><img src="../<? echo $rts['pic'];?>"  class="tx_blank"><? echo $rts['name'];?></dt>
   <dd><span class="fr txgrey"><input readonly="" type="text" value="<?php echo substr_replace($rts['banksn'],"******",8,6);?>" name="banksn"  /></span></dd>
 </dl>
</div>
<div class="txbox_con">备注：提现将扣除2元手续费</div>
<div class="tx_button" id="pp"><a id="next" href="javascript:;" onclick="ajax_postmoney();" >确认提交</a></div>


</form>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script type="text/javascript">
function ajax_postmoney(){
	//passs = $('input[name="pass"]').val();
	money = $('input[name="money"]').val();
	key = $('input[name="key"]').val();
	// 	if(money <= 2 ){
	// 	alert('提款金额必须大于2');
	// 	return false;
	// }
	
	// if(<?php echo isset($mymoney) ? $mymoney : '0';?> < money){
	// 	alert('提现金额大于您的最大可提现金额，请重新输入！');
	// 	return false;
	// }
	// if(money=="" ){
	// 	alert('请输入提款金额');
	// 	return false;
	// }
  
  document.getElementById("pp").innerHTML="<a href='javascript:;' style='background:#C3BFBF;' >确认提交</a>";
  


	   if(key != 'weixinssssssss'){
	  $.post('<?php echo ADMIN_URL;?>mycart.php',{action:'update_user_bank_sj',uid:<?php echo $uid;?>,pay_id:12,key:'<? echo $key;?>'},function(data){ 

		  if(data == "success"){
			 
			/* if(confirm('确认信息无误提款吗')){*/
		
			createwindow();
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_postmoney',money:money,id:'<?php echo $rts['id'];?>',key:'<? echo $key;?>'},function(data){ 
		
		if(data == "success"){
			alert("提现成功");
			WeixinJSBridge.call('closeWindow');
			
			}else{
				alert(data);
				WeixinJSBridge.call('closeWindow');
				}
			
		
		});
	/*}*/
	
			  }else{
				   alert(data); 
				  }
		   
		});
		
	   }else{
		   
		   createwindow();
		$.post('<?php echo ADMIN_URL;?>wefu.php',{action:'auto_ajax_postmoney',money:money,id:'<?php echo $rts['id'];?>',key:'<? echo $key;?>'},function(data){ 
		
		if(data == "success"){
			alert("提现成功");
			WeixinJSBridge.call('closeWindow');
			
			}else{
				alert(data);
				WeixinJSBridge.call('closeWindow');
				}
			
		
		});
		   
		   }
		




	
}


function myNumberic(e,len) {
    var obj=e.srcElement || e.target;
    var dot=obj.value.indexOf(".");//alert(e.which);
    len =(typeof(len)=="undefined")?2:len;
    var  key=e.keyCode|| e.which;
    if(key==8 || key==9 || key==46 || (key>=37  && key<=40))//这里为了兼容Firefox的backspace,tab,del,方向键
        return true;
    if (key<=57 && key>=48) { //数字
        if(dot==-1)//没有小数点
            return true;
        else if(obj.value.length<=dot+len)//小数位数
            return true;
        } else if((key==46) && dot==-1){//小数点
            return true;
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
<? }?>