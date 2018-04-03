

<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_style.css" rel="stylesheet" type="text/css" />
<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_category.css" rel="stylesheet" type="text/css" />
<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_index.css" rel="stylesheet" type="text/css" />


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
