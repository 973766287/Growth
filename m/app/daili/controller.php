<?php

class DailiController extends Controller {

  private $user_name;
    private $merchant_id;
    private $private_key_pw;
    private $pfx_path;
    private $url;
    private $send_data;
    private $ret_data;

    private $msg_real_time = array(
        '0000' => array(3, '处理完成'),
        '0001' => array(2, '系统处理失败'),
        '0002' => array(2, '已撤销'),
        '1000' => array(2, '报文内容检查错或者处理错'), //具体内容见返回错误信息
        '1001' => array(2, '报文解释错'),
        '1002' => array(2, '无法查询到该交易，可以重发'),
        '2000' => array(3, '系统正在对数据处理'),
        '2007' => array(3, '提交银行处理'),
        '3028' => array(2, '系统繁忙'),
        '3045' => array(2, '协议未生效'), //例工行协议同步
        '3097' => array(2, '渠道不支持或者商户不支持此渠道'),
    );
    private $msg_query_head = array(
        '0000' => array(3, '处理完成'),
        '0001' => array(2, '系统处理失败'),
        '0002' => array(2, '已撤销'),
        '1000' => array(2, '报文内容检查错或者处理错'), //具体内容见返回错误信息
        '1001' => array(2, '报文解释错'),
        '1002' => array(2, '无法查询到该交易，可以重发'),
        '2000' => array(3, '系统正在对数据处理'),
        '2001' => array(3, '等待商户审核'),
        '2002' => array(2, '商户审核不通过'),
        '2003' => array(3, '等待高汇通受理'),
        '2004' => array(2, '高汇通不通过受理'),
        '2005' => array(3, '等待高汇通复核'),
        '2006' => array(2, '高汇通不通过复核'),
        '2007' => array(3, '提交银行处理'),
    );
    private $msg_query_detail = array(
        '0000' => array(1, '交易成功'),
        '3001' => array(2, '查开户方原因'),
        '3002' => array(2, '没收卡'),
        '3003' => array(2, '不予承兑'),
        '3004' => array(2, '无效卡号'),
        '3005' => array(2, '受卡方与安全保密部门联系'),
        '3006' => array(2, '已挂失卡'),
        '3007' => array(2, '被窃卡'),
        '3008' => array(2, '余额不足'),
        '3009' => array(2, '无此账户'),
        '3010' => array(2, '过期卡'),
        '3011' => array(2, '密码错'),
        '3012' => array(2, '不允许持卡人进行的交易'),
        '3013' => array(2, '超出提款限额'),
        '3014' => array(2, '原始金额不正确'),
        '3015' => array(2, '超出取款次数限制'),
        '3016' => array(2, '已挂失折'),
        '3017' => array(2, '账户已冻结'),
        '3018' => array(2, '已清户'),
        '3019' => array(2, '原交易已被取消或冲正'),
        '3020' => array(2, '账户被临时锁定'),
        '3021' => array(2, '未登折行数超限'),
        '3022' => array(2, '存折号码有误'),
        '3023' => array(2, '当日存入的金额当日不能支取'),
        '3024' => array(2, '日期切换正在处理'),
        '3025' => array(2, 'PIN格式出错'),
        '3026' => array(2, '发卡方保密子系统失败'),
        '3027' => array(2, '原始交易不成功'),
        '3028' => array(3, '系统忙，请稍后再提交'),
        '3029' => array(2, '交易已被冲正'),
        '3030' => array(2, '账号错误'),
        '3031' => array(2, '账号户名不符'),
        '3032' => array(2, '账号货币不符'),
        '3033' => array(2, '无此原交易'),
        '3034' => array(2, '非活期账号，或为旧账号'),
        '3035' => array(2, '找不到原记录'),
        '3036' => array(2, '货币错误'),
        '3037' => array(2, '磁卡未生效'),
        '3038' => array(2, '非通兑户'),
        '3039' => array(2, '账户已关户'),
        '3040' => array(2, '金额错误'),
        '3041' => array(2, '非存折户'),
        '3042' => array(2, '交易金额小于该储种的最低支取金额'),
        '3043' => array(2, '未与银行签约'),
        '3044' => array(2, '超时拒付'),
        '3045' => array(2, '合同（协议）号在协议库里不存在'),
        '3046' => array(2, '合同（协议）号还没有生效'),
        '3047' => array(2, '合同（协议）号已撤销'),
        '3048' => array(2, '业务已经清算，不能撤销'),
        '3049' => array(2, '业务已被拒绝，不能撤销'),
        '3050' => array(2, '业务已撤销'),
        '3051' => array(2, '重复业务'),
        '3052' => array(2, '找不到原业务'),
        '3053' => array(2, '批量回执包未到规定最短回执期限（M日）'),
        '3054' => array(2, '批量回执包超过规定最长回执期限（N日）'),
        '3055' => array(2, '当日通兑业务累计金额超过规定金额'),
        '3056' => array(2, '退票'),
        '3057' => array(2, '账户状态错误'),
        '3058' => array(2, '数字签名或证书错'),
        '3097' => array(2, '系统不能对该账号进行处理'),
        '3999' => array(3, '交易失败，具体信息见中文'), //对于不能明确归入上面的情况置为该反馈码
    );

    private $public_key = '-----BEGIN CERTIFICATE-----
MIICbTCCAdagAwIBAgIEaCTPmDANBgkqhkiG9w0BAQQFADB7MQswCQYDVQQGEwJD
TjELMAkGA1UECBMCU0MxCzAJBgNVBAcTAkNEMRIwEAYDVQQKDAnpq5jmsYfpgJox
DDAKBgNVBAsTA0dIVDEwMC4GA1UEAwwn5YyX5Lqs6auY5rGH6YCa5ZWG5Lia566h
55CG5pyJ6ZmQ5YWs5Y+4MB4XDTE1MTAyNTA4MDkxNVoXDTI1MTAyMjA4MDkxNVow
ezELMAkGA1UEBhMCQ04xCzAJBgNVBAgTAlNDMQswCQYDVQQHEwJDRDESMBAGA1UE
CgwJ6auY5rGH6YCaMQwwCgYDVQQLEwNHSFQxMDAuBgNVBAMMJ+WMl+S6rOmrmOax
h+mAmuWVhuS4mueuoeeQhuaciemZkOWFrOWPuDCBnzANBgkqhkiG9w0BAQEFAAOB
jQAwgYkCgYEAnqif/UWI3CNVNgcEqIyS4KAjcJk77tI52ESULhtn60PyjwpGqo07
KlzNorF6664Vk8fMU9+CvChObrAKiyY0Xz8FSUz5mlWCONL4c3aC8PIuQolb8t+a
9rbseEyVz81PPSt1uA4259Nhe3719MMYFGLFh6Uo/ocowAOc56iV1UcCAwEAATAN
BgkqhkiG9w0BAQQFAAOBgQAc7vm1STZcAbBMHIpyqVufmk3Pl2E9kcSD8PXWPQ/5
UcvfDulMxPRVoqJJYhmk82Xik3rPWMvpSHhs5SslA2JpH8+ZpLYbKOgISz8xWJRA
Odei02hDno7oiKzfsVImGYPeETmC+kL1THNqw4paEjDmka6TPlaaQFt0aHVgSkBr
DQ==
-----END CERTIFICATE-----';
	
    function __construct() {
        $this->css(array('jquery_dialog.css', 'user2015.css'));
        $this->js(array('jquery.json-1.3.js', 'jquery_dialog.js', 'common.js', 'user.js'));
        
        $this->user_name = '000000000101150';          //测试的用户名
        $this->merchant_id = '000000000101150';     //测试的商户号
        $this->private_key_pw = '123456';           //私钥密码
        $this->pfx_path = "000000000101150.pfx";     //测试的密钥文件路径
        $this->url = 'https://rps.gaohuitong.com:8443/d/merchant/';                //测试的接口地址
        // $this->checked_login();
        //$this->layout('defaultdaili');
    }



    public function pay($id)
    {
		  $sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE id = ".$id;
        $info = $this->App->findrow($sql);
     //file_put_contents('./0auto_info.txt',var_export($info,true));
       // error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']$info:'."\n".var_export($info, true)."\n\n", 3, './0auto_daifu_pay_request.log');
        $this->set_data($info, 'pay');
		
        $this->curl_access($this->url);
		
		
		 $xml = simplexml_load_string($this->ret_data); //创建 SimpleXML对象
	
		 
		 $INFO = $xml ->xpath( '/GHT/INFO' );
		 
		 
		 $INFO1 = $xml ->xpath( '/GHT/BODY/RET_DETAILS/RET_DETAIL' );
		
		 $xmls = $INFO[0];
		  $xmls1 = $INFO1[0];
	
		
		
   
    $RET_CODE = $xmls->RET_CODE;
    $ERR_MSG = $xmls->ERR_MSG;
    $REQ_SN = $xmls->REQ_SN;
    $SIGNED_MSG = $xmls->SIGNED_MSG;
	
	 $RET_DETAIL_SN = $xmls1->SN;
	 $RET_DETAIL_RET_CODE = $xmls1->RET_CODE;
	 $RET_DETAIL_ERR_MSG = $xmls1->ERR_MSG;


		$dd = array();
	//file_put_contents('./0auto_ght_dd.txt',var_export($dd,true));
		if($RET_CODE == "0000"){
			$dd['state'] = 1;
		}
		$dd['INFO_REQ_SN'] = $REQ_SN;
		$dd['INFO_RET_CODE'] = $RET_CODE;
		$dd['INFO_ERR_MSG'] = $ERR_MSG;
		$dd['RET_DETAILS_RET_CODE'] = $RET_DETAIL_RET_CODE;
	    $dd['RET_DETAILS_ERR_MSG'] = $RET_DETAIL_ERR_MSG;
		$this->App->update('user_drawmoney', $dd, 'id', $id);
        $this->verify_ret('pay');
        // error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']'."\n".var_export($dd, true)."\n\n", 3, './0auto_daifu_pay.log');
     //if($RET_CODE == "0000"){
		 return $dd;
		 //}
		 
    }

    public function query($data = array())
    {
		$id = $data['id'];
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE id = ".$id;
        $info = $this->App->findrow($sql);
		
        $this->set_data($info, 'query');
        $this->curl_access($this->url);
		
		  $xml_obj = @simplexml_load_string($this->ret_data);//创建 SimpleXML对象
		 
		  $err_msg = (string)$xml_obj->BODY->RET_DETAILS->RET_DETAIL->ERR_MSG;
		 
       $this->verify_ret('query');
	   
	   echo  $err_msg;
	   // $this->jump('/admin/user.php?type=drawmoney', -1, $err_msg);
	
    }

