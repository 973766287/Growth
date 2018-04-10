
<link rel="stylesheet" href="css/css2.css" type="text/css">
<!--<script type="text/javascript" src="css/js/jquery-1.7.2.min.js"></script>-->
<!--<script src="css/js/jquery.SuperSlide.2.1.1.js" type="text/javascript"></script>
<script src="css/js/newsdetail.js" type="text/javascript"></script>-->
<style>
.score_detail{
	overflow: hidden;
    padding: 5px 0;
    margin-right: 5px;
    border-top: 1px dotted #c6c6c6;
    border-bottom: 1px dotted #c6c6c6;
	}
.score_detail li {
    width: 61px;
    float: left;
    text-align: center;
    border-left: 1px dotted #c6c6c6;
}
.score_detail li span.scores {
    color: #fe596a;
}
</style>
<script type="text/javascript">
$(function(){			
$(".jqzoom").jqueryzoom({
	xzoom:400,
	yzoom:470,
	offset:10,
	position:"right",
	preload:1,
	lens:1
});
$("#spec-list").jdMarquee({
	deriction:"left",
	width:470,
	height:78,
	step:2,
	speed:4,
	delay:10,
	control:true,
	_front:"#spec-right",
	_back:"#spec-left"
});
$("#spec-list img").bind("mouseover",function(){
	var src=$(this).attr("src");
	$("#spec-n1 img").eq(0).attr({
		src:src.replace("\/n5\/","\/n1\/"),
		jqimg:src.replace("\/n5\/","\/n0\/")
	});
	$(this).css({
		"border":"1px solid #ff6600",
		"padding":"1px"
	});
}).bind("mouseout",function(){
	$(this).css({
		"border":"1px solid #ccc",
		"padding":"2px"
	});
});				
})
</script>

<div class="list_by">
  <div class="list_by_top">当前位置：<?php echo $rt['hear'];?></div>
  <div class="inby">
    <div class="fl">
        <div id="preview">
					<div class="jqzoom" id="spec-n1">
					<img onload="loadimg2(this,470,470)"  src="<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>" jqimg="<?php echo SITE_URL.$rt['goodsinfo']['original_img'];?>" width="400" alt="">
					</div>
					<div id="spec-n5">
						<div class="control" id="spec-left">
							<img src="<?php echo $this->img('left.gif');?>">
						</div>
						<div id="spec-list">
							<ul class="list-h">
							<?php if(!empty($rt['gallery'])) foreach($rt['gallery'] as $row ){?>
								<li><img src="<?php echo $row['original_img'];?>" alt="<?php echo $row['img_desc'];?>" id="<?php echo $row['original_img'];?>" width="60" height="76" style="border: 1px solid rgb(204, 204, 204); padding: 2px;"> </li>
							<?php } ?>
							</ul>
						</div>
						<div class="control" id="spec-right">
							<img src="<?php echo $this->img('right.gif');?>">
						</div>
						
					</div>
				</div>
    <!-- <div class="detail_context_pic">
	<div class="detail_context_pic_top">
		<a href="#"><img src="" alt="" id="pic1" curindex="0" /></a> 
		
	</div>

	<div class="detail_context_pic_bot">
		<div class="detail_picbot_left">
			<a href="javascript:void(0)" id="preArrow_B"><img src="css/img/left1.jpg" alt="上一个" /></a> 
		</div>
		<div class="detail_picbot_mid">
			<ul>
                            <?php if(!empty($rt['gallery'])) foreach($rt['gallery'] as $row ){?>
				<li>
                                                    <a href='javascript:void(0);'>
                                                    <img src="<?php echo $row['goods_thumb'];?>" alt="<?php echo $row['img_desc'];?>" width='90px' height='60px'  bigimg='<?php echo $row['original_img'];?>' />
                                                    </a>
                                                </li>
                                                <?php } ?>
			</ul>
		</div>
		<div class="detail_picbot_right">
			<a href="javascript:void(0)" id="nextArrow_B"><img src="css/img/right1.jpg" alt="下一个" /></a> 
		</div>
	</div>
