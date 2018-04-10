<?php
class PageController extends Controller{
	private $mykey = 'D27897844bc35e217de79b30b3567356'; //安全码
	private $token;
	private $wecha_id;
    private $fun;
    private $data = array();
    public $fans;
    private $my = '小邻宝';
    public $wxuser;
    public $apiServer;
	
 	function  __construct() {
		$this->layout('content');
	}
	
	function get_site_name(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
				$sql = "SELECT site_name FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1";
    			$rt = $this->App->findvar($sql);
				
				$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
				
	}
	
	function test(){
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=j3N6qv682-1QwJgg1_rb2-9YnGXe4y6OV8Fqt_BoOVqB1ttY8VGdXjyYQ38-CL9Zb2KFRg5KNzAp1RVEjVDLd2ZidJ99G_vkQk7-dmO-Qh8&openid=o3i6tuN76fOIqquTYD5ZAo5fjDNw";
		$con = $this->curlGet($url);
		$json=json_decode($con);
		print_r($json);
	}
	
	function send($rts=array(),$type=""){
		if(empty($rts['openid'])) return;
		
		$access_token = $this->_get_access_token();
		$data = $this->_get_send_con($rts,$type);
		$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data,0);
	}
	function _get_send_con($rt=array(),$ty=''){
		$data = array();
		switch($ty){
			
			case 'assistant_success':
			$openid = $rt['openid'];
			$data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"店员添加成功！"}}';
			break;
			
			case 'assistant_fail':
			$openid = $rt['openid'];
			$data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"店员添加失败！"}}';
			break;
			
			case 'assistant_fail_s':
			$openid = $rt['openid'];
			$data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"店员已添加成功,请勿重复添加！"}}';
			break;
			
			
			case 'guanzhu':
			$openid = $rt['openid'];

			$str = '好友['.$rt['nickname'].']关注,新增积分:+'.$rt['points'].'积分;\n\n服务类型：推荐关注返积分\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：再接再厉哦!返积分详情点击进入查看!';

			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "服务申请提交成功", "description": "'.$str.'","url":"'.str_replace('api','m',ADMIN_URL).'user.php?act=mypoints"}]}}';

			break;
			case 'guanzhu_use':

			$openid = $rt['openid'];

			$str = '已关注,新增积分:+'.$rt['points'].'积分;\n\n服务类型：关注返积分\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：再接再厉哦!返积分详情点击进入查看!';

			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "服务申请提交成功", "description": "'.$str.'","url":"'.str_replace('api','m',ADMIN_URL).'user.php?act=mypoints"}]}}';

			break;
			case 'guanzhudaili':
			$openid = $rt['openid'];
			$str = '您的用户下级好友['.$rt['nickname'].']已经关注您啦;\n\n服务类型：下级好友关注返积分\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：他还需要购买您才有收入哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "服务申请提交成功", "description": "'.$str.'","url":"'.str_replace('api','m',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'guanzhudaili2'://二级代理
			$openid = $rt['openid'];
			$str = '您的用户二级好友['.$rt['nickname'].']已经关注您啦;\n\n服务类型：二级好友关注提醒\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：他还需要购买您才有收入哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "服务申请提交成功", "description": "'.$str.'","url":"'.str_replace('api','m',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'guanzhudaili3'://三级代理
			$openid = $rt['openid'];
			$str = '您的用户三级好友['.$rt['nickname'].']已经关注您啦;\n\n服务类型：三级好友关注提醒\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：他还需要购买您才有收入哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "服务申请提交成功", "description": "'.$str.'","url":"'.str_replace('api','m',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'markimg':
			$openid = $rt['openid'];
			$data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"正在生成您的微名片,请耐心等待..."}}';
			break;
			case 'markimg2':
			$openid = $rt['openid'];
			$data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"正在生成您推荐人的微名片,如需生成您自己的微名片请申请成为分销商或购物直接成为分销商..."}}';
			break;
			case 'markimg3':
			$openid = $rt['openid'];
			$data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"正在生成'.($this->get_site_name()).'的微名片,请耐心等待..."}}';
			break;
			case 'markimgsend':
			$openid = $rt['openid'];
			$MEDIA_ID = $rt['media_id'];
			$data = '{"touser":"'.$openid.'","msgtype":"image","image":{"media_id":"'.$MEDIA_ID.'"}}';
			break;
		}
		
		return $data;
	}
		
	function index(){
		@set_time_limit(300); //最大运行时间
		$thisurl = Import::basic()->thisurl();
		$rt = explode('index.php',$thisurl);
		$arg = isset($rt[1]) ? $rt[1] : '';
		if(!empty($arg)){
			$rt = explode('/',$arg);
			$arg = isset($rt[1]) ? $rt[1] : '';
			if(!empty($arg)){
				$this->token = trim($arg);
			}else{
				$t = isset($_GET['t']) ? $_GET['t'] : '';
				if(empty($t)){
					die('参数为空');
				}else{
					$this->token = $t;
				}
			}
		}else{
			$t = isset($_GET['t']) ? $_GET['t'] : '';
			if(empty($t)){
				die('参数为空');
			}else{
				$this->token = $t;
			}
		}

		$ss = $this->token;
		$sql = "SELECT pigsecret FROM `{$this->App->prefix()}wxuserset` WHERE token='$ss' LIMIT 1";
		$pigsecret = $this->App->findvar($sql);
		if(empty($pigsecret)) $pigsecret = "isempty";
		
		if (!class_exists('SimpleXMLElement')) {
            die('SimpleXMLElement class not exist');
        }
        if (!function_exists('dom_import_simplexml')) {
            die('dom_import_simplexml function not exist');
        }
        if (!preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)) {
            die('Error token');
        }
		//包含
		if(!class_exists('Wechat')) require_once(SYS_PATH_API.'inc/Wechat.class.php');
		$weixin = new Wechat($pigsecret);
		$data = $weixin->request();
        $this->data = $data;
		
		if ($this->data) {
            //自定义机器人名字
			$wecha_id = $this->data['FromUserName'];
			$token = $this->token;
			$this->Session->write('User.wecha_id',$wecha_id);
			$this->Session->write('User.token',$token);
			setcookie(CFGH.'USER[UKEY]', $wecha_id, mktime() + 2592000);
			
			$this->fans = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' ");
            $this->wxuser = 'wxuser_' . $this->token;
            $this->apiServer = 'http://api.apiqq.com';
            $this->fun = ''; //这里是允许访问的权限
			$this->wecha_id = $wecha_id;
			
            list($content, $type) = $this->reply($data);
            $weixin->response($content, $type);
        }
		
	}
	
	function _return_px(){
		   $t = '';
		   $x = $_SERVER["HTTP_HOST"];
		   $x1 = explode('.',$x);
		   if(count($x1)==2){
			 $t = $x1[0];
		   }elseif(count($x1)>2){
			 $t =$x1[0].$x1[1];
		   }
		   return $t;
	}
		
	//获取appid、appsecret
	function _get_appid_appsecret(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` LIMIT 1";
					$rr = $this->App->findrow($sql);
					$rt['appid'] = $rr['appid'];
					$rt['appsecret'] = $rr['appsecret'];
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	//获取access_token
	function _get_access_token(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) ){
				    include($fn);
			}
			else
		    {
					$rr = $this->_get_appid_appsecret();
					$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$rr['appid'].'&secret='.$rr['appsecret'];
					$con = $this->curlGet($url);
					$json=json_decode($con);
					$rt = $json->access_token; //获取 access_token
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	//返回最近分销
	function _firtuids($uid=0){
		$ut = array();
		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
		$uids = $this->App->findcol($sql);
		if(!empty($uids))foreach($uids as $uid){
			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
			if($ur!='1'){
				$ut[] = $uid;
			}else{
					/********************第二次*************************/
						$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
						$uids = $this->App->findcol($sql);
						if(!empty($uids))foreach($uids as $uid){
							$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
							if($ur!='1'){
								$ut[] = $uid;
							}else{
									/********************第三次*************************/
										$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
										$uids = $this->App->findcol($sql);
										if(!empty($uids))foreach($uids as $uid){
											$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
											if($ur!='1'){
												$ut[] = $uid;
											}else{
													/********************第四次*************************/
														$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
														$uids = $this->App->findcol($sql);
														if(!empty($uids))foreach($uids as $uid){
															$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
															if($ur!='1'){
																$ut[] = $uid;
															}else{
																	/********************第五次*************************/
																		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																		$uids = $this->App->findcol($sql);
																		if(!empty($uids))foreach($uids as $uid){
																			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																			if($ur!='1'){
																				$ut[] = $uid;
																			}else{
																					/********************第六次*************************/
																							$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																							$uids = $this->App->findcol($sql);
																							if(!empty($uids))foreach($uids as $uid){
																								$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																								if($ur!='1'){
																									$ut[] = $uid;
																								}else{
																										/********************第七次*************************/
																											$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																											$uids = $this->App->findcol($sql);
																											if(!empty($uids))foreach($uids as $uid){
																												$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																												if($ur!='1'){
																													$ut[] = $uid;
																												}else{
																														/********************第八次*************************/
																															$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																															$uids = $this->App->findcol($sql);
																															if(!empty($uids))foreach($uids as $uid){
																																$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																																if($ur!='1'){
																																	$ut[] = $uid;
																																}else{
																																	break;
																																}
																																
																															}
																														/********************************************/
																												}
																												
																											}
																										/********************************************/
																								}
																								
																							}
																					/********************************************/
																			}
																			
																		}
																	/********************************************/
															}
															
														}
													/********************************************/
											}
											
										}
									/********************************************/
							}
							
						}
					/********************************************/
			}
			
		}
		
		return $ut;
	} //end function
	
	private function reply($data)
    {
        //语音功能
        if (isset($data['MsgType'])) {
            if ('voice' == $data['MsgType']) {
                $data['Content'] = $data['Recognition'];
                $this->data['Content'] = $data['Recognition'];
            }
        }
		
		//单文本回复
		//return array('<a href="http://www.baidu.com">'.$this->token.$data['FromUserName'].'</a>', 'text');
		
		//单图文回复
/*		$data['title'] = "test";
		$data['keyword'] = "keyword";
		$data['picurl'] = 'http://www.wanyangok.com/theme/images/website04_img_left.jpg';
		$data['url'] = "http://www.baidu.com";*/
		//return array(array(array($data['title'], $data['keyword'], $data['picurl'], $data['url'])), 'news');
		
		//多图文(1)
/*		$data['title'] = "test";
		$data['keyword'] = "keyword";
		$data['picurl'] = 'http://www.wanyangok.com/theme/images/website04_img_left.jpg';
		$data['url'] = "http://www.baidu.com";*/
		//return array(array(array($data['title'], $data['keyword'], $data['picurl'], $data['url']),array($data['title'], $data['keyword'], $data['picurl'], $data['url'])), 'news');
		 
		 //多图文（2）
/*		$result = array();
		$result[0][] = $data['title'];
		$result[0][] = $data['keyword'];
		$result[0][] = $data['picurl'];
		$result[0][] = $data['url'];
		$result[1][] = $data['title'];
		$result[1][] = $data['keyword'];
		$result[1][] = $data['picurl'];
		$result[1][] = $data['url'];
		$result[2][] = $data['title'];
		$result[2][] = $data['keyword'];
		$result[2][] = $data['picurl'];
		$result[2][] = $data['url'];*/
		//return array($result, 'news');
		
		//多图文（3）
/*		$row = array();
		$row[] = $data['title'];
		$row[] = $data['keyword'];
		$row[] = $data['picurl'];
		$row[] = $data['url'];
		$result[] = $row;
		$result[] = array($data['title'], $data['keyword'], $data['picurl'], $data['url']);
		$result[] = array($data['title'], $data['keyword'], $data['picurl'], $data['url']);*/
		//return array($result, 'news');
		
		
        //判断关注
        if (isset($data['Event'])) {
            if ('CLICK' == $data['Event']) {
                $data['Content'] = $data['EventKey'];
                $this->data['Content'] = $data['EventKey'];
            }
            if ($data['Event'] == 'SCAN2') { //语音
                $data['Content'] = $this->getRecognition($data['EventKey']);
                $this->data['Content'] = $data['Content'];
				
            } elseif ($data['Event'] == 'MASSSENDJOBFINISH') {
               
				
            } elseif ('subscribe' == $data['Event'] || $data['Event'] == 'SCAN') { //关注后
				/***********************************************/
				$wecha_id = $data['FromUserName']; //用户openid
				//原来已经进入了的
				$sql = "SELECT user_id,user_rank,is_subscribe FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1";
				$thisRT = $this->App->findrow($sql);
				$thisuid = $thisRT['user_id'];
				$user_rank = $thisRT['user_rank'];
				$is_subscribe = $thisRT['is_subscribe'];
				if($is_subscribe=='1'){
					
										
						$pwei = isset($data['EventKey']) ? $data['EventKey'] : '';
					$assistant_uid = 0;
					if(!empty($pwei)){
						
						 if(strpos($pwei, 'assistant') !== false){
							 
							 
							$assistant_uid = str_replace('assistant_','',$pwei);
					    unset($pwei);
		$assistantuid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id='$assistant_uid' LIMIT 1");
		
		$assistant_info = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}user_assistant` WHERE uid='$assistant_uid'  and  assistant_id ='$thisuid' LIMIT 1");
		
        if($assistant_info > 0){
							exit;
			}		
					if($assistantuid!=$thisuid && $assistantuid > 0){//添加店员
									$assistant = array();
					$assistant['uid'] = $assistantuid;
					$assistant['assistant_id'] = $thisuid;
					$assistant['wecha_id'] = $wecha_id;
					$assistant['status'] = 1;
									if($this->App->insert('user_assistant',$assistant)){
							return array('店员添加成功！', 'text');
							exit;
										}else{
												return array('店员添加失败！', 'text');
							exit;
											}
						}
						
			
						 }
						
					}
					
					return array('您已经关注！', 'text');
					exit;
				}
				unset($thisRT);
				
				$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1";
				$rrL = $this->App->findrow($sql);
				
				 //自动开通分销【这是第二次进入才会执行到】
				//if($rrL['openfxauto']=='1'){ 
