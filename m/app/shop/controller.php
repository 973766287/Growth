<?php
class ShopController extends Controller {
    //构造函数，自动新建对象
    function __construct() {
        /*
        
         * 构造函数，自动新建session对象
        
        */
    }
  
  
    
    
    
    function return_daili_uid($uid = 0, $k = 0) {
        if (!($uid > 0)) {
            return 0;
        }
        $puid = 0;
        for ($i = 0;$i < 20;$i++) {
            $sql = "SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = '$uid' LIMIT 1";
            $p = $this->App->findvar($sql);
            if ($p > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$p' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != 1) {
                    $puid = $p;
                    break;
                } else {
                    $uid = $p;
                }
            }
        }
        return $puid;
    }
    function return_instead_daili_uid($uid = 0) {
        if (!($uid > 0)) {
            return 0;
        }
        $p = $this->App->findvar("SELECT dl_3_id FROM `{$this->App->prefix() }daili_invitecode` WHERE uid = '$uid' LIMIT 1");
        if ($p > 0) {
            return $p;
        } else {
            $p = $this->App->findvar("SELECT dl_2_id FROM `{$this->App->prefix() }daili_invitecode` WHERE uid = '$uid' LIMIT 1");
            if ($p > 0) {
                return $p;
            } else {
                $p = $this->App->findvar("SELECT dl_1_id FROM `{$this->App->prefix() }daili_invitecode` WHERE uid = '$uid' LIMIT 1");
                if ($p > 0) {
                    return $p;
                } else {
                    return 0;
                }
            }
        }
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

    function random($length) {
        for ($i = 0;$i < $length;$i++) {
            $output.= mt_rand(1, 9);
        }
        return $output;
    }
    function return_bank_no($card_id) {
        $bank_no = $this->App->findvar("SELECT bank_no FROM `{$this->App->prefix() }user_card_instead` WHERE id = " . $card_id . " LIMIT 1");
        return $bank_no;
    }
    function _get_payinfo($id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
        return $rt;
    }
   
  
    //支付成功改变支付状态
    function pay_successs_status_api($rt = array()) {
        @set_time_limit(300); //最大运行时间
        $order_sn = $rt['order_sn'];
        $status = $rt['status'];
        $orderdesc = $rt['orderdesc'];
        if (empty($order_sn)) return false;
        //购买用户返积分
        //上三级返佣金
        $pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_sn='$order_sn' LIMIT 1");
        $tt = "false";
        if ($pay_status != '1') {
            //检查
            $tt = "true";
        } else { //已经支付了的
            return true;
        }
        if ($tt == 'true' && $status == '1' && !empty($order_sn)) {
            $pu = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_sn='$order_sn' LIMIT 1");
            if (empty($pu)) {
                return false;
            }
            //$moeys = $pu['order_amount']*5/10000; //消费反润
            $order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
            $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
            $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
            $pay_id = isset($pu['pay_id']) ? $pu['pay_id'] : 0;
            $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
            $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
            $feilv = $pu['feilv'];
            //计算手续费
            $koulv = $feilv;
            $shouxufei = $order_amount * ($koulv / 10000) + $sxf_api;
            $sy_money = $order_amount - $shouxufei;
            $shengyu = sprintf("%.2f", substr(sprintf("%.3f", $sy_money), 0, -1));
            //初始化上三级扣率 2016-10-07 9:23
            $koulv1 = '0';
            $koulv2 = '0';
            $koulv3 = '0';
            //购买用户
            $nickname = $ni['nickname'];
            $dd = array();
            $dd['order_status'] = '2';
            $dd['pay_status'] = '1';
            $dd['orderdesc'] = $orderdesc;
            $dd['pay_time'] = mktime();
            $this->App->update('goods_order_info_instead', $dd, 'order_sn', $order_sn);
            //计算资金，便于下面返佣
            $sendrt_money = array();
            $sql = "SELECT * FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid=" . $uid . " LIMIT 1";
            $fenxiao = $this->App->findrow($sql);
            $p1_uid = isset($fenxiao['p1_uid']) ? $fenxiao['p1_uid'] : 0;
            $p2_uid = isset($fenxiao['p2_uid']) ? $fenxiao['p2_uid'] : 0;
            $p3_uid = isset($fenxiao['p3_uid']) ? $fenxiao['p3_uid'] : 0;
            //一级返佣金
            if ($p1_uid > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank > $ni['user_rank']) {
                    $feilv1 = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $rank . " LIMIT 1");
                    $feilv1 = unserialize($feilv1);
                    $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
                    //计算分成
                    $koulv1 = $feilv1[$pay_fangshi];
                    $moeys = $order_amount * (($koulv - $koulv1) / 10000);
                    if ($moeys) {
                        $record['puid1_money'] = $moeys;
                        $record['p_uid1'] = $p1_uid;
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix() }user` SET `fenrun` = `fenrun`+" . $moeys . ",`mymoney` = `mymoney`+" . $moeys . " WHERE user_id = " . $p1_uid;
                        $this->App->query($sql);
                        $sql = "UPDATE `{$this->App->prefix() }user_moneys` SET `fenrun` = `fenrun`+" . $moeys . " WHERE uid = " . $p1_uid;
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $p1_uid, 'level' => '1'));
                        //发送推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1");
                        $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
                    }
                }
            }
            //二级返佣金
            if ($p2_uid > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1";
                $rank_p1_uid = $this->App->findvar($sql);
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$p2_uid' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if (($rank > $ni['user_rank']) and ($rank > $rank_p1_uid)) {
                    $feilv2 = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $rank . " LIMIT 1");
                    $feilv2 = unserialize($feilv2);
                    $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
                    //计算分成 edit 2016-10-07 9:41
                    $koulv2 = $feilv2[$pay_fangshi];
                    if ($koulv1 > 0) {
                        $moeys = $order_amount * ((($koulv - $koulv2) - ($koulv - $koulv1)) / 10000);
                    } else {
                        $moeys = $order_amount * ((($koulv - $koulv2)) / 10000);
                    }
                    if ($moeys > 0) {
                        $record['puid2_money'] = $moeys;
                        $record['p_uid2'] = $p2_uid;
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix() }user` SET `fenrun` = `fenrun`+" . $moeys . ",`mymoney` = `mymoney`+" . $moeys . " WHERE user_id = '$p2_uid'";
                        $this->App->query($sql);
                        $sql = "UPDATE `{$this->App->prefix() }user_moneys` SET `fenrun` = `fenrun`+" . $moeys . " WHERE uid = " . $p2_uid;
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $p2_uid, 'level' => '2'));
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$p2_uid' LIMIT 1");
                        $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
                    }
                }
            }
            //三级返佣金
            if ($p3_uid > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1";
                $rank_p1_uid = $this->App->findvar($sql);
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $p2_uid . " LIMIT 1";
                $rank_p2_uid = $this->App->findvar($sql);
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$p3_uid' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if (($rank > $ni['user_rank']) and ($rank > $rank_p2_uid) and ($rank > $rank_p1_uid)) { //不是普通会员
                    $feilv3 = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $rank . " LIMIT 1");
                    $feilv3 = unserialize($feilv3);
                    $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
                    //计算分成 edit 2016-10-07 9:41
                    $koulv3 = $feilv3[$pay_fangshi];
                    if ($koulv2 > 0 && $koulv1 > 0) {
                        $moeys = $order_amount * ((($koulv - $koulv3) - ($koulv - $koulv2) - ($koulv - $koulv1)) / 10000);
                    } elseif ($koulv2 > 0 && $koulv1 == 0) {
                        $moeys = $order_amount * ((($koulv - $koulv3) - ($koulv - $koulv2)) / 10000);
                    } elseif ($koulv2 == 0 && $koulv1 > 0) {
                        $moeys = $order_amount * ((($koulv - $koulv3) - ($koulv - $koulv1)) / 10000);
                    } elseif ($koulv2 == 0 && $koulv1 == 0) {
                        $moeys = $order_amount * ((($koulv - $koulv3)) / 10000);
                    }
                    if ($moeys > 0) {
                        $record['puid3_money'] = $moeys;
                        $record['p_uid3'] = $p3_uid;
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix() }user` SET `fenrun` = `fenrun`+" . $moeys . ",`mymoney` = `mymoney`+" . $moeys . " WHERE user_id = '$p3_uid'";
                        $this->App->query($sql);
                        $sql = "UPDATE `{$this->App->prefix() }user_moneys` SET `fenrun` = `fenrun`+" . $moeys . " WHERE uid = " . $p3_uid;
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $p3_uid, 'level' => '3'));
                        //发送三级推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$p3_uid' LIMIT 1");
                        $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
                    }
                }
            }
            //返佣金提醒
             $mone = array();
                        if (!empty($sendrt_money)) foreach ($sendrt_money as $mone) {
                            $this->action('api', 'send', array('openid' => $mone['wecha_id'], 'appid' => '', 'appsecret' => '', 'nickname' => $mone['nickname'], 'money' => $mone['money'], 'order_sn' => $mone['order_sn']), $mone['type']);
                        }
            unset($sendrt_money);
        } //end if
        return true;
    }
    function auto_instead() {
        $cronkey = '';
        $verifykey = $_GET["key"];
        $test = $this->kft_daikou_order();
        $fase = $this->kft_pay_order();
        var_dump($test)."\n";
        var_dump($fase);die();
		if($cronkey == $verifykey){ //深圳汇联金创
		 $pay = $this->_get_payinfo(25);
            if ($pay['enabled'] == 1) {
        		$this->kft_daikou_order();
                $this->kft_pay_order();
			}else{
                exit();
			}
		}
    }


	
	



	//汇联金创
	function perform_instead_plan_pay_hljc(){
		
		$instead_plans = $this->App->find("SELECT *  FROM `{$this->App->prefix() }user_card_instead_plans` WHERE user_id > 0 and card_id>0   and status=1 and stop=0 and is_perform_auto=0 and kou_time < unix_timestamp(now())");
		
		foreach ($instead_plans as $row) {
			
			//if($row['user_id'] == 2) { //测试
			//if($row['plan_no'] != 'PLAN15181837402'){
			
            $pay_info = $this->App->findrow("SELECT pay_name,pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=25 LIMIT 1");
            $pay_name = $pay_info['pay_name'];
            $pay_code = $pay_info['pay_code'];
            $daili_uid = $this->return_daili_uid($row['user_id']); //一级
            $instead_daili_uid = $this->return_instead_daili_uid($row['user_id']);
            $bank_no = $this->return_bank_no($row['card_id']);
            $orderdata = array('order_sn' => "QZ" . date('Ymd') . time() . $row['user_id'], 'plan_id' => $row['id'], 'user_id' => $row['user_id'], 'daili_uid' => $instead_daili_uid, 'parent_uid' => $daili_uid, 'pay_id' => 25, 'pay_name' => $pay_name, 'order_amount' => $row['kou_money'], 'add_time' => mktime(), 'feilv' => $row['feilv'], 'sxf_instead' => $row['tixian'], 'bank_no' => $bank_no);
            
            if ($this->App->insert('goods_order_info_instead', $orderdata)) { //订单成功后
                $iid = $this->App->iid();
                $this->kuaijie_dh_hljc($iid, 25);
            }
			  //}
			//}//测试
        }
	

		}
		
        //快付通代还订单
        function kft_pay_order(){
            
            $instead_plans = $this->App->find("SELECT *  FROM `{$this->App->prefix() }user_card_instead_plans` WHERE user_id>0 and card_id>0 and status=2 and  is_perform_auto=1 and huan_time < unix_timestamp(now()) and stop=0");

            foreach ($instead_plans as $row) {
                //if ($row['user_id'] == 2) { //测试
                
                    // $goods_order_info_instead = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE plan_id=".$row['id']." LIMIT 1");//查询计划订单是否扣款成功
                     
                    // $pay_result = $this->hljc_query($goods_order_info_instead['order_sn']);//查询订单状态
                     
                    //if($goods_order_info_instead['pay_status'] == 1){
                    //if(!empty($goods_order_info_instead) && $pay_result == 'success'){
                        
                    $bankinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid = " . $row['user_id'] . " and id=" . $row['card_id'] . " LIMIT 1");
                    $rrr = $this->App->findrow("SELECT name,code FROM `{$this->App->prefix() }bank` WHERE id=" . $bankinfo['bank'] . " LIMIT 1");
                    $dd = array();
                    $dd['uid'] = $row['user_id'];
                    $dd['order_sn'] = 'QZ' . date('Ymd', time()) .time(). $row['user_id'];
                    $dd['plan_id'] = $row['id'];
                    $dd['amount'] = $row['huan_money'];
                    $dd['addtime'] = mktime();
                    $dd['date'] = date('Y-m', mktime());
                    $dd['bankname'] = $rrr['name'];
                    $dd['bank_code'] = $rrr['code'];
                    $dd['mobile'] = $bankinfo['mobile'];
                    $dd['account_name'] = $bankinfo['name'];
                    $dd['account_no'] = $bankinfo['bank_no'];
                    $dd['key'] = 'Instead';
                    $dd['idcard'] = $bankinfo['idcard'];
                    if ($this->App->insert('user_drawmoney_instead', $dd)) {
                        $id = $iid = $this->App->iid();
                        $this->kft_pay($id, 3);
                    }
                    
                    //}//查询计划订单是否扣款成功
                //} //测试
               
                
            }

        }
        //快付通代还
        function kft_pay($id,$pay_id){
            header("Content-type:text/html; charset=UTF-8");
            require_once('lib/Sign.php');

            $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE id=".$id." LIMIT 1");

            $plan_id = $rt['plan_id'];

            // $orderInfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_id=".$iid." LIMIT 1");

            $rts = $this->_get_payinfo($pay_id);
            $pay = unserialize($rts['pay_config']);
            $merchantId = $pay['pay_no'];
            $orderNo    = $rt['order_sn'];
            $tradeTime  = date('YmdHms',time());
            $amount     = $rt['amount'] * 100;
            $uid        = $rt['uid'];
            $bankInfo   = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid=".$uid." LIMIT 1");
            $bank       = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank_info` WHERE id=".$bankInfo['bank']." LIMIT 1");
            $custBankNo = substr($bank['bankno'],0, 7);
            $custBankAccountNo = $bankInfo['bank_no'];
            $custBindPhoneNo   = $bankInfo['mobile'];
            $custName   = $bankInfo['name'];
            $custID     = $bankInfo['idcard'];
            $custCardCvv2      = $bankInfo['cvn2'];
            $custCardValidDate = $bankInfo['valid'];
            $bs_params  = array(
                'service' => 'gbp_same_id_credit_card_pay',
                //请求编号,可空
                'reqNo'   => 'KFT0987654321',
                //接口版本号
                'version' => '1.0.0-IEST',
                //参数字符集
                'charset' => 'utf-8',
                //语言
                'language'=> 'zh_CN',
                //参数签名算法

                'callerIp'=> '127.0.0.1',
            );
            //业务参数
            $yw_params = array(
                "merchantId" => $merchantId,
                "productNo"  => "GBPTM002",
                "orderNo" => $orderNo,
                "tradeName" => '酒店预订',
                "tradeTime" => $tradeTime,
                "amount"  => $amount,
                "currency" => "CNY",
                "custBankNo" => $custBankNo,
                "custBankAccountNo" => $custBankAccountNo,
                "custBindPhoneNo" => $custBindPhoneNo,
                "custName"  => $custName,
                "custAccountCreditOrDebit" => "2",
                "custCertificationType" => "0",
                "custID" => $custID,
                "custCardValidDate" => $custCardValidDate,
                "custCardCvv2" => $custCardCvv2

            );
            
            $params = array_merge($bs_params, $yw_params);
            
            error_log('['. date('Y-m-d H:m:s') .'] request message:'."\n".var_export($params,true)."\n\n",3,'./app/shop/error_log/'."huan_".date('Y-m-d').'.log');

            $pfx_path = ADMIN_URL.'app/shop/account/pfx.pfx';

            //测试url
            $request_trade_url = "https://218.17.35.123:6443/gateway/nonbatch";

            $sign = new Sign($pfx_path, '123456');
            //普通交易请求
            $sign_data = $sign->sign_data($params);

            // echo $sign_data;
            $response_data = $sign->request_kft($params, $sign_data, $request_trade_url, false);

            $result = json_decode($response_data,true);

            error_log('['. date('Y-m-d H:m:s') .'] response message:'."\n".var_export($response_data,true)."\n\n"."<------------------------------------------------------------------------------------------------------------->"."\n",3,'./app/shop/error_log/'."huan_".date('Y-m-d').'.log');

            // if($result['status'] ==1){
            //     $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_id=".$iid." LIMIT 1");
            //     $plan_id = $rt['plan_id'];
            //     $is_plan = array('status' => 2, 'is_perform_auto' => 1);
            //     $rel = $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
            //     $sd = array('order_sn' => $rt['order_sn'], 'status' => 1 ,'orderdesc' => $result['failureDetails']);
            //     $rel = $this->pay_successs_status_api($sd);
            // }else{
            //     var_dump($result);
            // }

            if ($result['status'] == 1) {
                if (!empty($result['status'])) {
                    $arr['state'] = 1;
                    $arr['gender'] = 1;
                    $arr['payMsgId'] = $result['orderNo'];
                    $arr['INFO_REQ_SN'] = $result['orderNo'];
                    $arr['INFO_RET_CODE'] = '0000';
                    $arr['RET_DETAILS_RET_CODE'] = $result['errorCode'];
                    $arr['RET_DETAILS_ERR_MSG'] = $result['failureDetails'];
                    $arr['paytime'] = time();
                    $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                    $is_plan = array('status' => 3, 'is_perform_auto' => 2);
                    $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                } else {
                    $arr['state'] = 0;
                    $arr['gender'] = 1;
                    $arr['payMsgId'] = $result['orderNo'];
                    $arr['INFO_REQ_SN'] = $result['orderNo'];
                    $arr['INFO_RET_CODE'] = '0000';
                    $arr['RET_DETAILS_RET_CODE'] = $result['errorCode'];
                    $arr['RET_DETAILS_ERR_MSG'] = $result['failureDetails'];
                    $arr['paytime'] = time();
                    $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                    $is_plan = array('status' => 2, 'is_perform_auto' => 2);
                    $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                }
            } else {
                $arr['state'] = 0;
                $arr['gender'] = 1;
                $arr['payMsgId'] = '';
                $arr['INFO_REQ_SN'] = '';
                $arr['INFO_RET_CODE'] = '0000';
                $arr['RET_DETAILS_RET_CODE'] = $result['errorCode'];
                $arr['RET_DETAILS_ERR_MSG'] = $result['failureDetails'];
                $arr['paytime'] = time();
                $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                $is_plan = array('status' => 2, 'is_perform_auto' => 2);
                $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
            }

        }
		

		function   kuaijie_dh_hljc($iid, $pay_id){
			
				
		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_id=".$iid." LIMIT 1");
		$rts = $this->_get_payinfo($pay_id);
        $pay = unserialize($rts['pay_config']);
		  
		$plan_id = $rt['plan_id'];
		$user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=".$rt['user_id']." LIMIT 1");
		$bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=".$user_bank['bank']." LIMIT 1");
		  
		$bankclass = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bankclass` WHERE code=".$bank['code']." LIMIT 1");
		  
		$user_hljc_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_hljc_merchant` WHERE uid=".$rt['user_id']." and bankCard='".$rt['bank_no']."' LIMIT 1");
		  
		$sql = "SELECT * FROM `{$this->App->prefix() }user_hljc_merchant` WHERE uid=".$rt['user_id']." and bankCard='".$rt['bank_no']."' LIMIT 1";
		  
		   error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sql . "\n\n", 3, './app/shop/Instead_hljc/' . date('Y-m-d') . '.log');
		  
		   $card = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid=".$rt['user_id']." and bank_no=" . $rt['bank_no'] . " LIMIT 1");
		  
		  
	        $version = '1.0';	//M(String)	1.0
			$charset = 'UTF-8';	//M(String)	编码方式UTF-8
			$agentId = $pay['pay_no'];	//M(String)	受理方预分配的渠道代理商标识识
			$merId = $user_hljc_merchant['merId'];	//M(String)	子商户号
			$nonceStr = $this->str_rand(16);	//M(String)	随机字符串，字符范围a-zA-Z0-9
			$signType = 'RSA';	//M(String)	签名方式，固定RSA
			//$sign	//M(String)	签名数据
			$orderNo = $rt['order_sn'];	//M(String)	订单号
			$isCompay = '0';	//M(String)	对公对私标识0为对私，1为对公
			$idcardType = '01';	//M(String)	证件类型 暂只支持 01 身份证
			$idcard = $user_hljc_merchant['idcard'];	//M(String)	证件号码
			$name = $user_hljc_merchant['name'];	//M(String)	姓名
			$phone = $user_hljc_merchant['phone'];	//M(String)	手机号
			$bankId = $user_hljc_merchant['bankId'];	//M(String)	联行号
			$bankCard = $user_hljc_merchant['bankCard'];	//M(String)	银行卡号
			$notifyUrl = $pay['pay_address']; 	//M(String)	异步通知地址
			//$returnUrl = '';	//N(String)	返回地址
			$CVN2 = $card['cvn2'];	//M(String)	CVN2
			$expDate = substr($card['valid'],0,2)."-".substr($card['valid'],2,2);	//M(String)	信用卡有效期，格式 MM-yy
			$amount = $rt['order_amount']*100;	//M(String)	金额(分)

