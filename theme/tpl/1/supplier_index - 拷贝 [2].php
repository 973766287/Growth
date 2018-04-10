

<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_style.css" rel="stylesheet" type="text/css" />
<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_category.css" rel="stylesheet" type="text/css" />
<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_index.css" rel="stylesheet" type="text/css" />


<div id="site-nav"> 

</div>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">  
        $(document).ready(function () {  
            var ps = $("#div_pro").position();  
            $("#float_box").css("position", "absolute");  
            $("#float_box").css("left", ps.left + -50); //距离左边距  
            $("#float_box").css("top", ps.top + 30); //距离上边距  
            $("#div_pro").mouseenter(function () {
                $("#float_box").show();  
           });  
           $("#float_box").mouseleave(function () { 
                $("#float_box").hide();  
            });  
        })  
</script>

<div class="jShopHeader">
  <div class="jFixWidth">
    <div class="jShopHeaderArea">
      <div class="j-shopHeader">
        <div id="div_pro" class="jLogo" > <a href="suppliers.php?act=index&suppId=<? echo $s_info['supplier_id'];?>"><? echo $s_info['site_name'];?></a> </div>
        <div class="jRating">
          <div id="float_box"  style="display: none;"  class="jRatingTotal">
           <ul class="shopTolal">   
              <li> <span><a href="suppliers.php?act=index&suppId=<? echo $s_info['supplier_id'];?>"><img width="90" height="45" src="<? echo $s_info['site_logo'];?>" style="margin:0 36px;" /></a></span></li>
              <li style="border-top:1px dashed #F5AFB1 "> <span>商店等级：</span><span><? echo $s_info['dengji'];?></span></li>
			  <li> <span>商品数量：</span><span><? echo $s_info['count'];?></span></li>
              <li> <span>所在地区：</span><span><? echo $s_info['province'];?> <? echo $s_info['city'];?></span></li>
			  <li> <span>创店时间：</span><span><? echo $s_info['time'];?></span></li>
              <li> <span>详细地址：</span><span><? echo $s_info['company_url'];?></span></li>
            </ul>
          </div>
        </div>
      </div>
      <!--<div class="jAttention" onclick="guanzhu({$smarty.get.suppId})">
                <a><span>加关注</span></a>
            </div>-->
      <div title="<? echo $s_info['site_name'];?>在线客服" id="im" class="im jIm"><b>
     
       <? foreach ($s_info['qq'] as $im){?> 
       <? if ($im){?> 
      <a href="http://wpa.qq.com/msgrd?V=1&amp;uin=<? echo $im;?>&amp;Site=<? echo $s_info['site_name'];?>&amp;Menu=yes" target="_blank" title="<? echo $im;?>">在线客服</a>
       <? }}?>
    
      </b></div>
     <!-- <div class="ghs_score"><span>好评</span><em>{if $haoping gt 0}{$haoping}%{else}100%{/if}</em></div>
      <div class="ghs_score"><span>描述</span><em>{if $c_rank gt 0}{$c_rank}{else}5{/if}</em></div>
      <div class="ghs_score"><span>服务</span><em>{if $serv_rank gt 0}{$serv_rank}{else}5{/if}</em></div>
      <div class="ghs_score"><span>物流</span><em>{if $shipp_rank gt 0}{$shipp_rank}{else}5{/if}</em></div>-->
      
         <div class="ghs_score"><span>好评</span><em>100%</em></div>
      <div class="ghs_score"><span>描述</span><em>5</em></div>
      <div class="ghs_score"><span>服务</span><em>5</em></div>
      <div class="ghs_score"><span>物流</span><em>5</em></div>
      
      
    </div>
  </div>