</div>-->
    </div>
    <div class="inby_rg fr buyclass">
        
        <div class="flash_box_con_ct">	<h3><?php echo $rt['goodsinfo']['goods_name'];?></h3>
       <?php echo $rt['goodsinfo']['sort_desc'];?></div>
      <div class="in_rg_pri">价格<strong>￥<?php echo $p2 = format_price($rt['goodsinfo']['pifa_price']);?></strong><?php  $p1 = format_price($rt['goodsinfo']['shop_price']);?><span>参考价:<?php echo $p1;?></span><span><?php echo (@format_price($p2/$p1))*10;?></i>折</span></div>
    	
      <?php if($rt['goodsinfo']['is_promote']=='1' && $rt['goodsinfo']['promote_end_date'] > mktime()){?>
			 	 <div class="in_rg_pri">促销价：
				<span>￥<?php echo $p4 = $rt['goodsinfo']['promote_price']?></span>&nbsp;&nbsp;<b id="lefttime_2" style="font-size:16px">--:--:--</b>
				</div>
				<?php } ?>
      <form id="ECS_FORMBUY" name="ECS_FORMBUY" method="post" action="">
      <div class="in_rg_con">
        <div class="fl">
          <div class="in_rg_con_con">
          <div class="fl" style="font-size:16px; width:77px; color:#999;">库&nbsp;&nbsp;&nbsp;存</div>
		 <!-- <div class="fl">韩国至<select>
		    <option>烟台</option>
		  </select></div>-->
          <div class="fl" style="margin-left:8px;"><?php echo $rt['goodsinfo']['goods_number'] ? '有货':'缺货';?> </div> 
	  </div>
          <div class="in_rg_con_con">
          <div class="fl" style="font-size:16px; width:77px; color:#999;">数量</div>
		<!--  <div   desc="填写数字" id="number" style="text-align:center; float:left;">
			 <div class="less">-</div>
			 <div class="value" contenteditable="true" id="numDigits">1</div>
			 <div class="more">+</div>
		  </div>-->
                 <input type="text" name="number" size="5" value="1" style="height:24px;line-height:24px;"/>
                   <input type="hidden" name="price" id="btprice" value="0" />
<!--          <div class="fl"  style="margin-left:8px;">单次限购1件</div> -->
	  </div>
            		  <?php
					  if(!empty($rt['spec'])){
							echo '<p style="height:30px; line-height:30px;">请选择：<strong style="color:#999">';
							 foreach($rt['spec'] as $row){
									if(empty($row)||!is_array($row) || $row[0]['is_show_cart']==0) continue;
									$rl[$row[0]['attr_keys']] = $row[0]['attr_name'];
									$attr[$row[0]['attr_keys']] = $row[0]['attr_is_select'];
							}
							if(!empty($rl))  echo implode('、',$rl);
					   ?>
					  </strong>
					  </p>
					  <?php
					   } //end if
							if(!empty($rt['spec'])){
							foreach($rt['spec'] as $row){
							if(empty($row)||!is_array($row) || $row[0]['is_show_cart']==0) continue;

					  ?>
  <?php if(!empty($row[0]['attr_name'])){?>
					  <p class="spec_p"><span><?php  echo $row[0]['attr_name'].":";?></span>
							  <?php
							  if($row[0]['attr_is_select']==3){ //复选
									 foreach($row as $rows){
													$st .= '<label><input type="checkbox" name="'.$row[0]['attr_keys'].'" id="quanxuan" value="'.$rows['attr_value'].'" />&nbsp;'.$rows['attr_value']."</label>\n";
									  }
									  echo $st .='<label class="quxuanall" id="ALL" style="border:1px solid #ADADAD; background-color:#E1E5E6; padding-left:3px; height:18px; line-height:18px;padding:2px;">全选</label>';
							  }else{
									  echo '<input type="hidden" name="'.$row[0]['attr_keys'].'" value="" />'."\n";
									  foreach($row as $rows){
											if(!empty($rows['attr_addi']) && @is_file(SYS_PATH.$rows['attr_addi'])){//如果是图片
												echo '<a lang="'.trim($rows['attr_addi']).'" href="javascript:;" name="'.$row[0]['attr_keys'].'" id="'.trim($rows['attr_value']).'"><img src="'.(empty($rows['attr_addi']) ? 'theme/images/grey.png':$rows['attr_addi']).'" alt="'.$rows['attr_value'].'" title="'.$rows['attr_value'].'" width="40" height="50" /></a>';
											}else{
												echo '<a lang="'.trim($rows['attr_addi']).'" href="javascript:;" name="'.$row[0]['attr_keys'].'" id="'.trim($rows['attr_value']).'">'.$rows['attr_value'].'</a>';
											}
									  }
							  } //end if
							?>
							
					  </p><?php } ?><div class="clear"></div>
									<?php } // end foreach
									
					  } //end if?>
        </div>
      </div>
        <div class="in_rg_btn">
