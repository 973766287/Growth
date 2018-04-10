<style>
    .r{color:red}
</style>
<link href="zzf/css/store_joinin_new.css" rel="stylesheet" type="text/css">
<link href="zzf/css/store_joinin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="zzf/js/home_index.js"></script>
<script type="text/javascript" src="zzf/js/utils.js"></script>
<script type="text/javascript" src="zzf/js/region.js"></script>


  <div class="banner">
   
      
            
	<ul id="slides">
        
                 <?php foreach($rt['ad37'] as $_k=>$_v){?>
		<a href="<?php echo $_v['ad_url'];?>" target="_blank"><li><img src="<?php echo $_v['ad_img'];?>"></li></a>
		  <?php } ?>
                
                
	</ul>

    
</div>


<div class="header-extra">
       	 <div class="panel-heading">
            <div class="more">
                <div class="progress">
                    <div class="progress-wrap">
                        <div class="progress-item tobe">
                            <div class="number">1</div>
                            <div class="progress-desc">入驻须知</div>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item tobe">
                            <div class="number">2</div>
                            <div class="progress-desc">公司信息认证</div>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item ongoing">
                            <div class="number">3</div>
                            <div class="progress-desc">店铺信息认证</div>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item tobe">
                            <div class="number"><i class="tick"></i></div>
                            <div class="progress-desc">等待审核</div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
      
      
 <div class="store-joinin-apply">
  <div class="main">
    <div id="apply_company_info" class="apply-company-info">
      <form id="form_company_info" name="form_company_info" action="" method="post" enctype="multipart/form-data" onSubmit="return supplier_Reg()">
      <table border="0" cellpadding="0" cellspacing="0" class="all">
        <thead>
          <tr>
            <th colspan="20" align="left">店铺经营信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150"><i>*</i>店铺名称：</th>
            <td><input name="supplier_name" type="text" class="w200" value="<? echo $supplier['supplier_name']?>">
              <span></span>
              <p class="emphasis">店铺名称注册后不可修改，请认真填写。</p></td>
          </tr>
          <!--<tr>
            <th><i>*</i>店铺等级：</th>
            <td>
	    <select name="rank_id" size=1>
                <option value="0">请选择</option>
		<? foreach ($supplier_rank as $rank){?>
                <option value="<? echo $rank['rank_id'];?>" <? if ($supplier['rank_id'] == $rank['rank_id']){?>selected<? }?>><? echo $rank['rank_name'];?></option>
		<? }?>
              </select>
              <span></span>
              <div id="grade_explain" class="grade_explain"></div></td>
          </tr>-->
          <tr>
            <th><i>*</i>店铺分类：</th>
            <td> <select name="type_id" size=1>
                <option value="0">请选择</option>
                
		 <? foreach ($supplier_type as $type){?>
         
       
                <option value="<? echo $type['str_id'];?>" <? if ($supplier['type_id'] == $type['str_id']){?>selected<? }?>><? echo $type['str_name'];?></option>
		 <? }?>
              </select>
              <span></span>
              <p class="emphasis">请根据您所经营的内容认真选择店铺分类，注册后商家不可自行修改。</p></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="20">&nbsp;</td>
          </tr>
        </tfoot>
      </table>
      <input type="hidden" name="applynum" value="3">
      <input type="hidden" name="do" value="1">
      <div class="bottom">
      <input type="button" value="上一步" class="btn" 
	  <? if($supplier['guimo'] == ""){?>
      onclick="top.location.href='user.php?act=apply_two&shownum=2'"
	  <? }else{?>
      onclick="top.location.href='user.php?act=apply_two2&shownum=2'"
	  <? }?> >
       <input type="submit" value="下一步" class="btn"></div>
      </form>
    </div>
  </div>
</div>
<script>
function supplier_Reg()
{
	var msg = '';
	var frm = document.forms['form_company_info'];
	//var rank_id =  frm.elements['rank_id'] ? Utils.trim(frm.elements['rank_id'].value) : '0';
    var type_id =  frm.elements['type_id'] ? Utils.trim(frm.elements['type_id'].value) : '0';
	var supplier_name = frm.elements['supplier_name'] ? Utils.trim(frm.elements['supplier_name'].value) : '';

	if (supplier_name.length == 0)
	{
		msg += "店铺名称不能为空！" + '\n';
	}
	
	
	//
//	if (rank_id == '0')
//	{
//		msg += "店铺等级不能为空！" + '\n';
//	}
	if (type_id == '0')
	{
		msg += "店铺分类不能为空！" + '\n';
	}
	if (msg.length > 0)
	{
		alert(msg);
		return false;
	}
	else
	{
		  var fromAttr        = new Object();  //
	   var form      = document.forms['form_company_info']; //
	    if(form){
			fromAttr = getFromAttributes(form);
	   }else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
	   createwindow();
	   $.ajax({
		   type: "POST",
		   url: SITE_URL+"user.php?action=apply_three_add",
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){
                      
			   removewindow();
                        
		   		if(data.error==1){
				
				JqueryDialog.Open('系统提醒你','<br />'+data.message,250,50);
				
				}else{
                                 
				
                                        window.location.href=SITE_URL+'user.php?act=apply_four';
                                       // $('table .returnmes').html(data.message);
				
				}
		   }
		});
	}
}
</script>

