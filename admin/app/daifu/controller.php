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
        '0000' => array(3, '�������'),
        '0001' => array(2, 'ϵͳ����ʧ��'),
        '0002' => array(2, '�ѳ���'),
        '1000' => array(2, '�������ݼ�����ߴ����'), //�������ݼ����ش�����Ϣ
        '1001' => array(2, '���Ľ��ʹ�'),
        '1002' => array(2, '�޷���ѯ���ý��ף������ط�'),
        '2000' => array(3, 'ϵͳ���ڶ����ݴ���'),
        '2007' => array(3, '�ύ���д���'),
        '3028' => array(2, 'ϵͳ��æ'),
        '3045' => array(2, 'Э��δ��Ч'), //������Э��ͬ��
        '3097' => array(2, '������֧�ֻ����̻���֧�ִ�����'),
    );
    private $msg_query_head = array(
        '0000' => array(3, '�������'),
        '0001' => array(2, 'ϵͳ����ʧ��'),
        '0002' => array(2, '�ѳ���'),
        '1000' => array(2, '�������ݼ�����ߴ����'), //�������ݼ����ش�����Ϣ
        '1001' => array(2, '���Ľ��ʹ�'),
        '1002' => array(2, '�޷���ѯ���ý��ף������ط�'),
        '2000' => array(3, 'ϵͳ���ڶ����ݴ���'),
        '2001' => array(3, '�ȴ��̻����'),
        '2002' => array(2, '�̻���˲�ͨ��'),
        '2003' => array(3, '�ȴ��߻�ͨ����'),
        '2004' => array(2, '�߻�ͨ��ͨ������'),
        '2005' => array(3, '�ȴ��߻�ͨ����'),
        '2006' => array(2, '�߻�ͨ��ͨ������'),
        '2007' => array(3, '�ύ���д���'),
    );
    private $msg_query_detail = array(
        '0000' => array(1, '���׳ɹ�'),
        '3001' => array(2, '�鿪����ԭ��'),
        '3002' => array(2, 'û�տ�'),
        '3003' => array(2, '����ж�'),
        '3004' => array(2, '��Ч����'),
        '3005' => array(2, '�ܿ����밲ȫ���ܲ�����ϵ'),
        '3006' => array(2, '�ѹ�ʧ��'),
        '3007' => array(2, '���Կ�'),
        '3008' => array(2, '����'),
        '3009' => array(2, '�޴��˻�'),
        '3010' => array(2, '���ڿ�'),
        '3011' => array(2, '�����'),
        '3012' => array(2, '������ֿ��˽��еĽ���'),
        '3013' => array(2, '��������޶�'),
        '3014' => array(2, 'ԭʼ����ȷ'),
        '3015' => array(2, '����ȡ���������'),
        '3016' => array(2, '�ѹ�ʧ��'),
        '3017' => array(2, '�˻��Ѷ���'),
        '3018' => array(2, '���廧'),
        '3019' => array(2, 'ԭ�����ѱ�ȡ�������'),
        '3020' => array(2, '�˻�����ʱ����'),
        '3021' => array(2, 'δ������������'),
        '3022' => array(2, '���ۺ�������'),
        '3023' => array(2, '���մ���Ľ��ղ���֧ȡ'),
        '3024' => array(2, '�����л����ڴ���'),
        '3025' => array(2, 'PIN��ʽ����'),
        '3026' => array(2, '������������ϵͳʧ��'),
        '3027' => array(2, 'ԭʼ���ײ��ɹ�'),
        '3028' => array(3, 'ϵͳæ�����Ժ����ύ'),
        '3029' => array(2, '�����ѱ�����'),
        '3030' => array(2, '�˺Ŵ���'),
        '3031' => array(2, '�˺Ż�������'),
        '3032' => array(2, '�˺Ż��Ҳ���'),
        '3033' => array(2, '�޴�ԭ����'),
        '3034' => array(2, '�ǻ����˺ţ���Ϊ���˺�'),
        '3035' => array(2, '�Ҳ���ԭ��¼'),
        '3036' => array(2, '���Ҵ���'),
        '3037' => array(2, '�ſ�δ��Ч'),
        '3038' => array(2, '��ͨ�һ�'),
        '3039' => array(2, '�˻��ѹػ�'),
        '3040' => array(2, '������'),
        '3041' => array(2, '�Ǵ��ۻ�'),
        '3042' => array(2, '���׽��С�ڸô��ֵ����֧ȡ���'),
        '3043' => array(2, 'δ������ǩԼ'),
        '3044' => array(2, '��ʱ�ܸ�'),
        '3045' => array(2, '��ͬ��Э�飩����Э����ﲻ����'),
        '3046' => array(2, '��ͬ��Э�飩�Ż�û����Ч'),
        '3047' => array(2, '��ͬ��Э�飩���ѳ���'),
        '3048' => array(2, 'ҵ���Ѿ����㣬���ܳ���'),
        '3049' => array(2, 'ҵ���ѱ��ܾ������ܳ���'),
        '3050' => array(2, 'ҵ���ѳ���'),
        '3051' => array(2, '�ظ�ҵ��'),
        '3052' => array(2, '�Ҳ���ԭҵ��'),
        '3053' => array(2, '������ִ��δ���涨��̻�ִ���ޣ�M�գ�'),
        '3054' => array(2, '������ִ�������涨���ִ���ޣ�N�գ�'),
        '3055' => array(2, '����ͨ��ҵ���ۼƽ����涨���'),
        '3056' => array(2, '��Ʊ'),
        '3057' => array(2, '�˻�״̬����'),
        '3058' => array(2, '����ǩ����֤���'),
        '3097' => array(2, 'ϵͳ���ܶԸ��˺Ž��д���'),
        '3999' => array(3, '����ʧ�ܣ�������Ϣ������'), //���ڲ�����ȷ��������������Ϊ�÷�����
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
        $this->user_name = '000000000101038';          //���Ե��û���
        $this->merchant_id = '000000000101038';     //���Ե��̻���
		 $this->private_key_pw = '123456';           //˽Կ����
        //$this->pfx_path = "000000000101010.pfx";     //���Ե���Կ�ļ�·��
        	$this->pfx_path = "000000000101038.pfx";     //���Ե���Կ�ļ�·��
        $this->url = 'https://rps.gaohuitong.com:8443/d/merchant/';                //���ԵĽӿڵ�ַ
        /*
        $this->user_name = '000000000100323';       //��ʽ���û���
        $this->merchant_id = '000000000100323';       //��ʽ���̻���
        $this->private_key_pw = '123456';           //˽Կ����
        $this->pfx_path = "/home/webadm/wwwroot/include/txtong/GHT_XFL.pfx";        //��ʽ����Կ�ļ�·��
        $this->url = 'https://rps.gaohuitong.com:8443/d/merchant/';
        */
    }

    public function pay($id)
    {
		  $sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE id = ".$id;
        $info = $this->App->findrow($sql);

        error_log("--------------------------�ָ���---------------------\n".'['.date('Y-m-d H:i:s').']$info:'."\n".var_export($info, true)."\n\n", 3, './app/daifu/pay_request.log');
        $this->set_data($info, 'pay');
		
        $this->curl_access($this->url);
		
		
		$xml = simplexml_load_string($this->ret_data); //���� SimpleXML����
	
		 
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
     * code�����壺
     * 1��֧���ɹ�
     * 2��֧��ʧ��
     * 3���������ȷ
     */
    private function verify_ret($type)
    {
        if (trim($this->ret_data) == '') {
            return 'code=3&msg=�ٷ�����Ϊ��';
        }

        $xml_obj = @simplexml_load_string($this->ret_data);
        if (empty($xml_obj->INFO)) {
            return 'code=3&msg=�ٷ����ظ�ʽ����';
        }
        $info = (array)$xml_obj->INFO;
        error_log('['.date('Y-m-d H:i:s').']$xml_obj:'."\n".iconv('UTF-8', 'GBK', var_export($xml_obj, true))."\n\n", 3, './app/daifu/pay_request.log');

        //У��ǩ��
        $sign_data = preg_replace('/<SIGNED_MSG>(.+)<\/SIGNED_MSG>/', '', $this->ret_data);
        preg_match('/<SIGNED_MSG>(.+)<\/SIGNED_MSG>/', $this->ret_data, $match);
        $verify_result = $this->verify_sign($sign_data, $match[1]);
        if ($verify_result !== 1) {
            return 'code=3&msg=ǩ��У�����';
        }

        //����������
        $result = 'code=3&msg=δ֪���';
        if ($type == 'pay') {
            if ($info['INFO_RET_CODE'] == '0000') {
                $ret_code = (string)$xml_obj->BODY->RET_DETAILS->RET_DETAIL->RET_CODE;
                if ($ret_code == '0000') {
                    $result = 'code=1&msg=���׳ɹ�';
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
                $result = 'code=3&msg=�����ѽ��գ������ͨ����ѯ���׽ӿڻ�ȡ';
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
        $data = iconv('GBK', 'UTF-8', $data); //�߻�ͨ�Ǳ߼���ǩ������UFT-8����
        $pkey_content = file_get_contents($this->pfx_path); //��ȡ��Կ�ļ�����
		
		
        openssl_pkcs12_read($pkey_content, $certs, $this->private_key_pw); //��ȡ��Կ��˽Կ
        $pkey = $certs['pkey']; //˽Կ

        openssl_sign($data, $signMsg, $pkey, OPENSSL_ALGO_SHA1); //ע�����ɼ�����Ϣ
        $signMsg = bin2hex($signMsg);
        return $signMsg;
    }

    private function verify_sign($data, $sign) {
        $data = iconv('GBK', 'UTF-8', $data); //�߻�ͨ�Ǳ߼���ǩ������UFT-8����
        $sign = $this->HexToString($sign);

        $public_key_id = openssl_pkey_get_public($this->public_key);
        $res = openssl_verify($data, $sign, $public_key_id);   //��֤�����1����֤�ɹ���0����֤ʧ��

        error_log('['.date('Y-m-d H:i:s').']ǩ����֤���$res:'."\n".$res."\n\n", 3, './app/daifu/pay_request.log');
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
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);    //�߻�ͨ�Ǳߵİ汾
        }

        $ret_data = trim(curl_exec($ch));

        error_log('['.date('Y-m-d H:i:s').']�ٷ�����2:'."\n".$ret_data."\n\n", 3, './app/daifu/pay_request.log');
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
