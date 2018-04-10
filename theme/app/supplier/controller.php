<?php
class SupplierController extends Controller{
	
	
	  function __construct() {
       $this->layout('supplier');
    }

    function index($suppId=0) {

	if(!($suppId>0)){
			$this->action('common','show404tpl');
			}else{
				$sql = 
			$this->set('suppId', $suppId);	
				}
			
			
        //今日抢鲜
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,promote_price,market_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_shop_new='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and  supplier_id={$suppId} ORDER BY sort_order ASC, goods_id DESC LIMIT 4";
        $new = $this->App->find($sql);
        $this->set('new', $new);
		
		 //推荐
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,promote_price,market_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_shop_best='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and supplier_id={$suppId} ORDER BY sort_order ASC, goods_id DESC LIMIT 6";
		//echo $sql;
        $tj = $this->App->find($sql);
		
		//print_r( $tj) ;
        $this->set('tj', $tj);
		 //热销
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,promote_price,market_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_shop_hot='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and supplier_id={$suppId} ORDER BY sort_order ASC, goods_id DESC LIMIT 4";
		//echo $sql;
        $rx = $this->App->find($sql);
        $this->set('rx', $rx);
		
		
        //###########干果专区##################
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(589);
        $ids = implode(",", $idarr);
        $sql = "SELECT   goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'    ORDER BY sort_order ASC, goods_id DESC  LIMIT 10";
        $DryFruits = $this->App->find($sql);
        $this->set('DryFruits', $DryFruits);

        //#########潮服专区#############
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(591);
        $ids = implode(",", $idarr);
        $sql = "SELECT   goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC, goods_id DESC    LIMIT 10";
        $Chaofu = $this->App->find($sql);
        $this->set('Chaofu', $Chaofu);

        //#########鞋子专区#############
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(590);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'  ORDER BY sort_order ASC, goods_id DESC    LIMIT 10";
        $Shoes = $this->App->find($sql);
        $this->set('Shoes', $Shoes);

        //#########丝袜专区#############
        $idarr = array();
        $idarr = $this->get_goods_sub_cat_ids(583);
        $ids = implode(",", $idarr);
        $sql = "SELECT  goods_id,goods_name,goods_brief ,goods_thumb,goods_img,pifa_price,shop_price,sale_count  from `{$this->App->prefix()}goods`   WHERE cat_id in($ids) AND is_on_sale='1' AND is_alone_sale='1' and is_prize=0 AND is_delete = '0'  AND is_jifen='0'   ORDER BY sort_order ASC, goods_id DESC   LIMIT 10";
        $Stockings = $this->App->find($sql);
        $this->set('Stockings', $Stockings);
//所有分类
        $catList = $this->get_goods_cate_tree($suppId);

        $this->set('catList', $catList);

        //PC端首页banner广告位 
        $sql = "SELECT * FROM `{$this->App->prefix()}supplier_ad_content` WHERE  is_show='1' and supplier_id={$suppId}";
        $rt['ad'] = $this->App->find($sql);
		
	
	//	foreach ($rt['ad'] as $ad){
//	
//		$pic[] = $ad['ad_img'];
//		}
//		
//		$pics = implode("|",$pic);
//		 $this->set('pics', $pics);
		
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


      

$rt['allcommentlist'] = $this->action('product','get_comment_list',0,0,3);


        $this->title($GLOBALS['LANG']['site_name']);

        $this->set("rt", $rt);


        $this->meta("title", $title);
        $this->meta("keywords", htmlspecialchars($rt['goodsinfo']['meta_keys']));
        $this->meta("description", htmlspecialchars($rt['goodsinfo']['meta_desc']));

        $sql = "SELECT   *  from `{$this->App->prefix()}supplier_systemconfig`   WHERE supplier_id = {$suppId} LIMIT 10";
        $info = $this->App->findrow($sql);
		$mb = $info['moban'];
		 $this->set("mb", $mb);
		 
		 
		   $sql = "SELECT * FROM `{$this->App->prefix()}supplier_nav` WHERE is_show = '1' AND type = 'middle' and supplier_id={$suppId} ORDER BY vieworder ASC, id ASC";
            $s_nav = $this->App->find($sql);
			
			 $this->set("s_nav", $s_nav);
			 
			 //店铺信息
			   $sql = "SELECT * FROM `{$this->App->prefix()}supplier_systemconfig` WHERE  supplier_id={$suppId} limit 1";
            $s_info = $this->App->findrow($sql);
			
			 $sql = "SELECT * FROM `{$this->App->prefix()}supplier` WHERE  supplier_id={$suppId} limit 1";
			 // echo $sql;
            $infos = $this->App->findrow($sql);
			
			if($infos['rank_id']==1){$s_info['dengji'] = "免费店铺";}
			if($infos['rank_id']==2){$s_info['dengji'] = "收费店铺";}
			
			$s_info['time'] = date('Y-m-d',$infos['add_time']);
			
			 $sql = "SELECT * FROM `{$this->App->prefix()}region` WHERE  region_id=".$infos['province']." limit 1";
            $province = $this->App->findrow($sql);
			$s_info['province'] = $province['region_name']; 
			 $sql = "SELECT * FROM `{$this->App->prefix()}region` WHERE  region_id=".$infos['city']." limit 1";
            $city = $this->App->findrow($sql);
			$s_info['city'] = $city['region_name'];
			
			 $sql = "SELECT * FROM `{$this->App->prefix()}region` WHERE  region_id=".$infos['district']." limit 1";
            $city = $this->App->findrow($sql);
			$s_info['district'] = $city['region_name'];  
			
			 $sql = "SELECT count(*) as count FROM `{$this->App->prefix()}goods` WHERE  supplier_id=".$suppId." and is_check='1' and is_delete='0'";
			 
			
            $count = $this->App->findrow($sql);
			$s_info['count'] = $count['count'];
			
			$s_info['qq'] = explode(",",$s_info['custome_qq']);
			 $this->set("s_info", $s_info);
			 
			 
			 $sql = "SELECT * FROM `{$this->App->prefix()}supplier_street` WHERE  supplier_id={$suppId}";
			 $fenshu = $this->App->findrow($sql);
			  $this->set("fenshu", $fenshu);
			 
			 
			 $sql = "select cat_id,cat_name,cat_img,cat_url from `{$this->App->prefix()}supplier_category` where 
	supplier_id=".$suppId." and is_show=1 and is_index=1 order by sort_order desc";
	$result = $this->App->find($sql);
	if($result){
		foreach($result as $key => $row){
			$result[$key]['goods'] = $this->get_supplier_category_goods($row['cat_id'],$row['cat_goods_limit'],$suppId);
		}
	}
	$this->set("category_goods", $result);
	
			
		if($info['is_open'] == 1){
		 $this->template($mb.'/supplier_index');
		}else{
			  $this->layout('kong');
			$this->set("close_desc", $info['close_desc']);
			 $this->template($mb.'/supplier_error');
			}
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
    function get_goods_cate_tree($sid=0,$cid = 0) {
        $three_arr = array();
        $sql = 'SELECT count(cat_id) FROM `' . $this->App->prefix() . "supplier_category` WHERE supplier_id = $sid and parent_id = '$cid' AND is_show = 1";
        if ($this->App->findvar($sql) || $cid == 0) {
            $sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show,tb1.cat_title,tb1.cat_desc, tb1.keywords,tb1.show_in_nav,tb1.sort_order, COUNT(tb2.cat_id) AS goods_count FROM `' . $this->App->prefix() . "supplier_category` AS tb1";
            $sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2";
            $sql .=" ON tb1.cat_id = tb2.cat_id";
            $sql .= " WHERE  tb1.supplier_id = $sid and tb1.parent_id = '$cid' GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.cat_id ASC";
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
                    $three_arr[$row['cat_id']]['cat_id'] = $this->get_goods_cate_tree($sid,$row['cat_id']);
                }
            }
        }
        return $three_arr;
    }

 
 
 
 /*
 * 首页推荐分类中商品显示
 * @param int $catid  分类id
 * @param int $limit  分类下首页显示的商品id
 */
