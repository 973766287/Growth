<?php
	require_once('../load.php');

    $ORDER_ID = $_REQUEST['ORDER_ID'];//1
	$ORDER_AMT = $_REQUEST['ORDER_AMT'];
	$ORDER_TIME = $_REQUEST['ORDER_TIME'];//2
	$USER_ID = $_REQUEST['USER_ID'];
	$BUS_CODE = $_REQUEST['BUS_CODE'];//3
	$PAYCH_TIME = $_REQUEST['PAYCH_TIME'];//4
	$PAY_AMOUNT = $_REQUEST['PAY_AMOUNT'];//5
	$SIGN_TYPE =  $_REQUEST['SIGN_TYPE'];//6交易结果，1为成功，0未支付，2支付失败
	$RESP_CODE = $_REQUEST['RESP_CODE'];//7时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
	$RESP_DESC = $_REQUEST['RESP_DESC'];
	$SIGN = $_REQUEST['SIGN'];
   
	  

  	  $postdata = array (
        'ORDER_ID' => $ORDER_ID,
        'ORDER_AMT' => $ORDER_AMT, 
        'ORDER_TIME' => $ORDER_TIME, 
        'USER_ID' => $USER_ID, 
        'BUS_CODE' => $BUS_CODE,
	    'PAYCH_TIME' => $PAYCH_TIME,
	    'PAY_AMOUNT' => $PAY_AMOUNT,
		'SIGN_TYPE' => $SIGN_TYPE,
		'RESP_CODE' => $RESP_CODE,
		'RESP_DESC' => $RESP_DESC,
		'SIGN' => $SIGN
       );
	   

 error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, './h5pay/pay_'.date('Y-m-d').'.log');
 
 
 if($app->action('shopping','pay_successs_status_api',array('order_sn'=>$ORDER_ID,'status'=>'1','pay_time'=>$PAYCH_TIME,'pay_no'=>$ORDER_ID,'amount'=>$PAY_AMOUNT))){
					
	
      
				
				echo "success";
				
 }
			


?>

