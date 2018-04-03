<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">

<style>
.main{ width:100%; overflow:hidden;}
.thispage {
  font-size: 16px;
  font-weight: bold;
}
</style>


<div class="main-div">
  <form action="" method="post" name="form_supplier_info" enctype="multipart/form-data">
    <table width="100%" id="general-table">
	  <tr>
        <td class="label">店铺类型:</td>
        <td>
		<select name="supplier_type">
		<option value="0">请选择</option>
		<? foreach ($str_category as $name){?>
		<option value="<? echo $name['str_id'];?>" <? if($info['supplier_type'] == $name['str_id']){?> selected <? }?>><? echo $name['str_name'];?></option>
		<? }?>
		</select>
		</td>
      </tr>
      <tr>
        <td class="label">店铺名称:</td>
        <td><input type="text" name="supplier_name" value="<? echo $info['supplier_name'];?>">
		<font color="red">*</font> <span class="notice-span"></span>
		</td>
      </tr>
	  <tr>
        <td class="label">店铺标题:</td>
        <td><input type="text" name="supplier_title" value="<? echo $info['supplier_title'];?>" maxlength="13">
		<font color="red">*</font> <span class="notice-span">为保证美观度,店铺标题控制在13个文字以内</span>
		</td>
      </tr>
	  
          
	  <tr>
        <td class="label">店铺海报:</td>
        
     
        
        
        <td><input type="hidden" name="logo"  id="logos" size="40"/>
        
         <?php if(isset($info['logo'])){ ?><img src="<?php echo !empty($info['logo']) ? "../".$info['logo'] : $this->img('no_picture.gif');?>" width="150" height="150" style="padding:1px; border:1px solid #ccc"/><?php } ?>
         
         <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($info['logo'])&&!empty($info['logo'])? 'show' : '';?>&ty=logos&files=<?php echo isset($info['logo']) ? $info['logo'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
         
		<? if($info['logo']){?>
            <!-- <a href="?act=del&code=logo"><img src="images/no.gif" alt="Delete" border="0" /></a>  --><img src="images/yes.gif" border="0" onmouseover="showImg('logo_layer', 'show')" onmouseout="showImg('logo_layer', 'hide')" />
            <div id="logo_layer" style="position:absolute; width:100px; height:100px; z-index:1; visibility:hidden" border="1">
              <img src="../<? echo $info['logo'];?>" border="0" />
            </div>
		<? }else{?>
            <? if($info['logo'] == ""){?>
            <img src="images/yes.gif" alt="yes" />
           <? }else{?>
            <img src="images/no.gif" alt="no" />
            <? }?>
        <? }?>
	    <span class="notice-span">为达到前台图标显示最佳状态，建议上传150X150px图片</span></td>
      </tr>
	  <tr>
        <td class="label">是否显示:</td>
        <td><input type="radio" name="is_show"  value="1" <? if($info['is_show'] ==1){?> checked="true"<? }?>/>是
          <input type="radio" name="is_show"  value="0" <? if($info['is_show'] == 0){?> checked="true"<? }?> />否</td>
      </tr>
	  <tr>
        <td class="label">是否推荐:</td>
        <td><input type="radio" name="is_groom"  value="1" <? if($info['is_groom'] == 1){?> checked="true"<? }?>/>是
          <input type="radio" name="is_groom"  value="0" <? if($info['is_groom'] == 0){?> checked="true"<? }?> />否</td>
      </tr>
      
      
      <tr>
        <td class="label">排序:</td>
        <td><input type="text"  name='sort_order' <? if($info['sort_order']){?>value='<? echo $info['sort_order'];?>'<? }else{?> value="50"<? }?> size="15" />
		</td>
      </tr>
      
        
      <tr>
        <td class="label">商家描述:</td>
        <td><input type="text"  name='sjms' <? if($info['sjms']){?>value='<? echo $info['sjms'];?>'<? }else{?> value="5.0"<? }?> size="15" />
		</td>
      </tr>
      
        
      <tr>
        <td class="label">服务态度:</td>
        <td><input type="text"  name='fwtd' <? if($info['fwtd']){?>value='<? echo $info['fwtd'];?>'<? }else{?> value="5.0"<? }?> size="15" />
		</td>
      </tr>
      
        
      <tr>
        <td class="label">物流速度:</td>
        <td><input type="text"  name='wlsd' <? if($info['wlsd']){?>value='<? echo $info['wlsd'];?>'<? }else{?> value="5.0"<? }?> size="15" />
		</td>
      </tr>
      
      
	  <tr>
        <td class="label">审核通知:</td>
        <td><textarea cols='30' rows='3' name='supplier_notice' id='supplier_notice'><? echo $info['supplier_notice'];?></textarea>
		</td>
      </tr>
	  <tr>
        <td class="label">审核状态:</td>
        <td><input type="radio" name="status"  value="1" <? if($info['status'] == 1){?> checked="true"<? }?>/>
          通过审核
          <input type="radio" name="status"  value="0" <? if($info['status'] == 0){?> checked="true"<? }?> />
          拒绝审核 
		  </td>
      </tr>

    </table>
    <div class="button-div">
      <input class="saveinfo" type="submit" value="确定" />
      <input type="reset" value="重置" />
    </div>
  
  </form>
</div>

<?php  $thisurl = ADMIN_URL.'supplier.php'; ?>
<script type="text/javascript">
function showImg(id, act) {
	if (act == 'show') {
		document.getElementById(id).style.visibility = 'visible';
	} else {
		document.getElementById(id).style.visibility = 'hidden';
	}
}

$('.saveinfo').click(function(){
	
	
		supplier_notice = $('#supplier_notice').val();
		if(supplier_notice=='undefined' || supplier_notice==""){
			alert("审核通知不能为空！");
			return false;
		}
		
	
		
		return true;
	});

</script>
