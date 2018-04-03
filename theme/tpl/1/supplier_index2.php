

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
<!--店铺公告 start-->
<div class="shop_notice"><? echo $s_info['site_notice'];?></div>
<!--店铺公告 end-->
  <div class="ghs_content">
    <div class="layout-container">
      <div class="layout-main">
        <div class="layout-area J-layout-area no-margin">
          <div class="layout J-layout">
            <div class="layout-one" name="main">
            <div class="fn-clear userdefined-21" style="margin-bottom:0px;" origin="0">
  <div class="mc" style="background:#ffffff; min-height:0px;">
    <div class="j-module">
      <div class="userDefinedArea" style="width: auto; margin: 0px auto;">
        <div class="user_st"> 
         <?php foreach($catList as $_k=>$_v){?>
          <div> 
            <a  href="category.php?cid=<? echo $_v['id'];?>&amp;suppId=<? echo $suppId;?> " style="background:#66A232;"><?php echo $_v['name'];?></a>
            <div class="jThreeLevel">
              <ul>
                <?php 
                              if(!empty($_v['cat_id'])){
                              foreach($_v['cat_id'] as $_k1=>$_v1){ ?>
                <li><a title="<? echo $_v1['name'];?>" href="category.php?cid=<? echo $_v1['id'];?>&amp;suppId=<? echo $suppId;?>" ><?php echo $_v1['name'];?></a></li>
               <? }}?>
               
              </ul>
            </div>
          </div>
         <? }?>
        </div>
      </div>
    </div>
  </div>
