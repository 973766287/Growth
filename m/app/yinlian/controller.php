<?php

class YinlianController extends Controller {

    //构造函数，自动新建对象
    function __construct() {
        /*
         * 构造函数，自动新建session对象
         */
        $this->js(array('jquery.json-1.3.js', 'user.js?v=v1'));
    }

    function checked_login() {
        $uid = $this->Session->read('User.uid');
        if (!($uid > 0)) {
            $this->jump(ADMIN_URL . 'user.php?act=login');
            exit;
        }
        return $uid;
    }

    function get_regions($type, $parent_id = 0) {
        $p = "";
        if (!empty($parent_id))
            $p = "AND parent_id='$parent_id'";

        $sql = "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";
        return $this->App->find($sql);
    }

    function applyshop() {
        $uid = $this->checked_login();
        $this->action('common', 'checkjump');

        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);
        if ($rt['userinfo']['isshop'] == '1') {
            $this->set('fxrank', '1'); //是店铺
        } else {
            $this->set('fxrank', '2'); //申请店铺
        }

        $rt['province'] = $this->get_regions(1);  //获取省列表
        //当前用户的收货地址
        $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
        $rt['userress'] = $this->App->findrow($sql);

        if ($rt['userress']['province'] > 0)
            $rt['city'] = $this->get_regions(2, $rt['userress']['province']);  //城市
        if ($rt['userress']['city'] > 0)
            $rt['district'] = $this->get_regions(3, $rt['userress']['city']);  //区	

        $s = $fxrank != '1' ? '申请开店' : '编辑资料';
        $this->title($s);

        $this->set('rt', $rt);
        if (!defined(NAVNAME))
            define('NAVNAME', $s);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v3_applyshop');
    }

    function ajax_update_shopinfo($data = array()) {
        $json = Import::json();
        $uid = $this->checked_login();
        if (empty($uid)) {
            $result = array('error' => 3, 'message' => '先您先登录!');
            die($json->encode($result));
        }

        $result = array('error' => 2, 'message' => '传送的数据为空!');
        if (empty($data['fromAttr']))
            die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
        unset($data);


        $datas['isshop'] = '2';
        $datas['email'] = $fromAttr->email;
        if (empty($datas['email'])) {
            $result = array('error' => 4, 'message' => '请填写正确邮箱！');
            die($json->encode($result));
        }
        $datas['mobile_phone'] = $fromAttr->mobile_phone;
        if (empty($datas['mobile_phone'])) {
            $result = array('error' => 4, 'message' => '请填写手机号码！');
            die($json->encode($result));
        }
        //检测该号码是否存在
        $mb = $datas['mobile_phone'];
        $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE mobile_phone = '$mb' AND user_id!='$uid' LIMIT 1";
        $id = $this->App->findvar($sql);
        if ($id > 0) {
            $result = array('error' => 4, 'message' => '该手机号码已经被使用！');
            die($json->encode($result));
        }

        $datas['msn'] = $fromAttr->msn; //微信号
        if (empty($datas['msn'])) {
            $result = array('error' => 4, 'message' => '请填写微信号！');
            die($json->encode($result));
        }
        $ni = $fromAttr->consignee;
        if (empty($ni)) {
            $result = array('error' => 4, 'message' => '请填写真实姓名！');
            die($json->encode($result));
        }
        $this->App->update('user', $datas, 'user_id', $uid);
        unset($datas);

        $sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
        $rsid = $this->App->findvar($sql);

        $datas['user_id'] = $uid;
        $datas['email'] = $fromAttr->email;
        $datas['mobile'] = $fromAttr->mobile_phone;
        $datas['consignee'] = $ni;
        $datas['province'] = $fromAttr->province;
        $datas['city'] = $fromAttr->city;
        $datas['district'] = $fromAttr->district;
        $datas['address'] = $fromAttr->address;
        if (!($datas['province'] > 0) || !($datas['city'] > 0) || !($datas['district'] > 0) || empty($datas['address'])) {
            $result = array('error' => 4, 'message' => '请填写必要区域地址！');
            die($json->encode($result));
        }
        $datas['is_own'] = 1;

        if ($rsid > 0) { //更新
            $this->App->update('user_address', $datas, 'address_id', $rsid);
        } else { //添加
            $this->App->insert('user_address', $datas);
        }

        $result = array('error' => 4, 'message' => '操作成功！');
        die($json->encode($result));


        unset($datas);
    }

