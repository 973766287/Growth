<header class="top_header">银联缴费</header>

<div class="real_box">
  
  <dl id="api">
   <dt style="width:120px;">支付状态:</dt>
   <dd><?php echo $postdata['RESP_DESC'];?></dd>
  </dl>
  
			   
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script type="text/javascript">
/*window.onload=function(){
 alert(<?php echo $postdata['RESP_DESC'];?>);
	 WeixinJSBridge.call('closeWindow');
 }*/

$(document).ready(function(){ 
setTimeout(function(){
 document.location.href="<?php echo ADMIN_URL;?>mycart.php?type=shoukuan";
},3000);
}); 

</script>


