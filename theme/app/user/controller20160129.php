<?php

/*
 * 会员登录类
 */

class UserController extends Controller {

    function __construct() {
        $this->css(array('jquery_dialog.css', 'user.css', 'content.css'));
        $this->js(array('jquery.json-1.3.js', 'jquery_dialog.js', 'common.js', 'user.js', 'goods.js', 'time.js'));
    }

    //用户登录
    function login() {
        //$this->layout('login-default');
        $this->css('login.css');
        if (($this->is_login())) {
            $this->jump(SITE_URL . 'user.php');
            exit;
        } //
        $this->title("用户登录" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt['hear'][] = '<a href="' . SITE_URL . '">首页</a>&nbsp;&gt;&nbsp;';
        $rt['hear'][] = '用户登录';

        $this->set('rt', $rt);
        $this->template('user_login');
    }

    //EMAIL激活用户账号
    function email_action_user($data = array()) {
        $uname = $data[0];
        $uid = $data[1];
        $rank = $data[2];
        $time = $data[3];
        $email = $data[4];
        $data['error'] = 0;
        //if(mktime()-$time<24*3600){ //允许激活
        if ($uid > 0) {
            $sql = "SELECT user_id,active FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND email='$email' LIMIT 1";
            $rl = $this->App->findrow($sql);
            $uuid = isset($rl['user_id']) ? $rl['user_id'] : 0;
            $active = isset($rl['active']) ? $rl['active'] : 0;
            if ($active == 0) {
                if ($uuid > 0) {
                    if ($this->App->update('user', array('active' => '1'), 'user_id', $uuid)) {
                        //激活成功
                        $data['error'] = 0;
                    } else {
                        //激活失败
                        $data['error'] = 1;
                    }
                } else { //非法激活
                    $data['error'] = 2;
                }
            } else { //已经激活过了
                $data['error'] = 4;
            }
        } else { //非法激活
            $data['error'] = 2;
        }
        //}else{ //不允许激活
        //$data['error'] = 3;
        //}

        $this->set('rt', $data);
        $this->template('email_action_user');
    }

    //重设密码
    function ajax_rp_pass($data = array()) {
        $uname = $data['uname'];
        $uid = $data['uid'];
        $email = $data['email'];
        $pass = $data['pass'];
        if (empty($uname) || empty($email) || empty($pass)) {
            die("目前无法完成你的请求，请联系管理员！");
        }
        $md5pass = md5(trim($pass));
        $sql = "UPDATE `{$this->App->prefix()}user` SET `password` ='$md5pass' WHERE user_name='$uname' AND email='$email'";
        $email_config = unserialize($GLOBALS['LANG']['email_open_config']);

        //发送E-mail

        if ($this->App->query($sql)) {

            //发送E-mail
            if (!empty($email) && $email_config['findpassword'] == '1') {
                $datas['user_name'] = $uname;
                $datas['uid'] = $uid;
                $datas['email'] = $email;
                $datas['pass'] = trim($pass);
                $datas['type'] = 1;
                $this->set('email', $email);
                $this->set('is_true', true);
                //$this->action('email', 'find_password', $datas);
                $this->action('email', 'edit_password', $datas);
            }
            die("");
        } else {
            die("目前无法完成你的请求！");
        }
    }

    //用户注册
    function register() {
        //$this->layout('login-default');
        $this->css('login.css');
        if (($this->is_login())) {
            $this->jump(SITE_URL . 'user.php');
            exit;
        } //
        $this->title("用户注册" . ' - ' . $GLOBALS['LANG']['site_name']);
        $rt['hear'][] = '<a href="' . SITE_URL . '">首页</a>&nbsp;&gt;&nbsp;';
        $rt['hear'][] = '用户注册';
        $rt['province'] = $this->get_regions(1);  //获取省列表
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

    //用户后台
    function index() {
        $uid = $this->Session->read('User.uid');
        $rank = $this->Session->read('User.rank');
        //if(!($uid>0) || $rank !='10'){ $this->jump(SITE_URL.'user.php?act=login',0,'请先登录！'); exit;}

        $this->title("欢迎进入用户后台管理中心" . ' - ' . $GLOBALS['LANG']['site_name']);
        if (!($this->is_login())) {
            $this->jump(SITE_URL . 'user.php?act=login');
            exit;
        } //

        $rt['recommend10'] = $this->action('catalog', 'recommend_goods', 4);


        /*         * **************  look修改  开始  ****************************************** */
        $sql = "SELECT tb1.user_id,tb1.email,tb1.user_name,tb1.nickname,tb1.reg_time,tb1.user_id,tb1.user_rank,tb1.sex,tb1.avatar,tb1.birthday,tb1.last_login,tb1.last_ip,tb1.visit_count,tb1.qq,tb1.office_phone,tb1.home_phone,tb1.mobile_phone,tb1.active ,tb2.consignee FROM `{$this->App->prefix()}user` AS tb1 ";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_address` AS tb2 ON tb2.user_id  = tb1.user_id ";
        $sql .= " WHERE tb1.user_id ='{$uid}' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);
        /*         * **************  look修改  结束  ****************************************** */

        $sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
        $rt['userinfo']['level_name'] = $this->App->findvar($sql);  //

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid ='{$uid}'";
        $rt['userinfo']['zmoney'] = $this->App->findvar($sql);

        //当前用户的收货地址
        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        //$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id = tb1.town";
        //$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.village";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own='1' LIMIT 1";
        $rt['userress'] = $this->App->findrow($sql);

        if ($rank == '10') { //供应商
            $showtpl = 'suppliers_center';
        } else {

            $sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid ='{$uid}'";
            $rt['userinfo']['zpoint'] = $this->App->findvar($sql);

            $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2'";
            $rt['userinfo']['success_ordercount'] = $this->App->findvar($sql); //已成交订单

            $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND pay_status='0'";
            $rt['userinfo']['pay_ordercount'] = $this->App->findvar($sql);  //代付款

            $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='2'";
            $rt['userinfo']['shopping_ordercount'] = $this->App->findvar($sql);  //代收货

            $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid'";
            $rt['userinfo']['all_ordercount'] = $this->App->findvar($sql);  //所有订单

            $sql = "SELECT comment_id FROM `{$this->App->prefix()}comment` WHERE user_id='$uid'";
            $rt['userinfo']['is_comment'] = $this->App->findvar($sql);  //

            $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' AND money<0";
            $rt['userinfo']['spendmoney'] = $this->App->findvar($sql);  //

            $sql = "SELECT COUNT(og.goods_id) FROM `{$this->App->prefix()}order_goods` AS og";
            $sql .=" LEFT JOIN `{$this->App->prefix()}order_goods` AS oi ON og.order_id = oi.order_id";
            $sql .=" WHERE oi.shipping_status='5' AND oi.user_id='$uid' AND og.goods_id NOT IN(SELECT id_value FROM `{$this->App->prefix()}comment` WHERE user_id='$uid')";
            $rt['userinfo']['need_comment_count'] = $this->App->findvar($sql); //待评论商品

            $showtpl = 'user_center';
        }
        /* $dt = isset($_GET['dt'])&&intval($_GET['dt'])>0 ?  intval($_GET['dt']) : "";
          $status = isset($_GET['status']) ?  trim($_GET['status']) : "";
          $keyword = isset($_GET['kk']) ?  trim($_GET['kk']) : ""; */

        /* 用户订单
          $w_rt[] = "tb1.user_id = '$uid'";
          if(!empty($dt)){
          $w_rt[] = "tb1.add_time < '$dt'";
          }

          if(!empty($status)){
          $st = $this->action('suppliers','select_statue',$status);
          !empty($st)? $w_rt[] = $st : "";
          }
          if(!empty($keyword)){
          $w_rt[] = "(tb2.goods_name LIKE '%".$keyword."%' OR tb1.order_sn LIKE '%".$keyword."%')";
          }

          $page = 1;
          $list = 5;
          $tt = $this->__order_list_count($w_rt); //获取商品的数量
          $rt['order_count'] = $tt;

          $rt['orderpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_order_page_list',array());

          $rt['orderlist'] = $this->__order_list($w_rt,$page,$list);
          $rt['status'] = $status;
         */

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);
        $this->template($showtpl);
    }

    //ajax处理我的订单
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
            //$st = $this->action('suppliers','select_statue',$status);
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

    //用户订单列表
    /* function __order_list($w_rt=array(),$page=1,$list=5){
      if(is_array($w_rt)){
      if(!empty($w_rt)){
      $w = " WHERE ".implode(' AND ',$w_rt);
      }
      }else{
      $w = " WHERE ".$w_rt;
      }
      if(!$page) $page=1;
      $start = ($page-1)*$list;

      $sql = "SELECT tb1.order_id, tb1.order_sn, tb3.order_status, tb3.shipping_status,tb3.pay_status,tb3.is_print_shop,tb3.suppliers_id, tb1.shipping_name ,tb1.pay_name, tb1.add_time,tb1.consignee,u.user_name, (tb1.goods_amount + tb1.shipping_fee) AS total_fee FROM `{$this->App->prefix()}goods_order_info` AS tb1";
      $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_status` AS tb3 ON tb3.order_id=tb1.order_id";
      $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb2.order_id=tb1.order_id";
      $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id=tb1.shop_id"; //便利店
      $sql .=" $w GROUP BY tb3.oid,tb3.suppliers_id ORDER BY tb1.add_time DESC LIMIT $start,$list";

      $orderlist = $this->App->find($sql);
      if(!empty($orderlist)){
      foreach($orderlist as $k=>$row){
      $rt[$row['order_sn']][$k] = $row;
      $rt[$row['order_sn']][$k]['status'] = $this->action('suppliers','get_status',$row['order_status'],$row['pay_status'],$row['shipping_status']);
      $rt[$row['order_sn']][$k]['op'] = $this->get_user_order_option($row['order_id'],$row['order_status'],$row['pay_status'],$row['shipping_status'],$row['suppliers_id']);
      $sql= "SELECT goods_id,goods_name,goods_bianhao,market_price,goods_price,goods_thumb,is_gift,status FROM `{$this->App->prefix()}goods_order` WHERE order_id='$row[order_id]' AND suppliers_id='$row[suppliers_id]' ORDER BY rec_id DESC";
      $rt[$row['order_sn']][$k]['goods'] = $this->App->find($sql);
      }
      }
      unset($orderlist);
      return $rt;
      }

      function __order_list_count($w_rt=array()){
      if(is_array($w_rt)){
      if(!empty($w_rt)){
      $w = " WHERE ".implode(' AND ',$w_rt);
      }
      }else{
      $w = " WHERE ".$w_rt;
      }
      $sql = "SELECT COUNT(distinct tb1.order_id) FROM `{$this->App->prefix()}goods_order_info` AS tb1";
      $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb1.order_id=tb2.order_id ".$w;
      return $this->App->findvar($sql);
      } */

    //订单详情
    function orderinfo($orderid = "") {
        $this->title("欢迎进入用户后台管理中心" . ' - 订单详情 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        if (empty($orderid)) {
            $this->jump('user.php?act=myorder');
            exit;
        }
        $rank = $this->Session->read('User.rank');
        $sid = intval($_GET['sid']);
        //$sql= "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$orderid' ORDER BY goods_id";
        $sql = "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$orderid' AND suppliers_id = '$sid' ORDER BY rec_id DESC";
        $rt['goodslist'] = $this->App->find($sql);

        /* $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district,tb5.region_name AS towns, tb6.region_name AS villages,tb7.user_name AS peisongname FROM `{$this->App->prefix()}goods_order_info` AS tb1";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id = tb1.town";
          $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.village";
          $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb7 ON tb7.user_id = tb1.shop_id";
          $sql .=" WHERE tb1.order_id='$orderid'"; */

        $sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_info` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" WHERE tb1.order_id='$orderid'";
        $rt['orderinfo'] = $this->App->findrow($sql);

        $status = $this->get_status($rt['orderinfo']['order_status'], $rt['orderinfo']['pay_status'], $rt['orderinfo']['shipping_status']);
        //$status = $this->action('suppliers','get_status',$rt['orderinfo']['order_statuss'],$rt['orderinfo']['pay_statuss'],$rt['orderinfo']['shipping_statuss']);
        $rt['status'] = explode(',', $status);

        //订单打印
        if (isset($_GET['tt']) && $_GET['tt'] == 'print') {
            $this->layout('kong');
            //改变打印状态
            $this->set('rt', $rt);
            $this->App->update('goods_order_status', array('is_print_shop' => 1), 'oid', $rt['orderinfo']['ooid']);
            $this->template('store_order_print');
        } else {
            //商品分类列表		
            //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

            $this->set('rt', $rt);

            $this->template('user_orderinfo');
        }
    }

    //退换货订单
    function returnordergoods() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的退换货订单 - ' . $GLOBALS['LANG']['site_name']);
        $this->css('calendar.css');
        $this->js(array('time/calendar.js', 'time/calendar-setup.js', 'time/calendar-zh.js'));
        $status = isset($_GET['status']) ? trim($_GET['status']) : "";
        $keyword = isset($_GET['kk']) ? trim($_GET['kk']) : "";
        $uid = $this->Session->read('User.uid');

        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }

        //用户订单

        $w_rt[] = "tb1.user_id = '$uid'";
        $w_rt[] = "tb2.status > 0";
        $w_rt[] = "tb3.shipping_status > 0";

        if (isset($_GET['date1']) && !empty($_GET['date1']) && isset($_GET['date2']) && !empty($_GET['date2'])) {
            $t1 = strtotime($_GET['date1'] . ' ' . $_GET['t1'] . ':00:00');
            $t2 = strtotime($_GET['date2'] . ' ' . $_GET['t2'] . ':59:59');
            $w_rt[] = "tb1.add_time BETWEEN '$t1' AND '$t2'";
        } else {
            $t1 = strtotime(date('Y-m-d') . ' 00:00:01');
            $t2 = strtotime(date('Y-m-d') . ' 23:59:59');
            $w_rt[] = "tb1.add_time BETWEEN '$t1' AND '$t2'";
        }

        if (!empty($status)) {
            //$st = $this->action('suppliers','select_statue',$status);
            $st = $this->select_statue($status);
            !empty($st) ? $w_rt[] = $st : "";
        }
        if (!empty($keyword)) {
            $w_rt[] = "(tb2.goods_name LIKE '%" . $keyword . "%' OR tb1.order_sn LIKE '%" . $keyword . "%')";
        }

        if (is_array($w_rt)) {
            if (!empty($w_rt)) {
                $w = " WHERE " . implode(' AND ', $w_rt);
            }
        } else {
            $w = " WHERE " . $w_rt;
        }

        $tt = $this->__order_list_count($w_rt);

        $list = 15;
        if (!$page)
            $page = 1;
        $start = ($page - 1) * $list;
        $sql = "SELECT tb1.order_id, tb1.order_sn, tb3.order_status, tb3.shipping_status,tb3.pay_status,tb3.is_print_shop,tb3.suppliers_id, tb1.shipping_name ,tb1.pay_name, tb1.add_time,tb1.consignee,u.user_name, (tb1.goods_amount + tb1.shipping_fee) AS total_fee FROM `{$this->App->prefix()}goods_order_info` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_status` AS tb3 ON tb3.order_id=tb1.order_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_order` AS tb2 ON tb2.order_id=tb1.order_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id=tb1.shop_id"; //便利店
        $sql .=" $w GROUP BY tb3.oid,tb3.suppliers_id ORDER BY tb1.add_time DESC LIMIT $start,$list";

        $orderlist = $this->App->find($sql);
        if (!empty($orderlist)) {
            foreach ($orderlist as $k => $row) {
                $rt[$row['order_sn']][$k] = $row;
                $rt[$row['order_sn']][$k]['status'] = $this->action('suppliers', 'get_status', $row['order_status'], $row['pay_status'], $row['shipping_status']);
                $rt[$row['order_sn']][$k]['op'] = $this->get_user_order_option($row['order_id'], $row['order_status'], $row['pay_status'], $row['shipping_status'], $row['suppliers_id']);
                $sql = "SELECT goods_id,goods_name,goods_bianhao,market_price,goods_price,goods_thumb,is_gift,status FROM `{$this->App->prefix()}goods_order` WHERE order_id='$row[order_id]' AND suppliers_id='$row[suppliers_id]' AND status>0 ORDER BY rec_id DESC";
                $rt[$row['order_sn']][$k]['goods'] = $this->App->find($sql);
            }
        }
        unset($orderlist);

        $this->set('rt', $rt);
        $this->template('returnordergoods');
    }

    function error_jump() {
        $this->action('common', 'show404tpl');
    }

    //我要订购  商品列表页面
    function buylist() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我要订购 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        //排序
        $orderby = "";
        if (isset($_GET['desc'])) {
            $orderby = ' ORDER BY ' . $_GET['desc'] . ' DESC';
        } else if (isset($_GET['asc'])) {
            $orderby = ' ORDER BY ' . $_GET['asc'] . ' ASC';
        } else {
            $orderby = ' ORDER BY tb1.`goods_id` DESC';
        }
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        //查询条件
        $w = "";
        $ws = "";
        if (isset($_GET) && !empty($_GET)) {
            $art = array('cat_id', 'brand_id', 'is_on_sale');
            $comd = array();
            if (isset($_GET['is_delete']) && $_GET['is_delete'] == '1') {
                $comd[] = "tb1.is_delete='1'";
                $showbuy = "goods_list_delete";
            } else {
                $comd[] = "tb1.is_delete='0'";
                $showbuy = "buy_list";
            }

            if (isset($_GET['cat_id']) && intval($_GET['cat_id']) > 0) {
                $cids = $this->action('catalog', 'get_goods_sub_cat_ids', $_GET['cat_id']);
                $comd[] = 'tb1.cat_id IN (' . implode(",", $cids) . ')';
            }

            //供应商ID
            if (isset($_GET['uid']) && intval($_GET['uid']) > 0)
                $comd[] = 'tb1.uid=' . intval($_GET['uid']);

            /*             * *************  look注释   ********************************	
              if(isset($_GET['cat_id'])&&intval($_GET['cat_id'])>0)
              $comd[] = 'tb1.cat_id='.intval($_GET['cat_id']);
             */

            if (isset($_GET['brand_id']) && intval($_GET['brand_id']) > 0)
                $comd[] = 'tb1.brand_id=' . intval($_GET['brand_id']);
            /*
              if(isset($_GET['is_on_sale'])&&($_GET['is_on_sale']=='0'||$_GET['is_on_sale']=='1'))
              $comd[] = 'tb1.is_on_sale='.$_GET['is_on_sale']; */
            if (isset($_GET['is_goods_attr']) && !empty($_GET['is_goods_attr'])) {
                switch (trim($_GET['is_goods_attr'])) {
                    case 'is_hot':
                        $comd[] = "tb1.is_hot='1'";
                        break;
                    case 'is_new':
                        $comd[] = "tb1.is_new='1'";
                        break;
                    case 'is_best':
                        $comd[] = "tb1.is_best='1'";
                        break;
                    case 'is_promote':
                        $comd[] = "tb1.is_promote='1'";
                        break;
                    case 'is_alone_sale':
                        $comd[] = "tb1.is_alone_sale='0'";
                        break;
                    case 'is_qianggou':
                        $comd[] = "tb1.is_qianggou='1'";
                        break;
                    case 'is_jifen':
                        $comd[] = "tb1.is_jifen='1'";
                        break;
                }
            }
            $comd[] = "tb1.is_on_sale='1'";
            if (isset($_GET['keyword']) && $_GET['keyword'])
                $comd[] = "(tb1.goods_name LIKE '%" . trim($_GET['keyword']) . "%' OR tb1.goods_sn LIKE '%" . trim($_GET['keyword']) . "%')";
            //已审核
            //$comd[] = "tb1.is_check='1'";

            if (!empty($comd)) {
                $w = ' WHERE ' . implode(' AND ', $comd);
                $ws = str_replace('tb1.', '', $w);
            }
        }

        $list = 20;
        $start = ($page - 1) * $list;
        $sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` $ws";
        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);

        $sql = "SELECT tb1.goods_id,tb1.cat_id,tb1.goods_thumb, tb1.goods_sn, tb1.goods_name,tb1.buy_more_best, tb1.is_on_sale, tb1.is_promote,tb1.is_qianggou,tb1.market_price, tb1.shop_price,tb1.pifa_price,tb1.promote_price,tb1.qianggou_price,tb1.qianggou_start_date,tb1.qianggou_end_date,tb1.promote_start_date,tb1.promote_end_date, tb1.is_shipping,tb1.is_best,tb1.is_new,tb1.is_hot,tb1.is_alone_sale,tb1.goods_brief,tb2.cat_name,tb3.user_name,tb3.nickname FROM `{$this->App->prefix()}goods` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.uid=tb3.user_id";
        $sql .=" $w $orderby LIMIT $start,$list"; // echo $sql;
        $rt['list'] = $this->App->find($sql);

        //商品分类列表		
        $rt['menu'] = $this->action('catalog', 'get_goods_cate_tree');

        $this->set('rt', $rt);
        //分类列表
        $this->set('catelist', $rt['menu']);

        //品牌列表
        $this->set('brandlist', $this->action('brand', 'get_brand_cate_tree'));


        $this->template($showbuy);
    }

    /*     * **********  look添加 我要订购  结束   ******************************************* */

    //订单列表
    function orderlist() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的订单 - ' . $GLOBALS['LANG']['site_name']);
        $dt = isset($_GET['dt']) && intval($_GET['dt']) > 0 ? intval($_GET['dt']) : "";
        $status = isset($_GET['status']) ? trim($_GET['status']) : "";
        $keyword = isset($_GET['kk']) ? trim($_GET['kk']) : "";
        $uid = $this->Session->read('User.uid');
        //$rank = $this->Session->read('User.rank');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }

        //用户订单

        $w_rt[] = "tb1.user_id = '$uid'";

        if (!empty($dt)) {
            $w_rt[] = "tb1.add_time < '$dt'";
        }

        if (!empty($status)) {
            //$st = $this->action('suppliers','select_statue',$status);
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

        $rt['orderpage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_order_page_list', array($status));

        $rt['orderlist'] = $this->__order_list($w_rt, $page, $list);
        $rt['status'] = $status;


        $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND order_status='2'";
        $rt['userinfo']['success_ordercount'] = $this->App->findvar($sql); //成功订单

        $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND pay_status='0'";
        $rt['userinfo']['pay_ordercount'] = $this->App->findvar($sql); //待支付订单

        $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND shipping_status='2'";
        $rt['userinfo']['shopping_ordercount'] = $this->App->findvar($sql); //待发货订单

        $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid'";
        $rt['userinfo']['all_ordercount'] = $this->App->findvar($sql); //所有订单

        $sql = "SELECT COUNT(distinct order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' AND (shipping_status='2' OR pay_status='0' OR order_status='0')";
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
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);

        $this->template('user_orderlist');
    }

