<?php
class ShoppingController extends Controller {
    /*
     * @Photo Index
     * @param <type> $page
     * @param <type> $type
    */
    function __construct() {
        /*
         * 构造函数
        */
        $this->js(array('jquery.json-1.3.js', 'goods.js', 'user.js'));
        $this->css(array('comman.css'));
    }
    /* 析构函数 */
    function __destruct() {
        unset($rt);
    }
    ////////////////////////////////////////////////////////////////////
    //一个商品对应多个收货地址
    function ajax_get_address($data = array()) {
        $province = $data['province'];
        $city = $data['city'];
        $district = $data['district'];
        $resslist = $this->action('user', 'get_regions', 1); //获取省列表
        $dbress = array();
        if ($province > 0) {
            $dbress['city'] = $this->action('user', 'get_regions', 2, $province);
        }
        if ($city > 0) {
            $dbress['district'] = $this->action('user', 'get_regions', 3, $city);
        }
        $dbtype['province'] = $province;
        $dbtype['city'] = $city;
        $dbtype['district'] = $district;
        $this->set('dbtype', $dbtype);
        $this->set('dbress', $dbress);
        $this->set('resslist', $resslist);
        $this->set('goods_id', $data['gid']);
        echo $this->fetch('addressmore', true);
        exit;
    }
    function ajax_jisuanprice($data = array()) {
        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
        $rts = $this->App->findrow($sql);
        $gid = $data['gid'];
        $num = $data['num'];
        $goodslist  = $this->Session->read('cart');
        $shop_price = $goodslist[$gid]['shop_price'];
        $pifa_price = $goodslist[$gid]['pifa_price'];
        $issubscribe = $this->Session->read('User.subscribe');
        $guanzhuoff  = $rts['guanzhuoff'];
        $address3off = $rts['address3off'];
        $address2off = $rts['address2off'];
        if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
            $pifa_price = ($guanzhuoff / 100) * $pifa_price;
        }
        if ($num >= 2 && $address2off < 101 && $address2off > 0) {
            $pifa_price = ($address2off / 100) * $pifa_price;
        }
        if ($num >= 3 && $address3off < 101 && $address3off > 0) {
            $pifa_price = ($address3off / 100) * $pifa_price;
        }
        echo $pifa_price;
        exit;
    }
    //原始下单版本
    function confirm_daigou() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $order_sn = date('Y', mktime()) . mktime();
        if (isset($_POST) && !empty($_POST)) {
            $totalprice = $_POST['totalprice'];
            if ($totalprice < 0) {
                $this->jump(ADMIN_URL, 0, '非法提交');
                exit;
            }
            $pay_id = $_POST['pay_id'];
            $pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix() }payment` WHERE pay_id='$pay_id' LIMIT 1");
            $shipping_id = $_POST['shipping_id'];
            $shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix() }shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
            $postscript = $_POST['postscript'];
            $goodslist = $this->Session->read('cart');
            if (empty($goodslist)) {
                $this->jump(ADMIN_URL, 0, '购物车为空');
                exit;
            }
            //添加订单表
            $orderdata = array();
            $orderdata['pay_id'] = $pay_id;
            $orderdata['shipping_id'] = $shipping_id;
            $orderdata['pay_name'] = $pay_name;
            $orderdata['shipping_name'] = $shipping_name;
            $orderdata['order_sn'] = $order_sn;
            $orderdata['user_id'] = $uid;
            $parent_uid = $this->App->findvar("SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid='$uid' LIMIT 1");
            $orderdata['parent_uid'] = $parent_uid > 0 ? $parent_uid : '0';
            $orderdata['postscript'] = $postscript;
            $orderdata['goods_amount'] = $totalprice;
            $orderdata['order_amount'] = $totalprice;
            $orderdata['add_time'] = mktime();
            $this->App->insert('goods_order_info_daigou', $orderdata);
            $orderid = $this->App->iid();
            if ($orderid > 0) foreach ($goodslist as $row) {
                $gid = $row['goods_id'];
                $consignees = $_POST['consignee'][$gid];
                $numbers = $_POST['goods_number'][$gid];
                $moblies = $_POST['moblie'][$gid];
                $provinces = $_POST['province'][$gid];
                $citys = $_POST['city'][$gid];
                $districts = $_POST['district'][$gid];
                $addresss = $_POST['address'][$gid];
                if (empty($consignees)) continue;
                //添加订单商品表
                $ds = array();
                $ds['order_id'] = $orderid;
                $ds['goods_id'] = $gid;
                $ds['brand_id'] = $row['brand_id'];
                $ds['goods_name'] = $row['goods_name'];
                $ds['goods_thumb'] = $row['goods_thumb'];
                $ds['goods_bianhao'] = $row['goods_bianhao'];
                $ds['goods_unit'] = $row['goods_unit'];
                $ds['goods_sn'] = $row['goods_sn'];
                $ds['market_price'] = $row['shop_price'];
                $ds['goods_price'] = $row['pifa_price'];
                if (!empty($row['spec'])) $ds['goods_attr'] = implode("、", $row['spec']);
                $this->App->insert('goods_order_daigou', $ds);
                $rec_id = $this->App->iid();
                //添加订单地址表
                if ($rec_id > 0) { 
                    foreach ($consignees as $k => $consignee) {
                        $dd = array();
                        $dd['consignee'] = $consignee;
                        $dd['goods_number'] = $numbers[$k];
                        $dd['moblie'] = $moblies[$k];
                        $dd['province'] = $provinces[$k];
                        $dd['city'] = $citys[$k];
                        $dd['district'] = $districts[$k];
                        $dd['address'] = $addresss[$k];
                        $dd['rec_id'] = $rec_id;
                        $this->App->insert('goods_order_address', $dd);
                    }
                }
            }
        }
        $this->Session->write('cart', null);
        $this->jump(ADMIN_URL . 'mycart.php?type=pay&oid=' . $orderid);
        exit;
        exit;
    }
    function pay() {
        $this->action('common', 'checkjump');
        if (!defined(NAVNAME)) define('NAVNAME', "在线支付");
        $oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
        if (!($oid > 0)) {
            $this->jump(ADMIN_URL);
            exit;
        }
        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE order_id='$oid' LIMIT 1";
        $orderinfo = $this->App->findrow($sql);
        if (empty($orderinfo)) {
            $this->jump(ADMIN_URL);
            exit;
        }
        $sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix() }goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$oid' GROUP BY tb2.rec_id";
        $ordergoods = $this->App->find($sql);
        $this->set('ordergoods', $ordergoods);
        $this->set('orderinfo', $orderinfo);
        $this->template('order_pay');
    }
    function pay2() {
        $this->action('common', 'checkjump');
        if (!defined(NAVNAME)) define('NAVNAME', "在线支付");
        $oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
        if (!($oid > 0)) {
            $this->jump(ADMIN_URL);
            exit;
        }
        $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix() }goods_order_info` AS tb1";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";
        $sql.= " WHERE tb1.order_id='$oid'";
        $rt['orderinfo'] = $this->App->findrow($sql);
        if (empty($rt['orderinfo'])) {
            $this->jump(ADMIN_URL);
            exit;
        }
        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order` WHERE order_id='$oid' ORDER BY goods_id";
        $rt['goodslist'] = $this->App->find($sql);
        //我的余额
        $uid = $this->Session->read('User.uid');
        if ($uid > 0) {
            $sql = "SELECT mymoney FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
            $rt['mymoney'] = $this->App->findvar($sql);
        } else {
            $rt['mymoney'] = 0;
        }
        //支付方式
        $sql = "SELECT * FROM `{$this->App->prefix() }payment` WHERE enabled='1'";
        $rt['paymentlist'] = $this->App->find($sql);
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/shopping_order_pay');
    }
    function ajax_update_payid($rt = array()) {
        $payid = $rt['payid'];
        $oid = $rt['oid'];
        if ($payid > 0 && $oid > 0) {
            $pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix() }payment` WHERE pay_id='$payid' LIMIT 1");
            $this->App->update('goods_order_info', array('pay_id' => $payid, 'pay_name' => $pay_name), 'order_id', $oid);
        }
    }
    //快速支付
    function fastpay() {
        $oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
        if (!($oid > 0)) {
            $this->jump(ADMIN_URL, 0, '意外错误');
            exit;
        }
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            $this->jump(ADMIN_URL, 0, '非法支付提交！');
            exit;
        }
        $rts['pay_id'] = $rt['pay_id'];
        $rts['order_sn'] = $rt['order_sn'];
        $rts['order_amount'] = $rt['order_amount'];
        $rts['logistics_fee'] = $rt['shipping_fee'];
        $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }systemconfig` WHERE type='basic' LIMIT 1");
        $rts['address'] = $userredd['company_url'];
        $this->_alipayment($rts);
        unset($rt);
        exit;
    }
    //快速支付
    function fastpay2() {
        $oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
        if (!($oid > 0)) {
            $this->jump(ADMIN_URL, 0, '意外错误');
            exit;
        }
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            $this->jump(ADMIN_URL, 0, '非法支付提交！');
            exit;
        }
        $rts['pay_id'] = $rt['pay_id'];
        $rts['order_sn'] = $rt['order_sn'];
        $rts['order_amount'] = $rt['order_amount'];
        $rts['logistics_fee'] = $rt['shipping_fee'];
        $userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }systemconfig` WHERE type='basic' LIMIT 1");
        $rts['address'] = $userredd['company_url'];
        $this->_alipayment($rts);
        unset($rt);
        exit;
    }
    function ajax_remove_cargoods($data = array()) {
        $gid = $data['gid'];
        $uid = $this->Session->read('User.uid');
        if (!empty($gid)) {
            $cartlist = $this->Session->read('cart');
            if (isset($cartlist[$gid])) {
                $this->Session->write("cart.{$gid}", null);
            }
            $useradd = $this->Session->read('useradd');
            if (isset($useradd[$gid])) {
                $this->Session->write("useradd.{$gid}", null);
            }
        }
        //返回总价
        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
        $rts = $this->App->findrow($sql);
        $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $issubscribe = $this->App->findvar($sql);
        $guanzhuoff = $rts['guanzhuoff'];
        $address3off = $rts['address3off'];
        $address2off = $rts['address2off'];
        $prices = 0;
        $cartlist = $this->Session->read('cart');
        $off = 1;
        if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) { //关注折扣
            $off = ($guanzhuoff / 100);
        }
        $counts = 0;
        foreach ($cartlist as $k => $row) {
            $counts+= $row['number'];
        }
        if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
            $off = ($address2off / 100) * $off; //相对关注再折扣
            
        }
        if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
            $off = ($address3off / 100) * $off; //相对关注再折扣
            
        }
        foreach ($cartlist as $k => $row) {
            $prices+= format_price($row['pifa_price'] * $off) * $row['number'];
        }
        echo format_price($prices);
    }
    /////////////////////////////////////////////////////////////////////////
    //2018/03/15 微信支付pay_id = 2
    function _get_payinfo($id = 0) {
        if ($id == '2') { //微信支付 
            $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id' LIMIT 1");
            			$appid = $this->Session->read('User.appid');
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
              } 
            $sql = "SELECT appid,appsecret FROM `{$this->App->prefix() }wxuserset` ORDER BY id DESC LIMIT 1";
            $rts = $this->App->findrow($sql);
            $rt['appid'] = $rts['appid'];
            $rt['appsecret'] = $rts['appsecret'];
        } else {
            $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
        }
        return $rt;
    }
    function baoming_pay_successs_tatus($order_sn = '') {
        //改变状态
        $dd = array();
        $dd['pay_status'] = '1';
        $dd['pay_time'] = mktime();
        $this->App->update('cx_baoming_order', $dd, 'order_sn', $order_sn);
        //改变状态
        $userinfo = $this->App->findrow("SELECT bo.user_id ,bo.bid,bo.order_amount,bo.key,b.rank_id  FROM `{$this->App->prefix() }cx_baoming_order` as  bo left join `{$this->App->prefix() }cx_baoming` as b  on b.id=bo.bid WHERE order_sn='$order_sn' LIMIT 1");
        $uid = $userinfo['user_id'];
        if ($uid) {
            $newrank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
            //修改会员等级
            $tp = fopen(time()."uid=".$uid . ".txt", "a+");

            if ($userinfo['rank_id'] > $newrank) {
                $this->App->update('user', array('user_rank' => $userinfo['rank_id']), 'user_id', $uid);
                //  $this->update_daili_tree($uid); //更新代理关系
                //记录父级升级记录
                $remarklog = '直接充值进行会员升级';
                $sql = "insert into `{$this->App->prefix() }user_level_log` (user_id,user_rank,create_time,type,remark) values ('$uid', $userinfo[rank_id],UNIX_TIMESTAMP(),2,'$remarklog')";
                fwrite($tp, $sql . "\r\n");
                $this->App->query($sql);
				
				//分润，佣金，升级奖励升级不返
				if($userinfo['key'] == 'fenrun' || $userinfo['key'] == 'yongjin' || $userinfo['key'] == 'tuiguang'){
					 return true;
                     exit;
					}
                //查看是否有父级
                $sql = "SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = '$uid' LIMIT 1";
                fwrite($tp, $sql . "\r\n");
                $p = $this->App->findvar($sql);
                $appid = $this->Session->read('User.appid');
                if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
                $appsecret = $this->Session->read('User.appsecret');
                if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
                if ($p) {
                    //查找父级的详细信息
                    $puser = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$p' LIMIT 1");
                    fwrite($tp, $sql . "\r\n");
                    //父级等级
                    $prank = $puser['user_rank']; // 金牌，钻石，皇冠，合伙人
                    //当前用户升级的等级
                    $srank = $userinfo['rank_id']; //金牌，钻石，皇冠，合伙人
                    //鑫鑫  推广分成
                    $moeys = $userinfo['order_amount'] * 0.5;
                    if ($moeys > 0) {
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
                        $sql = "UPDATE `{$this->App->prefix() }user` SET `tuiguang` = `tuiguang`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$p'";
                        fwrite($tp, $sql . "\r\n");
                        $this->App->query($sql);
                        $sql1 = "UPDATE `{$this->App->prefix() }user_moneys` SET `tuiguang` = `tuiguang`+$moeys WHERE uid = '$p'";
                        $this->App->query($sql1);
                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值,会员升级返佣金', 'time' => mktime(), 'type' => 999, 'uid' => $p));
                        //发送推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$p' LIMIT 1");
                        $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => '', 'money' => $moeys, 'rank_id' => $userinfo['rank_id']), 'tjmember');
                    }
                    //查找上上级会员信息 如果是黄金会员
                    $sql = "SELECT * FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid = '$uid' LIMIT 1";
                    fwrite($tp, $sql . "\r\n");
                    $ppuser = $this->App->findrow($sql);
                    //  $payfee2 = array(12 => 5, 11 => 100, 10 => 500);
                    //上上级会员的等级
                    $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser[p2_uid]' LIMIT 1";
                    fwrite($tp, $sql . "\r\n");
                    $ppuser2 = $this->App->findrow($sql);
                    if ($ppuser2) {
                        $prank2 = $ppuser2['user_rank'];
                        //二级分成
                        $moeys = $userinfo['order_amount'] * 0.2;
                        if ($moeys > 0) {
                            $thismonth = date('Y-m-d', mktime());
                            $thism = date('Y-m', mktime());
                            $sql = "UPDATE `{$this->App->prefix() }user` SET `tuiguang` = `tuiguang`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$ppuser2[user_id]'";
                            fwrite($tp, $sql . "\r\n");
                            $this->App->query($sql);
                            $sql1 = "UPDATE `{$this->App->prefix() }user_moneys` SET `tuiguang` = `tuiguang`+$moeys WHERE uid = '$ppuser2[user_id]'";
                            $this->App->query($sql1);
                            $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值,会员升级返佣金', 'time' => mktime(), 'uid' => $ppuser2['user_id']));
                            //发送推荐用户通知
                            $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser2[user_id]' LIMIT 1");
                            $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => '', 'money' => $moeys, 'rank_id' => $userinfo['rank_id']), 'tjmember');
                        }
                    }
                    //上上上级会员的等级
                    $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser[p3_uid]' LIMIT 1";
                    fwrite($tp, $sql . "\r\n");
                    $ppuser3 = $this->App->findrow($sql);
                    if ($ppuser3) {
                        $prank3 = $ppuser3['user_rank'];
                        //三级分成
                        $moeys = $userinfo['order_amount'] * 0.1;
                        if ($moeys > 0) {
                            $thismonth = date('Y-m-d', mktime());
                            $thism = date('Y-m', mktime());
                            $sql = "UPDATE `{$this->App->prefix() }user` SET `tuiguang` = `tuiguang`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$ppuser3[user_id]'";
                            fwrite($tp, $sql . "\r\n");
                            $this->App->query($sql);
                            $sql1 = "UPDATE `{$this->App->prefix() }user_moneys` SET `tuiguang` = `tuiguang`+$moeys WHERE uid = '$ppuser3[user_id]'";
                            $this->App->query($sql1);
                            $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值升级,会员返佣金', 'time' => mktime(), 'uid' => $ppuser3['user_id']));
                            //发送推荐用户通知
                            $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser3[user_id]' LIMIT 1");
                            $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => '', 'money' => $moeys), 'tjmember');
                        }
                    }
                }
                fclose($tp);
            }
        }
        return true;
        exit;
    }
    //微信支付成功，修改订单状态
    function pay_successs_status2222($rt = array()) {
        set_time_limit(300); //最大运行时间
        $order_sn = $rt['order_sn'];
        $status = $rt['status'];
        $pu = $this->App->findrow("SELECT user_id FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
        //购买用户
        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
        $nickname = $ni['nickname'];
        $dd = array();
        $dd['order_status'] = '2';
        $dd['pay_status'] = '1';
        $dd['pay_time'] = mktime();
        $this->App->update('goods_order_info', $dd, 'order_sn', $order_sn);
        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");
        if (!empty($pwecha_id)) {
            $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname), 'payconfirm');
        }
    }
    //	 function notify_url(){
    //
    //				 echo "sssssss";
    //
    //	 $pay_result =  $_REQUEST['success'];//交易结果，1为成功，0未支付，2支付失败
    //	  $msg = $_REQUEST['msg'];//时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
    //	  $order_no = $_REQUEST['order_no'];
    //	  $ptime = $_REQUEST['ptime'];//付款时间
    //      $amount = $_REQUEST['amount'];
    //	  $num = $_REQUEST['num'];
    //
    //	   $sd = array('order_sn' => $order_no, 'status' => 1);
    //              if($this->pay_successs_status($sd)){
    //
    //				  echo "success";
    //				 }
    //
    //			 }
    //查询订单是否支付成功zzzzzz
    function query_order_pay($rt = array()) {
        $order_sn = $rt['order_sn'];
        $pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        if ($pay_status != 1) {
            return true;
            exit;
        }else{
			  return false;
            exit;
			
		}
    }
    //支付成功改变支付状态
    function pay_successs_status($rt = array()) {
        @set_time_limit(300); //最大运行时间
        $order_sn = $rt['order_sn'];
        $status = $rt['status'];
        $r_pay_time = $rt['pay_time'];
        $r_pay_no = $rt['pay_no'];
        $r_amount = $rt['amount'];
        if (empty($order_sn)) exit;
        //判断是否是在线报名支付
        $sql = "SELECT id,pay_status FROM `{$this->App->prefix() }cx_baoming_order` WHERE order_sn = '$order_sn' LIMIT 1";
        $isds = $this->App->findrow($sql);
        if (!empty($isds)) {
            if ($isds['pay_status'] == '1') {
                exit;
            }
            $this->baoming_pay_successs_tatus($order_sn);
            exit;
        }
        $record_num = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_pay_record` WHERE order_no='$order_sn' and pay_time='$r_pay_time' and pay_no ='$r_pay_no' and amount='$r_amount' and status=1");
        if ($record_num > 1) {
			//$sql = "update `{$this->App->prefix() }user_pay_record` set status=0   WHERE order_no='$order_sn' and pay_time='$r_pay_time' and pay_no ='$r_pay_no' and amount='$r_amount'";
//			 $this->App->query($sql);
            return false;
            exit;
        }
        //购买用户返积分
        //上三级返佣金
        $pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        $tt = "false";
        if ($pay_status != '1') {
            //检查
            $sql = "SELECT cid FROM `{$this->App->prefix() }user_money_change` WHERE order_sn='$order_sn'"; //资金
            $cid = $this->App->findvar($sql);
            if ($cid > 0) {
                return true;
                exit;
            } else {
                $sql = "SELECT cid FROM `{$this->App->prefix() }user_point_change` WHERE order_sn='$order_sn'"; //积分
                $cid = $this->App->findvar($sql);
                if ($cid > 0) {
                    return true;
                    exit;
                } else {
                    $tt = "true";
                }
            }
        } else { //已经支付了的
            return true;
            exit;
        }
        if ($tt == 'true' && $status == '1' && !empty($order_sn)) {
            $pu = $this->App->findrow("SELECT user_id,goods_amount,order_amount,order_sn,pay_status,order_id,pay_id,supplier_id FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if (empty($pu)) {
                return false;
                exit;
            }
            //$moeys = $pu['order_amount']*5/10000; //消费反润
            $order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
            $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
            $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
            $pay_id = isset($pu['pay_id']) ? $pu['pay_id'] : 0;
            $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
            $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
            //测试用付款方式
            //$pay_id = 4;
            //用户等级 $ni['user_rank']
            //	if($uid == 42){
            //费率单独设置
            $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
            $feilv = unserialize($feilv);
            $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
            //计算手续费
            $koulv = $feilv[$pay_fangshi];
            $shouxufei = $order_amount * ($koulv / 10000);
            $sy_money = $order_amount - $shouxufei;
            $shengyu = sprintf("%.2f",substr(sprintf("%.3f", $sy_money), 0, -1));
            //初始化上三级扣率 2016-10-07 9:23
            $koulv1 = '0';
            $koulv2 = '0';
            $koulv3 = '0';
            //	}else{
            //$koulv = $this->App->findvar("SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid=".$ni['user_rank']." LIMIT 1");
            //付款方式，决定添加的余额种类
            //$pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix()}payment` WHERE pay_id=".$pay_id." LIMIT 1");
            //计算手续费
            //$shouxufei = $order_amount*($koulv/10000);
            //	$shengyu = $order_amount - $shouxufei;
            //}
            //购买用户
            $nickname = $ni['nickname'];
            $dd = array();
            $dd['order_status'] = '2';
            $dd['pay_status'] = '1';
            $dd['pay_time'] = mktime();
            $dd['feilv'] = $koulv;
            $this->App->update('goods_order_info', $dd, 'order_sn', $order_sn);
            $result = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if ($result != 1) {
                return false;
                exit;
            }
            //
            $quid = $this->App->findvar("SELECT MAX(quid) FROM `{$this->App->prefix() }user` LIMIT 1");
            $this->App->update('user', array('quid' => ($quid + 1)), 'user_id', $uid);
			
			
			$sql = "UPDATE `{$this->App->prefix() }user` SET " . $pay_fangshi . " = " . $pay_fangshi . "+'$shengyu' WHERE user_id = " . $uid;
            $this->App->query($sql);
            $sql1 = "UPDATE `{$this->App->prefix() }user_moneys` SET " . $pay_fangshi . " = " . $pay_fangshi . "+'$shengyu' WHERE uid = " . $uid;
            $this->App->query($sql1);
				
			
			
			
			
            // $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1"; //用户配置信息
            //            $rts = $this->App->findrow($sql);
            //            $openfx_minmoney = empty($rts['openfx_minmoney']) ? 0 : intval($rts['openfx_minmoney']);
            $appid = $this->Session->read('User.appid');
            if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
            $appsecret = $this->Session->read('User.appsecret');
            if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
            //计算资金，便于下面返佣
            $sendrt_money = array();
            //发送店铺付款通知
            if ($pu['supplier_id'] > 0) {
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $uid . " LIMIT 1");
                $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname, 'money' => $order_amount, 'order_sn' => $order_sn), 'orderconfirm_toshop');
				//店铺收款店员通知
		$sql = "SELECT * FROM `{$this->App->prefix() }user_assistant` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.assistant_id  WHERE tb1.uid = ".$uid." and tb1.status = 1 and tb2.is_subscribe = 1";
        $rt = $this->App->find($sql);
        if (!empty($rt)) foreach ($rt as $row) {
			 $this->action('api', 'send', array('openid' => $row['wecha_id'],'money' => $order_amount, 'order_sn' => $order_sn), 'orderconfirm_toshop');
		}
            }
            $sql = "SELECT * FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid=" . $uid . " LIMIT 1";
            $fenxiao = $this->App->findrow($sql);
            $p1_uid = isset($fenxiao['p1_uid']) ? $fenxiao['p1_uid'] : 0;
            $p2_uid = isset($fenxiao['p2_uid']) ? $fenxiao['p2_uid'] : 0;
            $p3_uid = isset($fenxiao['p3_uid']) ? $fenxiao['p3_uid'] : 0;
            //一级返佣金
            if ($p1_uid > 0) {
				
				//第一次付款上级返佣金
				  $first_order = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }goods_order_info` WHERE  user_id='$uid' and pay_status=1 LIMIT 1");
				  
				  		    $f_yongjin = $this->App->findvar("SELECT f_yongjin FROM `{$this->App->prefix() }systemconfig` WHERE type='basic' LIMIT 1");
				  if($first_order == 1 && $f_yongjin>0){
					  					
					  $this->App->query("UPDATE `{$this->App->prefix() }user` SET `yongjin` = `yongjin`+$f_yongjin  WHERE user_id = '$p1_uid'");
			
					 $sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1' LIMIT 1";
              $rts = $this->App->findrow($sql);
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
					    $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $f_yongjin, 'changedesc' => '首次刷卡返佣金', 'time' => mktime(), 'uid' => $p1_uid, 'level' => '1'));
                        //发送推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1");
						  $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $rts['appid'], 'appsecret' => $rts['appsecret'],'money' => $f_yongjin), 'firstreturnmoney');
					  
					  }
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
	
	
	//微信支付更新订单
	 function pay_successs_status_wx($rt = array()) {
        @set_time_limit(300); //最大运行时间
        $order_sn = $rt['order_sn'];
        $status = $rt['status'];
        $r_pay_time = $rt['pay_time'];
        $r_pay_no = $rt['pay_no'];
        $r_amount = $rt['amount'];
        if (empty($order_sn)) exit;
        
        $record_num = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_pay_record` WHERE order_no='$order_sn' and pay_time='$r_pay_time' and pay_no ='$r_pay_no' and amount='$r_amount' and status=1");
        if ($record_num > 1) {
			$sql = "update `{$this->App->prefix() }user_pay_record` set status=0   WHERE order_no='$order_sn' and pay_time='$r_pay_time' and pay_no ='$r_pay_no' and amount='$r_amount'";
			 $this->App->query($sql);
            return false;
            exit;
        }
        //购买用户返积分
        //上三级返佣金
        $pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
      
        if ($pay_status == '1') {           //已经支付了的
            return true;
            exit;
        }
        if ($status == '1' && !empty($order_sn)) {
            $pu = $this->App->findrow("SELECT user_id,goods_amount,order_amount,order_sn,pay_status,order_id,pay_id,supplier_id FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if (empty($pu)) {
                return false;
                exit;
            }
            //$moeys = $pu['order_amount']*5/10000; //消费反润
            $order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
            $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
            $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
            $pay_id = isset($pu['pay_id']) ? $pu['pay_id'] : 0;
            $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
            $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
            //测试用付款方式
            //$pay_id = 4;
            //用户等级 $ni['user_rank']
            //	if($uid == 42){
            //费率单独设置
            $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
            $feilv = unserialize($feilv);
            $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
            //计算手续费
            $koulv = $feilv[$pay_fangshi];
            $shouxufei = $order_amount * ($koulv / 10000);
            $sy_money = $order_amount - $shouxufei;
            $shengyu = sprintf("%.2f",substr(sprintf("%.3f", $sy_money), 0, -1));
            //初始化上三级扣率 2016-10-07 9:23
            $koulv1 = '0';
            $koulv2 = '0';
            $koulv3 = '0';
            //	}else{
            //$koulv = $this->App->findvar("SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid=".$ni['user_rank']." LIMIT 1");
            //付款方式，决定添加的余额种类
            //$pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix()}payment` WHERE pay_id=".$pay_id." LIMIT 1");
            //计算手续费
            //$shouxufei = $order_amount*($koulv/10000);
            //	$shengyu = $order_amount - $shouxufei;
            //}
            //购买用户
            $nickname = $ni['nickname'];
            $dd = array();
            $dd['order_status'] = '2';
            $dd['pay_status'] = '1';
            $dd['pay_time'] = mktime();
            $dd['feilv'] = $koulv;
            $this->App->update('goods_order_info', $dd, 'order_sn', $order_sn);
            $result = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if ($result != 1) {
                return false;
                exit;
            }
          
			
		
				 $sql = "UPDATE `{$this->App->prefix() }user` SET " . $pay_fangshi . " = " . $pay_fangshi . "+'$shengyu' WHERE user_id = " . $uid;
            $this->App->query($sql);
			
            $sql1 = "UPDATE `{$this->App->prefix() }user_moneys` SET " . $pay_fangshi . " = " . $pay_fangshi . "+'$shengyu' WHERE uid = " . $uid;
            $this->App->query($sql1);
				
			
				
			
			//	 $this->action('wefu','auto_ajax_postmoney',array('order_sn'=>$order_sn));
		
            $appid = $this->Session->read('User.appid');
            if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
            $appsecret = $this->Session->read('User.appsecret');
            if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
            //计算资金，便于下面返佣
            $sendrt_money = array();
            //发送店铺付款通知
            if ($pu['supplier_id'] > 0) {
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $uid . " LIMIT 1");
                $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname, 'money' => $order_amount, 'order_sn' => $order_sn), 'orderconfirm_toshop');
				//店铺收款店员通知
		$sql = "SELECT * FROM `{$this->App->prefix() }user_assistant` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.assistant_id  WHERE tb1.uid = ".$uid." and tb1.status = 1 and tb2.is_subscribe = 1";
        $rt = $this->App->find($sql);
        if (!empty($rt)) foreach ($rt as $row) {
			 $this->action('api', 'send', array('openid' => $row['wecha_id'],'money' => $order_amount, 'order_sn' => $order_sn), 'orderconfirm_toshop');
		}
            }
            $sql = "SELECT * FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid=" . $uid . " LIMIT 1";
            $fenxiao = $this->App->findrow($sql);
            $p1_uid = isset($fenxiao['p1_uid']) ? $fenxiao['p1_uid'] : 0;
            $p2_uid = isset($fenxiao['p2_uid']) ? $fenxiao['p2_uid'] : 0;
            $p3_uid = isset($fenxiao['p3_uid']) ? $fenxiao['p3_uid'] : 0;
            //一级返佣金
            if ($p1_uid > 0) {
				
				//第一次付款上级返佣金
				  $first_order = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }goods_order_info` WHERE  user_id='$uid' and pay_status=1 LIMIT 1");
				  
				  		    $f_yongjin = $this->App->findvar("SELECT f_yongjin FROM `{$this->App->prefix() }systemconfig` WHERE type='basic' LIMIT 1");
				  if($first_order == 1 && $f_yongjin>0){
					  					
					  $this->App->query("UPDATE `{$this->App->prefix() }user` SET `yongjin` = `yongjin`+$f_yongjin  WHERE user_id = '$p1_uid'");
			
					 $sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1' LIMIT 1";
              $rts = $this->App->findrow($sql);
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
					    $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $f_yongjin, 'changedesc' => '首次刷卡返佣金', 'time' => mktime(), 'uid' => $p1_uid, 'level' => '1'));
                        //发送推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1");
						  $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $rts['appid'], 'appsecret' => $rts['appsecret'],'money' => $f_yongjin), 'firstreturnmoney');
					  
					  }
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
    //end function
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
    function _firtuids($uid = 0) {
        $ut = array();
        $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
        $uids = $this->App->findcol($sql);
        if (!empty($uids)) foreach ($uids as $uid) {
            $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
            if ($ur != '1') {
                $ut[] = $uid;
            } else {
                /*                     * ******************第二次************************ */
                $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                $uids = $this->App->findcol($sql);
                if (!empty($uids)) foreach ($uids as $uid) {
                    $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                    if ($ur != '1') {
                        $ut[] = $uid;
                    } else {
                        /*                                 * ******************第三次************************ */
                        $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                        $uids = $this->App->findcol($sql);
                        if (!empty($uids)) foreach ($uids as $uid) {
                            $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                            if ($ur != '1') {
                                $ut[] = $uid;
                            } else {
                                /*                                             * ******************第四次************************ */
                                $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                                $uids = $this->App->findcol($sql);
                                if (!empty($uids)) foreach ($uids as $uid) {
                                    $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                                    if ($ur != '1') {
                                        $ut[] = $uid;
                                    } else {
                                        /*                                                         * ******************第五次************************ */
                                        $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                                        $uids = $this->App->findcol($sql);
                                        if (!empty($uids)) foreach ($uids as $uid) {
                                            $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                                            if ($ur != '1') {
                                                $ut[] = $uid;
                                            } else {
                                                /*                                                                     * ******************第六次************************ */
                                                $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                                                $uids = $this->App->findcol($sql);
                                                if (!empty($uids)) foreach ($uids as $uid) {
                                                    $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                                                    if ($ur != '1') {
                                                        $ut[] = $uid;
                                                    } else {
                                                        /*                                                                                 * ******************第七次************************ */
                                                        $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                                                        $uids = $this->App->findcol($sql);
                                                        if (!empty($uids)) foreach ($uids as $uid) {
                                                            $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                                                            if ($ur != '1') {
                                                                $ut[] = $uid;
                                                            } else {
                                                                /*                                                                                             * ******************第八次************************ */
                                                                $sql = "SELECT uid FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid='$uid'";
                                                                $uids = $this->App->findcol($sql);
                                                                if (!empty($uids)) foreach ($uids as $uid) {
                                                                    $ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid'");
                                                                    if ($ur != '1') {
                                                                        $ut[] = $uid;
                                                                    } else {
                                                                        break;
                                                                    }
                                                                }
                                                                /*                                                                                             * ***************************************** */
                                                            }
                                                        }
                                                        /*                                                                                 * ***************************************** */
                                                    }
                                                }
                                                /*                                                                     * ***************************************** */
                                            }
                                        }
                                        /*                                                         * ***************************************** */
                                    }
                                }
                                /*                                             * ***************************************** */
                            }
                        }
                        /*                                 * ***************************************** */
                    }
                }
                /*                     * ***************************************** */
            }
        }
        return $ut;
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
            $sql = "SELECT id FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid='$uid' LIMIT 1";
            $id = $this->App->findvar($sql);
            if ($id > 0) {
                $this->App->update('user_tuijian_fx', $dd, 'id', $id);
            } else {
                $this->App->insert('user_tuijian_fx', $dd);
            }
            //
            $firtuids = $this->_firtuids($uid); //当前开通用户的最近一层分销用户
            $aup = array();
            if (!empty($firtuids)) foreach ($firtuids as $u) { //
                $dds = array();
                $dds['uid'] = $u;
                $dds['p1_uid'] = $uid;
                $dds['p2_uid'] = $dd['p1_uid'];
                $dds['p3_uid'] = $dd['p2_uid'];
                $aup[] = $dds;
                $firtuids2 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix() }user_tuijian_fx` WHERE p1_uid = '$u'");
                if (!empty($firtuids2)) foreach ($firtuids2 as $uu) { //
                    $dds = array();
                    $dds['uid'] = $uu;
                    $dds['p1_uid'] = $u;
                    $dds['p2_uid'] = $uid;
                    $dds['p3_uid'] = $dd['p1_uid'];
                    $aup[] = $dds;
                    $firtuids3 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix() }user_tuijian_fx` WHERE p1_uid = '$uu'");
                    if (!empty($firtuids3)) foreach ($firtuids3 as $uuu) { //
                        $dds = array();
                        $dds['uid'] = $uuu;
                        $dds['p1_uid'] = $uu;
                        $dds['p2_uid'] = $u;
                        $dds['p3_uid'] = $uid;
                        $aup[] = $dds;
                    } //end foreach
                    unset($firtuids3);
                } //end foreach
                unset($firtuids2);
            } //end foreach
            unset($firtuids);
            if (!empty($aup)) foreach ($aup as $up) {
                $this->App->update('user_tuijian_fx', $up, 'uid', $up['uid']);
            }
            unset($aup);
        } //end if
        
    }
    function update_user_tree($puid = 0, $ppuid = 0) {
        $three_arr = array();
        $sql = 'SELECT id,uid FROM `' . $this->App->prefix() . "user_tuijian` WHERE parent_uid = '$puid'";
        $rt = $this->App->find($sql);
        if (!empty($rt)) foreach ($rt as $row) {
            $id = $row['id'];
            $uid = $row['uid']; //
            //更新
            if ($id > 0) {
                $this->App->update('user_tuijian', array('daili_uid' => $ppuid), 'id', $id);
            }
            //判断当前是否是代理
            $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
            $rank = $this->App->findvar($sql);
            if ($rank == '1') { //普通会员
                $this->update_user_tree($uid, $ppuid);
            } else {
            }
        }
    }
    //支付成功改变支付状态(代购模式)
    function pay_successs_tatus($rt = array()) {
        $order_sn = $rt['order_sn'];
        $status = $rt['status'];
        //送佣金，找出推荐用户
        $pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,goods_amount,order_amount,order_sn FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE order_sn='$order_sn' LIMIT 1");
        $parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
        $daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
        $moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0;
        $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
        //检查
        $tt = "false";
        $sql = "SELECT cid FROM `{$this->App->prefix() }user_money_change` WHERE order_sn='$order_sn'"; //资金
        $cid = $this->App->findvar($sql);
        if ($cid > 0) {
            return false;
            exit;
        } else {
            $sql = "SELECT cid FROM `{$this->App->prefix() }user_point_change` WHERE order_sn='$order_sn'"; //积分
            $cid = $this->App->findvar($sql);
            if ($cid > 0) {
                return false;
                exit;
            } else {
                $tt = "true";
            }
        }
        if ($tt == 'true' && $status == '1' && !empty($order_sn)) {
            $nickname = $this->App->findvar("SELECT nickname FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
            $dd = array();
            $dd['order_status'] = 2;
            $dd['pay_status'] = 1;
            $dd['pay_time'] = mktime();
            $this->App->update('goods_order_info_daigou', $dd, 'order_sn', $order_sn);
            $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
            $rts = $this->App->findrow($sql);
            $appid = $this->Session->read('User.appid');
            if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
            $appsecret = $this->Session->read('User.appsecret');
            if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
            //购物上级以及购物者送积分
            $pointnum = $rts['pointnum'];
            if ($pointnum > 0 && !empty($moeys)) {
                if ($parent_uid > 0) { //存在上级，积分对半分
                    $points = ceil(intval($moeys * $pointnum) / 2);
                    $points = intval($points);
                } else {
                    $points = intval($moeys * $pointnum);
                }
                $thismonth = date('Y-m-d', mktime());
                //购买者送积分
                $sql = "UPDATE `{$this->App->prefix() }user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$uid'";
                $this->App->query($sql);
                $this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points, 'changedesc' => '消费返积分', 'time' => mktime(), 'uid' => $uid));
                //发送推荐用户通知
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
                $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => ''), 'payreturnpoints');
                //上级推荐用户的
                if ($parent_uid > 0) {
                    $sql = "UPDATE `{$this->App->prefix() }user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$parent_uid'";
                    $this->App->query($sql);
                    $this->App->insert('user_point_change', array('order_sn' => $order_sn, 'thismonth' => $thismonth, 'points' => $points, 'changedesc' => '推荐消费返积分', 'time' => mktime(), 'uid' => $parent_uid));
                    //发送推荐用户通知
                    $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$parent_uid' LIMIT 1");
                    $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => ''), 'payreturnpoints_parentuid');
                }
            }
            //检查当前用户是否是代理
            $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1"; //配置信息
            $rank = $this->App->findvar($sql);
            if ($rank == '10' && !empty($moeys)) { //如果是代理商，返佣给自己
                $sql = "SELECT types FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
                $types = $this->App->findvar($sql);
                $off = 0;
                if ($types == '1') { //全职
                    if ($rts['ticheng360'] < 101 && $rts['ticheng360'] > 0) {
                        $off = $rts['ticheng360'] / 100;
                    }
                } else {
                    if ($rts['ticheng180'] < 101 && $rts['ticheng180'] > 0) {
                        $off = $rts['ticheng180'] / 100;
                    }
                }
                $moeys = format_price($moeys * $off);
                $thismonth = date('Y-m-d', mktime());
                $thism = date('Y-m', mktime());
                $sql = "UPDATE `{$this->App->prefix() }user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$uid'";
                $this->App->query($sql);
                $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $uid));
                //发送推荐用户通知
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
                $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => $nickname), 'payreturnmoney');
            } elseif ($daili_uid > 0 && !empty($moeys)) { //推荐送佣金给代理
                $sql = "SELECT types FROM `{$this->App->prefix() }user` WHERE user_id='$daili_uid' LIMIT 1";
                $types = $this->App->findvar($sql);
                $off = 0;
                if ($types == '1') { //全职
                    if ($rts['ticheng360'] < 101 && $rts['ticheng360'] > 0) {
                        $off = $rts['ticheng360'] / 100;
                    }
                } else {
                    if ($rts['ticheng180'] < 101 && $rts['ticheng180'] > 0) {
                        $off = $rts['ticheng180'] / 100;
                    }
                }
                $moeys = format_price($moeys * $off);
                $thismonth = date('Y-m-d', mktime());
                $thism = date('Y-m', mktime());
                $sql = "UPDATE `{$this->App->prefix() }user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$daili_uid'";
                $this->App->query($sql);
                $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户购买返佣金', 'time' => mktime(), 'uid' => $daili_uid));
                //发送推荐用户通知
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$daili_uid' LIMIT 1");
                $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => $nickname), 'payreturnmoney');
            }
        }
    }
    //支付成功或者失败跳转的页面
    function paysuccessjump($t = '') {
        $url = str_replace('paywx/', '', ADMIN_URL);
        if ($t == '1') {
            $this->jump($url, 0, '您已经成功支付，感谢您的支持。');
            exit;
        } elseif ($t == '2') {
            $this->jump($url, 0, '支付发生意外错误，请稍后再试。');
            exit;
        }
        $this->jump($url);
        exit;
    }
    //获取用户的openid
    function get_openid_AND_pay_info() {
        $wecha_id = $this->Session->read('User.wecha_id');
        if (empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH . 'USER']['UKEY']) ? $_COOKIE[CFGH . 'USER']['UKEY'] : '';
        $wecha_id = $wecha_id;
        //
        $order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';

        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if(empty($rt)){
            $sql = "SELECT * FROM `{$this->App->prefix() }cx_baoming_order` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
            $rt = $this->App->findrow($sql);
        }
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
    //返回订单信息
    function get_order_pay_info($order_sn) {
        $sql = "SELECT order_sn,order_id,order_amount,pay_status,shipping_fee FROM `{$this->App->prefix() }goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
        $rt = $this->App->findrow($sql);
        $rt['order_amount'] = $rt['order_amount'] + $rt['shipping_fee'];
        if (empty($rt)) {
            $this->jump(str_replace('/yunpay', '', ADMIN_URL), 0, '非法支付提交！');
            exit;
        }
        if ($rt['pay_status'] == '1') {
            $this->jump(str_replace('/yunpay', '', ADMIN_URL) . 'user.php?act=orderlist');
            exit;
        }
        $rt['body'] = $GLOBALS['LANG']['site_name'] . '购物平台';
        $order_id = $rt['order_id'];
        $rt['gname'] = $this->App->findvar("SELECT goods_name FROM `{$this->App->prefix() }goods_order` WHERE order_id = '$order_id' LIMIT 1");
        return $rt;
    }
    //终端支付跳转
    function _alipayment($rt = array()) {
        $pay_id = $rt['pay_id'];
        $order_sn = $rt['order_sn']; //网站唯一订单编号
        $order_amount = $rt['order_amount'] + $rt['logistics_fee'];
        //  if ($pay_id == '3') { //银联支付
        //            $this->jump(ADMIN_URL . 'wxpay/yinlian.php?order_sn=' . $order_sn);
        //            exit;
        //        }

        //2018/03/15 
       // if ($pay_id == '2') { //微信支付
       //     echo (ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$order_sn);
       //     // var_export(ADMIN_URL .'wxpay/js_api_call.php?order_sn='.$order_sn);
       //     // exit;
       // }
        if ($pay_id == '8') { //支付宝支付
            $this->jump(ADMIN_URL . 'wxpay/weixin.php?order_sn=' . $order_sn);
            exit;
        }
        if ($pay_id == '6') { //云支付
            $this->jump(ADMIN_URL . 'yunpay/yunpay.php?order_sn=' . $order_sn);
            exit;
        }
        //余额支付
        if ($pay_id == '7') {
            //我的余额
            $uid = $this->Session->read('User.uid');
            if ($uid > 0) {
                $sql = "SELECT mymoney FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
                $mymoney = $this->App->findvar($sql);
            } else {
                $oid = $this->App->findvar("SELECT order_id FROM `{$this->App->prefix() }user` WHERE order_sn='$order_sn' LIMIT 1");
                $this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '余额不足，请选择其他支付方式！');
                exit;
            }
            if ($mymoney >= $order_amount) {
                $money = - $order_amount;
                $sql = "UPDATE `{$this->App->prefix() }user` SET `mymoney` = `mymoney`+$money, `mymoney` = `mymoney`+$order_amount  WHERE user_id = '$uid'";
                $this->App->query($sql);
                if ($this->action('shopping', 'pay_successs_status', array('order_sn' => $order_sn, 'status' => '1'))) {
                    $this->jump(ADMIN_URL . 'user.php?type=shoukuan', 0, '已成功支付');
                    exit;
                }
                //
                //               echo $this->action('shopping','pay_successs_status',array('order_sn'=>$order_sn,'status'=>'1'));
                //			   exit;
                // $sd = array();
                //                $sd = array('order_sn' => $order_sn, 'status' => 1);
                //                if ($this->pay_successs_status($sd)) {
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
                //
                //                    exit;
                //                } else {
                //                    $this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '意外错误！');
                //                    exit;
                //                }
                
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '余额不足，请选择其他支付方式！');
                exit;
            }
        }
        exit;
    }
    //确认订单
    function confirm_daigou2() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $order_sn = date('Y', mktime()) . mktime();
        if (isset($_POST) && !empty($_POST)) {
            //$totalprice = $_POST['totalprice'];
            //if($totalprice < 0){
            //$this->jump(ADMIN_URL,0,'非法提交');exit;
            //}
            $addresssall = $_POST['address'];
            $pay_id = $_POST['pay_id'];
            $pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix() }payment` WHERE pay_id='$pay_id' LIMIT 1");
            $shipping_id = $_POST['shipping_id'];
            $shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix() }shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
            $postscript = $_POST['postscript'];
            $goodslist = $this->Session->read('cart');
            if (empty($goodslist)) {
                $this->jump(ADMIN_URL, 0, '购物车为空');
                exit;
            }
            $totalprice = 0;
            $stotalprice = 0;
            foreach ($goodslist as $gid => $row) {
                if ($row['is_jifen_session'] == '1') {
                    $this->Session->write("cart.$gid", null);
                    $this->Session->write('useradd.$gid', null);
                    continue;
                }
                if (!($row['number'] > 0)) {
                    $row['number'] = 1;
                    $this->Session->write("cart.{$gid}.number", 1);
                }
                $totalprice+= $row['price'] * $row['number'];
                $stotalprice+= $row['pifa_price'] * $row['number'];
            }
            if (!($totalprice > 0)) {
                $this->jump(ADMIN_URL, 0, '非法 提交');
                exit;
            }
            //添加订单表
            $orderdata = array();
            $orderdata['pay_id'] = $pay_id;
            $orderdata['shipping_id'] = $shipping_id;
            $orderdata['pay_name'] = $pay_name;
            $orderdata['shipping_name'] = $shipping_name;
            $orderdata['order_sn'] = $order_sn;
            $orderdata['user_id'] = $uid;
            $pr = $this->App->findrow("SELECT parent_uid,daili_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid='$uid' LIMIT 1");
            $parent_uid = isset($pr['parent_uid']) ? $pr['parent_uid'] : 0;
            $daili_uid = isset($pr['daili_uid']) ? $pr['daili_uid'] : 0;
            $orderdata['parent_uid'] = $parent_uid > 0 ? $parent_uid : '0';
            $orderdata['daili_uid'] = $daili_uid > 0 ? $daili_uid : '0';
            $orderdata['postscript'] = $postscript;
            $orderdata['goods_amount'] = $stotalprice;
            $orderdata['order_amount'] = $totalprice;
            $orderdata['add_time'] = mktime();
            $this->App->insert('goods_order_info_daigou', $orderdata);
            $orderid = $this->App->iid();
            if ($orderid > 0) foreach ($goodslist as $row) {
                $gid = $row['goods_id'];
                //$consignees = $_POST['consignee'][$gid];
                //$numbers = $_POST['goods_number'][$gid];
                //$moblies = $_POST['moblie'][$gid];
                //$provinces = $_POST['province'][$gid];
                //$citys = $_POST['city'][$gid];
                //$districts = $_POST['district'][$gid];
                //$addresss = $_POST['address'][$gid];
                //if(empty($consignees)) continue;
                //添加订单商品表
                $ds = array();
                $ds['order_id'] = $orderid;
                $ds['goods_id'] = $gid;
                $ds['brand_id'] = $row['brand_id'];
                $ds['goods_name'] = $row['goods_name'];
                $ds['goods_thumb'] = $row['goods_thumb'];
                $ds['goods_bianhao'] = $row['goods_bianhao'];
                $ds['goods_unit'] = $row['goods_unit'];
                $ds['goods_sn'] = $row['goods_sn'];
                $ds['market_price'] = $row['pifa_price'];
                $ds['goods_price'] = $row['price'];
                $ds['goods_number'] = $row['number']; //单个商品的总数量
                if (!empty($row['spec'])) $ds['goods_attr'] = implode("、", $row['spec']);
                $this->App->insert('goods_order_daigou', $ds);
                $rec_id = $this->App->iid();
                //添加订单地址表
                if ($rec_id > 0) {
                    $useradd = $this->Session->read("useradd.{$gid}");
                    $l = 0;
                    if (!empty($useradd)) foreach ($useradd as $k => $addresss) {
                        $dd = array();
                        $dd['consignee'] = $addresss['consignee'];
                        $dd['goods_number'] = !($addresss['number'] > 0) ? 1 : $addresss['number'];
                        $dd['moblie'] = $addresss['moblie'];
                        //$dd['province'] = $provinces[$k];
                        //$dd['city'] = $citys[$k];
                        //$dd['district'] = $districts[$k];
                        $dd['address'] = !empty($addresssall[$gid][$l]) ? $addresssall[$gid][$l] : $addresss['address'];
                        $dd['rec_id'] = $rec_id;
                        $this->App->insert('goods_order_address', $dd);
                        ++$l;
                    }
                }
            }
        }
        $this->Session->write('cart', null);
        $this->Session->write('useradd', null);
        $this->jump(ADMIN_URL . 'mycart.php?type=pay&oid=' . $orderid);
        exit;
        exit;
    }
    //第三版(代购模式)
    function checkout2() {
        //$this->js('mycart.js');
        $this->title('确认订单 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL);
            exit;
        }
        $goodslist = $this->Session->read('cart');
        if (empty($goodslist)) {
            $this->jump(ADMIN_URL, 0, '购物车为空！');
            exit;
        }
        $useradd = $this->Session->read('useradd');
        //查找收货地址
        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix() }user_address` AS tb1";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";
        $sql.= " WHERE tb1.user_id='$uid' AND tb1.is_own='0' ORDER BY tb1.is_default DESC, tb1.address_id ASC LIMIT 1";
        $rt['userress'] = $this->App->findrow($sql);
        $rt['goodslist'] = array();
        $counts = 0;
        if (!empty($goodslist)) {
            foreach ($goodslist as $k => $row) {
                if ($row['is_jifen_session'] == '1') {
                    $this->Session->write("cart.$k", null);
                    $this->Session->write('useradd.$k', null);
                    continue;
                }
                if (empty($useradd[$k]) || !isset($useradd[$k])) { //当前地址为空的时候写入session
                    if (empty($rt['userress'])) {
                        $useradd[$k][1234567] = array('address' => '', 'number' => 1, 'consignee' => '', 'moblie' => '');
                    } else {
                        $us = $rt['userress']['provinces'] . $rt['userress']['citys'] . $rt['userress']['districts'] . $rt['userress']['address'];
                        $useradd[$k][1234567] = array('address' => $us, 'number' => 1, 'consignee' => $rt['userress']['consignee'], 'moblie' => $rt['userress']['mobile']);
                    }
                }
                $counts+= $row['number'];
                $this->Session->write("cart.{$k}.spec.number", null);
            }
            //写入地址
            $this->Session->write('useradd', $useradd);
            //计算地址数量
            /* foreach($useradd as $gid=>$item){
              if(!empty($item))foreach($item as $count){
              if(!isset($goodslist[$gid])){
              $this->Session->write("useradd.$gid",null);
              continue;
              }
              ++$counts;
              }
              } */
            //计算折扣
            $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
            $rts = $this->App->findrow($sql);
            $off = 1;
            $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
            $issubscribe = $this->App->findvar($sql);
            $guanzhuoff = $rts['guanzhuoff'];
            $address3off = $rts['address3off'];
            $address2off = $rts['address2off'];
            if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
                $off = ($guanzhuoff / 100);
            }
            if ($counts >= 2 && $address2off < 101 && $address2off > 0) {
                $off = ($address2off / 100);
            }
            if ($counts >= 3 && $address3off < 101 && $address3off > 0) {
                $off = ($address3off / 100) * $off;
            }
            //设置价格
            $useradd = $this->Session->read('useradd');
            foreach ($goodslist as $k => $row) {
                //$this->Session->write("cart.{$k}.number",count($useradd[$k])); //当前商品的总数量
                $price = format_price($row['pifa_price'] * $off);
                $this->Session->write("cart.{$k}.price", $price);
                $this->Session->write("cart.{$k}.zprice", $price * $row['number']);
            }
        }
        //支付方式
        $sql = "SELECT * FROM `{$this->App->prefix() }payment` WHERE enabled='1'";
        $rt['paymentlist'] = $this->App->find($sql);
        //配送方式
        $sql = "SELECT * FROM `{$this->App->prefix() }shipping`";
        $rt['shippinglist'] = $this->App->find($sql);
        $this->set('rt', $rt);
        if (!defined(NAVNAME)) define('NAVNAME', "确认订单");
        $this->template('mycart_checkout');
    }
    //
    function ajax_address_writesess($data = array()) {
        $kk = $data['kk'];
        $gid = $data['gid'];
        $consignee = $data['consignee'];
        $moblie = $data['moblie'];
        $address = $data['address'];
        $number = $data['number'];
        $ud = array('address' => $address, 'number' => $number, 'consignee' => $consignee, 'moblie' => $moblie);
        $this->Session->write("useradd.{$gid}.{$kk}", $ud);
        $n = $this->Session->read("cart.{$gid}.number");
        $this->Session->write("cart.{$gid}.number", (intval($n) + intval($number)));
    }
    //移除单个商品地址
    function ajax_remove_goods_address($data = array()) {
        $kk = trim($data['kk']);
        $gid = intval($data['gid']);
        $number = intval($data['number']);
        $d = $this->Session->read("useradd.{$gid}.{$kk}");
        $this->Session->write("useradd.{$gid}.{$kk}", null);
        $n = $this->Session->read("cart.{$gid}.number");
        $this->Session->write("cart.{$gid}.number", (intval($n) - intval($number)));
    }
    //改变地址商品数量
    function ajax_change_goods_number($data = array()) {
        $kk = $data['kk'];
        $gid = intval($data['gid']);
        $n = intval($data['n']); //当前地址的数量
        $ty = $data['ty'];
        $nums = $this->Session->read("cart.{$gid}.number");
        //echo 'gid:'.$gid.'kk:'.$kk.'nums:'.$nums.'ty:'.$ty.'n:'.$n;
        //exit;
        if ($ty == 'jian') {
            $this->Session->write("cart.{$gid}.number", (intval($nums) - 1));
            $this->Session->write("useradd.{$gid}.{$kk}.number", $n);
        } else {
            $this->Session->write("cart.{$gid}.number", (intval($nums) + 1));
            $this->Session->write("useradd.{$gid}.{$kk}.number", $n);
        }
    }
    //计算价格
    function ajax_jisuan_price() {
        //返回数据
        /*
          1、error:记录错误参数
          2、totalprice：总价格
          3、单个产品的数据：1、price:惊喜价,2、zprice:小计3、gid:产品ID
        */
        $err = 0;
        $result = array('error' => $err, 'totalprice' => '0.00', 'goods' => '', 'message' => '');
        $json = Import::json();
        //die($json->encode($result));
        $goodslist = $this->Session->read('cart');
        $useradd = $this->Session->read('useradd');
        //计算地址数量
        /* $counts = 0;
          if(!empty($useradd)) foreach($useradd as $gid=>$item){
          if(!empty($item))foreach($item as $count){
          if(!isset($goodslist[$gid])){
          $this->Session->write("useradd.$gid",null);
          continue;
          }
          ++$counts;
          }
          } */
        $counts = 0;
        if (!empty($goodslist)) foreach ($goodslist as $k => $row) {
            $counts+= $row['number'];
        }
        //计算折扣
        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
        $rts = $this->App->findrow($sql);
        $off = 1;
        $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $issubscribe = $this->App->findvar($sql);
        $guanzhuoff = $rts['guanzhuoff'];
        $address3off = $rts['address3off'];
        $address2off = $rts['address2off'];
        if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) {
            $off = ($guanzhuoff / 100);
        }
        if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
            $off = ($address2off / 100);
        }
        if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
            $off = ($address3off / 100) * $off;
        }
        //设置价格
        $useradd = $this->Session->read('useradd');
        $totalprice = 0;
        $grt = array();
        if (!empty($goodslist)) foreach ($goodslist as $k => $row) {
            $price = format_price($row['pifa_price'] * $off);
            $this->Session->write("cart.{$k}.price", $price);
            $zprice = $price * $row['number'];
            $this->Session->write("cart.{$k}.zprice", $zprice); //单个产品的总价
            $totalprice+= $zprice;
            $grt[] = $price . ',' . $zprice . ',' . $row['goods_id'];
        }
        if (empty($grt)) {
            $result['error'] = 1;
            $result['message'] = "非法错误";
            die($json->encode($result));
        }
        $result = array('error' => 0, 'totalprice' => $totalprice, 'goods' => implode('|', $grt), 'message' => '');
        die($json->encode($result));
    }
    function ajax_change_carval($data = array()) {
        $kk = $data['kk'];
        $gid = $data['gid'];
        $ty = explode('[', $data['type']);
        $type = $ty[0];
        $val = $data['val'];
        switch ($type) {
            case 'consignee':
                $this->Session->write("useradd.{$gid}.{$kk}.consignee", $val);
            break;
            case 'moblie':
                $this->Session->write("useradd.{$gid}.{$kk}.moblie", $val);
            break;
            case 'address':
                $this->Session->write("useradd.{$gid}.{$kk}.address", $val);
            break;
        }
    }
    function isWeixinBrowser() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($agent, "icroMessenger")) {
            return false;
        }
        return true;
    }
    /*     * *************************************** */
    function index() {
        $this->js('mycart.js');
        $this->title('我的购物车 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL);
            exit;
        }
        $hear[] = '<a href="' . ADMIN_URL . '">首页</a>';
        $hear[] = '<a href="' . ADMIN_URL . 'mycart.php">我的购物车</a>';
        $rt['hear'] = implode('&nbsp;>&nbsp;', $hear);
        //用户等级折扣
        $rt['discount'] = 100;
        $rank = $this->Session->read('User.rank');
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix() }user_level` WHERE lid='$rank' LIMIT 1";
            $rt['discount'] = $this->App->findvar($sql);
        }
        $active = $this->Session->read('User.active');
        //购物车商品
        $goodslist = $this->Session->read('cart');
        $rt['goodslist'] = array();
        if (!empty($goodslist)) {
            foreach ($goodslist as $k => $row) {
                $rt['goodslist'][$k] = $row;
                //$rt['goodslist'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
                $rt['goodslist'][$k]['goods_thumb'] = SITE_URL . $row['goods_thumb'];
                $rt['goodslist'][$k]['goods_img'] = SITE_URL . $row['goods_img'];
                $rt['goodslist'][$k]['original_img'] = SITE_URL . $row['original_img'];
                //求出实际价格
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    $comd[] = $row['qianggou_price'];
                    //同一折扣价格
                    if ($rt['discount'] > 0) {
                        $comd[] = ($rt['discount'] / 100) * $row['market_price'];
                    }
                    if ($row['shop_price'] > 0) { //普通会员价格
                        $comd[] = $row['shop_price']; //普通会员价格
                        
                    }
                } else {
                    $comd[] = $row['market_price'];
                }
                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) { //促销价格
                    $comd[] = $row['promote_price'];
                }
                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }
                $onetotal = min($comd);
                if (intval($onetotal) <= 0) $onetotal = $row['market_price'];
                $total+= ($row['number'] * $onetotal); //总价格
                
            }
            unset($goodslist);
        }
        //赠品类型
        /* 		$fn = SYS_PATH.'data/goods_spend_gift.php';
          $spendgift = array();
          if(file_exists($fn) && is_file($fn)){
          include_once($fn);
          }
          $rt['gift_typesd'] = $spendgift;
          unset($spendgift);
        
          //商品赠品模块
          $minspend = array();
          if(!empty($rt['gift_typesd'])){
          foreach($rt['gift_typesd'] as $k=>$row){
          ++$k;
          $minspend[$k] = $row['minspend'];
          }
          arsort($minspend);
          }
          $rt['gift_goods'] = array();
          $type = 0;
          if(count($minspend)>0){
          $count = count($minspend);
          foreach($minspend as $t=>$val){  //已最高消费赠品为准
          if($total>=$val){
          $type = $t; //赠品等级
          break;
          }
          }
          unset($minspend);
          //赠品
          $rt['gift_goods_ids'] = array();
          if($type>0){
          $sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
          $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
          $sql .=" WHERE tb2.is_alone_sale='0' AND tb2.is_on_sale='1' AND tb2.is_check='1' AND tb2.is_delete='0' AND tb1.type='$type'";
          $gift_goods = $this->App->find($sql);
          if(!empty($gift_goods)){
          foreach($gift_goods as $k=>$row){
          $rt['gift_goods'][$k] = $row;
          //$rt['gift_goods'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
          $rt['gift_goods_ids'][] = $row['goods_id']; //记录赠品的id
          }
          unset($gift_goods);
          }
          }
        
          } */
        //换购商品
        /* $sql = "SELECT goods_id,goods_name,market_price,shop_price,promote_price,goods_thumb,goods_img,is_jifen,need_jifen FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1' AND is_check='1' AND is_alone_sale='1' AND is_jifen='1' ORDER BY sort_order ASC, goods_id DESC LIMIT 5";
          $rt['jifengoods'] = $this->App->find($sql);
          if(!empty($jifengoods)){
          foreach($jifengoods as $k=>$row){
          $rt['jifengoods'][$k] = $row;
          $rt['jifengoods'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
          }
          unset($jifengoods);
          } */
        if (!defined(NAVNAME)) define('NAVNAME', "购物车");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/mycart_list');
    }
    //订单确认
    function checkout() {
        $this->action('common', 'checkjump');
        $this->title('确认订单 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $cartlist = $goodslist = $this->Session->read('cart');
        //zzzzzzzz店铺id
        $dianpus = array();
        $dianpus = $this->get_dianpus($goodslist);
        $this->set('dianpus', array_unique($dianpus));
        if (empty($goodslist)) {
            //$this->jump(ADMIN_URL,0,'购物车为空，请先加入购物车！');exit;
            if (!defined(NAVNAME)) define('NAVNAME', "去购物吧");
            $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
            $this->template($mb . '/mycart_checkout_empty');
            exit;
        }
        /*         * 判断购物车的商品是否有赠品 */
        foreach ($goodslist as $_k => $_v) {
            if ($_v['is_prize'] == '1') {
                //判断当前用是否会员
                $uid = $this->Session->read('User.uid');
                if (!$uid) {
                    $this->jump(ADMIN_URL, 0, '您暂时无法购买此产品');
                    exit;
                }
                //s扫描别人二维码进来的
                /*   if ($uid > 0) {
                  $sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
                  $tjr = $this->App->findrow($sql);
                  if (!$tjr) {
                  $this->jump(ADMIN_URL, 0, '您暂时无法购买此产品');
                  exit;
                  }
                  } */
                //判断该商品是否已经被当前用户购买
                //查询所有奖品区的商品
                /*   $sql = "SELECT goods_id FROM `{$this->App->prefix()}goods` where is_prize=1";
                $goodsall = $this->App->find($sql);
                foreach ($goodsall as $_k => $_v) {
                    //判断该商品是否已经被当前用户购买
                    $sql = "SELECT COUNT(*) FROM `{$this->App->prefix()}goods_order_info`  as goi left join  `{$this->App->prefix()}goods_order` as go on goi.order_id=go.order_id   WHERE user_id='$uid'  and goi.order_status!=1  and go.goods_id='$_v[goods_id]'";
                    $paycount = $this->App->findvar($sql);
                    if ($paycount > 0) {
                        // $this->jump(ADMIN_URL, 0, '您已经领取过奖品了');  exit;
                        $has = 1; //您已经领取过奖品了
                        break;
                    }
                }*/
                /* $sql = "SELECT COUNT(*) FROM `{$this->App->prefix()}goods_order_info`  as goi left join  `{$this->App->prefix()}goods_order` as go on goi.order_id=go.order_id   WHERE user_id='$uid' AND pay_status='1' and go.goods_id='$_v[goods_id]'";
                  $paycount = $this->App->findvar($sql);
                  if ($paycount > 0) {
                  $this->jump(ADMIN_URL, 0, '您已经领取过奖品了');
                  exit;
                  } */
            }
        }
        /* $rt['user_ress'] = array();
          if(!empty($uid)){
          $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}user_address` AS tb1";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
          $sql .=" WHERE tb1.user_id='$uid' ORDER BY tb1.type DESC, tb1.address_id ASC LIMIT 1";
          $rt['user_ress'] = $this->App->findrow($sql);
          } */
        $rt['province'] = $this->action('user', 'get_regions', 1); //获取省列表
        $sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix() }user_address` AS ua";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg ON rg.region_id = ua.province";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg1 ON rg1.region_id = ua.city";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id";
        $rt['userress'] = $this->App->find($sql);
        //print_r($rt);
        //支付方式
        $paywhere = '';
        if ($this->isWeixinBrowser()) {
            $paywhere = ' and pay_id in (4,7)';
        }
        $sql = "SELECT * FROM `{$this->App->prefix() }payment` WHERE enabled='1' " . $paywhere;
        $rt['paymentlist'] = $this->App->find($sql);
        //配送方式
        $sql = "SELECT * FROM `{$this->App->prefix() }shipping`";
        $rt['shippinglist'] = $this->App->find($sql);
        $sql = "SELECT * FROM `{$this->App->prefix() }supplier_shipping`";
        $rt['shippinglist_s'] = $this->App->find($sql);
        //用户等级折扣
        $rt['discount'] = 100;
        $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix() }user_level` WHERE lid='$rank' LIMIT 1";
            $rt['discount'] = $this->App->findvar($sql);
        }
        //计算折扣
        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
        $rts = $this->App->findrow($sql);
        $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $issubscribe = $this->App->findvar($sql);
        $guanzhuoff = $rts['guanzhuoff'];
        $address3off = $rts['address3off'];
        $address2off = $rts['address2off'];
        $off = 1;
        if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) { //关注折扣
            $off = ($guanzhuoff / 100);
        }
        $counts = 0;
        foreach ($cartlist as $k => $row) {
            $counts+= $row['number'];
        }
        if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
            $off = ($address2off / 100) * $off; //相对关注再折扣
            
        }
        if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
            $off = ($address3off / 100) * $off; //相对关注再折扣
            
        }
        foreach ($goodslist as $k => $row) {
            $comd = array();
            $comd[] = format_price($row['pifa_price'] * $off);
            if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                $comd[] = $row['promote_price'];
            }
            if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                $comd[] = $row['qianggou_price'];
            }
            //zzzzzzzzzzzzz获取店铺
            if ($row['supplier_id']) {
                $sql = "SELECT site_name FROM `{$this->App->prefix() }supplier_systemconfig` WHERE supplier_id=" . $row['supplier_id'] . " LIMIT 1";
                //echo $sql;
                $info = $this->App->findrow($sql);
                $rt['goodslist'][$k]['dianpu'] = !empty($info['site_name']) ? $info['site_name'] : "商家店铺名称";
            } else {
                $rt['goodslist'][$k]['dianpu'] = "网店自营";
            }
            $price = min($comd);
            $this->Session->write("cart.{$k}.price", $price);
        }
        //我的余额
        $sql = "SELECT mymoney FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $rt['mymoney'] = $this->App->findvar($sql);
        if (empty($rt['mymoney'])) $rt['mymoney'] = '0.00';
        /* //我的积分
          $sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
          $rt['mypoints'] = $this->App->findvar($sql);
        
          $active = $this->Session->read('User.active');
          //购物车商品
          if(!empty($rt['goodslist'])){
          foreach($rt['goodslist'] as $k=>$row){
          //求出实际价格
          $comd = array();
          $comd[] =  $row['pifa_price'];
          $comd[] =  $row['shop_price'];
          if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()){ //促销价格
          $comd[] =  $row['promote_price'];
          }
          if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()){ //抢购价格
          $comd[] =  $row['qianggou_price'];
          }
        
          $onetotal = min($comd);
          $total +=($row['number']*$onetotal); //总价格
        
          }
          }
        
          //赠品类型
          $fn = SYS_PATH.'data/goods_spend_gift.php';
          $spendgift = array();
          if(file_exists($fn) && is_file($fn)){
          include_once($fn);
          }
          $rt['gift_typesd'] = $spendgift;
          unset($spendgift);
        
          //商品赠品模块
          $minspend = array();
          if(!empty($rt['gift_typesd'])){
          foreach($rt['gift_typesd'] as $k=>$row){
          ++$k;
          $minspend[$k] = $row['minspend'];
          }
          arsort($minspend);
          }
          $rt['gift_goods'] = array();
          $type = 0;
          if(count($minspend)>0){
          $count = count($minspend);
          foreach($minspend as $t=>$val){  //已最高消费赠品为准
          if($total>=$val){
          $type = $t; //赠品等级
          break;
          }
          }
          unset($minspend);
          //赠品
          $rt['gift_goods_ids'] = array();
          if($type>0){
          $sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
          $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
          $sql .=" WHERE tb2.is_alone_sale='0' AND tb2.is_check='1' AND tb2.is_on_sale='1' AND tb2.is_delete='0' AND tb1.type='$type'";
          $gift_goods = $this->App->find($sql);
          if(!empty($gift_goods)){
          foreach($gift_goods as $k=>$row){
          $rt['gift_goods_ids'][] = $row['goods_id']; //记录赠品的id
          }
          unset($gift_goods);
          }
          }
        
          } */
        if (!defined(NAVNAME)) define('NAVNAME', "确认订单");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/mycart_checkout');
    }
    //收银系统    鑫鑫
    function shoukuan() {
        $uid = $this->Session->read('User.uid');
		
			//获取openid
		
		// $sql = "SELECT open_id FROM `{$this->App->prefix() }user_openid` WHERE uid='$uid'";
//        $oid = $this->App->findvar($sql);
//if(!empty($oid)){
//        $openid = $oid;
//}else{
//	 $openid = $this->get_openid($uid);
//	}
	
		
				//获取openid结束
	
        $sql = "SELECT * FROM `{$this->App->prefix() }user_card` WHERE uid='$uid'";
        $card = $this->App->find($sql);
        $sql1 = "SELECT * FROM `{$this->App->prefix() }user_card_h5` WHERE uid='$uid'";
        $card_h5 = $this->App->find($sql1);
		
		 $sql2 = "SELECT * FROM `{$this->App->prefix() }user_card_h5_hq` WHERE uid='$uid'";
        $card_h5_hq = $this->App->find($sql2);
		
		
		 $sql = "SELECT * FROM `{$this->App->prefix() }user_card_xj_api` WHERE uid='$uid'";
        $card_xj = $this->App->find($sql);
		
			 //$sql = "SELECT * FROM `{$this->App->prefix() }user_card_api` WHERE uid='$uid'";
//        $card_api = $this->App->find($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid='$uid'";
        $card_api = $this->App->find($sql);
			
        $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '银联支付' and enabled=1 LIMIT 1";
        $rr['pay_id_yl'] = $this->App->findvar($sql);
        $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_code = 'yinlian_h5' and enabled=1 LIMIT 1";
        $rr['pay_id_yl_h5'] = $this->App->findvar($sql);
        $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '微信支付' and enabled=1 LIMIT 1";
        $rr['pay_id_wx'] = $this->App->findvar($sql);
		
		 $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '商户扫码微信支付' and enabled=1 LIMIT 1";
        $rr['pay_id_wxs'] = $this->App->findvar($sql);
		
        $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '支付宝支付' and enabled=1 LIMIT 1";
        $rr['pay_id_zfb'] = $this->App->findvar($sql);
        $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '海外支付' and enabled=1 LIMIT 1";
        $rr['pay_id_hw'] = $this->App->findvar($sql);
        $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '京东支付' and enabled=1 LIMIT 1";
        $rr['pay_id_jd'] = $this->App->findvar($sql);
        $sql = "SELECT status FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1";
        $rt = $this->App->findvar($sql);
        if (!$rt) {
            $this->jump(ADMIN_URL . 'user.php?act=renzheng');
            exit;
        }
		
	
	
			
			$this->set('openid', $openid);	
        $this->set('uid', $uid);
        $this->set('rr', $rr);
        $this->set('card', $card);
        $this->set('card_h5', $card_h5);
		 $this->set('card_h5_hq', $card_h5_hq);
		 $this->set('card_xj', $card_xj);
		 $this->set('card_api', $card_api);
        $this->title('收银 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/shoukuan');
    }
    //收款二维码
    function shoukuan_code() {
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT id FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1";
        $bank = $this->App->findvar($sql);
        $sql = "SELECT status,id FROM `{$this->App->prefix() }user_shop` WHERE uid = " . $uid . " LIMIT 1";
        $shop = $this->App->findrow($sql);
        if ($bank > 0) {
            if ($shop['id'] > 0) {
                if ($shop['status'] != 1) {
                    $this->jump(ADMIN_URL . 'user.php?act=renzheng');
                }
            } else {
                $this->jump(ADMIN_URL . 'user.php?act=sj_renzheng');
                exit;
            }
        } else {
            $this->jump(ADMIN_URL . 'user.php?act=renzheng');
            exit;
        }
        $url = ADMIN_URL . 'mycart.php?type=sj_shoukuan&uid=' . $uid;
        $this->set('status', $status);
        $this->set('uid', $uid);
        $this->set('url', $url);
        $this->title('商家收银二维码 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/shoukuan_code');
    }
    //商家收银系统    鑫鑫
    function sj_shoukuan() {
        if ((!isset($_GET['uid'])) or (!$_GET['uid'])) {
            $this->jump(ADMIN_URL . 'user.php?act=sj_renzheng');
            exit;
        }
        $uid = isset($_GET['uid']) ? $_GET['uid'] : 0;
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_weixin = strpos($agent, 'micromessenger') ? true : false;
        if ($is_weixin) {
            $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '商户扫码微信支付' and enabled=1 LIMIT 1";
            $rr['pay_id'] = $this->App->findvar($sql);
        } else {
            $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '支付宝支付' and enabled=1 LIMIT 1";
            $rr['pay_id'] = $this->App->findvar($sql);
        }
        $sql = "SELECT s_name,id FROM `{$this->App->prefix() }user_shop` WHERE uid='$uid'";
        $shop = $this->App->findrow($sql);
        $this->set('uid', $uid);
        $this->set('rr', $rr);
        $this->set('shop', $shop);
        $this->title('商家收银 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/sj_shoukuan');
    }
	
	
	
	
	
	  //收款二维码(简洁版)
    function shoukuan_code_simple() {
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT id FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1";
        $bank = $this->App->findvar($sql);

        $sql = "SELECT status FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1";
        $status = $this->App->findvar($sql);
        if (!$bank ) {
            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple');
            exit;
        }
		
		if($status != 1){
			
			$this->jump(ADMIN_URL . 'user.php?act=renzheng_simple');
            exit;
		}
        $url = ADMIN_URL . 'mycart.php?type=sj_shoukuan_simple&uid=' . $uid;
        $this->set('status', $status);
        $this->set('uid', $uid);
        $this->set('url', $url);
        $this->title('商家收银二维码 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/shoukuan_code_simple');
    }
    //商家收银系统    鑫鑫
    function sj_shoukuan_simple() {
        if ((!isset($_GET['uid'])) or (!$_GET['uid'])) {
            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple');
            exit;
        }
        $uid = isset($_GET['uid']) ? $_GET['uid'] : 0;
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_weixin = strpos($agent, 'micromessenger') ? true : false;
        if ($is_weixin) {
            $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '商户扫码微信支付' and enabled=1 LIMIT 1";
            $rr['pay_id'] = $this->App->findvar($sql);
			
			
			
			if($uid == 42){
				$rr['pay_id'] = 15;
			}
		
			if($rr['pay_id'] > 0){
				
		if(!isset($_GET['code'])){
				$this->get_user_codes();//授权跳转
			}
			
			$code = isset($_GET['code']) ? $_GET['code'] : '';
	
			if(!empty($code)){
				
                            $access_token = $this->get_access_token();
							
							//$rt = $this->get_appid_appsecret();
							
							$appid = 'wxa1d2f0163a747532';//$rt['appid'];
		$appsecret = 'dd27e75cfff18e03fe777c6c4bb7f48a';//$rt['appsecret'];
				
                            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
                            $con = $this->curlGet($url);
                            if(!empty($con)){
				$json=json_decode($con);
				if(empty($access_token)) $access_token = $json->access_token;
				
				$openid = $json->openid;
				
				$rr['openid'] = $openid;

							}
			}
			}
			
			
	
		
		
		
        } else {
            $sql = "SELECT pay_id FROM `{$this->App->prefix() }payment` WHERE pay_name = '支付宝支付' and enabled=1 LIMIT 1";
            $rr['pay_id'] = $this->App->findvar($sql);
        }
        $sql = "SELECT s_name,id FROM `{$this->App->prefix() }user_shop` WHERE uid='$uid' and status=1";
        $shop_info = $this->App->findrow($sql);

        $sql = "SELECT shop_name FROM `{$this->App->prefix() }user_bank` WHERE uid='$uid' and status=1";
        $shop = $this->App->findvar($sql);
		
		if(empty($shop)){
			$shop = $shop_info['s_name'];
			}

        $this->set('uid', $uid);
        $this->set('rr', $rr);
        $this->set('shop', $shop);
        $this->title('商家收银 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/sj_shoukuan_simple');
    }
	
	
	
    /*
      确认订单提交页面
    */
    function confirm() {
        if (isset($_POST) && !empty($_POST)) {
            $uid = isset($_POST['uid']) ? $_POST['uid'] : $this->Session->read('User.iuid');
        }
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }

		
        $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $user_rank = $this->App->findvar($sql);
		
		// $sql = "SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
