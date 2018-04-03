<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 * ************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
require_once('../load.php');
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <?php
//计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            //商户订单号
            $get_content = $_GET;
            $out_trade_no = $_GET['out_trade_no']; //获取订单号
            $forpayid = $out_trade_no;
            $trade_no = $_GET['trade_no'];  //获取支付宝交易号

            $total_fee = $_GET['total_fee'];  //获取总价格
            $price = $total_fee;
            $buyer = $_GET['buyer_email'];
            //交易状态
            $trade_status = $_GET['trade_status'];


            if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //记录到文本日志
                logText($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $get_content);
          
                //判断是否是在线报名支付
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
             
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            } else {
                //记录到文本日志
                 
                  logText($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $get_content);
                echo "trade_status=".$_GET['trade_status'];
            }
            
            echo "验证成功<br />";
         
            header('Location: ' . str_replace('pay/', '', SITE_URL)  . 'user.php');
            // 
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            //记录到文本日志

            logText($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $get_content);
            echo "验证失败";
        }

        function logText($forpayid, $trade_no, $price, $buyer, $pay_time, $trade_status, $get_content) {
            $filename = 'logs/' . date('Y-m-d') . '-' . $forpayid . '-notify-paid.txt';
            !is_dir('logs') ? mkdir('logs', 0777) : '';
            file_put_contents($filename, "\n" . date('Y-m-d H:i:s') . "\n" . "订单号:" . $forpayid .
                    "\n" . "交易号:" . $trade_no . "\n" . "总金额:" . $price . "\n" . "购买者:" . $buyer . "\n" . "支付时间:" . $pay_time .
                    "\n" . "支付状态:" . $trade_status . "\n" . "GET详情:\n----------------------------\n", FILE_APPEND);
            foreach ($get_content as $m_key => $m_value) {
                $key = $m_key;
                $value = $m_value;
                file_put_contents($filename, $key . '=>' . $value . "\n", FILE_APPEND);
            }
        }
        ?>
        <title>支付宝即时到账交易接口</title>
    </head>
    <body>
    </body>
</html>