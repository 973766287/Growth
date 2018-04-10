<?php

/*
 * 这是一个后台产品处理类
 */

class Gift_BagController extends Controller {

    function __construct() {
        $this->css('content.css');
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

    function lists() {
        $type = isset($_GET['t']) ? $_GET['t'] : '';
        $this->set("t", $type);
        $ws = " where 1 and 1";
        if ($type) {
            $ws.=" and type=$type";
        }
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 20;
        $start = ($page - 1) * $list;
        $sql = "SELECT COUNT(*) FROM `{$this->App->prefix()}gift_bag` $ws";

        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);

        $sql = "SELECT * FROM `{$this->App->prefix()}gift_bag` $ws ";
        $this->set('lists', $this->App->find($sql));
        $this->template('gift_bag_list');
    }

    function orders() {
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 20;
        $start = ($page - 1) * $list;
        $ws = '';
        $sql = "SELECT COUNT(*) FROM `{$this->App->prefix()}gift_order` $ws";
        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);

        $sql = "SELECT tb1.*,tb2.*,tb3.user_name,tb3.nickname,tb4.* ,tb5.region_name as province ,tb6.region_name as city,  tb7.region_name as district  FROM `{$this->App->prefix()}gift_order` AS tb1";
        $sql .=" LEFT JOIN `{$this->App->prefix()}gift_bag` AS tb2 ON tb1.bid = tb2.bid";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.user_id=tb3.user_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}user_address` AS tb4 ON tb1.address_id=tb4.address_id";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id=tb4.province";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id=tb4.city";
        $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb7 ON tb7.region_id=tb4.district";
        $sql .="  order by tb1.oid desc  LIMIT $start,$list"; // echo $sql;

        $rt = $this->App->find($sql);
        $this->set('lists', $rt);
        $this->template('gift_bag_orders');
    }

    //确认派送
    function mksure() {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if (!$id) {
            echo'<script>alert("礼包名称不能为空！");</script>';
        }
        $sdata['status'] = 2;
        $this->App->update('gift_order', $sdata, 'oid', $id);
        unset($data, $sdata);
        $this->jump('gift_bag.php?type=orders');
    }

    function unmksure() {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if (!$id) {
            echo'<script>alert("礼包名称不能为空！");</script>';
        }
        $sdata['status'] = 1;
        $this->App->update('gift_order', $sdata, 'oid', $id);
        unset($data, $sdata);
        $this->jump('gift_bag.php?type=orders');
    }

    function bag_print() {
          $url_href = ADMIN_URL . 'gift_bag.php?type=orders';
        $this->check_priv($url_href);
        $oid = isset($_GET['oid']) ? $_GET['oid'] : '';
        if ($oid > 0) { //编辑页面
            //当前商品基本信息
            //  $sql = "SELECT go.*,gb.* FROM `{$this->App->prefix()}gift_order` as go left join `{$this->App->prefix()}gift_bag`  as gb on go.bid=gb.oid WHERE go.bid='{$bid}' LIMIT 1";
            $sql = "SELECT tb1.*,tb2.*,tb3.user_name,tb3.nickname,tb4.* ,tb5.region_name as province ,tb6.region_name as city,  tb7.region_name as district  FROM `{$this->App->prefix()}gift_order` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}gift_bag` AS tb2 ON tb1.bid = tb2.bid";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.user_id=tb3.user_id";
            $sql .=" LEFT JOIN `{$this->App->prefix()}user_address` AS tb4 ON tb1.address_id=tb4.address_id";
            $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb5 ON tb5.region_id=tb4.province";
            $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb6 ON tb6.region_id=tb4.city";
            $sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb7 ON tb7.region_id=tb4.district";
            $sql .="  where tb1.oid=$oid"; // echo $sql;

            $rt = $this->App->findrow($sql);
            $this->set('rt', $rt);
            if (empty($rt)) {
                echo'<script>alert("礼包领取记录不存在！");</script>';
                exit;
            }
            $this->template('bag_print');
        } else {
            echo'<script>alert("礼包领取记录不存在！");</script>';
            exit;
        }
    }

    function bag_info($bid = 0,$url='') {
       $url_href = isset($url) ? ADMIN_URL . $url : ADMIN_URL . 'gift_bag.php?type=lists';
        $this->check_priv($url_href);
        $this->js(array("kindeditor/kindeditor.js", "kindeditor/lang/zh_CN.js", 'time/WdatePicker.js'));
        $this->css('default.css');
        $sql = "SELECT * FROM `{$this->App->prefix()}user_level`  where is_show='1' and lid!=1  ";
        $this->set('user_level', $this->App->find($sql));
        if ($bid > 0) { //编辑页面
            //当前商品基本信息
            $sql = "SELECT * FROM `{$this->App->prefix()}gift_bag` WHERE bid='{$bid}' LIMIT 1";

            $rt = $this->App->findrow($sql);
            $this->set('rt', $rt);
            if (empty($rt)) {
                $this->jump('gift_bag.php?type=lists');
                exit;
            } if (isset($_POST) && !empty($_POST)) {
                if (empty($_POST['bag_name'])) {
                    echo'<script>alert("礼包名称不能为空！");</script>';
                } else {
                    if ($rt['original_img'] != $_POST['original_img']) {
                        //修改了上传文件 那么重新上传
                        $source_path = SYS_PATH . DS . str_replace('/', DS, $_POST['original_img']);
                        $pa = dirname($_POST['original_img']);
                        $thumb = basename($_POST['original_img']);

                        $tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
                        $th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
                        $tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
                        $th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
                        if (isset($_POST['goods_thumb']) && !empty($_POST['goods_thumb'])) {
                            //留空
                            if (!file_exists(SYS_PATH . $_POST['goods_thumb'])) {
                                Import::img()->thumb($source_path, dirname($source_path) . DS . 'thumb_s' . DS . $thumb, $tw_s, $th_s); //小缩略图
                                $_POST['goods_thumb'] = $pa . '/thumb_s/' . $thumb;
                            }
                        } else {
                            Import::img()->thumb($source_path, dirname($source_path) . DS . 'thumb_s' . DS . $thumb, $tw_s, $th_s); //小缩略图
                            $_POST['goods_thumb'] = $pa . '/thumb_s/' . $thumb;
                        }

                        Import::img()->thumb($source_path, dirname($source_path) . DS . 'thumb_b' . DS . $thumb, $tw_b, $th_b); //大缩略图
                        $_POST['goods_img'] = $pa . '/thumb_b/' . $thumb;
                    }
                    $this->App->update('gift_bag', $_POST, 'bid', $bid);
                    $this->action('system', 'add_admin_log', '修改礼包:' . $_POST['bag_name'] . '-bid:' . $bid);
                    $this->action('common', 'showdiv', $this->getthisurl());
                }
            }
        } else {
            if (isset($_POST) && !empty($_POST)) {
                if (empty($_POST['bag_name'])) {
                    echo'<script>alert("礼包名称不能为空！");</script>';
                } else {
                    $bid = $this->App->findvar("SELECT MAX(bid) + 1 FROM `{$this->App->prefix()}bag_gift`");
                    $bid = empty($bid) ? 1 : $bid;
                    if ($rt['original_img'] != $_POST['original_img']) {
                        //修改了上传文件 那么重新上传
                        $source_path = SYS_PATH . DS . str_replace('/', DS, $_POST['original_img']);
                        $pa = dirname($_POST['original_img']);
                        $thumb = basename($_POST['original_img']);

                        $tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
                        $th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
                        $tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
                        $th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
                        if (isset($_POST['goods_thumb']) && !empty($_POST['goods_thumb'])) {
                            //留空
                            if (!file_exists(SYS_PATH . $_POST['goods_thumb'])) {
                                Import::img()->thumb($source_path, dirname($source_path) . DS . 'thumb_s' . DS . $thumb, $tw_s, $th_s); //小缩略图
                                $_POST['goods_thumb'] = $pa . '/thumb_s/' . $thumb;
                            }
                        } else {
                            Import::img()->thumb($source_path, dirname($source_path) . DS . 'thumb_s' . DS . $thumb, $tw_s, $th_s); //小缩略图
                            $_POST['goods_thumb'] = $pa . '/thumb_s/' . $thumb;
                        }

                        Import::img()->thumb($source_path, dirname($source_path) . DS . 'thumb_b' . DS . $thumb, $tw_b, $th_b); //大缩略图
                        $_POST['goods_img'] = $pa . '/thumb_b/' . $thumb;
                    }
                    $_POST['add_time'] = mktime();
                    $this->App->insert('gift_bag', $_POST);
                    $this->action('system', 'add_admin_log', '添加礼包:' . $_POST['bag_name'] . '-bid:' . $bid);
                    $this->action('common', 'showdiv', $this->getthisurl());
                }
            } else {
                $rt['type'] = $_GET['t'];
                $this->set('rt', $rt);
            }
        }

        $this->template('bag_info');
    }

    //ajax删除商品
    function ajax_delbags($ids = 0, $tt = "") {
        if (empty($ids))
            die("非法删除，删除ID为空！");
        if (!is_array($ids))
            $id_arr = @explode('+', $ids);
        else
            $id_arr = $ids;



        $sql = "SELECT goods_thumb, goods_img, original_img FROM `{$this->App->prefix()}gift_bag` WHERE bid IN(" . @implode(',', $id_arr) . ")";
        $imgs = $this->App->find($sql);
        if (!empty($imgs)) {
            foreach ($imgs as $row) {
                if (!empty($row['goods_thumb']))
                    Import::fileop()->delete_file(SYS_PATH . $row['goods_thumb']); //
                if (!empty($row['goods_img']))
                    Import::fileop()->delete_file(SYS_PATH . $row['goods_img']); //
                if (!empty($row['original_img']))
                    Import::fileop()->delete_file(SYS_PATH . $row['original_img']); //
            }
            unset($imgs);
        }


        foreach ($id_arr as $id) {
            if (Import::basic()->int_preg($id)) {
                if ($this->App->delete('gift_bag', 'bid', $id)) { //删除商品
                }
            }
        }
        $this->action('system', 'add_admin_log', '删除礼包：' . @implode(',', $id_arr));
        return true;
    }

}

?>