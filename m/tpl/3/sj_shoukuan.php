
<header class="top_header">商家收银</header>
<div class="cashier_order" style="padding:3%;">
  <h3 style="border-bottom: 1px solid #e3e3e3;padding: 10px;">订单号:<? echo "WX".date('Ymd').time()?></h3>


 <h3 style="padding: 10px;">店铺名称:<? echo $shop['s_name'];?></h3>
</div>
<div class="order_con" style="display:none;">
<form action="<?php echo ADMIN_URL;?>mycart.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">
<input type="hidden" name="money" class="shuru input_cur"  value=""/>
<input type="hidden" name="order_sn"  value="<? echo "WX".date('Ymd').time()?>"/>
<input name="pay_id"  id="pay_id" value="" type="hidden">
<input name="uid"  id="uid" value="<? echo $uid;?>" type="hidden">
<input name="bank_no"  id="bank_no" value="" type="hidden">

<input name="supplier_id"  id="supplier_id" value="<? echo $shop['id'];?>" type="hidden">

</form>
</div>
<script type="text/javascript">


$(document).ready(function(){
  $(".order").click(function(){
  $(".order_con").toggle();
  });
});
</script>
<script>
//定义当前是否大写的状态 
var CapsLockValue=0; 
var curEditName;
var check; 

//给输入的密码框添加新值 
function addValue(newValue) 
{ 
	CapsLockValue==0?$(".input_cur").val($(".shuru").val()+ newValue):$(".input_cur").val($(".shuru").val());
	
	document.getElementById("total").innerHTML  =  $(".shuru").val();

} 
//清空 
function clearValue() 
{ 
	$(".input_cur").val(""); 
} 
//实现BackSpace键的功能 
function backspace() 
{ 
	var longnum=$(".input_cur").val().length; 
	var num ;
	num=$(".input_cur").val().substr(0,longnum-1); 
	$(".input_cur").val(num); 
	document.getElementById("total").innerHTML  =  $(".shuru").val();
} 
function changePanl(oj){
	$("#"+oj).siblings("div").hide();
	$("#"+oj).show();
}




</script>
<script type="application/javascript">
window.addEventListener('load', function() {
    var test,i;

    for(i=1;i<=12;i++) {
        test = document.getElementById('b'+i);
        FastClick.attach(test);
    }

}, false);
</script>





<dl class="cashier_con">
  <dt style="padding-top:5%;padding-bottom:3%;"><span>￥</span><span id="total">0.00</dt>
  <dd>
    <div class="cashier_con_num">
      <ul>
        <li><button id="b1" onClick="addValue('3')">3</button></li>
        <li><button id="b2" onClick="addValue('2')">2</button></li>
        <li><button id="b3" onClick="addValue('1')">1</button></li>
        <li><button id="b4" onClick="addValue('6')">6</button></li>
        <li><button id="b5" onClick="addValue('5')">5</button></li>
        <li><button id="b6" onClick="addValue('4')">4</button></li>
        <li><button id="b7" onClick="addValue('9')">9</button></li>
        <li><button id="b8" onClick="addValue('8')">8</button></li>
        <li><button id="b9" onClick="addValue('7')">7</button></li>
        <li><button id="b10" onClick="backspace()"><img src="img/sc.png"></button></li>
        <li><button id="b11" onClick="addValue('.')">.</button></li>
        <li><button id="b12" onClick="addValue('0')">0</button></li>
      </ul>
    </div>
    <div class="cashier_con_button" style="height:340px;background:#28a9e2;">
      <ul>
      
     
       <li style="width:30%;margin-left:35%;margin-top:60%;height:auto;">
   
       <a style="font-size:24px;color:#FFF;line-height: 50px;" href="javascript:void()" onclick="return checkvar(<? echo $rr['pay_id']?$rr['pay_id']:1;?>)">确认支付</a>
      
       </li>
      
     
      </ul> 
    </div>
  </dd>
</dl>
<script>
function checkvar(pay){
	document.getElementById("pay_id").value=pay;
	pp = $('input[name="money"]').val(); 
	if(typeof(pp)=='undefined' || pp =="" || pp <=0){
		alert("请输入金额！");
		return false;
	}
	
	if(pay == 4){
		if(pp < 0.1){
			alert("最少支付金额为0.1元");
		return false;
			
			}
		
		}
		
	//hyhyh 20160909  支付宝
	if(pay == 8){
		if(pp < 0.1){
			alert("最少支付金额为0.1元");
		return false;
			
			}
		
		}
	
	
	if((pay == 3) || (pay == 1)){
			alert("通道升级中......,请选择其他付款方式！");
			return false;
		/*	if(pp < 0.1){
			alert("最少支付金额为0.1元");
		return false;
			
			}*/
		}
	
	
		
		$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'update_user_bank_sj',uid:<?php echo $uid;?>,pay_id:pay},function(data){ 
		   
		 
		  
		  if(data == "success"){
			  document.CONSIGNEE_ADDRESS.submit();
			  }else{
				   alert(data); 
				  }
		   
		});
		
	
}






</script>
