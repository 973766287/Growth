<header class="top_header">收银</header>

<?php echo $this->css(array('shoukuan.css'));?>

<div class="cashier_order"><span class="order"><img src="img/sy_xg_03.png" ></span></span>

  <h3>订单号</h3>

<? echo "QZ".date('Ymd').time()?></div>

<div class="order_con" style="display:none;">

<form action="<?php echo ADMIN_URL;?>mycart.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">

<input type="hidden" name="money" class="shuru input_cur"  value=""/>

<input type="hidden" name="order_sn"  value="<? echo "QZ".date('Ymd').time()?>"/>

<input name="pay_id"  id="pay_id" value="" type="hidden">

<input name="bank_no"  id="bank_no" value="" type="hidden">

<input name="openid"  id="openid" value="<? echo $openid;?>" type="hidden">



  <input  type="text" name="consignee"   value="" size="30" placeholder="请输入您姓名" />

  

  <input  type="text" name="mobile"   value="" size="30" placeholder="请输入您的手机号" />

  

  <input  type="text" name="address"   value="" size="30"  placeholder="请输入您的地址"/>

  

  

  

  

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









<div class="yl_choose">

				<ul>

					<li style="background:#0099e5;color:#FFF;">选择银联支付方式 <span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>

                  



                  

                <?php if($uid == 1){?>   

                    <li><a href="#" onclick="return checkvar_xj(26)">
<p><h3>银联快捷（星洁商旅类秒到）</h3></p>

单笔交易限额100~20000元                

                    </a></li>
                    
                     <li><a href="#" onclick="return checkvar_api(3)">

                   

                   

<p><h3>银联快捷（商旅类秒到）</h3></p>

单笔交易限额100~20000元                

                    </a></li>
                 
                    
                    <?php }else{?>
                     <li><a href="#" onclick="return checkvar_api(<? echo $rr['pay_id_yl']?$rr['pay_id_yl']:1;?>)">               

<p><h3>通道一</h3></p>

单笔交易限额100~20000元                

                    </a></li>
                    
                    <?php }?>

					

                     <?php if($uid == 111111111111){?>      

                  <li><a href="#" onclick="return checkvars_jiaofei_hq(23)">

                    

                     <p><h3>银联快捷（缴费类秒到）</h3></p>

单笔交易限额500~10000元          

                    </a></li>
                   <?php }else{?>
                   <li><a href="#" onclick="return checkvars_jiaofei_hq(<? echo $rr['pay_id_yl_h5']?$rr['pay_id_yl_h5']:1;?>)">

                    

                     <p><h3>通道二</h3></p>

单笔交易限额800~20000元          

                    </a></li>
 
                   <?php }?>                  

                    

				</ul>

			</div>

            
<div class="payment_time_masks_hq" style="display:none;">
				<ul>
					<li style="background:#0099e5;color:#FFF;">选择银行卡支付 <span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>
                    <? foreach($card_h5_hq as $row){?>
					<li onclick="tijiao_h5_hq(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>
				<? }?>
					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5_hq"><li>+添加新卡支付</li></a>
					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_list_h5_hq"><li>-卡号管理</li></a>
				</ul>
			</div>
            

         



