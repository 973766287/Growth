<?php
require_once('load.php');
if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		case 'delcartid':
			$app->action('shopping','ajax_delcart_goods',isset($_POST['id'])? $_POST['id'] : 0);
			break;
		case 'jisuan_shopping':
			$app->action('shopping','ajax_jisuan_shopping',$_POST);
			break;
				case 'money':
			$app->action('shopping','ajax_money',$_POST);
			break;
		case 'change_jifen':
			$app->action('shopping','ajax_change_jifen',$_POST['checked']);
			break;
		case 'pay_shengji':
			$app->action('shopping','pay_shengji',$_POST);
		break;
			case 'getcode':
			$app->action('shopping','ajax_getcode',$_POST);
		break;
		    case 'getcode_api':
			$app->action('shopping','ajax_getcode_api',$_POST);
		break;
		
			
		default:
			$app->action('shopping',$_REQUEST['action'],$_POST);
			break;
	}
	exit;
}

$type = !isset($_REQUEST['type'])||empty($_REQUEST['type'])? 'cartlist' : $_REQUEST['type'];
switch($type){
	case 'cartlist':
		$app->action('shopping','checkout');
		break;
	case 'clear':
		$app->action('shopping','mycart_clear');
		break;
	case 'checkout':
		$app->action('shopping','checkout');
		break;
		case 'shoukuan':
		$app->action('shopping','shoukuan',isset($_GET['c']) ? $_GET['c'] : 0);
		break;
		case 'shoukuan_code':
		$app->action('shopping','shoukuan_code_simple');
		break;
      //  case 'shoukuan_code':
//		$app->action('shopping','shoukuan_code');
//		break;
		//case 'sj_shoukuan':
//		$app->action('shopping','sj_shoukuan_simple');
//		break;
        case 'sj_shoukuan':
		$app->action('shopping','sj_shoukuan');
		break;
		
			case 'shoukuan_code_simple':
		$app->action('shopping','shoukuan_code_simple');
		break;
		case 'sj_shoukuan_simple':
		$app->action('shopping','sj_shoukuan_simple');
		break;
		
		
		case 'ylcheck':
		$app->action('shopping','ylcheck',isset($_GET['order_sn']) ? $_GET['order_sn'] : "",isset($_GET['pay_id']) ? $_GET['pay_id'] : 0);
		break;
	case 'confirm':
		$app->action('shopping','confirm');
		break;
		
		case 'bk_confirm':
		$app->action('shopping','bk_confirm');
		break;
		case 'kj_confirm':
		$app->action('shopping','kjpay_confirm');
		break;
		
		case 'pay_shengji':
			$app->action('shopping','pay_shengji',isset($_GET['id']) ? $_GET['id'] : 0);
		break;
		
			case 'pay_sj':
			$app->action('shopping','pay_sj');
			break;
			
	default:
		$app->action('shopping',$type);
		break;
}

?>