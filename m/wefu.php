<?php 
require_once('load.php');

//ajax登录
if(isset($_REQUEST['action'])){
	switch(trim($_REQUEST['action'])){ 
	
	case 'ajax_postmoney':
			$app->action('wefu','ajax_postmoney',$_POST);
			break;
			

		default:
			$app->action('wefu',trim($_REQUEST['action']),$_POST);  //重设密码
			break;
	}
	exit;
}

$action = isset($_GET['act'])&&!empty($_GET['act']) ? $_GET['act'] : "default";
switch($action){
	case 'default':
		$app->action('wefu','index'); //用户后台
		break;

	default:
		$app->action('wefu',$_GET['act'],$_GET);
		break;
}
?>