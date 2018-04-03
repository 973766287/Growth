<?php
require_once('load.php');
if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		    case 'BaseMerchRegister':
			$app->action('yinlian','ajax_BaseMerchRegister',$_POST);
		break;
			
		default:
			$app->action('yinlian',$_REQUEST['action'],$_POST);
			break;
	}
	exit;
}

$type = !isset($_REQUEST['type'])||empty($_REQUEST['type'])? '' : $_REQUEST['type'];
switch($type){
		
	case 'h5pay':
	$app->action('yinlian','h5pay');
			break;	
	default:
		$app->action('yinlian',$type);
		break;
}

?>