function get_supplier_category_goods($catid=0,$limit=10,$suppId=0){
	
	$sql = "SELECT DISTINCT g.goods_id,g.* FROM `{$this->App->prefix()}goods` AS g, `{$this->App->prefix()}supplier_goods_cat` AS gc  
	WHERE gc.cat_id =".$catid." AND gc.supplier_id =".$suppId." AND gc.goods_id = g.goods_id 
	AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 
	ORDER BY g.goods_id DESC LIMIT 10";
	//echo $sql;
	$result = $this->App->find($sql);
	
	$goods = array();
	if($result){
		foreach ($result AS $idx => $row)
        {
            if ($row['promote_price'] > 0)
            {
                $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
            }
            else
            {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id']           = $row['goods_id'];
            $goods[$idx]['goods_name']         = $row['goods_name'];
            $goods[$idx]['brief']        = $row['goods_brief'];
            $goods[$idx]['brand_name']   = isset($goods_data['brand'][$row['goods_id']]) ? $goods_data['brand'][$row['goods_id']] : '';
//            $goods[$idx]['goods_style_name']   = add_style($row['goods_name'],$row['goods_name_style']);
//
//            $goods[$idx]['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
//                                               sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
//            $goods[$idx]['short_style_name']   = add_style($goods[$idx]['short_name'],$row['goods_name_style']);
//            $goods[$idx]['market_price'] = price_format($row['market_price']);
//            $goods[$idx]['shop_price']   = price_format($row['shop_price']);
 $goods[$idx]['market_price'] = $row['market_price'];
            $goods[$idx]['pifa_price']   = $row['pifa_price'];
//            $goods[$idx]['thumb']        = get_image_path($row['goods_id'], $row['goods_thumb'], true);
//            $goods[$idx]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
 $goods[$idx]['goods_img']    = $row['goods_img'];
//			$goods[$idx]['original_img'] = get_image_path($row['goods_id'], $row['original_img']);
            $goods[$idx]['url']          = get_url($row['goods_name'], $row['goods_id'], SITE_URL . 'product.php?id=' . $row['goods_id'], 'goods', array('product', 'index', $row['goods_id']));
        }
	}
	
	return $goods;
	
}






}
?>