//        $wecha_id = $this->App->findvar($sql);
		
        if (isset($_POST) && !empty($_POST)) {
            $consignee = isset($_POST['consignee']) ? $_POST['consignee'] : '';
            $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $bank_no = isset($_POST['bank_no']) ? $_POST['bank_no'] : '';
			$openid = isset($_POST['openid']) ? $_POST['openid'] : $wecha_id;
            //商铺号
            $supplier_id = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : 0;
			
		
            $pay_id = isset($_POST['pay_id']) ? $_POST['pay_id'] : 0;

			
			if($supplier_id && $pay_id == 15){
				if(empty($_POST['openid'])){
					$this->jump(ADMIN_URL . 'mycart.php?type=sj_shoukuan_simple&uid='.$uid, 0, '您的订单没有提交成功，请重新支付！');
                    exit;
				}
			}
            //if($pay_id == 3){
            //				$this->yinlian();
            //				exit;
            //				}
            //	if($pay_id == 4){
            //				$this->wx(339);
            //				}
            //
            //				exit;
            if (!$pay_id) exit;
            $pay_info = $this->App->findrow("SELECT pay_name,pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
            $pay_name = $pay_info['pay_name'];
            $pay_code = $pay_info['pay_code'];
            $sql = "SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid='$user_rank' LIMIT 1";
            $user_level = $this->App->findrow($sql);
            //费率单独设置
            $feilv = unserialize($user_level['feilv']);
            //	$rt['yinlian'] = $feilv['yinlian'];
            //				$rt['yinlian_h5'] = $feilv['yinlian_h5'];
            //				$rt['weixin'] = $feilv['weixin'];
            //				$rt['zhifubao'] = $feilv['zhifubao'];
            //				$rt['haiwai'] = $feilv['haiwai'];
            //				$rt['jingdong'] = $feilv['jingdong'];
            $orderdata['feilv'] = $feilv[$pay_code];
			if($pay_id == 19 || $pay_id == 21 ||$pay_id == 3){
				$orderdata['sxf_api'] = $user_level['sxf_api'];
				}
				if($pay_id == 23){
					$orderdata['sxf_api'] = 0.2;
					}
            //添加信息到数据表
            $total = !empty($_POST['money'])?$_POST['money']:$_POST['amount'];
            $orderdata['order_sn'] = $_POST['order_sn'] . $uid;
            $orderdata['user_id'] = $uid ? $uid : 0;
            $daili_uid = $this->return_daili_uid($uid); //一级
            $orderdata['parent_uid'] = $daili_uid;
            //$orderdata['consignee'] = $user_ress['consignee'] ? $user_ress['consignee'] : "";
            //			$orderdata['province'] = $user_ress['province'] ? $user_ress['province'] : 0;
            //			$orderdata['city'] = $user_ress['city'] ? $user_ress['city'] : 0;
            //			$orderdata['district'] = $user_ress['district'] ? $user_ress['district'] : 0;
            $orderdata['consignee'] = $consignee ? $consignee : "";
            $orderdata['mobile'] = $mobile ? $mobile : "";
            $orderdata['address'] = $address ? $address : "";
            $orderdata['bank_no'] = $bank_no ? $bank_no : "";
            $orderdata['supplier_id'] = $supplier_id ? $supplier_id : "";
            $orderdata['pay_id'] = $pay_id ? $pay_id : 0;
            $orderdata['pay_name'] = $pay_name ? $pay_name : "";
            $orderdata['zifuchuan'] = $this->random_string(16, $max = FALSE);
            //	$orderdata['goods_amount']  = format_price($mprices);
            $orderdata['order_amount'] = $total; //优惠后的价格,也就是最终支付价格
            //$orderdata['offprice']  = $moneyinfo['offmoney'];
            $orderdata['add_time'] = mktime();
			$orderdata['openid'] = $openid ? $openid : "";
            $od = $this->App->findvar("SELECT order_id FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn=" . $orderdata['order_sn'] . " LIMIT 1");
            if ($od) {
                $orderdata['order_sn'] = "QZ" . date('Ymd') . time() . $uid;
            }

            if ($this->App->insert('goods_order_info', $orderdata)) { //订单成功后
                $iid = $this->App->iid();
                //	$this->_return_money($orderdata['order_sn']);
                //if($pay_id == 3 || $pay_id == 4){
                //				$this->jump(ADMIN_URL.'mycart.php?type=ylcheck&order_sn='.$orderdata['order_sn'].'&pay_id='.$pay_id); exit;
                //				}
                if ($pay_name == "银联支付H5") {
                    $this->yinlian($iid, $pay_id);
                    exit;
                }
               // if ($pay_id == 3) {
//              
//                    $this->kuaijie($iid, $pay_id);
//                    exit;
//                
//                    
//                }

                 if ($pay_id == 3) {
              
			 
				   $this->yisheng_kuaijie($iid, $pay_id);
                    exit;
				 
                    
                }
				
				 if ($pay_id == 19) {
              
                    $this->kuaijie_api($iid, $pay_id);
                    exit;
                
                    
                }
				
				 if ($pay_id == 21) {
              
                    $this->h5pay($iid, $pay_id);
                    exit;
                
                    
                }
				
				
				 if ($pay_id == 23) {
              
                    $this->h5pay_jiaofei($iid, $pay_id);
                    exit;
                
                    
                }
				
				
				 if ($pay_id == 1) {
              
                    $this->xingjie_pay($iid, $pay_id);//江苏星洁支付
                    exit;
                
                    
                }
				
				if($pay_id == 2){
					$this->_alipayment($orderdata);
                    exit;
				}
					
                if ($pay_name == "微信支付") {
                    $this->wx($iid, $pay_id);
                    exit;
                }
                if ($pay_name == "商户扫码微信支付") {
                    $this->wxs($iid, $pay_id);
                    exit;
                }
                if ($pay_name == "支付宝支付") {
                    $this->zfb($iid, $pay_id);
                    exit;
                }
				 if ($pay_name == "京东支付") {
                    $this->jd($iid, $pay_id);
                    exit;
                }
                if ($pay_name == "海外支付") {
                    $this->hw($iid, $pay_id);
                    exit;
                }
				
                //发送通知
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");
                if (!empty($pwecha_id)) {
                    $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => ''), 'orderconfirm');
                }
                //通知商家
                $wid = $this->App->findvar("SELECT wid FROM `{$this->App->prefix() }userconfig` WHERE type='basic' LIMIT 1");
                if ($wid > 0) {
                    $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$wid' AND is_subscribe='1' LIMIT 1");
                    if (!empty($pwecha_id)) $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => ''), 'orderconfirm_toshop');
                }
                //$this->jump(ADMIN_URL.'mycart.php?type=pay2&oid='.$iid);exit;
                $rt['order_sn'] = $orderdata['order_sn'];
                $rt['pay_name'] = $pay_name;
                $rt['total'] = format_price($orderdata['order_amount']);
                $rts['pay_id'] = $pay_id;
                $rts['order_sn'] = $rt['order_sn'];
                $rts['order_amount'] = $rt['total'];
                $rts['username'] = $orderdata['consignee'];
                $rts['mobile'] = $orderdata['mobile'];
                $this->Session->write('cart', "");
                $this->_alipayment($rts);
                exit;
                $this->set('rt', $rt);
                $this->Session->write('cart', "");
                $this->template('mycart_submit_order');
                exit;
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '您的订单没有提交成功，请重新支付！');
                exit;
            }
        } else {
            $this->App->write('cart', "");
            $this->jump(ADMIN_URL . 'mycart.php');
        }
        $this->App->write('cart', "");
        $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '意外错误，请重新支付！');
        exit;
    }
	
	function xingjie_pay($iid, $pay_id){
        
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$iid." LIMIT 1");
        $rts = $this->_get_payinfo($pay_id);

         $pay = unserialize($rts['pay_config']);
          $user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=".$rt['user_id']." LIMIT 1");
          $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=".$user_bank['bank']." LIMIT 1");
          
           $card = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE bank_no='".$rt['bank_no']."' and uid = ".$rt['user_id']." LIMIT 1");
          
          $bankclass = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bankclass` WHERE code=".$bank['code']." LIMIT 1");
          
          $user_xj_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_xj_merchant` WHERE uid=".$rt['user_id']." LIMIT 1");
          $key = $pay['pay_code'];
        
        $appId = $pay['pay_no']; //分配的代理商唯一标识 是 String
        //sign //加密签名 是 String 签名方式见附录
        $nonceStr =  $this->str_rand();//随机字符串 是 string 字符范围a-zA-Z0-9
        //customerInfo //付款人银行卡信息 是 string 加密格式见附录
        $customer = $rt['bank_no']."|".$card['name']."|".$card['idcard']."|".$card['mobile'];
        $customerInfo = $this->encrypt_xj($customer,$key); //⽤户信息 是 string 加密格式⻅附录
        
         error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $customer . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_' . date('Y-m-d') . '.log');
         
        $totalFee = $rt['order_amount']*100; //交易金额 是 int 单位分，低于100元不能交易
        $agentOrderNo = $rt['order_sn']; //代理商订单编号 是 string
        $notifyUrl = $pay['pay_address']; //订单处理结果通知地址 是 string
        $mchId =  $user_xj_merchant['mchId']; //商户号 是 string
        
        $cvn = $card['cvn2'];
        //$expireDate = $card['valid'];
        
        
        $sign_str = "agentOrderNo=".$agentOrderNo."&appId=".$appId."&customerInfo=".$customerInfo."&cvn2=".$cvn."&mchId=".$mchId."&nonceStr=".$nonceStr."&notifyUrl=".$notifyUrl."&key=".$key;
        
        error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $sign_str . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_' . date('Y-m-d') . '.log');
        
        $sign = md5($sign_str);
        
        //$data = "address=".$address."&appId=".$appId."&cityCode=".$cityCode."&customerInfo=".$customerInfo."&d0fee=".$d0fee."&fee0=".$fee0."&nonceStr=".$nonceStr."&pointsType=".$pointsType."&provinceCode=".$provinceCode."&sign=".$sign;
