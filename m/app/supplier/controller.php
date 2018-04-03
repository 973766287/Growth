<?php

class SupplierController extends Controller {

    //构造函数，自动新建对象
    function __construct() {
         $this->layout('supplier');
    }

    function get_is_subscribe() {
        $uid = $this->Session->read('User.uid');
        return $this->App->findvar("SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
    }

    function get_site_nav($t = 'top', $list = 4 , $gid=0) {
        $ts = Common::_return_px();
        $cache = Import::ajincache();
        $cache->SetFunction(__FUNCTION__);
        $cache->SetMode('sitemes' . $ts);
        $fn = $cache->fpath(array('0' => $t));
        if (file_exists($fn) && !$cache->GetClose()) {
            include($fn);
        } else {
            $sql = "SELECT * FROM `{$this->App->prefix()}supplier_nav_wx` WHERE is_show = '1' AND type = '$t' and supplier_id ={$gid} ORDER BY vieworder ASC, id ASC LIMIT $list";
            $rt = $this->App->find($sql);

            $cache->write($fn, $rt, 'rt');
        }

        return $rt;
    }


    function index($gid = 0) {
        $this->action('common', 'checkjump');

        $t = Common::_return_px();
        $cache = Import::ajincache();
        $cache->SetFunction(__FUNCTION__);
        $cache->SetMode('page' . $t);
        $fn = $cache->fpath(array('0' => ''));
        if (file_exists($fn) && !$cache->GetClose()) {
            include($fn);
        } else {
            //分类产品
            $sql = "SELECT cat_name,cat_url,cat_img,cat_id,supplier_id FROM `{$this->App->prefix()}supplier_category` WHERE is_show='1' and is_index='1' and supplier_id = {$gid} ORDER BY sort_order ASC";
			//echo $sql;
            $rt['cat'] = $this->App->find($sql);
            if (empty($rt['cat'])) {
                $sql = "SELECT cat_name,cat_url,cat_img,cat_id FROM `{$this->App->prefix()}supplier_category` WHERE is_show='1'  and is_index='1' and supplier_id = {$gid} ORDER BY sort_order ASC";
                $rt['cat'] = $this->App->find($sql);
            }
            if (!empty($rt['cat']))
                foreach ($rt['cat'] as $row) {
                    //$sub_cids = $this->action('catalog','get_goods_sub_cat_ids',$row['cat_id']);
                    $cid = $row['cat_id'];
                    //$sql = "SELECT goods_id,goods_name,goods_thumb,goods_img,pifa_price,shop_price,sale_count FROM `{$this->App->prefix()}goods` WHERE cat_id ='$cid' AND is_on_sale='1' AND is_jifen = '0' AND is_delete = '0' AND (is_best ='1' OR is_new='1' OR is_hot='1') ORDER BY sort_order ASC LIMIT 6";
                    $sql = "SELECT goods_id,goods_name,goods_thumb,goods_img,pifa_price,shop_price,sale_count FROM `{$this->App->prefix()}goods` WHERE cat_id ='$cid' AND is_on_sale='1' AND is_jifen = '0' AND is_delete = '0'  and is_prize=0  ORDER BY sort_order ASC LIMIT 6";
                    $rt['goods'][$row['cat_id']] = $this->App->find($sql);
                }

            //推荐产品
            $sql = "SELECT goods_id,goods_name,goods_thumb,goods_img,pifa_price,shop_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_best='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and is_best ='1' ORDER BY sort_order ASC, goods_id DESC LIMIT 6";
            $rt['listsjf'] = $this->App->find($sql);

            //单个推荐商品
            //$sql = "SELECT * FROM `{$this->App->prefix()}goods` WHERE is_best = '1' AND is_on_sale = '1' ORDER BY sort_order ASC limit 1";
            //$rt['tj'] = $this->App->findrow($sql);
            //统计
            $sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0 and supplier_id=$gid LIMIT 1";
            $rt['zgcount'] = $this->App->findvar($sql);

            $sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_new='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0 LIMIT 1";
            $rt['zgnewcount'] = $this->App->findvar($sql);

            //品牌列表
            $sql = "SELECT distinct brand_name, brand_id,brand_name,brand_banner,brand_logo FROM `{$this->App->prefix()}brand` WHERE is_show='1' AND is_promote='1' and is_prize=0 ORDER BY sort_order ASC,brand_id LIMIT 7";
            // $rt['blist'] =  $this->App->find($sql);
            //产品分类
            $sql = "SELECT cat_name,cat_url,cat_img,cat_id FROM `{$this->App->prefix()}goods_cate` WHERE is_show='1' ORDER BY sort_order ASC";
            $rt['indexcat'] = $this->App->find($sql);

            $sql = "SELECT tb1.*,tb2.ad_name FROM `{$this->App->prefix()}supplier_ad_content` AS tb1 LEFT JOIN `{$this->App->prefix()}supplier_ad_position` AS tb2 ON tb1.tid = tb2.tid WHERE tb1.supplier_id={$gid} and tb1.is_show='1' AND tb2.ad_name LIKE '%首页轮播%' ORDER BY tb1.vieworder ASC,tb1.addtime DESC LIMIT 5";
            $rt['lunbo'] = $this->App->find($sql);
			
			
			$sql = "SELECT tb1.*,tb2.ad_name FROM `{$this->App->prefix()}supplier_ad_content` AS tb1 LEFT JOIN `{$this->App->prefix()}supplier_ad_position` AS tb2 ON tb1.tid = tb2.tid WHERE tb1.supplier_id={$gid} and tb1.is_show='1'  ORDER BY tb1.vieworder ASC,tb1.addtime DESC LIMIT 5";
            $rt['lunbo'] = $this->App->find($sql);

            //
            $rt['navtop'] = $this->get_site_nav('middle', 4, $gid);

            $cache->write($fn, $rt, 'rt');
        }

        $uid = $this->Session->read('User.uid');

        $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' LIMIT 1";
        $rt['zordercount'] = $this->App->findvar($sql);




 //今日抢鲜
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,promote_price,market_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_shop_new='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and  
		supplier_id={$gid} ORDER BY sort_order ASC, goods_id DESC LIMIT 4";
        $new = $this->App->find($sql);
        $this->set('new_goods', $new);
		
		 //推荐
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,promote_price,market_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_shop_best='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0 
		 and supplier_id={$gid} ORDER BY sort_order ASC, goods_id DESC LIMIT 6";
		//echo $sql;
        $tj = $this->App->find($sql);
		
		//print_r( $tj) ;
        $this->set('best_goods', $tj);
		 //热销
        $sql = "SELECT goods_id,goods_name,sort_desc ,goods_thumb,goods_img,pifa_price,shop_price,promote_price,market_price,sale_count FROM `{$this->App->prefix()}goods` WHERE is_shop_hot='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' and is_prize=0  and 
		 supplier_id={$gid} ORDER BY sort_order ASC, goods_id DESC LIMIT 4";
		//echo $sql;
        $rx = $this->App->find($sql);
        $this->set('hot_goods', $rx);
		
		
		
       
        /*         * 获取广告位 */
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='34' and is_show='1'  LIMIT 1";
        $rt['ad1'] = $this->App->findrow($sql);
        $sql = "SELECT * FROM `{$this->App->prefix()}ad_content` WHERE tid='35' and is_show='1'  LIMIT 1";
        $rt['ad2'] = $this->App->findrow($sql);
        $this->set('rt', $rt);
        $this->set('title', $GLOBALS['LANG']['metatitle']);
        $this->set('description', $GLOBALS['LANG']['metadesc']);

        $this->set('page', $page);
		
			
		$sql = "select * from `{$this->App->prefix()}supplier_systemconfig` where supplier_id={$gid}";
		$info = $this->App->findrow($sql);
		
		
		 $sql = "SELECT * FROM `{$this->App->prefix()}supplier_street` WHERE  supplier_id={$gid}";
			 $fenshu = $this->App->findrow($sql);
			  $this->set("fenshu", $fenshu);
		
		$mb = $info['mubanid'];
		  $this->set('mubanid', $info['mubanid']);
		  $this->set('info', $info);
		 $this->set('suppid', $gid);
		 $this->title($info['site_title']);
       // $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
//        $this->set('mubanid', $GLOBALS['LANG']['mubanid']);
        $this->template($mb . '/page_index');
    }

   
   
   function about(){
	   
	   $gid = $_GET['suppId'];
	   
	   $sql = "select * from `{$this->App->prefix()}supplier_systemconfig` where supplier_id={$gid}";
		$info = $this->App->findrow($sql);
		
		$mb = $info['mubanid'];
		  $this->set('mubanid', $info['mubanid']);
		  $this->set('info', $info);
		 $this->set('suppid', $gid);
		 $this->title($info['site_title']);
		 
		  $sql = "SELECT * FROM `{$this->App->prefix()}supplier` WHERE  supplier_id={$gid} limit 1";
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
			
			 $sql = "SELECT count(*) as count FROM `{$this->App->prefix()}goods` WHERE  supplier_id=$gid and is_check='1' and is_delete='0'";
			 
			
            $count = $this->App->findrow($sql);
			$s_info['count'] = $count['count'];
			
			$s_info['qq'] = explode(",",$s_info['custome_qq']);
			 $this->set("s_info", $s_info);
			 
			  $sql = "SELECT * FROM `{$this->App->prefix()}supplier_street` WHERE  supplier_id={$gid}";
			 $fenshu = $this->App->findrow($sql);
			  $this->set("fenshu", $fenshu);
			 
     
        $this->template($mb . '/about');
	   }
	   
	   
	   
	   function dianpufenlei(){
		     $gid = $_GET['suppId'];

			 $categories = $this->get_categories_tree(0,$gid);
			// print_r($categories);
			   $this->set('categories', $categories);
			  $sql = "select * from `{$this->App->prefix()}supplier_systemconfig` where supplier_id={$gid}";
		$info = $this->App->findrow($sql);
		
		$mb = $info['mubanid'];
		  $this->set('mubanid', $info['mubanid']);
		  $this->set('info', $info);
		 $this->set('suppid', $gid);
		 $this->title($info['site_title']);
     
        $this->template($mb . '/catalog_index');
		   }
		   
		   
		   
		   
		   /**
 * 获得指定分类同级的所有分类以及该分类下的子分类
 *
 * @access  public
 * @param   integer     $cat_id     分类编号
 * @return  array
 */
function get_categories_tree($cat_id = 0,$gid)
{
    if ($cat_id > 0)
    {
        $sql = 'SELECT parent_id FROM `{$this->App->prefix()}supplier_category` WHERE cat_id = $cat_id and supplier_id = $gid';
        $parent_id = $this->App->findvar($sql);
    }
    else
    {
        $parent_id = 0;
    }

    /*
     判断当前分类中全是是否是底级分类，
     如果是取出底级分类上级分类，
     如果不是取当前分类及其下的子分类
    */
    $sql = "SELECT count(*) FROM `{$this->App->prefix()}supplier_category` WHERE parent_id = '$parent_id' AND is_show = 1 and supplier_id = $gid ";
    if ($this->App->findvar($sql) || $parent_id == 0)
    {
        /* 获取当前分类及其子分类 */
        $sql = "SELECT * FROM `{$this->App->prefix()}supplier_category` WHERE parent_id = '$parent_id' AND is_show = 1 AND is_virtual=0  and supplier_id = $gid ORDER BY sort_order ASC, cat_id ASC";

        $res = $this->App->find($sql);
        $i = 1;
        foreach ($res AS $row)
        {
            if ($row['is_show'])
            {
                $cat_arr[$row['cat_id']]['id']   = $row['cat_id'];
                $cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
              //  $cat_arr[$row['cat_id']]['url']  = build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']);
		$cat_arr[$row['cat_id']]['img']   = $row['type_img'];
                if (isset($row['cat_id']) != NULL)
                {
                    $cat_arr[$row['cat_id']]['cat_id'] = $this->get_child_tree($row['cat_id'],$row['supplier_id']);
                }
				
				$cat_arr[$row['cat_id']]['i'] = $i++;
            }
        }
    }
    if(isset($cat_arr))
    {
        return $cat_arr;
    }
	

}

function get_child_tree($tree_id = 0,$gid)
{
    $three_arr = array();
    $sql = "SELECT count(*) FROM `{$this->App->prefix()}supplier_category` WHERE parent_id = '$tree_id' AND is_show = 1 and supplier_id = $gid ";
    if ($this->App->findvar($sql) || $tree_id == 0)
    {
        $child_sql = "SELECT * FROM `{$this->App->prefix()}supplier_category` WHERE parent_id = '$tree_id' AND is_show = 1 and supplier_id = $gid ORDER BY sort_order ASC, cat_id ASC";
        $res = $this->App->find($child_sql);
        foreach ($res AS $row)
        {
            if ($row['is_show'])

               $three_arr[$row['cat_id']]['id']   = $row['cat_id'];
               $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
	       $three_arr[$row['cat_id']]['img']   = $row['type_img'];
             //  $three_arr[$row['cat_id']]['url']  = build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']);

               if (isset($row['cat_id']) != NULL)
                   {
                       $three_arr[$row['cat_id']]['cat_id'] = $this->get_child_tree($row['cat_id']);

            }
        }
    }
    return $three_arr;
}



function InviteCodeActivation(){
	    $this->layout('Instead_h');
		
		$uid = $this->checked_instead_login();

		if($uid){
			 $this->jump(ADMIN_URL . 'user.php?act=Instead');
						exit();
			}
	  // echo $this->Session->read('InviteCode');
	    $mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
        $this->template($mb . '/activation');
		
	}
	
	
	function ajax_activation($data = array()){
	
	
	    $code = $data['code'];
		$invitecode = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}daili_invitecode` WHERE  InviteCode = '".$code."' limit 1");

		if(empty($invitecode)){
			echo "邀请码不正确，请联系代理商获取";
			}else{
				if($invitecode['status'] == 0){
				$this->Session->write('InviteCode',$code);
			//	$this->App->update('daili_invitecode', array('status' => 1,'updatetime' => mktime()), 'InviteCode', $code);
				echo  "success";
				}else{
					echo "邀请码已激活";
					}
				}
	   

		
	}

 
  function checked_instead_login() {
	    $uid = $this->Session->read('User.uid');
        $iuid = $this->Session->read('User.iuid');
        if (($uid > 0) || ($iuid > 0)) {
          if($uid > 0){
				$uid = $uid;
				}
				if($iuid > 0){
				$uid = $iuid;
				}
        }
        return $uid;
    }



   
}

?>