</div>

           <? if (!empty($tj)){?>
<div class="fn-clear user_e990_20004_5-493262" style="margin-bottom:0px;" >
                <div class="mc" style="background:#ffffff; min-height:0px;">
                  <div>
                    <div class="j-module"> 
                      <span class="hotSell"></span>
                      <div style="margin: 0px auto;" class="items">
                        <div class="items-inner">
                          <ul style="float: left;">
                          <? foreach ($tj as  $_k=>$bestgoods){?>
                        
                          <? if ($_k < 6){?>
                            <div class="item item<? echo $_k+1;?> user_bgcolor_3 user_border_3" style="border-color:#66A232"> 
                            	<a target="_blank" href="<?php echo SITE_URL . 'product.php?id=' . $bestgoods['goods_id']; ?>" title="<? echo $bestgoods['goods_name'];?>">
                                	<img src="<? echo $bestgoods['goods_img'];?>"  width="100%" border="0">
                                </a>
                              <div class="sp_txt">
                                <div class="jTitle"><a class="user_a_1" href="<?php echo SITE_URL . 'product.php?id=' . $bestgoods['goods_id']; ?>" target="_blank"><? echo $bestgoods['goods_name']?></a></div>
                                <div class="boxTitle"> 
                                	<span class="jPrice user_font_price">一口价 <span class="t"><span class="jdNum" jshop="price"> <? if ($bestgoods['promote_price'] > 0){?>
          								<? echo $bestgoods['promote_price'];?>
          							<? }else{?>
          							<? echo $bestgoods['pifa_price'];?>
          							<? }?>
                                    </span></span></span>
                                    <span class="SalePrice user_font_2">参考价：<span class="jsNum" jshop="price"><? echo $bestgoods['market_price'];?></span></span> </div>
                              </div>
                            </div>
                           <? }}?>
                              
                            </div>
                            <div class="user-clear"></div>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
<? }?>

           <? if($rx){?>        
<div class="stitle">热销推荐</div>
<div class="clear"></div>
              <div class="fn-clear user_s20019-493268">
                <div class="mc" style="width:1200px; "> 
                  <div class="user_item_box " >
                 <? foreach ($rx as $_k=>$bestgoods){?>
                    <div class="item <? if ($_k+1 ==  1){?>item_ts<? }?>">
                      <div class="jPic"><a href="<?php echo SITE_URL . 'product.php?id=' . $bestgoods['goods_id']; ?>" target="_blank" class="user_border_hover_1"><img src="<? echo $bestgoods['goods_img'];?>" class="" alt="<? echo $bestgoods['goods_name']?>" height="232" width="232"></a>
                        <div class="jBuy jBuy_3 user_bgcolor_1" style="background-color:#66A232;">
                          <div class="jDesc">
                            <div class="jTitle user_border_dashed_bottom"><a class="user_a_3" href="<?php echo SITE_URL . 'product.php?id=' . $bestgoods['goods_id']; ?>" target="_blank" title="<? echo $bestgoods['goods_name']?>"><? echo $bestgoods['goods_name']?></a></div>
                            <div class="buy"> 
                            	<span class="jdNum">现价： <? if ($bestgoods['promote_price'] >0){?>
          								<? echo $bestgoods['promote_price'];?>
          							<? }else{?>
          							<? echo $bestgoods['pifa_price'];?>
          							<? }?>
                                  </span> 
                            	  <span class="SalePrice user_font_3">参考价:<span class="jsNum"><? echo $bestgoods['market_price'];?></span></span>  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <? }?>
                  </div>
                </div>
              </div>
              <div class="clear"></div>
<? }?>

            <? if($new){?>
<div class="stitle">新品推荐</div>
<div class="clear"></div>
              <div class="fn-clear user_s20019-493268">
                <div class="mc" style="width:1200px; "> 
                  <div class="user_item_box ">
                  <? foreach ($new as $_k => $bestgoods){?>
                    <div class="item <? if($_k+1 == 1){?>item_ts<? }?>" >
                      <div class="jPic"><a href="<?php echo SITE_URL . 'product.php?id=' . $bestgoods['goods_id']; ?>" target="_blank" class="user_border_hover_1"><img src="<? echo $bestgoods['goods_img'];?>" class="" alt="<? echo $bestgoods['goods_name']?>" height="232" width="232"></a>
                        <div class="jBuy jBuy_3 user_bgcolor_1" style="background-color:#66A232;">
                          <div class="jDesc">
                            <div class="jTitle user_border_dashed_bottom"><a class="user_a_3" href="<?php echo SITE_URL . 'product.php?id=' . $bestgoods['goods_id']; ?>" target="_blank" title="<? echo $bestgoods['goods_name']?>"><? echo $bestgoods['goods_name']?></a></div>
                            <div class="buy"> 
                            	<span class="jdNum">现价： <? if ($bestgoods['promote_price'] >0){?>
          								<? echo $bestgoods['promote_price'];?>
          							<? }else{?>
          							<? echo $bestgoods['pifa_price'];?>
          							<? }?>
                                  </span> 
                            	  <span class="SalePrice user_font_3">参考价:<span class="jsNum"><? echo $bestgoods['market_price'];?></span></span>  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                 <? }?>
                  </div>
                </div>
              </div>            
<div class="clear"></div>            
 <? }?>



             <? if ($category_goods){?>
<? foreach ($category_goods as $ginfo){?>
<div class="stitle"><? echo $ginfo['cat_name'];?></div>
<div class="clear"></div>
<div class="user_s20019-493268">
<? if ($ginfo['cat_img']){?>
  <a href="<? echo $ginfo['cat_url'];?>" target="_blank" class="cat_pic"><img src="<? echo $ginfo['cat_img'];?>" width="474" height="474" /></a> 
                <div class="mc"> 
                  <div class="user_item_box ">
                   <? foreach ($ginfo['goods'] as $_k => $bestgoods){?>
                    <div class="item <? if($_k+1 == 1){?>item_ts<? }?>" >
                      <div class="jPic"><a href="<? echo $bestgoods['url'];?>"  title="<? echo $bestgoods['goods_name'];?>" target="_blank" class="user_border_hover_1"><img src="<? echo $bestgoods['goods_img'];?>" class="" alt="<? echo $bestgoods['goods_name'];?>" height="232" width="232"></a>
                        <div class="jBuy jBuy_3 user_bgcolor_1" style="background-color:#66A232;">
                          <div class="jDesc">
                            <div class="jTitle user_border_dashed_bottom"><a class="user_a_3" href="<? echo $bestgoods['url'];?>" target="_blank" title="<? echo $bestgoods['goods_name'];?>"><? echo $bestgoods['goods_name'];?></a></div>
                            <div class="buy"> 
                            	<span class="jdNum">现价：
                                     <? if ($bestgoods['promote_price'] != ""){?>
          								<? echo $bestgoods['promote_price'];?>
          							<? }else{?>
          								<? echo $bestgoods['pifa_price'];?>
          							 <? }?>
                                  </span> 
                            	  <span class="SalePrice user_font_3">参考价:<span class="jsNum"><? echo $bestgoods['market_price'];?></span></span>  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                 <? }?>
                  </div>
                </div>
              </div>
<? }else{?>            
         <div class="mc" style="width:1200px"> 
                  <div class="user_item_box ">
                  <? foreach($ginfo['goods'] as $_k=>$bestgoods){?>
                    <div class="item <? if ($_k+1 == 1){?>item_ts<? }?>" >
                      <div class="jPic"><a href="<? echo $bestgoods['url'];?>"  title="<? echo $bestgoods['goods_name'];?>" target="_blank" class="user_border_hover_1"><img src="<? echo $bestgoods['goods_img'];?>" class="" alt="<? echo $bestgoods['goods_name'];?>" height="232" width="232"></a>
                        <div class="jBuy jBuy_3 user_bgcolor_1" style="background-color:#66A232;">
                          <div class="jDesc">
                            <div class="jTitle user_border_dashed_bottom"><a class="user_a_3" href="<? echo $bestgoods['url'];?>" target="_blank" title="<? echo $bestgoods['goods_name'];?>"><? echo $bestgoods['goods_name'];?></a></div>
                            <div class="buy"> 
                            	<span class="jdNum">现价：
                                     <? if ($bestgoods['promote_price'] != ""){?>
          								<? echo $bestgoods['promote_price'];?>
          							<? }else{?>
          								<? echo $bestgoods['pifa_price'];?>
          							 <? }?>
                                  </span> 
                            	  <span class="SalePrice user_font_3">参考价:<span class="jsNum"><? echo $bestgoods['market_price'];?></span></span>  </div>
                          </div>
                        </div>
                      </div>
                    </div>
                 <? }?>
                  </div>
                </div>
              </div>     
<? }?>
              <div class="clear"></div>
<? }?>
<? }?>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div style="height:0px; line-height:0px; clear:both;"></div>
  </div>
  
 <!--文章列表 start--> 

<!--文章列表 end-->   


<script type="text/javascript">
<!--
$(function(){
  $("#allCategoryHeader").hide();
  $("#allcate").hover(function(){
    $("#allCategoryHeader").show();
  },function(){

    $("#allCategoryHeader").hide();

    $("#allCategoryHeader").hover(function() {

      $("#allCategoryHeader").show();

    }, function() {
      $("#allCategoryHeader").hide();
    });
  });
});
//-->
</script>
<script type="text/javascript" src="themes/{$template_dir}/images/ghs/js/a_002.js"></script>


<style>
.zdy img{ position: relative;
     left: 50%; 
     margin-left: -960px; }
.ghs_content{overflow:hidden;padding:10px 0;width:100%;background: url(<? echo $s_info['site_background'];?>) no-repeat top center #f3f1f4;color:#333}
</style>