$sign_str = "CVN2=".$CVN2."&agentId=".$agentId."&amount=".$amount."&bankCard=".$bankCard."&bankId=".$bankId."&charset=".$charset."&expDate=".$expDate."&idcard=".$idcard."&idcardType=".$idcardType."&isCompay=".$isCompay."&merId=".$merId."&name=".$name."&nonceStr=".$nonceStr."&notifyUrl=".$notifyUrl."&orderNo=".$orderNo."&phone=".$phone."&signType=".$signType."&version=".$version;
			
			
		
			
			 error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sign_str . "\n\n", 3, './app/shop/Instead_hljc/' . date('Y-m-d') . '.log');
			$sign = $this->pri_encode($sign_str);
			
			 $url = "http://39.108.137.8:8099/v1.0/facade/pay";
//            error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $data . "\n\n", 3, './app/shopping/Ainstead/' . date('Y-m-d') . '.log');
            //  echo post($url,$data);
			
			//$data = "agentId=".$agentId."&bankCard=".$bankCard."&bankId=".$bankId."&bankName=".$bankName."&bankNo=".$bankNo."&charset=".$charset."&extraFee=".$extraFee."&idcard=".$idcard."&idcardType=".$idcardType."&isCompay=".$isCompay."&name=".$name."&nonceStr=".$nonceStr."&phone=".$phone."&rate=".$rate."&signType=".$signType."&version=".$version."&sign=".$sign;
			
			$parm = array(
			'agentId' => $agentId,
			'amount' => $amount,
			'bankCard' => $bankCard,
			'bankId' => $bankId,
			'charset' => $charset,
			'CVN2' => $CVN2,
			'expDate' => $expDate,
			'idcard' => $idcard,
			'idcardType' => $idcardType,
			'isCompay' => $isCompay,
			'merId' => $merId,
			'name' => $name,
			'nonceStr' => $nonceStr,
			'notifyUrl' => $notifyUrl,
			'phone' => $phone,
			'orderNo' => $orderNo,
			'signType' => $signType,
			'version' => $version,
			'sign' => $sign
			);
			$jsonStr = json_encode($parm);
			error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $jsonStr. "\n\n", 3, './app/shop/Instead_hljc/' . date('Y-m-d') . '.log');
			
			  error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . var_export($parm, true). "\n\n", 3, './app/shop/Instead_hljc/' . date('Y-m-d') . '.log');
			  
            $result = $this->hljc_post($url, $parm);
            $result = json_decode($result,true);
            error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . var_export($result, true). "\n\n", 3, './app/shop/Instead_hljc/' . date('Y-m-d') . '.log');



 if ($result['code'] == '10000') {
            if (!empty($result['respCode']) && ($result['respCode'] == '10000' || $result['respCode'] == '10002')) {
                $is_plan = array('status' => 2, 'is_perform_auto' => 1);
                $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                       $sd = array('order_sn' => $rt['order_sn'], 'status' => 1 ,'orderdesc' => $result['respMessage']);
                            $this->pay_successs_status_api($sd);
				// $dd = array('orderdesc' => $result['respMessage']);
//                			$this->App->update('goods_order_info_instead', $dd, 'order_sn', $rt['order_sn']);
                
            } else {
                $is_plan = array('status' => 1, 'is_perform_auto' => 1);
                $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                   $dd = array('orderdesc' => $result['respMessage']);
                			$this->App->update('goods_order_info_instead', $dd, 'order_sn', $rt['order_sn']);
                
            }
        } else {
            $is_plan = array('status' => 1, 'is_perform_auto' => 1);
            $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
              $dd = array('orderdesc' => $result['message']);
            			$this->App->update('goods_order_info_instead', $dd, 'order_sn', $rt['order_sn']);
            
        }
			//if($result['code'] == '10000'){
