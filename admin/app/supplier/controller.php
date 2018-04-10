<?php

class SupplierController extends Controller {

    function __construct() {
        $this->css('content.css');
        $this->css(array('content.css', 'calendar.css'));  //look  添加时间显示样式calendar.css
        $this->js(array('calendar.js', 'calendar-setup.js', 'calendar-zh.js', 'supplier.js'));  //look  添加时间显示特效js
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

    function supplier_list($data = array()) {
		
       $status = isset($_GET['status']) ? $_GET['status'] : '';
	   $this->set('status',  $status);
	   $sp_name = isset($_GET['keyword']) ? $_GET['keyword'] : '';
	 
	   $r_id = isset($_GET['rank_id']) ? $_GET['rank_id'] : 0;
   
	   
	   
	   $where = " where 1 = 1";
	   if($status){
		   $where .= " and s.status={$status}";
		   }else{
			     $where .= " and s.status != 1";
			   }
	   if(!empty($sp_name)){
		   $where .= " and s.supplier_name like ('%{$sp_name}%')";
		   }
		   
		   if($r_id){
			     $where .= " and s.rank_id={$r_id}";
			   }
		 $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 30;
        $start = ($page - 1) * $list;
		
		
		 $sql = "SELECT COUNT(supplier_id) FROM `{$this->App->prefix()}supplier` {$where}";
		 
		
		
        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);
	
		$sql = "SELECT s.supplier_id,s.is_open,u.user_name, s.rank_id,sr.rank_name,s.supplier_name, s.tel, s.system_fee, s.supplier_bond, s.supplier_rebate, s.supplier_remark, s.status  ".
                "FROM `{$this->App->prefix()}supplier` as s ".
				"left join `{$this->App->prefix()}user` as u on s.user_id = u.user_id ".
				"left join `{$this->App->prefix()}supplier_rank` as sr on s.rank_id = sr.rank_id".
			//	"left join `{$this->App->prefix()}supplier_shop_config` as ssc on s.supplier_id = ssc.supplier_id ".
				" {$where} and applynum=3 ORDER BY s.supplier_id DESC LIMIT $start,$list";
		
			//$sql = "SELECT s.supplier_id,s.is_open,u.user_name, s.rank_id,sr.rank_name,s.supplier_name, s.tel, s.system_fee, s.supplier_bond, s.supplier_rebate, s.supplier_remark, s.status  ".
//                "FROM `{$this->App->prefix()}supplier` as s ".
//				"left join `{$this->App->prefix()}user` as u on s.user_id = u.user_id ".
//				"left join `{$this->App->prefix()}supplier_rank` as sr on s.rank_id = sr.rank_id".
//			//	"left join `{$this->App->prefix()}supplier_shop_config` as ssc on s.supplier_id = ssc.supplier_id ".
//				" where s.status!= 1 and applynum=3 ORDER BY s.supplier_id DESC LIMIT $start,$list";
			
			 
			
        $rt = $this->App->find($sql);
		
 $this->set('supplier_list', $rt);
       
        $this->template('supplier_list');
    }
	
	//入驻商申请审核
	function edit($data = array()){
		
		  $supplier_id = isset($data['id']) ? $data['id'] : '0';


	 $sql = "SELECT * ".
	           " FROM `{$this->App->prefix()}supplier` ".
	        
	          
	           " where supplier_id = {$supplier_id}";
			   
			   
			   
        $rt = $this->App->findrow($sql);
		if($rt['rank_id'] == 10){
		$rt['rank_name'] = "收费店铺";
		}else{
			$rt['rank_name'] = "免费店铺";
			}
			
			
				$sql = "select str_name from `{$this->App->prefix()}street_category` where str_id=".$rt['type_id'];
	$info = $this->App->findrow($sql);

	$rt['type_name'] = $info['str_name'];
	
	      $sql1 = "select region_name from `{$this->App->prefix()}region` where region_id=".$rt['province'];
		  $province = $this->App->findrow($sql1);
		  $rt['province_name']= $province['region_name'];
		   $sql2 = "select region_name from `{$this->App->prefix()}region` where region_id=".$rt['city'];
		     $city = $this->App->findrow($sql2);
			   $rt['city_name']= $city['region_name'];
		    $sql3 = "select region_name from `{$this->App->prefix()}region` where region_id=".$rt['district'];
		        $district = $this->App->findrow($sql3);
				  $rt['district_name']= $district['region_name'];
				  
				  
				  
				    if (!empty($_POST)) {
						
						 $save['bank_account_name']	=	trim($_POST['bank_account_name']);
							$save['bank_account_number']	=	trim($_POST['bank_account_number']);
							$save['bank_name']	=	trim($_POST['bank_name']);
							$save['bank_code']	=	trim($_POST['bank_code']);
							$save['settlement_bank_account_name']	=	trim($_POST['settlement_bank_account_name']);
							$save['settlement_bank_account_number']	=	trim($_POST['settlement_bank_account_number']);
							$save['settlement_bank_name']	=	trim($_POST['settlement_bank_name']);
							$save['settlement_bank_code']	=	trim($_POST['settlement_bank_code']);
							
							$save['system_fee']   = trim($_POST['system_fee']);
							$save['supplier_bond']   = trim($_POST['supplier_bond']);
							$save['supplier_rebate']   = trim($_POST['supplier_rebate']);
							$save['supplier_rebate_paytime']   = intval($_POST['supplier_rebate_paytime']);
							$save['supplier_remark']   = trim($_POST['supplier_remark']);
							$save['status']   = intval($_POST['status']);
							
							
							
							  /* 取得供货商信息 */
  //$sql = "SELECT * FROM " . $ecs->table('supplier') . " WHERE supplier_id = '" . $supplier_id ."' ";
  $sql = "select s.supplier_id,s.add_time,s.status,s.rank_id, u.* from `{$this->App->prefix()}supplier` as s left join `{$this->App->prefix()}user`".
  		 " as u on s.user_id=u.user_id where s.supplier_id=".$supplier_id;
  $supplier_old = $this->App->findrow($sql);
  
  
 
 
  if($save['status'] == 1){
  	//审核通过时就是店铺创建成功的时间
  	$save['add_time'] = mktime();
  }

  //操作店铺商品与店铺街信息
  if($save['status'] != $supplier_old['status'] && $save['status'] == -1){
	  //审核不通过
	  //店铺街信息失效
	
		
		$check_info['is_groom'] = 0;
		$check_info['is_show'] = 0;
		$check_info['supplier_notice'] = "";
		$check_info['status'] = 0;
	  $this->App->update('supplier_street', $check_info, 'supplier_id', $supplier_id);
	  //商品下架
	 
		  $good_info['is_on_sale'] = 0;
		
	 $this->App->update('goods', $good_info, 'supplier_id',$supplier_id);
	  //删除店铺所在的标签
	   $this->App->delete('supplier_tag_map','supplier_id',$supplier_id);
  }
  
  //更新相关店铺的管理员状态
  if($save['status'] == 1){
  $sql = "select * from `{$this->App->prefix()}supplier_admin_user` where supplier_id=".$supplier_old['supplier_id'];
  //   $rt = $this->App->findrow($sql);
  $info = $this->App->findrow($sql);
  if(count($info)>0){
	  
	  
			  
			   $admin['uid'] = $supplier_old['user_id'];
  $admin['adminname'] = $supplier_old['user_name'];
  $admin['groupid'] = 1;
   $admin['typeid'] = $supplier_old['rank_id'];
  $admin['password'] = $supplier_old['password'];
  $admin['lasttime'] = $supplier_old['last_login'];
  $admin['lastip'] = $supplier_old['last_ip'];
  $admin['active'] = 1;
  $admin['email'] = $supplier_old['email'];
  $admin['addtime'] = $supplier_old['last_login'];
  $admin['priv_password'] = $supplier_old['password'];
  $admin['supplier_id'] = $supplier_old['supplier_id'];

	 $this->App->update('supplier_admin_user', $admin, 'supplier_id',$supplier_old['supplier_id']);
	 
  }else{
	  
	
//	  	$insql = "INSERT INTO `{$this->App->prefix()}admin` (`uid`, `adminname`, `groupid`, `password`, `lasttime`, `lastip`, `active`,`email`,`add_time`, `priv_password`,  `supplier_id`) ".
//					"VALUES(".$supplier_old['user_id'].", '".$supplier_old['user_name']."',".$supplier_old['groupid'].", '".$supplier_old['password']."', ".$supplier_old['last_login'].",'".$supplier_old['last_ip']."', 1, '".$supplier_old['email']."','".$save['add_time']."','".$supplier_old['password']."', ".$supplier_old['supplier_id'].")";
//					
//					
//	  	if($this->App->query($insql)){
//			  $this->action('common', 'showdiv', $this->getthisurl());
//			}

 $admin['uid'] = $supplier_old['user_id'];
  $admin['adminname'] = $supplier_old['user_name'];
  $admin['groupid'] = 1;
   $admin['typeid'] = $supplier_old['rank_id'];
  $admin['password'] = $supplier_old['password'];
  $admin['lasttime'] = $supplier_old['last_login'];
  $admin['lastip'] = $supplier_old['last_ip'];
  $admin['active'] = 1;
  $admin['email'] = $supplier_old['email'];
  $admin['addtime'] = mktime();
  $admin['priv_password'] = $supplier_old['password'];
  $admin['supplier_id'] = $supplier_old['supplier_id'];
  
  
  
	  $this->App->insert('supplier_admin_user', $admin);
		
  
  }
  }else{
	    $this->App->delete('supplier_admin_user','supplier_id',$supplier_id);
	  
	  }
	

	/* 保存供货商信息 */

				
				

	if ($_POST['status']!='1')
	{
		$sql="update `{$this->App->prefix()}goods` set is_on_sale=0 where supplier_id='".$supplier_id;
		$this->App->query($sql);
	}else{



                if ($this->App->update('supplier', $save, 'supplier_id', $supplier_id)) {
                    $this->action('common', 'showdiv', $this->getthisurl());
                }

	}
              
			  
		  }
		  

        $this->set('supplier', $rt);
	
	 $this->template('supplier_edit');
	 
	 
		}
		