</div>
<div class="ghs_body">
  <div class="ghs_content1"> 
  <p style="text-align: center;">
  <? echo $s_info['copyright'];?>
  </p>
  </div>
  <div style="height:0px; line-height:0px; clear:both"></div>
  <div class="ghs_title" style="background:<? if(!empty($s_info['shop_header_color'])){echo $s_info['shop_header_color'];}else{echo "#66A232";}?>">
    <div class="ghs_content">
      <div class="fl" style="width:840px;"> 
        <a href="suppliers.php?act=index&suppId=<? echo $s_info['supplier_id'];?>" class="cur" style="background:<? if(!empty($s_info['shop_header_color'])){echo $s_info['shop_header_color'];}else{echo "#66A232";}?>">店铺首页</a> 
        <? foreach ($s_nav as $mall_get_navigator) {?> 
        <A title="<? echo $mall_get_navigator['name'];?>" href="<? echo $mall_get_navigator['url'];?>" <? if ($mall_get_navigator['opennew']){?>target="_blank" <? }?>><? echo $mall_get_navigator['name'];?></A>
       <? }?>
      </div>
      <!--<div class="su_Search fr" style="width:355px;">
        <form id="searchForm" name="searchForm" method="get" action="/supplier.php">
          <input type='hidden' name='go' value='search'>
          <input type='hidden' name='suppId' value='{$smarty.request.suppId}'>
          <input class="fl" style="width:180px;" name="keywords" type="text" id="su_keyword" value='{$smarty.request.keywords|default:请输入你要查找的商品}' onClick="javascript:this.value='';"/>
          {if $search_price }
          <select name="price" class="su-select" style="float:left;border:1px #CCCCCC solid">
            
                {foreach from=$search_price item=region key=key}
                  
            <option class="su-option" value="{$key}" {if $smarty.request.price eq $key}selected{/if}>{$region}</option>
            
                {/foreach}
        
          </select>
          {/if}
          <input class="fr"  type="submit" id="btsearch" value="搜 索" style="background:#fff;border:none;color:{$navcolor}"/>
        </form>
      </div>-->
    </div>
  </div>
</div>

<div class="banner">
   
      
            
	<ul id="slides">
        
                 <?php foreach($rt['ad'] as $_k=>$_v){?>
		<a href="<?php echo $_v['ad_url'];?>" target="_blank"><li><img src="<?php echo $_v['ad_img'];?>"></li></a>
		  <?php } ?>
                
                
	</ul>

    
</div>

<script>
function guanzhu(sid){
	Ajax.call('supplier.php', 'go=other&act=add_guanzhu&suppId=' + sid, selcartResponse, 'GET', 'JSON');
}

