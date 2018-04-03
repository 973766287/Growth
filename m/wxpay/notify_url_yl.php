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
//业务代码：PAY
//商户号：102100000125
//终端号：20000147
//商户系统订单号：1441879091739
//网关系统支付号：259976
//订单金额：0.01
//支付结果（1表示成功）：1
//支付时间（yyyyMMddHHmmss）：20150910190003
//清算日期（yyyyMMdd）：20150910
//清算时间（HHmmss）：190003
//订单备注：隐藏的画册
//签名算法：SHA256
//签名：4d0acbe92f2befb6d3956c0f7b258a34d3077b469a6d84c6150b5c3c3da0ba3a

//busi_code	业务代码	String(20)	是	支付的业务代码代码固定为PAY
//merchant_no	商户号	String(20)	是	商户号
//terminal_no	终端号	String(20)	是	终端号
//order_no	商户系统订单号	String(20)	是	商户订单号
//pay_no	支付单号	String(24)	是	网关系统支付单号
//amount	订单金额	String(12)	是	单位为元(CNY)，取值范围精确到小数点后两位，如：0.01、100.05
//pay_result	支付结果	String(1)	是	1支付成功，0未支付，2支付失败
//pay_time	支付时间	String(14)	是	格式：
//YYYYMMDDHHMISS
//sett_date	清算日期	String(8)	是	格式：YYYYMMDD
//sett_time	清算时间	String(6)	是	格式：HHMISS
//base64_memo	订单备注	String(200)	否	网关将把商户提交的这个备注原样返回
//exchg_rate	汇率	String（12）	否	仅当交易币种与清算币种不一致时返回。统一为100外币兑换人民币的汇率。
//取值范围精确到小数点后4位，如：0.0001、100.05
//sign_type	签名类型	String(20)	是	目前只支持SHA256算法
//sign	签名	String(200)	是	数字签名


     // $xml = file_get_contents('php://input');

    //  file_put_contents('wwwwwww.txt',$xml);
	
	
      $pay_result =  $_REQUEST['pay_result'];//交易结果，1为成功，0未支付，2支付失败
	  $pay_time = $_REQUEST['pay_time'];//时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
	  $order_no = $_REQUEST['order_no'];
	  $pay_no = $_REQUEST['pay_no'];
      $amount = $_REQUEST['amount'];
	  
	  

	   
	    $postdata = array (
        'pay_result' => $pay_result,
        'pay_time' => $pay_time, 
        'order_no' => $order_no, 
        'pay_no' => $pay_no, 
        'amount' => $amount,
	    'sign' => '',
		//'sign_local' => $sign_local,
	    'time' => time()
       );
	   
	   
	   
    //  if(isset($_COOKIE['pay_no']) && !empty($_COOKIE['pay_no'])){
//	if($_COOKIE['pay_no'] != $pay_no){
//		
//		  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, '../app/shopping/pay_successs_status/1xzpay_'.date('Y-m-d').'.log');
//		  
//		
//	  }else{
//		   exit;
//		  }
//		  
//	  }
//		   
//		   setcookie("pay_no", $pay_no, mktime()+30);
//	
               
	
	
	   
	   
	 
     
      $status = $pay_result;
		if($status == 1) {
			if(!empty($order_no)){
				
				if($app->action('shopping','query_order_pay',array('order_sn'=>$order_no))){
				
				 $app->action('shopping','add_pay_record',$postdata);
				 
		  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, '../app/shopping/pay_successs_status/pay_'.date('Y-m-d').'.log');
				//file_put_contents('error.txt',"(支付2)time:".$out_trade_no.'-'.$result_code,FILE_APPEND);
				if($app->action('shopping','pay_successs_status',array('order_sn'=>$order_no,'status'=>'1','pay_time'=>$pay_time,'pay_no'=>$pay_no,'amount'=>$amount))){
					
				
				echo "success";
				
			
			
			/*	echo "<script>alert('支付成功'); 	window.open('../mycart.php?type=shoukuan');</script>";*/
					
					}else{
						
						echo "fail";
						}//修改支付状态
				
				}else{
					
					echo "success";
					}
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