		//删除入驻商zzzzzzzzzzzz
		function  delete($data = array()){
			  if (empty($data['id']))
            die("非法操作，ID为空！");
         $supplier_id = $data['id'];
			
			$sql = "SELECT * FROM `{$this->App->prefix()}supplier` WHERE supplier_id = ".$supplier_id;
    $supplier = $this->App->findrow($sql);
    if (count($supplier) <= 0)
    {
        die('该供应商不存在！');
    }


	if($supplier_id > 0){
		
		$sql = 'delete FROM `{$this->App->prefix()}supplier_rebate_log` WHERE rebateid in (SELECT rebate_id FROM `{$this->App->prefix()}supplier_rebate` WHERE supplier_id ='.$supplier_id.')';
				$this->App->query($sql);
		 $this->App->delete('supplier_admin_user','supplier_id',$supplier_id);
		  $this->App->delete('supplier_article','supplier_id',$supplier_id);
		   $this->App->delete('supplier_category','supplier_id',$supplier_id);
		    $this->App->delete('supplier_cat_recommend','supplier_id',$supplier_id);
			 $this->App->delete('supplier_goods_cat','supplier_id',$supplier_id);
			  $this->App->delete('supplier_guanzhu','supplier_id',$supplier_id);
			   $this->App->delete('supplier_money_log','supplier_id',$supplier_id);
			    $this->App->delete('supplier_nav','supplier_id',$supplier_id);
				 $this->App->delete('supplier_ad_position','supplier_id',$supplier_id);
				  $this->App->delete('supplier_ad_content','supplier_id',$supplier_id);
				
				
				
				
				 
				 
				  $this->App->delete('supplier_systemconfig','supplier_id',$supplier_id);
				   $this->App->delete('supplier_shop_config','supplier_id',$supplier_id);
				   $this->App->delete('supplier_street','supplier_id',$supplier_id);
				 	$this->App->delete('supplier_tag_map','supplier_id',$supplier_id);
		
		
		//商品相关删除信息
		
		 
		
			$sql = 'delete FROM `{$this->App->prefix()}goods_activity` WHERE goods_id in (SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE supplier_id ='.$supplier_id.')';
			$this->App->query($sql);
			
			
			$sql = 'delete FROM `{$this->App->prefix()}goods_attr` WHERE goods_id in (SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE 
			supplier_id ='.$supplier_id.')';
			$this->App->query($sql);
			$sql = 'delete FROM `{$this->App->prefix()}goods_cat` WHERE goods_id in (SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE 
			supplier_id ='.$supplier_id.')';
			$this->App->query($sql);
			$sql = 'delete FROM `{$this->App->prefix()}goods_gallery` WHERE goods_id in (SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE supplier_id ='.$supplier_id.')';
			$this->App->query($sql);
			$sql = 'delete FROM `{$this->App->prefix()}goods_tag` WHERE goods_id in (SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE 
			supplier_id ='.$supplier_id.')';
			$this->App->query($sql);
			$sql = 'delete FROM `{$this->App->prefix()}products` WHERE goods_id in (SELECT goods_id FROM `{$this->App->prefix()}goods` WHERE 
			supplier_id ='.$supplier_id.')';
			
			$this->App->query($sql);
			
			
			  
		
		//最后删除中间表信息
		 $this->App->delete('goods','supplier_id',$supplier_id);
		  $this->App->delete('supplier','supplier_id',$supplier_id);
		   $this->App->delete('supplier_rebate','supplier_id',$supplier_id);
		
		
	}
	
	$this->action('common', 'showdiv', 'supplier.php?type=supplier_list&status=1');

	/* 提示信息 */
     
			
			}

    function br2nl($text) {
        $text = preg_replace('/<br\\s*?\/??>/i', chr(13), $text);
        return preg_replace('/ /i', ' ', $text);
    }

    function baominginfo($data = array()) {
        $url_href = ADMIN_URL . 'yuyue.php?type=baominglist';
        $this->check_priv($url_href);
        $this->js(array("kindeditor/kindeditor.js"));
        $id = isset($data['id']) ? $data['id'] : '0';
        if ($id > 0) {
            $sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` WHERE id='$id' LIMIT 1";
            $rt = $this->App->findrow($sql);
            $rt['description'] = $this->br2nl($rt['description']);
            if (!empty($_POST)) {
                $_POST['description'] = nl2br($_POST['description']);
                if ($this->App->update('cx_baoming', $_POST, 'id', $id)) {
                    $this->action('common', 'showdiv', $this->getthisurl());
                }
            }
        } else {
            if (!empty($_POST)) {
                $_POST['addtime'] = mktime();
                $_POST['description'] = nl2br($_POST['description']);
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

        $list = 2;
        $start = $list * $pagestart;
        $end = $list * $pageend;

        $zlist = ceil($end / $list);
      $sql = "SELECT s.supplier_id,s.is_open,u.user_name, s.rank_id,sr.rank_name,s.supplier_name, s.tel, s.system_fee, s.supplier_bond, s.supplier_rebate, s.supplier_remark, s.status  ".
                "FROM `{$this->App->prefix()}supplier` as s ".
				"left join `{$this->App->prefix()}user` as u on s.user_id = u.user_id ".
				"left join `{$this->App->prefix()}supplier_rank` as sr on s.rank_id = sr.rank_id".
			//	"left join `{$this->App->prefix()}supplier_shop_config` as ssc on s.supplier_id = ssc.supplier_id ".
				"   ORDER BY s.supplier_id DESC LIMIT $start,$end";

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
	
	
	
	
	
	function supplier_street_category($data = array()){
	
	
	
	  
	  $status = isset($_GET['status']) ? $_GET['status'] : '';
	  $info = isset($_GET['info']) ? $_GET['info'] : '';
	  $sid = isset($_GET['sid']) ? $_GET['sid'] : 0;
       
		 $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 30;
        $start = ($page - 1) * $list;
		if($status){
		 $sql = "SELECT COUNT(supplier_id) FROM `{$this->App->prefix()}street_category` where status={$status}";
		}else{
			 $sql = "SELECT COUNT(supplier_id) FROM `{$this->App->prefix()}street_category`";
			}
        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);
		
		if($info == "delete"){
			$this->App->delete('street_category', 'str_id', $sid);
			}
		$sql = "SELECT * FROM `{$this->App->prefix()}street_category` ";
			
        $rt = $this->App->find($sql);
		
	    $this->set('cat_info',$rt);
	
	
	  $this->template('supplier_street_category');
	
	}
	
	function supplier_street(){
		
		
		
		
		
	$sql = "select str_id,str_name from `{$this->App->prefix()}street_category` where is_show = 1";
	$info = $this->App->find($sql);
	$ret = array();
	foreach($info as $row){
		$ret[$row['str_id']]['str_id'] = $row['str_id'];
		$ret[$row['str_id']]['str_name'] = $row['str_name'];
	}
	
	  $this->set('str_category', $ret);
	  
	  
	  
	  
	  $supplier_type = isset($_GET['supplier_type']) ? $_GET['supplier_type'] : 0;
	   $is_show = isset($_GET['is_show']) ? $_GET['is_show'] : 0;
	      $supplier_name = isset($_GET['keyword']) ? $_GET['keyword'] : '';

		 $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 30;
        $start = ($page - 1) * $list;
		
		$sql = "SELECT COUNT(supplier_id) FROM `{$this->App->prefix()}supplier_street` where status=1";
		
		 if (isset($_GET) && !empty($_GET)) {
		if(isset($_GET['supplier_type']) && intval($_GET['supplier_type']) > 0){
			 $sql .= " and supplier_type={$supplier_type}";
			}
			
			if(isset($_GET['is_show']) && intval($_GET['is_show']) > -1){
			 $sql .= " and is_show={$is_show}";
			}
			
			if(isset($_GET['supplier_name']) && !empty($_GET['supplier_name'])){
			 $sql .= " and supplier_name='{$supplier_name}'";
			}
		 }
        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);
	
	
	$where = " WHERE 1=1 ";
	 if (isset($_GET) && !empty($_GET)) {
	if(isset($_GET['supplier_type']) && intval($_GET['supplier_type']) > 0){
			 $where .= " and ss.supplier_type={$_GET['supplier_type']}";
			}
			
			if(isset($_GET['is_show']) && intval($_GET['is_show']) > -1){
			$where .= " and ss.is_show={$_GET['is_show']}";
			}
			
			if(isset($_GET['supplier_name']) && !empty($_GET['supplier_name'])){
			 $where .= " and ss.supplier_name='{$_GET['supplier_name']}'";
			}
	 }
	 $sql = "SELECT ss.*,sc.str_name ".
	           " FROM `{$this->App->prefix()}supplier_street` AS ss ".
	           " LEFT JOIN `{$this->App->prefix()}street_category` AS sc ".
	           " ON ss.supplier_type = sc.str_id ".
	           " $where" .
	           " ORDER BY ss.sort_order desc".
	           " LIMIT $start,$list";
			   
			   
			   
        $rt = $this->App->find($sql);

        $this->set('shops_list', $rt);
		
		
		
	
		
		
	
	  $this->template('supplier_street');
		}
	
	
	    //ajax分类激活与是否显示在导航栏
    function ajax_street_category($data = array()) {
        if (empty($data['sid']))
            die("非法操作，ID为空！");
  
       if ($data['type'] == 'is_show') {

            $sdata['is_show'] = $data['active'];
            $this->action('system', 'add_admin_log', '修改店铺分类显示状态:ID为=>' . $data['sid']);
        } else if ($data['type'] == 'is_groom') {

            $sdata['is_groom'] = $data['active'];
            $this->action('system', 'add_admin_log', '修改店铺分类推荐状态:ID为=>' . $data['sid']);
        } else {
            die('没有指派类型！');
        }
        $this->App->update('street_category', $sdata, 'str_id', $data['sid']);
        unset($data, $sdata);
    }
	
	
	   function ajax_street_category_order($data = array()) {
        if (empty($data['gid']))
            die("非法操作，ID为空！");
  
       if ($data['type'] == 'sort_order') {

            $sdata['sort_order'] = $data['val'];
            $this->action('system', 'add_admin_log', '修改店铺分类排序:ID为=>' . $data['sid']);
        } else if ($data['type'] == 'str_style') {

            $sdata['str_style'] = $data['val'];
            $this->action('system', 'add_admin_log', '修改店铺分类样式:ID为=>' . $data['sid']);
        } else {
            die('没有指派类型！');
        }
        $this->App->update('street_category', $sdata, 'str_id', $data['gid']);
        unset($data, $sdata);
    }
	
	
	  function ajax_street($data = array()) {
        if (empty($data['sid']))
            die("非法操作，ID为空！");
  
       if ($data['type'] == 'is_show') {

            $sdata['is_show'] = $data['active'];
            $this->action('system', 'add_admin_log', '修改店铺显示状态:ID为=>' . $data['sid']);
        } else if ($data['type'] == 'is_groom') {

            $sdata['is_groom'] = $data['active'];
            $this->action('system', 'add_admin_log', '修改店铺推荐状态:ID为=>' . $data['sid']);
        } else if ($data['type'] == 'status') {

            $sdata['status'] = $data['active'];
			
			if($data['active'] == 1){
				$sdata['supplier_notice'] = "店铺审核通过";
				}else{
					$sdata['supplier_notice'] = "";
					}
            $this->action('system', 'add_admin_log', '修改店铺审核状态:ID为=>' . $data['sid']);
        }else {
            die('没有指派类型！');
        }
        $this->App->update('supplier_street', $sdata, 'supplier_id', $data['sid']);
		
        unset($data, $sdata);
    }
	
	
	
	function supplier_street_category_add(){
		
		  $this->template('supplier_street_category_add');
	
		}
	
	function ajax_supplier_street_category_add($data = array()){
	
		
		$_POST['str_name'] = isset($data['str_name']) ? trim(addslashes(htmlspecialchars($data['str_name']))) : '';
		$_POST['str_style'] = isset($data['str_style']) ? trim(addslashes(htmlspecialchars($data['str_style']))) : '';
		$_POST['sort_order'] = isset($data['sort_order']) ? intval($data['sort_order']) : 50;
		$_POST['is_groom'] = 1;
		$_POST['is_show'] = isset($data['is_show']) ? intval($data['is_show']) : 0;

      
		
		if($this->App->insert('street_category', $_POST)){
		// $this->action('common', 'showdiv', $this->getthisurl());
		 $result = array('error' => 0, 'message' => '');
		 
	   die($json->encode($result));
	   }
		
	}
	
	 function remove_back($data = array()) {
		  
         if (empty($data['ids']))
            die("非法操作，ID为空！");
        if (!is_array($data['ids']))
            $id_arr = @explode('+', $data['ids']);
        else
            $id_arr = $data['ids'];

        $sql = "update `{$this->App->prefix()}supplier_street` set is_show = 0 WHERE  supplier_id IN(" . @implode(',', $id_arr) . ")";
        $this->App->query($sql);
       
        exit;
    }
	
	 function edit_info($data = array()) {
	
	
	  $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : '0';
	
		
	$sql = "select str_id,str_name from `{$this->App->prefix()}street_category`";
	$info = $this->App->find($sql);
	$ret = array();
	foreach($info as $row){
		$ret[$row['str_id']]['str_id'] = $row['str_id'];
		$ret[$row['str_id']]['str_name'] = $row['str_name'];
	}
	
	  $this->set('str_category', $ret);
	  
	  
	 $sql = "SELECT * ".
	           " FROM `{$this->App->prefix()}supplier_street` ".
	        
	          
	           " where supplier_id = {$supplier_id}";
			   
			   
			   
        $rt = $this->App->findrow($sql);
		
		
		
		  if (!empty($_POST)) {
              
                if ($this->App->update('supplier_street', $_POST, 'supplier_id', $supplier_id)) {
                    $this->action('common', 'showdiv', $this->getthisurl());
                }
		  }
				

        $this->set('info', $rt);
	
	 $this->template('supplier_street_edit');
	 }




function supplier_rebate_list(){
	
	$status_info = array(0=>'冻结',1=>'可结算',2=>'等待商家确认',3=>'等待平台付款',4=>'结算完成');
	
	$this->set('statusinfo',$status_info);
	
	$is_pay_ok = $_GET['is_pay_ok'];
	  /* 过滤信息 */
        $filter['rebate_paytime_start'] = !empty($_GET['rebate_paytime_start']) ? $this->local_strtotime($_GET['rebate_paytime_start']) : 0;
		$filter['rebate_paytime_end'] = !empty($_GET['rebate_paytime_end']) ? $this->local_strtotime($_GET['rebate_paytime_end']." 23:59:59") : 0;
		$filter['status'] = (isset($_GET['status'])) ? intval($_GET['status']) : -1;
        $filter['sort_by'] = empty($_GET['sort_by']) ? ' sr.supplier_id' : trim($_GET['sort_by']);
        $filter['sort_order'] = empty($_GET['sort_order']) ? ' ASC' : trim($_GET['sort_order']);
		$filter['is_pay_ok'] = empty($_GET['is_pay_ok']) ? '0' : intval($_GET['is_pay_ok']);
		$filter['actname'] = empty($_GET['act']) ? "list" : $_GET['act']  ;
       
        $where = 'WHERE 1 ';
        $where .= $filter['rebate_paytime_start'] ? " AND sr.rebate_paytime_start >= '". $filter['rebate_paytime_start']. "' " :  " ";
		$where .= $filter['rebate_paytime_end'] ? " AND sr.rebate_paytime_end <= '". $filter['rebate_paytime_end']. "' " :  " ";
		$where .= $filter['is_pay_ok'] ? " AND sr.is_pay_ok = '". $filter['is_pay_ok']. "' " :  " AND sr.is_pay_ok = '0' ";
		$where .= ($filter['status'] > -1) ? " AND sr.status = '". $filter['status']. "' " :  " ";
		
	
	 $page = isset($_GET['page']) ? $_GET['page'] : '';
        if (empty($page)) {
            $page = 1;
        }
        $list = 30;
        $start = ($page - 1) * $list;
		
	  /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM `{$this->App->prefix()}supplier_rebate` AS sr  " . $where;
      // echo $sql;


        $tt = $this->App->findvar($sql);
        $pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
        $this->set("pagelink", $pagelink);
	
	
	
	  /* 查询 */
	
	 $sql = "SELECT sr.* , s.supplier_name, s.supplier_rebate ".
                "FROM `{$this->App->prefix()}supplier_rebate` AS  sr left join `{$this->App->prefix()}supplier` AS s on sr.supplier_id=s.supplier_id 
                $where
                ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order']. "
                LIMIT  $start,$list";
	
	//echo $sql;
	
	$list = $this->App->find($sql);
	
	$supplier_list=array();
	
    foreach ($list as $row)
	{
		$row['supplier_name'] = $row['supplier_name'];
	    $row['sign'] = $row['supplier_id'].sprintf("%07s", $row['rebate_id']); 
		$row['rebate_paytime_start'] = date('Y-m-d', $row['rebate_paytime_start']);
		$endtime = $row['rebate_paytime_end'];//+$GLOBALS['_CFG']['tuihuan_days_qianshou']*3600*24;
		$row['rebate_paytime_end'] = date('Y-m-d', $endtime);
		//$row['all_money'] = $GLOBALS['db']->getOne("select sum(money_paid + surplus) from ". $GLOBALS['ecs']->table('order_info') ." where rebate_id=". $row['rebate_id'] ." and rebate_ispay=2");
		$row['all_money'] = $this->App->findvar("select sum(" . $this->order_amount_field() . ") from `{$this->App->prefix()}goods_order_info` where rebate_id=". $row['rebate_id'] ." and rebate_ispay=2");
		//$row['all_money'] = "select sum(" . $this->order_amount_field() . ") from `{$this->App->prefix()}goods_order_info` where rebate_id=". $row['rebate_id'] ." and rebate_ispay=2";
		
			//分成佣金
		$order_id = $this->App->find("select order_id from `{$this->App->prefix()}goods_order_info` where rebate_id=". $row['rebate_id'] ." and rebate_ispay=2");
		
		
		
		if(!empty($order_id)){
		foreach ($order_id as $res){
			
			$goods_oid = $this->get_goods_oids($res['order_id']);
			
		$row['fcyj'] =  $this->App->findvar(
		"select sum(takemoney1) from `{$this->App->prefix()}goods` where goods_id in (" . implode(',', $goods_oid) . ")"
		);
		
		$fcyj += $row['fcyj'];
		
		}
		}else{
			$fcyj = 0;
			}
		//分成佣金
		
		
		
		$row['all_money_formated'] = empty($row['all_money']) ? 0:$row['all_money'];
		$row['rebate_money'] = ($row['all_money'] * $row['supplier_rebate'])/100+$fcyj;
		$row['rebate_money_formated'] =  $row['rebate_money'];
		$row['pay_money'] = $row['all_money'] - $row['rebate_money'];
		$row['pay_money_formated'] = $row['pay_money'];
		$row['pay_status'] = $row['is_pay_ok'] ? "已处理，已返佣" : "未处理";
		$row['pay_time'] = empty($row['pay_time'])? "":date('Y-m-d', $row['pay_time']);
		$row['user'] = $_SESSION['adminname'];
		$row['payable_price'] = $row['payable_price'];
		$row['status_name'] = $this->rebateStatus($row['status']);
		$row['caozuo'] = $this->getRebateDo($row['status'],$row['rebate_id'],$filter['actname']);
     $supplier_list[]=$row;   
	}
	
	
	$this->set('supplier_list',$supplier_list);
	
	
	 $this->template('supplier_rebate_list');
	}



//佣金状态
function rebateStatus($status=-1){
	$status_info = array(0=>'冻结',1=>'可结算',2=>'等待商家确认',3=>'等待平台付款',4=>'结算完成');
	if(array_key_exists($status,$status_info)){
		return $status_info[$status];
	}else{
		return $status_info;
	}
}


//根据佣金状态返回操作事件
function getRebateDo($status,$rid,$act){
	$do_info = array(
		'list'=>array(//佣金列表页
			0=>array(
				array('name'=>'查看明细','url'=>'supplier_order.php?type=view&rid='.$rid)
			),
			1=>array(
				array('name'=>'发起明细','url'=>'supplier_rebate.php?type=info&rid='.$rid),
				array('name'=>'查看明细','url'=>'supplier_order.php?type=view&rid='.$rid)
			),
			2=>array(
				array('name'=>'查看结算单','url'=>'supplier_rebate.php?type=info&rid='.$rid),
				array('name'=>'查看明细','url'=>'supplier_order.php?type=view&rid='.$rid)
			),
			3=>array(
				array('name'=>'查看结算单','url'=>'supplier_rebate.php?type=info&rid='.$rid),
				array('name'=>'查看明细','url'=>'supplier_order.php?type=view&rid='.$rid)
			),
			4=>array(
				array('name'=>'查看结算单','url'=>'supplier_rebate.php?type=info&rid='.$rid),
				array('name'=>'查看明细','url'=>'supplier_order.php?type=view&rid='.$rid)
			)
		),
		'view'=>array(//查看佣金明细页
			0=>array(
				array('name'=>'结算佣金','url'=>'')
			),
			1=>array(
				array('name'=>'撤销全部佣金','url'=>'')
			),
			2=>array(
				array('name'=>'查看结算单','url'=>'#'),
				array('name'=>'查看明细','url'=>'#')
			),
			3=>array(
				array('name'=>'查看结算单','url'=>'#'),
				array('name'=>'查看明细','url'=>'#')
			)
		),
		'rebate_view'=>array(//发起结算佣金明细页
			1=>array(
				array('name'=>'发起结算','type'=>'submit','act'=>'update','text'=>'')
			),
			2=>array(
				array('name'=>'取消发起结算','type'=>'submit','act'=>'cancel','text'=>'等待商家确认')
			),
			3=>array(
				array('name'=>'结算完成','type'=>'submit','act'=>'finish','text'=>'')
			),
			4=>array(
				array('name'=>'确认添加','type'=>'submit','act'=>'beizhu','text'=>'')
			)
		)
	);
	return $do_info[$act][$status];
}


/**
 * 生成查询订单总金额的字段
 * @param   string  $alias  order表的别名（包括.例如 o.）
 * @return  string
 */
function order_amount_field($alias = '')
{
    return "   {$alias}order_amount + {$alias}tax + {$alias}shipping_fee";
	// . " + {$alias}insure_fee + {$alias}pay_fee + {$alias}pack_fee" .
//           " + {$alias}card_fee ";
}




function view(){
	
	  /* 取得供货商返佣信息 */
     $id = intval($_REQUEST['rid']);
	 
	  $this->set('rid',   $id);
	 
	 $order_type = (isset($_REQUEST['otype']) && intval($_REQUEST['otype'])>0) ? intval($_REQUEST['otype']) : 0;
	 $this->set('order_type',   $order_type);

	
	$sql = "SELECT r.*, s.supplier_name, s.bank, s.supplier_rebate FROM  `{$this->App->prefix()}supplier_rebate`   AS r left join `{$this->App->prefix()}supplier` AS s on r.supplier_id=s.supplier_id WHERE r.rebate_id = '$id'";
		$rebate = $this->App->findrow($sql);
	   
	   
	   	
			//分成佣金
		$order_id = $this->App->find("select order_id from `{$this->App->prefix()}goods_order_info` where rebate_id=". $rebate['rebate_id'] ." and rebate_ispay=2");
		
		
		
		if(!empty($order_id)){
		foreach ($order_id as $res){
			
			$goods_oid = $this->get_goods_oids($res['order_id']);
			
		$row['fcyj'] =  $this->App->findvar(
		"select sum(takemoney1) from `{$this->App->prefix()}goods` where goods_id in (" . implode(',', $goods_oid) . ")"
		);
		
		$fcyj += $row['fcyj'];
		
		}
		}else{
			$fcyj = 0;
			}
		//分成佣金
		
		
		
	
	 
     if (empty($rebate))
     {
        die('该返佣记录不存在！');
     }
	 else
	{
		
		$rebate['sign'] = $rebate['supplier_id'].sprintf("%07s", $rebate['rebate_id']);
		 
		$nowtime = time();
		$rebate['rebate_paytime_start'] = date('Y-m-d', $rebate['rebate_paytime_start']);
		$paytime_end = $rebate['rebate_paytime_end'];
		$rebate['rebate_paytime_end'] = date('Y-m-d', $paytime_end);
		//设置7，则自下单起第7天系统会自动确认收货
		$rebate['isdo'] = (($paytime_end+7*3600*24)>=$nowtime) ? 0 : 1;
		
		$rebate['chadata'] = $this->datecha($paytime_end+7*3600*24);
		
		
		//$rebate['caozuo'] = $this->getRebateDo($rebate['status'],$rebate['rebate_id'],trim($_REQUEST['act']));
		
		
		if($rebate['status']>0){
			//非冻结状态
			$money = $this->getRebateOrderMoney($id);
			
			
			$money_info = array();
			foreach($money as $key=>$val){
			
				$money_info[$key]['allmoney'] = $val;
				$money_info[$key]['supplier_rebate'] = $rebate['supplier_rebate'];
				$money_info[$key]['rebatemoney'] = $val*$rebate['supplier_rebate']/100;
				$money_info[$key]['fcyj'] = $fcyj;
			}
			$this->set('money_info',   $money_info);
		}
		
		

		//if($order_type==0){
			//$order_list = $this->getOkOrder($id);
//		}else{
//			$back_money = $this->getBackOrderMoney();
//			$this->set('back_money',   $back_money);
//			$order_list = $this->getBackHuanOrder();
//		}





 //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }

            //条件
            $comd = array();
			
		
			
		$filter['rid'] =  (isset($_REQUEST['rid']) && intval($_REQUEST['rid'])>0) ? intval($_REQUEST['rid']) : 0;
		$filter['add_time_start'] = !empty($_REQUEST['add_time_start']) ? $this->local_strtotime($_REQUEST['add_time_start']) : 0;
		$filter['add_time_end'] = !empty($_REQUEST['add_time_end']) ? $this->local_strtotime($_REQUEST['add_time_end']." 23:59:59") : 0;
		$filter['order_sn'] = (isset($_REQUEST['order_sn'])) ? trim($_REQUEST['order_sn']) : '';



		
		
		 if($filter['rid'] > 0){
                    $comd[] = " rebate_id = '". $filter['rid']. "'";
		 }else{
			 $comd[] = " rebate_id = '". $id. "'";
			 }
			
			  if(!empty($filter['add_time_start']))
                    $comd[] = " add_time >= '". $filter['add_time_start']. "'";
					
					 if(!empty($filter['add_time_end']))
                    $comd[] = " add_time <= '". $filter['add_time_end']. "'";
					
					 if(!empty($filter['order_sn']))
                    $comd[] = " order_sn = '". $filter['order_sn']. "'";
		
            $w = ""; 
            if(!empty($comd)){
                $w = ' WHERE '.@implode(' AND ',$comd);
            }
            $list = 12;
            $start = ($page-1)*$list;
            $sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` $w";
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
        
            $sql = "select order_id, order_sn, add_time, order_status, shipping_status, order_amount,".
				"pay_status, consignee, address, email, tel,  shipping_time, rebate_ispay, " .
				"(" . $this->order_amount_field() . ") AS total_fee  FROM `{$this->App->prefix()}goods_order_info` $w GROUP BY order_id $orderby LIMIT $start,$list";
			
		
			
			//echo $sql;
            $rt = $this->App->find($sql);
            $orderlist = array();
            if(!empty($rt)){
                foreach($rt as $row){

					if(empty($row['order_id'])||empty($row['order_sn'])) continue;
					
						$is_order = $is_shipping = $is_pay = 0;
						
						$row['datas'] = 0;
						
						//订单状态
        if($row['order_status'] == 2){
        	$is_order = 1;
        }
        //配送状态
        if($row['shipping_status'] == 5){
        	$is_shipping = 1;
        }
		if($row['shipping_status'] == 5){
			$row['is_rebeat'] = 1;
		}
        //支付状态
        if($row['pay_status'] == 1){
        	$is_pay = 1;
        }
						
						 if($is_order && $is_shipping && $is_pay){
        	$cha = $this->datecha($row['shipping_time']);
        	$row['datas'] = 7 - $cha ;
			if($row['datas'] <= 0){
				$row['is_rebeat'] = 1;
			}
        }
		if($row['rebate_ispay'] == 2){
			$row['is_rebeat'] = 0;
		}
		
		
		$goods_oid = $this->get_goods_oids($row['order_id']);
		
		//print_r($goods_oid);
		
		//计算分成佣金
		$sql = "select sum(takemoney1)  FROM `{$this->App->prefix()}goods` where goods_id in (" . implode(',', $goods_oid) . ")";
				
				 $row['fcyj'] = $this->App->findvar($sql);
				 
				
                    $orderlist[] = array(
					    'supplier_id' => $row['supplier_id'],
                        'order_id'=>$row['order_id'],
                        'order_sn'=>$row['order_sn'],
                        'user_id'=>$row['user_id'],
                        'consignee'=>$row['consignee'],
						'sn_id'=>$row['sn_id'],
						'nickname'=>$this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1"),
						'shoppingname'=>$shoppingname,
						'shipping_code'=>$shipping_code,
						'shipping_id'=>$row['shipping_id'],
						'shipping_id_true'=>$row['shipping_id_true'],
                        'tprice'=>$row['tprice'],
                        'order_status'=>$row['order_status'],
                        'shipping_status'=>$row['shipping_status'],
                        'pay_status'=>$row['pay_status'],
						'is_prints'=>$row['is_prints'],
                        'add_time'=>(!empty($row['add_time'])? date('Y-m-d H:i:s',$row['add_time']) : '无知'),
                        'status'=>$this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']),
						//zzzzzzzzzzz
						'formated_order_amount' => $row['order_amount'],
                    //    'formated_money_paid' => $row['money_paid'],
		                'formated_rebate_fee' => $row['total_fee']*$rebate['supplier_rebate']/100+$row['fcyj'],
                        'formated_total_fee' => $row['total_fee'],
                        'short_order_time' => date('Y-m-d H:i', $row['add_time']),
                        'is_rebeat' => $row['is_rebeat']
						
		               //zzzzzzzzzzzzzzz
		
                        );
						
						
                }
            }
			
			
            $this->set('order_list',$orderlist);
			
	 }
	 $this->set('rebate', $rebate);

	 $is_pay_ok = $rebate['is_pay_ok'];

	 $this->template('supplier_rebate_info');

  
	 
	
	
	}