function selcartResponse(result){
	alert(result.info);
}
</script>
<div class="ghs_body">
	<style>
	.scrollimg{position:relative; overflow:hidden; margin:0px auto; /* 设置焦点图最大宽度 */}
	.scrollimg .hd{position:absolute; height:18px; line-height:18px; bottom:10px; right:45%; z-index:1;}
	.scrollimg .hd li{display:inline-block;  display: inline-block; background-color: rgba(255,255,255,0); border: 1px rgba(255,255,255,0.5) solid; width: 10px; height: 10px; border-radius: 10px; margin-right: 5px; text-indent:-9999px; overflow:hidden; cursor:pointer}
	.scrollimg .hd li.on{background:#fff;}
	.scrollimg .bd{position:relative; z-index:0;}
	.scrollimg .bd li{position:relative; text-align:center;}
	.scrollimg .bd li img{background:url(themes/dianpu6/images/loading.gif) center center no-repeat;  vertical-align:top; width:100%;/* 图片宽度100%，达到自适应效果 */}
	.scrollimg .bd li a{-webkit-tap-highlight-color:rgba(0,0,0,0); display:block; width:100%; height:400px; overflow:hidden;}  /* 去掉链接触摸高亮 */
	.scrollimg .bd li .tit{display:block; width:100%;  position:absolute; bottom:0; text-indent:10px; height:28px; line-height:28px; background:url(themes/dianpu6/images/focusBg.png) repeat-x; color:#fff;  text-align:left;}
	</style>
	<script type="text/javascript" src="data/flashdata/anan1/data.js"></script>
	<script type="text/javascript" src="themes/dianpu6/images/ghs/js/TouchSlide.1.1.js"></script>
	<div id="scrollimg" class="scrollimg">
		<div id="scrollimg_bd" class="bd">
		</div>
		<div class="hd">
			<ul>
			</ul>
		</div>
	</div>
	<script type="text/javascript">
var picss = new Array();
var linkss = new Array();
var textss = new Array();
picss = pics.split("|");
linkss = links.split("|");
textss = texts.split("|");
var picstr = '<ul>';
$.each(picss, function (index, tx) { 

	picstr += '<li><a href="'+decodeURIComponent(decodeURIComponent(linkss[index]))+'" target="_blank" style="background:url('+tx+') no-repeat center top"></a></li>';
});
picstr += '</ul>';
document.getElementById('scrollimg_bd').innerHTML = picstr;
	</script>
	<script type="text/javascript">
				TouchSlide({ 
					slideCell:"#scrollimg",
					titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
					mainCell:".bd ul", 
					effect:"leftLoop", 
					autoPage:true,//自动分页
					autoPlay:true //自动播放
				});
			</script>
</div>
<!--        自定义部分        -->
<div class="zdy">
	<? echo $s_info['zidingyi'];?>
</div>
<!--        通用模板        -->
<div class="store_box">
	<div class="store_box_top">
		<div class="store_box_top_tlt">
			<!--<span class="share_sns_wrap ">
			<i style="float:left; margin-right:8px; margin-top:4px; color:#999;">分享到</i><a class="vips_weixin share_gap " href="#" data-sns="weixin" data-original-title="分享到微信" mars_sead="sns_m_wxk_share_weixin_link"></a>
			<a class="vips_qq share_gap " href="#" data-sns="qq" data-original-title="分享到QQ好友" mars_sead="sns_m_wxk_share_qq_link"></a>
			<a class="vips_qzone share_gap " href="#" data-sns="qzone" data-original-title="分享到QQ空间" mars_sead="sns_m_wxk_share_qzone_link"></a>
			<a class="vips_tsina share_gap " href="#" data-sns="tsina" data-original-title="分享到新浪微博" mars_sead="sns_m_wxk_share_sinaweibo_link"></a>
			<a class="vips_tqq share_gap share_gap_last" href="#" data-sns="tqq" data-original-title="分享到腾讯微博" mars_sead="sns_m_wxk_share_qqweibo_link"></a>
			</span>-->
			<strong><? echo $s_info['site_name'];?></strong>
			<!--<span class="store_box_top_tlt_coll"><a href="#"><i class="icon iconfont">&#xe606;</i>收藏店铺</a></span>-->
		</div>
		<div class="store_box_top_box">
			<div class="store_box_top_box_lf">
				分类：
			</div>
			<div class="store_box_top_box_rg">
				<ul>
				
                     <?php foreach($catList as $_k=>$_v){?>
					<li><a  href="category.php?cid=<? echo $_v['id'];?>&amp;suppId=<? echo $suppId;?> " ><?php echo $_v['name'];?></a></li>
                    <? }?>
					
				</ul>
			</div>
		</div>
	</div>
	<div class="pro-list-oper" id="J-pro-list-fix">
		<div class="pro-oper">
			<div class="oper-hd-rexiao">
				<a href="#">热销推荐</a>
			</div>
			<div class="oper-hd-price">
				<div class="oper-price-tc ">
					<p class="oper-price-txt">
                        价格
						<i class="icon iconfont">&#xe6f6;</i>
					</p>
					<div class="oper-price-pop">
						<p>
							<a class="J_filter_param" target="_self" href="/560716.html?q=0|0|0|0|4|1">价格由高到低</a>
						</p>
						<p>
							<a class="J_filter_param" target="_self" href="/560716.html?q=0|0|0|0|3|1">价格由低到高</a>
						</p>
					</div>
				</div>
			</div>
			<div class="oper-hd-discot ">
				<p>
                    折扣
					<i class="icon iconfont">&#xe6f6;</i>
				</p>
				<div class="oper-price-pop">
					<p>
						<a class="J_filter_param" target="_self" href="/560716.html?q=0|0|0|0|2|1">折扣由高到低</a>
					</p>
					<p>
						<a class="J_filter_param" target="_self" href="/560716.html?q=0|0|0|0|1|1">折扣由低到高</a>
					</p>
				</div>
			</div>
		</div>
		<!-- 翻页导航工具栏 author:eason.chen@vipshop.com -->
		<div id="J_page_special" class="page pro-paging">
			<span class="page-total"><em class="page-nub">103</em>件商品</span>
			<span><em class="page-nub">1</em>/1</span>
			<span class="page-pre">&lt;</span>
			<span class="page-next">&gt;</span>
		</div>
	</div>
	<div class="store_box_img">
		<ul>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            <li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
            
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
			<li>
			<div class="store_box_img_tp">
				<a href="#"><img src="/images/201510/thumb_img/242_thumb_G_1445274084253.jpg"></a>
			</div>
			<div class="store_box_img_bt">
				<div class="store_box_img_bt_tlt">
					<h3><a href="#"><em>【官方授权】</em>“膜”界丽得姿，三代升级回归！AMINO水库面膜贴*10；普通面膜只补水</a></h3>
					<div class="store_box_img_bt_bot">
						<a href="#"><span class="tb_r2"></span></a>
         促销价 
						<strong><i>￥</i>109</strong><em>5.2折</em><br>
         原价：
						<span class="tb_c">￥208</span> 国内参考价￥289
					</div>
				</div>
			</div>
			</li>
		</ul>
	</div>
</div>

<style>
.zdy img{ position: relative;
     left: 50%; 
     margin-left: -960px; }
.store_box{overflow:hidden;padding:10px 0;width:100%;background: url(<? echo $s_info['site_background'];?>) no-repeat top center #f3f1f4;color:#333}
.store_box_top{overflow:hidden;margin:0 auto;width:1208px;border:1px solid #e6e6e6;background:#fff}
.store_box_top_tlt{padding:0 8px;height:40px;border-bottom:1px solid #e6e6e6;background:#fafafa;line-height:40px}
.store_box_top_tlt strong{margin-left:5px;font-weight:100;font-size:18px;font-family:"微软雅黑"}
.store_box_top_tlt_coll{margin-left:10px}
.store_box_top_tlt_coll a{padding:3px 15px 3px 10px;border-radius:50px;background:#eaeaea}
.store_box_top_tlt_coll a i{color:#b1b1b1}
.store_box_top_tlt_coll a:hover i{color:#fff}
.store_box_top_tlt_coll a:hover{background:#f3293f;color:#fff;text-decoration:none}
@font-face {font-family: 'iconfont';
    src: url('/images/iconfont.eot'); /* IE9*/
    src: url('/images/iconfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
    url('/images/iconfont.woff') format('woff'), /* chrome、firefox */
    url('/images/iconfont.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
    url('/images/iconfont.svg#iconfont') format('svg'); /* iOS 4.1- */
}
.iconfont{
    font-family:"iconfont" !important;
    font-size:16px;font-style:normal;
    -webkit-font-smoothing: antialiased;
    -webkit-text-stroke-width: 0.2px;
    -moz-osx-font-smoothing: grayscale;}
.icon-xin:before{content:"\e606"}
.share_sns_wrap{float:right;margin-top:-2px}
.share_gap{padding:2px 15px 8px;background:url(images/share_icon.png) no-repeat; line-height:41px;}
.vips_weixin{background-position:-238px -44px}
.vips_weixin:hover{background-position:-238px 0}
.vips_qq{background-position:-272px -44px}
.vips_qq:hover{background-position:-272px 0}
.vips_qzone{background-position:-34px -44px}
.vips_qzone:hover{background-position:-34px 0}
.vips_tsina{background-position:0 -44px}
.vips_tsina:hover{background-position:0 0}
.vips_tqq{background-position:-68px -44px}
.vips_tqq:hover{background-position:-68px 0}
.store_box_top_box{overflow:hidden;width:100%;background:#f7f7f7}
.store_box_top_box_lf{float:left;width:10%;text-align:center;line-height:40px}
.store_box_top_box_rg{float:right;overflow:hidden;padding-top:5px;padding-bottom:3px;width:90%;background:#fff}
.store_box_top_box_rg ul li{display:inline-block;padding:5px 15px}
.pro-list-oper{position:relative;z-index:9;clear:both;margin:10px auto;width:1210px;min-height:42px;background-color:#fafafa;}
.pro-oper{float:left}
.pro-oper .vipFont{vertical-align:middle}
.pro-paging{float:right;padding:12px 8px 0 0}
.oper-hd-discot,.oper-hd-hastock,.oper-hd-price{float:left;height:40px;line-height:40px}
.oper-hd-hastock{padding-left:15px}
.oper-hd-rexiao{float:left;padding:0 20px;border-right:1px solid #e6e6e6;line-height:42px}
.oper-hd-rexiao a{color:#333}
.oper-hd-hastock:hover{color:#f10180}
.oper-hd-hastock:hover .ui-checkbox-simulation{background-position:-24px -21px}
.oper-hd-price{position:relative;z-index:3}
.oper-price-tc,.oper-price-tp{float:left;display:block}
.oper-price-tp{cursor:pointer;cursor:default}
.oper-hd-discot,.oper-price-tc,.oper-price-tp{width:70px;border-right:1px solid #dfdfdf;text-align:center;cursor:pointer}
.oper-hd-discot:hover,.oper-price-tc:hover,.oper-price-tp:hover{color:#f10180}
.oper-hd-discot,.oper-price-tc{padding-left:7px}
.oper-price-tc{position:relative}
.oper-price-tc-hover,.oper-price-tc:hover{text-align:left}
.oper-price-tc-hover .oper-price-txt,.oper-price-tc:hover .oper-price-txt{display:none}
.oper-price-tc-hover .oper-price-pop,.oper-price-tc:hover .oper-price-pop{display:block;padding-left:22px}
.oper-hd-discot{position:relative;z-index:2}
.oper-hd-discot:hover .oper-price-pop{display:block;padding-left:22px;text-align:left}
.oper-price-pop{position:absolute;top:0;left:-1px;display:none;padding:8px 1px;min-width:98px;border:1px solid #dfdfdf;border-top:none;background-color:#fff;color:#333;line-height:24px}
.oper-price-pop p:hover{color:#f10180}
.page-nub{color:#f10180}
.store_box_img{margin:0 auto;width:1210px}
.store_box_img ul{width:1226px}
.store_box_img ul li{float:left;margin-right:16px;margin-bottom:15px;width:290px;background:#fff}
.store_box_img_tp img{width:100%}
.store_box_img_bt{padding:8px}
.store_box_img_bt_tlt{font-size:14px}
.store_box_img_bt_tlt h3{overflow:hidden;height:40px}
.store_box_img_bt_tlt h3 em{color:#f3293f}
.store_box_img_bt_bot{padding-top:10px;color:#999;font-size:12px;line-height:28px}
.store_box_img_bt_bot strong{color:#f3293f;font-size:18px}
.tb_r2{float:right;margin-top:2px;width:56px;height:52px;background:url(/themes/68ecshopcom_360buy/img/tb5.png) no-repeat;background-size:52px}
</style>