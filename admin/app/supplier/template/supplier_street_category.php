<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../zzf/js/utils.js"></script>


<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
.activeop{ cursor:pointer;}
</style>

<h1>
<span class="action-span"><a href="supplier.php?type=supplier_street_category_add">添加分类</a>&nbsp;&nbsp;</span>
<div style="clear:both"></div>
</h1>


<!-- 供货商搜索 -->
<form method="post" action="" name="listForm">
<!-- start ad position list -->
<div class="list-div" id="listDiv">

<table width="100%" cellspacing="1" cellpadding="2" id="list-table">
  <tr>
    <th>分类名称</th>
	<!--<th>分类样式</th>-->
    <th>店铺数量</th>
    <th>是否显示</th>
    <th>是否推荐</th>
    <th>排序</th>
    <th>操作</th>
  </tr>
  <? foreach ($cat_info as $cat){?>
  <tr align="center" id="<? echo $cat['str_id'];?>">
    <td align="left" class="first-cell" >
      <span><a href="supplier_street.php?act=list&supplier_type=<? echo $cat['str_id']?>"><? echo $cat['str_name'];?></a></span>
    </td>
<!--	<td width="10%" align="center"><span class="vieworder" id="<?php echo $cat['str_id'];?>" lang="str_style"><? echo $cat['str_style'];?></span></td>-->
    <td width="10%"><? if($cat['num']){echo $cat['num'];}else{echo 0;}?></td>
    
    <td width="10%"><img class="activeop" src="<?php echo $this->img($cat['is_show']==1 ? 'yes.gif' : 'no.gif');?>"  alt="<?php echo $cat['is_show']==1 ? '0' : '1';?>" lang="is_show" id="<? echo $cat['str_id'];?>" /></td>
    <td width="10%"><img class="activeop" src="<?php echo $this->img($cat['is_groom']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $cat['is_groom']==1 ? '0' : '1';?>" lang="is_groom"   id="<? echo $cat['str_id'];?>"/></td>
    <td width="10%" align="center"><span class="vieworder" id="<?php echo $cat['str_id'];?>" lang="sort_order"><? echo $cat['sort_order'];?></span></td>
    <td width="24%" align="center">
      <a href="supplier.php?type=supplier_street_category&info=delete&sid=<? echo $cat['str_id'];?>" onclick="confirm('您确认要删除这条记录吗')" title="移除">移除</a>
    </td>
  </tr>
 <? }?>
</table>

</div>
</form>
<?php  $thisurl = ADMIN_URL.'supplier.php'; ?>
<script>
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		sid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'ajax_street_category',active:star,sid:sid,type:type},function(data){
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
	
	
		//ajax排序处理
	$('.vieworder').click(function (){ edit_20151221(this); });
	function edit_20151221(object){
		thisvar = $(object).html();
		ids = $(object).attr('id');
		tname = $(object).attr('lang');
		
		//$(object).css('background-color','#FFFFFF');
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			 $(input).css('width', '70px');
             $(input).change(function(){
                 update_20151221(ids, this,tname)
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
			 $(input).select();
             $(object).find('input').focus();
         }
	}
	
	function update_20151221(id, object,type){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo $thisurl;?>',{action:'ajax_street_category_order',gid:id,val:editval,type:type},function(data){ 
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit_20151221(object);
             })
		});
    }
</script>