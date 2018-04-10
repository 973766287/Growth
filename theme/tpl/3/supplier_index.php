<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_style.css" rel="stylesheet" type="text/css" />
<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_category.css" rel="stylesheet" type="text/css" />
<link href="theme/tpl/<? echo $mb;?>/images/ghs/css/ghs_index.css" rel="stylesheet" type="text/css" />


<div class="banner">
   
      
            
	<ul id="slides">
        
                 <?php foreach($rt['ad'] as $_k=>$_v){?>
		<a href="<?php echo $_v['ad_url'];?>" target="_blank"><li><img src="<?php echo $_v['ad_img'];?>"></li></a>
		  <?php } ?>
                
                
	</ul>

    
</div>
<!--店铺公告 start-->
<div class="shop_notice">{$shop_notice}</div>
<!--店铺公告 end-->
<div class="ghs_content">
    <div class="promotionWrapper">
         <!-- #BeginLibraryItem "/library/recommend_new.lbi" --><!-- #EndLibraryItem -->
         <div class="right">
         	<!-- #BeginLibraryItem "/library/recommend_hot.lbi" --><!-- #EndLibraryItem -->
         </div>
         <div style="clear:both"></div>
    </div>
    <!-- #BeginLibraryItem "/library/recommend_best.lbi" --><!-- #EndLibraryItem -->  



<!-- {if $category_goods} -->
<!--{foreach from=$category_goods item=ginfo name=ginfo}-->
<div class="floorWrapper floorWrapper_{$smarty.foreach.ginfo.iteration}">
				<div class="floorTitle">
					<ul class="J_tabList">
						<li class="current" type="item">{$ginfo.cat_name}</li>
					</ul>
					<div class="floorLine"></div>
                    <!-- {if $ginfo.cat_pic} --> 
					<a href="{$ginfo.cat_pic_url}" target="_blank"><div class="floorLogo"><img src="{$ginfo.cat_pic}" width="189" height="50" /></div></a>
                    <!-- {/if} -->
				</div>
 				<div class="floorContent">
				 <!--{foreach from=$ginfo.goods item=bestgoods name=bestgoods}-->		
				<div class="itemOuter {if $smarty.foreach.bestgoods.iteration mod 4 eq 0}itemOuter_ts{/if}">
                	<div class="itemWrapper">
                    	<a href="{$bestgoods.url}"  title="{$bestgoods.name}" target="_blank">
                        	<img src="{$bestgoods.thumb}" alt="{$bestgoods.name}" height="220" width="220">
                            <h6>{$bestgoods.name}</h6>
                            <div class="priceSection">
                            	<div class="priceNumber">
                                	<span class="mainPrice"> <!-- {if $bestgoods.promote_price neq ""} -->
          								{$bestgoods.promote_price}
          							 <!-- {else}-->
          								{$bestgoods.shop_price}
          							 <!--{/if}--></span>
                                    <span class="subPrice"><del>{$bestgoods.market_price}</del></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!--{/foreach}--> 
      		 </div>				
		     <div style="clear:both"></div>
	 </div>
<!-- {/foreach} --> 
<!-- {/if} --> 



	          
    <div style="height:0px; line-height:0px; clear:both;"></div>
  </div>
  
 <!--文章列表 start--> 
<div class="tabs-panel">
  <ul class="mall-news">
    <!--{foreach from=$new_articles item=article_item name=name}-->
    <li><i></i><a target="_blank" href="{$article_item.url}" title="{$article_item.title}">{$article_item.short_title} 

</a> </li>
    <!--{/foreach}-->
  </ul>
</div>
<!--文章列表 end-->    


<script type="text/javascript">
Ajax.call('api/okgoods.php', '', '', 'GET', 'JSON');
//预售
Ajax.call('pre_sale.php?act=check_order', '', '', 'GET', 'JSON');
</script>



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