//      
//       error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $data . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
         
        $url = 'http://47.96.171.202:8010/api/v1.0/order';
        
        $parm = array(
        'agentOrderNo' => $agentOrderNo,
        'appId' => $appId,
        'customerInfo' => $customerInfo,
        'cvn2'  => $cvn,
        'totalFee' => $totalFee,
        //'expireDate'  => $expireDate,
        'mchId' => $mchId,
        'nonceStr' => $nonceStr,
        'notifyUrl' => $notifyUrl,
        'sign' => $sign
        );
        
        $jsonStr = json_encode($parm);
        error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $jsonStr . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_' . date('Y-m-d') . '.log');
        $result = $this->http_post_data($url,$jsonStr);
        $result = json_decode($result,true);
        
        error_log('[' . date('Y-m-d H:i:s') . ']API3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_' . date('Y-m-d') . '.log');
        
        $this->set('result', $result);
        $this->set('rt', $rt);
        if($result['isSuccess']){
                    
        $this->title('收银台 - ' . $GLOBALS['LANG']['site_name']);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/kuaijie_xj');   
        
        // echo $result['data']['result'];
            
        }else{
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['message']);
        }
        }
	
		
		function xj_kj_confirm(){
            
            $uid = $this->Session->read('User.uid');
            
            $bank_no = $_POST['bank_no'];
            $orderNo = $_POST['orderNo'];
            $smsCode = $_POST['p_code'];
            
            $url = 'http://47.96.171.202:8010/api/v1.0/order/'.$orderNo.'/sms/'.$smsCode;
            
            $parm = array(
        'orderNo' => $orderNo,
        'smsCode' => $smsCode,
        );
        
        $jsonStr = json_encode($parm);
        error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $jsonStr . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_confirm_' . date('Y-m-d') . '.log');
        $result = $this->http_post_data($url,$jsonStr);
        $result = json_decode($result,true);
        
        error_log('[' . date('Y-m-d H:i:s') . ']API3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_confirm_' . date('Y-m-d') . '.log');
        
        
         if ($result['isSuccess']) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['data']['stateMsg']);
       }else{
           
                        
        if($result['code'] == '77'){
                    
            // $this->App->query("UPDATE `{$this->App->prefix() }user_card_ys_api` SET open = 1  WHERE uid = ".$uid." and bank_no='".$bank_no."'");
                     
        //$this->xingjie_open();
            $this->App->query("UPDATE `{$this->App->prefix() }user_card_ys_api` SET open = 1  WHERE uid = ".$uid." and bank_no='".$bank_no."'");

      $arr = array(

      'pay_id' => $pay_id,

      'card' => $bank_no

      );

      $open_result = $this->ajax_xingjie_open($arr);

                    

                    if($open_result == "success"){

                        

                        $result = $this->http_post_data($url,$jsonStr);

        $result = json_decode($result,true);

        

        error_log('[' . date('Y-m-d H:i:s') . ']API3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/xj_pay_confirm_' . date('Y-m-d') . '.log');

        

         if ($result['isSuccess']) {

                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['data']['stateMsg']);

       }else{

             $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['message']);

       }

        

                        }else{

                              $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $open_result);

                            

                            }
        }


           $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['message']);
           }

    
            exit();
        
            }
	
	function h5pay_jiaofei($iid, $pay_id){
		
		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$iid." LIMIT 1");
		 $payment = $this->App->findvar("SELECT pay_config FROM `{$this->App->prefix() }payment` WHERE `pay_id`=".$pay_id." LIMIT 1");
		  $rts = unserialize($payment);
		  
		  $user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=".$rt['user_id']." LIMIT 1");
		  $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=".$user_bank['bank']." LIMIT 1");
		  
		  $bankclass = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bankclass` WHERE code=".$bank['code']." LIMIT 1");
		  
		  
		  $user_card_h5_hq = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_h5_hq` WHERE uid=".$rt['user_id']." and bank_no='".$rt['bank_no']."' LIMIT 1");
		  
		  $key = $rts['pay_code'];
		  
		  
