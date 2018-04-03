<?php

//后台菜单
//这里的菜单排放顺序一定要注意
$menu[1] = array(
    'en_name' => '1',
    'big_key' => 's01',
    'small_mod' => '基本设置',
    'big_mod' => '管理首页',
    'sub_mod' => array(
        array('name' => '信息设置', 'en_name' => '101', 'url' => 'systemconfig.php?type=basic'),
        array('name' => '站点SEO', 'en_name' => '102', 'url' => 'systemconfig.php?type=seo'),
     //   array('name' => '参数设置', 'en_name' => '103', 'url' => 'systemconfig.php?type=arg'),
     //   array('name' => '分销设置', 'en_name' => '104', 'url' => 'weixin.php?type=userconfig'),
      //  array('name'=>'二维码设置','en_name'=>'104','url'=>'weixin.php?type=erweimaset'),
        array('name' => '清空缓存', 'en_name' => '105', 'url' => 'systemconfig.php?type=clear'),
    )
);



$menu[4] = array(
    'en_name' => '4',
    'big_key' => 's01',
    'small_mod' => '会员升级',
    'big_mod' => '管理首页',
    'sub_mod' => array(
        array('name' => '会员升级选项', 'en_name' => '401', 'url' => 'yuyue.php?type=baominglist'),
        array('name' => '钻石会员升级订单', 'en_name' => '402', 'url' => 'yuyue.php?type=bmorderlist&t=4&s=1'),
        array('name' => '皇冠会员升级订单', 'en_name' => '403', 'url' => 'yuyue.php?type=bmorderlist&t=5&s=1'),
        array('name' => '投资合伙人升级订单', 'en_name' => '404', 'url' => 'yuyue.php?type=bmorderlist&t=6&s=1'),
  
    )
);



$menu[3] = array(
    'en_name' => '3',
    'big_key' => 's02',
    'small_mod' => '数据库设置',
    'big_mod' => '系统设置',
    'sub_mod' => array(
        array('name' => '备份数据库', 'en_name' => '301', 'url' => 'backdb.php?type=backdb'),
        array('name' => '还原数据库', 'en_name' => '302', 'url' => 'backdb.php?type=restoredb'),
        array('name' => '数据表优化', 'en_name' => '303', 'url' => 'backdb.php?type=youhua')
    )
);

$menu[5] = array(
    'en_name' => '5',
    'big_key' => 's02',
    'small_mod' => '管理员设置',
    'big_mod' => '系统设置',
    'sub_mod' => array(
        array('name' => '管理员列表', 'en_name' => '501', 'url' => 'manager.php?type=list'),
        array('name' => '添加管理员', 'en_name' => '502', 'url' => 'manager.php?type=add'),
        array('name' => '管理员日记', 'en_name' => '503', 'url' => 'manager.php?type=loglist'),
        array('name' => '修改密码', 'en_name' => '504', 'url' => 'manager.php?type=edit'),
        array('name' => '权限组列表', 'en_name' => '505', 'url' => 'manager.php?type=group'),
        array('name' => '添加权限组', 'en_name' => '506', 'url' => 'manager.php?type=group&tt=add')
		
    )
);




