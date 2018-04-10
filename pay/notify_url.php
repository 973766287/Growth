<?php

require_once('../load.php');
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 * ************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if ($verify_result) {//验证成功
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //请在这里加上商户的业务逻辑程序代
    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    //商户订单号码
    $post_content = $_POST;
    $out_trade_no = $_POST['out_trade_no'];
    $forpayid = $out_trade_no;
    //支付宝交易号
    $trade_no = $_POST['trade_no'];
    //获取总价格
    $total_fee = $_POST['total_fee'];
    $price = $total_fee;
    //获取支付者的支付宝账号
    $buyer = $_POST['buyer_email'];
    //交易状态
    $trade_status = $_POST['trade_status'];
    if ($_POST['trade_status'] == 'TRADE_FINISHED') {
        //记录到文本日志
        logNot($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $post_content);
        //判断是否是在线报名支付
        $sql = "SELECT id,pay_status FROM `{$app->App->prefix()}cx_baoming_order` WHERE order_sn = '$out_trade_no' LIMIT 1";
        $isds = $app->App->findrow($sql);
       
        if (!empty($isds)) {
         
            if ($isds['pay_status'] != 1) {
                $app->action('shopping', 'baoming_pay_successs_tatus', array('order_sn' => $out_trade_no, 'status' => '1')); //修改支付状态
            }
        } else {
            $app->action('shopping', 'pay_successs_tatus2', array('order_sn' => $out_trade_no, 'status' => '1')); //修改支付状态
        }

    } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
//记录到文本日志
        logNot($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $post_content);

        //判断是否是在线报名支付

        $sql = "SELECT id,pay_status FROM `{$app->App->prefix()}cx_baoming_order` WHERE order_sn = '$out_trade_no' LIMIT 1";
 
        $isds = $app->App->findrow($sql);
        
        if (!empty($isds)) {
         
            if ($isds['pay_status'] != 1) {
                 
                $app->action('shopping', 'baoming_pay_successs_tatus', array('order_sn' => $out_trade_no, 'status' => '1')); //修改支付状态
   
            }
        } else {
       
            $app->action('shopping', 'pay_successs_tatus2', array('order_sn' => $out_trade_no, 'status' => '1')); //修改支付状态
      
        }
        //注意：
        //付款完成后，支付宝系统发送该交易状态通知
        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    echo "success";  //请不要修改或删除
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
    logNot($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $post_content);
    //验证失败
    echo "fail";
    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}


function logNot($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $post_content) {

    $filename = 'logs/' . date('Y-m-d') . '-' . $forpayid . '-notify-paid.txt';
    !is_dir('logs') ? mkdir('logs', 0777) : '';
    file_put_contents($filename, "\n" . date('Y-m-d H:i:s') . "\n" . "订单号:" . $forpayid .
            "\n" . "交易号:" . $trade_no . "\n" . "总金额:" . $price . "\n" . "购买者:" . $buyer . "\n" . "支付时间:" . $pay_time .
            "\n" . "支付状态:" . $trade_status . "\n" . "POST详情:\n----------------------------\n", FILE_APPEND);
    foreach ($post_content as $m_key => $m_value) {
        $key = $m_key;
        $value = $m_value;
        file_put_contents($filename, $key . '=>' . $value . "\n", FILE_APPEND);
    }
}

?>