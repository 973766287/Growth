<?php
class DaifuController extends Controller
{
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
//000000000101038
    public function __construct()
    {
        $this->user_name = '000000000101038';          //测试的用户名
        $this->merchant_id = '000000000101038';     //测试的商户号
		 $this->private_key_pw = '123456';           //私钥密码
        //$this->pfx_path = "000000000101010.pfx";     //测试的密钥文件路径
        	$this->pfx_path = "000000000101038.pfx";     //测试的密钥文件路径
        $this->url = 'https://rps.gaohuitong.com:8443/d/merchant/';                //测试的接口地址
        /*
        $this->user_name = '000000000100323';       //正式的用户名
        $this->merchant_id = '000000000100323';       //正式的商户号
        $this->private_key_pw = '123456';           //私钥密码
        $this->pfx_path = "/home/webadm/wwwroot/include/txtong/GHT_XFL.pfx";        //正式的密钥文件路径
        $this->url = 'https://rps.gaohuitong.com:8443/d/merchant/';
        */
    }

    public function pay($id)
    {
		  $sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE id = ".$id;
        $info = $this->App->findrow($sql);

        error_log("--------------------------分割线---------------------\n".'['.date('Y-m-d H:i:s').']$info:'."\n".var_export($info, true)."\n\n", 3, './app/daifu/pay_request.log');
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
		if($RET_CODE == "0000"){
			$dd['state'] = 1;
		}
		$dd['INFO_REQ_SN'] = $REQ_SN;
		$dd['INFO_RET_CODE'] = $RET_CODE;
		$dd['RET_DETAILS_RET_CODE'] = $RET_DETAIL_RET_CODE;
	    $dd['RET_DETAILS_ERR_MSG'] = $RET_DETAIL_ERR_MSG;
		$this->App->update('user_drawmoney', $dd, 'id', $id);
        $this->verify_ret('pay');
		
     if($RET_CODE == "0000"){
		  $this->jump('user.php?type=drawmoney'); 
		 }
		 
    }

    public function query($info)
    {
        $this->set_data($info, 'query');
        $this->curl_access($this->url);
        return $this->verify_ret('query');
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
        error_log('['.date('Y-m-d H:i:s').']$xml_obj:'."\n".iconv('UTF-8', 'GBK', var_export($xml_obj, true))."\n\n", 3, './app/daifu/pay_request.log');

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
            if ($info['INFO_RET_CODE'] == '0000') {
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
                        <BUSINESS_CODE>09400</BUSINESS_CODE>
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
                        <VERSION>03</VERSION>
                        <DATA_TYPE>2</DATA_TYPE>
                        <REQ_SN>'.$info['order_id'].'</REQ_SN>
                        <USER_NAME>'.$this->user_name.'</USER_NAME>
                        <SIGNED_MSG></SIGNED_MSG>
                    </INFO>
                    <BODY>
                        <QUERY_TRANS>
                            <QUERY_SN>'.$info['order_id'].'</QUERY_SN>
                        </QUERY_TRANS>
                    </BODY>
                </GHT>';
        }

        $xml = str_replace(array(' ', "\n", "\r"), '', $xml);
        $xml = '<?xml version="1.0" encoding="GBK"?>'.$xml;

        $sign_data = str_replace('<SIGNED_MSG></SIGNED_MSG>', '', $xml);
		
        $sign = $this->create_sign($sign_data);
	
		
        $xml = str_replace('<SIGNED_MSG></SIGNED_MSG>', '<SIGNED_MSG>'.$sign.'</SIGNED_MSG>', $xml);

        error_log('['.date('Y-m-d H:i:s').']$xml:'."\n".$xml."\n\n", 3, './app/daifu/pay_request.log');
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

        error_log('['.date('Y-m-d H:i:s').']签名验证结果$res:'."\n".$res."\n\n", 3, './app/daifu/pay_request.log');
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

        error_log('['.date('Y-m-d H:i:s').']官方返回2:'."\n".$ret_data."\n\n", 3, './app/daifu/pay_request.log');
        error_log('['.date('Y-m-d H:i:s').']curl_errno:'."\n".curl_errno($ch)."\n\n", 3, './app/daifu/pay_request.log');
        error_log('['.date('Y-m-d H:i:s').']curl_error:'."\n".curl_error($ch)."\n\n", 3, './app/daifu/pay_request.log');
        error_log('['.date('Y-m-d H:i:s').']curl_getinfo:'."\n".var_export(curl_getinfo($ch), true)."\n\n", 3, './app/daifu/pay_request.log');

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
}
