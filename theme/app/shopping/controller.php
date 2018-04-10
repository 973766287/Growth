<?php

class ShoppingController extends Controller {
    /*
      //* @Photo Index
      //* @param <type> $page
      //* @param <type> $type
     */

    function __construct() {
        /*
         * 构造函数
         */
        $this->js(array('jquery_dialog.js', 'jquery.json-1.3.js', 'common.js', 'goods.js', 'user.js', 'tab.js'));
        $this->css(array('comman.css', 'menber.css', 'tabs.css', 'jquery_dialog.css'));
    }

    /* 析构函数 */

    function __destruct() {
        unset($rt);
    }

    function _get_payinfo($id = 0) {
        // return $this->App->findvar("SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
        if ($id == '4') { //微信支付
            $rt = $this->App->findrow("SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id' LIMIT 1");

            /* 			$appid = $this->Session->read('User.appid');
              if(empty($appid)) $appid = isset($_COOKIE[CFGH.'USER']['APPID']) ? $_COOKIE[CFGH.'USER']['APPID'] : '';
              $appsecret = $this->Session->read('User.appsecret');
              if(empty($appsecret)) $appsecret = isset($_COOKIE[CFGH.'USER']['APPSECRET']) ? $_COOKIE[CFGH.'USER']['APPSECRET'] : '';
              if(empty($appid) || empty($appsecret)){
              $sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1' LIMIT 1";
              $rts = $this->App->findrow($sql);
              $this->Session->write('User.appid',$rt['appid']);
              setcookie(CFGH.'USER[APPID]', $rt['appid'], mktime() + 3600*24);
              $this->Session->write('User.appsecret',$rt['appsecret']);
              setcookie(CFGH.'USER[APPSECRET]', $rt['appsecret'], mktime() + 3600*24);
              }else{
              $rts['appid'] = $appid;
              $rts['appsecret'] = $appsecret;
              } */

            $sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
            $rts = $this->App->findrow($sql);
            $rt['appid'] = $rts['appid'];
            $rt['appsecret'] = $rts['appsecret'];
        } else {
            $rt = $this->App->findvar("SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
        }
        return $rt;
    }

    /*  function _alipayment($rt = array()) {
      $pay_id = $rt['pay_id'];
      $sql = "SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$pay_id'";
      $pay_config = $this->App->findvar($sql);
      $configr = unserialize($pay_config);
      $paypalmail = isset($configr['pay_no']) ? $configr['pay_no'] : '';
      if (!$paypalmail) {
      return false;
      }
      $order_sn = $rt['order_sn']; //网站唯一订单编号
      $order_amount = $rt['order_amount'];
      $username = $rt['username'];
      $address = $rt['address'];
      $zip = $rt['zip'];
      $phone = $rt['phone'];
      $mobile = $rt['mobile'];
      $logistics_fee = $rt['logistics_fee'];
      if (!$paypalmail) {
      return false;
      }
      if ($paypalmail != 'yue') {
      $paypal_form = "<form name='aqua' method='post' action='" . SITE_URL . "pay/alipayapi.php'>
      <input type='hidden' name='WIDout_trade_no' value='" . $order_sn . "'>
      <input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
      <input type='hidden' name='WIDsubject' value='金葵花商城商品支付系统'>
      <input type='hidden' name='WIDprice' value='" . $order_amount . "'>
      <input type='hidden' name='WIDreceive_name' value='" . $username . "'>
      <input type='hidden' name='logistics_fee' value='" . $logistics_fee . "'>
      <input type='hidden' name='logistics_type' value='EXPRESS'>
      <input type='hidden' name='logistics_payment' value='BUYER_PAY'>
      <input type='hidden' name='WIDreceive_address' value='" . $address . "'>
      <input type='hidden' name='WIDreceive_zip' value='" . $zip . "'>
      <input type='hidden' name='WIDreceive_phone' value='" . $phone . "'>
      <input type='hidden' name='WIDreceive_mobile' value='" . $mobile . "'>
      </form>";
      $paypal_form.="<script language='javascript'>
      aqua.submit();
      </script>
      ";
      echo $paypal_form;
      die();
      }
      }
     */

    //终端支付跳转
  //  function _alipayment($rt = array()) {
//		
//		
//        $pay_id = $rt['pay_id'];
//        $order_sn = $rt['order_sn']; //网站唯一订单编号
//        $order_amount = $rt['order_amount'] + $rt['logistics_fee'];
//		
		
		//echo $pay_id;
//		echo $order_sn;
//		echo $order_amount;

//print_r( $order_sn);
    // echo count($order_sn);
//		exit;

//if(count($order_sn) > 1 ){ $order_sn = $rt['parent_sn'];}else{ $order_sn = $order_sn[0];}
//
//
//        if ($pay_id == '4') { //微信支付
//            $this->jump(SITE_URL . 'wxpay/native.php?order_sn=' . $order_sn);
//            exit;
//        }
//
//        //余额支付
//        if ($pay_id == '7') {
//            //我的余额
//            $uid = $this->Session->read('User.uid');
//            if ($uid > 0) {
//                $sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
//                $mymoney = $this->App->findvar($sql);
//            } else {
//                $oid = $this->App->findvar("SELECT order_id FROM `{$this->App->prefix()}user` WHERE order_sn='$order_sn' LIMIT 1");
//                $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
//                exit;
//            }
//            if ($mymoney >= $order_amount) {
//                $money = -$order_amount;
//                $sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney` = `mymoney`+$money WHERE user_id = '$uid'";
//                $this->App->query($sql);
//
//                $sd = array();
//                $sd = array('order_sn' => $order_sn, 'status' => 1);
//                if ($this->pay_successs_tatus2($sd)) {
//                    $sd = array();
//                    $thismonth = date('Y-m-d', mktime());
//                    $thism = date('Y-m', mktime());
//                    $sd['time'] = mktime();
//                    $sd['changedesc'] = '余额支付';
//                    $sd['money'] = $money;
//                    $sd['uid'] = $uid;
//                    $sd['buyuid'] = $uid;
//                    $sd['order_sn'] = $order_sn;
//                    $sd['thismonth'] = $thismonth;
//                    $sd['thism'] = $thism;
//                    $sd['type'] = '3';
//                    $this->App->insert('user_money_change', $sd);
//                    unset($sd);
//                    $this->jump(SITE_URL . 'user.php?act=myorder', 0, '已成功支付');
//                    exit;
//                } else {
//                    $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '意外错误！');
//                    exit;
//                }
//            } else {
//                $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
//                exit;
//            }
//        }
//
//        $sql = "SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$pay_id'";
//        $pay_config = $this->App->findvar($sql);
//        $configr = unserialize($pay_config);
//        $paypalmail = isset($configr['pay_no']) ? $configr['pay_no'] : '';
//        if (!$paypalmail) {
//            $this->jump(SITE_URL, 0, '这是货到付款方式，等待商家发货');
//            exit;
//            return false;
//        }
//
//
//        if (!$paypalmail) {
//            return false;
//        }
      //  if ($pay_id == '3') { //支付宝
            //WAP
            /*  $paypal_form = "<form name='aqua' method='post' action='" . SITE_URL . "pay/alipayapi.php'>
              <input type='hidden' name='WIDout_trade_no' value='" . $order_sn . "'>
              <input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
              <input type='hidden' name='WIDsubject' value='商城支付系统'>
              <input type='hidden' name='WIDtotal_fee' value='" . $order_amount . "'>
              </form>";
              $paypal_form.="<script language='javascript'>
              aqua.submit();
              </script>
              "; */
           /* $paypal_form = "<form name='aqua' method='post' action='" . SITE_URL . "pay/alipayapi.php'>
      <input type='hidden' name='WIDout_trade_no' value='" . $order_sn . "'>
      <input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
      <input type='hidden' name='WIDsubject' value='金葵花商城商品支付系统'>
      <input type='hidden' name='WIDprice' value='" . $order_amount . "'>
      <input type='hidden' name='WIDreceive_name' value='" . $username . "'>
      <input type='hidden' name='logistics_fee' value='" . $logistics_fee . "'>
      <input type='hidden' name='logistics_type' value='EXPRESS'>
      <input type='hidden' name='logistics_payment' value='BUYER_PAY'>
      <input type='hidden' name='WIDreceive_address' value='" . $address . "'>
      <input type='hidden' name='WIDreceive_zip' value='" . $zip . "'>
      <input type='hidden' name='WIDreceive_phone' value='" . $phone . "'>
      <input type='hidden' name='WIDreceive_mobile' value='" . $mobile . "'>
      </form>";
            $paypal_form.="<script language='javascript'>
      aqua.submit();
      </script>
      ";*/

       //     echo $paypal_form;
//        }
//
//        die();
//    }





 //终端支付跳转
    function _alipayment($rt = array()) {
        $pay_id = $rt['pay_id'];
		
	//	echo $pay_id;
//		exit;

        $order_sn = $rt['order_sn']; //网站唯一订单编号
		//$order_sn = $rt['parent_sn'];
        $order_amount = $rt['order_amount'] + $rt['logistics_fee'];


        if ($pay_id == '4') { //微信支付
            $this->jump(SITE_URL . 'wxpay/native.php?order_sn=' . $order_sn);
            exit;
        }

        //余额支付
        if ($pay_id == '7') {
            //我的余额
            $uid = $this->Session->read('User.uid');
            if ($uid > 0) {
                $sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
                $mymoney = $this->App->findvar($sql);
            } else {
                $oid = $this->App->findvar("SELECT order_id FROM `{$this->App->prefix()}user` WHERE order_sn='$order_sn' LIMIT 1");
                $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
                exit;
            }
            if ($mymoney >= $order_amount) {
                $money = -$order_amount;
                $sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney` = `mymoney`+$money WHERE user_id = '$uid'";
                $this->App->query($sql);

                $sd = array();
                $sd = array('order_sn' => $order_sn, 'status' => 1);
                if ($this->pay_successs_tatus2($sd)) {
                    $sd = array();
                    $thismonth = date('Y-m-d', mktime());
                    $thism = date('Y-m', mktime());
                    $sd['time'] = mktime();
                    $sd['changedesc'] = '余额支付';
                    $sd['money'] = $money;
                    $sd['uid'] = $uid;
                    $sd['buyuid'] = $uid;
                    $sd['order_sn'] = $order_sn;
                    $sd['thismonth'] = $thismonth;
                    $sd['thism'] = $thism;
                    $sd['type'] = '3';
                    $this->App->insert('user_money_change', $sd);
                    unset($sd);
                    $this->jump(SITE_URL . 'user.php?act=myorder', 0, '已成功支付');
                    exit;
                } else {
                    $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '意外错误！');
                    exit;
                }
            } else {
                $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
                exit;
            }
        }

        $sql = "SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$pay_id'";
        $pay_config = $this->App->findvar($sql);
        $configr = unserialize($pay_config);
        $paypalmail = isset($configr['pay_no']) ? $configr['pay_no'] : '';
        if (!$paypalmail) {
            $this->jump(SITE_URL, 0, '这是货到付款方式，等待商家发货');
            exit;
            return false;
        }


        if (!$paypalmail) {
            return false;
        }
        if ($pay_id == '3') { //支付宝
            //WAP
            /*  $paypal_form = "<form name='aqua' method='post' action='" . SITE_URL . "pay/alipayapi.php'>
              <input type='hidden' name='WIDout_trade_no' value='" . $order_sn . "'>
              <input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
              <input type='hidden' name='WIDsubject' value='商城支付系统'>
              <input type='hidden' name='WIDtotal_fee' value='" . $order_amount . "'>
              </form>";
              $paypal_form.="<script language='javascript'>
              aqua.submit();
              </script>
              "; */
            $paypal_form = "<form name='aqua' method='post' action='" . SITE_URL . "pay/alipayapi.php'>
      <input type='hidden' name='WIDout_trade_no' value='" . $order_sn . "'>
      <input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
      <input type='hidden' name='WIDsubject' value='金葵花商城商品支付系统'>
      <input type='hidden' name='WIDprice' value='" . $order_amount . "'>
      <input type='hidden' name='WIDreceive_name' value='" . $username . "'>
      <input type='hidden' name='logistics_fee' value='" . $logistics_fee . "'>
      <input type='hidden' name='logistics_type' value='EXPRESS'>
      <input type='hidden' name='logistics_payment' value='BUYER_PAY'>
      <input type='hidden' name='WIDreceive_address' value='" . $address . "'>
      <input type='hidden' name='WIDreceive_zip' value='" . $zip . "'>
      <input type='hidden' name='WIDreceive_phone' value='" . $phone . "'>
      <input type='hidden' name='WIDreceive_mobile' value='" . $mobile . "'>
      </form>";
            $paypal_form.="<script language='javascript'>
      aqua.submit();
      </script>
      ";

            echo $paypal_form;
        }

        die();
    }

    function pay_successs_tatus2($rt = array()) {
        set_time_limit(300); //最大运行时间

        $order_sn = $rt['order_sn'];
        $status = $rt['status'];

        if (empty($order_sn))
            exit;
        $order_sn = substr($order_sn, -14, 14);

        //判断是否是在线报名支付
        $sql = "SELECT id,pay_status FROM `{$this->App->prefix()}cx_baoming_order` WHERE order_sn = '$order_sn' LIMIT 1";
        $isds = $this->App->findrow($sql);
        if (!empty($isds)) {
            if ($isds['pay_status'] == '1') {
                exit;
            }
            $this->baoming_pay_successs_tatus($order_sn);
            exit;
        }

        //购买用户返积分
        //上三级返佣金

        $pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        $tt = "false";
        if ($pay_status != '1') {
            //检查
            $sql = "SELECT cid FROM `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'"; //资金
            $cid = $this->App->findvar($sql);
            if ($cid > 0) {
                return true;
                exit;
            } else {
                $sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'"; //积分
                $cid = $this->App->findvar($sql);
                if ($cid > 0) {
                    return true;
                    exit;
                } else {
                    $tt = "true";
                }
            }
        } else {//已经支付了的
            return true;
            exit;
        }

        if ($tt == 'true' && $status == '1' && !empty($order_sn)) {
            $pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if (empty($pu)) {
                exit;
            }
            $parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
            $parent_uid2 = isset($pu['parent_uid2']) ? $pu['parent_uid2'] : 0; //分享者
            $parent_uid3 = isset($pu['parent_uid3']) ? $pu['parent_uid3'] : 0; //分享者
            $parent_uid4 = isset($pu['parent_uid4']) ? $pu['parent_uid4'] : 0; //分享者
            $user_id = isset($pu['user_id']) ? $pu['user_id'] : 0; //分享者

            $daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
            $moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
            $order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
            $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
            $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;

            $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;

            //改变销量
            $sql = "SELECT goods_id,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id = '$order_id'";
            $rd = $this->App->find($sql);
            if (!empty($rd))
                foreach ($rd as $rdd) {
                    $gid = $rdd['goods_id'];
                    $number = $rdd['goods_number'];
                    $sql = "UPDATE `{$this->App->prefix()}goods` SET `sale_count` = `sale_count`+'$number' , `goods_number` = `goods_number`- '$number' WHERE goods_id = '$gid' LIMIT 1";
                    $this->App->query($sql);
                }
            unset($rd);

            //购买用户
            $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
            $nickname = $ni['nickname'];
            $dd = array();
            $dd['order_status'] = '2';
            $dd['pay_status'] = '1';
            $dd['pay_time'] = mktime();
            $this->App->update('goods_order_info', $dd, 'order_sn', $order_sn);
            //
            $quid = $this->App->findvar("SELECT MAX(quid) FROM `{$this->App->prefix()}user` LIMIT 1");
            $this->App->update('user', array('quid' => ($quid + 1)), 'user_id', $uid);

            //自动升级
            $sql = "SELECT lid,money FROM `{$this->App->prefix()}user_level` ORDER BY money DESC";
            $sj = $this->App->find($sql);
            $zmo = $this->App->findvar("SELECT order_amount FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND pay_status='1' LIMIT 1"); //总消费
            $ra = $ni['user_rank'];
            $raa = $ra;
            if (!empty($sj))
                foreach ($sj as $item) {
                    $mo = $item['money'];
                    $lid = $item['lid'];
                    /*  if ($zmo >= $mo && $mo > 0) {
                      if ($ra == '1') { //普通会员升级为分销
                      $ra = $lid;
                      $this->App->update('user', array('user_rank' => $lid), 'user_id', $uid);
                      break;
                      } elseif ($ra == '12' && $lid != '1' && $lid < 12) {
                      $ra = $lid;
                      $this->App->update('user', array('user_rank' => $lid), 'user_id', $uid);
                      break;
                      } elseif ($ra == '11' && $lid != '1' && $lid < 11) {
                      $ra = $lid;
                      $this->App->update('user', array('user_rank' => $lid), 'user_id', $uid);
                      break;
                      }
                      } */
                }
            unset($ni, $sj);
            $newrank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
            if ($raa == '1' && $newrank != '1') {//变更升级
                $this->update_daili_tree($uid); //更新代理关系
            }

            $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1"; //用户配置信息
            $rts = $this->App->findrow($sql);
            $openfx_minmoney = empty($rts['openfx_minmoney']) ? 0 : intval($rts['openfx_minmoney']);

            if ($rts['openfxbuy'] == '1' && $zmo >= $openfx_minmoney) {
                //付款开通代理  兼容旧版
                if ($uid > 0) {
                    //$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1");
                    if ($newrank == '1') {
                        $this->App->update('user', array('user_rank' => '12'), 'user_id', $uid);

                        $this->update_daili_tree($uid); //更新代理关系
                    }
                }
            }

            $sendrt_point = array();
            $sendrt_money = array();

            $appid = $this->Session->read('User.appid');
            if (empty($appid))
                $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
            $appsecret = $this->Session->read('User.appsecret');
            if (empty($appsecret))
                $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';

            //计算资金，便于下面返佣
            //计算每个产品的佣金
            $sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id'";
            $moneys = $this->App->find($sql);

            //购物者根据消费金额送积分
            $pointnum = $rts['pointnum'];
            $pointnum_ag = isset($rts['pointnum_ag']) && !empty($rts['pointnum_ag']) ? format_price($rts['pointnum_ag']) : 1;
            $points = intval($order_amount * $pointnum * $pointnum_ag);
            if ($points > 0) {
                //检查是否重复返
                $sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn' AND user_id='$uid'";
                $chenkid = $this->App->findvar($sql);
                if ($chenkid > 0) {
                    return true;
                    exit;
                }

                $thismonth = date('Y-m-d', mktime());
                //购买者送积分
                $sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$uid'";
                $this->App->query($sql);
                $this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points, 'changedesc' => '消费返积分', 'time' => mktime(), 'uid' => $uid));
                //购买者返积分通知
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
                $sendrt_point[] = array('wecha_id' => $pwecha_id, 'nickname' => '', 'points' => $points, 'order_sn' => $order_sn, 'type' => 'payreturnpoints');
                //$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>'','points'=>$points,'order_sn'=>$order_sn),'payreturnpoints');
            }

            $moeysall = 0;
            if (!empty($moneys))
                foreach ($moneys as $row) {
                    if ($row['takemoney1'] > 0) {
                        $moeysall +=$row['takemoney1'] * $row['goods_number'];
                    }
                }

            //购买者返佣
            $moeys = 0;
            $thismonth = date('Y-m-d', mktime());
            $thism = date('Y-m', mktime());
            $ticheng360_1 = $rts['ticheng360_1'];
            if ($ticheng360_1 > 0) {
                $off = $ticheng360_1 / 100;
                $moeys = format_price($moeysall * $off);
                if ($moeys > 0) {
                    $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$uid'";
                    $this->App->query($sql);
                    $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '消费返佣金', 'time' => mktime(), 'uid' => $uid, 'level' => '10'));
                    if (empty($pwecha_id)) {
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
                    }
                    $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => '', 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payusereturnmoney'); //通知
                }
            }

