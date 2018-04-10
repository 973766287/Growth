<?php
require_once('load.php');


if($_GET['act']){
	switch($_GET['act']){
		case 'about':
			$app->action('supplier','about',$_GET['suppId']);
			break;
			case 'index':
			$app->action('supplier','index',$_GET['suppId']);
			break;
				case 'dianpufenlei':
			$app->action('supplier','dianpufenlei',$_GET['suppId']);
			break;
			
			case 'street':
			$app->action('catalog','street',$cid,$page,$_GET);
			break;
	}
	exit;
}



?>