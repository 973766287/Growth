<?php
class ApiController extends Controller{
	
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
					$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
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
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
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
	
	function send($rts=array(),$type=""){
		if(empty($rts['openid'])) return;
		
		/*$appid = isset($rts['appid']) ? $rts['appid'] : "";
		$appsecret = isset($rts['appsecret']) ? $rts['appsecret'] : "";
		if(empty($appid) || empty($appsecret)){
			$sql = "SELECT appid,appsecret,is_oauth,winxintype FROM `{$this->App->prefix()}wxuserset` WHERE id='1'";
			$rt = $this->App->findrow($sql);
			$appid = $rt['appid'];
			$appsecret = $rt['appsecret'];
		}
		$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
		$json=json_decode($this->curlGet($url_get));
		if (!$json->errmsg){
			$data = $this->_get_send_con($rts,$type);
			if(!empty($data)){
				$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$json->access_token,$data,0);
				if($rt['rt']==false){
					//操作失败
				}else{
					
				}
		    }
		}*/
		
		$access_token = $this->_get_access_token();
		$data = $this->_get_send_con($rts,$type);
		$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data,0);
	}
	
	function _get_send_con($rt=array(),$ty=''){
		$data = array();
		switch($ty){
			case 'share':
			$openid = $rt['openid'];
			if(empty($rt['nickname'])){
				$str = '新增一位游客啦;\n\n服务类型：分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：1位好友浏览您的分享等于1次【邀请】,他还需要关注您才有积分收入哦!';
			}else{
				$str = '新增一位好友['.$rt['nickname'].']啦;\n\n服务类型：分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：1位好友浏览您的分享等于1次【邀请】,他还需要关注您才有积分收入哦!';
			}
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "邀请好友服务提醒", "description": "'.$str.'","url":"'.ADMIN_URL.'user.php?act=myshare"}]}}';
			break;
			case 'sharedaili': //代理下面的用户分享通知代理
			$openid = $rt['openid'];
			$str = '下级好友['.$rt['nickname'].']浏览您的分享啦;\n\n服务类型：下级用户分享已被浏览\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：他还需要关注并且购买您才有收入哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "邀请好友服务提醒", "description": "'.$str.'","url":"'.ADMIN_URL.'user.php?act=myshare"}]}}';
			break;
                    case 'tjmember': //支付返积分
			$openid = $rt['openid'];
                        $rank_id = $rt['rank_id'];
                        if($rank_id==12){
                            $rank_name="品牌推广商";
                        }elseif($rank_id==11){
                              $rank_name="微店代理商";
                        }elseif($rank_id==10){
                              $rank_name="黄金会员";
                        }else{
                            $rank_name="会员";
                        }
			//$str = '下级用户已提交会费,新增余额:+'.$rt['money'].';\n\n服务类型：下级入会返现金\n提交时间：'.date('Y-m-d H:i:s');
			//$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "下级入会返现金服务提醒", "description": "'.$str.'","url":"'.ADMIN_URL.'daili.php?act=monrydeial"}]}}';
			   $con="【账户新增推广升级奖励】+".$rt['money']."元 
【余额变动时间】". date('Y-m-d H:i:s') ;
			   $data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
                            //$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "服务申请提交成功", "description": "'.$str.'","url":"'.str_replace('api','m',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
                        break;
			case 'payreturnpoints': //支付返积分
			$openid = $rt['openid'];
			$str = '订单['.$rt['order_sn'].']已支付,新增积分:+'.$rt['points'].';\n\n服务类型：消费返积分\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "消费返积分服务提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'payreturnpoints_parentuid': //支付返积分
			$openid = $rt['openid'];
			$str = '下级用户订单已支付,新增积分:+'.$rt['points'].';\n\n服务类型：下级消费返积分\n提交时间：'.date('Y-m-d H:i:s').'\n\n备注：消费越多,积分越多,用积分可赢大奖哦!';
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "下级消费返积分服务提醒", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=mypoints"}]}}';
			break;
			case 'payreturnmoney': //支付返佣金
			$openid = $rt['openid'];
                               $con="【账户新增刷卡分润】+".$rt['money']."元 
【新增金额来源】队员刷卡返润 
【余额变动时间】". date('Y-m-d H:i:s');
			  $data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
                            break;
							
							
							case 'shimingreturnmoney': //实名认证返佣金
			$openid = $rt['openid'];
                               $con="【账户新增佣金】+".$rt['money']."元 
【新增金额来源】队员注册返润 
【余额变动时间】". date('Y-m-d H:i:s');
			  $data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
                            break;
							
								case 'firstreturnmoney': //首次支付返佣金
			$openid = $rt['openid'];
                               $con="【账户新增佣金】+".$rt['money']."元 
【新增金额来源】队员首次刷卡返润 
【余额变动时间】". date('Y-m-d H:i:s');
			  $data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
                            break;
							
							
	
			case 'payusereturnmoney': //购买者返佣金
			$openid = $rt['openid'];
		
			  $data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
                            break;
			case 'buymess': //需要购买，开通分销通知
			$openid = $rt['openid'];
			$str = '['.$rt['nickname'].'],购买产品成为合伙人赚分红,您还需要至少购买一件产品哦！\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "购买产品成分销商赚佣金", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php"}]}}';
			break;
			case 'sendgift': //
			$openid = $rt['openid'];
			$str = '['.$rt['nickname'].'],您已免费获取一张价值980元的消费卡！\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "赠送980元消费卡", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=mygift"}]}}';
			break;
			//case 'orderconfirm': //
//			$openid = $rt['openid'];
//			$str = '订单已成功提交,请尽快付款!\n\n提交时间：'.date('Y-m-d H:i:s');
//			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "订单确认通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
//			break;
			case 'orderconfirm_toshop': //通知商家
			
			$openid = $rt['openid'];
            $con="【店铺收款】: ".$rt['money']."元
			【订单号】: ".$rt['order_sn']."
			【余额变动时间】: ". date('Y-m-d H:i:s');
			  $data = '{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$con.'"}}';
                            break;
							
		
			case 'payconfirm': //
			$openid = $rt['openid'];
			$str = '订单已成功支付!\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "订单已支付通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
			break;
			case 'payconfirm_vg': //
			$openid = $rt['openid'];
			$str = '['.$rt['nickname'].'],订单已成功支付,'.(!empty($rt['goods_sn']) ? '卡号:'.$rt['goods_sn'].',' : '').'卡密:'.$rt['goods_pass'].',请注意查收!\n\n提交时间：'.date('Y-m-d H:i:s');
			$data='{"touser": "'.$openid.'","msgtype": "news","news": {"articles": [{"title": "订单已支付通知服务", "description": "'.$str.'","url":"'.str_replace(array('paywx/','wxpay/'),'',ADMIN_URL).'user.php?act=orderlist"}]}}';
			break;
			
			case 'renzheng': //
			$openid = $rt['openid'];
			$str = '实名认证中...';
			$data='{"touser": "'.$openid.'","msgtype": "text","text": {"content":"'.$str.'"}}';
			
		
			break;
			
			
		}
		
		return $data;
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
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
   }

}
?>