<div class="payment_time_masks">

				<ul>

					<li style="background:#0099e5;color:#FFF;">选择银行卡支付 <span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>

                    <? foreach($card as $row){?>

					<li onclick="pay_h5(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

                

                <? foreach($card_h5 as $row){?>

					<li onclick="pay_h5(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

                

                

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5"><li>+添加新卡支付</li></a>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_list_h5"><li>-卡号管理</li></a>

				</ul>

			</div>

          

				<!--<div class="payment_time_masks">

				<ul>

					<li style="background:#0099e5;color:#FFF;">选择银行卡支付 <span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>

                    <? foreach($card as $row){?>

					<li onclick="tijiao_h5(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

                

                <? foreach($card_h5 as $row){?>

					<li onclick="tijiao_h5(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

                

                

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5"><li>+添加新卡支付</li></a>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_list_h5"><li>-卡号管理</li></a>

				</ul>

			</div>-->

				

				



<div class="payment_time_mask">

				<ul>

					<li style="background:#0099e5;color:#FFF;">选择银行卡支付 <span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>

                    <? foreach($card as $row){?>

					<li onclick="tijiao(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka"><li>+添加新卡支付</li></a>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_list"><li>-卡号管理</li></a>

				</ul>

			</div>

            

          <!-- 银联快捷API -->

            

            <div class="payment_time_mask_api">

				<ul>

					<li style="background:#0099e5;color:#FFF;">选择银行卡支付(商旅)<span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>

                    <? foreach($card_api as $row){?>

					<li onclick="tijiao_api(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_api"><li>+添加新卡支付</li></a>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_list_api"><li>-卡号管理</li></a>

				</ul>

			</div>

<!-- 银联快捷API -->


 <!-- 江苏星洁API -->

            

            <div class="payment_time_mask_xj">

				<ul>

					<li style="background:#0099e5;color:#FFF;">选择银行卡支付(商旅)<span class="close" style="float:right;font-weight:600;color:#F70000;">╳</span></li>

                    <? foreach($card_xj as $row){?>

					<li onclick="xingjie_api(<? echo $row['bank_no'];?>);"><? echo $row['bank_no'];?><span style="float:right;">></span></li>

				<? }?>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_xj_api"><li>+添加新卡支付</li></a>

					<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_list_xj_api"><li>-卡号管理</li></a>

				</ul>

			</div>

<!-- 江苏星洁API -->


<dl class="cashier_con">

  <dt><span>￥</span><span id="total">0.00</span></dt>

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

    <div class="cashier_con_button">

      <ul>

      

     

      

       

         <!--{银联}-->

         <li class="c1"><a href="#" onclick="return checkvar_yl()"><img src="img/c1.png"></a></li>

         <!--{微信}-->

 

          <li class="c2"><a href="#" onclick="return checkvar(<? echo $rr['pay_id_wx']?$rr['pay_id_wx']:1;?>)"><img src="img/c2.png"></a></li>

   

         <!--{支付宝}-->

         <li class="c5"><a href="#" onclick="return checkvar(<? echo $rr['pay_id_zfb']?$rr['pay_id_zfb']:1;?>)"><img src="img/c5_hb.png"></a></li>

         <!--{海外}-->

         <li class="c3"><a href="#" onclick="return checkvar_hw(<? echo $rr['pay_id_hw']?$rr['pay_id_hw']:1;?>)"><img src="img/c3.png"></a></li>

         <!--{京东}-->

         <li class="c4"><a href="#" onclick="return checkvar(<? echo $rr['pay_id_jd']?$rr['pay_id_jd']:1;?>)"><img src="img/c4.png"></a></li>

     

     

      </ul> 

    </div>

  </dd>

</dl>

<script>

/*function wx_pay(pay){

	document.getElementById("pay_id").value=pay;

	pp = $('input[name="money"]').val(); 

	if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}

		if(pp < 2){

			alert("最少支付金额为2元");

		return false;

			

			}

			

			if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;

		}

		

	

	

	document.CONSIGNEE_ADDRESS.submit();

	}*/

	

	function checkvar_hw(pay){

		

		document.getElementById("pay_id").value=pay;

	    pp = $('input[name="money"]').val(); 

	

	

	if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;

		}

		

		if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}

		

		document.CONSIGNEE_ADDRESS.submit();

		

		}

  function checkvar(pay){

	document.getElementById("pay_id").value=pay;

	pp = $('input[name="money"]').val(); 

	openid = $('input[name="openid"]').val();

	

	

	if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;

		}

		

		

	if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}

	//微信

	if(pay == 12){

		if(pp < 2){

			alert("最少支付金额为2元");

		return false;

 			}

			

			if(openid == ''){

			alert("openid 为空,请重新支付");

			WeixinJSBridge.call('closeWindow');

		return false;

 			}

			

 		}

		

	//支付宝

	if(pay == 13){

		if(pp < 2){

			alert("最少支付金额为2元");

		    return false;

			      }

		}

		//微信公众

		if(pay == 15){

		if(pp < 2){

			alert("最少支付金额为2元");

		return false;

 			}

 		}

		

		//京东

		if(pay == 18){

		if(pp < 2){

			alert("最少支付金额为2元");

		return false;

 			}

 		}

	

	

		

		$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'update_user_bank_sj',uid:<?php echo $uid;?>,pay_id:pay},function(data){ 

		   

		 

		  

		  if(data == "success"){

			  document.CONSIGNEE_ADDRESS.submit();

			  }else{

				   alert(data); 

				  }

		   

		});

		



		

}







function checkvar_yl(){	





 var $box = $('.yl_choose');

        $box.css({

            display: "block",

        });

			

			 //点击关闭按钮的时候，遮罩层关闭

   

	

	 $(".yl_choose li .close").on('click',function () {

        $("#bg,.yl_choose").css("display", "none");

    });

	

}









