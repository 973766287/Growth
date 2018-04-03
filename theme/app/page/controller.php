<?php

class PageController extends Controller {

    function __construct() {
        $this->layout('index');
    }

    function index() {

        //今日抢鲜
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_best='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and is_best ='1' ORDER BY sort_order ASC,supplier_id asc, goods_id DESC LIMIT 4";
        $qx = $this->App->find($sql);
        $this->set('qx', $qx);
        //###########干果专区##################
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(589);
        $ids = implode(",", $idarr);
        $sql = "SELECT   goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'    ORDER BY sort_order ASC,supplier_id asc, goods_id DESC  LIMIT 10";
        $DryFruits = $this->App->find($sql);
        $this->set('DryFruits', $DryFruits);

        //#########潮服专区#############
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(606);
        $ids = implode(",", $idarr);
        $sql = "SELECT   goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC,supplier_id asc, goods_id DESC    LIMIT 10";
        $Chaofu = $this->App->find($sql);
        $this->set('Chaofu', $Chaofu);

        //#########鞋子专区#############
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(590);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'  ORDER BY sort_order ASC,supplier_id asc, goods_id DESC    LIMIT 10";
        $Shoes = $this->App->find($sql);
        $this->set('Shoes', $Shoes);

        //#########丝袜专区#############
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(583);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC,supplier_id asc, goods_id DESC   LIMIT 10";
        $Stockings = $this->App->find($sql);
        $this->set('Stockings', $Stockings);
		
		
		  $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(632);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC,supplier_id asc, goods_id DESC   LIMIT 10";
       $neiyis = $this->App->find($sql);
        $this->set('neiyis', $neiyis);
		
		
		  $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(639);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC,supplier_id asc, goods_id DESC   LIMIT 10";
        $peishis = $this->App->find($sql);
        $this->set('peishis', $peishis);
		
		
		  $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(645);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC,supplier_id asc, goods_id DESC   LIMIT 10";
        $yunyings = $this->App->find($sql);
        $this->set('yunyings', $yunyings);
//所有分类
        $catList = $this->get_goods_cate_tree();

        $this->set('catList', $catList);

        //PC端首页banner广告位 
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='37' and is_show='1'  LIMIT 2";
        $rt['ad37'] = $this->App->find($sql);
        //PC端首页每日精品
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='38' and is_show='1'  LIMIT 2";
        $rt['ad38'] = $this->App->find($sql);
        //PC端首页今日抢鲜
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='39' and is_show='1'  LIMIT 1";
        $rt['ad39'] = $this->App->findrow($sql);
   //PC端首页今日抢鲜下方横条广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='46' and is_show='1'  LIMIT 1";
        $rt['ad46'] = $this->App->findrow($sql);
        //PC端首页干果广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='40'and is_show='1'  LIMIT 1";
        $rt['ad40'] = $this->App->findrow($sql);
        //PC端首页潮服广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='41' and is_show='1'  LIMIT 1";
        $rt['ad41'] = $this->App->findrow($sql);
        //PC端首页鞋子广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='42'and is_show='1'  LIMIT 1";
        $rt['ad42'] = $this->App->findrow($sql);
        //PC端首页丝袜广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='43' and is_show='1'  LIMIT 1";
        $rt['ad43'] = $this->App->findrow($sql);
        //PC端视频右侧广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='44' and is_show='1'  LIMIT 1";
        $rt['ad44'] = $this->App->findrow($sql);
		
		 //PC端首页内衣位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='47' and is_show='1'  LIMIT 1";
        $rt['ad47'] = $this->App->findrow($sql);
		
		 //PC端首页配饰位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='48' and is_show='1'  LIMIT 1";
        $rt['ad48'] = $this->App->findrow($sql);
		
		 //PC端首页孕婴童广告位
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='49' and is_show='1'  LIMIT 1";
        $rt['ad49'] = $this->App->findrow($sql);


      

$rt['allcommentlist'] = $this->action('product','get_comment_list',0,0,3);


        $this->title($GLOBALS['LANG']['site_name']);

        $this->set("rt", $rt);


        $this->meta("title", $title);
        $this->meta("keywords", htmlspecialchars($rt['goodsinfo']['meta_keys']));
        $this->meta("description", htmlspecialchars($rt['goodsinfo']['meta_desc']));

        $this->template('index');
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

    //获取商品分类
    function get_goods_cate_tree($cid = 0,$s = 0) {
	
	if($s){
	$limit = " limit {$s} ";
	}
        $three_arr = array();
        $sql = 'SELECT count(cat_id) FROM `' . $this->App->prefix() . "goods_cate` WHERE parent_id = '$cid' AND is_show = 1";
        if ($this->App->findvar($sql) || $cid == 0) {
            $sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show,tb1.cat_title,tb1.cat_desc, tb1.keywords,tb1.show_in_nav,tb1.sort_order, COUNT(tb2.cat_id) AS goods_count FROM `' . $this->App->prefix() . "goods_cate` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2";
            $sql .=" ON tb1.cat_id = tb2.cat_id";
            $sql .= " WHERE tb1.parent_id = '$cid' GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.cat_id ASC {$limit}";
            $res = $this->App->find($sql);
			
			$i = 1;
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

                $three_arr[$row['cat_id']]['i'] = $i++;
				
                if (isset($row['cat_id']) != NULL) {
                    $three_arr[$row['cat_id']]['cat_id'] = $this->get_goods_cate_tree($row['cat_id'],2);
                }
            }
        }
        return $three_arr;
    }

}

?>