//					$this->App->update('user',array('user_rank'=>'9'),'user_id',$thisuid);
//
//					$dd = array();
//					$dd['uid'] = $thisuid;
//					$dd['p1_uid'] = 0;
//					$dd['p2_uid'] = 0;
//					$dd['p3_uid'] = 0;
//					
//					$p1_uid = $this->return_daili_uid($thisuid);//找到当前的父UID  E
//				
//					$firtuids = array();
//					if($p1_uid > 0 ){
//						//更新前
//						//$firtuids = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$p1_uid'");
//						
//						$dd['p1_uid'] = $p1_uid;
//						$p2_uid = $this->return_daili_uid($p1_uid);
//						if($p2_uid > 0 ){
//							$dd['p2_uid'] = $p2_uid;
//							$p3_uid = $this->return_daili_uid($p2_uid);
//							if($p3_uid > 0 ){
//								$dd['p3_uid'] = $p3_uid;
//								/*$p4_uid = $this->return_daili_uid($p3_uid);
//								if($p4_uid > 0){
//									$dd['p4_uid'] = $p4_uid;
//								}*/
//							}
//						}
//					}
//					
//					//
//					$sql = "SELECT id FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid='$thisuid' LIMIT 1";
//					$id = $this->App->findvar($sql);
//					
//					if($id > 0){
//						$this->App->update('user_tuijian_fx',$dd,'id',$id);
//					}else{
//						$this->App->insert('user_tuijian_fx',$dd);
//					}
//				    //////////
//					unset($dd);
//					
//					
//					$mm = array();
//					$mm['uid'] = $thisuid;
//					$mm['fenrun'] = 0;
//					$mm['yongjin'] = 0;
//					$mm['tuiguang'] = 0;
//					$mm['yinlian'] = 0;
//					$mm['weixin'] = 0;
//					$mm['baidu'] = 0;
//					$mm['jingdong'] = 0;
//					$mm['duanxin'] = 0;
//					
//						$sql = "SELECT id FROM `{$this->App->prefix()}user_moneys` WHERE uid='$thisuid' LIMIT 1";
//					$id = $this->App->findvar($sql);
//					
//					if(empty($id)){
//					$this->App->insert('user_moneys',$mm);
//					}
//					
//					unset($mm);
//					
//					
//					$firtuids = $this->_firtuids($thisuid); //当前开通用户的最近一层分销用户
//			
//					$aup = array();
//					if(!empty($firtuids))foreach($firtuids as $u){ //
//						$dds = array();
//						$dds['uid'] = $u;
//						$dds['p1_uid'] = $thisuid;
//						$dds['p2_uid'] = $dd['p1_uid'];
//						$dds['p3_uid'] = $dd['p2_uid'];
//						
//						$aup[] = $dds;
//						
//						$firtuids2 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$u'");
//						if(!empty($firtuids2))foreach($firtuids2 as $uu){ //
//						
//							$dds = array();
//							$dds['uid'] = $uu;
//							$dds['p1_uid'] = $u;
//							$dds['p2_uid'] = $thisuid;
//							$dds['p3_uid'] = $dd['p1_uid'];
//							
//							$aup[] = $dds;
//							
//							$firtuids3 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$uu'");
//							if(!empty($firtuids3))foreach($firtuids3 as $uuu){ //
//								
//								$dds = array();
//								$dds['uid'] = $uuu;
//								$dds['p1_uid'] = $uu;
//								$dds['p2_uid'] = $u;
//								$dds['p3_uid'] = $thisuid;
//								
//								$aup[] = $dds;
//								
//							}//end foreach
//							unset($firtuids3);
//						} //end foreach
//						unset($firtuids2);
//					} //end foreach
//					unset($firtuids);
//					
//					if(!empty($aup))foreach($aup as $up){
//						$this->App->update('user_tuijian_fx',$up,'uid',$up['uid']);
//					}
//					unset($aup);
//			
//				}//end 自动开通分销
				
				if(!($thisuid>0)){	//第一次进来			
					$pwei = isset($data['EventKey']) ? $data['EventKey'] : '';
					$assistant_uid = 0;
					$spuid = 0;
					if(!empty($pwei)){

				  if(strpos($pwei, 'assistant') !== false){
							$assistant_uid = str_replace('assistant_','',str_replace('qrscene_','',$pwei));
					    unset($pwei);
					}else{
						$spuid = str_replace('qrscene_','',$pwei);
						unset($pwei);
					}
					
					}
					if($spuid > 0){//推荐者quid
						$uuid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id='$spuid' LIMIT 1");
						$this->Session->write('User.tid',$uuid);
						setcookie(CFGH.'USER[TID]', $uuid, mktime() - 2592000);
					}
					
						

				}else{
					
					$pwei = isset($data['EventKey']) ? $data['EventKey'] : '';
					$assistant_uid = 0;
					
					if(!empty($pwei)){
				  if(strpos($pwei, 'assistant') !== false){
							$assistant_uid = str_replace('assistant_','',str_replace('qrscene_','',$pwei));
					    unset($pwei);
						
						$assistantuid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id='$assistant_uid' LIMIT 1");
		
		$assistant_info = $this->App->findvar("SELECT id FROM `{$this->App->prefix()}user_assistant` WHERE uid='$assistant_uid'  and  assistant_id ='$thisuid' LIMIT 1");
		
        if($assistant_info > 0){
				//return array('店员已添加成功,请勿重复添加！', 'text');
				//$this->send(array('openid'=>$wecha_id),'assistant_fail_s');	
					exit;	
			}		
					if($assistantuid!=$thisuid && $assistantuid > 0){//添加店员
							$assistant = array();
							$assistant['uid'] = $assistantuid;
							$assistant['assistant_id'] = $thisuid;
							$assistant['wecha_id'] = $wecha_id;
							$assistant['status'] = 1;
							if($this->App->insert('user_assistant',$assistant)){
							$this->send(array('openid'=>$wecha_id),'assistant_success');
							//return array('店员添加成功！', 'text');
							
										}else{
												//return array('店员添加失败！', 'text');
												$this->send(array('openid'=>$wecha_id),'assistant_fail');
							
											}
						}
						
				  }
					}
					$this->Session->write('User.uid',$thisuid);
					setcookie(CFGH.'USER[UID]', $thisuid, mktime() + 2592000);
					
					//return array('您已经关注！', 'text');
				}
				
				if($assistant_uid > 0){//商家id(添加店员)
						$assistantuid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_id='$assistant_uid' LIMIT 1");
						$this->Session->write('User.assid',$assistantuid);
						setcookie(CFGH.'USER[ASSID]', $assistantuid, mktime() - 2592000);
					}
				
				
				//1、更改关注标识 表user_tuijian，user
				//2、更改用户资料
				//3、关注时间、关注排名等
				
				
				$rr = $this->_get_appid_appsecret();
				$appid = $rr['appid'];
				$appsecret = $rr['appsecret'];
				
				$access_token = $this->_get_access_token();
				if(!empty($access_token)){
				//获取用户信息
				$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$wecha_id;
				$con = $this->curlGet($url);
				$nickname = '';
				$sex = '';
				$city = '';
				$province = '';
				$headimgurl = '';
				$subscribe_time = '';
				if(!empty($con)){
					$json=json_decode($con);
					$subscribe = $json->subscribe;
					$nickname = isset($json->nickname)?$json->nickname : '';
					$sex = isset($json->sex)?$json->sex : '';
					$city = isset($json->city)?$json->city : '';
					$province = isset($json->province)?$json->province : '';
					$headimgurl = isset($json->headimgurl)?$json->headimgurl : '';
					$subscribe_time = isset($json->subscribe_time)?$json->subscribe_time : '';
			
					$this->Session->write('User.subscribe','1');
					setcookie(CFGH.'USER[SUBSCRIBE]', '1', mktime() + 2592000);
					$dd = array();
					$dd['is_subscribe'] = '1';
					$dd['subscribe_time'] = mktime();
					if(!empty($nickname)) $dd['nickname'] = $nickname;
					if(!empty($sex)) $dd['sex'] = $sex;
					if(!empty($city)) $dd['cityname'] = $city;
					if(!empty($province)) $dd['provincename'] = $province;
					if(!empty($headimgurl)) $dd['headimgurl'] = $headimgurl;
					if(!empty($subscribe_time)) $dd['subscribe_time'] = $subscribe_time;
					
					//检查是否存在该用户
					$ukey = $this->Session->read('User.ukey');
					if(empty($ukey)) $ukey = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
					if(!empty($ukey) && $ukey!=$wecha_id){//不是当前用户
						$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1";
						$uid = $this->App->findvar($sql);
					}else{
						$uid = $this->Session->read('User.uid');
						if(!($uid>0)){
							$uid = isset($_COOKIE[CFGH.'USER']['UID']) ? $_COOKIE[CFGH.'USER']['UID'] : '0';
							if(!($uid>0)){
									$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1";
									$uid = $this->App->findvar($sql);
									$this->Session->write('User.uid',$uid);
									setcookie(CFGH.'USER[UID]', $uid, mktime() + 2592000);
							}
						}
					}
					if($uid > 0){
						$this->App->update('user',$dd,'user_id',$uid);
						$counts = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE is_subscribe='1'");
						$this->App->update('user',array('subscribe_rank'=>$counts),'user_id',$uid); //更改排名
					}else{
						if(empty($rrL)){
						$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` WHERE type = 'basic' LIMIT 1";
						$rrL = $this->App->findrow($sql);
						}
						//添加用户
						$dd['user_name'] = $wecha_id;
						$dd['wecha_id'] = $wecha_id;
						$t = mktime();
						$dd['password'] = md5('A123456');
						//自动开通代理
						if($rrL['openfxauto']=='1'){
							$dd['user_rank'] = 9; //普通分销商
						}else{
							$dd['user_rank'] = 9;
						}
						$ip = Import::basic()->getip();
						$dd['reg_ip'] = $ip ? $ip : '0.0.0.0';
						$dd['reg_time'] = $t;
						$dd['reg_from'] = Import::ip()->ipCity($ip);
						$dd['last_login'] = mktime();
						$dd['last_ip'] = $dd['reg_ip'];
						$dd['active'] = 1;
						//2018/03/12 禁止微信端注册
						exit();
					// 	if($this->App->insert('user',$dd)){
					// 			$uid = $this->App->iid();
					// 				$assid = $this->Session->read('User.assid');	
					// 			if(!($assid>0)) $assid = isset($_COOKIE[CFGH.'USER']['ASSID']) ? $_COOKIE[CFGH.'USER']['ASSID'] : "0"; //店铺的来源ID
								
					// 			if($assid!=$uid && $assid > 0){//添加店员
					// 				$assistant = array();
					// 				$assistant['uid'] = $assid;
					// 				$assistant['assistant_id'] = $uid;
					// 				$assistant['wecha_id'] = $wecha_id;
					// 				$assistant['status'] = 1;
					// 				if($this->App->insert('user_assistant',$assistant)){
					// 					$this->send(array('openid'=>$wecha_id),'assistant_success');
					// 				}
					// 			}
								

					// 			//添加用户金钱总额表
					// 			$money = array();
					// 			$money['uid'] = $uid;
					// 			$this->App->insert('user',$money);
					// 			$counts = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE is_subscribe='1'");
					// 			$$counts = $counts+1;
					// 			$this->App->update('user',array('subscribe_rank'=>$counts),'user_id',$uid); //更改排名
								
					// 			$this->Session->write('User.username',$dd['user_name']);
					// 			$this->Session->write('User.uid',$uid);
					// 			$this->Session->write('User.active','1');
					// 			$this->Session->write('User.rank','9');
					// 			$this->Session->write('User.ukey',$dd['wecha_id']);
					// 			//$this->Session->write('User.pass',$dd['password']);
					// 			$this->Session->write('User.addtime',mktime());
					// 			//写入cookie
					// 			setcookie(CFGH.'USER[UKEY]',$dd['wecha_id'],mktime()+2592000);
					// 			setcookie(CFGH.'USER[UID]',$uid,mktime()+2592000);
								
					// 			$tid = $this->Session->read('User.tid');	
					// 			if(!($tid>0)) $tid = isset($_COOKIE[CFGH.'USER']['TID']) ? $_COOKIE[CFGH.'USER']['TID'] : "0"; //分享的来源ID
					// 			$to_wecha_id = $this->Session->read('User.to_wecha_id'); //来源ID
					// 			if(!($to_wecha_id>0)) $to_wecha_id = isset($_COOKIE[CFGH.'USER']['TOOPENID']) ? $_COOKIE[CFGH.'USER']['TOOPENID'] : "0";
	
					// 			if($tid!=$uid){//加入分享表
					// 				$dd = array();
					// 				//$url = $this->Session->read('User.url');
					// 				$dd['share_uid'] = $tid; //分享者uid
					// 				$dd['parent_uid'] = $to_wecha_id > 0 ? $to_wecha_id : $tid; //关注者分享ID
					// 				$dd['uid'] = $uid;
					// 				$puid = $dd['parent_uid'];
					// 				$duid = 0;
					// 				//正常来说一下代理不会执行到
					// 				/*if($puid > 0){
					// 					//检查是否是代理
					// 					$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$puid' LIMIT 1");
					// 					if($rank!='1'){
					// 						$duid = $puid;
					// 					}else{
					// 						//检查推荐的代理ID
					// 						$duid = $this->App->findvar("SELECT daili_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$puid' LIMIT 1");
					// 					}
					// 				}*/
					// 				//$dd['url'] = $url;
					// 				$dd['addtime'] = mktime();
					// 				if($this->App->insert('user_tuijian',$dd)){ //添加推荐用户
					// 					   if($dd['share_uid'] > 0){
					// 							$id = $dd['share_uid'];
					// 							$sql = "UPDATE `{$this->App->prefix()}user` SET `share_ucount` = `share_ucount`+1 WHERE user_id = '$id'";
					// 							$this->App->query($sql);
					// 					   }
					// 				}
					// 				unset($dd);
					// 			} //end if
								
					// 			//添加地址
					// 			if(!empty($city) && !empty($province)){
					// 				$sql = "SELECT region_id FROM `{$this->App->prefix()}region` WHERE region_name LIKE '%$city%' LIMIT 1";
					// 				$cityid = $this->App->findvar($sql);
					// 				$sql = "SELECT region_id FROM `{$this->App->prefix()}region` WHERE region_name LIKE '%$province%' LIMIT 1";
					// 				$provinceid = $this->App->findvar($sql);
					// 				if($cityid > 0 && $provinceid>0){
					// 					$dd = array();
					// 					$dd['consignee'] = $nickname;
					// 					$dd['user_id'] = $uid;
					// 					$dd['sex'] = $sex;
					// 					$dd['city'] = $cityid;
					// 					$dd['province'] = $provinceid;
					// 					$dd['country'] = 1;
					// 					$dd['is_own'] = 1;
					// 					$this->App->insert('user_address',$dd);
					// 					unset($dd);
					// 				}
					// 			}//end if
								
								
					// 			 //自动开通分销
					// 			if($rrL['openfxauto']=='1'){  
					// 				$dd = array();
					// 				$ss = array();
					// 				$ss[] = $uid;
					// 				$dd['uid'] = $uid;
					// 				$dd['p1_uid'] = 0;
					// 				$dd['p2_uid'] = 0;
					// 				$dd['p3_uid'] = 0;
									
					// 				$p1_uid = $this->return_daili_uid($uid);
								
					// 				if($p1_uid > 0 && !in_array($p1_uid,$ss)){
					// 					$dd['p1_uid'] = $p1_uid;
					// 					$p2_uid = $this->return_daili_uid($p1_uid);
					// 					$ss[] = $p1_uid;
					// 					$ss[] = $uid;
					// 					if($p2_uid > 0 && !in_array($p2_uid,$ss)){
					// 						$dd['p2_uid'] = $p2_uid;
					// 						$p3_uid = $this->return_daili_uid($p2_uid);
					// 						$ss[] = $p2_uid;
					// 						if($p3_uid > 0 && !in_array($p3_uid,$ss)){
					// 							$dd['p3_uid'] = $p3_uid;
					// 						}
					// 					}
					// 				}
					// 				//
					// 				$sql = "SELECT id FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid='$uid' LIMIT 1";
					// 				$id = $this->App->findvar($sql);
									
					// 				if($id > 0){
					// 					$this->App->update('user_tuijian_fx',$dd,'id',$id);
					// 				}else{
					// 					$this->App->insert('user_tuijian_fx',$dd);
					// 				}
					// 				unset($dd);
									
					// 				$mm = array();
					// 				$mm['uid'] = $uid;
					// 				$mm['fenrun'] = 0;
					// 				$mm['yongjin'] = 0;
					// 				$mm['tuiguang'] = 0;
					// 				$mm['yinlian'] = 0;
					// 				$mm['yinlian_h5'] = 0;
					// 				$mm['weixin'] = 0;
					// 				$mm['baidu'] = 0;
					// 				$mm['jingdong'] = 0;
					// 				$mm['duanxin'] = 0;
					
					// 	$sql = "SELECT id FROM `{$this->App->prefix()}user_moneys` WHERE uid='$uid' LIMIT 1";
					// $id = $this->App->findvar($sql);
					
					// if(empty($id)){
					// $this->App->insert('user_moneys',$mm);
					// }
					
					// unset($mm);
					
					
					// 			}//end if 开通分销
								
					// 	} //end insert
	
					} //end if uid>0
				
			
					//增加关注积分
					$sql = "SELECT tuijiannum,ticheng360_2 FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
					$tjj = $this->App->findrow($sql);
                    $tuijiannum = $tjj['tuijiannum'];  
					$dixin360 = $tjj['ticheng360_2']; 
					unset($tjj);
                          
					//关注送积分	
					if($dixin360 > 0 && $uid > 0){
					 	$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints` = `mypoints`+$dixin360,`points_ucount` = `points_ucount`+$dixin360 WHERE user_id = '$uid'";
						$this->App->query($sql);
						
						$dd = array();
						$dd['time'] = mktime();
						$dd['points'] = $dixin360;
						$dd['uid'] = $uid;
						$dd['subuid'] = 0;
						$dd['changedesc'] = '关注送积分';
						$dd['thismonth'] = date('Y-m-d',mktime());
						$this->App->insert('user_point_change',$dd);
						unset($dd);
						
						$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
						$this->send(array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>'','points'=>$dixin360),'guanzhu_use');
						$pwecha_id = '';
					}
					              
					if($tuijiannum > 0){
						//查找推荐用户人
						$uid = $this->Session->read('User.uid');
						if(!($uid > 0)){
							$uid = isset($_COOKIE[CFGH.'USER']['UID']) ? $_COOKIE[CFGH.'USER']['UID'] : "0";
							if(!($uid > 0)){
								$uid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1");
							}
						}
						if($uid > 0){
							$purt = $this->App->findrow("SELECT ut.parent_uid,u.wecha_id FROM `{$this->App->prefix()}user_tuijian` AS ut LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = ut.parent_uid WHERE ut.uid='$uid' LIMIT 1");
							$puid = isset($purt['parent_uid']) ? $purt['parent_uid'] : '0';
							$pwecha_id = isset($purt['wecha_id']) ? $purt['wecha_id'] : '';
                            //检查积分是否返了
                            $sql = "SELECT time FROM `{$this->App->prefix()}user_point_change` WHERE uid='$puid' AND subuid='$uid' LIMIT 1";
							$t = $this->App->findvar($sql);
							$run = 'true';
							if(empty($t)){
								//$run = 'true';
							}else{
								if((mktime()-$t) > 60){
									//$run = 'true';
								}else{
									$run = 'false';
								}
							}
							if($puid > 0 && $run=='true'){ //派送积分  推荐的用户
								$dd = array();
								$dd['time'] = mktime();
								$dd['points'] = $tuijiannum;
								$dd['uid'] = $puid;
								$dd['subuid'] = $uid;
								$dd['changedesc'] = '推荐关注送积分';
								$dd['thismonth'] = date('Y-m-d',mktime());
								$this->App->insert('user_point_change',$dd);
								
								//积分总计、关注数叠加 方便排序及查找
								$tuijiannum = intval($tuijiannum);
								//if(!($tuijiannum>0)) $tuijiannum = 1;
								$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints` = `mypoints`+$tuijiannum,`points_ucount` = `points_ucount`+$tuijiannum,`guanzhu_ucount` = `guanzhu_ucount`+1 WHERE user_id = '$puid' AND is_subscribe='1'";
								$this->App->query($sql);
								
								//父类UID	
								$this->send(array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>$nickname,'points'=>$tuijiannum),'guanzhu');
								
								//查找上一级代理
								$dts = $this->App->findcol("SELECT p1_uid,p2_uid,p3_uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid = '$puid' LIMIT 1");
								$duid = $dts[0];
								$duid2 = $dts[1];
								$duid3 = $dts[2];
								if($duid > 0){
										$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$duid' LIMIT 1");
										$this->send(array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>$nickname),'guanzhudaili');
								}
								if($duid2 > 0){
										//$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$duid2' LIMIT 1");
										//$this->send(array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>$nickname),'guanzhudaili2');
								}
								if($duid3 > 0){
										//$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$duid3' LIMIT 1");
										//$this->send(array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>$nickname),'guanzhudaili3');
								}
								//信息推送
							}//end if
						} //end if
					} //end if
					/**********************************************/
				
					$token = $this->token;
					$keyword = $this->App->findvar("SELECT keyword FROM `{$this->App->prefix()}wxkeyword` WHERE type='guanzhu' LIMIT 1");
					if(!empty($keyword)){
					
						return $this->keyword($keyword);
						
						//查找图文
						/*$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE keyword='$keyword' LIMIT 1";
						$rts = $this->App->findrow($sql);
						if(empty($rts)){
							return array('商家暂未有设置关注回复，请联系商家设置', 'text');
						}else{
							$type = $rts['type'];
							if($type=="txt"){ //文本信息
								return array($rts['content'], 'text');
							}else{
								  
								//回复图文信息
								$url = $rts['art_url'];
								$id = $rts['article_id'];
								if(empty($url)) $url = SITE_URL.'m/art.php?id='.$id;
								$img = SITE_URL.$rts['article_img'];
								$about = $rts['about'];
								$title = $rts['article_title'];
								
								$data['title'] = $title;
								$data['keyword'] = $about;
								$data['picurl'] = $img;
								$data['url'] = $url;
								return array(array(array($data['title'], $data['keyword'], $data['picurl'], $data['url'])), 'news');
							}
						}*/
					}else{
							//这是回复推荐人的信息
							if($uid > 0){
								$gzcount = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` LIMIT 1");
								$gzcount = $gzcount*5+20000;
								if($puid > 0){
									$nickname = $this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id = '$puid' LIMIT 1");
									if(empty($nickname)) $nickname = $this->get_site_name();
									$str = '来自好友【'.$nickname.'】的推荐成为第【'.$gzcount.'】位会员，立即关注，抢夺东家地盘！';
								}else{
									$str = '来自【'.$this->get_site_name().'】的推荐成为第【'.$gzcount.'】位会员，立即关注，抢夺东家地盘！';
								}
								return array($str, 'text');
							}
					}
					
/*					if ($follow_data['home'] == 1) {
						return $this->keyword($follow_data['keyword']);
					} else {
						return array(html_entity_decode($follow_data['content']), 'text');
					}*/
				   
					
				}else{//end if
					return array('获取微信用户信息失败，确保是否支持CURL远程获取！', 'text');
				}
                        }
            } elseif ('unsubscribe' == $data['Event']) { //取消关注
				//释放cookie 释放session 更改关注标记
				$wecha_id = $this->wecha_id; //用户openid
				$this->App->update('user',array('is_subscribe'=>'0'),'wecha_id',$wecha_id); //更改排名
				$this->Session->write('User.subscribe',null);
				unset($_SESSION['User']['subscribe']);
				if(isset($_COOKIE[CFGH.'USER']['SUBSCRIBE'])) setcookie(CFGH.'USER[SUBSCRIBE]',"",mktime()-2592000); 
				unset($_COOKIE[CFGH.'USER']['SUBSCRIBE']);
				
				//改变取消关注的数据
				$sql = "SELECT tuijiannum,ticheng360_2 FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
				$tjj = $this->App->findrow($sql);
				$tuijiannum = $tjj['tuijiannum'];  
				$dixin360 = $tjj['ticheng360_2'];
				unset($tjj);
				
				$uid = $this->Session->read('User.uid');
				if(!($uid > 0)){
					$uid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1");
				}
				
				if($uid > 0 && $dixin360 > 0){
					$dd = array();
					$dd['time'] = mktime();
					$dd['points'] = -$dixin360;
					$dd['uid'] = $uid;
					$dd['changedesc'] = '取消关注减积分';
					$dd['thismonth'] = date('Y-m-d',mktime());
					$this->App->insert('user_point_change',$dd);
					
					$dixin360 = intval(-$dixin360);
					$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints` = `mypoints`+$dixin360,`points_ucount` = `points_ucount`+$dixin360 WHERE user_id = '$uid' LIMIT 1";
					$this->App->query($sql);
					unset($dd);
				}
				
				if($tuijiannum > 0){
					//查找推荐用户人
					$uid = $this->Session->read('User.uid');
					if(!($uid > 0)){
						$uid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1");
					}
					if($uid > 0){
						//父类UID
						$purt = $this->App->findrow("SELECT ut.parent_uid,u.wecha_id FROM `{$this->App->prefix()}user_tuijian` AS ut LEFT JOIN `{$this->App->prefix()}user` AS u ON u.user_id = ut.parent_uid WHERE ut.uid='$uid' LIMIT 1");
						$puid = isset($purt['parent_uid']) ? $purt['parent_uid'] : '0';
						$pwecha_id = isset($purt['wecha_id']) ? $purt['wecha_id'] : '';
						if($puid > 0){ //派送积分
							$dd = array();
							$dd['time'] = mktime();
							$dd['points'] = -$tuijiannum;
							$dd['uid'] = $puid;
							$dd['subuid'] = $uid;
							$dd['changedesc'] = '用户取消关注减积分';
							$dd['thismonth'] = date('Y-m-d',mktime());
							$this->App->insert('user_point_change',$dd);
							
							//积分总计、关注数叠加 方便排序及查找
							$tuijiannum = intval(-$tuijiannum);
							$sql = "UPDATE `{$this->App->prefix()}user` SET `mypoints` = `mypoints`+$tuijiannum,`points_ucount` = `points_ucount`+$tuijiannum,`guanzhu_ucount` = `guanzhu_ucount`-1 WHERE user_id = '$puid'";
							$this->App->query($sql);
							
							
							//$this->send(array('openid'=>$pwecha_id),'guanzhu');
							//信息推送

						}
					}
				}
							
				
            } elseif ($data['Event'] == 'LOCATION') { //自动获取位置回复
                //return array('LOCATION', 'text');
            }
        }
		
		return $this->keyword($data['Content']);
	} 
	
	function requestdata($field)
    {
        $data['year'] = date('Y');
        $data['month'] = date('m');
        $data['day'] = date('d');
        $data['token'] = $this->token;
       /* $mysql = M('Requestdata');
        $check = $mysql->field('id')->where($data)->find();
        if ($check == false) {
            $data['time'] = time();
            $data[$field] = 1;
            $mysql->add($data);
        } else {
            $mysql->where($data)->setInc($field);
        }*/
    }
	
	function get_user_parent_uid(){
		$uid = $this->Session->read('User.uid');
		if(!($uid > 0)) return 0;
		
		$t = Common::_return_px();
		$cache = Import::ajincache();
		$cache->SetFunction(__FUNCTION__);
		$cache->SetMode('sitemes'.$t);
		$fn = $cache->fpath(array('0'=>$uid));
		if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				include($fn);
		}
		else
		{
				$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
				$rt = $this->App->findvar($sql);
				$cache->write($fn, $rt,'rt');
	   }
	   return $rt;
	}
	
	function return_daili_uid($uid=0,$k=0){
		if(!($uid > 0)){
			return 0;
		}
		$puid = 0;
		for($i=0;$i<20;$i++){
				$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
				$p = $this->App->findvar($sql);
				if($p > 0){
					$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$p' LIMIT 1";
					$rank = $this->App->findvar($sql);
					if($rank != 1){
						$puid = $p;
						break;
					}else{
						$uid = $p;
					}
				}
		}
		return $puid;
	}
	
	function keyword($keyword=''){
		if(!(empty($keyword))){
			$wecha_id = $this->wecha_id;
			switch($keyword){
				case '推广链接':
					$RL = $this->App->findrow("SELECT user_id,is_subscribe FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1");
					$issubscribe = isset($RL['is_subscribe']) ? $RL['is_subscribe'] : '0';
					$uid = isset($RL['user_id']) ? $RL['user_id'] : '0';
					unset($RL);
					if($issubscribe=='0'){
						return array('请先关注抢占分享地盘！', 'text');
						exit;
					}
					$thisurl = SITE_URL."m/in.php?tid=".$uid;
					return array('复制链接发送给朋友赚钱:'.$thisurl, 'text');
					break;
				case 'qr'://生成二维码
				case '我的二维码'://生成二维码
					$yuming = str_replace(array('www','.',),'',$_SERVER["HTTP_HOST"]);
					if(!empty($yuming)) $yuming = $yuming.DS;

					if(!empty($wecha_id)){
						//获取配置信息
						$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1";
						$peizhi = $this->App->findrow($sql);
						
						$tb = 'markimg';
						
						$sql = "SELECT is_subscribe,user_rank,user_id,quid,headimgurl,nickname FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1";
						$RT = $this->App->findrow($sql);
						if($RT['is_subscribe']=='0'){
							return array('未关注，请重新关注！', 'text');
							exit;
						}
						
						if($RT['user_rank']=='1'){
							return array('您购买后系统会自动生成您的推广名片！', 'text');
							exit;
						}
						$sql1 = "SELECT status FROM `{$this->App->prefix()}user_bank` WHERE uid=".$RT['user_id']." LIMIT 1";
						$RTS = $this->App->findvar($sql1);
						if(!$RTS){
							return array('实名认证后系统会自动生成您的推广名片！', 'text');
							exit;
						}
						
						
						$access_token = $this->_get_access_token();
						
						if($RT['user_rank']=='1'){
							//生成推荐人二维码
							$tb = 'markimg2';
							$uid = isset($RT['user_id']) ? $RT['user_id'] : '0';
							$puid = $this->return_daili_uid($uid);//返回上级分销商
							
							if($puid > 0){
								$sql = "SELECT is_subscribe,user_rank,user_id,quid,headimgurl,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$puid' LIMIT 1";
								$RT = $this->App->findrow($sql);
							}else{//生成官网二维码
								$tb = 'markimg3';
								$this->send(array('openid'=>$wecha_id,'appid'=>'','appsecret'=>'','nickname'=>''),$tb);
								//$sql = "SELECT site_url FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1";
								//$f3 = $this->App->findvar($sql);
								$f3 = $peizhi['site_url'];
								if(!empty($f3)) $f3 = SYS_PATH.$f3;
								if(file_exists($f3)){
											$type = "image";
											$filedata = array("media"=>"@".$f3);
											$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
											$result = $this->https_request($url,$filedata);
											$json = json_decode($result);
											$media_id = $json->media_id;
											if(!empty($media_id)) $this->send(array('openid'=>$wecha_id,'appid'=>'','appsecret'=>'','media_id'=>$media_id),'markimgsend');
											exit;
								}
								
								return array('您是由'.$this->get_site_name().'推荐，暂无设置微名片！', 'text');
								exit;
							}
							
							
						}
						
						$uid = $RT['user_id'];
						$quid = $RT['user_id'];
						$headimgurl = $RT['headimgurl'];
						$nickname = $RT['nickname'];
						if(empty($nickname)) $nickname = $this->get_site_name();
						if(empty($headimgurl)){
							//return array('请先上传您的微信头像后生成吧！', 'text');
							//exit;
						}
						
						unset($RT);
//					
//							$sql = "SELECT MAX(quid) FROM `{$this->App->prefix()}user` LIMIT 1";
//							$quid = $this->App->findvar($sql);
//							$quid = intval($quid)+1;
//							
//							$sql = "UPDATE `{$this->App->prefix()}user` SET `quid` = `$quid` WHERE user_id = '$uid'";
//												$this->App->query($sql);
//												
							//$this->App->update('user',array('quid'=>$quid),'user_id',$uid);
						
						
						$fop = Import::fileop();
						$tis = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$uid.DS.'cache'.$quid.'.txt';
						if(file_exists($tis) && (mktime() - filemtime($tis) < 60)){
							return;
							exit;
						}
						if(!file_exists($tis) || (mktime() - filemtime($tis) > 60)){
							$fop->checkDir($tis);
							@file_put_contents($tis,"run");
						}
						
						$access_token = $this->_get_access_token();

						//提示生成
						$this->send(array('openid'=>$wecha_id,'appid'=>'','appsecret'=>'','nickname'=>''),$tb);
						$f3 = SYS_PATH.'photos'.DS.$yuming.'qcode'.DS.$uid.DS.'ms'.$quid.'.jpg';//原图
						if(file_exists($f3)){
									$type = "image";
									$filedata = array("media"=>"@".$f3);
									$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
									$result = $this->https_request($url,$filedata);
									$json = json_decode($result);
									$media_id = $json->media_id;
									if(!empty($media_id)) $this->send(array('openid'=>$wecha_id,'appid'=>'','appsecret'=>'','media_id'=>$media_id),'markimgsend');
									exit;
						}
																									
						$f = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$uid.DS.'s'.$quid.'.jpg';
						if(!file_exists($f)){
													
								//生成二维码
								$data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$quid.'}}}';
								//$data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_str": "'.$wecha_id.'"}}}';
								$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token,$data,10);
								$json=json_decode($rt);
								$ticket = $json->ticket;
								$url = $json->url;
								if(!empty($ticket)){
									$str = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
									$img = file_get_contents($str);
									if(empty($img)){ $img = Import::crawler()->curl_get_con($str); }
									if(!empty($img)){
										$fop->checkDir($f);
										@file_put_contents($f,$img);
									}
			
								}
						}
						if(!file_exists($f)){
							return array('生成图片失败，请联系网站管理员解决此问题！', 'text');
							exit;
						}
						
						//二维码坐标
						$ewm_xy = isset($peizhi['ewm_xy']) ? $peizhi['ewm_xy'] : '';
						if(empty($ewm_xy)){
							//$ewm_xy = '55,26|265,64|152,410';
							$ewm_xy = '0,0|0,0|0,0';
						}
						$xyy = array();
						$xy = explode('|',$ewm_xy);
						foreach($xy as $it){
							$xyy[] = explode(',',$it);
						}
						unset($xy,$ewm_xy);
						
						$f2 = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$uid.DS.'m'.$quid.'.jpg';//二维码
						$fop->checkDir($f2);
						$imgobj = Import::img();
						$imgobj->thumb($f,$f2,360,360);
						
						$sf = SYS_PATH.'photos'.DS.$yuming.'codebg.jpg';//原图背景
						if(file_exists($sf)==false){
							$sf = SYS_PATH.'photos'.DS.'codebg.jpg';//原图背景
						}
						$f3 = SYS_PATH.'photos'.DS.$yuming.'qcode'.DS.$uid.DS.'ms'.$quid.'.jpg';//原图
						$fop->checkDir($f3);
						$imgobj->thumb($sf,$f3,720,1150);
						
						$t = 'false';
						$t = $this->mark_img($f3,$f2,$xyy[2][0],$xyy[2][1]);
						if($t=='true'){
							//头像
							$t = "false";
							$img = file_get_contents($headimgurl);
							if(empty($img)){ $img = Import::crawler()->curl_get_con($headimgurl); } 
							$f4 = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$uid.DS.'mh'.$quid.'.jpg';//头像
							if(!empty($img)){
								$fop->checkDir($f4);
								@file_put_contents($f4,$img);
								if(file_exists($f4)){
									$f5 = SYS_PATH.'cache'.DS.$yuming.'qcode'.DS.$uid.DS.'mht'.$quid.'.jpg';//小头像
									$fop->checkDir($f5);
									$imgobj->thumb($f4,$f5,110,110);
									if(file_exists($f5)){
										if($this->mark_img($f3,$f5,$xyy[0][0],$xyy[0][1])=="true"){
											if($this->mark_txt($f3,$nickname,$xyy[1][0],$xyy[1][1])=="true"){
												$type = "image";
												$filedata = array("media"=>"@".$f3);
												$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
												$result = $this->https_request($url,$filedata);
												$json = json_decode($result);
												$media_id = $json->media_id;
												if(!empty($media_id)) $this->send(array('openid'=>$wecha_id,'appid'=>'','appsecret'=>'','media_id'=>$media_id),'markimgsend');
											}
										}
									}
								}
							}
	
						}else{
							return array('生成图片失败，请联系网站管理员解决此问题！', 'text');
							exit;
						}
					}
					break;
			}
			
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE keyword LIKE '%$keyword%' ORDER BY vieworder ASC LIMIT 8";
			$rt = $this->App->find($sql);
          
			if(!empty($rt)){
				if(count($rt)==1){
					$rts = $rt[0];
					if(!empty($rts)){
						$ty = $rts['type'];
						if($ty=='txt'){
							if(!empty($rts['content'])){
								$uid = $this->Session->read('User.uid');
								if(!($uid > 0)){
									$uid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}user` WHERE wecha_id='$wecha_id' LIMIT 1");
								}
								$puid = $this->App->findvar("SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1");
								$nickname = $this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id = '$puid' LIMIT 1");
								if(empty($nickname)) $nickname = $this->get_site_name();
								$gzcount = $this->App->findvar("SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` LIMIT 1");
								$gzcount = $gzcount*5+750;
								$ss = sprintf($rts['content'],$nickname,$gzcount);
								return array($ss, 'text');
							}else{
								return array("请在后台先编辑好内容", 'text');
							}
						}else{
								$data['title'] = $rts['article_title'];
								$data['keyword'] = $rts['about'];
								$data['picurl'] = empty($rts['article_img']) ? SITE_URL.'m/images/ico-success.png' : SITE_URL.$rts['article_img'];
								$data['url'] = empty($rts['art_url']) ? SITE_URL.'m/art.php?id='.$rts['article_id'] : $rts['art_url'];
								return array(array(array($data['title'], $data['keyword'], $data['picurl'], $data['url'])), 'news');
						}
					}
				}else{
						$result = array();
						$k=0;
						foreach($rt as $row){
							if($row['type']=='txt') continue;
							$result[$k][] = $row['article_title'];
							$result[$k][] = $row['about'];
							$result[$k][] = empty($row['article_img']) ? SITE_URL.'m/images/ico-success.png' : SITE_URL.$row['article_img'];
							$result[$k][] = empty($row['art_url']) ? SITE_URL.'m/art.php?id='.$row['article_id'] : $row['art_url'];
							++$k;
						}
						return array($result, 'news');
					
				}//end if
				
			}
		}
		//
	}
	
	function https_request($url, $data = null)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	function mark_img($image_dir='',$formname = '',$x=0,$y=0){
		$iinfo=getimagesize($image_dir);
		$nimage=imagecreatetruecolor($iinfo[0],$iinfo[1]);
		$white=imagecolorallocate($nimage,255,255,255);
		$black=imagecolorallocate($nimage,0,0,0);
		$red=imagecolorallocate($nimage,255,0,0);
		
		$simage =imagecreatefromjpeg($image_dir);

		imagecopy($nimage,$simage,0,0,0,0,$iinfo[0],$iinfo[1]);
			
		$inn=getimagesize($formname);  

		$in=@imagecreatefromJPEG($formname);

		$wh = imagecolorallocate($in, 255, 255, 255); 
		imagecolortransparent($in,$wh);   
		imagecopy($nimage,$in,$x,$y,0,0,$inn[0],$inn[1]);
		imagedestroy($in);

		imagejpeg($nimage,$image_dir);
		
		return "true";
	}
	
	function mark_txt($image_dir='',$formname = '',$x=0,$y=0){
		$image_dir = Import::gz_iconv()->ec_iconv('UTF8', 'GB2312',$image_dir);
		$iinfo=getimagesize($image_dir);
		$nimage=imagecreatetruecolor($iinfo[0],$iinfo[1]);
		$white=imagecolorallocate($nimage,255,255,255);
		$black=imagecolorallocate($nimage,131,24,30);
		$red=imagecolorallocate($nimage,255,0,0);
		$ye = imagecolorallocate($nimage,253,229,0);
		
		$font = SYS_PATH.'data'.DS.'simhei.ttf'; //定义字体  
			
		$simage =imagecreatefromjpeg($image_dir);
		
		imagecopy($nimage,$simage,0,0,0,0,$iinfo[0],$iinfo[1]);
		
		//imagestring($nimage,5,300,50,$formname,$black);
		imagettftext($nimage,24,0,$x,$y,$ye, $font, $formname);
		imagejpeg($nimage,$image_dir);
		

		return "true";
	}
	
	private function getRecognition($id)
    {
        return false;
    }
	
	function curlPost($url, $data,$showError=1){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if($showError=='10'){ return $tmpInfo; exit;}
		
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if (intval($js['errcode']==0)){
				return array('rt'=>true,'errorno'=>0,'media_id'=>$js['media_id'],'msg_id'=>$js['msg_id']);
			}else {
				if ($showError){
					return array('rt'=>true,'errorno'=>10,'msg'=>'发生了Post错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
				}
			}
		}
	}

	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		if(empty($temp)) $temp = Import::crawler()->curl_get_con($url);
		return $temp;
    }
}
?>