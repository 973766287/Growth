<?php

/*



 * 会员登录类



*/

class UserController extends Controller {

    function __construct() {

        $this->css(array('user2015.css'));

        $this->js(array('jquery.json-1.3.js', 'user.js?v=v1'));

    }

    /*     * ********** */

    // 获取jsticket 两小时有效

    function getjsticket() { // 只允许本类调用，继承的都不可以调用，公开调用就更不可以了

        $access_token = $this->action('common', '_get_access_token');

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token . "&type=jsapi"; // 两小时有效

        $rurl = file_get_contents($url);

        $rurl = json_decode($rurl, true);

        if ($rurl['errcode'] != 0) {

            return false;

        } else {

            $jsticket = $rurl['ticket'];

            return $jsticket;

        }

    }

    // 获取 signature

    function getsignature() {

        $access_token = $this->action('common', '_get_access_token');

        $rr = $this->action('common', '_get_appid_appsecret');

        $appid = $rr['appid'];

        $appsecret = $rr['appsecret'];

        $noncestr = $nonceStr = $this->createNonceStr();;

        $jsapi_ticket = $this->getjsticket();

        $timestamp = time();

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $url = $protocol . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];

        $string1 = 'jsapi_ticket=' . $jsapi_ticket . '&noncestr=' . $noncestr . '&timestamp=' . $timestamp . '&url=' . $url;

        $signature = sha1($string1);

        $signPackage = array("appId" => $appid, "nonceStr" => $nonceStr, "timestamp" => $timestamp, "url" => $url, "signature" => $signature, "rawString" => $string, "access_token" => $access_token);