//end function
    //获取用户的openid
    function get_openid_AND_pay_info() {
        $wecha_id = $this->Session->read('User.wecha_id');
        if (empty($wecha_id))
            $wecha_id = isset($_COOKIE[CFGH . 'USER']['UKEY']) ? $_COOKIE[CFGH . 'USER']['UKEY'] : '';

        //
        $order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
        if (empty($order_sn))
            exit;

        $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming_order` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            $this->jump(str_replace('/wxpay', '', ADMIN_URL), 0, '非法支付提交！');
            exit;
        }
        if ($rt['pay_status'] == '1') {
            $this->jump(str_replace('/wxpay', '', ADMIN_URL));
            exit;
        }
        $rt['openid'] = $wecha_id;
        $rt['body'] = $GLOBALS['LANG']['site_name'] . '-在线报名';
        return $rt;
    }

    function confirmpay($data = array()) {
        if (!empty($_POST)) {

            $uname = $_POST['uname'];
            $upne = $_POST['upne'];
            $price = $_POST['price'];
            $ids = $_POST['ids'];

            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $cardcode = $_POST['cardcode'];
            $school = $_POST['school'];
            $department = $_POST['department'];
            $grade = $_POST['grade'];
            $job = $_POST['job'];
            $address = $_POST['address'];
            $qq = $_POST['qq'];
            $weixin = $_POST['weixin'];
            $cardphoto1 = $_POST['cardphoto1'];
            $cardphoto2 = $_POST['cardphoto2'];
            $cardphoto3 = $_POST['cardphoto3'];
            $cardphoto4 = $_POST['cardphoto4'];
            $uid = $this->checked_login();

            /*   if ($price > 999) {

              $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
              $rt = $this->App->findrow($sql);
              $this->set('user_rank', $rt['user_rank']);
              $this->set('rank', $ids);
              $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

              $this->set('mubanid', $GLOBALS['LANG']['mubanid']);
              $this->template($mb . '/v3_baoming_kefu');
              exit;
              } */

            if (empty($uname) || empty($upne) || empty($price) || empty($ids)) {
                exit;
            }

            $on = date('Y', mktime()) . mktime();
            $dd = array();
            $dd['bid'] = $ids;
            $dd['order_sn'] = $on;
            $dd['user_id'] = $uid;
            $dd['order_amount'] = $price;
            $dd['uname'] = $uname;
            $dd['upne'] = $upne;
            $dd['add_time'] = mktime();

            $dd['age'] = $age;
            $dd['gender'] = $gender;
            $dd['cardcode'] = $cardcode;
            $dd['school'] = $school;
            $dd['department'] = $department;
            $dd['grade'] = $grade;
            $dd['job'] = $job;
            $dd['qq'] = $qq;
            $dd['weixin'] = $weixin;
            $dd['address'] = $address;

            $dd['cardphoto1'] = $cardphoto1;
            $dd['cardphoto2'] = $cardphoto2;
            $dd['cardphoto3'] = $cardphoto3;
            $dd['cardphoto4'] = $cardphoto4;

            if ($this->App->insert('cx_baoming_order', $dd)) {
                $this->jump(ADMIN_URL . 'wxpay/js_api_call.php?order_sn=' . $on . '&bm=baoming');
                exit;
            } else {
                $this->jump(ADMIN_URL, 0, '意外错误');
                exit;
            }
        }
    }

    function baoming_word($data = array()) {
        $uid = $this->checked_login();
        $this->action('common', 'checkjump');
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($id > 0)
            $s = "WHERE id='$id'";
        $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` {$s} ORDER BY id DESC LIMIT 1";
        $rt['pinfo'] = $this->App->findrow($sql);
        $this->title($rt['pinfo']['title']);

        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);
        $this->template($mb . '/v3_baoming_word');
    }

    //报名
    function baoming($data = array()) {


        $uid = $this->checked_login();
        $this->action('common', 'checkjump');
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);
        if (empty($rt['userinfo'])) {
            //die("此账号已经被禁用或者没有激活！");
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
            $this->jump(ADMIN_URL . 'bm.php?act=baoming&id=' . $id);
            exit;
        }

        $t = Common::_return_px();
        $cache = Import::ajincache();
        $cache->SetFunction(__FUNCTION__);
        $cache->SetMode('page' . $t);
        $fn = $cache->fpath(array('0' => $id));
        if (file_exists($fn) && !$cache->GetClose()) {
            include($fn);
        } else {
            $s = '';
            if ($id > 0)
                $s = "WHERE id='$id'";
            $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` {$s} ORDER BY id DESC LIMIT 1";
            $rt['pinfo'] = $this->App->findrow($sql);

            $cache->write($fn, $rt, 'rt');
        }

        //   $uid = $this->Session->read('User.uid');
        $rt['tjr']['nickname'] = '[官网]';
        $rt['tjr']['headimgurl'] = ADMIN_URL . 'images/uclicon.jpg';
        $rt['uinfo'] = array();
        if ($uid > 0) {
            $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");

            $this->set('rank', $rank);
            //查找是否已经领取奖品
          //  $hasgift = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix()}gift_order` WHERE user_id='$uid' and bid=$id LIMIT 1");
             $sql = "SELECT  bid FROM `{$this->App->prefix()}gift_bag` where type =(select type from `{$this->App->prefix()}gift_bag`  where bid='$id' ) ";
        $hasgift = 0;
        $bids = $this->App->find($sql);
        foreach ($bids as $_k => $_v) {
            $sql = "SELECT count(*) FROM `{$this->App->prefix()}gift_order` where bid='$_v[bid]' and user_id='$uid'";

            $count = $this->App->findvar($sql);

            if ($count) {
                // $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');
                // exit;
                $hasgift = 1;
                break;
            }
        }
        
            if (!$hasgift) {
                 $hasgift = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix()}user_level_log` WHERE user_id='$uid' and user_rank=$rank and type=1 LIMIT 1");
            }
            $this->set('hasgift', $hasgift);
            /*    if ($rank != 1 and $rank <= $rt['pinfo']['rank_id']) {
              $this->jump(ADMIN_URL, 0, '您的级别高于当前级别，您可以向更高级别进军了！');
              exit;
              } */
            $sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
            $rt['tjr'] = $this->App->findrow($sql);
            if (empty($rt['tjr'])) {
                $rt['tjr']['nickname'] = '[官网]';
                $rt['tjr']['headimgurl'] = ADMIN_URL . 'images/uclicon.jpg';
            }
            $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
            $rt['uinfo'] = $this->App->findrow($sql);
            if (!empty($rt['uinfo']['headimgurl'])) {
                $rt['tjr']['headimgurl'] = $rt['uinfo']['headimgurl'];
            }
        }

        $this->title($rt['pinfo']['title']);

        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';

        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);
        $this->template($mb . '/v3_baoming');
    }

    function uploadimg() {


        $media_id = $_POST['imgid'];
        $did = $_POST['did'];
        $sql = "SELECT * FROM `{$this->App->prefix()}wxuserset` WHERE id=1  LIMIT 1";
        $weixin = $this->App->findrow($sql);


        $appid = $weixin['appid'];
        $appsecret = $weixin['appsecret'];


        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        $jsoninfo = $this->get_token($url);
        $access_token = $jsoninfo["access_token"];

        $url1 = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=" . $media_id;
        //   $imgs = $wx->get_token($url1);

        echo $this->downloadImageFromQzone($url1, $did);
    }

    function downloadImageFromQzone($url, $did) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);

        curl_close($ch);

        $imageAll = array_merge(array('imgBody' => $package), $httpinfo);
        $imageExt = (0 < preg_match('{image/(\w+)}i', $imageAll["content_type"], $extmatches)) ? $extmatches[1] : "jpeg";
        $contentStr = '';

        if (preg_match('{(jpg|jpeg|png)$}i', $imageExt) == 0) { //非jpg,jpeg,png格式
            $contentStr = -1; //"不支持类型"
        } else if ($imageAll["download_content_length"] / 1024 > 200) { //大于200K
            $contentStr = -2; //"图片太大"
        } else if ($imageAll["total_time"] > 1) { //大于1秒
            $contentStr = -3; //"网速太慢"
        } else {

            $dir0 = SYS_PATH . '/';
            $dir1 = "photos/jinkuihuatop/mcard/";
            $dir = $dir0 . $dir1;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
                chmod($dir, 0777);
            } else {
                chmod($dir, 0777);
            }

            $filename = time();
            $object = $dir . $filename . '.jpg';

            $local_file = fopen($object, 'w');
            if (false !== $local_file) {


                if (false !== fwrite($local_file, $imageAll["imgBody"])) {
                    fclose($local_file);
                    $contentStr = $object;
                    return $dir1 . $filename . '.jpg';
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }




        //  return $imageAll;
    }

    function get_token($url) {

        /* $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $output = curl_exec($ch);
          curl_close($ch); */
        $output = $this->https_request($url);
        $jsoninfo = json_decode($output, true);
        // $access_token = $jsoninfo["access_token"];
        return $jsoninfo;
    }

    function https_request($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
	
	
	
	function ajax_BaseMerchRegister($arr = array()){
	
	
	$query_shiming =  $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$arr['uid']."  LIMIT 1");
		
		if(empty($query_shiming) || $query_shiming['status'] == 0){
			echo "请先实名认证！";
			exit();
			}
			
	
			
	$bankinfo = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}bank` WHERE id=".$query_shiming['bank']." LIMIT 1");
	$bakcode = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}bankclass` WHERE code='".$bankinfo['code']."' LIMIT 1");
	
	
	    $ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix() }user` WHERE user_id=".$arr['uid']." LIMIT 1");
        //费率单独设置
        $user_level = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_level` WHERE lid=" . $ni['user_rank'] . " LIMIT 1");
		
		$tixian_sxf = $user_level['sxf_api']*100;
		$feilv = $user_level['feilv'];
        $feilv = unserialize($feilv);
        $pay_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }payment` WHERE pay_id=" . $arr['pay_id'] . " LIMIT 1");
        //计算手续费
		$pay_code = $pay_info['pay_code'];
		$pay_config = unserialize($pay_info['pay_config']);
        $koulv = $feilv['yinlian_api'] / 10000;

		$user_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_merchant` WHERE uid=".$arr['uid']." LIMIT 1");
		if(!empty($user_merchant)){
			$merchant_info = unserialize($user_merchant['info']);
			if($merchant_info['bankAccountName'] != $query_shiming['uname'] || $merchant_info['legalPersonIdcard'] != $query_shiming['idcard'] || $merchant_info['phoneNo'] != $query_shiming['mobile'] || $merchant_info['bankAccountNo'] != $query_shiming['banksn']){
				
    $orderId = "WS".date('YmdHis',time()).time().$arr['uid'];
    $parent = $pay_config['pay_no'];
    $key = $pay_config['pay_code'];
    $handleType = "M";
	$changeType = "M02";
	$merchId = $merchant_info['merchantNo'];
    $merchName = $merchant_info['merchName'];
    $merchAbb = $merchant_info['merchAbb'];
    $merchAddress = $merchant_info['merchAddress'];
    $telNo = $merchant_info['telNo'];
    $bankAccountName = $query_shiming['uname'];
    $legalPersonIdcard = $query_shiming['idcard'];
    $phoneNo = $query_shiming['mobile'];
    $bankAccountNo = $query_shiming['banksn'];
    $settBankName = $bankinfo['name'];
    $bankChannelNo = $bakcode['bankChannelNo'];
    $bankSubName = $bankinfo['name'];
    $bankProvince = $merchant_info['bankProvince'];
    $bankCity = $merchant_info['bankCity'];
    $bankCode = $bakcode['code'];
    $bankAbbr = $bakcode['b_code'];
    $debitRate = $merchant_info['debitRate'];
    $countFeeT0 = $merchant_info['countFeeT0'];
    $futureMinAmount = "0";
    $debitCapAmount = "99999";
	
	$params = "bankAccountNo=".$bankAccountNo."&debitRate=".$debitRate."&handleType=".$handleType."&merchName=".$merchName."&orderId=".$orderId."&parent=".$parent."&phoneNo=".$phoneNo."&key=".$key;
	
	$sign = strtoupper(md5($params));
	
	$url = "http://222.76.210.177:9006/ChannelPay/merchBaseInfo/merchInterface";
	$data = "orderId=".$orderId.
			"&parent=".$parent.
			"&handleType=".$handleType.
			"&changeType=".$changeType.
			"&merchId=".$merchId.
			"&merchName=".$merchName.
			"&merchAbb=".$merchAbb.
			"&merchAddress=".$merchAddress.
			"&telNo=".$telNo.
			"&bankAccountName=".$bankAccountName.
			"&legalPersonIdcard=".$legalPersonIdcard.
			"&phoneNo=".$phoneNo.
			"&bankAccountNo=".$bankAccountNo.
			"&settBankName=".$settBankName.
			"&bankChannelNo=".$bankChannelNo.
			"&bankSubName=".$bankSubName.
			"&bankProvince=".$bankProvince.
			"&bankCity=".$bankCity.
			"&bankCode=".$bankCode.
			"&bankAbbr=".$bankAbbr.
			"&debitRate=".$debitRate.
			"&countFeeT0=".$countFeeT0.
			"&futureMinAmount=".$futureMinAmount.
			"&debitCapAmount=".$debitCapAmount.
			"&sign=".$sign;
	
	$result = $this->https_request($url,$data);
	
	 error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']银联缴费结算修改报文:'."\n".$data."\n\n", 3, './app/yinlian/request.log');
	 
	  error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']银联缴费结算修改报文:'."\n".var_export((json_decode($result,true)),true)."\n\n", 3, './app/yinlian/request.log');
	  $ee = json_decode($result,true);
	  
				
				}else if($koulv != $merchant_info['debitRate'] || $tixian_sxf != $merchant_info['countFeeT0']){
					
					 $orderId = "WS".date('YmdHis',time()).time().$arr['uid'];
    $parent = $pay_config['pay_no'];
    $key = $pay_config['pay_code'];
    $handleType = "M";
	$changeType = "M03";
	$merchId = $merchant_info['merchantNo'];
    $merchName = $merchant_info['merchName'];
    $merchAbb = $merchant_info['merchAbb'];
    $merchAddress = $merchant_info['merchAddress'];
    $telNo = $merchant_info['telNo'];
    $bankAccountName = $merchant_info['bankAccountName'];
    $legalPersonIdcard = $merchant_info['legalPersonIdcard'];
    $phoneNo = $merchant_info['phoneNo'];
    $bankAccountNo = $merchant_info['bankAccountNo'];
    $settBankName = $merchant_info['settBankName'];
    $bankChannelNo = $merchant_info['bankChannelNo'];
    $bankSubName = $merchant_info['bankSubName'];
    $bankProvince = $merchant_info['bankProvince'];
    $bankCity = $merchant_info['bankCity'];
    $bankCode = $merchant_info['bankCode'];
    $bankAbbr = $merchant_info['bankAbbr'];
    $debitRate = $koulv;
    $countFeeT0 = $tixian_sxf;
    $futureMinAmount = "0";
    $debitCapAmount = "99999";
	
	$params = "bankAccountNo=".$bankAccountNo."&debitRate=".$debitRate."&handleType=".$handleType."&merchName=".$merchName."&orderId=".$orderId."&parent=".$parent."&phoneNo=".$phoneNo."&key=".$key;
	
	$sign = strtoupper(md5($params));
	
	$url = "http://222.76.210.177:9006/ChannelPay/merchBaseInfo/merchInterface";
	$data = "orderId=".$orderId.
			"&parent=".$parent.
			"&handleType=".$handleType.
			"&changeType=".$changeType.
			"&merchId=".$merchId.
			"&merchName=".$merchName.
			"&merchAbb=".$merchAbb.
			"&merchAddress=".$merchAddress.
			"&telNo=".$telNo.
			"&bankAccountName=".$bankAccountName.
			"&legalPersonIdcard=".$legalPersonIdcard.
			"&phoneNo=".$phoneNo.
			"&bankAccountNo=".$bankAccountNo.
			"&settBankName=".$settBankName.
			"&bankChannelNo=".$bankChannelNo.
			"&bankSubName=".$bankSubName.
			"&bankProvince=".$bankProvince.
			"&bankCity=".$bankCity.
			"&bankCode=".$bankCode.
			"&bankAbbr=".$bankAbbr.
			"&debitRate=".$debitRate.
			"&countFeeT0=".$countFeeT0.
			"&futureMinAmount=".$futureMinAmount.
			"&debitCapAmount=".$debitCapAmount.
			"&sign=".$sign;
	
	$result = $this->https_request($url,$data);
	
	 error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']银联缴费费率修改报文:'."\n".$data."\n\n", 3, './app/yinlian/request.log');
	 
	  error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']银联缴费费率修改报文:'."\n".var_export((json_decode($result,true)),true)."\n\n", 3, './app/yinlian/request.log');
	  $ee = json_decode($result,true);
	  
					
					
					}else{
						echo "success";
						exit();
						}
				
				
			}else{
				
   // $user_merchant = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user_merchant` WHERE uid=".$arr['uid']." LIMIT 1");