function checkvars(pay){	

	if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;



		}

		

 

		

	document.getElementById("pay_id").value=pay;

	pp = $('input[name="money"]').val(); 

	if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}



		/*if(pp < 100){

			alert("最少支付金额为100元");

		return false;

			

			}*/

			

			if(pp > 10000){

			alert("最高限额10000元");

		return false;

			

			}

			

			   $(".yl_choose").css("display", "none");

			

			<? if (!empty($card)){?>

			

        var $box = $('.payment_time_mask');

        $box.css({

            display: "block",

        });

			<? }else{?>

			

			window.location.href="<?php echo ADMIN_URL;?>mycart.php?type=bangka";

			<? }?>

			

			 //点击关闭按钮的时候，遮罩层关闭

   

	

	 $(".payment_time_mask li .close").on('click',function () {

        $(".payment_time_mask").css("display", "none");

    });

	

}



//20180208   江苏星洁快捷

function checkvar_xj(pay){
	if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;
		}
		
		$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'ajax_xj_merchant',uid:<?php echo $uid;?>,pay_id:pay},function(data){  
		
		if(data == "success"){
			
		document.getElementById("pay_id").value=pay;

		pp = $('input[name="money"]').val(); 

		if(typeof(pp)=='undefined' || pp =="" || pp <=0){

			alert("请输入金额！");

			return false;

	}



		if(pp < 1){

			alert("最少支付金额为100元");

		return false;

			

			}

			

			if(pp > 20000){

			alert("最高限额20000元");

		return false;

			

			}

			

			   $(".yl_choose").css("display", "none");

			

			<? if (!empty($card_xj)){?>

			

        var $box = $('.payment_time_mask_xj');

        $box.css({

            display: "block",

        });

			<? }else{?>

			

			window.location.href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_xj_api";

			<? }?>

			

			 //点击关闭按钮的时候，遮罩层关闭

   

	

	 $(".payment_time_mask_xj li .close").on('click',function () {

        $(".payment_time_mask_xj").css("display", "none");

    });
			}else{
				alert(data);
				}
		});
	
	}





//20171229易生银联商旅

function checkvar_api(pay){	

	// if(pay == 1){

	// 		alert("通道升级中......,请选择其他付款方式！");

	// 		return false;



	// 	}

		

	//	

		$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'ajax_xj_merchant',uid:<?php echo $uid;?>,pay_id:pay},function(data){ 
  

		  if(data == "success"){
	

	document.getElementById("pay_id").value=pay;

	pp = $('input[name="money"]').val(); 

	if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}



		if(pp < 100){

			alert("最少支付金额为100元");

		return false;

			

			}

			

			if(pp > 20000){

			alert("最高限额20000元");

		return false;

			

			}

			

			   $(".yl_choose").css("display", "none");

			

			<? if (!empty($card_api)){?>

			

        var $box = $('.payment_time_mask_api');

        $box.css({

            display: "block",

        });

			<? }else{?>

			

			window.location.href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_api";

			<? }?>

			

			 //点击关闭按钮的时候，遮罩层关闭

   

	

	 $(".payment_time_mask_api li .close").on('click',function () {

        $(".payment_time_mask_api").css("display", "none");

    });

			 }else{

				   alert(data);

				  }

		   

		});



	

}


	function checkvars_jiaofei_hq(pay){
	
	  $(".yl_choose").css("display", "none");
	if(pay == 1){
			alert("通道升级中......,请选择其他付款方式！");
			return false;

		}
		document.getElementById("pay_id").value=pay;

		pp = $('input[name="money"]').val(); 
	if(typeof(pp)=='undefined' || pp =="" || pp <=0){
		alert("请输入金额！");
		return false;
	}

		if(pp < 500){
			alert("最少支付金额为500元");
		return false;
			
			}
			
			if(pp > 10000){
			alert("最高限额10000元");
		return false;
			
			}
	
			 
			<? if (!empty($card_h5_hq)){?>
        var $box = $('.payment_time_masks_hq');
        $box.css({
            display: "block",
        });
			<? }else{?>
			
			window.location.href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5_hq";
			<? }?>
			
			 //点击关闭按钮的时候，遮罩层关闭
   
	
	 $(".payment_time_masks_hq li .close").on('click',function () {
        $("#bg,.payment_time_masks_hq").css("display", "none");
    });
		 
		
	
	}
	

