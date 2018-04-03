<?php

class YuyueController extends Controller {

    function __construct() {
        $this->css('content.css');
        $this->css(array('content.css', 'calendar.css'));  //look  添加时间显示样式calendar.css
        $this->js(array('calendar.js', 'calendar-setup.js', 'calendar-zh.js'));  //look  添加时间显示特效js
        $this->set("url", $url);
    }

    function check_priv($url = '') {

        if (!$_SESSION['priv_user']) {
            //  $this->jump();
            if ($url) {

                $this->jump(ADMIN_URL . 'user.php?type=priv_user&url=' . urlencode($url));
                exit;
            } else {
                echo -1;
                exit;
            }
        }
    }

    function bmorderlist($data = array()) {
        // $this->check_priv();
        $id = $data['id'];
        if ($id > 0) {
            if ($this->App->delete('cx_baoming_order', 'id', $id)) {
                $this->jump(ADMIN_URL . 'yuyue.php?type=bmorderlist');
                exit;
            }
        }
        $t = isset($_GET['t']) ? $_GET['t'] : '';
        $this->set("t", $t);
        $s = isset($_GET['s']) ? $_GET['s'] : 0;
        $w = "WHERE  1 and 1 ";
        if ($t) {
            $w .=" and tb1.bid='$t'";
        }
        if ($s) {
            $w.="and pay_status='$s'";
        }
        //  $w = " where tb1.bid='$t' and pay_status=$s";
        $this->set('t', $t);
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 30;
        $start = ($page - 1) * $list;

        $sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}cx_baoming_order` AS tb1 $w";
        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);

        $sql = "SELECT tb1.*,tb2.title,tb2.img,u.nickname,u.money_ucount,u.mymoney FROM `{$this->App->prefix()}cx_baoming_order` AS tb1 "
                . "LEFT JOIN `{$this->App->prefix()}cx_baoming` AS tb2 ON tb2.id = tb1.bid "
                . "LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = tb1.user_id $w ORDER BY tb1.id DESC LIMIT $start,$list";
        $rt = $this->App->find($sql);

        $this->set('rt', $rt);
        $this->template('bmorderlist');
    }

    function baominglist($data = array()) {
        // $this->check_priv();
        $id = isset($data['id']) ? $data['id'] : '0';
        if ($id > 0) {
            if ($this->App->delete('cx_baoming', 'id', $id)) {
                $this->jump(ADMIN_URL . 'yuyue.php?type=baominglist', 0, '已删除');
                exit;
            }
        }

        $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` ORDER BY id DESC";

        $rt = $this->App->find($sql);

        $this->set('rt', $rt);
        $this->template('baominglist');
    }

    function br2nl($text) {
        $text = preg_replace('/<br\\s*?\/??>/i', chr(13), $text);
        return preg_replace('/ /i', ' ', $text);
    }

    function baominginfo($data = array()) {
        $url_href = ADMIN_URL . 'yuyue.php?type=baominglist';
        $this->check_priv($url_href);
        $this->js(array("edit/kindeditor.js"));
        $id = isset($data['id']) ? $data['id'] : '0';
        if ($id > 0) {
            $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` WHERE id='$id' LIMIT 1";
            $rt = $this->App->findrow($sql);
            $rt['description'] = $rt['description'];
            if (!empty($_POST)) {
                $_POST['description'] = $_POST['description'];
                if ($this->App->update('cx_baoming', $_POST, 'id', $id)) {
                    $this->action('common', 'showdiv', $this->getthisurl());
                }
            }
        } else {
            if (!empty($_POST)) {
                $_POST['addtime'] = mktime();
                $_POST['description'] = $_POST['description'];
                if ($this->App->insert('cx_baoming', $_POST)) {
                    $this->action('common', 'showdiv', $this->getthisurl());
                }
            }
        }

        $this->set('rt', $rt);
        $this->template('baominginfo');
    }

    function bmorderinfo($data = array()) {

        $url_href = isset($data['url']) ? ADMIN_URL . $data['url'] : ADMIN_URL . 'yuyue.php?type=bmorderlist';
        $this->check_priv($url_href);

        $id = isset($data['id']) ? $data['id'] : '0';
        if ($id > 0) {
            $sql = "SELECT tb1.*,tb2.title,tb2.img,u.nickname FROM `{$this->App->prefix()}cx_baoming_order` AS tb1 LEFT JOIN `{$this->App->prefix()}cx_baoming` AS tb2 ON tb2.id = tb1.bid LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = tb1.user_id WHERE tb1.id='$id' LIMIT 1";
            $rt = $this->App->findrow($sql);

            $this->set('rt', $rt);
            $this->template('bmorderinfo');
        } else {
            $this->jump(ADMIN_URL . 'yuyue.php?type=baominglist', 0, '订单不存在');
            exit;
        }
    }

    function ajax_import_order_data($data = array()) {
        $pagestart = $data['pagestart'];
        $pageend = $data['pageend'];
        $t = $data['t'];
        if (!($pagestart > 0))
            $pagestart = 1;
        $pagestart = $pagestart - 1;

        if (!($pageend > 0))
            $pageend = 1;

        $list = 12;
        $start = $list * $pagestart;
        $end = $list * $pageend;

        $zlist = ceil($end / $list);
        $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming_order` WHERE pay_status = '1'  and bid='$t' ORDER BY id DESC LIMIT $start,$end";
        unset($data);

        $rt = $this->App->find($sql);

        header("Content-Type:text/html;charset=utf-8");
        header("Content-type:application/vnd.ms-excel");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-Disposition:filename=已付款升级订单(page:" . ($pagestart + 1) . "-page:" . $pageend . ").xls");
        //微信昵称  真实姓名 保证金 营业额  总资金 级别 推荐代理 邮箱 电话 地址
        $str1 = '';
        if (!empty($rt)) {
            $str1 = '<table border="1" cellspacing="0" cellpadding="0">';
            $str1 .='<tr><td style="text-align:center;" width="120">编号</td><td style="text-align:center;" width="70">缴费金额</td><td style="text-align:center;" width="70">真实姓名</td><td style="text-align:center;" width="110">微信昵称</td><td style="text-align:center;">手机</td><td style="text-align:center;">微信</td><td style="text-align:center;">身份证</td><td style="text-align:center;">qq</td><td style="text-align:center;">时间</td></tr>';
            foreach ($rt as $row) {
                $order_id = $row['order_id'];

                $str1 .= '<tr><td>[' . $row['order_sn'] . ']</td><td>' . $row['order_amount'] . '&nbsp;</td><td>' . $row['uname'] . '&nbsp;</td><td>' . $row['nickname'] . '</td><td>[' . $row['upne'] . ']</td><td>[' . $row['weixin'] . ']</td><td>[' . $row['cardcode'] . ']</td><td>[' . $row['qq'] . ']</td><td>' . date('Y-m-d', $row['pay_time']) . '</td></tr>';
            }
            $str1 .='</table>';
        }
        echo $str1;
    }

}

?>