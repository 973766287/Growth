<?php

class CatetreeController extends Controller {

    function __construct() {
        $this->js(array('time.js')); //将js文件放到页面头
        $this->css(array('jquery_dialog.css'));
    }

    /* 析构函数 */

    function __destruct() {
        unset($rt);
    }

    /* 商品分类页面
     *  $cid 分类id
     *  type int
     */

    function index($rs = array()) {

        //所有分类
        $rt['catList'] = $this->get_goods_cate_tree();

       
        //设置页面meta cat_title
        $title = '全部分类';
        $this->title($title . ' - ' . $GLOBALS['LANG']['site_name']);
        $this->meta("title", $title);
        $this->meta("keywords", htmlspecialchars($GLOBALS['LANG']['keywords']));
        $this->meta("description", htmlspecialchars($GLOBALS['LANG']['cat_desc']));
        $this->set('rt', $rt);

        $this->template('cate_tree');
    }

    //获取商品分类
    function get_goods_cate_tree($cid = 0) {
        $three_arr = array();
        $sql = 'SELECT count(cat_id) FROM `' . $this->App->prefix() . "goods_cate` WHERE parent_id = '$cid' AND is_show = 1";
        if ($this->App->findvar($sql) || $cid == 0) {
            $sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show,tb1.cat_title,tb1.cat_desc, tb1.keywords,tb1.show_in_nav,tb1.sort_order, COUNT(tb2.cat_id) AS goods_count FROM `' . $this->App->prefix() . "goods_cate` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2";
            $sql .=" ON tb1.cat_id = tb2.cat_id";
            $sql .= " WHERE tb1.parent_id = '$cid' GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.cat_id ASC";
            $res = $this->App->find($sql);
            foreach ($res as $row) {
                $three_arr[$row['cat_id']]['id'] = $row['cat_id'];
                $three_arr[$row['cat_id']]['parent_id'] = $row['parent_id'];
                $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
                $three_arr[$row['cat_id']]['is_show'] = $row['is_show'];
                $three_arr[$row['cat_id']]['show_in_nav'] = $row['show_in_nav'];
                $three_arr[$row['cat_id']]['cat_title'] = $row['cat_title'];
                $three_arr[$row['cat_id']]['sort_order'] = $row['sort_order'];
                $three_arr[$row['cat_id']]['goods_count'] = $row['goods_count'];
                $three_arr[$row['cat_id']]['keywords'] = $row['keywords'];
                $three_arr[$row['cat_id']]['cat_desc'] = $row['cat_desc'];
                $three_arr[$row['cat_id']]['url'] = get_url($row['cat_name'], $row['cat_id'], "costume.php?cid=" . $row["cat_id"], 'goodscate', array('catalog', 'index', $row['cat_id']));

                if (isset($row['cat_id']) != NULL) {
                    $three_arr[$row['cat_id']]['cat_id'] = $this->get_goods_cate_tree($row['cat_id']);
                }
            }
        }
        return $three_arr;
    }
//获商品子自分类cat_id
    function get_goods_sub_cat_ids($cid = 0) {
        //if(!($cid>=0)) return false;
        $rts = $this->get_goods_cate_tree($cid);
        if ($cid > 0) {
            $cids[] = $cid;
        }
        if (!empty($rts)) {
            foreach ($rts as $row) {
                $cids[] = $row['id'];
                if (!empty($row['cat_id'])) {
                    foreach ($row['cat_id'] as $rows) {
                        $cids[] = $rows['id'];
                        if (!empty($rows['cat_id'])) {
                            foreach ($rows['cat_id'] as $rowss) {
                                $cids[] = $rowss['id'];
                            } // end foreach
                        } // end if
                    } // end foreach
                } // end if
            } // end foreach
        }// end if
        return $cids;
    }
}

?>