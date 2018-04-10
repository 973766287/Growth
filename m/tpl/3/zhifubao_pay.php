<?php $this->element('3/top',array('lang'=>$lang)); ?>

<link rel="stylesheet" href="/m/style.css" type="text/css">
<div class='tabs' style='font-size:16px;'>
	  
      <div class='js2_sj' style='font-size:16px;'>
      支付金额： ¥<? echo $rt['order_amount'];?>
      <div class='real_sub' style='background: #B5B5B5;font-size:16px;'>支付方法</div>
      </div>
      <div>

  <div class='bt_icon' style='padding: 16px;'>
 1.打开支付宝，扫码支付
 	   <br>
 2.长按保存至手机，打开支付宝 > 扫一扫 > 右上角选择从相册选取二维码
  </div>
  <div class='code-img'>

<img width="80%" src="http://qr.liantu.com/api.php?text=<? echo $qr_code;?>&w=200"/>

</div>

</div></div>