$menu[6] = array(
    'en_name' => '6',
    'big_key' => 's03',
    'small_mod' => '会员管理',
    'big_mod' => '用户管理',
    'sub_mod' => array(
        //array('name'=>'批量更正关系','en_name'=>'6011','url'=>'user.php?type=ajax_import_order'),
        //array('name'=>'会员设置','en_name'=>'605','url'=>'user.php?type=userset'),
		// array('name'=>'实名认证','en_name'=>'605','url'=>'user.php?type=userbank'),
        array('name' => '申请会员', 'en_name' => '601', 'url' => 'user.php?type=list&status=0'),
		 array('name' => '送审会员', 'en_name' => '602', 'url' => 'user.php?type=list&status=2'),
		  array('name' => '正式会员', 'en_name' => '603', 'url' => 'user.php?type=list&status=1'),
        array('name' => '会员关系', 'en_name' => '604', 'url' => 'user.php?type=userrelate'),
        array('name' => '邀请统计', 'en_name' => '605', 'url' => 'user.php?type=yaoqing'),
        array('name' => '会员等级', 'en_name' => '606', 'url' => 'user.php?type=levellist'),
        array('name' => '提款申请', 'en_name' => '607', 'url' => 'user.php?type=drawmoney'),
        array('name' => '帐变明细', 'en_name' => '608', 'url' => 'user.php?type=usermoney'),
        array('name' => '推广二维码', 'en_name' => '609', 'url' => 'user.php?type=usererweima'),
		/*array('name' => '重新入驻结算卡', 'en_name' => '610', 'url' => 'user.php?type=delete_old_bank')*/
    )
);



  
  
/*
  $menu[7] = array(
  'en_name'=>'7',
  'big_key'=>'s03',
  'small_mod'=>'商家会员',
  'big_mod'=>'用户管理',
  'sub_mod'=>array(
  //array('name'=>'代理设置','en_name'=>'605','url'=>'user.php?type=dailiset'),
  array('name'=>'提款申请','en_name'=>'603','url'=>'user.php?type=drawmoney'),
  //array('name'=>'分销申请','en_name'=>'601','url'=>'user.php?type=dailiapply'),
  array('name'=>'分销列表','en_name'=>'602','url'=>'user.php?type=suppliers'),
  array('name'=>'帐变明细','en_name'=>'608','url'=>'user.php?type=usermoney'),
  array('name'=>'添加分销','en_name'=>'603','url'=>'user.php?type=infodaili_step1'),
  array('name'=>'推广二维码','en_name'=>'6011','url'=>'user.php?type=usererweima')
  //array('name'=>'用户列表','en_name'=>'603','url'=>'user.php?type=dailiuser'),
  //array('name'=>'发货申请','en_name'=>'604','url'=>'user.php?type=fahuoapply')
  //array('name'=>'分销业绩','en_name'=>'605','url'=>'user.php?type=dailiorder')

  )
  );
  $menu[27] = array(
  'en_name'=>'27',
  'big_key'=>'s03',
  'small_mod'=>'商家会员',
  'big_mod'=>'用户管理',
  'sub_mod'=>array(
  array('name'=>'商家列表','en_name'=>'2701','url'=>'user.php?type=shoplist'),
  array('name'=>'添加商家','en_name'=>'2702','url'=>'user.php?type=shopinfo')

  )
  );
 */
/* $menu[9] = array(
  'en_name' => '9',
  'big_key' => 's03',
  'small_mod' => '附近商家',
  'big_mod' => '用户管理',
  'sub_mod' => array(
  //array('name'=>'分类列表','en_name'=>'701','url'=>'con_new.php?type=catelist'),
  //array('name'=>'添加分类','en_name'=>'702','url'=>'con_new.php?type=cateadd'),
  array('name' => '商家列表', 'en_name' => '701', 'url' => 'con_new.php?type=newlist'),
  array('name' => '添加商家', 'en_name' => '702', 'url' => 'con_new.php?type=newadd')
  )
  );
 */