//获取相关佣金所有订单金额
function getRebateOrderMoney($rid){
//	
	$back_and = '';
	if(($back_order_id = $this->getBackOrderByRebate($rid)) != false){
		//获取退货订单中相关订单
		$back_and = "and order_id not in(".implode(',',$back_order_id).")";
	}
	$pay_id = $this->getPayHoudaofukuan();//获取货到付款的id
	$sql = "select (" . $this->order_amount_field() . ") AS total_fee,pay_id from  `{$this->App->prefix()}goods_order_info` where rebate_id=".$rid."  and rebate_ispay=2";
	$query = $this->App->find($sql);
	$online = $onout = 0;
	foreach($query as $row){
		if($row['pay_id'] == $pay_id){
			//货到付款
			$onout += $row['total_fee'];
		}else{
			//在线支付
			$online += $row['total_fee'];
		}
	}
	return array('online'=>$online,'onout'=>$onout);
}



//获取佣金中相关的退换货的订单
function getBackOrderByRebate($rid){
	
	$sql = "select order_id,order_status from `{$this->App->prefix()}goods_order_info`  where rebate_id=".$rid;
	$query = $this->App->findrow($sql);
	$ret = array();
	foreach($query as $row){
		if($row['order_status']!=3){
			//排除维修的订单
			$ret[] = $row['order_id'];
		}
	}
	return (empty($ret)) ? false : $ret;
}

