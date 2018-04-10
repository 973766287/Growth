<?php
	require_once('../load.php');
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
// $c = $_GET['c'];//交易结果，0为成功，>0为失败
// 	  $t = $_GET['t'];//时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
// 	  $r = $_GET['r'];//随机字符串 (16位以内，调用方生成的随机字符串)
// 	  $p0 = $_GET['p0'];//酷贝交易号 (32位以内，返回酷贝支付交易号) 
	  
// 	  $p1 = $_GET['p1'];//支付方法	是	字符串类型，8位以内，1为支付宝；2为微信；3百度支付；4手机QQ支付；5为京东
// 	    $p2 = $_GET['p2'];//支付完成时间	是	字符串类型，格式为yyyyMMddhhmmss，如2009年12月27日9点10分10秒表示为20091227091010。时区为GMT+8 beijing。
// 		  $p3 = $_GET['p3'];//支付金额	是	支付金额，单位为分
// 		    $p4 = $_GET['p4'];//折扣金额		折扣金额，单位为分 p3+p4=订单金额
// 			  $p5 = $_GET['p5'];//支付订单号	是	字符串类型，64位以内，第三方支付平台订单号
// 			    $p6 = $_GET['p6'];//机具号	是	字符串，合作伙伴维护的设备号
// 				  $p7 = $_GET['p7'];//商户交易号	是	字符串，订单中合作伙伴维护的交易号
// 				  $s = $_GET['s'];//签名	是	利用partnerKey对所有参数按规则进行MD5签名。见签名方法
	

	  
     
//       $status = $_GET['c'];
//2018/03/15
// $xml = "<xml><appid><![CDATA[wxec249a83c2981248]]></appid>
// <bank_type><![CDATA[CCB_DEBIT]]></bank_type>
// <cash_fee><![CDATA[1]]></cash_fee>
// <fee_type><![CDATA[CNY]]></fee_type>
// <is_subscribe><![CDATA[Y]]></is_subscribe>
// <mch_id><![CDATA[1498289052]]></mch_id>
// <nonce_str><![CDATA[wn8kfo3rwp54re8ok0jymv9rjfmujuxk]]></nonce_str>
// <openid><![CDATA[ouPFZ1YyOdXeA8Tdqn4veBDBGK2E]]></openid>
// <out_trade_no><![CDATA[QZ20180315152110505534]]></out_trade_no>
// <result_code><![CDATA[SUCCESS]]></result_code>
// <return_code><![CDATA[SUCCESS]]></return_code>
// <sign><![CDATA[52DD421551F41C500BDC28435681627E]]></sign>
// <time_end><![CDATA[20180315171103]]></time_end>
// <total_fee>1</total_fee>
// <trade_type><![CDATA[JSAPI]]></trade_type>
// <transaction_id><![CDATA[4200000080201803159167847045]]></transaction_id>
// </xml>";

		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
      	
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		error_log("[" . date('Y-m-d H:i:s'). "]:" . "\n" . json_encode($array_data) ."\n\n",3,"./wxpay_log/wxpay_". date('Y-m-d') .".log");
		
		if($array_data['result_code'] == "SUCCESS") {
			if(!empty($array_data['out_trade_no'])){
				if($rel = $app->action('shopping','baoming_pay_successs_tatus',$array_data['out_trade_no'])){
					error_log("-----------------[" . date('Y-m-d H:i:s'). "]----------------" . $rel . "true" ."\n\n",3,"./wxpay_log/wxpay_". date('Y-m-d') .".log");

					echo "SUCCESS";					
					}else{
						error_log("-----------------[" . date('Y-m-d H:i:s'). "]----------------" . $rel . "false" ."\n\n",3,"./wxpay_log/wxpay_". date('Y-m-d') .".log");
						echo "FAIL";
						}
					
					//修改支付状态
				
				//file_put_contents('error.txt',"(支付3)time:".$out_trade_no.'-'.$result_code,FILE_APPEND);
			}
		}
    //$OpenId = $postObj->OpenId;  //可以这样获取XML里面的信息

	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
/*	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();*/
	//echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
	//以log文件形式记录回调信息
	
?>