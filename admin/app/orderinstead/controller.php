<?php
class OrderinsteadController extends Controller {
    function __construct() {
        $this->css(array('content.css', 'calendar.css'));
        $this->js(array('calendar.js', 'calendar-setup.js', 'calendar-zh.js'));
    }
    function shoppingsn($data = array()) {
        $rt = $this->App->find("SELECT * FROM `{$this->App->prefix() }shipping`");
        $this->set('rt', $rt);
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 20;
        $start = ($page - 1) * $list;
        $comd = array();
        $w = "";
        if (isset($_GET['sid']) && $_GET['sid'] > 0) $comd[] = "tb1.shipping_id = '" . intval($_GET['sid']) . "'";
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) $comd[] = "tb1.shipping_sn LIKE '%" . trim($_GET['keyword']) . "%'";
        if (!empty($comd)) {
            $w = "WHERE " . implode(' AND ', $comd);
        }
        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix() }shipping` AS tb2 ON tb1.shipping_id = tb2.shipping_id $w";
        $tt = $this->App->findvar($sql);
        $rts['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $sql = "SELECT tb1.*,tb2.shipping_name FROM `{$this->App->prefix() }shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix() }shipping` AS tb2 ON tb1.shipping_id = tb2.shipping_id $w ORDER BY tb1.id DESC LIMIT $start,$list";
        $rts['lists'] = $this->App->find($sql);
        $this->set('rts', $rts);
        $this->template('shoppingsn');
    }
    function ajax_add_mark_sn($data = array()) {
        $sid = intval($data['shopping_id']);
        $ptid = intval($data['shipping_sn']);
        if ($sid > 0 && $ptid > 0) {
            $sql = "SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE shipping_sn = '$ptid' LIMIT 1";
            $id = $this->App->findvar($sql);
            if ($id > 0) {
            } else {
                $this->App->insert('shipping_sn', array('shipping_id' => $sid, 'shipping_sn' => $ptid, 'addtime' => mktime()));
            }
        }
        exit;
    }
    //生成物流号码
    function ajax_submit_mark_sn($data = array()) {
        $sid = $data['sid'];
        $ptid = $data['ptid'];
        $startptid = $data['startptid'];
        $endptid = $data['endptid'];
        if ($sid > 0 && $ptid > 0 && $startptid > 0 && $endptid > 0 && $endptid > $startptid) {
            $k = 0;
            for ($i = $startptid;$i <= $endptid;$i++) {
                ++$k;
                if ($k > 300) break;
                $sn = $ptid . $i;
                $sql = "SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE shipping_sn = '$sn' LIMIT 1";
                $id = $this->App->findvar($sql);
                if ($id > 0) {
                } else {
                    $this->App->insert('shipping_sn', array('shipping_id' => $sid, 'shipping_sn' => $sn, 'addtime' => mktime()));
                }
            }
        }
        exit;
    }
    function planslist($data = array()) {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 16;
        $start = ($page - 1) * $list;
        $user_info = $_GET['user_info'];
        if (isset($_GET['user_info']) && !empty($_GET['user_info'])) {
            $user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = '$user_info' or uname='$user_info' or mobile = '$user_info'  LIMIT 1");
        }
        if ($user_bank) {
            $user_id = $user_bank['uid'];
            $comd[] = "tb1.user_id = " . $user_id;
        }
        if (isset($_GET['plan_id']) && !empty($_GET['plan_id'])) {
            $plan_info = $this->App->findrow("SELECT plan_no,card_id FROM `{$this->App->prefix() }user_card_instead_plans` WHERE id=" . $_GET['plan_id']);
            if ($plan_info) {
                $comd[] = "tb1.plan_no = '" . $plan_info['plan_no'] . "'";
                $comd[] = "tb1.card_id = " . $plan_info['card_id'];
            }
        }
        if (isset($_GET['plan_no']) && !empty($_GET['plan_no'])) {
            $comd[] = "tb1.plan_no = '" . $_GET['plan_no'] . "'";
        }
        if (isset($_GET['jz_status']) && !empty($_GET['jz_status'])) {
            switch ($_GET['jz_status']) {
                case '3';
                $comd[] = "tb1.status = " . $_GET['jz_status'];
            break;
            case '2';
            $comd[] = "tb1.status = " . $_GET['jz_status'];
        break;
    }
}
if (isset($_GET['pay_status']) && !empty($_GET['pay_status'])) {
    switch ($_GET['pay_status']) {
        case '1';
        $comd[] = "tb5.pay_status = " . $_GET['pay_status'];
    break;
    case '-1';
    $comd[] = "tb5.pay_status = 0";
break;
}
}
if (isset($_GET['df_status']) && !empty($_GET['df_status'])) {
    switch ($_GET['df_status']) {
        case '1';
        $comd[] = "tb6.state = " . $_GET['df_status'];
    break;
    case '-1';
    $comd[] = "tb6.state = 0";
break;
}
}
if (isset($_GET['add_time1']) && !empty($_GET['add_time1']) && empty($_GET['add_time2'])) {
    $t = strtotime($_GET['add_time1']) + 24 * 60 * 60;
    $comd[] = "tb1.kou_time >= " . strtotime($_GET['add_time1']) . " and tb1.kou_time < " . $t;
}
if (isset($_GET['add_time2']) && !empty($_GET['add_time2']) && empty($_GET['add_time1'])) {
    $comd[] = "tb1.kou_time <= " . strtotime($_GET['add_time2']);
}
if (isset($_GET['add_time1']) && !empty($_GET['add_time1']) && isset($_GET['add_time2']) && !empty($_GET['add_time2'])) {
    $t = strtotime($_GET['add_time2']) + 24 * 60 * 60;
    $comd[] = "tb1.kou_time >= " . strtotime($_GET['add_time1']) . " and tb1.kou_time < " . $t;
}
//if(empty($_GET['add_time1']) && empty($_GET['add_time2']) && empty($user_bank) && empty($_GET['plan_id']) && empty($_GET['plan_no'])){
//
//			$t = strtotime(date('Y-m-d'))+24*60*60 ;
//			 $comd[] = "tb1.kou_time >= ". strtotime(date('Y-m-d')) ." and tb1.kou_time < " .$t;
//			}
$w = "";
if (!empty($comd)) {
    $w = ' WHERE ' . @implode(' AND ', $comd);
    $w.= ' and (tb1.stop = 0 or (tb1.status != 1 and tb1.stop=1))';
} else {
    $w = ' where (tb1.stop = 0) or (tb1.status != 1 and tb1.stop=1)';
}
$sql = "SELECT count(tb1.id) FROM `{$this->App->prefix() }user_card_instead_plans` AS tb1 LEFT JOIN `{$this->App->prefix() }user_bank` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix() }user_card_instead` AS tb3 ON tb3.id=tb1.card_id LEFT JOIN `{$this->App->prefix() }bank` AS tb4 ON tb4.id=tb3.bank LEFT JOIN `{$this->App->prefix() }goods_order_info_instead` AS tb5 ON tb5.plan_id=tb1.id LEFT JOIN `{$this->App->prefix() }user_drawmoney_instead` AS tb6 ON tb6.plan_id=tb1.id";
$sql.= " $w ";
$tt = $this->App->findvar($sql);
$rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
$sql = "SELECT tb1.*,tb2.uname,tb2.mobile,tb4.name as bankname,tb3.bank_no FROM `{$this->App->prefix() }user_card_instead_plans` AS tb1 LEFT JOIN `{$this->App->prefix() }user_bank` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix() }user_card_instead` AS tb3 ON tb3.id=tb1.card_id LEFT JOIN `{$this->App->prefix() }bank` AS tb4 ON tb4.id=tb3.bank LEFT JOIN `{$this->App->prefix() }goods_order_info_instead` AS tb5 ON tb5.plan_id=tb1.id LEFT JOIN `{$this->App->prefix() }user_drawmoney_instead` AS tb6 ON tb6.plan_id=tb1.id " . $w . " GROUP BY tb1.id ORDER BY tb1.id ASC LIMIT $start,$list";
//echo $sql;
$lists = $this->App->find($sql);
$rt['lists'] = array();
if (!empty($lists)) foreach ($lists as $k => $row) {
    $row['order_instead'] = $this->get_order_instead($row['id']);
    $row['draworder_instead'] = $this->get_draworder_instead($row['id']);
    $rt['lists'][$k] = $row;
    //$rt['lists'][$k]['status'] = $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
    
}
//var_dump($rt['lists']);
$this->set('rt', $rt);
$this->template('goods_order_list');
}
function get_order_instead($plan_id) {
    $order_insteads = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE plan_id=" . $plan_id . " limit 1");
    return $order_insteads;
}
function get_draworder_instead($plan_id) {
    $draworder_insteads = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE plan_id=" . $plan_id . " limit 1");
    return $draworder_insteads;
}
function ajax_stop_plans_all() {
    $planlists = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` WHERE stop=0 AND is_perform_auto=0");
    if ($planlists) {
        $sql = "UPDATE `{$this->App->prefix() }user_card_instead_plans` SET stop=1 WHERE stop=0 AND is_perform_auto=0";
        if ($this->App->query($sql)) {
            echo "计划终止成功";
        } else {
            echo "计划终止失败";
        }
    } else {
        echo "没有可以终止的计划";
    }
}
function _get_payinfo($id = 0) {
    $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
    return $rt;
}


function ajax_yinlianapi_query($arr = array()) {
   $draw_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id = " . $arr['id']);
    $uid = $draw_info['uid'];
    $plan_id = $draw_info['plan_id'];
    $rts = $this->_get_payinfo(22);
    $pay = unserialize($rts['pay_config']);
    $user_ys_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_ys_merchant` WHERE uid=" . $uid . " limit 1");
    $signKey = $pay['pay_code'];
    $input_charset = 'UTF-8'; //String(10)	编码	NO	UTF-8
    $version = 'N2'; //String(10)	接口版本	NO	N2
    $partner = $pay['pay_idt']; //String(15)	合作方编码 易生系统分配和合作方，易生系统唯一	NO	123456123456789
    $service = 'settlement_query'; //String(10)	接口名称(固定为:qpay)	NO	qpay
    $sign_type = 'MD5'; //String(5)	签名方法 大写的MD5 SHA1	NO	MD5
    $merchant_id = $pay['pay_no']; //String(15)	
    $order_id = $draw_info['payMsgId'];
    $signstr = "input_charset=" . $input_charset . "&merchant_id=" . $merchant_id . "&order_id=" . $order_id . "&partner=" . $partner . "&service=" . $service . "&version=" . $version;
    $sign = md5($signstr . $signKey);
    error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    $data = "action=settlement_query&amount=" . $amount . "&bank_acc=" . $bank_acc . "&input_charset=" . $input_charset . "&merchant_id=" . $merchant_id . "&name=" . $name . "&nbkno=" . $nbkno . "&order_id=" . $order_id . "&partner=" . $partner . "&service=" . $service . "&sign_type=" . $sign_type . "&sign=" . $sign . "&version=" . $version;
	
	 $url = "http://ws.weishuapay.com/m/yishengpay/yishengpay.php";
    //$url = "https://wepay.mpay.cn/new_gateway.do";
    error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    //  echo post($url,$data);
    $result = $this->h5_post($url, $data);
	$result = json_decode($result);
    error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true) . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    $result = $this->xmlToArray2($result);
	
	
	 if ($result['is_success'] == "T") {
        if (!empty($result['response']['status']) && $result['response']['status'] == '00') {
			 echo $result['response']['msg'];
		}else{
			 echo $result['response']['msg'];
			}
	 }else{
		  echo $result['error_msg'];
		 }

}