function checkvars_jiaofei(pay){

	

	  $(".yl_choose").css("display", "none");

	if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;



		}

		document.getElementById("pay_id").value=pay;



		pp = $('input[name="money"]').val(); 

	if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}



		if(pp < 800){

			alert("最少支付金额为800元");

		return false;

			

			}

			

			if(pp > 20000){

			alert("最高限额20000元");

		return false;

			

			}

	

		

		createwindow();

		

		$.post('<?php echo ADMIN_URL;?>ylpay.php',{action:'BaseMerchRegister',uid:<?php echo $uid;?>,pay_id:pay},function(data){ 

		   

		 if(data == 'success'){

		 removewindow();

		

			 

			<? if (!empty($card) || !empty($card_h5)){?>

        var $box = $('.payment_time_masks');

        $box.css({

            display: "block",

        });

			<? }else{?>

			

			window.location.href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5";

			<? }?>

			

			 //点击关闭按钮的时候，遮罩层关闭

   

	

	 $(".payment_time_masks li .close").on('click',function () {

        $("#bg,.payment_time_masks").css("display", "none");

    });

		 }else{

			 removewindow();

			alert(data);

			 

			 }

		  

		 

			   

		

		});

		

	

	}



function checkvars_h5(pay){	

	if(pay == 1){

			alert("通道升级中......,请选择其他付款方式！");

			return false;



		}

		

 

		

	document.getElementById("pay_id").value=pay;

	pp = $('input[name="money"]').val(); 

	if(typeof(pp)=='undefined' || pp =="" || pp <=0){

		alert("请输入金额！");

		return false;

	}



		if(pp < 500){

			alert("最少支付金额为500元");

		return false;

			

			}

			

			

			if(pp > 20000){

			alert("最高限额20000元");

		return false;

			

			}

			

			

			 $(".yl_choose").css("display", "none");

			 

			<? if (!empty($card) || !empty($card_h5)){?>

        var $box = $('.payment_time_masks');

        $box.css({

            display: "block",

        });

			<? }else{?>

			

			window.location.href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5";

			<? }?>

			

			 //点击关闭按钮的时候，遮罩层关闭

   

	

	 $(".payment_time_masks li .close").on('click',function () {

        $("#bg,.payment_time_masks").css("display", "none");

    });

	

}









function tijiao(card){

	document.getElementById("bank_no").value=card;

	

	var pay = document.getElementById("pay_id").value;

	

        $(".payment_time_mask").css("display", "none");

		

		

       

	 

		

		$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'update_user_bank_sj',uid:<?php echo $uid;?>,pay_id:pay},function(data){ 

		   

		 

		  

		  if(data == "success"){

			  document.CONSIGNEE_ADDRESS.submit();

			  }else{

				   alert(data);

				  }

		   

		});

		

		

	

	

	}

	
function tijiao_h5_hq(card){
	document.getElementById("bank_no").value=card;
	
	var pay = document.getElementById("pay_id").value;
	
        $(".payment_time_masks_hq").css("display", "none");
		
		createwindow();
		
			  document.CONSIGNEE_ADDRESS.submit();
				 
		
	}
	
	

	function tijiao_h5(card){

	document.getElementById("bank_no").value=card;

	

	var pay = document.getElementById("pay_id").value;

	

        $(".payment_time_masks").css("display", "none");

		

		$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'update_user_bank_sj',uid:<?php echo $uid;?>,pay_id:pay},function(data){ 

		   

		 

		  		  if(data == "success"){

			  document.CONSIGNEE_ADDRESS.submit();

				  }else{

			   alert(data); 

			  }

		   

		});

		

	}

	

	function pay_h5(card){

		document.getElementById("bank_no").value=card;

		

				

		

	var pay = document.getElementById("pay_id").value;

	

        $(".payment_time_masks").css("display", "none");

		

createwindow();

			  document.CONSIGNEE_ADDRESS.submit();

				 

		}

	

	function tijiao_api(card){

		document.getElementById("bank_no").value=card;

	

	var pay = document.getElementById("pay_id").value;

	

        $(".payment_time_mask_api").css("display", "none");

		

		  document.CONSIGNEE_ADDRESS.submit();

	}
	
	
	function xingjie_api(card){

		document.getElementById("bank_no").value=card;

	    var pay = document.getElementById("pay_id").value;

        $(".payment_time_mask_api").css("display", "none");

		document.CONSIGNEE_ADDRESS.submit();
	}

	

	



</script>

