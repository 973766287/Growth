<?php
header("Content-type:text/html; charset=UTF-8");
$data = $_GET;

$data2= $_POST;

$data3 = $_REQUEST;

$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
      	
$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

error_log('['.date('Y-m-d H:i:s').']:查询'."\n". var_export($data,true)."\n".var_dump($data3) ."\n\n",3,"./ys_pay_log/".date('Y-m-d').'.log');








?>