//			
//			}else{
//			
//			
//			}
			
			}
			
			
			function perform_instead_plan_daifu_hljc() {
                $instead_plans = $this->App->find("SELECT *  FROM `{$this->App->prefix() }user_card_instead_plans` WHERE user_id>0 and card_id>0 and status=2 and  is_perform_auto=1 and huan_time < unix_timestamp(now()) and stop=0");

                foreach ($instead_plans as $row) {
                    //if ($row['user_id'] == 2) { //测试
        			
        				// $goods_order_info_instead = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE plan_id=".$row['id']." LIMIT 1");//查询计划订单是否扣款成功
        				 
        				// $pay_result = $this->hljc_query($goods_order_info_instead['order_sn']);//查询订单状态
        				 
        				//if($goods_order_info_instead['pay_status'] == 1){
        				//if(!empty($goods_order_info_instead) && $pay_result == 'success'){
        					
                        $bankinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid = " . $row['user_id'] . " and id=" . $row['card_id'] . " LIMIT 1");
                        $rrr = $this->App->findrow("SELECT name,code FROM `{$this->App->prefix() }bank` WHERE id=" . $bankinfo['bank'] . " LIMIT 1");
                        $dd = array();
                        $dd['uid'] = $row['user_id'];
                        $dd['order_sn'] = 'QZ' . date('Ymd', time()) .time(). $row['user_id'];
                        $dd['plan_id'] = $row['id'];
                        $dd['amount'] = $row['huan_money'];
                        $dd['addtime'] = mktime();
                        $dd['date'] = date('Y-m', mktime());
                        $dd['bankname'] = $rrr['name'];
                        $dd['bank_code'] = $rrr['code'];
                        $dd['mobile'] = $bankinfo['mobile'];
                        $dd['account_name'] = $bankinfo['name'];
                        $dd['account_no'] = $bankinfo['bank_no'];
                        $dd['key'] = 'Instead';
                        $dd['idcard'] = $bankinfo['idcard'];
                        if ($this->App->insert('user_drawmoney_instead', $dd)) {
                            $id = $iid = $this->App->iid();
                            $dd['extra_fee'] = $row['tixian'];
                            $this->daifupay_yinlian_dh_hljc($dd, $id);
                        }
        				
        				//}//查询计划订单是否扣款成功
                    //} //测试
        		   
                    
                }

                
    }
    //快付通代扣订单
    function kft_daikou_order() {

        $instead_plans = $this->App->find("SELECT *  FROM `{$this->App->prefix() }user_card_instead_plans` WHERE user_id > 0 and card_id>0   and status=1 and stop=0 and is_perform_auto=0 and kou_time < unix_timestamp(now())");

                foreach ($instead_plans as $row) {
                    
                    //if($row['user_id'] == 2) { //测试
                    //if($row['plan_no'] != 'PLAN15181837402'){
                    
                    $pay_info = $this->App->findrow("SELECT pay_name,pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=3 LIMIT 1");
                    $pay_name = $pay_info['pay_name'];
                    $pay_code = $pay_info['pay_code'];
                    $daili_uid = $this->return_daili_uid($row['user_id']); //一级
                    $instead_daili_uid = $this->return_instead_daili_uid($row['user_id']);
                    $bank_no = $this->return_bank_no($row['card_id']);
                    $orderdata = array('order_sn' => "QZ" . date('Ymd') . time() . $row['user_id'], 'plan_id' => $row['id'], 'user_id' => $row['user_id'], 'daili_uid' => $instead_daili_uid, 'parent_uid' => $daili_uid, 'pay_id' => 25, 'pay_name' => $pay_name, 'order_amount' => $row['kou_money'], 'add_time' => mktime(), 'feilv' => $row['feilv'], 'sxf_instead' => $row['tixian'], 'bank_no' => $bank_no);

                    if ($this->App->insert('goods_order_info_instead', $orderdata)) { //订单成功后
                        $iid     = $this->App->iid();
                        $card_id = $row['card_id'];
                        $rel = $this->kft_daikou($iid , $card_id);

                    } 
                      //}
                    //}//测试

                }
    }

    //快付通代扣
    function  kft_daikou($iid, $card_id){
        
        $orderinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_id=".$iid." LIMIT 1");

        $plan_id   = $orderinfo['plan_id'];

        $bankInfo  = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE id = ".$card_id." LIMIT 1");

        $uid = $bankInfo['uid'];
        
        $rate = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` WHERE user_id=".$uid." AND id =".$plan_id." LIMIT 1");

        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank_info` WHERE id=".$bankInfo['bank']." LIMIT 1");
        $bankType   = substr($bank['bankno'],0, 7);
        $rts  = $this->_get_payinfo(3);
        $pay  = unserialize($rts['pay_config']);
        $merchantId = $pay['pay_no'];//商户ID
        $treatyNo   = $bankInfo['treatyId'];//代扣协议号
        $name       = $bankInfo['name'];
        $orderNo    = $orderinfo['order_sn']; //M(String) 订单号
        $amount     = $orderinfo['order_amount']*100;    //M(String) 金额(分)
        $bankCardNo = $bankInfo['bank_no'];
        $rateAmount = round(($orderinfo['order_amount'] * 0.44/100 + 0.5),2) * 100;
        $custCardCvv2 = $bankInfo['cvn2'];
        $custCardValidDate = $bankInfo['valid'];
        if($bankInfo['is_first_pay'] == 0){//如果是第一次代扣加0.5元手续费
            $rateAmount += 50; 
        }
        header("Content-type:text/html; charset=UTF-8");
        require_once('lib/Sign.php');
        $bs_params  = array(
            'service' => 'gbp_same_id_credit_card_treaty_collect',
            //请求编号,可空
            'reqNo' => 'KFT0987654321',
            //接口版本号
            'version' => '1.0.0-IEST',
            //参数字符集
            'charset' => 'utf-8',
            //语言
            'language' => 'zh_CN',
            //参数签名算法

            'callerIp' => '127.0.0.1',
        );
            //业务参数
        $yw_params = array(

            "merchantId" => $merchantId,
            "orderNo"    => $orderNo,
            "productNo"  => "GBPTM004",
            "treatyNo"   => $treatyNo,
            "amount"     => $amount,
            "tradeTime"  => date('YmdHms',time()),
            "currency"   => "CNY",
            "custAccountId" => '2',//银行卡类型 2 信用卡
            "holderName" => $name,
            "bankType"   => $bankType,
            "bankCardNo" => $bankCardNo,
            "rateAmount" => $rateAmount,
            "custCardValidDate" => $custCardValidDate,
            "custCardCvv2"  => $custCardCvv2
        );
        $params = array_merge($bs_params, $yw_params);
           
        error_log('['. date('Y-m-d H:m:s') .'] request message:'."\n".var_export($params,true)."\n\n",3,'./app/shop/error_log/'.'kou_'.date('Y-m-d').'.log');

        $pfx_path = ADMIN_URL.'app/shop/account/pfx.pfx';

        //测试url
        $request_trade_url = "https://218.17.35.123:6443/gateway/nonbatch";

        $sign = new Sign($pfx_path, '123456');
        //普通交易请求

        $sign_data = $sign->sign_data($params);

        // echo $sign_data;
        $response_data = $sign->request_kft($params, $sign_data, $request_trade_url, false);

        $result = json_decode($response_data,true);

        error_log('['. date('Y-m-d H:m:s') .'] response message:'."\n".var_export($response_data,true)."\n\n"."<------------------------------------------------------------------------------------------------------------->"."\n",3,'./app/shop/error_log/'.'kou_'.date('Y-m-d').'.log');

        if($result['status'] ==1){
                if($bankInfo['is_first_pay'] == 0){
                    
                    $rel = $this->App->update('user_card_instead', array('is_first_pay'=>1), 'id', $card_id);

                }
                $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info_instead` WHERE order_id=".$iid." LIMIT 1");
                $plan_id = $rt['plan_id'];
                $is_plan = array('status' => 2, 'is_perform_auto' => 1);
                $rel = $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                $sd = array('order_sn' => $rt['order_sn'], 'status' => 1 ,'orderdesc' => $result['failureDetails']);
                $rel = $this->pay_successs_status_api($sd);
            }else{
                var_dump($result);
            }


        // if ($result['status'] == 1) {
        //     if (!empty($result['status'])) {
        //         $arr['state'] = 1;
        //         $arr['gender'] = 1;
        //         $arr['payMsgId'] = $result['orderNo'];
        //         $arr['INFO_REQ_SN'] = $result['orderNo'];
        //         $arr['INFO_RET_CODE'] = '0000';
        //         $arr['RET_DETAILS_RET_CODE'] = $result['errorCode'];
        //         $arr['RET_DETAILS_ERR_MSG'] = $result['failureDetails'];
        //         $arr['paytime'] = time();
        //         $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
        //         $is_plan = array('status' => 3, 'is_perform_auto' => 2);
        //         $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
        //     } else {
        //         $arr['state'] = 0;
        //         $arr['gender'] = 1;
        //         $arr['payMsgId'] = $result['orderNo'];
        //         $arr['INFO_REQ_SN'] = $result['orderNo'];
        //         $arr['INFO_RET_CODE'] = '0000';
        //         $arr['RET_DETAILS_RET_CODE'] = $result['errorCode'];
        //         $arr['RET_DETAILS_ERR_MSG'] = $result['failureDetails'];
        //         $arr['paytime'] = time();
        //         $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
        //         $is_plan = array('status' => 2, 'is_perform_auto' => 2);
        //         $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
        //     }
        // } else {
        //     $arr['state'] = 0;
        //     $arr['gender'] = 1;
        //     $arr['payMsgId'] = '';
        //     $arr['INFO_REQ_SN'] = '';
        //     $arr['INFO_RET_CODE'] = '0000';
        //     $arr['RET_DETAILS_RET_CODE'] = $result['errorCode'];
        //     $arr['RET_DETAILS_ERR_MSG'] = $result['failureDetails'];
        //     $arr['paytime'] = time();
        //     $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
        //     $is_plan = array('status' => 2, 'is_perform_auto' => 2);
        //     $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
        // }
        
    }

	
	
	
	function  hljc_query($order_sn){
	
	        $version = '1.0';	//M(String)	1.0
			$charset = 'UTF-8';	//M(String)	编码方式UTF-8
			$agentId = '1001023';	//M(String)	受理方预分配的渠道代理商标识识
			$nonceStr = $this->str_rand(16);	//M(String)	随机字符串，字符范围a-zA-Z0-9
			$signType = 'RSA';	//M(String)	签名方式，固定RSA
			$orderNo = $order_sn;	//M(String)	订单号
			
			
			$sign_str = "agentId=".$agentId."&charset=".$charset."&nonceStr=".$nonceStr."&orderNo=".$orderNo."&signType=".$signType."&version=".$version;
 			 error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sign_str . "\n\n", 3, './app/shop/Instead_hljc/hljc_query_' . date('Y-m-d') . '.log');
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
			
			  error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . var_export($parm, true). "\n\n", 3, './app/shop/Instead_hljc/hljc_query_' . date('Y-m-d') . '.log');
			  
            $result = $this->hljc_post($url, $parm);
            $result = json_decode($result,true);
            error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . var_export($result, true). "\n\n", 3, './app/shop/Instead_hljc/hljc_query_' . date('Y-m-d') . '.log');

if($result['code'] == '10000'){
	  if($result['payStatus'] == 1){
	  return "success";
	  }else{
		     return $result['payComment'];
		}
	}else{
	return $result['message'];
	}
	
	}
	
	
	function  daifupay_yinlian_dh_hljc($dd, $id){
		
		$uid = $dd['uid'];
		$card_no = $dd['account_no'];
		
		  $plan_id = $dd['plan_id'];
		  
		 $user_hljc_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_hljc_merchant` WHERE uid=".$uid." and bankCard='".$card_no."' LIMIT 1");
		 
		 $sql = "SELECT * FROM `{$this->App->prefix() }user_hljc_merchant` WHERE uid=".$uid." and bankCard='".$card_no."' LIMIT 1";
		
		
			 error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" .  $sql . "\n\n", 3, './app/shop/Instead_hljc/daifu_' . date('Y-m-d') . '.log');
		    $version = '1.0';	//M(String)	1.0
			$charset = 'UTF-8';	//M(String)	编码方式UTF-8
			$agentId = '1001023';	//M(String)	受理方预分配的渠道代理商标识识
			$merId = $user_hljc_merchant['merId'];	//M(String)	子商户号
			$nonceStr = $this->str_rand(16);	//M(String)	随机字符串，字符范围a-zA-Z0-9
			$signType = 'RSA';	//M(String)	签名方式，固定RSA
			$orderNo = $dd['order_sn'];	//M(String)	订单号
			$notifyUrl = 'http://www.chm1688.com/m/wxpay/notify_url_hljc_daifu.php';	//M(String)	异步通知地址
			//returnUrl	//N(String)	返回地址
			$amount = $dd['amount']*100;	//M(String)	金额(分)
			
			
			$sign_str = "agentId=".$agentId."&amount=".$amount."&charset=".$charset."&merId=".$merId."&nonceStr=".$nonceStr."&notifyUrl=".$notifyUrl."&orderNo=".$orderNo."&signType=".$signType."&version=".$version;
			
			
		
			
			 error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sign_str . "\n\n", 3, './app/shop/Instead_hljc/daifu_' . date('Y-m-d') . '.log');
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
			
			  error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . var_export($parm, true). "\n\n", 3, './app/shop/Instead_hljc/daifu_' . date('Y-m-d') . '.log');
			  
            $result = $this->hljc_post($url, $parm);
            $result = json_decode($result,true);
            error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . var_export($result, true). "\n\n", 3, './app/shop/Instead_hljc/daifu_' . date('Y-m-d') . '.log');

