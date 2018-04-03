<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">

<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>



<!-- 订单搜索 -->
<div class="form-div">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />    
    类别:<select name="supplier_type">
	<option value="0">请选择</option>
	<? foreach ($str_category as $name){?>
	<option value="<? echo $name['str_id'];?>" <?php if(isset($_GET['supplier_type'])&&$_GET['supplier_type']==$name['str_id']){ echo 'selected="selected""'; } ?>><? echo $name['str_name'];?></option>
	<? }?>
	</select>
    店铺名称:<input name="supplier_name" type="text" id="supplier_name" size="15">
    状态<select name="is_show" id="is_show"><option value="-1">请选择</option><option value="0">下线</option><option value="1">显示中</option></select>
    <input type="button" value="搜索" class="cate_search" />

</div>

<!-- 订单列表 -->
<form method="post" action="supplier_street.php?act=remove_show" name="listForm" onsubmit="return check()">
  <div class="list-div" id="listDiv">


<table cellpadding="3" cellspacing="1">
  <tr>
  <th align=left><label><input type="checkbox" class="quxuanall" value="checkbox" />店铺id</label></th>
    <th>店铺类别</th>
	<th>店铺名称</th>
    <th>是否显示</th>
    <th>是否推荐</th>
	<th>审核状态</th>
	<th>店铺标签</th>
    <th>排序</th>
    <th>操作</th>
  <tr>
  <? foreach ($shops_list as $shop){?>
  <tr>
  <td><input type="checkbox" name="quanxuan" value="<? echo $shop['supplier_id'];?>" class="gids"/><? echo $shop['supplier_id'];?></td>
    <td align="center"  nowrap="nowrap"><? echo $shop['str_name'];?></td>
	<td align="center"  nowrap="nowrap"><? echo $shop['supplier_name'];?></td>
	<td align="center"  nowrap="nowrap">
    <img class="activeop" src="<?php echo $this->img($shop['is_show']==1 ? 'yes.gif' : 'no.gif');?>"  alt="<?php echo $shop['is_show']==1 ? '0' : '1';?>" lang="is_show" id="<? echo $shop['supplier_id'];?>" />
    </td>
    <td align="center"  nowrap="nowrap">
    <img class="activeop" src="<?php echo $this->img($shop['is_groom']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $shop['is_groom']==1 ? '0' : '1';?>" lang="is_groom"   id="<? echo $shop['supplier_id'];?>"/>
    </td>
	<td align="center"  nowrap="nowrap">
     <img class="activeop" src="<?php echo $this->img($shop['status']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $shop['status']==1 ? '0' : '1';?>" 
     lang="status"   id="<? echo $shop['supplier_id'];?>"/>
    </td>
	<td align="center" nowrap="nowrap">
	<? foreach ($shop['taginfo'] as $tag){?>
	<input type="checkbox" {if $tag.select eq 1}checked{/if} onclick="listTable.toggle_ext(this, 'toggle_tag', {$tkey}, <? echo $shop['supplier_id'];?>)"><? echo $tag['tag_name'];?></input>
	<? }?>
	</td>
    <td align="center"  nowrap="nowrap"><span onclick="listTable.edit(this, 'edit_sort_order', <? echo $shop['supplier_id'];?>)"><? echo $shop['sort_order'];?></span></td>
    <td align="center"   nowrap="nowrap">
     <a href="supplier.php?type=edit_info&supplier_id=<? echo $shop['supplier_id'];?>">编辑</a>
     <a onclick="{if(confirm('您确定要删除吗？')){return true;}return false;}" href="supplier.php?type=remove_supplier&supplier_id=<? echo $shop['supplier_id'];?>">删除</a>
    </td>
  </tr>
<? }?>
</table>

<!-- 分页 -->
 <?php $this->element('page', array('pagelink' => $pagelink)); ?>  </div>
  <div>
  
         
  <input type="checkbox" class="quxuanall" value="checkbox" />
    <input name="button" type="button" id="bathdel" value="批量下线" disabled="disabled" class="remove_back" />
  </div>
</form>

<?php  $thisurl = ADMIN_URL.'supplier.php'; ?>

<script>
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		sid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'ajax_street',active:star,sid:sid,type:type},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo $this->img('yes.gif');?>';
				}else{
					id = 1;
					src = '<?php echo $this->img('no.gif');?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
	</script>

<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
  
    $('.remove_back').click(function (){
         var url = '<?php echo urlencode($url); ?>';
   		if(confirm("确定下线吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+');
			$.post('<?php echo $thisurl;?>',{action:'remove_back',ids:str},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					//alert(data);
                                         location.href = 'supplier.php?type=supplier_street';
				}
			});
		}else{
			return false;
		}
   });
   
   
   	$('.cate_search').click(function(){
		
	
		
		supplier_type = $('select[name="supplier_type"]').val();
		
		is_show = $('select[name="is_show"]').val();

		keys = $('input[name="supplier_name"]').val();
	
		location.href='<?php echo $thisurl;?>?type=supplier_street&supplier_type='+supplier_type+'&is_show='+is_show+'&keyword='+keys;
	});
   
</script>