            $record = array();
            $moeys = 0;
            //一级返佣金
            if ($parent_uid > 0) {

                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    $sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
                    $types = $this->App->findvar($sql);

                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_1'] < 101 && $rts['ticheng180_1'] > 0) {
                            $off = $rts['ticheng180_1'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0) {
                            $off = $rts['ticheng180_h1_1'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0) {
                            $off = $rts['ticheng180_h2_1'] / 100;
                        }
                    }

                    //}


                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $record['puid1_money'] = $moeys;
                        $record['p_uid1'] = $parent_uid;
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid'";
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid, 'level' => '1'));

                        //发送推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1");
                        //$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$nickname,'money'=>$moeys,'order_sn'=>$order_sn),'payreturnmoney');
                        $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');

                        //返一级积分
                        $tjpointnum = isset($rts['tjpointnum']) ? $rts['tjpointnum'] : 0;
                        $tjpointnum_ag = isset($rts['tjpointnum_ag']) && !empty($rts['tjpointnum_ag']) ? format_price($rts['tjpointnum_ag']) : 1;
                        $points2 = intval($order_amount * $tjpointnum * $tjpointnum_ag);
                        if ($points2 > 0) {
                            //送积分
                            $sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points2,`mypoints` = `mypoints`+$points2 WHERE user_id = '$parent_uid'";
                            $this->App->query($sql);
                            $this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points2, 'changedesc' => '客户消费返积分', 'time' => mktime(), 'uid' => $parent_uid));

                            $sendrt_point[] = array('wecha_id' => $pwecha_id, 'nickname' => '', 'points' => $points2, 'order_sn' => $order_sn, 'type' => 'payreturnpoints_parentuid');
                        }
                    }
                }
            }

            $moeys = 0;
            //二级返佣金
            if ($parent_uid2 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    $sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
                    $types = $this->App->findvar($sql);

                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_2'] < 101 && $rts['ticheng180_2'] > 0) {
                            $off = $rts['ticheng180_2'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0) {
                            $off = $rts['ticheng180_h1_2'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0) {
                            $off = $rts['ticheng180_h2_2'] / 100;
                        }
                    }

                    //}
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $record['puid2_money'] = $moeys;
                        $record['p_uid2'] = $parent_uid2;
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid2'";
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid2, 'level' => '2'));

                        //发送推荐用户通知
                        if ($moeys > 0) {
                            $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1");
                            //$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$nickname,'money'=>$moeys,'order_sn'=>$order_sn),'payreturnmoney');
                            $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
                        }
                        //返二级积分
                        $tjpointnum = isset($rts['tjpointnum2']) ? $rts['tjpointnum2'] : 0;
                        $tjpointnum_ag = isset($rts['tjpointnum_ag2']) && !empty($rts['tjpointnum_ag2']) ? format_price($rts['tjpointnum_ag2']) : 1;
                        $points2 = intval($order_amount * $tjpointnum * $tjpointnum_ag);

                        //送积分
                        $sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points2,`mypoints` = `mypoints`+$points2 WHERE user_id = '$parent_uid2'";
                        $this->App->query($sql);
                        $this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points2, 'changedesc' => '二级客户消费返积分', 'time' => mktime(), 'uid' => $parent_uid2));

                        $sendrt_point[] = array('wecha_id' => $pwecha_id, 'nickname' => '', 'points' => $points2, 'order_sn' => $order_sn, 'type' => 'payreturnpoints_parentuid');
                    }
                }
            }

            $moeys = 0;
            //三级返佣金
            if ($parent_uid3 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    $sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                    $types = $this->App->findvar($sql);

                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_3'] < 101 && $rts['ticheng180_3'] > 0) {
                            $off = $rts['ticheng180_3'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0) {
                            $off = $rts['ticheng180_h1_3'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0) {
                            $off = $rts['ticheng180_h2_3'] / 100;
                        }
                    }

                    //}
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $record['puid3_money'] = $moeys;
                        $record['p_uid3'] = $parent_uid3;
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid3'";
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid3, 'level' => '3'));

                        //发送三级推荐用户通知

                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1");
                        //$this->action('api','send',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$nickname,'money'=>$moeys,'order_sn'=>$order_sn),'payreturnmoney');
                        $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');

                        //返三级积分
                        $tjpointnum = isset($rts['tjpointnum3']) ? $rts['tjpointnum3'] : 0;
                        $tjpointnum_ag = isset($rts['tjpointnum_ag3']) && !empty($rts['tjpointnum_ag3']) ? format_price($rts['tjpointnum_ag3']) : 1;
                        $points2 = intval($order_amount * $tjpointnum * $tjpointnum_ag);
                        if ($points2 > 0) {
                            //送积分
                            $sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points2,`mypoints` = `mypoints`+$points2 WHERE user_id = '$parent_uid3'";
                            $this->App->query($sql);
                            $this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points2, 'changedesc' => '三级客户消费返积分', 'time' => mktime(), 'uid' => $parent_uid3));

                            $sendrt_point[] = array('wecha_id' => $pwecha_id, 'nickname' => '', 'points' => $points2, 'order_sn' => $order_sn, 'type' => 'payreturnpoints_parentuid');
                        }
                    }
                }
            }

            $moeys = 0;
            //四级返佣金
            if ($parent_uid4 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid4' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_4'] < 101 && $rts['ticheng180_4'] > 0) {
                            $off = $rts['ticheng180_4'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_4'] < 101 && $rts['ticheng180_h1_4'] > 0) {
                            $off = $rts['ticheng180_h1_4'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_4'] < 101 && $rts['ticheng180_h2_4'] > 0) {
                            $off = $rts['ticheng180_h2_4'] / 100;
                        }
                    }

                    //}
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$parent_uid4'";
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid4, 'level' => '4'));

                        //发送三级推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid4' LIMIT 1");
                        $sendrt_money[] = array('wecha_id' => $pwecha_id, 'nickname' => $nickname, 'money' => $moeys, 'order_sn' => $order_sn, 'type' => 'payreturnmoney');
                    }
                }
            }

            //添加到资金记录表
            if (!empty($record)) {
                $record['oid'] = $order_id;
                $record['uid'] = $uid;
                $record['date_y'] = date('Y', mktime());
                $record['date_m'] = date('Y-m', mktime());
                $record['date_d'] = date('Y-m-d', mktime());
                $this->App->insert('user_money_record', $record);
            }

            //如果是虚拟卡变更状态
            if ($uid > 0) {
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");

                $sql = "SELECT type,consignee FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1";
                $types = $this->App->findrow($sql);
                $type = $types['type'];
                $nickname = $types['consignee'];
                if ($type == '3') {
                    $this->App->update('goods_order_info', array('shipping_status' => '5'), 'order_sn', $order_sn);
                    $gid = $this->App->findvar("SELECT goods_id FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id' LIMIT 1");
                    if ($gid > 0) {
                        $ids = $this->App->findrow("SELECT id,goods_pass,goods_sn FROM `{$this->App->prefix()}goods_sn` WHERE goods_id='$gid' AND is_use = '0' ORDER BY id ASC LIMIT 1");
                        if (!empty($ids)) {
                            $id = $ids['id'];
                            $pass = $ids['goods_pass'];
                            $sn = $ids['goods_sn'];
                            $this->App->update('goods_sn', array('is_use' => '1', 'usetime' => mktime(), 'order_id' => $order_id), 'id', $id);
                        }
                        //  $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname, 'goods_pass' => $pass, 'goods_sn' => $sn), 'payconfirm_vg');
                    }
                } else {
                    //发送支付成功通知
                    /* if (!empty($pwecha_id)) {
                      $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname), 'payconfirm');
                      } */
                }
            }

            //返佣金提醒
            $mone = array();
            /*  if (!empty($sendrt_money))
              foreach ($sendrt_money as $mone) {
              $this->action('api', 'send', array('openid' => $mone['wecha_id'], 'appid' => '', 'appsecret' => '', 'nickname' => $mone['nickname'], 'money' => $mone['money'], 'order_sn' => $mone['order_sn']), $mone['type']);
              } */
            unset($sendrt_money);

            //返积分提醒
            $point = array();
            /* if (!empty($sendrt_point))
              foreach ($sendrt_point as $point) {
              $this->action('api', 'send', array('openid' => $point['wecha_id'], 'appid' => '', 'appsecret' => '', 'nickname' => '', 'points' => $point['points'], 'order_sn' => $point['order_sn']), $point['type']);
              } */
            unset($sendrt_point);
        }//end if

        return true;
    }

    //在线预约付款状态改变
    function baoming_pay_successs_tatus($rt = array()) {
        set_time_limit(300); //最大运行时间

        $order_sn = $rt['order_sn'];
        $status = $rt['status'];

        if (empty($order_sn))
            exit;
        $order_sn = substr($order_sn, -14, 14);
        //改变状态
        $dd = array();
        $dd['pay_status'] = $status;
        $dd['pay_time'] = mktime();
        $this->App->update('cx_baoming_order', $dd, 'order_sn', $order_sn);

        //开通分销
        $sql = "SELECT openfx_baoming FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1"; //用户配置信息
        $openfx_baoming = $this->App->findvar($sql);
        $tp = fopen(time() . ".txt", "a+");
        if ($openfx_baoming == '1') {
            $userinfo = $this->App->findrow("SELECT bo.user_id ,bo.bid,bo.order_amount,b.rank_id  FROM `{$this->App->prefix()}cx_baoming_order` as  bo left join `{$this->App->prefix()}cx_baoming` as b  on b.id=bo.bid WHERE order_sn='$order_sn' LIMIT 1");
            $uid = $userinfo['user_id'];
            if ($uid) {
                $newrank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
                /* huixia */
                //修改会员等级

                fwrite($tp, $userinfo['rank_id'] . "sssss\r\n");
                fwrite($tp, $newrank . "aaaaa\r\n");
                if ($newrank == 1 || ( $userinfo['rank_id'] < $newrank )) {
                    $this->App->update('user', array('user_rank' => $userinfo['rank_id']), 'user_id', $uid);
                    $this->update_daili_tree($uid); //更新代理关系
                    //记录父级升级记录
                    $remarklog = '直接充值进行会员升级';
                    $sql = "insert into `{$this->App->prefix()}user_level_log` (user_id,user_rank,create_time,type,remark) values ('$uid', $userinfo[rank_id],UNIX_TIMESTAMP(),2,'$remarklog')";
                    fwrite($tp, $sql . "\r\n");
                    $this->App->query($sql);
                    //查看是否有父级

                    $sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
                    fwrite($tp, $sql . "\r\n");
                    $p = $this->App->findvar($sql);
                    if ($p) {
                        //查找父级的详细信息
                        $puser = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$p' LIMIT 1");
                        fwrite($tp, $sql . "\r\n");
                        //父级等级
                        $prank = $puser['user_rank'];
                        //当前用户升级的等级
                        $srank = $userinfo['rank_id'];
                        //，有父级增加团队
                        //如果是该用户升级 则修改以前的记录状态
                        /* $sql = "SELECT count(*) FROM `{$this->App->prefix()}user_team` WHERE   user_id=$p and son_id=$uid and `status`=1  and  create_time>UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 7 DAY))  LIMIT 1";
                          $count = $this->App->findvar($sql); */
                        //if ($count) {
                        $sql = "update  `{$this->App->prefix()}user_team` set status=2 where user_id=$p and son_id=$uid";
                        fwrite($tp, $sql . "\r\n");
                        $this->App->query($sql);
                        // }
                        $sql = "insert into  `{$this->App->prefix()}user_team` (user_id,user_rank,amount,pay_type,create_time,son_id,pay_amount)"
                                . " values ($p,$prank,$userinfo[order_amount],$userinfo[rank_id],UNIX_TIMESTAMP(),$uid,$userinfo[order_amount])";
                        fwrite($tp, $sql . "\r\n");
                        $this->App->query($sql);
                        //，有父级 给父级增加推广金额
                        //根据父级的等级 及发展的会员等级  增加父级推广费用

                        $payfee = array(
                            12 => array(12 => 10, 11 => 100, 10 => 200),
                            11 => array(12 => 30, 11 => 300, 10 => 1000),
                            10 => array(12 => 30, 11 => 500, 10 => 2000)
                        );
                        $moeys = $payfee[$prank][$srank];
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$p'";
                        fwrite($tp, $sql . "\r\n");
                        $this->App->query($sql);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值升级返佣金', 'time' => mktime(), 'type' => 999, 'uid' => $p));
                        //查找上上级会员信息 如果是黄金会员

                        $sql = "SELECT * FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid = '$uid' LIMIT 1";
                        fwrite($tp, $sql . "\r\n");
                        $ppuser = $this->App->findrow($sql);
                        $payfee2 = array(12 => 5, 11 => 100, 10 => 500);
                        //上上级会员的等级
                        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$ppuser[p2_uid]' LIMIT 1";
                        fwrite($tp, $sql . "\r\n");
                        $ppuser2 = $this->App->findrow($sql);
                        //如果是黄金会员则参与分成
                        if ($ppuser2['user_rank'] == 10) {

                            $moeys = $payfee2[$srank];
                            $thismonth = date('Y-m-d', mktime());
                            $thism = date('Y-m', mktime());
                            $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$ppuser2[user_id]'";
                            fwrite($tp, $sql . "\r\n");
                            $this->App->query($sql);
                            $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值，上两级黄金会员升级返佣金', 'time' => mktime(), 'uid' => $ppuser2[user_id]));
                        }
                        //上上上级会员的等级
                        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$ppuser[p3_uid]' LIMIT 1";
                        fwrite($tp, $sql . "\r\n");
                        $ppuser3 = $this->App->findrow($sql);
                        $payfee3 = array(12 => 5, 11 => 100, 10 => 500);
                        //如果是黄金会员则参与分成
                        if ($ppuser3['user_rank'] == 10) {

                            $moeys = $payfee3[$srank];
                            $thismonth = date('Y-m-d', mktime());
                            $thism = date('Y-m', mktime());
                            $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$ppuser3[user_id]'";
                            fwrite($tp, $sql . "\r\n");
                            $this->App->query($sql);
                            $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值升级,上三级黄金会员返佣金', 'time' => mktime(), 'uid' => $ppuser3[user_id]));
                        }



                        //查找父级每个等级的团队记录
                        $sql = "SELECT *  FROM  `{$this->App->prefix()}user_level`  where lid !=1 and is_show='1'";
                        fwrite($tp, $sql . "\r\n");
                        //每个等级升级需要的数量，根据父级等级 推广记录判断是否升级
                        $ranklevel = array(
                            12 => array(12 => 10, 11 => 2, 10 => 1),
                            11 => array(12 => 60, 11 => 10, 10 => 2),
                        );

                        $ranklist = $this->App->find($sql);

                        /*                         * 父级现有的团队记录 */
                        $ranklist2 = array();
                        foreach ($ranklist as $_k => $_v) {
                            $teamcount = 0;
                            $sql = 'SELECT count(*) as num  ' . " FROM   `{$this->App->prefix()}user_team`" .
                                    " WHERE user_id = $p and user_rank=$prank   and pay_type=$_v[lid]  LIMIT 1";
                            $teamcount = $this->App->findvar($sql);
                            fwrite($tp, $sql . "\r\n");
                            $ranklist2[$_v[lid]] = $teamcount;
                            fwrite($tp, json_encode($ranklist2) . "\r\n");
                        }
                        if ($prank != 10) {
                            //每个等级需要升级所需的团队记录
                            $userRankLevel = $ranklevel[$prank];

                            foreach ($ranklist2 as $_k => $_v) {
                                fwrite($tp, $userRankLevel[$_k] . "\r\n");
                                fwrite($tp, $_v . "\r\n");
                                //升级每个等级需要的人数与现有团队记录是否相等
                                if ($userRankLevel[$_k] == $_v) {
                                    //父级升级
                                    $sql = "UPDATE  `{$this->App->prefix()}user` " . " SET user_rank=user_rank-1    WHERE user_id = " . $p;
                                    fwrite($tp, $sql . "\r\n");
                                    $this->App->query($sql);
                                    //记录父级升级记录
                                    $remarklog = '团队组建 进行会员升级';
                                    $sql = "insert into `{$this->App->prefix()}user_level_log` (user_id,user_rank,create_time,type,remark) values ('$p',$prank-1,UNIX_TIMESTAMP(),1,'$remarklog')";
                                    fwrite($tp, $sql . "\r\n");
                                    $this->App->query($sql);
                                    break;
                                }
                            }
                        }
                    }
                }
                /* huixia 0721
                 * if($newrank=='1'){
                  $this->App->update('user',array('user_rank'=>'12'),'user_id',$uid);

                  $this->update_daili_tree($uid);//更新代理关系
                  } */
            }
            fclose($tp);
        }
    }

    function index() {
        $this->title('我的购物车 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        //if(empty($uid)){ $this->jump(SITE_URL.'user.php?act=login',0,'请先登录！'); exit;}

        $hear[] = '<a href="' . SITE_URL . '">首页</a>';
        $hear[] = '<a href="' . SITE_URL . 'mycart.php">我的购物车</a>';
        $rt['hear'] = implode('&nbsp;&gt;&nbsp;', $hear);

        //用户等级折扣
        $rt['discount'] = 100;
        $rank = $this->Session->read('User.rank');
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
            $rt['discount'] = $this->App->findvar($sql);
        }

        $active = $this->Session->read('User.active');
        //购物车商品
        $goodslist = $this->Session->read('cart');
        $rt['goodslist'] = array();
        if (!empty($goodslist)) {
            foreach ($goodslist as $k => $row) {
                $rt['goodslist'][$k] = $row;
                $rt['goodslist'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
                $rt['goodslist'][$k]['goods_thumb'] = is_file(SYS_PATH . $row['goods_thumb']) ? SITE_URL . $row['goods_thumb'] : SITE_URL . 'theme/images/no_picture.gif';
                $rt['goodslist'][$k]['goods_img'] = is_file(SYS_PATH . $row['goods_img']) ? SITE_URL . $row['goods_img'] : SITE_URL . 'theme/images/no_picture.gif';
                $rt['goodslist'][$k]['original_img'] = is_file(SYS_PATH . $row['original_img']) ? SITE_URL . $row['original_img'] : SITE_URL . 'theme/images/no_picture.gif';
				
				
				//zzzzzzzzzzzzz获取店铺
				if($row['supplier_id']){
					
      $sql = "SELECT site_name FROM `{$this->App->prefix()}supplier_systemconfig` WHERE supplier_id=".$row['supplier_id']." LIMIT 1";
		//echo $sql;
		$info = $this->App->findrow($sql);
		
				  $rt['goodslist'][$k]['dianpu'] = !empty($info['site_name'])? $info['site_name']:"商家店铺名称";
				}else{
					 $rt['goodslist'][$k]['dianpu'] = "网店自营";
					}
				

                //求出实际价格
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    //同一折扣价格
                    if ($rt['discount'] > 0) {
                        $comd[] = ($rt['discount'] / 100) * $row['shop_price'];
                    }

                    if ($row['shop_price'] > 0 && $rank == 1) { //个人会员价格
                        $comd[] = $row['shop_price']; //个人会员价格
                    }
                    if ($row['pifa_price'] > 0 && $rank != '1') { //高级会员价格
                        $comd[] = $row['pifa_price']; //高级会员价格
                    }
                } else {
                    $comd[] = $row['shop_price'];
                }

                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                    $comd[] = $row['promote_price'];
                }
                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }

                $onetotal = min($comd);
                if (intval($onetotal) <= 0)
                    $onetotal = $row['shop_price'];
                $total +=($row['number'] * $onetotal); //总价格
            }
            unset($goodslist);
        }

        //赠品类型
        $fn = SYS_PATH . 'data/goods_spend_gift.php';
        $spendgift = array();
        if (file_exists($fn) && is_file($fn)) {
            include_once($fn);
        }
        $rt['gift_typesd'] = $spendgift;
        unset($spendgift);

        //商品赠品模块
        $minspend = array();
        if (!empty($rt['gift_typesd'])) {
            foreach ($rt['gift_typesd'] as $k => $row) {
                ++$k;
                $minspend[$k] = $row['minspend'];
            }
            arsort($minspend);
        }
        $rt['gift_goods'] = array();
        $type = 0;
        if (count($minspend) > 0) {
            $count = count($minspend);
            foreach ($minspend as $t => $val) {  //已最高消费赠品为准
                if ($total >= $val) {
                    $type = $t; //赠品等级
                    break;
                }
            }
            unset($minspend);
            //赠品
            $rt['gift_goods_ids'] = array();
            if ($type > 0) {
                $sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
                $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
                $sql .=" WHERE tb2.is_alone_sale='0' AND tb2.is_on_sale='1' AND tb2.is_delete='0' AND tb1.type='$type'";
                $gift_goods = $this->App->find($sql);
                if (!empty($gift_goods)) {
                    foreach ($gift_goods as $k => $row) {
                        $rt['gift_goods'][$k] = $row;
                        $rt['gift_goods'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
                        $rt['gift_goods_ids'][] = $row['goods_id']; //记录赠品的id
                    }
                    unset($gift_goods);
                }
            }
        }

        //换购商品
        $sql = "SELECT goods_id,goods_name,market_price,shop_price,promote_price,goods_thumb,goods_img,is_jifen,need_jifen FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1' AND is_alone_sale='1' AND is_jifen='1' ORDER BY sort_order ASC, goods_id DESC LIMIT 5";
        $jifengoods = $this->App->find($sql);
        if (!empty($jifengoods)) {
            foreach ($jifengoods as $k => $row) {
                $rt['jifengoods'][$k] = $row;
                $rt['jifengoods'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
            }
            unset($jifengoods);
        }


        //全站banner
        $rt['quanzhan'] = $this->action('banner', 'quanzhan');

        $this->set('rt', $rt);
        $this->template('mycart_list');
    }

    //订单确认
    function checkout() {
        $this->css('calendar.css');
        $this->js(array('time/calendar.js', 'time/calendar-setup.js', 'time/calendar-zh.js'));
        $this->title('我的购物车 - 确认订单 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $hear[] = '<a href="' . SITE_URL . '">首页</a>';
        $hear[] = '<a href="' . SITE_URL . 'shopping/">我的购物车</a>';
        $hear[] = '<a href="' . SITE_URL . 'shopping/checkout/">确认订单</a>';
        $rt['hear'] = implode('&nbsp;&gt;&nbsp;', $hear);
        $rt['goodslist'] = $this->Session->read('cart');
         
		 
		 //zzzzzzzz店铺id
		 $dianpus = array();
		 
        $dianpus = $this->get_dianpus($rt['goodslist']);
		 
		  $this->set('dianpus', array_unique($dianpus));
        /* $rt['user_ress'] = array();
          if(!empty($uid)){
          $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}user_address` AS tb1";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
          $sql .=" WHERE tb1.user_id='$uid' ORDER BY tb1.type DESC, tb1.address_id ASC LIMIT 1";
          $rt['user_ress'] = $this->App->findrow($sql);
          } */

        $rt['province'] = $this->action('user', 'get_regions', 1);  //获取省列表
        //当前用户的收货地址
        /* 		$sql = "SELECT tb1.*,tb3.user_name ,tb3.user_name AS peisongname,tb2.address AS addr, tb3.home_phone AS phone, tb3.mobile_phone AS mob FROM `{$this->App->prefix()}user_address` AS tb1";
          $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb1.shop_id"; //look 添加
          $sql .= " LEFT JOIN `{$this->App->prefix()}user_address` AS tb2 ON tb2.user_id = tb1.shop_id  AND tb2.is_own='1' WHERE tb1.user_id='$uid' AND tb1.is_own='0' GROUP BY tb1.address_id"; */

        $sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id";
        $rt['userress'] = $this->App->find($sql);
        //print_r($rt['userress']);
        /* 		if(!empty($rt['userress'])){
          foreach($rt['userress'] as $row){
          $rt['city'][$row['address_id']] = $this->action('user','get_regions',2,$row['province']);  //城市
          $rt['district'][$row['address_id']] = $this->action('user','get_regions',3,$row['city']);  //区
          $rt['town'][$row['address_id']] = $this->action('user','get_regions',4,$row['district']);  //城镇
          $rt['village'][$row['address_id']] = $this->action('user','get_regions',5,$row['town']);  //村

          }
          }
         */
        //print_r($rt);
        //支付方式
        $sql = "SELECT * FROM `{$this->App->prefix()}payment` where  pay_id in (3,7) and enabled=1";
        //$sql = "SELECT * FROM `{$this->App->prefix()}payment` where  enabled=1";
        $rt['paymentlist'] = $this->App->find($sql);


        //配送方式
        $sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
        $rt['shippinglist'] = $this->App->find($sql);
		
		
		  $sql = "SELECT * FROM `{$this->App->prefix()}supplier_shipping`";
        $rt['shippinglist_s'] = $this->App->find($sql);




        //我的积分
        $sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
        $rt['mypoints'] = $this->App->findvar($sql);

        //用户等级折扣
        $rt['discount'] = 100;
        $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
            $rt['discount'] = $this->App->findvar($sql);
        }

        $active = $this->Session->read('User.active');
        //购物车商品
        if (!empty($rt['goodslist'])) {
            foreach ($rt['goodslist'] as $k => $row) {
                //求出实际价格
                $comd = array();

                if (!empty($uid) && $active == '1') {

                    if ($rt['discount'] > 0) {
                        $comd[] = ($rt['discount'] / 100) * $row['shop_price'];
                    }

                    if ($row['shop_price'] > 0 && $rank == 1) { //个人会员价格
                        $comd[] = $row['shop_price']; //个人会员价格
                    }
                    if ($row['pifa_price'] > 0 && $rank != '1') { //高级会员价格
                        $comd[] = $row['pifa_price']; //高级会员价格
                    }
                } else {
                    $comd[] = $row['shop_price'];
                }

                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                    $comd[] = $row['promote_price'];
                }
                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }
				
				
				
				
				//zzzzzzzzzzzzz获取店铺
				if($row['supplier_id']){
					
      $sql = "SELECT site_name FROM `{$this->App->prefix()}supplier_systemconfig` WHERE supplier_id=".$row['supplier_id']." LIMIT 1";
		//echo $sql;
		$info = $this->App->findrow($sql);
		
				  $rt['goodslist'][$k]['dianpu'] = !empty($info['site_name'])? $info['site_name']:"商家店铺名称";
				}else{
					 $rt['goodslist'][$k]['dianpu'] = "网店自营";
					}
					
					

                $onetotal = min($comd);
                if (intval($onetotal) <= 0)
                    $onetotal = $row['shop_price'];
                $total +=($row['number'] * $onetotal); //总价格
            }
        }


        //赠品类型
        $fn = SYS_PATH . 'data/goods_spend_gift.php';
        $spendgift = array();
        if (file_exists($fn) && is_file($fn)) {
            include_once($fn);
        }
        $rt['gift_typesd'] = $spendgift;
        unset($spendgift);

        //商品赠品模块
        $minspend = array();
        if (!empty($rt['gift_typesd'])) {
            foreach ($rt['gift_typesd'] as $k => $row) {
                ++$k;
                $minspend[$k] = $row['minspend'];
            }
            arsort($minspend);
        }
        $rt['gift_goods'] = array();
        $type = 0;
        if (count($minspend) > 0) {
            $count = count($minspend);
            foreach ($minspend as $t => $val) {  //已最高消费赠品为准
                if ($total >= $val) {
                    $type = $t; //赠品等级
                    break;
                }
            }
            unset($minspend);
            //赠品
            $rt['gift_goods_ids'] = array();
            if ($type > 0) {
                $sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
                $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
                $sql .=" WHERE tb2.is_alone_sale='0' AND tb2.is_check='1' AND tb2.is_on_sale='1' AND tb2.is_delete='0' AND tb1.type='$type'";
                $gift_goods = $this->App->find($sql);
                if (!empty($gift_goods)) {
                    foreach ($gift_goods as $k => $row) {
                        $rt['gift_goods_ids'][] = $row['goods_id']; //记录赠品的id
                    }
                    unset($gift_goods);
                }
            }
        }

        $this->set('rt', $rt);
		
		
		
		
		
		//
