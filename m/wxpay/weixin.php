<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	require_once('../load.php');
	$pay = $app->action('shopping','_get_payinfo',4);
    $rts = unserialize($pay['pay_config']);

// 商户编号： $rts['pay_no'];
// 机具号：$rts['pay_code'];
		 
//	if(isset($_GET['bm']) && $_GET['bm']=='baoming'){
//		$rt = $app->action('shop','get_openid_AND_pay_info');
//	}else{
		$rt = $app->action('shopping','get_openid_AND_pay_info');
		
		
//	}
//	$openid = $rt['openid'];

	$body = $rt['body'];
	$order_amount = $rt['order_amount'];
	if(!($order_amount > 0)) exit;
	$order_amount = $order_amount*100;
	
	$chars = $rts['pay_address'].$rts['pay_no'].$rts['pay_idt'].$rt['order_sn'].$order_amount.$rt['zifuchuan'].$rt['add_time'].$rts['pay_code'] ;
	
//	$chars = '烟台市芝罘区峰山路1600810199800000000754WX1234567891Sd9aX088Gt8x1dR914707228354A7EFFB0715FCBD3C5161EC0171194C6';
	
	$skey = strtoupper(md5($chars));
		//echo $skey;


//	$data = array("p" =>  $rts['pay_no'], "t" => $rt['add_time'],"r" =>$rt['zifuchuan'],"n" => '烟台市芝罘区峰山路', "p0" => '', "p1" => $rts['pay_code'], "p2" => $rt['order_sn'], "p3" => $order_amount, "s" => $skey  ); 
	
	//$data = array("p" => '160081', "t" => '1470722835',"r" =>'Sd9aX088Gt8x1dR9',"n" => '烟台市芝罘区峰山路', "p0" => '', "p1" => '0199800000000754', "p2" => 'WX123456789', "p3" => '1', "s" => 'B48F515D6087D3EB861B59B185C0BED9'  );       
	
	
	//header("Location: http://demo.counect.com/vcupe/getPay.do?p=160081&t=1470722835&r=Sd9aX088Gt8x1dR9&n=烟台市芝罘区峰山路&p0=&p1=0199800000000754&p2=WX123456789&p3=1&s=B48F515D6087D3EB861B59B185C0BED9");
////确保重定向后，后续代码不会被执行
//exit;
	     
		
  
$data = curl_post("http://demo.counect.com/vcupe/getPay.do", array("p" =>  $rts['pay_no'], "t" => $rt['add_time'],"r" =>$rt['zifuchuan'],"n" => $rts['pay_address'], "p0" => '', "p1" => $rts['pay_idt'], "p2" => $rt['order_sn'], "p3" => $order_amount, "s" => $skey  ));  
  
 
 $arr = json_decode($data,true);
//print_r($arr);

//echo $arr['BODY'];
 
 echo '<link rel="stylesheet" href="/m/style.css" type="text/css">';
 echo "<div class='tabs' style='font-size:30px;'>
	  
      <div class='js2_sj' style='font-size:30px;'>支付金额： ¥".$rt['order_amount']."<div class='real_sub' style='background: #B5B5B5;font-size:30px;'>支付方法</div></div>
                 <div>

  <div class='bt_icon' style='padding: 30px;'>
 1.请长按识别二维码或者扫码支付
 <br>
 2.请点击右上角，选择发送给朋友进行扫码支付
  </div>
  <div class='code-img'>";

echo '<img width="80%" src="http://qr.topscan.com/api.php?bg=f3f3f3&fg=ff0000&gc=222222&el=l&w=200&m=10&text='.$arr['BODY'].'"/>';

echo "</div></div></div>";
		                                                      
//$data_string = $data;   
//                                                                             
// 
//print_r($data_string);
//$ch = curl_init('http://demo.counect.com/vcupe/getPay.do');                                                                      
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                 
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
//    'Content-Type: application/json',                                                                                
//    'Content-Length: ' . strlen($data_string))                                                                       
//);  
//                                                                                                                
// 
//$result = curl_exec($ch);
//print_r($result);

 function curl_post($url, $post) {  
    $options = array(  
        CURLOPT_RETURNTRANSFER => true,  
        CURLOPT_HEADER         => false,  
        CURLOPT_POST           => true,  
        CURLOPT_POSTFIELDS     => $post,  
    );  
  
    $ch = curl_init($url);  
    curl_setopt_array($ch, $options);  
    $result = curl_exec($ch);  
    curl_close($ch);  
    return $result;  
}  
?>