    /**
     * code的意义：
     * 1：支付成功
     * 2：支付失败
     * 3：结果不明确
     */
    private function verify_ret($type)
    {
        if (trim($this->ret_data) == '') {
            return 'code=3&msg=官方返回为空';
        }

        $xml_obj = @simplexml_load_string($this->ret_data);
        if (empty($xml_obj->INFO)) {
            return 'code=3&msg=官方返回格式错误';
        }
        $info = (array)$xml_obj->INFO;
        error_log('['.date('Y-m-d H:i:s').']$xml_obj:'."\n".iconv('UTF-8', 'GBK', var_export($xml_obj, true))."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');

        //校验签名
        $sign_data = preg_replace('/<SIGNED_MSG>(.+)<\/SIGNED_MSG>/', '', $this->ret_data);
        preg_match('/<SIGNED_MSG>(.+)<\/SIGNED_MSG>/', $this->ret_data, $match);
        $verify_result = $this->verify_sign($sign_data, $match[1]);
        if ($verify_result !== 1) {
            return 'code=3&msg=签名校验错误';
        }

        //处理返回数据
        $result = 'code=3&msg=未知结果';
        if ($type == 'pay') {
            if ($info['RET_CODE'] == '0000') {
                $ret_code = (string)$xml_obj->BODY->RET_DETAILS->RET_DETAIL->RET_CODE;
                if ($ret_code == '0000') {
                    $result = 'code=1&msg=交易成功';
                } elseif (isset($this->msg_real_time[$ret_code])) {
                    $result = 'code='.$this->msg_real_time[$ret_code][0].'&msg='.$this->msg_real_time[$ret_code][1];
                } else {
                    $err_msg = (string)$xml_obj->BODY->RET_DETAILS->RET_DETAIL->ERR_MSG;
                    $err_msg = iconv('UTF-8', 'GBK', $err_msg);
                    $result = 'code=3&msg='.$err_msg;
                }
            } elseif (isset($this->msg_real_time[$info['RET_CODE']])) {
                $result = 'code='.$this->msg_real_time[$info['RET_CODE']][0].'&msg='.$this->msg_real_time[$info['RET_CODE']][1];
            } else {
                $result = 'code=3&msg=请求已接收，结果需通过查询交易接口获取';
            }
        } elseif ($type == 'query') {
            if ($info['RET_CODE'] == '0000') {
                $ret_code = (string)$xml_obj->BODY->RET_DETAILS->RET_DETAIL->RET_CODE;

                if ($ret_code == '3999') {
                    $err_msg = (string)$xml_obj->BODY->RET_DETAILS->RET_DETAIL->ERR_MSG;
                    if ($err_msg) {
                        $err_msg = iconv('UTF-8', 'GBK', $err_msg);
                        $result = 'code=3&msg='.$err_msg;
                    } else {
                        $result = 'code=3&msg='.$this->msg_query_detail[$ret_code][1];
                    }
                } elseif (isset($this->msg_query_detail[$ret_code])) {
                    $result = 'code='.$this->msg_query_detail[$ret_code][0].'&msg='.$this->msg_query_detail[$ret_code][1];
                }
            } elseif (isset($this->msg_query_head[$info['RET_CODE']])) {
                $result = 'code='.$this->msg_query_head[$info['RET_CODE']][0].'&msg='.$this->msg_query_head[$info['RET_CODE']][1];
            }
        }

        return $result;
    }

    private function set_data($info, $type = 'pay')
    {
        $xml = '';
        if ($type == 'pay') {
            $xml = '<GHT>
                    <INFO>
                        <TRX_CODE>100005</TRX_CODE>
                        <VERSION>04</VERSION>
                        <DATA_TYPE>2</DATA_TYPE>
                        <LEVEL>0</LEVEL>
                        <USER_NAME>'.$this->user_name.'</USER_NAME>
                        <REQ_SN>rrd'.$info['order_sn'].'</REQ_SN>
                        <SIGNED_MSG></SIGNED_MSG>
                    </INFO>
                    <BODY>
                    <TRANS_SUM>
                        <BUSINESS_CODE>00800</BUSINESS_CODE>
                        <MERCHANT_ID>'.$this->merchant_id.'</MERCHANT_ID>
                        <SUBMIT_TIME>'.date('YmdHis').'</SUBMIT_TIME>
                        <TOTAL_ITEM>1</TOTAL_ITEM>
                        <TOTAL_SUM>'.($info['amount']*100).'</TOTAL_SUM>
                    </TRANS_SUM>
                    <TRANS_DETAILS>
                        <TRANS_DETAIL>
                            <SN>0001</SN>
                            <BANK_CODE>102</BANK_CODE>
                            <ACCOUNT_TYPE>00</ACCOUNT_TYPE>
                            <ACCOUNT_NO>'.$info['account_no'].'</ACCOUNT_NO>
                            <ACCOUNT_NAME>'.iconv('utf-8','gbk',$info['account_name']).'</ACCOUNT_NAME>
                            <ACCOUNT_PROP>0</ACCOUNT_PROP>
                            <AMOUNT>'.($info['amount']*100).'</AMOUNT>
                            <CURRENCY>CNY</CURRENCY>
                        </TRANS_DETAIL>
                    </TRANS_DETAILS>
                    </BODY>
                </GHT>';
        } elseif ($type == 'query') {
            $xml = '<GHT>
                    <INFO>
                        <TRX_CODE>200001</TRX_CODE>
                        <VERSION>04</VERSION>
                        <DATA_TYPE>2</DATA_TYPE>
                        <REQ_SN>'.date('Ymd',time()).$uid.time().'</REQ_SN>
                        <USER_NAME>'.$this->user_name.'</USER_NAME>
                        <SIGNED_MSG></SIGNED_MSG>
                    </INFO>
                    <BODY>
                        <QUERY_TRANS>
                            <QUERY_SN>'.$info['INFO_REQ_SN'].'</QUERY_SN>
                        </QUERY_TRANS>
                    </BODY>
                </GHT>';
        }

        $xml = str_replace(array(' ', "\n", "\r"), '', $xml);
        $xml = '<?xml version="1.0" encoding="GBK"?>'.$xml;

        $sign_data = str_replace('<SIGNED_MSG></SIGNED_MSG>', '', $xml);
		
        $sign = $this->create_sign($sign_data);
	
		
        $xml = str_replace('<SIGNED_MSG></SIGNED_MSG>', '<SIGNED_MSG>'.$sign.'</SIGNED_MSG>', $xml);

        error_log('['.date('Y-m-d H:i:s').']$xml:'."\n".$xml."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');
        $this->send_data = $xml;
		
		
		
			

    }

    private function create_sign($data)
    {
        $data = iconv('GBK', 'UTF-8', $data); //高汇通那边计算签名是用UFT-8编码
        $pkey_content = file_get_contents($this->pfx_path); //获取密钥文件内容
		
		
        openssl_pkcs12_read($pkey_content, $certs, $this->private_key_pw); //读取公钥、私钥
        $pkey = $certs['pkey']; //私钥

        openssl_sign($data, $signMsg, $pkey, OPENSSL_ALGO_SHA1); //注册生成加密信息
        $signMsg = bin2hex($signMsg);
        return $signMsg;
    }

    private function verify_sign($data, $sign) {
        $data = iconv('GBK', 'UTF-8', $data); //高汇通那边计算签名是用UFT-8编码
        $sign = $this->HexToString($sign);

        $public_key_id = openssl_pkey_get_public($this->public_key);
        $res = openssl_verify($data, $sign, $public_key_id);   //验证结果，1：验证成功，0：验证失败

        error_log('['.date('Y-m-d H:i:s').']签名验证结果$res:'."\n".$res."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');
        return $res;
    }

    private function curl_access($url)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_TIMEOUT,60);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$this->send_data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        if (strpos($url, 'https') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);    //高汇通那边的版本
        }

        $ret_data = trim(curl_exec($ch));

        error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".$ret_data."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');
        error_log('['.date('Y-m-d H:i:s').']curl_errno:'."\n".curl_errno($ch)."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');
        error_log('['.date('Y-m-d H:i:s').']curl_error:'."\n".curl_error($ch)."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');
        error_log('['.date('Y-m-d H:i:s').']curl_getinfo:'."\n".var_export(curl_getinfo($ch), true)."\n\n", 3, './app/daifu/pay_request'.date('Y-m-d').'.log');

        curl_close($ch);

        $this->ret_data = $ret_data;
    }

    private function HexToString($s){
        $r = "";
        for($i=0; $i<strlen($s); $i+=2){
            $r .= chr(hexdec('0x'.$s{$i}.$s{$i+1}));
        }
        return $r;
    }
    
    //修改密码
    function update_user_pass($data = array()) {
        $newpass = $data['pass'];
        $datas['password'] = $data['newpass'];
        $rp_pass = $data['rpnewpass'];
        $uid = $this->Session->read('User.uid');
        if (!empty($newpass)) {
            if (empty($datas['password'])) {
                echo '请输入新密码';
                exit;
            }

            if (!empty($rp_pass) && $datas['password'] == $rp_pass) {
                $datas['password'] = md5(trim($datas['password']));
                if (md5($newpass) == $datas['password']) {
                    echo '新密码跟旧密码不能相同';
                }

                $newpass = md5(trim($newpass));
                $sql = "SELECT password FROM `{$this->App->prefix()}user` WHERE password='$newpass' AND user_id='$uid'";
                $newrt = $this->App->findvar($sql);
                if (empty($newrt)) {
                    echo '您的原始密码错误';
                    exit;
                }

                if ($this->App->update('user', $datas, 'user_id', $uid)) {
                    echo '密码修改成功';
                    exit;
                } else {
                    echo '密码修改失败';
                    exit;
                }
            } else {
                echo '密码与确认密码不一致';
                exit;
            }
        } else {
            echo '请输入原始密码';
            exit;
        }
    }

    //修改提款密码
    function update_user_passpay($data = array()) {
        $newpass = $data['pass']; //原始密码
        $datas['pass'] = $data['newpass'];
        $rp_pass = $data['rpnewpass'];
        $uid = $this->Session->read('User.uid');
        //if(!empty($newpass)){
        if (empty($datas['pass'])) {
            echo '请输入新密码';
            exit;
        }

        if (!empty($rp_pass) && $datas['pass'] == $rp_pass) {
            $datas['pass'] = md5(trim($datas['pass'])); //新密码
            $newpass = md5(trim($newpass)); //原始密码

            $sql = "SELECT pass,id FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
            $pt = $this->App->findrow($sql);
            $pass = isset($pt['pass']) ? $pt['pass'] : '';
            $id = isset($pt['id']) ? $pt['id'] : '0';
            if (!empty($pass)) {
                if ($newpass == $datas['pass']) {
                    echo '新密码跟旧密码不能相同';
                }

                if ($pass != $newpass) {
                    echo '您的原始密码错误';
                    exit;
                }
            }

            if ($id > 0) {
                if ($this->App->update('user_bank', $datas, 'id', $id)) {
                    echo '密码修改成功';
                    exit;
                } else {
                    echo '密码修改失败';
                    exit;
                }
            } else {
                $datas['uptime'] = mktime();
                $datas['uid'] = $uid;
                if ($this->App->insert('user_bank', $datas)) {
                    echo '密码修改成功';
                    exit;
                } else {
                    echo '密码修改失败';
                    exit;
                }
            }
        } else {
            echo '密码与确认密码不一致';
            exit;
        }
        unset($data, $datas);
        //}
    }

    //修改提款信息
    function update_user_bank($data = array()) {
		
		
						  
        //$newpass = $data['pass'];
		 $mobile = $data['mobile'];
		 $yz_code = $data['yz_code'];
		 $uname = $data['name'];
		 $idcard = $data['card_no'];
		 
		 $card_front_img = $data['card_front_img'];
		 $card_back_img = $data['card_back_img'];
		 
		 $banksn = $data['bank_no'];
         $bank = $data['bank'];
       
		 
		
		 $card_hand_img = $data['card_hand_img'];
       
        
       
        $uid = $this->Session->read('User.uid');
	 $verfiy_yz_code = $this->Session->read('User.yz_code');

        if ((empty($bank) || empty($uname) || empty($banksn)) && empty($idcard)) {
            echo '请输入完整信息';
            exit;
        }
       if($yz_code  != $verfiy_yz_code){
       echo '手机验证码填写错误,请重新填写';
       exit;
       }
        $dd = array();
		
		 $dd['mobile'] = $mobile;
		   $dd['uname'] = $uname;
		     $dd['idcard'] = $idcard;
			 
			
			 
			
           //   $dd['card_front_img'] = $card_front_img;
	
//			  $dd['card_back_img'] = $card_back_img;
			  
			    $dd['banksn'] = $banksn;
				$dd['bank'] = $bank;
			
                $dd['uptime'] = mktime();
				$dd['status'] = 2;
			
			//	$dd['bank_front_img'] = $this->download($this->action('common', '_get_access_token'),$bank_front_img,"bank_front_img");
				
			

           //   $dd['bank_front_img'] = $bank_front_img;
//				
//			  $dd['card_hand_img'] = $card_hand_img;

        $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$uid." LIMIT 1";
        $info = $this->App->findrow($sql);
	
		
		if($card_front_img != $info['card_front_img'] || empty($info['card_front_img'])){
		 $dd['card_front_img'] = $this->download($this->action('common', '_get_access_token'),$card_front_img,"card_front_img");
		 
		 if(empty($dd['card_front_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($card_back_img != $info['card_back_img'] || empty($info['card_back_img'])){
			  $dd['card_back_img'] = $this->download($this->action('common', '_get_access_token'),$card_back_img,"card_back_img");
			  
			   if(empty($dd['card_back_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($card_hand_img != $info['card_hand_img'] || empty($info['card_hand_img'])){
			    $dd['card_hand_img'] = $this->download($this->action('common', '_get_access_token'),$card_hand_img,"card_hand_img");
				 if(empty($dd['card_hand_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		
		
        if ($info['id'] > 0) { //修改
            if ($this->App->update('user_bank', $dd, 'id', $info['id'])) {
                echo '提交成功';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
        } else {
            $dd['uid'] = $uid;
			 $dd['yijian'] = "";
            if ($this->App->insert('user_bank', $dd)) {
                echo '提交成功';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
        }
    }
	
	
	
	 //商家申请
    function update_user_shop($data = array()) {
		
		
	     $s_zz = $data['s_zz'];			  
        //$newpass = $data['pass'];
		 $s_name = $data['s_name'];
		 $s_hangye = $data['s_hangye'];
		 $s_address = $data['s_address'];
		
		$s_y_zhizhao_img = $data['s_y_zhizhao_img']?$data['s_y_zhizhao_img']:"";
		$s_y_mentou_img = $data['s_y_mentou_img']?$data['s_y_mentou_img']:"";
		$s_y_neijing_img = $data['s_y_neijing_img']?$data['s_y_neijing_img']:"";
		$s_y_card_front_img = $data['s_y_card_front_img']?$data['s_y_card_front_img']:"";
		$s_y_card_back_img = $data['s_y_card_back_img']?$data['s_y_card_back_img']:"";
		
		
		$s_n_card_front_img = $data['s_n_card_front_img']?$data['s_n_card_front_img']:"";
		$s_n_card_back_img = $data['s_n_card_back_img']?$data['s_n_card_back_img']:"";
		$s_n_mentou_img = $data['s_n_mentou_img']?$data['s_n_mentou_img']:"";
		$s_n_neijing_img = $data['s_n_neijing_img']?$data['s_n_neijing_img']:"";
		$s_n_card_hand_img = $data['s_n_card_hand_img']?$data['s_n_card_hand_img']:"";
	
		
		
        $uid = $this->Session->read('User.uid');
	
        $dd = array();
		$dd['s_zz'] = $s_zz;
		 $dd['s_name'] = $s_name;
		   $dd['s_hangye'] = $s_hangye;
		     $dd['s_address'] = $s_address;
			  $dd['uptime'] = mktime();
				$dd['status'] = 2;
		//$dd['s_y_zhizhao_img'] = $data['s_y_zhizhao_img']?$data['s_y_zhizhao_img']:"";
//		$dd['s_y_mentou_img'] = $data['s_y_mentou_img']?$data['s_y_mentou_img']:"";
//		$dd['s_y_neijing_img'] = $data['s_y_neijing_img']?$data['s_y_neijing_img']:"";
//		$dd['s_y_card_front_img'] = $data['s_y_card_front_img']?$data['s_y_card_front_img']:"";
//		$dd['s_y_card_back_img'] = $data['s_y_card_back_img']?$data['s_y_card_back_img']:"";
//			
//        
//		$dd['s_n_card_front_img'] = $data['s_n_card_front_img']?$data['s_n_card_front_img']:"";
//		$dd['s_n_card_back_img'] = $data['s_n_card_back_img']?$data['s_n_card_back_img']:"";
//		$dd['s_n_mentou_img'] = $data['s_n_mentou_img']?$data['s_n_mentou_img']:"";
//		$dd['s_n_neijing_img'] = $data['s_n_neijing_img']?$data['s_n_neijing_img']:"";
//		$dd['s_n_card_hand_img'] = $data['s_n_card_hand_img']?$data['s_n_card_hand_img']:"";
			
			
              
	

        $sql = "SELECT * FROM `{$this->App->prefix()}user_shop` WHERE uid=".$uid." LIMIT 1";
        $info = $this->App->findrow($sql);
		
		if($info){
			
			//如果有营业执照
   if($s_zz == 1){
			
		if($s_y_zhizhao_img != $info['s_y_zhizhao_img'] || empty($info['s_y_zhizhao_img'])){
		 $dd['s_y_zhizhao_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_zhizhao_img,"s_y_zhizhao_img");
		 
		 if(empty($dd['s_y_zhizhao_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($s_y_mentou_img != $info['s_y_mentou_img'] || empty($info['s_y_mentou_img'])){
			  $dd['s_y_mentou_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_mentou_img,"s_y_mentou_img");
			  
			   if(empty($dd['s_y_mentou_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($s_y_neijing_img != $info['s_y_neijing_img'] || empty($info['s_y_neijing_img'])){
			    $dd['s_y_neijing_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_neijing_img,"s_y_neijing_img");
				 if(empty($dd['s_y_neijing_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		
		if($s_y_card_front_img != $info['s_y_card_front_img'] || empty($info['s_y_card_front_img'])){
			    $dd['s_y_card_front_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_card_front_img,"s_y_card_front_img");
				 if(empty($dd['s_y_card_front_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($s_y_card_back_img != $info['s_y_card_back_img'] || empty($info['s_y_card_back_img'])){
			    $dd['s_y_card_back_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_card_back_img,"s_y_card_back_img");
				 if(empty($dd['s_y_card_back_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
  }
		
		
		//如果没有营业执照
			if($s_zz == 0){
		if($s_n_card_front_img != $info['s_n_card_front_img'] || empty($info['s_n_card_front_img'])){
		 $dd['s_n_card_front_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_card_front_img,"s_n_card_front_img");
		 
		 if(empty($dd['s_n_card_front_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($s_n_card_back_img != $info['s_n_card_back_img'] || empty($info['s_n_card_back_img'])){
			  $dd['s_n_card_back_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_card_back_img,"s_n_card_back_img");
			  
			   if(empty($dd['s_n_card_back_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($s_n_mentou_img != $info['s_n_mentou_img'] || empty($info['s_n_mentou_img'])){
			    $dd['s_n_mentou_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_mentou_img,"s_n_mentou_img");
				 if(empty($dd['s_n_mentou_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		
		if($s_n_neijing_img != $info['s_n_neijing_img'] || empty($info['s_n_neijing_img'])){
			    $dd['s_n_neijing_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_neijing_img,"s_n_neijing_img");
				 if(empty($dd['s_n_neijing_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		if($s_n_card_hand_img != $info['s_n_card_hand_img'] || empty($info['s_n_card_hand_img'])){
			    $dd['s_n_card_hand_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_card_hand_img,"s_n_card_hand_img");
				 if(empty($dd['s_n_card_hand_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		}
		}
	
			
			}else{
				
				//如果有营业执照
   if($s_zz == 1){
		      $dd['s_y_zhizhao_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_zhizhao_img,"s_y_zhizhao_img");
		       if(empty($dd['s_y_zhizhao_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			  $dd['s_y_mentou_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_mentou_img,"s_y_mentou_img");
			  
			   if(empty($dd['s_y_mentou_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			  $dd['s_y_neijing_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_neijing_img,"s_y_neijing_img");
				 if(empty($dd['s_y_neijing_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			 $dd['s_y_card_front_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_card_front_img,"s_y_card_front_img");
				 if(empty($dd['s_y_card_front_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			    $dd['s_y_card_back_img'] = $this->download($this->action('common', '_get_access_token'),$s_y_card_back_img,"s_y_card_back_img");
				 if(empty($dd['s_y_card_back_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
		
  }
  
  
  
  //如果没有营业执照
	if($s_zz == 0){
	
		    $dd['s_n_card_front_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_card_front_img,"s_n_card_front_img");
		      if(empty($dd['s_n_card_front_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			  $dd['s_n_card_back_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_card_back_img,"s_n_card_back_img");
			   if(empty($dd['s_n_card_back_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			    $dd['s_n_mentou_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_mentou_img,"s_n_mentou_img");
				 if(empty($dd['s_n_mentou_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			    $dd['s_n_neijing_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_neijing_img,"s_n_neijing_img");
				 if(empty($dd['s_n_neijing_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			    $dd['s_n_card_hand_img'] = $this->download($this->action('common', '_get_access_token'),$s_n_card_hand_img,"s_n_card_hand_img");
				 if(empty($dd['s_n_card_hand_img'])){
			  echo '上传照片失败！请重新上传';
                exit;
			 }
			 
			 
		}
			
            
				
				
				}
	
	
		
		
		
		
        if ($info['id'] > 0) { //修改
            if ($this->App->update('user_shop', $dd, 'id', $info['id'])) {
                echo '成功提交申请，请等待审核';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
        } else {
            $dd['uid'] = $uid;
			 $dd['beizhu'] = "";
            if ($this->App->insert('user_shop', $dd)) {
                echo '成功提交申请，请等待审核';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
        }
    }
	
	
	
	function download($a="",$m="",$c=""){
		
		
		  $yuming = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
          if(!empty($yuming)) $yuming = $yuming.'/';
          $save_path = SYS_PATH.'photos/'.$yuming.'renzheng/'.date('Ymd')."/";
	      $pic_path = 'photos/'.$yuming.'renzheng/'.date('Ymd')."/";

	if(!@is_dir($save_path))@mkdir($save_path,0777);

		  // 要存在你服务器哪个位置？
		  $targetName = $c.date('YmdHis').".jpg";
		  $pic = $save_path.$targetName;
		  
		  $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$a."&media_id=".$m;
		  $ch = curl_init($url);
		  $fp = fopen($pic, 'wb');
		  curl_setopt($ch, CURLOPT_FILE, $fp);
		  curl_setopt($ch, CURLOPT_HEADER, 0);
		  curl_exec($ch);
		 // curl_close($ch);
//		  fclose($fp);
         return $pic_path.$targetName;
		
		}

    function ajax_open_dailiapply($data = array()) {
        $var = $data['ty'];
        $uid = $this->Session->read('User.uid');
        $this->App->update('user', array('is_dailiapply' => $var), 'user_id', $uid);
    }

    function is_daili() {
        /* $uid = $this->Session->read('User.uid');
          $rank = $this->Session->read('User.rank');
          if($rank=='1'){
          //判断级别
          $sql = "SELECT user_rank,is_salesmen FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
          $rls = $this->App->findrow($sql);
          $rank = isset($rls['user_rank']) ? $rls['user_rank'] : '1';
          $is_apply = isset($rls['is_salesmen']) ? $rls['is_salesmen'] : '1';
          if($rank=='1' || $is_apply =='1'){
          $this->jump(ADMIN_URL.'user.php',0,'您没有权限访问'); exit;
          }
          $this->Session->write('User.rank',$rank);
          } */
    }
	
	 function checked_instead_login() {
	    $uid = $this->Session->read('User.uid');
        $iuid = $this->Session->read('User.iuid');
        if (!($uid > 0) && !($iuid > 0)) {
            $this->jump(ADMIN_URL . 'user.php?act=login_instead');
            exit;
        }else{
			if($uid > 0){
				$uid = $uid;
				}
				if($iuid > 0){
				$uid = $iuid;
				}
			
			}
        return $uid;
    }
	

    function checked_login() {
        $uid = $this->Session->read('User.uid');
        if (!($uid > 0)) {
            $this->jump(ADMIN_URL . 'user.php?act=login');
            exit;
        }
        return;
    }

    //
    function ajax_moneyrank_page($rts = array()) {
        $hh = $rts['hh'];
        $tops = $rts['tops'];
        $tops = intval($tops);
        if (($tops - $hh) >= 0) {
            $page = ceil($tops / $hh);
            if ($page > 1)
                $page = $page - 1;
            $list = 30;
            $start = $page * $list;

            $sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";
            $ulist = $this->App->find($sql);

            $this->set('ulist', $ulist);
            $this->set('pagec', $page * $list);
            echo $this->fetch('load_zmoney', true);
        }
        echo "";
        exit;
    }

    //代理公告
    function gonggao() {
        $this->checked_login();
        $this->action('common', 'checkjump');
        $sql = "SELECT cat_name FROM `{$this->App->prefix()}wx_cate` WHERE cat_id='2' LIMIT 1";
        $cat_name = $this->App->findvar($sql);
        $this->title($cat_name);
        $this->is_daili();
        $sql = "SELECT article_title, addtime , article_id FROM `{$this->App->prefix()}wx_article` WHERE cat_id='2' AND is_show='1' ORDER BY vieworder ASC,article_id DESC LIMIT 10";
        $rt = $this->App->find($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', $cat_name);
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_gonggao');
    }

    function gonggaoinfo() {
        $this->checked_login();
        $this->action('common', 'checkjump');

        $this->is_daili();
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if (!($id > 0)) {
            $this->jump(ADMIN_URL . 'daili.php?act=gonggao');
            exit;
        }
        $sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='$id' AND is_show='1' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            $this->jump(ADMIN_URL . 'daili.php?act=gonggao');
            exit;
        }

        $this->title($rt['article_title']);
        if (!defined(NAVNAME))
            define('NAVNAME', $rt['article_title']);
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_gonggaoinfo');
    }

    //客户订单
    function kehuorder() {
        $this->checked_login();
        $this->title("客户订单" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $page = 1;
        $list = 5;
        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE daili_uid='$uid'";
        $tt = $this->App->findvar($sql);
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE daili_uid='$uid' ORDER BY order_id DESC LIMIT $start,$list";
        $lists = $this->App->find($sql);
        $rt['lists'] = array();
        if (!empty($lists))
            foreach ($lists as $k => $row) {
                $rt['lists'][$k] = $row;
                $oid = $row['order_id'];
                $rt['lists'][$k]['gimg'] = $this->App->findcol("SELECT goods_thumb FROM `{$this->App->prefix()}goods_order_daigou` WHERE order_id='$oid'");
                $rt['lists'][$k]['status'] = $this->get_status($row['order_status'], $row['pay_status'], $row['shipping_status']);
                $rt['lists'][$k]['op'] = $this->get_option($row['order_id'], $row['order_status'], $row['pay_status'], $row['shipping_status']);
            }

        if (!defined(NAVNAME))
            define('NAVNAME', "客户订单");
        $this->set('rt', $rt);
        $this->template('v2_kehuorder');
    }

    //订单详情
    function kehuorderinfo() {
        $this->checked_login();
        $orderid = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

        $this->title("欢迎进入用户后台管理中心" . ' - 订单详情 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');

        $sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id='$orderid' AND daili_uid='$uid'";
        $rt['orderinfo'] = $this->App->findrow($sql);
        if (empty($rt['orderinfo'])) {
            $this->jump(ADMIN_URL . 'daili.php?act=kehuorder');
            exit;
        }
        $sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix()}goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$orderid' GROUP BY tb2.rec_id";
        $goodslist = $this->App->find($sql);
        if (!empty($goodslist))
            foreach ($goodslist as $k => $row) {
                $rt['goodslist'][$k] = $row;
                $rec_id = $row['rec_id'];
                $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_address` AS tb1";
                $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
                $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
                $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
                $sql .=" WHERE tb1.rec_id='$rec_id'";
                $rt['goodslist'][$k]['ress'] = $this->App->find($sql);
            }

        $status = $this->get_status($rt['orderinfo']['order_status'], $rt['orderinfo']['pay_status'], $rt['orderinfo']['shipping_status']);
        $rt['status'] = explode(',', $status);

        if (!defined(NAVNAME))
            define('NAVNAME', "订单详情");
        $this->set('rt', $rt);
        $this->template('v2_kehuorderinfo');
    }

    function moneyrank() {
        $this->checked_login();
        $this->title("富豪榜" . ' - ' . $GLOBALS['LANG']['site_name']);

        $uid = $this->Session->read('User.uid');

        $list = 30;
        $page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;
        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE active='1' AND user_id!='2' ORDER BY points_ucount DESC";
        //$sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY points_ucount DESC";
        $tt = $this->App->findvar($sql);
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix()}user` WHERE active='1' AND user_id!='2' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";
        //$sql = "SELECT nickname,headimgurl,is_subscribe,points_ucount,money_ucount,share_ucount,guanzhu_ucount,reg_time,subscribe_time,is_subscribe FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT $start,$list";
        $rt['ulist'] = $this->App->find($sql);

        $sql = "SELECT points_ucount,money_ucount,share_ucount,guanzhu_ucount FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);

        //当前排名
        $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE active='1' AND user_id!='2' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT 100";
        //$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE active='1' ORDER BY money_ucount DESC,share_ucount DESC,reg_time ASC LIMIT 100";
        $ulist = $this->App->findcol($sql);
        $rt['userinfo']['thisrank'] = 0;
        if (!empty($ulist))
            foreach ($ulist as $ks => $vv) {
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

        if (!defined(NAVNAME))
            define('NAVNAME', "佣金榜");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_moneyrank');
    }

    function v2_mymoney() {
        $this->checked_login();
        $uid = $this->Session->read('User.uid');
        if (!defined(NAVNAME))
            define('NAVNAME', "我的佣金");
        //未有付款佣金
        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.pay_status='0' AND tb2.order_status!='4' AND tb2.order_status!='1' AND tb1.money > 0 LIMIT 1";
        $rt['pay1'] = $this->App->findvar($sql);

        //已经付款佣金
        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.pay_status='1' AND tb1.money > 0 LIMIT 1";
        $rt['pay2'] = $this->App->findvar($sql);

        //已经收货订单佣金
        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.shipping_status='5' AND tb1.money > 0 LIMIT 1";
        $rt['pay3'] = $this->App->findvar($sql);

        //已经取消作废佣金
        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND (tb2.order_status='1' OR tb2.pay_status='2') AND tb1.money > 0 LIMIT 1";
        $rt['pay4'] = $this->App->findvar($sql);

        //审核通过的佣金
        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn WHERE tb1.uid = '$uid' AND tb2.shipping_status='5' AND tb2.pay_status='1' AND tb1.money > 0 LIMIT 1";
        $rt['pay5'] = $this->App->findvar($sql);

        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_mymoney');
    }

    function mydata() {
        $this->checked_login();
        if (!defined(NAVNAME))
            define('NAVNAME', "我的推广");
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);

        //订单数量
        $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE parent_uid = '$uid' AND order_status='2' AND user_id!='$uid'";
        $rt['userinfo']['ordercount'] = $this->App->findvar($sql);

        $sql = "SELECT COUNT(ut.uid) FROM `{$this->App->prefix()}user_tuijian` AS ut LEFT JOIN `{$this->App->prefix()}user` AS u ON ut.uid = u.user_id WHERE ut.parent_uid = '$uid' AND u.user_rank!='1' AND ut.uid!='$uid'";
        $rt['userinfo']['fxcount'] = $this->App->findvar($sql);

        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_mydata');
    }

    function my_is_daili() {
        $this->checked_login();
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT tb1.*,tbl.level_name,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tbl.lid = tb2.user_rank";
        $sql .=" WHERE tb1.parent_uid = '$uid' AND tb2.user_rank!='1' AND tb1.uid!='$uid' ORDER BY tb2.user_id ASC";
        $rt['lists'] = $this->App->find($sql);
        if (!defined(NAVNAME))
            define('NAVNAME', "成为分销");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_myuser');
    }

    function monrydeial() {
        //2018/03/12
        $this->checked_login();
        $this->title("资金变动明细" . ' - ' . $GLOBALS['LANG']['site_name']);
        //删除
        $id = isset($_GET['id']) ? $_GET['id'] : '0';
        if ($id > 0) {
            $this->App->delete('user_money_change', 'cid', $id);
            $this->jump(ADMIN_URL . 'daili.php?act=monrydeial');
            exit;
        }

        $uid = $this->Session->read('User.uid');
        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
        $rt['zmoney'] = $this->App->findvar($sql);
        //分页
        $page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 10; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        //$sql = "SELECT tb1.*,tb2.nickname,tb2.headimgurl FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.buyuid = tb2.user_id WHERE tb1.uid='$uid' ORDER BY tb1.time DESC LIMIT $start,$list";
        $sql = "SELECT tb1.*,tb3.nickname,tb3.headimgurl FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.buyuid = tb3.user_id WHERE tb1.uid='$uid' ORDER BY tb1.time DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql); //商品列表
        $rt['page'] = $page;

        if (!defined(NAVNAME))
            define('NAVNAME', "收入明细");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_monrydeial');
    }

    function _return_statue_where($id = "") {
        if (empty($id))
            return "";
        switch ($id) {
            case 'weifu':
                return "tb2.pay_status='0' AND tb2.order_status!='4' AND tb2.order_status!='1'";
                break;
            case 'yifu':
                return "tb2.pay_status='1'";
                break;
            case 'shouhuo':
                return "tb2.shipping_status='5'";
                break;
            case 'quxiao':
                return "(tb2.order_status='1' OR tb2.pay_status='2')";
                break;
            case 'tongguo':
                return "tb2.shipping_status='5' AND tb2.pay_status='1'";
                break;
            default :
                return "";
                break;
        }
    }

    function mymoneydata() {
        $this->title("佣金明细" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $w_rt = array();
        $w_rt[] = "tb1.uid = '$uid'";
        $status = isset($_GET['status']) ? trim($_GET['status']) : "";
        if (!empty($status)) {
            $st = $this->_return_statue_where($status);
            !empty($st) ? $w_rt[] = $st : "";
        }
        $w = " WHERE " . implode(' AND ', $w_rt);

        $sql = "SELECT SUM(tb1.money) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn $w";
        $rt['zmoney'] = $this->App->findvar($sql);
        //分页
        $page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 10; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(tb1.cid) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn $w");
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT tb1.*,tb3.nickname,tb3.headimgurl FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.buyuid = tb3.user_id $w ORDER BY tb1.time DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql); //商品列表
        $rt['page'] = $page;

        if (!defined(NAVNAME))
            define('NAVNAME', "佣金明细");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_mymoneydata');
    }

    function _return_goods_name($sn) {
        if (empty($sn))
            return "";
        $sql = "SELECT tb1.goods_name FROM `{$this->App->prefix()}goods_order` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_id = tb1.order_id WHERE tb2.order_sn='$sn' LIMIT 1";
        return $this->App->findvar($sql);
    }

    function setpass() {
        $this->checked_login();
        $this->title("设置密码" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND active='1' LIMIT 1";
        $rt = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
        $rts = $this->App->findrow($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "设置密码");
        $this->set('rt', $rt);
        $this->set('rts', $rts);
        $this->template('v2_setpass');
    }

    function setpasspay() {
        $this->checked_login();
        $this->title("修改提款密码" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
        $rts = $this->App->findrow($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "修改提款密码");
        $this->set('rts', $rts);
        $this->template('v2_setpasspay');
    }

    function postmoney() {
       
        $this->checked_login();
        $this->title("申请结算");
        $uid = $this->Session->read('User.uid');
        //表单token
        $token = md5(microtime(true));  
        $this->Session->write('token', $token);
		
		 $sql = "SELECT work_time FROM `{$this->App->prefix()}systemconfig`";
        $work_time = $this->App->findvar($sql);
		$arr=split("-",$work_time);
		  $this->set('work_time', $arr);
        
        $sql = "SELECT tb1.*,tb2.name,tb2.pic FROM `{$this->App->prefix()}user_bank` as tb1 LEFT JOIN `{$this->App->prefix()}bank` AS tb2 ON tb2.id = tb1.bank WHERE tb1.uid='$uid' LIMIT 1";
	
        $rts = $this->App->findrow($sql);
		
		$rts['bankname'] = $this->App->findvar("SELECT name FROM `{$this->App->prefix()}bank` WHERE id=".$rts['bank']." LIMIT 1");
		
        if (empty($rts)) {
            $this->jump(ADMIN_URL . 'user.php?act=myinfos_b', 0, '请先设置提款信息');
            exit;
        }
		
		$key = $_GET['key'];
        $sql = "SELECT ".$key." FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
        $mymoney = $this->App->findvar($sql);

        $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1";
        $rL = $this->App->findrow($sql);
        $this->set('rL', $rL);
        unset($rL);
        if (!defined(NAVNAME))
            define('NAVNAME', "结算");
        $this->set('rts', $rts);
        $this->set('token', $token);
        $this->set('mymoney', $mymoney);
	    $this->set('key', $key);
	    $this->set('uid', $uid);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_postmoney');
    }

    //申请提款
    function ajax_postmoney($data = array()) {
        // $this->jump(ADMIN_URL . 'index.php', 0, '通知:周六周日不予提现！起始时间2015年12月4日17:00点至2015年12月7日9:00点！');
        // exit;
		if(($uid == 42)){
    		 $client = $_SERVER['HTTP_USER_AGENT'];
    		 //用php自带的函数strpos来检测是否是微信端
            if (strpos($client , 'MicroMessenger') === false) {
                die("请在微信端打开");
            	exit;
            }
        }
        $uid = $this->Session->read('User.uid');
		
		if(!$uid){
			exit;
		}
		//限制提现
        //		
        $post_moneys =  $this->App->findvar("SELECT post_moneys FROM `{$this->App->prefix()}user` WHERE user_id=".$uid);
		if($post_moneys != 1){
			echo "暂时关闭提现功能";
			 exit;
			}
        $stop_post = array("9718","9892",'11841','11828','11821','11767','12236');
		if(in_array($uid,$stop_post)){
			echo "暂时关闭提现功能";
			 exit;
			}

        
        if($data['money'] <= 2){
          echo "提款金额必须大于2！";
		   exit;
          }
          $moneyx = floor($data['money']*100)/100;
          	//file_put_contents('./0auto_moneyx.txt',$moneyx);
        $fei = 2;
        $money = floor($data['money']*100)/100 - $fei;//实际提款金额要减2元的手续费
        //	file_put_contents('./0auto_money.txt',$money);
        $ids = $data['id'];
		$key = $data['key'];
		
		
		$s_moneys =  $this->App->findvar("SELECT {$key} FROM `{$this->App->prefix()}user` WHERE user_id=".$uid);
		  
		 
		if($money > $s_moneys){
		   exit;
		}
		 
		  
        /* 		if($money < 50){
          echo "暂时不能为您服务，先赚取50以上佣金再来吧！";exit;
          } */
        //检查密码
        //$pass = md5(trim($pass));
        $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1";
        $rL = $this->App->findrow($sql);

        $sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
        $id = $this->App->findvar($sql);
        if ($id > 0) {
       
            if (!(intval($ids) > 0))
                $ids = $id;
            $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE id='$ids' LIMIT 1";
            $rr = $this->App->findrow($sql);
			
			$rrr = $this->App->findrow("SELECT name,code FROM `{$this->App->prefix()}bank` WHERE id=".$rr['bank']." LIMIT 1");
			

            $dd = array();
            $dd['uid'] = $uid;
			$dd['order_sn'] = date('Ymd',time()).$uid.time();
            $dd['amount'] = $money;
            $dd['addtime'] = mktime();
            $dd['date'] = date('Y-m', mktime());
            $dd['bankname'] = $rrr['name'];
			$dd['bank_code'] = $rrr['code'];
            $dd['mobile'] = $rr['mobile'];
            $dd['account_name'] = $rr['uname'];
            $dd['account_no'] = $rr['banksn'];
            $dd['key'] = $key;
            $dd['idcard'] = $rr['idcard'];
			
			
			 unset($rr);
			
			if($key == "yinlian_api" && ($uid == 5593)){
				
				// $daifu_in = $this->daifu_in($uid);
			
			 //查询代付入驻结果
				//   if($uid == 42){
//				   $daifu_in_result = $this->daifu_in_query($uid);
//				   
//				   echo $daifu_in_result;
//				   exit;
//				   }
				   
				   	 
			  if(1){	
			  
			     
				   	  
	  if ($this->App->insert('user_drawmoney', $dd)) {
		  
		  $id=$iid = $this->App->iid();

		  $m_key = $this->App->findvar("SELECT yinlian FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");

		//  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $m_key."\n\n", 3, './app/daili/sql_'.date('Y-m-d').'.log');
		  
				  if($m_key >= $moneyx){
					  
				  $moneys = floor($m_key*100)/100-$moneyx;

				  $sql = "UPDATE `{$this->App->prefix()}user` SET `yinlian` = '$moneys' WHERE user_id = '$uid'";

				 // error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $sql."\n\n", 3, './app/daili/sql_'.date('Y-m-d').'.log');
				  
				 
				   

				  if($this->App->query($sql)){

					     $daifu_return = $this->daifupay_yinlian_api($dd);

					  }else{
						  echo "提款申请出错了哦！";
						  exit;
						  }
						  
				
				  }else{
					    echo'资金不足，您不能提款';
						 exit;
					  }
				  

							
							
							   
													 if($daifu_return['respCode'] == "000000"){
														 
																$arr['state'] = 1;
																$arr['gender'] = 1;
																$arr['INFO_REQ_SN'] = $daifu_return['reqMsgId'];
																$arr['INFO_RET_CODE'] = "0000";
																$arr['RET_DETAILS_RET_CODE'] = "0000";
																$arr['RET_DETAILS_ERR_MSG'] = $daifu_return['respMsg'];
																$this->App->update('user_drawmoney', $arr, 'id', $id);
																
																 echo "success";
																  exit;
														 }else{
															 
															   $arr['state'] = 0;
															   $arr['gender'] = 1;
																$arr['INFO_REQ_SN'] = $daifu_return['reqMsgId'];
																$arr['INFO_RET_CODE'] = "0000";
																$arr['RET_DETAILS_RET_CODE'] = $daifu_return['respCode'];
																$arr['RET_DETAILS_ERR_MSG'] = $daifu_return['respMsg'];
																$this->App->update('user_drawmoney', $arr, 'id', $id);
																
															  echo "success";
															   exit;
															 }
							 
					  
	           }
	  
				   }else{
						 echo $daifu_in;
						  exit;
					 }
					 
			
			
			}else if($key == "weixin" || $key == "zhifubao" || $key == "jingdong" || $key == "yinlian_h5" ){	
			
			  $daifu_in = $this->daifu_in($uid);
			
			 //查询代付入驻结果
				//   if($uid == 42){
//				   $daifu_in_result = $this->daifu_in_query($uid);
//				   
//				   echo $daifu_in_result;
//				   exit;
//				   }
				   
				   	 
			  if($daifu_in == "success"){	
			  
			     
				   	  
	  if ($this->App->insert('user_drawmoney', $dd)) {
		  
		  $id=$iid = $this->App->iid();

		  $m_key = $this->App->findvar("SELECT ".$key." FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");

		//  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $m_key."\n\n", 3, './app/daili/sql_'.date('Y-m-d').'.log');
		  
				  if($m_key >= $moneyx){
					  
				  $moneys = floor($m_key*100)/100-$moneyx;

				  $sql = "UPDATE `{$this->App->prefix()}user` SET `".$key."` = '$moneys' WHERE user_id = '$uid'";

				 // error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $sql."\n\n", 3, './app/daili/sql_'.date('Y-m-d').'.log');
				  
				 
				   

				  if($this->App->query($sql)){

					     $daifu_return = $this->daifupay($dd);

					  }else{
						  echo "提款申请出错了哦！";
						  exit;
						  }
						  
				
				  }else{
					    echo'资金不足，您不能提款';
						 exit;
					  }
				  

							
							
							   
													 if($daifu_return['respCode'] == "000000"){
														 
																$arr['state'] = 1;
																$arr['gender'] = 1;
																$arr['INFO_REQ_SN'] = $daifu_return['reqMsgId'];
																$arr['INFO_RET_CODE'] = "0000";
																$arr['RET_DETAILS_RET_CODE'] = "0000";
																$arr['RET_DETAILS_ERR_MSG'] = $daifu_return['respMsg'];
																$this->App->update('user_drawmoney', $arr, 'id', $id);
																
																 echo "success";
																  exit;
														 }else{
															 
															   $arr['state'] = 0;
															   $arr['gender'] = 1;
																$arr['INFO_REQ_SN'] = $daifu_return['reqMsgId'];
																$arr['INFO_RET_CODE'] = "0000";
																$arr['RET_DETAILS_RET_CODE'] = $daifu_return['respCode'];
																$arr['RET_DETAILS_ERR_MSG'] = $daifu_return['respMsg'];
																$this->App->update('user_drawmoney', $arr, 'id', $id);
																
															  echo "success";
															   exit;
															 }
							 
					  
	           }
	  
				   }else{
						 echo $daifu_in;
						  exit;
					 }
			  
		}else{
							
				if ($this->App->insert('user_drawmoney', $dd)) {
                    //2018/03/10
                    echo "success";
					// $daifuid=$iid = $this->App->iid();
					
					// $m_key = $this->App->findvar("SELECT ".$key." FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
					// 						  if($m_key >= $moneyx){
												  
					// 						  $moneys = floor($m_key*100)/100-$moneyx;
					// 					 $sql = "UPDATE `{$this->App->prefix()}user` SET `".$key."` = '$moneys' WHERE user_id = '$uid'";
					// 						  if($this->App->query($sql)){
												  
					// 							  	$df_return = $this->pay($daifuid);
													
					// 							  }else{
					// 								    echo "提款申请出错了哦！";
					// 								   exit;
					// 								  }
											  
					
					//  }else{
					// 							  echo'资金不足，您不能提款';
					// 				   exit;
					// 							  }
	
				
		
					// 			if($df_return['INFO_RET_CODE'] == "0000"){
									
								
					// 						   if($df_return['RET_DETAILS_RET_CODE'] == "0000"){
					// 						  echo "success";
					// 						   exit;
					// 						  }else{
					// 							  echo $df_return['RET_DETAILS_ERR_MSG'];
					// 							   exit;
					// 							  }
											   
											 
								
					// 			}else{
									
					// 			 echo  $df_return['INFO_ERR_MSG'];;
					// 	    exit;
					// 				}
				} else {
					echo "提款失败，请联系我们客服处理！";
				   exit;
				}
				
			
				
				
				}
				  

				
				
       
           
			
				
				
				
				
        } else {
            echo "提款信息错误！";
            exit;
        }
		
		
		
    }

    function postmoneydata($data = array()) {
		 $this->title('提现明细 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
		
		  $w_rt[] = " uid = '$uid'";
		 if (is_array($w_rt)) {
            if (!empty($w_rt)) {
                $w = " WHERE " . implode(' AND ', $w_rt);
            }
        } else {
            $w = " WHERE " . $w_rt;
        }
        $sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}user_drawmoney`".$w;
      
		
		
		
		 $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if (!($page > 0))
            $page = 1;
			
			 $list = 10;
			   $start = ($page - 1) * $list;
       
        $tt = $this->App->findvar($sql); //获取商品的数量
		
       

        $orderpage = Import::basic()->getpage($tt, $list, $page, '?page=', true);
		
		
		
		
        $sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE uid='$uid' ORDER BY id DESC LIMIT $start,$list";
        $rt_ = $this->App->find($sql);
        $rt = array();
        if (!empty($rt_))
            foreach ($rt_ as $k => $row) {
                $rt[$k] = $row;
            }
        unset($rt_);
        $this->set('rt', $rt);
		  $this->set('page', $page);
		   $this->set('orderpage', $orderpage);
        if (!defined(NAVNAME))
            define('NAVNAME', '提现明细');
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_postmoneydata');
    }

    function fahuo() {
        $this->title("申请发货" . ' - ' . $GLOBALS['LANG']['site_name']);
        $this->is_daili();

        if (!defined(NAVNAME))
            define('NAVNAME', "申请发货");
        $this->set('rt', $rt);
        $this->template('v2_fahuo');
    }

    /*     * ***************************************************** */

    //用户登录
    function login() {
        $this->css('login.css');
        if (($this->is_login())) {
            $this->jump(ADMIN_URL . 'daili.php');
            exit;
        } //
        $this->title("代理登录" . ' - ' . $GLOBALS['LANG']['site_name']);

        if (!defined(NAVNAME))
            define('NAVNAME', "代理登录");
        $this->set('rt', $rt);
        $this->template('user_login');
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
        $sql = "UPDATE `{$this->App->prefix()}user` SET password ='$md5pass' WHERE user_name='$uname' AND email='$email' AND user_rank='10'";
        if ($this->App->query($sql)) {
            die("");
        } else {
            die("目前无法完成您的请求！");
        }
    }

    //用户注册
    function register() {
        $this->css('login.css');
        if (($this->is_login())) {
            $this->jump(ADMIN_URL . 'daili.php');
            exit;
        } //
        $this->title("代理注册" . ' - ' . $GLOBALS['LANG']['site_name']);
        $rt['hear'][] = '<a href="' . ADMIN_URL . '">首页</a>&nbsp;&gt;&nbsp;';
        $rt['hear'][] = '代理注册';
        //$rt['province'] = $this->get_regions(1);  //获取省列表

        if (!defined(NAVNAME))
            define('NAVNAME', "代理注册");
        $this->set('rt', $rt);
        $this->template('user_register');
    }

    //当前文章的分类的所有文章
    function __get_all_article($type = 'default') {
        $article_list = $this->Cache->read(3600);
        if (is_null($rt)) {
            $order = "ORDER BY tb1.vieworder ASC, tb1.article_id DESC";
            $sql = "SELECT tb1.article_title,tb1.cat_id, tb1.article_id,tb2.cat_name FROM `{$this->App->prefix()}article` AS tb1";
            $sql .= " LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2";
            $sql .= " ON tb1.cat_id = tb2.cat_id";
            $sql .=" WHERE tb2.type='$type'  $order";
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

    //代理设置
    function dailiset() {
        $this->title("基本设置" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');


        $rt = array();
        if (!defined(NAVNAME))
            define('NAVNAME', "基本设置");
        $this->set('rt', $rt);
        $this->template('dailiset');
    }

    //代理价格
    function fromprice() {
        $this->title("代理价格说明" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}article` WHERE article_id='122'";
        $rt = $this->App->findrow($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', (!empty($rt['article_title']) ? $rt['article_title'] : "代理价格说明"));
        $this->set('rt', $rt);
        $this->template('fromprice');
    }

    //销售统计
    function saleorder() {
        $this->title("销售统计" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');

        $rt = array();
        if (!defined(NAVNAME))
            define('NAVNAME', "销售统计");
        $this->set('rt', $rt);
        $this->template('saleorder');
    }

    //我的分红
    function fenhong() {
        $this->title("销售统计" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');

        $rt = array();
        if (!defined(NAVNAME))
            define('NAVNAME', "我的分红");
        $this->set('rt', $rt);
        $this->template('fenhong');
    }

    //我的推荐
    function mytuijian() {
        $this->title("我的推荐" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');

        $rt = array();
        if (!defined(NAVNAME))
            define('NAVNAME', "我的推荐");
        $this->set('rt', $rt);
        $this->template('mytuijian');
    }

    //用户后台
    function index() {
        $uid = $this->Session->read('User.uid');
        $rank = $this->Session->read('Agent.rank');
        $this->title("用户后台管理中心" . ' - ' . $GLOBALS['LANG']['site_name']);
        //if(!($uid>0)){ $this->jump(ADMIN_URL.'daili.php?act=login',0,'请先登录！'); exit;} //

        $sql = "SELECT user_id,email,user_name,nickname,reg_time,user_id,user_rank,sex,avatar,birthday,last_login,last_ip,visit_count,qq,office_phone,home_phone,mobile_phone,active FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);

        $sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
        $rt['userinfo']['level_name'] = $this->App->findvar($sql);  //

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}'";
        $rt['userinfo']['zmoney'] = $this->App->findvar($sql);

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}' AND money < '0'";
        $rt['userinfo']['spzmoney'] = $this->App->findvar($sql);

        $sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid ='{$uid}'";
        $rt['userinfo']['points'] = $this->App->findvar($sql);

        //当前用户的收货地址
        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own ORDER BY tb1.is_own DESC, tb1.address_id ASC LIMIT 1";
        $rt['userress'] = $this->App->findrow($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "财富中心");
        $this->set('rt', $rt);
        $this->template('index');
    }

    //AJAX获取我的用户
    function ajax_myuser_page($rts = array()) {
        $hh = $rts['hh'];
        $tops = $rts['tops'];
        $level = $rts['level'];
        $tops = intval($tops);
        if (($tops - $hh) >= 0) {
            $page = ceil($tops / $hh);
            $list = 30;
            $start = $page * $list;
            $uid = $this->Session->read('User.uid');
            if ($level == '0') {
                $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
                $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
                $sql .=" WHERE tb1.uid !='$uid' AND (tb1.daili_uid = '$uid' OR tb1.parent_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
                $ulist = $this->App->find($sql);
            } elseif ($level == '1') {
                $ulist = $this->get_myuser_level_1($uid, $start, $list);
            } elseif ($level == '2') {
                $ulist = $this->get_myuser_level_2($uid, $start, $list);
            } elseif ($level == '3') {
                $ulist = $this->get_myuser_level_3($uid, $start, $list);
            }

            $this->set('ulist', $ulist);
            $this->set('pagec', $page * $list);
            echo $this->fetch('load_myuser', true);
        }
        echo "";
        exit;
    }

    //一级用户
    function get_myuser_level_1($uid = '0', $start = '0', $list = '30') {
        $sql = "SELECT tb1.*,tbl.level_name,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb2.user_rank = tbl.lid";
        $sql .=" WHERE tb1.parent_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
        return $this->App->find($sql);
    }

    //二级用户
    function get_myuser_level_2($uid = '0', $start = '0', $list = '30') {
        $sql = "SELECT tb2.*,tbl.level_name,tb3.subscribe_time,tb3.reg_time,tb3.nickname,tb3.headimgurl,tb3.money_ucount,tb3.points_ucount,tb3.share_ucount,tb3.guanzhu_ucount,tb3.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb2.uid = tb3.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb3.user_rank = tbl.lid";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL ORDER BY tb3.share_ucount DESC,tb3.money_ucount DESC,tb2.id DESC LIMIT $start,$list";
        return $this->App->find($sql);
    }

    //三级用户
    function get_myuser_level_3($uid = '0', $start = '0', $list = '30') {
        $sql = "SELECT tb3.*,tbl.level_name,tb4.subscribe_time,tb4.reg_time,tb4.nickname,tb4.headimgurl,tb4.money_ucount,tb4.points_ucount,tb4.share_ucount,tb4.guanzhu_ucount,tb4.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb3.uid = tb4.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb4.user_rank = tbl.lid";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  ORDER BY tb4.share_ucount DESC,tb4.money_ucount DESC,tb3.id DESC LIMIT $start,$list";
        return $this->App->find($sql);
    }

    function myuserinfo($data = array()) {
        $this->checked_login();

        $uid = $data['uid'];

        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);

        $sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
        $rt['userinfo']['level_name'] = $this->App->findvar($sql);  //

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}'";
        $rt['userinfo']['zmoney'] = $this->App->findvar($sql);

        $sql = "SELECT SUM(order_amount) FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE user_id ='{$uid}' AND pay_status = '1'";
        $rt['userinfo']['spzmoney'] = $this->App->findvar($sql);

        $sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid ='{$uid}'";
        $rt['userinfo']['points'] = $this->App->findvar($sql);

        $sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE is_subscribe ='1' LIMIT 1";
        $rt['gzcount'] = $this->App->findvar($sql);

        $sql = "SELECT tb1.nickname FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
        $rt['tjren'] = $this->App->findvar($sql);

        //一级
        $rt['zcount1'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid = '$uid' LIMIT 1");
        //二级
        //$rt['zcount2'] = $this->App->findvar("SELECT COUNT(DISTINCT tb2.uid) FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid AND tb2.uid != tb2.daili_uid WHERE tb1.parent_uid='$uid' LIMIT 1");
        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL  LIMIT 1";
        $rt['zcount2'] = $this->App->findvar($sql);

        //三级
        //$rt['zcount3'] = $this->App->findvar("SELECT COUNT(tb3.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid AND tb2.uid != tb2.daili_uid LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid AND tb3.uid != tb3.daili_uid WHERE tb1.parent_uid='$uid' LIMIT 1");
        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  LIMIT 1";
        $rt['zcount3'] = $this->App->findvar($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "分销会员详情");
        $this->set('rt', $rt);

        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_myuserinfo');
    }

    //我的客户
    function myuser() {
        $this->checked_login();
        $uid = $this->Session->read('User.uid');
        $ts = isset($_GET['t']) ? $_GET['t'] : '0';
        $l = $ts == '9' ? '金牌会员' : ($ts == '10' ? '钻石会员' : ($ts == '11' ? '皇冠会员' : ($ts == '12' ? '投资合伙人' : '')));
        if (!defined(NAVNAME))
            define('NAVNAME', "推广列表:" . $l);

          $this->title("推广明细" . ' - ' . $GLOBALS['LANG']['site_name']);
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : '1';
        $list = 30;
        $start = ($page - 1) * $list;
        //$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        //$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2";
        //$sql .=" ON tb1.uid = tb2.user_id WHERE tb1.daili_uid = '$uid'";
        //$tt = $this->App->findvar($sql);
        //$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
        if ($ts == '9') {
            //全部用户
            $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe,tb3.mobile FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE tb2.user_rank=9 and tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
			
            $rt['lists'] = $this->App->find($sql); // AND tb2.is_subscribe ='1'
			
			 $sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=9 and tb1.uid !='$uid' AND tb1.p1_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['zhijie'] = $this->App->findvar($sql);
			$sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=9 and tb1.uid !='$uid' AND tb1.p1_uid != '$uid' and (tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			
			$rr['jianjie'] = $this->App->findvar($sql);
			
			 $sql = "SELECT COUNT(distinct tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE tb2.user_rank=9 and tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			
			$tt = $this->App->findvar($sql);
			
			
        } else if ($ts == '10') {
            //一级用户
          $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe,tb3.mobile FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE tb2.user_rank=10 and tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
            $rt['lists'] = $this->App->find($sql); // AND tb2.is_subscribe ='1'
			
			 $sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE  tb2.user_rank=10 and tb1.uid !='$uid' AND tb1.p1_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['zhijie'] = $this->App->findvar($sql);
			
			$sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=10 and tb1.uid !='$uid' AND tb1.p1_uid != '$uid' and (tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['jianjie'] = $this->App->findvar($sql);
			
		$sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE tb2.user_rank=10 and tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
            $tt = $this->App->findvar($sql); // AND tb2.is_subscribe ='1'
			
        } else if ($ts == '11') {
            //二级用户
          $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe,tb3.mobile FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE  tb2.user_rank=11 and tb2.user_rank=11 and  tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
            $rt['lists'] = $this->App->find($sql); // AND tb2.is_subscribe ='1'
			
			 $sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=11 and tb1.uid !='$uid' AND tb1.p1_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['zhijie'] = $this->App->findvar($sql);
			
			$sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=11 and tb1.uid !='$uid' AND tb1.p1_uid != '$uid' and (tb1.p2_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['jianjie'] = $this->App->findvar($sql);
			
			
				 $sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE  tb2.user_rank=11 and tb2.user_rank=11 and  tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
            $tt = $this->App->findvar($sql); 
			
        } else if ($ts == '12') {
            //三级用户
          $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe,tb3.mobile FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE tb2.user_rank=12 and tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
            $rt['lists'] = $this->App->find($sql); // AND tb2.is_subscribe ='1'
			
			 $sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=12 and tb1.uid !='$uid' AND tb1.p1_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['zhijie'] = $this->App->findvar($sql);
			
			$sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb2.user_rank=12 and tb1.uid !='$uid' AND tb1.p1_uid != '$uid' and (tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
			$rr['jianjie'] = $this->App->findvar($sql);
		
				 $sql = "SELECT count(tb1.id) FROM `{$this->App->prefix()}user_tuijian_fx` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
			 $sql .=" LEFT JOIN `{$this->App->prefix()}user_bank` AS tb3 ON tb1.uid = tb3.uid";
            $sql .=" WHERE tb2.user_rank=12 and tb1.uid !='$uid' AND (tb1.p1_uid = '$uid' OR tb1.p2_uid = '$uid' or tb1.p3_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC";
            $tt = $this->App->findvar($sql); 
        }

      $pages = Import::basic()->getpage($tt, $list, $page,'?page=',true);
        
		
		
        $this->set('level', $ts);
		$this->set('rr', $rr);
		$this->set('uid', $uid);
		$this->set('zcount', $tt);
        $this->set('rt', $rt);
		$this->set('pages', $pages);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/v2_myuser');
    }

    function myusertype() {
        //2018/03/12
        $this->checked_login();
	    $uid = $this->Session->read('User.uid');
		
        $sql = "SELECT status FROM `{$this->App->prefix()}user_bank` WHERE uid = '$uid' LIMIT 1";

        $status = $this->App->findvar($sql);
		if(!$status){
			$this->jump(ADMIN_URL.'user.php?act=renzheng');
			exit;
			}

        $sql = "SELECT tuiguang FROM `{$this->App->prefix()}user_moneys` WHERE uid = '$uid' LIMIT 1";

        $rt['tuiguang'] = $this->App->findvar($sql);
		
		if($uid == 17 || $uid == 42 || $uid == 26 || $uid == 1798 || $uid == 30){
				$rt['tuiguang'] = $rt['tuiguang']*10000;
				}
		
        $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
        //全部下级 如果是高级分销商可以统计所有客户
        $rt['zcount'] = 0;
      
            $rt['zcount'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}user_tuijian` WHERE uid !='$uid' AND (daili_uid='$uid' OR parent_uid='$uid') LIMIT 1");
			
			if($uid == 17 || $uid == 42 || $uid == 26 || $uid == 1798 || $uid == 30){
				$rt['zcount'] = $rt['zcount']*100;
				}
			
			//金牌
			 $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb2.user_id = tb1.uid ";
        $sql .= " WHERE tb1.uid !='$uid' AND (tb1.daili_uid='$uid' OR tb1.parent_uid='$uid') AND tb2.user_rank=9  LIMIT 1";
        $rt['jinpai'] = $this->App->findvar($sql);
		if($uid == 17 || $uid == 42 || $uid == 26 || $uid == 1798 || $uid == 30){
				$rt['jinpai'] = $rt['jinpai']*100;
				}
		//钻石
			 $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb2.user_id = tb1.uid ";
        $sql .= " WHERE tb1.uid !='$uid' AND (tb1.daili_uid='$uid' OR tb1.parent_uid='$uid') AND tb2.user_rank=10  LIMIT 1";
        $rt['zuanshi'] = $this->App->findvar($sql);
		if($uid == 17 || $uid == 42 || $uid == 26 || $uid == 1798 || $uid == 30){
				$rt['zuanshi'] = $rt['zuanshi']*100;
				}
		//皇冠
			 $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb2.user_id = tb1.uid ";
        $sql .= " WHERE tb1.uid !='$uid' AND (tb1.daili_uid='$uid' OR tb1.parent_uid='$uid') AND tb2.user_rank=11  LIMIT 1";
        $rt['huangguan'] = $this->App->findvar($sql);
		if($uid == 17 || $uid == 42 || $uid == 26 || $uid == 1798 || $uid == 30){
				$rt['huangguan'] = $rt['huangguan']*100;
				}
		//合伙人
			 $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb2.user_id = tb1.uid ";
        $sql .= " WHERE tb1.uid !='$uid' AND (tb1.daili_uid='$uid' OR tb1.parent_uid='$uid') AND tb2.user_rank=12  LIMIT 1";
        $rt['hehuo'] = $this->App->findvar($sql);
		if($uid == 17 || $uid == 42 || $uid == 26 || $uid == 1798 || $uid == 30){
				$rt['hehuo'] = $rt['hehuo']*100;
				}
			
      
        //一级
        $rt['zcount1'] = $this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid = '$uid' LIMIT 1");
        //二级
        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL  LIMIT 1";
        $rt['zcount2'] = $this->App->findvar($sql);

        //三级
        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  LIMIT 1";
        $rt['zcount3'] = $this->App->findvar($sql);

        $this->set('rt', $rt);
		  $this->title("推广明细" . ' - ' . $GLOBALS['LANG']['site_name']);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->css("css.css");
        $this->template($mb . '/v2_myusertype');
    }

    //我的分享
    function myshare() {
        $this->checked_login();
        $this->title("我的分享" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $id = isset($_GET['id']) ? $_GET['id'] : '0';
        if ($id > 0) {
            $this->App->delete('user_tuijian', 'id', $id);
            $this->jump(ADMIN_URL . 'daili.php?act=myshare');
            exit;
        }
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : '1';
        if (empty($page)) {
            $page = 1;
        }
        $list = 10;
        $start = ($page - 1) * $list;
        $sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2";
        $sql .=" ON tb1.uid = tb2.user_id WHERE tb1.daili_uid = '$uid'";
        $tt = $this->App->findvar($sql);
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT tb1.*,tb2.user_name,tb2.nickname FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2";
        $sql .=" ON tb1.uid = tb2.user_id";
        $sql .=" WHERE tb1.share_uid = '$uid' ORDER BY id DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "我的分享");
        $this->set('rt', $rt);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/myshare');
    }

    //调研投票
    function myvotes() {
        $this->title("调研投票" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt = array();
        if (!defined(NAVNAME))
            define('NAVNAME', "调研投票");
        $this->set('rt', $rt);
        $this->template('myvotes');
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
        if (!$page)
            $page = 1;
        $start = ($page - 1) * $list;
        $sql = "SELECT distinct tb1.order_id, tb1.order_sn, tb1.order_status, tb1.shipping_status,tb1.shipping_name ,tb1.pay_name, tb1.pay_status, tb1.add_time,tb1.consignee, (tb1.goods_amount + tb1.shipping_fee) AS total_fee FROM `{$this->App->prefix()}goods_order_info` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id=tb2.order_id";
        $sql .=" $w ORDER BY tb1.add_time DESC LIMIT $start,$list";
        $orderlist = $this->App->find($sql);
        if (!empty($orderlist)) {
            foreach ($orderlist as $k => $row) {

                $orderlist[$k]['status'] = $this->get_status($row['order_status'], $row['pay_status'], $row['shipping_status']);
                $orderlist[$k]['op'] = $this->get_option($row['order_id'], $row['order_status'], $row['pay_status'], $row['shipping_status']);
                $sql = "SELECT goods_id,goods_name,goods_bianhao,market_price,goods_price,goods_thumb FROM `{$this->App->prefix()}goods_order` WHERE order_id='$row[order_id]' ORDER BY goods_id";
                $orderlist[$k]['goods'] = $this->App->find($sql);
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
        $sql = "SELECT COUNT(distinct tb1.order_id) FROM `{$this->App->prefix()}goods_order_info` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id=tb2.order_id " . $w;
        return $this->App->findvar($sql);
    }

    //订单详情
    function orderinfo($orderid = "") {
        $this->title("欢迎进入用户后台管理中心" . ' - 订单详情 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        if (empty($orderid)) {
            $this->jump('daili.php?act=myorder');
            exit;
        }

        $sql = "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$orderid' ORDER BY goods_id";
        $rt['goodslist'] = $this->App->find($sql);

        $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_info` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" WHERE tb1.order_id='$orderid'";
        $rt['orderinfo'] = $this->App->findrow($sql);

        $status = $this->get_status($rt['orderinfo']['order_status'], $rt['orderinfo']['pay_status'], $rt['orderinfo']['shipping_status']);
        $rt['status'] = explode(',', $status);

        //$rt['recommend10'] = $this->action('catalog','recommend_goods');
        //商品分类列表		
        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        if (!defined(NAVNAME))
            define('NAVNAME', "订单详情");
        $this->set('rt', $rt);
        $this->template('user_orderinfo');
    }

    //选择订单的所在状态
    function select_statue($id = "") {
        if (empty($id))
            return "";
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
            default :
                return "";
                break;
        }
    }

    ##############################

    function error_jump() {

        $this->action('common', 'show404tpl');
    }

    //订单列表
    function orderlist() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的订单 - ' . $GLOBALS['LANG']['site_name']);
        $dt = isset($_GET['dt']) && intval($_GET['dt']) > 0 ? intval($_GET['dt']) : "";
        $status = isset($_GET['status']) ? trim($_GET['status']) : "";
        $keyword = isset($_GET['kk']) ? trim($_GET['kk']) : "";
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
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

        $page = 1;
        $list = 5;
        $tt = $this->__order_list_count($w_rt); //获取商品的数量
        $rt['order_count'] = $tt;

        $rt['orderpage'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $rt['orderlist'] = $this->__order_list($w_rt, $page, $list);
        $rt['status'] = $status;

        $rt['userinfo']['user_id'] = $this->Session->$uid;

        $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2'";
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
        $rt['userinfo']['need_comment_count'] = $this->App->findvar($sql);
        //print_r($rt);
        //商品分类列表		
        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        if (!defined(NAVNAME))
            define('NAVNAME', "我的订单");
        $this->set('rt', $rt);
        $this->set('page', $page);
        $this->template('user_orderlist');
    }

    //用户资料
    function myinfo() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的资料 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }

        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND user_rank='10' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);

        $rt['province'] = $this->get_regions(1);  //获取省列表
        //当前用户的收货地址
        $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
        $rt['userress'] = $this->App->findrow($sql);

        if ($rt['userress']['province'] > 0)
            $rt['city'] = $this->get_regions(2, $rt['userress']['province']);  //城市
        if ($rt['userress']['city'] > 0)
            $rt['district'] = $this->get_regions(3, $rt['userress']['city']);  //区		

        $this->set('rt', $rt);
        if (!defined(NAVNAME))
            define('NAVNAME', "代理资料");
        $this->template('user_info');
    }

    //收货地址
    function address() {
        $this->title("欢迎进入用户后台管理中心" . ' - 收货地址 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }

        /* if(isset($_POST)&&!empty($_POST)){

          if(empty($_POST['province'])){
          $this->jump('daili.php?act=address_list',0,'选择省份！'); exit;
          }else if(empty($_POST['city'])){
          $this->jump('daili.php?act=address_list',0,'选择城市！');exit;
          }else if(empty($_POST['consignee'])){
          $this->jump('daili.php?act=address_list',0,'收货人不能为空！');exit;
          }else if(empty($_POST['email'])){
          $this->jump('daili.php?act=address_list',0,'电子邮箱不能为空！');exit;
          }else if(empty($_POST['address'])){
          $this->jump('daili.php?act=address_list',0,'收货地址不能为空！');exit;
          }else if(empty($_POST['tel'])){
          $this->jump('daili.php?act=address_list',0,'电话号码不能为空！');exit;
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

        $rt['province'] = $this->get_regions(1);  //获取省列表
        //当前用户的收货地址
        $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='0'";
        $rt['userress'] = $this->App->find($sql);
        if (!empty($rt['userress'])) {
            foreach ($rt['userress'] as $row) {
                $rt['city'][$row['address_id']] = $this->get_regions(2, $row['province']);  //城市
                $rt['district'][$row['address_id']] = $this->get_regions(3, $row['city']);  //区
            }
        }


        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own = '0' ORDER BY tb1.address_id ASC";
        $rt['userress'] = $this->App->find($sql);

        //商品分类列表		
        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');
        if (!defined(NAVNAME))
            define('NAVNAME', "收货地址簿");
        $this->set('rt', $rt);
        $this->template('user_consignee_address');
    }

    //用户密码修改
    function editpass() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->title("欢迎进入用户后台管理中心" . ' - 用户密码修改 - ' . $GLOBALS['LANG']['site_name']);

        //商品分类列表		
        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');
        $this->set('rt', $rt);
        if (!defined(NAVNAME))
            define('NAVNAME', "修改密码");
        $this->template('user_editpass');
    }

    //用户订单操作
    function ajax_order_op($id = 0, $op = "") {
        if (empty($id) || empty($op))
            die("传送ID为空！");
        if ($op == "cancel_order")
            $this->App->update('goods_order_info', array('order_status' => '1'), 'order_id', $id);
        else if ($op == "confirm")
            $this->App->update('goods_order_info', array('shipping_status' => '5'), 'order_id', $id);
    }

    //我的余额
    function mymoney($page = 1) {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的余额 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
        $rt['zmoney'] = $this->App->findvar($sql);
        $rt['zmoney'] = format_price($rt['zmoney']);
        //分页
        if (empty($page)) {
            $page = 1;
        }
        $list = 10; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $sql = "SELECT * FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql);
        $rt['page'] = $page;
        //商品分类列表		
        $this->set('rt', $rt);

        //ajax
        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
            echo $this->fetch('ajax_user_moneychange', true);
            exit;
        }
        if (!defined(NAVNAME))
            define('NAVNAME', "我的资金");
        $this->template('mymoney');
    }

    //我的积分
    function mypoints() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的积分 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        $sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
        $rt['zpoints'] = $this->App->findvar($sql);
        //分页
        $page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 10; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'");
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $sql = "SELECT * FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
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
        if (!defined(NAVNAME))
            define('NAVNAME', "我的积分");
        $this->template('mypoints');
    }

    //用户收藏
    function mycolle() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->js('goods.js');
        $this->title("欢迎进入用户后台管理中心" . ' - 我的收藏 - ' . $GLOBALS['LANG']['site_name']);
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($id > 0) {
            $this->App->delete('shop_collect', 'rec_id', $id);
            $this->jump(ADMIN_URL . 'daili.php?act=mycoll');
            exit;
        }
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 4; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(rec_id) FROM `{$this->App->prefix()}goods_collect` WHERE user_id='$uid'");
        $rt['pages'] = Import::basic()->ajax_page($tt, $list, $page, 'get_usercolle_page_list');
        $sql = "SELECT tb1.rec_id,tb1.user_id,tb1.add_time,tb2.goods_id, tb2.goods_name,tb2.goods_bianhao,tb2.shop_price, tb2.market_price,tb2.pifa_price,tb2.goods_thumb, tb2.original_img, tb2.goods_img,tb2.promote_start_date,tb2.promote_end_date,tb2.promote_price,tb2.is_promote,tb2.qianggou_start_date,tb2.qianggou_end_date,tb2.qianggou_price,tb2.is_qianggou FROM `{$this->App->prefix()}goods_collect` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
        $sql .=" WHERE tb1.user_id='$uid' ORDER BY tb1.add_time DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql); //商品列表


        $this->set('rt', $rt);
        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
            echo $this->fetch('ajax_mycoll', true);
            exit;
        }
        if (!defined(NAVNAME))
            define('NAVNAME', "我的收藏");
        $this->template('user_mycolle');
    }

    //ajax删除收藏
    function ajax_delmycoll($ids = 0) {
        if (empty($ids))
            die("非法删除，删除ID为空！");
        $id_arr = @explode('+', $ids);
        foreach ($id_arr as $id) {
            if (Import::basic()->int_preg($id))
                $this->App->delete('shop_collect', 'rec_id', $id);
        }
    }

    function user_tuijian() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
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

        $tt = $this->App->findvar("SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_new='1'");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT goods_id,goods_img,goods_name,pifa_price,need_jifen FROM `{$this->App->prefix()}goods` WHERE is_new='1' ORDER BY goods_id DESC LIMIT $start,$list";
        $rt['categoodslist'] = $this->App->find($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "我的推荐");
        $this->set('rt', $rt);
        $this->set('page', $page);
        $this->template('user_tuijian');
    }

    function messages() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->title("欢迎进入用户后台管理中心" . ' - 我的提问 - ' . $GLOBALS['LANG']['site_name']);

        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }

        $list = 4; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");

        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix()}message` AS tb1 LEFT JOIN  `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";
        $rt['meslist'] = $this->App->find($sql);

        if (!defined(NAVNAME))
            define('NAVNAME', "我的提问");
        $this->set('rt', $rt);
        $this->template('user_question');
    }

    function xiaofei() {
        $this->template('user_xiaofei');
    }

    function comment() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(ADMIN_URL . 'daili.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->title("欢迎进入用户后台管理中心" . ' - 我的评论 - ' . $GLOBALS['LANG']['site_name']);

        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 4; //每页显示多少个
        $start = ($page - 1) * $list;
        $sql = "SELECT COUNT(comment_id) FROM `{$this->App->prefix()}comment`";
        $sql .=" WHERE parent_id = 0 AND status='1' AND user_id='$uid'";
        $tt = $this->App->findvar($sql);

        $rt['goodscommentpage'] = Import::basic()->ajax_page($tt2, $list, $page, 'get_mycomment_page_list');

        $sql = "SELECT c.*,u.avatar,u.user_name AS dbuname,u.nickname,g.goods_thumb,g.goods_name,g.goods_id FROM `{$this->App->prefix()}comment` AS c LEFT JOIN `{$this->App->prefix()}user` AS u ON c.user_id=u.user_id LEFT JOIN `{$this->App->prefix()}goods` AS g ON g.goods_id = c.id_value";
        $sql .=" WHERE c.parent_id = 0  AND c.status='1' AND c.user_id='$uid' ORDER BY c.add_time DESC LIMIT $start,$list";
        $this->App->fieldkey('comment_id');
        $commentlist = $this->App->find($sql);
        $rp_commentlist = array();
        if (!empty($commentlist)) { //回复的评论
            $commend_id = array_keys($commentlist);
            $sql = "SELECT c.*,a.adminname FROM `{$this->App->prefix()}comment` AS c";
            $sql .=" LEFT JOIN `{$this->App->prefix()}admin` AS a ON a.adminid = c.user_id";
            $sql .=" WHERE c.parent_id IN (" . implode(',', $commend_id) . ")";
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

        if (!defined(NAVNAME))
            define('NAVNAME', "我的评论");
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
        $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");
        $rt['notgoodmespage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_myquestion_notgoods_page_list');
        $sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix()}message` AS tb1 LEFT JOIN  `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";
        $rt['notgoodsmeslist'] = $this->App->find($sql);
        $this->set('rt', $rt);
        $result['error'] = 0;
        $result['message'] = $this->fetch('ajax_userquestion_nogoods', true);
        die($json->encode($result));
    }

    //删除提问
    function ajax_delmessages($id = 0) {
        if (!($id > 0))
            die("传送的ID为空！");
        if ($this->App->delete('message', 'mes_id', $id)) {
            echo "";
        } else {
            echo "删除意外出错！";
        }
        exit;
    }

    //删除评论
    function ajax_delcomment($id = 0) {
        if (!($id > 0))
            die("传送的ID为空！");
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
        if (!($uid > 0))
            return false;
        $rank = $this->Session->read('Agent.rank');
        $sql = "SELECT * FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
        $rtlevel = $this->App->findrow($sql);
        $jfdesc = $rtlevel['jifendesc'];
        $dbjfdesc = array(); //当前会员级别能够得到积分的权限
        if (!empty($jfdesc)) {
            $dbjfdesc = explode('+', $jfdesc);
        }
        if (in_array($type, $dbjfdesc)) {  //拥有得到积分的权限
            switch ($type) {
                case 'comment': //参与每件已购商品评论获奖10分，依次类推，参与10件已购商品评论可获奖100个积分（一张订单每个产品只能获得一次积分）。
                    $data['time'] = mktime();
                    $data['changedesc'] = "评论所得积分！";
                    $data['points'] = 10;
                    $data['uid'] = $uid;
                    if ($this->App->insert('user_point_change', $data)) {
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
                case 'spendthan1500':  //单次购物达1500元，当次购物获取2倍积分
                    $sql = "SELECT goods_amount FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2' ORDER BY pay_time DESC LIMIT 1";
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
        if (empty($data['fromAttr']))
            die($json->encode($result));

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
                $sql = "SELECT password FROM `{$this->App->prefix()}user` WHERE password='$newpass' AND user_id='$uid'";
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
        $username = $this->Session->read('Agent.username');
        if (empty($uid) || empty($username)) {
            return false;
        } else {
            return true;
        }
    }

    function get_regions($type, $parent_id = 0) {
        $p = "";
        if (!empty($parent_id))
            $p = "AND parent_id='$parent_id'";

        $sql = "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";
        return $this->App->find($sql);
    }

    //退出登录
    function logout() {
        //session_destroy();
        //
		//if(isset($_COOKIE['user'])){
        //if(is_array($_COOKIE['user'])){
        //foreach($_COOKIE['user'] as $key=>$val){
        //setcookie("user[".$key."]", "");
        if (isset($_COOKIE['AGENT']['USERNAME']))
            setcookie('AGENT[USERNAME]', "", 0);
        if (isset($_COOKIE['AGENT']['PASS']))
            setcookie('AGENT[PASS]', "", 0);
        //}
        //}
        //}
        $this->Session->write('Agent', null);

        //$url = $this->Session->read('REFERER');
        $url = ADMIN_URL;
        $this->jump($url);
        exit;
    }

    function ajax_getuid() {
        echo $this->Session->read('User.uid');
        exit;
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

            $sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_name='$uname' LIMIT 1";
            $dbuname = $this->App->findvar($sql);
            if (empty($dbuname)) {
                $this->jump('', 0, '该用户不存在！');
                exit;
            }
            $sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_name= '$uname' AND email='$email' LIMIT 1";
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

        if (!defined(NAVNAME))
            define('NAVNAME', "找回密码");
        $this->set('rt', $rt);
        $this->template('user_forgetpass');
    }

    //注册成功提示的页面
    function user_regsuccess_mes() {
        $this->title("注册成功" . ' - ' . $GLOBALS['LANG']['site_name']);
        $this->template('user_regsuccess_mes');
    }

    //自动登录
    function auto_login() {
        $uid = $this->Session->read('User.uid');
        if ($uid > 0) {
            $addtime = $this->Session->read('Agent.addtime');
            if ((mktime() - intval($addtime)) > 12 * 3600) {
                if ($uid > 0) {
                    $sql = "SELECT mobile_phone FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
                    $uname = $this->App->findvar($sql);
                    if (empty($uname)) {
                        $this->Session->write('Agent', null);
                        if (isset($_COOKIE['Agent']['USERNAME']))
                            setcookie('Agent[USERNAME]', "", 0);
                        if (isset($_COOKIE['Agent']['PASS']))
                            setcookie('Agent[PASS]', "", 0);
                        $this->jump(ADMIN_URL . 'daili.php?act=login');
                        exit;
                    }
                }
            }
            $username = $this->Session->read('Agent.username');
            $pass = $this->Session->read('Agent.pass');
            if (!empty($username) && !empty($pass)) {
                setcookie('AGENT[USERNAME]', $username, mktime() + 2592000);
                setcookie('AGENT[PASS]', $pass, mktime() + 2592000);
            }
        } else {
            $user = isset($_COOKIE['AGENT']['USERNAME']) ? $_COOKIE['AGENT']['USERNAME'] : "";
            $pass = isset($_COOKIE['AGENT']['PASS']) ? $_COOKIE['AGENT']['PASS'] : "";
            if (!empty($user) && !empty($pass)) {
                $sql = "SELECT password,user_id,user_name,last_login,active,user_rank,mobile_phone FROM `{$this->App->prefix()}user` WHERE mobile_phone='$user' AND user_rank='10' LIMIT 1";
                $rt = $this->App->findrow($sql);
                if (empty($rt)) {
                    $this->Session->write('Agent', null);
                    if (isset($_COOKIE['Agent']['USERNAME']))
                        setcookie('Agent[USERNAME]', "", 0);
                    if (isset($_COOKIE['Agent']['PASS']))
                        setcookie('Agent[PASS]', "", 0);
                    $this->jump(ADMIN_URL . 'daili.php?act=login');
                    exit;
                }else {
                    if ($rt['password'] == $pass) {
                        //登录成功,记录登录信息
                        $ip = Import::basic()->getip();
                        $datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;
                        $datas['last_login'] = mktime();
                        $datas['visit_count'] = '`visit_count`+1';
                        $this->Session->write('Agent.prevtime', $rt['last_login']); //记录上一次的登录时间

                        $this->App->update('user', $datas, 'user_id', $rt['user_id']); //更新
                        $this->Session->write('Agent.username', $user);
                        $this->Session->write('Agent.pass', $rt['password']);
                        $this->Session->write('User.uid', $rt['user_id']);
                        $this->Session->write('Agent.active', $rt['active']);
                        $this->Session->write('Agent.rank', $rt['user_rank']);
                        $this->Session->write('Agent.lasttime', $datas['last_login']);
                        $this->Session->write('Agent.lastip', $datas['last_ip']);
                        $this->Session->write('Agent.addtime', mktime());

                        setcookie('AGENT[USERNAME]', $user, mktime() + 2592000);
                        setcookie('AGENT[PASS]', $pass, mktime() + 2592000);
                        unset($data);
                        return true;
                    } else {
                        $this->Session->write('Agent.username', null);
                        $this->Session->write('Agent.pass', null);
                        if (isset($_COOKIE['Agent']['USERNAME']))
                            setcookie('Agent[USERNAME]', "", 0);
                        if (isset($_COOKIE['Agent']['PASS']))
                            setcookie('Agent[PASS]', "", 0);
                        $this->jump(ADMIN_URL . 'daili.php?act=login');
                        exit;
                    }
                } //end if
            }else {
                //跳转到登陆页面
                $this->jump(ADMIN_URL . 'daili.php?act=login');
                exit;
            }
        }
        return true;
    }

//end function 
    //ajax登录
    function ajax_user_login($data = array()) {
        if (empty($data))
            die("请填写完整信息");
        $user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤
        if (empty($user))
            die("请输入用户名");
        $pass = md5(trim($data['password']));
        if (empty($pass))
            die("请输入密码");
        $vcode = isset($data['vifcode']) ? $data['vifcode'] : "";
        if (!empty($vcode)) {
            if (strtolower($vcode) != strtolower($this->Session->read('vifcode'))) {
                die("验证码错误！");
            }
        }
        $sql = "SELECT password,user_id,last_login,active,user_rank,mobile_phone,wecha_id,user_name FROM `{$this->App->prefix()}user` WHERE mobile_phone='$user' AND active='1' LIMIT 1";
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

    //ajax注册
    function ajax_user_register($data = array()) {
        $json = Import::json();
        $result = array('error' => 2, 'message' => '传送的数据为空!');
        if (empty($data['fromAttr']))
            die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
        unset($data);

        $uid = $this->Session->read('User.uid');
        $wecha_id = $this->Session->read('User.wecha_id');
        if (!($uid > 0)) {
            $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id ='$wecha_id' LIMIT 1";
            $uid = $this->App->findvar($sql);
            if (!($uid > 0)) {
                die("无法登录代理用户，麻烦通知我们管理员，谢谢");
            }
        }
        //以下字段对应评论的表单页面 一定要一致
        $datas['user_rank'] = $fromAttr->user_rank; //用户级别
        $datas['mobile_phone'] = $fromAttr->username; //用户名
        if (empty($datas['mobile_phone'])) {
            $result = array('error' => 2, 'message' => '请输入手机号码作为登录帐号！');
            if (empty($data['fromAttr']))
                die($json->encode($result));
        }
        if (preg_match("/1[3458]{1}\d{9}$/", $datas['mobile_phone'])) {
            
        } else {
            $result = array('error' => 2, 'message' => '手机号码不合法，请重新输入！');
            if (empty($data['fromAttr']))
                die($json->encode($result));
        }
        //检查该手机是否已经使用了
        $mobile_phone = $datas['mobile_phone'];
        $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id !='$uid' AND mobile_phone='$mobile_phone'";
        $uuid = $this->App->findvar($sql);
        if ($uuid > 0) {
            $result = array('error' => 2, 'message' => '抱歉，该手机号码已经被使用了！');
            if (empty($data['fromAttr']))
                die($json->encode($result));
        }

        $datas['password'] = $fromAttr->password;
        if (empty($datas['password'])) {
            $result = array('error' => 2, 'message' => '用户密码不能为空！');
            if (empty($data['fromAttr']))
                die($json->encode($result));
        }
        $rp_pass = $fromAttr->rp_pass;
        if ($rp_pass != $datas['password']) {
            $result = array('error' => 2, 'message' => '两次密码不相同！');
            if (empty($data['fromAttr']))
                die($json->encode($result));
        }
        $datas['password'] = md5($datas['password']);

        /* if(!($datas['user_rank']>0)) */$datas['user_rank'] = 10;
        /* 		$yyy = $fromAttr->yyy;
          $mmm = $fromAttr->mmm;
          $ddd = $fromAttr->ddd;
          $datas['birthday'] = $yyy.'-'.$mmm.'-'.$ddd;
          $datas['sex'] = $fromAttr->sex; */

        //$regcode = $fromAttr->regcode;
        /* $regcode = '';
          if(!empty($regcode)){
          //检查该注册码是否有效
          $sql = "SELECT tb1.bonus_id FROM `{$this->App->prefix()}user_coupon_list` AS tb1 LEFT JOIN `{$this->App->prefix()}user_coupon_type` AS tb2 ON tb1.type_id = tb2.type_id WHERE tb1.bonus_sn='$regcode' AND tb1.is_used='0' LIMIT 1";
          $uuid = $this->App->findvar($sql);
          if($uuid > 0){

          }else{
          $result = array('error' => 2, 'message' => '请检查该注册码是否有效!');
          die($json->encode($result));
          }
          }

          $emails = '';
          if(!empty($emails)){
          $sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails' AND user_rank='10'";
          $dbemail = $this->App->findvar($sql);
          if(!empty($dbemail)){
          $result = array('error' => 2, 'message' => '该电子邮箱已经被使用了!');
          die($json->encode($result));
          }
          } */
        $ip = Import::basic()->getip();
        $reg_ip = $ip ? $ip : '0.0.0.0';
        //$datas['reg_time'] = mktime();
        //$datas['reg_from'] = Import::ip()->ipCity($ip);
        //$datas['last_login'] = mktime();
        $datas['last_ip'] = $reg_ip;
        $datas['active'] = 0;
        if ($this->App->update('user', $datas, 'user_id', $uid)) {
            /* $this->Session->write('Agent.username',$uname);
              $this->Session->write('User.uid',$uid);
              $this->Session->write('Agent.active',$datas['active']);
              $this->Session->write('Agent.rank','10');
              $this->Session->write('Agent.lasttime',$datas['last_login']);
              $this->Session->write('Agent.lastip',$datas['last_ip']);

              //注册码表
              if(!empty($regcode)){
              $this->App->insert('user_regcode',array('code'=>$regcode,'uid'=>$uid,'addtime'=>mktime()));
              $this->App->update('user_coupon_list',array('is_used'=>'1','user_id'=>$uid,'used_time'=>mktime()),'bonus_sn',$regcode);
              } */

            $result = array('error' => 0, 'message' => '登记成功，正等待管理员审核!');
            unset($datas, $datass);
        } else {
            $result = array('error' => 2, 'message' => '登记失败!');
        }
        die($json->encode($result));
    }

    //ajax删除用户收货地址
    function ajax_delress($id = 0) {
        $uid = $this->Session->read('User.uid');
        if (empty($uid))
            die("请您先登录！");
        if (empty($id))
            die("非法删除！");

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

    function ajax_updateinfo($data = array()) {
        $json = Import::json();
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $result = array('error' => 3, 'message' => '先您先登录!');
            die($json->encode($result));
        }

        $result = array('error' => 2, 'message' => '传送的数据为空!');
        if (empty($data['fromAttr']))
            die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
        unset($data);

        //
        /* $emails = $fromAttr->email;
          if(!empty($emails)){
          $sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails' LIMIT 1";
          $dbemail = $this->App->findvar($sql);
          if(!empty($dbname)&&dbemail !=$emails){
          $result = array('error' => 4, 'message' => '不能更改这个电子邮箱,已经被使用!');
          die($json->encode($result));
          }
          } */
        //$datas['sex'] = $fromAttr->sex;
        //$datas['email'] = $emails;
        //$datas['birthday'] = ($fromAttr->yes).'-'.($fromAttr->mouth).'-'.($fromAttr->day);
        //$datas['avatar'] = $fromAttr->avatar; //身份证
        //$datas['nickname'] = $fromAttr->nickname;
        ///$datas['qq'] = $fromAttr->qq;
        //$datas['office_phone'] = $fromAttr->office_phone;
        //检查当前用户是否购买（已经开通分销）
        $uk = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1");
        if ($uk == '1') {
            $datas['user_rank'] = '1';
            $result = array('error' => 4, 'message' => '您必须购买后才能开通店铺哦！');
            die($json->encode($result));
        }
        $datas['is_salesmen'] = '1';
        $datas['mobile_phone'] = $fromAttr->mobile_phone;
        $datas['question'] = $fromAttr->question; //店铺名称
        $datas['answer'] = $fromAttr->answer;
        if (empty($datas['question'])) {
            $result = array('error' => 4, 'message' => '店铺名称必须填写！');
            die($json->encode($result));
        }
        if (empty($datas['mobile_phone'])) {
            $result = array('error' => 4, 'message' => '手机必须填写！');
            die($json->encode($result));
        }
        if (empty($datas['answer'])) {
            $result = array('error' => 4, 'message' => '姓名必须填写！');
            die($json->encode($result));
        }

        if ($this->App->update('user', $datas, 'user_id', $uid)) {
            //unset($datas);
        }

        $sql = "SELECT id FROM `{$this->App->prefix()}udaili_siteset` WHERE uid='$uid' LIMIT 1";
        $id = $this->App->findvar($sql);

        $data = array();
        $data['uid'] = $uid;
        $data['sitename'] = $datas['question'];
        $data['sitetitle'] = $datas['question'];

        if ($id > 0) {
            $this->App->update('udaili_siteset', $data, 'id', $id);
        } else {
            $this->App->insert('udaili_siteset', $data);
        }
        unset($datas, $datas);
        $this->action('common', 'get_daili_info', 'true');
        $result = array('error' => 0, 'message' => '您的信息已经提交，请等待审核通过!');
        die($json->encode($result));
    }

    function ajax_get_ress($data = array()) {
        $type = $data['type'];
        $parent_id = $data['parent_id'];
        if (empty($type) || empty($parent_id)) {
            exit;
        }
        $sql = "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' AND parent_id='$parent_id' ORDER BY region_id ASC";
        $rt = $this->App->find($sql);
        if (!empty($rt)) {
            if ($type == 2) {
                $str = '<option value="0">选择城市</option>';
            } else if ($type == 3) {
                $str = '<option value="0">选择区</option>';
            }

            foreach ($rt as $row) {
                $str .='<option value="' . $row['region_id'] . '">' . $row['region_name'] . '</option>' . "\n";
            }
            die($str);
        }
    }

    function ajax_get_ge_peisong($data = array()) {
        $district_id = $data['district_id'];
        if (empty($district_id)) {
            exit;
        }

        $sql = "SELECT tb1.user_id,tb2.nickname,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.district='$district_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
        $rt = $this->App->find($sql);
        if (empty($rt)) {
            $sql = "SELECT tb1.user_id,tb3.nickname,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb1.district = tb2.region_id LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.user_id = tb3.user_id WHERE tb2.parent_id='$district_id' AND tb1.is_own='1' AND tb3.user_rank='12'";
            $rt = $this->App->find($sql);
            if (empty($rt)) {
                $sql = "SELECT tb1.user_id,tb2.nickname,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.is_own='1' AND tb2.user_rank='12'";
                $rt = $this->App->find($sql);
            }
        }

        if (!empty($rt)) {
            $str = '<option value="0">选择配送店</option>';
            foreach ($rt as $row) {
                $str .='<option value="' . $row['user_id'] . '">' . (!empty($row['nickname']) ? $row['nickname'] : $row['consignee'] . '配送店') . '</option>' . "\n";
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
            $dd['sex'] = $attrbul->sex;
            $dd['email'] = $attrbul->email;
            $dd['zipcode'] = $attrbul->zipcode;
            $dd['mobile'] = $attrbul->mobile;
            $dd['tel'] = $attrbul->tel;
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

        $id = $data['id'];
        $type = $data['type'];
        if (!empty($id) && !empty($type)) {
            switch ($type) {
                case 'delete': //删除收货地址
                    $this->App->delete('user_address', 'address_id', $id);
                    break;
                case 'setdefaut':  //设为默认收货地址
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
                    $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND address_id='$id'";
                    $rt['userress'] = $this->App->findrow($sql);
                    $rt['province'] = $this->get_regions(1);  //获取省列表
                    $rt['city'] = $this->get_regions(2, $rt['userress']['province']);  //城市
                    $rt['district'] = $this->get_regions(3, $rt['userress']['city']);  //区

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
        switch ($oid) {
            case '0':
                $str .= '未确认,';
                break;
            case '1':
                $str .= '<font color="red">取消</font>,';
                break;
            case '2':
                $str .= '确认,';
                break;
            case '3':
                $str .= '<font color="red">退货</font>,';
                break;
            case '4':
                $str .= '<font color="red">无效</font>,';
                break;
        }

        switch ($pid) {
            case '0':
                $str .= '未付款,';
                break;
            case '1':
                $str .= '已付款,';
                break;
            case '2':
                $str .= '已退款,';
                break;
        }

        switch ($sid) {
            case '0':
                $str .= '未发货';
                break;
            case '1':
                $str .= '配货中';
                break;
            case '2':
                $str .= '已发货';
                break;
            case '3':
                $str .= '部分发货';
                break;
            case '4':
                $str .= '退货';
                break;
            case '5':
                $str .= '已收货';
                break;
        }
        return $str;
    }

    function get_option($sn = 0, $oid = 0, $pid = 0, $sid = 0) {
        if (empty($sn))
            return "";
        $str = '';
        switch ($sid) {
            case '2':
                return $str = '<a href="javascript:;" name="confirm" id="' . $sn . '" class="oporder"><font color="red">确认收货</font><a>';
                break;
            case '5':
                return $str = '<font color="red">已完成</font>';
                break;
        }

        switch ($oid) {
            case '0':
                $str = '<a href="javascript:;" name="cancel_order" id="' . $sn . '" class="oporder"><font color="red">取消订单</font></a>';
                break;
            case '1':
                $str = '<font color="red">已取消</font>';
                break;
            case '2':
                $str = '<font color="red">已确认</font>';
                break;
            case '3':
                $str = '<font color="red">已退货</font>';
                break;
            case '4':
                $str = '<font color="red">无效订单</font>';
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
        for ($i = 0; $i < $num; $i++) {
            $code.= $str[mt_rand(0, strlen($str) - 1)];
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
        for ($i = 0; $i < 5; $i++) {
            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($im, mt_rand(-$width, $width), mt_rand(-$height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
        }
        // 画干扰点
        for ($i = 0; $i < 50; $i++) {
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
	
	
	
	function daifu_in($uid){

		 $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj1` WHERE uid=".$uid." limit 1");
		 $daifu_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_daifu` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
		// $allotFlag = $this->App->findvar("SELECT allotFlag FROM `{$this->App->prefix()}user_daifu` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
				  if(!empty($daifu_info)){
					  if($daifu_info['allotFlag'] > 0){
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
			  $card['futureRateType'] = 1;//费率类型 百分比
			  $card['futureRateValue'] = 0;
               			
			  
			
		
		$key = $this->random_string(16, $max=FALSE);
		
		 $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>549440153990220</agencyId>
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
		
		// error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".iconv('UTF-8', 'GBK', $xml)."\n\n", 3, './app/daili/daifu_'.date('Y-m-d').'.log');
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'./app/daili/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key,'./app/daili/549440153990220_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => '549440153990220', 
   'signData' => $signData, 
    'tranCode' => '100003'
       );
	   

	 

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";
		
		  $response = $this->curl_daifu($url,$postdata);
	
	// error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".iconv('UTF-8', 'GBK', $response)."\n\n", 3, './app/daili/daifu_'.date('Y-m-d').'.log');
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemi($encryptKey_host,'./app/daili/549440153990220.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		  //  error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/daili/daifu_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 
			 if($rt['respCode'] == "000000"){
				 if(empty($daifu_info)){
				   $this->App->insert('user_daifu', $card);
				 }else{
					 $sql = "UPDATE `{$this->App->prefix()}user_daifu` SET `uid` = ".$card['uid'].",`merchantId` = '".$card['merchantId']."',`cycleValue` = ".$card['cycleValue'].",`busiCode` = '".$card['busiCode']."',`allotFlag` = ".$card['allotFlag'].",`handleType` = ".$card['handleType']."  WHERE `uid` = ".$card['uid']." and `busiCode` = '".$card['busiCode']."'";
					 
					//  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $sql."\n\n", 3, './app/daili/sql_'.date('Y-m-d').'.log');
                        $this->App->query($sql);

					 }
				   return "success";
			 }else{
						  return  $rt['respMsg'];
						  
				 }
			 
			 
		
		}
		
		
		function daifu_in_query($uid){

		
		 $daifu_info = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_daifu` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
				
			  
			    $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$uid);
                 

			  
			
		
		$key = $this->random_string(16, $max=FALSE);
		
		 $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>549440153990220</agencyId>
				<msgType>01</msgType>
				<tranCode>100004</tranCode>
				<reqMsgId>wx'.date('Ymdhis',time()).$uid.'</reqMsgId>
				<reqDate>'.date('Ymdhis',time()).'</reqDate>		  
			</head>
			<body>
			  <merchantId>'.$daifu_info['merchantId'].'</merchantId>
			</body>
	</merchant>';
		
		
		
		 $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'./app/daili/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key,'./app/daili/549440153990220_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => '549440153990220', 
   'signData' => $signData, 
    'tranCode' => '100004'
       );
	   

	 

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/qryAuthInfo";
		
		  $response = $this->curl_daifu($url,$postdata);
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemi($encryptKey_host,'./app/daili/549440153990220.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		   // error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/daili/daifu_query_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 $rt['authResult'] = $xml_obj->body->authResult;
			 
						  return  $rt['authResult'];
						  

			 
			 
		
		}
		
		
		function daifupay_yinlian_api($info=array()){
		
		 $uid = $this->Session->read('User.uid');

		$data = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid = ".$uid);
		
		 $bank =  $this->App->findrow("SELECT * FROM `{$this->App->prefix()}bank` WHERE id=".$data['bank']);
		 
		  $sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj2_api` WHERE uid=".$uid." limit 1");
		 

       // error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']$info:'."\n".var_export($info, true)."\n\n", 3, './0auto_daifu_pay_request.log');

		$rts = $this->_get_payinfo(19);
                $pay = unserialize($rts['pay_config']); 
				
		$key = $this->random_string(16, $max=FALSE);
		
		$xml = '
				<merchant> 
		  <head> 
			<version>1.0.0</version> 
			<agencyId>'.$pay['pay_no'].'</agencyId> 
			<msgType>01</msgType> 
			<tranCode>200001</tranCode> 
			<reqMsgId>wx'.date('Ymdhis',time()).$uid.'</reqMsgId> 
			<reqDate>'.date('Ymdhis',time()).'</reqDate> 
		  </head> 
		  <body> 
			<business_code>B00302</business_code> 
			<user_id>'.$sj2['merchantId'].'</user_id> 
			<bank_code>'.$sj2['bankCode'].'</bank_code> 
			<account_type>00</account_type> 
			<account_no>'.$sj2['bankaccountNo'].'</account_no> 
			<account_name>'.trim($sj2['name']).'</account_name>
			<allot_flag>0</allot_flag> 
			<amount>'.($info['amount']*100).'</amount>
			<terminal_no>'.$pay['pay_idt'].'</terminal_no> 
			<id_type>0</id_type> 
			<ID>'.$sj2['certNo'].'</ID> 
		  </body> 
		</merchant>';
		
		
		 $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;
		 
		 
		  error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".iconv('UTF-8', 'GBK', $xml)."\n\n", 3, './app/daili/daifu_'.date('Y-m-d').'.log');
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'app/daili/549440153997077.pem');
        $encyrptKey = $this->rsasign_public($key,'app/daili/549440153997077_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => '549440153997077', 
   'signData' => $signData, 
    'tranCode' => '200001'
       );
	   
 //error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".var_export($postdata,true)."\n\n", 3, './app/daili/sj_'.date('Y-m-d').'.log');
	

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/realTimeDF";
		
		$response = $this->curl_daifu($url,$postdata);
		
		//return $response;
		
		//  if (is_array($postdata)) {
//        ksort($postdata);
//        $content = http_build_query($postdata);
//        $content_length = strlen($content);
//        $options = array(
//            'http' => array(
//                'method' => 'POST',
//                'header' =>
//                "Content-type: application/x-www-form-urlencoded\r\n" .
//                "Content-length: $content_length\r\n",
//                'content' => $content
//            )
//        );
//        $response = file_get_contents($url, false, stream_context_create($options));
//    }
	
	// error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".iconv('UTF-8', 'GBK', $response)."\n\n", 3, './app/daili/sj_'.date('Y-m-d').'.log');
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemi($encryptKey_host,'app/daili/549440153997077.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		    error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/daili/daifu_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt = array();
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['payMsgId'] = $xml_obj->head->payMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 
			
				 return $rt;
		
				 
				 
				 
				 
		 
		}
		
		
	
	function daifupay($info=array()){
		
		 $uid = $this->Session->read('User.uid');

		$data = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid = ".$uid);
		
		 $bank =  $this->App->findrow("SELECT * FROM `{$this->App->prefix()}bank` WHERE id=".$data['bank']);
		 
		  $sj2 = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj2` WHERE uid=".$uid." limit 1");
		 

       // error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']$info:'."\n".var_export($info, true)."\n\n", 3, './0auto_daifu_pay_request.log');

		$rts = $this->_get_payinfo(12);
                $pay = unserialize($rts['pay_config']); 
				
		$key = $this->random_string(16, $max=FALSE);
		
		$xml = '
				<merchant> 
		  <head> 
			<version>1.0.0</version> 
			<agencyId>'.$pay['pay_no'].'</agencyId> 
			<msgType>01</msgType> 
			<tranCode>200001</tranCode> 
			<reqMsgId>wx'.date('Ymdhis',time()).$uid.'</reqMsgId> 
			<reqDate>'.date('Ymdhis',time()).'</reqDate> 
		  </head> 
		  <body> 
			<business_code>B00302</business_code> 
			<user_id>'.$sj2['merchantId'].'</user_id> 
			<bank_code>'.$sj2['bankCode'].'</bank_code> 
			<account_type>00</account_type> 
			<account_no>'.$sj2['bankaccountNo'].'</account_no> 
			<account_name>'.trim($sj2['name']).'</account_name>
			<allot_flag>0</allot_flag> 
			<amount>'.($info['amount']*100).'</amount>
			<extra_fee>200</extra_fee> 
			<terminal_no>'.$pay['pay_idt'].'</terminal_no> 
			<id_type>0</id_type> 
			<ID>'.$sj2['certNo'].'</ID> 
		  </body> 
		</merchant>';
		
		
		 $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;
		 
		 
		 // error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".iconv('UTF-8', 'GBK', $xml)."\n\n", 3, './app/daili/sj_'.date('Y-m-d').'.log');
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'app/daili/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key,'app/daili/549440153990220_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => '549440153990220', 
   'signData' => $signData, 
    'tranCode' => '200001'
       );
	   
 //error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".var_export($postdata,true)."\n\n", 3, './app/daili/sj_'.date('Y-m-d').'.log');
	

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/realTimeDF";
		
		$response = $this->curl_daifu($url,$postdata);
		
		//return $response;
		
		//  if (is_array($postdata)) {
//        ksort($postdata);
//        $content = http_build_query($postdata);
//        $content_length = strlen($content);
//        $options = array(
//            'http' => array(
//                'method' => 'POST',
//                'header' =>
//                "Content-type: application/x-www-form-urlencoded\r\n" .
//                "Content-length: $content_length\r\n",
//                'content' => $content
//            )
//        );
//        $response = file_get_contents($url, false, stream_context_create($options));
//    }
	
	// error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".iconv('UTF-8', 'GBK', $response)."\n\n", 3, './app/daili/sj_'.date('Y-m-d').'.log');
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemi($encryptKey_host,'app/daili/549440153990220.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		   // error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/daili/sj_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt = array();
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['payMsgId'] = $xml_obj->head->payMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 
			
				 return $rt;
		
				 
				 
				 
				 
		 
		}
//	 function update_user_sj1($data = array()) {
//		 
//		 
//		  $uid = $this->Session->read('User.uid');
//	
//	//if($uid == 42){
//		      $card['uid'] = $uid;
//		      $card['merchantName'] = $data['merchantName'];
//			  $card['shortName'] = $data['shortName'];
//			  $card['city'] = $data['city'];
//			  $card['merchantAddress'] = $data['merchantAddress'];
//			  $card['servicePhone'] = $data['servicePhone'];
//			  $card['orgCode'] = $data['orgCode'];
//			  $card['merchantType'] = $data['merchantType'];
//			  $card['category'] = $data['category'];
//			  $card['corpmanName'] = $data['corpmanName'];
//			  $card['corpmanId'] = $data['corpmanId'];
//			  $card['corpmanPhone'] = $data['corpmanPhone'];
//			  $card['corpmanMobile'] = $data['corpmanMobile'];
//			  $card['corpmanEmail'] = $data['corpmanEmail'];
//			  $card['bankCode'] = $data['bankCode'];
//			  $card['bankName'] = $data['bankName'];
//			  $card['bankaccountNo'] = $data['bankaccountNo'];
//			  $card['bankaccountName'] = $data['bankaccountName'];
//			  $card['autoCus'] = $data['autoCus'];
//			  $card['remark'] = $data['remark'];
//			  
//			  
//			    
//				 
//				 
//				
//				 
//		$key = $this->random_string(16, $max=FALSE);
//		
//		 $xml = '
//				<merchant>
//					  <head>
//						  <version>1.0.0</version>
//						  <agencyId>102100000125</agencyId>
//						  <msgType>01</msgType>
//						  <tranCode>100001</tranCode>
//						  <reqMsgId>'.date('Ymdhis',time()).'</reqMsgId>
//						  <reqDate>'.date('Ymdhis',time()).'</reqDate>
//					  </head>
//					  <body>
//							<merchantName>'.$data['merchantName'].'</merchantName>
//							<shortName>'.$data['shortName'].'</shortName>
//							<city>'.$data['city'].'</city>
//							<merchantAddress>'.$data['merchantAddress'].'</merchantAddress>
//							<servicePhone>'.$data['servicePhone'].'</servicePhone>
//							<merchantType>'.$data['merchantType'].'</merchantType>
//							<category>'.$data['category'].'</category>
//							<corpmanName>'.$data['corpmanName'].'</corpmanName>
//							<corpmanId>'.$data['corpmanId'].'</corpmanId>
//							<corpmanPhone>'.$data['corpmanPhone'].'</corpmanPhone>
//							<corpmanMobile>'.$data['corpmanMobile'].'</corpmanMobile>
//							<bankCode>'.$data['bankCode'].'</bankCode>
//							<bankName>'.$data['bankName'].'</bankName>
//							<bankaccountNo>'.$data['bankaccountNo'].'</bankaccountNo>
//							<bankaccountName>'.$data['bankaccountName'].'</bankaccountName>
//							<autoCus>'.$data['autoCus'].'</autoCus>
//							<remark>'.$data['remark'].'</remark>
//					  </body>
//				</merchant>';
//      
/*        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.$xml;*/
//		
//		 error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".$xml."\n\n", 3, './app/daili/baowen'.date('Y-m-d').'.log');
//		   
//		$encryptData = $this->encrypt($xml,$key);
//        $signData = $this->rsaSign($xml,'test_pkcs8_rsa_private_key_2048.pem');
//        $encyrptKey = $this->rsasign_public($key,'test_rsa_public_key_2048.pem');
//
//		
//	  $postdata = array (
//        'encryptData' => $encryptData,
//     'encryptKey' => $encyrptKey, 
//  'agencyId' => '102100000125', 
//   'signData' => $signData, 
//    'tranCode' => '100001'
//       );
//	   
//
//	 
//
//		$url = "http://120.31.132.119/interfaceWeb/basicInfo";
//		
//		  if (is_array($postdata)) {
//        ksort($postdata);
//        $content = http_build_query($postdata);
//        $content_length = strlen($content);
//        $options = array(
//            'http' => array(
//                'method' => 'POST',
//                'header' =>
//                "Content-type: application/x-www-form-urlencoded\r\n" .
//                "Content-length: $content_length\r\n",
//                'content' => $content
//            )
//        );
//        $response = file_get_contents($url, false, stream_context_create($options));
//    }
//	
//	 error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".$response."\n\n", 3, './app/daili/sj_1'.date('Y-m-d').'.log');
//	
//	
//	$resp=explode('&', $response);
//
//		
//$first = strpos( $resp[0] ,"=");//字符第一次出现的位置
//
//
//$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);
//
//
//          
//		
//         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置
//
//
//$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);
//
// $merchantAESKey = $this->jiemi($encryptKey_host,'test_pkcs8_rsa_private_key_2048.pem');
//
//		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
//		   
//		    error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n". $xmlData."\n\n", 3, './app/daili/sj_1'.date('Y-m-d').'.log');
//
//             $xml_obj = simplexml_load_string($xmlData);
//			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
//			 $rt['respCode'] = $xml_obj->head->respCode;
//			 $rt['respMsg'] = $xml_obj->head->respMsg;
//		 
//		 
//		 if($rt['respCode'] == "000000"){
//			  $this->App->insert('user_sj1', $card);
//			 }
//		
//		var_export($rt);
//		 
//	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 //快捷API

 function encrypt($input, $key) {
	$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
	$input = $this->pkcs5_pad($input, $size);
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$data = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$data = base64_encode($data);
	return $data;
	}
 
	 function pkcs5_pad ($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
 
	 function decrypt($sStr, $sKey) {
		$decrypted= mcrypt_decrypt(
		MCRYPT_RIJNDAEL_128,
		$sKey,
		base64_decode($sStr),
		MCRYPT_MODE_ECB
	);
 
		$dec_s = strlen($decrypted);
		$padding = ord($decrypted[$dec_s-1]);
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
	 $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的  
	 openssl_public_encrypt($data,$encrypted,$pu_key);//公钥加密  
$encrypted = base64_encode($encrypted);  
return $encrypted;  

}

function random_string($length, $max=FALSE)
{
 if (is_int($max) && $max > $length)
  {
    $length = mt_rand($length, $max);
  }
  $output = '';
   
  for ($i=0; $i<$length; $i++)
  {
    $which = mt_rand(0,2);
     
    if ($which === 0)
    {
      $output .= mt_rand(0,9);
    }
    elseif ($which === 1)
    {
      $output .= chr(mt_rand(65,90));
    }
    else
    {
      $output .= chr(mt_rand(97,122));
    }
  }
  return $output;
}

	
	function jiemi($encryptKey_host,$private_key_path){
	
        $sKey = file_get_contents($private_key_path);
		openssl_private_decrypt(base64_decode($encryptKey_host),$decrypted,$sKey);//私钥解密  
		return $decrypted;
  
			}
			
			
			
			  function decode($str,$key) {
				  
				  $str = base64_decode($str);
$str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_ECB);
$block = mcrypt_get_block_size('rijndael_128', 'ecb');
$pad = ord($str[($len = strlen($str)) - 1]);
$len = strlen($str);
$pad = ord($str[$len-1]);
return substr($str, 0, strlen($str) - $pad);


    }
	
	
	
	
	 function _get_payinfo($id = 0) {
        if ($id == '4') { //微信支付
            $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id' LIMIT 1");

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
            $rt = $this->App->findrow("SELECT * FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='$id'");
        }
        return $rt;
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
	
	
	
	/*function pl_gengxin(){
		
		$daifu = array();
		
		$daifu = $this->App->find("SELECT * FROM `{$this->App->prefix()}user_daifu` ");
		
		foreach($daifu as $k => $row){
			
		
		
		$this->pl_daifu_in($row['uid']);
	
		}
	
		
		}
	
	function pl_daifu_in($uid){

		 $sj1 = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_sj1` WHERE uid=".$uid." limit 1");
		 $sj3 = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}user_daifu` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
		 $allotFlag = $this->App->findvar("SELECT allotFlag FROM `{$this->App->prefix()}user_daifu` WHERE uid=".$uid." and busiCode ='B00302'  limit 1");
				  if(($sj3 > 0) && ($allotFlag > 0)){
					  return "success";
					   exit;
					  }
			  
			    $data = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$uid);
                 

				
				
				
			  $card['uid'] = $uid;
		      $card['merchantId'] = $sj1['corpmanMobile'];
			  if($allotFlag == 0){
			  $card['handleType'] = 1;
			  }else{
				  $card['handleType'] = 0;
				  }
			  $card['cycleValue'] = 2;//结算周期 D+0
			  $card['allotFlag'] = 1;
			  $card['busiCode'] = "B00302";
			  $card['futureRateType'] = 1;//费率类型 百分比
			  $card['futureRateValue'] = 0;
               			
			  
			
		
		$key = $this->random_string(16, $max=FALSE);
		
		 $xml = '
	<merchant>
			<head>
				<version>1.0.0</version>
				<agencyId>549440153990220</agencyId>
				<msgType>01</msgType>
				<tranCode>100003</tranCode>
				<reqMsgId>'.date('Ymdhis',time()).'</reqMsgId>
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
		
		 error_log('['.date('Y-m-d H:i:s').']官方返回1:'."\n".iconv('UTF-8', 'GBK', $xml)."\n\n", 3, './app/daili/pldaifu_'.date('Y-m-d').'.log');
		   
		$encryptData = $this->encrypt($xml,$key);
        $signData = $this->rsaSign($xml,'./app/daili/549440153990220.pem');
        $encyrptKey = $this->rsasign_public($key,'./app/daili/549440153990220_pub.pem');

		
	  $postdata = array (
        'encryptData' => $encryptData,
     'encryptKey' => $encyrptKey, 
  'agencyId' => '549440153990220', 
   'signData' => $signData, 
    'tranCode' => '100003'
       );
	   

	 

		$url = "http://epay.gaohuitong.com:8083/interfaceWeb/busiInfo";
		
		  $response = $this->curl_daifu($url,$postdata);
	
	 error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".iconv('UTF-8', 'GBK', $response)."\n\n", 3, './app/daili/pldaifu_'.date('Y-m-d').'.log');
	
	
	$resp=explode('&', $response);

		
$first = strpos( $resp[0] ,"=");//字符第一次出现的位置


$encryptData_host = substr( $resp[0] ,$first+1,strlen($resp[0])+1);//截取字符串，形式如：substr($string,0,-3);


          
		
         $first = strpos( $resp[1] ,"=");//字符第一次出现的位置


$encryptKey_host = substr( $resp[1] ,$first+1,strlen($resp[1])+1);//截取字符串，形式如：substr($string,0,-3);

 $merchantAESKey = $this->jiemi($encryptKey_host,'./app/daili/549440153990220.pem');

		   $xmlData = $this->decode($encryptData_host,$merchantAESKey);
		   
		    error_log('['.date('Y-m-d H:i:s').']官方返回3:'."\n". iconv('UTF-8', 'GBK', $xmlData)."\n\n", 3, './app/daili/pldaifu_'.date('Y-m-d').'.log');

             $xml_obj = simplexml_load_string($xmlData);
			 $rt['reqMsgId'] = $xml_obj->head->reqMsgId;
			 $rt['respCode'] = $xml_obj->head->respCode;
			 $rt['respMsg'] = $xml_obj->head->respMsg;
			 
			 if($rt['respCode'] == "000000"){
				 if($card['handleType'] == 0){
				   $this->App->insert('user_daifu', $card);
				 }else{
					 $sql = "UPDATE `{$this->App->prefix()}user_daifu` SET `uid` = ".$card['uid'].",`merchantId` = '".$card['merchantId']."',`cycleValue` = ".$card['cycleValue'].",`busiCode` = '".$card['busiCode']."',`allotFlag` = ".$card['allotFlag'].",`handleType` = ".$card['handleType']."  WHERE `uid` = ".$card['uid']." and `busiCode` = '".$card['busiCode']."'";
					 
					//  error_log('['.date('Y-m-d H:i:s').']语句:'."\n". $sql."\n\n", 3, './app/daili/sql_'.date('Y-m-d').'.log');
                        $this->App->query($sql);

					 }
				 
			 }
			 
			 
		
		}*/
		
		//信用卡还款认证
		 function update_user_bank_simple_instead($data = array()) {
		
		
						  
        //$newpass = $data['pass'];
		 $shop_name = $data['shop_name'];
		 $address = $data['address'];
		 $uname = $data['name'];
		 $idcard = $data['card_no'];
		 $banksn = $data['bank_no'];
		  $bank_code = $data['bank_code'];
		 $mobile = $data['mobile'];
		 $yz_code = $data['yz_code'];
       
		 
		
       
        
       
        $uid = $this->Session->read('User.iuid');
		
		 $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id=".$uid." LIMIT 1";
        $userinfo = $this->App->findrow($sql);
		if(empty($userinfo)){
			 echo '请先关注公众号！';
			exit;
			}
	
	 $verfiy_yz_code = $this->Session->read('User.yz_code');

       if($yz_code  != $verfiy_yz_code){
       echo '手机验证码填写错误,请重新填写';
       exit;
       }
        $dd = array();
		
		$dd['shop_name'] = $shop_name;
		$dd['address'] = $address;
		 $dd['mobile'] = $mobile;
		   $dd['uname'] = $uname;
		     $dd['idcard'] = $idcard;
			 $dd['banksn'] = $banksn;
			 $dd['bank'] = $bank_code;
                $dd['uptime'] = mktime();
				$dd['status'] = 1;
			


        $key = 'c3b0cdac';
        $transcode = '007';
        $version = '0100';
        $ordersn = time() . $uid;
        $merchno = '201705110080808';
        $dsorderid = time() . $uid;
        $idtype = '01';
        $idcard = $idcard;
        $username = $uname;
        $bankcard = $banksn;
        $mobile = $mobile;
        $signstr = "bankcard=" . $bankcard . "dsorderid=" . $dsorderid . "idcard=" . $idcard . "idtype=" . $idtype . "merchno=" . $merchno . "mobile=" . $mobile . "ordersn=" . $ordersn . "transcode=" . $transcode . "username=" . $username . "version=" . $version;
        $sign = md5($signstr . $key);
        $postdata = jsonFormat(array('transcode' => $transcode, 'version' => $version, 'ordersn' => $ordersn, 'merchno' => $merchno, 'dsorderid' => $dsorderid, 'sign' => $sign, 'idtype' => $idtype, 'idcard' => $idcard, 'username' => $username, 'bankcard' => $bankcard, 'mobile' => $mobile));
        $url = "http://mdt.huanqiuhuiju.com:9002/authsys/api/auth/execute.do";
		
        $return = json_decode(curl_bank($url, $postdata), true);
        if ($return['returncode'] == "0000") {
			
			
			  $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$uid." LIMIT 1";
        $info = $this->App->findrow($sql);
	
		
        if ($info['id'] > 0) { //修改
            if ($this->App->update('user_bank', $dd, 'id', $info['id'])) {
                echo '提交成功';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
        } else {
			if($idcard != "37028519871116295X"){
			 $sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE idcard='".$idcard."' LIMIT 1";
        $user_bank_id = $this->App->findvar($sql);
			}
		
		
		if($user_bank_id > 0){
			 echo '此身份证已使用！请勿重复使用';
                exit;
			}else{
			
            $dd['uid'] = $uid;
			 $dd['yijian'] = "";
            if ($this->App->insert('user_bank', $dd)) {
				
                echo '提交成功';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
			}
        }
			
			
			
		}else{
			echo $return['errtext'];
			exit;
			}
      
    }
	
	
	
	
	 //修改提款信息(简洁版)
    function update_user_bank_simple($data = array()) {
		
		
						  
        //$newpass = $data['pass'];
		 $shop_name = $data['shop_name'];
		 $address = $data['address'];
		 $uname = $data['name'];
		 $idcard = $data['card_no'];
		 $banksn = $data['bank_no'];
		  $bank_code = $data['bank_code'];
		 $mobile = $data['mobile'];
		 $yz_code = $data['yz_code'];
       
		 
		
       
        
       
        $uid = $this->Session->read('User.uid');
		
		 $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id=".$uid." LIMIT 1";
        $userinfo = $this->App->findrow($sql);
		if(empty($userinfo)){
			 echo '请先关注公众号！';
			exit;
			}
	
	 $verfiy_yz_code = $this->Session->read('User.yz_code');

       if($yz_code  != $verfiy_yz_code){
       echo '手机验证码填写错误,请重新填写';
       exit;
       }
        $dd = array();
		
		$dd['shop_name'] = $shop_name;
		$dd['address'] = $address;
		 $dd['mobile'] = $mobile;
		   $dd['uname'] = $uname;
		     $dd['idcard'] = $idcard;
			 $dd['banksn'] = $banksn;
			 $dd['bank'] = $bank_code;
                $dd['uptime'] = mktime();
				$dd['status'] = 1;
       
			if(1){
			
			  $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid=".$uid." LIMIT 1";
        $info = $this->App->findrow($sql);
	
		
        if ($info['id'] > 0) { //修改
            if ($this->App->update('user_bank', $dd, 'id', $info['id'])) {
                echo '提交成功';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
        } else {
			//if($idcard != "37028519871116295X"){
			 $sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE idcard='".$idcard."' LIMIT 1";
        $user_bank_id = $this->App->findvar($sql);
			//}
		
		
		if($user_bank_id > 0){
			 echo '此身份证已使用！请勿重复使用';
                exit;
			}else{
			
            $dd['uid'] = $uid;
			 $dd['yijian'] = "";
            if ($this->App->insert('user_bank', $dd)) {
				
				 //查看是否有父级
                $sql = "SELECT parent_uid FROM `{$this->App->prefix() }user_tuijian` WHERE uid = '$uid' LIMIT 1";
                $p = $this->App->findvar($sql);
				
				if($p > 0){
					  $tj_yongjin = $this->App->findvar("SELECT tj_yongjin FROM `{$this->App->prefix() }systemconfig` WHERE type='basic' LIMIT 1");
					//查找父级的详细信息
                   // $puser = $this->App->findrow("SELECT * FROM `{$this->App->prefix() }user` WHERE user_id='$p' LIMIT 1");
					
					  $sql = "UPDATE `{$this->App->prefix() }user` SET `yongjin` = `yongjin`+$tj_yongjin  WHERE user_id = '$p'";
					
					  $this->App->query($sql);
					  
					     $thismonth = date('Y-m-d', mktime());
                        $thism = date('Y-m', mktime());
					  $this->App->insert('user_money_change', array('buyuid' => $uid, 'order_sn' => $uid, 'thismonth' => $thismonth, 'thism' => $thism, 'money' => $tj_yongjin, 'changedesc' => '推荐注册,实名认证返佣金', 'time' => mktime(), 'uid' => $p));
					  
					  
					    $sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1' LIMIT 1";
              $rts = $this->App->findrow($sql);
			
					   $pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix() }user` WHERE user_id='$p' LIMIT 1");
					   $this->action('api', 'send', array('openid' => $pwecha_id, 'appid' => $rts['appid'], 'appsecret' => $rts['appsecret'],'money' => $tj_yongjin), 'shimingreturnmoney');
					  
					}
				
                echo '提交成功';
                exit;
            } else {
                echo '提交失败';
                exit;
            }
			}
        }
			
			
			
		}else{
			echo $return['errtext'];
			exit;
			}
      
    }
	
	

}

?>