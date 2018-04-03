<div class="contentbox">
<style type="text/css">
.menu_content .tab{ display:none}
.nav .active{
	 /*background: url(<?php echo $this->img('manage_r2_c13.jpg');?>) no-repeat;*/
	 background-color:#F5F5F5;
} 
.nav .other{
	/* background: url(<?php echo $this->img('manage_r2_c14.jpg');?>) no-repeat;*/
	 background-color:#E9E9E9;
} 
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:0px; margin-bottom:0px}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
.addi{ margin:0px; padding:0px;}
.vipprice td{ border-bottom:1px dotted #ccc}
.vipprice th{ background-color:#EEF2F5}
</style>
<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">


 <div class="menu_content">
 	<!--start 通用信息-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab1">
	  <tr>
		<td class="label">礼包名称:</td>
		<td><input name="bag_name" id="bag_name"  type="text" size="43" value="<?php echo isset($rt['bag_name']) ? $rt['bag_name'] : '';?>"><span style="color:#FF0000">*</span><span class="goods_name_mes"></span>
		
		</td>
	  </tr>
	  <tr>
		<td class="label">会员级别:</td>
		<td>
                    <select name="type" id="type">
                         <option value="">请选择</option>
                        <?php foreach($user_level as $_k=>$_v){?>
                        <option value="<?php echo $_v['lid'];?>"  <?php if(isset($rt['type'])&&$rt['type']==$_v['lid']){ echo 'selected="selected""'; } ?> ><?php echo $_v['level_name'];?></option>
                        <?php } ?>
                    </select>
		<span style="color:#FF0000">*</span><span class="type_mes"></span>
		</td>
	  </tr>
	
   

	   <tr>
		<td class="label">上传礼包主图:</td>
		<td>
		  <?php if(isset($rt['goods_img'])){ ?><img src="<?php echo !empty($rt['goods_img']) ? SITE_URL.$rt['goods_img'] : $this->img('no_picture.gif');?>" width="100" style="padding:1px; border:1px solid #ccc"/><?php } ?>
		  <input name="original_img" id="goods" type="hidden" value="<?php echo isset($rt['original_img']) ? $rt['original_img'] : '';?>"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['original_img'])&&!empty($rt['original_img'])? 'show' : '';?>&ty=goods&files=<?php echo isset($rt['original_img']) ? $rt['original_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	  <tr>
		<td class="label">礼包缩略图:</td>
		<td>
		  <?php if(isset($rt['goods_thumb'])){ ?><img src="<?php echo !empty($rt['goods_thumb']) ? SITE_URL.$rt['goods_thumb'] : $this->img('no_picture.gif');?>" width="70" style="padding:1px; border:1px solid #ccc"/><?php } ?>
		  <input name="goods_thumb" id="goods_thumb" type="hidden" value="<?php echo isset($rt['goods_thumb']) ? $rt['goods_thumb'] : '';?>"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['goods_thumb'])&&!empty($rt['goods_thumb'])? 'show' : '';?>&ty=goods_thumb&tyy=goods&files=<?php echo isset($rt['goods_thumb']) ? $rt['goods_thumb'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe><br /><em>如果留空，那么将以原始图片生成缩略图</em>
		</td>
	  </tr>
	  <?php if(isset($gallerylist) && !empty($gallerylist)){?>
	  <tr>
	  <td class="label">&nbsp;</td>
	  <td>
	  <?php 
		if(!empty($gallerylist)){
		echo "<ul class='gallery'>\n";
		foreach($gallerylist as $row){
			echo '<li style="width:120px; text-align:center; border:1px dashed #ccc; float:left; margin:2px;position:relative;height:140px;overflow:hidden "><img src="'.SITE_URL.$row['img_url'].'" alt="'.$row['img_desc'].'" width="90"/><p align="center">'.$row['img_desc'].'</p><a class="delgallery" id="'.$row['img_id'].'" style="position:absolute; top:2px; right:2px; background-color:#FF3333; display:block; width:35px; height:16px;">删除</a></li>';
		}
		echo "</ul>\n";
		}
	  ?>
	  </td>
	  </tr>
	  <?php } ?>
	
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('#content', {
					themeType : 'default'
				});
			});
		</script>
		<tr>
		<td class="label" width="150">产品详情:</td>
		<td><textarea name="bag_desc" id="content" style="width:95%;height:400px;display:none;"><?php echo isset($rt['bag_desc']) ? $rt['bag_desc'] : '';?></textarea>
		</td>
	  </tr>
	  		
	 </table>
	 <!--end 通用信息-->
	 	 
	
	
<!--	  <table cellspacing="2" cellpadding="5" width="100%" id="tab3" class="tab">

	 </table>-->
	  <p style="text-align:center;">
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" style="cursor:pointer" />&nbsp;
	  </p>
	  <div style="clear:both"></div>
 </div> 
  </form>
</div>

<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		art_title = $('#bag_name').val();
		if(art_title=='undefined' || art_title==""){
			$('.goods_name_mes').html("标题不能为空！");
			$('.goods_name_mes').css('color','#FE0000');
			return false;
		}
                type = $('#type').val();
		if(type=='undefined' || type==""){
			$('.type_mes').html("分类不能为空！");
			$('.type_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
	


-->
</script>