/*function ajax_yinlianapi_query($data = array()) {
    $draw_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id = " . $data['id']);
    $uid = $draw_info['uid'];
    $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $draw_info['uid'] . " limit 1");
    $daifu_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_daifu_instead` WHERE uid=" . $draw_info['uid']);
    $pay = $this->_get_payinfo(20);
    $rts = unserialize($pay['pay_config']);
    $key = $this->random_string(16, $max = FALSE);
    $xml = '
				<merchant> 
		  <head> 
			<version>1.0.0</version> 
			<agencyId>' . $rts['pay_no'] . '</agencyId> 
			<msgType>01</msgType> 
			<tranCode>200002</tranCode> 
			<reqMsgId>DF' . date('Ymdhis', time()) . $uid . '</reqMsgId> 
			<reqDate>' . date('Ymdhis', time()) . '</reqDate> 
		  </head> 
		  <body> 
			<User_id>' . $sj1['servicePhone'] . '</User_id> 
			<Query_sn>' . $draw_info['INFO_REQ_SN'] . '</Query_sn> 
		  </body> 
		</merchant>';
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
    error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    $encryptData = $this->encrypt($xml, $key);
    $signData = $this->rsaSign($xml, '549440148160026.pem');
    $encyrptKey = $this->rsasign_public($key, '549440148160026_pub.pem');
    $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $rts['pay_no'], 'signData' => $signData, 'tranCode' => '200002');
    error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . var_export($postdata, true) . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    $url = "http://epay.gaohuitong.com:8083/interfaceWeb/queryResultDF";
    $response = $this->curl_daifu($url, $postdata);
    error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    $resp = explode('&', $response);
    $first = strpos($resp[0], "="); //字符第一次出现的位置
    $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
    $first = strpos($resp[1], "="); //字符第一次出现的位置
    $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
    $merchantAESKey = $this->jiemi($encryptKey_host, '549440148160026.pem');
    $xmlData = $this->decode($encryptData_host, $merchantAESKey);
    error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/orderinstead/df/chaxun_' . date('Y-m-d') . '.log');
    $xml_obj = simplexml_load_string($xmlData);
    $rt = array();
    $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
    $rt['payMsgId'] = $xml_obj->head->payMsgId;
    $rt['respCode'] = $xml_obj->head->respCode;
    $rt['respMsg'] = $xml_obj->head->respMsg;
    echo $rt['respMsg'];
}*/
function change_state() {
    $state = $_GET['state'];
    $id = $_GET['id'];
    $plan_id = $this->App->findvar("SELECT plan_id FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id= " . $id);
    $dd = array();
    if ($state == 1) {
        $dd['state'] = 0;
        $dd['upstate_time'] = mktime();
        $this->App->update('user_drawmoney_instead', $dd, 'id', $id);
    } else {
        $dd['state'] = 1;
        $dd['upstate_time'] = mktime();
        $this->App->update('user_drawmoney_instead', $dd, 'id', $id);
        $plan = array('status' => 3);
        $this->App->update('user_card_instead_plans', $plan, 'id', $plan_id);
    }
    $this->jump($_SERVER['HTTP_REFERER']);
}
function ajax_yinlianapi_pay_daifu($arr = array()) {
    $draw_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id = " . $arr['id']);
    $uid = $draw_info['uid'];
    $plan_id = $draw_info['plan_id'];
    $rts = $this->_get_payinfo(22);
    $pay = unserialize($rts['pay_config']);
    $user_ys_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_ys_merchant` WHERE uid=" . $uid . " limit 1");
    $signKey = $pay['pay_code'];
    $input_charset = 'UTF-8'; //String(10)	编码	NO	UTF-8
    $version = 'N2'; //String(10)	接口版本	NO	N2
    $partner = $pay['pay_idt']; //String(15)	合作方编码 易生系统分配和合作方，易生系统唯一	NO	123456123456789
    $service = 'withdrawAcc'; //String(10)	接口名称(固定为:qpay)	NO	qpay
    $sign_type = 'MD5'; //String(5)	签名方法 大写的MD5 SHA1	NO	MD5
    $merchant_id = $user_ys_merchant['merchantId']; //String(15)	子商户号 (上传商户信息成功后返回的录入商户编号)	NO	990513000000002
    $amount = $draw_info['amount'] * 100; //Number(10)	交易金额单位为分，收款1元，应该填100	NO	100
    $bank_acc = $draw_info['account_no']; //String(19)	交易时间 yyyy-MM-dd HH:mm:ss	NO	2017-07-07 12:59:11
    $nbkno = $user_ys_merchant['nbkno'];
    $name = $user_ys_merchant['realName']; //String(30)	持卡人姓名	NO	刘德华
    $order_id = "DF" . date('Ymdhis', time());
    $signstr = "amount=" . $amount . "&bank_acc=" . $bank_acc . "&input_charset=" . $input_charset . "&merchant_id=" . $merchant_id . "&name=" . $name . "&nbkno=" . $nbkno . "&order_id=" . $order_id . "&partner=" . $partner . "&service=" . $service . "&version=" . $version;
    $sign = md5($signstr . $signKey);
    error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr . "\n\n", 3, './app/orderinstead/df/re_daifu_' . date('Y-m-d') . '.log');
    $data = "action=withdrawAcc&amount=" . $amount . "&bank_acc=" . $bank_acc . "&input_charset=" . $input_charset . "&merchant_id=" . $merchant_id . "&name=" . $name . "&nbkno=" . $nbkno . "&order_id=" . $order_id . "&partner=" . $partner . "&service=" . $service . "&sign_type=" . $sign_type . "&sign=" . $sign . "&version=" . $version;
    //$url = "https://wepay.mpay.cn/new_gateway.do";
	
	 $url = "http://ws.weishuapay.com/m/yishengpay/yishengpay.php";
	 
    error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data . "\n\n", 3, './app/orderinstead/df/re_daifu_' . date('Y-m-d') . '.log');
    //  echo post($url,$data);
    $result = $this->h5_post($url, $data);
	 $result = json_decode($result, true);
    error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true) . "\n\n", 3, './app/orderinstead/df/re_daifu_' . date('Y-m-d') . '.log');
    $result = $this->xmlToArray2($result);
    if ($result['is_success'] == "T") {
        if (!empty($result['response']['errCode']) && $result['response']['errCode'] == '00') {
            $arrs = array();
            $arrs['order_sn'] = $order_id;
            $arrs['state'] = 1;
            $arrs['gender'] = 1;
            $arrs['payMsgId'] = $result['response']['order_id'];
            $arrs['INFO_REQ_SN'] = $result['response']['order_id'];
            $arrs['INFO_RET_CODE'] = "0000";
            $arrs['RET_DETAILS_RET_CODE'] = $result['response']['errCode'];
            $arrs['RET_DETAILS_ERR_MSG'] = $result['response']['errCodeDes'];
            $arrs['paytime'] = time();
            $this->App->update('user_drawmoney_instead', $arrs, 'id', $arr['id']);
            unset($arrs);
            $is_plan = array('status' => 3, 'is_perform_auto' => 2);
            $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
            echo "success";
        } else {
            $arrs = array();
            $arrs['order_sn'] = $order_id;
            $arrs['state'] = 0;
            $arrs['gender'] = 1;
            $arrs['payMsgId'] = $result['response']['order_id'];
            $arrs['INFO_REQ_SN'] = $result['response']['order_id'];
            $arrs['INFO_RET_CODE'] = "0000";
            $arrs['RET_DETAILS_RET_CODE'] = $result['response']['errCode'];
            $arrs['RET_DETAILS_ERR_MSG'] = $result['response']['errCodeDes'];
            $arrs['paytime'] = time();
            $this->App->update('user_drawmoney_instead', $arrs, 'id', $arr['id']);
            unset($arrs);
            $is_plan = array('status' => 2, 'is_perform_auto' => 2);
            $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
            echo $result['response']['errCodeDes'];
        }
    } else {
        $arrs = array();
        $arrs['order_sn'] = $order_id;
        $arrs['state'] = 0;
        $arrs['gender'] = 1;
        $arrs['payMsgId'] = '';
        $arrs['INFO_REQ_SN'] = '';
        $arrs['INFO_RET_CODE'] = "0000";
        $arrs['RET_DETAILS_RET_CODE'] = $result['is_success'];
        $arrs['RET_DETAILS_ERR_MSG'] = $result['error_msg'];
        $arrs['paytime'] = time();
        $this->App->update('user_drawmoney_instead', $arrs, 'id', $arr['id']);
        unset($arrs);
        $is_plan = array('status' => 2, 'is_perform_auto' => 2);
        $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
        echo $result['error_msg'];
    }
}
function xmlToArray2($xml) {
    // 将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}
function h5_post($url, $post_data = '', $timeout = 60) { //curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    if ($post_data != '') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}
/*function ajax_yinlianapi_pay_daifu($arr = array()){
		
		 $draw_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_drawmoney_instead` WHERE id = ".$arr['id']);
		
		 $plan_id = $draw_info['plan_id'];
		 $sj1 =  $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj1_instead` WHERE uid=".$draw_info['uid']);

    
		 
		$pay = $this->_get_payinfo(20);
        $rts = unserialize($pay['pay_config']);	
		$key = $this->random_string(16, $max=FALSE);
		
		$new_order_sn = 'NDF'.date('Ymdhis',time()).$draw_info['uid'];
		
		$amount = ($info['amount']+$info['extra_fee'])*100;
		
		$extra_fee = $this->App->findvar("SELECT tixian  FROM `{$this->App->prefix() }user_card_instead_plans` WHERE id=".$plan_id." limit 1");
		
		$xml = '
				<merchant> 
		  <head> 
			<version>1.0.0</version> 
			<agencyId>'.$rts['pay_no'].'</agencyId> 
			<msgType>01</msgType> 
			<tranCode>200001</tranCode> 
			<reqMsgId>'.$new_order_sn.'</reqMsgId> 
			<reqDate>'.date('Ymdhis',time()).'</reqDate> 
		  </head> 
		  <body> 
			<business_code>B00302</business_code> 
			<user_id>'.$sj1['servicePhone'].'</user_id> 
			<bank_code>'.$draw_info['bank_code'].'</bank_code> 
			<account_type>00</account_type> 
			<account_no>'.$draw_info['account_no'].'</account_no> 
			<account_name>'.trim($draw_info['account_name']).'</account_name>
			<allot_flag>1</allot_flag> 
			<amount>'.($draw_info['amount']*100).'</amount>
			<extra_fee>'.($extra_fee*100).'</extra_fee> 
			<terminal_no>'.$rts['pay_idt'].'</terminal_no> 
			<id_type>0</id_type> 
			<ID>'.$draw_info['idcard'].'</ID> 
		  </body> 
		</merchant>';
		
		
		 $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;
		 
		 
		  error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".iconv('UTF-8', 'GBK', $xml)."\n\n", 3, './app/orderinstead/df/re_daifu_'.date('Y-m-d').'.log');
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'549440148160026.pem');
        $encyrptKey = $this->rsasign_public($key,'549440148160026_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => $rts['pay_no'], 
   'signData' => $signData, 
    'tranCode' => '200001'
       );
	   
 error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".var_export($postdata,true)."\n\n", 3, './app/orderinstead/df/re_daifu_'.date('Y-m-d').'.log');
	

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/realTimeDF";
		
		$response = $this->curl_daifu($url,$postdata);

	 error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".iconv('UTF-8', 'GBK', $response)."\n\n", 3, './app/orderinstead/df/re_daifu_'.date('Y-m-d').'.log');
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemi($encryptKey_host,'549440148160026.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		    error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/orderinstead/df/re_daifu_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt = array();
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['payMsgId'] = $xml_obj->head->payMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 
			
				 if($rt['respCode'] == "000000"){
					 
					 $arrs = array();
                            $arrs['order_sn'] = $new_order_sn;
							$arrs['state'] = 1;
							$arrs['gender'] = 1;
							$arrs['payMsgId'] = $rt['payMsgId'];
							$arrs['INFO_REQ_SN'] = $rt['reqMsgId'];
							$arrs['INFO_RET_CODE'] = "0000";
							$arrs['RET_DETAILS_RET_CODE'] = "0000";
							$arrs['RET_DETAILS_ERR_MSG'] = $rt['respMsg'];
							$arr['paytime'] = time();
							$this->App->update('user_drawmoney_instead', $arrs, 'id', $arr['id']);
						  unset($arrs);	
						  $is_plan = array('status' => 3,'is_perform_auto' => 2);
			    $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
							 echo "success";
					 }else{
						  $arrs = array();
						   $arrs['order_sn'] = $new_order_sn;
						   $arrs['state'] = 0;
						   $arrs['gender'] = 1;
						   $arrs['payMsgId'] = $rt['payMsgId'];
							$arrs['INFO_REQ_SN'] = $rt['reqMsgId'];
							$arrs['INFO_RET_CODE'] = "0000";
							$arrs['RET_DETAILS_RET_CODE'] = $rt['respCode'];
							$arrs['RET_DETAILS_ERR_MSG'] = $rt['respMsg'];
							$arr['paytime'] = time();
							$this->App->update('user_drawmoney_instead', $arrs, 'id', $arr['id']);
							  unset($arrs);	
							   $is_plan = array('status' => 2,'is_perform_auto' => 2);
			    $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
						  echo $rt['respMsg'];
					 
					 }
		
				 
				 
				 
				 
		 
		}*/
function orderlist_yifa($data = array()) {
    //分页
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    if (empty($page)) {
        $page = 1;
    }
    $list = 20;
    $start = ($page - 1) * $list;
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $st = $this->select_statue($_GET['status']);
        !empty($st) ? $comd[] = $st : "";
    }
    if (isset($_GET['order_sn']) && !empty($_GET['order_sn'])) $comd[] = "order_sn LIKE '%" . trim($_GET['order_sn']) . "%'";
    if (isset($_GET['add_time1']) && !empty($_GET['add_time1']) && empty($_GET['add_time2'])) {
        $t = strtotime($_GET['add_time1']) + 24 * 60 * 60;
        $comd[] = "add_time >= " . strtotime($_GET['add_time1']) . "&&add_time < " . $t;
    }
    if (isset($_GET['add_time2']) && !empty($_GET['add_time2']) && empty($_GET['add_time1'])) {
        $comd[] = "add_time <= " . strtotime($_GET['add_time2']);
    }
    if (isset($_GET['add_time1']) && !empty($_GET['add_time1']) && isset($_GET['add_time2']) && !empty($_GET['add_time2'])) {
        $t = strtotime($_GET['add_time2']) + 24 * 60 * 60;
        $comd[] = "add_time >= " . strtotime($_GET['add_time1']) . "&&add_time < " . $t;
    }
    if (isset($_GET['consignee']) && !empty($_GET['consignee'])) $comd[] = "consignee LIKE '%" . trim($_GET['consignee']) . "%'";
    $sql = "SELECT COUNT(id) FROM `{$this->App->prefix() }goods_order_address` WHERE shipping_status ='2'";
    $tt = $this->App->findvar($sql);
    $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
    $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix() }goods_order_address` AS tb1";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";
    $sql.= " WHERE tb1.shipping_status ='2' ORDER BY id DESC LIMIT $start,$list";
    $res = $this->App->find($sql);
    $ress = '';
    if (!empty($res)) foreach ($res as $k => $row) {
        $ress[$k] = $row;
        $rid = $row['rec_id'];
        $id = $row['id'];
        $sql = "SELECT tb1.shipping_id,tb1.order_sn,tb1.add_time FROM `{$this->App->prefix() }goods_order_info_daigou` AS tb1 LEFT JOIN  `{$this->App->prefix() }goods_order_daigou` AS tb2 ON tb2.order_id = tb1.order_id";
        $sql.= " LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb3 ON tb3.rec_id = tb2.rec_id";
        $sql.= " WHERE tb3.rec_id = '$rid' LIMIT 1";
        $rts = $this->App->findrow($sql);
        $ress[$k]['shipping_id'] = isset($rts['shipping_id']) ? $rts['shipping_id'] : '0';
        $ress[$k]['order_sn'] = isset($rts['order_sn']) ? $rts['order_sn'] : '0';
        $ress[$k]['add_time'] = isset($rts['add_time']) ? $rts['add_time'] : '0';
        $sql = "SELECT tb1.shipping_sn FROM `{$this->App->prefix() }shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb2 ON tb2.id = tb1.rid";
        $sql.= " WHERE tb1.rid='$id' LIMIT 1";
        $ress[$k]['shipping_sn'] = $this->App->findvar($sql);
    }
    $this->set('rt', $ress);
    //print_r($ress);
    $sql = "SELECT * FROM `{$this->App->prefix() }shipping`";
    $this->set('sp', $this->App->find($sql));
    $this->template('orderlist_yifa');
}
function orderlist_daifa($data = array()) {
    //分页
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    if (empty($page)) {
        $page = 1;
    }
    $list = 20;
    $start = ($page - 1) * $list;
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $st = $this->select_statue($_GET['status']);
        !empty($st) ? $comd[] = $st : "";
    }
    if (isset($_GET['order_sn']) && !empty($_GET['order_sn'])) $comd[] = "order_sn LIKE '%" . trim($_GET['order_sn']) . "%'";
    if (isset($_GET['add_time1']) && !empty($_GET['add_time1']) && empty($_GET['add_time2'])) {
        $t = strtotime($_GET['add_time1']) + 24 * 60 * 60;
        $comd[] = "add_time >= " . strtotime($_GET['add_time1']) . "&&add_time < " . $t;
    }
    if (isset($_GET['add_time2']) && !empty($_GET['add_time2']) && empty($_GET['add_time1'])) {
        $comd[] = "add_time <= " . strtotime($_GET['add_time2']);
    }
    if (isset($_GET['add_time1']) && !empty($_GET['add_time1']) && isset($_GET['add_time2']) && !empty($_GET['add_time2'])) {
        $t = strtotime($_GET['add_time2']) + 24 * 60 * 60;
        $comd[] = "add_time >= " . strtotime($_GET['add_time1']) . "&&add_time < " . $t;
    }
    if (isset($_GET['consignee']) && !empty($_GET['consignee'])) $comd[] = "consignee LIKE '%" . trim($_GET['consignee']) . "%'";
    $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }goods_order_address` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_daigou` AS tb2  ON tb2.rec_id = tb1.rec_id";
    $sql.= " LEFT JOIN `{$this->App->prefix() }goods_order_info_daigou` AS tb3  ON tb3.order_id = tb2.order_id";
    $sql.= " WHERE tb1.shipping_status ='0' AND tb3.pay_status = '2'";
    echo $sql;
    //$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}goods_order_address` WHERE shipping_status ='0'";
    $tt = $this->App->findvar($sql);
    $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
    $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix() }goods_order_address` AS tb1";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";
    $sql.= " WHERE tb1.shipping_status ='0' ORDER BY id DESC LIMIT $start,$list";
    $res = $this->App->find($sql);
    $ress = '';
    if (!empty($res)) foreach ($res as $k => $row) {
        $ress[$k] = $row;
        $rid = $row['rec_id'];
        $id = $row['id'];
        $sql = "SELECT tb1.shipping_id,tb1.order_sn,tb1.add_time FROM `{$this->App->prefix() }goods_order_info_daigou` AS tb1 LEFT JOIN  `{$this->App->prefix() }goods_order_daigou` AS tb2 ON tb2.order_id = tb1.order_id";
        $sql.= " LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb3 ON tb3.rec_id = tb2.rec_id";
        $sql.= " WHERE tb3.rec_id = '$rid' LIMIT 1";
        $rts = $this->App->findrow($sql);
        $ress[$k]['shipping_id'] = isset($rts['shipping_id']) ? $rts['shipping_id'] : '0';
        $ress[$k]['order_sn'] = isset($rts['order_sn']) ? $rts['order_sn'] : '0';
        $ress[$k]['add_time'] = isset($rts['add_time']) ? $rts['add_time'] : '0';
        $sql = "SELECT tb1.shipping_sn FROM `{$this->App->prefix() }shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb2 ON tb2.id = tb1.rid";
        $sql.= " WHERE tb1.rid='$id' LIMIT 1";
        //$ress[$k]['shipping_sn'] = $this->App->findvar($sql);
        
    }
    $this->set('rt', $ress);
    $sql = "SELECT * FROM `{$this->App->prefix() }shipping`";
    $this->set('sp', $this->App->find($sql));
    $this->template('orderlist_daifa');
}
function update_shipping_id($id = 0, $sid = 0) {
    //ID:goods_order_address id AND shipping_sn rid
    if ($id > 0 && $sid > 0) {
        //
        $tt = 'false';
        $sql = "SELECT is_use FROM `{$this->App->prefix() }shipping_sn` WHERE rid = '$id' LIMIT 1";
        $is_use = $this->App->findvar($sql);
        if ($is_use == '1') {
            $this->App->update('shipping_sn', array('is_use' => '0', 'usetime' => '0'), 'rid', $id);
            //重新选择物流单号
            $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
            if ($ids > 0) {
                $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
            } else {
                $tt = 'true';
            }
        } else {
            //选择物流单号
            $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
            if ($ids > 0) {
                $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
            } else {
                $tt = 'true';
            }
        }
        if ($tt == 'false') {
            $this->App->update('goods_order_address', array('shipping_id' => $sid), 'id', $id);
        }
    }
}
function update_shipping_id2($data = array()) {
    $id = $data['id'];
    $sid = $data['sid'];
    if ($id > 0 && $sid > 0) {
        $sql = "SELECT is_use FROM `{$this->App->prefix() }shipping_sn` WHERE rid = '$id' LIMIT 1";
        $is_use = $this->App->findvar($sql);
        if ($is_use == '1') {
            $this->App->update('shipping_sn', array('is_use' => '0', 'usetime' => '0'), 'rid', $id);
            //重新选择物流单号
            $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
            if ($ids > 0) {
                $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
            } else {
                echo "2";
                exit;
            }
        } else {
            //选择物流单号
            $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
            if ($ids > 0) {
                $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
            } else {
                echo "2";
                exit;
            }
        }
        $this->App->update('goods_order_address', array('shipping_id' => $sid), 'id', $id);
    }
}
//发货操作
function ajax_fahuo($data = array()) {
    $rid = $data['rid'];
    $oid = $data['oid'];
    $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix() }goods_order_address` AS tb1";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";
    $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";
    $sql.= " WHERE tb1.rec_id='$rid'";
    $res = $this->App->find($sql);
    $ress = '';
    if (!empty($res)) foreach ($res as $k => $row) {
        $ress[$k] = $row;
        $rid = $row['rec_id'];
        $id = $row['id'];
        $sql = "SELECT tb1.shipping_id,tb1.order_sn,tb1.add_time FROM `{$this->App->prefix() }goods_order_info_daigou` AS tb1 LEFT JOIN  `{$this->App->prefix() }goods_order_daigou` AS tb2 ON tb2.order_id = tb1.order_id";
        $sql.= " LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb3 ON tb3.rec_id = tb2.rec_id";
        $sql.= " WHERE tb3.rec_id = '$rid' LIMIT 1";
        $rts = $this->App->findrow($sql);
        $ress[$k]['shipping_id'] = isset($rts['shipping_id']) ? $rts['shipping_id'] : '0';
        $ress[$k]['order_sn'] = isset($rts['order_sn']) ? $rts['order_sn'] : '0';
        $ress[$k]['add_time'] = isset($rts['add_time']) ? $rts['add_time'] : '0';
        $sql = "SELECT tb1.shipping_sn FROM `{$this->App->prefix() }shipping_sn` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb2 ON tb2.id = tb1.rid";
        $sql.= " WHERE tb1.rid='$id' LIMIT 1";
        $ress[$k]['shipping_sn'] = $this->App->findvar($sql);
    }
    $this->set('rt', $ress);
    $sql = "SELECT * FROM `{$this->App->prefix() }shipping`";
    $this->set('sp', $this->App->find($sql));
    $sql = "SELECT shipping_id FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE order_id = '$oid' LIMIT 1";
    $this->set('sid', $this->App->findvar($sql));
    $this->set('oid', $oid);
    $this->set('rid', $rid);
    $this->template('ajax_fahuo');
}
function ajax_fahuo_op($data = array()) {
    $id = $data['id'];
    $rid = $data['rid'];
    if ($rid > 0) { //批量发货
        $this->App->update('goods_order_address', array('shipping_status' => '2'), 'rec_id', $rid);
        //
        $sql = "SELECT order_id FROM `{$this->App->prefix() }goods_order_daigou` WHERE rec_id ='$rid' LIMIT 1";
        $oid = $this->App->findvar($sql);
        if ($oid > 0) {
            //更新状态
            $sql = "SELECT tb1.id FROM `{$this->App->prefix() }goods_order_address` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_daigou` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb2.order_id = '$oid' AND tb1.shipping_status != '2' LIMIT 1";
            $id = $this->App->findvar($sql);
            if ($id > 0) {
                $this->App->update('goods_order_info_daigou', array('shipping_status' => '3'), 'order_id', $oid);
            } else {
                $this->App->update('goods_order_info_daigou', array('shipping_status' => '2'), 'order_id', $oid);
            }
            $sql = "SELECT shipping_id FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE order_id = '$oid' LIMIT 1";
            $ssid = $this->App->findvar($sql);
            //选择物流单号
            $sql = "SELECT id,shipping_id FROM `{$this->App->prefix() }goods_order_address` WHERE rec_id='$rid'";
            $ll = $this->App->find($sql);
            if (!empty($ll)) foreach ($ll as $row) {
                $id = $row['id'];
                $sid = $row['shipping_id'];
                if (!($sid > 0)) $sid = $ssid;
                $sql = "SELECT is_use FROM `{$this->App->prefix() }shipping_sn` WHERE rid = '$id' LIMIT 1";
                $is_use = $this->App->findvar($sql);
                if ($is_use == '1') {
                    $this->App->update('shipping_sn', array('is_use' => '0', 'usetime' => '0'), 'rid', $id);
                    //重新选择物流单号
                    $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
                    if ($ids > 0) {
                        $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
                    } else {
                    }
                } else {
                    //选择物流单号
                    $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
                    if ($ids > 0) {
                        $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
                    } else {
                    }
                }
            }
        }
    } else { //单个地址发货
        $this->App->update('goods_order_address', array('shipping_status' => '2'), 'id', $id);
        //
        $sql = "SELECT tb1.order_id FROM `{$this->App->prefix() }goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb2 ON tb2.rec_id = tb1.rec_id WHERE tb2.id ='$id' LIMIT 1";
        $oid = $this->App->findvar($sql);
        if ($oid > 0) {
            $sql = "SELECT tb1.id FROM `{$this->App->prefix() }goods_order_address` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_daigou` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb2.order_id = '$oid' AND tb1.shipping_status != '2' LIMIT 1";
            $idl = $this->App->findvar($sql);
            if ($idl > 0) {
                $this->App->update('goods_order_info_daigou', array('shipping_status' => '3'), 'order_id', $oid); //部分发货
                
            } else {
                $this->App->update('goods_order_info_daigou', array('shipping_status' => '2'), 'order_id', $oid);
            }
            $sql = "SELECT shipping_id FROM `{$this->App->prefix() }goods_order_address` WHERE id='$id' LIMIT 1";
            $sid = $this->App->findvar($sql);
            if (!($sid > 0)) {
                $sql = "SELECT shipping_id FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE order_id = '$oid' LIMIT 1";
                $sid = $this->App->findvar($sql);
            }
            $sql = "SELECT is_use FROM `{$this->App->prefix() }shipping_sn` WHERE rid = '$id' LIMIT 1";
            $is_use = $this->App->findvar($sql);
            if ($is_use == '1') {
                $this->App->update('shipping_sn', array('is_use' => '0', 'usetime' => '0'), 'rid', $id);
                //重新选择物流单号
                $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
                if ($ids > 0) {
                    $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
                } else {
                }
            } else {
                //选择物流单号
                $ids = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }shipping_sn` WHERE is_use='0' AND shipping_id='$sid' ORDER BY id ASC LIMIT 1");
                if ($ids > 0) {
                    $this->App->update('shipping_sn', array('is_use' => '1', 'usetime' => mktime(), 'rid' => $id), 'id', $ids);
                } else {
                }
            }
        }
    }
}
function order_info() {
    $this->css('jquery_dialog.css');
    $this->js('jquery_dialog.js');
    $plan_id = $_GET['id'];
    if (!$plan_id) {
        $this->jump(ADMIN_URL . 'Instead.php');
        exit;
    }
    $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE plan_id='$plan_id'";
    $orderinfo = $this->App->findrow($sql);
    if (empty($orderinfo)) {
        $this->jump(ADMIN_URL . 'Instead.php');
        exit;
    }
    $drawmoneyinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE plan_id='$plan_id'");
    $this->set('orderinfo', $orderinfo);
    $this->set('drawmoneyinfo', $drawmoneyinfo);
    $this->template('order_info');
}
//获取操作状态按钮
function ajax_get_status_button($var = 0) {
    if (strlen($var) != 3) return;
    $order_status = substr($var, 0, 1);
    $pay_status = substr($var, 1, 1);
    $shipping_status = substr($var, -1);
    $str = $this->get_order_action_button($order_status, $pay_status, $shipping_status);
    die($str);
}
//ajax 处理订单状态
function ajax_order_bathop($ids = 0, $type = "") {
    @set_time_limit(600); //最大运行时间
    if (empty($ids)) {
        echo "没有找到需要终止的计划！";
        exit;
    }
    if (empty($type)) {
        echo "没有指定的操作类型！";
        exit;
    }
    $id_arr = @explode('+', $ids);
    switch ($type) {
        case 'stopplan':
            //批量终止计划
            if (!empty($id_arr)) {
                $this->App->update('user_card_instead_plans', array('stop' => '1'), 'id', $id_arr);
            } else {
                echo "无法进行该操作！";
                exit;
            }
        break;
        case 'bathdel':
            //批量删除订单
            $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_id IN(" . implode(',', $id_arr) . ")");
            if (!empty($now_status)) {
                $afterarr = array();
                foreach ($now_status as $k => $status) {
                    if (in_array($status, array('0', '2', '3'))) {
                        //$str = "部分操作不能完成，例如：确认、退货、刚下的的订单不能操作了！";
                        $afterarr[] = $id_arr[$k];
                    }
                }
                if (!empty($afterarr)) {
                    $id_arr_ = array_diff($id_arr, $afterarr);
                    unset($id_arr);
                    $id_arr = $id_arr_;
                    unset($id_arr_);
                }
            }
            //$sql = "DELETE FROM `{$this->App->prefix()}goods_order_info` WHERE order_id IN(".implode(',',$id_arr).")";
            //$this->App->query($sql);
            if (!empty($id_arr)) {
                $this->App->delete('goods_order_info', 'order_id', $id_arr); //订单表
                $this->App->delete('goods_order', 'order_id', $id_arr); //订单商品表
                $this->App->delete('goods_order_action', 'order_id', $id_arr); //订单操作记录表
                $this->action('system', 'add_admin_log', '批量删除商品订单：' . @implode(',', $id_arr));
            } else {
                echo "无法进行该操作！";
                exit;
            }
        break;
        case 'bathconfirm':
            //批量确认订单
            //查询当前的订单状态，如果当前的状态为取消[1]、失效[4]、那么将不能再确认了
            $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_id IN(" . implode(',', $id_arr) . ")");
            if (!empty($now_status)) {
                $afterarr = array();
                foreach ($now_status as $k => $status) {
                    if (in_array($status, array('1', '4'))) {
                        //$str = "部分操作不能完成，例如：失效、取消的订单不能操作了！";
                        $afterarr[] = $id_arr[$k];
                    }
                }
                if (!empty($afterarr)) {
                    $id_arr_ = array_diff($id_arr, $afterarr);
                    unset($id_arr);
                    $id_arr = $id_arr_;
                    unset($id_arr_);
                }
            }
            if (!empty($id_arr)) {
                if ($this->App->update('goods_order_info', array('order_status' => '2'), 'order_id', $id_arr)) {
                    $sql = "SELECT tb1.user_id,tb1.order_sn,tb1.order_id,tb2.user_name,tb2.email FROM `{$this->App->prefix() }goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.order_id IN(" . implode(',', $id_arr) . ")";
                    $emails = $this->App->find($sql);
                    if (!empty($emails)) foreach ($emails as $row) {
                        //确认后，发送mail
                        $datas = array();
                        if (!empty($row['email']) && $GLOBALS['LANG']['email_open_config']['confirm_order'] == '1') {
                            $datas['user_name'] = $row['user_name'];
                            $datas['uid'] = $row['user_id'];
                            $datas['order_sn'] = $row['order_sn'];
                            $datas['email'] = $row['email'];
                            $datas['orderinfourl'] = SITE_URL . 'user.php?act=orderinfo&order_id=' . $row['order_id'];
                            $this->action('email', 'send_confirm_order', $datas);
                            unset($datas);
                        }
                    }
                }
                $this->action('system', 'add_admin_log', '批量确认订单：' . @implode(',', $id_arr));
            } else {
                echo "无法进行该操作！";
                exit;
            }
            break;
        case 'bathcancel':
            //批量取消订单
            //查询当前的订单状态，如果当前的状态为确认[2]、退货[3]、那么将不能再取消了了
            $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_id IN(" . implode(',', $id_arr) . ")");
            $str = "";
            if (!empty($now_status)) {
                $afterarr = array();
                foreach ($now_status as $k => $status) {
                    if (in_array($status, array('2', '3'))) {
                        $afterarr[] = $id_arr[$k];
                    }
                }
                if (!empty($afterarr)) {
                    $id_arr_ = array_diff($id_arr, $afterarr);
                    unset($id_arr);
                    $id_arr = $id_arr_;
                    unset($id_arr_);
                }
            }
            if (!empty($id_arr)) {
                $this->App->update('goods_order_info', array('order_status' => '1'), 'order_id', $id_arr);
                $sql = "SELECT tb1.user_id,tb1.order_sn,tb1.order_id,tb2.user_name,tb2.email FROM `{$this->App->prefix() }goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.order_id IN(" . implode(',', $id_arr) . ")";
                $emails = $this->App->find($sql);
                if (!empty($emails)) foreach ($emails as $row) {
                    //订单取消后，发送mail
                    $datas = array();
                    if (!empty($row['email']) && $GLOBALS['LANG']['email_open_config']['cancel_order'] == '1') {
                        $datas['user_name'] = $row['user_name'];
                        $datas['uid'] = $row['user_id'];
                        $datas['order_sn'] = $row['order_sn'];
                        $datas['email'] = $row['email'];
                        $datas['orderinfourl'] = SITE_URL . 'user.php?act=orderinfo&order_id=' . $row['order_id'];
                        $this->action('email', 'send_cancel_order', $datas);
                        unset($datas);
                    }
                }
                $this->action('system', 'add_admin_log', '批量取消订单：' . @implode(',', $id_arr));
            } else {
                echo "无法进行该操作！";
                exit;
            }
            break;
        case 'bathinvalid':
            //批量失效订单
            //查询当前的订单状态，如果当前的状态为确认[2]、退货[3]、那么将不能再失效了
            $now_status = $this->App->findcol("SELECT order_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_id IN(" . implode(',', $id_arr) . ")");
            $str = "";
            if (!empty($now_status)) {
                $afterarr = array();
                foreach ($now_status as $k => $status) {
                    if (in_array($status, array('2', '3'))) {
                        //$str = "部分操作不能完成，例如：确认、退货的订单不能操作了！";
                        $afterarr[] = $id_arr[$k];
                    }
                }
                if (!empty($afterarr)) {
                    $id_arr_ = array_diff($id_arr, $afterarr);
                    unset($id_arr);
                    $id_arr = $id_arr_;
                    unset($id_arr_);
                }
            }
            if (!empty($id_arr)) {
                $this->App->update('goods_order_info', array('order_status' => '4'), 'order_id', $id_arr);
                $sql = "SELECT tb1.user_id,tb1.order_sn,tb1.order_id,tb2.user_name,tb2.email FROM `{$this->App->prefix() }goods_order_info` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.order_id IN(" . implode(',', $id_arr) . ")";
                $emails = $this->App->find($sql);
                if (!empty($emails)) foreach ($emails as $row) {
                    //订单取消后，发送mail
                    $datas = array();
                    if (!empty($row['email']) && $GLOBALS['LANG']['email_open_config']['orders_invalid'] == '1') {
                        $datas['user_name'] = $row['user_name'];
                        $datas['uid'] = $row['user_id'];
                        $datas['order_sn'] = $row['order_sn'];
                        $datas['email'] = $row['email'];
                        $datas['orderinfourl'] = SITE_URL . 'user.php?act=orderinfo&order_id=' . $row['order_id'];
                        $this->action('email', 'send_invalid_order', $datas);
                        unset($datas);
                    }
                }
                $this->action('system', 'add_admin_log', '批量失效订单：' . @implode(',', $id_arr));
            } else {
                echo "无法进行该操作！";
                exit;
            }
            break;
        }
    }
    //订单的状态
    function get_status($oid = 0, $pid = 0, $sid = 0) { //分别为：订单 支付 发货状态
        $str = '';
        switch ($oid) {
            case '0':
                $str.= '未确认,';
            break;
            case '1':
                $str.= '<font color="red">取消</font>,';
            break;
            case '2':
                $str.= '确认,';
            break;
            case '3':
                $str.= '<font color="red">退货</font>,';
            break;
            case '4':
                $str.= '<font color="red">无效</font>,';
            break;
        }
        switch ($pid) {
            case '0':
                $str.= '未付款,';
            break;
            case '1':
                $str.= '已付款,';
            break;
            case '2':
                $str.= '已退款,';
            break;
        }
        switch ($sid) {
            case '0':
                $str.= '未发货';
            break;
            case '1':
                $str.= '配货中';
            break;
            case '2':
                $str.= '已发货';
            break;
            case '3':
                $str.= '部分发货';
            break;
            case '4':
                $str.= '退货';
            break;
            case '5':
                $str.= '已收货';
            break;
        }
        return $str;
    }
    function get_order_action_button($order_status = 0, $pay_status = 0, $shipping_status = 0) {
        $str = "";
        if ($order_status == 0) { //没确认(没付款、没发货)
            $str.= '<input value="确认" class="order_action" type="button" id="200">' . "\n";
            $str.= '<input value="付款" class="order_action" type="button" id="210">' . "\n";
            $str.= '<input value="取消" class="order_action" type="button" id="100">' . "\n";
            $str.= '<input value="无效" class="order_action" type="button" id="400">' . "\n";
        } else if ($order_status == 2) { //已经确认
            if ($pay_status == 0) { //没支付
                $str.= '<input value="付款" class="order_action" type="button" id="210">' . "\n";
                $str.= '<input value="取消" class="order_action" type="button" id="100">' . "\n";
                $str.= '<input value="无效" class="order_action" type="button" id="400">' . "\n";
            } else if ($pay_status == 1) { //已支付
                if ($shipping_status == 0) { //未发货
                    $str.= '<input value="发货" class="order_action" type="button" id="212">' . "\n";
                    $str.= '<input value="设为未支付" class="order_action" type="button" id="200">' . "\n";
                    $str.= '<input value="取消" class="order_action" type="button" id="100">' . "\n";
                } else if ($shipping_status == 2) { //已发货
                    $str.= '<input value="设为未支付" class="order_action" type="button" id="200">' . "\n";
                    $str.= '<input value="设为未发货" class="order_action" type="button" id="210">' . "\n";
                    $str.= '<input value="收货" class="order_action" type="button" id="215">' . "\n";
                    $str.= '<input value="退货" class="order_action" type="button" id="324">' . "\n";
                } else if ($shipping_status == 1) { //配货中
                    $str.= '<input value="设为未支付" class="order_action" type="button" id="200">' . "\n";
                    $str.= '<input value="取消" class="order_action" type="button" id="100">' . "\n";
                } else if ($shipping_status == 5) { //已收货
                    $str.= '<input value="退货" class="order_action" type="button" id="324">' . "\n";
                }
            } else if ($pay_status == 2) { //退款
                if ($shipping_status == 2) { //已发货
                    $str.= '<input value="设为未发货" class="order_action" type="button" id="120">' . "\n";
                    $str.= '<input value="退货" class="order_action" type="button" id="124">' . "\n";
                } else if ($shipping_status == 1) { //配货中
                    $str.= '<input value="设为未发货" class="order_action" type="button" id="120">' . "\n";
                    $str.= '<input value="退货" class="order_action" type="button" id="124">' . "\n";
                } else if ($shipping_status == 5) { //已收货
                    $str.= '<input value="设为未发货" class="order_action" type="button" id="120">' . "\n";
                    $str.= '<input value="退货" class="order_action" type="button" id="124">' . "\n";
                }
            }
        } else if ($order_status == 1) { //取消
            $str.= '<input value="确认" class="order_action" type="button" id="200">' . "\n";
            $str.= '<input value="移除" class="order_action" type="button" id="remove">' . "\n";
        } else if ($order_status == 4) { //无效
            $str.= '<input value="确认" class="order_action" type="button" id="200">' . "\n";
        } else if ($order_status == 3) { //退货
            $str.= '<input value="确认" class="order_action" type="button" id="200">' . "\n";
            $str.= '<input value="移除" class="order_action" type="button" id="remove">' . "\n";
        }
        return $str;
    }
	
	function ajax_liushui_search($arr = array()){
			
	
			
			$w = ' WHERE pay_status=1 ';
			/***********  look添加 日期查询 开始  ********************************/	
			if(isset($arr['add_time1'])&&!empty($arr['add_time1']) && empty($arr['add_time2'])){
				//	$t = strtotime($arr['add_time1'])+24*60*60 ;
                   // $w .= " and pay_time >= ". strtotime($arr['add_time1']) ." and pay_time < " .$t;
				    $w .= " and pay_time >= ". strtotime($arr['add_time1']);
					}
			if(isset($arr['add_time2'])&&!empty($arr['add_time2']) && empty($arr['add_time1'])){
				    $t = strtotime($arr['add_time2'])+24*60*60 ;
                    $w .= " and pay_time <= ". $t;
					}
			if(isset($arr['add_time1'])&&!empty($arr['add_time1']) &&isset($arr['add_time2'])&& !empty($arr['add_time2'])){
					$t = strtotime($arr['add_time2'])+24*60*60 ;
                    $w .= " and pay_time >= ". strtotime($arr['add_time1']) ." and pay_time < " .$t;
			}
			$sql = "SELECT IFNULL(SUM(order_amount),0) AS tprice FROM `{$this->App->prefix()}goods_order_info_instead`".$w;
			$tprice =  $this->App->findvar($sql);
		
			echo $tprice;
			
			}
			
    //快捷API
    function encrypt($input, $key) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }
    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    function decrypt($sStr, $sKey) {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sKey, base64_decode($sStr), MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
    function rsaSign($data, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    function rsasign_public($data, $public_key_path) {
        $public_key = file_get_contents($public_key_path);
        $pu_key = openssl_pkey_get_public($public_key); //这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt($data, $encrypted, $pu_key); //公钥加密
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }
    function random_string($length, $max = FALSE) {
        if (is_int($max) && $max > $length) {
            $length = mt_rand($length, $max);
        }
        $output = '';
        for ($i = 0;$i < $length;$i++) {
            $which = mt_rand(0, 2);
            if ($which === 0) {
                $output.= mt_rand(0, 9);
            } elseif ($which === 1) {
                $output.= chr(mt_rand(65, 90));
            } else {
                $output.= chr(mt_rand(97, 122));
            }
        }
        return $output;
    }
    function jiemi($encryptKey_host, $private_key_path) {
        $sKey = file_get_contents($private_key_path);
        openssl_private_decrypt(base64_decode($encryptKey_host), $decrypted, $sKey); //私钥解密
        return $decrypted;
    }
    function decode($str, $key) {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('rijndael_128', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        $len = strlen($str);
        $pad = ord($str[$len - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }
    function curl_daifu($url, $postdata) {
        $timeout = 60;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($postdata != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
	
	

		
		function hljc_query($arr = array()){
			
			$id = $arr['id'];
			
			 $user_drawmoney_instead = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id=".$id." LIMIT 1");

			$version = '1.0';	//M(String)	1.0
			$charset = 'UTF-8';	//M(String)	编码方式UTF-8
			$agentId = '1001023';	//M(String)	受理方预分配的渠道代理商标识识
			$nonceStr = $this->str_rand(16);	//M(String)	随机字符串，字符范围a-zA-Z0-9
			$signType = 'RSA';	//M(String)	签名方式，固定RSA
			$orderNo = $user_drawmoney_instead['order_sn'];	//M(String)	订单号
			
			
			$sign_str = "agentId=".$agentId."&charset=".$charset."&nonceStr=".$nonceStr."&orderNo=".$orderNo."&signType=".$signType."&version=".$version;
 			 error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sign_str . "\n\n", 3, './app/user/Ainstead/hljc_query_' . date('Y-m-d') . '.log');
			//$sign_str[] = $this->getBytes($sign_str);
			$sign = $this->pri_encode($sign_str);
			
			 $url = "http://39.108.137.8:8099/v1.0/facade/query";
			
			$parm = array(
			'agentId' => $agentId,
			'charset' => $charset,
			'nonceStr' => $nonceStr,
			'orderNo' => $orderNo,
			'signType' => $signType,
			'version' => $version,
			'sign' => $sign
			);
			$jsonStr = json_encode($parm);
			
			  error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . var_export($parm, true). "\n\n", 3, './app/user/Ainstead/hljc_query_' . date('Y-m-d') . '.log');
			  
            $result = $this->hljc_post($url, $parm);
            $result = json_decode($result,true);
            error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . var_export($result, true). "\n\n", 3, './app/user/Ainstead/hljc_query_' . date('Y-m-d') . '.log');

if($result['code'] == '10000'){
	  echo $result['payComment'];
	}else{
		     echo $result['message'];
		}
	
        
			}	
			
			
			function hljc_pay_daifu($arrs=array()){
				
				$id = $arrs['id'];
				 $user_drawmoney_instead = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id=".$id." LIMIT 1");
				 
				   $user_hljc_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_hljc_merchant` WHERE uid=".$user_drawmoney_instead['uid']." and bankCard='".$user_drawmoney_instead['account_no']."' LIMIT 1");
				   
			$version = '1.0';	//M(String)	1.0
			$charset = 'UTF-8';	//M(String)	编码方式UTF-8
			$agentId = '1001023';	//M(String)	受理方预分配的渠道代理商标识识
			$merId = $user_hljc_merchant['merId'];	//M(String)	子商户号
			$nonceStr = $this->str_rand(16);	//M(String)	随机字符串，字符范围a-zA-Z0-9
			$signType = 'RSA';	//M(String)	签名方式，固定RSA
			$orderNo = "QZ" . date('Ymd') . time().$user_drawmoney_instead['uid'];	//M(String)	订单号
			$notifyUrl = 'http://www.chm1688.com/m/wxpay/notify_url_hljc_daifu.php';	//M(String)	异步通知地址
			//returnUrl	//N(String)	返回地址
			$amount = $user_drawmoney_instead['amount']*100;	//M(String)	金额(分)
			
			
			$sign_str = "agentId=".$agentId."&amount=".$amount."&charset=".$charset."&merId=".$merId."&nonceStr=".$nonceStr."&notifyUrl=".$notifyUrl."&orderNo=".$orderNo."&signType=".$signType."&version=".$version;
			
			
		
			
			 error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sign_str . "\n\n", 3, './app/user/Ainstead/' . date('Y-m-d') . '.log');
			//$sign_str[] = $this->getBytes($sign_str);
			$sign = $this->pri_encode($sign_str);
			
			 $url = "http://39.108.137.8:8099/v1.0/facade/mercPay";
			
			$parm = array(
			'agentId' => $agentId,
			'amount' => $amount,
			'charset' => $charset,
			'merId' => $merId,
			'nonceStr' => $nonceStr,
			'notifyUrl' => $notifyUrl,
			'orderNo' => $orderNo,
			'signType' => $signType,
			'version' => $version,
			'sign' => $sign
			);
			$jsonStr = json_encode($parm);
			
			  error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . var_export($parm, true). "\n\n", 3, './app/user/Ainstead/' . date('Y-m-d') . '.log');
			  
            $result = $this->hljc_post($url, $parm);
            $result = json_decode($result,true);
            error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . var_export($result, true). "\n\n", 3, './app/user/Ainstead/' . date('Y-m-d') . '.log');

//if($result['code'] == '10000'){
//	  echo $result['respMessage'];
//	}else{
//		     echo $result['message'];
//		}


 if ($result['code'] == '10000') {
                if (!empty($result['respCode']) && ($result['respCode'] == '10000' || $result['respCode'] == '10002')) {
					
					$arr['order_sn'] = $orderNo;
                    $arr['state'] = 1;
                    $arr['gender'] = 1;
                    $arr['payMsgId'] = $result['orderNum'];
                    $arr['INFO_REQ_SN'] = $result['orderNum'];
                    $arr['INFO_RET_CODE'] = '0000';
                    $arr['RET_DETAILS_RET_CODE'] = $result['respMessage'];
                    $arr['RET_DETAILS_ERR_MSG'] = $result['respMessage'];
                    $arr['paytime'] = time();
                    $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                  
                } else {
					$arr['order_sn'] = $orderNo;
                    $arr['state'] = 0;
                    $arr['gender'] = 1;
                    $arr['payMsgId'] = $result['orderNum'];
                    $arr['INFO_REQ_SN'] = $result['orderNum'];
                    $arr['INFO_RET_CODE'] = '0000';
                    $arr['RET_DETAILS_RET_CODE'] = $result['respMessage'];
                    $arr['RET_DETAILS_ERR_MSG'] = $result['respMessage'];
                    $arr['paytime'] = time();
                    $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                   
                }
				
				echo $result['respMessage'];
				
            } else {
				$arr['order_sn'] = $orderNo;
                $arr['state'] = 0;
                $arr['gender'] = 1;
                $arr['payMsgId'] = '';
                $arr['INFO_REQ_SN'] = '';
                $arr['INFO_RET_CODE'] = '0000';
                $arr['RET_DETAILS_RET_CODE'] = $result['code'];
                $arr['RET_DETAILS_ERR_MSG'] = $result['message'];
                $arr['paytime'] = time();
                $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
              
			  echo $result['message'];
            }
			
			
		
				
				}
			
			
			
			function str_rand($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    if(!is_int($length) || $length < 0) {
        return false;
    }

    $string = '';
    for($i = $length; $i > 0; $i--) {
        $string .= $char[mt_rand(0, strlen($char) - 1)];
    }

    return $string;
}
	
	
	function pri_encode($data){
	$encrypted='';
	$private_key=file_get_contents('./app/orderinstead/1001023_prv.pem');	//秘钥
	$pi_key =  openssl_pkey_get_private($private_key);	//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id 
	$str='';
	foreach (str_split($data, 117) as $chunk) {
		openssl_private_encrypt($chunk,$encryptedTemp,$pi_key);  //私钥加密  
        $str .= $encryptedTemp;
	}
	$encrypted = base64_encode($str);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
	return $encrypted;
}
	
	
	function hljc_post($url, $post_data = '', $timeout = 60){//curl

    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);

    curl_setopt ($ch, CURLOPT_POST, 1);

    if($post_data != ''){

      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

    }

    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    $file_contents = curl_exec($ch);

    curl_close($ch);

    return $file_contents;

  }				
  
  
  
  
}

?>