  <?php
$sale_count = $rt['thisorder']=='sale_count' ? ' class="ac" ' : "";
$add_time = !(isset($rt['thisorder']))||$rt['thisorder']=='goods_id' ? ' class="ac" ' : "";
$pifa_price = $rt['thisorder']=='pifa_price' ? ' class="ac" ' : "";
$is_new = $rt['thisorder']=='is_new' ? ' class="ac" ' : "";
$p1 = '￥';
$p2 = '￥';
if(!empty($rt['price'])){
	$p = explode('-',$rt['price']);
	$p1 = isset($p[0]) ? '￥'.$p[0] : '￥';
	$p2 = isset($p[1]) ? '￥'.$p[1] : '￥';
}
?>
  <div class="sortbar" id="sortpage">
	<div class="sortrow ">
		<ul class="sortnav">
<!--			<li asc="id"><a onclick="return goodList.SetAsc('de');" href="#">默认推荐</a></li>
			<li><a onclick="return goodList.SetAsc('re');" href="#">人气</a></li>
			<li class="current"><a onclick="return goodList.SetAsc('id');" href="#">新品</a></li>
			<li>
			<a href="#">销量<i></i></a>
			<p>
				<a onclick="return goodList.SetAsc('sd');" href="#">销量从高到低</a>
				<a onclick="return goodList.SetAsc('sa');" href="#">销量从低到高</a>
			</p>
			</li>
			<li>
			<a href="#">价格<i></i></a>
			<p>
				<a class="pricedown" onclick="return goodList.SetAsc('pd');" href="#">价格从高到低</a>
				<a class="priceup" onclick="return goodList.SetAsc('pa');" href="#">价格从低到高</a>
			</p>
			</li>-->
<li<?php echo $add_time;?>><a href="javascript:void(0)" rel="nofollow" onclick="clearstyle('goods_id');get_categoods_page_list('1','<?php echo $rt['thiscid'];?>','<?php echo $rt['thisbid'];?>','<?php echo $rt['price'];?>','goods_id','<?php echo $rt['sort']=='DESC' ? 'ASC' : 'DESC';?>','<?php echo $rt['limit'];?>','<?php echo $rt['thisattr'];?>')">默认</a></li>
		<li<?php echo $is_new;?>><a href="javascript:void(0)" rel="nofollow" onclick="clearstyle('is_new');get_categoods_page_list('1','<?php echo $rt['thiscid'];?>','<?php echo $rt['thisbid'];?>','<?php echo $rt['price'];?>','is_new','<?php echo $rt['sort']=='DESC' ? 'ASC' : 'DESC';?>','<?php echo $rt['limit'];?>','<?php echo $rt['thisattr'];?>')">新款</a></li>
		<li<?php echo $sale_count;?>><a href="javascript:void(0)" rel="nofollow" onclick="clearstyle('sale_count');get_categoods_page_list('1','<?php echo $rt['thiscid'];?>','<?php echo $rt['thisbid'];?>','<?php echo $rt['price'];?>','sale_count','<?php echo $rt['sort']=='DESC' ? 'ASC' : 'DESC';?>','<?php echo $rt['limit'];?>','<?php echo $rt['thisattr'];?>')">销量</a></li>
		<li<?php echo $pifa_price;?>><a href="javascript:void(0)" rel="nofollow" onclick="clearstyle('pifa_price');get_categoods_page_list('1','<?php echo $rt['thiscid'];?>','<?php echo $rt['thisbid'];?>','<?php echo $rt['price'];?>','pifa_price','<?php echo $rt['sort']=='DESC' ? 'ASC' : 'DESC';?>','<?php echo $rt['limit'];?>','<?php echo $rt['thisattr'];?>')">价格</a></li>
