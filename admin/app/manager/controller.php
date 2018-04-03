<?php
class ManagerController extends Controller{

	 //构造函数，自动新建对象
 	function  __construct(){
		/*
		*构造函数
		*/
	 $this->css(array('content.css','calendar.css'));
		  $this->js(array('calendar.js','calendar-setup.js','calendar-zh.js'));
	}
	
	//管理员列表
    function managerlist(){
		$sql = "SELECT tb1.*, tb2.groupname FROM `{$this->App->prefix()}admin` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}admin_group` AS tb2 ";
		$sql .= " ON tb1.groupid = tb2.gid where tb1.parentid = 0";

		$this->set('adminlist',$this->App->find($sql));
		
		$this->template('managerlist');
    }
	
	
	  function managerdaililist(){
		  $adminid = $this->getuserinfo('adminid');
		$sql = "SELECT tb1.*, tb2.groupname FROM `{$this->App->prefix()}admin` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}admin_group` AS tb2 ";
		$sql .= " ON tb1.groupid = tb2.gid where tb1.parentid > 0  and tb1.parentid = ".$adminid;

		$this->set('adminlist',$this->App->find($sql));
		
		$this->template('managerdaililist');
    }
	
	
	//添加管理员 / 管理员资料修改
	function manageredit($type='add',$id=0){
		$sql = "SELECT groupname,gid FROM `{$this->App->prefix()}admin_group` WHERE active ='1'";
		$groupar = $this->App->find($sql);
		$this->set('groupar',$groupar);
		unset($groupar);
		$rts = array();
		if($type == 'edit'){
			if(empty($id)){
			   $id = $this->getuserinfo('adminid');
			}
			if(empty($id) || !(Import::basic()->int_preg($id))){
				$this->jump('manager.php?type=list');
				exit;
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}admin` WHERE adminid='{$id}' LIMIT 1";
			$rts = $this->App->findrow($sql);
		}
			$this->set('type',$type);
			$this->set('rts',$rts);
			//$this->set('thisurl',$this->getthisurl());
			$this->template('manager_info');
	}
	
	
	
	//添加管理员 / 管理员资料修改
	function managerdailiedit($type='add',$id=0){
		
		$groupid = $this->getuserinfo('groupid');
		if($groupid == 1){
			$sql = "SELECT groupname,gid FROM `{$this->App->prefix()}admin_group` WHERE gid = 16 and active ='1'";
			}else if($groupid == 16){
				$sql = "SELECT groupname,gid FROM `{$this->App->prefix()}admin_group` WHERE gid = 17 and active ='1'";
				}else{
					$sql = "SELECT groupname,gid FROM `{$this->App->prefix()}admin_group` WHERE gid = 18 and active ='1'";
					}
		 
		
		$groupar = $this->App->find($sql);
		$this->set('groupar',$groupar);
		unset($groupar);
		$rts = array();
		if($type == 'dailiedit'){
			if(empty($id)){
			   $id = $this->getuserinfo('adminid');
			}
			if(empty($id) || !(Import::basic()->int_preg($id))){
				$this->jump('manager.php?type=daililist');
				exit;
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}admin` WHERE adminid='{$id}' LIMIT 1";
			$rts = $this->App->findrow($sql);
		}
			$this->set('type',$type);
			$this->set('rts',$rts);
			//$this->set('thisurl',$this->getthisurl());
			$this->template('manager_daili_info');
	}
	
	
	
		  //代理基本设置
    function managerdailiset() {
		
		$adminid = $this->Session->read('adminid');
		$groupid = $this->Session->read('groupid');
		
		$daili = array(
		'dl_adminid' => $adminid,
		'dl_groupid' => $groupid,
		'dl_feilv' => $_POST['dl_feilv'],
		'dl_sxf' => $_POST['dl_sxf'],
		);
		

			$parentid =  $this->App->findvar("SELECT parentid FROM `{$this->App->prefix()}admin` where adminid = $adminid LIMIT 1");
			$dq_set =  $this->App->findrow("SELECT * FROM `{$this->App->prefix()}daili_set` where dl_adminid = $parentid  LIMIT 1");

		
        $sql = "SELECT * FROM `{$this->App->prefix()}daili_set` where dl_adminid = ".$adminid." and dl_groupid =".$groupid." LIMIT 1";
        $rt = $this->App->findrow($sql);
        if (!empty($_POST)) {
            if (empty($rt)) {
                $this->App->insert('daili_set', $daili);
                $this->action('common', 'showdiv', $this->getthisurl());
                $rt = $_POST;
            } else {
                $this->App->update('daili_set', $daili, 'id', $rt['id']);
                $this->action('common', 'showdiv', $this->getthisurl());
                $rt = $_POST;
            }
        }
		$this->set('dq_set', $dq_set);
		$this->set('groupid', $groupid);
        $this->set('rt', $rt);
        $this->template('managerdailiset');
    }
	
	
	// function return_instead_daili_uid($uid = 0) {
//        if (!($uid > 0)) {
//            return 0;
//        }
//		$p = $this->App->findvar("SELECT dl_3_id FROM `{$this->App->prefix() }daili_invitecode` WHERE uid = '$uid' LIMIT 1");
//            if ($p > 0) {
//                 return $p;
//			}else{
//				$p = $this->App->findvar("SELECT dl_2_id FROM `{$this->App->prefix() }daili_invitecode` WHERE uid = '$uid' LIMIT 1");
//				if($p > 0){
//					  return $p;
//					}else{
//						$p = $this->App->findvar("SELECT dl_1_id FROM `{$this->App->prefix() }daili_invitecode` WHERE uid = '$uid' LIMIT 1");
//						if($p > 0){
//					  return $p;
//						         }else{
//									 return 0;
//									 }
//						}
//				}
//    }
//	