$merchantId = $rts['pay_no'];	//商户号		我司提供的商户号	M
$amount = sprintf("%.2f",$rt['order_amount']);	//交易金额	金额0.01就是1分	金额格式最好采用”0.01”,”1.00”正常金额小数点后两位	M
$corp_flow_no = $rt['order_sn'];	//订单号	不要超过19位（可以19位）	商户订单号（由商户生成，必须保证唯一，19位内数字的组合）	M
$sign = MD5($merchantId."pay".$amount.$key);	//验签	MD5(merchantId+"pay"+amount+商户秘钥)	商户秘钥code，由平台提供，加密方式采用MD5加密方式,MD5加密编码utf-8	M
//productDesc	//产品描述			O
$url_return = 'http://www.chm1688.com/m/h5pay.php';	//回显地址		交易完成后，本平台系统把交易参数传到回显地址	O
$buyer_ip = "47.97.189.250";  	//客服端IP		127.0.0.1	M
$notify_url = $rts['pay_address'];	//异步通知		商户必须实现，接收订单结果通知，接收请返回00	M
$rate = $rt['feilv']/100;	//下发快捷费率		rate的费率必须满足:交易金额*rate>1元	M
$userFee = 0.2;	//固定手续费			M
$payerAcc = $rt['bank_no'];	//付款方银行卡号		付款的银行卡卡号	M
$payerIdNum = $user_bank['idcard'];	//付款方身份证号		付款方的身份证号码	M
$payerName = $user_bank['uname'];	//付款方名称		付款方的姓名	M
$payerPhoneNo = $user_bank['mobile'];	//付款方手机号		付款方的手机号	M
$payerBankCode = $user_card_h5_hq['b_code'];	//付款方银行编码		银行编码，如中国建设银行则是CCB	M
$payeeIdNum = $user_bank['idcard'];	//收款方身份证号		收款方的身份证号码，需要与付款方身份证号码一致	M
$payeeBankCode = $bankclass['b_code'];	//收款方		银行编码，如中国建设银行则是CCB	M
$payeeAcc = $user_bank['banksn'];	//收款方银行卡号			M
$payeeName = $user_bank['uname'];	//收款方名称			M
$payeePhoneNo = $user_bank['mobile'];	//收款方手机号			M

		

            $param ="merchantId=".$merchantId.
			"&amount=".$amount.
			"&corp_flow_no=".$corp_flow_no.
			"&sign=".$sign.
			"&buyer_ip=".$buyer_ip.
			"&url_return=".$url_return.
			"&notify_url=".$notify_url.
			"&rate=".$rate.
			"&userFee=".$userFee.
			"&payerAcc=".$payerAcc.
			"&payerIdNum=".$payerIdNum.
			"&payerName=".$payerName.
			"&payerPhoneNo=".$payerPhoneNo.
			"&payerBankCode=".$payerBankCode.
			"&payeeIdNum=".$payeeIdNum.
			"&payeeBankCode=".$payeeBankCode.
			"&payeeAcc=".$payeeAcc.
			"&payeeName=".$payeeName.
			"&payeePhoneNo=".$payeePhoneNo;
			
		
		$url = "http://www.yum-pay.com:10005/CashOutPay/doCashOutPay.do";
		
		
		// header("Location:" . $url);
		
		error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $param. "\n\n", 3, './app/shopping/h5pay/jiaofei_' . date('Y-m-d') . '.log');
		
		echo $this->h5_post($url,$param);
		
		
			
		
		}
		
	
	
	function yisheng_kuaijie1($iid, $pay_id){
		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$iid." LIMIT 1");
		 $payment = $this->App->findvar("SELECT pay_config FROM `{$this->App->prefix() }payment` WHERE `pay_id`=".$pay_id." LIMIT 1");
		  $rts = unserialize($payment);
		   $user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $rt['user_id'] . " limit 1");
		 $this->set('rt', $rt);
		 $this->set('rts', $rts);
		 $this->set('user_bank', $user_bank);
		 $this->set('iid', $iid);
	  $this->set('pay_id', $pay_id);
      $this->title('收银台 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/kuaijie_ys1');
		}
		
		function ajax_yisheng_kuaijie($arr = array()){
			
			$uid = $this->Session->read('User.uid');
			
			$iid = $arr['oid'];
			if(!$iid){
				echo "支付订单不存在";
				}
			$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$iid." LIMIT 1");
			if(empty($rt)){
				echo "支付订单不存在";
				}
				
			$pay_id = $rt['pay_id'];
		 $user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $rt['user_id'] . " limit 1");
		  $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $user_bank['bank']);
		  $user_card_api = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid=".$rt['user_id']." and bank_no='" . $rt['bank_no']."' limit 1");
		  $payment = $this->App->findvar("SELECT pay_config FROM `{$this->App->prefix() }payment` WHERE `pay_id`=".$pay_id." LIMIT 1");
		  $rts = unserialize($payment);
		  $userinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user` WHERE user_id=" . $rt['user_id']);
		   $user_level = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid=" . $userinfo['user_rank'] . " LIMIT 1");
            $feilv = unserialize($user_level['feilv']);
          
              $koulv = $feilv['yinlian_api']/100;	
			  $sxf = $user_level['sxf_api']*100;
		
					$signKey=$rts['pay_code'];


$input_charset = "UTF-8";
$version = "N2";
$partner = $rts['pay_idt'];
$service = "qpay";
$sign_type = "MD5";
$order_id = $rt['order_sn'];
$merchant_id = $rts['pay_no'];
$amount = $rt['order_amount']*100;
$business_time = date('Y-m-d H:i:s',time());
$order_desc = "收银服务";
$name = $user_bank['uname'];//持卡人姓名
$notify_url = $rts['pay_address'];
$id_no = strtoupper($user_bank['idcard']);   //持卡人身份证	NO	320683XXXXXXXXXXXX
$acc =    $rt['bank_no'];//信用卡卡号	NO	1234567890000
$cvv =    $user_card_api['cvn2'];//信用卡背面3位数字	NO	123
$validity_date	=	$user_card_api['valid'];//信用卡有效期格式 MMYY 如果是 2019年05月 写成 0519	NO	0519
$mobile =    $user_card_api['mobile'];//信用卡预留号码	NO	13651654XXX
$settlement_acc =  $user_bank['banksn'] ; //借记卡卡号(结算账户)	NO	121212121212121212
$credit_bank_code =   $bank['code']; //消费银行卡银行代码 如招行 308	NO	308
$debit_bank_code =    $bank['code'];//借记卡(结算账户)银行代码 如招行 308	NO	308
$t0_fee	=	$sxf;//单位分	NO	t0 额外的手续费
$fee_rate	= $koulv;	//纯小数	NO	0.1 表示收取 千分之一的手续费
$has_point =   "true"; //字符串,代表是否有积分快捷支付	NO	true:代表有积分,false:无积分, 默认无积分

		
		$signstr = "acc=".$acc."&amount=".$amount."&business_time=".$business_time."&credit_bank_code=".$credit_bank_code."&cvv=".$cvv."&debit_bank_code=".$debit_bank_code."&fee_rate=".$fee_rate."&has_point=".$has_point."&id_no=".$id_no."&input_charset=".$input_charset."&merchant_id=".$merchant_id."&mobile=".$mobile."&name=".$name."&notify_url=".$notify_url."&order_desc=".$order_desc."&order_id=".$order_id."&partner=".$partner."&service=".$service."&settlement_acc=".$settlement_acc."&t0_fee=".$t0_fee."&validity_date=".$validity_date."&version=".$version;
 
$sign = md5($signstr.$signKey);
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr. "\n\n", 3, './app/shopping/kuaijie_api/' . date('Y-m-d') . '.log');

$data = 
"acc=".$acc.
"&amount=".$amount.
"&business_time=".$business_time.
"&credit_bank_code=".$credit_bank_code.
"&cvv=".$cvv.
"&debit_bank_code=".$debit_bank_code.
"&fee_rate=".$fee_rate.
"&has_point=".$has_point.
"&id_no=".$id_no.
"&input_charset=".$input_charset.
"&merchant_id=".$merchant_id.
"&mobile=".$mobile.
"&name=".$name.
"&notify_url=".$notify_url.
"&order_desc=".$order_desc.
"&order_id=".$order_id.
"&partner=".$partner.
"&service=".$service.
"&settlement_acc=".$settlement_acc.
"&sign_type=".$sign_type.
"&sign=".$sign.
"&t0_fee=".$t0_fee.
"&validity_date=".$validity_date.
"&version=".$version;

		
		$url = "https://wepay.mpay.cn/new_gateway.do";
		
		
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data. "\n\n", 3, './app/shopping/kuaijie_api/' . date('Y-m-d') . '.log');
		
//  echo post($url,$data); 
   $result = $this->h5_post($url,$data);
   

   
   error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true). "\n\n", 3, './app/shopping/kuaijie_api/' . date('Y-m-d') . '.log');
   
   $result = $this->xmlToArray2($result);
   
   if($result['is_success'] == 'T'){
	   echo "ok";
	   }else{
		   echo $result['error_msg'];
		   }	
			}
			
			
			
			function ys_kj_confirm_diy(){
						
						$order_id = $_POST['order_id'];
						$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$order_id." LIMIT 1");
						$pay_id = $rt['pay_id'];
						 $payment = $this->App->findvar("SELECT pay_config FROM `{$this->App->prefix() }payment` WHERE `pay_id`=".$pay_id." LIMIT 1");
		  $rts = unserialize($payment);
						
						$signKey=$rts['pay_code'];


$input_charset = "UTF-8";
$version = "N2";
$partner = $rts['pay_idt'];
$service = "qpay_confirm";
$sign_type = "MD5";
$order_id = $rt['order_sn'];
$merchant_id = $rts['pay_no'];
$check_code = $_POST['p_code'];

$signstr = "check_code=".$check_code."&input_charset=".$input_charset."&merchant_id=".$merchant_id."&order_id=".$order_id."&partner=".$partner."&service=".$service."&version=".$version;
 
$sign = md5($signstr.$signKey);
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr. "\n\n", 3, './app/shopping/kuaijie_api/confirm' . date('Y-m-d') . '.log');

$data = 
"check_code=".$check_code.
"&input_charset=".$input_charset.
"&merchant_id=".$merchant_id.
"&order_id=".$order_id.
"&partner=".$partner.
"&service=".$service.
"&sign_type=".$sign_type.
"&sign=".$sign.
"&version=".$version;

		
		$url = "https://wepay.mpay.cn/new_gateway.do";
		
		
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data. "\n\n", 3, './app/shopping/kuaijie_api/confirm' . date('Y-m-d') . '.log');
		
//  echo post($url,$data); 
   $result = $this->h5_post($url,$data);
   

   
   error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true). "\n\n", 3, './app/shopping/kuaijie_api/confirm' . date('Y-m-d') . '.log');
   
   $result = $this->xmlToArray2($result);
   
   
   if ($result['is_success'] == 'T') {
	   
	    $sd = array('order_sn' => $_POST['order_id'], 'status' => 1);
            if ($this->pay_successs_status_api($sd)) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付成功！');
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付失败！');
            }

      } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['error_msg']);
            }
   
						}
	
	function  yisheng_kuaijie($iid, $pay_id){
		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$iid." LIMIT 1");
		 $user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $rt['user_id'] . " limit 1");
		  $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $user_bank['bank']);
		  $user_card_api = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid=".$rt['user_id']." and bank_no='" . $rt['bank_no']."' limit 1");
		  $payment = $this->App->findvar("SELECT pay_config FROM `{$this->App->prefix() }payment` WHERE `pay_id`=".$pay_id." LIMIT 1");
		  $rts = unserialize($payment);
		  $userinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user` WHERE user_id=" . $rt['user_id']);
		   $user_level = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid=" . $userinfo['user_rank'] . " LIMIT 1");
            $feilv = unserialize($user_level['feilv']);
          
              $koulv = $feilv['yinlian_api']/100;	
			  $sxf = $user_level['sxf_api']*100;
		
					$signKey=$rts['pay_code'];

$input_charset = "UTF-8";
$version = "N2";
$partner = $rts['pay_idt'];
$service = "qpay";
$sign_type = "MD5";
$order_id = $rt['order_sn'];
$merchant_id = $rts['pay_no'];
$amount = $rt['order_amount']*100;
$business_time = date('Y-m-d H:i:s',time());
$order_desc = "收银服务";
$name = $user_bank['uname'];//持卡人姓名
$notify_url = $rts['pay_address'];
$id_no = strtoupper($user_bank['idcard']);   //持卡人身份证	NO	320683XXXXXXXXXXXX
$acc =    $rt['bank_no'];//信用卡卡号	NO	1234567890000
$cvv =    $user_card_api['cvn2'];//信用卡背面3位数字	NO	123
$validity_date	=	$user_card_api['valid'];//信用卡有效期格式 MMYY 如果是 2019年05月 写成 0519	NO	0519
$mobile =    $user_card_api['mobile'];//信用卡预留号码	NO	13651654XXX
$settlement_acc =  $user_bank['banksn'] ; //借记卡卡号(结算账户)	NO	121212121212121212
$credit_bank_code =   $bank['code']; //消费银行卡银行代码 如招行 308	NO	308
$debit_bank_code =    $bank['code'];//借记卡(结算账户)银行代码 如招行 308	NO	308
$t0_fee	=	$sxf;//单位分	NO	t0 额外的手续费
$fee_rate	= $koulv;	//纯小数	NO	0.1 表示收取 千分之一的手续费
$has_point =   "true"; //字符串,代表是否有积分快捷支付	NO	true:代表有积分,false:无积分, 默认无积分


//$input_charset = "UTF-8";
//$version = "N2";
//$partner = "900029000021348";
//$service = "qpay";
//$sign_type = "MD5";
//$order_id = "WS".date('YmdHis',time()).time();
//$merchant_id = "900029000021348";
//$amount = 5000;
//$business_time = date('Y-m-d H:i:s',time());
//$order_desc = "测试商品";
//$name = "周华泽";//持卡人姓名
//$id_no = "440823198908151738";   //持卡人身份证	NO	320683XXXXXXXXXXXX
//$acc =    "6222520716535659";//信用卡卡号	NO	1234567890000
//$cvv =    "297";//信用卡背面3位数字	NO	123
//$validity_date	=	"0621";//信用卡有效期格式 MMYY 如果是 2019年05月 写成 0519	NO	0519
//$mobile =    "15626463986";//信用卡预留号码	NO	13651654XXX
//$settlement_acc =  "6222600710016642359" ; //借记卡卡号(结算账户)	NO	121212121212121212
//$credit_bank_code =   "302"; //消费银行卡银行代码 如招行 308	NO	308
//$debit_bank_code =    "301";//借记卡(结算账户)银行代码 如招行 308	NO	308
//$t0_fee	=	200;//单位分	NO	t0 额外的手续费
//$fee_rate	= 0.04;	//纯小数	NO	0.1 表示收取 千分之一的手续费
//$has_point =   "true"; //字符串,代表是否有积分快捷支付	NO	true:代表有积分,false:无积分, 默认无


 $signstr = "acc=".$acc."&amount=".$amount."&business_time=".$business_time."&credit_bank_code=".$credit_bank_code."&cvv=".$cvv."&debit_bank_code=".$debit_bank_code."&fee_rate=".$fee_rate."&has_point=".$has_point."&id_no=".$id_no."&input_charset=".$input_charset."&merchant_id=".$merchant_id."&mobile=".$mobile."&name=".$name."&notify_url=".$notify_url."&order_desc=".$order_desc."&order_id=".$order_id."&partner=".$partner."&service=".$service."&settlement_acc=".$settlement_acc."&t0_fee=".$t0_fee."&validity_date=".$validity_date."&version=".$version;
 
$sign = md5($signstr.$signKey);
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr. "\n\n", 3, './app/shopping/kuaijie_api/' . date('Y-m-d') . '.log');

$data = 
"acc=".$acc.
"&amount=".$amount.
"&business_time=".$business_time.
"&credit_bank_code=".$credit_bank_code.
"&cvv=".$cvv.
"&debit_bank_code=".$debit_bank_code.
"&fee_rate=".$fee_rate.
"&has_point=".$has_point.
"&id_no=".$id_no.
"&input_charset=".$input_charset.
"&merchant_id=".$merchant_id.
"&mobile=".$mobile.
"&name=".$name.
"&notify_url=".$notify_url.
"&order_desc=".$order_desc.
"&order_id=".$order_id.
"&partner=".$partner.
"&service=".$service.
"&settlement_acc=".$settlement_acc.
"&sign_type=".$sign_type.
"&sign=".$sign.
"&t0_fee=".$t0_fee.
"&validity_date=".$validity_date.
"&version=".$version;

$arr = 
"action=yisheng_kuaijie&acc=".$acc.
"&amount=".$amount.
"&business_time=".$business_time.
"&credit_bank_code=".$credit_bank_code.
"&cvv=".$cvv.
"&debit_bank_code=".$debit_bank_code.
"&fee_rate=".$fee_rate.
"&has_point=".$has_point.
"&id_no=".$id_no.
"&input_charset=".$input_charset.
"&merchant_id=".$merchant_id.
"&mobile=".$mobile.
"&name=".$name.
"&notify_url=".$notify_url.
"&order_desc=".$order_desc.
"&order_id=".$order_id.
"&partner=".$partner.
"&service=".$service.
"&settlement_acc=".$settlement_acc.
"&sign_type=".$sign_type.
"&sign=".$sign.
"&t0_fee=".$t0_fee.
"&validity_date=".$validity_date.
"&version=".$version;

//$arr = array(
//'action' => 'yisheng_kuaijie',
//'acc' => $acc,
//'amount' => $amount,
//'business_time' => $business_time,
//'credit_bank_code' => $credit_bank_code,
//'cvv' => $cvv,
//'debit_bank_code' => $debit_bank_code,
//'fee_rate' => $fee_rate,
//'has_point' => $has_point,
//'id_no' => $id_no,
//'input_charset' => $input_charset,
//'merchant_id' => $merchant_id,
//'mobile' => $mobile,
//'name' => $name,
//'notify_url' => $notify_url,
//'order_desc' => $order_desc,
//'order_id' => $order_id,
//'partner' => $partner,
//'service' => $service,
//'settlement_acc' => $settlement_acc,
//'sign_type' => $sign_type,
//'sign' => $sign,
//'t0_fee' => $t0_fee,
//'validity_date' => $validity_date,
//'version' => $version
//);

	//	if($rt['user_id'] == 5){
					$url = "http://ws.weishuapay.com/m/yishengpay/yishengpay.php";
		//	}else{
//				$url = "https://wepay.mpay.cn/new_gateway.do";
//				}
		
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data. "\n\n", 3, './app/shopping/kuaijie_api/' . date('Y-m-d') . '.log');
		
//  echo post($url,$data); 
  
   //if($rt['user_id'] == 5){
    $result = $this->h5_post($url,$arr);//人人嘀平台请求
	$result = json_decode($result);
  // }else{
	   // $result = $this->h5_post($url,$data);
	  // }
   

   
   error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true). "\n\n", 3, './app/shopping/kuaijie_api/' . date('Y-m-d') . '.log');
   
   $result = $this->xmlToArray2($result);
   
     $this->set('result', $result);
	  $this->set('rt', $rt);
	  $this->set('rts', $rts);
      $this->title('收银台 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/kuaijie_ys');
					

					}
					//function request_post($url = '', $post_data = array()) {
//         if (empty($url) || empty($post_data)) {
//            return false;
//        }
//         
//        $o = "";
//        foreach ( $post_data as $k => $v )
//        {
//            $o.= "$k=" . urlencode( $v ). "&" ;
//        }
//        $post_data = substr($o,0,-1);
// 
//        $postUrl = $url;
//        $curlPost = $post_data;
//
//		 
//        $ch = curl_init();//初始化curl
//        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
//        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
//        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
//        $data = curl_exec($ch);//运行curl
//        curl_close($ch);
//         
//        return $data;
//    }
					
					
					function ys_kj_confirm(){
						
						$pay_id = $_POST['pay_id'];
						$uid = $_POST['uid'];
						 $payment = $this->App->findvar("SELECT pay_config FROM `{$this->App->prefix() }payment` WHERE `pay_id`=".$pay_id." LIMIT 1");
		  $rts = unserialize($payment);
						
						$signKey=$rts['pay_code'];


$input_charset = "UTF-8";
$version = "N2";
$partner = $rts['pay_idt'];
$service = "qpay_confirm";
$sign_type = "MD5";
$order_id = $_POST['order_id'];
$merchant_id = $rts['pay_no'];
$check_code = $_POST['p_code'];

$signstr = "check_code=".$check_code."&input_charset=".$input_charset."&merchant_id=".$merchant_id."&order_id=".$order_id."&partner=".$partner."&service=".$service."&version=".$version;
 
$sign = md5($signstr.$signKey);
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr. "\n\n", 3, './app/shopping/kuaijie_api/confirm' . date('Y-m-d') . '.log');

$data = 
"check_code=".$check_code.
"&input_charset=".$input_charset.
"&merchant_id=".$merchant_id.
"&order_id=".$order_id.
"&partner=".$partner.
"&service=".$service.
"&sign_type=".$sign_type.
"&sign=".$sign.
"&version=".$version;

//$arr = array(
//'action' => 'yisheng_kuaijie_confirm',
//'check_code' => $check_code,
//'input_charset' => $input_charset,
//'merchant_id' => $merchant_id,
//'order_id' => $order_id,
//'partner' => $partner,
//'service' => $service,
//'sign_type' => $sign_type,
//'sign' => $sign,
//'version' => $version
//);

$arr = 
"action=yisheng_kuaijie_confirm&check_code=".$check_code.
"&input_charset=".$input_charset.
"&merchant_id=".$merchant_id.
"&order_id=".$order_id.
"&partner=".$partner.
"&service=".$service.
"&sign_type=".$sign_type.
"&sign=".$sign.
"&version=".$version;
		
		//if($uid == 5){
					$url = "http://ws.weishuapay.com/m/yishengpay/yishengpay.php";
			//}else{
				//$url = "https://wepay.mpay.cn/new_gateway.do";
				//}
		
		
error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data. "\n\n", 3, './app/shopping/kuaijie_api/confirm' . date('Y-m-d') . '.log');
		
