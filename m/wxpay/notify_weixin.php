<?php
	require_once('../load.php');


//接收传送的数据
$fileContent = file_get_contents("php://input");
$result = json_decode($fileContent);

 error_log('['.date('Y-m-d H:i:s').']'.iconv('UTF-8', 'GBK','官方异步返回:')."\n". iconv('UTF-8', 'GBK', $fileContent)."\n\n", 3, '../app/shopping/wx_pay/notify_'.date('Y-m-d').'.log');



	   

if($result->code == 0){
if($result->data->result_code == 0){
	
			$pay_result = 1;
			$pay_time = time();
			$order_no = $result->data->mch_trade_id;
			$pay_no = $result->data->out_trade_id;
			$amount = $result->data->total_fee/100;
			$sign = $result->sign;
			
			
			  $postdata = array (
					'pay_result' => $pay_result,
					'pay_time' => $pay_time, 
					'order_no' => $order_no, 
					'pay_no' => $pay_no, 
					'amount' => $amount,
					'sign' => $sign,
					'time' => time()
				   );
				   
				   
	
				if(!empty($order_no)){
								  if($app->action('shopping','query_order_pay',array('order_sn'=>$order_no))){
										   $app->action('shopping','add_pay_record',$postdata);
		error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, '../app/shopping/wxpay_success_status/pay_'.date('Y-m-d').'.log');
								if($app->action('shopping','pay_successs_status_wx',array('order_sn'=>$order_no,'status'=>'1','pay_time'=>$pay_time,'pay_no'=>$pay_no,'amount'=>$amount))){
									//if($amount > 2){
									//}
													echo "success";
													}else{
															
															echo "fail";
															}//修改支付状态
						
										   
								  }else{
											  echo "success";
											  }
					
				}else{
					echo "fail";//订单号为空
					exit;
					}
	
	}else{
		echo "success";
		exit;
		}
}else{
	
	echo "success";
	exit;
	}
  
  	/*  $postdata = array (
        'pay_result' => $pay_result,
        'pay_time' => $pay_time, 
        'order_no' => $order_no, 
        'pay_no' => $pay_no, 
        'amount' => $amount,
	    'sign' => $sign,
		//'sign_local' => $sign_local,
	    'time' => time()
       );

     
      $status = $pay_result;
		if($status == 1 && $merchant_no == '549440153990220') {
			if(!empty($order_no)){
				
				if($app->action('shopping','query_order_pay',array('order_sn'=>$order_no))){
				
				 $app->action('shopping','add_pay_record',$postdata);
				 
		  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, '../app/shopping/wxpay_success_status/pay_'.date('Y-m-d').'.log');
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
*/
?>