/*


  $menu[] = array(
  'en_name'=>'7',
  'big_key'=>'s04',
  'small_mod'=>'品牌预告',
  'big_mod'=>'产品管理',
  'sub_mod'=>array(
  array('name'=>'分类列表','en_name'=>'701','url'=>'con_case.php?type=catelist'),
  array('name'=>'添加分类','en_name'=>'702','url'=>'con_case.php?type=cateadd'),
  array('name'=>'预告列表','en_name'=>'703','url'=>'con_case.php?type=newlist'),
  array('name'=>'添加内容','en_name'=>'704','url'=>'con_case.php?type=newadd')
  array('name'=>'颜色分类','en_name'=>'705','url'=>'con_case.php?type=colorlist'),
  array('name'=>'添加颜色','en_name'=>'706','url'=>'con_case.php?type=colorinfo')
  )
  );

  $menu[] = array(
  'en_name'=>'8',
  'big_key'=>'s04',
  'small_mod'=>'网站建设',
  'big_mod'=>'内容管理',
  'sub_mod'=>array(
  array('name'=>'分类列表','en_name'=>'801','url'=>'con_website.php?type=catelist'),
  array('name'=>'添加分类','en_name'=>'802','url'=>'con_website.php?type=cateadd'),
  array('name'=>'内容列表','en_name'=>'803','url'=>'con_website.php?type=newlist'),
  array('name'=>'添加内容','en_name'=>'804','url'=>'con_website.php?type=newadd')
  )
  );
 */

//$menu[10] = array(
//    'en_name' => '10',
//    'big_key' => 's05',
//    'small_mod' => '商品管理',
//    'big_mod' => '产品管理',
//    'sub_mod' => array(
//        array('name' => '积分商品', 'en_name' => '1001', 'url' => 'exchange.php?type=lists'),
//        array('name' => '虚拟商品', 'en_name' => '1002', 'url' => 'vgoods.php?type=lists'),
//        array('name' => '实体商品', 'en_name' => '1003', 'url' => 'goods.php?type=goods_list'),
        //array('name'=>'商品转移','en_name'=>'1023','url'=>'goods.php?type=zhuanyi'),
        //array('name'=>'已审核商品','en_name'=>'1023','url'=>'goods.php?type=goods_list_check&sale=yes'),
        //array('name'=>'待审核商品','en_name'=>'1021','url'=>'goods.php?type=goods_list_check&sale=no'),
//        array('name' => '我的回收站', 'en_name' => '1004', 'url' => 'goods.php?type=goods_list_all'),
        //array('name'=>'添加商品','en_name'=>'1004','url'=>'goods.php?type=goods_info'),
        //  array('name' => '批量传图', 'en_name' => '1005', 'url' => 'goods.php?type=batch_add'),
        // array('name' => '批量上传', 'en_name' => '1006', 'url' => 'goods.php?type=batch_add_text'),
//        array('name' => '分类列表', 'en_name' => '1007', 'url' => 'goods.php?type=cate_list'),
//        array('name' => '添加分类', 'en_name' => '1008', 'url' => 'goods.php?type=cate_info'),
//        array('name' => '品牌列表', 'en_name' => '1009', 'url' => 'brand.php?type=band_list'),
//        array('name' => '添加品牌', 'en_name' => '1010', 'url' => 'brand.php?type=band_info'),
        //array('name'=>'搜索关键字','en_name'=>'1008','url'=>'goods.php?type=keyword'),
        //array('name'=>'品牌类型','en_name'=>'1008','url'=>'goods.php?type=band_type'),
//        array('name' => '商品属性', 'en_name' => '1011', 'url' => 'goods.php?type=goods_attr_list'),
        //array('name'=>'团购管理','en_name'=>'1012','url'=>'groupbuy.php?type=list'),
        //array('name'=>'派放红包','en_name'=>'1013','url'=>'coupon.php?type=list'),
//        array('name' => '用户评论', 'en_name' => '1014', 'url' => 'goods.php?type=comment_list')
    //array('name'=>'消费额赠品','en_name'=>'1017','url'=>'goods.php?type=spend_gift'),
    //array('name'=>'设置提取目录','en_name'=>'1018','url'=>'goods.php?type=freecataloginfo'),
    //array('name'=>'提取目录列表','en_name'=>'1019','url'=>'goods.php?type=freecatalog'),
    //array('name'=>'专题管理','en_name'=>'1020','url'=>'topic.php?type=list'),
    //array('name'=>'推荐产品','en_name'=>'1021','url'=>'topgoods.php?type=clist'),
    //array('name'=>'产品收藏','en_name'=>'1022','url'=>'goods.php?type=goodscoll')
 //   )
