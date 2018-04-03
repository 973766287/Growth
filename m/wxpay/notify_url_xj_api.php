<?php
	require_once('../load.php');
	header('Content-Type: application/json; charset=utf-8');
	ini_set('date.timezone','Asia/Shanghai');



    $result =  file_get_contents('php://input');
	$result = json_decode($result,true);
	$order_no = $result['agentOrderNo'];//4
    $pay_time = time();
	$sign = $result['sign'];
	   
	   
	    $postdata = array (
        'pay_result' => $result['state'],//0： ⽀付中 1：⽀付失败 2： ⽀付完成 3：结算中 4：结算成功
        'pay_time' => $pay_time, 
        'order_no' => $result['agentOrderNo'], 
	    'sign' => $result['sign'],
	    'time' => time()
       );
	   
	    error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($result,true)."\n\n", 3, './xingjie/xj_notice_'.date('Y-m-d').'.log');
	   
	   
   if($result['state'] == 4){
		   
		    $app->action('shopping','add_pay_record',$postdata);
  }
  
  echo "success";
	

	
?>

