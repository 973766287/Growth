

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
      
        <div class="ghs_score"><span>描述</span><em><? if(!empty($fenshu['sjms'])){echo $fenshu['sjms'];}else{?>5.0<? }?></em></div>
      <div class="ghs_score"><span>服务</span><em><? if(!empty($fenshu['fwtd'])){echo $fenshu['fwtd'];}else{?>5.0<? }?></em></div>
      <div class="ghs_score"><span>物流</span><em><? if(!empty($fenshu['wlsd'])){echo $fenshu['wlsd'];}else{?>5.0<? }?></em></div>
      
      
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
            <a  href="category.php?cid=<? echo $_v['id'];?>&amp;suppId=<? echo $suppId;?> " style="background:<? if(!empty($s_info['shop_header_color'])){echo $s_info['shop_header_color'];}else{echo "#66A232";}?>"><?php echo $_v['name'];?></a>
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




              <link rel="stylesheet" href="css/css2.css" type="text/css">
<!---列表页--->
<div class="list_by">
  
  <div class="list_by_tlt">
    <ul>
      <li><p>类别：</p><div>	<a href="javascript:;" class="selectgoods<?php echo $rt['thiscid']=='0' ? ' ac' : '';?>" id="0" name="goods">不限</a>
						<?php if(!empty($rt['menu_show']))foreach($rt['menu_show'] as $row){
								echo '<a href="javascript:;" class="selectgoods'.($rt['thiscid']==$row['id'] ? ' ac':'').'" name="goods"'.(isset($_GET['cid'])&&$_GET['cid']==$row['id']?' style="color:#88071B"':'').' id="'.$row['id'].'">'.$row['name'].'</a>';
						
/*								if(!empty($row['cat_id'])){
									echo '<p class="p2">';
									foreach($row['cat_id'] as $rows){
									echo '<a href="javascript:;" class="selectgoods" name="goods"'.(isset($_GET['cid'])&&$_GET['cid']==$rows['id']?' style="color:#88071B"':'').' id="'.$rows['id'].'">'.$rows['name'].'</a>';
									}
									echo '</p>';
								}*/
						 } ?></div></li>
       <?php
		  if(!empty($rt['attr'])){
			  $k=0;
			  foreach($rt['attr'] as $row){
			  $k++;
		   ?>
        <li><p><?php echo $row[0]['attr_name'];?>：</p><div><a href="javascript:;" class="selectgoods ac" id="0" name="attr" lang="<?php echo ($k-1);?>">不限</a>
						 <?php 
						  foreach($row as $rows){
							  echo '<a href="javascript:;" class="selectgoods" id="'.$rows['attr_value'].'" name="attr" lang="'.($k-1).'">'.$rows['attr_value'].'</a>';
						  }
						  ?></div></li>
        	<?php } } ?>	 
<!--      <li><p>品牌：</p><div><a href="#">Nutrilon/诺优能</a><a href="#">KARICARE/可瑞康</a><a href="#">FIRMUS/飞鹤</a><a href="#">君乐宝</a><a href="#">BEINGMATE/贝因美</a><a href="#">Nutrilon/诺优能</a><a href="#">KARICARE/可瑞康</a><a href="#">FIRMUS/飞鹤</a><a href="#">君乐宝</a><a href="#">BEINGMATE/贝因美</a></div></li>
      <li><p>大牌奶粉：</p><div><a href="#">母婴用品<span>(156)</span></a><a href="#">婴儿奶粉<span>(156)</span></a><a href="#">孕妇用品<span>(156)</span></a></div></li>
      <li><p>原产地：</p><div><a href="#">日本</a><a href="#">美国</a><a href="#">韩国</a><a href="#">澳大利亚</a></div></li>
      <li><p>包装种类：</p><div><a href="#">灌装</a><a href="#">盒装</a><a href="#">袋装</a><a href="#">箱装</a></div></li>-->
      <li style="border-bottom:0;"><p>品牌：</p><div>
              <a href="javascript:;" class="selectgoods<?php echo $rt['thisbid']=='0' ? ' ac' : '';?>" name="brand" id="0">不限</a>
						<?php if(!empty($rt['brandlist'])){ foreach($rt['brandlist'] as $row){?>
						<a href="javascript:;" class="selectgoods<?php echo $rt['thisbid']==$row['brand_id'] ? ' ac':'';?>" name="brand" id="<?php echo $row['brand_id'];?>"><?php echo $row['brand_name'];?></a>
						<?php } } ?></div></li>
    </ul>
  </div>
  

  
  <div class="AJAX-PRODUCT-CONNENT">
  
  <?php $this->element('ajax_goods_connents',array('rt'=>$rt));?>
  </div>