//  echo post($url,$data); 
  //if($uid == 5){
    $result = $this->h5_post($url,$arr);//人人嘀平台请求
	$result = json_decode($result);
  // }else{
	    //$result = $this->h5_post($url,$data);
	  // }
   

   
   error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true). "\n\n", 3, './app/shopping/kuaijie_api/confirm' . date('Y-m-d') . '.log');
   
   $result = $this->xmlToArray2($result);
   
   
   if ($result['is_success'] == 'T') {
	   if(!empty($result['response']['errCode']) && $result['response']['errCode'] == '00'){
	   // $sd = array('order_sn' => $_POST['order_id'], 'status' => 1);
//            if ($this->pay_successs_status_api($sd)) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['response']['errCodeDes']);
           // } else {
//                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付失败！');
//            }
	   }else{
		   $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['response']['errCodeDes']);
		   }

      } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $result['error_msg']);
            }
   
						}
					
					function xmlToArray2($xml) { 
    // 将XML转为array 
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true); 
    return $array_data; 
}
					
	
	function h5pay($iid, $pay_id){
		
		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id=".$iid." LIMIT 1");
		$user_merchant = $this->App->findvar("SELECT info FROM `{$this->App->prefix() }user_merchant` WHERE uid=".$rt['user_id']." LIMIT 1");
		$merchant = unserialize($user_merchant);
		
		$key = $merchant['merchantKey'];
		
		$ORDER_ID  = $rt['order_sn'];                         
		$ORDER_AMT = $rt['order_amount'];                                     
		$ORDER_TIME = date('YmdHis',time());                                  
		$PAY_TYPE  ="13";                                       
		$USER_TYPE ="02"; 
		$USER_ID   =$merchant['merchantNo'];                                        
		$BG_URL    =ADMIN_URL ."wxpay/notify_ylh5.php";
		$PAGE_URL  =ADMIN_URL . 'ylpay.php?type=h5pay';
		$SIGN_TYPE ="03";                               
		$BUS_CODE  ="3001";               //业务代码                     
		$CCT       ="CNY";  
		
		$ID_NO =   $merchant['legalPersonIdcard']; 
		$SETT_ACCT_NO = $merchant['bankAccountNo'];
		$CARD_INST_NAME = $merchant['settBankName'];
		$NAME        = $merchant['bankAccountName'];
		$PHONE_NO   = $merchant['phoneNo'];
		$ADD1   = $rt['bank_no']; //支付卡号                             

       // $sign_source = "ORDER_ID=".$ORDER_ID."&ORDER_AMT=".$ORDER_AMT."&ORDER_TIME=".$ORDER_TIME."&PAY_TYPE=".$PAY_TYPE."&USER_TYPE=".$USER_TYPE."&USER_ID=".$USER_ID."&BUS_CODE=".$BUS_CODE;
	   
	    $sign_source = $ORDER_ID.$ORDER_AMT.$ORDER_TIME.$PAY_TYPE.$USER_TYPE.$USER_ID.$BUS_CODE;
		
		
        $sign = $this->makeSign($sign_source,$key);

            $param ="ORDER_ID=".$ORDER_ID.
			"&ORDER_AMT=".$ORDER_AMT.
			"&ORDER_TIME=".$ORDER_TIME.
			"&PAY_TYPE=".$PAY_TYPE.
			"&USER_TYPE=".$USER_TYPE.
			"&BG_URL=".$BG_URL.
			"&PAGE_URL=".$PAGE_URL.
			"&USER_ID=".$USER_ID.
			"&SIGN=".$sign.
			"&SIGN_TYPE=".$SIGN_TYPE.
			"&BUS_CODE=".$BUS_CODE.
			"&CCT=".$CCT.
			"&PHONE_NO=".$PHONE_NO.
			"&ID_NO=".$ID_NO.
			"&SETT_ACCT_NO=".$SETT_ACCT_NO.
			"&CARD_INST_NAME=".$CARD_INST_NAME.
			"&NAME=".$NAME.
			"&ADD1=".$ADD1;
			
		
		$url = "http://npsapi.mylandpay.com/MyLandQuickPay/servlet/QuickPay";
		
		
		// header("Location:" . $url);
		
		error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $param. "\n\n", 3, './app/shopping/h5pay/baowen_' . date('Y-m-d') . '.log');
		
		echo $this->h5_post($url,$param);
		
		
			
		
		}
		
		function h5_post($url, $post_data = '', $timeout = 60){//curl
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_POST, 1);
    if($post_data != ''){
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
  }

		
		function makeSign($sign_source,$key){
			
			$sign_source1 = strtoupper(md5($sign_source));
		    $sign_source2 = $sign_source1.$key;
			$sign_s = strtoupper(md5($sign_source2));
			
			$sign = substr($sign_s,8,16);
		    return $sign;
			
			}
		
		
		
    function bangka_h5() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->findrow("SELECT uname,idcard FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1");
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('添加新卡 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_h5');
    }
	
	function bangka_h5_hq() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->findrow("SELECT uname,idcard FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1");
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('添加新卡 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_h5_hq');
    }
	
	function bk_confirm_h5_hq() {
        $uid = $this->Session->read('User.uid');
        //if($uid == 42){
        $card['uid'] = $uid;
        $card['name'] = $_POST['name'];
        $card['idcard'] = $_POST['idcard'];
        $card['bank_no'] = $_POST['bank_no'];
        $card['b_code'] = $_POST['b_code'];
        // $card['valid'] = $_POST['valid'];
        //				 $card['cvn2'] = $_POST['cvn2'];
        $r = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_card_h5_hq` WHERE uid=" . $uid . " and bank_no='" . $card['bank_no'] . "' LIMIT 1");
      
        if ($r) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '该卡已绑定！');
            exit;
        }
      
	    if ($this->App->insert('user_card_h5_hq', $card)) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                exit;
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=bangka', 0, '绑卡失败，请重新绑定！');
                exit;
            }
			
		
		}
	
    function bk_confirm_h5() {
        $uid = $this->Session->read('User.uid');
        //if($uid == 42){
        $card['uid'] = $uid;
        $card['name'] = $_POST['name'];
        $card['idcard'] = $_POST['idcard'];
        $card['bank_no'] = $_POST['bank_no'];
      //  $card['mobile'] = $_POST['mobile'];
        // $card['valid'] = $_POST['valid'];
        //				 $card['cvn2'] = $_POST['cvn2'];
        $r = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_card_h5` WHERE uid=" . $uid . " and bank_no='" . $card['bank_no'] . "' LIMIT 1");
        $rr = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_card` WHERE uid=" . $uid . " and bank_no='" . $card['bank_no'] . "' LIMIT 1");
        if ($r) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '该卡已绑定！');
            exit;
        }
        if ($rr) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '该卡已绑定！');
            exit;
        }
       // $key = 'c3b0cdac';
//        $transcode = '007';
//        $version = '0100';
//        $ordersn = time() . $uid;
//        $merchno = '201705110080808';
//        $dsorderid = time() . $uid;
//        $idtype = '01';
//        $idcard = $card['idcard'];
//        $username = $card['name'];
//        $bankcard = $card['bank_no'];
//        $mobile = $card['mobile'];
//        $signstr = "bankcard=" . $bankcard . "dsorderid=" . $dsorderid . "idcard=" . $idcard . "idtype=" . $idtype . "merchno=" . $merchno . "mobile=" . $mobile . "ordersn=" . $ordersn . "transcode=" . $transcode . "username=" . $username . "version=" . $version;
//        $sign = md5($signstr . $key);
//        $postdata = jsonFormat(array('transcode' => $transcode, 'version' => $version, 'ordersn' => $ordersn, 'merchno' => $merchno, 'dsorderid' => $dsorderid, 'sign' => $sign, 'idtype' => $idtype, 'idcard' => $idcard, 'username' => $username, 'bankcard' => $bankcard, 'mobile' => $mobile));
//        $url = "http://mdt.huanqiuhuiju.com:9002/authsys/api/auth/execute.do";
//        $return = json_decode(curl_bank($url, $postdata), true);
//        if ($return['returncode'] == "0000") {
            //  if($r){
            //
            //				  $this->App->update('user_card_h5',$card,'bank_no',$bankCardNo);
            //
            //				   $this->jump(ADMIN_URL.'mycart.php?type=shoukuan',0,'绑卡成功，请重新选择支付！'); exit;
            //
            //
            //				  }else{
            //
            //					  if($rr){
            //						  $this->jump(ADMIN_URL.'mycart.php?type=shoukuan',0,'该卡已绑定！'); exit;
            //						  }else{
            if ($this->App->insert('user_card_h5', $card)) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                exit;
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=bangka', 0, '绑卡失败，请重新绑定！');
                exit;
            }
       // }else{
//               $this->jump(ADMIN_URL.'mycart.php?type=shoukuan',0,$return['errtext']); exit;
//        	 }
        
    }
	
	
	
	
    function bangka() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->findrow("SELECT uname,idcard FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1");
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('添加新卡 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka');
    }
	
	
	 function bangka_api() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->findrow("SELECT uname,idcard FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1");
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('添加新卡 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_api');
    }
	
	function ys_bk_confirm_api() {
		 $uid = $this->Session->read('User.uid');
		  $card['uid'] = $uid;
        $card['name'] = $_POST['name'];
        $card['idcard'] = $_POST['idcard'];
        $card['bank_no'] = $_POST['bank_no'];
        $card['mobile'] = $_POST['mobile'];
        $card['valid'] = $_POST['valid'];
        $card['cvn2'] = $_POST['cvn2'];
		
		
		
		 $r = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid=" . $uid . " and bank_no='" . $bankCardNo . "' LIMIT 1");
                if ($r) {
                    $this->App->update('user_card_ys_api', $card, 'bank_no', $bankCardNo);
                    $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                    exit;
                    //  $this->jump(ADMIN_URL.'mycart.php?type=bangka',0,'该卡已绑定！'); exit;
                    
                } else {
                    if ($this->App->insert('user_card_ys_api', $card)) {
                        $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                        exit;
                    } else {
                        $this->jump(ADMIN_URL . 'mycart.php?type=bangka_api', 0, '绑卡失败，请重新绑定！');
                        exit;
                    }
                }
				
				
		}
	
	
	 function bk_confirm_api() {
        $uid = $this->Session->read('User.uid');
		
		  $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_api` WHERE uid=" . $uid . " limit 1");
		  
		  $rts = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }payment` WHERE `pay_id`=19 LIMIT 1");
        $pay = unserialize($rts['pay_config']);
        //if($uid == 42){
        $card['uid'] = $uid;
        $card['name'] = $_POST['name'];
        $card['idcard'] = $_POST['idcard'];
        $card['bank_no'] = $_POST['bank_no'];
        $card['mobile'] = $_POST['mobile'];
        $card['valid'] = $_POST['valid'];
        $card['cvn2'] = $_POST['cvn2'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
	<head>
		<version>1.0.0</version>
		<merchantId>'.$pay['pay_no'].'</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP001</tranCode>
		<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
		<bankCardNo>' . $card['bank_no'] . '</bankCardNo>
		<accountName>' . $card['name'] . '</accountName>
		<bankCardType>02</bankCardType>
		<certificateType>ZR01</certificateType>
		<certificateNo>' . strtoupper($card['idcard']) . '</certificateNo>
		<mobilePhone>' . $card['mobile'] . '</mobilePhone>
		<valid>' . $card['valid'] . '</valid>
		<cvn2>' . $card['cvn2'] . '</cvn2>
		<terminalId>'.$pay['pay_idt'].'</terminalId>
		<userId>' . $card['uid'] . '</userId>
		<childMerchantId>'.$sj1['servicePhone'].'</childMerchantId>
	</body>
</merchant>
';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
		
		error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml, true)) . "\n\n", 3, './app/shopping/api/bangka_' . date('Y-m-d') . '.log');
		
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => 'IFP001', 'callBack' => 'http://www.chm1688.com/m/zhifu.php');
        //var_dump($postdata);
        //  $post_string = "encryptData=".$encryptData."&encryptKey=".$encyrptKey."&merchantId=102100000125&signData=".$signData."tranCode=IFP001&callBack=http://www.chm1688.com/m/zhifu.php";
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153997077.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
		
		error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . iconv('UTF-8', 'GBK', var_export($xmlData, true)) . "\n\n", 3, './app/shopping/api/bangka_' . date('Y-m-d') . '.log');
		
        $xml_obj = simplexml_load_string($xmlData);
		
		
        //
        //			 var_dump($xml_obj);
        $respCode = (string)$xml_obj->head->respCode;
        $respMsg = (string)$xml_obj->head->respMsg;
        $bindId = (string)$xml_obj->body->bindId;
        $bankCardNo = (string)$xml_obj->body->bankCardNo;
        // echo $respCode; echo $respMsg;
        if ($respCode == '000000') {
            if (!empty($bindId)) {
                $r = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_card_api` WHERE uid=" . $uid . " and bank_no='" . $bankCardNo . "' LIMIT 1");
                $card['bindId'] = $bindId;
                if ($r) {
                    $this->App->update('user_card_api', $card, 'bank_no', $bankCardNo);
                    $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                    exit;
                    //  $this->jump(ADMIN_URL.'mycart.php?type=bangka',0,'该卡已绑定！'); exit;
                    
                } else {
                    if ($this->App->insert('user_card_api', $card)) {
                        $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                        exit;
                    } else {
                        $this->jump(ADMIN_URL . 'mycart.php?type=bangka_api', 0, '绑卡失败，请重新绑定！');
                        exit;
                    }
                }
            }
        } else {
            $this->jump(ADMIN_URL . 'mycart.php?type=bangka_api', 0, $respMsg);
            exit;
        }
      
        
    }
	
	
	
    function bk_confirm() {
        $uid = $this->Session->read('User.uid');
        //if($uid == 42){
        $card['uid'] = $uid;
        $card['name'] = $_POST['name'];
        $card['idcard'] = $_POST['idcard'];
        $card['bank_no'] = $_POST['bank_no'];
        $card['mobile'] = $_POST['mobile'];
        $card['valid'] = $_POST['valid'];
        $card['cvn2'] = $_POST['cvn2'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
	<head>
		<version>1.0.0</version>
		<merchantId>102100000145</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP001</tranCode>
		<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
		<bankCardNo>' . $card['bank_no'] . '</bankCardNo>
		<accountName>' . $card['name'] . '</accountName>
		<bankCardType>02</bankCardType>
		<certificateType>ZR01</certificateType>
		<certificateNo>' . strtoupper($card['idcard']) . '</certificateNo>
		<mobilePhone>' . $card['mobile'] . '</mobilePhone>
		<valid>' . $card['valid'] . '</valid>
		<cvn2>' . $card['cvn2'] . '</cvn2>
		<userId>' . $card['uid'] . '</userId>
	</body>
</merchant>
';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
		
		error_log('[' . date('Y-m-d H:i:s') . ']银联绑卡:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml, true)) . "\n\n", 3, './app/shopping/kuaijie/bangka_' . date('Y-m-d') . '.log');
		
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, 'pkcs8_rsa_private_key_2048.pem');
        $encyrptKey = $this->rsasign_public($key, 'rsa_public_key_2048.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => '102100000145', 'signData' => $signData, 'tranCode' => 'IFP001', 'callBack' => 'http://www.chm1688.com/m/zhifu.php');
        //var_dump($postdata);
        //  $post_string = "encryptData=".$encryptData."&encryptKey=".$encyrptKey."&merchantId=102100000125&signData=".$signData."tranCode=IFP001&callBack=http://www.chm1688.com/m/zhifu.php";
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, 'pkcs8_rsa_private_key_2048.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        $xml_obj = simplexml_load_string($xmlData);
		
		error_log('[' . date('Y-m-d H:i:s') . ']银联绑卡:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml_obj, true)) . "\n\n", 3, './app/shopping/kuaijie/bangka_' . date('Y-m-d') . '.log');
        //
        //			 var_dump($xml_obj);
        $respCode = (string)$xml_obj->head->respCode;
        $respMsg = (string)$xml_obj->head->respMsg;
        $bindId = (string)$xml_obj->body->bindId;
        $bankCardNo = (string)$xml_obj->body->bankCardNo;
        // echo $respCode; echo $respMsg;
        if ($respCode == '000000') {
            if (!empty($bindId)) {
                $r = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_card` WHERE uid=" . $uid . " and bank_no='" . $bankCardNo . "' LIMIT 1");
                $card['bindId'] = $bindId;
                if ($r) {
                    $this->App->update('user_card', $card, 'bank_no', $bankCardNo);
                    $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                    exit;
                    //  $this->jump(ADMIN_URL.'mycart.php?type=bangka',0,'该卡已绑定！'); exit;
                    
                } else {
                    if ($this->App->insert('user_card', $card)) {
                        $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                        exit;
                    } else {
                        $this->jump(ADMIN_URL . 'mycart.php?type=bangka', 0, '绑卡失败，请重新绑定！');
                        exit;
                    }
                }
            }
        } else {
            $this->jump(ADMIN_URL . 'mycart.php?type=bangka', 0, $respMsg);
            exit;
        }
        $first = strpos($resp[2], "="); //字符第一次出现的位置
        $signData_host = substr($resp[2], $first + 1, strlen($resp[2]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //  $sig = base64_decode($signData_host);
        //$res = openssl_pkey_get_public(file_get_contents("test_rsa_public_key_2048.pem"));
        //if (openssl_verify('hello', $sig, $res) === 1){
        //	echo "签名验证通过";
        //	}else{
        //		echo "签名验证不通过";
        //		}
        $first = strpos($resp[3], "="); //字符第一次出现的位置
        $tranCode_host = substr($resp[3], $first + 1, strlen($resp[3]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //  $re=file_get_contents($url);
        //	 echo $this->curl_access($url,$postdata);
        //echo $re;
        exit;
        //}
        // if(isset($_POST) && !empty($_POST)){
        //			  // $lid = $_POST['lid'];
        //			  $card['uid'] = $uid;
        //			  $card['name'] = $_POST['name'];
        //			   $card['idcard'] = $_POST['idcard'];
        //			    $card['bank_no'] = $_POST['bank_no'];
        //				 $card['mobile'] = $_POST['mobile'];
        //			  }
        //			  $r = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}user_card` WHERE uid=".$uid." and bank_no=".$card['bank_no']." LIMIT 1");
        //			  if($r){
        //
        //				    $this->jump(ADMIN_URL.'mycart.php?type=bangka',0,'该卡已绑定！'); exit;
        //
        //				  }
        //				  else{
        //			  if($this->App->insert('user_card',$card)){
        //
        //				  $this->jump(ADMIN_URL.'mycart.php?type=shoukuan',0,'绑卡成功，请重新选择支付！'); exit;
        //
        //				  }
        //				  else{
        //					    $this->jump(ADMIN_URL.'mycart.php?type=bangka',0,'绑卡失败，请重新绑定！'); exit;
        //					  }
        //					  }
        //			  $xml = '<merchant>
        //	<head>
        //		<version>1.0.0</version>
        //		<merchantid>102100000125</merchantid>
        //		<msgtype>01</msgtype>
        //		<trancode>IFP001</trancode>
        //		<reqmsgid>'.date('Ymd',time()).$uid.time().'</reqmsgid>
        //		<reqdate>'.date('Ymdhis',time()).'</reqdate>
        //	</head>
        //	<body>
        //		<bankcardno>'.$bank_no.'</bankcardno>
        //		<accountName>'.$name.'</accountName>
        //		<bankCardType>02</bankCardType>
        //		<certificateType>ZR01</certificateType>
        //		<certificateNo>'.$idcard.'</certificateNo>
        //		<mobilePhone>'.$mobile.'</mobilePhone>
        //		<valid></valid>
        //		<cvn2></cvn2>
        //		<pin></pin>
        //		< bankBranch ></ bankBranch >
        //		< province > </ province >
        //		< city > </ city >
        //		<userid>20000147</userid>
        //	</body>
        //</merchant>';
        /* $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;*/
        /* $this->send_data = $xml;
        $this->url = "http://120.31.132.114:8080/entry.do";
           $this->curl_access($this->url);
        
        
        var_dump($this->ret_data);  */
        //$this->App->insert('user_card',$dd)
        //$this->set('rt',$rt);
        //		$this->title('添加新卡 - ' . $GLOBALS['LANG']['site_name']);
        //		 $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        //        $this->template($mb . '/bangka');
        
    }
    //20170103 解绑
	
	function delete_yl($arr = array()){
		$card_id = $arr['id'];
		$uid = $this->Session->read('User.uid');
		
		if($card_id && $uid){
			if($this->App->delete('user_card_h5', 'id', $card_id)){
				echo "success";
				}else{
					echo "解绑失败";
					}
			}else{
				echo "解绑失败";
				}
		
		}
		
		function delete_yl_hq($arr = array()){
		$card_id = $arr['id'];
		$uid = $this->Session->read('User.uid');
		
		if($card_id && $uid){
			if($this->App->delete('user_card_h5_hq', 'id', $card_id)){
				echo "success";
				}else{
					echo "解绑失败";
					}
			}else{
				echo "解绑失败";
				}
		
		}
		
		
    function delete_ylbank() {
		
		$uid = $this->Session->read('User.uid');
		if($uid != 42){
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
		}

		$id = $_GET['id'];
		
		$r = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE id=" .$id. " LIMIT 1");
		if(empty($r)){
			$this->jump(ADMIN_URL . 'mycart.php?type=bangka_list_api');
            exit;
			}
		
		if($this->App->delete('user_card_ys_api', 'id', $id)){
        $this->jump(ADMIN_URL . 'mycart.php?type=bangka_list_api', 0, "解绑成功");
            exit;
		}else{
			 $this->jump(ADMIN_URL . 'mycart.php?type=bangka_list_api', 0, "解绑失败");
			  exit;
			}
      
		
		
    }
    function jiemis($encryptKey_host, $private_key_path) {
        $sKey = file_get_contents($private_key_path);
        if (openssl_private_decrypt(base64_decode($encryptKey_host), $decrypted, $sKey)) $data = $decrypted;
        else $data = '';
        //openssl_private_decrypt(base64_decode($encryptKey_host),$decrypted,$sKey);//私钥解密
        return $data;
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
	
	function kuaijie_api($lid = 0, $pay_id = 0) { //银联快捷API一户一码  20171031
        $uid = $this->Session->read('User.uid');
		
		 $sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2_api` WHERE uid=" . $uid . " LIMIT 1");
		 
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        $amount = $rt['order_amount'] * 100;
		
		
			  $card = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_api` WHERE uid='$uid' and bank_no=" . $rt['bank_no'] . " LIMIT 1");
			
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
		
		$shouxufei = $rt['order_amount']*($rt['feilv']/10000)+$rt['sxf_api'];
		
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
	<head>
		<version>1.0.0</version>
		<merchantId>' . $rts['pay_no'] . '</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP004</tranCode>
		<reqMsgId>' . $rt['order_sn'] . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
	   <terminalId>' . $rts['pay_idt'] . '</terminalId>
		<userId>' . $uid . '</userId>
		<bindId>' . $card['bindId'] . '</bindId>
		<childMerchantId>'.$sj2['merchantId'].'</childMerchantId>
		<currency>CNY</currency>
        <reckonCurrency>CNY</reckonCurrency >
		<amount>' . $amount . '</amount>
		<fcCardNo>'.$sj2['bankaccountNo'].'</fcCardNo>
		<userFee>0</userFee>
		<productCategory>01</productCategory>
		<productName>' . $rt['order_sn'] . '</productName>
		<productDesc>' . $rt['order_sn'] . '</productDesc>
		<reckonCurrency>CNY</reckonCurrency>
	</body>
</merchant>';

		

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']快捷支付:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml, true)) . "\n\n", 3, './app/shopping/kuaijie_api/pay_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $rts['pay_no'], 'signData' => $signData, 'tranCode' => 'IFP004', 'callBack' => $rts['pay_address']);
        //var_dump($postdata);
        //  $post_string = "encryptData=".$encryptData."&encryptKey=".$encyrptKey."&merchantId=102100000125&signData=".$signData."tranCode=IFP001&callBack=http://www.chm1688.com/m/zhifu.php";
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
		
		  error_log('[' . date('Y-m-d H:i:s') . ']快捷支付:' . "\n" . var_export($response, true). "\n\n", 3, './app/shopping/kuaijie_api/pay_' . date('Y-m-d') . '.log');
		  
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, './app/shopping/549440153997077.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        $xml_obj = simplexml_load_string($xmlData);
        $rt['oriReqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        error_log('[' . date('Y-m-d H:i:s') . ']快捷支付:' . "\n" . var_export($xml_obj, true). "\n\n", 3, './app/shopping/kuaijie_api/pay_' . date('Y-m-d') . '.log');
        $this->set('rt', $rt);
        $this->set('rts', $rts);
        $this->set('uid', $uid);
        $this->set('card', $card);
        $this->title('收银台 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/kuaijie_api');
    }
	
    function kuaijie($lid = 0, $pay_id = 0) {//银联快捷API  20171031
        $uid = $this->Session->read('User.uid');
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        $amount = $rt['order_amount'] * 100;
        $card = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card` WHERE uid='$uid' and bank_no=" . $rt['bank_no'] . " LIMIT 1");
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
	<head>
		<version>1.0.0</version>
		<merchantId>' . $rts['pay_no'] . '</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP004</tranCode>
		<reqMsgId>' . $rt['order_sn'] . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
	   <terminalId>' . $rts['pay_idt'] . '</terminalId>
		<userId>' . $uid . '</userId>
		<bindId>' . $card['bindId'] . '</bindId>
		<currency>CNY</currency>
        <reckonCurrency>CNY</reckonCurrency >
		<amount>' . $amount . '</amount>
		<productCategory>01</productCategory>
		<productName>' . $rt['order_sn'] . '</productName>
		<productDesc>' . $rt['order_sn'] . '</productDesc>
	</body>
</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']快捷支付:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml, true)) . "\n\n", 3, './app/shopping/kuaijie/pay_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, 'pkcs8_rsa_private_key_2048.pem');
        $encyrptKey = $this->rsasign_public($key, 'rsa_public_key_2048.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $rts['pay_no'], 'signData' => $signData, 'tranCode' => 'IFP004', 'callBack' => $rts['pay_address']);
        //var_dump($postdata);
        //  $post_string = "encryptData=".$encryptData."&encryptKey=".$encyrptKey."&merchantId=102100000125&signData=".$signData."tranCode=IFP001&callBack=http://www.chm1688.com/m/zhifu.php";
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, 'pkcs8_rsa_private_key_2048.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        $xml_obj = simplexml_load_string($xmlData);
        $rt['oriReqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        error_log('[' . date('Y-m-d H:i:s') . ']快捷支付:' . "\n" . var_export($xml_obj, true). "\n\n", 3, './app/shopping/kuaijie/pay_request' . date('Y-m-d') . '.log');
        $this->set('rt', $rt);
        $this->set('rts', $rts);
        $this->set('uid', $uid);
        $this->set('card', $card);
        $this->title('收银台 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/kuaijie');
    }
    function ajax_getcode($data = array()) {
        $uid = $this->Session->read('User.uid');
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
	<head>
		<version>1.0.0</version>
		<merchantId>' . $data['merchantId'] . '</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP012</tranCode>
		<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
		<userId>' . $uid . '</userId>
		<oriReqMsgId>' . $data['oriReqMsgId'] . '</oriReqMsgId>
	</body>
</merchant>
';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
		
			error_log('[' . date('Y-m-d H:i:s') . ']快捷支付短信报文:' . "\n" . iconv('UTF-8', 'GBK', $xml_obj) . "\n\n", 3, './app/shopping/kuaijie/pay_code' . date('Y-m-d') . '.log');
			
			
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, 'pkcs8_rsa_private_key_2048.pem');
        $encyrptKey = $this->rsasign_public($key, 'rsa_public_key_2048.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $data['merchantId'], 'signData' => $signData, 'tranCode' => 'IFP012', 'callBack' => $data['pay_address']);
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, 'pkcs8_rsa_private_key_2048.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        $xml_obj = simplexml_load_string($xmlData);
        $respCode = $xml_obj->head->respCode;
        $respMsg = $xml_obj->head->respMsg;
		
		
			error_log('[' . date('Y-m-d H:i:s') . ']快捷支付短信验证码:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml_obj, true)) . "\n\n", 3, './app/shopping/kuaijie/pay_code' . date('Y-m-d') . '.log');
			
			
        if ($respCode == "000000") {
            echo "ok";
        } else {
            echo $respMsg;
        }
    }
	
	
	
	
	
	
    function kjpay_confirm() {
        $uid = $this->Session->read('User.uid');
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
<head>
		<version>1.0.0</version>
		<merchantId>' . $_POST['merchantId'] . '</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP013</tranCode>
		<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
		<userId>' . $uid . '</userId>
		<oriReqMsgId>' . $_POST['oriReqMsgId'] . '</oriReqMsgId>
		<validateCode>' . $_POST['p_code'] . '</validateCode>
	</body>
</merchant>
';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, 'pkcs8_rsa_private_key_2048.pem');
        $encyrptKey = $this->rsasign_public($key, 'rsa_public_key_2048.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $_POST['merchantId'], 'signData' => $signData, 'tranCode' => 'IFP013', 'callBack' => $_POST['pay_address']);
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, 'pkcs8_rsa_private_key_2048.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        $xml_obj = simplexml_load_string($xmlData);
        $rt['respType'] = $xml_obj->head->respType;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if (($rt['respType'] == 'S') && ($rt['respCode'] == '000000')) {
            //
            //					 $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
            //						$feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix()}user_level` WHERE lid=".$ni['user_rank']." LIMIT 1");
            //		    $feilv = unserialize($feilv);
            //		    $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix()}payment` WHERE pay_id=".$_POST['pay_id']." LIMIT 1");
            //			//计算手续费
            //			$koulv =  $feilv[$pay_fangshi];
            //
            //				   $dd = array();
            //            $dd['order_status'] = '2';
            //            $dd['pay_status'] = '1';
            //            $dd['pay_time'] = mktime();
            //			$dd['feilv'] = $koulv;
            //
            //            $this->App->update('goods_order_info', $dd, 'order_sn', $_POST['order_sn']);
            $sd = array('order_sn' => $_POST['order_sn'], 'status' => 1);
            if ($this->pay_successs_status($sd)) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付成功！');
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付失败！');
            }
        } else {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $rt['respMsg']);
        }
    }
    function bangka_list() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card` WHERE uid=" . $uid);
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('银行卡管理 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_list');
    }
    function bangka_list_h5() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_h5` WHERE uid=" . $uid);
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('银行卡管理 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_list_h5');
    }
	
	 function bangka_list_h5_hq() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_h5_hq` WHERE uid=" . $uid);
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('银行卡管理 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_list_h5_hq');
    }
	
	 function bangka_list_api() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid=" . $uid);
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('银行卡管理 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_list_api');
    }
    function curl_access($url, $postdata) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (strpos($url, 'https') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1); //高汇通那边的版本
            
        }
        $ret_data = curl_exec($ch);
        return $ret_data;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . $ret_data . "\n\n", 3, './app/shopping/bangka' . date('Y-m-d') . '.log');
        error_log('[' . date('Y-m-d H:i:s') . ']curl_errno:' . "\n" . curl_errno($ch) . "\n\n", 3, './app/shopping/bangka' . date('Y-m-d') . '.log');
        error_log('[' . date('Y-m-d H:i:s') . ']curl_error:' . "\n" . curl_error($ch) . "\n\n", 3, './app/shopping/bangka' . date('Y-m-d') . '.log');
        error_log('[' . date('Y-m-d H:i:s') . ']curl_getinfo:' . "\n" . var_export(curl_getinfo($ch), true) . "\n\n", 3, './app/shopping/bangka' . date('Y-m-d') . '.log');
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
    // function rsasign($data, $private_key_path) {
    //    $private_key = file_get_contents($private_key_path);
    //	$pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回
    //
    //	openssl_private_encrypt($data,$encrypted,$pi_key);//私钥加密
    //
    //    //base64编码
    //    $encrypted = base64_encode($encrypted);
    //    return  $encrypted;
    //}
    //
    // function rsasigns($data, $public_key_path) {
    //    $public_key = file_get_contents($public_key_path);
    //
    //	$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
    //
    //	openssl_public_encrypt($data,$sign,$pu_key);//公钥加密
    //
    //	//base64编码
    //    $sign = base64_encode($sign);
    //    return  $sign;
    //}
    //快捷API
    function hw($lid = 0, $pay_id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
        $url = "http://www.mbc.net.cn/UPpay/api.php?Order_No=" . $rt['order_sn'] . "&Amount=" . $rt['order_amount'] . "&Num=1&Return_Url=" . $rts['pay_address'] . "&Notify_Url=http://www.chm1688.com/m/wxpay/notify_url_hw_yb.php&Business_Type=Pay&PID=" . $rts['pay_no'] . "&KEY=" . $rts['pay_code'];
        error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/haiwai/haiwai_' . date('Y-m-d') . '.log');
        //$post_string = "Order_No=".$rt['order_sn']."&Amount=".$rt['order_amount']."&Num=1&Return_Url=".$rts['pay_address']."&Business_Type=Pay&PID=".$rts['pay_no']."&KEY=".$rts['pay_code'];
        //$this->request_by_curl("http://www.mbc.net.cn/UPpay/api.php",$post_string);
        Header("Location: " . $url);
        exit;
    }
    /**
     * Curl版本
     * 使用方法：
     * $post_string = "app=request&version=beta";
     * request_by_curl('http://facebook.cn/restServer.php',$post_string);
     */
    function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Jimmy's CURL Example beta");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    function wx($lid = 0, $pay_id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3` WHERE uid=" . $rt['user_id'] . " and pay_id=" . $pay_id . " LIMIT 1");
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
        if (!empty($sj3)) {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&appid=wxa1d2f0163a747532&bank_code=WECHAT&busi_code=FRONT_PAY&child_merchant_no=' . $sj3['merchantId'] . '&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            //   $signx = 'amount='.$rt['order_amount'].'&bank_code=WECHAT&busi_code=FRONT_PAY&child_merchant_no='.$sj3['merchantId'].'&currency_type=CNY&merchant_no='.$rts['pay_no'].'&notify_url='.$rts['pay_address'].'&order_no='.$rt['order_sn'].'&product_desc=订单号：'.$rt['order_sn'].'&product_name=订单号：'.$rt['order_sn'].'&return_url='.$rts['pay_address'].'&sett_currency_type=CNY&terminal_no='.$rts['pay_idt'].'&key='.$rts['pay_code'];
            $url = "https://epay.gaohuitong.com/backStageEntry.do?busi_code=FRONT_PAY&bank_code=WECHAT&merchant_no=" . $rts['pay_no'] . "&child_merchant_no=" . $sj3['merchantId'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY&appid=wxa1d2f0163a747532";
        } else {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=WECHAT&busi_code=FRONT_PAY&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            $url = "https://epay.gaohuitong.com:8443/backStageEntry.do?busi_code=FRONT_PAY&bank_code=WECHAT&merchant_no=" . $rts['pay_no'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        }
        $re = file_get_contents($url);
        if (!empty($sj3)) {
            // error_log('['.date('Y-m-d H:i:s').']字符串:'."\n".$signx."\n\n", 3, './app/shopping/yimayihu_wx_'.date('Y-m-d').'.log');
            error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/yimayihu_wx/yimayihu_wx_' . date('Y-m-d') . '.log');
            error_log('[' . date('Y-m-d H:i:s') . ']官方返回:' . "\n" . $re . "\n\n", 3, './app/shopping/yimayihu_wx/yimayihu_wx_' . date('Y-m-d') . '.log');
        }
        $xml = simplexml_load_string($re); //创建 SimpleXML对象
        $usernamme = $xml->sign;
        $comment = $xml->resp_desc;
        $merchant_no = $xml->merchant_no;
        $qr_code = $xml->qr_code;
        if (empty($qr_code)) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $comment);
            exit;
        }
        //echo $merchant_no;
        //		echo $qr_code;
        $this->set('rt', $rt);
        $this->set('qr_code', $qr_code);
        if (!defined(NAVNAME)) define('NAVNAME', "微信支付");
        $this->title('微信支付 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/weixin_pay');
        exit;
    }
    function wxs($lid = 0, $pay_id = 0) {
		
		
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
		
		 $user_bank_card_no = $this->App->findvar("SELECT bankaccountNo FROM `{$this->App->prefix() }user_sj1` WHERE uid=" . $rt['user_id'] . " LIMIT 1");
		 
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3` WHERE uid=" . $rt['user_id'] . " and pay_id=" . $pay_id . " LIMIT 1");
		
		
		
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
        if (!empty($sj3)) {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&app_id=wxa1d2f0163a747532&bank_code=PUBLICWECHAT&busi_code=PAY&child_merchant_no=' . $sj3['merchantId'] . '&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&user_bank_card_no='.$rt['openid'].'&key=' . $rts['pay_code']);
            //	echo $sign;
            //$url = "https://epay.gaohuitong.com:8443/backStageEntry.do?amount=100&bank_code=WECHAT&busi_code=FRONT_PAY&currency_type=CNY&merchant_no=102100000145&notify_url=http://www.chm1688.com/m/wxpay/notify_url_yl.php&order_no=wx00000123456&product_desc=%E5%9B%BE%E4%B9%A6&product_name=%E9%9A%90%E8%97%8F%E7%9A%84%E7%94%BB%E5%86%8C&sign=".$sign."&sett_currency_type=CNY&terminal_no=20000132";
            $url = "https://epay.gaohuitong.com:8443/entry.do?busi_code=PAY&bank_code=PUBLICWECHAT&child_merchant_no=" . $sj3['merchantId'] . "&merchant_no=" . $rts['pay_no'] . "&terminal_no=" . $rts['pay_idt'] . "&user_bank_card_no=".$rt['openid']."&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY&app_id=wxa1d2f0163a747532";
        } else {
            /*$product_desc = $rt['order_sn'];
             $product_name = $rt['order_sn'];*/
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=PUBLICWECHAT&busi_code=PAY&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            //	echo $sign;
            //$url = "https://epay.gaohuitong.com:8443/backStageEntry.do?amount=100&bank_code=WECHAT&busi_code=FRONT_PAY&currency_type=CNY&merchant_no=102100000145&notify_url=http://www.chm1688.com/m/wxpay/notify_url_yl.php&order_no=wx00000123456&product_desc=%E5%9B%BE%E4%B9%A6&product_name=%E9%9A%90%E8%97%8F%E7%9A%84%E7%94%BB%E5%86%8C&sign=".$sign."&sett_currency_type=CNY&terminal_no=20000132";
            $url = "https://epay.gaohuitong.com:8443/entry.do?busi_code=PAY&bank_code=PUBLICWECHAT&merchant_no=" . $rts['pay_no'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        }
        error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/yimayihu_wxgz/yimayihu_wxgz_' . date('Y-m-d') . '.log');
        header("Location: " . $url);
        exit;
    }
	
	function get_access_token(){
		
		
				//$rr = $this->get_appid_appsecret();
					
					$rr['appid'] = "wxa1d2f0163a747532";//$rr['appid'];
		$rr['appsecret'] = "dd27e75cfff18e03fe777c6c4bb7f48a";//$rr['appsecret'];
		
					$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$rr['appid'].'&secret='.$rr['appsecret'];
					$con = $this->curlGet($url);
					$json=json_decode($con);
					$rt = $json->access_token; //获取 access_token
					
					 return $rt;
		}
	
	function get_user_codes(){
		
		
		//$rr = $this->get_appid_appsecret();
		$appid = 'wxa1d2f0163a747532';//$rr['appid'];
		$appsecret = 'dd27e75cfff18e03fe777c6c4bb7f48a';//$rr['appsecret'];
		
		$thisurl = Import::basic()->thisurl();
		$thisurl = $this->remove_get_args($thisurl);
		
		 error_log('[' . date('Y-m-d H:i:s') . ']open返回:' . "\n".$thisurl."\n\n", 3, './app/shopping/open_' . date('Y-m-d') . '.log');


		
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($thisurl).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		
		error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/code_' . date('Y-m-d') . '.log');
		
		 header('location:'.$url);
		 //exit; //返回带code的URL
		
		}
		
		function remove_get_args($thisurl=''){
		 $rrr = explode('?',$thisurl);
		 $t2 = isset($rrr[1])&&!empty($rrr[1]) ? $rrr[1] : "";
		 $dd = array();
		 if(!empty($t2)){
			$rr2 = explode('&',$t2);
			if(!empty($rr2))foreach($rr2 as $v){
				$rr2 = explode('=',$v);
				if($rr2[0]=='from' || $rr2[0]=='isappinstalled'|| $rr2[0]=='code'|| $rr2[0]=='state') continue;
				$dd[] = $v;
			}
		 }
		 if(!empty($dd)){
		 	 $thisurl = $rrr[0].'?'.implode('&',$dd);
			 unset($dd);
		 }
		
		 return $thisurl;
	}
	
	
	function get_appid_appsecret(){
		
		$sql = "SELECT appid,appsecret,is_oauth,winxintype FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
					$rr = $this->App->findrow($sql);
					
					return $rr;
		}
		
			function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		if(empty($temp)) $temp = Import::crawler()->curl_get_con($url);
		return $temp;
	}
   
/*    function yinlian($lid = 0, $pay_id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
     
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);

        $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=ABCQBY&busi_code=PAY&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&user_bank_card_no=' . $rt['bank_no'] . '&key=' . $rts['pay_code']);
        $signx = 'amount=' . $rt['order_amount'] . '&bank_code=ABCQBY&busi_code=PAY&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&user_bank_card_no=' . $rt['bank_no'] . '&key=' . $rts['pay_code'];
        $url = "https://epay.gaohuitong.com/entry.do?bank_code=ABCQBY&busi_code=PAY&merchant_no=" . $rts['pay_no'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&user_bank_card_no=" . $rt['bank_no'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        
        error_log('[' . date('Y-m-d H:i:s') . ']签名:' . "\n" . $signx . "\n\n", 3, './app/shopping/yinlian_h5/yinlian_h5_' . date('Y-m-d') . '.log');
        error_log('[' . date('Y-m-d H:i:s') . ']报文:' . "\n" . $url . "\n\n", 3, './app/shopping/yinlian_h5/yinlian_h5_' . date('Y-m-d') . '.log');
        header("Location:" . $url); 
    }*/
	
	
	//银联一户一码
	    function yinlian($lid = 0, $pay_id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        	$sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj3` WHERE uid=".$rt['user_id']." and pay_id=".$pay_id." LIMIT 1");
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
    
       
        $sign = hash('SHA256','amount=' . $rt['order_amount'] . '&bank_code=CREDITQBY&busi_code=PAY&child_merchant_no='.$sj3['merchantId'].'&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&user_bank_card_no=' . $rt['bank_no'] . '&key=' . $rts['pay_code']);
       	                //   $signx = 'amount='.$rt['order_amount'].'&bank_code=WECHAT&busi_code=FRONT_PAY&child_merchant_no='.$sj3['merchantId'].'&currency_type=CNY&merchant_no='.$rts['pay_no'].'&notify_url='.$rts['pay_address'].'&order_no='.$rt['order_sn'].'&product_desc=订单号：'.$rt['order_sn'].'&product_name=订单号：'.$rt['order_sn'].'&return_url='.$rts['pay_address'].'&sett_currency_type=CNY&terminal_no='.$rts['pay_idt'].'&key='.$rts['pay_code'];
      
	  
	  	$url="https://epay.gaohuitong.com/entry.do?bank_code=CREDITQBY&busi_code=PAY&merchant_no=" . $rts['pay_no'] . "&child_merchant_no=".$sj3['merchantId']."&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&user_bank_card_no=" . $rt['bank_no'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
      
	       error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/yinlian_h5/yimayihu_h5_' . date('Y-m-d') . '.log');
          
    
        header("Location:" . $url);
	   
	
       
       
       
     
		
    }
	
	
	
    function zfb($lid = 0, $pay_id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3` WHERE uid=" . $rt['user_id'] . " and pay_id=" . $pay_id . " LIMIT 1");
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
        if (!empty($sj3)) {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=ALIPAY&busi_code=FRONT_PAY&child_merchant_no=' . $sj3['merchantId'] . '&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            $url = "https://epay.gaohuitong.com/backStageEntry.do?busi_code=FRONT_PAY&bank_code=ALIPAY&merchant_no=" . $rts['pay_no'] . "&child_merchant_no=" . $sj3['merchantId'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        } else {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=ALIPAY&busi_code=FRONT_PAY&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            $url = "https://epay.gaohuitong.com:8443/backStageEntry.do?busi_code=FRONT_PAY&bank_code=ALIPAY&merchant_no=" . $rts['pay_no'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        }
        $re = file_get_contents($url);
        if (!empty($sj3)) {
            // error_log('['.date('Y-m-d H:i:s').']字符串:'."\n".$signx."\n\n", 3, './app/shopping/yimayihu_wx_'.date('Y-m-d').'.log');
            error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/yimayihu_zfb/yimayihu_zfb_' . date('Y-m-d') . '.log');
            error_log('[' . date('Y-m-d H:i:s') . ']官方返回:' . "\n" . $re . "\n\n", 3, './app/shopping/yimayihu_zfb/yimayihu_zfb_' . date('Y-m-d') . '.log');
        }
        $xml = simplexml_load_string($re); //创建 SimpleXML对象
        $usernamme = $xml->sign;
        $comment = $xml->resp_desc;
        $merchant_no = $xml->merchant_no;
        $qr_code = $xml->qr_code;
        if (empty($qr_code)) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $comment);
            exit;
        }
		
		if($rt['supplier_id']){
			header("Location:".$qr_code );
			 exit;
		}else{
			$this->jump(ADMIN_URL . 'mycart.php?type=zhifubao&code='.$qr_code);
			 exit;
		}
        //echo $merchant_no;
        //		echo $qr_code;
       // $this->set('rt', $rt);
//        $this->set('qr_code', $qr_code);
//        if (!defined(NAVNAME)) define('NAVNAME', "支付宝支付");
//        $this->title('支付宝支付 - ' . $GLOBALS['LANG']['site_name']);
//        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
//        $this->template($mb . '/zhifubao_pay');
//        exit;
    }
	
	function zhifubao(){
		
		$qr_code = $_GET['code'];
		 $this->set('qr_code', $qr_code);
        if (!defined(NAVNAME)) define('NAVNAME', "支付宝支付");
        $this->title('支付宝支付 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/zhifubao_pay_url');
        exit;
		
		}
    //function zfb($lid){
    //
    //		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE order_id='$lid' LIMIT 1");
    //
    //				$pay = $this->_get_payinfo(8);
    //    $rts = unserialize($pay['pay_config']);
    //
    //	$order_amount = $rt['order_amount'];
    //	if(!($order_amount > 0)) exit;
    //	$order_amount = $order_amount*100;
    //
    //	$chars = $rts['pay_address'].$rts['pay_no'].$rts['pay_idt'].$rt['order_sn'].$order_amount.$rt['zifuchuan'].$rt['add_time'].$rts['pay_code'] ;
    //
    //
    //	$skey = strtoupper(md5($chars));
    //
    //
    //
    //
    //$data = $this->curl_post("http://demo.counect.com/vcupe/getPay.do", array("p" =>  $rts['pay_no'], "t" => $rt['add_time'],"r" =>$rt['zifuchuan'],"n" => $rts['pay_address'], "p0" => '', "p1" => $rts['pay_idt'], "p2" => $rt['order_sn'], "p3" => $order_amount, "s" => $skey  ));
    //
    //
    // $arr = json_decode($data,true);
    //
    // $qr_code = $arr['BODY'];
    //
    //  $this->set('rt',$rt);
    //		    $this->set('qr_code',$qr_code);
    //
    //		    if (!defined(NAVNAME))
    //            define('NAVNAME', "支付宝支付");
    //		 $this->title('支付宝支付 - ' . $GLOBALS['LANG']['site_name']);
    //
    //			 $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
    //        $this->template($mb . '/weixin_pay');
    //
    //
    //
    //		}
    //
    //
    //	function curl_post($url, $post) {
    //    $options = array(
    //        CURLOPT_RETURNTRANSFER => true,
    //        CURLOPT_HEADER         => false,
    //        CURLOPT_POST           => true,
    //        CURLOPT_POSTFIELDS     => $post,
    //    );
    //
    //    $ch = curl_init($url);
    //    curl_setopt_array($ch, $options);
    //    $result = curl_exec($ch);
    //    curl_close($ch);
    //    return $result;
    //}
	
	//京东支付
	function jd($lid = 0, $pay_id = 0) {
        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$lid' LIMIT 1");
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3` WHERE uid=" . $rt['user_id'] . " and pay_id=" . $pay_id . " LIMIT 1");
        $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
        if (!empty($sj3)) {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=JD_FRONT&busi_code=FRONT_PAY&child_merchant_no=' . $sj3['merchantId'] . '&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            $url = "https://epay.gaohuitong.com/backStageEntry.do?busi_code=FRONT_PAY&bank_code=JD_FRONT&merchant_no=" . $rts['pay_no'] . "&child_merchant_no=" . $sj3['merchantId'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        } else {
            $sign = hash('SHA256', 'amount=' . $rt['order_amount'] . '&bank_code=JD_FRONT&busi_code=FRONT_PAY&currency_type=CNY&merchant_no=' . $rts['pay_no'] . '&notify_url=' . $rts['pay_address'] . '&order_no=' . $rt['order_sn'] . '&product_desc=订单号：' . $rt['order_sn'] . '&product_name=订单号：' . $rt['order_sn'] . '&return_url=' . $rts['pay_address'] . '&sett_currency_type=CNY&terminal_no=' . $rts['pay_idt'] . '&key=' . $rts['pay_code']);
            $url = "https://epay.gaohuitong.com:8443/backStageEntry.do?busi_code=FRONT_PAY&bank_code=JD_FRONT&merchant_no=" . $rts['pay_no'] . "&terminal_no=" . $rts['pay_idt'] . "&order_no=" . $rt['order_sn'] . "&amount=" . $rt['order_amount'] . "&notify_url=" . urlencode($rts['pay_address']) . "&return_url=" . $rts['pay_address'] . "&product_name=订单号：" . $rt['order_sn'] . "&product_desc=订单号：" . $rt['order_sn'] . "&sign=" . $sign . "&currency_type=CNY&sett_currency_type=CNY";
        }
        $re = file_get_contents($url);
        if (!empty($sj3)) {
            // error_log('['.date('Y-m-d H:i:s').']字符串:'."\n".$signx."\n\n", 3, './app/shopping/yimayihu_wx_'.date('Y-m-d').'.log');
            error_log('[' . date('Y-m-d H:i:s') . ']参数:' . "\n" . $url . "\n\n", 3, './app/shopping/yimayihu_jingdong/yimayihu_jingdong_' . date('Y-m-d') . '.log');
            error_log('[' . date('Y-m-d H:i:s') . ']官方返回:' . "\n" . $re . "\n\n", 3, './app/shopping/yimayihu_jingdong/yimayihu_jingdong_' . date('Y-m-d') . '.log');
        }
        $xml = simplexml_load_string($re); //创建 SimpleXML对象
        $usernamme = $xml->sign;
        $comment = $xml->resp_desc;
        $merchant_no = $xml->merchant_no;
        $qr_code = $xml->qr_code;
        if (empty($qr_code)) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $comment);
            exit;
        }
        //echo $merchant_no;
        //		echo $qr_code;
        $this->set('rt', $rt);
        $this->set('qr_code', $qr_code);
        if (!defined(NAVNAME)) define('NAVNAME', "京东支付");
        $this->title('京东支付 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/jingdong_pay');
        exit;
    }
	
	
    function ylcheck($order_sn = "") {
        $order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : 0;
        $pay_id = isset($_GET['pay_id']) ? $_GET['pay_id'] : 0;
        if (empty($order_sn)) {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan');
            exit;
        }
        $pay = $this->action('shopping', '_get_payinfo', 3);
        $rts = unserialize($pay['pay_config']);
        $rt = $this->action('shopping', 'get_openid_AND_pay_info', $order_sn);
        $chars = "amount=" . $rt['order_amount'] . "&busi_code=PAY&currency_type=CNY&merchant_no=" . $rts['pay_no'] . "&notify_url=" . $rts['pay_address'] . "&order_no=" . $rt['order_sn'] . "&product_name=公众支付&return_url=" . $rts['pay_address'] . "&sett_currency_type=CNY&sign_type=SHA256&terminal_no=" . $rts['pay_idt'] . "&key=" . $rts['pay_code'];
        $rts['skey'] = hash('sha256', $chars);
        $this->set('rts', $rts);
        $this->set('rt', $rt);
        $this->set('pay_id', $pay_id);
        if ($pay_id == 3) {
            if (!defined(NAVNAME)) define('NAVNAME', "银联支付");
            $this->title('银联支付 - ' . $GLOBALS['LANG']['site_name']);
        } else if ($pay_id == 4) {
            if (!defined(NAVNAME)) define('NAVNAME', "微信支付");
            $this->title('微信支付 - ' . $GLOBALS['LANG']['site_name']);
        }
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/yilian');
    }
    //返佣缓存
    function _return_money($order_sn = '') {
        @set_time_limit(300); //最大运行时间
        //送佣金，找出推荐用户
        $pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        $parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
        $parent_uid2 = isset($pu['parent_uid2']) ? $pu['parent_uid2'] : 0; //分享者
        $parent_uid3 = isset($pu['parent_uid3']) ? $pu['parent_uid3'] : 0; //分享者
        $parent_uid4 = isset($pu['parent_uid4']) ? $pu['parent_uid4'] : 0; //分享者
        $daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
        $moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
        $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
        $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
        $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //用户配置信息
        $rts = $this->App->findrow($sql);
        if (!empty($order_sn)) {
            //计算每个产品的佣金
            $sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix() }goods_order` WHERE order_id='$order_id'";
            $moneys = $this->App->find($sql);
            $thismonth = date('Y-m-d', mktime());
            $thism = date('Y-m', mktime());
            $moeysall = 0;
            if (!empty($moneys)) foreach ($moneys as $row) {
                if ($row['takemoney1'] > 0) {
                    $moeysall+= $row['takemoney1'] * $row['goods_number'];
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
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$parent_uid' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_1'] < 101 && $rts['ticheng180_1'] > 0) {
                            $off = $rts['ticheng180_1'] / 100;
                        }
                    } elseif ($rank == '11') { //高级分销商
                        if ($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0) {
                            $off = $rts['ticheng180_h1_1'] / 100;
                        }
                    } elseif ($rank == '10') { //特权分销商
                        if ($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0) {
                            $off = $rts['ticheng180_h2_1'] / 100;
                        }
                    }
                    //}
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $parent_uid, 'level' => '1'));
                    }
                }
            }
            $moeys = 0;
            //二级返佣金
            if ($parent_uid2 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$parent_uid2' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_2'] < 101 && $rts['ticheng180_2'] > 0) {
                            $off = $rts['ticheng180_2'] / 100;
                        }
                    } elseif ($rank == '11') { //高级分销商
                        if ($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0) {
                            $off = $rts['ticheng180_h1_2'] / 100;
                        }
                    } elseif ($rank == '10') { //特权分销商
                        if ($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0) {
                            $off = $rts['ticheng180_h2_2'] / 100;
                        }
                    }
                    //}
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $parent_uid2, 'level' => '2'));
                    }
                }
            }
            $moeys = 0;
            //三级返佣金
            if ($parent_uid3 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$parent_uid3' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_3'] < 101 && $rts['ticheng180_3'] > 0) {
                            $off = $rts['ticheng180_3'] / 100;
                        }
                    } elseif ($rank == '11') { //高级分销商
                        if ($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0) {
                            $off = $rts['ticheng180_h1_3'] / 100;
                        }
                    } elseif ($rank == '10') { //特权分销商
                        if ($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0) {
                            $off = $rts['ticheng180_h2_3'] / 100;
                        }
                    }
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $parent_uid3, 'level' => '3'));
                    }
                }
            } //end if
            $moeys = 0;
            //四级返佣金
            if ($parent_uid4 > 0) {
                $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$parent_uid4' LIMIT 1";
                $rank = $this->App->findvar($sql);
                if ($rank != '1') { //不是普通会员
                    //$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
                    //$types = $this->App->findvar($sql);
                    $off = 0;
                    if ($rank == '12') { //普通分销商
                        if ($rts['ticheng180_4'] < 101 && $rts['ticheng180_4'] > 0) {
                            $off = $rts['ticheng180_4'] / 100;
                        }
                    } elseif ($rank == '11') { //高级分销商
                        if ($rts['ticheng180_h1_4'] < 101 && $rts['ticheng180_h1_4'] > 0) {
                            $off = $rts['ticheng180_h1_4'] / 100;
                        }
                    } elseif ($rank == '10') { //特权分销商
                        if ($rts['ticheng180_h2_4'] < 101 && $rts['ticheng180_h2_4'] > 0) {
                            $off = $rts['ticheng180_h2_4'] / 100;
                        }
                    }
                    $moeys = format_price($moeysall * $off);
                    if (!empty($moeys)) {
                        $this->App->insert('user_money_change_cache', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '刷卡返佣金', 'time' => mktime(), 'uid' => $parent_uid4, 'level' => '4'));
                    }
                }
            } //end if
            
        }
    }
    //快速支付
    function fastcheckout() {
        $oid = $_POST['order_id'];
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE pay_status = '0' AND order_id='$oid'";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            $this->jump(ADMIN_URL, 0, '非法支付提交！');
            exit;
        }
        $rts['pay_id'] = $rt['pay_id'];
        $rts['order_sn'] = $rt['order_sn'];
        $rts['order_amount'] = $rt['order_amount'];
        $rts['username'] = $orderdata['consignee'];
        $rts['logistics_fee'] = $rt['shipping_fee'];
        $sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix() }goods_order_info` AS ua";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg ON rg.region_id = ua.province";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg1 ON rg1.region_id = ua.city";
        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg2 ON rg2.region_id = ua.district WHERE ua.order_id='$oid' LIMIT 1";
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
        $json = Import::json();
        $id = $data['id'];
        $number = $data['number'];
        $shipping_id = $data['shipping_id'];
        $userress_id = $data['userress_id'];
        $maxnumber = $this->Session->read("cart.{$id}.goods_number");
        if ($number > $maxnumber) {
            $result = array('error' => 2, 'message' => "购买数量已经超过了库存，您最大只能购买:" . $maxnumber);
            die($json->encode($result));
        }
        //是否是赠品，如果是赠品，那么只能添加一件，不能重复添加
        $is_alone_sale = $this->Session->read("cart.{$id}.is_alone_sale");
        if (!empty($is_alone_sale)) {
            $this->Session->write("cart.{$id}.number", $number);
        }
        //end 赠品
        $uid = $this->Session->read('User.uid');
        $cartlist = $this->Session->read('cart');
        //返回总价
        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` LIMIT 1"; //配置信息
        $rts = $this->App->findrow($sql);
        $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $issubscribe = $this->App->findvar($sql);
        $guanzhuoff = $rts['guanzhuoff'];
        $address3off = $rts['address3off'];
        $address2off = $rts['address2off'];
        $prices = 0;
        $thisprice = 0;
        $off = 1;
        if ($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0) { //关注折扣
            $off = ($guanzhuoff / 100);
        }
        $counts = 0;
        foreach ($cartlist as $k => $row) {
            $counts+= $row['number'];
        }
        if ($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0) {
            $off = ($address2off / 100) * $off; //相对关注再折扣
            
        }
        if ($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0) {
            $off = ($address3off / 100) * $off; //相对关注再折扣
            
        }
        foreach ($cartlist as $k => $row) {
            $comd = array();
            $comd[] = format_price($row['pifa_price'] * $off);
            if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price'] > 0) { //促销价格
                $comd[] = $row['promote_price'];
            }
            if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price'] > 0) { //抢购价格
                $comd[] = $row['qianggou_price'];
            }
            $price = min($comd);
            $this->Session->write("cart.{$k}.price", $price);
            if ($id == $k) {
                $thisprice = $price;
            }
            $prices+= $price * $row['number'];
        }
        $prices = format_price($prices);
        unset($cartlist);
        //邮费
        $f = $this->ajax_jisuan_shopping(array('shopping_id' => $shipping_id, 'userress_id' => $userress_id), 'cart');
        $f = empty($f) ? '0' : $f;
        unset($cartlist);
        $result = array('error' => 0, 'message' => '1', 'prices' => $prices, 'thisprice' => $thisprice, 'freemoney' => $f);
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
            $sql = "SELECT discount FROM `{$this->App->prefix() }user_level` WHERE lid='$rank' LIMIT 1";
            $discount = $this->App->findvar($sql);
        }
        $cartlist = $this->Session->read('cart');
        $total = 0;
        if (!empty($cartlist)) {
            foreach ($cartlist as $row) {
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    if ($discount > 0) {
                        $comd[] = ($discount / 100) * $row['market_price'];
                    }
                    if ($row['shop_price'] > 0) { //普通会员价格
                        $comd[] = $row['shop_price']; //普通会员价格
                        
                    }
                } else {
                    $comd[] = $row['market_price'];
                }
                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) { //促销价格
                    $comd[] = $row['promote_price'];
                }
                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }
                //$onetotal = min($comd);
                $onetotal = $row['pifa_price'];
                $total+= ($row['number'] * $onetotal);
                //if($row['is_jifen_session']=='1'){
                $jifen_onetotal+= $row['number'] * $onetotal;
                //}
                
            }
        }
        unset($cartlist);
        //我的积分
        $sql = "SELECT SUM(points) FROM `{$this->App->prefix() }user_point_change` WHERE uid='$uid'";
        $mypoints = $this->App->findvar($sql);
        if ($is_confirm == 'true') {
            if ($mypoints >= $jifen_onetotal) {
                echo $total - $jifen_onetotal;
            } else {
                echo $total - $mypoints;
            }
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
            if ($tt == 'ajax') {
                die("请选择一个收货地址！");
            } else {
                return "0";
            }
        }
        if (!($shopping_id > 0)) {
            if ($tt == 'ajax') {
                die("请选择一个配送方式！");
            } else {
                return "0";
            }
        }
        $sql = "SELECT country,province,city,district FROM `{$this->App->prefix() }user_address` WHERE address_id='$userress_id'";
        $ids = $this->App->findrow($sql);
        if (empty($ids)) {
            if ($tt == 'ajax') {
                die("请先设置一个收货地址！");
            } else {
                return "请先设置一个收货地址！";
            }
        }
        $cartlist = $this->Session->read('cart');
        $items = 0;
        $weights = 0;
        if (!empty($cartlist)) {
            foreach ($cartlist as $row) {
                if ($row['supplier_id'] == $supplier_id) {
                    if ($row['is_shipping'] == '1' || $row['is_alone_sale'] == '0') continue;
                    $items+= $row['number'];
                    // $weights +=$row['goods_weight'];
                    $weights_each+= $row['goods_weight']; //总重量
                    //    $total +=$row['pifa_price'] * $row['number'];
                    $weights+= $row['number'] * $row['goods_weight'];
                }
            }
        }
        $weights = $weights * $items;
        if ($supplier_id > 0) {
            $sql = "SELECT * FROM `{$this->App->prefix() }supplier_shipping_area` WHERE shipping_id='$shopping_id'";
            $area_rt = $this->App->find($sql); //配送区域列表
            
        } else {
            $sql = "SELECT * FROM `{$this->App->prefix() }shipping_area` WHERE shipping_id='$shopping_id'";
            $area_rt = $this->App->find($sql); //配送区域列表
            
        }
        //   $sql = "SELECT * FROM `{$this->App->prefix()}shipping_area` WHERE shipping_id='$shopping_id'";
        $area_rt = $this->App->find($sql);
        if (!empty($area_rt)) {
            foreach ($area_rt as $row) {
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
                            if ($type == 'item') { //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0)) $weight_fee = '0.00';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        } elseif (in_array($ids['city'], $configure)) { //城市
                            if ($type == 'item') { //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0)) $weight_fee = '0';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        } elseif (in_array($ids['province'], $configure)) { //省
                            if ($type == 'item') { //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0)) $weight_fee = '0';
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $weight_fee);
                                    } else {
                                        return $weight_fee;
                                    }
                                }
                            }
                            break;
                        } elseif (in_array($ids['country'], $configure)) { //国家
                            if ($type == 'item') { //件计算
                                $zyoufei = $item_fee + (($items - 1) * $step_item_fee);
                                if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                if ($tt == 'ajax') {
                                    die($row['shipping_area_name'] . '+' . $zyoufei);
                                } else {
                                    return $zyoufei;
                                }
                            } elseif ($type == 'weight') { //重量计算
                                if ($weights > 500) {
                                    $zyoufei = $weight_fee + ((ceil(($weights - 500) / 500)) * $step_weight_fee);
                                    if ($zyoufei > $max_money && intval($max_money) > 0) $zyoufei = $max_money;
                                    if ($tt == 'ajax') {
                                        die($row['shipping_area_name'] . '+' . $zyoufei);
                                    } else {
                                        return $zyoufei;
                                    }
                                } else {
                                    if (!($weights > 0)) $weight_fee = '0';
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
    //删除购物车商品
    function ajax_delcart_goods($id = 0) {
        //if(empty($id)) return "";
        if (!empty($id)) {
            $cartlist = $this->Session->read('cart');
            if (isset($cartlist[$id])) {
                $this->Session->write("cart.{$id}", "");
            }
            unset($cartlist);
        }
        $uid = $this->Session->read('User.uid');
        //用户等级折扣
        $rt['discount'] = 100;
        $rank = $this->Session->read('User.rank');
        if ($rank > 0) {
            $sql = "SELECT discount FROM `{$this->App->prefix() }user_level` WHERE lid='$rank' LIMIT 1";
            $rt['discount'] = $this->App->findvar($sql);
        }
        $active = $this->Session->read('User.active');
        $goodslist = $this->Session->read('cart');
        $rt['goodslist'] = array();
        if (!empty($goodslist)) {
            foreach ($goodslist as $k => $row) {
                $rt['goodslist'][$k] = $row;
                $rt['goodslist'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
                $rt['goodslist'][$k]['goods_thumb'] = SITE_URL . $row['goods_thumb'];
                $rt['goodslist'][$k]['goods_img'] = SITE_URL . $row['goods_img'];
                $rt['goodslist'][$k]['original_img'] = SITE_URL . $row['original_img'];
                //求出实际价格
                $comd = array();
                if (!empty($uid) && $active == '1') {
                    $comd[] = $row['market_price'];
                    if ($rt['discount'] > 0) {
                        $comd[] = ($rt['discount'] / 100) * $row['market_price'];
                    }
                    if ($row['shop_price'] > 0) { //普通会员价格
                        $comd[] = $row['shop_price']; //普通会员价格
                        
                    }
                } else {
                    $comd[] = $row['market_price'];
                }
                if ($row['is_promote'] == '1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) { //促销价格
                    $comd[] = $row['promote_price'];
                }
                if ($row['is_qianggou'] == '1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) { //抢购价格
                    $comd[] = $row['qianggou_price'];
                }
                $onetotal = min($comd);
                if (intval($onetotal) <= 0) $onetotal = $row['market_price'];
                $total+= ($row['number'] * $onetotal); //总价格
                
            }
            unset($goodslist);
        }
        //赠品类型
        $fn = SYS_PATH . 'data/goods_spend_gift.php';
        $spendgift = array();
        if (file_exists($fn) && is_file($fn)) {
            include_once ($fn);
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
            foreach ($minspend as $t => $val) { //已最高消费赠品为准
                if ($total >= $val) {
                    $type = $t; //赠品等级
                    break;
                }
            }
            unset($minspend);
            //赠品
            $rt['gift_goods_ids'] = array();
            if ($type > 0) {
                $sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix() }goods_gift` AS tb1";
                $sql.= " LEFT JOIN `{$this->App->prefix() }goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
                $sql.= " WHERE (tb2.is_alone_sale='0' OR tb2.is_alone_sale IS NOT NULL) AND tb2.is_on_sale='1' tb2.is_check='1' AND AND tb2.is_delete='0' AND tb1.type='$type'";
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
        $this->Session->write("cart", null);
        $this->Session->write('useradd', null);
        $this->jump(ADMIN_URL);
        exit;
    }
    function get_dianpus($dd) {
        foreach ($dd as $k => $row) {
            $cids[] = $row['supplier_id'];
        }
        return $cids;
    }
    function ajax_get_suppname($data = array()) {
        $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : 0;
        if ($supplier_id == 0) {
            return "网店自营";
        }
        if ($supplier_id > 0) {
            $sql = "SELECT site_name FROM `{$this->App->prefix() }supplier_systemconfig` WHERE supplier_id=" . $supplier_id . " LIMIT 1";
            //echo $sql;
            $info = $this->App->findrow($sql);
            return $info['site_name'];
        }
    }
    function get_goodslist($oid) {
        $goodslist = array();
        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order` WHERE order_id='" . $oid . "' ORDER BY goods_id";
        $info = $this->App->find($sql);
        //   echo $sql;
        foreach ($info as $row) {
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
    function get_order_rebate($suppid) {
        $spkey = intval($suppid);
        if ($spkey <= 0) {
            return 0;
        }
        $sql = "select rebate_id, rebate_paytime_start, rebate_paytime_end from `{$this->App->prefix() }supplier_rebate` where supplier_id='$spkey' and is_pay_ok=0 order by rebate_id desc limit 0,1";
        $row = $this->App->findrow($sql);
        $nowtime = time();
        if ($nowtime >= $row['rebate_paytime_start'] && $nowtime <= $row['rebate_paytime_end']) {
            $rebate_id = $row['rebate_id'];
        } else {
            $kkk = 'yes';
            while ($kkk == 'yes') {
                $this->insert_id_rebate($spkey);
                $sql2 = "select rebate_id, rebate_paytime_start, rebate_paytime_end from `{$this->App->prefix() }supplier_rebate` where supplier_id='$spkey' and is_pay_ok=0 order by rebate_id desc limit 0,1";
                $row2 = $this->App->findrow($sql2);
                if ($nowtime >= $row2['rebate_paytime_start'] && $nowtime <= $row2['rebate_paytime_end']) {
                    $rebate_id = $row2['rebate_id'];
                    $kkk = 'no';
                }
            }
        }
        return $rebate_id;
    }
    function insert_id_rebate($supplier_id) {
        $sql = "select supplier_rebate_paytime from `{$this->App->prefix() }supplier` where supplier_id='$supplier_id'";
        $supplier_rebate_paytime = $this->App->findvar($sql);
        $sql = "select rebate_paytime_start, rebate_paytime_end from `{$this->App->prefix() }supplier_rebate` where supplier_id= '$supplier_id' and is_pay_ok=0 order by rebate_id DESC LIMIT 0,1";
        $row = $this->App->findrow($sql);
        if (!$row['rebate_paytime_start']) {
            $rebate_paytime_start = $this->local_mktime(0, 0, 0, $this->local_date('m'), $this->local_date('d'), $this->local_date('Y'));
        }
        if (!$row['rebate_paytime_end']) {
            switch ($supplier_rebate_paytime) {
                case '1':
                    $rebate_paytime_end = $this->local_strtotime("this Sunday") + 24 * 60 * 60 - 1;
                break;
                case '2':
                    $rebate_paytime_end = $this->local_mktime(23, 59, 59, $this->local_date("m"), $this->local_date("t"), $this->local_date("Y"));
                break;
                case '3':
                    if ($this->local_date("m") == '1' || $this->local_date("m") == '2' || $this->local_date("m") == '3') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 3, 31, $this->local_date("Y"));
                    } elseif ($this->local_date("m") == '4' || $this->local_date("m") == '5' || $this->local_date("m") == '6') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 6, 30, $this->local_date("Y"));
                    } elseif ($this->local_date("m") == '7' || $this->local_date("m") == '8' || $this->local_date("m") == '9') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 9, 30, $this->local_date("Y"));
                    } elseif ($this->local_date("m") == '10' || $this->local_date("m") == '11' || $this->local_date("m") == '12') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 12, 31, $this->local_date("Y"));
                    }
                break;
                case '4':
                    $rebate_paytime_end = $this->local_mktime(23, 59, 59, 12, 31, $this->local_date("Y"));
                break;
            }
        }
        if ($row['rebate_paytime_start'] && $row['rebate_paytime_end']) {
            $rebate_paytime_start = $row['rebate_paytime_end'] + 1;
            switch ($supplier_rebate_paytime) {
                case '1':
                    $rebate_paytime_end = $row['rebate_paytime_end'] + 24 * 60 * 60 * 7;
                break;
                case '2':
                    $rebate_paytime_end = $this->local_mktime(23, 59, 59, $this->local_date("m", $rebate_paytime_start), $this->local_date("t", $rebate_paytime_start), $this->local_date("Y", $rebate_paytime_start));
                break;
                case '3':
                    if ($this->local_date("m", $rebate_paytime_start) == '1' || $this->local_date("m") == '2' || $this->local_date("m") == '3') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 3, 31, $this->local_date("Y"));
                    } elseif ($this->local_date("m") == '4' || $this->local_date("m") == '5' || $this->local_date("m") == '6') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 6, 30, $this->local_date("Y"));
                    } elseif ($this->local_date("m") == '7' || $this->local_date("m") == '8' || $this->local_date("m") == '9') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 9, 30, $this->local_date("Y"));
                    } elseif ($this->local_date("m") == '10' || $this->local_date("m") == '11' || $this->local_date("m") == '12') {
                        $rebate_paytime_end = $this->local_mktime(23, 59, 59, 12, 31, $this->local_date("Y"));
                    }
                break;
                case '4':
                    $rebate_paytime_end = $this->local_mktime(23, 59, 59, 12, 31, $this->local_date("Y"));
                break;
            }
        }
        $sql = "insert into `{$this->App->prefix() }supplier_rebate`  (rebate_paytime_start, rebate_paytime_end, supplier_id) value('$rebate_paytime_start', '$rebate_paytime_end', '$supplier_id') ";
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
    function local_mktime($hour = NULL, $minute = NULL, $second = NULL, $month = NULL, $day = NULL, $year = NULL) {
        $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];
        /**
         * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
         * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
         *
         */
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
    function local_strtotime($str) {
        $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];
        /**
         * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
         * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
         *
         */
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
    function local_date($format, $time = NULL) {
        $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];
        if ($time === NULL) {
            $time = time();
        } elseif ($time <= 0) {
            return '';
        }
        $time+= ($timezone * 3600);
        return date($format, $time);
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
    function pay_shengji() {
        $uid = $this->Session->read('User.uid');
        $rt['uid'] = $uid;
        $rt['id'] = $_GET['id'];
        $rt['amount'] = $this->App->findvar("SELECT price FROM `{$this->App->prefix() }cx_baoming` WHERE id=" . $_GET['id'] . " LIMIT 1");
        $sql = "SELECT fenrun,yongjin,tuiguang,yinlian,yinlian_h5,weixin,haiwai,jingdong,zhifubao FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";
        $row = $this->App->findrow($sql);
        $tuiguang_money = $row['yilian'] + $row['weixin'] + $row['haiwai'] + $row['jingdong'] + $row['zhifubao'];
        $this->set('rt', $rt);
        $this->set('row', $row);
        $this->title('升级扣费通道选择页面 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/shengji_pay');
    }
    //
    //    function ajax_money($data = array()) {
    //
    //		$keyword = $data['keys'];
    //		$amount = $data['amount'];
    //        $uid = $this->Session->read('User.uid');
    //
    //
    //                $sql = "SELECT ".$keyword." FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
    //                $row = $this->App->findvar($sql);
    //
    //
    //     if($row < $amount){
    //
    //            echo "您的余额不足，请充值";
    //	 }
    //        exit;
    //    }

    //2018/03/16 升级达人 微信支付 
    function pay_sj() {
        $uid = $this->Session->read('User.uid');
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $pay = $_POST['pay'];
            $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
            $s = "WHERE id='$id'";
            $sql = "SELECT * FROM `{$this->App->prefix() }cx_baoming` {$s} ORDER BY id DESC LIMIT 1";
            $rt['pinfo'] = $this->App->findrow($sql);
            if ($rank >= $rt['pinfo']['rank_id']) {
                $this->jump(ADMIN_URL . "user.php?act=baoming", 0, '您的级别高于当前级别，您可以向更高级别进军了！');
                exit;
            }
			
			$user_account = $this->App->findvar("SELECT ".$pay." FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
			// if($rt['pinfo']['price'] > $user_account){
			// 	 $this->jump(ADMIN_URL . "user.php?act=baoming", 0, '您的余额不足，请先充值！');
   //              exit;
			// 	}
			
            $price = $rt['pinfo']['price'];
            $on = date('Y', mktime()) . mktime();
            $dd = array();
            $dd['bid'] = $id;
            $dd['order_sn'] = $on;
            $dd['user_id'] = $uid;
            $dd['order_amount'] = $price;
            $dd['add_time'] = mktime();
			$dd['key'] = $pay;
            if ($this->App->insert('cx_baoming_order', $dd)) {
                echo (ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$dd['order_sn']);
                exit;
                // $money = - $price;
                // $sql = "UPDATE `{$this->App->prefix() }user` SET `" . $pay . "` = `" . $pay . "`+$money WHERE user_id = '$uid'";
                // $this->App->query($sql);
				 if ($pay == "yongjin") {
                    $payname = "佣金支付";
                }
				 if ($pay == "fenrun") {
                    $payname = "分润支付";
                }
				
				 if ($pay == "tuiguang") {
                    $payname = "升级奖励支付";
                }
				
                if ($pay == "yinlian") {
                    $payname = "银联商旅类支付";
                }
				if ($pay == "yinlian_h5") {
                    $payname = "银联缴费类支付";
                }
                if ($pay == "weixin") {
                    $payname = "微信支付";
                }
                if ($pay == "baidu") {
                    $payname = "百度支付";
                }
                if ($pay == "jingdong") {
                    $payname = "京东支付";
                }
                if ($pay == "duanxin") {
                    $payname = "短信支付";
                }
				 if ($pay == "zhifubao") {
                    $payname = "支付宝支付";
                }
                $sd = array();
                $sd = array('order_sn' => $dd['order_sn'], 'status' => 1);
                if ($this->baoming_pay_successs_tatus($dd['order_sn'])) {
                    $sd = array();
                    $thismonth = date('Y-m-d', mktime());
                    $thism = date('Y-m', mktime());
                    $sd['time'] = mktime();
                    $sd['changedesc'] = $payname;
                    $sd['money'] = $money;
                    $sd['uid'] = $uid;
                    $sd['buyuid'] = $uid;
                    $sd['order_sn'] = $dd['order_sn'];
                    $sd['thismonth'] = $thismonth;
                    $sd['thism'] = $thism;
                    $sd['type'] = '3';
                    $this->App->insert('user_money_change', $sd);
                    unset($sd);
                    $this->jump(ADMIN_URL . 'user.php?act=baoming', 0, '已成功支付');
                    exit;
                } else {
                    $this->jump(ADMIN_URL . 'user.php?act=baoming', 0, '意外错误！');
                    exit;
                }
            }
        }
    }
    function update_user_bank_sj($arr = array()) {
        if (!empty($arr['key'])) {
            if (($arr['key'] == "haiwai") || ($arr['key'] == "yinlian") || ($arr['key'] == "fenrun") || ($arr['key'] == "tuiguang") || ($arr['key'] == "yongjin")) {
                echo "success";
                exit;
            }
        }
        $uid = $arr['uid'];
        $sj1 = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_sj1` WHERE uid=" . $uid . " limit 1");
        if ($sj1 > 0) {
            $this->sj2($arr);
            exit;
        }
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        //if($uid == 42){
        $card['uid'] = $uid;
        $card['merchantName'] = $data['uname'];
        $card['shortName'] = $data['uname'];
        $card['city'] = 5810;
        $card['merchantAddress'] = "广东省广州市天河区华夏路";
        $card['servicePhone'] = $data['mobile'];
        // $card['orgCode'] = $data['orgCode'];
        $card['merchantType'] = "01";
        $card['category'] = 4816;
        $card['corpmanName'] = $data['uname'];
        $card['corpmanId'] = $data['idcard'];
        $card['corpmanPhone'] = $data['mobile'];
        $card['corpmanMobile'] = $data['mobile'];
        // $card['corpmanEmail'] = $data['corpmanEmail'];
        $card['bankCode'] = $bank['code'];
        $card['bankName'] = $bank['name'];
        $card['bankaccountNo'] = $data['banksn'];
        $card['bankaccountName'] = $data['uname'];
        $card['autoCus'] = 0;
        // $card['remark'] = $data['remark'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
				<merchant>
					  <head>
						  <version>1.0.0</version>
						  <agencyId>' . $pay['pay_no'] . '</agencyId>
						  <msgType>01</msgType>
						  <tranCode>100001</tranCode>
						  <reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
						  <reqDate>' . date('Ymdhis', time()) . '</reqDate>
					  </head>
					  <body>
							<merchantName>' . $card['merchantName'] . '</merchantName>
							<shortName>' . $card['shortName'] . '</shortName>
							<city>' . $card['city'] . '</city>
							<merchantAddress>' . $card['merchantAddress'] . '</merchantAddress>
							<servicePhone>' . $card['servicePhone'] . '</servicePhone>
							<merchantType>' . $card['merchantType'] . '</merchantType>
							<category>' . $card['category'] . '</category>
							<corpmanName>' . $card['corpmanName'] . '</corpmanName>
							<corpmanId>' . $card['corpmanId'] . '</corpmanId>
							<corpmanPhone>' . $card['corpmanPhone'] . '</corpmanPhone>
							<corpmanMobile>' . $card['corpmanMobile'] . '</corpmanMobile>
							<bankCode>' . $card['bankCode'] . '</bankCode>
							<bankName>' . $card['bankName'] . '</bankName>
							<bankaccountNo>' . $card['bankaccountNo'] . '</bankaccountNo>
							<bankaccountName>' . $card['bankaccountName'] . '</bankaccountName>
							<autoCus>' . $card['autoCus'] . '</autoCus>
					  </body>
				</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/sj1/sj_1_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153990220_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100001');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/basicInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/sj1/sj_1_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153990220.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/sj1/sj_1_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if ($rt['respCode'] == "000000") {
            $this->App->insert('user_sj1', $card);
            $this->sj2($arr);
        } else {
            echo $rt['respMsg'];
        }
        //var_export($rt);
        
    }
    function sj2($arr = array()) {
        $uid = $arr['uid'];
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1` WHERE uid=" . $uid . " limit 1");
        $sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2` WHERE uid=" . $uid . " limit 1");
        if (!empty($sj2) && ($sj2['bankaccountNo'] == '99999999999999999999')) {
            $old_bank = array();
            $old_bank['uid'] = $uid;
            $old_bank['pay_id'] = $arr['pay_id'];
            $old_bank['merchantId'] = $sj1['corpmanMobile'];
            $old_bank['bankaccountNo'] = $sj2['bankaccountNo'];
            $this->delete_bankno_old($old_bank);
        } else {
            if (!empty($sj2) && ($sj2['bankaccountNo'] == $data['banksn'])) {
                $this->sj3($arr);
                exit;
            }
        }
        //删除旧卡
        if (!empty($sj2) && ($sj2['bankaccountNo'] != $data['banksn'])) {
            $old_bank = array();
            $old_bank['uid'] = $uid;
            $old_bank['pay_id'] = $arr['pay_id'];
            $old_bank['merchantId'] = $sj1['corpmanMobile'];
            $old_bank['bankaccountNo'] = $sj2['bankaccountNo'];
            $this->delete_bankno_old($old_bank);
        }
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $card['uid'] = $uid;
        $card['merchantId'] = $sj1['corpmanMobile'];
        $card['bankCode'] = $bank['code'];
        $card['bankaccProp'] = 0;
        $card['name'] = $data['uname'];
        $card['bankaccountNo'] = $data['banksn'];
        $card['bankaccountType'] = 1;
        $card['certCode'] = 1;
        $card['certNo'] = $data['idcard'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
		<merchant>
			<head>
			<version>1.0.0</version>
			<agencyId>' . $pay['pay_no'] . '</agencyId>
			<msgType>01</msgType>
			<tranCode>100002</tranCode>
			<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
			<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			<merchantId>' . $card['merchantId'] . '</merchantId>
			<bankCode>' . $card['bankCode'] . '</bankCode>
			<bankaccProp>' . $card['bankaccProp'] . '</bankaccProp>
			<name>' . $card['name'] . '</name>
			<bankaccountNo>' . $card['bankaccountNo'] . '</bankaccountNo>
			<bankaccountType>' . $card['bankaccountType'] . '</bankaccountType>
			<certCode>' . $card['certCode'] . '</certCode>
			<certNo>' . $card['certNo'] . '</certNo>
			</body>
		</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/sj2/sj_2_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153990220_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100002');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/bankInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/sj2/sj_2_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153990220.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/sj2/sj_2_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if ($rt['respCode'] == "000000") {
            if (!empty($sj2)) {
                $this->App->update('user_sj2', $card, 'uid', $uid);
            } else {
                $this->App->insert('user_sj2', $card);
            }
            $this->sj3($arr);
        } else {
            echo $rt['respMsg'];
        }
    }
    function sj3($arr = array()) {
		
		if($arr['pay_id'] == 3){
			 echo "success";
                exit;
			}
        $uid = $arr['uid'];
        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1` WHERE uid=" . $uid . " limit 1");
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3` WHERE uid=" . $uid . " and pay_id =" . $arr['pay_id'] . "  limit 1");
        //  if($sj3 > 0){
        //					  echo "sdsdfdfed";
        //					   exit;
        //					  }
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
        //费率单独设置
        $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
        $feilv = unserialize($feilv);
        $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $arr['pay_id'] . " LIMIT 1");
        //计算手续费
        $koulv = $feilv[$pay_fangshi] / 100;
        $card['uid'] = $uid;
        $card['pay_id'] = $arr['pay_id'];
        $card['merchantId'] = $sj1['corpmanMobile'];
        if (!empty($sj3)) {
            if ($sj3['futureRateValue'] != $koulv) {
                $card['handleType'] = 1;
            } else {
                echo "success";
                exit;
            }
        } else {
            $card['handleType'] = 0;
        }
        $card['cycleValue'] = 2; //结算周期 D+0
        if ($arr['pay_id'] == 12) {
            $card['busiCode'] = "B00107";
        } else if ($arr['pay_id'] == 13) {
            $card['busiCode'] = "B00109";
        } else if ($arr['pay_id'] == 17 || $arr['pay_id'] == 3 ) {
            $card['busiCode'] = "B00108";
        } else if ($arr['pay_id'] == 15) {
            $card['busiCode'] = "B00114";
        }
		else if ($arr['pay_id'] == 18) {
            $card['busiCode'] = "B00123";
        }
        $card['futureRateType'] = 1; //费率类型 百分比
        $card['futureRateValue'] = $koulv;
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>' . $pay['pay_no'] . '</agencyId>
				<msgType>01</msgType>
				<tranCode>100003</tranCode>
				<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
				<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			  <merchantId>' . $card['merchantId'] . '</merchantId>
			  <handleType>' . $card['handleType'] . '</handleType>
			  <cycleValue>' . $card['cycleValue'] . '</cycleValue>
			<busiList>
				<busiCode>' . $card['busiCode'] . '</busiCode>
				<futureRateType>' . $card['futureRateType'] . '</futureRateType>
				<futureRateValue>' . $card['futureRateValue'] . '</futureRateValue>
			</busiList>
			</body>
	</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/sj3/sj_3_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153990220_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100003');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/sj3/sj_3_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153990220.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/sj3/sj_3_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if ($rt['respCode'] == "000000") {
            if ($card['handleType'] == 0) {
                $this->App->insert('user_sj3', $card);
            } else {
                $sql = "UPDATE `{$this->App->prefix() }user_sj3` SET `uid` = " . $card['uid'] . ",`pay_id` = " . $card['pay_id'] . ",`merchantId` = '" . $card['merchantId'] . "',`cycleValue` = " . $card['cycleValue'] . ",`busiCode` = '" . $card['busiCode'] . "',`futureRateType` = " . $card['futureRateType'] . ",`futureRateValue` = " . $card['futureRateValue'] . ",`handleType` = " . $card['handleType'] . "  WHERE `uid` = " . $card['uid'] . " and `pay_id` = " . $card['pay_id'];
                $this->App->query($sql);
            }
            echo "success";
        } else {
            echo $rt['respMsg'];
        }
    }
    function pl_sj3() {
        $pl_sj3 = array();
        $pl_sj3 = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_sj3` ");
        foreach ($pl_sj3 as $k => $row) {
            if ($row['futureRateValue'] < 0.1) {
                $arr = array();
                $arr['uid'] = $row['uid'];
                $arr['pay_id'] = $row['pay_id'];
                $this->pls_sj3($arr);
            }
        }
    }
    function pls_sj3($arr = array()) {
        $uid = $arr['uid'];
        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1` WHERE uid=" . $uid . " limit 1");
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3` WHERE uid=" . $uid . " and pay_id =" . $arr['pay_id'] . "  limit 1");
        //  if($sj3 > 0){
        //					  echo "sdsdfdfed";
        //					   exit;
        //					  }
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
        //费率单独设置
        $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
        $feilv = unserialize($feilv);
        $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $arr['pay_id'] . " LIMIT 1");
        //计算手续费
        $koulv = $feilv[$pay_fangshi] / 100;
        $card['uid'] = $uid;
        $card['pay_id'] = $arr['pay_id'];
        $card['merchantId'] = $sj1['corpmanMobile'];
        $card['handleType'] = 1;
        $card['cycleValue'] = 2; //结算周期 D+0
        if ($arr['pay_id'] == 12) {
            $card['busiCode'] = "B00107";
        } else if ($arr['pay_id'] == 13) {
            $card['busiCode'] = "B00109";
        } else if ($arr['pay_id'] == 14) {
            $card['busiCode'] = "B00108";
        } else if ($arr['pay_id'] == 15) {
            $card['busiCode'] = "B00114";
        }
        $card['futureRateType'] = 1; //费率类型 百分比
        $card['futureRateValue'] = $koulv;
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>' . $pay['pay_no'] . '</agencyId>
				<msgType>01</msgType>
				<tranCode>100003</tranCode>
				<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
				<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			  <merchantId>' . $card['merchantId'] . '</merchantId>
			  <handleType>' . $card['handleType'] . '</handleType>
			  <cycleValue>' . $card['cycleValue'] . '</cycleValue>
			<busiList>
				<busiCode>' . $card['busiCode'] . '</busiCode>
				<futureRateType>' . $card['futureRateType'] . '</futureRateType>
				<futureRateValue>' . $card['futureRateValue'] . '</futureRateValue>
			</busiList>
			</body>
	</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/sj_3_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153990220_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100003');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/sj_3_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153990220.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/sj_3_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if ($rt['respCode'] == "000000") {
            if ($card['handleType'] == 0) {
                $this->App->insert('user_sj3', $card);
            } else {
                $sql = "UPDATE `{$this->App->prefix() }user_sj3` SET `uid` = " . $card['uid'] . ",`pay_id` = " . $card['pay_id'] . ",`merchantId` = '" . $card['merchantId'] . "',`cycleValue` = " . $card['cycleValue'] . ",`busiCode` = '" . $card['busiCode'] . "',`futureRateType` = " . $card['futureRateType'] . ",`futureRateValue` = " . $card['futureRateValue'] . ",`handleType` = " . $card['handleType'] . "  WHERE `uid` = " . $card['uid'] . " and `pay_id` = " . $card['pay_id'];
                $this->App->query($sql);
            }
            echo "success";
        } else {
            echo $rt['respMsg'];
        }
    }
    function add_pay_record($data) {
        $recorddata = array();
        $recorddata['pay_result'] = $data['pay_result'];
        $recorddata['pay_time'] = $data['pay_time'];
        $recorddata['order_no'] = $data['order_no'];
        $recorddata['pay_no'] = $data['pay_no'];
        $recorddata['amount'] = $data['amount'];
		 $recorddata['sign'] = $data['sign'];
		//  $recorddata['sign_local'] = $data['sign_local'];
		$recorddata['time'] = $data['time'];
		
		$record_num = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_pay_record` WHERE order_no='".$recorddata['order_no']."'");
		
		if(!$record_num){
			 $this->App->insert('user_pay_record', $recorddata);
			 
			 $sd = array('order_sn' => $recorddata['order_no'], 'status' => 1);
             $this->pay_successs_status_api($sd);
		}
    }
	
	
	 function query_pay_record($order_no) {
      
	  
	   $record_num = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_pay_record` WHERE order_no='$order_no'");
        if ($record_num > 1 || $record_num == 0) {
			 return false;
		}
		if($record_num == 1){
			return true;
			}
       
    }
	
	function delete_pay_record($order_no) {
      
	  
	 $this->App->delete('user_pay_record', 'order_no', $order_no);
      
       
    }
	
	
	 function add_pay_record_instead($data) {
        $recorddata = array();
        $recorddata['pay_result'] = $data['pay_result'];
		$recorddata['errCodeDes'] = $data['errCodeDes'];
        $recorddata['pay_time'] = $data['pay_time'];
        $recorddata['order_no'] = $data['order_no'];
        $recorddata['pay_no'] = $data['pay_no'];
        $recorddata['amount'] = $data['amount'];
		 $recorddata['sign'] = $data['sign'];
		//  $recorddata['sign_local'] = $data['sign_local'];
		$recorddata['time'] = $data['time'];
		
		$record_num = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_pay_record_instead` WHERE order_no='".$recorddata['order_no']."'");
		
		if(!$record_num){
			 $this->App->insert('user_pay_record_instead', $recorddata);
			 
			 $sd = array('order_sn' => $recorddata['order_no'], 'status' => 1, 'orderdesc' => $recorddata['errCodeDes']);
             $this->pay_successs_status_instead($sd);
		}
    }
	
	
	 function query_pay_record_instead($order_no) {
      
	  
	   $record_num = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_pay_record_instead` WHERE order_no='$order_no'");
        if ($record_num > 1 || $record_num == 0) {
			 return false;
		}
		if($record_num == 1){
			return true;
			}
       
    }
	
	
	 //支付成功改变支付状态
    function pay_successs_status_instead($rt = array()) {
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
            $shengyu = sprintf("%.2f",substr(sprintf("%.3f", $sy_money), 0, -1));
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
           // $mone = array();
//            if (!empty($sendrt_money)) foreach ($sendrt_money as $mone) {
//                $this->action('api', 'send', array('openid' => $mone['wecha_id'], 'appid' => '', 'appsecret' => '', 'nickname' => $mone['nickname'], 'money' => $mone['money'], 'order_sn' => $mone['order_sn']), $mone['type']);
//            }
            unset($sendrt_money);
        } //end if
        return true;
    }
	
	
	
	
    function delete_bankno_old($old_bank) {
        $rts = $this->_get_payinfo($old_bank['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $card['merchantId'] = $old_bank['merchantId'];
        $card['handleType'] = 1;
        $card['bankaccountNo'] = $old_bank['bankaccountNo'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
		<merchant>
			<head>
			<version>1.0.0</version>
			<agencyId>' . $pay['pay_no'] . '</agencyId>
			<msgType>01</msgType>
			<tranCode>100002</tranCode>
			<reqMsgId>wx' . date('Ymdhis', time()) . $old_bank['uid'] . '</reqMsgId>
			<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			<merchantId>' . $card['merchantId'] . '</merchantId>
			<handleType>' . $card['handleType'] . '</handleType>
			<bankaccountNo>' . $card['bankaccountNo'] . '</bankaccountNo>
			</body>
		</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/delete_bank/delete_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153990220_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100002');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/bankInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/delete_bank/delete_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153990220.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/delete_bank/delete_' . date('Y-m-d') . '.log');
    }
	
	
	function wx_pay($iid, $pay_id){
	        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_id='$iid' LIMIT 1");
			$wecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=".$rt['user_id']." LIMIT 1");
            $mch_trade_id = $rt['order_sn'];
	        $nonce_str =  $this->createNoncestr();
			$total_fee = $rt['order_amount']*100;
			
			 $pay = $this->_get_payinfo($pay_id);
        $rts = unserialize($pay['pay_config']);
 
    $sign = strtoupper(MD5('body='.$mch_trade_id.'&mch_trade_id='.$mch_trade_id.'&merchant_id=7139366587449633&nonce_str='.$nonce_str.'&notify_url=http://www.chm1688.com/m/wxpay/notify_weixin.php&pay_type=wechat.jsurl&settle_type=0&spbill_create_ip=113.106.89.99&sub_appid=wx60246a2d4bb0f8ab&sub_openid='.$wecha_id.'&total_fee='.$total_fee.'&key='.$rts['pay_code']));
	
	
	$data = '
{
    "pay_type": "wechat.jsurl",
    "merchant_id": "'.$rts['pay_no'].'",
    "mch_trade_id": "'.$mch_trade_id.'",
    "body": "'.$mch_trade_id.'",
	"sub_openid":"'.$wecha_id.'",
	"sub_appid":"wx60246a2d4bb0f8ab",
    "total_fee": '.$total_fee.',
    "spbill_create_ip": "113.106.89.99",
    "notify_url": "'.$rts['pay_address'].'",
    "nonce_str": "'.$nonce_str.'",
    "sign": "'.$sign.'",
	"settle_type":0
}
';
	
	$url = "https://gateway.pooulcloud.cn/paygate/:partner_code/pay";
		
		$result = $this->curlPost($url, $data);
		
		$obj = json_decode($result);
		
		 error_log('[' . date('Y-m-d H:i:s') . ']官方返回:' . "\n" . $result . "\n\n", 3, './app/shopping/wx_pay/wx_pay_' . date('Y-m-d') . '.log');
		
		if($obj->code == 0){
			if($obj->data->result_code == 0){
			header("Location: ".$obj->data->pay_url); 
			exit;
			}else{
				$this->jump(ADMIN_URL . 'user.php?type=shoukuan', 0, $obj->data->err_msg);
                    exit;
				}
			}else{
				
				$this->jump(ADMIN_URL . 'user.php?type=shoukuan', 0, $obj->msg);
                    exit;
				}
		
		}
		
		/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	 function createNoncestr( $length = 32 ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	
	function curlPost($url, $data,$showError=1){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);

          if (curl_errno($ch)) {
       echo 'Errno'.curl_error($ch);//捕抓异常
    }
    curl_close($ch); // 关闭CURL会话
    return $result; // 返回数据
	}
	

	
	function get_openid($uid){
		
	if(!isset($_GET['code'])){
				$this->get_user_codes();//授权跳转
			}
			
			$code = isset($_GET['code']) ? $_GET['code'] : '';
			
					 error_log('[' . date('Y-m-d H:i:s') . ']code返回:' . "\n".$code."\n\n", 3, './app/shopping/open_' . date('Y-m-d') . '.log');

	
			if(!empty($code)){
				
                            $access_token = $this->get_access_token();
							
							//$rt = $this->get_appid_appsecret();
							
							$appid = 'wxa1d2f0163a747532';//$rt['appid'];
		$appsecret = 'dd27e75cfff18e03fe777c6c4bb7f48a';//$rt['appsecret'];
				
                            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
                            $con = $this->curlGet($url);
                            if(!empty($con)){
				$json=json_decode($con);
				if(empty($access_token)) $access_token = $json->access_token;
				
				$openid = $json->openid;
				
				 error_log('[' . date('Y-m-d H:i:s') . ']openid返回:' . "\n" . $openid . "\n\n", 3, './app/shopping/open_' . date('Y-m-d') . '.log');
				 if(!empty($openid)){
					  $this->App->insert('user_openid', array('uid' => $uid, 'open_id' => $openid));
					 }
				
				 return  $openid;
       
				
							}
			}
		
		}
	
	
	
	
	   function update_user_bank_sj_api($arr = array()) {
        $uid = $arr['uid'];
        $sj1 = $this->App->findvar("SELECT id FROM `{$this->App->prefix() }user_sj1_api` WHERE uid=" . $uid . " limit 1");
        if ($sj1 > 0) {
            $this->sj2_api($arr);
            exit;
        }
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        //if($uid == 42){
        $card['uid'] = $uid;
        $card['merchantName'] = $data['uname'];
        $card['shortName'] = $data['uname'];
        $card['city'] = 5810;
        $card['merchantAddress'] = "广东省广州市天河区华夏路";
        $card['servicePhone'] = $data['mobile'];
        // $card['orgCode'] = $data['orgCode'];
        $card['merchantType'] = "01";
        $card['category'] = 4816;
        $card['corpmanName'] = $data['uname'];
        $card['corpmanId'] = $data['idcard'];
        $card['corpmanPhone'] = $data['mobile'];
        $card['corpmanMobile'] = $data['mobile'];
        // $card['corpmanEmail'] = $data['corpmanEmail'];
        $card['bankCode'] = $bank['code'];
        $card['bankName'] = $bank['name'];
        $card['bankaccountNo'] = $data['banksn'];
        $card['bankaccountName'] = $data['uname'];
        $card['autoCus'] = 1;
        // $card['remark'] = $data['remark'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
				<merchant>
					  <head>
						  <version>1.0.0</version>
						  <agencyId>' . $pay['pay_no'] . '</agencyId>
						  <msgType>01</msgType>
						  <tranCode>100001</tranCode>
						  <reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
						  <reqDate>' . date('Ymdhis', time()) . '</reqDate>
					  </head>
					  <body>
							<merchantName>' . $card['merchantName'] . '</merchantName>
							<shortName>' . $card['shortName'] . '</shortName>
							<city>' . $card['city'] . '</city>
							<merchantAddress>' . $card['merchantAddress'] . '</merchantAddress>
							<servicePhone>' . $card['servicePhone'] . '</servicePhone>
							<merchantType>' . $card['merchantType'] . '</merchantType>
							<category>' . $card['category'] . '</category>
							<corpmanName>' . $card['corpmanName'] . '</corpmanName>
							<corpmanId>' . $card['corpmanId'] . '</corpmanId>
							<corpmanPhone>' . $card['corpmanPhone'] . '</corpmanPhone>
							<corpmanMobile>' . $card['corpmanMobile'] . '</corpmanMobile>
							<bankCode>' . $card['bankCode'] . '</bankCode>
							<bankName>' . $card['bankName'] . '</bankName>
							<bankaccountNo>' . $card['bankaccountNo'] . '</bankaccountNo>
							<bankaccountName>' . $card['bankaccountName'] . '</bankaccountName>
							<autoCus>' . $card['autoCus'] . '</autoCus>
					  </body>
				</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/api/sj_1_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100001');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/basicInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/api/sj_1_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153997077.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/api/sj_1_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        var_dump($xml_obj);
        if ($rt['respCode'] == "000000") {
            $this->App->insert('user_sj1_api', $card);
            $this->sj2_api($arr);
        } else {
            echo $rt['respMsg'];
        }
        //var_export($rt);
        
    }
     function sj2_api($arr = array()) {
        $uid = $arr['uid'];
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_api` WHERE uid=" . $uid . " limit 1");
        $sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2_api` WHERE uid=" . $uid . " limit 1");
        if (!empty($sj2) && ($sj2['bankaccountNo'] == '99999999999999999999')) {
            $old_bank = array();
            $old_bank['uid'] = $uid;
            $old_bank['pay_id'] = $arr['pay_id'];
            $old_bank['merchantId'] = $sj1['corpmanMobile'];
            $old_bank['bankaccountNo'] = $sj2['bankaccountNo'];
            $this->delete_bankno_old_api($old_bank);
        } else {
            if (!empty($sj2) && ($sj2['bankaccountNo'] == $data['banksn'])) {
                $this->sj3_api($arr);
                exit;
            }
        }
        //删除旧卡
        if (!empty($sj2) && ($sj2['bankaccountNo'] != $data['banksn'])) {
            $old_bank = array();
            $old_bank['uid'] = $uid;
            $old_bank['pay_id'] = $arr['pay_id'];
            $old_bank['merchantId'] = $sj1['corpmanMobile'];
            $old_bank['bankaccountNo'] = $sj2['bankaccountNo'];
            $this->delete_bankno_old_api($old_bank);
        }
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $card['uid'] = $uid;
        $card['merchantId'] = $sj1['corpmanMobile'];
        $card['bankCode'] = $bank['code'];
        $card['bankaccProp'] = 0;
        $card['name'] = $data['uname'];
        $card['bankaccountNo'] = $data['banksn'];
        $card['bankaccountType'] = 1;
        $card['certCode'] = 1;
        $card['certNo'] = $data['idcard'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
		<merchant>
			<head>
			<version>1.0.0</version>
			<agencyId>' . $pay['pay_no'] . '</agencyId>
			<msgType>01</msgType>
			<tranCode>100002</tranCode>
			<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
			<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			<merchantId>' . $card['merchantId'] . '</merchantId>
			<bankCode>' . $card['bankCode'] . '</bankCode>
			<bankaccProp>' . $card['bankaccProp'] . '</bankaccProp>
			<name>' . $card['name'] . '</name>
			<bankaccountNo>' . $card['bankaccountNo'] . '</bankaccountNo>
			<bankaccountType>' . $card['bankaccountType'] . '</bankaccountType>
			<certCode>' . $card['certCode'] . '</certCode>
			<certNo>' . $card['certNo'] . '</certNo>
			</body>
		</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/api/sj_2_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100002');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/bankInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/api/sj_2_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153997077.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/api/sj_2_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if ($rt['respCode'] == "000000") {
            if (!empty($sj2)) {
                $this->App->update('user_sj2_api', $card, 'uid', $uid);
            } else {
                $this->App->insert('user_sj2_api', $card);
            }
            $this->sj3_api($arr);
        } else {
            echo $rt['respMsg'];
        }
    }
    function sj3_api($arr = array()) {
		
        $uid = $arr['uid'];
        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_api` WHERE uid=" . $uid . " limit 1");
        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3_api` WHERE uid=" . $uid . " and pay_id =" . $arr['pay_id'] . "  limit 1");
        //  if($sj3 > 0){
//        					 $this->daifu_api($arr);
//        					   exit;
//        					  }
        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);
        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);
        $rts = $this->_get_payinfo($arr['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
        //费率单独设置
        $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
        $feilv = unserialize($feilv);
        $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $arr['pay_id'] . " LIMIT 1");
        //计算手续费
        $koulv = $feilv[$pay_fangshi] / 100;
        $card['uid'] = $uid;
        $card['pay_id'] = $arr['pay_id'];
        $card['merchantId'] = $sj1['corpmanMobile'];
        if (!empty($sj3)) {
            if ($sj3['futureRateValue'] != $koulv) {
                $card['handleType'] = 1;
            } else {
				$result = $this->daifu_api($arr);
			 echo  $result;
               // echo "success";
                exit;
            }
        } else {
            $card['handleType'] = 0;
        }
        $card['cycleValue'] = 2; //结算周期 D+0
       
            $card['busiCode'] = "B00108";
       
        $card['futureRateType'] = 1; //费率类型 百分比
        $card['futureRateValue'] = $koulv;
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>' . $pay['pay_no'] . '</agencyId>
				<msgType>01</msgType>
				<tranCode>100003</tranCode>
				<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
				<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			  <merchantId>' . $card['merchantId'] . '</merchantId>
			  <handleType>' . $card['handleType'] . '</handleType>
			  <cycleValue>' . $card['cycleValue'] . '</cycleValue>
			<busiList>
				<busiCode>' . $card['busiCode'] . '</busiCode>
				<futureRateType>' . $card['futureRateType'] . '</futureRateType>
				<futureRateValue>' . $card['futureRateValue'] . '</futureRateValue>
			</busiList>
			</body>
	</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/api/sj_3_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100003');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/api/sj_3_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153997077.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/api/sj_3_' . date('Y-m-d') . '.log');
        $xml_obj = simplexml_load_string($xmlData);
        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if ($rt['respCode'] == "000000") {
            if ($card['handleType'] == 0) {
                $this->App->insert('user_sj3_api', $card);
            } else {
                $sql = "UPDATE `{$this->App->prefix() }user_sj3_api` SET `uid` = " . $card['uid'] . ",`pay_id` = " . $card['pay_id'] . ",`merchantId` = '" . $card['merchantId'] . "',`cycleValue` = " . $card['cycleValue'] . ",`busiCode` = '" . $card['busiCode'] . "',`futureRateType` = " . $card['futureRateType'] . ",`futureRateValue` = " . $card['futureRateValue'] . ",`handleType` = " . $card['handleType'] . "  WHERE `uid` = " . $card['uid'] . " and `pay_id` = " . $card['pay_id'];
                $this->App->query($sql);
            }
			
			$result = $this->daifu_api($arr);
			 echo  $result;
			
           // echo "success";
        } else {
            echo $rt['respMsg'];
        }
    }
	
	
	
	function daifu_api($arr = array()){

        $uid = $arr['uid'];
		  $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
		  $sxf_api = $this->App->findvar("SELECT sxf_api FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
        
		
         $rts = $this->_get_payinfo($arr['pay_id']);
		 $pay = unserialize($rts['pay_config']);
		 $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj1_api` WHERE uid=".$uid." limit 1");
		 $daifu_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_daifu_api` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
		// $allotFlag = $this->App->findvar("SELECT allotFlag FROM `{$this->App->prefix()}user_daifu` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
				  if(!empty($daifu_info)){
					  if($daifu_info['futureRateValue'] == $sxf_api){
					  return "success";
					   exit;
					  }
					  }
			  
			    $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$uid);
                 

				
				
				
			  $card['uid'] = $uid;
		      $card['merchantId'] = $sj1['corpmanMobile'];
			  if(!empty($daifu_info)){
			  $card['handleType'] = 1;
			  }else{
				  $card['handleType'] = 0;
				  }
			  $card['cycleValue'] = 2;//结算周期 D+0
			  $card['allotFlag'] = 1;
			  $card['busiCode'] = "B00302";
			  $card['futureRateType'] = 2;//费率类型 单笔
			  $card['futureRateValue'] = $sxf_api;
               			
			  
			
		
		$key = $this->random_string(16, $max=FALSE);
		
		 $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>'.$pay['pay_no'].'</agencyId>
				<msgType>01</msgType>
				<tranCode>100003</tranCode>
				<reqMsgId>wx'.date('Ymdhis',time()).$uid.'</reqMsgId>
				<reqDate>'.date('Ymdhis',time()).'</reqDate>		  
			</head>
			<body>
			  <merchantId>'.$card['merchantId'].'</merchantId>
			  <handleType>'.$card['handleType'].'</handleType>
			  <cycleValue>'.$card['cycleValue'].'</cycleValue>
			  <allotFlag>'.$card['allotFlag'].'</allotFlag>
			<busiList>
				<busiCode>'.$card['busiCode'].'</busiCode>
				<futureRateType>'.$card['futureRateType'].'</futureRateType>
				<futureRateValue>'.$card['futureRateValue'].'</futureRateValue>
			</busiList>
			</body>
	</merchant>';
		
		
		
		 $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;
		
		 error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".iconv('UTF-8', 'GBK', $xml)."\n\n", 3, './app/shopping/daifu_api/daifu_'.date('Y-m-d').'.log');
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'./app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key,'./app/shopping/549440153997077_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => $pay['pay_no'], 
   'signData' => $signData, 
    'tranCode' => '100003'
       );
	   

	 

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";
		
		  $response = $this->curl_daifu($url,$postdata);
	
	 error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".iconv('UTF-8', 'GBK', $response)."\n\n", 3, './app/shopping/daifu_api/daifu_'.date('Y-m-d').'.log');
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemis($encryptKey_host,'./app/shopping/549440153997077.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		    error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/shopping/daifu_api/daifu_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 
			 if($rt['respCode'] == "000000"){
				 if(empty($daifu_info)){
				   $this->App->insert('user_daifu_api', $card);
				 }else{
					 $sql = "UPDATE `{$this->App->prefix()}user_daifu_api` SET `uid` = ".$card['uid'].",`merchantId` = '".$card['merchantId']."',`cycleValue` = ".$card['cycleValue'].",`busiCode` = '".$card['busiCode']."',`allotFlag` = ".$card['allotFlag'].",`handleType` = ".$card['handleType']." ,`futureRateType` = ".$card['futureRateType']." ,`futureRateValue` = ".$card['futureRateValue']." WHERE `uid` = ".$card['uid']." and `busiCode` = '".$card['busiCode']."'";
					 
					  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $sql."\n\n", 3, './app/shopping/daifu_api/sql_'.date('Y-m-d').'.log');
                        $this->App->query($sql);

					 }
				   return "success";
			 }else{
						  return  $rt['respMsg'];
						  
				 }
			 
			 
		
		}
	
	 function curl_daifu($url,$postdata)
    {
		$timeout = 60;
       $ch = curl_init();
 
        curl_setopt ($ch, CURLOPT_URL, $url);
 
        curl_setopt ($ch, CURLOPT_POST, 1);
 
        if($postdata != ''){
 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
 
        }
 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
 
        curl_setopt($ch, CURLOPT_HEADER, false);
 
        $file_contents = curl_exec($ch);
 
        curl_close($ch);
 
        return $file_contents;
      
    }
	
	
	
	 function delete_bankno_old_api($old_bank) {
        $rts = $this->_get_payinfo($old_bank['pay_id']);
        $pay = unserialize($rts['pay_config']);
        $card['merchantId'] = $old_bank['merchantId'];
        $card['handleType'] = 1;
        $card['bankaccountNo'] = $old_bank['bankaccountNo'];
        $key = $this->random_string(16, $max = FALSE);
        $xml = '
		<merchant>
			<head>
			<version>1.0.0</version>
			<agencyId>' . $pay['pay_no'] . '</agencyId>
			<msgType>01</msgType>
			<tranCode>100002</tranCode>
			<reqMsgId>wx' . date('Ymdhis', time()) . $old_bank['uid'] . '</reqMsgId>
			<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  
			</head>
			<body>
			<merchantId>' . $card['merchantId'] . '</merchantId>
			<handleType>' . $card['handleType'] . '</handleType>
			<bankaccountNo>' . $card['bankaccountNo'] . '</bankaccountNo>
			</body>
		</merchant>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/delete_bank_api/delete_' . date('Y-m-d') . '.log');
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100002');
        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/bankInfo";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/delete_bank_api/delete_' . date('Y-m-d') . '.log');
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $merchantAESKey = $this->jiemis($encryptKey_host, './app/shopping/549440153997077.pem');
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/shopping/delete_bank_api/delete_' . date('Y-m-d') . '.log');
    }
	
	
	 function ajax_getcode_api($data = array()) {
        $uid = $this->Session->read('User.uid');
		$sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2_api` WHERE uid=" . $uid . " LIMIT 1");
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
	<head>
		<version>1.0.0</version>
		<merchantId>' . $data['merchantId'] . '</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP012</tranCode>
		<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
		<userId>' . $uid . '</userId>
		<oriReqMsgId>' . $data['oriReqMsgId'] . '</oriReqMsgId>
		<childMerchantId>'.$sj2['merchantId'].'</childMerchantId>
	</body>
</merchant>
';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
		
			error_log('[' . date('Y-m-d H:i:s') . ']快捷支付短信报文:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/shopping/kuaijie_api/pay_code' . date('Y-m-d') . '.log');
			
			
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $data['merchantId'], 'signData' => $signData, 'tranCode' => 'IFP012', 'callBack' => $data['pay_address']);
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
		
		error_log('[' . date('Y-m-d H:i:s') . ']快捷支付短信报文:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/shopping/kuaijie_api/pay_code' . date('Y-m-d') . '.log');
		
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, './app/shopping/549440153997077.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
        $xml_obj = simplexml_load_string($xmlData);
        $respCode = $xml_obj->head->respCode;
        $respMsg = $xml_obj->head->respMsg;
		
		
			error_log('[' . date('Y-m-d H:i:s') . ']快捷支付短信验证码:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml_obj, true)) . "\n\n", 3, './app/shopping/kuaijie_api/pay_code' . date('Y-m-d') . '.log');
			
			
        if ($respCode == "000000") {
            echo "ok";
        } else {
            echo $respMsg;
        }
    }
	
	
	
	 function kj_confirm_api() {
        $uid = $this->Session->read('User.uid');
		$sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2_api` WHERE uid=" . $uid . " LIMIT 1");
        $key = $this->random_string(16, $max = FALSE);
        $xml = '';
        $xml = '
<merchant>
<head>
		<version>1.0.0</version>
		<merchantId>' . $_POST['merchantId'] . '</merchantId>
		<msgType>01</msgType>
		<tranCode>IFP013</tranCode>
		<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>
		<reqDate>' . date('Ymdhis', time()) . '</reqDate>
	</head>
	<body>
		<userId>' . $uid . '</userId>
		<childMerchantId>'.$sj2['merchantId'].'</childMerchantId>
		<oriReqMsgId>' . $_POST['oriReqMsgId'] . '</oriReqMsgId>
		<validateCode>' . $_POST['p_code'] . '</validateCode>
	</body>
</merchant>
';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
		
		error_log('[' . date('Y-m-d H:i:s') . ']完成支付:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml, true)) . "\n\n", 3, './app/shopping/kuaijie_api/confirm_' . date('Y-m-d') . '.log');
		
        $encryptData = $this->encrypt($xml, $key);
        $signData = $this->rsaSign($xml, './app/shopping/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key, './app/shopping/549440153997077_pub.pem');
        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $_POST['merchantId'], 'signData' => $signData, 'tranCode' => 'IFP013', 'callBack' => $_POST['pay_address']);
        $url = "http://epay.gaohuitong.com:8082/quickInter/channel/commonSyncInter.do";
        if (is_array($postdata)) {
            ksort($postdata);
            $content = http_build_query($postdata);
            $content_length = strlen($content);
            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));
            $response = file_get_contents($url, false, stream_context_create($options));
        }
		
			error_log('[' . date('Y-m-d H:i:s') . ']完成支付:' . "\n" . iconv('UTF-8', 'GBK', var_export($response, true)) . "\n\n", 3, './app/shopping/kuaijie_api/confirm_' . date('Y-m-d') . '.log');
			
        $resp = explode('&', $response);
        $first = strpos($resp[0], "="); //字符第一次出现的位置
        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);
        $first = strpos($resp[1], "="); //字符第一次出现的位置
        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);
        //echo $encryptKey_host;
        //echo "<br>";
        $merchantAESKey = $this->jiemi($encryptKey_host, './app/shopping/549440153997077.pem');
        // echo $merchantAESKey;
        $xmlData = $this->decode($encryptData_host, $merchantAESKey);
		
			error_log('[' . date('Y-m-d H:i:s') . ']完成支付:' . "\n" . iconv('UTF-8', 'GBK', var_export($xmlData, true)) . "\n\n", 3, './app/shopping/kuaijie_api/confirm_' . date('Y-m-d') . '.log');
			
        $xml_obj = simplexml_load_string($xmlData);
        $rt['respType'] = $xml_obj->head->respType;
        $rt['respCode'] = $xml_obj->head->respCode;
        $rt['respMsg'] = $xml_obj->head->respMsg;
        if (($rt['respType'] == 'S') && ($rt['respCode'] == '000000')) {
            $sd = array('order_sn' => $_POST['order_sn'], 'status' => 1);
            if ($this->pay_successs_status_api($sd)) {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付成功！');
            } else {
                $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '支付失败！');
            }
        } else {
            $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, $rt['respMsg']);
        }
    }
	
	
	
	 //支付成功改变支付状态
    function pay_successs_status_api($rt = array()) {
        @set_time_limit(300); //最大运行时间
        $order_sn = $rt['order_sn'];
        $status = $rt['status'];
        $r_pay_time = $rt['pay_time'];
        $r_pay_no = $rt['pay_no'];
        $r_amount = $rt['amount'];
        if (empty($order_sn)) exit;
		
		
		
		
        //购买用户返积分
        //上三级返佣金
        $pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
        $tt = "false";
        if ($pay_status != '1') {
            //检查
            $sql = "SELECT cid FROM `{$this->App->prefix() }user_money_change` WHERE order_sn='$order_sn'"; //资金
            $cid = $this->App->findvar($sql);
            if ($cid > 0) {
                return true;
                exit;
            } else {
                $sql = "SELECT cid FROM `{$this->App->prefix() }user_point_change` WHERE order_sn='$order_sn'"; //积分
                $cid = $this->App->findvar($sql);
                if ($cid > 0) {
                    return true;
                    exit;
                } else {
                    $tt = "true";
                }
            }
        } else { //已经支付了的
            return true;
            exit;
        }
        if ($tt == 'true' && $status == '1' && !empty($order_sn)) {
            $pu = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if (empty($pu)) {
                return false;
                exit;
            }
            //$moeys = $pu['order_amount']*5/10000; //消费反润
            $order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
            $uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
            $pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
            $pay_id = isset($pu['pay_id']) ? $pu['pay_id'] : 0;
            $order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
            $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");
			  
			$sxf_api = $pu['sxf_api'];
            $feilv = $pu['feilv'];
            //计算手续费
            $koulv = $feilv;
            $shouxufei = $order_amount * ($koulv / 10000) + $sxf_api;
            $sy_money = $order_amount - $shouxufei;
            $shengyu = sprintf("%.2f",substr(sprintf("%.3f", $sy_money), 0, -1));
            //初始化上三级扣率 2016-10-07 9:23
            $koulv1 = '0';
            $koulv2 = '0';
            $koulv3 = '0';
            //	}else{
            //$koulv = $this->App->findvar("SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid=".$ni['user_rank']." LIMIT 1");
            //付款方式，决定添加的余额种类
            //$pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix()}payment` WHERE pay_id=".$pay_id." LIMIT 1");
            //计算手续费
            //$shouxufei = $order_amount*($koulv/10000);
            //	$shengyu = $order_amount - $shouxufei;
            //}
            //购买用户
            $nickname = $ni['nickname'];
            $dd = array();
            $dd['order_status'] = '2';
            $dd['pay_status'] = '1';
            $dd['pay_time'] = mktime();
            $this->App->update('goods_order_info', $dd, 'order_sn', $order_sn);
			
			
//			  $sj2_api = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2_api` WHERE uid='$uid' LIMIT 1");
//			  $bankname = $this->App->findrow("SELECT name FROM `{$this->App->prefix() }bank` WHERE code=".$sj2_api['bankCode']." LIMIT 1");
//			  $daifu = array();
//            $daifu['uid'] = $uid;
//			  $daifu['order_sn'] = date('Ymd',time()).$uid.time();
//            $daifu['amount'] = $shengyu;
//            $daifu['addtime'] = mktime();
//            $daifu['date'] = date('Y-m', mktime());
//            $daifu['bankname'] = $bankname;
//			  $daifu['bank_code'] = $sj2_api['code'];
//            $daifu['mobile'] = $sj2_api['merchantId'];
//            $daifu['account_name'] = $sj2_api['name'];
//            $daifu['account_no'] = $sj2_api['bankaccountNo'];
//            $daifu['key'] = 'yinlian_api';
//            $daifu['idcard'] = $sj2_api['certNo'];
//			
//		      $this->App->insert('user_drawmoney', $daifu);

       
	   
            $result = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix() }goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
            if ($result != 1) {
                return false;
                exit;
            }
            //
            $quid = $this->App->findvar("SELECT MAX(quid) FROM `{$this->App->prefix() }user` LIMIT 1");
            $this->App->update('user', array('quid' => ($quid + 1)), 'user_id', $uid);
			
			
		   // $sql = "UPDATE `{$this->App->prefix() }user` SET " . $pay_fangshi . " = " . $pay_fangshi . "+'$shengyu' WHERE user_id = " . $uid;
            $this->App->query($sql);
            $sql1 = "UPDATE `{$this->App->prefix() }user_moneys` SET yinlian = yinlian+'$shengyu' WHERE uid = " . $uid;
            $this->App->query($sql1);
				
			
				
				
				
				
				
			
			
            // $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1"; //用户配置信息
            //            $rts = $this->App->findrow($sql);
            //            $openfx_minmoney = empty($rts['openfx_minmoney']) ? 0 : intval($rts['openfx_minmoney']);
            $appid = $this->Session->read('User.appid');
            if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';
            $appsecret = $this->Session->read('User.appsecret');
            if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';
            //计算资金，便于下面返佣
            $sendrt_money = array();
            //发送店铺付款通知
            if ($pu['supplier_id'] > 0) {
                $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $uid . " LIMIT 1");
                $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname, 'money' => $order_amount, 'order_sn' => $order_sn), 'orderconfirm_toshop');
				//店铺收款店员通知
		$sql = "SELECT * FROM `{$this->App->prefix() }user_assistant` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.assistant_id  WHERE tb1.uid = ".$uid." and tb1.status = 1 and tb2.is_subscribe = 1";
        $rt = $this->App->find($sql);
        if (!empty($rt)) foreach ($rt as $row) {
			 $this->action('api', 'send', array('openid' => $row['wecha_id'],'money' => $order_amount, 'order_sn' => $order_sn), 'orderconfirm_toshop');
		}
            }
            $sql = "SELECT * FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid=" . $uid . " LIMIT 1";
            $fenxiao = $this->App->findrow($sql);
            $p1_uid = isset($fenxiao['p1_uid']) ? $fenxiao['p1_uid'] : 0;
            $p2_uid = isset($fenxiao['p2_uid']) ? $fenxiao['p2_uid'] : 0;
            $p3_uid = isset($fenxiao['p3_uid']) ? $fenxiao['p3_uid'] : 0;
            //一级返佣金
            if ($p1_uid > 0) {
				
				//第一次付款上级返佣金
				  $first_order = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }goods_order_info` WHERE  user_id='$uid' and pay_status=1 LIMIT 1");
				  
				  		    $f_yongjin = $this->App->findvar("SELECT f_yongjin FROM `{$this->App->prefix() }systemconfig` WHERE type='basic' LIMIT 1");
				  if($first_order == 1 && $f_yongjin>0){
					  					
					  $this->App->query("UPDATE `{$this->App->prefix() }user` SET `yongjin` = `yongjin`+$f_yongjin  WHERE user_id = '$p1_uid'");
			
					 $sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1' LIMIT 1";
              $rts = $this->App->findrow($sql);
                        $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
					    $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $f_yongjin, 'changedesc' => '首次刷卡返佣金', 'time' => mktime(), 'uid' => $p1_uid, 'level' => '1'));
                        //发送推荐用户通知
                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id=" . $p1_uid . " LIMIT 1");
						  $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $rts['appid'], 'appsecret' => $rts['appsecret'],'money' => $f_yongjin), 'firstreturnmoney');
					  
					  }
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
	
	
	
	function ajax_xj_merchant($arr = array()){
		$uid = $arr['uid'];
		$pay_id = $arr['pay_id'];
		
		$pay = $this->_get_payinfo($pay_id);
		$pay_config = unserialize($pay['pay_config']);
		 
		$key = $pay_config['pay_code'];
		
		
		$appId = $pay_config['pay_no']; //分配的代理商唯⼀标识 是 string
		//$sign //加密签名 是 string 签名⽅式⻅附录
		$nonceStr = $this->str_rand(); //随机字符串 是 string 字符范围a-zA-Z0-9
		
		$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $uid . " LIMIT 1");
		
		 $user_level = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid=" . $rank . " LIMIT 1");
         $feilv = unserialize($user_level['feilv']);
         $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $pay_id . " LIMIT 1");
         $koulv = $feilv[$pay_fangshi]/10;
		 $sxf =  $user_level['sxf_api']*100;
        // $koulv = 58;
		// $sxf =  200;
		$user_bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1");
		
		$user_xj_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_xj_merchant` WHERE uid=" . $uid . " LIMIT 1");
        
		if(empty($user_xj_merchant)){
		$customer = $user_bank['banksn']."|".$user_bank['uname']."|".$user_bank['idcard']."|".$user_bank['mobile'];
		$customerInfo = $this->encrypt_xj($customer,$key); //⽤户信息 是 string 加密格式⻅附录
        $provinceCode = '320000'; //省份编码 是 string
		$cityCode = '320100'; //城市编码 是 string
		$address = '南京市'; //地址 是 string
		$fee0 = $koulv; //费率 是 double 费率‰
		$d0fee = $sxf; //单笔费⽤ 是 int 单位：分
		$pointsType = 0; //商户类型 是 int 0：带积分 1：不带积分
		
		$sign_str = "address=".$address."&appId=".$appId."&cityCode=".$cityCode."&customerInfo=".$customerInfo."&d0fee=".$d0fee."&fee0=".$fee0."&nonceStr=".$nonceStr."&pointsType=".$pointsType."&provinceCode=".$provinceCode."&key=".$key;
		
		 error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $sign_str . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		 
		$sign = md5($sign_str);
		
		//$data = "address=".$address."&appId=".$appId."&cityCode=".$cityCode."&customerInfo=".$customerInfo."&d0fee=".$d0fee."&fee0=".$fee0."&nonceStr=".$nonceStr."&pointsType=".$pointsType."&provinceCode=".$provinceCode."&sign=".$sign;
//		
//		 error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $data . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		 
		$url = 'http://47.96.171.202:8010/api/v1.0/debit';
		
		$parm = array(
		'address' => $address,
		'appId' => $appId,
		'cityCode' => $cityCode,
		'customerInfo' => $customerInfo,
		'd0fee' => $d0fee,
		'fee0' => $fee0,
		'nonceStr' => $nonceStr,
		'pointsType' => $pointsType,
		'provinceCode' => $provinceCode,
		'sign' => $sign
		);
		
		$jsonStr = json_encode($parm);
		 error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $jsonStr . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		$result = $this->http_post_data($url,$jsonStr);

		$result = json_decode($result,true);
		
		error_log('[' . date('Y-m-d H:i:s') . ']API3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		
		if($result['isSuccess']){
			$sj_data = array(
			'uid' => $uid,
			'mchId' => $result['data'],
			'banksn' => $user_bank['banksn'],
			'uname' => $user_bank['uname'],
			'idcard' => $user_bank['idcard'],
			'mobile' => $user_bank['mobile'],
			'fee0' => $fee0,
			'd0fee' => $d0fee
			);
			$this->App->insert('user_xj_merchant', $sj_data);
				 echo "success";
			 
			}else{
				
				echo $result['message'];
				}
		
		}else{
			if($user_xj_merchant['fee0'] != $koulv || $user_xj_merchant['d0fee'] != $sxf || $user_bank['banksn'] != $user_xj_merchant['banksn']){
			
		$mchId = $user_xj_merchant['mchId'];	
		$customer = $user_bank['banksn']."|".$user_bank['uname']."|".$user_bank['idcard']."|".$user_bank['mobile'];
		$customerInfo = $this->encrypt_xj($customer,$key); //⽤户信息 是 string 加密格式⻅附录
        $provinceCode = '320000'; //省份编码 是 string
		$cityCode = '320100'; //城市编码 是 string
		$address = '南京市'; //地址 是 string
		$fee0 = (string)$koulv; //费率 是 double 费率‰
		$d0fee = (string)$sxf; //单笔费⽤ 是 int 单位：分
		//$pointsType = 0; //商户类型 是 int 0：带积分 1：不带积分
		
		$sign_str = "appId=".$appId."&cityCode=".$cityCode."&customerInfo=".$customerInfo."&d0fee=".$d0fee."&fee0=".$fee0."&mchId=".$mchId."&nonceStr=".$nonceStr."&provinceCode=".$provinceCode."&key=".$key;
		
		 error_log('[' . date('Y-m-d H:i:s') . ']updateAPI1:' . "\n" . $sign_str . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		 
		$sign = md5($sign_str);
		
		//$data = "address=".$address."&appId=".$appId."&cityCode=".$cityCode."&customerInfo=".$customerInfo."&d0fee=".$d0fee."&fee0=".$fee0."&nonceStr=".$nonceStr."&pointsType=".$pointsType."&provinceCode=".$provinceCode."&sign=".$sign;
//		
//		 error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $data . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		 
		$url = 'http://47.96.171.202:8010/api/v1.0/debit';
		
		$parm = array(
		'appId' => $appId,
		'mchId' => $mchId,
		'cityCode' => $cityCode,
		'customerInfo' => $customerInfo,
		'd0fee' => $d0fee,
		'fee0' => $fee0,
		'nonceStr' => $nonceStr,
		'provinceCode' => $provinceCode,
		'sign' => $sign
		);
		
		$jsonStr = json_encode($parm);
		
		//$jsonStr = '{"appId":'.$appId.',"mchId":'.$mchId.',"cityCode":'.$cityCode.',"customerInfo":'.$customerInfo.',"d0fee":'.$d0fee.',"fee0":'.$fee0.',"nonceStr":'.$nonceStr.',"provinceCode":'.$provinceCode.',"sign":'.$sign.'}';
		 error_log('[' . date('Y-m-d H:i:s') . ']updateAPI2:' . "\n" . $jsonStr . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		$result = $this->http_put_data($url,$jsonStr);
		$result = json_decode($result,true);
		
		error_log('[' . date('Y-m-d H:i:s') . ']updateAPI3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/' . date('Y-m-d') . '.log');
		
		
		if($result['isSuccess']){
			$sj_data = array(
			'banksn' => $user_bank['banksn'],
			'uname' => $user_bank['uname'],
			'idcard' => $user_bank['idcard'],
			'mobile' => $user_bank['mobile'],
			'fee0' => $fee0,
			'd0fee' => $d0fee
			);
			$this->App->update('user_xj_merchant', $sj_data, 'uid',$uid);
				 echo "success";
			 
			}else{
				
				echo $result['message'];
				}
				
			}else{
				echo "success";
				}
			
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


//数据加密
 	function encrypt_xj($input,$key)
 	{
 		$size = mcrypt_get_block_size(MCRYPT_3DES,'ecb');
 		$input = $this->pkcs5_pad($input, $size);
 		$key = str_pad($key,24,'0');
 		$td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
 		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
 		@mcrypt_generic_init($td, $key, $iv);
 		$data = mcrypt_generic($td, $input);
 		mcrypt_generic_deinit($td);
 		mcrypt_module_close($td);
 		$data = base64_encode($data);
 		return $data;
 	}
 	//数据解密
 	function decrypt_xj($encrypted,$key)
 	{
 		$encrypted = base64_decode($encrypted);
 		$key = str_pad($key,24,'0');
 		$td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');
 		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
 		$ks = mcrypt_enc_get_key_size($td);
 		@mcrypt_generic_init($td, $key, $iv);
 		$decrypted = mdecrypt_generic($td, $encrypted);
 		mcrypt_generic_deinit($td);
 		mcrypt_module_close($td);
 		$y=$this->pkcs5_unpad($decrypted);
 		return $y;
 	}
 	
 	function pkcs5_pad_xj ($text, $blocksize) 
 	{
 		$pad = $blocksize - (strlen($text) % $blocksize);
 		return $text . str_repeat(chr($pad), $pad);
 	}
 	
	function pkcs5_unpad_xj($text)
	{
 		$pad = ord($text{strlen($text)-1});
 		if ($pad > strlen($text)) 
 		{
 		return false;
 		}
 		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
 		{
 			return false;
 		}
 		return substr($text, 0, -1 * $pad);
 	}

	
	
function http_post_data($url, $data_string) {  
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_POST, 1);  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
        "Content-Type: application/json; charset=utf-8",  
        "Content-Length: " . strlen($data_string))  
    );  
    ob_start();  
    curl_exec($ch);  
    $return_content = ob_get_contents();  
    ob_end_clean();  
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
    return $return_content;  
}  


//function http_put_data($url,$data,$method='put'){
//    $ch = curl_init(); //初始化CURL句柄 
//    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
//    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
//     
//    curl_setopt($ch,CURLOPT_HTTPHEADER,array("ContentType：application/json;charset=UTF-8"));//设置HTTP头信息
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
//    $document = curl_exec($ch);//执行预定义的CURL 
//    if(!curl_errno($ch)){ 
//      $info = curl_getinfo($ch); 
//     // echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']; 
//    } else { 
//     // echo 'Curl error: ' . curl_error($ch); 
//    }
//    curl_close($ch);
//     
//    return $document;
//}


function http_put_data($url,$data){
    //$data = json_encode($data);
    $ch = curl_init(); //初始化CURL句柄 
    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
    curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT"); //设置请求方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}


/*function http_put_data($URL,$params){
	$type = 'PUT';
	$headers = array('ContentType：application/json;');
        $ch = curl_init($URL);
        $timeout = 5;
        if($headers!=""){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        }else {
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        switch ($type){
            case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, true);break;
            case "POST": curl_setopt($ch, CURLOPT_POST,true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PATCH": curl_setopt($ch, CULROPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);break;
            case "DELETE":curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
        }
        $file_contents = curl_exec($ch);//获得返回值
        return $file_contents;
        curl_close($ch);
    }*/
 

	function bangka_xj_api() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->findrow("SELECT uname,idcard FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1");
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('添加新卡 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_xj_api');
    }
	
	
	 function bangka_list_xj_api() {
        $uid = $this->Session->read('User.uid');
		$client = $_SERVER['HTTP_USER_AGENT'];

//用php自带的函数strpos来检测是否是微信端
if (strpos($client , 'MicroMessenger') === false) {
    die("请在微信端打开");
	exit;
}
        $rt = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid=" . $uid);
        $this->set('rt', $rt);
        $this->set('uid', $uid);
        //$this->set('lid',$lid);
        $this->title('银行卡管理 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/bangka_list_xj_api');
    }
	
	
	function xj_bk_confirm_api() {
		 $uid = $this->Session->read('User.uid');
		$card['uid'] = $uid;
        $card['name'] = $_POST['name'];
        $card['idcard'] = $_POST['idcard'];
        $card['bank_no'] = $_POST['bank_no'];
        $card['mobile'] = $_POST['mobile'];
        $card['valid'] = $_POST['valid'];
        $card['cvn2'] = $_POST['cvn2'];
		
		 $pay = $this->_get_payinfo(26);
		 $pay_config = unserialize($pay['pay_config']);
		 
		 	$user_xj_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_xj_merchant` WHERE uid=" . $uid . " LIMIT 1");
		 
		$key = $pay_config['pay_code'];
		$appId = $pay_config['pay_no']; //分配的代理商唯⼀标识 是 string
		//$sign //加密签名 是 string 签名⽅式⻅附录
		$nonceStr = $this->str_rand(); //随机字符串 是 string 字符范围a-zA-Z0-9
		$mchId = $user_xj_merchant['mchId'];
		$name = $card['name'];
		$cardNumber = $card['bank_no'];
		$tel = $card['mobile'];
		$cvn = $card['cvn2'];
		$expireDate = $card['valid'];
		
		
		//$sign_str = "appId=".$appId."&cardNumber=".$cardNumber."&cvn=".$cvn."&expireDate=".$expireDate."&mchId=".$mchId."&name=".$name."&nonceStr=".$nonceStr."&tel=".$tel."&key=".$key;
		
		 //error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $sign_str . "\n\n", 3, './app/shopping/xj_kuaijie/xj_open_' . date('Y-m-d') . '.log');
		 
		//$sign = md5($sign_str);
		 
		//$url = 'http://47.96.171.202:8010/api/v1.0/open';
		
		$parm = array(
		'appId' => $appId,
		'cardNumber' => $cardNumber,
		'cvn' => $cvn,
		'expireDate' => $expireDate,
		'mchId' => $mchId,
		'name'  => $name,
		'nonceStr' => $nonceStr,
		'tel' => $tel,
		'sign' => $sign
		);
		
		//$jsonStr = json_encode($parm);
		 //error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $jsonStr . "\n\n", 3, './app/shopping/xj_kuaijie/xj_open_' . date('Y-m-d') . '.log');
		//$result = $this->http_post_data($url,$jsonStr);
		//$result = json_decode($result,true);
		
		//error_log('[' . date('Y-m-d H:i:s') . ']API3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/xj_open_' . date('Y-m-d') . '.log');
		
		//if($result['isSuccess']){
			
			 if ($this->App->insert('user_card_xj_api', $card)) {
                        $this->jump(ADMIN_URL . 'mycart.php?type=shoukuan', 0, '绑卡成功，请重新选择支付！');
                        exit;
                    } else {
                        $this->jump(ADMIN_URL . 'mycart.php?type=bangka_xj_api', 0, '绑卡失败，请重新绑定！');
                        exit;
                    }
		//}else{
			 //$this->jump(ADMIN_URL . 'mycart.php?type=bangka_xj_api', 0, $result['message']);
                        //exit;
			//}
		
		
		
				
				
		}

        function ajax_xingjie_open($arr = array()){

            

             $uid = $this->Session->read('User.uid');

             $bank_no = $arr['card'];

             $pay_id = $arr['pay_id'];

             

     $user_card_ys_api = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_ys_api` WHERE uid=" . $uid . " and bank_no='".$bank_no."' LIMIT 1");

     

     



             //echo "fail";

             

        $card['uid'] = $uid;

        $card['name'] = $user_card_ys_api['name'];

        $card['idcard'] = $user_card_ys_api['idcard'];

        $card['bank_no'] = $user_card_ys_api['bank_no'];

        $card['mobile'] = $user_card_ys_api['mobile'];

        $card['valid'] = $user_card_ys_api['valid'];

        $card['cvn2'] = $user_card_ys_api['cvn2'];

        

         $pay = $this->_get_payinfo($pay_id);

         $pay_config = unserialize($pay['pay_config']);

         

            $user_xj_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_xj_merchant` WHERE uid=" . $uid . " LIMIT 1");

         

        $key = $pay_config['pay_code'];

        $appId = $pay_config['pay_no']; //分配的代理商唯⼀标识 是 string

        //$sign //加密签名 是 string 签名⽅式⻅附录

        $nonceStr = $this->str_rand(); //随机字符串 是 string 字符范围a-zA-Z0-9

        $mchId = $user_xj_merchant['mchId'];

        $name = $card['name'];

        $cardNumber = $card['bank_no'];

        $tel = $card['mobile'];

        $cvn = $card['cvn2'];

        $expireDate = $card['valid'];

        

        $sign_str = "appId=".$appId."&cardNumber=".$cardNumber."&cvn=".$cvn."&expireDate=".$expireDate."&mchId=".$mchId."&name=".$name."&nonceStr=".$nonceStr."&tel=".$tel."&key=".$key;

        

         error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $sign_str . "\n\n", 3, './app/shopping/xj_kuaijie/xj_open_' . date('Y-m-d') . '.log');

         

        $sign = md5($sign_str);

         

        $url = 'http://47.96.171.202:8010/api/v1.0/open';

        

            $parm = array(

        'appId' => $appId,

        'cardNumber' => $cardNumber,

        'cvn' => $cvn,

        'expireDate' => $expireDate,

        'mchId' => $mchId,

        'name'  => $name,

        'nonceStr' => $nonceStr,

        'tel' => $tel,

        'sign' => $sign

        );

        

        $jsonStr = json_encode($parm);

         error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . $jsonStr . "\n\n", 3, './app/shopping/xj_kuaijie/xj_open_' . date('Y-m-d') . '.log');

        $result = $this->http_post_data($url,$jsonStr);

        $result = json_decode($result,true);

        

        error_log('[' . date('Y-m-d H:i:s') . ']API3:' . "\n" . var_export($result,true) . "\n\n", 3, './app/shopping/xj_kuaijie/xj_open_' . date('Y-m-d') . '.log');

        

        

        if($result['isSuccess']){

              //$sql = "update `{$this->App->prefix() }user_card_ys_api` set open=0 where uid='$uid' and bank_no = '$bank_no'";

              //$this->App->query($sql);

            return "success";

        }else{

            return $result['message'];

            

            }

             }
		
		
		
	
	   }
                    