       function insteadorder_summary($data=array()){
		
		 $groupid = $this->Session->read('groupid');
		  $adminid = $this->Session->read('adminid');
		  
		 
		  
		  if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) && empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time1'])+24*60*60 ;
                    $comd[] = "tb1.kou_time >= ". strtotime($_GET['add_time1']) ." and tb1.kou_time < " .$t;
			}
			
			if(isset($_GET['add_time2'])&&!empty($_GET['add_time2']) && empty($_GET['add_time1'])){
                    $comd[] = "tb1.pay_time <= ". strtotime($_GET['add_time2']);
			}
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) &&isset($_GET['add_time2'])&& !empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time2'])+24*60*60 ;
                    $comd[] = "tb1.pay_time >= ". strtotime($_GET['add_time1']) ." and tb1.pay_time < " .$t;
			}
			
			 $w = " WHERE tb1.pay_status=1 and tb1.daili_uid > 0"; 
            if(!empty($comd)){
                $w .= ' and '.@implode(' AND ',$comd);
            }
			
			if(isset($_GET['dl_id']) && $_GET['dl_id'] > 0){
				
				
				  
				  	switch($groupid){
					case '1':
					
					$next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_1_id=".$_GET['dl_id']." and status = 1 ");
					break;
					
					case '16':
					
					$next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_2_id=".$_GET['dl_id']." and status = 1 ");
       
					break;
					
					case '17':
					
					$next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$_GET['dl_id']." and status = 1 ");
        
					break;
					
					case '18':

				 $next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$_GET['dl_id']." and status = 1 ");
				   
			        
					break;
					
					}
					
					
					 $id_arr = array();

				 if($next_ids){
			 foreach($next_ids as $key => $row){
				 array_push($id_arr, $row['uid']);
				 }
			    
				 
				  $w .= " and tb1.user_id IN(".@implode(',',$id_arr).")"; 


									   $feilv = $this->App->findvar("SELECT feilv FROM `{$this->App->prefix() }user_level` WHERE lid=9 LIMIT 1");
                     $feilv = unserialize($feilv);
					 $koulv = $feilv['yinlian_instead'];
					 
					
					
if($groupid == 18){
		$shangji = $this->App->findvar("SELECT parentid FROM `{$this->App->prefix() }admin` WHERE adminid=".$adminid." LIMIT 1");
		$feilv1 = $this->App->findrow("SELECT dl_feilv,dl_sxf FROM `{$this->App->prefix() }daili_set` WHERE dl_adminid=".$shangji." LIMIT 1");
	}else{
	 $feilv1 = $this->App->findrow("SELECT dl_feilv,dl_sxf FROM `{$this->App->prefix() }daili_set` WHERE dl_adminid=".$adminid." LIMIT 1");
		}

 $koulv1 = $feilv1['dl_feilv'];
 if(!$koulv1 || $koulv < $koulv1){
	  $koulv1 = $feilv['yinlian_instead'];
	 }
	 

					$sql = "SELECT IFNULL(sum(tb1.order_amount),0) as zong_order_amount,count(tb1.order_id) as bishu,sum(tb1.order_amount*(tb1.feilv-".$koulv1.")/10000) as fenrun FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 ".$w." limit 1";
     
				
			}
			
			  }
				  
			
			
			
			 	$lists = $this->App->findrow($sql);
			 
			 unset($next_ids);
		unset($id_arr);
			
			if($lists){
				$lists['adminname'] = $this->App->findvar("SELECT adminname FROM  `{$this->App->prefix()}admin` WHERE adminid=".$_GET['dl_id']);
				$lists['zong_sxf'] = $lists['bishu']*$feilv1['dl_sxf'];
				$lists['zong_fenrun'] =  $lists['fenrun']+$lists['zong_sxf'];
			}
			
				if($groupid == 18){
			$sql1 = "SELECT tb1.*, tb2.groupname FROM `{$this->App->prefix()}admin` AS tb1";
		$sql1 .= " LEFT JOIN `{$this->App->prefix()}admin_group` AS tb2 ";
		$sql1 .= " ON tb1.groupid = tb2.gid where tb1.parentid > 0  and tb1.adminid = ".$adminid;
				}else{
					$sql1 = "SELECT tb1.*, tb2.groupname FROM `{$this->App->prefix()}admin` AS tb1";
		$sql1 .= " LEFT JOIN `{$this->App->prefix()}admin_group` AS tb2 ";
		$sql1 .= " ON tb1.groupid = tb2.gid where tb1.parentid > 0  and tb1.parentid = ".$adminid;
					}
		
		
		
		$daili_list = $this->App->find($sql1);

		$this->set('daili_list',$daili_list);
			
			
			$this->set('groupid',$groupid);
		 	$this->set('lists',$lists);
			$this->template('manager_goods_order_summary');
			
			
		}
		
	
	function insteadorderlist($data=array()){
		
		
		
		
		
		   //分页
           $page= isset($_GET['page']) ? $_GET['page'] : 1;
           if(empty($page)){
                $page = 1;
           }
		   $list = 16;
		   $start = ($page-1)*$list;
		 
		  $groupid = $this->Session->read('groupid');
		  $adminid = $this->Session->read('adminid');
		     
			
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) && empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time1'])+24*60*60 ;
                    $comd[] = "tb1.pay_time >= ". strtotime($_GET['add_time1']) ." and tb1.pay_time < " .$t;
			}
			
			if(isset($_GET['add_time2'])&&!empty($_GET['add_time2']) && empty($_GET['add_time1'])){
                    $comd[] = "tb1.pay_time <= ". strtotime($_GET['add_time2']);
			}
			if(isset($_GET['add_time1'])&&!empty($_GET['add_time1']) &&isset($_GET['add_time2'])&& !empty($_GET['add_time2'])){
					$t = strtotime($_GET['add_time2'])+24*60*60 ;
                    $comd[] = "tb1.pay_time >= ". strtotime($_GET['add_time1']) ." and tb1.pay_time < " .$t;
			}
			
		
			
			
			 $w = " WHERE tb1.pay_status=1 and tb1.daili_uid > 0 "; 
            if(!empty($comd)){
                $w .= ' and  '.@implode(' AND ',$comd);
            }
		
		 if($groupid == 1){
			 if(isset($_GET['dl_id']) && $_GET['dl_id'] > 0){
				 $next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_1_id=".$_GET['dl_id']." and status = 1 ");
				 if($next_ids){
					 $id_arr = array();
			 foreach($next_ids as $key => $row){
				 array_push($id_arr, $row['uid']);
				 }
				 
				 $w .= " and tb1.user_id IN(".@implode(',',$id_arr).")"; 
				 
				  $sql = "SELECT count(tb1.order_id) FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid ";
            $sql .= " $w ";
			
		
			
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb3.adminname as dailiname,tb2.InviteCode FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id  LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid ".$w." ORDER BY tb1.order_id ASC LIMIT $start,$list";
					}
				 
			 }else{
				 
			 $sql = "SELECT count(tb1.order_id) FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid ";
            $sql .= " $w ";
			
		
			
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb3.adminname as dailiname,tb2.InviteCode FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id  LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid ".$w." ORDER BY tb1.order_id ASC LIMIT $start,$list";
			 }
			
		
			
			 }
			  if($groupid == 16){
				   if(isset($_GET['dl_id']) && $_GET['dl_id'] > 0){
				 $next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_2_id=".$_GET['dl_id']." and status = 1 ");
			 }else{
				   $next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_1_id=".$adminid." and status = 1 ");
			 }
					if($next_ids){
					 $id_arr = array();
			 foreach($next_ids as $key => $row){
				 array_push($id_arr, $row['uid']);
				 }
				 
				 $w .= " and tb1.user_id IN(".@implode(',',$id_arr).")"; 
					
			 
			 $sql = "SELECT count(tb1.order_id) FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb2.dl_2_id LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb4.user_id=tb1.user_id ";
            $sql .= " $w ";
			
		
			
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb3.adminname as dailiname,tb2.InviteCode FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id  LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb2.dl_2_id LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb4.user_id=tb1.user_id".$w." ORDER BY tb1.order_id ASC LIMIT $start,$list";
			}
			
				
			
			
			
			 }
			  if($groupid == 17){
				  
				    $next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_2_id=".$adminid." and status = 1 ");
					if($next_ids){
					 $id_arr = array();
			 foreach($next_ids as $key => $row){
				 array_push($id_arr, $row['uid']);
				 }
				 
				 $w .= " and tb1.user_id IN(".@implode(',',$id_arr).")"; 
					
					
			 $sql = "SELECT count(tb1.order_id) FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb4.user_id=tb1.user_id ";
            $sql .= " $w ";
			
		
			
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb3.adminname as dailiname,tb2.InviteCode FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id  LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid LEFT JOIN `{$this->App->prefix()}user` AS tb4 ON tb4.user_id=tb1.user_id".$w." ORDER BY tb1.order_id ASC LIMIT $start,$list";
			
				}
			
			
			 }
			  if($groupid == 18){
				  
				    $next_ids = $this->App->find("SELECT uid FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$adminid." and status = 1 ");
					if($next_ids){
					 $id_arr = array();
			 foreach($next_ids as $key => $row){
				 array_push($id_arr, $row['uid']);
				 }
				 
				 $w .= " and tb1.user_id IN(".@implode(',',$id_arr).")"; 
					
					
			 $sql = "SELECT count(tb1.order_id) FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid ";
            $sql .= " $w ";
			
		
			
			$tt = $this->App->findvar($sql);
			$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
			
			$sql = "SELECT tb1.*,tb2.InviteCode FROM `{$this->App->prefix()}goods_order_info_instead` AS tb1 LEFT JOIN `{$this->App->prefix()}daili_invitecode` AS tb2 ON tb2.uid=tb1.user_id  LEFT JOIN `{$this->App->prefix()}admin` AS tb3 ON tb3.adminid=tb1.daili_uid ".$w." ORDER BY tb1.order_id ASC LIMIT $start,$list";
			
				}
			
		
			 }
			
			 
			 	$lists = $this->App->find($sql);
			 
			 unset($next_ids);
		unset($id_arr);
			 
			$rt['lists'] = array();
			if(!empty($lists))foreach($lists as $k=>$row){
				
				
				$row['draworder_instead'] = $this->get_draworder_instead($row['plan_id']);
				$rt['lists'][$k] = $row;
			
				//$rt['lists'][$k]['status'] = $this->get_status($row['order_status'],$row['pay_status'],$row['shipping_status']);
			}
			
		$sql1 = "SELECT tb1.*, tb2.groupname FROM `{$this->App->prefix()}admin` AS tb1";
		$sql1 .= " LEFT JOIN `{$this->App->prefix()}admin_group` AS tb2 ";
		$sql1 .= " ON tb1.groupid = tb2.gid where tb1.parentid > 0  and tb1.parentid = ".$adminid;
		
		$daili_list = $this->App->find($sql1);

		$this->set('daili_list',$daili_list);
		
		
		    $this->set('groupid',$groupid);
		 	$this->set('rt',$rt);
			$this->template('manager_goods_order_list');
	}
	
	function get_order_instead($plan_id){
		 $order_insteads = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}goods_order_info_instead` WHERE plan_id=".$plan_id." limit 1"); 
		 return $order_insteads;
		}
	
	function get_draworder_instead($plan_id){
		
		 $draworder_insteads = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user_drawmoney_instead` WHERE plan_id=".$plan_id." limit 1"); 
		 return $draworder_insteads;
		}
	
	//管理员权限组
	function managergroup($tt="",$id=0){
		if(empty($tt)){
			$sql = "SELECT * FROM `{$this->App->prefix()}admin_group`";
			$this->set('grouplist',$this->App->find($sql));
			$this->template('managergrouplist');
		}else{
			$rts = array();
			if($tt=='edit'){
				if(empty($id) || !(Import::basic()->int_preg($id))){
					$this->jump('manager.php?type=group');
					exit;
				}
				$sql = "SELECT *FROM `{$this->App->prefix()}admin_group` WHERE gid='$id' LIMIT 1";
				$rts = $this->App->findrow($sql);
				if(empty($rts)){
					$this->jump('manager.php?type=group');
					exit;
				}
			}
			require_once(SYS_PATH_ADMIN.'inc/admingroup.php');
			$sql = "SELECT `option_group` FROM `{$this->App->prefix()}admin_group` WHERE gid='$id' LIMIT 1";
			$option_group = $this->App->findvar($sql);
			$option_group_arr = array();
			if(!empty($option_group)){
				$option_group_arr = explode('+',$option_group);
			}
			
			$this->set('option_group_arr',$option_group_arr);
			$this->set('groupname_arr',$groupname_arr);
			$this->set('groupname_arr2_sub',$groupname_arr2_sub);
			$this->set('rts',$rts);
			$this->set('type',$tt);
			unset($option_group_arr,$groupname_arr,$rts);
			$this->template('managergroup_info');
		}
	}
	
	//管理员日记
	function managerlog($adminname=''){
		$w = "";
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY `'.$_GET['desc'].'` DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY `'.$_GET['asc'].'` ASC';
		}else {
		  	  $orderby = ' ORDER BY `gid` DESC';
		}
		
		if(!empty($adminname)){
			$w = "WHERE optioner='$adminname'";
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(gid) FROM `{$this->App->prefix()}adminlog` {$w}";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);

		$sql = "SELECT * FROM `{$this->App->prefix()}adminlog` {$w} {$orderby} LIMIT $start,$list";
		$rts = $this->App->find($sql);
		$this->set('rts',$rts);
		$this->set('page',$page);
		$this->template('managerlog');
	}
	
	//ajax 删除管理员
	function ajax_deladmin($id = 0){
		if(empty($id) || !(Import::basic()->int_preg($id))){ echo "非法删除！删除ID为空或者不合法！"; return false;}
		
		$sql = "SELECT groupid FROM `{$this->App->prefix()}admin` WHERE adminid = '{$id}' LIMIT 1";
		$groupid = $this->App->findvar($sql);
		if($groupid == 1){
			echo "你没有权限删除最高管理员！";
			exit;
		}
		if($this->App->delete('admin','adminid',$id)){
			$this->action('system','add_admin_log','删除管理员：ID为'.$id);
		}else{
			echo "删除中发生意外错误！";	
		}
	}
	
	function ajax_deldaili($id = 0){
		if(empty($id) || !(Import::basic()->int_preg($id))){ echo "非法删除！删除ID为空或者不合法！"; return false;}
		
		$sql = "SELECT groupid FROM `{$this->App->prefix()}admin` WHERE adminid = '{$id}' LIMIT 1";
		$groupid = $this->App->findvar($sql);
		if($groupid == 1){
			echo "你没有权限！";
			exit;
		}
		if($this->App->delete('admin','adminid',$id)){
			$this->action('system','add_admin_log','删除代理：ID为'.$id);
		}else{
			echo "删除中发生意外错误！";	
		}
	}
	
	//ajax 删除组权限
	function ajax_delgroup($id = 0){
		if(empty($id) || !(Import::basic()->int_preg($id))){ echo "非法删除！删除ID为空或者非法！"; return false;}

		if($this->App->delete('admin_group','gid',$id)){
			$this->action('system','add_admin_log','删除权限组：ID为'.$id);
		}else{
			echo "删除中发生意外错误！";	
		}
	}
	//ajax 添加/修改管理员
	function ajax_addmanmger($data = array(),$aid=0){
	    if(empty($data)){ echo "数据为空！";  return false;}
		$uname = $data['adminname'];
		if(!(Import::basic()->username_preg($uname))){
			echo "你指定的管理名字不合法！"; 
			return false;
		}
		
		//判断是否已经存在
		$sql = "SELECT adminid FROM `{$this->App->prefix()}admin` WHERE adminname = '$uname' LIMIT 1";
		$adminid = $this->App->findvar($sql);
		//修改操作
		if(!empty($aid)){ 
			if(Import::basic()->int_preg($aid)){
				if(empty($adminid) || $adminid == $aid){
					$data_ = array_diff_assoc($data,array('addtime'=>time()));
					$this->App->update('admin',$data_,'adminid',$aid);
					$this->action('system','add_admin_log','修改管理员：'.$data['adminname']);
					unset($data);
				}else{
					echo "重复管理员名称，无法操作！";	
				}
			}else{
				echo "非法的ID！";
			}
			exit;
		}

		if(!empty($adminid)){
			echo "重复管理员名称，无法操作！";	
		}else{
			if($this->App->insert('admin',$data)){
				$this->action('system','add_admin_log','添加管理员：'.$data['adminname']);
			}else{
				echo "操作中发生意外错误！";	
			}
		}
	}
	
	
	
		//ajax 添加/修改管理员
	function ajax_adddailimanmger($data = array(),$aid=0){
	    if(empty($data)){ echo "数据为空！";  return false;}
		$uname = $data['adminname'];
		if(!(Import::basic()->username_preg($uname))){
			echo "你指定的管理名字不合法！"; 
			return false;
		}
		
		//判断是否已经存在
		$sql = "SELECT adminid FROM `{$this->App->prefix()}admin` WHERE adminname = '$uname' LIMIT 1";
		$adminid = $this->App->findvar($sql);
		//修改操作
		if(!empty($aid)){ 
			if(Import::basic()->int_preg($aid)){
				if(empty($adminid) || $adminid == $aid){
					$data_ = array_diff_assoc($data,array('addtime'=>time()));
					$this->App->update('admin',$data_,'adminid',$aid);
					$this->action('system','add_admin_log','修改代理：'.$data['adminname']);
					unset($data);
				}else{
					echo "重复代理名称，无法操作！";	
				}
			}else{
				echo "非法的ID！";
			}
			exit;
		}

		if(!empty($adminid)){
			echo "重复代理名称，无法操作！";	
		}else{
			$adminid = $this->Session->read('adminid');
			$data['parentid'] = $adminid; 
			if($this->App->insert('admin',$data)){
				$this->action('system','add_admin_log','添加代理：'.$data['adminname']);
			}else{
				echo "操作中发生意外错误！";	
			}
		}
	}
	//ajax添加/修改 权限组
	function ajax_addgroup($data = array(),$gid=0){
		if(empty($data)){ echo "数据为空！";  return false;}
		$gname = $data['groupname'];
		/*if(!(Import::basic()->username_preg($gname))){
			echo "你指定的权限组名字不合法！"; 
			return false;
		}*/
		
		$sql = "SELECT groupname FROM `{$this->App->prefix()}admin_group` WHERE groupname = '$gname' LIMIT 1";
		$gname = $this->App->findvar($sql);
		
		$g_adminid = $this->App->findvar("SELECT adminid FROM `{$this->App->prefix()}admin_group` WHERE groupname = '$gname' LIMIT 1");
		
		//修改操作
		if(!empty($gid) && (Import::basic()->int_preg($gid))){
			if(empty($gname) || ($gname == $data['groupname']) || ($g_adminid == 0)){
				$data_ = array_diff_assoc($data,array('addtime'=>time())); 
				$data_['adminid'] = $this->Session->read('adminid');
				if($this->App->update('admin_group',$data_,'gid',$gid)){
					$this->action('system','add_admin_log','修改权限组：'.($gname ? $gname : '审核状态'));
				}else{
					echo "数据从未改变，无需修改！";
				}
				unset($data);
				exit;
			}else{
				echo "重复管理组名称，无法修改！";	 exit;
			}
		}
		
		if(!empty($gname)){
			echo "重复管理组名称，无法添加！";	
		}else{
			$data['adminid'] = $this->Session->read('adminid');
			if($this->App->insert('admin_group',$data)){
				$this->action('system','add_admin_log','添加权限组：'.$gname);
			}else{
				echo "入库时发生意外错误！";	
			}
		}
		}function ajax_check_lib(){
		
	}
	

	//ajax删除日记
	function ajax_dellog($ids){
		if(empty($ids)){ echo "删除ID为空！"; exit;}
		$groupid = $this->Session->read('groupid');
		if($groupid!='1'){
			echo "你不是最高管理员，无法删除！";exit;
		}
		$arr = explode('+',$ids);
		foreach($arr as $id){
		  $this->App->delete('adminlog','gid',$id);
		}
		$this->action('system','add_admin_log','管理员日记删除：ID为'.implode(',',$arr));
	}
	################管理员登陆部分###############
	//管理员登录
	function index($type=''){
		$this->layout('login-default');
		$this->title($GLOBALS['LANG']['site_name']);
		$this->css('login.css');
		$this->template('login');
	}
	
	//管理员登陆验证
  	function login($data=array()){ 
		if(!isset($data['adminname']) || empty($data['adminname']) || !isset($data['password']) || empty($data['password']) ) die("用户名或密码不能为空！"); 
		$username = $data['adminname'];
		$pass = md5($data['password']);
		$vifcode = $data['vifcode']; 
		if(strtolower($vifcode) != strtolower($this->Session->read('vifcode'))){
			die("验证码错误！");
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}admin` WHERE `adminname`='$username' AND `password`='$pass' LIMIT 1";
		$rt = $this->App->findrow($sql); 
		if(!empty($rt)){
			$this->App->update('admin',array('lasttime'=>time()),'adminid',$rt['adminid']);
			$this->Session->write('adminname',$rt['adminname']);
			$this->Session->write('adminid',$rt['adminid']);
			$this->Session->write('groupid',$rt['groupid']);
			$this->Session->write('lasttime',$rt['lasttime']);
			$this->Session->write('lastip',$rt['lastip']);
			$this->Session->write('email',$rt['email']);
			
			//增加权限组
			$groupid = $rt['groupid'];
			if($groupid=='1'){
				$sql = "SELECT  option_group FROM `{$this->App->prefix()}admin_group` WHERE active ='1' AND gid='$groupid'";
				$option_group = $this->App->findvar($sql);
				if(!empty($option_group)){
					$Permissions = @explode("+",$option_group);
					if(count($Permissions) < 10){
						require_once(SYS_PATH_ADMIN."inc/menulist.php");
						if(!empty($menu)){
							 $groupname_arr = array();
							 foreach($menu as $row){
								$groupname_arr[] = $row['big_key'];
								foreach($row['sub_mod'] as $rows){
									$groupname_arr[] = $rows['en_name'];
								}
							 }
							 if(!empty($groupname_arr)){
							 	$this->App->update('admin_group',array('option_group'=>implode('+',$groupname_arr)),'gid','1' );
							 }
						 }
					}
				}
			}
			$this->action('system','add_admin_log','登录成功，登录管理员：'.$rt['adminname']);
		}else{
			die("用户名与密码不匹配，请重新输入！");
			$this->action('system','add_admin_log','登录失败，登录管理员：'.$rt['adminname']);
		}
	}
	
	/*/管理员权限查询start*/
	function admin_Permissions(){
		$groupid = $this->Session->read('groupid');
		$sql = "SELECT  option_group FROM `{$this->App->prefix()}admin_group` WHERE active ='1' and gid='$groupid'";
		return $this->App->findvar($sql);
		
	}
	
	/*/管理员权限查询end*/
	
	//判断是否已经登陆
	function is_login(){
		if(!isset($_SESSION['adminid']) || empty($_SESSION['adminid']) || !isset($_SESSION['adminname']) || empty($_SESSION['adminname']) ) {
			return false;
		}else{
		 	return true;
		}
	}
	
	//退出登录
	function logout(){
		$this->action('system','add_admin_log','退出登录-'.date('Y-m-d H:i:s',mktime()).'-'.($this->Session->read('adminname'))); 
		session_destroy();
	}
	
	//返回用户记录的session信息
	function getuserinfo($type=""){
		switch($type){
			case 'adminname':
				return isset($_SESSION['adminname']) ? $_SESSION['adminname'] : "";
				break;
			case 'adminid':
				return isset($_SESSION['adminid']) ? $_SESSION['adminid'] : "0";
				break;
			case 'groupid':
				return isset($_SESSION['groupid']) ? $_SESSION['groupid'] : "0";
				break;
			case 'lasttime':
				return isset($_SESSION['lasttime']) ? $_SESSION['lasttime'] : "";
				break;
			case 'lastip':
				return isset($_SESSION['lastip']) ? $_SESSION['lastip'] : "0.0.0.0";
			case 'email':
				return isset($_SESSION['email']) ? $_SESSION['email'] : "";
				break;
			default:
				return array('adminid'=>$_SESSION['adminid'],'groupid'=>$_SESSION['groupid'],'lasttime'=>$_SESSION['lasttime'],'lastip'=>$_SESSION['lastip'],'adminname'=>$_SESSION['adminname'],'email'=>$_SESSION['email']);
				break;
		}
	}
	
	##############下面是来自系统的留言################
	function message_list($status=0){
		$w = "";
		if($status==1){
			$w = " WHERE tb1.status='1'";
			$ws = " WHERE status='1'";
		}elseif($status==2){
			$w = " WHERE tb1.status='2'";
			$ws = " WHERE status='1'";
		}
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY tb1.`'.$_GET['desc'].'` DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY tb1.`'.$_GET['asc'].'` ASC';
		}else {
		  	  $orderby = ' ORDER BY tb1.`mes_id` DESC';
		}
		
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` {$ws}";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT tb1.*, tb2.user_name AS dbuser_name,tb2.nickname,tb3.goods_name,tb3.goods_id FROM `{$this->App->prefix()}message` AS tb1";
		//如果有用户id的话
		$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb3 ON tb1.goods_id=tb3.goods_id";
		$sql .=" $w $orderby LIMIT $start,$list";
		$this->set('meslist',$this->App->find($sql));
		$this->template('mes_list');
		
	}
	
	function message_info($id="0"){
		if($id==0){ $this->jump('manager.php?type=meslist'); exit;}
		$sql = "SELECT tb1.*, tb2.user_name AS dbuser_name,tb2.nickname,tb3.goods_name,tb3.goods_id FROM `{$this->App->prefix()}message` AS tb1";
		//如果有用户id的话
		$sql .=" LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb3 ON tb1.goods_id=tb3.goods_id";
		$sql .=" WHERE tb1.mes_id = '{$id}' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){ $this->jump('manager.php?type=meslist'); exit;}
		$this->set('rt',$rt);
		$this->template('mes_info');
	}
	
	function ajax_delmes($ids){
		if(empty($ids)) echo "删除ID为空！";
		$arr = explode('+',$ids);
		foreach($arr as $id){
		  $this->App->delete('message','mes_id',$id);
		}
		$this->action('system','add_admin_log','留言删除：ID为'.implode(',',$arr));
	}
	
	function ajax_savemes($data=array()){
		if(empty($data['mes_id'])) die("非法操作，无识别ID！");
		$sdata['admin_remark'] = $data['admin_remark'];
		$sdata['status'] = 2;
		$sdata['rp_content'] = $data['rp_content'];
		$sdata['rp_adminid'] = $this->getuserinfo('adminid');
		$this->App->update('message',$sdata,'mes_id',$data['mes_id']);
		$this->action('system','add_admin_log','留言审核：'.$data['title'].'=>'.$data['mes_id']);
		unset($data,$sdata);
	}
	
	
	
	function Invitecodelist(){
		
		$adminid = $this->Session->read('adminid');
		$groupid = $this->Session->read('groupid');
		if($adminid == 1){
			
			 //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
			
			  $list = 10;
            $start = ($page-1)*$list;
            $sql = "SELECT count(id) FROM `{$this->App->prefix()}daili_invitecode`";

            //echo $sql;
            
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
			//$InviteCodeList = $this->App->find("SELECT * FROM `{$this->App->prefix()}daili_invitecode` order by id asc LIMIT $start,$list");
			
			$InviteCodeList = $this->App->find("SELECT tb1.*,tb2.adminname FROM `{$this->App->prefix()}daili_invitecode` as tb1 left join `{$this->App->prefix()}admin` as tb2 on tb2.adminid = tb1.dl_1_id  order by tb1.id asc LIMIT $start,$list");
			
			}else{
				
				
				
					 //分页
            $page= isset($_GET['page']) ? $_GET['page'] : '';
            if(empty($page)){
                   $page = 1;
            }
			
			  $list = 10;
            $start = ($page-1)*$list;
			   if($groupid == 16){
				    $sql = "SELECT count(id) FROM `{$this->App->prefix()}daili_invitecode` where dl_1_id=".$adminid;
					   
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
				$InviteCodeList = $this->App->find("SELECT tb1.*,tb2.adminname FROM `{$this->App->prefix()}daili_invitecode` as tb1 left join `{$this->App->prefix()}admin` as tb2 on tb2.adminid = tb1.dl_2_id  where tb1.dl_1_id=".$adminid." order by tb1.id asc LIMIT $start,$list");
				
				}
				if($groupid == 17){
				    $sql = "SELECT count(id) FROM `{$this->App->prefix()}daili_invitecode` where dl_2_id=".$adminid;
					   
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
					$InviteCodeList = $this->App->find("SELECT tb1.*,tb2.adminname FROM `{$this->App->prefix()}daili_invitecode` as tb1 left join `{$this->App->prefix()}admin` as tb2 on tb2.adminid = tb1.dl_3_id  where tb1.dl_2_id=".$adminid." order by tb1.id asc LIMIT $start,$list");
				}
				if($groupid == 18){
				    $sql = "SELECT count(id) FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$adminid;
					   
            $tt = $this->App->findvar($sql);
            $pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
            $this->set("pagelink",$pagelink);
			
				$InviteCodeList = $this->App->find("SELECT * FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$adminid." order by id asc LIMIT $start,$list");
				}
        

            //echo $sql;
         
				}
		
		$this->set('groupid',$groupid);
		$this->set('InviteCodeList',$InviteCodeList);
		$this->template('InviteCode');
		}
		
		function ajax_CreateInviteCode(){
			
			for($i=0;$i<1000;$i++){
				$sdata['InviteCode'] = $this->random_string(6);
				$sdata['addtime'] = mktime();
				
					if($this->App->insert('daili_invitecode',$sdata)){
					echo "";
					}else{
						echo json_encode($sdata);
						}
			
			
				}
				
			
			}
			
			
			 function random_string($len) {
       $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $string=time();
        for(;$len>=1;$len--)
        {
            $position=rand()%strlen($chars);
            $position2=rand()%strlen($string);
            $string=substr_replace($string,substr($chars,$position,1),$position2,0);
        }
        return $string;

    }
	
	
	 function distribution() {
		 $dl_id = $_GET['id'];
            
            $rts = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}admin` where adminid=".$dl_id);
		 
		 $this->set('rts',$rts);
		 $this->set('dl_id',$dl_id);
		$this->template('DistributionCode');
		
	 }
	 function ajax_distribution($data = array()){
		 $number = $data['number'];
		 $dl_id = $data['dl_id'];
		 
		 
		 $adminid = $this->Session->read('adminid');
		 $groupid = $this->Session->read('groupid');
		 if($adminid == 1){
		 $xuanze = $this->App->find("SELECT id FROM `{$this->App->prefix()}daili_invitecode` where status_1 = 0 and status=0 order by id asc limit ".$number);
		 	 $id_arr = array();
			 foreach($xuanze as $key => $row){
				 array_push($id_arr, $row['id']);
				 }
			 
			 $this->App->update('daili_invitecode',array('dl_1_id'=>$dl_id,'status_1'=>'1'),'id',$id_arr);

			 	 unset($id_arr);
			 
		 }else{
			 
			 if($groupid == 16){
				    $xuanze = $this->App->find("SELECT id FROM `{$this->App->prefix()}daili_invitecode` where status_2=0 and status=0 and dl_1_id=".$adminid." order by id asc limit ".$number);
					
					 $id_arr = array();
			 foreach($xuanze as $key => $row){
				 array_push($id_arr, $row['id']);
				 }
			 
			 $this->App->update('daili_invitecode',array('dl_2_id'=>$dl_id,'status_2'=>'1'),'id',$id_arr);
			 
			
			 
				}
				if($groupid == 17){
				    $xuanze = $this->App->find("SELECT id FROM `{$this->App->prefix()}daili_invitecode` where status_3=0 and status=0 and dl_2_id=".$adminid." order by id asc limit ".$number);
					 $id_arr = array();
			 foreach($xuanze as $key => $row){
				 array_push($id_arr, $row['id']);
				 }
			 
			 $this->App->update('daili_invitecode',array('dl_3_id'=>$dl_id,'status_3'=>'1'),'id',$id_arr);
				}
				
        
			 }
		
		 
	 }
	
	function export_invitecode(){
		
		
		$adminid = $this->Session->read('adminid');
		$groupid = $this->Session->read('groupid');
		 error_reporting(0);  
				header( "Cache-Control: public" );
    header( "Pragma: public" );
header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=" . iconv ( "UTF-8", "GBK", "invitecode".date('Y-m-j_H_i_s')). ".csv" );
    header('Content-Type:APPLICATION/OCTET-STREAM');
				

	
				
				 // 打开PHP文件句柄，php://output 表示直接输出到浏览器  
                $fp = fopen('php://output', 'a');   
                //表头  
        $column_name = array('邀请码','代理','状态');  
                // 将中文标题转换编码，否则乱码  
              foreach ($column_name as $i => $v) {    
                   $column_name[$i] = iconv('utf-8', 'gbk', $v);    
              }  
        // 将标题名称通过fputcsv写到文件句柄    
              fputcsv($fp, $column_name);  
              $pagecount = 1000;//一次读取多少条
			  
			  if($groupid == 1){
				  
				  $tt = $this->App->findvar("SELECT COUNT(*) FROM `{$this->App->prefix()}daili_invitecode`");
				  
                  $sql = "SELECT d.*,a.adminname FROM `{$this->App->prefix()}daili_invitecode` AS d ";
				  $sql .= " LEFT JOIN `{$this->App->prefix()}admin` AS a ON a.adminid = d.dl_1_id ";				  
			  }
			  
			  if($groupid == 16){
				  
				  $tt = $this->App->findvar("SELECT COUNT(*) FROM `{$this->App->prefix()}daili_invitecode` where dl_1_id=".$adminid);
				  $sql = "SELECT d.*,a.adminname FROM `{$this->App->prefix()}daili_invitecode` AS d ";
				  $sql .= " LEFT JOIN `{$this->App->prefix()}admin` AS a ON a.adminid = d.dl_2_id where d.dl_1_id=".$adminid;
			  }
			  
			  if($groupid == 17){
				  
				  $tt = $this->App->findvar("SELECT COUNT(*) FROM `{$this->App->prefix()}daili_invitecode` where dl_2_id=".$adminid);
				 $sql = "SELECT d.*,a.adminname FROM `{$this->App->prefix()}daili_invitecode` AS d ";
				  $sql .= " LEFT JOIN `{$this->App->prefix()}admin` AS a ON a.adminid = d.dl_3_id where d.dl_2_id=".$adminid;
			  }
			  
			  if($groupid == 18){
				  
				  $tt = $this->App->findvar("SELECT COUNT(*) FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$adminid);
				  $sql = "SELECT * FROM `{$this->App->prefix()}daili_invitecode` where dl_3_id=".$adminid;
			  }

		  
        $totalcount = $tt;//总记录数  
	
		
		
		
		
		
	
		
           for ($i=0;$i<intval($totalcount/$pagecount)+1;$i++){  
            $data = $this->App->find($sql." limit ".strval($i*$pagecount).",".$pagecount);  
			
			$rows = array();
            foreach ( $data as $v ) {  
                  
    
				// $rows[] = iconv('utf-8', 'gbk', $v);  
                    $rows['InviteCode'] = iconv('utf-8', 'GBK', $v['InviteCode']); 
					$adminname = empty($v['adminname'])?'无':$v['adminname'];
					 $rows['adminname'] = iconv('utf-8', 'GBK', $adminname);  
					 $status = ($v['status'] > 0)?'已激活':'未激活';
					  $rows['status'] = iconv('utf-8', 'GBK', $status);  
					  

           

                fputcsv($fp, $rows);  
            }  
            // 将已经写到csv中的数据存储变量销毁，释放内存占用  
            unset($data);  
            //刷新缓冲区  
            ob_flush();  
            flush();  
        }  
    exit;  
	
	}

	
}