//		$id_ext = "";
//	if ($_SESSION['sel_cartgoods'])
//	{
//		$id_ext = " AND c.rec_id in (". $_SESSION['sel_cartgoods'] .") ";
//	}
///* 代码增加_end  By www.68ecshop.com */
//$sql_where = $_SESSION['user_id']>0 ? "c.user_id='". $_SESSION['user_id'] ."' " : "c.session_id = '" . SESS_ID . "' AND c.user_id=0 ";
//    $sql = "SELECT c.rec_id, c.user_id, c.goods_id, c.goods_name, c.goods_sn, c.goods_number, c.market_price, " .
//			" c.goods_price, c.goods_attr, c.is_real, c.extension_code, c.parent_id, c.is_gift, c.is_shipping, " .
//			" package_attr_id, c.goods_price * c.goods_number AS subtotal, " .
//			" g.supplier_id as supplier_id, " .
//			" IFNULL(s.supplier_name, '网站自营') as seller " .
//            " FROM " . $GLOBALS['ecs']->table('cart') .
//            " as c LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " as g ON c.goods_id = g.goods_id LEFT JOIN ". $GLOBALS['ecs']->table('supplier') .
//            " as s ON s.supplier_id = g.supplier_id " .

//			" WHERE $sql_where " .
//            " AND c.rec_type = '$type' $id_ext ";  //代码修改 By  www.68ecshop.com  增加一个 $id_ext , package_attr_id	
//
//
//
//    $arr = $GLOBALS['db']->getAll($sql);
//
//    /* 格式化价格及礼包商品 */
//    foreach ($arr as $key => $value)
//    {
//        $arr[$key]['formated_market_price'] = price_format($value['market_price'], false);
//        $arr[$key]['formated_goods_price']  = price_format($value['goods_price'], false);
//        $arr[$key]['formated_subtotal']     = price_format($value['subtotal'], false);
//
//		/* 代码增加_start  By  www.68ecshop.com */
//		$arr[$key]['goods_thumb']  = $GLOBALS['db']->getOne("SELECT `goods_thumb` FROM " . $GLOBALS['ecs']->table('goods') . " WHERE `goods_id`='{$value['goods_id']}'");
//        $arr[$key]['goods_thumb'] = get_image_path($value['goods_id'], $arr[$key]['goods_thumb'], true);
//		/* 代码增加_end   By  www.68ecshop.com */
//
//        if ($value['extension_code'] == 'package_buy')
//        {
//            $arr[$key]['package_goods_list'] = get_package_goods($value['goods_id'], $value['package_attr_id']); //修改 by www.ecshop68.com
//        }
//    }
//    return $sql;
	
	
	
		
		
		//SELECT c.*, c.goods_price * c.goods_number AS subtotal, g.supplier_id as supplier_id, IFNULL(s.supplier_name, '网站自营') as seller FROM `h`.`ecs_cart` as c LEFT JOIN `h`.`ecs_goods` as g ON c.goods_id = g.goods_id LEFT JOIN `h`.`ecs_supplier` as s ON s.supplier_id = g.supplier_id   WHERE c.user_id='24' AND c.rec_type = '0' AND c.rec_id in (18,19,15,20,16,17)

        $this->template('mycart_checkout');
    }

    /*
      确认订单提交页面
     */

    function confirm() {
        $this->title('我的购物车 - 订单号 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }

        if (isset($_POST) && !empty($_POST)) {
            //购物车商品
            $cartlist = $this->Session->read('cart');
            if (empty($cartlist)) {
                $this->jump(ADMIN_URL . 'mycart.php', 0, '购物车商品为空!');
                exit;
            }
			
			
			//zzzzzzzzzzz获取商铺数组
			$supp = array();
			$supp = isset($_POST['supp']) ? $_POST['supp'] : "";
			//print_r($supp);exit;
			
            //print_r($cartlist);exit;
			
              
         $orderdata['parent_sn'] = date('Y', mktime()) . mktime();
		 	
				$parent_sn = $orderdata['parent_sn'];
			
			foreach ($supp as $_k){
				
				
				  $shipping_id = isset($_POST['shipping_id_'.$_k]) ? $_POST['shipping_id_'.$_k] : 0;
				   $userress_id = isset($_POST['userress_id']) ? $_POST['userress_id'] : 0;
            $dd = array();
            if (empty($userress_id)) {  //如果是提交添加地址的，需要添加到user_address表
                //添加收货地址
                $dd['user_id'] = $uid;
                $dd['consignee'] = $_POST['consignee'];
                if (empty($dd['consignee'])) {
                    $this->jump(SITE_URL . 'mycart.php?type=checkout', 0, '收货人不能为空！');
                    exit;
                }
                $dd['country'] = 1;
                $dd['province'] = $_POST['province'];
                $dd['city'] = $_POST['city'];
                $dd['district'] = $_POST['district'];
                $dd['address'] = $_POST['address'];
                //$dd['town'] = $_POST['town'];
                //$dd['village'] = $_POST['village'];
                //$dd['shop_id'] = $_POST['shop_id'];
                if (empty($dd['province']) || empty($dd['city']) || empty($dd['district']) || empty($dd['address'])) {
                    $this->jump(SITE_URL . 'mycart.php?type=checkout', 0, '收货地址不能为空！');
                    exit;
                }
                $dd['sex'] = $_POST['sex'];
                $dd['zipcode'] = $_POST['zipcode'];
                /* if(empty($dd['zipcode'])){
                  $this->jump(SITE_URL.'mycart.php?type=checkout',0,'邮政编码不能为空！'); exit ;
                  }
                  if(!($dd['zipcode']>0)){
                  $this->jump(SITE_URL.'mycart.php?type=checkout',0,'邮政编码必须是整数！'); exit ;
                  } */
                $dd['email'] = $_POST['email'];   //look添加

                $dd['mobile'] = $_POST['mobile'];
                $dd['tel'] = $_POST['tel'];
                $dd['is_default'] = '1';
                $dd['shoppingname'] = $shipping_id;
                $this->App->update('user_address', array('is_default' => '0'), 'user_id', $uid);
                $this->App->insert('user_address', $dd); //添加到地址表
                $userress_id = $this->App->iid();

                if (!($userress_id > 0)) {
                    $this->jump(SITE_URL . 'mycart.php?type=checkout', 0, '非法的收货地址！');
                    exit;
                } else {
                    //$user_ress = $dd;
                    unset($dd);
                }
            }

            $pay_id = isset($_POST['pay_id']) ? $_POST['pay_id'] : 0;
            $pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");

            $postscript = isset($_POST['postscript']) ? $_POST['postscript'] : "";
            if (empty($dd)) {

                //收货信息
                $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id' LIMIT 1";

                //$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
                //$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
                //$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
                //$sql .=" WHERE tb1.user_id='$uid'";
                $user_ress = $this->App->findrow($sql);

                if (empty($user_ress)) {
                    $this->jump(SITE_URL . 'mycart.php?type=checkout', 0, '非法收货地址！');
                    exit;
                }
            } else {
                $user_ress = $dd;
                unset($dd);
            }
            //$shipping_id = $user_ress['shoppingname'] ? $user_ress['shoppingname'] : $shipping_id;
			
			
				
				if($_k > 0){
					
			
			  $shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}supplier_shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
				}else{
				
            $shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			}
					
					

            //购物车商品
            $cartlist = $this->Session->read('cart');
            if (empty($cartlist)) {
                $this->jump(SITE_URL . 'mycart.php', 0, '购物车商品为空!');
                exit;
            }

            //添加信息到数据表
//			$orderdata['parent_sn'] = date('Y', mktime()) . mktime();
			
            $orderdata['order_sn'] = date('Y', mktime()) . mktime().$_k;
			
			$orderdata['supplier_id'] = $_k;
            $orderdata['user_id'] = $uid ? $uid : 0;
            $daili_uid = $this->return_daili_uid($uid); //一级
            $orderdata['parent_uid'] = $daili_uid;

            //查找二级、三级代理
            if ($daili_uid > 0) {
                $sql = "SELECT p1_uid,p2_uid,p3_uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid ='$daili_uid' LIMIT 1";
                $pr = $this->App->findrow($sql);
                $parent_uid = isset($pr['p1_uid']) ? $pr['p1_uid'] : 0;
                $orderdata['parent_uid2'] = $parent_uid > 0 && $parent_uid != $daili_uid ? $parent_uid : '0'; //上二级

                $parent_uid = isset($pr['p2_uid']) ? $pr['p2_uid'] : 0;
                $orderdata['parent_uid3'] = $parent_uid > 0 && $parent_uid != $daili_uid ? $parent_uid : '0'; //上三级

                $parent_uid = isset($pr['p3_uid']) ? $pr['p3_uid'] : 0;
                $orderdata['parent_uid4'] = $parent_uid > 0 && $parent_uid != $daili_uid ? $parent_uid : '0'; //上四级
            }
            $orderdata['country'] = $user_ress['country'] ? $user_ress['country'] : "";
            $orderdata['consignee'] = $user_ress['consignee'] ? $user_ress['consignee'] : "";
            $orderdata['province'] = $user_ress['province'] ? $user_ress['province'] : 0;
            $orderdata['city'] = $user_ress['city'] ? $user_ress['city'] : 0;
            $orderdata['district'] = $user_ress['district'] ? $user_ress['district'] : 0;
            //$orderdata['town'] = $user_ress['town'] ? $user_ress['town'] : 0;
            //$orderdata['village'] = $user_ress['village'] ? $user_ress['village'] : 0;
            $orderdata['address'] = $user_ress['address'] ? $user_ress['address'] : "";
            $orderdata['zipcode'] = $user_ress['zipcode'] ? $user_ress['zipcode'] : "";
            $orderdata['tel'] = $user_ress['tel'] ? $user_ress['tel'] : "";
            $orderdata['mobile'] = $user_ress['mobile'] ? $user_ress['mobile'] : "";
            $orderdata['email'] = $user_ress['email'] ? $user_ress['email'] : "";
            $orderdata['shipping_id'] = $shipping_id;
            $orderdata['shipping_name'] = $shipping_name;
            if (isset($_POST['best_time']) && !empty($_POST['best_time'])) {
                $orderdata['best_time'] = trim($_POST['best_time']);
            }
            $orderdata['pay_id'] = $pay_id ? $pay_id : 0;
            $orderdata['pay_name'] = $pay_name ? $pay_name : "";
            $orderdata['postscript'] = $postscript;

            //$orderdata['shop_id'] = $user_ress['shop_id'] ? $user_ress['shop_id'] : 0; //配送店ID
            //用户等级折扣
            $discount = 100;

            $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
            //	$rank = $this->Session->read('User.rank');
            $active = $this->Session->read('User.active');
            if ($rank > 0) {
                $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
                $discount = $this->App->findvar($sql);
            }


            $k = 0;
            $total = 0;
            $jifen_onetotal = 0;
            $shop_ids = array();
            //返回总价




            foreach ($cartlist as $row) {
				
				//echo $row['supplier_id'];

               if($row['supplier_id'] == $_k){
                //if($row['uid']>0){ $suppliers_ids[$row['uid']] = $row['uid']; } //将供应商ID放入数组中
                // $said = $this->auto_goods_assign_suppliers($userress_id,$row['goods_id']);
                // $suppliers_ids[$said] = $said;
				  $data[$k]['supplier_id'] = $row['supplier_id'];
                $data[$k]['goods_id'] = $row['goods_id'];
                $data[$k]['brand_id'] = $row['brand_id'];
                $data[$k]['suppliers_id'] = $said;
                $data[$k]['goods_name'] = $row['goods_name'];
                $data[$k]['goods_bianhao'] = $row['goods_bianhao'];
                $data[$k]['goods_thumb'] = $row['goods_thumb'];
                $data[$k]['goods_sn'] = $row['goods_sn'];
                $data[$k]['goods_number'] = $row['number'];
                $data[$k]['market_price'] = $row['shop_price'] > 0 ? $row['shop_price'] : $row['pifa_price']; //原始价格
                if (!empty($row['buy_more_best'])) {
                    $data[$k]['buy_more_best'] = $row['buy_more_best']; //买多送多，如：10送1
                }

                // $mprice = $row['pifa_price'] > 0 ? $row['pifa_price'] : $row['shop_price'];
                $mprice = $row['shop_price']; // 原始价
                // $onetotal = format_price($row['pifa_price'] * $off);
                // $onetotal = $row['price'];
                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                    $onetotal = $row['promote_price'];
                } else {
                    $onetotal = $row['pifa_price'] * ($discount / 100); //折扣价
                }


                //$prices += format_price($onetotal*$row['number']);
                $mprices += $mprice * $row['number']; //零售总价
                $total += $row['number'] * $onetotal; //折扣总价
                if ($row['takemoney1'] > 0)
                    $data[$k]['takemoney1'] = $row['takemoney1']; //佣金
                if ($row['takemoney2'] > 0)
                    $data[$k]['takemoney2'] = $row['takemoney2']; //佣金
                if ($row['takemoney3'] > 0)
                    $data[$k]['takemoney3'] = $row['takemoney3']; //佣金
                $data[$k]['market_price'] = $mprice;
                $data[$k]['goods_price'] = $onetotal; //实际价格
                $data[$k]['goods_attr'] = !empty($row['spec']) ? $row['goods_brief'] . implode("<br />", $row['spec']) : $row['goods_brief'];
                $data[$k]['goods_unit'] = $row['goods_unit'];

                if (isset($_POST['confirm_jifen']) && intval($_POST['confirm_jifen']) > 0) {
                    if ($row['is_jifen_session'] == '1') {
                        $data[$k]['from_jifen'] = $row['need_jifen'] * $row['number'];
                        $jifen_onetotal += $s;
                    }
                }
                $k++;

                if (!empty($row['gifts'])) {
                    $data[$k]['goods_id'] = $row['gifts']['goods_id'];
                    $data[$k]['brand_id'] = $row['gifts']['brand_id'];
                    $data[$k]['suppliers_id'] = $said;
                    $data[$k]['goods_name'] = '<span style="color:#FE0000">[赠品]</span>' . $row['gifts']['goods_name'];
                    $data[$k]['goods_bianhao'] = $row['gifts']['goods_bianhao'];
                    $data[$k]['goods_thumb'] = $row['goods_thumb'];
                    $data[$k]['goods_sn'] = $row['gifts']['goods_sn'] . '-gift';
                    $data[$k]['goods_number'] = $row['gifts']['number'];
                    $data[$k]['market_price'] = $row['gifts']['shop_price']; //原始价格
                    $data[$k]['goods_price'] = $row['gifts']['shop_price'];  //实际价格
                    $data[$k]['goods_attr'] = !empty($row['gifts']['spec']) ? implode("<br />", $row['gifts']['spec']) : "";
                    $data[$k]['goods_unit'] = $row['gifts']['goods_unit'];
                    $data[$k]['is_gift'] = 1;

                    $k++;
                }
            }
			
		//	echo $row['supplier_id'];
					//echo   $row['goods_id']."|"; 
//				echo   $k."+";
			}


            //价格为0 不允许购物
            if (!($total > 0)) {
                $this->template('mycart_submit_order_error');
                exit;
            }

            //$moneyinfo = $this->get_give_off_monery($total); //返回赠送的余额

            $d = array('userress_id' => $userress_id, 'shopping_id' => $shipping_id, 'supplier_id' => $_k);
            $fr = $this->ajax_jisuan_shopping($d, 'cart'); //邮费

            $n = ($fr > 0) ? format_price($fr) : '0.00';
            $orderdata['goods_amount'] = format_price($mprices);
            $orderdata['order_amount'] = format_price($total * ($discount / 100)); //优惠后的价格,也就是最终支付价格
            $orderdata['offprice'] = $moneyinfo['offmoney'];
            $orderdata['add_time'] = mktime();
            //$orderdata['shipping_fee'] = $n; //邮费
			
			$orderdata['shipping_fee'] = $_POST['free_'.$_k]; //邮费
			
            //是否用积分兑换商品
            if (isset($_POST['confirm_jifen']) && $_POST['confirm_jifen'] > 0 && intval($jifen_onetotal) > 0) {
                $orderdata['goods_amount'] = $orderdata['goods_amount'] - $jifen_onetotal;
                $orderdata['order_amount'] = $orderdata['order_amount'] - $jifen_onetotal;
                $this->App->insert('user_point_change', array('time' => mktime(), 'changedesc' => "用积分兑换商品", 'uid' => $uid, 'points' => -intval($_POST['confirm_jifen'])));
            }
			
			
			//获取佣金id
    	$orderdata['rebate_id'] = $this->get_order_rebate($_k);
			
			//echo $orderdata['rebate_id'];
//			exit;
			
			//   print_r($data);    
			//echo "ssssssss|";     
			  
			
			//zzzzzzzzzzzzzzzzz店铺订单添加
			
			
           
            if ($this->App->insert('goods_order_info', $orderdata)) { //订单成功后
                $iid = $this->App->iid();

                foreach ($data as $kk => $rows) {
					
					if($rows['supplier_id'] == $_k){
                    $rows['order_id'] = $iid;
					
					//echo $rows['goods_id'];
					

                    $this->App->insert('goods_order', $rows);  //添加订单商品表
                    //更新销售数量
                    $gid = $rows['goods_id'];
                    $num = $rows['goods_number']; //look 添加 库存量在购买成功后减少
                    if ($gid > 0 && $rows['is_gift'] != '1') {
                        $sql = "UPDATE `{$this->App->prefix()}goods` SET `sale_count` = `sale_count`+1 , `goods_number` = `goods_number`- '$num' WHERE goods_id = '$gid'";
                        $this->App->query($sql);
					}
                    }
                }
                $this->_return_money($orderdata['order_sn']);
				
				
				
				
				
				
            } else {
                //$this->App->write('cart',"");
                $this->jump(SITE_URL . 'mycart.php', 0, '你的订单没有提交成功，我们是尽快处理出现问题！');
                exit;
            }
			
			
				$shipping_fees += $orderdata['shipping_fee'];
				$shipping_names[] = $shipping_name;
				$orders[]= $orderdata['order_sn']; 
				//$iids[] = $iid; 
			
				}
				//print_r($shipping_fees);
//				print_r($orders);
                      // print_r($shipping_names);         exit;
				// exit;
				
				
				$iids = array();
				
                //插入账户改变表user_money_change
                $this->Session->write('cart', "");
                //	$this->action('user','add_user_money','spend',array('money'=>($orderdata['offprice']>0 ? (-$orderdata['offprice']) : 0.00),'order_id'=>$iid));
                //插入供应商订单表goods_order_status
                /* if(!empty($suppliers_ids)){
                  foreach($suppliers_ids as $id){
                  if(!($id>0)) continue;
                  $subdata = array('suppliers_id'=>$id,'order_id'=>$iid);
                  $this->App->insert('goods_order_status',$subdata);
                  }
                  } */
               
              //  $this->jump(SITE_URL . 'mycart.php?type=pay2&oid=' .$iid);
				//$this->action('shopping','pay2',array('parent_sn'=>$parent_sn));
				
					 $this->jump(SITE_URL . 'mycart.php?type=pay2&parent_sn=' .$parent_sn);
				
                exit;

                $rt['order_sn'] = $orders;
                $rt['shipping_name'] = $shipping_names;
                $rt['pay_name'] = $pay_name;
                $rt['total'] = format_price($orderdata['order_amount']);
                //$rt['shipping_fee'] = $n; //邮费
				
				$rt['shipping_fee'] = $orderdata['shipping_fee']; //邮费
                ####################################################
                //热销
                //$rt['top10'] = $this->action('catalog','top10',0,5);
                ##################################


                $rts['pay_id'] = $pay_id;
                $rts['order_sn'] = $rt['order_sn'];
                $rts['order_amount'] = $rt['total'];
                $rts['username'] = $orderdata['consignee'];
                $rts['logistics_fee'] = $rt['shipping_fee'];

                $sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
                $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
                $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
                $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.address_id='$userress_id' LIMIT 1";
                $userress = $this->App->findrow($sql);

                $rts['address'] = $userress['provincename'] . '&nbsp;' . $userress['cityname'] . '&nbsp;' . $userress['districtname'] . '&nbsp;' . $userress['address'];
                $rts['zip'] = !empty($userress['zipcode']) ? $userress['zipcode'] : $orderdata['zipcode'];
                $rts['phone'] = !empty($userress['tel']) ? $userress['tel'] : $orderdata['tel'];
                $rts['mobile'] = !empty($userress['mobile']) ? $userress['mobile'] : $orderdata['mobile'];
                $this->Session->write('cart', "");
                $this->_alipayment($rts);


                exit;


                $this->set('rt', $rt);
                $this->Session->write('cart', "");
                $this->template('mycart_submit_order');
                exit;
			
			
			
			
			
        } else {
            $this->App->write('cart', "");
            $this->jump(SITE_URL . 'mycart.php');
        }
        $this->App->write('cart', "");
        $this->jump(SITE_URL . 'mycart.php', 0, '意外错误，我们是尽快处理出现问题！');
        exit;
    }

    function return_daili_uid($uid = 0, $k = 0) {
        if (!($uid > 0)) {
            return 0;
        }
        $puid = 0;
        for ($i = 0; $i < 20; $i++) {
            $sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
            $p = $this->App->findvar($sql);
            if ($p > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$p' LIMIT 1";
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

    function pay2() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        if (!defined(NAVNAME))
            define('NAVNAME', "订单支付");
        $this->title('订单支付 ' . $GLOBALS['LANG']['site_name']);
        //$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		$oid = isset($_GET['parent_sn']) ? $_GET['parent_sn'] : "";
      
        if (empty($oid)) {
            $this->jump(SITE_URL);
            exit;
        }
        $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_info` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" WHERE tb1.parent_sn='$oid'";
        $rt['orderinfo'] = $this->App->find($sql);
		
		//echo $sql;
        if (empty($rt['orderinfo'])) {
            $this->jump(SITE_URL);
            exit;
        }
		$orderinfo = array();
		foreach ($rt['orderinfo'] as  $_k => $row){
		$orderinfo[$_k]['order_sn'] = $row['order_sn'];
	    $orderinfo[$_k]['shipping_name'] = $row['shipping_name'];
		
		$order_amount += $row['order_amount'];
		$shipping_fee += $row['shipping_fee'];
		$pay_name = $row['pay_name'];
        $orderinfo[$_k]['goodslist'] = $this->get_goodslist($row['order_id']);
        $orderinfo[$_k]['order_id'] = $row['order_id'];
			}
	    $this->set('orderinfo', $orderinfo);
		 $this->set('order_amount', $order_amount);
		  $this->set('shipping_fee', $shipping_fee);
		   $this->set('pay_name', $pay_name);
          $dds = count($rt['orderinfo']);
		  
      $this->set('dds', $dds);
	  
	  if($dds == 1){   $this->set('oid', $orderinfo[0]['order_id']); }else{   $this->set('oid', $oid);}



        //我的余额
        $uid = $this->Session->read('User.uid');
        if ($uid > 0) {
            $sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
            $rt['mymoney'] = $this->App->findvar($sql);
        } else {
            $rt['mymoney'] = 0;
        }

        //支付方式
        $sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
        $rt['paymentlist'] = $this->App->find($sql);

        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template('shopping_order_pay');
    }

    //快速支付
    function fastpay2() {
        $oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
        if (!($oid > 0)) {
            $this->jump(SITE_URL, 0, '意外错误');
            exit;
        }
        $uid = $this->Session->read('User.uid');
		
		
      //  $sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND parent_sn='$oid' ";
//        $rt = $this->App->find($sql);
//		
//		if(empty($rt)){
			 $sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
        $rt = $this->App->findrow($sql);

        if (empty($rt)) {
            $this->jump(SITE_URL, 0, '非法支付提交！');
            exit;
        }

        $rts['pay_id'] = $rt['pay_id'];
        $rts['order_sn'] = $rt['order_sn'];
        $rts['order_amount'] = $rt['order_amount'];
        $rts['logistics_fee'] = $rt['shipping_fee'];
        $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
        $rts['address'] = $userredd['company_url'];

		
		
			//}
		//	else{
//
//        if (empty($rt)) {
//            $this->jump(SITE_URL, 0, '非法支付提交！');
//            exit;
//        }


      //    $rts = array();
//        foreach($rt as $_k => $row){
			
       // $pay_id[] = $row['pay_id'];
//        $order_sn[] = $row['order_sn'];
//        $rts['order_amount'] = $rt['order_amount'];
//        $rts['logistics_fee'] = $rt['shipping_fee'];
//		if($row['supplier_id']){
//        $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}supplier_systemconfig` WHERE supplier_id=".$row['supplier_id']." type='basic' LIMIT 1");
//		}else{
//			  $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
//			}
       // $rts['pay_id'] = $row['pay_id'];
//		$rts['parent_sn'] = $row['parent_sn'];
//		$rts['order_amount'] += $row['order_amount'];
//		$rts['logistics_fee'] += $row['shipping_fee'];
//        $rts[$_k]['order_sn'] = $row['order_sn'];
//        $rts[$_k]['order_amount'] = $row['order_amount'];
//       $rts[$_k]['logistics_fee'] = $row['shipping_fee'];
//	   
//	   $rts['order_sn'][] = $row['order_sn']; 
//	   
//     if($row['supplier_id']){
//        $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}supplier_systemconfig` WHERE supplier_id=".$row['supplier_id']." and  type='basic' LIMIT 1");
//		}else{
//			  $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
//			}
//        $rts[$_k]['address'] = $userredd['company_url'];
//
//			}
      //  $rts['pay_id'] = $rt['pay_id'];
//        $rts['order_sn'] = $rt['order_sn'];
//        $rts['order_amount'] = $rt['order_amount'];
//        $rts['logistics_fee'] = $rt['shipping_fee'];
//        $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
//        $rts['address'] = $userredd['company_url'];
      
     //   $rts['order_sn'][] = $row['order_sn'];
//        $rts['order_amount'][] = $row['order_amount'];
//        $rts['logistics_fee'][] = $row['shipping_fee'];
//        $rts['address'][] = $userredd['company_url'];

// 
//  
//      
//  
//	print_r($rts);
//   exit;

		//	}
        $this->_alipayment($rts);
        unset($rt);
        exit;
    }

    //返佣缓存
    function _return_money($order_sn = '') {
        @set_time_limit(300); //最大运行时间
        //送佣金，找出推荐用户
        $pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        $parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
        $parent_uid2 = isset($pu['parent_uid2']) ? $pu['parent_uid2'] : 0; //分享者
        $parent_uid3 = isset($pu['parent_uid3']) ? $pu['parent_uid3'] : 0; //分享者
        $parent_uid4 = isset($pu['parent_uid4']) ? $pu['parent_uid4'] : 0; //分享者

        $daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
        $moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
        $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
        $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;

        $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;

        $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1"; //用户配置信息
        $rts = $this->App->findrow($sql);


        if (!empty($order_sn)) {
            //计算每个产品的佣金
            $sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id'";
            $moneys = $this->App->find($sql);

            $thismonth = date('Y-m-d', mktime());
            $thism = date('Y-m', mktime());

            $moeysall = 0;
            if (!empty($moneys))
                foreach ($moneys as $row) {
                    if ($row['takemoney1'] > 0) {
                        $moeysall +=$row['takemoney1'] * $row['goods_number'];
                    }
                }

            //购买者返佣
            $moeys = 0;
            $ticheng360_1 = $rts['ticheng360_1'];
            if ($ticheng360_1 > 0) {
                $off = $ticheng360_1 / 100;
                $moeys = format_price($moeysall * $off);
                if ($moeys > 0) {
                    $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '消费返佣金', 'time' => mktime(), 'uid' => $uid, 'level' => '10'));
                }
            }

            $record = array();
            $moeys = 0;
            //一级返佣金
            if ($parent_uid > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_1'] < 101 && $rts['ticheng180_1'] > 0) {
                            $off = $rts['ticheng180_1'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0) {
                            $off = $rts['ticheng180_h1_1'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0) {
                            $off = $rts['ticheng180_h2_1'] / 100;
                        }
                    }

                    //}

                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid, 'level' => '1'));
                    }
                }
            }

            $moeys = 0;
            //二级返佣金
            if ($parent_uid2 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_2'] < 101 && $rts['ticheng180_2'] > 0) {
                            $off = $rts['ticheng180_2'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0) {
                            $off = $rts['ticheng180_h1_2'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0) {
                            $off = $rts['ticheng180_h2_2'] / 100;
                        }
                    }

                    //}
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid2, 'level' => '2'));
                    }
                }
            }

            $moeys = 0;
            //三级返佣金
            if ($parent_uid3 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;

                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_3'] < 101 && $rts['ticheng180_3'] > 0) {
                            $off = $rts['ticheng180_3'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0) {
                            $off = $rts['ticheng180_h1_3'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0) {
                            $off = $rts['ticheng180_h2_3'] / 100;
                        }
                    }

                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid3, 'level' => '3'));
                    }
                }
            }//end if

            $moeys = 0;
            //四级返佣金
            if ($parent_uid4 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid4' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;

                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_4'] < 101 && $rts['ticheng180_4'] > 0) {
                            $off = $rts['ticheng180_4'] / 100;
                        }
                    } elseif ($rank == '11') {//高级分销商
                        if ($rts['ticheng180_h1_4'] < 101 && $rts['ticheng180_h1_4'] > 0) {
                            $off = $rts['ticheng180_h1_4'] / 100;
                        }
                    } elseif ($rank == '10') {//特权分销商
                        if ($rts['ticheng180_h2_4'] < 101 && $rts['ticheng180_h2_4'] > 0) {
                            $off = $rts['ticheng180_h2_4'] / 100;
                        }
                    }

                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '购买商品返佣金', 'time' => mktime(), 'uid' => $parent_uid4, 'level' => '4'));
                    }
                }
            }//end if
        }
    }

    function fastcheckout() {
        $oid = $_POST['order_id'];
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE user_id = '$uid' AND order_id='$oid'";
        $rt = $this->App->findrow($sql);


        $rts['pay_id'] = $rt['pay_id'];
        $rts['order_sn'] = $rt['order_sn'];
        $rts['order_amount'] = $rt['order_amount'] + $rt['shipping_fee'];
        $rts['username'] = $orderdata['consignee'];
        $rts['logistics_fee'] = $rt['shipping_fee'];


        $sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}goods_order_info` AS ua";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.order_id='$oid' LIMIT 1";
        $userress = $this->App->findrow($sql);

        $rts['address'] = $userress['provincename'] . '&nbsp;' . $userress['cityname'] . '&nbsp;' . $userress['districtname'] . '&nbsp;' . $userress['address'];
        $rts['zip'] = !empty($userress['zipcode']) ? $userress['zipcode'] : $orderdata['zipcode'];
        $rts['phone'] = !empty($userress['tel']) ? $userress['tel'] : $orderdata['tel'];
        $rts['mobile'] = !empty($userress['mobile']) ? $userress['mobile'] : $orderdata['mobile'];

        $this->_alipayment($rts);
        unset($rt);
        exit;
    }

    //ajax更新购物的价格
    function ajax_change_price($data = array()) {
        $id = $data['id'];
        $number = $data['number'];
        $maxnumber = $this->Session->read("cart.{$id}.goods_number");
        if ($number > $maxnumber) {
            die("购买数量已经超过了库存，你最大只能购买:" . $maxnumber);
        }

        //是否是赠品，如果是赠品，那么只能添加一件，不能重复添加
        $is_alone_sale = $this->Session->read("cart.{$id}.is_alone_sale");
        if (!empty($is_alone_sale)) {
            $this->Session->write("cart.{$id}.number", $number);
        }
        //end 赠品

        $uid = $this->Session->read('User.uid');
        $active = $this->Session->read('User.active');

        //用户等级折扣
        $discount = 100;
        $rank = $this->Session->read('User.rank');
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
            $discount = $this->App->findvar($sql);
        }

        $json = Import::json();

        $cartlist = $this->Session->read('cart');
        $total = 0;
        if (!empty($cartlist)) {
            foreach ($cartlist as $row) {
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    if ($discount > 0) {
                        $comd[] = ($discount / 100) * $row['shop_price'];
                    }
                    if ($row['shop_price'] > 0 && $rank == 1) { //个人会员价格
                        $comd[] = $row['shop_price']; //个人会员价格
                    }
                    if ($row['pifa_price'] > 0 && $rank != '1') { //高级会员价格
                        $comd[] = $row['pifa_price']; //高级会员价格
                    }
                } else {
                    $comd[] = $row['shop_price'];
                }

                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                    $comd[] = $row['promote_price'];
                }

                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }

                $onetotal = min($comd);

                $total +=($row['number'] * $onetotal);

                //是否赠品，如：买10送1
                $gifts = array();
                $gift2 = array();
                if (!empty($row['buy_more_best']) && $row['goods_id'] == $id) {
                    if (preg_match_all('/1\d{1,2}|2[01][0-9]|22[0-7]|[1-9][0-9]|[1-9]/', $row['buy_more_best'], $buyrt)) {
                        $num1 = isset($buyrt[0][0]) ? $buyrt[0][0] : 0;
                        $num2 = isset($buyrt[0][1]) ? $buyrt[0][1] : 0;
                        $gift2 = $this->Session->read("cart.{$id}.gifts");
                        if ($number >= $num1 && $num2 > 0) { //允许赠品
                            $mb = mb_substr(trim($row['buy_more_best']), -1, 1, 'utf-8');
                            if (!empty($mb)) {
                                if ($mb > 0) {
                                    $gifts['goods_unit'] = $row['goods_unit'];
                                } else {
                                    $gifts['goods_unit'] = $mb;
                                }
                            } else {
                                $gifts['goods_unit'] = $row['goods_unit'];
                            }
                            $gifts['number'] = $num2;
                            $gifts['goods_id'] = $row['goods_id'];
                            $gifts['goods_sn'] = $row['goods_sn'];
                            $gifts['goods_bianhao'] = $row['goods_bianhao'];
                            $gifts['goods_key'] = $row['goods_id'] . '__' . mktime();
                            $gifts['goods_name'] = $row['goods_name'];
                            $gifts['shop_price'] = 0.00;
                            $gifts['pifa_price'] = 0.00;
                            $gifts['goods_brief'] = $row['goods_brief'];
                        } //end if
                    }//end if
                    $gift = $this->Session->read("cart.{$id}.gifts");
                    $this->Session->write("cart.{$id}.gifts", $gifts);

                    if ((!empty($gift2) && $number <= $num1) || (empty($gift) && $number >= $num1)) {
                        $cartlist = $this->Session->read('cart');
                        $rt['goodslist'] = array();
                        if (!empty($cartlist)) {
                            foreach ($cartlist as $k => $row) {
                                $rt['goodslist'][$k] = $row;
                                $rt['goodslist'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
                                $rt['goodslist'][$k]['goods_thumb'] = is_file(SYS_PATH . $row['goods_thumb']) ? SITE_URL . $row['goods_thumb'] : SITE_URL . 'theme/images/no_picture.gif';
                                $rt['goodslist'][$k]['goods_img'] = is_file(SYS_PATH . $row['goods_img']) ? SITE_URL . $row['goods_img'] : SITE_URL . 'theme/images/no_picture.gif';
                                $rt['goodslist'][$k]['original_img'] = is_file(SYS_PATH . $row['original_img']) ? SITE_URL . $row['original_img'] : SITE_URL . 'theme/images/no_picture.gif';
                            } //end foreach
                            unset($goodslist);
                        } //end if cart

                        $this->set('rt', $rt);
                        $con = $this->fetch('ajax_mycart', true);
                        unset($cartlist, $gift, $gift2);
                        $result = array('error' => 1, 'message' => $con);
                        die($json->encode($result));
                    }
                }//end if
            } //end foreach
        } //end if
        unset($cartlist);
        $moneyinfo = $this->get_give_off_monery($total);
        $result = array('error' => 0, 'message' => $total, 'offprice' => $moneyinfo['offmoney'], 'shippingprice' => $moneyinfo['shippingprice']);
        die($json->encode($result));
    }

    //改变使用积分换取商品
    function ajax_change_jifen($is_confirm = 'true') {
        $uid = $this->Session->read('User.uid');
        $active = $this->Session->read('User.active');

        //用户等级折扣
        $discount = 100;
        $rank = $this->Session->read('User.rank');
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
            $discount = $this->App->findvar($sql);
        }

        $cartlist = $this->Session->read('cart');
        $total = 0;
        if (!empty($cartlist)) {
            foreach ($cartlist as $row) {
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    if ($discount > 0) {
                        $comd[] = ($discount / 100) * $row['shop_price'];
                    }
                    if ($row['shop_price'] > 0 && $rank == 1) { //个人会员价格
                        $comd[] = $row['shop_price']; //个人会员价格
                    }
                    if ($row['pifa_price'] > 0 && $rank != '1') { //高级会员价格
                        $comd[] = $row['pifa_price']; //高级会员价格
                    }
                } else {
                    $comd[] = $row['shop_price'];
                }

                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                    $comd[] = $row['promote_price'];
                }

                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }

                $onetotal = min($comd);

                $total +=($row['number'] * $onetotal);

                if ($row['is_jifen_session'] == '1') {
                    $jifen_onetotal += $row['number'] * $onetotal;
                }
            } //end foreach
        } //end if cart
        unset($cartlist);
        if ($is_confirm == 'true') {
            echo $total - $jifen_onetotal;
        } else {
            echo $total;
        }
        exit;
    }

    //ajax计算邮费
    function ajax_jisuan_shopping($data = array(), $tt = 'ajax') {
        $shopping_id = isset($data['shopping_id']) ? $data['shopping_id'] : 0;
        $userress_id = isset($data['userress_id']) ? $data['userress_id'] : 0;
		  $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : 0;

        if (!($userress_id > 0)) {
            /* if($tt=='ajax'){
              die("请选择一个收货地址！");
              }else{
              return "请选择一个收货地址！";
              } */
        }
        if (!($shopping_id > 0)) {
            if ($tt == 'ajax') {
                die("请选择一个配送方式！");
            } else {
                return "请选择一个配送方式！";
            }
        }

        //当前的收货地址
        $sql = "SELECT country,province,city,district FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id'";
        $ids = $this->App->findrow($sql);

        /* if(empty($ids)){
          if($tt=='ajax'){
          die("请先设置一个收货地址！");
          }else{
          return "请先设置一个收货地址！";
          }
          } */

        $cartlist = $this->Session->read('cart');
        $items = 0;
        $weights = 0;
        $total = 0;
        if (!empty($cartlist)) {
            foreach ($cartlist as $row) {
				
				if( $row['supplier_id'] == $supplier_id){
                if ($row['is_shipping'] == '1' || $row['is_alone_sale'] == '0')
                    continue;
                $items +=$row['number'];
                $weights_each +=$row['goods_weight']; //总重量
                $total +=$row['pifa_price'] * $row['number'];
                $weights += $row['number'] * $row['goods_weight'];
				}
            }
        }


        if($supplier_id > 0){
        $sql = "SELECT * FROM `{$this->App->prefix()}supplier_shipping_area` WHERE shipping_id='$shopping_id'";
        $area_rt = $this->App->find($sql); //配送区域列表
		}else{
			 $sql = "SELECT * FROM `{$this->App->prefix()}shipping_area` WHERE shipping_id='$shopping_id'";
        $area_rt = $this->App->find($sql); //配送区域列表
			}

        if (!empty($area_rt)) {
            foreach ($area_rt as $row) {
                /*    if ($total >= 199) {
                  if ($tt == 'ajax') {
                  die($row['shipping_area_name'] . '+0.00');
                  } else {
                  return '0.00';
                  }
                  break;
                  }
                 */

                if (!empty($row['configure'])) {
                    $configure = json_decode($row['configure']);
                    if (is_array($configure)) {
                        $type = $row['type'];
                        $item_fee = $row['item_fee'];
                        $weight_fee = $row['weight_fee'];
                        $step_weight_fee = $row['step_weight_fee'];
                        $step_item_fee = $row['step_item_fee'];
                        $max_money = $row['max_money'];

                        if (in_array($ids['district'], $configure)) { //区
                            if ($type == 'item') {  //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0)
                                    $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0)
                                        $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0))
                                        $weight_fee = '0.00';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        } elseif (in_array($ids['city'], $configure)) { //城市
                            if ($type == 'item') {  //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0)
                                    $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0)
                                        $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0))
                                        $weight_fee = '0';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        } elseif (in_array($ids['province'], $configure)) { //省
                            if ($type == 'item') {  //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0)
                                    $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0)
                                        $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0))
                                        $weight_fee = '0';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        } elseif (in_array($ids['country'], $configure)) { //国家
                            if ($type == 'item') {  //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0)
                                    $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0)
                                        $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei . '+' .$total);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0))
                                        $weight_fee = '0';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        }
                    } //end if
                } // end if
            } // end foreach
        } else {
            if ($tt == 'ajax') {
                die("");
            } else {
                return $zyoufei;
            }
        }
        if ($tt == 'ajax') {
            die("");
        } else {
            return $zyoufei;
        }
    }
	
	
	
	
	
	//zzzzzzzzzzzzzzzzzzzzzzzzz
	

    //删除购物车商品
    function ajax_delcart_goods($id = 0) {
        //if(empty($id)) return "";
        if (!empty($id)) {
            $cartlist = $this->Session->read('cart');
            if (strpos($id, '__')) {
                $l = explode('__', $id);
                $ids = $l[0];
                $this->Session->write("cart.{$ids}.gifts", "");
            } else {
                if (isset($cartlist[$id])) {
                    $this->Session->write("cart.{$id}", "");
                }
            }
            unset($cartlist);
        }

        $uid = $this->Session->read('User.uid');
        //用户等级折扣
        $rt['discount'] = 100;
        $rank = $this->Session->read('User.rank');
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
            $rt['discount'] = $this->App->findvar($sql);
        }

        $active = $this->Session->read('User.active');
        $goodslist = $this->Session->read('cart');
        $rt['goodslist'] = array();
        if (!empty($goodslist)) {
            foreach ($goodslist as $k => $row) {
                $rt['goodslist'][$k] = $row;
                $rt['goodslist'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
                $rt['goodslist'][$k]['goods_thumb'] = is_file(SYS_PATH . $row['goods_thumb']) ? SITE_URL . $row['goods_thumb'] : SITE_URL . 'theme/images/no_picture.gif';
                $rt['goodslist'][$k]['goods_img'] = is_file(SYS_PATH . $row['goods_img']) ? SITE_URL . $row['goods_img'] : SITE_URL . 'theme/images/no_picture.gif';
                $rt['goodslist'][$k]['original_img'] = is_file(SYS_PATH . $row['original_img']) ? SITE_URL . $row['original_img'] : SITE_URL . 'theme/images/no_picture.gif';

                //求出实际价格
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    if ($rt['discount'] > 0) {
                        $comd[] = ($rt['discount'] / 100) * $row['shop_price'];
                    }
                    if ($row['shop_price'] > 0 && $rank == 1) { //个人会员价格
                        $comd[] = $row['shop_price']; //个人会员价格
                    }
                    if ($row['pifa_price'] > 0 && $rank != '1') { //高级会员价格
                        $comd[] = $row['pifa_price']; //高级会员价格
                    }
                } else {
                    $comd[] = $row['shop_price'];
                }

                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                    $comd[] = $row['promote_price'];
                }
                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }

                $onetotal = min($comd);
                if (intval($onetotal) <= 0)
                    $onetotal = $row['shop_price'];
                $total +=($row['number'] * $onetotal); //总价格
            } //end foreach
            unset($goodslist);
        } //end if cart
        //赠品类型
        $fn = SYS_PATH . 'data/goods_spend_gift.php';
        $spendgift = array();
        if (file_exists($fn) && is_file($fn)) {
            include_once($fn);
        }
        $rt['gift_typesd'] = $spendgift;
        unset($spendgift);

        //商品赠品模块
        $minspend = array();
        if (!empty($rt['gift_typesd'])) {
            foreach ($rt['gift_typesd'] as $k => $row) {
                ++$k;
                $minspend[$k] = $row['minspend'];
            }
            arsort($minspend);
        }

        $rt['gift_goods'] = array();
        $type = 0;
        if (count($minspend) > 0) {
            $count = count($minspend);
            foreach ($minspend as $t => $val) {  //已最高消费赠品为准
                if ($total >= $val) {
                    $type = $t; //赠品等级
                    break;
                }
            }
            unset($minspend);
            //赠品
            $rt['gift_goods_ids'] = array();
            if ($type > 0) {
                $sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
                $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
                $sql .=" WHERE (tb2.is_alone_sale='0' OR tb2.is_alone_sale IS NOT NULL) AND tb2.is_on_sale='1' AND AND tb2.is_delete='0' AND tb1.type='$type'";
                $gift_goods = $this->App->find($sql);
                if (!empty($gift_goods)) {
                    foreach ($gift_goods as $k => $row) {
                        $rt['gift_goods_ids'][] = $row['goods_id']; //记录赠品的id
                    }
                    unset($gift_goods);
                }
            }
        }

        $this->set('rt', $rt);
        $con = $this->fetch('ajax_mycart', true);
        die($con);
    }

    //清空购物车
    function mycart_clear() {
        $this->Session->write("cart", "");
        // $this->jump(SITE_URL.'shopping/');
        $this->template('mycart_list');
        exit;
    }

    //处理用注册赠送1200元来消费的功能
    function get_give_off_monery($total = 0) {
        $uid = $this->Session->read('User.uid');
        $rank = $this->Session->read('User.rank');
        if (!($uid > 0) || !($total > 0))
            return 0.00;
        $give_money = $this->App->findvar("SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' AND type='register'");

        $give_money_month = ($GLOBALS['LANG']['reg_give_money_data']['give_money_month'] / 100) * $give_money; //每个月只能消费多少

        $give_money_month_one = $GLOBALS['LANG']['reg_give_money_data']['give_money_month_one' . $rank]; //报销当月最大消费的百分之几

        $sba = $this->App->findvar("SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
        $rt['shengxiamoney'] = format_price($sba); //还剩下多少可以消费的资金
        /* $zspendprice = $this->App->findvar("SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' AND type='spend'");
          if(!empty($zspendprice)){
          $zspendprice = abs($zspendprice);
          } */

        //查找当月已经消费多少了
        $m = date('m', mktime());
        $thismouthspend = 0;
        $thismouthspend = $this->App->findvar("SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' AND type='spend' AND thismonth='$m'");
        if (!empty($thismouthspend)) {
            $thismouthspend = abs($thismouthspend);
        }
        $rt['month_spend_money'] = format_price($thismouthspend); //当月已经消费多少了

        $rt['shippingprice'] = $total;
        $rt['month_max_spend_money'] = 0.00;
        if ($thismouthspend >= $give_money_month) { //消费满了，不能再用赠送消费了
            $rt['offmoney'] = 0.00;
            return $rt;
        } else {
            $shengxiamoney = $give_money_month - $thismouthspend; //这个月最多只能消费那么多
        }
        $rt['month_max_spend_money'] = format_price($shengxiamoney); //这个月最多只能消费那么多

        $thisspendgoodsmoney = $total * ($give_money_month_one / 100); //这次购物所能抵消的费用

        if ($thisspendgoodsmoney >= $shengxiamoney) {
            $rt['offmoney'] = format_price($shengxiamoney);
        } else {
            $rt['offmoney'] = format_price($thisspendgoodsmoney);
        }
        $rt['shippingprice'] = format_price($total - $rt['offmoney']); //实际支付
        return $rt;

        //查找
    }

    //商品自动分配供应商，供应商的区域检查
    function auto_goods_assign_suppliers($shop_address_id = 0, $goods_id = 0) {
        if (!($shop_address_id > 0) || !($goods_id > 0)) {
            die("尊敬的顾客你好，由于我们的购物系统出现故障，目前将暂时停止订购！对此，引起你的不便，敬请原谅！");
        }
        $sql = "SELECT country,province,city,district,town,village FROM `{$this->App->prefix()}user_address` WHERE address_id='$shop_address_id'";
        $ids = $this->App->findrow($sql); //这里是当前收货地址
        //检查当前商品的供应商
        $sql = "SELECT ua.user_id FROM `{$this->App->prefix()}user_address` AS ua LEFT JOIN `{$this->App->prefix()}suppliers_goods` AS sg ON sg.suppliers_id = ua.user_id";
        $sql .=" WHERE sg.goods_id='$goods_id' AND sg.is_delete='0' AND sg.is_on_sale='1' AND sg.is_check='1' GROUP BY ua.user_id ORDER BY ua.user_id ASC"; //如果很多供应商的时候，这样还需要增加条件查询更精确的供应商
        $suppliers_ids = $this->App->findcol($sql); //所有供应商ID
        //从中找到送货地址最近的供应商
        if (!empty($suppliers_ids)) {
            $this->App->fieldkey('suppliers_id');
            $area_data_item = $this->App->find("SELECT suppliers_id,area_data FROM `{$this->App->prefix()}suppliers_shoppong_area` WHERE suppliers_id IN(" . implode(',', $suppliers_ids) . ") AND active='1'");

            foreach ($suppliers_ids as $id) {
                $area_data = isset($area_data_item[$id]['area_data']) ? $area_data_item[$id]['area_data'] : "";
                $areaid = !empty($area_data) ? json_decode($area_data) : array(); //该供应商的配送范围的区域ID
                if (!empty($areaid)) {
                    if (in_array($ids['village'], $areaid)) { //村
                        return $id;
                        continue;
                    }
                } else {
                    continue;
                }
            }//end foreach

            foreach ($suppliers_ids as $id) {
                $area_data = $area_data_item[$id]['area_data'];
                $areaid = !empty($area_data) ? json_decode($area_data) : array(); //该供应商的配送范围的区域ID
                if (!empty($areaid)) {
                    if (in_array($ids['town'], $areaid)) { //镇
                        return $id;
                        continue;
                    }
                } else {
                    continue;
                }
            }//end foreach


            foreach ($suppliers_ids as $id) {
                $area_data = $area_data_item[$id]['area_data'];
                $areaid = !empty($area_data) ? json_decode($area_data) : array(); //该供应商的配送范围的区域ID
                if (!empty($areaid)) {
                    if (in_array($ids['district'], $areaid)) { //区
                        return $id;
                        continue;
                    }
                } else {
                    continue;
                }
            }//end foreach

            foreach ($suppliers_ids as $id) {
                $area_data = $area_data_item[$id]['area_data'];
                $areaid = !empty($area_data) ? json_decode($area_data) : array(); //该供应商的配送范围的区域ID
                if (!empty($areaid)) {
                    if (in_array($ids['city'], $areaid)) { //城市
                        return $id;
                        continue;
                    }
                } else {
                    continue;
                }
            }//end foreach


            foreach ($suppliers_ids as $id) {
                $area_data = $area_data_item[$id]['area_data'];
                $areaid = !empty($area_data) ? json_decode($area_data) : array(); //该供应商的配送范围的区域ID
                if (!empty($areaid)) {
                    if (in_array($ids['province'], $areaid)) { //省
                        return $id;
                        continue;
                    }
                } else {
                    continue;
                }
            }//end foreach

            foreach ($suppliers_ids as $id) {
                $area_data = $area_data_item[$id]['area_data'];
                $areaid = !empty($area_data) ? json_decode($area_data) : array(); //该供应商的配送范围的区域ID
                if (!empty($areaid)) {
                    if (in_array($ids['country'], $areaid)) { //国家
                        return $id;
                        continue;
                    }
                } else {
                    continue;
                }
            }//end foreach
        } //end if
        //die("尊敬的顾客你好，由于我们的购物系统出现故障，目前将暂时停止订购！对此，引起你的不便，敬请原谅！");
        //die("尊敬的顾客你好，由于我们的配送系统出现故障，目前没有商品仓库配送到该配送店！对此，引起你的不便，敬请原谅！");
        $this->template('shopping_noprice_error');
        exit;
        //return $suid;
    }

//end function
    function update_daili_tree($uid = 0) {
        if ($uid > 0) {
            $dd = array();
            $dd['uid'] = $uid;
            $dd['p1_uid'] = 0;
            $dd['p2_uid'] = 0;
            $dd['p3_uid'] = 0;

            $p1_uid = $this->return_daili_uid($uid); //最近分销

            $firtuids = array();
            if ($p1_uid > 0) {
                $dd['p1_uid'] = $p1_uid;
                $p2_uid = $this->return_daili_uid($p1_uid);

                if ($p2_uid > 0) {
                    $dd['p2_uid'] = $p2_uid;
                    $p3_uid = $this->return_daili_uid($p2_uid);

                    if ($p3_uid > 0) {
                        $dd['p3_uid'] = $p3_uid;
                        /* $p4_uid = $this->return_daili_uid($p3_uid);
                          if($p4_uid > 0){
                          $dd['p4_uid'] = $p4_uid;
                          } */
                    }
                }
            }

            //
            $sql = "SELECT id FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid='$uid' LIMIT 1";
            $id = $this->App->findvar($sql);

            if ($id > 0) {
                $this->App->update('user_tuijian_fx', $dd, 'id', $id);
            } else {
                $this->App->insert('user_tuijian_fx', $dd);
            }

            //
            $firtuids = $this->_firtuids($uid); //当前开通用户的最近一层分销用户

            $aup = array();
            if (!empty($firtuids))
                foreach ($firtuids as $u) { //
                    $dds = array();
                    $dds['uid'] = $u;
                    $dds['p1_uid'] = $uid;
                    $dds['p2_uid'] = $dd['p1_uid'];
                    $dds['p3_uid'] = $dd['p2_uid'];

                    $aup[] = $dds;

                    $firtuids2 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$u'");
                    if (!empty($firtuids2))
                        foreach ($firtuids2 as $uu) { //
                            $dds = array();
                            $dds['uid'] = $uu;
                            $dds['p1_uid'] = $u;
                            $dds['p2_uid'] = $uid;
                            $dds['p3_uid'] = $dd['p1_uid'];

                            $aup[] = $dds;

                            $firtuids3 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$uu'");
                            if (!empty($firtuids3))
                                foreach ($firtuids3 as $uuu) { //
                                    $dds = array();
                                    $dds['uid'] = $uuu;
                                    $dds['p1_uid'] = $uu;
                                    $dds['p2_uid'] = $u;
                                    $dds['p3_uid'] = $uid;

                                    $aup[] = $dds;
                                }//end foreach
                            unset($firtuids3);
                        } //end foreach
                    unset($firtuids2);
                } //end foreach
            unset($firtuids);

            if (!empty($aup))
                foreach ($aup as $up) {
                    $this->App->update('user_tuijian_fx', $up, 'uid', $up['uid']);
                }
            unset($aup);
        } //end if
    }

    //获取用户的openid
    function get_openid_AND_pay_info() {
        $wecha_id = $this->Session->read('User.wecha_id');
        if (empty($wecha_id))
            $wecha_id = isset($_COOKIE[CFGH . 'USER']['UKEY']) ? $_COOKIE[CFGH . 'USER']['UKEY'] : '';
        $wecha_id = $wecha_id;

        //
        $order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
        $sql = "SELECT order_sn,order_amount,pay_status,shipping_fee,add_time,zifuchuan FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
        $rt = $this->App->findrow($sql);
        $rt['order_amount'] = $rt['order_amount'] + $rt['shipping_fee'];
        if (empty($rt)) {
            $this->jump(str_replace('/wxpay', '', ADMIN_URL), 0, '非法支付提交！');
            exit;
        }
        if ($rt['pay_status'] == '1') {
            $this->jump(str_replace('/wxpay', '', ADMIN_URL) . 'user.php?act=orderlist');
            exit;
        }
        $rt['openid'] = $wecha_id;
        $rt['body'] = $GLOBALS['LANG']['site_name'] . '购物平台';
        return $rt;
    }
	
	
	
	function get_dianpus($dd){
		  foreach ($dd as $k => $row) {
		  $cids[] = $row['supplier_id'];
		  }
		  return $cids;
		}
	

function ajax_get_suppname($data = array()){
	 $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : 0;
	 
	 if($supplier_id == 0){
		 return "网店自营";
	 }
	 if($supplier_id > 0){
		   $sql = "SELECT site_name FROM `{$this->App->prefix()}supplier_systemconfig` WHERE supplier_id=".$supplier_id." LIMIT 1";
		//echo $sql;
		$info = $this->App->findrow($sql);
		return $info['site_name'];
		 
		 }
	}
	
	
	
	
	function get_goodslist($oid){
		 
		   $goodslist = array();
		   $sql = "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='".$oid."' ORDER BY goods_id";
		   $info = $this->App->find($sql);
		//   echo $sql;
		   foreach($info  as  $row){
			   $goodslist[$row['rec_id']]['goods_thumb'] = $row['goods_thumb'];
			    $goodslist[$row['rec_id']]['goods_name'] = $row['goods_name'];
				 $goodslist[$row['rec_id']]['goods_attr'] = $row['goods_attr'];
				  $goodslist[$row['rec_id']]['market_price'] = $row['market_price'];
				   $goodslist[$row['rec_id']]['goods_price'] = $row['goods_price'];
				    $goodslist[$row['rec_id']]['goods_number'] = $row['goods_number'];
					

			   }
		   return $goodslist;
		}
		
		
		
		
		
		/*
 * 获取订单对应的佣金记录id(只有店铺才计算)
 * @param int $suppid  店铺id
 */
function get_order_rebate($suppid){
	$spkey = intval($suppid);
	if($spkey<=0){
		return 0;
	}
	$sql = "select rebate_id, rebate_paytime_start, rebate_paytime_end from `{$this->App->prefix()}supplier_rebate` where supplier_id='$spkey' and is_pay_ok=0 order by rebate_id desc limit 0,1";
	$row = $this->App->findrow($sql);
	$nowtime = time();
	if (  $nowtime >=  $row['rebate_paytime_start']  && $nowtime <= $row['rebate_paytime_end'] )
	{
		$rebate_id= $row['rebate_id'];
	}
	else
	{
		$kkk='yes';
		while($kkk=='yes')
		{
			$this->insert_id_rebate($spkey);
			$sql2 = "select rebate_id, rebate_paytime_start, rebate_paytime_end from `{$this->App->prefix()}supplier_rebate` where supplier_id='$spkey' and is_pay_ok=0 order by rebate_id desc limit 0,1";
			$row2 = $this->App->findrow($sql2);
			if (  $nowtime >=  $row2['rebate_paytime_start']  && $nowtime <= $row2['rebate_paytime_end'] )
			{
				$rebate_id= $row2['rebate_id'];
				$kkk='no';
			}
		}
	  }
	  return $rebate_id;
}



function  insert_id_rebate($supplier_id)
{
		$sql="select supplier_rebate_paytime from `{$this->App->prefix()}supplier` where supplier_id='$supplier_id'";
		$supplier_rebate_paytime = $this->App->findvar($sql);

		$sql = "select rebate_paytime_start, rebate_paytime_end from `{$this->App->prefix()}supplier_rebate` where supplier_id= '$supplier_id' and is_pay_ok=0 order by rebate_id DESC LIMIT 0,1";
		$row = $this->App->findrow($sql);
		if (!$row['rebate_paytime_start'])
		{
			$rebate_paytime_start = $this->local_mktime(0,0,0,$this->local_date('m'), $this->local_date('d'), $this->local_date('Y'));
		}
		if (!$row['rebate_paytime_end'])
		{
			switch($supplier_rebate_paytime)
			{
				case '1':
					$rebate_paytime_end= $this->local_strtotime("this Sunday") + 24*60*60-1;
					break;
				case '2':
					$rebate_paytime_end= $this->local_mktime(23,59,59,$this->local_date("m"),$this->local_date("t"),$this->local_date("Y"));
					break;
				case '3':
					if ($this->local_date("m")=='1' || $this->local_date("m")=='2' || $this->local_date("m")=='3')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59,3,31,$this->local_date("Y"));
					}
					elseif ($this->local_date("m")=='4' || $this->local_date("m")=='5' || $this->local_date("m")=='6')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59, 6,30,$this->local_date("Y"));
					}
					elseif($this->local_date("m")=='7' || $this->local_date("m")=='8' || $this->local_date("m")=='9')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59, 9, 30,$this->local_date("Y"));
					}
					elseif($this->local_date("m")=='10' || $this->local_date("m")=='11' || $this->local_date("m")=='12')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59, 12,31,$this->local_date("Y"));
					}
					break;
				case '4':
					$rebate_paytime_end= $this->local_mktime(23,59,59,12,31,$this->local_date("Y"));
					break;
			}
		}
		if ( $row['rebate_paytime_start']  &&  $row['rebate_paytime_end'] )
		{
			$rebate_paytime_start = $row['rebate_paytime_end'] + 1;
			switch($supplier_rebate_paytime)
			{
				case '1':
					$rebate_paytime_end= $row['rebate_paytime_end'] + 24*60*60*7;
					break;
				case '2':
					$rebate_paytime_end= $this->local_mktime(23,59,59,$this->local_date("m",$rebate_paytime_start),$this->local_date("t",$rebate_paytime_start),$this->local_date("Y",$rebate_paytime_start));
					break;
				case '3':
					if ($this->local_date("m",$rebate_paytime_start)=='1' || $this->local_date("m")=='2' || $this->local_date("m")=='3')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59,3,31,$this->local_date("Y"));
					}
					elseif ($this->local_date("m")=='4' || $this->local_date("m")=='5' || $this->local_date("m")=='6')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59, 6,30,$this->local_date("Y"));
					}
					elseif($this->local_date("m")=='7' || $this->local_date("m")=='8' || $this->local_date("m")=='9')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59, 9, 30,$this->local_date("Y"));
					}
					elseif($this->local_date("m")=='10' || $this->local_date("m")=='11' || $this->local_date("m")=='12')
					{
						$rebate_paytime_end= $this->local_mktime(23,59,59, 12,31,$this->local_date("Y"));
					}
					break;
				case '4':
					$rebate_paytime_end= $this->local_mktime(23,59,59,12,31,$this->local_date("Y"));
					break;
			}
		}

		$sql="insert into `{$this->App->prefix()}supplier_rebate`  (rebate_paytime_start, rebate_paytime_end, supplier_id) value('$rebate_paytime_start', '$rebate_paytime_end', '$supplier_id') ";
		$this->App->query($sql);
}


/**
 *  生成一个用户自定义时区日期的GMT时间戳
 *
 * @access  public
 * @param   int     $hour
 * @param   int     $minute
 * @param   int     $second
 * @param   int     $month
 * @param   int     $day
 * @param   int     $year
 *
 * @return void
 */
function local_mktime($hour = NULL , $minute= NULL, $second = NULL,  $month = NULL,  $day = NULL,  $year = NULL)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
    **/
    $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

    return $time;
}


/**
 *  将一个用户自定义时区的日期转为GMT时间戳
 *
 * @access  public
 * @param   string      $str
 *
 * @return  integer
 */
function local_strtotime($str)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
    **/
    $time = strtotime($str) - $timezone * 3600;

    return $time;

}


/**
 * 将GMT时间戳格式化为用户自定义时区日期
 *
 * @param  string       $format
 * @param  integer      $time       该参数必须是一个GMT的时间戳
 *
 * @return  string
 */

function local_date($format, $time = NULL)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    if ($time === NULL)
    {
        $time = time();
    }
    elseif ($time <= 0)
    {
        return '';
    }

    $time += ($timezone * 3600);

    return date($format, $time);
}









}