//获取本系统中货到付款的支付id
function getPayHoudaofukuan(){
	//return $this->App->findvar('select pay_id from `{$this->App->prefix()}payment` where is_cod=1 and is_pickup=0');
	return 1;
}
//计算时间
function datecha($times){
	$i = 0;
	$tj = true;
	$nowtime = time();
	while ($tj){
		if($times <= ($nowtime+$i*3600*24)){
			$tj=false;
		}else{
			$i++;
		}
	}
	return $i;
}



//佣金中的妥投订单
function getOkOrder(){
	global $ecs,$db,$rebate;
	$result = get_filter();
    if ($result === false)
    {
		$filter['rid'] = $rid = (isset($_REQUEST['rid']) && intval($_REQUEST['rid'])>0) ? intval($_REQUEST['rid']) : 0;
		$filter['add_time_start'] = !empty($_REQUEST['add_time_start']) ? $this->local_strtotime($_REQUEST['add_time_start']) : 0;
		$filter['add_time_end'] = !empty($_REQUEST['add_time_end']) ? $this->local_strtotime($_REQUEST['add_time_end']." 23:59:59") : 0;
		$filter['order_sn'] = (isset($_REQUEST['order_sn'])) ? trim($_REQUEST['order_sn']) : '';
		//$and = ' rebate_id='.$rid.' and shipping_status in ('.SS_SHIPPED.','.SS_RECEIVED.')';
		$and = ' rebate_id='.$rid;
		//$hpay_id = getPayHoudaofukuan();
		//if($hpay_id){
			//$and .= ' and pay_id !='.$hpay_id.' ';
		//}

		$back_order_id = getBackOrderByRebate($rid);
		if(!empty($back_order_id)){
			$notin = " and order_id not in(".implode(',',$back_order_id).")";
		}else{
			$notin = '';
		}
		$and .= $notin;

		$and .= $filter['add_time_start'] ? " AND add_time >= '". $filter['add_time_start']. "' " :  " ";
		$and .= $filter['add_time_end'] ? " AND add_time <= '". $filter['add_time_end']. "' " :  " ";
		$and .= $filter['order_sn'] ? " AND order_sn = '". $filter['order_sn']. "' " :  " ";

		/* 分页大小 */
		$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else
		{
			$filter['page_size'] = 15;
		}

		//总数
		$sql = "select count(order_id) from ".$ecs->table('order_info')." where ".$and;
		$filter['record_count']   = $GLOBALS['db']->getOne($sql);
		$filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

		//记录
		$sql = "select order_id, order_sn, add_time, order_status, shipping_status, order_amount, money_paid,".
				"pay_status, consignee, address, email, tel, extension_code, extension_id, shipping_time, rebate_ispay, " .
				"(" . order_amount_field() . ") AS total_fee " .
			"from ".$ecs->table('order_info')." where ".$and." LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ",$filter[page_size]";
		//echo $sql;
		set_filter($filter, $sql);
	}
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
	$query = $db->query($sql);
	$ret = array();
	while($row = $db->fetchRow($query)){

		$is_order = $is_shipping = $is_pay = 0;
		$row['formated_order_amount'] = price_format($row['order_amount']);
        $row['formated_money_paid'] = price_format($row['money_paid']);
		$row['formated_rebate_fee'] = 0-price_format($row['total_fee']*$rebate['supplier_rebate']/100);
        $row['formated_total_fee'] = price_format($row['total_fee']);
        $row['short_order_time'] = local_date('Y-m-d H:i', $row['add_time']);
        $row['is_rebeat'] = $row['datas'] = 0;
		//订单状态
        if($row['order_status'] == OS_CONFIRMED || $row['order_status'] == OS_SPLITED){
        	$is_order = 1;
        }
        //配送状态
        if($row['shipping_status'] == SS_SHIPPED){
        	$is_shipping = 1;
        }
		if($row['shipping_status'] == SS_RECEIVED){
			$row['is_rebeat'] = 1;
		}
        //支付状态
        if($row['pay_status'] == PS_PAYED){
        	$is_pay = 1;
        }
        if($is_order && $is_shipping && $is_pay){
        	$cha = datecha($row['shipping_time']);
        	$row['datas'] = $GLOBALS['_CFG']['okgoods_time'] - $cha ;
			if($row['datas'] <= 0){
				$row['is_rebeat'] = 1;
			}
        }
		if($row['rebate_ispay'] == 2){
			$row['is_rebeat'] = 0;
		}

		$ret[$row['order_id']] = $row;

	}
	//echo "<pre>";
	//print_r($ret);
	$arr = array('orders' => $ret, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

//佣金中退货订单
function getBackHuanOrder(){
	global $ecs,$db,$rebate;
	$result = get_filter();
    if ($result === false)
    {
		$filter['rid'] = $rid = (isset($_REQUEST['rid']) && intval($_REQUEST['rid'])>0) ? intval($_REQUEST['rid']) : 0;
		$filter['add_time_start'] = !empty($_REQUEST['add_time_start']) ? $this->local_strtotime($_REQUEST['add_time_start']) : 0;
		$filter['add_time_end'] = !empty($_REQUEST['add_time_end']) ? $this->local_strtotime($_REQUEST['add_time_end']." 23:59:59") : 0;
		$filter['order_sn'] = (isset($_REQUEST['order_sn'])) ? trim($_REQUEST['order_sn']) : '';

		//$and = ' rebate_id='.$rid.' and shipping_status in ('.SS_SHIPPED.','.SS_RECEIVED.')';
		$and = ' oi.rebate_id='.$rid.' and bo.back_type!=3 and bo.status_back<5 and oi.order_id=bo.order_id ';

		$and .= $filter['add_time_start'] ? " AND oi.add_time >= '". $filter['add_time_start']. "' " :  " ";
		$and .= $filter['add_time_end'] ? " AND oi.add_time <= '". $filter['add_time_end']. "' " :  " ";
		$and .= $filter['order_sn'] ? " AND oi.order_sn = '". $filter['order_sn']. "' " :  " ";

		/* 分页大小 */
		$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else
		{
			$filter['page_size'] = 15;
		}

		//总数
		$sql = "select count(oi.order_id) " .
			"from ".$ecs->table('order_info')." as oi,".$ecs->table('back_order')." as bo where ".$and;
		$filter['record_count']   = $GLOBALS['db']->getOne($sql);
		$filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

		//记录
		$sql = "select oi.order_id, oi.order_sn, oi.add_time, oi.order_status, oi.shipping_status, oi.order_amount, oi.money_paid,".
				"oi.pay_status, oi.consignee, oi.address, oi.email, oi.tel, oi.extension_code, oi.extension_id, oi.shipping_time, bo.add_time as back_add_time,bo.status_back,bo.status_refund, " .
				"(" . order_amount_field('oi.') . ") AS total_fee " .
			"from ".$ecs->table('order_info')." as oi,".$ecs->table('back_order')." as bo where ".$and." LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ",$filter[page_size]";
		//echo $sql;
		set_filter($filter, $sql);
	}
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
	$query = $db->query($sql);
	$ret = array();
	while($row = $db->fetchRow($query)){

		$is_order = $is_shipping = $is_pay = 0;
		$row['formated_order_amount'] = price_format($row['order_amount']);
        $row['formated_money_paid'] = price_format($row['money_paid']);
		$row['formated_rebate_fee'] = 0-price_format($row['total_fee']*$rebate['supplier_rebate']/100);
        $row['formated_total_fee'] = price_format($row['total_fee']);
        $row['short_order_time'] = local_date('Y-m-d H:i', $row['add_time']);
		$row['short_back_add_time'] = local_date('Y-m-d H:i', $row['back_add_time']);
        $row['is_rebeat'] = $row['datas'] = 0;
		$ret[$row['order_id']] = $row;

	}
	//echo "<pre>";
	//print_r($ret);
	$arr = array('orders' => $ret, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

//退货订单货款记录
function getBackOrderMoney(){
	global $ecs,$db;

	$rid = (isset($_REQUEST['rid']) && intval($_REQUEST['rid'])>0) ? intval($_REQUEST['rid']) : 0;

	$hpay_id = getPayHoudaofukuan();//货到付款支付方式id;

	$sql = "select bo.status_refund,(" . order_amount_field('oi.') . ") AS total_fee,oi.pay_id " .
			"from ".$ecs->table('order_info')." as oi,".$ecs->table('back_order')." as bo where oi.rebate_id=".$rid." and bo.back_type!=3 and bo.status_back < 5 and oi.order_id=bo.order_id";
	$query = $db->query($sql);

	$ret = array('all'=>0.00,'finish'=>0.00,'nofinish'=>0.00,'online'=>0.00,'onout'=>0.00);
	while($row = $db->fetchRow($query)){
		$ret['all'] += $row['total_fee'];
		if($row['status_refund'] > 0){
			//完成退款
			$ret['finish'] += $row['total_fee'];
		}else{
			//申请中
			$ret['nofinish'] += $row['total_fee'];
		}
		if($row['pay_id'] != $hpay_id){
			//在线支付
			$ret['online'] += $row['total_fee'];
		}else{
			//货到付款
			$ret['onout'] += $row['total_fee'];
		}
	}
	return $ret;
}


/**
 *  将一个用户自定义时区的日期转为GMT时间戳
 *
 * @access  public
 * @param   string      $str
 *
 * @return  integer
 */
function local_strtotime($str)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
    **/
    $time = strtotime($str) - $timezone * 3600;

    return $time;

}

  //订单的状态
        function get_status($oid=0,$pid=0,$sid=0){ //分别为：订单 支付 发货状态
            $str = '';
            switch($oid){
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

           switch($pid){
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

            switch($sid){
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




function ajax_supplier_jiesuan($ids=0,$rid=0){
	@set_time_limit(600); //最大运行时间
			
            if(empty($ids)){ echo "没有找到需要结算的订单！"; exit;}
            if(empty($rid)){ echo "没有找到结算项目！"; exit;}
            $id_arr = @explode('+',$ids);
	
	
	
	//获取所有可以结算的订单
	$sql = "update `{$this->App->prefix()}goods_order_info` set rebate_ispay=2 where rebate_id=".$rid." and order_sn IN(".implode(',',$id_arr).")";
	// echo $sql;
	if($this->App->query($sql)){
		//入驻商资金添加日志
	//	writelog($rebid);
		//结算订单佣金日志记录
		
			$rebate_order['rebateid'] = $rid;
			$rebate_order['username'] = '平台方:'.$_SESSION['adminname'];
			$rebate_order['type'] = 1;
			$rebate_order['typedec'] = '结算佣金';
			$rebate_order['contents'] = '订单id'.implode(',',$id_arr)."佣金结算";
			$rebate_order['addtime'] = time();
		
		
		//if($id > 0){
//					$this->App->update('user_tuijian_fx',$dd,'id',$id);
//				}else{
//					$this->App->insert('user_tuijian_fx',$dd);
//				}
				
				$this->App->insert('supplier_rebate_log',$rebate_order);
		
		//if(changeStatus($rebid)){
			//记录用户资金日志
			
			//修改佣金状态
			$status['status'] = 1;
			$this->App->update('supplier_rebate',$status,'rebate_id',$rid);
			//修改佣金信息状态记录
			
				$rebate_list['rebateid'] = $rid;
				$rebate_list['username'] = '平台方:'.$_SESSION['adminname'];
				$rebate_list['type'] = 2;
				$rebate_list['typedec'] = '结算佣金';
				$rebate_list['contents'] = '佣金状态由冻结变可结算';
				$rebate_list['addtime'] = time();
			
			$this->App->insert('supplier_rebate_log', $rebate_list);
		//}
		
	}
	
	
	
	
	else{
							echo "无法进行该操作！";exit;
					}

	}
	
	
	function info(){
		
		  $id = intval($_REQUEST['rid']);
	 
	  $this->set('rid',   $id);
	  
	  $sql = "SELECT r.*, s.supplier_name, s.bank, s.supplier_rebate FROM  `{$this->App->prefix()}supplier_rebate`   AS r left join `{$this->App->prefix()}supplier` AS s on r.supplier_id=s.supplier_id WHERE r.rebate_id = '$id'";
		$rebate = $this->App->findrow($sql);
		
		//分成佣金
		$order_id = $this->App->find("select order_id from `{$this->App->prefix()}goods_order_info` where rebate_id=". $rebate['rebate_id'] ." and rebate_ispay=2");
		
		
		
		if(!empty($order_id)){
		foreach ($order_id as $res){
			
			$goods_oid = $this->get_goods_oids($res['order_id']);
			
		$row['fcyj'] =  $this->App->findvar(
		"select sum(takemoney1) from `{$this->App->prefix()}goods` where goods_id in (" . implode(',', $goods_oid) . ")"
		);
		
		$fcyj += $row['fcyj'];
		
		}
		}else{
			$fcyj = 0;
			}
		//分成佣金
		echo $fcyj;
		
		
	    if (empty($rebate))
    {
        echo'该返佣记录不存在！'; exit;
    }
	else
	{
		$rebate['sign'] = $rebate['supplier_id'].sprintf("%07s", $rebate['rebate_id']);
		
		$rebate['rebate_paytime_start'] = date('Y-m-d', $rebate['rebate_paytime_start']);
		$paytime_end = $rebate['rebate_paytime_end'];
		$rebate['rebate_paytime_end'] = date('Y-m-d', $paytime_end);
		
		
		
			
		//结算信息
		$money = $this->getRebateOrderMoney($id);
		$money_info = array();
		foreach($money as $key=>$val){
			$money_info[$key]['allmoney'] = $val;
				$money_info[$key]['supplier_rebate'] = $rebate['supplier_rebate'];
				$money_info[$key]['rebatemoney'] = $val*$rebate['supplier_rebate']/100;
				$money_info[$key]['fcyj'] = $fcyj;
		}
		$this->set('money_info',   $money_info);

		//佣金统计
		$allmoney = array_sum($money);
		$tongji['allmoney'] = $allmoney;
		$tongji['allrebate'] = $allmoney*$rebate['supplier_rebate']/100+$fcyj;
		$tongji['chamoney'] = $allmoney*(1-$rebate['supplier_rebate']/100)-$fcyj;

		$tongji['rebate_all'] = ($rebate['rebate_all'] > 0) ? $rebate['rebate_all'] : $tongji['allmoney'];
		$tongji['rebate_money'] = ($rebate['rebate_money'] > 0) ? $rebate['rebate_money'] : $tongji['allrebate'];
		$tongji['payable_price'] = ($rebate['payable_price'] > 0) ? $rebate['payable_price'] : '';

		$rebate['caozuo'] = $this->getRebateDo($rebate['status'],$rebate['rebate_id'],'rebate_view');
		$this->set('allmoney',   $tongji);

		//商家店铺信息
		$sql = "select s.*, r.rank_name, u.user_name from `{$this->App->prefix()}supplier` AS s left join `{$this->App->prefix()}supplier_rank` AS r on s.rank_id=r.rank_id left join `{$this->App->prefix()}user` AS u on s.user_id=u.user_id  where s.supplier_id='$rebate[supplier_id]' ";
					
		$supplier =$this->App->findrow($sql);
		if (!empty($supplier))
		{
			$supplier['province'] = $this->App->findvar("select region_name from `{$this->App->prefix()}region` where region_id='$supplier[province]' ");
			$supplier['city'] = $this->App->findvar("select region_name from `{$this->App->prefix()}region` where region_id='$supplier[city]' ");
			$supplier['district'] = $this->App->findvar("select region_name from `{$this->App->prefix()}region` where region_id='$supplier[district]' ");
		}

		//佣金操作日志
		$sql = "select * from `{$this->App->prefix()}supplier_rebate_log` where rebateid=".$rebate['rebate_id']." and type=2 order by logid desc";
		$logs = array();
		$query = $this->App->find($sql);
		foreach($query as $row){
			$row['addtime_dec'] = date('Y-m-d H:i', $row['addtime']);
			$logs[$row['logid']] = $row;
		}
		

		$this->set('logs', $logs);
	}
       // echo $sql;
//           print_r($logs);
	$this->set('rebate', $rebate);
	$this->set('supplier', $supplier);

	
	 $is_pay_ok = $rebate['is_pay_ok'];
	
   
   
   
   if(isset($_POST['act']) && $_POST['act'] != ""){
	   //发起结算
	   if($_POST['act'] == 'update'){

		   $rebate_all = (isset($_POST['rebate_all']) && floatval($_POST['rebate_all']) > 0) ? floatval($_POST['rebate_all']) : 0;
	$rebate_money = (isset($_POST['rebate_money']) && floatval($_POST['rebate_money']) > 0) ? floatval($_POST['rebate_money']) : 0;
	$remark = (isset($_POST['remark'])) ? addslashes($_POST['remark']) : '';

	if($rebate_all<=0){
		echo '请调整授权调整货款！'; exit;
	}
	if($rebate_money<=0){
		echo '请调整授权调整佣金！'; exit;
	}

   /* 提交值 */
   $rebate_id =  intval($_POST['id']);
   $payable_price = $rebate_all - $rebate_money;
   
   
   $sql = "SELECT r.*, s.supplier_name, s.bank, s.supplier_rebate FROM  `{$this->App->prefix()}supplier_rebate`   AS r left join `{$this->App->prefix()}supplier` AS s on r.supplier_id=s.supplier_id WHERE r.rebate_id = '$rebate_id'";
		$rebate = $this->App->findrow($sql);
		
		
		
	    if (empty($rebate))
    {
        echo'该返佣记录不存在！'; exit;
    }
	
  
		$rebates['remark']   = $remark;
		$rebates['rebate_all']   = $rebate_all;
		$rebates['rebate_money']   = $rebate_money;
	    $rebates['payable_price'] = $payable_price;
		$rebates['status']	= 2;
 

	/* 保存返佣信息 */
	$this->App->update('supplier_rebate',$rebates,'rebate_id',$rebate_id);

	//修改佣金信息状态记录
		
				$rebate_list['rebateid'] = $rebate_id;
				$rebate_list['username'] = '平台方:'.$_SESSION['adminname'];
				$rebate_list['type'] = 2;
				$rebate_list['typedec'] = '发起结算';
				$rebate_list['contents'] = '佣金状态由可结算变等待审核';
				$rebate_list['addtime'] = time();
	
		$this->App->insert('supplier_rebate_log', $rebate_list);
	
	     $this->jump('supplier.php?type=supplier_rebate_list&is_pay_ok=0',0,'发起结算成功！'); exit;
		   }
		   //发起结算
		   
		   
		   //取消结算
		   else if($_POST['act'] == "cancel"){
			   
			   
			   	$rebate_id =  intval($_POST['id']);
   $sql = "SELECT r.*, s.supplier_name, s.bank, s.supplier_rebate FROM  `{$this->App->prefix()}supplier_rebate`   AS r left join `{$this->App->prefix()}supplier` AS s on r.supplier_id=s.supplier_id WHERE r.rebate_id = '$rebate_id'";
		$rebate = $this->App->findrow($sql);
		
		
		
	    if (empty($rebate))
    {
        echo'该返佣记录不存在！'; exit;
    }
	
	    $rebates['remark']   = '';
		$rebates['rebate_all']   = 0.00;
		$rebates['rebate_money']   = 0.00;
	    $rebates['payable_price'] = 0.00;
		$rebates['status']	= 1;
		
	

/* 保存返佣信息 */
	$this->App->update('supplier_rebate',$rebates,'rebate_id',$rebate_id);
	
	//修改佣金信息状态记录
	
	
				$rebate_list['rebateid'] = $rebate_id;
				$rebate_list['username'] = '平台方:'.$_SESSION['adminname'];
				$rebate_list['type'] = 2;
				$rebate_list['typedec'] = '取消发起结算';
				$rebate_list['contents'] = '佣金状态由等待审核变可结算';
				$rebate_list['addtime'] = time();
				
		
		$this->App->insert('supplier_rebate_log', $rebate_list);
		
		 $this->jump('supplier.php?type=supplier_rebate_list&is_pay_ok=0',0,'取消结算成功！'); exit;
			   
			   }
			   
			   //取消结算
			   //结算完成
			   
			   
			   
			     else if($_POST['act'] == "finish"){
			   
			   
			   	$rebate_id =  intval($_POST['id']);
   $sql = "SELECT r.*, s.supplier_name, s.bank, s.supplier_rebate FROM  `{$this->App->prefix()}supplier_rebate`   AS r left join `{$this->App->prefix()}supplier` AS s on r.supplier_id=s.supplier_id WHERE r.rebate_id = '$rebate_id'";
		$rebate = $this->App->findrow($sql);
		
		
		
	    if (empty($rebate))
    {
      
		$this->jump('supplier.php?type=supplier_rebate_list&is_pay_ok=0',0,'该返佣记录不存在！');
    }
	
	if(empty($_POST['rebate_img'])){
		
		 $this->jump('supplier.php?type=supplier_rebate_list&is_pay_ok=0',0,'汇票凭证必须上传！');
	}
	
	  $rebates['is_pay_ok'] = 1;
	    $rebates['pay_time']  =  time();

	  $rebates['rebate_img'] = $_POST['rebate_img'];
		$rebates['status']	= 4;
		
	

/* 保存返佣信息 */
	$this->App->update('supplier_rebate',$rebates,'rebate_id',$rebate_id);
	
	
	
		$loginfo['rebateid']=$rebate_id;
		$loginfo['addtime']=time();
		$loginfo['reason']='佣金'.$rebate['supplier_id'].sprintf("%07s", $rebate['rebate_id']).'转帐：'.$rebate['payable_price'];
		$loginfo['supplier_money']=$rebate['payable_price'];
		$loginfo['doman']='平台方:'.$_SESSION['adminname'];
		$loginfo['supplier_id']=$rebate['supplier_id'];
	
	$this->App->insert('supplier_money_log', $loginfo);
	
	
	$this->App->query("update `{$this->App->prefix()}supplier` set supplier_money = supplier_money + ".$rebate['payable_price']." where supplier_id=".$rebate['supplier_id']);

	
	
	//修改佣金信息状态记录
	
	
				$rebate_list['rebateid'] = $rebate_id;
				$rebate_list['username'] = '平台方:'.$_SESSION['adminname'];
				$rebate_list['type'] = 2;
				$rebate_list['typedec'] = '平台方付款';
				$rebate_list['contents'] = '佣金状态由等待付款变结算完成';
				$rebate_list['addtime'] = time();
				

		$this->App->insert('supplier_rebate_log', $rebate_list);
		
		 $this->jump('supplier.php?type=supplier_rebate_list&is_pay_ok=1',0,'结算成功！'); exit;
			   
			   }

			   //结算完成
			   
			     elseif ($_POST['act']=='beizhu')
{

	$rebate_id =  intval($_POST['id']);
    $remark = (isset($_POST['remark'])) ? addslashes($_POST['remark']) : '';
	    $rebates['remark'] = $remark;

	/* 保存返佣信息 */
	$this->App->update('supplier_rebate', $rebates,'rebate_id',$rebate_id);
	 $this->jump('supplier.php?type=supplier_rebate_list&is_pay_ok=1',0,'备注成功！'); exit;
	   
	   }
			   
	   
	   }

	
    $this->template('supplier_rebate_view');
	  
	  
		}

//分成佣金
function get_goods_oids($oid){
	
	 $sql = 'SELECT goods_id FROM `' . $this->App->prefix() . "goods_order` where order_id = $oid";
	  
	  $res = $this->App->find($sql);
	   $three_arr = array();
	   
	   foreach($res as  $row){
		   
		   $three_arr[] = $row['goods_id'];
		   
		   }
		   
		   return $three_arr;
	   
	}
	

}

?>