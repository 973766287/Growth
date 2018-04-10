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


    $amount = $_REQUEST['amount'];//1
	$base64_memo = $_REQUEST['base64_memo'];
	$busi_code = $_REQUEST['busi_code'];//2
	$exchg_rate = $_REQUEST['exchg_rate'];
	$merchant_no = $_REQUEST['merchant_no'];//3
	$order_no = $_REQUEST['order_no'];//4
	$pay_no = $_REQUEST['pay_no'];//5
	$pay_result =  $_REQUEST['pay_result'];//6交易结果，1为成功，0未支付，2支付失败
	$pay_time = $_REQUEST['pay_time'];//7时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
	$sett_date = $_REQUEST['sett_date'];
	$sett_time = $_REQUEST['sett_time'];
    $terminal_no = $_REQUEST['terminal_no'];
	$sign_type = $_REQUEST['sign_type'];
	$sign = $_REQUEST['sign'];
	  

//
//  $data = array(
//	'amount' => $amount,//1
//	'base64_memo' => $base64_memo,
//	'busi_code' => $busi_code,//2
//	'exchg_rate' => $exchg_rate,
//	'merchant_no' => $merchant_no,//3
//	'order_no' => $order_no,//4
//	'pay_no' => $pay_no,//5
//	'pay_result' =>  $pay_result,//6交易结果，1为成功，0未支付，2支付失败
//	'pay_time' => $pay_time,//7时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
//	'sett_date' => $sett_date,
//	'sett_time' => $sett_time,
//    'terminal_no' => $terminal_no,
//	'sign_type' => $sign_type,
//	'sign' => $sign
//	  );
//
//  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($data,true)."\n\n", 3, '../app/shopping/wx_return_'.date('Y-m-d').'.log');
//  
//  $str = "";
//      if(!empty($amount)){
//	  $str .= 'amount='.$amount.'&';
//	  }
//	   if(!empty($base64_memo)){
//	  $str .= 'base64_memo='.$base64_memo.'&';
//	  }
//	   if(!empty($busi_code)){
//	  $str .= 'busi_code='.$busi_code.'&';
//	  }
//	  
//	   if(!empty($exchg_rate)){
//	  $str .= 'exchg_rate='.$exchg_rate.'&';
//	  }
//	   if(!empty($merchant_no)){
//	  $str .= 'merchant_no='.$merchant_no.'&';
//	  }
//	   if(!empty($order_no)){
//	  $str .= 'order_no='.$order_no.'&';
//	  }
//	   if(!empty($pay_no)){
//	  $str .= 'pay_no='.$pay_no.'&';
//	  }
//	   if(!empty($pay_result)){
//	  $str .= 'pay_result='.$pay_result.'&';
//	  }
//	   if(!empty($pay_time)){
//	  $str .= 'pay_time='.$pay_time.'&';
//	  }
//	   if(!empty($sett_date)){
//	  $str .= 'sett_date='.$sett_date.'&';
//	  }
//	   if(!empty($sett_time)){
//	  $str .= 'sett_time='.$sett_time.'&';
//	  }
//	  
//	   if(!empty($terminal_no)){
//	  $str .= 'terminal_no='.$terminal_no.'&';
//	  }
//	  
//	   $str .= 'key=0f6bf6a53b3b81223a98afc14d44642c';

// $sign_local = hash('SHA256', 'amount='.$amount.'&busi_code='.$busi_code.'&merchant_no='.$merchant_no.'&order_no='.$order_no.'&pay_no='.$pay_no.'&pay_result='.$pay_result.'&pay_time='.$pay_time.'&sett_date='.$sett_date.'&terminal_no='.$terminal_no. '&key=0f6bf6a53b3b81223a98afc14d44642c');
  
   // error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $str."\n\n", 3, '../app/shopping/wx_return_'.date('Y-m-d').'.log');


//  $sign_local = hash('SHA256',$str);
  
  	  $postdata = array (
        'pay_result' => $pay_result,
        'pay_time' => $pay_time, 
        'order_no' => $order_no, 
        'pay_no' => $pay_no, 
        'amount' => $amount,
	    'sign' => $sign,
		//'sign_local' => $sign_local,
	    'time' => time()
       );
	   
	   
     // $pay_result =  $_REQUEST['pay_result'];//交易结果，1为成功，0未支付，2支付失败
//	  $pay_time = $_REQUEST['pay_time'];//时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
//	  $order_no = $_REQUEST['order_no'];
//	  $pay_no = $_REQUEST['pay_no'];
//      $amount = $_REQUEST['amount'];
//	  
//	  
//	  
//	   $postdata = array (
//        'pay_result' => $_REQUEST['pay_result'],
//     'pay_time' => $_REQUEST['pay_time'], 
//  'order_no' => $_REQUEST['order_no'], 
//   'pay_no' => $_REQUEST['pay_no'], 
//    'amount' => $_REQUEST['amount'],
//	'sign' => $_REQUEST['sign'],
//	'time' => time()
//       );
	  
     
      $status = $pay_result;
		if($status == 1 && $merchant_no == '549440153990220') {
			if(!empty($order_no)){
				
				if($app->action('shopping','query_order_pay',array('order_sn'=>$order_no))){
				
				 $app->action('shopping','add_pay_record',$postdata);
				 
		  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, '../app/shopping/ylh5pay_success_status/pay_'.date('Y-m-d').'.log');
				if($app->action('shopping','pay_successs_status',array('order_sn'=>$order_no,'status'=>'1','pay_time'=>$pay_time,'pay_no'=>$pay_no,'amount'=>$amount))){
				
				echo "success";
				}else{
						
						echo "fail";
						}//修改支付状态
				
				}else{
					
					echo "success";
					}
				
			
			
					
				
				//file_put_contents('error.txt',"(支付3)time:".$out_trade_no.'-'.$result_code,FILE_APPEND);
			}
		}

?>