//);





$menu[23] = array(
    'en_name' => '23',
    'big_key' => 's08',
    'small_mod' => '订单管理',
    'big_mod' => '订单管理',
    'sub_mod' => array(
        array('name' => '订单列表', 'en_name' => '2301', 'url' => 'goods_order.php?type=list'),
//		array('name' => '入驻商订单列表', 'en_name' => '2301', 'url' => 'goods_order.php?type=list&tt=supplier&supplier=yes'),
        //array('name'=>'积分订单','en_name'=>'2301','url'=>'goods_order.php?type=jifenorder'),
        ///  array('name' => '待发货', 'en_name' => '2302', 'url' => 'goods_order.php?type=list&status=2x0'),
//        array('name' => '待发货', 'en_name' => '2302', 'url' => 'goods_order.php?type=list&status=210&supplier=no'),
//        array('name' => '物流单', 'en_name' => '2303', 'url' => 'goods_order.php?type=list&tt=delivery&status=222&supplier=no'),
//        array('name' => '退货单', 'en_name' => '2304', 'url' => 'goods_order.php?type=list&tt=back&status=3&supplier=no'),
//        array('name' => '退款单', 'en_name' => '2305', 'url' => 'goods_order.php?type=list&tt=back&status=2&supplier=no'),
//        array('name' => '退货申请单', 'en_name' => '2306', 'url' => 'goods_order.php?type=list&tt=back&status=5&supplier=no'),
        //array('name'=>'换货申请单','en_name'=>'2307','url'=>'goods_order.php?type=list&tt=back&status=6'),
//        array('name' => '退款申请单', 'en_name' => '2308', 'url' => 'goods_order.php?type=list&tt=back&status=7&supplier=no')
    //array('name'=>'生成物流单','en_name'=>'2309','url'=>'shopping.php?type=shoppingsn')
    //array('name'=>'产品总销量','en_name'=>'2304','url'=>'goods_order.php?type=product_list')  //look添加
    )
);

$menu[24] = array(
    'en_name' => '24',
    'big_key' => 's08',
    'small_mod' => '其他设置',
    'big_mod' => '订单管理',
    'sub_mod' => array(
       // array('name' => '地区设置', 'en_name' => '2401', 'url' => 'area.php?type=list'),
	   array('name' => '银行设置', 'en_name' => '2401', 'url' => 'payment.php?type=banklist'),
        array('name' => '支付方式', 'en_name' => '2413', 'url' => 'payment.php?type=list'),
     //   array('name' => '配送方式', 'en_name' => '2414', 'url' => 'shopping.php?type=shoppinglist'),
//        array('name' => '邮费设置', 'en_name' => '2415', 'url' => 'delivery.php?type=list')
    )
);



$menu[25] = array(
    'en_name' => '25',
    'big_key' => 's08',
    'small_mod' => '信用卡还款',
    'big_mod' => '订单管理',
    'sub_mod' => array(
	   array('name' => '还款计划', 'en_name' => '2501', 'url' => 'Instead.php?type=planslist'),
      
    )
);