        return $signPackage;

    }

    function createNonceStr($length = 16) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $str = "";

        for ($i = 0;$i < $length;$i++) {

            $str.= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

        }

        return $str;

    }

    function get_user_wecha_id_new() {

        $rt = $this->action('common', '_get_appid_appsecret');

        if (is_weixin() == false || $rt['is_oauth'] == '0') {

            unset($rt);

            return "";

        }

        unset($rt);

        $t = Common::_return_px();

        $cache = Import::ajincache();

        $cache->SetFunction(__FUNCTION__);

        $cache->SetMode('user' . $t);

        $uid = $this->Session->read('User.uid');

        $fn = $cache->fpath(array('0' => $uid));

        if (file_exists($fn) && !$cache->GetClose() && !isset($_GET['code'])) {

            include ($fn);

        } else {

            if (!isset($_GET['code'])) {

                $this->action('common', 'get_user_code'); //授权跳转

                

            }

            $code = isset($_GET['code']) ? $_GET['code'] : '';

            if (!empty($code)) {

                $rr = $this->action('common', '_get_appid_appsecret');

                $appid = $rr['appid'];

                $appsecret = $rr['appsecret'];

                $access_token = $this->action('common', '_get_access_token');

                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';

                $con = $this->action('common', 'curlGet', $url);

                $json = json_decode($con);

                if (empty($access_token)) $access_token = $json->access_token;

                $wecha_id = $json->openid;

                $refresh_token = $json->refresh_token; //获取 refresh_token

                if (!empty($refresh_token) && !empty($access_token)) {

                    if (empty($wecha_id)) {

                        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $appid . '&grant_type=refresh_token&refresh_token=' . $refresh_token;

                        $con = $this->action('common', 'curlGet', $url);

                        $json = json_decode($con);

                        $wecha_id = $json->openid; //获取 openid

                        

                    }

                }

            }

            $cache->write($fn, $wecha_id, 'wecha_id');

        }

        return $wecha_id;

    }

    /*     * ****************************** */

    function ajax_checked_fenxiao($data = array()) {

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` WHERE type = 'basic' LIMIT 1";

        $rrL = $this->App->findrow($sql);

        //print_r($rrL);exit;

        if ($rrL['viewfxset'] == '1') {

            echo "1";

            exit;

        } else {

            $sql = "SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$uid' LIMIT 1";

            $rank = $this->App->findvar($sql);

            if ($rank == '1') {

                $appid = $this->Session->read('User.appid');

                if (empty($appid)) $appid = isset($_COOKIE[CFGH . 'USER']['APPID']) ? $_COOKIE[CFGH . 'USER']['APPID'] : '';

                $appsecret = $this->Session->read('User.appsecret');

                if (empty($appsecret)) $appsecret = isset($_COOKIE[CFGH . 'USER']['APPSECRET']) ? $_COOKIE[CFGH . 'USER']['APPSECRET'] : '';

                //发送用户通知

                $wd = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

                $wecha_id = isset($wd['wecha_id']) ? $wd['wecha_id'] : '';

                $nickname = isset($wd['nickname']) ? $wd['nickname'] : '';

                if (!empty($wecha_id)) {

                    $this->action('api', 'send', array('openid' => $wecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => $nickname), 'buymess');

                }

                echo "成为合伙人，抢占地盘，您至少需要购买一件产品哦！";

            } else {

                echo "1";

            }

        }

        exit;

    }

    //会员留言

    function facebook() {

        $page = 1;

        $list = 8;

        $start = ($page - 1) * $list;

        $tt = $this->action('feedback', '__get_message_count');

        $rt['message_count'] = $tt;

        $rt['messagelist'] = $this->action('feedback', '__get_message', 0, $start, $list);

        $rt['messagepage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_message_page');

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "我要留言");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/facebook');

    }

    function shoplist($data = array()) {

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT tb1.s_ld,tb1.author,tb1.article_title,tb1.article_img,tb1.article_id FROM `{$this->App->prefix() }article` AS tb1 LEFT JOIN `{$this->App->prefix() }article_cate` AS tb2 ON tb2.cat_id = tb1.cat_id WHERE tb2.type='new' ORDER BY tb1.vieworder ASC,tb1.article_id DESC";

        $rt_ = $this->App->find($sql);

        $rt = array();

        if (!empty($rt_)) foreach ($rt_ as $k => $row) {

            $rt[$k] = $row;

            $id = $row['article_id'];

            $sql = "SELECT tb1.address,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix() }article` AS tb1";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";

            $sql.= " WHERE tb1.cat_id='82' AND tb1.article_id='$id' LIMIT 1";

            $userress = $this->App->findrow($sql);

            $rt[$k]['address'] = "";

            if (!empty($userress)) $rt[$k]['address'] = $userress['provinces'] . $userress['citys'] . $userress['districts'] . $userress['address'];

        }

        unset($rt_);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "附近的店");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/shoplist');

    }

    function shopinfo($data = array()) {

        $id = $data['id'];

        $sql = "SELECT * FROM `{$this->App->prefix() }article` WHERE article_id = '$id' LIMIT 1";

        $rt = $this->App->findrow($sql);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', $rt['article_title']);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/shopinfo');

    }

    function shopyuyue($data = array()) {

        $id = $data['id'];

        if (!defined(NAVNAME)) define('NAVNAME', '在线预约');

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/shopyuyue');

    }

    function ajax_submit_yuyue($data = array()) {

        $uname = $data['uname'];

        $mobile = $data['mobile'];

        $sex = $data['sex'];

        $yutime = $data['yutime'];

        $sid = $data['sid'];

        if (empty($uname) || empty($mobile) || empty($yutime)) {

            die("请输入完整信息！");

        }

        if ($this->App->insert('shop_yuyue', array('uname' => $uname, 'mobile_phone' => $mobile, 'sex' => $sex, 'yutime' => $yutime, 'time' => mktime(), 'sid' => $sid))) {

            $sql = "SELECT colorid FROM `{$this->App->prefix() }article` WHERE article_id='{$sid}' LIMIT 1";

            $uid = $this->App->findvar($sql);

            if ($uid > 0) {

                $rr = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix() }user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");

                $pwecha_id = isset($rr['wecha_id']) ? $rr['wecha_id'] : '';

                $nickname = isset($rr['nickname']) ? $rr['nickname'] : '';

                if (!empty($pwecha_id) && !empty($nickname)) {

                    $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => '', 'appsecret' => '', 'nickname' => $nickname), 'yuyuesuccess');

                }

            }

            die("预约成功！");

        } else {

            die("预约失败，请联系在线客服！");

        }

    }

    //赠送红包

    function mygift($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT tb1.*,tb2.* FROM `{$this->App->prefix() }bonus_list` AS tb1 LEFT JOIN `{$this->App->prefix() }bonus_type` AS tb2 ON tb2.type_id = tb1.bonus_type_id WHERE tb1.user_id = '$uid'";

        $rt = $this->App->find($sql);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "我的红包");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/mygift');

    }

    //赠送红包

    function giftlist($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id = '$uid' LIMIT 1";

        $rt = $this->App->findrow($sql);

        if ($rt[user_rank] == 1) {

            $rankType = $_GET['rank'];

        } else {

            $rankType = $rt['user_rank'];

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }gift_bag` where `type`='$rankType'";

        $this->set('lists', $this->App->find($sql));

        if (!defined(NAVNAME)) define('NAVNAME', "领取礼包");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/giftlist');

    }

    //赠送红包

    function gift_info($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";

        $userinfo = $this->App->findrow($sql);

        $bid = isset($_GET['bid']) ? $_GET['bid'] : '';

        $has = 0;

        if ($bid) {

            /* $sql = "SELECT count(*) FROM `{$this->App->prefix()}gift_order` where bid='$bid' and uid='$uid'";

            

              $count = $this->App->findvar($sql);

            

              if ($count) {

            

              $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');

            

              } */

            //查找该用户已经领取该等级的礼包了

            $sql = "SELECT  bid FROM `{$this->App->prefix() }gift_bag` where type =(select type from `{$this->App->prefix() }gift_bag`  where bid='$bid' ) ";

            $bids = $this->App->find($sql);

            foreach ($bids as $_k => $_v) {

                $sql = "SELECT count(*) FROM `{$this->App->prefix() }gift_order` where bid='$_v[bid]' and user_id='$uid'";

                $count = $this->App->findvar($sql);

                if ($count) {

                    // $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');

                    // exit;

                    $has = 1;

                    break;

                }

            }

            if (!$has) {

                $sql = " select type from `{$this->App->prefix() }gift_bag`  where bid='$bid' limit 1  ";

                $rank = $this->App->findvar($sql);

                $has = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_level_log` WHERE user_id='$uid' and user_rank=$rank and type=1 LIMIT 1");

            }

            $this->set('has', $has);

            $sql = "SELECT * FROM `{$this->App->prefix() }gift_bag` where bid='$bid'";

            $gift_info = $this->App->findrow($sql);

            //判断用户的等级 可以领取该礼包

            /* if ($gift_info['type'] != $userinfo['user_rank']) {

            

              $this->jump(ADMIN_URL, 0, '您无法领取该礼包！');

            

              exit;

            

              } */

            $this->set('gift', $gift_info);

            $this->set('userinfo', $userinfo);

        }

        $rt['province'] = $this->action('user', 'get_regions', 1); //获取省列表

        $sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix() }user_address` AS ua";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg ON rg.region_id = ua.province";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg1 ON rg1.region_id = ua.city";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id";

        $rt['userress'] = $this->App->find($sql);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "领取礼包");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/gift_info');

    }

    public function gift_save() {

        $uid = $this->checked_login();

        $has = 0;

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";

        $userinfo = $this->App->findrow($sql);

        //查找该用户是否已经领取该礼包

        $bid = $_POST['bid'];

        $sql = "SELECT * FROM `{$this->App->prefix() }gift_bag` where bid='$bid'";

        $gift_info = $this->App->findrow($sql);

        //判断用户的等级 可以领取该礼包

        if ($gift_info['type'] != $userinfo['user_rank']) {

            $this->jump(ADMIN_URL, 0, '您无法领取该礼包！');

            exit;

        }

        $sql = "SELECT  bid FROM `{$this->App->prefix() }gift_bag` where type =(select type from `{$this->App->prefix() }gift_bag`  where bid='$bid' ) ";

        $bids = $this->App->find($sql);

        foreach ($bids as $_k => $_v) {

            $sql = "SELECT count(*) FROM `{$this->App->prefix() }gift_order` where bid='$_v[bid]' and user_id='$uid'";

            $count = $this->App->findvar($sql);

            if ($count) {

                $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');

                exit;

                /* $has = 1;

                

                  break; */

            }

        }

        $sql = " select type from `{$this->App->prefix() }gift_bag`  where bid='$bid' limit 1 ";

        $rank = $this->App->findvar($sql);

        $has = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix() }user_level_log` WHERE user_id='$uid' and user_rank=$rank and type=1 LIMIT 1");

        if ($has) {

            $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');

            exit;

        }

        $this->set('has', $has);

        if (!$_POST['userress_id']) {

            $this->jump(ADMIN_URL, 0, '请先选择收货地址！');

            exit;

        }

        $data['create_time'] = mktime();

        $data['user_id'] = $uid;

        $data['address_id'] = $_POST['userress_id'];

        $data['bid'] = $_POST['bid'];

        if ($this->App->insert('gift_order', $data)) {

            $this->jump(ADMIN_URL . "user.php", 0, '您的奖品已经预定成功，我们将尽快给您发货，请您耐心等待！祝您工作顺心、生活愉快！谢谢！');

        }

    }

    function get_user_info($uid = '') {

        if (empty($uid)) return array();

        $t = Common::_return_px();

        $cache = Import::ajincache();

        $cache->SetFunction(__FUNCTION__);

        $cache->SetMode('sitemes' . $t);

        $fn = $cache->fpath(array('0' => $uid));

        if (file_exists($fn) && (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()) {

            include ($fn);

        } else {

            $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id = '$uid' LIMIT 1";

            $rt = $this->App->findrow($sql);

            $cache->write($fn, $rt, 'rt');

        }

        return $rt;

    }

    //我的二维码

    function myerweima() {

        $uid = $this->checked_login();

        $this->action('common', 'checkjump');

        $filename = $uid . '.png';

        $t = Common::_return_px();

        $f = SYS_PATH_PHOTOS . 'qcody' . DS . (!empty($t) ? $t . DS : '') . $uid . DS . $filename;

        $issubscribe = '0';

        if ($uid > 0) {

            $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id = '$uid' LIMIT 1";

            $issubscribe = $this->App->findvar($sql);

        }

        if ($issubscribe == '0') {

            $to_wecha_id = $this->action('common', 'get_user_parent_uid');

            $thisurl = ADMIN_URL . "?toid=" . $to_wecha_id . "&tid=" . $uid;

        } else {

            $thisurl = ADMIN_URL . "?tid=" . $uid;

        }

        if (!(is_file($f)) || !file_exists($f) || (mktime() - filemtime($fn) > 10000)) {

            $this->action('common', 'mark_phpqrcode', $f, $thisurl);

        }

        $this->set('thisurl', $thisurl);

        if (!defined(NAVNAME)) define('NAVNAME', "我的二维码");

        $this->set('qcodeimg', SITE_URL . 'photos/qcody/' . (!empty($t) ? $t . '/' : '') . $uid . '/' . $uid . '.png');

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myerweima');

    }

    function ajax_down_img() {

        $uid = $this->Session->read('User.uid');

        $filename = $uid . '.png';

        $qcodeimg = SYS_PATH_PHOTOS . 'qcody' . DS . $uid . DS . $filename;

        Import::fileop()->downloadfile($qcodeimg, 'image/jpg');

    }

    //AJAX获取分页信息示例

    function ajax_zpoints_page($rts = array()) {

        $hh = $rts['hh'];

        $tops = $rts['tops'];

        $tops = intval($tops);

        if (($tops - $hh) >= 0) {

            $page = ceil($tops / $hh);

            $list = 30;

            $start = $page * $list;

            $sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix() }user` WHERE active='1' ORDER BY points_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";

            $ulist = $this->App->find($sql);

            $this->set('ulist', $ulist);

            $this->set('pagec', $page * $list);

            echo $this->fetch('load_zpoints', true);

        }

        echo "";

        exit;

    }

    //AJAX获取我的邀请

    function ajax_myshate_page($rts = array()) {

        $hh = $rts['hh'];

        $tops = $rts['tops'];

        $tops = intval($tops);

        if (($tops - $hh) >= 0) {

            $page = ceil($tops / $hh);

            $list = 30;

            $start = $page * $list;

            $uid = $this->Session->read('User.uid');

            $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix() }user_tuijian` AS tb1";

            $sql.= " LEFT JOIN `{$this->App->prefix() }user` AS tb2";

            $sql.= " ON tb1.uid = tb2.user_id";

            $sql.= " WHERE tb1.share_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.points_ucount DESC,tb1.id DESC LIMIT $start,$list";

            $ulist = $this->App->find($sql);

            $this->set('ulist', $ulist);

            $this->set('pagec', $page * $list);

            echo $this->fetch('load_myshate', true);

        }

        echo "";

        exit;

    }

    //AJAX获取我的好友

    function ajax_myuser_page($rts = array()) {

        $hh = $rts['hh'];

        $tops = $rts['tops'];

        $tops = intval($tops);

        if (($tops - $hh) >= 0) {

            $page = ceil($tops / $hh);

            $list = 30;

            $start = $page * $list;

            $uid = $this->Session->read('User.uid');

            $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix() }user_tuijian` AS tb1";

            $sql.= " LEFT JOIN `{$this->App->prefix() }user` AS tb2";

            $sql.= " ON tb1.uid = tb2.user_id";

            $sql.= " WHERE tb1.parent_uid = '$uid' AND tb2.is_subscribe ='1' ORDER BY tb2.share_ucount DESC,tb2.points_ucount DESC,tb1.id DESC LIMIT $start,$list";

            $ulist = $this->App->find($sql);

            $this->set('ulist', $ulist);

            $this->set('pagec', $page * $list);

            echo $this->fetch('load_myuser', true);

        }

        echo "";

        exit;

    }

    function login_instead() {

        $this->layout('Instead_h');

        if (($this->is_login_instead())) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $this->title("用户登录" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt['hear'][] = '<a href="' . ADMIN_URL . '">首页</a>&nbsp;>&nbsp;';

        $rt['hear'][] = '用户登录';

        if (!defined(NAVNAME)) define('NAVNAME', "用户登陆");

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_login_instead');

    }

    //用户登录

    function login() {

        $id = $_REQUEST['id'];  

        $client = $_SERVER['HTTP_USER_AGENT'];

        //用php自带的函数strpos来检测是否是微信端

        // if (strpos($client, 'MicroMessenger') === false) {

        //     die("请在微信端打开");

        //     exit;

        // }

  
        //2018/03/13
        $this->css('reset.css');
        $this->css('css.css');
        if (($this->is_login())) {

            $this->jump(ADMIN_URL . 'user.php');

            exit;

        } //

        $this->title("用户登录" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt['hear'][] = '<a href="' . ADMIN_URL . '">首页</a>&nbsp;>&nbsp;';

        $rt['hear'][] = '用户登录';

        //地区

        $sql = "SELECT * FROM `{$this->App->prefix() }region` WHERE parent_id='76' AND region_type='3' ORDER BY region_id ASC";

        $rt['diqucate'] = $this->App->find($sql);

        //店铺分类

        $sql = "SELECT * FROM `{$this->App->prefix() }user_cate` WHERE parent_id='0' AND is_show='1' ORDER BY sort_order ASC,cat_id ASC";

        $rt['shopcate'] = $this->App->find($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "用户登陆");

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/login');

    }

    //重设密码

    function ajax_rp_pass($data = array()) {

        $uname = $data['uname'];

        $email = $data['email'];

        $pass = $data['pass'];

        if (empty($uname) || empty($email) || empty($pass)) {

            die("目前无法完成您的请求！");

        }

        $md5pass = md5(trim($pass));

        $sql = "UPDATE `{$this->App->prefix() }user` SET password ='$md5pass' WHERE user_name='$uname' AND email='$email'";

        if ($this->App->query($sql)) {

            die("");

        } else {

            die("目前无法完成您的请求！");

        }

    }

    //用户注册

    function register() {

        $this->css('reset.css');
        $this->css('css.css');

        if (($this->is_login())) {

            $this->jump(ADMIN_URL . 'user.php');

            exit;

        } //
        if(!empty($_GET['uid'])){

            $this->set('parent_uid',$_GET['uid']);

        }

        $this->title("用户注册" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt['hear'][] = '<a href="' . ADMIN_URL . '">首页</a>&nbsp;>&nbsp;';

        $rt['hear'][] = '用户注册';

        $rt['province'] = $this->get_regions(1); //获取省列表

        //地区

        $sql = "SELECT * FROM `{$this->App->prefix() }region` WHERE parent_id='76' AND region_type='3' ORDER BY region_id ASC";

        $rt['diqucate'] = $this->App->find($sql);

        //店铺分类

        $sql = "SELECT * FROM `{$this->App->prefix() }user_cate` WHERE parent_id='0' AND is_show='1' ORDER BY sort_order ASC,cat_id ASC";

        $rt['shopcate'] = $this->App->find($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "用户注册");

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/registration');

    }

    //用户注册

    function register_instead() {

        $this->layout('Instead_h');

        $InviteCode = $this->Session->read('InviteCode');

        if (empty($InviteCode)) {

            $this->jump(ADMIN_URL . 'InviteCode.php');

            exit;

        }

        $this->title("用户注册" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt['hear'][] = '<a href="' . ADMIN_URL . '">首页</a>&nbsp;>&nbsp;';

        $rt['hear'][] = '用户注册';

        if (!defined(NAVNAME)) define('NAVNAME', "用户注册");

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_register_instead');

    }

    //当前文章的分类的所有文章

    function __get_all_article($type = 'default') {

        $article_list = $this->Cache->read(3600);

        if (is_null($rt)) {

            $order = "ORDER BY tb1.vieworder ASC, tb1.article_id DESC";

            $sql = "SELECT tb1.article_title,tb1.cat_id, tb1.article_id,tb2.cat_name FROM `{$this->App->prefix() }article` AS tb1";

            $sql.= " LEFT JOIN `{$this->App->prefix() }article_cate` AS tb2";

            $sql.= " ON tb1.cat_id = tb2.cat_id";

            $sql.= " WHERE tb2.type='$type'  $order";

            $rt = $this->App->find($sql);

            $article_list = array();

            if (!empty($rt)) {

                foreach ($rt as $k => $row) {

                    $article_list[$row['cat_id']][$k] = $row;

                    $article_list[$row['cat_id']][$k]['url'] = get_url($row['article_title'], $row['article_id'], $type . '.php?id=' . $row['article_id'], 'article', array($type, 'article', $row['article_id']));

                }

                unset($rt);

            }

            $this->Cache->write($article_list);

        }

        return $article_list;

    }

    //再次检测用户信息

    function _get_weixin_user_info($rts = array()) {

        $t = Common::_return_px();

        $cache = Import::ajincache();

        $cache->SetFunction(__FUNCTION__);

        $cache->SetMode('sitemes' . $t);

        $fn = $cache->fpath(func_get_args());

        if (file_exists($fn) && (mktime() - filemtime($fn) < 10000) && !$cache->GetClose()) {

        } else {

            $wecha_id = $rts['wecha_id'];

            $is_subscribe = $rts['is_subscribe'];

            $nickname = $rts['nickname'];

            $headimgurl = $rts['headimgurl'];

            $cityname = $rts['cityname'];

            $provincename = $rts['provincename'];

            if (!empty($wecha_id) && $is_subscribe == '1' && (empty($nickname) || empty($headimgurl) || empty($cityname) || empty($provincename))) {

                //1、更改关注标识 表user_tuijian，user

                //2、更改用户资料

                //3、关注时间、关注排名等

                $rr = $this->action('common', '_get_appid_appsecret');

                $appid = $rr['appid'];

                $appsecret = $rr['appsecret'];

                $access_token = $this->action('common', '_get_access_token');



                //获取用户信息

                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $wecha_id;

                $con = Import::crawler()->curl_get_con($url);

                $json = json_decode($con);

                $subscribe = $json->subscribe;

                $nickname = isset($json->nickname) ? $json->nickname : '';

                $sex = isset($json->sex) ? $json->sex : '';

                $city = isset($json->city) ? $json->city : '';

                $province = isset($json->province) ? $json->province : '';

                $headimgurl = isset($json->headimgurl) ? $json->headimgurl : '';

                $subscribe_time = isset($json->subscribe_time) ? $json->subscribe_time : '';

                $this->Session->write('User.subscribe', $subscribe);

                setcookie(CFGH . 'USER[SUBSCRIBE]', $subscribe, mktime() + 2592000);

                $dd = array();

                if (!empty($nickname)) $dd['nickname'] = $nickname;

                if (!empty($sex)) $dd['sex'] = $sex;

                if (!empty($city)) $dd['cityname'] = $city;

                if (!empty($province)) $dd['provincename'] = $province;

                if (!empty($headimgurl)) $dd['headimgurl'] = $headimgurl;

                if (!empty($subscribe_time)) $dd['subscribe_time'] = $subscribe_time;

                if (!empty($dd)) {

                    $dd['is_subscribe'] = $json->subscribe;;

                    $uid = $this->Session->read('User.uid');

                    $this->App->update('user', $dd, 'user_id', $uid);

                }

            } //

            $rt = "run";

            $cache->write($fn, $rt, 'rt');

        }

        return true;

    }

    //检查是否已经成功存在推荐

    function check_share_uid($issubscribe = '0') {

        $tid = $this->Session->read('User.tid');

        if (!($tid > 0)) $tid = isset($_COOKIE[CFGH . 'USER']['TID']) ? $_COOKIE[CFGH . 'USER']['TID'] : '0';

        $toid = $this->Session->read('User.to_wecha_id');

        if (!($toid > 0)) $toid = isset($_COOKIE[CFGH . 'USER']['TOOPENID']) ? $_COOKIE[CFGH . 'USER']['TOOPENID'] : '0';

        if (!($tid > 0)) $tid = $toid;

        if (!($toid > 0)) $toid = $tid;

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT id FROM `{$this->App->prefix() }user_tuijian` WHERE uid = '$uid' LIMIT 1";

        $id = $this->App->findvar($sql);

        if (!($id > 0)) {

            $uid = $this->Session->read('User.uid');

            if ($uid == $tid) $tid = 0;

            if ($uid == $toid) $toid = 0;

            $dd = array();

            $dd['share_uid'] = $tid; //分享者uid

            $dd['parent_uid'] = $toid; //关注者分享ID

            $puid = $dd['parent_uid'];

            $duid = 0;

            if ($puid > 0) {

                //检查是否是代理

                $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id = '$puid' LIMIT 1");

                if ($rank == '10') {

                    $duid = $puid;

                } else {

                    //检查推荐的代理ID

                    $duid = $this->App->findvar("SELECT daili_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = '$puid' LIMIT 1");

                }

            }

            $dd['daili_uid'] = $duid;

            $dd['uid'] = $uid;

            $dd['addtime'] = mktime();

            if ($this->App->insert('user_tuijian', $dd)) { //添加推荐用户

                //统计分享 跟 关注数

                if ($issubscribe == '1') {

                    if ($toid > 0) {

                        $sql = "UPDATE `{$this->App->prefix() }user` SET `guanzhu_ucount` = `guanzhu_ucount`+1 WHERE user_id = '$toid'";

                        $this->App->query($sql);

                    }

                    if ($tid > 0) {

                        $sql = "UPDATE `{$this->App->prefix() }user` SET `share_ucount` = `share_ucount`+1 WHERE user_id = '$tid'";

                        $this->App->query($sql);

                    }

                } else {

                    //统计分享用户数

                    if ($tid > 0) {

                        $sql = "UPDATE `{$this->App->prefix() }user` SET `share_ucount` = `share_ucount`+1 WHERE user_id = '$tid'";

                        $this->App->query($sql);

                    }

                }

            }

        }

    }

    //用户后台

    function index() {

        $uid = $this->checked_login();

        $renzheng = $this->App->findvar("SELECT status FROM `{$this->App->prefix() }user_bank` WHERE uid ='{$uid}' LIMIT 1");

        if (!isset($renzheng) || $renzheng != 1) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng');

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}'  AND active='1'  LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_moneys` WHERE uid ='{$uid}' LIMIT 1";

        $rts['usermoney'] = $this->App->findrow($sql);

        if (empty($rt['userinfo'])) {

            die("此账号已经被禁用或者没有激活！");

            session_destroy();

            $this->Session->write('User', null);

            setcookie(CFGH . 'USER[TOOPENID]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[UKEY]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[PASS]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[TID]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[UID]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[CODETIME]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[ISOAUTH]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[APPID]', "", mktime() - 3600);

            setcookie(CFGH . 'USER[APPSECRET]', "", mktime() - 3600);

            exit;

        }

        $this->action('common', 'checkjump');

        $rank = $this->Session->read('User.rank');

        $this->title("结算中心");

        /* $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";

        

          $rt['userinfo'] = $this->App->findrow($sql);

        

          if(empty($rt['userinfo'])){

        

          $this->Session->write('User',null);

        

          setcookie(CFGH.'USER[TOOPENID]', "", mktime()-7200);

        

          setcookie(CFGH.'USER[UKEY]', "", mktime()-7200);

        

          setcookie(CFGH.'USER[PASS]', "", mktime()-7200);

        

          setcookie(CFGH.'USER[TID]', "", mktime()-7200);

        

          setcookie(CFGH.'USER[UID]', "", mktime()-7200);

        

          setcookie(CFGH.'USER[SUBSCRIBE]', "", mktime()-7200);

        

          setcookie(CFGH.'USER[CODETIME]', "", mktime()-7200);

        

          $this->jump(ADMIN_URL);exit;

        

          } */

        /*      $wecha_id_new = $this->get_user_wecha_id_new();

        

        $wecha_id2 = $this->Session->read('User.wecha_id');

        

        if (($wecha_id_new != $wecha_id2 || $wecha_id_new != $rt['userinfo']['wecha_id']) && !empty($wecha_id_new)) {

        

            //更新错误

        

            $access_token = $this->action('common', '_get_access_token');

        

            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $wecha_id_new;

        

            $con = $this->action('common', 'curlGet', $url);

        

            $json = json_decode($con);

        

            $subscribe = $json->subscribe;

        

            $nickname = isset($json->nickname) ? $json->nickname : '';

        

            $sex = isset($json->sex) ? $json->sex : '';

        

            $city = isset($json->city) ? $json->city : '';

        

            $province = isset($json->province) ? $json->province : '';

        

            $headimgurl = isset($json->headimgurl) ? $json->headimgurl : '';

        

            $subscribe_time = isset($json->subscribe_time) ? $json->subscribe_time : '';

        

            $this->Session->write('User.subscribe', $subscribe);

        

            setcookie(CFGH . 'USER[SUBSCRIBE]', $subscribe, mktime() + 2592000);

        

        

        

            $dd = array();

        

            if (!empty($nickname))

        

                $dd['nickname'] = $nickname;

        

            if (!empty($sex))

        

                $dd['sex'] = $sex;

        

            if (!empty($city))

        

                $dd['cityname'] = $city;

        

            if (!empty($province))

        

                $dd['provincename'] = $province;

        

            if (!empty($headimgurl))

        

                $dd['headimgurl'] = $headimgurl;

        

            if (!empty($subscribe_time))

        

                $dd['subscribe_time'] = $subscribe_time;

        

            if (!empty($dd)) {

        

                $dd['wecha_id'] = $wecha_id_new;

        

                $dd['is_subscribe'] = $json->subscribe;

        

                ;

        

        

        

                $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id ='{$wecha_id_new}' ORDER BY user_id ASC LIMIT 1";

        

                $uid = $this->App->findvar($sql);

        

                $this->App->update('user', $dd, 'user_id', $uid);

        

                $this->Session->write('User.uid', $uid);

        

        

        

                $this->Session->write('User.subscribe', $dd['is_subscribe']);

        

                setcookie(CFGH . 'USER[SUBSCRIBE]', $dd['is_subscribe'], mktime() + 2592000);

        

        

        

                $this->Session->write('User.wecha_id', $dd['wecha_id']);

        

                setcookie(CFGH . 'USER[UKEY]', $dd['wecha_id'], mktime() + 2592000);

        

            }

        

        }*/

        $this->_get_weixin_user_info($rt['userinfo']);

        $this->check_share_uid($rt['userinfo']['is_subscribe']);

        if (!defined(NAVNAME)) define('NAVNAME', "结算");

        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->css("css.css");
        
        $this->template($mb . '/user_index');

    }

    //代理申请

    function apply() {

        $uid = $this->checked_login();

        $this->action('common', 'checkjump');

        $this->title("欢迎进入用户后台管理中心" . ' - ' . $GLOBALS['LANG']['site_name']);

        /* 		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' LIMIT 1";

        

          $rt['userinfo'] = $this->App->findrow($sql);

        

          if($rt['userinfo']['user_rank']!='1' && $rt['userinfo']['is_salesmen']=='2'){ //已经申请成功的代理要跳转

        

          $this->jump(ADMIN_URL.'user.php');exit;

        

          }

        

        

        

          $rt['province'] = $this->get_regions(1);  //获取省列表

        

        

        

          //当前用户的收货地址

        

          $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";

        

          $rt['userress'] = $this->App->findrow($sql);

        

        

        

          if($rt['userress']['province']>0) $rt['city'] = $this->get_regions(2,$rt['userress']['province']);  //城市

        

          if($rt['userress']['city']>0) $rt['district'] = $this->get_regions(3,$rt['userress']['city']);  //区

        

        

        

          //介绍

        

          $sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='8' OR keyword LIKE '%创业申请%' LIMIT 1";

        

          $this->set('info',$this->App->findrow($sql)); */

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        if ($rt['userinfo']['user_rank'] != '1') {

            $this->set('fxrank', '1');

            //$this->jump(ADMIN_URL.'user.php'); exit;

            

        } else {

            $this->set('fxrank', '2');

        }

        //介绍

        $sql = "SELECT * FROM `{$this->App->prefix() }wx_article` WHERE keyword LIKE '%申请开店%' LIMIT 1";

        $rt['info'] = $this->App->findrow($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "代理申请");

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/apply');

    }

    //代理中心

    function dailicenter() {

        $this->title("财富中心" . ' - ' . $GLOBALS['LANG']['site_name']);

        if (!defined(NAVNAME)) define('NAVNAME', "财富中心");

        $uid = $this->checked_login();

        $rank = $this->Session->read('User.rank');

        if ($rank == '1' || empty($rank)) {

            //判断级别

            $sql = "SELECT user_rank,is_salesmen FROM `{$this->App->prefix() }user` WHERE user_id = '$uid' LIMIT 1";

            $rls = $this->App->findrow($sql);

            $rank = isset($rls['user_rank']) ? $rls['user_rank'] : '1';

            $is_apply = isset($rls['is_salesmen']) ? $rls['is_salesmen'] : '1';

            if ($rank == '1') {

                $sql = "SELECT * FROM `{$this->App->prefix() }userconfig` WHERE type = 'basic' LIMIT 1";

                $rrL = $this->App->findrow($sql);

                if ($rrL['viewfxset'] == '1') {

                } else {

                    unset($rrL);

                    $this->jump(ADMIN_URL . 'user.php', 0, '您没有权限访问');

                    exit;

                }

            }

            $this->Session->write('User.rank', $rank);

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $sql = "SELECT tb2.level_name FROM `{$this->App->prefix() }user` AS tb1 LEFT JOIN `{$this->App->prefix() }user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid' LIMIT 1";

        $rt['userinfo']['level_name'] = $this->App->findvar($sql); //

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix() }user_money_change` WHERE uid ='{$uid}'";

        $rt['userinfo']['zmoney'] = $this->App->findvar($sql);

        $sql = "SELECT SUM(order_amount) FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE user_id ='{$uid}' AND pay_status = '1'";

        $rt['userinfo']['spzmoney'] = $this->App->findvar($sql);

        $sql = "SELECT SUM(points) FROM `{$this->App->prefix() }user_point_change` WHERE uid ='{$uid}'";

        $rt['userinfo']['points'] = $this->App->findvar($sql);

        $sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix() }user` WHERE is_subscribe ='1' LIMIT 1";

        $rt['gzcount'] = $this->App->findvar($sql);

        $sql = "SELECT tb1.nickname FROM `{$this->App->prefix() }user` AS tb1 LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix() }user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";

        $rt['tjren'] = $this->App->findvar($sql);

        //一级

        $rt['zcount1'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid = '$uid' LIMIT 1");

        //二级

        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";

        $sql.= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL  LIMIT 1";

        $rt['zcount2'] = $this->App->findvar($sql);

        //三级

        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";

        $sql.= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  LIMIT 1";

        $rt['zcount3'] = $this->App->findvar($sql);

        //总用户

        $rt['zcount'] = intval($rt['zcount1']) + intval($rt['zcount2']) + intval($rt['zcount3']);

        //下一级订单

        $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix() }goods_order_info` WHERE parent_uid = '$uid' AND order_status='2' AND user_id!='$uid'";

        $rt['userinfo']['ordercount'] = $this->App->findvar($sql);

        //开通分销的人数

        $sql = "SELECT COUNT(ut.uid) FROM `{$this->App->prefix() }user_tuijian` AS ut LEFT JOIN `{$this->App->prefix() }user` AS u ON ut.uid = u.user_id WHERE ut.parent_uid = '$uid' AND u.user_rank!='1' AND ut.uid!='$uid'";

        $rt['userinfo']['fxcount'] = $this->App->findvar($sql);

        //未有付款佣金

        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix() }user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.pay_status='0' AND tb2.order_status!='4' AND tb2.order_status!='1' AND tb1.money > 0 LIMIT 1";

        $rt['pay1'] = $this->App->findvar($sql);

        //已经付款佣金

        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix() }user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.pay_status='1' AND tb1.money > 0 LIMIT 1";

        $rt['pay2'] = $this->App->findvar($sql);

        //已经收货订单佣金

        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix() }user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.shipping_status='5' AND tb1.money > 0 LIMIT 1";

        $rt['pay3'] = $this->App->findvar($sql);

        //已经取消作废佣金

        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix() }user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND (tb2.order_status='1' OR tb2.pay_status='2') AND tb1.money > 0 LIMIT 1";

        $rt['pay4'] = $this->App->findvar($sql);

        //审核通过的佣金

        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix() }user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.shipping_status='5' AND tb2.pay_status='1' AND tb1.money > 0 LIMIT 1";

        $rt['pay5'] = $this->App->findvar($sql);

        //营业额

        $sql = "SELECT SUM(order_amount) FROM `{$this->App->prefix() }goods_order_info` WHERE parent_uid = '$uid' AND pay_status='1' AND user_id!='$uid'";

        $rt['userinfo']['zordermoney'] = $this->App->findvar($sql);

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/dailicenter');

    }

    //下三级级订单

    function mysuborder($data = array()) {

        $this->title("下级购买详情" . ' - ' . $GLOBALS['LANG']['site_name']);

        if (!defined(NAVNAME)) define('NAVNAME', "下级购买");

        $uid = $this->checked_login();

        $t = $data['t'];

        if ($t == 1) { //一级分销

            

        } elseif ($t == 2) { //二级分销

            

        } elseif ($t == 3) { //三级分销

            

        }

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/v3_mysuborder');

    }

    //平台介绍

    function aboutpt() {

        if (!defined(NAVNAME)) define('NAVNAME', "平台介绍");

        $this->template('aboutpt');

    }

    //积分排行

    function zpoints() {

        $uid = $this->checked_login();

        if (!defined(NAVNAME)) define('NAVNAME', "积分排行");

        $list = 30;

        $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix() }user` WHERE active='1' ORDER BY points_ucount DESC";

        $tt = $this->App->findvar($sql);

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,mypoints,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix() }user` WHERE active='1' ORDER BY points_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";

        $rt['ulist'] = $this->App->find($sql);

        $sql = "SELECT points_ucount,money_ucount,share_ucount,guanzhu_ucount,mypoints FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        //当前排名

        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE active='1' ORDER BY points_ucount DESC,share_ucount DESC,reg_time ASC LIMIT 100";

        $ulist = $this->App->findcol($sql);

        $rt['userinfo']['thisrank'] = 0;

        if (!empty($ulist)) foreach ($ulist as $ks => $vv) {

            if ($uid == $vv) {

                ++$ks;

                $rt['userinfo']['thisrank'] = $ks;

            }

        }

        if ($rt['userinfo']['thisrank'] == '0') {

            if (!empty($ulist)) {

                $rt['userinfo']['thisrank'] = '>100';

            } else {

                $rt['userinfo']['thisrank'] = '0';

            }

        }

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/zpoints');

    }

    function myuser() {

        $uid = $this->checked_login();

        if (!defined(NAVNAME)) define('NAVNAME', "我的好友");

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : '1';

        if (empty($page)) {

            $page = 1;

        }

        $list = 30;

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user` AS tb2";

        $sql.= " ON tb1.uid = tb2.user_id WHERE tb1.parent_uid = '$uid'";

        //$tt = $this->App->findvar($sql);

        //$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);

        $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user` AS tb2";

        $sql.= " ON tb1.uid = tb2.user_id";

        $sql.= " WHERE tb1.parent_uid = '$uid' AND tb2.is_subscribe ='1' ORDER BY tb2.share_ucount DESC,tb2.points_ucount DESC,tb1.id DESC LIMIT $start,$list";

        $rt['lists'] = $this->App->find($sql);

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myuser');

    }

    function youhuijuan() {

        $this->title("我的优惠卷" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        if (isset($_POST) && !empty($_POST)) {

            $key = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

            if (!empty($key)) {

                $sql = "SELECT bonus_id FROM `{$this->App->prefix() }user_coupon_list` WHERE bonus_sn = '$key' AND is_used = '0'";

                $bonus_id = $this->App->findvar($sql);

                if ($bonus_id > 0) {

                    $this->App->insert('user_regcode', array('code' => $key, 'uid' => $uid, 'addtime' => mktime()));

                    $this->App->update('user_coupon_list', array('is_used' => '1', 'user_id' => $uid, 'used_time' => mktime()), 'bonus_sn', $key);

                    $this->jump(ADMIN_URL . 'user.php?act=youhuijuan', 0, '您已成功得到优惠劵！');

                    exit;

                } else {

                    $this->jump(ADMIN_URL . 'user.php?act=youhuijuan', 0, '该优惠码无效或已失效！');

                    exit;

                }

            }

        }

        //优惠劵

        $list = 10;

        $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;

        $start = ($page - 1) * $list;

        $sql = "SELECT tb1.*,tb2.user_id,tb2.used_time,tb2.is_used,tb3.* FROM `{$this->App->prefix() }user_regcode` AS tb1 LEFT JOIN `{$this->App->prefix() }user_coupon_list` AS tb2 ON tb2.bonus_sn = tb1.code";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_coupon_type` AS tb3 ON tb3.type_id = tb2.type_id WHERE tb1.uid = '$uid' AND tb3.send_type='1' ORDER BY tb1.addtime DESC LIMIT $start,$list";

        $this->set('juanlist', $this->App->find($sql));

        $sql = "SELECT COUNT(tb1.rid) FROM `{$this->App->prefix() }user_regcode` AS tb1 LEFT JOIN `{$this->App->prefix() }user_coupon_list` AS tb2 ON tb2.bonus_sn = tb1.code LEFT JOIN `{$this->App->prefix() }user_coupon_type` AS tb3 ON tb3.type_id = tb2.type_id WHERE tb1.uid = '$uid' AND tb3.send_type='1'";

        $tt = $this->App->findvar($sql);

        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $this->set('pagelink', $pagelink);

        if (!defined(NAVNAME)) define('NAVNAME', "我的优惠劵");

        $this->set('rt', $rt);

        $this->template('youhuijuan');

    }

    function xianjinka() {

        $this->title("我的现金卡" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        //现金卡

        $list = 10;

        $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;

        $start = ($page - 1) * $list;

        $sql = "SELECT tb2.user_id,tb2.used_time,tb2.is_used,tb3.* FROM `{$this->App->prefix() }user_coupon_list` AS tb2 LEFT JOIN `{$this->App->prefix() }user_coupon_type` AS tb3 ON tb2.type_id = tb3.type_id";

        $sql.= " WHERE tb2.user_id = '$uid' AND tb3.send_type='3' ORDER BY tb2.used_time DESC LIMIT $start,$list";

        $this->set('juanlist', $this->App->find($sql));

        $sql = "SELECT COUNT(tb2.bonus_id) FROM `{$this->App->prefix() }user_coupon_list` AS tb2 LEFT JOIN `{$this->App->prefix() }user_coupon_type` AS tb3 ON tb2.type_id = tb3.type_id WHERE tb2.user_id = '$uid' AND tb3.send_type='3'";

        $tt = $this->App->findvar($sql);

        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $this->set('pagelink', $pagelink);

        if (!defined(NAVNAME)) define('NAVNAME', "我的现金卡");

        $this->set('rt', $rt);

        $this->template('xianjinka');

    }

    function youhuika() {

        $this->title("我的优惠卡" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        //优惠卡

        $list = 10;

        $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;

        $start = ($page - 1) * $list;

        $sql = "SELECT tb2.user_id,tb2.used_time,tb2.is_used,tb3.* FROM `{$this->App->prefix() }user_coupon_list` AS tb2 LEFT JOIN `{$this->App->prefix() }user_coupon_type` AS tb3 ON tb2.type_id = tb3.type_id";

        $sql.= " WHERE tb2.user_id = '$uid' AND tb3.send_type='2' ORDER BY tb2.used_time DESC LIMIT $start,$list";

        $this->set('juanlist', $this->App->find($sql));

        $sql = "SELECT COUNT(tb2.bonus_id) FROM `{$this->App->prefix() }user_coupon_list` AS tb2 LEFT JOIN `{$this->App->prefix() }user_coupon_type` AS tb3 ON tb2.type_id = tb3.type_id WHERE tb2.user_id = '$uid' AND tb3.send_type='2'";

        $tt = $this->App->findvar($sql);

        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $this->set('pagelink', $pagelink);

        if (!defined(NAVNAME)) define('NAVNAME', "我的优惠卡");

        $this->set('rt', $rt);

        $this->template('youhuika');

    }

    //我的分享

    function myshare() {

        $this->title("我的分享" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $id = isset($_GET['id']) ? $_GET['id'] : '0';

        if ($id > 0) {

            //$this->App->delete('user_tuijian','id',$id);

            //$this->jump(ADMIN_URL.'user.php?act=myshare');exit;

            

        }

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : '1';

        if (empty($page)) {

            $page = 1;

        }

        $list = 30;

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user` AS tb2";

        $sql.= " ON tb1.uid = tb2.user_id WHERE tb1.share_uid = '$uid'";

        $tt = $this->App->findvar($sql);

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user` AS tb2";

        $sql.= " ON tb1.uid = tb2.user_id";

        $sql.= " WHERE tb1.share_uid = '$uid' ORDER BY tb1.id DESC LIMIT $start,$list";

        $rt['lists'] = $this->App->find($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "我的邀请");

        $this->set('rt', $rt);

        $this->template('myshare');

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myshare');

    }

    //调研投票

    function myvotes() {

        $this->title("调研投票" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt = array();

        if (!defined(NAVNAME)) define('NAVNAME', "调研投票");

        $this->set('rt', $rt);

        $this->template('myvotes');

    }

    //我的礼包

    function mygiftbag() {

        $this->title("我领取的礼包" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt = array();

        if (!defined(NAVNAME)) define('NAVNAME', "我领取的礼包");

        $uid = $this->checked_login();

        $sql = "select go.*,gb.bag_name from `{$this->App->prefix() }gift_order` as go left join   `{$this->App->prefix() }gift_bag` as gb on go.bid=gb.bid where go.user_id='$uid'";

        $rt = $this->App->find($sql);

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/mygiftbag');

    }

    //我要晒单

    function mysaidan() {

        $this->title("我的晒单列表" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if ($id > 0) {

            $img = $this->App->findvar("SELECT article_img FROM `{$this->App->prefix() }article` WHERE article_id='$id'");

            if (!empty($img)) {

                Import::fileop()->delete_file(SYS_PATH . $img); //删除图片

                $q = dirname($img);

                $h = basename($img);

                Import::fileop()->delete_file(SYS_PATH . $q . DS . 'thumb_s' . DS . $h);

                Import::fileop()->delete_file(SYS_PATH . $q . DS . 'thumb_b' . DS . $h);

            }

            $this->App->delete('article', 'article_id', $id);

            $this->jump(ADMIN_URL . 'user.php?act=mysaidan');

            exit;

        }

        //排序

        $orderby = ' ORDER BY tb1.vieworder ASC,tb1.`article_id` DESC';

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : '';

        if (empty($page)) {

            $page = 1;

        }

        $list = 5;

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(tb1.article_id) FROM `{$this->App->prefix() }article` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }article_cate` AS tb2";

        $sql.= " ON tb1.cat_id = tb2.cat_id WHERE tb1.uid = '$uid'";

        $tt = $this->App->findvar($sql);

        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $this->set("pages", $pagelink);

        $sql = "SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix() }article` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }article_cate` AS tb2";

        $sql.= " ON tb1.cat_id = tb2.cat_id";

        $sql.= " WHERE tb1.uid = '$uid' {$orderby} LIMIT $start,$list";

        $this->set('lists', $this->App->find($sql));

        if (!defined(NAVNAME)) define('NAVNAME', "我的晒单");

        $this->set('page', $page);

        $this->template('mysaidan');

    }

    function mysaidaninfo() {

        $this->title("我要晒单" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        $rt = array();

        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if ($id > 0) {

            if (!empty($_POST)) {

                $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，', '。', '.'), ',', $_POST['meta_keys']) : "";

                $_POST['uptime'] = time();

                //$_POST['content'] = @str_replace('/photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接

                $_POST['uid'] = $uid;

                $this->App->update('article', $_POST, 'article_id', $id);

                $this->jump(ADMIN_URL . 'user.php?act=mysaidaninfo&id=' . $id, 0, '修改成功！');

                exit;

            }

            $sql = "SELECT * FROM `{$this->App->prefix() }article` WHERE article_id='{$id}'";

            $rt = $this->App->findrow($sql);

            if ($rt['province'] > 0) $rt['ress']['city'] = $this->get_regions(2, $rt['province']); //城市

            if ($rt['city'] > 0) $rt['ress']['district'] = $this->get_regions(3, $rt['city']); //区

            

        } else {

            if (!empty($_POST)) {

                $_POST['addtime'] = time();

                $_POST['uptime'] = time();

                $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，', '。', '.'), ',', $_POST['meta_keys']) : "";

                $_POST['content'] = @str_replace('/photos/', SYS_PHOTOS_URL, $_POST['content']); //替换为绝对路径的链接

                $_POST['uid'] = $uid;

                $this->App->insert('article', $_POST);

                $this->jump(ADMIN_URL . 'user.php?act=mysaidaninfo', 0, '添加成功！');

                exit;

            }

        }

        $rt['ress']['province'] = $this->get_regions(1); //获取省列表

        if (!defined(NAVNAME)) define('NAVNAME', "晒单详情");

        $this->set('id', $id);

        $this->set('rt', $rt);

        $this->set('catids', $this->action('article', 'get_cate_tree', 0, 'about'));

        $this->template('mysaidaninfo');

    }

    function myyuding() {

        $this->title("我的预定" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if ($id > 0) {

            $this->App->delete('user_yuding', 'mes_id', $id);

            $this->jump(ADMIN_URL . 'user.php?act=myyuding');

            exit;

        }

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : '';

        if (empty($page)) {

            $page = 1;

        }

        $list = 10;

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(mes_id) FROM `{$this->App->prefix() }user_yuding` WHERE user_id='$uid'";

        $tt = $this->App->findvar($sql);

        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $this->set("pagelink", $pagelink);

        $sql = "SELECT tb1.*,tb2.nickname FROM `{$this->App->prefix() }user_yuding` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.shop_id  WHERE tb1.user_id='$uid' ORDER BY tb1.mes_id DESC LIMIT $start,$list";

        $this->set('rt', $this->App->find($sql));

        $this->set('page', $page);

        $this->template('myyuding');

    }

    function myyudingdetail() {

        $this->title("我的预定" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $this->jump(ADMIN_URL . 'user.php?act=login', 0, '请先登录！');

            exit;

        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        $sql = "SELECT tb1.*,tb2.nickname FROM `{$this->App->prefix() }user_yuding` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.shop_id  WHERE tb1.mes_id='$id' AND tb1.user_id='$uid'";

        if (!defined(NAVNAME)) define('NAVNAME', "我的预订");

        $this->set('rt', $this->App->findrow($sql));

        $this->template('myyudingdetail');

    }

    function ajax_getorderlist($data = array()) {

        $dt = isset($data['time']) && intval($data['time']) > 0 ? intval($data['time']) : "";

        $status = isset($data['status']) ? trim($data['status']) : "";

        $keyword = isset($data['keyword']) ? trim($data['keyword']) : "";

        $page = isset($data['page']) && intval($data['page'] > 0) ? intval($data['page']) : 1;

        $list = 5;

        //用户订单

        $uid = $this->Session->read('User.uid');

        $w_rt[] = "tb1.user_id = '$uid'";

        if (!empty($dt)) {

            $ts = mktime() - $dt;

            $w_rt[] = "tb1.add_time > '$ts'";

        }

        if (!empty($status)) {

            $st = $this->select_statue($status);

            !empty($st) ? $w_rt[] = $st : "";

        }

        if (!empty($keyword)) {

            $w_rt[] = "(tb2.goods_name LIKE '%" . $keyword . "%' OR tb1.order_sn LIKE '%" . $keyword . "%')";

        }

        $tt = $this->__order_list_count($w_rt); //获取商品的数量

        $rt['order_count'] = $tt;

        $rt['orderpage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_order_page_list', array($status));

        $rt['orderlist'] = $this->__order_list($w_rt, $page, $list);

        $rt['status'] = $status;

        $rt['keyword'] = $keyword;

        $rt['time'] = $dt;

        $this->set('rt', $rt);

        $con = $this->fetch('ajax_orderlist', true);

        die($con);

    }

    ###########################

    //用户订单列表

    function __order_list($w_rt = array(), $page = 1, $list = 5) {

        if (is_array($w_rt)) {

            if (!empty($w_rt)) {

                $w = " WHERE " . implode(' AND ', $w_rt);

            }

        } else {

            $w = " WHERE " . $w_rt;

        }

        if (!$page) $page = 1;

        $start = ($page - 1) * $list;

        $sql = "SELECT distinct tb1.order_id, tb1.order_sn,tb1.sn_id,tb1.shipping_id,tb1.shipping_id_true, tb1.order_status, tb1.shipping_status,tb1.shipping_name ,tb1.pay_name, tb1.pay_status, tb1.add_time,tb1.consignee,tb1.type, (tb1.order_amount + tb1.shipping_fee) AS total_fee FROM `{$this->App->prefix() }goods_order_info` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }goods_order` AS tb2 ON tb1.order_id=tb2.order_id";

        $sql.= " $w ORDER BY tb1.add_time DESC LIMIT $start,$list";

        $orderlist = $this->App->find($sql);

        if (!empty($orderlist)) {

            foreach ($orderlist as $k => $row) {

                $sid = $row['shipping_id_true'];

                $orderlist[$k]['shipping_code'] = $this->App->findvar("SELECT shipping_code FROM `{$this->App->prefix() }shipping_name` WHERE shipping_id = '$sid' LIMIT 1");

                $orderlist[$k]['status'] = $this->get_status($row['order_status'], $row['pay_status'], $row['shipping_status']);

                $orderlist[$k]['op'] = $this->get_option($row['order_id'], $row['order_status'], $row['pay_status'], $row['shipping_status']);

                $sql = "SELECT goods_id,goods_name,goods_bianhao,market_price,goods_price,goods_thumb FROM `{$this->App->prefix() }goods_order` WHERE order_id='$row[order_id]' ORDER BY goods_id";

                $orderlist[$k]['goods'] = $this->App->find($sql);

                $oid = $row['order_id'];

                $passsn = $this->App->findrow("SELECT goods_pass,goods_sn FROM `{$this->App->prefix() }goods_sn` WHERE order_id = '$oid' LIMIT 1");

                $orderlist[$k]['sn'] = isset($passsn['goods_sn']) ? $passsn['goods_sn'] : '';

                $orderlist[$k]['pass'] = isset($passsn['goods_pass']) ? $passsn['goods_pass'] : '';

            }

        }

        return $orderlist;

    }

    function __order_list_count($w_rt = array()) {

        if (is_array($w_rt)) {

            if (!empty($w_rt)) {

                $w = " WHERE " . implode(' AND ', $w_rt);

            }

        } else {

            $w = " WHERE " . $w_rt;

        }

        $sql = "SELECT COUNT(distinct tb1.order_id) FROM `{$this->App->prefix() }goods_order_info` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }goods_order` AS tb2 ON tb1.order_id=tb2.order_id " . $w;

        return $this->App->findvar($sql);

    }

    //订单详情

    function orderinfo($orderid = "") {

        $uid = $this->checked_login();

        $this->action('common', 'checkjump');

        $this->title("欢迎进入用户后台管理中心" . ' - 订单详情 - ' . $GLOBALS['LANG']['site_name']);

        $orderid = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

        if (!($orderid > 0)) {

            $this->jump('user.php?act=myorder');

            exit;

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE order_id='$orderid' AND user_id='$uid'";

        $rt['orderinfo'] = $this->App->findrow($sql);

        if (empty($rt['orderinfo'])) {

            $this->jump(ADMIN_URL . 'user.php?act=myorder');

            exit;

        }

        $sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix() }goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix() }goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$orderid' GROUP BY tb2.rec_id";

        $goodslist = $this->App->find($sql);

        if (!empty($goodslist)) foreach ($goodslist as $k => $row) {

            $rt['goodslist'][$k] = $row;

            $rec_id = $row['rec_id'];

            $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix() }goods_order_address` AS tb1";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";

            $sql.= " WHERE tb1.rec_id='$rec_id'";

            $rt['goodslist'][$k]['ress'] = $this->App->find($sql);

        }

        $status = $this->get_status($rt['orderinfo']['order_status'], $rt['orderinfo']['pay_status'], $rt['orderinfo']['shipping_status']);

        $rt['status'] = explode(',', $status);

        if (!defined(NAVNAME)) define('NAVNAME', "订单详情");

        $this->set('rt', $rt);

        $this->template('user_orderinfo');

    }

    //订单详情

    function orderinfo2014($data = array()) {

        $this->title('订单详情 - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        $orderid = $data['order_id'];

        if (empty($orderid)) {

            $this->jump(ADMIN_URL . 'user.php?act=orderlist');

            exit;

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order` WHERE order_id='$orderid' ORDER BY goods_id";

        $rt['goodslist'] = $this->App->find($sql);

        $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix() }goods_order_info` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";

        $sql.= " WHERE tb1.order_id='$orderid'";

        $rt['orderinfo'] = $this->App->findrow($sql);

        $status = $this->get_status($rt['orderinfo']['order_status'], $rt['orderinfo']['pay_status'], $rt['orderinfo']['shipping_status']);

        $rt['status'] = explode(',', $status);

        if (!defined(NAVNAME)) define('NAVNAME', "订单详情");

        $this->set('rt', $rt);

        //$this->template('user_orderinfo2014');

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_orderinfo2014');

    }

    //选择订单的所在状态

    function select_statue($id = "") {

        if (empty($id)) return "";

        switch ($id) {

            case '-1':

                return "";

            break;

            case '11':

                return "tb1.order_status='0'";

            break;

            case '200':

                return "tb1.order_status='2' AND tb1.shipping_status='0' AND tb1.pay_status='0'";

            break;

            case '210':

                return "tb1.order_status='2' AND tb1.shipping_status='0' AND tb1.pay_status='1'";

            break;

            case '214':

                return "tb1.order_status='2' AND tb1.shipping_status='4' AND tb1.pay_status='1'";

            break;

            case '1':

                return "tb1.order_status='1'";

            break;

            case '4':

                return "tb1.order_status='4'";

            break;

            case '3':

                return "tb1.order_status='3'";

            break;

            case '2':

                return "tb1.pay_status='2'";

            break;

            case '222': //已发货

                return "tb1.shipping_status='2'";

            break;

            default:

                return "";

            break;

        }

    }

    ##############################

    function error_jump() {

        $this->action('common', 'show404tpl');

    }

    //用户订单操作

    function ajax_order_op_user($data = array()) {

        $id = isset($data['id']) ? $data['id'] : 0;

        $op = isset($data['type']) ? $data['type'] : '';

        if (empty($id) || empty($op)) die("传送ID为空！");

        if ($op == "cancel_order") {

            //$this->App->update('goods_order_info_daigou',array('order_status'=>'1'),'order_id',$id);

            $this->App->update('goods_order_info', array('order_status' => '1'), 'order_id', $id);

        } else if ($op == "confirm") {

            //$this->App->update('goods_order_info_daigou',array('shipping_status'=>'5'),'order_id',$id);

            /* 			$uid = $this->Session->read('User.uid');

            

              $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1");

            

              if($rank=='1'){

            

              $this->App->update('user',array('user_rank'=>'12'),'user_id',$uid);

            

            

            

              $this->App->update('user_tuijian',array('daili_uid'=>$uid),'uid',$uid);

            

            

            

              $this->update_user_tree($uid,$uid);

            

            

            

              $this->update_daili_tree($uid);//更新代理关系

            

              } */

            $this->App->update('goods_order_info', array('shipping_status' => '5'), 'order_id', $id);

        } elseif ($op == "tuikuan") { //申请退款

            $this->App->update('goods_order_info', array('order_status' => '5'), 'order_id', $id);

        } elseif ($op == "tuihuo") { //申请退货

            $this->App->update('goods_order_info', array('order_status' => '6'), 'order_id', $id);

        }

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

        }

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

    //代购模式

    function myorder() {

        $uid = $this->checked_login();

        $this->action('common', 'checkjump');

        $this->title("欢迎进入用户后台管理中心" . ' - 我的订单 - ' . $GLOBALS['LANG']['site_name']);

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        if (!($page > 0)) $page = 1;

        $list = 5;

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE user_id='$uid'";

        $tt = $this->App->findvar($sql);

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT * FROM `{$this->App->prefix() }goods_order_info_daigou` WHERE user_id='$uid' ORDER BY order_id DESC LIMIT $start,$list";

        $lists = $this->App->find($sql);

        $rt['lists'] = array();

        if (!empty($lists)) foreach ($lists as $k => $row) {

            $rt['lists'][$k] = $row;

            $oid = $row['order_id'];

            $rt['lists'][$k]['gimg'] = $this->App->findcol("SELECT goods_thumb FROM `{$this->App->prefix() }goods_order_daigou` WHERE order_id='$oid'");

            $rt['lists'][$k]['status'] = $this->get_status($row['order_status'], $row['pay_status'], $row['shipping_status']);

            $rt['lists'][$k]['op'] = $this->get_option($row['order_id'], $row['order_status'], $row['pay_status'], $row['shipping_status']);

        }

        if (!defined(NAVNAME)) define('NAVNAME', "我的订单");

        $this->set('rt', $rt);

        $this->set('page', $page);

        $this->template('user_myorder');

    }

    //订单列表

    function orderlist() {

        $this->title('交易明细 - ' . $GLOBALS['LANG']['site_name']);

        $dt = isset($_GET['dt']) && intval($_GET['dt']) > 0 ? intval($_GET['dt']) : "";

        $status = isset($_GET['status']) ? trim($_GET['status']) : "";

        $keyword = isset($_GET['kk']) ? trim($_GET['kk']) : "";

        $uid = $this->checked_login();

        //用户订单

        $w_rt[] = "tb1.user_id = '$uid'";

        if (!empty($dt)) {

            $w_rt[] = "tb1.add_time < '$dt'";

        }

        if (!empty($status)) {

            $st = $this->select_statue($status);

            !empty($st) ? $w_rt[] = $st : "";

        }

        if (!empty($keyword)) {

            $w_rt[] = "(tb2.goods_name LIKE '%" . $keyword . "%' OR tb1.order_sn LIKE '%" . $keyword . "%')";

        }

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        if (!($page > 0)) $page = 1;

        $list = 5;

        $tt = $this->__order_list_count($w_rt); //获取商品的数量

        $rt['order_count'] = $tt;

        $rt['orderpage'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $rt['orderlist'] = $this->__order_list($w_rt, $page, $list);

        $rt['status'] = $status;

        $rt['userinfo']['user_id'] = $this->Session->read('User.uid');

        //在线报名订单

        $sql = "SELECT tb1.*,tb2.title,tb2.img,u.nickname FROM `{$this->App->prefix() }cx_baoming_order` AS tb1 LEFT JOIN `{$this->App->prefix() }cx_baoming` AS tb2 ON tb2.id = tb1.bid LEFT JOIN `{$this->App->prefix() }user` AS u ON u.user_id = tb1.user_id WHERE tb1.user_id = '$uid' ORDER BY tb1.id DESC LIMIT 10";

        $rt['bmorder'] = $this->App->find($sql);

        /* 		$sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2'";

        

          $rt['userinfo']['success_ordercount'] = $this->App->findvar($sql); //成功订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND pay_status='0'";

        

          $rt['userinfo']['pay_ordercount'] = $this->App->findvar($sql); //待支付订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='2'";

        

          $rt['userinfo']['shopping_ordercount'] = $this->App->findvar($sql); //待发货订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid'";

        

          $rt['userinfo']['all_ordercount'] = $this->App->findvar($sql); //所有订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND (tb6.shipping_status='2' OR tb6.pay_status='0' OR tb6.order_status='0')";

        

          $rt['userinfo']['daichuli_ordercount'] = $this->App->findvar($sql); //待处理订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='5'";

        

          $rt['userinfo']['haicheng_ordercount'] = $this->App->findvar($sql); //已完成订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='1'";

        

          $rt['userinfo']['quxiao_ordercount'] = $this->App->findvar($sql); //已取消订单

        

        

        

          $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='2'";

        

          $rt['userinfo']['yifahuo_ordercount'] = $this->App->findvar($sql); //已发货

        

        

        

          $sql = "SELECT COUNT(og.goods_id) FROM `{$this->App->prefix()}order_goods` AS og";

        

          $sql .=" LEFT JOIN `{$this->App->prefix()}order_goods` AS oi ON og.order_id = oi.order_id";

        

          $sql .=" WHERE oi.shipping_status='5' AND oi.user_id='$uid' AND og.goods_id NOT IN(SELECT id_value FROM `{$this->App->prefix()}comment` WHERE user_id='$uid')";

        

          $rt['userinfo']['need_comment_count'] = $this->App->findvar($sql); */

        //print_r($rt);

        //商品分类列表

        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        if (!defined(NAVNAME)) define('NAVNAME', "交易明细");

        $this->set('rt', $rt);

        $this->set('page', $page);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_orderlist');

    }

    //申请退款退货

    function apply_tk_or_th($data = array()) {

        $tname = $data['tt'] == 'tuikuan' ? '申请退款' : '申请退货';

        $id = $data['oid'];

        if (!empty($_POST) && in_array($data['tt'], array('tuihuo', 'tuikuan')) && $id > 0) {

            if ($data['tt'] == "tuikuan") { //申请退款

                $this->App->update('goods_order_info', array('order_status' => '5', 'orderdesc' => $_POST['orderdesc'], 'ordertxt' => $_POST['ordertxt']), 'order_id', $id);

            } elseif ($data['tt'] == "tuihuo") { //申请退货

                $this->App->update('goods_order_info', array('order_status' => '6', 'orderdesc' => $_POST['orderdesc'], 'ordertxt' => $_POST['ordertxt']), 'order_id', $id);

            }

            $this->jump(ADMIN_URL . 'user.php?act=orderlist', 0, '申请成功，等待审核');

            exit;

        }

        if (!defined(NAVNAME)) define('NAVNAME', $tname);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_order_apply_tk_or_th');

    }

    function myinfos($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "我的资料");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myinfos');

    }

    function my_shop() {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        if ($rt['userinfo']['user_rank'] != 10) {

            die("您无法进行此操作！");

        }

        $rt['province'] = $this->get_regions(1); //获取省列表

        //当前用户的收货地址

        $sql = "SELECT * FROM `{$this->App->prefix() }user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";

        $rt['userress'] = $this->App->findrow($sql);

        if ($rt['userress']['province'] > 0) $rt['city'] = $this->get_regions(2, $rt['userress']['province']); //城市

        if ($rt['userress']['city'] > 0) $rt['district'] = $this->get_regions(3, $rt['userress']['city']); //区

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "我的店铺");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/v2_myshop');

    }

    function myinfos_u($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $rt['province'] = $this->get_regions(1); //获取省列表

        //当前用户的收货地址

        $sql = "SELECT * FROM `{$this->App->prefix() }user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";

        $rt['userress'] = $this->App->findrow($sql);

        if ($rt['userress']['province'] > 0) $rt['city'] = $this->get_regions(2, $rt['userress']['province']); //城市

        if ($rt['userress']['city'] > 0) $rt['district'] = $this->get_regions(3, $rt['userress']['city']); //区

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "我的注册资料");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myinfos_u');

    }

    function myinfos_user($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "设置用户名和密码");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myinfos_user');

    }

    function myinfos_bd_user($data = array()) {

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "绑定平台用户名和密码");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myinfos_bd_user');

    }

    function ajax_set_account_save($data = array()) {

        $json = Import::json();

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $result = array('error' => 3, 'message' => '先您先登录!');

            die($json->encode($result));

        }

        $result = array('error' => 2, 'message' => '传送的数据为空!');

        if (empty($data['fromAttr'])) die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象

        unset($data);

        $datas['user_name'] = $fromAttr->user_name;

        $sql = "SELECT setcount FROM `{$this->App->prefix() }user` WHERE   user_id='$uid' LIMIT 1";

        $setcount = $this->App->findvar($sql);

        if ($setcount > 0) {

            $result = array('error' => 4, 'message' => '您已经设置过登录账号和密码！');

            die($json->encode($result));

        }

        $datas['password'] = $fromAttr->pass;

        if (empty($datas['user_name'])) {

            $result = array('error' => 4, 'message' => '填写登陆账号！');

            die($json->encode($result));

        }

        if (empty($datas['password'])) {

            $result = array('error' => 4, 'message' => '请输入6位密码！');

            die($json->encode($result));

        }

        $datas['password'] = md5($datas['password']);

        $datas['setcount'] = 1;

        //检测该号码是否存在

        $user_name = $datas['user_name'];

        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE  user_name = '$user_name' AND user_id!='$uid' LIMIT 1";

        $id = $this->App->findvar($sql);

        if ($id > 0) {

            $result = array('error' => 4, 'message' => '该登陆账号已经被使用！');

            die($json->encode($result));

        }

        if ($this->App->update('user', $datas, 'user_id', $uid)) {

            unset($datas);

            $result = array('error' => 5, 'message' => '设置成功!');

            die($json->encode($result));

        }

        ############################

        //if($this->App->update('user',$datas,'user_id',$uid)){

        $result = array('error' => 10, 'message' => '设置成功!');

        //}else{

        //$result = array('error' => 2, 'message' => '无法更新!');

        //}

        die($json->encode($result));

    }

    function ajax_bd_account_save($data = array()) {

        $json = Import::json();

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $result = array('error' => 3, 'message' => '先您先登录!');

            die($json->encode($result));

        }

        $result = array('error' => 2, 'message' => '传送的数据为空!');

        if (empty($data['fromAttr'])) die($json->encode($result));

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE   user_id='$uid' LIMIT 1";

        $user = $this->App->findrow($sql);

        if ($user['setcount'] > 0) {

            $result = array('error' => 4, 'message' => '您已经绑定过平台账号和密码！');

            die($json->encode($result));

        }

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象

        unset($data);

        $datas['user_name'] = $fromAttr->user_name;

        $datas['password'] = $fromAttr->pass;

        $bdtype = $fromAttr->bdtype;

        if (empty($datas['user_name'])) {

            $result = array('error' => 4, 'message' => '请填写平台账号！');

            die($json->encode($result));

        }

        if (empty($datas['password'])) {

            $result = array('error' => 4, 'message' => '请输入平台密码！');

            die($json->encode($result));

        }

        $datas['password'] = md5($datas['password']);

        if (empty($bdtype)) {

            $result = array('error' => 4, 'message' => '请输入选择保留账号！');

            die($json->encode($result));

        }

        //检测该平台账号密码是否正确

        $user_name = $datas['user_name'];

        $password = $datas['password'];

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_name = '$user_name' AND password='$password'  LIMIT 1";

        $ptuser = $this->App->findrow($sql);

        $ptid = $ptuser['user_id'];

        if (!$ptid) {

            $result = array('error' => 4, 'message' => '平台账号密码不正确！');

            die($json->encode($result));

        }

        //更新两个账号各自的等级关系

        //微信账号

        //  $this->update_daili_tree($uid);

        //  $this->update_daili_tree($ptid);

        if ($bdtype == 1) {

            //保留微信账号

            //修改下级

            $sql = "update `{$this->App->prefix() }user_tuijian` set parent_uid='$uid',share_uid='$uid' where parent_uid='$ptid'";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_tuijian_fx`  set p1_uid='$uid'  where p1_uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_tuijian_fx`  set p2_uid='$uid'  where p2_uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_tuijian_fx`  set p3_uid='$uid'  where p3_uid=$ptid";

            $this->App->query($sql);

            //修改微信账号

            $sql = "update `{$this->App->prefix() }user`  set  bdcount=bdcount+1  " . ",user_money=user_money+" . $ptuser['user_money'] . ",pay_points=pay_points+" . $ptuser['pay_points'] . ",rank_points=rank_points+" . $ptuser['rank_points'] . ",share_ucount=share_ucount+" . $ptuser['share_ucount'] . ",guanzhu_ucount=guanzhu_ucount+" . $ptuser['guanzhu_ucount'] . ",points_ucount=points_ucount+" . $ptuser['points_ucount'] . ",money_ucount=money_ucount+" . $ptuser['money_ucount'] . ",mymoney=mymoney+" . $ptuser['mymoney'] . ",mypoints=mypoints+" . $ptuser['mypoints'] . "  where user_id=$uid";

            $this->App->query($sql);

            //修改被绑定账号

            $sql = "update `{$this->App->prefix() }user`  set wecha_id=' ',nickname='',active=0,is_subscribe=0  where user_id=$ptid";

            $this->App->query($sql);

            //修改boming

            $sql = "update `{$this->App->prefix() }cx_baoming_order`  set user_id='$uid',old_user_id='$ptid'  where user_id=$ptid";

            $this->App->query($sql);

            //修改礼品包

            $sql = "update `{$this->App->prefix() }gift_order`  set user_id='$uid',old_user_id='$ptid'  where user_id=$ptid";

            $this->App->query($sql);

            //修改订单所属人

            $sql = "update `{$this->App->prefix() }goods_order_info`  set user_id='$uid',old_user_id='$ptid'  where user_id=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set daili_uid='$uid'  where daili_uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid='$uid'  where parent_uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid2='$uid'  where parent_uid2=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid3='$uid'  where parent_uid3=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid4='$uid'  where parent_uid4=$ptid";

            $this->App->query($sql);

            //修改会员资金变动记录

            $sql = "update `{$this->App->prefix() }user_money_change`  set uid='$uid'  where uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_change`  set buyuid='$uid'  where buyuid=$ptid";

            $this->App->query($sql);

            //分成记录

            $sql = "update `{$this->App->prefix() }user_money_record`  set uid='$uid'  where uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_record`  set p_uid1='$uid'  where p_uid1=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_record`  set p_uid2='$uid'  where p_uid2=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_record`  set p_uid3='$uid'  where p_uid3=$ptid";

            $this->App->query($sql);

            //gz_user_point_change

            $sql = "update `{$this->App->prefix() }user_point_change`  set uid='$uid'  where uid=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_point_change`  set subuid='$uid'  where subuid=$ptid";

            $this->App->query($sql);

            //gz_user_salesmen_brand

            $sql = "update `{$this->App->prefix() }user_salesmen_brand`  set uid='$uid'  where uid=$ptid";

            $this->App->query($sql);

            //share

            $sql = "update `{$this->App->prefix() }user_share`  set uid='$uid',old_user_id='$ptid'   where uid=$ptid";

            $this->App->query($sql);

            //user_team

            $sql = "update `{$this->App->prefix() }user_team`  set user_id='$uid',old_user_id='$ptid'   where user_id=$ptid";

            $this->App->query($sql);

            //用户评论

            $sql = "update `{$this->App->prefix() }comment`  set user_id='$uid',old_user_id='$ptid'   where user_id=$ptid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }comment`  set parent_id='$uid',old_parent_id='$ptid'   where parent_id=$ptid";

            $this->App->query($sql);

            // $this->update_daili_tree($uid);

            

        } elseif ($bdtype == 2) {

            //保留平台账号

            $sql = "update `{$this->App->prefix() }user_tuijian` set parent_uid='$ptid',share_uid='$ptid' where parent_uid='$uid'";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_tuijian_fx`  set p1_uid='$ptid'  where p1_uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_tuijian_fx`  set p2_uid='$ptid'  where p2_uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_tuijian_fx`  set p3_uid='$ptid'  where p3_uid=$uid";

            $this->App->query($sql);

            //将微信的wxid对应   //合并账号余额等

            $sql = "update `{$this->App->prefix() }user`  set wecha_id='" . $user['wecha_id'] . "',nickname='" . $user['nickname'] . "',subscribe_time='" . $user['subscribe_time'] . "',subscribe_rank='" . $user['subscribe_rank'] . "',is_subscribe=1,bdcount=bdcount+1,active='1'" . ",user_money=user_money+" . $user['user_money'] . ",pay_points=pay_points+" . $user['pay_points'] . ",rank_points=rank_points+" . $user['rank_points'] . ",share_ucount=share_ucount+" . $user['share_ucount'] . ",guanzhu_ucount=guanzhu_ucount+" . $user['guanzhu_ucount'] . ",points_ucount=points_ucount+" . $user['points_ucount'] . ",money_ucount=money_ucount+" . $user['money_ucount'] . ",mymoney=mymoney+" . $user['mymoney'] . ",mypoints=mypoints+" . $user['mypoints'] . "  where user_id=$ptid";

            $this->App->query($sql);

            //被绑定账号修改为无法登录

            $sql = "update `{$this->App->prefix() }user`  set wecha_id='',nickname='',active=0,is_subscribe=0  where user_id=$uid";

            $this->App->query($sql);

            //修改boming

            $sql = "update `{$this->App->prefix() }cx_baoming_order`  set user_id='$ptid',old_user_id='$uid'  where user_id=$uid";

            $this->App->query($sql);

            //修改礼品包

            $sql = "update `{$this->App->prefix() }gift_order`  set user_id='$ptid',old_user_id='$uid'  where user_id=$uid";

            $this->App->query($sql);

            //修改订单所属人

            $sql = "update `{$this->App->prefix() }goods_order_info`  set user_id='$ptid',old_user_id='$uid'  where user_id=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set daili_uid='$ptid'  where daili_uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid='$ptid'  where parent_uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid2='$ptid'  where parent_uid2=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid3='$ptid'  where parent_uid3=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }goods_order_info`  set parent_uid4='$ptid'  where parent_uid4=$uid";

            $this->App->query($sql);

            //修改会员资金变动记录

            $sql = "update `{$this->App->prefix() }user_money_change`  set uid='$ptid'  where uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_change`  set buyuid='$ptid'  where buyuid=$uid";

            $this->App->query($sql);

            //分成记录

            $sql = "update `{$this->App->prefix() }user_money_record`  set uid='$ptid'  where uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_record`  set p_uid1='$ptid'  where p_uid1=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_record`  set p_uid2='$ptid'  where p_uid2=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_money_record`  set p_uid3='$ptid'  where p_uid3=$uid";

            $this->App->query($sql);

            //gz_user_point_change

            $sql = "update `{$this->App->prefix() }user_point_change`  set uid='$ptid'  where uid=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }user_point_change`  set subuid='$ptid'  where subuid=$uid";

            $this->App->query($sql);

            //gz_user_salesmen_brand

            $sql = "update `{$this->App->prefix() }user_salesmen_brand`  set uid='$ptid'  where uid=$uid";

            $this->App->query($sql);

            //share

            $sql = "update `{$this->App->prefix() }user_share`  set uid='$ptid',old_user_id='$uid'   where uid=$uid";

            $this->App->query($sql);

            //user_team

            $sql = "update `{$this->App->prefix() }user_team`  set user_id='$ptid',old_user_id='$uid'   where user_id=$uid";

            $this->App->query($sql);

            //用户评论

            $sql = "update `{$this->App->prefix() }comment`  set user_id='$ptid',old_user_id='$uid'   where user_id=$uid";

            $this->App->query($sql);

            $sql = "update `{$this->App->prefix() }comment`  set parent_id='$ptid',old_parent_id='$uid'   where parent_id=$uid";

            $this->App->query($sql);

            //模拟登录

            $sql = "SELECT password,user_id,last_login,active,user_rank,mobile_phone,wecha_id,user_name FROM `{$this->App->prefix() }user` WHERE  user_id=$ptid AND active='1' LIMIT 1";

            $rt = $this->App->findrow($sql);

            $this->Session->write('User.username', $rt['user_name']);

            $this->Session->write('User.uid', $rt['user_id']);

            $this->Session->write('User.active', '1');

            $this->Session->write('User.rank', $rt['user_rank']);

            $this->Session->write('User.ukey', $rt['wecha_id']);

            $this->Session->write('User.addtime', mktime());

            //写入cookie

            setcookie(CFGH . 'USER[UKEY]', $rt['wecha_id'], mktime() + 2592000);

            setcookie(CFGH . 'USER[UID]', $rt['user_id'], mktime() + 2592000);

            //  $rs=   $this->update_daili_tree($ptid);

            

        }

        /* if ($this->App->update('user', $datas, 'user_id', $uid)) {

        

          unset($datas);

        

          $result = array('error' => 5, 'message' => '更新成功!');

        

          die($json->encode($result));

        

          }

        

        */

        $result = array('error' => 5, 'message' => '绑定成功!');

        die($json->encode($result));

    }

    function myinfos_s($data = array()) {

        $uid = $this->checked_login();

        $rt['province'] = $this->get_regions(1); //获取省列表

        $rt['iid'] = $id = isset($data['id']) ? $data['id'] : 0;

        //当前用户的收货地址

        /* $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='0'";

        

          $rt['userress'] = $this->App->find($sql);

        

          if (!empty($rt['userress'])) {

        

          foreach ($rt['userress'] as $row) {

        

          $rt['city'][$row['address_id']] = $this->get_regions(2, $row['province']);  //城市

        

          $rt['district'][$row['address_id']] = $this->get_regions(3, $row['city']);  //区

        

          }

        

          } */

        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix() }user_address` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";

        $sql.= " WHERE tb1.user_id='$uid' AND tb1.is_own = '0' ORDER BY tb1.address_id ASC";

        $rt['userress'] = $this->App->find($sql);

        if ($id) {

            $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix() }user_address` AS tb1";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";

            $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";

            $sql.= " WHERE tb1.user_id='$uid'  AND tb1.is_own = '0' and tb1.address_id =$id ";

            $rt['userressinfo'] = $userressinfo = $this->App->findrow($sql);

            $rt['city'] = $this->get_regions(2, $userressinfo['province']); //城市

            $rt['district'] = $this->get_regions(3, $userressinfo['city']); //区

            

        }

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "我的收货资料");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myinfos_s');

    }

    function myinfos_b($data = array()) {

        $this->title("实名认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$uid' AND active='1' LIMIT 1";

        $rt = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid='$uid' LIMIT 1";

        $rts = $this->App->findrow($sql);

        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/myinfos_b');

    }

    /* 发送手机验证码*/

    function getcodes() {

        //	$uid = $this->checked_instead_login();

        //$this->Session->write('adminname', $rt['adminname']);//写入资料到session

        $mobile_phone = trim($_REQUEST['mobile']);

        if (empty($mobile_phone)) {

            exit("手机号不能为空");

            return;

        }

        // if (preg_match("/1[3458]{1}\d{9}$/", $mobile_phone)) {

        //        } else {

        //		exit("手机号格式不正确");

        //		return;

        //        }

        //检查该手机是否已经使用了

        //  $sql = "SELECT uid FROM `{$this->App->prefix()}user_bank` WHERE mobile='".$mobile_phone."'";

        //        $uuid = $this->App->findvar($sql);

        //        	if ($uuid > 0) {

        // 		exit("抱歉，该手机号码已经被使用了");

        //		return;

        //        }

        //开始短信发送流程

        //读取session里面记录的短信发送时间

        $last_send_time = $this->Session->read('User.last_send_time');

        $create_time = $this->Session->read('User.sms_create_time');

        $sms_count = $this->Session->read('User.sms_count');

        // 每天每个手机号最多发送的验证码数量

        $max_sms_count = 10;

        // 发送最多验证码数量的限制时间，默认为24小时

        $max_sms_count_time = 60 * 60 * 24;

        /*	if(empty($last_send_time)){

        

        $last_send_time = time();

        

        $this->Session->write('User.last_send_time', $last_send_time);//写入资料到session

        

        $last_send_time = $this->Session->read('User.last_send_time');

        

        }*/

        if (empty($create_time) || time() - $create_time > $max_sms_count_time) {

            $new_create_time = time();

            $this->Session->write('User.sms_create_time', $new_create_time); //写入资料到session

            $create_time = $this->Session->read('User.sms_create_time');

        }

        if (empty($sms_count)) {

            $sms_count = 0;

            $this->Session->write('User.sms_count', $sms_count); //写入资料到session

            $sms_count = $this->Session->read('User.sms_count');

        }

        if ((time() - $last_send_time) < 60) {

            echo ("每60秒内只能发送一次短信验证码，请稍候重试");

            return;

        } else if (time() - $create_time < $max_sms_count_time && $sms_count > $max_sms_count) {

            echo ("您发送验证码太过于频繁，请稍后重试！");

            return;

        }

        // 生成6位短信验证码

        $mobile_code = get_mobile_code(); //rand(100000, 999999);

        // 短信内容

        // $content = "验证码：" . $mobile_code . "（客服绝不会以任何理由索取此验证码，切勿告知他人），请在页面中输入以完成验证。";

        $result = $this->sendSMSS($mobile_phone, $mobile_code);
        // file_put_contents('./0c.txt',$result);

        //$result = true;
        if ($result == "true") {

            $sms_count++;

            $last_send_time = time();

            $this->Session->write('User.last_send_time', $last_send_time); //写入资料到session

            $this->Session->write('User.sms_count', $sms_count); //写入资料到session

            $this->Session->write('User.yz_code', $mobile_code); //写入资料到session

            echo 'ok';

        } else {

            echo "验证码发送失败";

        }

    }
    function Send($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }
    //将 xml数据转换为数组格式。
    function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }
    /*
    *sendSMS_f
    */
    function sendSMSS($mobile_phone, $mobile_code)
    {
        //短信接口地址
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        //获取手机号
        $mobile = $mobile_phone;
        // //获取验证码
        // $send_code = $_POST['send_code'];
        
        if(empty($mobile)){
            exit('手机号码不能为空');
        }

        $post_data = "account=C77277178&password=74682c49f3875af4e9f68fb16e3c9ec9&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        //用户名是登录用户中心->验证码短信->产品总览->APIID
        //查看密码请登录用户中心->验证码短信->产品总览->APIKEY

        $gets =  $this->xml_to_array($this->Send($post_data, $target));
        if($gets['SubmitResult']['code']==2){
            $_SESSION['mobile'] = $mobile;
            return 'true';
        }else{
            return $gets['SubmitResult']['msg'];
        }

    }

    /* 发送手机验证码*/

    function getcodes_instead() {

        //$client = $_SERVER['HTTP_USER_AGENT'];

        //

        ////用php自带的函数strpos来检测是否是微信端

        //if (strpos($client , 'MicroMessenger') === false) {

        //    die("请在微信端打开");

        //	exit;

        //}

        $uid = $this->checked_instead_login();

        //$this->Session->write('adminname', $rt['adminname']);//写入资料到session

        $mobile_phone = trim($_REQUEST['mobile']);

        if (empty($mobile_phone)) {

            exit("手机号不能为空");

            return;

        }

        // if (preg_match("/1[3458]{1}\d{9}$/", $mobile_phone)) {

        //        } else {

        //		exit("手机号格式不正确");

        //		return;

        //        }

        //开始短信发送流程

        //读取session里面记录的短信发送时间

        $last_send_time = $this->Session->read('User.instead_last_send_time');

        $create_time = $this->Session->read('User.instead_sms_create_time');

        $sms_count = $this->Session->read('User.instead_sms_count');

        // 每天每个手机号最多发送的验证码数量

        $max_sms_count = 10;

        // 发送最多验证码数量的限制时间，默认为24小时

        $max_sms_count_time = 60 * 60 * 24;

        /*	if(empty($last_send_time)){

        

        $last_send_time = time();

        

        $this->Session->write('User.last_send_time', $last_send_time);//写入资料到session

        

        $last_send_time = $this->Session->read('User.last_send_time');

        

        }*/

        if (empty($create_time) || time() - $create_time > $max_sms_count_time) {

            $new_create_time = time();

            $this->Session->write('User.instead_sms_create_time', $new_create_time); //写入资料到session

            $create_time = $this->Session->read('User.instead_sms_create_time');

        }

        if (empty($sms_count)) {

            $sms_count = 0;

            $this->Session->write('User.instead_sms_count', $sms_count); //写入资料到session

            $sms_count = $this->Session->read('User.instead_sms_count');

        }

        if ((time() - $last_send_time) < 60) {

            echo ("每60秒内只能发送一次短信验证码，请稍候重试");

            return;

        } else if (time() - $create_time < $max_sms_count_time && $sms_count > $max_sms_count) {

            echo ("您发送验证码太过于频繁，请稍后重试！");

            return;

        }

        // 生成6位短信验证码

        $mobile_code = get_mobile_code(); //rand(100000, 999999);

        // 短信内容

        $content = "验证码：" . $mobile_code . "（客服绝不会以任何理由索取此验证码，切勿告知他人），请在页面中输入以完成验证。";

        //file_put_contents('./0c.txt',$content);

        $result = sendSMS($mobile_phone, $content);

        //$result = true;

        if ($result == "true") {

            $sms_count++;

            $last_send_time = time();

            $this->Session->write('User.instead_last_send_time', $last_send_time); //写入资料到session

            $this->Session->write('User.instead_sms_count', $sms_count); //写入资料到session

            $this->Session->write('User.instead_yz_code', $mobile_code); //写入资料到session

            echo 'ok';

        } else {

            echo "验证码发送失败";

        }

    }

    function validation_yz_code($arr = array()) {

        $mobile = $arr['mobile'];

        $yz_code = $arr['yz_code'];
        
        $verfiy_yz_code = $this->Session->read('User.yz_code');

        if ($yz_code != $verfiy_yz_code) {

            echo '验证码错误！';

            exit;

        } else {

            echo "success";

            exit;

        }

    }
    //银行卡三要素验证
        function kft_bank_verification($card){
            header("Content-type:text/html; charset=UTF-8");
            require_once('lib/Sign.php');

            $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank_info` WHERE id=".$card['bank']." LIMIT 1");

            $custBankNo = substr($bank['bankno'],0, 7);

            $orderNo = 'vrf'.time();

            $bs_params = array(
                'service' => 'gbp_threeMessage_verification',
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

                "merchantId" => "2018032100095306",
                "productNo"  => "GBPTM001",
                "orderNo" => $orderNo,
                "custBankNo" => $custBankNo,//客户银行卡行别
                "custName"   => $card['name'],
                "custBankAccountNo" => $card['bank_no'],//客户银行账户号
                "custAccountCreditOrDebit" => "2",//客户账户借记贷记类型 2 信用卡
                "custCertificationType" => "0",//客户证件类型 0 身份证
                "custID" => $card['idcard'], //证件号
            );
            $params = array_merge($bs_params, $yw_params);
            
            error_log('['. date('Y-m-d H:m:s') .'] request message:'."\n".var_export($params,true)."\n\n",3,'./app/user/kft_error_log/'.'verify_'.date('Y-m-d').'.log');

            $pfx_path = ADMIN_URL.'app/user/account/pfx.pfx';

            //测试url
            $request_trade_url = "https://218.17.35.123:6443/gateway/nonbatch";

            $sign = new Sign($pfx_path, '123456');
            //普通交易请求
            $sign_data = $sign->sign_data($params);

            // echo $sign_data;
            $response_data = $sign->request_kft($params, $sign_data, $request_trade_url, false);

            error_log('['. date('Y-m-d H:m:s') .'] response message:'."\n".var_export($response_data,true)."\n\n"."<------------------------------------------------------------------------------------------------------------->"."\n",3,'./app/user/kft_error_log/'.'verify_'.date('Y-m-d').'.log');
            return json_decode($response_data,true);

        }

    function bk_confirm_instead() {

        $uid = $this->checked_instead_login();

        $card['uid'] = $uid;

        $card['name'] = $_POST['name'];

        $card['idcard'] = $_POST['idcard'];

        $card['bank_no'] = $_POST['bank_no'];

        $card['mobile'] = $_POST['mobile'];

        $card['bank'] = $_POST['bank'];

        $card['valid'] = $_POST['valid'];

        $card['cvn2'] = $_POST['cvn2'];

        $user_card_instead = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid=" . $uid . " and bank_no='" . $card['bank_no'] . "' limit 1");

        if ($user_card_instead) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '此卡已绑定！');

            exit;

            // $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '绑卡成功，请设置还款计划！');

            //                        exit;

            
        }
        

        //if ($uid == 1) {

        // $this->hljc_merchant($card); //提交绑卡时进件（汇联金创）

        // }

        /* sleep(10);

        

        

        

        $result = $this->ruzhu_query($uid,$card);

        

        

        

        if($result != 1){

        

        $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, '绑卡失败,请重新绑定！');

        

                        exit;

        

        }

        

        

        

        

        

        

        

        

        

        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $uid . " limit 1");

        

        

        

        $rts = $this->_get_payinfo(20);

        

        $pay = unserialize($rts['pay_config']);

        

        

        

        

        

        

        

        $key = $this->random_string(16, $max = FALSE);

        

        $xml = '';

        

        $xml = '

        

        <merchant>

        

        <head>

        

        <version>1.0.0</version>

        

        <merchantId>'.$pay['pay_no'].'</merchantId>

        

        <msgType>01</msgType>

        

        <tranCode>IFP001</tranCode>

        

        <reqMsgId>DH' . date('Ymdhis', time()) . $uid . '</reqMsgId>

        

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

        

        

        

        error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . iconv('UTF-8', 'GBK', var_export($xml, true)) . "\n\n", 3, './app/user/instead/bangka_' . date('Y-m-d') . '.log');

        

        

        

        $encryptData = $this->encrypt($xml, $key);

        

        $signData = $this->rsaSign($xml, './app/user/549440148160026.pem');

        

        $encyrptKey = $this->rsasign_public($key, './app/user/549440148160026_pub.pem');

        

        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'merchantId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => 'IFP001', 'callBack' => 'http://ws.weishuapay.com/m/zhifu.php');

        

        //var_dump($postdata);

        

        //  $post_string = "encryptData=".$encryptData."&encryptKey=".$encyrptKey."&merchantId=102100000125&signData=".$signData."tranCode=IFP001&callBack=http://ws.weishuapay.com/m/zhifu.php";

        

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

        

        $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440148160026.pem');

        

        // echo $merchantAESKey;

        

        $xmlData = $this->decode($encryptData_host, $merchantAESKey);

        

        

        

        error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . iconv('UTF-8', 'GBK', var_export($xmlData, true)) . "\n\n", 3, './app/user/instead/bangka_' . date('Y-m-d') . '.log');

        

        

        

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

        

                    $card['bindId'] = $bindId;*/
        $result = $this->kft_bank_verification($card);

        if($result['status'] == 1){

            if ($this->App->insert('user_card_instead', $card)) {

                $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '绑卡成功，请设置还款计划！');

                exit;

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, '绑卡失败，请重新绑定！');

                exit;

            }
        }else{

            $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, $result['failureDetails']);

                exit;
        }
        

        //}

        //} else {

        //            $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, $respMsg);

        //            exit;

        //        }

        

    }

    function _get_payinfo($id = 0) {

        $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");

        return $rt;

    }

    function sj1_instead($uid) {

        $uid = $this->checked_instead_login();

        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $uid . " limit 1");

        if (!empty($sj1)) {

            $this->sj3_instead($uid);

        } else {

            $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);

            $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);

            $rts = $this->_get_payinfo(20);

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



						  <reqMsgId>DH' . date('Ymdhis', time()) . $uid . '</reqMsgId>



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

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/user/instead/sj_1_' . date('Y-m-d') . '.log');

            $encryptData = $this->encrypt($xml, $key);

            $signData = $this->rsaSign($xml, './app/user/549440148160026.pem');

            $encyrptKey = $this->rsasign_public($key, './app/user/549440148160026_pub.pem');

            $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100001');

            $url = "http://epay.gaohuitong.com:8083/interfaceWeb/basicInfo";

            if (is_array($postdata)) {

                ksort($postdata);

                $content = http_build_query($postdata);

                $content_length = strlen($content);

                $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));

                $response = file_get_contents($url, false, stream_context_create($options));

            }

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/user/instead/sj_1_' . date('Y-m-d') . '.log');

            $resp = explode('&', $response);

            $first = strpos($resp[0], "="); //字符第一次出现的位置

            $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $first = strpos($resp[1], "="); //字符第一次出现的位置

            $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440148160026.pem');

            $xmlData = $this->decode($encryptData_host, $merchantAESKey);

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/user/instead/sj_1_' . date('Y-m-d') . '.log');

            $xml_obj = simplexml_load_string($xmlData);

            $rt['reqMsgId'] = $xml_obj->head->reqMsgId;

            $rt['respCode'] = $xml_obj->head->respCode;

            $rt['respMsg'] = $xml_obj->head->respMsg;

            if ($rt['respCode'] == "000000") {

                $this->App->insert('user_sj1_instead', $card);

                $this->sj3_instead($uid);

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, $rt['respMsg']);

                exit;

            }

        }

    }

    function sj2_instead($uid, $cardinfo) {

        $user_id = $this->checked_instead_login();

        if (($uid != $user_id) || empty($cardinfo['bank_no'])) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka');

            exit;

        }

        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);

        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $uid . " limit 1");

        $sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj2_instead` WHERE uid=" . $uid . " and bankaccountNo ='" . $cardinfo['bank_no'] . "' limit 1");

        if (!empty($sj2)) {

            $this->sj3_instead($uid);

        } else {

            $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $cardinfo['bank']);

            $rts = $this->_get_payinfo(20);

            $pay = unserialize($rts['pay_config']);

            $card['uid'] = $uid;

            $card['merchantId'] = $sj1['corpmanMobile'];

            $card['bankCode'] = $bank['code'];

            $card['bankaccProp'] = 0;

            $card['name'] = $data['uname'];

            $card['bankaccountNo'] = $cardinfo['bank_no'];

            $card['bankaccountType'] = 2;

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



			<reqMsgId>DH' . date('Ymdhis', time()) . $uid . '</reqMsgId>



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

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/user/instead/sj_2_' . date('Y-m-d') . '.log');

            $encryptData = $this->encrypt($xml, $key);

            $signData = $this->rsaSign($xml, './app/user/549440148160026.pem');

            $encyrptKey = $this->rsasign_public($key, './app/user/549440148160026_pub.pem');

            $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100002');

            $url = "http://epay.gaohuitong.com:8083/interfaceWeb/bankInfo";

            if (is_array($postdata)) {

                ksort($postdata);

                $content = http_build_query($postdata);

                $content_length = strlen($content);

                $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));

                $response = file_get_contents($url, false, stream_context_create($options));

            }

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/user/instead/sj_2_' . date('Y-m-d') . '.log');

            $resp = explode('&', $response);

            $first = strpos($resp[0], "="); //字符第一次出现的位置

            $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $first = strpos($resp[1], "="); //字符第一次出现的位置

            $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440148160026.pem');

            $xmlData = $this->decode($encryptData_host, $merchantAESKey);

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/user/instead/sj_2_' . date('Y-m-d') . '.log');

            $xml_obj = simplexml_load_string($xmlData);

            $rt['reqMsgId'] = $xml_obj->head->reqMsgId;

            $rt['respCode'] = $xml_obj->head->respCode;

            $rt['respMsg'] = $xml_obj->head->respMsg;

            if ($rt['respCode'] == "000000" || $rt['respMsg'] = '已添加的银行信息不能重复添加') {

                $this->App->insert('user_sj2_instead', $card);

                $this->sj3_instead($uid);

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, $rt['respMsg']);

                exit;

            }

        }

    }

    function sj3_instead($uid) {

        $uid = $this->checked_instead_login();

        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $uid . " limit 1");

        $sj3 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj3_instead` WHERE uid=" . $uid . " and pay_id =20 limit 1");

        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);

        $rts = $this->_get_payinfo(20);

        $pay = unserialize($rts['pay_config']);

        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

        //费率单独设置

        $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");

        $feilv = unserialize($feilv);

        $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=20 LIMIT 1");

        //计算手续费

        $koulv = $feilv[$pay_fangshi] / 100;

        if (!empty($sj3) && ($sj3['futureRateValue'] == $koulv)) {

            $this->daifu_instead($uid);

        } else {

            $card['uid'] = $uid;

            $card['pay_id'] = 20;

            $card['merchantId'] = $sj1['corpmanMobile'];

            if (!empty($sj3)) {

                $card['handleType'] = 1;

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



				<reqMsgId>DH' . date('Ymdhis', time()) . $uid . '</reqMsgId>



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

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/user/instead/sj_3_' . date('Y-m-d') . '.log');

            $encryptData = $this->encrypt($xml, $key);

            $signData = $this->rsaSign($xml, './app/user/549440148160026.pem');

            $encyrptKey = $this->rsasign_public($key, './app/user/549440148160026_pub.pem');

            $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100003');

            $url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";

            if (is_array($postdata)) {

                ksort($postdata);

                $content = http_build_query($postdata);

                $content_length = strlen($content);

                $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));

                $response = file_get_contents($url, false, stream_context_create($options));

            }

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/user/instead/sj_3_' . date('Y-m-d') . '.log');

            $resp = explode('&', $response);

            $first = strpos($resp[0], "="); //字符第一次出现的位置

            $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $first = strpos($resp[1], "="); //字符第一次出现的位置

            $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440148160026.pem');

            $xmlData = $this->decode($encryptData_host, $merchantAESKey);

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/user/instead/sj_3_' . date('Y-m-d') . '.log');

            $xml_obj = simplexml_load_string($xmlData);

            $rt['reqMsgId'] = $xml_obj->head->reqMsgId;

            $rt['respCode'] = $xml_obj->head->respCode;

            $rt['respMsg'] = $xml_obj->head->respMsg;

            if ($rt['respCode'] == "000000") {

                if ($card['handleType'] == 0) {

                    $this->App->insert('user_sj3_instead', $card);

                } else {

                    $sql = "UPDATE `{$this->App->prefix() }user_sj3_instead` SET `uid` = " . $card['uid'] . ",`pay_id` = " . $card['pay_id'] . ",`merchantId` = '" . $card['merchantId'] . "',`cycleValue` = " . $card['cycleValue'] . ",`busiCode` = '" . $card['busiCode'] . "',`futureRateType` = " . $card['futureRateType'] . ",`futureRateValue` = " . $card['futureRateValue'] . ",`handleType` = " . $card['handleType'] . "  WHERE `uid` = " . $card['uid'] . " and `pay_id` = " . $card['pay_id'];

                    $this->App->query($sql);

                }

                $this->daifu_instead($uid);

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, $rt['respMsg']);

                exit;

            }

        }

    }

    function daifu_instead($uid) {

        $daifu_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_daifu_instead` WHERE uid=" . $uid . " and busiCode ='B00302'  limit 1");

        if (empty($daifu_info)) {

            $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

            $sxf_instead = $this->App->findvar("SELECT sxf_api FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");

            $rts = $this->_get_payinfo(20);

            $pay = unserialize($rts['pay_config']);

            $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $uid . " limit 1");

            $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);

            $card['uid'] = $uid;

            $card['merchantId'] = $sj1['corpmanMobile'];

            $card['handleType'] = 0;

            $card['cycleValue'] = 2; //结算周期 D+0

            $card['allotFlag'] = 1;

            $card['busiCode'] = "B00302";

            $card['futureRateType'] = 2; //费率类型 单笔

            $card['futureRateValue'] = 0;

            $key = $this->random_string(16, $max = FALSE);

            $xml = '



	<merchant>



			<head>



				<version>1.0.0</version>



				<agencyId>' . $pay['pay_no'] . '</agencyId>



				<msgType>01</msgType>



				<tranCode>100003</tranCode>



				<reqMsgId>DH' . date('Ymdhis', time()) . $uid . '</reqMsgId>



				<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  



			</head>



			<body>



			  <merchantId>' . $card['merchantId'] . '</merchantId>



			  <handleType>' . $card['handleType'] . '</handleType>



			  <cycleValue>' . $card['cycleValue'] . '</cycleValue>



			  <allotFlag>' . $card['allotFlag'] . '</allotFlag>



			<busiList>



				<busiCode>' . $card['busiCode'] . '</busiCode>



				<futureRateType>' . $card['futureRateType'] . '</futureRateType>



				<futureRateValue>' . $card['futureRateValue'] . '</futureRateValue>



			</busiList>



			</body>



	</merchant>';

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/user/instead/daifu_' . date('Y-m-d') . '.log');

            $encryptData = $this->encrypt($xml, $key);

            $signData = $this->rsaSign($xml, './app/user/549440148160026.pem');

            $encyrptKey = $this->rsasign_public($key, './app/user/549440148160026_pub.pem');

            $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100003');

            $url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";

            $response = $this->curl_daifu($url, $postdata);

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/user/instead/daifu_' . date('Y-m-d') . '.log');

            $resp = explode('&', $response);

            $first = strpos($resp[0], "="); //字符第一次出现的位置

            $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $first = strpos($resp[1], "="); //字符第一次出现的位置

            $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);

            $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440148160026.pem');

            $xmlData = $this->decode($encryptData_host, $merchantAESKey);

            error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/user/instead/daifu_' . date('Y-m-d') . '.log');

            $xml_obj = simplexml_load_string($xmlData);

            $rt['reqMsgId'] = $xml_obj->head->reqMsgId;

            $rt['respCode'] = $xml_obj->head->respCode;

            $rt['respMsg'] = $xml_obj->head->respMsg;

            if ($rt['respCode'] == "000000") {

                $this->App->insert('user_daifu_instead', $card);

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=Instead_bangka', 0, $rt['respMsg']);

                exit;

            }

        }

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

    //提示

    function tishi() {

        $this->title($GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $this->set('uid', $uid);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/tishi');

    }

    //实名认证

    function renzheng($data = array()) {

        $client = $_SERVER['HTTP_USER_AGENT'];

        //用php自带的函数strpos来检测是否是微信端

        if (strpos($client, 'MicroMessenger') === false) {

            die("请在微信端打开");

            exit;

        }

        $this->title("实名认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        if ($rts) {

            $sql2 = "SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $rts['bank'] . " LIMIT 1";

            $bank = $this->App->findrow($sql2);

        } else {

            $sql2 = "SELECT * FROM `{$this->App->prefix() }bank`";

            $bank = $this->App->find($sql2);

        }

        $rt['NonceStr'] = $this->random_string(16);

        $signPackage = $this->getsignature();

        // $rr = $this->action('common', '_get_appid_appsecret');

        //                $appid = $rr['appid'];

        //		$rt['AppId'] = $rr['appid'];

        //		$appsecret = $rr['appsecret'];

        //

        //        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        $this->set('signPackage', $signPackage);

        if ($rts) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_after');

            exit;

            //  $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

            //        $this->template($mb . '/renzheng_after');

            //			exit;

            

        }

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng');

    }

    function renzheng_after() {

        $this->title("实名认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_shop` WHERE uid=" . $uid . " LIMIT 1";

        $shop = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $rts['bank'] . " LIMIT 1";

        $bank = $this->App->findrow($sql);

        $this->set('rts', $rts);

        $this->set('shop', $shop);

        $this->set('bank', $bank);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_after');

    }

    function renzheng_info() {

        $this->title("实名认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $sql2 = "SELECT * FROM `{$this->App->prefix() }bank`";

        $bank = $this->App->find($sql2);

        $rt['NonceStr'] = $this->random_string(16);

        $signPackage = $this->getsignature(); // $rr = $this->action('common', '_get_appid_appsecret');

        //                $appid = $rr['appid'];

        //		$rt['AppId'] = $rr['appid'];

        //		$appsecret = $rr['appsecret'];

        //

        //        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        $this->set('signPackage', $signPackage);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng');

    }

    //商家实名认证

    function sj_renzheng($data = array()) {

        $this->title("商家认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rz = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_shop` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $rts['mobile'] = $rz['mobile'];

        $rts['uname'] = $rz['uname'];

        $rt['NonceStr'] = $this->random_string(16);

        $signPackage = $this->getsignature(); // $rr = $this->action('common', '_get_appid_appsecret');

        //                $appid = $rr['appid'];

        //		$rt['AppId'] = $rr['appid'];

        //		$appsecret = $rr['appsecret'];

        //

        //        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $this->set('signPackage', $signPackage);

        $this->set('uid', $uid);

        if ($rts['id']) {

            $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

            $this->template($mb . '/sj_renzheng_after');

            exit;

        } else {

            $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

            $this->template($mb . '/sj_renzheng');

        }

    }

    function sj_renzheng_info() {

        $this->title("商家认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_shop` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $signPackage = $this->getsignature(); // $rr = $this->action('common', '_get_appid_appsecret');

        //                $appid = $rr['appid'];

        //		$rt['AppId'] = $rr['appid'];

        //		$appsecret = $rr['appsecret'];

        //

        //        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        $this->set('signPackage', $signPackage);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/sj_renzheng');

    }

    //用户资料

    function userinfo() {

        $this->title("欢迎进入用户后台管理中心" . ' - 我的资料 - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $rt['province'] = $this->get_regions(1); //获取省列表

        //当前用户的收货地址

        $sql = "SELECT * FROM `{$this->App->prefix() }user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";

        $rt['userress'] = $this->App->findrow($sql);

        if ($rt['userress']['province'] > 0) $rt['city'] = $this->get_regions(2, $rt['userress']['province']); //城市

        if ($rt['userress']['city'] > 0) $rt['district'] = $this->get_regions(3, $rt['userress']['city']); //区

        //$rt['recommend10'] = $this->action('catalog','recommend_goods');

        //print_r($rt);

        //商品分类列表

        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "用户资料");

        $this->template('user_info');

    }

    //收货地址

    function address() {

        $this->title("欢迎进入用户后台管理中心" . ' - 收货地址 - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        /* if(isset($_POST)&&!empty($_POST)){

        

        

        

          if(empty($_POST['province'])){

        

          $this->jump('user.php?act=address_list',0,'选择省份！'); exit;

        

          }else if(empty($_POST['city'])){

        

          $this->jump('user.php?act=address_list',0,'选择城市！');exit;

        

          }else if(empty($_POST['consignee'])){

        

          $this->jump('user.php?act=address_list',0,'收货人不能为空！');exit;

        

          }else if(empty($_POST['email'])){

        

          $this->jump('user.php?act=address_list',0,'电子邮箱不能为空！');exit;

        

          }else if(empty($_POST['address'])){

        

          $this->jump('user.php?act=address_list',0,'收货地址不能为空！');exit;

        

          }else if(empty($_POST['tel'])){

        

          $this->jump('user.php?act=address_list',0,'电话号码不能为空！');exit;

        

          }

        

        

        

          if(!isset($_POST['address_id'])&&empty($_POST['address_id'])){ //添加

        

          $_POST['user_id'] = $uid;

        

          if($this->App->insert('user_address',$_POST)){

        

          if(isset($_GET['ty'])&&$_GET['ty']=='cart'){

        

          $this->jump('mycart.php?type=checkout'); exit;

        

          }else{

        

          $this->jump('',0,'添加成功！');exit;

        

          }

        

          }else{

        

          $this->jump('',0,'添加失败！');exit;

        

          }

        

        

        

          }else{ //修改

        

          $address_id = $_POST['address_id'];

        

          $_POST = array_diff_key($_POST,array('address_id'=>'0'));

        

          if($this->App->update('user_address',$_POST,'address_id',$address_id )){



        

          if(isset($_GET['ty'])&&$_GET['ty']=='cart'){

        

          $this->jump('mycart.php?type=checkout'); exit;

        

          }else{

        

          $this->jump('',0,'更新成功！');exit;

        

          }

        

          }

        

          else{

        

          $this->jump('',0,'更新失败！');exit;

        

          }

        

          }

        

          } */

        $rt['province'] = $this->get_regions(1); //获取省列表

        //当前用户的收货地址

        $sql = "SELECT * FROM `{$this->App->prefix() }user_address` WHERE user_id='$uid' AND is_own='0'";

        $rt['userress'] = $this->App->find($sql);

        if (!empty($rt['userress'])) {

            foreach ($rt['userress'] as $row) {

                $rt['city'][$row['address_id']] = $this->get_regions(2, $row['province']); //城市

                $rt['district'][$row['address_id']] = $this->get_regions(3, $row['city']); //区

                

            }

        }

        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix() }user_address` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb2.region_id = tb1.province";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb3 ON tb3.region_id = tb1.city";

        $sql.= " LEFT JOIN `{$this->App->prefix() }region` AS tb4 ON tb4.region_id = tb1.district";

        $sql.= " WHERE tb1.user_id='$uid' AND tb1.is_own = '0' ORDER BY tb1.address_id ASC";

        $rt['userress'] = $this->App->find($sql);

        //商品分类列表

        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        if (!defined(NAVNAME)) define('NAVNAME', "收货地址簿");

        $this->set('rt', $rt);

        $this->template('user_consignee_address');

    }

    //用户密码修改

    function editpass() {

        $uid = $this->checked_login();

        $this->title("欢迎进入用户后台管理中心" . ' - 用户密码修改 - ' . $GLOBALS['LANG']['site_name']);

        //商品分类列表

        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        $this->set('rt', $rt);

        if (!defined(NAVNAME)) define('NAVNAME', "修改密码");

        $this->template('user_editpass');

    }

    //用户订单操作

    function ajax_order_op($id = 0, $op = "") {

        if (empty($id) || empty($op)) die("传送ID为空！");

        if ($op == "cancel_order") $this->App->update('goods_order_info', array('order_status' => '1'), 'order_id', $id);

        else if ($op == "confirm") $this->App->update('goods_order_info', array('shipping_status' => '5'), 'order_id', $id);

    }

    //我的余额

    function mymoney($page = 1) {

        $this->title("欢迎进入用户后台管理中心" . ' - 我的余额 - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix() }user_money_change` WHERE uid='$uid'";

        $rt['zmoney'] = $this->App->findvar($sql);

        $rt['zmoney'] = format_price($rt['zmoney']);

        //分页

        if (empty($page)) {

            $page = 1;

        }

        $list = 10; //每页显示多少个

        $start = ($page - 1) * $list;

        $tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix() }user_money_change` WHERE uid='$uid'");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_money_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";

        $rt['lists'] = $this->App->find($sql);

        $rt['page'] = $page;

        //商品分类列表

        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);

        //ajax

        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {

            echo $this->fetch('ajax_user_moneychange', true);

            exit;

        }

        if (!defined(NAVNAME)) define('NAVNAME', "我的余额");

        $this->template('mymoney');

    }

    //我的积分

    function mypoints() {

        $this->title("欢迎进入用户后台管理中心" . ' - 我的积分 - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        //删除

        $id = isset($_GET['id']) ? $_GET['id'] : '0';

        if ($id > 0) {

            $this->App->delete('user_point_change', 'cid', $id);

            $this->jump(ADMIN_URL . 'user.php?act=mypoints');

            exit;

        }

        $sql = "SELECT SUM(points) FROM `{$this->App->prefix() }user_point_change` WHERE uid='$uid'";

        $rt['zpoints'] = $this->App->findvar($sql);

        //分页

        $page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;

        if (empty($page)) {

            $page = 1;

        }

        $list = 30; //每页显示多少个

        $start = ($page - 1) * $list;

        $tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix() }user_point_change` WHERE uid='$uid'");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        //$sql = "SELECT * FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";

        $sql = "SELECT tb1.*,tb2.nickname FROM `{$this->App->prefix() }user_point_change` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb1.subuid = tb2.user_id WHERE tb1.uid='$uid' ORDER BY tb1.time DESC LIMIT $start,$list";

        $rt['lists'] = $this->App->find($sql); //商品列表

        $rt['page'] = $page;

        //商品分类列表

        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);

        //ajax

        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {

            echo $this->fetch('ajax_user_pointchange', true);

            exit;

        }

        if (!defined(NAVNAME)) define('NAVNAME', "我的积分");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/mypoints');

    }

    //用户收藏

    function mycolle() {

        $uid = $this->checked_login();

        $this->js('goods.js');

        $this->title("欢迎进入用户后台管理中心" . ' - 我的收藏 - ' . $GLOBALS['LANG']['site_name']);

        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if ($id > 0) {

            $this->App->delete('shop_collect', 'rec_id', $id);

            $this->jump(ADMIN_URL . 'user.php?act=mycoll');

            exit;

        }

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        if (empty($page)) {

            $page = 1;

        }

        $list = 4; //每页显示多少个

        $start = ($page - 1) * $list;

        $tt = $this->App->findvar("SELECT COUNT(rec_id) FROM `{$this->App->prefix() }goods_collect` WHERE user_id='$uid'");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT tb1.rec_id,tb1.user_id,tb1.add_time,tb2.goods_id, tb2.goods_name,tb2.goods_bianhao,tb2.shop_price, tb2.market_price,tb2.pifa_price,tb2.goods_thumb, tb2.original_img, tb2.goods_img,tb2.promote_start_date,tb2.promote_end_date,tb2.promote_price,tb2.is_promote,tb2.qianggou_start_date,tb2.qianggou_end_date,tb2.qianggou_price,tb2.is_qianggou FROM `{$this->App->prefix() }goods_collect` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }goods` AS tb2 ON tb1.goods_id=tb2.goods_id";

        $sql.= " WHERE tb1.user_id='$uid' ORDER BY tb1.add_time DESC LIMIT $start,$list";

        $rt['lists'] = $this->App->find($sql); //商品列表

        $this->set('rt', $rt);

        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {

            echo $this->fetch('ajax_mycoll', true);

            exit;

        }

        if (!defined(NAVNAME)) define('NAVNAME', "我的收藏");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_mycolle');

    }

    //ajax删除收藏

    function ajax_delmycoll($ids = 0) {

        if (empty($ids)) die("非法删除，删除ID为空！");

        $id_arr = @explode('+', $ids);

        foreach ($id_arr as $id) {

            if (Import::basic()->int_preg($id)) $this->App->delete('shop_collect', 'rec_id', $id);

        }

    }

    function user_tuijian() {

        $uid = $this->checked_login();

        $this->title("欢迎进入用户后台管理中心" . ' - 我的推荐 - ' . $GLOBALS['LANG']['site_name']);

        $rt['uid'] = $uid;

        //商品分类列表

        /* 		$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        

        */

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        if (empty($page)) {

            $page = 1;

        }

        $list = 8; //每页显示多少个

        $start = ($page - 1) * $list;

        $tt = $this->App->findvar("SELECT COUNT(goods_id) FROM `{$this->App->prefix() }goods` WHERE is_new='1'");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT goods_id,goods_img,goods_name,pifa_price,need_jifen FROM `{$this->App->prefix() }goods` WHERE is_new='1' ORDER BY goods_id DESC LIMIT $start,$list";

        $rt['categoodslist'] = $this->App->find($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "我的推荐");

        $this->set('rt', $rt);

        $this->set('page', $page);

        $this->template('user_tuijian');

    }

    function messages() {

        $uid = $this->checked_login();

        $this->title("欢迎进入用户后台管理中心" . ' - 我的提问 - ' . $GLOBALS['LANG']['site_name']);

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        if (empty($page)) {

            $page = 1;

        }

        $list = 4; //每页显示多少个

        $start = ($page - 1) * $list;

        $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix() }message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix() }message` AS tb1 LEFT JOIN  `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";

        $rt['meslist'] = $this->App->find($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "我的提问");

        $this->set('rt', $rt);

        $this->template('user_question');

    }

    function xiaofei() {

        $this->template('user_xiaofei');

    }

    function comment() {

        $uid = $this->checked_login();

        $this->title("欢迎进入用户后台管理中心" . ' - 我的评论 - ' . $GLOBALS['LANG']['site_name']);

        //分页

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        if (empty($page)) {

            $page = 1;

        }

        $list = 4; //每页显示多少个

        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(comment_id) FROM `{$this->App->prefix() }comment`";

        $sql.= " WHERE parent_id = 0 AND status='1' AND user_id='$uid'";

        $tt = $this->App->findvar($sql);

        $rt['goodscommentpage'] = Import::basic()->ajax_page($tt2, $list, $page, 'get_mycomment_page_list');

        $sql = "SELECT c.*,u.avatar,u.user_name AS dbuname,u.nickname,g.goods_thumb,g.goods_name,g.goods_id FROM `{$this->App->prefix() }comment` AS c LEFT JOIN `{$this->App->prefix() }user` AS u ON c.user_id=u.user_id LEFT JOIN `{$this->App->prefix() }goods` AS g ON g.goods_id = c.id_value";

        $sql.= " WHERE c.parent_id = 0  AND c.status='1' AND c.user_id='$uid' ORDER BY c.add_time DESC LIMIT $start,$list";

        $this->App->fieldkey('comment_id');

        $commentlist = $this->App->find($sql);

        $rp_commentlist = array();

        if (!empty($commentlist)) { //回复的评论

            $commend_id = array_keys($commentlist);

            $sql = "SELECT c.*,a.adminname FROM `{$this->App->prefix() }comment` AS c";

            $sql.= " LEFT JOIN `{$this->App->prefix() }admin` AS a ON a.adminid = c.user_id";

            $sql.= " WHERE c.parent_id IN (" . implode(',', $commend_id) . ")";

            $this->App->fieldkey('parent_id');

            $rp_commentlist = $this->App->find($sql);

            foreach ($commentlist as $cid => $row) {

                $rt['goodscommentlist'][$cid] = $row;

                $rt['goodscommentlist'][$cid]['rp_comment_list'] = isset($rp_commentlist[$cid]) ? $rp_commentlist[$cid] : array();

            }

            unset($commentlist);

        } else {

            $rt['goodscommentlist'] = array();

        }

        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {

            $this->set('rt', $rt);

            echo $this->fetch('ajax_mycomment', true);

            exit;

        }

        //商品分类列表

        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        if (!defined(NAVNAME)) define('NAVNAME', "我的评论");

        $this->set('rt', $rt);

        $this->template('user_mycomment');

    }

    function ajax_feedback($data = array()) {

        $err = 0;

        $result = array('error' => $err, 'message' => '');

        $json = Import::json();

        if (empty($data)) {

            $result['error'] = 2;

            $result['message'] = '传送的数据为空！';

            die($json->encode($result));

        }

        $mesobj = $json->decode($data); //反json ,返回值为对象

        //以下字段对应评论的表单页面 一定要一致

        $datas['comment_title'] = $mesobj->comment_title;

        $datas['goods_id'] = $mesobj->goods_id;

        $goods_id = $datas['goods_id'];

        $uid = $this->Session->read('User.uid');

        $datas['user_id'] = !empty($uid) ? $uid : 0;

        $datas['status'] = 2;

        if (strlen($datas['comment_title']) < 12) {

            $result['error'] = 2;

            $result['message'] = '评论内容不能太少！';

            die($json->encode($result));

        }

        $datas['addtime'] = mktime();

        $ip = Import::basic()->getip();

        $datas['ip_address'] = $ip ? $ip : '0.0.0.0';

        $datas['ip_from'] = Import::ip()->ipCity($ip);

        if ($this->App->insert('message', $datas)) {

            $result['error'] = 0;

            $result['message'] = '提问成功！我们会很快回答您的问题！';

        } else {

            $result['error'] = 1;

            $result['message'] = '提问失败，请通过在线联系客服吧！';

        }

        unset($datas, $data);

        $page = 1;

        $list = 2; //每页显示多少个

        $start = ($page - 1) * $list;

        $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix() }message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");

        $rt['notgoodmespage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_myquestion_notgoods_page_list');

        $sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix() }message` AS tb1 LEFT JOIN  `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";

        $rt['notgoodsmeslist'] = $this->App->find($sql);

        $this->set('rt', $rt);

        $result['error'] = 0;

        $result['message'] = $this->fetch('ajax_userquestion_nogoods', true);

        die($json->encode($result));

    }

    //删除提问

    function ajax_delmessages($id = 0) {

        if (!($id > 0)) die("传送的ID为空！");

        if ($this->App->delete('message', 'mes_id', $id)) {

            echo "";

        } else {

            echo "删除意外出错！";

        }

        exit;

    }

    //删除评论

    function ajax_delcomment($id = 0) {

        if (!($id > 0)) die("传送的ID为空！");

        if ($this->App->delete('comment', 'comment_id', $id)) {

            echo "";

        } else {

            echo "删除意外出错！";

        }

        exit;

    }

    //用户积分获取

    function add_user_jifen($type = "", $obj = array()) {

        $art = array('buy', 'comment', 'tuijian', 'otherjifen');

        $uid = $this->Session->read('User.uid');

        if (!($uid > 0)) return false;

        $rank = $this->Session->read('User.rank');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid='$rank' LIMIT 1";

        $rtlevel = $this->App->findrow($sql);

        $jfdesc = $rtlevel['jifendesc'];

        $dbjfdesc = array(); //当前会员级别能够得到积分的权限

        if (!empty($jfdesc)) {

            $dbjfdesc = explode('+', $jfdesc);

        }

        if (in_array($type, $dbjfdesc)) { //拥有得到积分的权限

            switch ($type) {

                case 'comment': //参与每件已购商品评论获奖10分，依次类推，参与10件已购商品评论可获奖100个积分（一张订单每个产品只能获得一次积分）。

                    $points = 10;

                    $data['time'] = mktime();

                    $data['changedesc'] = "留言所得积分！";

                    $data['points'] = $points;

                    $data['uid'] = $uid;

                    if ($this->App->insert('user_point_change', $data)) {

                        $sql = "UPDATE `{$this->App->prefix() }user` SET `points_ucount` = `points_ucount`+'$points' , `mypoints` = `mypoints`+ '$points' WHERE user_id = '$uid' LIMIT 1";

                        $this->App->query($sql);

                        return $data;

                    } else {

                        return false;

                    }

                break;

                case 'tuijian': //推荐好友注册获奖50分，好友首次成功购物获奖同倍积分；

                    $data['time'] = mktime();

                    $data['changedesc'] = "推荐好友注册所得积分！";

                    $data['points'] = 50;

                    $data['uid'] = $uid;

                    if ($this->App->insert('user_point_change', $data)) {

                        return $data;

                    } else {

                        return false;

                    }

                break;

                case 'spendthan1500': //单次购物达1500元，当次购物获取2倍积分

                    $sql = "SELECT goods_amount FROM `{$this->App->prefix() }goods_order_info` WHERE user_id='$uid' AND order_status='2' ORDER BY pay_time DESC LIMIT 1";

                    $amount = $this->App->findvar($sql);

                    if (intval($amount) > 1500) {

                        $data['time'] = mktime();

                        $data['changedesc'] = "本次购物'$amount'元！【单次购物达1500以上所得积分】";

                        $data['points'] = $amount * 2;

                        $data['uid'] = $uid;

                        if ($this->App->insert('user_point_change', $data)) {

                            return $data;

                        } else {

                            return false;

                        }

                    } elseif (intval($amount) > 0) {

                        $data['time'] = mktime();

                        $data['changedesc'] = "本次购物'$amount'元所得积分！";

                        $data['points'] = $amount * 2;

                        $data['uid'] = $uid;

                        if ($this->App->insert('user_point_change', $data)) {

                            return $data;

                        } else {

                            return false;

                        }

                    } else {

                        return false;

                    }

                break;

                case 'upuserinfo': //特定时间内，更新正确个人资料，可获奖10个积分； 一个星期之内更新

                    $data['time'] = mktime();

                    $data['changedesc'] = "更新正确个人资料所得积分！";

                    $data['points'] = 10;

                    $data['uid'] = $uid;

                    if ($this->App->insert('user_point_change', $data)) {

                        return $data;

                    } else {

                        return false;

                    }

                break;

                case 'yearthancount6': //全年购物超过6次，于每年年末奖励100个积分（2010-1-1起开始计算）

                    $data['time'] = mktime();

                    $data['changedesc'] = "全年购物超过6次所得积分！";

                    $data['points'] = 100;

                    $data['uid'] = $uid;

                    if ($this->App->insert('user_point_change', $data)) {

                        return $data;

                    } else {

                        return false;

                    }

                break;

            }

        } else {

            return false;

        }

    }

    //更新密码

    function ajax_updatepass($data = array()) {

        $json = Import::json();

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $result = array('error' => 3, 'message' => '先您先登录！');

            die($json->encode($result));

        }

        $result = array('error' => 2, 'message' => '传送的数据为空！');

        if (empty($data['fromAttr'])) die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象

        unset($data);

        $newpass = $fromAttr->newpass;

        $rp_pass = $fromAttr->rp_password;

        $datas['password'] = $fromAttr->password;

        if (!empty($newpass)) {

            if (empty($datas['password'])) {

                $result = array('error' => 2, 'message' => '请输入新密码！');

                die($json->encode($result));

            }

            if (!empty($rp_pass) && $datas['password'] == $rp_pass) {

                $datas['password'] = md5(trim($datas['password']));

                if (md5($newpass) == $datas['password']) {

                    $result = array('error' => 2, 'message' => '新密码跟旧密码不能相同！');

                    die($json->encode($result));

                }

                $newpass = md5(trim($newpass));

                $sql = "SELECT password FROM `{$this->App->prefix() }user` WHERE password='$newpass' AND user_id='$uid'";

                $newrt = $this->App->findvar($sql);

                if (empty($newrt)) {

                    $result = array('error' => 2, 'message' => '您的原始密码错误！');

                    die($json->encode($result));

                }

                if ($this->App->update('user', $datas, 'user_id', $uid)) {

                    $result = array('error' => 2, 'message' => '密码修改成功！');

                    die($json->encode($result));

                } else {

                    $result = array('error' => 2, 'message' => '密码修改失败！');

                    die($json->encode($result));

                }

            } else {

                $result = array('error' => 2, 'message' => '密码与确认密码不一致！');

                die($json->encode($result));

            }

        } else {

            $result = array('error' => 2, 'message' => '请输入原始密码！');

            die($json->encode($result));

        }

    }

    //判断是否已经登陆

    function is_login() {

        $uid = $this->Session->read('User.uid');

        $iuid = $this->Session->read('User.iuid');

        $username = $this->Session->read('User.username');

        if (empty($uid) || empty($username) || empty($iuid)) {

            return false;

        } else {

            return true;

        }

    }

    //判断是否已经登陆

    function is_login_instead() {

        $uid = $this->Session->read('User.iuid');

        if (empty($uid)) {

            return false;

        } else {

            return true;

        }

    }

    //2018/03/13
    function checked_login() {
        
        $uid = $this->Session->read('User.uid');

        $iuid = $this->Session->read('User.iuid');
        
        if (!($iuid > 0)) {

            $this->jump(ADMIN_URL . 'user.php?act=login');

            exit;

        }else{

            // $sql = "SELECT is_subscribe FROM `{$this->App->prefix() }user` WHERE user_id = ".$iuid;

            // $rel = $this->App->findrow($sql);

            // if($rel['is_subscribe'] == 0){

            //     $this->jump("https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU3OTQ0MTU5Mg==#wechat_redirect");

            // }

        }

        return $iuid;

    }

    function checked_instead_login() {

        $uid = $this->Session->read('User.uid');

        $iuid = $this->Session->read('User.iuid');
        if (!($uid > 0) && !($iuid > 0)) {

            $this->jump(ADMIN_URL . 'user.php?act=login_instead');

            exit;

        } else {

            if ($uid > 0) {

                $uid = $uid;

            }

            if ($iuid > 0) {

                $uid = $iuid;

            }

        }

        return $uid;

    }

    function get_regions($type, $parent_id = 0) {

        $p = "";

        if (!empty($parent_id)) $p = "AND parent_id='$parent_id'";

        $sql = "SELECT region_id,region_name FROM `{$this->App->prefix() }region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";

        return $this->App->find($sql);

    }

    //退出登录

    function logout() {

        session_destroy();

        //

        //if(isset($_COOKIE['user'])){

        //if(is_array($_COOKIE['user'])){

        //foreach($_COOKIE['user'] as $key=>$val){

        //setcookie("user[".$key."]", "");

        if (isset($_COOKIE[CFGH . 'USER']['AUTOLOGIN'])) setcookie(CFGH . 'USER[AUTOLOGIN]', "", 0); //清空自动登录

        $url = $this->Session->read('REFERER');

        if (empty($url)) $url = ADMIN_URL . 'catalog.php';

        $this->jump($url);

        exit;

    }

    function mypoints_convert() {

        $this->title("积分兑换" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $sql = "SELECT SUM(points) FROM `{$this->App->prefix() }user_point_change` WHERE uid='$uid'";

        $rt['zpoints'] = $this->App->findvar($sql);

        if (!defined(NAVNAME)) define('NAVNAME', "积分兑换");

        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/mypoints_convert');

    }

    function ajax_postpoints_convert() {

        $uid = $this->checked_login();

        $zpoints = intval($_POST['zpoints']);

        if ($zpoints < 1) {

            echo "请输入合法积分金额！";

            exit;

        }

        $sql = "SELECT SUM(points) FROM `{$this->App->prefix() }user_point_change` WHERE uid='$uid'";

        $points = $this->App->findvar($sql);

        if ($points < $zpoints) {

            echo "请输入合法积分金额！";

            exit;

        }

        $moeys = $zpoints * 0.1;

        $thismonth = date('Y-m-d', mktime());

        $thism = date('Y-m', mktime());

        //   $sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$uid'";

        $sql = "UPDATE `{$this->App->prefix() }user` SET  `mymoney` = `mymoney`+$moeys WHERE user_id = '$uid'";

        $this->App->query($sql);

        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => '', 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '积分兑换余额', 'type' => 'exchange', 'time' => mktime(), 'uid' => $uid, 'level' => '66'));

        //购买者送积分

        $sql = "UPDATE `{$this->App->prefix() }user` SET `points_ucount` = `points_ucount`-$zpoints,`mypoints` = `mypoints`-$zpoints WHERE user_id = '$uid'";

        $this->App->query($sql);

        $this->App->insert('user_point_change', array('order_sn' => '', 'thismonth' => $thismonth, 'points' => - $zpoints, 'changedesc' => '积分兑换余额', 'time' => mktime(), 'uid' => $uid));

        echo "成功兑换！";

        exit;

    }

    function ajax_getuid() {

        echo $this->Session->read('User.uid');

        exit;

    }

    //忘记密码
    //2018/03/13
    function findPsd (){

        $this->css('reset.css');
        $this->css('css.css');
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/findPassword');
    }

    function findPassword($data = array())
    {
        

        if (!empty($data)) {

            $arr = json_decode(stripslashes(urldecode($data['data'])),true);

            if (empty($arr['mobile'])) {

                die(json_encode(array('status'=>-1,'message'=>"手机号码不能为空!")));

            }


            $vifcode = $arr['yz_code'];

            if (empty($vifcode)) {

                die(json_encode(array('status'=>-1,'message'=>"验证码不能为空!")));

            }

            $dbvifcode = strtolower($this->Session->read('User.yz_code'));

            if ($vifcode != $dbvifcode) {

                die(json_encode(array('status'=>-1,'message'=>"验证码错误!")));

            }

            $phone = $arr['mobile'];

            $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE home_phone='$phone' LIMIT 1";

            $dbuname = $this->App->findvar($sql);

            $sql = "SELECT uid FROM `{$this->App->prefix() }user_bank` WHERE mobile='$phone'";

            $bank_uid = $this->App->findvar($sql);

            if (empty($dbuname) && empty($bank_uid)) {

                die(json_encode(array('status'=>-1,'message'=>"未找到该用户!")));

            }

            $datas['password_instead'] = md5($arr['password']);

            $datas['home_phone']       = $phone;

            if($this->App->update('user', $datas, 'user_id', empty($dbuname)? $bank_uid :$dbuname)) {

                die(json_encode(array('status'=>1,'message'=>"更改成功!")));

            }else{

                die(json_encode(array('status'=>-1,'message'=>"意外错误")));

            }

        } // end if

        //商品分类列表

        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        if (!defined(NAVNAME)) define('NAVNAME', "找回密码");

        $this->set('rt', $rt);

    }
    //忘记密码
    function forgetpass() {

        $this->title("找回密码" . ' - ' . $GLOBALS['LANG']['site_name']);

        if (isset($_POST) && !empty($_POST)) {

            $uname = $_POST['uname'];

            if (empty($uname)) {

                $this->jump('', 0, '请输入您的账号名称！');

                exit;

            }

            $email = $_POST['email'];

            if (empty($email)) {

                $this->jump('', 0, '请输入您的原始电子邮箱！');

                exit;

            }

            $vifcode = $_POST['vifcode'];

            if (empty($vifcode)) {

                $this->jump('', 0, '请输入您的验证码！');

                exit;

            }

            $dbvifcode = strtolower($this->Session->read('vifcode'));

            if ($vifcode != $dbvifcode) {

                $this->jump('', 0, '验证码错误！');

                exit;

            }

            $sql = "SELECT user_name FROM `{$this->App->prefix() }user` WHERE user_name='$uname' LIMIT 1";

            $dbuname = $this->App->findvar($sql);

            if (empty($dbuname)) {

                $this->jump('', 0, '该用户不存在！');

                exit;

            }

            $sql = "SELECT user_name FROM `{$this->App->prefix() }user` WHERE user_name= '$uname' AND email='$email' LIMIT 1";

            $dbemail = $this->App->findvar($sql);

            if (empty($dbemail)) {

                $this->jump('', 0, '无法完成您的请求，您的用户名跟电子邮箱不对应！');

                exit;

            } else {

                $this->set('uname', $uname);

                $this->set('email', $email);

                $this->set('is_true', true);

                $this->template('user_forgetpass_result');

                exit;

            }

        } // end if

        //商品分类列表

        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        if (!defined(NAVNAME)) define('NAVNAME', "找回密码");

        $this->set('rt', $rt);

        $this->template('user_forgetpass');

    }

    //注册成功提示的页面

    function user_regsuccess_mes() {

        $this->title("注册成功" . ' - ' . $GLOBALS['LANG']['site_name']);

        $this->template('user_regsuccess_mes');

    }

    //自动登录

    function auto_login($data = array()) {

        $user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤

        $pass = md5(trim($data['password']));

        $sql = "SELECT password,user_id,last_login,active,user_rank FROM `{$this->App->prefix() }user` WHERE user_name='$user' LIMIT 1";

        $rt = $this->App->findrow($sql);

        if (empty($rt)) {

            return false;

        } else {

            if ($rt['password'] == $pass) {

                //登录成功,记录登录信息

                $ip = Import::basic()->getip();

                $datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;

                $datas['last_login'] = mktime();

                $datas['visit_count'] = '`visit_count`+1';

                $this->Session->write('User.prevtime', $rt['last_login']); //记录上一次的登录时间

                $this->App->update('user', $datas, 'user_id', $rt['user_id']); //更新

                $this->Session->write('User.username', $user);

                $this->Session->write('User.uid', $rt['user_id']);

                $this->Session->write('User.active', $rt['active']);

                $this->Session->write('User.rank', $rt['user_rank']);

                $this->Session->write('User.lasttime', $datas['last_login']);

                $this->Session->write('User.lastip', $datas['last_ip']);

                if (isset($data['issave']) && intval($data['issave']) == 1) {

                    setcookie(CFGH . 'USER[USERNAME]', $user, mktime() + 3600 * 24 * 30);

                    setcookie(CFGH . 'USER[PASS]', trim($data['password']), mktime() + 3600 * 24 * 30);

                } else {

                    if (isset($_COOKIE[CFGH . 'USER']['USERNAME'])) setcookie(CFGH . 'USER[USERNAME]', "", 0);

                    if (isset($_COOKIE[CFGH . 'USER']['PASS'])) setcookie(CFGH . 'USER[PASS]', "", 0);

                }

                if (isset($data['isauto']) && intval($data['isauto']) == 1) {

                    setcookie(CFGH . 'USER[AUTOLOGIN]', $data['isauto'], mktime() + 3600 * 24 * 30);

                } else {

                    if (isset($_COOKIE[CFGH . 'USER']['AUTOLOGIN'])) setcookie(CFGH . 'USER[AUTOLOGIN]', "", 0);

                }

                unset($data);

                return true;

            } else {

                //密码是错误的

                return false;

            }

        } //end if

        

    }

    //end function

    //ajax登录

    function ajax_user_login_instead($data = array()) {

        if (empty($data)) die("请填写完整信息");

        $user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤

        if (empty($user)) die("请输入账号");

        $pass = md5(trim($data['password']));

        if (empty($pass)) die("请输入密码");

        $sql = "SELECT password_instead,user_id,last_login,active,user_rank,home_phone,user_name FROM `{$this->App->prefix() }user` WHERE home_phone='$user' AND active='1' LIMIT 1";

        $rt = $this->App->findrow($sql);

        if (empty($rt)) {

            die("用户名不存在！");

        } else {

            if ($rt['password_instead'] == $pass) {

                //登录成功,记录登录信息

                $this->Session->write('User.iuid', $rt['user_id']);

                $this->Session->write('User.uid', $rt['user_id']);

                $this->Session->write('User.active', '1');

                $this->Session->write('User.rank', $rt['user_rank']);

                $this->Session->write('User.addtime', mktime());

                //写入cookie

                setcookie(CFGH . 'USER[IUID]', $rt['user_id'], mktime() + 2592000);

                unset($data);

                //2018/03/13
                die(json_encode(array('status'=>1,'message'=>"登录成功!")));
            } else {

                //密码是错误的

                die(json_encode(array('status'=>-1,'message'=>"密码错误!")));

            }

        }

    }

    function ajax_user_login($data = array()) {
        if (empty($data)) die("请填写完整信息");

        $user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤

        if (empty($user)) die("请输入用户名");

        $pass = md5(trim($data['password']));

        if (empty($pass)) die("请输入密码");

        $vcode = isset($data['vifcode']) ? $data['vifcode'] : "";

        if (!empty($vcode)) {

            if (strtolower($vcode) != strtolower($this->Session->read('vifcode'))) {

                die("验证码错误！");

            }

        }

        $sql = "SELECT password,user_id,last_login,active,user_rank,mobile_phone,wecha_id,user_name FROM `{$this->App->prefix() }user` WHERE mobile_phone='$user' AND active='1' LIMIT 1";

        $rt = $this->App->findrow($sql);

        if (empty($rt)) {

            die("用户名不存在或者还没审核！");

        } else {

            if ($rt['password'] == $pass) {

                //登录成功,记录登录信息

                $ip = Import::basic()->getip();

                $datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;

                $datas['last_login'] = mktime();

                $datas['visit_count'] = '`visit_count`+1';

                $this->Session->write('Agent.prevtime', $rt['last_login']); //记录上一次的登录时间

                $this->App->update('user', $datas, 'user_id', $rt['user_id']); //更新

                $this->Session->write('User.username', $rt['user_name']);

                $this->Session->write('User.uid', $rt['user_id']);

                $this->Session->write('User.active', '1');

                $this->Session->write('User.rank', $rt['user_rank']);

                $this->Session->write('User.ukey', $rt['wecha_id']);

                $this->Session->write('User.addtime', mktime());

                //写入cookie

                setcookie(CFGH . 'USER[UKEY]', $rt['wecha_id'], mktime() + 2592000);

                setcookie(CFGH . 'USER[UID]', $rt['user_id'], mktime() + 2592000);

                unset($data);

            } else {

                //密码是错误的

                die("密码错误");

            }

        }

    }

    //2018/03/13 新用户注册
    function user_register($data = array())
    {
        $result = array('error' => 2, 'message' => '传送的数据为空!');

        if (empty($data['data'])) die($json->encode($result));

        $arr = json_decode(stripslashes(urldecode($data['data'])),true);
        
        unset($data);

        //以下字段对应评论的表单页面 一定要一致

        $datas['user_rank'] = 9; //用户级别

        $datas['home_phone'] = $arr['mobile'];

        if (empty($datas['home_phone'])) {

            $result = array('error' => 2, 'message' => '请填上手机号码！');

            die(json_encode($result));

        }

        if (preg_match("/1[34578]{1}\d{9}$/", $datas['home_phone'])) {

        } else {

            $result = array('error' => 2, 'message' => '手机号码不合法，请重新输入！');

            die(json_encode($result));

        }

        //检查该手机是否已经使用了

        $mobile_phone = $datas['home_phone'];

        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE home_phone='$mobile_phone'";

        $uuid = $this->App->findvar($sql);

        $uuid_bank = $this->App->findvar("SELECT uid FROM `{$this->App->prefix() }user_bank` WHERE mobile='$mobile_phone'");

        if ($uuid > 0 || $uuid_bank > 0) {

            $result = array('error' => 2, 'message' => '抱歉，该手机号码已经被使用了！');

            if (empty($data['fromAttr'])) die(json_encode($result));

        }

        $datas['password_instead'] = $arr['password'];

        if (empty($datas['password_instead'])) {

            $result = array('error' => 2, 'message' => '用户密码不能为空！');

            if (empty($data['fromAttr'])) die(json_encode($result));

        }

        // $rp_pass = $fromAttr->rp_pass;

        // if ($rp_pass != $datas['password_instead']) {

        //     $result = array('error' => 2, 'message' => '两次密码不相同！');

        //     if (empty($data['fromAttr'])) die($json->encode($result));

        // }

        $yz_code = $arr['yz_code'];

        if (empty($yz_code)) {

            $result = array('error' => 2, 'message' => '手机验证码不能为空！');

            if (empty($data['fromAttr'])) die(json_encode($result));

        } else {

            $yz_codess = $this->Session->read('User.yz_code');

            if ($yz_code != $yz_codess) {

                $result = array('error' => 2, 'message' => '手机验证码不正确！');

                if (empty($data['fromAttr'])) die(json_encode($result));

            }

        }

        $datas['password_instead'] = md5($datas['password_instead']);

        $datas['InviteCode'] = $this->Session->read('InviteCode');

        $datas['nickname'] = $arr['nickname'];
        
        $ip = Import::basic()->getip();

        $datas['reg_ip'] = $ip ? $ip : '0.0.0.0';

        $datas['reg_time'] = mktime();

        $datas['reg_from'] = Import::ip()->ipCity($ip);

        $datas['last_login'] = mktime();

        $datas['last_ip'] = $datas['reg_ip'];

        // $datas['last_ip'] = $datas['reg_ip'];

        $datas['active'] = 1;

        if ($this->App->insert('user', $datas)) {

            $invite['uid'] = $this->App->iid();

            $invite['status'] = 1;

            $invite['updatetime'] = mktime();

            $this->App->update('daili_invitecode', $invite, 'InviteCode', $datas['InviteCode']);

            $this->Session->write('InviteCode', null);

            //            $this->Session->write('User.uid', $uid);

            //            $this->Session->write('User.active', $datas['active']);

            //            $this->Session->write('User.rank', 9);

            //            $this->Session->write('User.lasttime', $datas['last_login']);

            //            $this->Session->write('User.lastip', $datas['last_ip']);

            //
            // 2018/03/19 写入推荐关系
            if(!empty($arr['parent_uid'])){

                $tuijian['parent_uid'] = $arr['parent_uid'];

                $tuijian['addtime']    = time();

                $tuijian['uid']        = $invite['uid'];

                $this->App->insert('user_tuijian', $tuijian);

                //查找是否存在上上级 2018/03/19

                $sql = "SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = ".$arr['parent_uid'];

                $tid = $this->App->findrow($sql);

                if(!empty($tid['parent_uid'])){

                    $sql = "SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = ".$tid['parent_uid'];

                    $ttid = $this->App->findrow($sql);

                }

                $tuijian_fx = array();

                $tuijian_fx['uid']    = $invite['uid'];

                $tuijian_fx['p1_uid'] = $arr['parent_uid'];

                $tuijian_fx['p2_uid'] = $tid['parent_uid'];

                $tuijian_fx['p3_uid'] = $ttid['parent_uid'];

                $tuijian_fx['active'] = 1;

                $this->App->insert('user_tuijian_fx', $tuijian_fx);


            }

            $result = array('error' => 0, 'message' => '注册成功!');

            unset($datas);

        } else {

            $result = array('error' => 2, 'message' => '注册失败!');

        }
        die(json_encode($result));
    }

    function test()
    {
        
    }

    //ajax注册

    function ajax_user_register_instead($data = array()) {

        $json = Import::json();

        $result = array('error' => 2, 'message' => '传送的数据为空!');

        if (empty($data['fromAttr'])) die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象

        unset($data);

        //以下字段对应评论的表单页面 一定要一致

        $datas['user_rank'] = 9; //用户级别

        $datas['home_phone'] = $fromAttr->home_phone;

        if (empty($datas['home_phone'])) {

            $result = array('error' => 2, 'message' => '请填上手机号码！');

            if (empty($data['fromAttr'])) die($json->encode($result));

        }

        if (preg_match("/1[34578]{1}\d{9}$/", $datas['home_phone'])) {

        } else {

            $result = array('error' => 2, 'message' => '手机号码不合法，请重新输入！');

            if (empty($data['fromAttr'])) die($json->encode($result));

        }

        //检查该手机是否已经使用了

        $mobile_phone = $datas['home_phone'];

        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE home_phone='$mobile_phone'";

        $uuid = $this->App->findvar($sql);

        $uuid_bank = $this->App->findvar("SELECT uid FROM `{$this->App->prefix() }user_bank` WHERE mobile='$mobile_phone'");

        if ($uuid > 0 || $uuid_bank > 0) {

            $result = array('error' => 2, 'message' => '抱歉，该手机号码已经被使用了！');

            if (empty($data['fromAttr'])) die($json->encode($result));

        }

        $datas['password_instead'] = $fromAttr->password;

        if (empty($datas['password_instead'])) {

            $result = array('error' => 2, 'message' => '用户密码不能为空！');

            if (empty($data['fromAttr'])) die($json->encode($result));

        }

        $rp_pass = $fromAttr->rp_pass;

        if ($rp_pass != $datas['password_instead']) {

            $result = array('error' => 2, 'message' => '两次密码不相同！');

            if (empty($data['fromAttr'])) die($json->encode($result));

        }

        $yz_code = $fromAttr->yz_code;

        if (empty($yz_code)) {

            $result = array('error' => 2, 'message' => '手机验证码不能为空！');

            if (empty($data['fromAttr'])) die($json->encode($result));

        } else {

            $yz_codess = $this->Session->read('User.yz_code');

            if ($yz_code != $yz_codess) {

                $result = array('error' => 2, 'message' => '手机验证码不正确！');

                if (empty($data['fromAttr'])) die($json->encode($result));

            }

        }

        $datas['password_instead'] = md5($datas['password_instead']);

        $datas['InviteCode'] = $this->Session->read('InviteCode');

        $ip = Import::basic()->getip();

        $datas['reg_ip'] = $ip ? $ip : '0.0.0.0';

        $datas['reg_time'] = mktime();

        $datas['reg_from'] = Import::ip()->ipCity($ip);

        $datas['last_login'] = mktime();

        $datas['last_ip'] = $datas['reg_ip'];

        $datas['active'] = 1;

        if ($this->App->insert('user', $datas)) {

            $invite['uid'] = $this->App->iid();

            $invite['status'] = 1;

            $invite['updatetime'] = mktime();

            $this->App->update('daili_invitecode', $invite, 'InviteCode', $datas['InviteCode']);

            $this->Session->write('InviteCode', null);

            //            $this->Session->write('User.uid', $uid);

            //            $this->Session->write('User.active', $datas['active']);

            //            $this->Session->write('User.rank', 9);

            //            $this->Session->write('User.lasttime', $datas['last_login']);

            //            $this->Session->write('User.lastip', $datas['last_ip']);

            //

            $result = array('error' => 0, 'message' => '注册成功!');

            unset($datas);

        } else {

            $result = array('error' => 2, 'message' => '注册失败!');

        }

        die($json->encode($result));

    }

    //ajax删除用户收货地址

    function ajax_delress($id = 0) {

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) die("请您先登录！");

        if (empty($id)) die("非法删除！");

        if ($this->App->delete('user_address', 'address_id', $id)) {

        } else {

            die("删除失败!");

        }

    }

    //设置为默认收货地址

    /* function ajax_setaddress($data=array()){

    

      $uid = $this->Session->read('User.uid');

    

      if(empty($uid)) die("请您先登录！");

    

      $id = isset($data['id'])?intval($data['id']):0;

    

      $val = isset($data['val'])?$data['val']:0;

    

      if($id>0){

    

      $sql = "UPDATE `{$this->App->prefix()}user_address` SET type='0' WHERE user_id='$uid'";

    

      $this->App->query($sql);

    

      $sql = "UPDATE `{$this->App->prefix()}user_address` SET type='$val' WHERE user_id='$uid' AND address_id='$id'";

    

      if($this->App->query($sql)){

    

      die("");

    

      }else{

    

      die("设置失败！");

    

      }

    

      }else{

    

      die("传送ID为空！");

    

      }

    

      } */

    //ajax更新用户信息

    function ajax_updateinfo($data = array()) {

        $json = Import::json();

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $result = array('error' => 3, 'message' => '先您先登录!');

            die($json->encode($result));

        }

        $result = array('error' => 2, 'message' => '传送的数据为空!');

        if (empty($data['fromAttr'])) die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象

        unset($data);

        //以下字段对应评论的表单页面 一定要一致

        /* $emails = $fromAttr->email;

        

          if(!empty($emails)){

        

          $sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails' AND user_rank='1'";

        

          $dbemail = $this->App->findvar($sql);

        

          if(!empty($dbname)&&dbemail !=$emails){

        

          $result = array('error' => 4, 'message' => '不能更改这个电子邮箱,已经被使用!');

        

          die($json->encode($result));

        

          }

        

          } */

        $datas['qq'] = $fromAttr->qq;

        $datas['password'] = $fromAttr->pass;

        $datas['mobile_phone'] = $fromAttr->mobile_phone;

        if (empty($datas['mobile_phone'])) {

            $result = array('error' => 4, 'message' => '填写电话或者手机号码！');

            die($json->encode($result));

        }

        if (empty($datas['password'])) {

            $result = array('error' => 4, 'message' => '请输入6位密码！');

            die($json->encode($result));

        }

        $datas['password'] = md5($datas['password']);

        if (empty($datas['qq'])) {

            $result = array('error' => 4, 'message' => '请输入QQ号码！');

            die($json->encode($result));

        }

        //检测该号码是否存在

        $mb = $datas['mobile_phone'];

        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE mobile_phone = '$mb' AND user_id!='$uid' LIMIT 1";

        $id = $this->App->findvar($sql);

        if ($id > 0) {

            $result = array('error' => 4, 'message' => '该电话号码已经被使用！');

            die($json->encode($result));

        }

        if ($this->App->update('user', $datas, 'user_id', $uid)) {

            unset($datas);

            $result = array('error' => 5, 'message' => '更新成功!');

            die($json->encode($result));

        }

        //$datas['question'] = $fromAttr->question;

        //$datas['answer'] = $fromAttr->answer;

        //更新表

        /* $is_jifen = false;

        

          $sql = "SELECT uptime,reg_time FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND user_rank='1'";

        

          $dts = $this->App->findrow($sql);

        

          if(!empty($dts)){

        

          if(empty($dts['uptime'])&&($dts['reg_time']+3600*24*7)>mktime()) $is_jifen = true; //七天之内更新资料有送积分,而且是第一次更新资料

        

          }

        

          if($this->App->update('user',$datas,'user_id',$uid)){

        

          if($is_jifen){

        

          //$this->add_user_jifen('upuserinfo');

        

          }

        

          unset($datas,$dts);

        

          }

        

        

        

          ############################

        

          $dd = array();

        

          $sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";

        

          $rsid = $this->App->findvar($sql);

        

        

        

          $dd['consignee'] = $fromAttr->consignee;

        

          if(empty($dd['consignee'])){

        

          $result = array('error' => 4, 'message' => '真实姓名不能为空！');

        

          die($json->encode($result));

        

          }

        

          $dd['country'] = '1';

        

          $dd['province'] = $fromAttr->province;

        

          $dd['city'] = $fromAttr->city;

        

          $dd['district'] = $fromAttr->district;

        

          $dd['is_own'] = '1';

        

          $dd['address'] = $fromAttr->address;

        

          //$dd['zipcode'] = $fromAttr->zipcode;

        

          $dd['user_id'] = $uid;

        

          if(empty($rsid)){ //添加

        

          if(!empty($dd['consignee'])){

        

          $this->App->insert('user_address',$dd);

        

          }

        

          }else{ //更新

        

          if($this->App->update('user_address',$dd,'address_id',$rsid)){

        

          unset($dd);

        

          if($is_jifen){

        

          //$result = array('error' => 5, 'message' => '更新成功！您在特定时间更新个人信息，赠送10积分！');

        

          //die($json->encode($result));

        

          }

        

          }

        

          } */

        ############################

        //if($this->App->update('user',$datas,'user_id',$uid)){

        $result = array('error' => 10, 'message' => '更新成功!');

        //}else{

        //$result = array('error' => 2, 'message' => '无法更新!');

        //}

        die($json->encode($result));

    }

    //ajax更新用户店铺

    function ajax_updateshop($data = array()) {

        $json = Import::json();

        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {

            $result = array('error' => 3, 'message' => '先您先登录!');

            die($json->encode($result));

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}' LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        if ($rt['userinfo']['user_rank'] != 10) {

            $result = array('error' => 4, 'message' => '您无法进行此操作!');

            die($json->encode($result));

        }

        $result = array('error' => 2, 'message' => '传送的数据为空!');

        if (empty($data['fromAttr'])) die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象

        unset($data);

        //以下字段对应评论的表单页面 一定要一致

        /* $emails = $fromAttr->email;

        

          if(!empty($emails)){

        

          $sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails' AND user_rank='1'";

        

          $dbemail = $this->App->findvar($sql);

        

          if(!empty($dbname)&&dbemail !=$emails){

        

          $result = array('error' => 4, 'message' => '不能更改这个电子邮箱,已经被使用!');

        

          die($json->encode($result));

        

          }

        

          } */

        $datas['qq'] = $fromAttr->qq;

        $datas['shop_name'] = $fromAttr->shop_name;

        $datas['mobile_phone'] = $fromAttr->mobile_phone;

        if (empty($datas['mobile_phone'])) {

            $result = array('error' => 4, 'message' => '填写电话或者手机号码！');

            die($json->encode($result));

        }

        if (empty($datas['shop_name'])) {

            $result = array('error' => 4, 'message' => '请输入店铺名称！');

            die($json->encode($result));

        }

        ///  $datas['shop_name'] = $datas['shop_name'];

        //检测该号码是否存在

        $mb = $datas['mobile_phone'];

        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE mobile_phone = '$mb' AND user_id!='$uid' LIMIT 1";

        $id = $this->App->findvar($sql);

        if ($id > 0) {

            $result = array('error' => 4, 'message' => '该电话号码已经被使用！');

            die($json->encode($result));

        }

        if ($this->App->update('user', $datas, 'user_id', $uid)) {

            unset($datas);

            $result = array('error' => 5, 'message' => '更新成功!');

            die($json->encode($result));

        }

        //$datas['question'] = $fromAttr->question;

        //$datas['answer'] = $fromAttr->answer;

        //更新表

        /* $is_jifen = false;

        

          $sql = "SELECT uptime,reg_time FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND user_rank='1'";

        

          $dts = $this->App->findrow($sql);

        

          if(!empty($dts)){

        

          if(empty($dts['uptime'])&&($dts['reg_time']+3600*24*7)>mktime()) $is_jifen = true; //七天之内更新资料有送积分,而且是第一次更新资料

        

          }

        

          if($this->App->update('user',$datas,'user_id',$uid)){

        

          if($is_jifen){

        

          //$this->add_user_jifen('upuserinfo');

        

          }

        

          unset($datas,$dts);

        

          }

        

        

        

          ############################

        

          $dd = array();

        

          $sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";

        

          $rsid = $this->App->findvar($sql);

        

        

        

          $dd['consignee'] = $fromAttr->consignee;

        

          if(empty($dd['consignee'])){

        

          $result = array('error' => 4, 'message' => '真实姓名不能为空！');

        

          die($json->encode($result));

        

          }

        

          $dd['country'] = '1';

        

          $dd['province'] = $fromAttr->province;

        

          $dd['city'] = $fromAttr->city;

        

          $dd['district'] = $fromAttr->district;

        

          $dd['is_own'] = '1';

        

          $dd['address'] = $fromAttr->address;

        

          //$dd['zipcode'] = $fromAttr->zipcode;

        

          $dd['user_id'] = $uid;

        

          if(empty($rsid)){ //添加

        

          if(!empty($dd['consignee'])){

        

          $this->App->insert('user_address',$dd);

        

          }

        

          }else{ //更新

        

          if($this->App->update('user_address',$dd,'address_id',$rsid)){

        

          unset($dd);

        

          if($is_jifen){

        

          //$result = array('error' => 5, 'message' => '更新成功！您在特定时间更新个人信息，赠送10积分！');

        

          //die($json->encode($result));

        

          }

        

          }

        

          } */

        ############################

        //if($this->App->update('user',$datas,'user_id',$uid)){

        $result = array('error' => 10, 'message' => '更新成功!');

        //}else{

        //$result = array('error' => 2, 'message' => '无法更新!');

        //}

        die($json->encode($result));

    }

    function ajax_get_ress($data = array()) {

        $type = $data['type'];

        $parent_id = $data['parent_id'];

        if (empty($type) || empty($parent_id)) {

            exit;

        }

        $sql = "SELECT region_id,region_name FROM `{$this->App->prefix() }region` WHERE region_type='$type' AND parent_id='$parent_id' ORDER BY region_id ASC";

        $rt = $this->App->find($sql);

        if (!empty($rt)) {

            if ($type == 2) {

                $str = '<option value="0">选择城市</option>';

            } else if ($type == 3) {

                $str = '<option value="0">选择区</option>';

            }

            foreach ($rt as $row) {

                $str.= '<option value="' . $row['region_id'] . '">' . $row['region_name'] . '</option>' . "\n";

            }

            die($str);

        }

    }

    function ajax_get_ge_peisong($data = array()) {

        $district_id = $data['district_id'];

        if (empty($district_id)) {

            exit;

        }

        $sql = "SELECT tb1.user_id,tb2.nickname,tb1.consignee FROM `{$this->App->prefix() }user_address` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.district='$district_id' AND tb1.is_own='1' AND tb2.user_rank='12'";

        $rt = $this->App->find($sql);

        if (empty($rt)) {

            $sql = "SELECT tb1.user_id,tb3.nickname,tb1.consignee FROM `{$this->App->prefix() }user_address` AS tb1 LEFT JOIN `{$this->App->prefix() }region` AS tb2 ON tb1.district = tb2.region_id LEFT JOIN `{$this->App->prefix() }user` AS tb3 ON tb1.user_id = tb3.user_id WHERE tb2.parent_id='$district_id' AND tb1.is_own='1' AND tb3.user_rank='12'";

            $rt = $this->App->find($sql);

            if (empty($rt)) {

                $sql = "SELECT tb1.user_id,tb2.nickname,tb1.consignee FROM `{$this->App->prefix() }user_address` AS tb1 LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.is_own='1' AND tb2.user_rank='12'";

                $rt = $this->App->find($sql);

            }

        }

        if (!empty($rt)) {

            $str = '<option value="0">选择配送店</option>';

            foreach ($rt as $row) {

                $str.= '<option value="' . $row['user_id'] . '">' . (!empty($row['nickname']) ? $row['nickname'] : $row['consignee'] . '配送店') . '</option>' . "\n";

            }

            die($str);

        }

    }

    function ajax_ressinfoop($data = array()) {

        $uid = $this->Session->read('User.uid');

        if (isset($data['attrbul']) && !empty($data['attrbul'])) {

            $err = 0;

            $result = array('error' => $err, 'message' => '');

            $json = Import::json();

            $attrbul = $json->decode($data['attrbul']); //反json

            if (empty($attrbul)) {

                $result['error'] = 1;

                $result['message'] = "传送的数据为空！";

                die($json->encode($result));

            }

            $id = $attrbul->id;

            $dd = array();

            $type = $attrbul->type;

            $dd['user_id'] = $uid;

            $dd['consignee'] = $attrbul->consignee;

            if (empty($dd['consignee'])) {

                $result['error'] = 1;

                $result['message'] = "收货人姓名不能为空！";

                die($json->encode($result));

            }

            $dd['country'] = 1;

            $dd['province'] = $attrbul->province;

            $dd['city'] = $attrbul->city;

            $dd['district'] = $attrbul->district;

            $dd['address'] = $attrbul->address;

            /* $dd['shoppingname'] = $attrbul->shoppingname;

            

              $dd['shoppingtime'] = $attrbul->shoppingtime; */

            if (empty($dd['province']) || empty($dd['city']) || empty($dd['district']) || empty($dd['address'])) {

                $result['error'] = 1;

                $result['message'] = "收货地址不能为空！";

                die($json->encode($result));

            }

            //$dd['sex'] = $attrbul->sex;

            $dd['email'] = $attrbul->email;

            //$dd['zipcode'] = $attrbul->zipcode;

            $dd['mobile'] = $attrbul->mobile;

            //$dd['tel'] = $attrbul->tel;

            if (empty($dd['mobile']) && empty($dd['tel'])) {

                $result['error'] = 1;

                $result['message'] = "电话或者手机必须填写一个！";

                die($json->encode($result));

            }

            $dd['is_default'] = '1';

            if (!($id > 0) && $type == 'add') { //添加

                $this->App->update('user_address', array('is_default' => '0'), 'user_id', $uid);

                $this->App->insert('user_address', $dd);

            } elseif ($type == 'update') { //编辑

                $this->App->update('user_address', $dd, 'address_id', $id);

            }

            unset($dd);

            if (empty($dd['mobile']) && empty($dd['tel'])) {

                $result['error'] = 0;

                $result['message'] = "操作成功！";

                die($json->encode($result));

            }

            exit;

        }

        //配送方式

        $sql = "SELECT * FROM `{$this->App->prefix() }shipping`";

        $rt['shippinglist'] = $this->App->find($sql);

        $id = $data['id'];

        $type = $data['type'];

        if (!empty($id) && !empty($type)) {

            switch ($type) {

                case 'delete': //删除收货地址

                    $this->App->delete('user_address', 'address_id', $id);

                break;

                case 'setdefaut': //设为默认收货地址

                    if (!empty($uid)) {

                        $this->App->update('user_address', array('is_default' => '0'), 'user_id', $uid);

                        $this->App->update('user_address', array('is_default' => '1'), 'address_id', $id);

                    }

                break;

                case 'quxiao': //取消收货地址

                    $this->App->update('user_address', array('is_default' => '0'), 'address_id', $id);

                break;

                case 'showupdate':

                    //当前用户的收货地址

                    $sql = "SELECT * FROM `{$this->App->prefix() }user_address` WHERE user_id='$uid' AND address_id='$id'";

                    $rt['userress'] = $this->App->findrow($sql);

                    $rt['province'] = $this->get_regions(1); //获取省列表

                    $rt['city'] = $this->get_regions(2, $rt['userress']['province']); //城市

                    $rt['district'] = $this->get_regions(3, $rt['userress']['city']); //区

                    $this->set('rt', $rt);

                    $con = $this->fetch('ajax_show_updateressbox', true);

                    die($con);

                break;

            }

        }

    }

    //订单的状态

    function get_status($oid = 0, $pid = 0, $sid = 0) { //分别为：订单 支付 发货状态

        $str = '';

        //  switch ($oid) {

        //            case '0':

        //                $str .= '未确认,';

        //                break;

        //            case '1':

        //                $str .= '<font color="red">取消</font>,';

        //                break;

        //            case '2':

        //                $str .= '确认,';

        //                break;

        //            case '3':

        //                $str .= '<font color="red">退货</font>,';

        //                break;

        //            case '4':

        //                $str .= '<font color="red">无效</font>,';

        //                break;

        //        }

        switch ($pid) {

            case '0':

                $str.= '未付款';

            break;

            case '1':

                $str.= '已付款';

            break;

            case '2':

                $str.= '已退款';

            break;

        }

        // switch ($sid) {

        //            case '0':

        //                $str .= '未发货';

        //                break;

        //            case '1':

        //                $str .= '配货中';

        //                break;

        //            case '2':

        //                $str .= '已发货';

        //                break;

        //            case '3':

        //                $str .= '部分发货';

        //                break;

        //            case '4':

        //                $str .= '退货';

        //                break;

        //            case '5':

        //                $str .= '已收货';

        //                break;

        //        }

        return $str;

    }

    function get_option($sn = 0, $oid = 0, $pid = 0, $sid = 0) {

        if (empty($sn)) return "";

        $str = '';

        switch ($sid) {

            case '2':

                return $str = '<a href="javascript:;" name="confirm" id="' . $sn . '" class="oporder"><font color="red">确认收货</font></a>';

            break;

            case '5':

                return $str = '<a href="javascript:;"><font color="red">已完成</font></a>';

            break;

        }

        switch ($oid) {

            case '0':

                $str = '<a href="javascript:;" name="cancel_order" id="' . $sn . '" class="oporder"><font color="red">取消订单</font></a>';

            break;

            case '1':

                $str = '<a href="javascript:;"><font color="red">已取消</font></a>';

            break;

            case '2':

                $str = '<a href="javascript:;"><font color="red">已确认</font></a>';

            break;

            case '3':

                $str = '<a href="javascript:;"><font color="red">已退货</font></a>';

            break;

            case '4':

                $str = '<a href="javascript:;"><font color="red">无效订单</font></a>';

            break;

        }

        return $str;

    }

    ########################################

    /*

    

     * 自定义大小验证码函数

    

     * @$num:字符数

    

     * @$size:大小

    

     * @$width,$height:不设置会自动

    

    */

    function vCode($num = 4, $size = 18, $width = 0, $height = 0) {

        !$width && $width = $num * $size * 4 / 5 - 2;

        !$height && $height = $size + 8;

        // 去掉了 0 1 O l 等

        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";

        $code = '';

        for ($i = 0;$i < $num;$i++) {

            $code.= $str[mt_rand(0, strlen($str) - 1) ];

        }

        //写入session

        $this->Session->write('vifcode', $code);

        // 画图像

        $im = imagecreatetruecolor($width, $height);

        // 定义要用到的颜色

        $back_color = imagecolorallocate($im, 235, 236, 237);

        $boer_color = imagecolorallocate($im, 118, 151, 199);

        $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));

        // 画背景

        imagefilledrectangle($im, 0, 0, $width, $height, $back_color);

        // 画边框

        imagerectangle($im, 0, 0, $width - 1, $height - 1, $boer_color);

        // 画干扰线

        for ($i = 0;$i < 5;$i++) {

            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

            imagearc($im, mt_rand(-$width, $width), mt_rand(-$height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);

        }

        // 画干扰点

        for ($i = 0;$i < 50;$i++) {

            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);

        }

        //echo $this->Session->read('vifcode');

        // 画验证码

        @imagefttext($im, $size, 0, 5, $size + 3, $text_color, SYS_PATH . 'data/monofont.ttf', $code);

        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");

        header("Content-type: image/png");

        imagepng($im);

        imagedestroy($im);

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

    function baoming() {

        $uid = $this->checked_login();

        $sql = "SELECT status FROM `{$this->App->prefix() }user_bank` WHERE uid = '$uid' LIMIT 1";

        $rt = $this->App->findvar($sql);

        if (!$rt) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng');

            exit;

        }

        //判断信用卡还款计划

        $instead_plan = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where user_id=" . $uid . " and status =1 and stop = 0");

        $sql = "SELECT * FROM `{$this->App->prefix() }cx_baoming` order by id asc";

        $bm = $this->App->find($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }cx_baoming` ";

        $bmx = $this->App->find($sql);

        foreach ($bmx as $row) {

            $bmx['description'][$row['id']] = $row['description'];

            $bmx['content'][$row['id']] = $row['content'];

            $bmx['koulv'][$row['id']] = $row['koulv'];

            $bmx['price'][$row['id']] = $row['price'];

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }user_level` ";

        $fl = $this->App->find($sql);

        foreach ($fl as $row) {

            $fl['discount'][$row['lid']] = $row['discount'] / 100;

        }

        //$sql = "SELECT tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district,tb5.region_name AS towns, tb6.region_name AS villages FROM `{$this->App->prefix()}goods_order_info` AS tb1";

        //					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";

        //					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";

        //					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";

        //					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id = tb1.town";

        //					$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.village";

        //					$sql .=" WHERE tb1.order_id ='$oid' LIMIT 1";

        $sql = "SELECT tb1.nickname,tb2.level_name FROM `{$this->App->prefix() }user` AS tb1 ";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_level` AS tb2 ON tb2.lid = tb1.user_rank";

        $sql.= " WHERE tb1.user_id='$uid' LIMIT 1";

        $rt = $this->App->findrow($sql);

        $sql1 = "SELECT fenrun,yongjin,tuiguang,yinlian,weixin,haiwai,jingdong,zhifubao FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";

        $row = $this->App->findrow($sql1);

        $rt['tuiguang_money'] = $row['fenrun'] + $row['yongjin'] + $row['tuiguang'];

        $rt['tuiguang_money'] = sprintf("%.2f", substr(sprintf("%.3f", $rt['tuiguang_money']), 0, -1));

        //会员关系

        //一级

        //可用推广人数

        // $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";

        //				 $sql .= " LEFT JOIN `{$this->App->prefix()}user_bank` AS tb2 ON tb2.uid = tb1.uid ";

        //				 $sql .= " WHERE tb1.parent_uid = '$uid' and tb2.status = 1 LIMIT 1";

        $sql = "SELECT COUNT(id) FROM `{$this->App->prefix() }user_tuijian` WHERE parent_uid = '$uid' LIMIT 1";

        $rt1['zcount1'] = $this->App->findvar($sql);

        //二级

        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";

        $sql.= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL  LIMIT 1";

        $rt2['zcount2'] = $this->App->findvar($sql);

        //三级

        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix() }user_tuijian` AS tb1";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";

        $sql.= " LEFT JOIN `{$this->App->prefix() }user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";

        $sql.= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  LIMIT 1";

        $rt3['zcount3'] = $this->App->findvar($sql);

        $rt['zcount'] = $rt1['zcount1'] + $rt2['zcount2'] + $rt3['zcount3'];

      

        $this->title("会员升级中心");

        if (!defined(NAVNAME)) define('NAVNAME', "会员升级");

        $this->set('instead_plan', $instead_plan);

        $this->set('bm', $bm);

        $this->set('bmx', $bmx);

        $this->set('fl', $fl);

        $this->set('rt', $rt);

        $this->css('reset.css');

        $this->css('css.css');

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/user_baoming');

    }

    function confirmpay($data = array()) {

        $uid = $this->checked_login();
        echo 11;
        if (!empty($_POST)) {

            $id = $_POST['RadioGroup1'];

            $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

            $s = "WHERE id='$id'";

            $sql = "SELECT * FROM `{$this->App->prefix() }cx_baoming` {$s} ORDER BY id DESC LIMIT 1";

            $rt['pinfo'] = $this->App->findrow($sql);

            if ($rank >= $rt['pinfo']['rank_id']) {

                $this->jump(ADMIN_URL . "user.php?act=baoming", 0, '您的级别高于当前级别，您可以向更高级别进军了！');

                exit;

            }

            $price = $rt['pinfo']['price'];

            $on = date('Y', mktime()) . mktime();

            $dd = array();

            $dd['bid'] = $id;

            $dd['order_sn'] = $on;

            $dd['user_id'] = $uid;

            $dd['order_amount'] = $price;

            $dd['add_time'] = mktime();

            $sql = "SELECT fenrun,yongjin,tuiguang,yinlian,weixin,baidu,jingdong,duanxin FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1";

            $row = $this->App->findrow($sql);

            $tuiguang_money = $row['yilian'] + $row['weixin'] + $row['baidu'] + $row['jingdong'] + $row['duanxin'];

            // if ($tuiguang >= $price) {

            //                $money = -$price;

            //                $sql = "UPDATE `{$this->App->prefix()}user` SET `tuiguang` = `tuiguang`+$money WHERE user_id = '$uid'";

            //                $this->App->query($sql);

            //$this->App->insert('cx_baoming_order', $dd);

            // $sd = array();

            //                $sd = array('order_sn' => $dd['order_sn'], 'status' => 1);

            //                if ($this->baoming_pay_successs_tatus($dd['order_sn'])) {

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

            //                    $this->jump(ADMIN_URL . 'user.php?act=orderlist', 0, '已成功支付');

            //                    exit;

            //                } else {

            //                    $this->jump(ADMIN_URL . 'mycart.php?type=pay2&oid=' . $oid, 0, '意外错误！');

            //                    exit;

            //                }

            //            } else {

            $this->jump(ADMIN_URL . 'mycart.php?type=pay_shengji&id=' . $id, 0);

            //  }

            //

            //

            

        }

    }

    function baoming_pay_successs_tatus($order_sn = '') {

        //改变状态

        $dd = array();

        $dd['pay_status'] = '1';

        $dd['pay_time'] = mktime();

        $this->App->update('cx_baoming_order', $dd, 'order_sn', $order_sn);

        //开通分销

        $sql = "SELECT openfx_baoming FROM `{$this->App->prefix() }userconfig` WHERE type='basic' LIMIT 1"; //用户配置信息

        $openfx_baoming = $this->App->findvar($sql);

        if ($openfx_baoming == '1') {

            $userinfo = $this->App->findrow("SELECT bo.user_id ,bo.bid,bo.order_amount,b.rank_id  FROM `{$this->App->prefix() }cx_baoming_order` as  bo left join `{$this->App->prefix() }cx_baoming` as b  on b.id=bo.bid WHERE order_sn='$order_sn' LIMIT 1");

            $uid = $userinfo['user_id'];

            if ($uid) {

                $newrank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

                /* huixia */

                //修改会员等级

                // $tp = fopen(time() . ".txt", "a+");

                if ($userinfo['rank_id'] > $newrank) {

                    $this->App->update('user', array('user_rank' => $userinfo['rank_id']), 'user_id', $uid);

                    $this->update_daili_tree($uid); //更新代理关系

                    //记录父级升级记录

                    $remarklog = '直接充值进行会员升级';

                    $sql = "insert into `{$this->App->prefix() }user_level_log` (user_id,user_rank,create_time,type,remark) values ('$uid', $userinfo[rank_id],UNIX_TIMESTAMP(),2,'$remarklog')";

                    // fwrite($tp, $sql . "\r\n");

                    $this->App->query($sql);

                    //查看是否有父级

                    $sql = "SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = '$uid' LIMIT 1";

                    //  fwrite($tp, $sql . "\r\n");

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

                        //，有父级增加团队

                        //如果是该用户升级 则修改以前的记录状态

                        /* $sql = "SELECT count(*) FROM `{$this->App->prefix()}user_team` WHERE   user_id=$p and son_id=$uid and `status`=1  and  create_time>UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 7 DAY))  LIMIT 1";

                        

                          $count = $this->App->findvar($sql); */

                        //if ($count) {

                        $sql = "update  `{$this->App->prefix() }user_team` set status=2 where user_id=$p and son_id=$uid";

                        fwrite($tp, $sql . "\r\n");

                        $this->App->query($sql);

                        // }

                        $sql = "insert into  `{$this->App->prefix() }user_team` (user_id,user_rank,amount,pay_type,create_time,son_id,pay_amount)" . " values ($p,$prank,$userinfo[order_amount],$userinfo[rank_id],UNIX_TIMESTAMP(),$uid,$userinfo[order_amount])";

                        fwrite($tp, $sql . "\r\n");

                        $this->App->query($sql);

                        //，有父级 给父级增加推广金额

                        //根据父级的等级 及发展的会员等级  增加父级推广费用

                        //鑫鑫  推广分成

                        if ($prank > $srank) {

                            $payfee = array(12 => array(12 => $userinfo['order_amount'] * 0.6, 11 => $userinfo['order_amount'] * 0.6, 10 => $userinfo['order_amount'] * 0.6), 11 => array(11 => $userinfo['order_amount'] * 0.4, 10 => $userinfo['order_amount'] * 0.4), 10 => array(10 => $userinfo['order_amount'] * 0.3));

                            $moeys = $payfee[$prank][$srank];

                        } else {

                            $moeys = 0.00;

                        }

                        //鑫鑫  推广分成

                        //  $payfee = array(

                        //                            12 => array(12 => 10, 11 => 100, 10 => 200),

                        //                            11 => array(12 => 20, 11 => 200, 10 => 500),

                        //                            10 => array(12 => 30, 11 => 300, 10 => 1000)

                        //                        );

                        //                        $moeys = $payfee[$prank][$srank];

                        $thismonth = date('Y-m-d', mktime());

                        $thism = date('Y-m', mktime());

                        $sql = "UPDATE `{$this->App->prefix() }user` SET `tuiguang` = `tuiguang`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$p'";

                        fwrite($tp, $sql . "\r\n");

                        $this->App->query($sql);

                        $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值,会员升级返佣金', 'time' => mktime(), 'type' => 999, 'uid' => $p));

                        //发送推荐用户通知

                        $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$p' LIMIT 1");

                        $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => '', 'money' => $moeys, 'rank_id' => $userinfo['rank_id']), 'tjmember');

                        //查找上上级会员信息 如果是黄金会员

                        $sql = "SELECT * FROM `{$this->App->prefix() }user_tuijian_fx` WHERE uid = '$uid' LIMIT 1";

                        fwrite($tp, $sql . "\r\n");

                        $ppuser = $this->App->findrow($sql);

                        //  $payfee2 = array(12 => 5, 11 => 100, 10 => 500);

                        //上上级会员的等级

                        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser[p2_uid]' LIMIT 1";

                        fwrite($tp, $sql . "\r\n");

                        $ppuser2 = $this->App->findrow($sql);

                        $prank2 = $ppuser2['user_rank'];

                        //如果是黄金会员则参与分成

                        if (($prank2 > $prank) && ($prank2 > $srank)) {

                            $payfee2 = array(12 => array(12 => $userinfo['order_amount'] * 0.3, 11 => $userinfo['order_amount'] * 0.3, 10 => $userinfo['order_amount'] * 0.3), 11 => array(11 => $userinfo['order_amount'] * 0.1, 10 => $userinfo['order_amount'] * 0.1),);

                            $moeys = $payfee2[$prank2][$srank];

                            $thismonth = date('Y-m-d', mktime());

                            $thism = date('Y-m', mktime());

                            $sql = "UPDATE `{$this->App->prefix() }user` SET `tuiguang` = `tuiguang`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$ppuser2[user_id]'";

                            fwrite($tp, $sql . "\r\n");

                            $this->App->query($sql);

                            $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值,会员升级返佣金', 'time' => mktime(), 'uid' => $ppuser2[user_id]));

                            //发送推荐用户通知

                            $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser2[user_id]' LIMIT 1");

                            $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => '', 'money' => $moeys, 'rank_id' => $userinfo['rank_id']), 'tjmember');

                        }

                        //上上上级会员的等级

                        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser[p3_uid]' LIMIT 1";

                        fwrite($tp, $sql . "\r\n");

                        $ppuser3 = $this->App->findrow($sql);

                        $prank3 = $ppuser3['user_rank'];

                        //如果是黄金会员则参与分成

                        if (($prank3 > $srank) && ($prank3 > $prank2)) {

                            $moeys = $payfee3[$srank];

                            $thismonth = date('Y-m-d', mktime());

                            $thism = date('Y-m', mktime());

                            $sql = "UPDATE `{$this->App->prefix() }user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$ppuser3[user_id]'";

                            fwrite($tp, $sql . "\r\n");

                            $this->App->query($sql);

                            $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $order_sn, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $moeys, 'changedesc' => '推荐用户充值升级,会员返佣金', 'time' => mktime(), 'uid' => $ppuser3[user_id]));

                            //发送推荐用户通知

                            $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$ppuser3[user_id]' LIMIT 1");

                            $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $appid, 'appsecret' => $appsecret, 'nickname' => '', 'money' => $moeys), 'tjmember');

                        }

                        //查找父级每个等级的团队记录

                        //  $sql = "SELECT *  FROM  `{$this->App->prefix()}user_level`  where lid !=1 and is_show='1'";

                        //                        fwrite($tp, $sql . "\r\n");

                        //                        //每个等级升级需要的数量，根据父级等级 推广记录判断是否升级

                        //                        $ranklevel = array(

                        //                            12 => array(12 => 10, 11 => 2, 10 => 1),

                        //                            11 => array(12 => 60, 11 => 10, 10 => 2),

                        //                        );

                        //

                        //                        $ranklist = $this->App->find($sql);

                        /*                         * 父级现有的团队记录 */

                        //   $ranklist2 = array();

                        //                        foreach ($ranklist as $_k => $_v) {

                        //                            $teamcount = 0;

                        //                            $sql = 'SELECT count(*) as num  ' . " FROM   `{$this->App->prefix()}user_team`" .

                        //                                    " WHERE user_id = $p and user_rank=$prank   and pay_type=$_v[lid]  LIMIT 1";

                        //                            $teamcount = $this->App->findvar($sql);

                        //                            fwrite($tp, $sql . "\r\n");

                        //                            $ranklist2[$_v[lid]] = $teamcount;

                        //                            fwrite($tp, json_encode($ranklist2) . "\r\n");

                        //                        }

                        //  if ($prank != 10) {

                        //                            //每个等级需要升级所需的团队记录

                        //                            $userRankLevel = $ranklevel[$prank];

                        //

                        //                            foreach ($ranklist2 as $_k => $_v) {

                        //                                fwrite($tp, $userRankLevel[$_k] . "\r\n");

                        //                                fwrite($tp, $_v . "\r\n");

                        //                                //升级每个等级需要的人数与现有团队记录是否相等

                        //                                if ($userRankLevel[$_k] == $_v) {

                        //                                    //父级升级

                        //                                    $sql = "UPDATE  `{$this->App->prefix()}user` " . " SET user_rank=user_rank-1    WHERE user_id = " . $p;

                        //                                    fwrite($tp, $sql . "\r\n");

                        //                                    $this->App->query($sql);

                        //                                    //记录父级升级记录

                        //                                    $remarklog = '团队组建 进行会员升级';

                        //                                    $sql = "insert into `{$this->App->prefix()}user_level_log` (user_id,user_rank,create_time,type,remark) values ('$p',$prank-1,UNIX_TIMESTAMP(),1,'$remarklog')";

                        //                                    fwrite($tp, $sql . "\r\n");

                        //                                    $this->App->query($sql);

                        //                                    break;

                        //                                }

                        //                            }

                        //                        }

                        

                    }

                    fclose($tp);

                }

                /* huixia 0721

                

                 * if($newrank=='1'){

                

                  $this->App->update('user',array('user_rank'=>'12'),'user_id',$uid);

                

                

                

                  $this->update_daili_tree($uid);//更新代理关系

                

                  } */

            }

        }

        exit;

    }

    function ajax_vcode($data = array()) {

        $vcode = isset($data['vifcode']) ? $data['vifcode'] : "";

        if (!empty($vcode)) {

            if (strtolower($vcode) != strtolower($this->Session->read('vifcode'))) {

                echo "fail";

            } else {

                echo "success";

            }

        }

    }

    function sj_1() {

        $this->title("商户基础信息入驻" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rz = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_shop` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $rts['mobile'] = $rz['mobile'];

        $rts['uname'] = $rz['uname'];

        $rt['NonceStr'] = $this->random_string(16);

        $signPackage = $this->getsignature(); // $rr = $this->action('common', '_get_appid_appsecret');

        //                $appid = $rr['appid'];

        //		$rt['AppId'] = $rr['appid'];

        //		$appsecret = $rr['appsecret'];

        //

        //        $this->set('rt', $rt);

        $this->set('rts', $rts);

        $this->set('signPackage', $signPackage);

        $this->set('uid', $uid);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/sj_1');

    }

    //实名认证简洁版(网页版)

    function renzheng_simple_instead() {

        $this->title("商户认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_instead_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $sql2 = "SELECT * FROM `{$this->App->prefix() }bank`";

        $bank = $this->App->find($sql2);

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        if ($rts) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_after_simple_instead');

            exit;

        }

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_simple_instead');

    }

    //实名认证简洁版

    function renzheng_simple() {

        $this->checked_login();

        $client = $_SERVER['HTTP_USER_AGENT'];

        //用php自带的函数strpos来检测是否是微信端

        // if (strpos($client, 'MicroMessenger') === false) {

        //     die("请在微信端打开");

        //     exit;

        // }

        $this->title("商户认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');
        
        //$uid = $this->checked_instead_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        //	if($rts){

        //				$sql2 = "SELECT * FROM `{$this->App->prefix()}bank` WHERE id=".$rts['bank']." LIMIT 1";

        //        $bank = $this->App->findrow($sql2);

        //

        //			}else{

        $sql2 = "SELECT * FROM `{$this->App->prefix() }bank`";

        $bank = $this->App->find($sql2);

        //	}

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        if ($rts) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_after_simple');

            exit;

            //  $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

            //        $this->template($mb . '/renzheng_after');

            //			exit;

            

        }

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_simple');

    }

    function renzheng_after_simple() {

        $this->title("商户认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $this->set('rts', $rts);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_after_simple');

    }

    function renzheng_after_simple_instead() {

        $this->title("商户认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_instead_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $this->set('rts', $rts);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_after_simple_instead');

    }

    function renzheng_info_simple() {

        $this->title("商户认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $sql2 = "SELECT * FROM `{$this->App->prefix() }bank`";

        $bank = $this->App->find($sql2);

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_simple');

    }

    function renzheng_info_simple_instead() {

        $this->title("商户认证" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->checked_instead_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        $sql2 = "SELECT * FROM `{$this->App->prefix() }bank`";

        $bank = $this->App->find($sql2);

        $this->set('rts', $rts);

        $this->set('bank', $bank);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/renzheng_simple_instead');

    }

    function assistant() {

        $uid = $this->checked_login();

        $this->title("店员管理" . ' - ' . $GLOBALS['LANG']['site_name']);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        if (empty($rts)) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple');

            exit;

        }

        $sql = "SELECT tb1.*,tb2.nickname,tb2.headimgurl FROM `{$this->App->prefix() }user_assistant` as tb1  LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.assistant_id WHERE tb1.uid=" . $uid . " and tb1.status = 1";

        $assistant_list = $this->App->find($sql);

        $this->set('rts', $rts);

        $this->set('assistant_list', $assistant_list);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/assistant_list');

    }

    function assistant_add() {

        $uid = $this->checked_login();

        $this->title("添加店员" . ' - ' . $GLOBALS['LANG']['site_name']);

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid . " LIMIT 1";

        $rts = $this->App->findrow($sql);

        if (empty($rts)) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple');

            exit;

        }

        $yuming = str_replace(array('www', '.',), '', $_SERVER["HTTP_HOST"]);

        if (!empty($yuming)) $yuming = $yuming . DS;

        $access_token = $this->action('common', '_get_access_token');

        $f = SYS_PATH . 'photos' . DS . $yuming . 'assistant' . DS . 'assistant' . $uid . '.jpg'; //原图

        if (!file_exists($f)) {

            $fop = Import::fileop();

            //生成二维码

            //$data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$quid.'}}}';

            $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "assistant_' . $uid . '"}}}';

            $rt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token, $data, 10);

            $json = json_decode($rt);

            $ticket = $json->ticket;

            $url = $json->url;

            if (!empty($ticket)) {

                $str = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $ticket;

                $img = file_get_contents($str);

                if (empty($img)) {

                    $img = Import::crawler()->curl_get_con($str);

                }

                if (!empty($img)) {

                    $fop->checkDir($f);

                    @file_put_contents($f, $img);

                }

            }

        }

        //$assistant_code	= ADMIN_URL . 'user.php?act=assistant_confirm%26pid='.$uid;

        $assistant_code = SITE_URL . 'photos/' . $yuming . 'assistant/assistant' . $uid . '.jpg';;

        $this->set('assistant_code', $assistant_code);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/assistant_add');

    }

    //生成推广二维码 2018/03/19
    function make_qrcode()
    {
        // include(SYS_PATH."wxpay/qrcode.php");

        $iuid = $this->checked_login();

        $sql  = "SELECT nickname FROM `{$this->App->prefix() }user` WHERE user_id = ".$iuid;

        $rest = $this->App->findrow($sql);

        $nickname = $rest['nickname'];

        $imgpath  = './qrcode/tui_qrcode/'.$iuid.'.png';

        if(!file_exists($imgpath)){

            $f = SYS_PATH."photos/codebg.jpg";

            $errorLevel= 'L';

            $size      =  "11";

            $iuid       =  $this->Session->read('User.iuid');

            error_reporting(E_ERROR);

            require_once SYS_PATH.'wxpay/phpqrcode/phpqrcode.php';

            $url = urldecode('http://'.$_SERVER['HTTP_HOST']."/m/user.php?act=register&uid=".$iuid);
           
            $filename = './qrcode/'.$iuid.'.png';

            QRcode::png($url, $filename, $errorLevel, $size ,2);

            $bigImgPath = "http://".$_SERVER["HTTP_HOST"].'/photos/codebg.png'; 
             
            // 图片二  
            $qCodePath = ADMIN_URL.'qrcode/'.$iuid.'.png'; 
            
            list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodePath);

            list($bigImgWidth, $bigImgHight, $bigImgType) = getimagesize($bigImgPath);

            // 创建图片对象  
            $bigImg   = imagecreatefromstring(file_get_contents($bigImgPath));

            $qCodeImg = imagecreatefromstring(file_get_contents($qCodePath));  

            // 合成图片  
            imagecopymerge($bigImg, $qCodeImg, ($bigImgWidth - $qCodeWidth)/2 + 11, ($bigImgHight - $qCodeHight)/2 + 8, 0, 0, $qCodeWidth, $qCodeHight, 100);

            header('Content-Type:image/png');

            imagepng($bigImg ,$imgpath);

            $image   = imagecreatefromstring(file_get_contents($imgpath));

            imagealphablending($image, true);
            //设置颜色，后三个数字参数是RGB
            $white = imagecolorallocate($image, 255,255, 255);
            //字体文件路径，simsun宋体
            $font = SYS_PATH.'data/cuti.ttf';
            //添加上姓名和联系方式，第二个参数设定font-size，第三个参数设定字体的阅读方向，0则为从左到右阅读，具体查一下PHP手册，第四和第五个参数则为文字水印的摆放坐标，第六是字体颜色，第七是字体样式，第八是文字内容
            imagefttext($image, 24, 0, $bigImgWidth/2, $bigImgHight/17, $white , $font, $nickname);
            // 输出合成图片  
            imagepng($image ,$imgpath);

            imagepng($image);

            imagedestroy($image);

            imagedestroy($bigImg);

            imagedestroy($qCodeImg);

            // imagedestroy($bigImg);

            // imagedestroy($qcodeImg);
        }else{
            
            echo "<img style='width:100%;height:100%;' src= ".$imgpath." />";

        }

    }
//     function scerweima($url='www.baidu.com'){  
//     require_once 'phpqrcode.php';  
      
//     $value = $url;                  //二维码内容  
      
//     $errorCorrectionLevel = 'L';    //容错级别   
//     $matrixPointSize = 5;           //生成图片大小    
      
//     //生成二维码图片  
//     $filename = 'qrcode/'.microtime().'.png';  
//     QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);    
    
//     $QR = $filename;                //已经生成的原始二维码图片文件    
  
  
//     $QR = imagecreatefromstring(file_get_contents($QR));    
    
//     //输出图片    
//     imagepng($QR, 'qrcode.png');    
//     imagedestroy($QR);  
//     return '<img src="qrcode.png" alt="使用微信扫描支付">';     
// }  

    function ajax_del_assistant($id) {

        $uid = $this->checked_login();

        //   $err = 0;

        //        $result = array('error' => $err,'message' => '', 'data' => '');

        $json = Import::json();

        if (empty($id)) {

            $result['error'] = 2;

            $result['message'] = '店员ID为空！';

            die($json->encode($result));

        }

        $sql = "UPDATE `{$this->App->prefix() }user_assistant` SET status = 0 WHERE id=" . $id;

        if ($this->App->query($sql)) {

            $sql = "SELECT tb1.*,tb2.nickname,tb2.headimgurl FROM `{$this->App->prefix() }user_assistant` as tb1  LEFT JOIN `{$this->App->prefix() }user` AS tb2 ON tb2.user_id = tb1.assistant_id WHERE tb1.uid=" . $uid . " and tb1.status = 1";

            $assistant_list = $this->App->find($sql);

            $result['error'] = 0;

            $result['data'] = $assistant_list;

            die($json->encode($result));

        } else {

            $result['error'] = 2;

            $result['message'] = '店员删除失败！';

            die($json->encode($result));

        }

        // if (empty($id)) {

        //           echo "fail";

        //        }

        //        $sql = "UPDATE `{$this->App->prefix()}user_assistant` SET status = 0 WHERE id=".$id;

        //        if ($this->App->query($sql)) {

        //           echo "success";

        //        } else {

        //             echo "fail";

        //        }

        

    }

    function assistant_confirm($pid = "") {

        $pid = isset($_GET['pid']) ? $_GET['pid'] : 0;

        if (!($pid > 0)) {

            $this->jump('user.php?act=renzheng_simple');

            exit;

        }

        $wecha_id = $this->get_user_wecha_id_new();

        $sql = "SELECT user_id,user_rank,is_subscribe FROM `{$this->App->prefix() }user` WHERE wecha_id='$wecha_id' LIMIT 1";

        $thisRT = $this->App->findrow($sql);

        $thisuid = $thisRT['user_id'];

        $user_rank = $thisRT['user_rank'];

        $is_subscribe = $thisRT['is_subscribe'];

        if ($is_subscribe == '0') {

            //return array('您已经关注！', 'text');

            //					exit;

            $data =

            //1、更改关注标识 表user_tuijian，user

            //2、更改用户资料

            //3、关注时间、关注排名等

            $rr = $this->action('common', '_get_appid_appsecret');

            $appid = $rr['appid'];

            $appsecret = $rr['appsecret'];

            $access_token = $this->action('common', '_get_access_token');

            if (!empty($access_token)) {

                //获取用户信息

                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $wecha_id;

                $con = $this->curlGet($url);

                print_r($con);

                $nickname = '';

                $sex = '';

                $city = '';

                $province = '';

                $headimgurl = '';

                $subscribe_time = '';

                if (!empty($con)) {

                    $json = json_decode($con);

                    $subscribe = $json->subscribe;

                    $nickname = isset($json->nickname) ? $json->nickname : '';

                    echo $nickname;

                    $sex = isset($json->sex) ? $json->sex : '';

                    $city = isset($json->city) ? $json->city : '';

                    $province = isset($json->province) ? $json->province : '';

                    $headimgurl = isset($json->headimgurl) ? $json->headimgurl : '';

                    $subscribe_time = isset($json->subscribe_time) ? $json->subscribe_time : '';

                    $this->Session->write('User.subscribe', '1');

                    setcookie(CFGH . 'USER[SUBSCRIBE]', '1', mktime() + 2592000);

                    $dd = array();

                    $dd['is_subscribe'] = '1';

                    $dd['subscribe_time'] = mktime();

                    if (!empty($nickname)) $dd['nickname'] = $nickname;

                    if (!empty($sex)) $dd['sex'] = $sex;

                    if (!empty($city)) $dd['cityname'] = $city;

                    if (!empty($province)) $dd['provincename'] = $province;

                    if (!empty($headimgurl)) $dd['headimgurl'] = $headimgurl;

                    if (!empty($subscribe_time)) $dd['subscribe_time'] = $subscribe_time;

                    //检查是否存在该用户

                    $ukey = $this->Session->read('User.ukey');

                    if (empty($ukey)) $ukey = isset($_COOKIE[CFGH . 'USER']['UKEY']) ? $_COOKIE[CFGH . 'USER']['UKEY'] : '';

                    if (!empty($ukey) && $ukey != $wecha_id) { //不是当前用户

                        $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE wecha_id='$wecha_id' LIMIT 1";

                        $uid = $this->App->findvar($sql);

                    } else {

                        $uid = $this->Session->read('User.uid');

                        if (!($uid > 0)) {

                            $uid = isset($_COOKIE[CFGH . 'USER']['UID']) ? $_COOKIE[CFGH . 'USER']['UID'] : '0';

                            if (!($uid > 0)) {

                                $sql = "SELECT user_id FROM `{$this->App->prefix() }user` WHERE wecha_id='$wecha_id' LIMIT 1";

                                $uid = $this->App->findvar($sql);

                                $this->Session->write('User.uid', $uid);

                                setcookie(CFGH . 'USER[UID]', $uid, mktime() + 2592000);

                            }

                        }

                    }

                }

            }

            if ($uid > 0) {

                $this->App->update('user', $dd, 'user_id', $uid);

                $counts = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix() }user` WHERE is_subscribe='1'");

                $this->App->update('user', array('subscribe_rank' => $counts), 'user_id', $uid); //更改排名

                

            } else {

                //添加用户

                $dd['user_name'] = $wecha_id;

                $dd['wecha_id'] = $wecha_id;

                $t = mktime();

                $dd['password'] = md5('ABCDE');

                //自动开通代理

                $dd['user_rank'] = 9;

                $ip = Import::basic()->getip();

                $dd['reg_ip'] = $ip ? $ip : '0.0.0.0';

                $dd['reg_time'] = $t;

                $dd['reg_from'] = Import::ip()->ipCity($ip);

                $dd['last_login'] = mktime();

                $dd['last_ip'] = $dd['reg_ip'];

                $dd['active'] = 1;

                if ($this->App->insert('user', $dd)) {

                    echo "成功";

                }

            }

        }

    }

    function curlGet($url) {

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

        if (empty($temp)) $temp = Import::crawler()->curl_get_con($url);

        return $temp;

    }

    function curlPost($url, $data, $showError = 1) {

        $ch = curl_init();

        $header = "Accept-Charset: utf-8";

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $tmpInfo = curl_exec($ch);

        if ($showError == '10') {

            return $tmpInfo;

            exit;

        }

        $errorno = curl_errno($ch);

        if ($errorno) {

            return array('rt' => false, 'errorno' => $errorno);

        } else {

            $js = json_decode($tmpInfo, 1);

            if (intval($js['errcode'] == 0)) {

                return array('rt' => true, 'errorno' => 0, 'media_id' => $js['media_id'], 'msg_id' => $js['msg_id']);

            } else {

                if ($showError) {

                    return array('rt' => true, 'errorno' => 10, 'msg' => '发生了Post错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);

                }

            }

        }

    }

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

    function paid_detail() {

        $uid = $this->checked_login();

        $renzheng = $this->App->findvar("SELECT status FROM `{$this->App->prefix() }user_bank` WHERE uid ='{$uid}' LIMIT 1");

        if (!isset($renzheng) || $renzheng != 1) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng');

        }

        $sql = "SELECT * FROM `{$this->App->prefix() }user` WHERE user_id ='{$uid}'  AND active='1'  LIMIT 1";

        $rt['userinfo'] = $this->App->findrow($sql);

        $this->title("自动结算明细");

        //条件

        $date_start = date('Y-m-d', (time() - 172800));

        $date_end = date('Y-m-d', time());

        $mobile = '18553574543';

        if (isset($_GET['add_time1']) && !empty($_GET['add_time1'])) {

            $date_start = $_GET['add_time1'];

        }

        if (isset($_GET['add_time2']) && !empty($_GET['add_time2'])) {

            $date_end = $_GET['add_time2'];

        }

        if (isset($_GET['mobile']) && !empty($_GET['mobile'])) {

            $mobile = $_GET['mobile'];

        }

        $key = $this->random_string(16, $max = FALSE);

        $xml = '



		<merchant>



			<head>



			<version>1.0.0</version>



			<agencyId>549440153997077</agencyId>



			<msgType>01</msgType>



			<tranCode>100009</tranCode>



			<reqMsgId>wx' . date('Ymdhis', time()) . $uid . '</reqMsgId>



			<reqDate>' . date('Ymdhis', time()) . '</reqDate>		  



			</head>



			<body>



			<merchantId>' . $mobile . '</merchantId>



			<beginCreateDate>' . $date_start . '</beginCreateDate>



			<endCreateDate>' . $date_end . '</endCreateDate>



			<page>0</page>



			<size>30</size>



			</body>



		</merchant>';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;

        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/user/api/daifu' . date('Y-m-d') . '.log');

        $encryptData = $this->encrypt($xml, $key);

        $signData = $this->rsaSign($xml, './app/user/549440153997077.pem');

        $encyrptKey = $this->rsasign_public($key, './app/user/549440153997077_pub.pem');

        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => '549440153997077', 'signData' => $signData, 'tranCode' => '100009');

        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/qryDsfOrder";

        if (is_array($postdata)) {

            ksort($postdata);

            $content = http_build_query($postdata);

            $content_length = strlen($content);

            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));

            $response = file_get_contents($url, false, stream_context_create($options));

        }

        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/user/api/daifu' . date('Y-m-d') . '.log');

        $resp = explode('&', $response);

        $first = strpos($resp[0], "="); //字符第一次出现的位置

        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);

        $first = strpos($resp[1], "="); //字符第一次出现的位置

        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);

        $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440153997077.pem');

        $xmlData = $this->decode($encryptData_host, $merchantAESKey);

        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/user/api/daifu' . date('Y-m-d') . '.log');

        $xml_obj = simplexml_load_string($xmlData);

        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;

        $rt['respCode'] = $xml_obj->head->respCode;

        $rt['respMsg'] = $xml_obj->head->respMsg;

        $totalElements = $xml_obj->body->totalElements;

        $list = json_decode(json_encode($xml_obj->body), true);

        if ($rt['respCode'] == "000000") {

            $this->set('totalElements', $totalElements);

            $this->set('list', $list['list']);

        } else {

            echo $rt['respMsg'];

        }

        if (!defined(NAVNAME)) define('NAVNAME', "自动结算明细");

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->template($mb . '/daifu_detail');

    }

   

    function Instead_bangka() {

        $this->layout('Instead_h');

        $uid = $this->checked_instead_login();

        $sql = "SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1";

        $rt = $this->App->findrow($sql);

        if (!$rt['status']) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

            exit;

        }

        // $ruzhu_result = $this->ruzhu_query($uid);

        // if($ruzhu_result != 1){

        //			   $this->jump(ADMIN_URL . 'user.php?act=Instead',0,'子商户入驻失败，稍后再试');

        //            exit;

        //			 }

        $bank = $this->App->find("SELECT * FROM `{$this->App->prefix() }bank` where id != 5 and id != 20 and id != 21 and id != 22");



        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('user', $rt);

        $this->set('bank', $bank);

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->template($mb . '/Instead_bangka');

    }

    /*function ruzhu_query($uid, $card) {

        $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_sj1_instead` WHERE uid=" . $uid . " limit 1");

        $rts = $this->_get_payinfo(20);

        $pay = unserialize($rts['pay_config']);

        $key = $this->random_string(16, $max = FALSE);

        $xml = '

    

    <merchant>

    

    <head>

    

    <version>1.0.0</version>

    

    <agencyId>' . $pay['pay_no'] . '</agencyId>

    

    <msgType>01</msgType>

    

    <tranCode>100006</tranCode>

    

    <reqMsgId>DH' . date('Ymdhis', time()) . $uid . '</reqMsgId>

    

    <reqDate>' . date('Ymdhis', time()) . '</reqDate>		  

    

    </head>

    

    <body>

    

     <merchantId>' . $sj1['servicePhone'] . '</merchantId>

    

     <bankaccountNo>' . $card['bank_no'] . '</bankaccountNo>

    

    </body>

    

    </merchant>';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . $xml;

        error_log('[' . date('Y-m-d H:i:s') . ']官方返回1:' . "\n" . iconv('UTF-8', 'GBK', $xml) . "\n\n", 3, './app/user/instead/ruzhu_query_' . date('Y-m-d') . '.log');

        $encryptData = $this->encrypt($xml, $key);

        $signData = $this->rsaSign($xml, './app/user/549440148160026.pem');

        $encyrptKey = $this->rsasign_public($key, './app/user/549440148160026_pub.pem');

        $postdata = array('encryptData' => $encryptData, 'encryptKey' => $encyrptKey, 'agencyId' => $pay['pay_no'], 'signData' => $signData, 'tranCode' => '100006');

        $url = "http://epay.gaohuitong.com:8083/interfaceWeb/qryCardInfo";

        if (is_array($postdata)) {

            ksort($postdata);

            $content = http_build_query($postdata);

            $content_length = strlen($content);

            $options = array('http' => array('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-length: $content_length\r\n", 'content' => $content));

            $response = file_get_contents($url, false, stream_context_create($options));

        }

        error_log('[' . date('Y-m-d H:i:s') . ']官方返回2:' . "\n" . iconv('UTF-8', 'GBK', $response) . "\n\n", 3, './app/user/instead/ruzhu_query_' . date('Y-m-d') . '.log');

        $resp = explode('&', $response);

        $first = strpos($resp[0], "="); //字符第一次出现的位置

        $encryptData_host = substr($resp[0], $first + 1, strlen($resp[0]) + 1); //截取字符串，形式如：substr($string,0,-3);

        $first = strpos($resp[1], "="); //字符第一次出现的位置

        $encryptKey_host = substr($resp[1], $first + 1, strlen($resp[1]) + 1); //截取字符串，形式如：substr($string,0,-3);

        $merchantAESKey = $this->jiemi($encryptKey_host, './app/user/549440148160026.pem');

        $xmlData = $this->decode($encryptData_host, $merchantAESKey);

        error_log('[' . date('Y-m-d H:i:s') . ']官方返回3:' . "\n" . iconv('UTF-8', 'GBK', $xmlData) . "\n\n", 3, './app/user/instead/ruzhu_query_' . date('Y-m-d') . '.log');

        $xml_obj = simplexml_load_string($xmlData);

        $rt['reqMsgId'] = $xml_obj->head->reqMsgId;

        $rt['respCode'] = $xml_obj->head->respCode;

        $rt['respMsg'] = $xml_obj->head->respMsg;

        $rt['authResult'] = $xml_obj->body->bankaccounList->authResult;

        return $rt['authResult'];

    }*/

    //2018/03/27
    function Instead_setting() {

        $this->layout('Instead_h');

        $uid = $this->checked_instead_login();

        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1");

        if (!$rt['status']) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

            exit;

        }
        //快付通公共参数 $bs_params
        $rts = $this->_get_payinfo(3);

        $pay = unserialize($rts['pay_config']);

        $merchantId = $pay['pay_no'];

        $bs_params = array(
            //产品编号
            'service' => '',
            //请求编号
            'reqNo' => 'KFT0987654321',
            //接口版本号
            'version' => '1.0.0-IEST',
            //参数字符集
            'charset' => 'utf-8',
            //语言
            'language' => 'zh_CN',

            'callerIp' => '127.0.0.1',

            'merchantId' => $merchantId
        );

        $this->Session->write("bs_params" , $bs_params);

        // $this->sj1_instead($uid);

        $card_id = $_GET['id'];

        if (!isset($card_id) || empty($card_id)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $cardinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE  uid=" . $uid . " and id = " . $card_id . " LIMIT 1");

        if (empty($cardinfo)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        //if ($uid == 1) {

        $card['uid'] = $cardinfo['uid'];

        $card['name'] = $cardinfo['name'];

        $card['idcard'] = $cardinfo['idcard'];

        $card['bank_no'] = $cardinfo['bank_no'];

        $card['mobile'] = $cardinfo['mobile'];

        $card['bank'] = $cardinfo['bank'];

        $card['valid'] = $cardinfo['valid'];

        $card['cvn2'] = $cardinfo['cvn2'];

        $this->hljc_merchant($card); //设置计划时进件(汇联金创)

        // }

        $user_card_instead_plans = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $card_id . " and user_id =" . $uid . " and  stop=0  and  status!=3");

        if ($user_card_instead_plans) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead_splans&card_id=' . $card_id);

            exit();

        }

        $cardsetting = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead_setting` WHERE user_id = " . $uid . " and card_id =" . $card_id . " LIMIT 1");

        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` where id=" . $cardinfo['bank']);

        $cardinfo['bank_name'] = $bank['name'];

        $cardinfo['bank_pic'] = $bank['pic'];

        $cardinfo['bank_no_sort'] = substr($cardinfo['bank_no'], -4);

        $days = date("t"); //当月天数

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('user', $rt);

        $this->set('cardinfo', $cardinfo);

        $this->set('cardsetting', $cardsetting);

        $this->set('days', $days);

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->template($mb . '/Instead_setting');

    }

    function diffBetweenTwoDays($day1, $day2) //日期天数差

    {

        $second1 = strtotime($day1);

        $second2 = strtotime($day2);

        if ($second1 < $second2) {

            $tmp = $second2;

            $second2 = $second1;

            $second1 = $tmp;

            return ($second1 - $second2) / 86400;

        }

        if ($second1 == $second2) {

            return 1;

        }

        if ($second1 > $second2) {

            return 0;

        }

    }

    function date_arr($s, $e) {

        $start = new DateTime($s);

        $end = new DateTime($e);

        $end = $end->modify('+1 day'); // 不包含结束日期当天，需要人为的加一天

        foreach (new DatePeriod($start, new DateInterval('P1D'), $end) as $d) {

            /** 

             * @var $d DateTime

             */

            $date_arr[] = $d->format('Y-m-d');

        }

        return $date_arr;

    }

    function ajax_add_instead_setting($data = array()) {

        $uid = $this->checked_instead_login();

        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1");

        if (!$rt['status']) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

            exit;

        }

        ini_set('date.timezone', 'Asia/Shanghai');

        $card_id = $data['card_id'];

        $user_id = $uid;

        $Instead_money = $data['Instead_money'];

        $Over_money = $data['Over_money'];

        $Bill_day = $data['Bill_day'];

        $Instead_day = $data['Instead_day'];

        $this->Session->write("plan_list_" . $card_id, null);

        $this->Session->write("date_arr_" . $card_id, null);

        $y = date('Y', time());

        $m = date('m', time());

        $d = date('d', time());

        $days = date('t'); //date('t');//当月天数

        //$gudingtime = strtotime(date('Y-m-d 18:00:00')); //当前日期18：00时间戳

		

		 $gudingtime = strtotime(date('Y-m-d 12:00:00')); //当前日期12：00时间戳

		

        if ($Instead_day >= $d) {

            if (time() < $gudingtime) {

                $huan_start_date = $y . "-" . $m . "-" . $d;

                $huan_end_date = $y . "-" . $m . "-" . ($Instead_day - 1);

            } else {

                if ($d + 1 > $days) {

                    if ($m == 12) {

                        $huan_start_date = ($y + 1) . "-" . ($m - 1) . "-01";

                        $huan_end_date = ($y + 1) . "-" . ($m - 1) . "-" . ($Instead_day - 1);

                    } else {

                        $huan_start_date = $y . "-" . ($m + 1) . "-01";

                        $huan_end_date = $y . "-" . ($m + 1) . "-" . ($Instead_day - 1);

                    }

                } else {

                    $huan_start_date = $y . "-" . $m . "-" . ($d + 1);

                    $huan_end_date = $y . "-" . $m . "-" . ($Instead_day - 1);

                }

            }

        } else {

            if (time() < $gudingtime) {

                if ($m == 12) {

                    $huan_start_date = $y . "-" . $m . "-" . $d;

                    $huan_end_date = ($y + 1) . "-" . ($m - 11) . "-" . ($Instead_day - 1);

                } else {

                    $huan_start_date = $y . "-" . $m . "-" . $d;

                    $huan_end_date = $y . "-" . ($m + 1) . "-" . ($Instead_day - 1);

                }

            } else {

                if ($d + 1 > $days) {

                    if ($m == 12) {

                        $huan_start_date = ($y + 1) . "-" . ($m - 1) . "-01";

                        $huan_end_date = ($y + 1) . "-" . ($m - 1) . "-" . ($Instead_day - 1);

                    } else {

                        $huan_start_date = $y . "-" . ($m + 1) . "-01";

                        $huan_end_date = $y . "-" . ($m + 1) . "-" . ($Instead_day - 1);

                    }

                } else {

                    $huan_start_date = $y . "-" . $m . "-" . ($d + 1);

                    $huan_end_date = $y . "-" . ($m+1) . "-" . ($Instead_day - 1);

                }

            }

        }

        //		  if($Bill_day>=$Instead_day){

        //			if($Bill_day+1 > $days){

        //				if($m == 12){

        //					$huan_start_date = ($y+1)."-".($m-11)."-01";

        //				$huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //					}else{

        //						$huan_start_date = $y."-".($m+1)."-01";

        //				$huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //						}

        //

        //			}else{

        //				    if($Bill_day+1 <= $d){

        //						    if(time() < $gudingtime){

        //								if($m == 12){

        //									 $huan_start_date = $y."-".$m."-".$d;

        //								 $huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //								}else{

        //									 $huan_start_date = $y."-".$m."-".$d;

        //								 $huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //									}

        //

        //							}else{

        //								if($m == 12){

        //									 $huan_start_date = $y."-".$m."-".($d+1);

        //								 $huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //								}else{

        //									 $huan_start_date = $y."-".$m."-".($d+1);

        //								 $huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //									}

        //

        //								}

        //					 }else{

        //						     if($Instead_day-1 > $d){

        //

        //

        //								 if($m+1 > 12){

        //								 $huan_start_date = $y."-".$m."-".$d;

        //								 $huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //									 }else{

        //								 $huan_start_date = $y."-".$m."-".$d;

        //								 $huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //										 }

        //

        //							 }else{

        //								 if($m == 12){

        //									 	 $huan_start_date = ($y+1)."-".($m-11)."-01";

        //								 $huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //									 }else{

        //											 $huan_start_date = $y."-".($m+1)."-01";

        //								 $huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //										 }

        //

        //								 }

        //

        //						}

        //

        //			}

        //	     }else{

        //			     if(($d >= $Bill_day+1) && ($d < $Instead_day-1) && ($Instead_day-1 <= $days)){

        //					 if(time() < $gudingtime){

        //					   $huan_start_date = $y."-".$m."-".$d;

        //					   $huan_end_date = $y."-".$m."-".($Instead_day-1);

        //						 }else{

        //					   $huan_start_date = $y."-".$m."-".($d+1);

        //					   $huan_end_date = $y."-".$m."-".($Instead_day-1);

        //						 }

        //				 }else if($d == $Instead_day-1){

        //					  if(time() < $gudingtime){

        //					   $huan_start_date = $y."-".$m."-".$d;

        //					   $huan_end_date = $y."-".$m."-".($Instead_day-1);

        //						 }else{

        //							 if($m == 12){

        //								  $huan_start_date = ($y+1)."-".($m-11)."-".($Bill_day+1);

        //					   $huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //								 }else{

        //									  $huan_start_date = $y."-".($m+1)."-".($Bill_day+1);

        //					   $huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //									 }

        //

        //						 }

        //

        //		         }else{

        //					 if($d < $Bill_day+1){

        //					   $huan_start_date = $y."-".($m)."-".($Bill_day+1);

        //					   $huan_end_date = $y."-".($m)."-".($Instead_day-1);

        //					 }else if($d > $Instead_day-1){

        //						 					  if($m == 12){

        //								  $huan_start_date = ($y+1)."-".($m-11)."-".($Bill_day+1);

        //					   $huan_end_date = ($y+1)."-".($m-11)."-".($Instead_day-1);

        //								 }else{

        //									  $huan_start_date = $y."-".($m+1)."-".($Bill_day+1);

        //					   $huan_end_date = $y."-".($m+1)."-".($Instead_day-1);

        //									 }

        //						 }

        //

        //					 }

        //

        //		}

        $hk_days = $this->diffBetweenTwoDays($huan_start_date, $huan_end_date); //还款区间天数

        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $uid . " LIMIT 1");
        //2018/04/02
        $level = $this->App->findrow("SELECT feilv,sxf_instead FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");

        $feilvs = unserialize($level['feilv']);

        $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=3 LIMIT 1");

        //计算手续费

        $koulv = $feilvs[$pay_fangshi];

        $feilv = $koulv / 10000;

        $sxf   = $level['sxf_instead'];

        $cishu = 1;

        if ($Over_money > $Instead_money * 1.03) {

            $huankuan = $Instead_money;

            //$shouxufei = $Instead_money*$feilv+$sxf;

            $jiaoyi = round((($huankuan + $sxf) / (1 - $feilv)), 2);

            $shouxufei = round(($jiaoyi * $feilv + $sxf), 2);

            $plan_list[] = array('suiji' => 0, 'jiaoyi' => $jiaoyi, 'shouxufei' => $shouxufei, 'huankuan' => $huankuan);

        } else {

            $x = $Over_money * 0.75;

            while ($x <= $Instead_money) {

                if ($cishu == 1) {

                    $suiji = 75 / 100;

                } else {

                    $suiji = rand(60, 75) / 100;

                }

                $huankuan = $Over_money * $suiji;

                $gs_huankuan = $huankuan;

                $shouxufei = $gs_huankuan * $feilv + $sxf;

                // $gs_shouxufei = round($shouxufei,2);

                $jiaoyi = round((($huankuan + $sxf) / (1 - $feilv)), 2);

                $gs_shouxufei = round(($jiaoyi * $feilv + $sxf), 2);

                $plan_list[] = array('suiji' => $suiji, 'jiaoyi' => $jiaoyi, 'shouxufei' => $gs_shouxufei, 'huankuan' => $gs_huankuan);

                $Instead_money = $Instead_money - $huankuan;

                $x = $Over_money * 0.75;

                if ($x > $Instead_money) {

                    $e_huankuan = $Instead_money;

                    // $e_shouxufei = $e_huankuan*$feilv+$sxf;

                    $e_jiaoyi = round((($e_huankuan + $sxf) / (1 - $feilv)), 2);

                    $gs_e_shouxufei = round(($e_jiaoyi * $feilv + $sxf), 2);

                    // $gs_e_shouxufei = round($e_shouxufei,2);

                    //								 $e_jiaoyi = round(($e_huankuan+$e_shouxufei),2);

                    $plan_list[] = array('suiji' => 0, 'jiaoyi' => $e_jiaoyi, 'shouxufei' => $gs_e_shouxufei, 'huankuan' => $e_huankuan);

                }

                $cishu++;

            }

        }

        if ($cishu % 3 > 0) {

            $mhk_days = ($cishu + (3 - $cishu % 3)) / 3;

        } else {

            $mhk_days = $cishu / 3;

        }

        if ($mhk_days > $hk_days) {

            echo "距离还款日过近，请提高卡余额!";

        } else {

            $card_id = $data['card_id'];

            $user_id = $uid;

            $Instead_money = $data['Instead_money'];

            $Over_money = $data['Over_money'];

            $Bill_day = $data['Bill_day'];

            $Instead_day = $data['Instead_day'];

            $Instead_info = array('Instead_money' => $Instead_money, 'Over_money' => $Over_money, 'Bill_day' => $Bill_day, 'Instead_day' => $Instead_day, 'cishu' => $cishu,);

            $this->Session->write("Instead_info_" . $card_id, $Instead_info);

            $date_arr = $this->date_arr($huan_start_date, $huan_end_date);

            //判断当前时间是否在（09:00-20:25之间）

            $start_timestamp = strtotime(date('Y-m-d 09:00:00')); //当前日期09：00时间戳

            $end_timestamp = strtotime(date('Y-m-d 11:00:00')); //当前日期16：00时间戳

            if ($Over_money > $Instead_money * 1.03) {

                //$huan_start_date,$huan_end_date

                if (strtotime($huan_start_date) > strtotime(date('Y-m-d'))) {

                    $kou_time = strtotime($huan_start_date . " 11:" . rand(1, 60) . ":" . rand(1, 60));

                    $huan_time = $kou_time + rand(60, 80) * 60;

                    $plan_list_new[] = array('suiji' => $plan_list[0]['suiji'], 'jiaoyi' => $plan_list[0]['jiaoyi'], 'shouxufei' => $plan_list[0]['shouxufei'], 'huankuan' => $plan_list[0]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                } else {

                    if ($start_timestamp > time()) {

                        $kou_time = strtotime($huan_start_date . " 09:" . rand(1, 60) . ":" . rand(1, 60));

                        $huan_time = $kou_time + 43 * 60;

                    } else {

                        $kou_time = time() + 10 * 60;

                        $huan_time = $kou_time + 43 * 60;

                    }

                    $plan_list_new[] = array('suiji' => $plan_list[0]['suiji'], 'jiaoyi' => $plan_list[0]['jiaoyi'], 'shouxufei' => $plan_list[0]['shouxufei'], 'huankuan' => $plan_list[0]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                }

            } else {

                for ($i = 1;$i <= $mhk_days;$i++) {

                    $y = ($i - 1) * 3;

                    if ($start_timestamp > time()) {

                        $kou_time = strtotime($huan_start_date . " 09:" . rand(1, 30) . ":" . rand(1, 60));

                    } else {

                        $kou_time = time();

                    }

                    $kou_time1 = strtotime($date_arr[$i - 1] . " 09:" . rand(1, 30) . ":" . rand(1, 59));

                    if ($i * 3 >= $cishu) {

                        $yy = $cishu;

                    } else {

                        $yy = $i * 3;

                    }

                    for ($y;$y < $yy;$y++) {

                        if ($i - 1 == 0) {

                            if (strtotime($date_arr[$i - 1]) > strtotime(date('Y-m-d'))) {

                               // if ($y % 3 == 0) {

//                                    $kou_time = $kou_time1;

//                                    $huan_time = $kou_time1 + rand(61, 79) * 60;

//                                    $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

//                                }

//                                if ($y % 3 == 1) {

//                                    $kou_time = $kou_time1 + rand(121, 139) * 60;

//                                    $huan_time = $kou_time1 + rand(221, 239) * 60;

//                                    $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

//                                }

//                                if ($y % 3 == 2) {

//                                    $kou_time = $kou_time1 + rand(301, 319) * 60;

//                                    $huan_time = $kou_time1 + rand(381, 419) * 60;

//                                    $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

//                                }



 if ($y % 3 == 0) {

                                    $kou_time = $kou_time1;

                                    $huan_time = $kou_time1 + 27 * 60;

                                    $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                                }

                                if ($y % 3 == 1) {

                                    $kou_time = $kou_time1 + 57 * 60;

                                    $huan_time = $kou_time1 + 79 * 60;

                                    $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                                }

                                if ($y % 3 == 2) {

                                    $kou_time = $kou_time1 + 109 * 60;

                                    $huan_time = $kou_time1 + 132 * 60;

                                    $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                                }

								

                                //$huan_time = $kou_time1+$y*rand(61,79);

                                //

                                //									 $plan_list_new[] = array(

                                //									'suiji' => $plan_list[$y]['suiji'],

                                //									'jiaoyi' => $plan_list[$y]['jiaoyi'],

                                //									'shouxufei' => $plan_list[$y]['shouxufei'],

                                //									'huankuan' => $plan_list[$y]['huankuan'],

                                //									'kou_time' => $kou_time1,

                                //									'huan_time' => $huan_time,

                                //							                                );

                                //

                                //									 $kou_time1 = $huan_time + $y*rand(61,79);

                                

                            } else {

                                if ($end_timestamp < time()) {

                                    if ($y % 3 == 0) {

                                        $kou_time1 = $kou_time + 6 * 60;

                                        $huan_time = $kou_time + 27 * 60;

                                        $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time1, 'huan_time' => $huan_time,);

                                    }

                                    if ($y % 3 == 1) {

                                        $kou_time1 = $kou_time + 57 * 60;

                                        $huan_time = $kou_time + 79 * 60;

                                        $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time1, 'huan_time' => $huan_time,);

                                    }

                                    if ($y % 3 == 2) {

                                        $kou_time1 = $kou_time + 109 * 60;

                                        $huan_time = $kou_time + 132 * 60;

                                        $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time1, 'huan_time' => $huan_time,);

                                    }

                                } else {

                                    if ($y % 3 == 0) {

                                        $kou_time1 = $kou_time + 10 * 60;

                                        $huan_time = $kou_time + 43 * 60;

                                        $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time1, 'huan_time' => $huan_time,);

                                    }

                                    if ($y % 3 == 1) {

                                        $kou_time1 = $kou_time + 102 * 60;

                                        $huan_time = $kou_time + 138 * 60;

                                        $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time1, 'huan_time' => $huan_time,);

                                    }

                                    if ($y % 3 == 2) {

                                        $kou_time1 = $kou_time + 183 * 60;

                                        $huan_time = $kou_time + 225 * 60;

                                        $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time1, 'huan_time' => $huan_time,);

                                    }

                                 

                                }

                                // $kou_time += 10*60;

                                //									  $huan_time = $kou_time+43*60;

                                //									  $plan_list_new[] = array(

                                //									  'suiji' => $plan_list[$y]['suiji'],

                                //									  'jiaoyi' => $plan_list[$y]['jiaoyi'],

                                //									  'shouxufei' => $plan_list[$y]['shouxufei'],

                                //									  'huankuan' => $plan_list[$y]['huankuan'],

                                //									  'kou_time' => $kou_time,

                                //									  'huan_time' => $huan_time,

                                //															  );

                                

                            }

                        } else {

                            if ($y % 3 == 0) {

                                $kou_time = $kou_time1;

                                $huan_time = $kou_time1 + 27 * 60;

                                $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                            }

                            if ($y % 3 == 1) {

                                $kou_time = $kou_time1 + 57 * 60;

                                $huan_time = $kou_time1 + 79 * 60;

                                $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                            }

                            if ($y % 3 == 2) {

                                $kou_time = $kou_time1 + 109 * 60;

                                $huan_time = $kou_time1 + 132 * 60;

                                $plan_list_new[] = array('suiji' => $plan_list[$y]['suiji'], 'jiaoyi' => $plan_list[$y]['jiaoyi'], 'shouxufei' => $plan_list[$y]['shouxufei'], 'huankuan' => $plan_list[$y]['huankuan'], 'kou_time' => $kou_time, 'huan_time' => $huan_time,);

                            }

                            // $kou_time1 += rand(60,80)*rand(50,60);

                            //									  $huan_time = $kou_time1+rand(60,80)*rand(50,60);

                            //									  $plan_list_new[] = array(

                            //									  'suiji' => $plan_list[$y]['suiji'],

                            //									  'jiaoyi' => $plan_list[$y]['jiaoyi'],

                            //									  'shouxufei' => $plan_list[$y]['shouxufei'],

                            //									  'huankuan' => $plan_list[$y]['huankuan'],

                            //									  'kou_time' => $kou_time1,

                            //									  'huan_time' => $huan_time,

                            //															  );

                            

                        }

                    }

                }

            }

            $end_cishu = $cishu - 1;

            if ($plan_list_new[$end_cishu]['huankuan'] < 100) {

                $huankuan_1 = $plan_list_new[0]['huankuan'] - 100;

                //$shouxufei_1 = round(($huankuan_1*$feilv+$sxf),2);

                $jiaoyi_1 = round((($huankuan_1 + $sxf) / (1 - $feilv)), 2);

                $shouxufei_1 = round(($jiaoyi_1 * $feilv + $sxf), 2);

                $plan_list_new[0] = array('suiji' => 0, 'jiaoyi' => $jiaoyi_1, 'shouxufei' => $shouxufei_1, 'huankuan' => $huankuan_1, 'kou_time' => $plan_list_new[0]['kou_time'], 'huan_time' => $plan_list_new[0]['huan_time'],);

                $huankuan_end = $plan_list_new[$end_cishu]['huankuan'] + 100;

                //$shouxufei_end = round(($huankuan_end*$feilv+$sxf),2);

                $jiaoyi_end = round((($huankuan_end + $sxf) / (1 - $feilv)), 2);

                $shouxufei_end = round(($jiaoyi_end * $feilv + $sxf), 2);

                $plan_list_new[$end_cishu] = array('suiji' => 0, 'jiaoyi' => $jiaoyi_end, 'shouxufei' => $shouxufei_end, 'huankuan' => $huankuan_end, 'kou_time' => $plan_list_new[$end_cishu]['kou_time'], 'huan_time' => $plan_list_new[$end_cishu]['huan_time'],);

            }

            $this->Session->write("plan_list_" . $card_id, $plan_list_new);

            //$this->Session->write("date_arr_".$card_id,  $date_arr);

            //$thisplan_list = $this->Session->read('plan_list.{$card_id}');

            //							if(isset($thisplan_list)&&!empty($thisplan_list)){

            //								//$this->Session->write("plan_list.{$card_id}",null);

            //								 $this->Session->write("plan_list.{$card_id}",  $plan_list);

            //								}else{

            //									 $this->Session->write("plan_list.{$card_id}",  $plan_list);

            //									}

            echo "success";

        }

        // $this->Session->write("plan_list.{$card_id}",  $plan_list);

        //

        //				$thiscart = $this->Session->read('plan_list.{$card_id}');

        

    }

    function Instead_plan() {

        $this->layout('Instead_h');

        $uid = $this->checked_instead_login();

        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1");

        if (!$rt['status']) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

            exit;

        }

        $card_id = $_GET['card_id'];

        if (!isset($card_id) || empty($card_id)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $cardinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE  uid=" . $uid . " and id = " . $card_id . " LIMIT 1");

        if (empty($cardinfo)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $user_card_instead_plans = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $card_id . " and user_id =" . $uid . " and  stop = 0 and is_perform_auto!=2");

        if ($user_card_instead_plans) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead_splans&card_id=' . $card_id);

            exit();

        }

        $thisplan_list = $this->Session->read('plan_list_' . $card_id);

        //$date_arr = $this->Session->read('date_arr_'.$card_id);

        $Instead_info = $this->Session->read('Instead_info_' . $card_id);

        // var_dump($thisplan_list);

        // var_dump($date_arr);

        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` where id=" . $cardinfo['bank']);

        $cardinfo['bank_name'] = $bank['name'];

        $cardinfo['bank_pic'] = $bank['pic'];

        $cardinfo['bank_no_sort'] = substr($cardinfo['bank_no'], -4);

        $cardinfo['mobile_sort'] = substr_replace($cardinfo['mobile'], '****', 3, 4);

        $this->set('thisplan_list', $thisplan_list);

        $this->set('cardinfo', $cardinfo);

        $this->set('Instead_info', $Instead_info);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->template($mb . '/Instead_plan');

    }
    //2018/03/27

    /*快捷协议代扣协议申请接口
    *access public
    *@param array $bs_params 公共参数
    *@param array $yw_params 业务参数
    *
    */
    function ktf_treaty_apply($yw_params = array()){

        header("Content-type:text/html; charset=UTF-8");

        require_once('lib/Sign.php');

        if(empty($yw_params)){

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;
        }

        $bs_params = $this->Session->read('bs_params');

        $bs_params['service'] = "gbp_treaty_collect_apply";

        $params = array_merge($bs_params, $yw_params);

        error_log('['. date('Y-m-d H:m:s') .'] request message:'."\n".var_export($params,true)."\n\n",3,'./app/user/kft_error_log/treaty_apply/'.date('Y-m-d').'.log');

        $pfx_path = ADMIN_URL.'app/user/account/pfx.pfx';

        //测试url
        $request_trade_url = "https://218.17.35.123:6443/gateway/nonbatch";

        $sign = new Sign($pfx_path, '123456');
        //普通交易请求
        $sign_data = $sign->sign_data($params);

        // echo $sign_data;
        $response_data = $sign->request_kft($params, $sign_data, $request_trade_url, false);

        error_log('['. date('Y-m-d H:m:s') .'] response message:'."\n".var_export($response_data,true)."\n\n"."<------------------------------------------------------------------------------------------------------------->"."\n",3,'./app/user/kft_error_log/treaty_apply/'.date('Y-m-d').'.log');

        return json_decode($response_data,true);
        
    }

    /*
    *快捷协议代扣协议确认接口
    *@param array $bs_params 公共参数
    *@param array $yw_params 业务参数
    *
    **/
    function kft_treaty_confirm($yw_params = array()){

        header("Content-type:text/html; charset=UTF-8");

        require_once('lib/Sign.php');

        if(empty($yw_params)){

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;
        }

        $bs_params = $this->Session->read('bs_params');

        $bs_params['service'] = "gbp_confirm_treaty_collect_apply";

        $params = array_merge($bs_params, $yw_params);

        error_log('['. date('Y-m-d H:m:s') .'] request message:'."\n".var_export($params,true)."\n\n",3,'./app/user/kft_error_log/treaty_confirm/'.date('Y-m-d').'.log');

        $pfx_path = ADMIN_URL.'app/user/account/pfx.pfx';

        //测试url
        $request_trade_url = "https://218.17.35.123:6443/gateway/nonbatch";

        $sign = new Sign($pfx_path, '123456');
        //普通交易请求
        $sign_data = $sign->sign_data($params);

        // echo $sign_data;
        $response_data = $sign->request_kft($params, $sign_data, $request_trade_url, false);

        error_log('['. date('Y-m-d H:m:s') .'] response message:'."\n".var_export($response_data,true)."\n\n"."<------------------------------------------------------------------------------------------------------------->"."\n",3,'./app/user/kft_error_log/treaty_confirm/'.date('Y-m-d').'.log');
        return json_decode($response_data,true);
    }

    function Instead_verify_code()
    {
        
        $card_id  = !empty($_POST['card_id'])?$_POST['card_id']:'';

        $uid = $this->checked_instead_login();

        if (empty($card_id)) {

            $card_id = !empty($_GET['card_id'])?$_GET['card_id']:'';

            if(empty($card_id)){

                $this->jump(ADMIN_URL . 'user.php?act=Instead');

                exit;
            }
            

        }

        $this->set('card_id',$card_id);

        $thisplan_list = $this->Session->read('plan_list_' . $card_id);

        foreach ($thisplan_list as  $value) {

            $huan_time[] = $value['huan_time'];
        }

        $plan_list['kou']  = date("Y-m-d",time());

        $plan_list['huan'] =  date("Y-m-d",max($huan_time));

        if($plan_list['kou'] == $plan_list['huan']){

            $plan_list['huan'] = date("Y-m-d", (max($huan_time)+3600*24));

        }

        $orderNo = time();

        $sql = "SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE state = 1 AND uid = ".$uid;

        $bankInfo = $this->App->findrow($sql);

        $sql = "SELECT bankno FROM `{$this->App->prefix() }bank_info` WHERE id = ".$bankInfo['bank'];

        $rt = $this->App->findrow($sql);

        $bankType = substr($rt['bankno'],0, 7);
 
        $valid = $bankInfo['valid'];

        $cvn2  = $bankInfo['cvn2'];

        $startDate = date('Ymd',strtotime($plan_list['kou']));
        
        $endDate   = date('Ymd',strtotime($plan_list['huan']));

        $yw_params = array(
            "productNo"  => "GBPTM004",//产品编号

            "orderNo"    => $orderNo,

            "treatyType" => "12",//协议类型 11 借记卡 12 信用卡

            "startDate"  => $startDate,

            "endDate"    => $endDate,

            "holderName" => $bankInfo['name'],

            "bankType"   => $bankType,

            "bankCardType"=> "2",//1 借记卡 2信用卡

            "bankCardNo"  => $bankInfo['bank_no'],

            "mobileNo"    => $bankInfo['mobile'],

            "certificateType" => "0",

            "certificateNo"=> $bankInfo['idcard'],

            "custCardValidDate" => $valid,//信用卡有效期

            "custCardCvv2"=> $cvn2,//cvn2
        );

        $result = $this->ktf_treaty_apply($yw_params);

        if($result['status'] == 1){

            $smsSeq  = $result['smsSeq'];

            $this->set('smsSeq', $smsSeq);

            $orderNo = $result['orderNo'];

            $this->set('orderNo', $orderNo);


        }else{

            if($result['errorCode'] == '20103'){

                $this->plan_confirm($_POST);

                exit;
                }

            $this->jump(ADMIN_URL . 'user.php?act=Instead',0,$result['failureDetails']);

            exit;
        }
        $this->set('plan_list', $plan_list);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->template($mb . '/Instead_verify_code');
    }

    function kft_verify_code(){

        $uid = $this->checked_instead_login();

        if(empty($_POST['yz_code']) || empty($_POST) || empty($_POST['smsSeq']) || empty($_POST['orderNo']) || empty($_POST['card_id'])){

            $this->jump(ADMIN_URL . 'user.php?act=Instead',0,'必要参数不能为空');

            exit;
        }
        $sql = "SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE state = 1 AND uid = ".$uid;

        $bankInfo = $this->App->findrow($sql);

        $orderNo  = $_POST['orderNo'];

        $card_id  = $_POST['card_id'];

        $yw_params = array(

            "productNo"  => "GBPTM004",//产品编号

            "orderNo"    => $orderNo,

            "smsSeq" => $_POST['smsSeq'],//短信流水单号

            "holderName" => $bankInfo['name'],

            "authCode"=> $_POST['yz_code'],//短信验证码

            "bankCardNo" => $bankInfo['bank_no'],

            "custCardValidDate" => $bankInfo['valid'],

            "custCardCvv2" => $bankInfo['cvn2'],
        );

        $result = $this->kft_treaty_confirm($yw_params);

        if($result['status'] == 1){

            $treatyId = $result['treatyId'];

            $orderNo  = $result['orderNo'];

            $data = array('treatyId' => $treatyId,'orderNo'=> $orderNo);

            if($this->App->update('user_card_instead',$data ,'uid' ,$uid)){

                echo json_encode(array('status'=>1,'message'=>$card_id));
                }

        }else{

           echo json_encode(array('status'=>-1,'message'=>$result['failureDetails']));
        }

    }

    function plan_confirm($postData = '') {

        $_POST = $postData;

        if (isset($_POST) && !empty($_POST)) {

            $cardinstead['card_id'] = empty($_POST['card_id']) ? 0 : $_POST['card_id'];

            $cardinstead['user_id'] = empty($_POST['user_id']) ? 0 : $_POST['user_id'];

            $cardinstead['Instead_money'] = empty($_POST['Instead_money']) ? 0 : $_POST['Instead_money'];

            $cardinstead['Over_money'] = empty($_POST['Over_money']) ? 0 : $_POST['Over_money'];

            $cardinstead['Bill_day'] = empty($_POST['Bill_day']) ? 0 : $_POST['Bill_day'];

            $cardinstead['Instead_day'] = empty($_POST['Instead_day']) ? 0 : $_POST['Instead_day'];

            $cardinstead['addtime'] = time();

            $user_card_instead_plans = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $cardinstead['card_id'] . " and user_id =" . $cardinstead['user_id'] . " and status = 1 and stop =0");

            if ($user_card_instead_plans) {

                $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '计划已生成！重新生成还款计划需终止当前还款计划');

                exit();

            }

            $Instead_info = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_setting` WHERE card_id = " . $cardinstead['card_id'] . " and user_id = " . $cardinstead['user_id']);

            if ($Instead_info) {

                $this->App->update('user_card_instead_setting', $cardinstead, 'card_id', $cardinstead['card_id']);

            } else {

                $this->App->insert('user_card_instead_setting', $cardinstead);

            }

            $thisplan_list = $this->Session->read('plan_list_' . $cardinstead['card_id']);

            //$date_arr = $this->Session->read('date_arr_'.$card_id);

            $Instead_info = $this->Session->read('Instead_info_' . $cardinstead['card_id']);

            if ($thisplan_list) {

                if ($cardinstead['user_id']) {

                    $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id=" . $cardinstead['user_id'] . " LIMIT 1");

                    $level = $this->App->findrow("SELECT feilv,sxf_instead FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");

                    $feilvs = unserialize($level['feilv']);

                    $pay_fangshi = $this->App->findvar("SELECT pay_code FROM `{$this->App->prefix() }payment` WHERE pay_id=20 LIMIT 1");

                    //计算手续费

                    $koulv = $feilvs[$pay_fangshi];

                    $feilv = $koulv;

                    $sxf = $level['sxf_instead'];

                }

                foreach ($thisplan_list as $row) {

                    $plan_no = 'PLAN' . time() . $cardinstead['user_id'];

                    $cardinsteadplan = array('user_id' => empty($_POST['user_id']) ? 0 : $_POST['user_id'], 'plan_no' => $plan_no, 'card_id' => empty($_POST['card_id']) ? 0 : $_POST['card_id'], 'kou_money' => $row['jiaoyi'], 'kou_time' => $row['kou_time'], 'huan_money' => $row['huankuan'], 'huan_time' => $row['huan_time'], 'Instead_sxf' => $row['shouxufei'], 'feilv' => $feilv, 'tixian' => $sxf, 'status' => 1, 'addtime' => time());

                    $this->App->insert('user_card_instead_plans', $cardinsteadplan);

                }

                //其他计划设为终止

                $this->App->query("UPDATE `{$this->App->prefix() }user_card_instead_plans` SET stop =1  WHERE card_id=" . $_POST['card_id'] . " and stop=0 and is_perform_auto >0");

                //$this->jump(ADMIN_URL . 'user.php?act=Instead_splans&card_id='.$cardinstead['card_id']);

                $this->jump(ADMIN_URL . 'user.php?act=Instead_splans_confirm_detail&card_id=' . $cardinstead['card_id']);

                exit;

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=Instead');

                exit;

            }

        }

    }

    function Instead_splans_confirm_detail() {

        $this->layout('Instead_h');

        $uid = $this->checked_instead_login();

        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1");

        if (!$rt['status']) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

            exit;

        }

        $card_id = $_GET['card_id'];

        if (!isset($card_id) || empty($card_id)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $cardinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid=" . $uid . " and id = " . $card_id . " LIMIT 1");

        if (empty($cardinfo)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $this->set('card_id', $card_id);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->template($mb . '/Instead_splans_confirm_detail');

    }

    function Instead_splans() {

        $this->layout('Instead_h');

        $uid = $this->checked_instead_login();

        $rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid . " LIMIT 1");

        if (!$rt['status']) {

            $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

            exit;

        }

        $card_id = $_GET['card_id'];

        if (!isset($card_id) || empty($card_id)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $cardinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid=" . $uid . " and id = " . $card_id . " LIMIT 1");

        if (empty($cardinfo)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead');

            exit;

        }

        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` where id=" . $cardinfo['bank']);

        $cardinfo['bank_name'] = $bank['name'];

        $cardinfo['bank_pic'] = $bank['pic'];

        $cardinfo['bank_no_sort'] = substr($cardinfo['bank_no'], -4);

        $cardinfo['mobile_sort'] = substr_replace($cardinfo['mobile'], '****', 3, 4);

        $Instead_info = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_setting` WHERE card_id = " . $card_id . " and user_id = " . $uid);

        $user_card_instead_plans_ed = $this->App->findrow("SELECT IFNULL(count(id),0) as yh_qishu, IFNULL(sum(huan_money),0) as huan_moneys FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $card_id . " and user_id =" . $uid . " and status = 3 and is_perform_auto=2 and  stop = 0");

        $user_card_instead_plans_all = $this->App->findrow("SELECT MAX(huan_time) as m_huan_time, IFNULL(count(id),0) as z_qishu, IFNULL(sum(huan_money),0) as z_huan_moneys FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $card_id . " and user_id =" . $uid . " and  stop = 0");

        $thisplan_list = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $card_id . " and user_id =" . $uid . " and  stop = 0");

        if (empty($thisplan_list)) {

            $this->jump(ADMIN_URL . 'user.php?act=Instead_setting&id=' . $card_id);

            exit();

        }

        foreach ($thisplan_list as $row) {

            $daifu_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_drawmoney_instead` WHERE plan_id = " . $row['id'] . " and uid = " . $uid);

            if ($daifu_info) {

                $row['daifu_time'] = $daifu_info['paytime'];

                $row['daifu_sn'] = $daifu_info['order_sn'];

            }

            $thisplan_lists[] = $row;

        }

        $percent = round(($user_card_instead_plans_ed['huan_moneys'] / $user_card_instead_plans_all['z_huan_moneys']), 2);

        $this->set('thisplan_list', $thisplan_lists);

        $this->set('cardinfo', $cardinfo);

        $this->set('Instead_info', $Instead_info);

        $this->set('user_card_instead_plans_ed', $user_card_instead_plans_ed);

        $this->set('user_card_instead_plans_all', $user_card_instead_plans_all);

        $this->set('percent', $percent);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->template($mb . '/Instead_splans');

    }

    function Instead_splans_stop() {

        if (isset($_POST) && !empty($_POST)) {

			 $uid = $this->checked_instead_login();

            $card_id = empty($_POST['card_id']) ? 0 : $_POST['card_id'];

            $user_card_instead_plans = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $card_id . " and user_id =" . $uid. " and status = 2 and is_perform_auto = 2  and stop =0");

            if ($user_card_instead_plans) {

                $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '计划执行中！重新生成还款计划需等待当前还款计划完成');

                exit();

            }
            $rts = $this->_get_payinfo(3);

            $pay = unserialize($rts['pay_config']);

            $merchantId = $pay['pay_no'];

            $treatyinfo = $this->App->findrow(" SELECT treatyId,orderNo FROM `{$this->App->prefix() }user_card_instead` WHERE uid =".$uid);

            $orderNo   = $treatyinfo['orderNo'];

            $treatyId  = $treatyinfo['treatyId'];
            //取消代扣协议
            header("Content-type:text/html; charset=UTF-8");

            require_once('lib/Sign.php');

            $bs_params = array(
                'service' => 'gbp_cancel_treaty_info',
                //请求编号,可空提示
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
                "orderNo" => $orderNo,
                "treatyNo" => $treatyId,
            );

            $params = array_merge($bs_params, $yw_params);
            
            error_log('['. date('Y-m-d H:m:s') .'] request message:'."\n".var_export($params,true)."\n\n",3,'./app/user/kft_error_log/'.'giveup_'.date('Y-m-d').'.log');

            $pfx_path = ADMIN_URL.'app/user/account/pfx.pfx';

            //测试url
            $request_trade_url = "https://218.17.35.123:6443/gateway/nonbatch";

            $sign = new Sign($pfx_path, '123456');
            //普通交易请求
            $sign_data = $sign->sign_data($params);

            // echo $sign_data;
            $response_data = $sign->request_kft($params, $sign_data, $request_trade_url, false);

            error_log('['. date('Y-m-d H:m:s') .'] response message:'."\n".var_export($response_data,true)."\n\n"."<------------------------------------------------------------------------------------------------------------->"."\n",3,'./app/user/kft_error_log/'.'giveup_'.date('Y-m-d').'.log');

            $result = json_decode($response_data,true);

            if($result['status'] == 1){

                $sql = "UPDATE `{$this->App->prefix() }user_card_instead_plans` SET stop =1  WHERE card_id=" . $card_id;

                if ($this->App->query($sql)) {

                    //$this->App->update('user_card_instead_plans',array('stop'=>1),'card_id',$card_id);

                    $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '计划已终止！可以重新生成还款计划');

                    exit();

                }
            }else{

                $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, '解除协议失败，错误详情:'."\n".$result['failureDetails']);

                exit();
            }

        }

    }

    function ajax_delete_card($data = array()) {

        $uid = $this->checked_instead_login();

        $card_id = $data['card_id'];

        if (!$uid || !$card_id) {

            echo "解绑失败";

        } else {

            $instead_plan = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead_plans` where user_id=" . $uid . " and status =1 and stop = 0 and card_id=" . $card_id);

            if ($instead_plan) {

                echo "智能还款计划执行中，请先终止还款计划再解绑！";

            } else {

                $card_no = $this->App->findvar("SELECT bank_no FROM `{$this->App->prefix() }user_card_instead` where user_id=" . $uid . " and card_id=" . $card_id);

                //  if($this->App->delete('user_card_instead','id',$card_id) && $this->App->delete('user_sj2_instead','bankaccountNo',$card_no)){

                if ($this->App->delete('user_card_instead', 'id', $card_id)) {

                    echo "success";

                } else {

                    echo "解绑失败";

                }

            }

        }

    }

   

    function request_by_other($url, $data) {

        $opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded', 'content' => $data));

        $context = stream_context_create($opts);

        $result = file_get_contents($url, false, $context);

        return $result;

    }

    function post($url, $param, $timeout = 5) { //curl

        $postUrl = $url;

        $curlPost = $param;

        $ch = curl_init(); //初始化curl

        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页

        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式

        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);

        $data = curl_exec($ch); //运行curl

        curl_close($ch);

        return $data;

    }

    function getBytes($string) {

        $bytes = array();

        for ($i = 0;$i < strlen($string);$i++) {

            $bytes[] = ord($string[$i]);

        }

        return $bytes;

    }

    /*易生支付（智能还款）20180103*/

    function Instead() {
        $this->checked_instead_login();
        $this->layout('Instead_h');

        $uid = $this->checked_instead_login();

        $rt = $this->App->findvar("SELECT status FROM `{$this->App->prefix() }user_bank` WHERE uid = " . $uid);

        if (!$rt) {

            $invitecode = $this->App->findvar("SELECT InviteCode FROM `{$this->App->prefix() }user` WHERE user_id = " . $uid);

            if ($invitecode) {

                $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead');

                exit;

            } else {

                $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple');

                exit;

            }

        }

       // $result = $this->ys_merchant($uid);

        $card_instead = $this->App->find("SELECT * FROM `{$this->App->prefix() }user_card_instead` WHERE uid = " . $uid);

        foreach ($card_instead as $key => $row) {

            $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id = " . $row['bank'] . " LIMIT 1");

            $row['bankname'] = $bank['name'];

            $row['bankpic'] = $bank['pic'];

            $user_card_instead_plans_yhinfo = $this->App->findrow("SELECT IFNULL(count(id),0) as yh_qishu, IFNULL(sum(huan_money),0) as huan_moneys FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $row['id'] . " and user_id =" . $uid . " and status = 3 and stop = 0");

            $user_card_instead_plans_allinfo = $this->App->findrow("SELECT IFNULL(count(id),0) as s_qishu, IFNULL(sum(huan_money),0) as huan_moneys FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $row['id'] . " and user_id =" . $uid . " and  stop = 0");

            $user_card_instead_plans_dhinfo = $this->App->findrow("SELECT IFNULL(count(id),0) as s_qishu FROM `{$this->App->prefix() }user_card_instead_plans` where card_id=" . $row['id'] . " and user_id =" . $uid . " and (status = 1 or status=2) and  stop = 0");

            if ($user_card_instead_plans_dhinfo['s_qishu'] > 0) {

                //				 if($user_card_instead_plans_yhinfo['yh_qishu'] >0){

                $row['instead_desc'] = "还款中(" . $user_card_instead_plans_yhinfo['yh_qishu'] . "/" . $user_card_instead_plans_allinfo['s_qishu'] . "期)";

                // }else{

                //					$row['instead_desc'] = "还款中(".$user_card_instead_plans_yhinfo['yh_qishu']."/".$user_card_instead_plans_allinfo['s_qishu']."期)";

                //					 }

                

            } else {

                $row['instead_desc'] = "请及时设置本月代还款";

            }

            $card_insteads[] = $row;

        }

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('card_insteads', $card_insteads);

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);

        $this->css('css.css');
        $this->template($mb . '/Instead_my');

    }

    //汇联金创商户进件

    function hljc_merchant($arr = array()) {

        $uid = $arr['uid'];

        $name = $arr['name'];

        $idcard = $arr['idcard'];

        $bank_no = $arr['bank_no'];

        $mobile = $arr['mobile'];

        $bank = $arr['bank'];

        $valid = $arr['valid'];

        $cvn2 = $arr['cvn2'];

        $user_hljc_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_hljc_merchant` WHERE uid=" . $uid . " and bankCard='" . $bank_no . "' limit 1");

        //$data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);

        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $bank);

        $bank_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank_info` WHERE code='" . $bank['code'] . "'");

        $rts = $this->_get_payinfo(25);

        $pay = unserialize($rts['pay_config']);

        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

        //费率单独设置

        $user_level = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");

        $feilv = unserialize($user_level['feilv']);

        $koulv = $feilv['yinlian_instead'];

        $sxf = $user_level['sxf_instead'] * 100;

        if (!empty($user_hljc_merchant)) {

            if ($koulv != $user_hljc_merchant['rate'] || $sxf != $user_hljc_merchant['extraFee']) {

                $version = '1.0'; //M(String)	1.0

                $charset = 'UTF-8'; //M(String)	编码方式UTF-8

                $agentId = $pay['pay_no']; //M(String)	受理方预分配的渠道代理商标识

                $merId = $user_hljc_merchant['merId']; //M(String)	要修改的商户号

                $nonceStr = $this->str_rand(); //M(String)	随机字符串，字符范围a-zA-Z0-9

                $signType = 'RSA'; //M(String)	签名方式，固定RSA

                //sign	//M(String)	签名数据

                $type = 'R'; //M(String)	R、N、B

                //R 修改费率信息

                $rate = $koulv; //N(String)	费率‱ ，不小于代理商费率

                $extraFee = $sxf; //N(String)	手续费(分)

                $sign_str = "agentId=" . $agentId . "&charset=" . $charset . "&extraFee=" . $extraFee . "&merId=" . $merId . "&nonceStr=" . $nonceStr . "&rate=" . $rate . "&signType=" . $signType . "&type=" . $type . "&version=" . $version;

                // error_log('[' . date('Y-m-d H:i:s') . ']updateAPI0:' . "\n" . $sign_str . "\n\n", 3, './app/user/huilian/' . date('Y-m-d') . '.log');

                //$sign_str[] = $this->getBytes($sign_str);

                $sign = $this->pri_encode($sign_str);

                //echo $sign;

                $url = "http://39.108.137.8:8099/v1.0/facade/updateMid";

                $parm = array('agentId' => $agentId, 'charset' => $charset, 'extraFee' => $extraFee, 'merId' => $merId, 'nonceStr' => $nonceStr, 'rate' => $rate, 'signType' => $signType, 'type' => $type, 'version' => $version, 'sign' => $sign);

                // error_log('[' . date('Y-m-d H:i:s') . ']updateAPI1:' . "\n" . var_export($parm, true) . "\n\n", 3, './app/user/huilian/' . date('Y-m-d') . '.log');

                //  echo post($url,$data);

                // $jsonStr = json_encode($parm);

                // $result = $this->h5_post($url, $parm);

                // $result = json_decode($result, true);

                // error_log('[' . date('Y-m-d H:i:s') . ']updateAPI2:' . "\n" . var_export($result, true) . "\n\n", 3, './app/user/huilian/' . date('Y-m-d') . '.log');

                // if ($result['code'] == "10000") {

                    // if (!empty($result['respCode']) && $result['respCode'] == '10000') {

                        $card['rate'] = $rate; //M(String)	费率‱ ，不小于代理商费率

                        $card['extraFee'] = $extraFee; //M(String)	手续费(分)

                        $this->App->update('user_hljc_merchant', $card, 'id', $user_hljc_merchant['id']);

                    // } else {

                    //     $client = $_SERVER['HTTP_USER_AGENT'];

                    //     //用php自带的函数strpos来检测是否是微信端

                    //     if (strpos($client, 'MicroMessenger') === false) {

                    //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['respMessage']);

                    //         exit;

                    //     } else {

                    //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['respMessage']);

                    //         exit;

                    //     }

                    // }

                // } else {

                //     $client = $_SERVER['HTTP_USER_AGENT'];

                //     //用php自带的函数strpos来检测是否是微信端

                //     if (strpos($client, 'MicroMessenger') === false) {

                //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['message']);

                //         exit;

                //     } else {

                //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['message']);

                //         exit;

                //     }

                // }

            }

        } else {

            $version = '1.0'; //M(String)	1.0

            $charset = 'UTF-8'; //M(String)	编码方式UTF-8

            $agentId = $pay['pay_no']; //M(String)	受理方预分配的渠道代理商标识

            $nonceStr = $this->str_rand(); //M(String)	随机字符串，字符范围a-zA-Z0-9

            $signType = 'RSA'; //M(String)	签名方式，固定RSA

            //$sign = '';	//M(String)	签名数据

            $isCompay = '0'; //M(String)	对公对私标识0为对私，1为对公

            $idcardType = '01'; //M(String)	证件类型 暂只支持 01 身份证

            $idcard = $idcard; //M(String)	证件号码

            $name = $name; //M(String)	姓名

            $phone = $mobile; //M(String)	手机号

            $bankId = $bank_info['bankno']; //M(String)	联行号

            $bankCard = $bank_no; //M(String)	银行卡号

            $bankName = $bank_info['bankname']; //M(String)	开户行名称

            $bankNo = $bank_info['bankcode']; //M(String)	开户行代码(PAB)

            $rate = $koulv; //M(String)	费率‱ ，不小于代理商费率

            $extraFee = $sxf; //M(String)	手续费(分)

            //$address	//N(String)	地址

            //$remark	//N(String)	备注

            $card['uid'] = $uid;

            $card['agentId'] = $agentId; //M(String)	受理方预分配的渠道代理商标识

            $card['nonceStr'] = $nonceStr; //M(String)	随机字符串，字符范围a-zA-Z0-9

            $card['idcard'] = $idcard; //M(String)	证件号码

            $card['name'] = $name; //M(String)	姓名

            $card['phone'] = $phone; //M(String)	手机号

            $card['bankId'] = $bankId; //M(String)	联行号

            $card['bankCard'] = $bankCard; //M(String)	银行卡号

            $card['bankName'] = $bankName; //M(String)	开户行名称

            $card['bankNo'] = $bankNo; //M(String)	开户行代码(PAB)

            $card['rate'] = $rate; //M(String)	费率‱ ，不小于代理商费率

            $card['extraFee'] = $extraFee; //M(String)	手续费(分)

            $sign_str = "agentId=" . $agentId . "&bankCard=" . $bankCard . "&bankId=" . $bankId . "&bankName=" . $bankName . "&bankNo=" . $bankNo . "&charset=" . $charset . "&extraFee=" . $extraFee . "&idcard=" . $idcard . "&idcardType=" . $idcardType . "&isCompay=" . $isCompay . "&name=" . $name . "&nonceStr=" . $nonceStr . "&phone=" . $phone . "&rate=" . $rate . "&signType=" . $signType . "&version=" . $version;

            // error_log('[' . date('Y-m-d H:i:s') . ']API0:' . "\n" . $sign_str . "\n\n", 3, './app/user/huilian/' . date('Y-m-d') . '.log');



            //$sign_str[] = $this->getBytes($sign_str);

            // $sign = $this->pri_encode($sign_str);

            //echo $sign;

            $data = "agentId=" . $agentId . "&bankCard=" . $bankCard . "&bankId=" . $bankId . "&bankName=" . $bankName . "&bankNo=" . $bankNo . "&charset=" . $charset . "&extraFee=" . $extraFee . "&idcard=" . $idcard . "&idcardType=" . $idcardType . "&isCompay=" . $isCompay . "&name=" . $name . "&nonceStr=" . $nonceStr . "&phone=" . $phone . "&rate=" . $rate . "&signType=" . $signType . "&version=" . $version . "&sign=" . $sign;

            // $url = "http://39.108.137.8:8099/v1.0/facade/report";

            // error_log('[' . date('Y-m-d H:i:s') . ']API1:' . "\n" . $data . "\n\n", 3, './app/user/huilian/' . date('Y-m-d') . '.log');

            //  echo post($url,$data);

            $data = "agentId=" . $agentId . "&bankCard=" . $bankCard . "&bankId=" . $bankId . "&bankName=" . $bankName . "&bankNo=" . $bankNo . "&charset=" . $charset . "&extraFee=" . $extraFee . "&idcard=" . $idcard . "&idcardType=" . $idcardType . "&isCompay=" . $isCompay . "&name=" . $name . "&nonceStr=" . $nonceStr . "&phone=" . $phone . "&rate=" . $rate . "&signType=" . $signType . "&version=" . $version . "&sign=" . $sign;

            $parm = array('agentId' => $agentId, 'bankCard' => $bankCard, 'bankId' => $bankId, 'bankName' => $bankName, 'bankNo' => $bankNo, 'charset' => $charset, 'extraFee' => $extraFee, 'idcard' => $idcard, 'idcardType' => $idcardType, 'isCompay' => $isCompay, 'name' => $name, 'nonceStr' => $nonceStr, 'phone' => $phone, 'rate' => $rate, 'signType' => $signType, 'version' => $version, 'sign' => $sign);

            // $jsonStr = json_encode($parm);

            // $result = $this->h5_post($url, $parm);
            
            // $result = json_decode($result, true);

            // error_log('[' . date('Y-m-d H:i:s') . ']API2:' . "\n" . var_export($result, true) . "\n\n", 3, './app/user/huilian/' . date('Y-m-d') . '.log');

            // if ($result['code'] == "10000") {

                // if (!empty($result['respCode']) && $result['respCode'] == '10000') {

                    $card['merId'] = $result['merId'];

                    $this->App->insert('user_hljc_merchant', $card);

                // } else {

                //     $client = $_SERVER['HTTP_USER_AGENT'];

                //     //用php自带的函数strpos来检测是否是微信端

                //     if (strpos($client, 'MicroMessenger') === false) {

                //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['respMessage']);

                //         exit;

                //     } else {

                //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['respMessage']);

                //         exit;

                //     }

                // }

            // } else {

            //     $client = $_SERVER['HTTP_USER_AGENT'];

            //     //用php自带的函数strpos来检测是否是微信端

            //     if (strpos($client, 'MicroMessenger') === false) {

            //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['message']);

            //         exit;

            //     } else {

            //         $this->jump(ADMIN_URL . 'user.php?act=Instead', 0, $result['message']);

            //         exit;

            //     }

            // }

        }

    }

    // function getBytes($string) {

    //        $bytes = array();

    //        for($i = 0; $i < strlen($string); $i++){

    //             $bytes[] = ord($string[$i]);

    //        }

    //        return $bytes;

    //    }

    function str_rand($length = 16, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {

        if (!is_int($length) || $length < 0) {

            return false;

        }

        $string = '';

        for ($i = $length;$i > 0;$i--) {

            $string.= $char[mt_rand(0, strlen($char) - 1) ];

        }

        return $string;

    }

    function pri_encode($data) {

        $encrypted = '';

        $private_key = file_get_contents('./app/user/1001023_prv.pem'); //秘钥

        $pi_key = openssl_pkey_get_private($private_key); //这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id

        $str = '';

        foreach (str_split($data, 117) as $chunk) {

            openssl_private_encrypt($chunk, $encryptedTemp, $pi_key); //私钥加密

            $str.= $encryptedTemp;

        }

        $encrypted = base64_encode($str); //加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的

        return $encrypted;

    }

    //function http_post_data($url, $data_string) {

    //    $ch = curl_init();

    //    curl_setopt($ch, CURLOPT_POST, 1);

    //    curl_setopt($ch, CURLOPT_URL, $url);

    //    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

    //    curl_setopt($ch, CURLOPT_HTTPHEADER, array(

    //        "Content-Type: application/json; charset=utf-8",

    //        "Content-Length: " . strlen($data_string))

    //    );

    //    ob_start();

    //    curl_exec($ch);

    //    $return_content = ob_get_contents();

    //    ob_end_clean();

    //    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //    return $return_content;

    //}

    function h5_post($url, $post_data = '', $timeout = 60) { //curl

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        if ($post_data != '') {

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_HEADER, false);

        $file_contents = curl_exec($ch);

        curl_close($ch);

        return $file_contents;

    }

    function ys_merchant($uid) {

        $uid = $this->checked_instead_login();

        $user_ys_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_ys_merchant` WHERE uid=" . $uid . " limit 1");

        $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_bank` WHERE uid=" . $uid);

        $bank = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bank` WHERE id=" . $data['bank']);

        $bankclass = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }bankclass` WHERE code='" . $bank['code'] . "'");

        $rts = $this->_get_payinfo(22);

        $pay = unserialize($rts['pay_config']);

        $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id='$uid' LIMIT 1");

        //费率单独设置

        $user_level = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");

        $feilv = unserialize($user_level['feilv']);

        $koulv = $feilv['yinlian_instead'] / 100;

        $sxf = $user_level['sxf_instead'] * 100;

        if (!empty($user_ys_merchant)) {

            if ($koulv != $user_ys_merchant['kjpay3FeeRate'] || $sxf != $user_ys_merchant['t0_fee']) {

                $signKey = $pay['pay_code'];

                $input_charset = "UTF-8";

                $version = "N2";

                $partner = $pay['pay_idt'];

                $service = "updateMerchant";

                $sign_type = "MD5";

                $merchant_id = $user_ys_merchant['merchantId']; //String(15)	通过智能还款商户信息上传接口进件的商户返回的商户编码，易生系统分配给商户的唯一商户编码

                $kjpay3FeeRate = $koulv; //Double	快捷费率百分比,最多两位小数,0.60代表0.60%	NO	0.60

                $t0_fee = $sxf; //Number	单位分	NO	t0额外的手续费,代付手续费分

                $signstr = "input_charset=" . $input_charset . "&kjpay3FeeRate=" . $kjpay3FeeRate . "&merchant_id=" . $merchant_id . "&partner=" . $partner . "&service=" . $service . "&t0_fee=" . $t0_fee . "&version=" . $version;

                $sign = md5($signstr . $signKey);

                error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr . "\n\n", 3, './app/user/kuaijie_api/' . date('Y-m-d') . '.log');

                $data = "action=updateMerchant&input_charset=" . $input_charset . "&kjpay3FeeRate=" . $kjpay3FeeRate . "&merchant_id=" . $merchant_id . "&partner=" . $partner . "&service=" . $service . "&sign_type=" . $sign_type . "&sign=" . $sign . "&t0_fee=" . $t0_fee . "&version=" . $version;

                $url = "http://ws.weishuapay.com/m/yishengpay/yishengpay.php";

                //$url = "https://wepay.mpay.cn/new_gateway.do";

                error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data . "\n\n", 3, './app/user/kuaijie_api/' . date('Y-m-d') . '.log');

                //  echo post($url,$data);

                $result = $this->post($url, $data);

                $result = json_decode($result);

                error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true) . "\n\n", 3, './app/user/kuaijie_api/' . date('Y-m-d') . '.log');

                $result = $this->xmlToArray2($result);

                if ($result['is_success'] == "T") {

                    if (!empty($result['response']['merchantId'])) {

                        $this->App->update('user_ys_merchant', array('kjpay3FeeRate' => $kjpay3FeeRate, 't0_fee' => $t0_fee), 'uid', $uid);

                    }

                } else {

                    $client = $_SERVER['HTTP_USER_AGENT'];

                    //用php自带的函数strpos来检测是否是微信端

                    if (strpos($client, 'MicroMessenger') === false) {

                        $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead', 0, $result['error_msg']);

                        exit;

                    } else {

                        $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple', 0, $result['error_msg']);

                        exit;

                    }

                }

            }

            // $this->sj3_instead($uid);

            

        } else {

            $signKey = $pay['pay_code'];

            $input_charset = "UTF-8";

            $version = "N2";

            $partner = $pay['pay_idt'];

            $service = "addMerchant";

            $sign_type = "MD5";

            $merchant_id = $pay['pay_no']; //String(15)	上层机构编号,易生系统分配给商户的唯一商户编码,大商户号,代理机构编号	NO	000000000000002

            $name = $data['uname']; //String	商户名称	NO	测试商户A

            $realName = $data['uname']; //String	银行卡户名	NO	张三

            $id_no = strtoupper($data['idcard']); //String(18)	身份证号	NO	310228199001010001

            $mobile = $data['mobile']; //String(11)	手机号	NO	13011122222

            $bank_name = $bank['name']; //String	开户网点	NO	中国建设银行上海七莘路支行

            $bank_acc = $data['banksn']; //String	银行卡号	NO	6217001210033089409

            $nbkno = $bankclass['bankChannelNo']; //String	联行号	NO	105290074066

            $kjpay3FeeRate = $koulv; //Double	快捷费率百分比,最多两位小数,0.60代表0.60%	NO	0.60

            $t0_fee = $sxf; //Number	单位分	NO	t0额外的手续费,代付手续费分

            $card['uid'] = $uid;

            $card['input_charset'] = $input_charset;

            $card['version'] = $version;

            $card['partner'] = $partner;

            $card['service'] = $service;

            $card['sign_type'] = $sign_type;

            $card['merchant_id'] = $merchant_id; //String(15)	上层机构编号,易生系统分配给商户的唯一商户编码,大商户号,代理机构编号	NO	000000000000002

            $card['name'] = $name; //String	商户名称	NO	测试商户A

            $card['realName'] = $realName; //String	银行卡户名	NO	张三

            $card['id_no'] = $id_no; //String(18)	身份证号	NO	310228199001010001

            $card['mobile'] = $mobile; //String(11)	手机号	NO	13011122222

            $card['bank_name'] = $bank_name; //String	开户网点	NO	中国建设银行上海七莘路支行

            $card['bank_acc'] = $bank_acc; //String	银行卡号	NO	6217001210033089409

            $card['nbkno'] = $nbkno; //String	联行号	NO	105290074066

            $card['kjpay3FeeRate'] = $kjpay3FeeRate; //Double	快捷费率百分比,最多两位小数,0.60代表0.60%	NO	0.60

            $card['t0_fee'] = $t0_fee; //Number	单位分	NO	t0额外的手续费,代付手续费分

            $signstr = "bank_acc=" . $bank_acc . "&bank_name=" . $bank_name . "&id_no=" . $id_no . "&input_charset=" . $input_charset . "&kjpay3FeeRate=" . $kjpay3FeeRate . "&merchant_id=" . $merchant_id . "&mobile=" . $mobile . "&name=" . $name . "&nbkno=" . $nbkno . "&partner=" . $partner . "&realName=" . $realName . "&service=" . $service . "&t0_fee=" . $t0_fee . "&version=" . $version;

            $sign = md5($signstr . $signKey);

            error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $signstr . "\n\n", 3, './app/user/kuaijie_api/' . date('Y-m-d') . '.log');

            $data = "action=addMerchant&bank_acc=" . $bank_acc . "&bank_name=" . $bank_name . "&id_no=" . $id_no . "&input_charset=" . $input_charset . "&kjpay3FeeRate=" . $kjpay3FeeRate . "&merchant_id=" . $merchant_id . "&mobile=" . $mobile . "&name=" . $name . "&nbkno=" . $nbkno . "&partner=" . $partner . "&realName=" . $realName . "&service=" . $service . "&sign_type=" . $sign_type . "&sign=" . $sign . "&t0_fee=" . $t0_fee . "&version=" . $version;

            $url = "http://ws.weishuapay.com/m/yishengpay/yishengpay.php";

            //$url = "https://wepay.mpay.cn/new_gateway.do";

            error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . $data . "\n\n", 3, './app/user/kuaijie_api/' . date('Y-m-d') . '.log');

            //  echo post($url,$data);

            $result = $this->post($url, $data);

            $result = json_decode($result);

            error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($result, true) . "\n\n", 3, './app/user/kuaijie_api/' . date('Y-m-d') . '.log');

            $result = $this->xmlToArray2($result);

            if ($result['is_success'] == "T") {

                if (!empty($result['response']['merchantId'])) {

                    $card['merchantId'] = $result['response']['merchantId'];

                    $this->App->insert('user_ys_merchant', $card);

                }

            } else {

                $client = $_SERVER['HTTP_USER_AGENT'];

                //用php自带的函数strpos来检测是否是微信端

                if (strpos($client, 'MicroMessenger') === false) {

                    $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple_instead', 0, $result['error_msg']);

                    exit;

                } else {

                    $this->jump(ADMIN_URL . 'user.php?act=renzheng_simple', 0, $result['error_msg']);

                    exit;

                }

            }

        }

    }

    function xmlToArray2($xml) {

        // 将XML转为array

        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $array_data;

    }

}

?>