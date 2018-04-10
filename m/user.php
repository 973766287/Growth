<?php

require_once('load.php');

//ajax登录
if (isset($_REQUEST['action'])) {
    switch (trim($_REQUEST['action'])) {
        case 'register_instead':
            $app->action('user', 'ajax_user_register_instead', $_POST);
            break;
		case 'user_login_instead':
            $app->action('user', 'ajax_user_login_instead', $_POST);
            break;
        case 'ressinfoop':
            $app->action('user', 'ajax_ressinfoop', $_POST);
            break;
		case 'delete_card':
            $app->action('user', 'ajax_delete_card', $_POST);
            break;
        case 'updateinfo':
            $app->action('user', 'ajax_updateinfo', $_POST);
            break;
        case 'set_account_save':
            $app->action('user', 'ajax_set_account_save', $_POST);
            break;
        case 'bd_account_save':
            $app->action('user', 'ajax_bd_account_save', $_POST);
            break;
        case 'updateshop':
            $app->action('user', 'ajax_updateshop', $_POST);
            break;
        case 'updatepass':
            $app->action('user', 'ajax_updatepass', $_POST);
            break;
        case 'get_ress':
            $app->action('user', 'ajax_get_ress', $_POST);
        case 'get_peisong':
            $app->action('user', 'ajax_get_ge_peisong', $_POST);
            break;
        case 'delress':
            $app->action('user', 'ajax_delress', $_POST['id']);
            break;
        case 'getorderlist':
            $app->action('user', 'ajax_getorderlist', $_POST);
            break;
        case 'order_op':
            $app->action('user', 'ajax_order_op', (isset($_POST['id']) ? $_POST['id'] : 0), $_POST['type']);
            break;
        case 'getuid':
            $app->action('user', 'ajax_getuid');
            break;
        case 'delmycoll':
            $app->action('user', 'ajax_delmycoll', $_POST['ids']);
            break;
        case 'feedback':
            $app->action('user', 'ajax_feedback', $_POST['message']);
            break;
        case 'delmes':
            $app->action('user', 'ajax_delmessages', $_POST['mes_id']);
            break;
        case 'delcomment':
            $app->action('user', 'ajax_delcomment', $_POST['id']);
            break;
		case 'del_assistant':
            $app->action('user', 'ajax_del_assistant', $_POST['assistant_id']);
			   break;
        case 'rp_pass':
            $app->action('user', 'ajax_rp_pass', $_POST);  //重设密码	
			   break;
        default:
            $app->action('user', $_REQUEST['action'], $_POST);
            break;
    }
    exit;
}

$action = isset($_GET['act']) && !empty($_GET['act']) ? $_GET['act'] : "default";
switch ($action) {
	case 'renzheng': //hyhyh 20160914
        $app->action('user', 'renzheng_simple');
        break;
		
		case 'renzheng_simple': //hyhyh 20160914
        $app->action('user', 'renzheng_simple');
        break;
		
		case 'renzheng_after': //hyhyh 20160914
        $app->action('user', 'renzheng_after_simple');
        break;
		
		case 'renzheng_after_simple': //hyhyh 20160914
        $app->action('user', 'renzheng_after_simple');
        break;
		
		case 'sj_renzheng': //hyhyh 20160914
        $app->action('user', 'sj_renzheng');
        break;
		
		case 'tishi': //hyhyh 20160914
        $app->action('user', 'tishi');
        break;
		
		
		case 'assistant': 
        $app->action('user', 'assistant');
        break;
		
		case 'assistant_add': 
        $app->action('user', 'assistant_add');
        break;
		
		
		 case 'assistant_confirm': //用户订单
        $app->action('user', 'assistant_confirm', isset($_GET['pid']) ? $_GET['pid'] : 0);
        break;
		
	
    case 'login': //用户登录
        $app->action('user', 'login');
        break;
    case 'register': //用户注册
        $app->action('user', 'register');
        break;
    case 'default':
        $app->action('user', 'index'); //用户后台
        break;
    /* 	case 'myorder': //用户订单
      $app->action('user','orderlist');
      break; */
    case 'orderinfo': //用户订单
        $app->action('user', 'orderinfo', isset($_GET['order_id']) ? $_GET['order_id'] : "");
        break;
    case 'address_list': //收货地址
        $app->action('user', 'address');
        break;
    case 'mycoll':  //用户收藏
        $app->action('user', 'mycolle');
        break;
    case 'myinfo':   //用户资料
        $app->action('user', 'userinfo');
        break;
    case 'editpass':
        $app->action('user', 'editpass');  //修改密码
        break;
    case 'logout';  //用户退出
        $app->action('user', 'logout'); //用户注销==》清空session
        break;
    case 'forgetpass':
        $app->action('user', 'forgetpass');
        break;
    case 'shop':
        $app->action('user', 'shop');
        break;
    case 'regsuccess':
        $app->action('user', 'user_regsuccess_mes');
        break;
    case 'tuijian':
        $app->action('user', 'user_tuijian');
        break;
    case 'question':
        $app->action('user', 'messages');
        break;
    case 'mycomment':
        $app->action('user', 'comment');
        break;
		 case 'baoming':
        $app->action('user', 'baoming');
        break;
		 case 'confirmpay':
        $app->action('user', 'confirmpay');
        break;
		
	case 'send_mobile_code': //hyhyh 20160914
        echo "success";
        break;
		
		
		case 'daifu_detail': 
       $app->action('user', 'paid_detail');
        break;
		
		
		
    default:
        $app->action('user', $_GET['act'], $_GET);
        break;
}
?>