/*
$menu[14] = array(
    'en_name' => '14',
    'big_key' => 's06',
    'small_mod' => '文章管理',
    'big_mod' => '其他扩展',
    'sub_mod' => array(
        array('name' => '文章分类', 'en_name' => '1401', 'url' => 'con_default.php?type=catelist'),
        array('name' => '添加分类', 'en_name' => '1402', 'url' => 'con_default.php?type=cateadd'),
        array('name' => '文章列表', 'en_name' => '1403', 'url' => 'con_default.php?type=newlist'),
        array('name' => '添加文章', 'en_name' => '1404', 'url' => 'con_default.php?type=newadd')
    )
);
$menu[16] = array(
    'en_name' => '16',
    'big_key' => 's06',
    'small_mod' => '广告设置',
    'big_mod' => '其他扩展',
    'sub_mod' => array(
        array('name' => '广告列表', 'en_name' => '1601', 'url' => 'ads.php?type=adslist'),
        array('name' => '广告标签', 'en_name' => '1602', 'url' => 'ads.php?type=adstaglist'),
        array('name' => '添加标签', 'en_name' => '1603', 'url' => 'ads.php?type=adstag_add'),
        array('name' => '添加广告', 'en_name' => '1604', 'url' => 'ads.php?type=ads_add')
    )
);

*/
/*if ($_SERVER["HTTP_HOST"] == "weixin.apiqq.com") {
    $menu[32] = array(
        'en_name' => '32',
        'big_key' => 's06',
        'small_mod' => '平台设置',
        'big_mod' => '其他扩展',
        'sub_mod' => array(
            array('name' => '导航栏列表', 'en_name' => '3202', 'url' => 'systemconfig.php?type=nav_list_wx'),
            array('name' => '添加导航栏', 'en_name' => '3203', 'url' => 'systemconfig.php?type=nav_info_wx')
        )
    );
} else {
    $menu[32] = array(
        'en_name' => '32',
        'big_key' => 's06',
        'small_mod' => '平台设置',
        'big_mod' => '其他扩展',
        'sub_mod' => array(
            array('name' => '导航栏列表', 'en_name' => '3202', 'url' => 'systemconfig.php?type=nav_list_wx'),
            array('name' => '添加导航栏', 'en_name' => '3203', 'url' => 'systemconfig.php?type=nav_info_wx')
        )
    );
}

$menu[17] = array(
    'en_name' => '17',
    'big_key' => 's06',
    'small_mod' => 'PC端导航',
    'big_mod' => '其他扩展',
    'sub_mod' => array(
        array('name' => '导航栏列表', 'en_name' => '1701', 'url' => 'systemconfig.php?type=nav_list'),
        array('name' => '添加导航栏', 'en_name' => '1702', 'url' => 'systemconfig.php?type=nav_add')
    )
);*/


/*$menu[21] = array(
    'en_name' => '21',
    'big_key' => 's010',
    'small_mod' => '数据分析',
    'big_mod' => '数据管理',
    'sub_mod' => array(
        array('name' => '订单走势', 'en_name' => '2101', 'url' => 'stats.php?type=order_trend'),
        array('name' => '销售走势', 'en_name' => '2102', 'url' => 'stats.php?type=sale_trend')
    )
);*/

/*$menu[22] = array(
    'en_name' => '22',
    'big_key' => 's02',
    'small_mod' => '邮箱服务器设置',
    'big_mod' => '系统设置',
    'sub_mod' => array(
        array('name' => '服务器账号设置', 'en_name' => '2201', 'url' => 'email.php?type=email_config'),
        array('name' => '发送开启设置', 'en_name' => '2203', 'url' => 'email.php?type=send')
    )
);*/


$menu[26] = array(
    'en_name' => '26',
    'big_key' => 's09',
    'small_mod' => '公众平台',
    'big_mod' => '公众平台',
    'sub_mod' => array(
        array('name' => '公众号管理', 'en_name' => '2601', 'url' => 'weixin.php?type=wxconfig'),
        array('name' => '关注时回复', 'en_name' => '2602', 'url' => 'weixin.php?type=wxgzreply'),
        array('name' => '关注外链', 'en_name' => '2603', 'url' => 'weixin.php?type=wxguanzhuurl'),
        array('name' => '图文信息', 'en_name' => '2604', 'url' => 'weixin.php?type=wxnewlist'),
        array('name' => '文本信息', 'en_name' => '2605', 'url' => 'weixin.php?type=wxnewlisttxt'),
        array('name' => '分类列表', 'en_name' => '2606', 'url' => 'weixin.php?type=catelist'),
        array('name' => '自定义菜单', 'en_name' => '2607', 'url' => 'weixin.php?type=diymenu')
    //array('name'=>'通知配置','en_name'=>'2607','url'=>'weixin.php?type=tongzhiset')
    )
);


?>