<!--		<li class="pricesearch">
		  <p><input type="text" name="minprice" class="txxt" value="<?php echo $p1;?>" onfocus="javascript:clearTip(this);" onblur="javascript:backTip(this, '￥');"/><span>-</span><input value="<?php echo $p2;?>" type="text" name="maxprice" class="txxt" onfocus="javascript:clearTip(this);" onblur="javascript:backTip(this, '￥');"/><input type="submit" name="Submit" class="subtxt" value="确认" onclick="return ajax_price_search()"/></p>
		</li>-->
		</ul>
		<div class="price">
			
				<input type="text" name="minprice" class="txxt" value="<?php echo $p1;?>" onfocus="javascript:clearTip(this);" onblur="javascript:backTip(this, '￥');"/><span>-</span><input value="<?php echo $p2;?>" type="text" name="maxprice" class="txxt" onfocus="javascript:clearTip(this);" onblur="javascript:backTip(this, '￥');"/><input type="submit" name="Submit" class="subtxt" value="确认" onclick="return ajax_price_search()"/>
				
			
		</div>
		<div class="pager">
<!--			<span class="prev">上一页</span>
			<span class="total"><b>1</b>/42</span>
			<a href="#" onclick="return goodList.Next();" class="next">下一页</a>-->
		</div>
		<div class="clear">
		</div>
	</div>
</div>
  <div class="list_bybox goodslist">
    <ul>
    <?php if(!empty($rt['categoodslist'])) foreach($rt['categoodslist'] as $row){?>
      <li>
         <a href="<?php echo SITE_URL.'product.php?id='.$row['goods_id'];?>">
             <img src="<?php echo $row['goods_thumb'];?>" alt="<?php echo $row['goods_name'];?>"/>
         <h3><em>￥<?php echo $row['pifa_price'];?></em><span>￥<?php echo $row['shop_price'];?></span></h3>
         <p><?php echo $row['goods_name'];?></p>
         <i>已有<?php echo $row['sale_count'];?>人购买</i>
         </a>
      </li>
      <?php } ?>
    </ul>
  </div>
  <div class="page" id="js_page">
      <?php if(!empty($rt['categoodspage'])){?>
<p class="pages">
    <span ><?php echo str_replace('首页','首页',$rt['categoodspage']['first']);?></span>
  <span><?php echo str_replace('上一页','上一页',$rt['categoodspage']['prev']);?></span>
<?php
 if(isset($rt['categoodspage']['list'])&&!empty($rt['categoodspage']['list'])){
 foreach($rt['categoodspage']['list'] as $aa){
 echo $aa."\n";
 }
 }
?>
<?php echo str_replace('下一页','下一页',$rt['categoodspage']['next']);?>
<?php echo str_replace('尾页','尾页',$rt['categoodspage']['last']);?>
<!--<em>到第<input type="text" name="pageindex" class="pageinput" value="<?php echo $rt['page']+1;?>" maxlength="4">页</em><input type="submit" name="Submit" class="subtxt" value="确认" onclick="get_categoods_page_list($('.pageinput').val(),'<?php echo $rt['thiscid'];?>','<?php echo $rt['thisbid'];?>','<?php echo $rt['price'];?>','<?php echo $rt['order'];?>','<?php echo $rt['sort'];?>','<?php echo $rt['limit'];?>','<?php echo $rt['thisattr'];?>')"/>-->
</p>
<?php } ?>
<!--      
	<span class="prev">&nbsp;</span>
        
        <span class="current">1</span>
        <a onclick="return goodList.Go(2)" href="#" rel="2">2</a>
        <a onclick="return goodList.Go(3)" href="#" rel="3">3</a>
        <a onclick="return goodList.Go(4)" href="#" rel="4">4</a>
        <a onclick="return goodList.Go(5)" href="#" rel="5">5</a>
        <a onclick="return goodList.Go(6)" href="#" rel="6">6</a>
        <a onclick="return goodList.Go(7)" href="#" rel="7">7</a>
        <a onclick="return goodList.Go(8)" href="#" rel="8">8</a>
        <a onclick="return goodList.Go(9)" href="#" rel="9">9</a>
        <a onclick="return goodList.Go(10)" href="#" rel="10">10</a>
        <span class="morepage">…</span>
        <a onclick="return goodList.Next()" class="next" href="#" rel="2">&nbsp;</a>-->
</div>