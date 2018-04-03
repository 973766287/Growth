<?php

require_once('load.php');











$action = isset($_GET['act'])&&!empty($_GET['act']) ? $_GET['act'] : "auto_instead";



switch($action){

	case 'pay_successs': 

		$app->action('shop','pay_successs');

		break;

	case 'auto_instead_new': 

		$app->action('shop','auto_instead_new');

		break;

	default: 

		$app->action('shop',$action,$_GET);

		break;

		

}



?>