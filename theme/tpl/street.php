<link rel="stylesheet" href="css/css2.css" type="text/css">
<style>
.stlist_box{ width:1160px; margin:0px auto;}
.stlist_box ul{ width:1170px;}
.stlist_box ul li{ float:left; background:#fff; overflow:hidden; border:1px solid #e8e8e8; float:left; margin-right:10px; width:378px; height:315px; margin-top:10px;}
.stlist_box ul li p{ text-align:center; padding-top:15px; color:#999;}
.stlist_box_img{display: block;height:275px;overflow: hidden;}
.stlist_box_img img{display: block; width:100%;}
.stlist_box_logo{    display: block;width: 100px;height: 50px;margin: 20px auto 0;text-align: center;}
.stlist_box_logo img{ width:100%;}
</style>

<!---列表页--->
<div id="content">
    <div class="flow">
      <div class="cate_attr">
      <div class="nav-tag clearfix"> 
      	<h5 class="filter-label-ab">分类</h5>
        <div class="cate_attr_con">
        	<div class="filter-all-ab">
      			<a <? if(empty($rt['str_id'])){?> class="selected" <? }?> target="_self" href="suppliers.php?act=street"><span>全部</span></a>
            </div>
            <div class="district-tab">
        		<? foreach ($rt['street'] as $cat){?> 
        		<a <? if ($rt['str_id'] == $cat['str_id']){?> class="selected" <? }?> target="_self" href="suppliers.php?act=street&id=<? echo $cat['str_id'];?>"><span><? echo $cat['str_name'];?></span></a>
        		<? }?>
            </div> 
        </div>
      </div>
    </div>
     
     
     
     <div class="stlist_box">
  <ul>
  <? foreach($rt['categoodslist'] as $shop){?>
    <li><a href="?act=index&suppId=<? echo $shop['supplier_id'];?>">
       <div class="stlist_box_img"><img src="<? echo $shop['logo'];?>"></div>
      <!-- <div class="stlist_box_logo"><img src="img2/logo.png"></div>-->
       <p><? echo $shop['supplier_name'];?></p>
    </a></li>
   <? }?>
  </ul>
</div>

	  <!-- #BeginLibraryItem "/library/stores_pager.lbi" --><!-- #EndLibraryItem --> 
    </div>
    <!-- #BeginLibraryItem "/library/stores_tuijian.lbi" --><!-- #EndLibraryItem -->
    <div class="blank5"></div>
  </div>