    //用户资料
    function userinfo() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的资料 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }

        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);

        $rt['province'] = $this->get_regions(1);  //获取省列表
        //当前用户的收货地址
        $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
        $rt['userress'] = $this->App->findrow($sql);

        $rt['city'] = $this->get_regions(2, $rt['userress']['province']);  //城市
        $rt['district'] = $this->get_regions(3, $rt['userress']['city']);  //区
        //$rt['town'] = $this->get_regions(4,$rt['userress']['district']); 
        //$rt['village'] = $this->get_regions(5,$rt['userress']['town']);
        //$rt['recommend10'] = $this->action('catalog','recommend_goods');
        //print_r($rt);
        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);


        //	$this->template('user_info');
        //	$sql = "SELECT `user_rank` FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' LIMIT 1";
        //	$user_rank = $this->App->findrow($sql);

        $this->template('user_info');
        exit;
        if ($rt['userinfo']['user_rank'] == '1') {
            $this->template('user_info');
        } elseif ($rt['userinfo']['user_rank'] == '10') {
            $this->template('user_info10');
        } elseif ($rt['userinfo']['user_rank'] == '11') {
            $this->template('user_info11');
        } elseif ($rt['userinfo']['user_rank'] == '12') {
            $this->template('user_info12');
        }
    }

    //收货地址
    function address() {
        $this->title("欢迎进入用户后台管理中心" . ' - 收货地址 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }

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

        $rt['province'] = $this->get_regions(1);  //获取省列表
        //配送方式
        $sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
        $rt['shippinglist'] = $this->App->find($sql);

        //当前用户的收货地址
        $sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='0'";
        $rt['userress'] = $this->App->find($sql);
        if (!empty($rt['userress'])) {
            foreach ($rt['userress'] as $row) {
                $rt['city'][$row['address_id']] = $this->get_regions(2, $row['province']);  //城市
                $rt['district'][$row['address_id']] = $this->get_regions(3, $row['city']);  //区
                //$rt['town'][$row['address_id']] = $this->get_regions(4,$row['district']);  //城镇
                //$rt['village'][$row['address_id']] = $this->get_regions(5,$row['town']);  //村
            }
        }


        $sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys, tb4.region_name AS districts,sp.shipping_name FROM `{$this->App->prefix()}user_address` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" LEFT JOIN `{$this->App->prefix()}shipping` AS sp ON sp.shipping_id = tb1.shoppingname";
//		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id = tb1.town";
//		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.village";
//		$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb7 ON tb7.user_id = tb1.shop_id"; //look 添加
        $sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own = '0' GROUP BY tb1.address_id ORDER BY tb1.address_id ASC";

        $rt['userress'] = $this->App->find($sql);


        //print_r($rt['userress']);
        //	exit;
        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);
        $this->template('user_consignee_address');
    }

    //用户密码修改
    function editpass() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->title("欢迎进入用户后台管理中心" . ' - 用户密码修改 - ' . $GLOBALS['LANG']['site_name']);

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
        $this->set('rt', $rt);
        $this->template('user_editpass');
    }

    //用户订单操作
    /* 	function ajax_order_op_user($id=0,$op=""){
      if(empty($id) || empty($op)) die("传送ID为空！");
      $ids = explode('-',$id);
      $order_id = isset($ids[0]) ? $ids[0] : 0;
      $suppliers_id = isset($ids[1]) ? $ids[1] : 0;
      if($order_id>0 && $suppliers_id>0){
      if($op=="cancel_order"){ //数量增加，返回原来值,
      $this->App->update('goods_order_status',array('order_status'=>'1'),array("suppliers_id='$suppliers_id'","order_id='$order_id'"));
      }else if($op=="confirm"){//确认收货
      $this->App->update('goods_order_status',array('shipping_status'=>'3','pay_status'=>'1'),array("suppliers_id='$suppliers_id'","order_id='$order_id'"));
      }
      }
      } */

    //用户订单操作
    function ajax_order_op_user($id = 0, $op = "") {
        if (empty($id) || empty($op))
            die("传送ID为空！");
        if ($op == "cancel_order")
            $this->App->update('goods_order_info', array('order_status' => '1'), 'order_id', $id);
        else if ($op == "confirm")
            $this->App->update('goods_order_info', array('shipping_status' => '5'), 'order_id', $id);
    }

    //我的余额
    function money($page = 1) {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的余额 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
        $rt['zmoney'] = $this->App->findvar($sql);
        $rt['zmoney'] = format_price($rt['zmoney']);
        //分页
        if (empty($page)) {
            $page = 1;
        }
        $list = 30; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
        $rt['usermoneypage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_usermoney_page_list');
        $sql = "SELECT * FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
        $rt['usermoneylist'] = $this->App->find($sql);

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);

        //ajax
        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
            echo $this->fetch('ajax_user_moneychange', true);
            exit;
        }

        $this->template('user_mymoney');
    }

    function points() {
        $this->title("欢迎进入用户后台管理中心" . ' - 积分详情 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
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
        // $rt['userpointpage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_userpoint_page_list');
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $sql = "SELECT * FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
        $rt['userpointlist'] = $this->App->find($sql); //商品列表
        $rt['page'] = $page;

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);

        //ajax
        /*  if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
          echo $this->fetch('ajax_user_pointchange', true);
          exit;
          } */

        $this->template('user_pointchange');
    }

    //用户收藏
    function mycolle() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->js('goods.js');
        $this->title("欢迎进入用户后台管理中心" . ' - 我的收藏 - ' . $GLOBALS['LANG']['site_name']);
        $rank = $this->Session->read('User.rank');
        $sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
        $discount = $this->App->findvar($sql);

        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 4; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(rec_id) FROM `{$this->App->prefix()}goods_collect` WHERE user_id='$uid'");
        $rt['usercollpage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_usercolle_page_list');
        $sql = "SELECT tb1.rec_id,tb1.user_id,tb1.add_time,tb2.goods_id, tb2.goods_name,tb2.goods_bianhao,tb2.shop_price, tb2.market_price,tb2.pifa_price,tb2.goods_thumb, tb2.original_img, tb2.goods_img,tb2.promote_start_date,tb2.promote_end_date,tb2.promote_price,tb2.is_promote,tb2.qianggou_start_date,tb2.qianggou_end_date,tb2.qianggou_price,tb2.is_qianggou FROM `{$this->App->prefix()}goods_collect` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
        $sql .=" WHERE tb1.user_id='$uid' ORDER BY tb1.add_time DESC LIMIT $start,$list";
        $usercolllist = $this->App->find($sql); //商品列表
        $rt['usercolllist'] = array();
        if (!empty($usercolllist)) {
            foreach ($usercolllist as $k => $row) {
                $rEprice = array();
                $rt['usercolllist'][$k] = $row;
                $rt['usercolllist'][$k]['url'] = get_url($row['goods_name'], $row['goods_id'], SITE_URL . 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
                $rt['usercolllist'][$k]['goods_thumb'] = SITE_URL . $row['goods_thumb'];
                $rt['usercolllist'][$k]['goods_img'] = SITE_URL . $row['goods_img'];
                $rt['usercolllist'][$k]['original_img'] = SITE_URL . $row['original_img'];
                $row['shop_price'] > 0 ? $rEprice[] = $row['shop_price'] : "0.00";
                $row['pifa_price'] > 0 ? $rEprice[] = $row['pifa_price'] : "0.00";
                if ($row['is_promote'] == '1') {
                    //促销 价格
                    if ($row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()) {
                        $row['promote_price'] = format_price($row['promote_price']);
                        $row['promote_price'] > 0 ? $rEprice[] = $row['promote_price'] : "0.00";
                    } else {
                        $row['promote_price'] = "0.00";
                    }
                } else {
                    $row['promote_price'] = "0.00";
                }
                if ($row['is_qianggou'] == '1') {
                    //抢购 价格
                    if ($row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()) {
                        $row['qianggou_price'] = format_price($row['qianggou_price']);
                        $row['qianggou_price'] > 0 ? $rEprice[] = $row['qianggou_price'] : "0.00";
                    } else {
                        $row['qianggou_price'] = "0.00";
                    }
                } else {
                    $row['promote_price'] = "0.00";
                }
                $rt['usercolllist'][$k]['promote_price'] = $row['promote_price'];
                $rt['usercolllist'][$k]['zprice'] = !empty($rEprice) ? min($rEprice) : $row['pifa_price'];
            }
            unset($usercolllist);
        }

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);
        if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
            echo $this->fetch('ajax_mycoll', true);
            exit;
        }

        $this->template('user_mycolle');
    }

    //ajax删除收藏
    function ajax_delmycoll($ids = 0) {
        if (empty($ids))
            die("非法删除，删除ID为空！");
        $id_arr = @explode('+', $ids);
        foreach ($id_arr as $id) {
            if (Import::basic()->int_preg($id))
                $this->App->delete('goods_collect', 'rec_id', $id);
        }
    }

    function user_tuijian() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->title("欢迎进入用户后台管理中心" . ' - 我的推荐 - ' . $GLOBALS['LANG']['site_name']);
        $rt['uid'] = $uid;

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);
        $this->template('user_tuijian');
    }

    function messages() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
            exit;
        }
        $this->title("欢迎进入用户后台管理中心" . ' - 我的提问 - ' . $GLOBALS['LANG']['site_name']);

        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (empty($page)) {
            $page = 1;
        }

        if (!isset($_GET['tt']) || $_GET['tt'] == 'goodsnull') {
            $list = 2; //每页显示多少个
            $start = ($page - 1) * $list;
            $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` WHERE user_id='$uid' AND (goods_id IS NULL OR goods_id='')");
            $rt['notgoodmespage'] = Import::basic()->ajax_page($tt, $list, $page, 'get_myquestion_notgoods_page_list');
            $sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername FROM `{$this->App->prefix()}message` AS tb1 LEFT JOIN  `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NULL OR tb1.goods_id='') ORDER BY tb1.addtime DESC LIMIT $start,$list";
            $rt['notgoodsmeslist'] = $this->App->find($sql);
            if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
                $this->set('rt', $rt);
                echo $this->fetch('ajax_userquestion_nogoods', true);
                exit;
            }
        }

        if (!isset($_GET['tt']) || $_GET['tt'] == 'goodsnotnull') {
            $list = 4; //每页显示多少个
            $start = ($page - 1) * $list;
            $tt2 = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` WHERE user_id='$uid' AND (goods_id IS NOT NULL AND goods_id!='')");
            $rt['goodsmespage'] = Import::basic()->ajax_page($tt2, $list, $page, 'get_myquestion_page_list');
            $sql = "SELECT distinct tb1.*,tb2.avatar,tb2.nickname,tb2.user_name AS dbusername,tb3.goods_name FROM `{$this->App->prefix()}message` AS tb1 LEFT JOIN  `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id  LEFT JOIN `{$this->App->prefix()}goods` AS tb3 ON tb1.goods_id=tb3.goods_id WHERE tb1.user_id='$uid' AND (tb1.goods_id IS NOT NULL AND tb1.goods_id!='') ORDER BY tb1.addtime DESC LIMIT $start,$list";
            $rt['goodsmeslist'] = $this->App->find($sql);
            if (isset($_GET['type']) && $_GET['type'] == 'ajax') {
                $this->set('rt', $rt);
                echo $this->fetch('ajax_userquestion', true);
                exit;
            }
        }

        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

        $this->set('rt', $rt);
        $this->template('user_question');
    }

    function comment() {
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login', 0, '请先登录！');
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
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');

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
            $result['message'] = '提问成功！我们会很快回答你的问题！';
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

    function myinbox() {
        $this->title("欢迎进入用户后台管理中心" . ' - 我的信箱 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login');
            exit;
        }
        //删除
        if (isset($_GET['op']) && $_GET['op'] == 'del' && isset($_GET['id']) && intval($_GET['id']) > 0) {
            $this->App->delete('user_message', 'mes_id', intval($_GET['id']));
            $url = $this->getthisurl();
            $this->jump(str_replace('&op=del&id=' . $_GET['id'], '', $url), 0);
            exit;
        }
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? intval($_GET['page']) : 1;
        $list = 8;
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}user_message` WHERE uid = '$uid' AND parent_id='0'");
        //$rt['pagelink'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
        $rt['pagelink'] = Import::basic()->ajax_page($tt, $list, $page, 'get_myinbox_page_list');

        $sql = "SELECT tb1.*,tb2.user_name FROM `{$this->App->prefix()}user_message` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid=tb2.user_id WHERE tb1.uid = '$uid' AND tb1.parent_id = '0' ORDER BY tb1.mes_id DESC LIMIT $start,$list";
        $rt['meslist'] = $this->App->find($sql);

        $this->set("rt", $rt);
        $this->template('user_myinbox');
    }

    function ajax_batdel_myinbox($rt = array()) {
        $ids = $rt['ids'];
        if (!empty($ids)) {
            $id_rt = explode('+', $ids);
            foreach ($id_rt as $id) {
                if ($id > 0) {
                    $this->App->delete('user_message', 'mes_id', $id);
                    $this->App->delete('user_message', 'parent_id', $id);
                }
            }
        } else {
            echo "Is empty?";
        }
        exit;
    }

    function get_myinbox_page_list($rt = array()) {
        $uid = $this->Session->read('User.uid');
        $page = $rt['page'];
        $list = 8;
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}user_message` WHERE uid = '$uid' AND parent_id = '0'");
        $rt['pagelink'] = Import::basic()->ajax_page($tt, $list, $page, 'get_myinbox_page_list');

        $sql = "SELECT tb1.*,tb2.user_name FROM `{$this->App->prefix()}user_message` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid=tb2.user_id WHERE tb1.uid = '$uid' AND tb1.parent_id = '0' ORDER BY tb1.mes_id DESC LIMIT $start,$list";
        $rt['meslist'] = $this->App->find($sql);

        $this->set("rt", $rt);
        echo $this->fetch('ajax_myinbox_connent', true);
        exit;
    }

    function myinboxinfo() {
        $this->js(array("edit/kindeditor.js"));
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $this->title("欢迎进入用户后台管理中心" . ' - 信息详情 - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        if (empty($uid)) {
            $this->jump(SITE_URL . 'user.php?act=login');
            exit;
        }

        if (!($id > 0)) {
            $this->jump(SITE_URL . 'user.php?act=inbox');
            exit;
        }

        if (isset($_POST['rp_content']) && !empty($_POST['rp_content'])) {
            $this->App->insert('user_message', array('parent_id' => intval($_POST['mes_id']), 'content' => $_POST['rp_content'], 'title' => '---', 'uid' => $uid, 'status' => 1, 'addtime' => mktime()));
            $this->jump(SITE_URL . 'user.php?act=inboxinfo&id=' . $id, 0, '回复成功!');
            exit;
        }

        $sql = "SELECT tb1.*,tb2.user_name FROM `{$this->App->prefix()}user_message` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid=tb2.user_id WHERE tb1.mes_id='$id' AND tb1.uid = '$uid'";
        $rt = $this->App->findrow($sql);
        $rt['rp'] = $this->App->find("SELECT mes_id,content,status,rp_content FROM `{$this->App->prefix()}user_message` WHERE parent_id='$rt[mes_id]' AND uid = '$uid'");
        if (!empty($rt['rp']))
            foreach ($rt['rp'] as $item) {
                if ($item['status'] == 0) {
                    $this->App->update('user_message', array('status' => 1), 'mes_id', $item['mes_id']);
                }
            }

        if ($rt['status'] == 0) {
            $this->App->update('user_message', array('status' => 1), 'mes_id', $rt['mes_id']);
        }
        $this->set('rt', $rt);
        $this->template('user_myinboxinfo');
    }

    function __return_rp_mes($id = 0, $status = 0) {
        if ($status == 0)
            return 0;
        $uid = $this->Session->read('User.uid');
        if ($uid > 0) {
            $st = $this->App->findcol("SELECT status FROM `{$this->App->prefix()}user_message` WHERE parent_id='$id' AND uid = '$uid'");
            if (!empty($st))
                foreach ($st as $s) {
                    if ($s == 0)
                        return 0;
                    break;
                }
        }
        return 1;
    }

    function __return_inbox_conunt() {
        $uid = $this->Session->read('User.uid');
        return $this->App->findvar("SELECT COUNT(mes_id) FROM `{$this->App->prefix()}user_message` WHERE status='0' AND uid = '$uid'");
    }

    //用户积分获取
    function add_user_jifen($type = "", $obj = array()) {
        $art = array('buy', 'comment', 'tuijian', 'otherjifen');
        $uid = $this->Session->read('User.uid');
        if (!($uid > 0))
            return false;
        $rank = $this->Session->read('User.rank');
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

    //用户积分获取
    function add_user_money($type = "", $obj = array()) {
        $art = array('register', 'system', 'tuijian', 'otherjifen');
        $uid = $this->Session->read('User.uid');
        if (!($uid > 0))
            return false;
        //$rank = $this->Session->read('User.rank');
        $data = array();
        switch ($type) {
            case 'register':
                $data['uid'] = $uid;
                $data['time'] = mktime();
                $data['changedesc'] = '注册赠送';
                $data['money'] = $GLOBALS['LANG']['reg_give_money_data']['give_money'] > 0 ? $GLOBALS['LANG']['reg_give_money_data']['give_money'] : 1200;
                $data['thismonth'] = date('m', mktime());
                $data['type'] = $type;
                $this->App->insert('user_money_change', $data);
                unset($data);
                break;
            case 'spend':
                $data['uid'] = $uid;
                $data['time'] = mktime();
                $data['changedesc'] = '商品消费';
                $data['money'] = $obj['money'];
                $data['thismonth'] = date('m', mktime());
                $data['type'] = $type;
                $data['order_id'] = $obj['order_id'];
                $this->App->insert('user_money_change', $data);
                unset($data, $obj);
                break;
        }
        return $data;
    }

    //更新密码
    function ajax_updatepass($data = array()) {
        $json = Import::json();
        $result = array('error' => 2, 'message' => '传送的数据为空！');
        if (empty($data['fromAttr']))
            die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
        unset($data);
        $types = $fromAttr->types;
        if ($types == '10') {
            $uid = $this->Session->read('Agent.uid');
        } else {
            $uid = $this->Session->read('User.uid');
        }
        if (empty($uid)) {
            $result = array('error' => 3, 'message' => '先你先登录！');
            die($json->encode($result));
        }
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
                $sql = "SELECT password,email FROM `{$this->App->prefix()}user` WHERE password='$newpass' AND user_id='$uid'";
                $rst = $this->App->findrow($sql);
                $newrt = isset($rst['password']) ? $rst['password'] : "";
                $emails = isset($rst['email']) ? $rst['email'] : "";
                if (empty($newrt)) {
                    $result = array('error' => 2, 'message' => '你的原始密码错误！');
                    die($json->encode($result));
                }

                if ($this->App->update('user', $datas, 'user_id', $uid)) {
                    $result = array('error' => 2, 'message' => '密码修改成功！');

                    //发送mail
                    /* 					if(!empty($emails) && $GLOBALS['LANG']['email_open_config']['editpassword']=='1'){
                      $datas['uid'] = $uid;
                      $datas['error'] = 0;
                      $datas['email'] = $emails;
                      $datas['user_name'] = $this->Session->read('User.username');
                      $datas['password'] = $fromAttr->password;
                      $this->action('email','edit_password',$datas);
                      } */
                    die($json->encode($result));
                } else {
                    $result = array('error' => 2, 'message' => '密码修改失败！');

                    //发送mail
                    /* 					if(!empty($emails) && $GLOBALS['LANG']['email_open_config']['editpassword']=='1'){
                      $datas['uid'] = $uid;
                      $datas['error'] = 1;
                      $datas['email'] = $emails;
                      $datas['user_name'] = $this->Session->read('User.username');
                      $this->action('email','edit_password',$datas);
                      } */
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
        $username = $this->Session->read('User.username');
        $lastip = $this->Session->read('User.lastip');
        if (empty($uid) || empty($lastip) || $lastip == '0.0.0.0') {
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
        session_destroy();
        //
        //if(isset($_COOKIE['user'])){
        //if(is_array($_COOKIE['user'])){
        //foreach($_COOKIE['user'] as $key=>$val){
        //setcookie("user[".$key."]", "");
        if (isset($_COOKIE['USER']['AUTOLOGIN']))
            setcookie('USER[AUTOLOGIN]', "", 0); //清空自动登录

































            
//}
        //}
        //}

        $url = $this->Session->read('REFERER');
        if (empty($url))
            $url = SITE_URL . 'user.php?act=login';
        $this->jump($url);
        exit;
    }

    function ajax_getuid() {
        echo $this->Session->read('User.uid');
        exit;
    }

    function get_user_session_info() {
        $rt['uid'] = $this->Session->read('User.uid');
        $rt['username'] = $this->Session->read('User.username');
        $rt['rank'] = $this->Session->read('User.rank');
        return $rt;
    }

    //忘记密码
    function forgetpass() {
        $this->css('login.css');
        $this->title("找回密码" . ' - ' . $GLOBALS['LANG']['site_name']);
        if (isset($_POST) && !empty($_POST)) {
            /* $uname = $_POST['uname'];
              if(empty($uname)){
              $this->jump('',0,'请输入你的账号名称！');exit;
              } */
            $email = $_POST['email'];
            if (empty($email)) {
                $this->jump('', 0, '请输入你的原始电子邮箱！');
                exit;
            }
            $vifcode = $_POST['vifcode'];
            if (empty($vifcode)) {
                $this->jump('', 0, '请输入你的验证码！');
                exit;
            }
            $dbvifcode = strtolower($this->Session->read('vifcode'));
            if (strtolower($vifcode) != $dbvifcode) {
                $this->jump('', 0, '验证码错误！');
                exit;
            }

            $sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE email='$email' LIMIT 1";
            $uname = $this->App->findvar($sql);
            if (empty($uname)) {
                $this->jump('', 0, '该电子邮箱不存在！');
                exit;
            } else {
                $this->set('email', $email);
                $this->set('is_true', true);
                $email_config = unserialize($GLOBALS['LANG']['email_open_config']);

                //发送E-mail
                if (!empty($email) && $email_config['findpassword'] == '1') {

                    $datas['user_name'] = $uname;
                    $datas['email'] = $email;
                    $this->action('email', 'find_password', $datas);
                }
                $this->template('user_forgetpass_result');
                exit;
            }
            /* $sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_name= '$uname' AND email='$email' LIMIT 1";
              $dbemail= $this->App->findvar($sql);
              if(empty($dbemail)){
              $this->jump('',0,'无法完成你的请求，你的用户名跟电子邮箱不对应！'); exit;
              }else{
              $this->set('uname',$uname);
              $this->set('email',$email);
              $this->set('is_true',true);
              $this->template('user_forgetpass_result');
              exit;
              } */
        } // end if
        //商品分类列表		
        //$rt['menu'] = $this->action('catalog','get_goods_cate_tree');
        $this->set('rt', $rt);
        $this->template('user_forgetpass');
    }

    function setpass($data = array()) {
        $this->css('login.css');
        $this->title("重设密码" . ' - ' . $GLOBALS['LANG']['site_name']);
        //$this->set('rt',$rt);
        if (isset($_GET['ts']) && !empty($_GET['ts'])) {
            $str = base64_decode($_GET['ts']);
            $ar = explode('||', $str);
            if (count($ar) == 3) {
                $uname = $ar[0];
                $email = $ar[1];
                $times = $ar[2];
                $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_name='$uname' AND email='$email' LIMIT 1";
                $uid = $this->App->findvar($sql);
                if ($uid > 0) {
                    $ar['uid'] = $uid;
                    $this->set('rt', $ar);
                    $this->template('user_setpass');
                } else {
                    $this->action('common', 'show404tpl');
                }
            } else {
                $this->action('common', 'show404tpl');
            }
        } else {
            $this->action('common', 'show404tpl');
        }
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
        $sql = "SELECT password,user_id,last_login,active,user_rank,user_name,is_salesmen FROM `{$this->App->prefix()}user` WHERE email='$user' LIMIT 1";
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
                $this->Session->write('User.username', $rt['user_name']);
                $this->Session->write('User.uid', $rt['user_id']);
                $this->Session->write('User.active', $rt['active']);
                $this->Session->write('User.rank', $rt['user_rank']);
                $this->Session->write('User.is_salesmen', $rt['is_salesmen']);
                $this->Session->write('User.lasttime', $datas['last_login']);
                $this->Session->write('User.lastip', $datas['last_ip']);

                if (isset($data['issave']) && intval($data['issave']) == 1) {
                    setcookie('USER[USERID]', $user, mktime() + 3600 * 24 * 30);
                    setcookie('USER[PASS]', trim($data['password']), mktime() + 3600 * 24 * 30);
                } else {
                    if (isset($_COOKIE['USER']['USERID']))
                        setcookie('USER[USERID]', "", 0);

                    if (isset($_COOKIE['USER']['PASS']))
                        setcookie('USER[PASS]', "", 0);
                }

                if (isset($data['isauto']) && intval($data['isauto']) == 1) {
                    setcookie('USER[AUTOLOGIN]', $data['isauto'], mktime() + 3600 * 24 * 30);
                } else {
                    if (isset($_COOKIE['USER']['AUTOLOGIN']))
                        setcookie('USER[AUTOLOGIN]', "", 0);
                }
                unset($data);
                return true;
            }else {
                //密码是错误的
                return false;
            }
        } //end if
    }

//end function 
    //ajax登录

    /*     * ************************************** look添加程序  开始 ************************************ */

    //ajax登录
    function ajax_user_login($data = array()) {
        if (empty($data))
            die("请输入全部信息！");
        $user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤
        if (empty($user))
            die("请输入你的账号或者邮箱！");
        $pass = md5(trim($data['password']));
        if (empty($pass))
            die("请输入你的账号密码！");
        $vcode = isset($data['vifcode']) ? $data['vifcode'] : "";
        if (!empty($vcode)) {
            if (strtolower($vcode) != strtolower($this->Session->read('vifcode'))) {
                die("请输入验证码！");
            }
        }
        $sql = "SELECT password,user_id,last_login,active,user_name FROM `{$this->App->prefix()}user` WHERE email='$user' or user_name='$user'  LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            die("这账号不存在！");
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
                //$this->Session->write('User.email',$rt['email']);
                $this->Session->write('User.active', $rt['active']);
                $this->Session->write('User.lasttime', $datas['last_login']);
                $this->Session->write('User.lastip', $datas['last_ip']);

                if (isset($data['issave']) && intval($data['issave']) == 1) {
                    setcookie('USER[USERNAME]', $user, mktime() + 3600 * 24 * 30);
                    setcookie('USER[PASS]', trim($data['password']), mktime() + 3600 * 24 * 30);
                } else {
                    if (isset($_COOKIE['USER']['USERNAME']))
                        setcookie('USER[USERNAME]', "", 0);

                    if (isset($_COOKIE['USER']['PASS']))
                        setcookie('USER[PASS]', "", 0);
                }

                if (isset($data['isauto']) && intval($data['isauto']) == 1) {
                    setcookie('USER[AUTOLOGIN]', $data['isauto'], mktime() + 3600 * 24 * 30);
                } else {
                    if (isset($_COOKIE['USER']['AUTOLOGIN']))
                        setcookie('USER[AUTOLOGIN]', "", 0);
                }
                unset($data);
            }else {
                //密码是错误的
                die("密码错误！");
            }
        }
    }

    function ajax_user_login2($data = array()) {
        if (empty($data))
            die("请填写完整信息");
        $user = trim(stripcslashes(strip_tags(nl2br($data['userid'])))); //过滤
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
        $sql = "SELECT password,user_id,last_login,active,user_rank,user_name,is_salesmen FROM `{$this->App->prefix()}user` WHERE user_id='$user' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (empty($rt)) {
            die("用户名不存在！");
        } else {
            if ($rt['password'] == $pass) {
                //登录成功,记录登录信息
                $ip = Import::basic()->getip();
                $datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;
                $datas['last_login'] = mktime();
                $datas['visit_count'] = '`visit_count`+1';
                $this->Session->write('User.prevtime', $rt['last_login']); //记录上一次的登录时间

                $this->App->update('user', $datas, 'user_id', $rt['user_id']); //更新
                $this->Session->write('User.username', $rt['user_name']);
                $this->Session->write('User.uid', $rt['user_id']);
                $this->Session->write('User.active', $rt['active']);
                $this->Session->write('User.rank', $rt['user_rank']);
                $this->Session->write('User.is_salesmen', $rt['is_salesmen']);
                $this->Session->write('User.lasttime', $datas['last_login']);
                $this->Session->write('User.lastip', $datas['last_ip']);

                if (isset($data['issave']) && intval($data['issave']) == 1) {
                    setcookie('USER[USERID]', $user, mktime() + 3600 * 24 * 30);
                    setcookie('USER[PASS]', trim($data['password']), mktime() + 3600 * 24 * 30);
                } else {
                    if (isset($_COOKIE['USER']['USERID']))
                        setcookie('USER[USERID]', "", 0);

                    if (isset($_COOKIE['USER']['PASS']))
                        setcookie('USER[PASS]', "", 0);
                }

                if (isset($data['isauto']) && intval($data['isauto']) == 1) {
                    setcookie('USER[AUTOLOGIN]', $data['isauto'], mktime() + 3600 * 24 * 30);
                } else {
                    if (isset($_COOKIE['USER']['AUTOLOGIN']))
                        setcookie('USER[AUTOLOGIN]', "", 0);
                }
                unset($data);
            }else {
                //密码是错误的
                die("密码错误");
            }
        }
    }

    /*     * ************************************** look添加程序  结束 ************************************ */
    /*     * ****************** 
     * 	
     * 	look修改注释  原程序 
     * 			
      function ajax_user_login($data=array()){
      if(empty($data)) die("请填写完整信息");
      $user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤
      if(empty($user)) die("请输入用户名");
      $pass = md5(trim($data['password']));
      if(empty($pass)) die("请输入密码");
      $vcode = isset($data['vifcode'])? $data['vifcode'] : "";
      //if(!empty($vcode)){
      if(strtolower($vcode) != strtolower($this->Session->read('vifcode'))){
      die("验证码错误！");
      }
      //}
      $sql = "SELECT password,user_id,last_login,active,user_rank FROM `{$this->App->prefix()}user` WHERE user_name='$user' LIMIT 1";
      $rt = $this->App->findrow($sql);
      if(empty($rt)){ die("用户名不存在！");
      }else{
      if($rt['password']==$pass){
      //登录成功,记录登录信息
      $ip = Import::basic()->getip();
      $datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;
      $datas['last_login'] = mktime();
      $datas['visit_count'] = '`visit_count`+1';
      $this->Session->write('User.prevtime',$rt['last_login']); //记录上一次的登录时间

      $this->App->update('user',$datas,'user_id',$rt['user_id']); //更新
      $this->Session->write('User.username',$user);
      $this->Session->write('User.uid',$rt['user_id']);
      $this->Session->write('User.active',$rt['active']);
      $this->Session->write('User.rank',$rt['user_rank']);
      $this->Session->write('User.lasttime',$datas['last_login']);
      $this->Session->write('User.lastip',$datas['last_ip']);

      if(isset($data['issave'])&&intval($data['issave'])==1){
      setcookie('USER[USERNAME]', $user, mktime() + 3600 * 24 * 30);
      setcookie('USER[PASS]', trim($data['password']), mktime() + 3600 * 24 * 30);
      }else{
      if(isset($_COOKIE['USER']['USERNAME'])) setcookie('USER[USERNAME]',"",0);

      if(isset($_COOKIE['USER']['PASS']))  setcookie('USER[PASS]',"",0);
      }

      if(isset($data['isauto'])&&intval($data['isauto'])==1){
      setcookie('USER[AUTOLOGIN]', $data['isauto'], mktime() + 3600 * 24 * 30);
      }else{
      if(isset($_COOKIE['USER']['AUTOLOGIN'])) setcookie('USER[AUTOLOGIN]',"",0);
      }
      unset($data);

      }else{
      //密码是错误的
      die("密码错误");
      }
      }

      }

     * *********************************************************************************** */

    //ajax注册
    function ajax_user_register($data = array()) {
        $json = Import::json();
        $result = array('error' => 2, 'message' => '传送的数据为空!');
        if (empty($data['fromAttr']))
            die($json->encode($result));

        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
        unset($data);

        //以下字段对应评论的表单页面 一定要一致

        $datas['user_rank'] = $fromAttr->user_rank; //用户级别
        $datas['user_name'] = $fromAttr->username; //用户名
        $datas['email'] = $fromAttr->email;
        $datas['password'] = md5($fromAttr->password);
        if (!($datas['user_rank'] > 0))
            $datas['user_rank'] = 1;

        if ($datas['user_rank'] != '1') { //供应商 || 配送店 || 企业会员
            /** 	
             * 	look修改注释


              $datass['consignee'] = $fromAttr->consignee;
              if(empty($datass['consignee'])){
              $result = array('error' => 2, 'message' => '联系人姓名不能为空!');
              if(empty($data['fromAttr']))  die($json->encode($result));
              }

              $datass['tel'] = $fromAttr->tel;
              if(empty($datass['tel'])){
              $result = array('error' => 2, 'message' => '固定电话不能为空!');
              if(empty($data['fromAttr']))  die($json->encode($result));
              }
              $datass['mobile'] = $fromAttr->mobile;
              $datass['province'] = $fromAttr->province;
              if(empty($datass['province'])){
              $result = array('error' => 2, 'message' => '必须选择省份!');
              if(empty($data['fromAttr']))  die($json->encode($result));
              }
              $datass['city'] = $fromAttr->city;
              if(empty($datass['city'])){
              $result = array('error' => 2, 'message' => '必须选择城市!');
              if(empty($data['fromAttr']))  die($json->encode($result));
              }
              $datass['district'] = $fromAttr->district;
              if(empty($datass['district'])){
              $result = array('error' => 2, 'message' => '必须选择地区!');
              if(empty($data['fromAttr']))  die($json->encode($result));
              }
              $datass['address'] = $fromAttr->address;
              if(empty($datass['address'])){
              $result = array('error' => 2, 'message' => '详细地址不能为空!');
              if(empty($data['fromAttr']))  die($json->encode($result));
              }

             */
            $datass['email'] = $datas['email'];
        }


        $uname = $datas['user_name'];

        //      * ****  look修改注释   取消注册时不能用户名一样的。          ************************	

        $sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_name='$uname'";
        $dbname = $this->App->findvar($sql);
        if (!empty($dbname)) {
            $result = array('error' => 2, 'message' => '该用户名已经被注册了!');
            die($json->encode($result));
        }





        $emails = $datas['email'];
        if (!empty($emails)) {
            $sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails'";
            $dbemail = $this->App->findvar($sql);
            if (!empty($dbemail)) {
                $result = array('error' => 2, 'message' => '该电子邮箱已经被使用了!');
                die($json->encode($result));
            }
        }


        $ip = Import::basic()->getip();
        $datas['reg_ip'] = $ip ? $ip : '0.0.0.0';
        $datas['reg_time'] = mktime();
        $datas['reg_from'] = Import::ip()->ipCity($ip);
        $datas['last_login'] = mktime();
        $datas['last_ip'] = $datas['reg_ip'];
        $datas['active'] = 0;
        if ($this->App->insert('user', $datas)) {
            $uid = $this->App->iid();
            $this->Session->write('User.username', $uname);
            $this->Session->write('User.uid', $uid);
            $this->Session->write('User.active', $datas['active']);
            $this->Session->write('User.rank', $datas['user_rank']);
            $this->Session->write('User.lasttime', $datas['last_login']);
            $this->Session->write('User.lastip', $datas['last_ip']);
            $datass['user_id'] = $uid;
            $datass['is_own'] = '1';
            $this->App->insert('user_address', $datass);

            $result = array('error' => 0, 'message' => '注册成功!');

            //注册成功后，发送mail
            if (!empty($emails) && $GLOBALS['LANG']['email_open_config']['register'] == '1') {
                $datas['uid'] = $uid;
                $this->action('email', 'send_register', $datas);
            }

            //$this->add_user_money('register',array()); //赠送12000
            //释放cookie
            if (isset($_COOKIE['USER']['USERID']))
                setcookie('USER[USERID]', "", 0);

            if (isset($_COOKIE['USER']['PASS']))
                setcookie('USER[PASS]', "", 0);

            unset($datas, $datass);
        }

        else {
            $result = array('error' => 2, 'message' => '注册失败!');
        }
        die($json->encode($result));
    }

    //ajax删除用户收货地址
    function ajax_delress($id = 0) {
        $uid = $this->Session->read('User.uid');
        if (empty($uid))
            die("请你先登录！");
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
      if(empty($uid)) die("请你先登录！");
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
        $result = array('error' => 2, 'message' => '传送的数据为空!');
        if (empty($data['fromAttr']))
            die($json->encode($result));
        $fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
        unset($data);

        $types = $fromAttr->types;
        if ($types == '10') {
            $uid = $this->Session->read('Agent.uid');
        } else {
            $uid = $this->Session->read('User.uid');
        }

        if (empty($uid)) {
            $result = array('error' => 3, 'message' => '先你先登录!');
            die($json->encode($result));
        }

        //以下字段对应评论的表单页面 一定要一致
        $emails = $fromAttr->email;
        if (!empty($emails)) {
            $sql = "SELECT email FROM `{$this->App->prefix()}user` WHERE email='$emails'";
            $dbemail = $this->App->findvar($sql);
            if (!empty($dbname) && $dbemail != $emails) {
                $result = array('error' => 4, 'message' => '不能更改这个电子邮箱,已经被使用!');
                die($json->encode($result));
            }
        }
        $datas['mobile_phone'] = $fromAttr->mobile_phone;
        $mobile_phone = $datas['mobile_phone'];
        if (empty($mobile_phone)) {
            $result = array('error' => 4, 'message' => '手机号码必须填写！');
            die($json->encode($result));
        } else {
            $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id!='$uid' AND mobile_phone='$mobile_phone'";
            $uuid = $this->App->findvar($sql);
            if ($uuid > 0) {
                $result = array('error' => 4, 'message' => '改手机号码已经被使用!');
                die($json->encode($result));
            }
        }


        $datas['sex'] = $fromAttr->sex;
        $datas['email'] = $emails;
        $datas['birthday'] = ($fromAttr->yes) . '-' . ($fromAttr->mouth) . '-' . ($fromAttr->day);
        $datas['avatar'] = $fromAttr->avatar;
        $datas['nickname'] = $fromAttr->nickname;
        ///$datas['qq'] = $fromAttr->qq;
        //$datas['office_phone'] = $fromAttr->office_phone;
        $datas['home_phone'] = $fromAttr->home_phone;
        $datas['qq'] = $fromAttr->qq;
        //$datas['answer'] = $fromAttr->answer;
        //更新表
        /* 		$is_jifen = false;
          $sql = "SELECT uptime,reg_time FROM `{$this->App->prefix()}user` WHERE user_id='$uid'";
          $dts = $this->App->findrow($sql);
          if(!empty($dts)){
          if(empty($dts['uptime'])&&($dts['reg_time']+3600*24*7)>mktime()) $is_jifen = true; //七天之内更新资料有送积分,而且是第一次更新资料
          } */
        $dd['country'] = '1';
        if ($this->App->update('user', $datas, 'user_id', $uid)) {

            if ($is_jifen) {
                //$this->add_user_jifen('upuserinfo');
            }
            unset($datas, $dts);
        }

        ############################
        /* $dd = array();
          $sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
          $rsid = $this->App->findvar($sql);

          $dd['company'] = $fromAttr->company;
          $dd['consignee'] = $fromAttr->consignee;
          $dd['about'] = $fromAttr->about;
          //$dd['brand'] = $fromAttr->brand;
          if(empty($dd['consignee'])){
          $result = array('error' => 4, 'message' => '真实姓名不能为空！');
          die($json->encode($result));
          }

          $dd['country'] = '1';
          $dd['province'] = $fromAttr->province;
          $dd['city'] = $fromAttr->city;
          $dd['district'] = $fromAttr->district;
          //$dd['town'] = $fromAttr->town;
          //$dd['village'] = $fromAttr->village;
          $dd['is_own'] = '1';
          $dd['address'] = $fromAttr->address;
          $dd['zipcode'] = $fromAttr->zipcode;
          $dd['user_id'] = $uid;
          if (empty($rsid)) { //添加
          if (!empty($dd['consignee'])) {
          $this->App->insert('user_address', $dd);
          }
          } else { //更新
          if ($this->App->update('user_address', $dd, 'address_id', $rsid)) {
          unset($dd);
          if ($is_jifen) {
          //$result = array('error' => 5, 'message' => '更新成功！你在特定时间更新个人信息，赠送10积分！');
          //die($json->encode($result));
          }
          }
          }
         */
        ############################
        //if($this->App->update('user',$datas,'user_id',$uid)){
        $result = array('error' => 0, 'message' => '更新成功!');
        //}else{
        //$result = array('error' => 2, 'message' => '无法更新!');
        //}
        die($json->encode($result));
    }

    function ajax_get_ress($data = array()) {      //修改个人信息
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
            } else if ($type == 4) {
                $str = '<option value="0">选择城镇</option>';
            } else if ($type == 5) {
                $str = '<option value="0">选择村</option>';
            }

            foreach ($rt as $row) {
                $str .='<option value="' . $row['region_id'] . '">' . $row['region_name'] . '</option>' . "\n";
            }
            die($str);
        } else {
            if ($type == 2) {
                $str = '<option value="0">选择城市</option>';
            } else if ($type == 3) {
                $str = '<option value="0">选择区</option>';
            } else if ($type == 4) {
                $str = '<option value="0">选择城镇</option>';
            } else if ($type == 5) {
                $str = '<option value="0">选择村</option>';
            }
        }
        die($str);
    }

    function ajax_get_peisong_shop($data = array()) {
        $village_id = $data['village_id'];



        if (empty($village_id)) {
            exit;
        }

        $town_id = $data['town_id'];
        $district_id = $data['district_id'];
        /*         * ************** look 添加开始   ************************************************ */

        $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb1.shop_id,tb1.consignee,tb2.user_name FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.district='$district_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
        $rt = $this->App->find($sql);
        if (!empty($rt) && intval($town_id) > 0) {
            $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb1.shop_id,tb1.consignee,tb2.user_name FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.town='$town_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
            $rt = $this->App->find($sql);
        }

        if (!empty($rt) && intval($village_id) > 0) {
            $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb1.shop_id,tb1.consignee,tb2.user_name FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.village='$village_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
            $rt = $this->App->find($sql);
        }
        if (empty($rt) && intval($village_id) >= 0) {
            $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb1.shop_id,tb1.consignee,tb2.user_name FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.town='$town_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
            $rt = $this->App->find($sql);
        }

        /*         * ************** look   添加结束   ************************************************ */

        /*         * ****************  原代码    ***************************************************

          $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb2.user_name,tb1.shop_id,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.village='$village_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
          $rt = $this->App->find($sql);

          if(empty($rt) && intval($town_id)>0){
          $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb2.user_name,tb1.shop_id,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.town='$town_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
          $rt = $this->App->find($sql);
          }

          if(empty($rt) && intval($district_id)>0){
          $sql = "SELECT tb1.user_id,tb1.address ,tb2.mobile_phone ,tb2.home_phone,tb2.nickname,tb2.user_name,tb1.shop_id,tb1.consignee FROM `{$this->App->prefix()}user_address` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id WHERE tb1.district='$district_id' AND tb1.is_own='1' AND tb2.user_rank='12'";
          $rt = $this->App->find($sql);
          } */
        //echo $sql;
        //如果不是通过AJAX传送的，那么返回值
        if ($data['type'] != 'ajax') {
            return $rt;
            exit;
        }

        if (!empty($rt)) {
            $str = '<option value="0">选择配送店</option>';
            foreach ($rt as $row) {
                /*                 * ****** look修改  开始  ************** */
                //	$str .='<option value="'.$row['user_id'].'">'.(!empty($row['user_name'])?$row['user_name']:$row['consignee']).'</option>'."\n";
                $str .='<option value="' . $row['user_id'] . '">' . $row['user_name'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . '联系电话:' . (!empty($row['home_phone']) ? $row['home_phone'] : $row['mobile_phone']) . '&nbsp;&nbsp;&nbsp;&nbsp;' . '地址:' . $row['address'] . '</option>' . "\n";
                /*                 * ****** look修改  结束  ************** */
            }
            die($str);
        }
    }

    function get_suppliers_address($data = array()) {
        $uid = $data['suppliers_id'];
        if (!($uid > 0)) {
            echo "获取失败";
            exit;
        }
        $sql = "SELECT tb1.address,tb2.region_name AS provinces,tb4.region_name AS districts,tb3.region_name AS citys,tb5.region_name AS town,tb6.region_name AS village FROM `{$this->App->prefix()}user_address` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id = tb1.town";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id = tb1.village";
        $sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own='1' LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (!empty($rt)) {
            echo $rt['provinces'] . '-' . $rt['citys'] . '-' . $rt['districts'] . '-' . $rt['town'] . '-' . $rt['village'] . '-' . $rt['address'];
        } else {
            echo "获取失败";
        }
        exit;
    }

    //收获地址操作
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
            //$dd['town'] = $attrbul->town; //城镇
            //$dd['village'] = $attrbul->village; //村
            //$dd['shop_id'] = $attrbul->shop_id;  //配送店
            $dd['address'] = $attrbul->address;
            $dd['shoppingname'] = $attrbul->shipping_id;
            $dd['shoppingtime'] = $attrbul->shoppingtime;
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
        /*         * ****** look 添加 开始  ***************************** */
        //配送方式
        $sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
        $rt['shippinglist'] = $this->App->find($sql);
        /*         * ****** look 添加 结束  ***************************** */

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
                    //$rt['town'] = $this->get_regions(4,$row['district']);  //城镇
                    //$rt['village'] = $this->get_regions(5,$row['town']);  //村
                    //$rt['peisong'] = $this->ajax_get_peisong_shop(array('town_id'=>$rt['userress']['town'],'village_id'=>$rt['userress']['village'],'district_id'=>$rt['userress']['district'],'type'=>''));

                    $this->set('rt', $rt);
                    $con = $this->fetch('ajax_show_updateressbox', true);
                    die($con);
                    break;
            }
        }
    }

    function get_user_order_option($sn = 0, $oid = 0, $pid = 0, $sid = 0, $suppliers_id = 0) {
        if (empty($sn))
            return "";
        $str = '';
        switch ($sid) {
            case '2':
                return $str = '<a href="javascript:;" name="confirm" id="' . $sn . '-' . $suppliers_id . '" class="oporder"><font color="red">确认收货</font><a>';
                break;
            case '5':
                return $str = '<font color="red">已完成</font>';
                break;
        }

        switch ($oid) {
            case '0':
                $str = '<a href="javascript:;" name="cancel_order" id="' . $sn . '-' . $suppliers_id . '" class="oporder"><font color="red">取消订单</font></a>';
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

    //我的客户
    function myuser() {
        // $this->checked_login();
        $uid = $this->Session->read('User.uid');
        $ts = isset($_GET['t']) ? $_GET['t'] : '0';
        $l = $ts == '1' ? '一级会员' : ($ts == '2' ? '二级会员' : '三级会员');
        $rt['ts'] = $l;
        if (!defined(NAVNAME))
            define('NAVNAME', "我的分销：" . $l);


        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : '1';
        if (empty($page)) {
            $page = 1;
        }
        $list = 30;
        $start = ($page - 1) * $list;
        //$sql = "SELECT COUNT(tb1.id) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        //$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2";
        //$sql .=" ON tb1.uid = tb2.user_id WHERE tb1.daili_uid = '$uid'";
        //$tt = $this->App->findvar($sql);
        //$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
        if ($ts == '0') {
            $sql = "SELECT count(*) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb1.uid !='$uid' AND (tb1.daili_uid = '$uid' OR tb1.parent_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC  ";
            $tt = $this->App->findvar($sql);
            $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
            //全部用户
            $sql = "SELECT tb1.*,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.points_ucount,tb2.money_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
            $sql .=" WHERE tb1.uid !='$uid' AND (tb1.daili_uid = '$uid' OR tb1.parent_uid = '$uid') ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
            $rt['lists'] = $this->App->find($sql); // AND tb2.is_subscribe ='1'
        } elseif ($ts == '1') {
            $tt = $this->get_myuser_level_1_count($uid);
            $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
            //一级用户
            $rt['lists'] = $this->get_myuser_level_1($uid, $start, $list);
        } elseif ($ts == '2') {
            //二级用户
            $tt = $this->get_myuser_level_2_count($uid);
            $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
            $rt['lists'] = $this->get_myuser_level_2($uid, $start, $list);
        } elseif ($ts == '3') {
            //三级用户
            $tt = $this->get_myuser_level_3_count($uid);
            $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);
            $rt['lists'] = $this->get_myuser_level_3($uid, $start, $list);
        }

        $this->set('level', $ts);
        $this->set('rt', $rt);

        $this->template('user_myuser');
    }

    //一级用户
    function get_myuser_level_1($uid = '0', $start = '0', $list = '30') {
        $sql = "SELECT tb1.*,tbl.level_name,tb2.subscribe_time,tb2.reg_time,tb2.nickname,tb2.headimgurl,tb2.money_ucount,tb2.points_ucount,tb2.share_ucount,tb2.guanzhu_ucount,tb2.is_subscribe FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb2.user_rank = tbl.lid";
        $sql .=" WHERE tb1.parent_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC LIMIT $start,$list";
        return $this->App->find($sql);
    }

    //一级用户总数
    function get_myuser_level_1_count($uid = '0') {
        $sql = "SELECT count(*) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb2.user_rank = tbl.lid";
        $sql .=" WHERE tb1.parent_uid = '$uid' ORDER BY tb2.share_ucount DESC,tb2.money_ucount DESC,tb1.id DESC  ";
        return $this->App->findvar($sql);
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

//二级用户总数

    function get_myuser_level_2_count($uid = '0') {
        $sql = "SELECT count(*) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb2.uid = tb3.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb3.user_rank = tbl.lid";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb2.uid IS NOT NULL ORDER BY tb3.share_ucount DESC,tb3.money_ucount DESC,tb2.id DESC ";
        return $this->App->findvar($sql);
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

    //三级用户总数
    function get_myuser_level_3_count($uid = '0') {
        $sql = "SELECT count(*) FROM `{$this->App->prefix()}user_tuijian` AS tb1";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb2.parent_uid = tb1.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb3 ON tb3.parent_uid = tb2.uid ";
        $sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb3.uid = tb4.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_level` AS tbl ON tb4.user_rank = tbl.lid";
        $sql .= " WHERE tb1.parent_uid='$uid' AND tb3.uid IS NOT NULL  ORDER BY tb4.share_ucount DESC,tb4.money_ucount DESC,tb3.id DESC ";
        return $this->App->findvar($sql);
    }

    function myyongjin() {
        //  $this->checked_login();
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
        $this->template('user_myyongjin');
    }

    function mymoneydata() {
        $this->title("佣金明细" . ' - ' . $GLOBALS['LANG']['site_name']);
        $this->checked_login();
        $uid = $this->Session->read('User.uid');

        $sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
        $rt['zmoney'] = $this->App->findvar($sql);
        $rt['zmoney'] = format_price($rt['zmoney']);
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
        $list = 30; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(tb1.cid) FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn $w");
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT tb1.*,tb3.nickname,tb3.headimgurl FROM `{$this->App->prefix()}user_money_change_cache` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_sn = tb1.order_sn LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.buyuid = tb3.user_id $w ORDER BY tb1.time DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql); //商品列表
        $rt['page'] = $page;

        if (!defined(NAVNAME))
            define('NAVNAME', "佣金明细");
        $this->set('rt', $rt);

        $this->template('user_mymoneydata');
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

    function postmoney() {
        $this->checked_login();
        $this->title("申请提款" . ' - ' . $GLOBALS['LANG']['site_name']);
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
        $rts = $this->App->findrow($sql);
        if (empty($rts)) {
            $this->jump(SITE_URL . 'user.php?act=myinfos_b', 0, '请先设置提款信息');
            exit;
        }
        $sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
        $mymoney = $this->App->findvar($sql);

        $sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1";
        $rL = $this->App->findrow($sql);
        $this->set('rL', $rL);
        unset($rL);
        if (!defined(NAVNAME))
            define('NAVNAME', "申请提款");
        $this->set('rts', $rts);
        $this->set('mymoney', $mymoney);
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template('user_postmoney');
    }

    function postmoneydata($data = array()) {
        $uid = $this->Session->read('User.uid');
        $sql = "SELECT * FROM `{$this->App->prefix()}user_drawmoney` WHERE uid='$uid' ORDER BY id DESC";
        $rt_ = $this->App->find($sql);
        $rt = array();
        if (!empty($rt_))
            foreach ($rt_ as $k => $row) {
                $rt[$k] = $row;
                if (empty($row['banksn'])) {
                    $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
                    $rr = $this->App->findrow($sql);
                    $rt[$k]['uname'] = $rr['uname'];
                    $rt[$k]['mobile'] = $rr['bankaddress'];
                    $rt[$k]['bankname'] = $rr['bankname'];
                    $rt[$k]['banksn'] = $rr['banksn'];
                    unset($rr);
                }
            }
        unset($rt_);
        $this->set('rt', $rt);
        if (!defined(NAVNAME))
            define('NAVNAME', '提款记录');
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template('user_postmoneydata');
    }

    //申请提款
    function ajax_postmoney($data = array()) {
        $this->checked_login();
        $uid = $this->Session->read('User.uid');
        $this->is_daili();
        //$pass = $data['pass'];
        $money = $data['money'];
        $ids = $data['id'];
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
            //检查资金
            $sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
            $mymoney = $this->App->findvar($sql);
            if (intval($mymoney) < intval($money)) {
                echo "资金不足，您不能提款！";
                exit;
            }
            $money = number_format($money, 2, '.', '');
            if (intval($money) < $rL['dixin360']) {
                echo "提款金额最低" . $rL['dixin360'] . "元起！";
                exit;
            }
            if (!(intval($ids) > 0))
                $ids = $id;
            $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE id='$ids' LIMIT 1";
            $rr = $this->App->findrow($sql);

            $dd = array();
            $dd['uid'] = $uid;
            $dd['money'] = $money;
            $dd['addtime'] = mktime();
            $dd['date'] = date('Y-m', mktime());
            $dd['bankname'] = $rr['bankname'];
            $dd['mobile'] = $rr['bankaddress'];
            $dd['uname'] = $rr['uname'];
            $dd['banksn'] = $rr['banksn'];
            $dd['weixin'] = $rr['weixin'];
            $dd['alipay'] = $rr['alipay'];
            unset($rr);
            if ($this->App->insert('user_drawmoney', $dd)) {
                $money = -$money;
                $sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney` = `mymoney`+'$money' WHERE user_id = '$uid' LIMIT 1";
                $this->App->query($sql);
                echo "提款成功，等待我们的处理！";
                exit;
            } else {
                echo "提款失败，请联系我们客服处理！";
                exit;
            }
        } else {
            echo "提款信息错误！";
            exit;
        }
    }

    function myinfos_b($data = array()) {
        $uid = $this->checked_login();
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND active='1' LIMIT 1";
        $rt = $this->App->findrow($sql);

        $sql = "SELECT * FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
        $rts = $this->App->findrow($sql);

        $this->set('rt', $rt);
        $this->set('rts', $rts);
        if (!defined(NAVNAME))
            define('NAVNAME', "银行卡号资料");
        $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template('myinfos_b');
    }

    //修改提款信息
    function update_user_bank($data = array()) {
        $this->checked_login();
        //$newpass = $data['pass'];
        $bankname = $data['bankname'];
        $bankaddress = $data['bankaddress'];
        $uname = $data['uname'];
        $banksn = $data['banksn'];
        $alipay = $data['alipay'];
        $weixin = $data['weixin'];
        $uid = $this->Session->read('User.uid');
        /* if(empty($newpass)){
          echo '请输入密码'; exit;
          } */
        //密码是否正确
        /* $pp = md5(trim($newpass));
          $sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE pass = '$pp' AND uid='$uid' LIMIT 1";
          $id = $this->App->findvar($sql);
          if(!($id>0)){
          echo '密码错误！或者您可以修改密码！'; exit;
          } */

        if ((empty($bankname) || empty($uname) || empty($banksn)) && empty($alipay) && empty($weixin)) {
            echo '请输入完整信息';
            exit;
        }
        if (empty($uname)) {
            //  echo '请输入户名';
            // exit;
        }
        if (empty($banksn)) {
            //echo '请输入卡号'; exit;
        }
        $dd = array();
        $dd['bankname'] = $bankname;
        $dd['bankaddress'] = $bankaddress;
        $dd['uname'] = $uname;
        $dd['banksn'] = $banksn;
        $dd['alipay'] = $alipay;
        $dd['weixin'] = $weixin;
        $dd['uptime'] = mktime();

        $sql = "SELECT id FROM `{$this->App->prefix()}user_bank` WHERE uid='$uid' LIMIT 1";
        $uids = $this->App->findvar($sql);
        if ($uids > 0) { //修改
            if ($this->App->update('user_bank', $dd, 'id', $uids)) {
                echo '修改成功';
                exit;
            } else {
                echo '修改失败';
                exit;
            }
        } else {
            $dd['uid'] = $uid;
            if ($this->App->insert('user_bank', $dd)) {
                echo '修改成功';
                exit;
            } else {
                echo '修改失败';
                exit;
            }
        }
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
        $this->template('user_moneyrank');
    }

    function baoming() {
        $uid = $this->checked_login();
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if (!$id) {
            $this->jump(SITE_URL . 'user.php', 0, '非法操作！');
            exit;
        }
        $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` WHERE id='$id' LIMIT 1";
        $bm = $rt['pinfo'] = $this->App->findrow($sql);
        $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
        $this->set('rank', $rank);
        //查找是否已经领取奖品
        /*    $hasgift = $this->App->findvar("SELECT count(*) FROM `{$this->App->prefix()}gift_order` WHERE user_id='$uid' and bid=$id LIMIT 1");
          $this->set('hasgift', $hasgift); */
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
        $this->set('hasgift', $hasgift);
        /*    if ($rank != 1 and $rank <= $rt['pinfo']['rank_id']) {
          $this->jump(SITE_URL . "user.php", 0, '您的级别高于当前级别，您可以向更高级别进军了！');
          exit;
          } */

        $this->title($bm['title'] . ' - ' . $GLOBALS['LANG']['site_name']);
        if (!defined(NAVNAME))
            define('NAVNAME', $bm['title']);
        $this->set('rt', $rt);
        $this->template('user_baoming');
    }

    function account_bd() {
        $uid = $this->checked_login();
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' LIMIT 1";
        $rt['userinfo'] = $this->App->findrow($sql);
        $this->set('rt', $rt);
        if (!defined(NAVNAME))
            define('NAVNAME', '绑定账号');
        $this->title('绑定账号 - ' . $GLOBALS['LANG']['site_name']);
        $this->template('user_account_bd');
    }

    function ajax_account_bd() {
        
    }

    function confirmpay($data = array()) {
        $this->checked_login();
        if (!empty($_POST)) {
            $uid = $this->Session->read('User.uid');
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
            /* $cardphoto1 =$this->uploadimg ('cardphoto1');
              $cardphoto2 =$this->uploadimg ('cardphoto2');
              $cardphoto3 =$this->uploadimg ('cardphoto3');
              $cardphoto4 = $this->uploadimg ('cardphoto4'); */

            $cardphoto1 = $_POST['cardphoto1'];
            $cardphoto2 = $_POST['cardphoto2'];
            $cardphoto3 = $_POST['cardphoto3'];
            $cardphoto4 = $_POST['cardphoto4'];

            if (empty($uname) || empty($upne) || empty($price) || empty($ids)) {
                $this->jump(SITE_URL . "user.php", 0, '请填写完整信息！');
                exit;
            }
            $uid = $this->checked_login();

            $rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
            $s = "WHERE id='$ids'";
            $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` {$s} ORDER BY id DESC LIMIT 1";
            $rt['pinfo'] = $this->App->findrow($sql);

            if ($rank != 1 and $rank <= $rt['pinfo']['rank_id']) {
                $this->jump(SITE_URL . "user.php", 0, '您的级别高于当前级别，您可以向更高级别进军了！');
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
            $sql = "SELECT `pay_config` FROM `" . $this->App->prefix() . "payment` WHERE `pay_id`='3'";
            $pay_config = $this->App->findvar($sql);
            $configr = unserialize($pay_config);
            $paypalmail = isset($configr['pay_no']) ? $configr['pay_no'] : '';
            if ($this->App->insert('cx_baoming_order', $dd)) {
                // $this->jump(SITE_URL . 'pay/alipayapi.php?order_sn=' . $on . '&bm=baoming');
                $paypal_form = "<form name='aqua' method='post' action='" . SITE_URL . "pay/alipayapi.php'>
      <input type='hidden' name='WIDout_trade_no' value='" . $on . "'>
      <input type='hidden' name='WIDseller_email' value='" . $paypalmail . "'>
      <input type='hidden' name='WIDsubject' value='金葵花商城商品支付系统'>
      <input type='hidden' name='WIDprice' value='" . $price . "'>
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
                exit;
            } else {
                $this->jump(SITE_URL . "user.php", 0, '意外错误');
                exit;
            }
        }
    }

    function monrydeial() {
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
        $list = 30; //每页显示多少个
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

        $this->template('user_monrydeial');
    }

    function _return_goods_name($sn) {
        if (empty($sn))
            return "";
        $sql = "SELECT tb1.goods_name FROM `{$this->App->prefix()}goods_order` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_info` AS tb2 ON tb2.order_id = tb1.order_id WHERE tb2.order_sn='$sn' LIMIT 1";
        return $this->App->findvar($sql);
    }

    function uploadimg($filename) {
        if (empty($filename))
            return false;
        $imgobj = Import::img();
        $f_name = $_FILES[$filename]['name']; //获取上传源文件名 
        $t = strrchr($f_name, '.'); //图片类型
        $name = time() . time() . $t; //原始图名称
        //设置生成缩略图图片的大小
        $filedir = str_replace('\\', '/', realpath(dirname(__FILE__) . '../../../../')) . "/m/uploads/";

        if (!is_dir($filedir)) {
            mkdir($filedir, 0777);
        } else {
            chmod($filedir, 0777);
        }
        $rs = $imgobj->upload($filename, $filedir . $name);

        $imgobj->upload($filename, $filedir . $name);
        return "uploads/" . $name;
    }

    //赠送红包
    function giftlist($data = array()) {
        $uid = $this->checked_login();
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
        $this->title("领取礼包" . ' - ' . $GLOBALS['LANG']['site_name']);
        $rt = $this->App->findrow($sql);
        if ($rt[user_rank] == 1) {
            $rankType = $_GET['rank'];
        }  else {
            $rankType = $rt['user_rank'];
        }
        //分页
        $page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
        if (empty($page)) {
            $page = 1;
        }
        $list = 30; //每页显示多少个
        $start = ($page - 1) * $list;
        $tt = $this->App->findvar("SELECT COUNT(*) FROM `{$this->App->prefix()}gift_bag` WHERE `type`='$rankType' ");
        $rt['pages'] = Import::basic()->getpage($tt, $list, $page, '?page=', true);

        $sql = "SELECT * FROM `{$this->App->prefix()}gift_bag`  WHERE `type`='$rankType'    ORDER BY bid DESC LIMIT $start,$list";
        $rt['lists'] = $this->App->find($sql); //商品列表
        $rt['page'] = $page;

        if (!defined(NAVNAME))
            define('NAVNAME', "领取礼包");
        $this->set('rt', $rt);

        $this->template('giftlist');
    }

    function gift_info() {
        $uid = $this->checked_login();
        $this->title("领取礼包" . ' - ' . $GLOBALS['LANG']['site_name']);
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
        $userinfo = $this->App->findrow($sql);

        $bid = isset($_GET['bid']) ? $_GET['bid'] : '';
        $has = 0;
        if ($bid) {
            /* $sql = "SELECT count(*) FROM `{$this->App->prefix()}gift_order` where bid='$bid' and uid='$uid'";
              $count = $this->App->findvar($sql);
              if ($count) {
              $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');
              } */   //查找该用户已经领取该等级的礼包了
            $sql = "SELECT  bid FROM `{$this->App->prefix()}gift_bag` where type =(select type from `{$this->App->prefix()}gift_bag`  where bid='$bid' ) ";

            $bids = $this->App->find($sql);
            foreach ($bids as $_k => $_v) {
                $sql = "SELECT count(*) FROM `{$this->App->prefix()}gift_order` where bid='$_v[bid]' and user_id='$uid'";

                $count = $this->App->findvar($sql);

                if ($count) {
                    // $this->jump(ADMIN_URL, 0, '您已经领取过礼包了！');
                    // exit;
                    $has = 1;
                    break;
                }
            }
            $this->set('has', $has);
            $sql = "SELECT * FROM `{$this->App->prefix()}gift_bag` where bid='$bid'";
            $gift_info = $this->App->findrow($sql);
            //判断用户的等级 可以领取该礼包
            /* if ($gift_info['type'] != $userinfo['user_rank']) {
              $this->jump(ADMIN_URL, 0, '您无法领取该礼包！');
              exit;
              } */
            $this->set('gift', $gift_info);
            $this->set('userinfo', $userinfo);
        }
        $rt['province'] = $this->action('user', 'get_regions', 1);  //获取省列表

        $sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id";
        $rt['userress'] = $this->App->find($sql);
        $this->set('rt', $rt);
        if (!defined(NAVNAME))
            define('NAVNAME', "领取礼包");

        $this->template('gift_info');
    }

    public function gift_save() {
        $uid = $this->checked_login();
        $has = 0;
        $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
        $userinfo = $this->App->findrow($sql);
        //查找该用户是否已经领取该礼包
        $bid = $_POST['bid'];
        $sql = "SELECT * FROM `{$this->App->prefix()}gift_bag` where bid='$bid'";
        $gift_info = $this->App->findrow($sql);
        //判断用户的等级 可以领取该礼包
        if ($gift_info['type'] < $userinfo['user_rank']) {
            $this->jump(SITE_URL, 0, '您无法领取该礼包！');
            exit;
        }
        $sql = "SELECT  bid FROM `{$this->App->prefix()}gift_bag` where type =(select type from `{$this->App->prefix()}gift_bag`  where bid='$bid' ) ";

        $bids = $this->App->find($sql);
        foreach ($bids as $_k => $_v) {
            $sql = "SELECT count(*) FROM `{$this->App->prefix()}gift_order` where bid='$_v[bid]' and user_id='$uid'";

            $count = $this->App->findvar($sql);

            if ($count) {
                $this->jump(SITE_URL, 0, '您已经领取过礼包了！');
                exit;
                /* $has = 1;
                  break; */
            }
        }


        $this->set('has', $has);
        $data['create_time'] = mktime();
        $data['user_id'] = $uid;
        $data['address_id'] = $_POST['userress_id'];
        $data['bid'] = $_POST['bid'];
        if ($this->App->insert('gift_order', $data)) {
            $this->jump(SITE_URL . "user.php", 0, '您的奖品已经预定成功，我们将尽快给您发货，请您耐心等待！祝您工作顺心、生活愉快！谢谢！');
        }
    }

//我的礼包

    function mygiftbag() {
        $this->title("我领取的礼包" . ' - ' . $GLOBALS['LANG']['site_name']);

        $rt = array();
        if (!defined(NAVNAME))
            define('NAVNAME', "我领取的礼包");
        $uid = $this->checked_login();
        $sql = "select go.*,gb.bag_name from `{$this->App->prefix()}gift_order` as go left join   `{$this->App->prefix()}gift_bag` as gb on go.bid=gb.bid where go.user_id='$uid'";

        $rt = $this->App->find($sql);
        $this->set('rt', $rt);

        $this->template('mygiftbag');
    }

    function checked_login() {
        $uid = $this->Session->read('User.uid');
        if (!($uid > 0)) {
            $this->jump(SITE_URL . 'user.php?act=login');
            exit;
        }
        return $uid;
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

}

?>