</div>
<script language="javascript" type="text/javascript">
function clearstyle(ty){
	$.cookie('THISORDER',ty);
}

function setshowtype(type){
	if(type==""||typeof(type)=="undefined") var type="list";
	$.cookie('DISPLAY_TYPE',type);
	window.location.reload();
	return false;
}
function setshowlimit(obj){
	$.cookie('DISPLAY_LIMIT',$(obj).val());
	window.location.reload();
	return false;
}
</script>
<script language="javascript" type="text/javascript">
	var arrt = new Array();
	<?php
	  $k=0;
	  if(!empty($rt['attr']))foreach($rt['attr'] as $row){
		  $k++;
	 ?>
	arrt[<?php echo $k-1;?>] = 0;
	<?php }?>		   
	var price = '';
	var cid = <?php echo $rt['cateinfo']['cat_id']>0?$rt['cateinfo']['cat_id']:0;?>;
	var bid = 0;
   
   
   function ajax_price_search(){
	   
	   suppId = <? echo $suppId;?>;
   		minprices = $('input[name="minprice"]').val();
		maxprices = $('input[name="maxprice"]').val();
		//minprice = $('#minprice').val();
		//maxprice = $('#maxprice').val();
		minprices=minprices.replace('￥','');
		maxprices=maxprices.replace('￥','');
		if(!(minprices>0)){ 
			return false;
		}
		if(!(maxprices>0)){
			return false;
		}
		
		price = minprices+'-'+maxprices;
		arrt_s = arrt.join("|");
		createwindow();
   		$.post(SITE_URL+'ajaxfile/ajax.php',{func:'categorys',type:'ajax_select_goods',cid:cid,bid:bid,price:price,attr:arrt_s,supplier_id:suppId},function(data){ 
			removewindow();
			if(data !=""){ 
				$('.AJAX-PRODUCT-CONNENT').html(data);
			}
		});
		return false;
		
   }
   
   $('.selectgoods').click(function(){	
		key = $(this).attr('name');
		ids = $(this).attr('id');
		suppId = <? echo $suppId;?>;
		if(key=='price'){
			price = ids;
		}else if(key=='goods'){
			cid = ids;
		}else if(key=='brand'){
			bid = ids;
		}else if(key=='attr'){
			la = $(this).attr('lang');
			arrt[la] = ids;
		}

		arrt_s = arrt.join("|");
		
		$(this).parent().parent().find('a').removeClass("ac");
		$(this).addClass("ac");
			
		createwindow();
   		$.post(SITE_URL+'ajaxfile/ajax.php',{func:'categorys',type:'ajax_select_goods',cid:cid,bid:bid,price:price,attr:arrt_s,supplier_id:suppId},function(data){ 
			removewindow();
			if(data !=""){ 
				$('.AJAX-PRODUCT-CONNENT').html(data);
			}
		});
		return false;
   });
   
   function clearcid(){
   		cid = 0;
   }
   function clearbid(){
   		bid = 0;
   }

</script>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="blank "></div>
<div style="height:0px; line-height:0px; clear:both;"></div>
</div>
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

<script type="text/javascript" src="themes/tpl/<? echo $mb;?>/images/ghs/js/a_002.js"></script>
