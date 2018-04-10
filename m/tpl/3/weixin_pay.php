<?php $this->element('3/top',array('lang'=>$lang)); ?>

<link rel="stylesheet" href="/m/style.css" type="text/css">
<div class='tabs' style='font-size:16px;'>
	  
      <div class='js2_sj' style='font-size:16px;'>
      支付金额： ¥<? echo $rt['order_amount'];?>
      <div class='real_sub' style='background: #B5B5B5;font-size:16px;'>支付方法</div>
      </div>
      <div>

  <div class='bt_icon' style='padding-left:16px; padding-right:16px;'>
 <p>》请打开微信“扫一扫”，直接扫描二维码完成支付。</p>
<br />
 <p>》不支持长按识别二维码支付！</p>
<br />
 <p>》不支持通过相册选中图片并识别二维码支付！</p>

  </div>
  <div class='code-img' style="margin-bottom:15px;">


<img width="80%" src="http://qr.liantu.com/api.php?text=<? echo $qr_code;?>&w=200"/>

</div>

</div></div>