//if($result['code'] == '10000'){
//	  echo $result['respMessage'];
//	}else{
//		     echo $result['message'];
//		}
$result['code'] = '10000';
$result['respCode'] = '10000';		
		
		 if ($result['code'] == '10000') {
                if (!empty($result['respCode']) && ($result['respCode'] == '10000' || $result['respCode'] == '10002')) {
                    $arr['state'] = 1;
                    $arr['gender'] = 1;
                    $arr['payMsgId'] = $result['orderNum'];
                    $arr['INFO_REQ_SN'] = $result['orderNum'];
                    $arr['INFO_RET_CODE'] = '0000';
                    $arr['RET_DETAILS_RET_CODE'] = $result['respMessage'];
                    $arr['RET_DETAILS_ERR_MSG'] = $result['respMessage'];
                    $arr['paytime'] = time();
                    $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                    $is_plan = array('status' => 3, 'is_perform_auto' => 2);
                    $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                } else {
                    $arr['state'] = 0;
                    $arr['gender'] = 1;
                    $arr['payMsgId'] = $result['orderNum'];
                    $arr['INFO_REQ_SN'] = $result['orderNum'];
                    $arr['INFO_RET_CODE'] = '0000';
                    $arr['RET_DETAILS_RET_CODE'] = $result['respMessage'];
                    $arr['RET_DETAILS_ERR_MSG'] = $result['respMessage'];
                    $arr['paytime'] = time();
                    $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                    $is_plan = array('status' => 2, 'is_perform_auto' => 2);
                    $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
                }
            } else {
                $arr['state'] = 0;
                $arr['gender'] = 1;
                $arr['payMsgId'] = '';
                $arr['INFO_REQ_SN'] = '';
                $arr['INFO_RET_CODE'] = '0000';
                $arr['RET_DETAILS_RET_CODE'] = $result['code'];
                $arr['RET_DETAILS_ERR_MSG'] = $result['message'];
                $arr['paytime'] = time();
                $this->App->update('user_drawmoney_instead', $arr, 'id', $id);
                $is_plan = array('status' => 2, 'is_perform_auto' => 2);
                $this->App->update('user_card_instead_plans', $is_plan, 'id', $plan_id);
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
	$private_key=file_get_contents('./app/shop/1001023_prv.pem');	//秘钥
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

  }  //深圳汇联金创
		
	
	
	
	
}
?>