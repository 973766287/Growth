<?php
	require_once('../load.php');
	ini_set('date.timezone','Asia/Shanghai');

   
	
	
	$amount = $_REQUEST['amount'];//1
	$merchant_no = $_REQUEST['merchantId'];//3
	$order_no = $_REQUEST['orderId'];//4
	$pay_no = $_REQUEST['orderNo'];//5
    $pay_time = time();
	$sign = $_REQUEST['sign'];
	   
	   
	    $postdata = array (
        'pay_result' => $_POST['errCode'],
		'errCodeDes' => $_POST['errCodeDes'],
        'pay_time' => $pay_time, 
        'order_no' => $order_no, 
        'pay_no' => $pay_no, 
        'amount' => $amount,
	    'sign' => $sign,
	    'time' => time()
       );
	   
	   
	   if($_REQUEST['errCode'] == '00'){
		   
		    $app->action('shopping','add_pay_record_instead',$postdata);
			
			sleep(5);
			if($app->action('shopping','query_pay_record_instead',$order_no)){
					   		   
	  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($_POST,true)."\n\n", 3, '../app/shopping/pay_successs_status_instead/ys_pay_'.date('Y-m-d').'.log');
	  
	  
	echo "success";
				}else{
					 error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($_POST,true)."\n\n", 3, '../app/shopping/pay_successs_status_instead/new_pay_'.date('Y-m-d').'.log');
					//$app->action('shopping','delete_pay_record',$order_no);
					echo "fail";
					
					}
	
		//   if(!empty($order_no)){
//				
//				if($app->action('shopping','query_order_pay',array('order_sn'=>$order_no))){
//					
//					if($app->action('shopping','pay_successs_status_api',array('order_sn'=>$order_no,'status'=>'1','pay_time'=>$pay_time,'pay_no'=>$pay_no,'amount'=>$amount))){
				
				//echo "success";
				//}else{
//						
//						echo "fail";
//						}//修改支付状态
//						
//				}else{
//					echo "success";
//					}
//		   
//		   
//		   
//		   }
   }
	

	
?>