<!--        <img src="<?php echo $this->img('in01.jpg');?>" width="163" height="46" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>')" />-->
          <img src="<?php echo $this->img('in02.jpg');?>" width="163" height="46" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>')"/> 
         <a href="#"><img src="<?php echo $this->img('in03.jpg');?>" width="53" height="46" onclick="return addToColl('<?php echo $rt['goodsinfo']['goods_id'];?>')"/></a>
<!--         <a href="#"><img src="<?php echo $this->img('in04.jpg');?>" width="53" height="46"></a>-->
        </div>
      	</form>
    </div>
  </div>
  <div class="inby_bot">
    <div class="inby_bot_lf fl">
    
    
    <? if($rt['goodsinfo']['supplier_id']){?>
    <div class="inby_bot_lf_con">
        <h2>店铺信息</h2>
        
        <div class="inby_bot_lf_con_stro">
        <div id="brand-bar-pop">
  <dl id="zy_shop" style="border-bottom:1px solid #ddd; text-align:center;padding-top:5px;padding-bottom:5px;*padding-top:5px;*padding-bottom:5px;">
    <dt class="shop_title">  <h3><? echo $rt['goodsinfo']['site_name'];?></h3>  </dt>
    <div class="ghs_clear"></div>
    <dd></dd>
  </dl>
     <dl id="hotline" style="padding-top:5px;">
    <dt>店铺等级&nbsp;&nbsp;&nbsp;&nbsp;<? echo $rt['goodsinfo']['rank_name'];?></dt>
    <dd> </dd>
    <div class="ghs_clear"></div>
  </dl>
   
  
<dl id="hotline" style="padding-top:5px;">
<? if(!empty($s_info['qq'])){?>
<dt style="float:left;">客服 QQ&nbsp;&nbsp;&nbsp;&nbsp;

 <? foreach ($s_info['qq'] as $im){?> 
       <? if ($im){?> 
     <dd style="padding-left:65px;">
      <a style="padding-left: 20px;
    color: #f84c4c;
    background: url(images/53c493dbN0395a7b7.png) 1px no-repeat;
    text-decoration: none;" href="http://wpa.qq.com/msgrd?V=1&amp;uin=<? echo $im;?>&amp;Site=<? echo $s_info['site_name'];?>&amp;Menu=yes" target="_blank" title="<? echo $im;?>">在线客服</a>
    </dd>
   
       <? }}?>
       
       </dt>
       <? }?>
                   </dl>

  
           <div>保 证 金&nbsp;&nbsp;&nbsp;&nbsp; <img style="vertical-align:-5px;" src="images/k0.jpg" width="19" height="19"><img style="vertical-align:-5px;" src="images/k1.jpg" width="19" height="19"><span style="border:1px solid #00ba97; height:14px; font-size:12px; line-height:14px; padding:0 5px; margin:0; color:#00ba97;"><? echo $rt['goodsinfo']['system_fee'];?>元</span></div>
   
  
  <dl id="hotline" style="padding-top:5px;">
    <ul class="score_detail">
  	<li style="border-left:0;">
    	<span>描述</span>
        <span class="scores">5.0</span>
    </li>
    <li>
    	<span>服务</span>
        <span class="scores">5.0</span>
    </li>
    <li>
    	<span>物流</span>
        <span class="scores">5.0</span>
    </li>
  </ul>
    </dl>
    
    <p><a style="margin: 0 55px;" href="suppliers.php?act=index&suppId=<? echo $rt['goodsinfo']['supplier_id'];?>" target="_blank">进入店铺</a></p>
     </div>
 
        </div>
        
        
        
        
        
        
      </div>
      <? }?>
      
      
      <div class="inby_bot_lf_con">
        <h2>相关产品</h2>
        <div class="inby_bot_lf_con_rank">
           <ul>
           <?php if(!empty($rt['relategoods']))foreach($rt['relategoods'] as $k=>$row){ if($k>8) break;?>
             <li>
             <a href="<?php echo SITE_URL;?>product.php?id=<?php echo $row['goods_id'];?>">
                 <img src="<?php echo $row['goods_thumb'];?>" alt="<?php echo $row['goods_name'];?>"/> 
                <?php echo $row['short_title'];?>
                 <br><strong> ￥<?php echo $row['pifa_price'];?></strong>
               </a>
             </li>
             	<?php } ?>
           </ul>
        </div>
      </div>
      <div class="inby_bot_lf_con">
        <h2>销售排行</h2>
        <div class="inby_bot_lf_con_brows">
          <ul>
           <?php if(!empty($rt['top10']))foreach($rt['top10'] as $k=>$row){ if($k>8) break;?>
             <li>
             <a href="<?php echo SITE_URL;?>product.php?id=<?php echo $row['goods_id'];?>">
                 <img src="<?php echo $row['goods_thumb'];?>" alt="<?php echo $row['goods_name'];?>"/> 
                <?php echo $row['goods_name'];?>
                 <br><strong> ￥<?php echo $row['pifa_price'];?></strong>
               </a>
             </li>
             	<?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="inby_bot_rg fr">
         <div class="inby_bot_rg_top"> 
         <ul id="tabs">
    <li><a href="#" title="tab1">产品详情</a></li>
    <li><a href="#" title="tab2">口碑评价</a></li>
<!--    <li><a href="#" title="tab3">商品属性</a></li>-->
    </li>    
</ul>
<div id="content"> 
    <div id="tab1">
      <h2>品牌名称：<span style="color:#0550C0;" ><?php echo $rt['brand_name'];?></span>
<!--          <a class="guanzhu" href="#"><span><img src="css/img/hr1.png" style="margin-right:3px;" width="10" height="10"></span><strong style="color:#fff;">关注</strong></a>-->
      
      </h2>
      <h3>产品参数</h3>
      <ul>
            <?php 
						  if(!empty($rt['spec'])){
							foreach($rt['spec'] as $row){
							if(empty($row)||!is_array($row)) continue;
						  ?>
      <li><?php  echo $row[0]['attr_name'].":";?>：  <?php
								  foreach($row as $rows){
									echo '<span class="valuess">'.trim($rows['attr_value']).'</span>';
								  }
								?></li>
 <?php } ?>						  
						  <?php } // end foreach
										
						  ?>
      </ul>
    </div>
    <div id="tab2">
        	<table width="310" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td colspan="3"><div style="line-height:18px; height:18px; padding-left:20px">商品评价ss</div></td>
					  </tr>
					  <tr>
						<td rowspan="4" width="111">
							<p style="font-size:14px;line-height:24px;height:24px;"><u><?php echo isset($rt['rank_count'][3]) ? intval(($rt['rank_count'][3]/$rt['rank_count']['zcount'])*100) : 100;?>%</u></p>
							<p style="font-size:14px;line-height:24px;height:24px;">■好评度</p>                                </td>
						<td colspan="2" style="margin:0px; padding:0px;"><p class="cc"><b style="font-size:10px">■</b> 好评 <img src="<?php echo $this->img('pl.png');?>" width="<?php echo isset($rt['rank_count'][3]) ? ($rt['rank_count'][3]/$rt['rank_count']['zcount'])*120 : 120;?>" height="12" />&nbsp;&nbsp;<span style="font-size:12px;"><?php echo isset($rt['rank_count'][3]) ? intval(($rt['rank_count'][3]/$rt['rank_count']['zcount'])*100) : 100;?>%</span></p></td>
					  </tr>
					  <tr>
						<td colspan="2" style="margin:0px; padding:0px;"><p class="cc"><b style="font-size:10px">■</b> 中评 <img src="<?php echo $this->img('pl.png');?>" width="<?php echo isset($rt['rank_count'][2]) ? ($rt['rank_count'][2]/$rt['rank_count']['zcount'])*120 : 0;?>" height="12" />&nbsp;&nbsp;<span style="font-size:12px;"><?php echo isset($rt['rank_count'][2]) ? intval(($rt['rank_count'][2]/$rt['rank_count']['zcount'])*100) : 0;?>%</span></p></td>
					  </tr>
					  <tr>
						<td colspan="2" style="margin:0px; padding:0px;"><p class="cc"><b style="font-size:10px">■</b> 差评 <img src="<?php echo $this->img('pl.png');?>" width="<?php echo isset($rt['rank_count'][1]) ? ($rt['rank_count'][1]/$rt['rank_count']['zcount'])*120 : 0;?>" height="12" />&nbsp;&nbsp;<span style="font-size:12px;"><?php echo isset($rt['rank_count'][1]) ? intval(($rt['rank_count'][1]/$rt['rank_count']['zcount'])*100) : 0;?>%</span></p></td>
					  </tr>
					  <tr>
						<td width="100" align="center"><img src="<?php echo $this->img('asked.gif');?>" width="99" height="32" onclick="return ajax_check_comment('<?php echo $rt['goodsinfo']['goods_id'];?>','<?php echo $rt['goodsinfo']['supplier_id'];?>')" style="cursor:pointer"/></td>
						<td align="center"><!--<a href="<?php echo SITE_URL;?>comment/g<?php echo $rt['goodsinfo']['goods_id'];?>/">查看所有评论</a>--></td>
					  </tr>
					</table>
        
        <div class="GOODSCOMMENT">
				<?php $this->element('ajax_comment',array('rt'=>array('commentlist'=>$rt['commentlist'],'commentpage'=>$rt['commentpage'])));?>
				</div>
<!--      <ul>
        <li>
          <h3 class="tab2_lf fl"><a href="#"><img src="css/img/usero.jpg" ><br>用***称</a></h3>
          <h4 class="tab2_rg fr">吃了一个挺甜的，每一个都看了，卖相也挺好的，家里人特别是孩子都喜欢吃这个口感。据说是冷库保存的，不知道怎么保存的这么好，不但甜，重点是口感很脆，和新鲜的没什么差别，很喜欢，我们都是水冲冲直接连皮吃的，好吃！下次还要买！
           <p><a href="#"><span>有用</span>(0)</a>2015年08月17日 13:48</p>
          </h4>
        </li>
        <li>
          <h3 class="tab2_lf fl"><a href="#"><img src="css/img/usero.jpg" ><br>用***称</a></h3>
          <h4 class="tab2_rg fr">吃了一个挺甜的，每一个都看了，卖相也挺好的，家里人特别是孩子都喜欢吃这个口感。据说是冷库保存的，不知道怎么保存的这么好，不但甜，重点是口感很脆，和新鲜的没什么差别，很喜欢，我们都是水冲冲直接连皮吃的，好吃！下次还要买！
           <p><a href="#"><span>有用</span>(0)</a>2015年08月17日 13:48</p>
          </h4>
        </li>
        <li>
          <h3 class="tab2_lf fl"><a href="#"><img src="css/img/usero.jpg" ><br>用***称</a></h3>
          <h4 class="tab2_rg fr">吃了一个挺甜的，每一个都看了，卖相也挺好的，家里人特别是孩子都喜欢吃这个口感。据说是冷库保存的，不知道怎么保存的这么好，不但甜，重点是口感很脆，和新鲜的没什么差别，很喜欢，我们都是水冲冲直接连皮吃的，好吃！下次还要买！
           <p><a href="#"><span>有用</span>(0)</a>2015年08月17日 13:48</p>
          </h4>
        </li>
        <li>
          <h3 class="tab2_lf fl"><a href="#"><img src="css/img/usero.jpg" ><br>用***称</a></h3>
          <h4 class="tab2_rg fr">吃了一个挺甜的，每一个都看了，卖相也挺好的，家里人特别是孩子都喜欢吃这个口感。据说是冷库保存的，不知道怎么保存的这么好，不但甜，重点是口感很脆，和新鲜的没什么差别，很喜欢，我们都是水冲冲直接连皮吃的，好吃！下次还要买！
           <p><a href="#"><span>有用</span>(0)</a>2015年08月17日 13:48</p>
          </h4>
        </li>
      </ul>-->
    </div>
<!--    <div id="tab3">
该卖家已缴纳15000.0元保证金。
在确认收货15天内，如有商品质量问题、描述不符或未收到货等，您有权申请退款或退货，来回邮费由卖家承担。    </div>-->
  
</div><script type="text/javascript">

			
$('.pro_rank img').live('mouseover',function(){
len = $(this).attr('id');
	if(len>0){
		$('.pro_rank img').each(function(i){
			if(i<len){
				$(this).attr('src',SITE_URL+'theme/images/01.jpg');
			}else{
				$(this).attr('src',SITE_URL+'theme/images/02.jpg');
			}
		});
		$('.comment_con input[name="goods_rand"]').val(len);
	}
});
$('.sp_rank img').live('mouseover',function(){
len = $(this).attr('id');
	if(len>0){
		$('.sp_rank img').each(function(i){
			if(i<len){
				$(this).attr('src',SITE_URL+'theme/images/01.jpg');
			}else{
				$(this).attr('src',SITE_URL+'theme/images/02.jpg');
			}
		});
		$('input[name="shopping_rand"]').val(len);
	}
});
$('.sale_rank img').live('mouseover',function(){
len = $(this).attr('id');
	if(len>0){
		$('.sale_rank img').each(function(i){
			if(i<len){
				$(this).attr('src',SITE_URL+'theme/images/01.jpg');
			}else{
				$(this).attr('src',SITE_URL+'theme/images/02.jpg');
			}
		});
		$('input[name="saleafter_rand"]').val(len);
	}
});


</script>
<script>
$(document).ready(function() {
	$("#content div").hide(); // Initially hide all content
	$("#tabs li:first").attr("id","current"); // Activate first tab
	$("#content div:first").fadeIn(); // Show first tab content
    
    $('#tabs a').click(function(e) {
        e.preventDefault();        
        $("#content div").hide(); //Hide all content
        $("#tabs li").attr("id",""); //Reset id's
        $(this).parent().attr("id","current"); // Activate this
        $('#' + $(this).attr('title')).fadeIn(); // Show content for current tab
    });
})();
</script>
         </div>
         <div class="inby_bot_rg_bot"><?php echo $rt['goodsinfo']['goods_desc'];?></div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('input[name="number"]').change(function(){
	vall = $(this).val();
	if(!(vall>0)){
		$(this).val('1');
	}
});

$('.buyclass .spec_p a').click(function(){
	na = $(this).attr('name');
	vl = $(this).attr('id');
	$('.buyclass input[name="'+na+'"]').val(vl);
	
	$(this).parent().find('a').each(function(i){
	   this.style.border='1px solid #cbcbcb';
	});
	
	$(this).css('border','1px solid #FF0000');
	
	var src = $(this).find('img').attr('src');
	if(typeof(src)!='undefined' && src!=""){
		$('.jqzoom').attr('href',src);
		$('.jqzoom img').attr('src',src);
		$('.jqzoom img').attr('jqimg',src);
	}
	
	price = $(this).attr('lang');
	if(price>0){
		$('.yt-num').html('￥'+price);
		$('#btprice').val(price);
	}
	return false;
});

			
function checkcartattr(){
	<?php 
	if(!empty($rl)){
		foreach($rl as $k=>$v){?>
		a<?php echo $k;?> = $('.buyclass input[name="<?php echo $k;?>"]<?php echo $attr[$k]==3 ? ':checked' : "";?>').val();
		if(a<?php echo $k;?> ==""||typeof(a<?php echo $k;?>)=='undefined'){
		  alert("必须选择<?php echo $v;?>");
		  return false;
		}
	<?php } }?>
	return true;
}


var dt = '<?php echo $rt['goodsinfo']['is_promote']&&$rt['goodsinfo']['promote_start_date']<mktime() ? ($rt['goodsinfo']['promote_end_date']-mktime()) : ($rt['goodsinfo']['promote_end_date']-$rt['goodsinfo']['promote_start_date']);?>';
var st = new showTime('2', dt);  
st.desc = "促销结束";
st.preg = "倒计时	{a}天	{b}:{c}:{d}";
st.setid = "lefttime_";
st.setTimeShow(); 
</script>

<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
