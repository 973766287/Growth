<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../zzf/js/utils.js"></script>


<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>

<h1>
<span class="action-span"><a href="supplier.php?type=supplier_street_category&info=add">添加分类</a>&nbsp;&nbsp;</span>
<div style="clear:both"></div>
</h1>


<!-- 供货商搜索 -->
<form method="post" action="" name="listForm">
<!-- start ad position list -->
<div class="list-div" id="listDiv">

<table width="100%" cellspacing="1" cellpadding="2" id="list-table">
  <tr>
    <th>分类名称</th>
	<th>分类样式</th>
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
	<td width="10%" align="center"><span onclick="listTable.edit(this, 'edit_str_style', <? echo $cat['str_id}'];?>"><? echo $cat['str_style'];?></span></td>
    <td width="10%"><? if($cat['num']){echo $cat['num'];}else{echo 0;}?></td>
    <td width="10%"><img src="images/<? if ($cat['is_show'] == 1) { echo "yes";}else{echo "no";}?>.gif" onclick="listTable.toggle(this, 'toggle_is_show', <? echo $cat['str_id'];?>)" /></td>
    <td width="10%"><img src="images/<? if ($cat['is_groom'] == 1){ echo "yes";}else{ echo "no";}?>.gif" onclick="listTable.toggle(this, 'toggle_is_groom', <? echo $cat['str_id'];?>)" /></td>
    <td width="10%" align="center"><span onclick="listTable.edit(this, 'edit_sort_order', <? echo $cat['str_id'];?>)"><? echo $cat['sort_order'];?></span></td>
    <td width="24%" align="center">
      <a href="supplier.php?type=supplier_street_category&info=delete&sid=<? echo $cat['str_id'];?>" onclick="confirm('您确认要删除这条记录吗')" title="移除">移除</a>
    </td>
  </tr>
 <? }?>
</table>

</div>
</form>
