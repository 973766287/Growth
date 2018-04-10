<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<?php $this->element('3/top',array('lang'=>$lang)); ?>

<style type="text/css">
.pw,.pwt{
height:26px; line-height:normal;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pws{ background:#ededed}
</style>
<div id="main" style="min-height:300px">
	<div style="background:#f5f5f5; border-bottom:1px solid #d1d1d1;padding:10px;">
	<form name="USERINFO2" id="USERINFO2" action="" method="post">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:30px;">
		   <tr>
			<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> <font color="#999999">兑换积分数：</font></td>
			<td width="75%" align="left" style="padding-bottom:2px;">
			<input   type="text" value="<?php echo isset($rt['zpoints']) ? $rt['zpoints'] : '';?>" name="zpoints"  class="pw pws"/>
                        <br/>
                        积分兑换余额比例为10:1
                        </td>
		 
                   </tr>
		 
 
		  <tr>
			<td align="center" style="padding-top:10px;" colspan="2">
			<a href="javascript:;" onclick="return ajax_postjifen();" style="border-radius:5px;display:block;background:#3083CE;cursor:pointer;width:140px; height:25px; line-height:25px; font-size:14px; color:#FFF">确认提交</a>
                        
                        
                        <span class="returnmes2" style="padding-left:10px; color:#FF0000"></span>
			</td>
		  </tr>
		</table>
	</form>
	</div>

</div>
<script type="text/javascript">
function ajax_postjifen(){
	//passs = $('input[name="pass"]').val();
        var points=<?php echo isset($rt['zpoints']) ? $rt['zpoints'] : '';?>;
	var zpoints =parseInt( $('input[name="zpoints"]').val());
	if(parseInt(points) < 1){
		$('.returnmes2').html('暂时不能为你服务，先赚取积分再来吧！');
		return false;
	}
	if(zpoints=="" ){
		$('.returnmes2').html('请输入兑换积分数量');
		return false;
	}
        if(points<zpoints){
            $('.returnmes2').html('您输入的金额不合法');
		return false;
        }

	if(confirm('确认信息无误要兑换积分吗')){
		createwindow();
		
		$.post('<?php echo ADMIN_URL;?>user.php',{action:'ajax_postpoints_convert',zpoints:zpoints},function(data){ 
			$('.returnmes2').html(data);
			removewindow();
		});
	}
	return false;
}

</script>
<?php $this->element('3/footer',array('lang'=>$lang)); ?>