//		
//		if($user_merchant){
//			
//			echo "success";
//			exit();
//			}
		
   // $orderId = "WS".date('YmdHis',time()).time().$arr['uid'];
//    $parent = "CSRZ0002";
//    $key = "5D7140EF5C46B157E050007F01002478";
//    $handleType = "A";
//	//$changeType = "M02";
//	//$merchId = "C20171220113855";
//    $merchName = "微刷";
//    $merchAbb = "微刷";
//    $merchAddress = "广东省广州市";
//    $telNo = "18553574543";
//    $bankAccountName = "邹智飞";
//    $legalPersonIdcard = "37028519871116295X";
//    $phoneNo = "18553574543";
//    $bankAccountNo = "6222021606015220091";
//    $settBankName = "中国工商银行";
//    $bankChannelNo = "102100099996";
//    $bankSubName = "中国工商银行";
//    $bankProvince = "广州市";
//    $bankCity = "广州市";
//    $bankCode = "102";
//    $bankAbbr = "ICBC";
//    $debitRate = "0.0035";
//    $countFeeT0 = "100";
//    $futureMinAmount = "0";
//    $debitCapAmount = "200";

    $orderId = "WS".date('YmdHis',time()).time().$data['uid'];
    $parent = $pay_config['pay_no'];
    $key = $pay_config['pay_code'];
    $handleType = "A";
    $merchName = $query_shiming['uname'];
    $merchAbb = $query_shiming['uname'];
    $merchAddress = "广东省广州市天河区华夏路";
    $telNo = $query_shiming['mobile'];
    $bankAccountName = $query_shiming['uname'];
    $legalPersonIdcard = $query_shiming['idcard'];
    $phoneNo = $query_shiming['mobile'];
    $bankAccountNo = $query_shiming['banksn'];
    $settBankName = $bankinfo['name'];
    $bankChannelNo = $bakcode['bankChannelNo'];
    $bankSubName = $bankinfo['name'];
    $bankProvince = "广东省";
    $bankCity = "广州市";
    $bankCode = $bakcode['code'];
    $bankAbbr = $bakcode['b_code'];
    $debitRate = $koulv;
    $countFeeT0 = $tixian_sxf;
    $futureMinAmount = "0";
    $debitCapAmount = "99999";
	
	$params = "bankAccountNo=".$bankAccountNo."&debitRate=".$debitRate."&handleType=".$handleType."&merchName=".$merchName."&orderId=".$orderId."&parent=".$parent."&phoneNo=".$phoneNo."&key=".$key;
	
	$sign = strtoupper(md5($params));
	
	$url = "http://222.76.210.177:9006/ChannelPay/merchBaseInfo/merchInterface";
	$data = "orderId=".$orderId.
			"&parent=".$parent.
			"&handleType=".$handleType.
			"&merchName=".$merchName.
			"&merchAbb=".$merchAbb.
			"&merchAddress=".$merchAddress.
			"&telNo=".$telNo.
			"&bankAccountName=".$bankAccountName.
			"&legalPersonIdcard=".$legalPersonIdcard.
			"&phoneNo=".$phoneNo.
			"&bankAccountNo=".$bankAccountNo.
			"&settBankName=".$settBankName.
			"&bankChannelNo=".$bankChannelNo.
			"&bankSubName=".$bankSubName.
			"&bankProvince=".$bankProvince.
			"&bankCity=".$bankCity.
			"&bankCode=".$bankCode.
			"&bankAbbr=".$bankAbbr.
			"&debitRate=".$debitRate.
			"&countFeeT0=".$countFeeT0.
			"&futureMinAmount=".$futureMinAmount.
			"&debitCapAmount=".$debitCapAmount.
			"&sign=".$sign;
	
	$result = $this->https_request($url,$data);
	
	 error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']银联缴费新增报文:'."\n".$data."\n\n", 3, './app/yinlian/request.log');
	 
	  error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']银联缴费新增修改报文:'."\n".var_export((json_decode($result,true)),true)."\n\n", 3, './app/yinlian/request.log');
	  $ee = json_decode($result,true);
		
			}
			
			
		if($ee['respCode'] == '0000'){
			if($user_merchant){
				
				
			$merchant['info'] = serialize(array(
	  'orderId' => $orderId,
      'parent' => $parent,
      'handleType' => $handleType,
      'merchName' => $merchName,
      'merchAbb' => $merchAbb,
      'merchAddress' => $merchAddress,
      'telNo' => $telNo,
      'bankAccountName' => $bankAccountName,
      'legalPersonIdcard' => $legalPersonIdcard,
      'phoneNo' => $phoneNo,
      'bankAccountNo' => $bankAccountNo,
      'settBankName' => $settBankName,
      'bankChannelNo' => $bankChannelNo,
      'bankSubName' => $bankSubName,
      'bankProvince' => $bankProvince,
      'bankCity' => $bankCity,
      'bankCode' => $bankCode,
      'bankAbbr' => $bankAbbr,
      'debitRate' => $debitRate,
      'countFeeT0' => $countFeeT0,
      'futureMinAmount' => $futureMinAmount,
      'debitCapAmount' => $debitCapAmount,
      'sign' => $sign,
	  'merchantNo' => $merchant_info['merchantNo'],
	  'merchantKey' => $merchant_info['merchantKey']
	));
	
	$this->App->update('user_merchant', $merchant,'uid',$arr['uid']);
	
			}else{
			$merchant['uid'] = $arr['uid'];
			$merchant['info'] = serialize(array(
	  'orderId' => $orderId,
      'parent' => $parent,
      'handleType' => $handleType,
      'merchName' => $merchName,
      'merchAbb' => $merchAbb,
      'merchAddress' => $merchAddress,
      'telNo' => $telNo,
      'bankAccountName' => $bankAccountName,
      'legalPersonIdcard' => $legalPersonIdcard,
      'phoneNo' => $phoneNo,
      'bankAccountNo' => $bankAccountNo,
      'settBankName' => $settBankName,
      'bankChannelNo' => $bankChannelNo,
      'bankSubName' => $bankSubName,
      'bankProvince' => $bankProvince,
      'bankCity' => $bankCity,
      'bankCode' => $bankCode,
      'bankAbbr' => $bankAbbr,
      'debitRate' => $debitRate,
      'countFeeT0' => $countFeeT0,
      'futureMinAmount' => $futureMinAmount,
      'debitCapAmount' => $debitCapAmount,
      'sign' => $sign,
	  'merchantNo' => $ee['data']['merchantNo'],
	  'merchantKey' => $ee['data']['merchantKey']
	));
	
	$this->App->insert('user_merchant', $merchant);
			}
			}
			
			if($ee['respCode'] == '0000'){
				echo "success";
				}else{
		echo $ee['respMsg'];
				}
		}
		
		
		function h5pay(){
			
		$uid = $this->Session->read('User.uid');
		//$client = $_SERVER['HTTP_USER_AGENT'];
//
//		//用php自带的函数strpos来检测是否是微信端
//		if (strpos($client , 'MicroMessenger') === false) {
//			die("请在微信端打开");
//			exit;
//		}


    $ORDER_ID = $_REQUEST['ORDER_ID'];//1
	$ORDER_AMT = $_REQUEST['ORDER_AMT'];
	$ORDER_TIME = $_REQUEST['ORDER_TIME'];//2
	$USER_ID = $_REQUEST['USER_ID'];
	$BUS_CODE = $_REQUEST['BUS_CODE'];//3
	$PAYCH_TIME = $_REQUEST['PAYCH_TIME'];//4
	$PAY_AMOUNT = $_REQUEST['PAY_AMOUNT'];//5
	$SIGN_TYPE =  $_REQUEST['SIGN_TYPE'];//6交易结果，1为成功，0未支付，2支付失败
	$RESP_CODE = $_REQUEST['RESP_CODE'];//7时间戳 (从1970年1月1日00：00：00至今的秒数，即当前的时间，需要转换为字符串形式)
	$RESP_DESC = urldecode($_REQUEST['RESP_DESC']);
	$SIGN = $_REQUEST['SIGN'];
   
	  

  	  $postdata = array (
        'ORDER_ID' => $ORDER_ID,
        'ORDER_AMT' => $ORDER_AMT, 
        'ORDER_TIME' => $ORDER_TIME, 
        'USER_ID' => $USER_ID, 
        'BUS_CODE' => $BUS_CODE,
	    'PAYCH_TIME' => $PAYCH_TIME,
	    'PAY_AMOUNT' => $PAY_AMOUNT,
		'SIGN_TYPE' => $SIGN_TYPE,
		'RESP_CODE' => $RESP_CODE,
		'RESP_DESC' => $RESP_DESC,
		'SIGN' => $SIGN
       );
	   

 error_log('['.date('Y-m-d H:i:s').']语句:'."\n". var_export($postdata,true)."\n\n", 3, './app/yinlian/pay_'.date('Y-m-d').'.log');


        $merchant = $this->App->findvar("SELECT info FROM `{$this->App->prefix() }user_merchant` WHERE uid=" . $uid . " LIMIT 1");
		
		
		$merchant = unserialize($merchant);
        $this->set('merchant', $merchant);
        $this->set('uid', $uid);
		$this->set('postdata', $postdata);
        $this->title('银联缴费 - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/yinlian_h5');
			
			}
		

		
		
		

}

?>