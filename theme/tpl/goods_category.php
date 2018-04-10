<link rel="stylesheet" href="css/css2.css" type="text/css">
<!---列表页--->
<div class="list_by">
  <div class="list_by_top">当前位置：<?php echo $rt['hear'];?></div>
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
  
  <?php $this->element('ajax_goods_connent',array('rt'=>$rt));?>
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
   		$.post(SITE_URL+'ajaxfile/ajax.php',{func:'catalog',type:'ajax_select_goods',cid:cid,bid:bid,price:price,attr:arrt_s},function(data){ 
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
   		$.post(SITE_URL+'ajaxfile/ajax.php',{func:'catalog',type:'ajax_select_goods',cid:cid,bid:bid,price:price,attr:arrt_s},function(data){ 
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