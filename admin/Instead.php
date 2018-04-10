<?php
require_once('load.php');

if ($_POST['action']) {
    switch ($_POST['action']) {
      
	   case 'bathop':
            $app->action('orderinstead', 'ajax_order_bathop', $_POST['ids'],$_POST['type']);
            break;
	    case 'yinlianapi_pay_daifu':
            $app->action('orderinstead', 'ajax_yinlianapi_pay_daifu', $_POST);
            break;
        case 'yinlianapi_query':
            $app->action('orderinstead', 'ajax_yinlianapi_query', $_POST);
            break;
		case 'stop_plans_all': 
		    $app->action('orderinstead','ajax_stop_plans_all');
		    break;
		case 'liushui_search': 
		    $app->action('orderinstead','ajax_liushui_search',$_POST);
		    break;	
        default:
            $app->action('orderinstead', $_POST['action'], $_POST);
            break;
    }
    exit;
}

$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'planslist';

switch($type){
	
	 case 'order_info':
            $app->action('orderinstead', 'order_info');
            break;
			
     default: 
		$app->action('orderinstead',$type,$_GET);
		break;
}
?>