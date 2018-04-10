<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>

<!-- 供货商搜索 -->
<div class="form-div">
 
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" /> 

   入驻商名称
    <input name="supplier_name" type="text" id="supplier_name" size="15" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
   入驻商等级
    <select name="rank_name" size=1>
      <option value="0">请选择</option>
      
		
                
      <option value="1" <?php if(isset($_GET['rank_id'])&&$_GET['rank_id']==1){ echo 'selected="selected""'; } ?>>免费商铺</option>
      <option value="10" <?php if(isset($_GET['rank_id'])&&$_GET['rank_id']==10){ echo 'selected="selected""'; } ?>>收费商铺</option>
      
		
              
    </select>
    
      <input name="status" type="hidden" id="status" size="15" value="<? echo $status;?>">
    <input value=" 搜索 " class="cate_search" type="button">
   
  
</div>
<form method="post" action="" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
<div class="list-div" id="listDiv">


  <table cellpadding="3" cellspacing="1">
    <tr>
	  <th>会员名称</th>
      <th>入驻商名称</th>
      <th>入驻商等级</th>
      <th>公司电话</th>
	  <th>平台使用费</th>
	  <th>商家保证金</th>
	  <th>分成利率</th>
	  <th>入驻商备注</th>
	  <th>状态</th>
      <th>操作</th>
    </tr>
    <? 
	if(!empty($supplier_list)){ 
	foreach($supplier_list as $supplier){
   ?>
    <tr>
	  <td ><? echo $supplier['user_name'];?> </td>
      <td class="first-cell" style="padding-left:10px;" ><? echo $supplier['supplier_name'];?></td>
      <td ><? echo $supplier['rank_name'];?> </td>
      <td><? echo $supplier['tel'];?></td>
	  <td align="center"><? echo $supplier['system_fee'];?></td>
	  <td align="center"><? echo $supplier['supplier_bond'];?></td>
	  <td align="center"><? echo $supplier['supplier_rebate'];?></td>
	  <td align="center"><? echo $supplier['supplier_remark'];?></td>
	  <td align="center"><? if ($supplier['status'] == 1){?>通过<? }else if($supplier['status'] == 0){?> 未审核<? }else{?>未通过 <? }?></td>
      <td align="center">
        <a href="supplier.php?type=edit&id=<? echo $supplier['supplier_id'];?>&status=<? echo $supplier['status'];?>" title="查看">查看</a><? if ($supplier['status'] > 0 &&  $supplier['is_open'] > 0){?>&nbsp;&nbsp;<a href="../supplier.php?suppId=<? echo $supplier['supplier_id'];?>" target="_blank">查看店铺</a>&nbsp;&nbsp;<a href="supplier.php?act=view&id=<? echo $supplier['supplier_id'];?>" title="查看佣金">查看佣金</a><? }else{?>&nbsp;&nbsp;<? }?>&nbsp;&nbsp;<a href="javascript:del_supplier(<? echo $supplier['supplier_id']?>)" title="删除店铺">删除店铺</a></td>
    </tr>
    <? }}else{?>
    <tr><td class="no-records" colspan="10">没有找到任何记录</td></tr>
   <? }?>
  </table>
  <?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
</form>

<?php  $thisurl = ADMIN_URL.'supplier.php'; ?>
<script>

	function del_supplier(suppid){
		var url = "supplier.php?type=delete&id="+suppid;
		if(confirm('删除后，相关商品，佣金及其它店铺信息将永久删除，确定删除？')){
			self.location.href = url;
		}
	}

</script>

<script>

	$('.cate_search').click(function(){
		
		rank_id = $('select[name="rank_name"]').val();
        status = $('input[name="status"]').val();
		keys = $('input[name="supplier_name"]').val();
		
		location.href='<?php echo $thisurl;?>?type=supplier_list&status='+status+'&rank_id='+rank_id+'&keyword='+keys;
	});

</script>