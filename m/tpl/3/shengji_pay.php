<style>
.bt_icon label {
	background: #0099e5;
    margin: 0px auto;
    width: 94%;
    font-size: 18px;
    border: 0;
    border-radius: 5px;
    color: #fff;
    height: 50px;
    line-height: 50px;
 
	}
</style>

<div class="tabs">
	  
      <div class="js2_sj">升级所需金额：¥<? echo $rt['amount'];?><button class="real_sub" id="ClickMe">请选择升级扣费通道</button></div>
                 <div>

  <div class="bt_icon">
  <form id="BAOMINGS" name="BAOMINGS" method="post" action="<?php echo ADMIN_URL . 'mycart.php?type=pay_sj'; ?> " enctype="multipart/form-data"   >
  <input type="hidden" name="id" value="<? echo $rt['id'];?>"/>
  <input type="hidden" id="yongjin" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['yongjin']), 0, -1));?>"/>
  <input type="hidden" id="fenrun" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['fenrun']), 0, -1));?>"/>
  <input type="hidden" id="tuiguang" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['tuiguang']), 0, -1));?>"/>
  <input type="hidden" id="yinlian" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['yinlian']), 0, -1));?>"/>
  
   <input type="hidden" id="yinlian_h5" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['yinlian_h5']), 0, -1));?>"/>
   
  <input type="hidden" id="weixin" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['weixin']), 0, -1));?>"/>
  <input type="hidden" id="haiwai" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['haiwai']), 0, -1));?>"/>
  <input type="hidden" id="jingdong" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['jingdong']), 0, -1));?>"/>
  <input type="hidden" id="zhifubao" value="<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['zhifubao']), 0, -1));?>"/>
      <p>
      
       <label>

          <!-- <input type="radio" name="pay" value="yongjin">
        佣金支付： ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['yongjin']), 0, -1));?></label>
        <br>
        
         <label>

          <input type="radio" name="pay" value="fenrun">
        分润支付： ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['fenrun']), 0, -1));?></label>
        <br>
        
          <label>

          <input type="radio" name="pay" value="tuiguang">
        升级奖励支付： ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['tuiguang']), 0, -1));?></label>
        <br>
     
        <label>

          <input type="radio" name="pay" value="yinlian">
        银联商旅类支付： ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['yinlian']), 0, -1));?></label>
        <br>
          <label>

          <input type="radio" name="pay" value="yinlian_h5">
        银联缴费类支付： ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['yinlian_h5']), 0, -1));?></label> -->
        <!-- <br> -->
     
      <label >
          <input type="radio" name="pay" value="weixin" checked="checked">
        微信支付</label>
        <br>
        
         <!-- <label>
          <input type="radio" name="pay" value="haiwai">
        海外支付：  ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['haiwai']), 0, -1));?></label>
        <br>
        
         <label>
          <input type="radio" name="pay" value="jingdong">
        京东支付：  ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['jingdong']), 0, -1))?></label>
        <br>
        
         <label>
          <input type="radio" name="pay" value="zhifubao">
        支付宝：  ¥<? echo sprintf("%.2f",substr(sprintf("%.3f", $row['zhifubao']), 0, -1));?></label> -->
        <br>
      
      </p>
    </form>
  </div>
  <div class="code-img"><button class="real_sub" onclick="real_sub();">确认升级</button></div>
</div>


    
    


 <script>
 
function real_sub() {
    var keyword = $('input[name="pay"]:checked').val();
    var money  = document.getElementById(keyword).value;
    var amount = <? echo $rt['amount'];?>;
		var pay_id = 2;//微信支付
    var id     = <? echo $rt['id'];?>;
		 // if(amount > money){
			//  alert("余额不足，请先充值");
			//  }else{
			// 	  $("#BAOMINGS").submit();
				 
			// 	 }
			 
       $.post('<?php echo ADMIN_URL.'mycart.php';?>', {type: 'pay_sj', keys:keyword, amount:amount, id:id }, function (data) {
 
           removewindow();
           if (data == "") {
           check_sj();
           } else {
              console.log(data);
               location.href=data;
           }

       });
       
    }
	

     
    </script>
	  
</div>
