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
<span class="action-span"><a href="supplier.php?type=supplier_street_category_add">店铺分类</a>&nbsp;&nbsp;</span>
<div style="clear:both"></div>
</h1>


<div class="main-div">
  <form action="" method="post" name="theForm"  onsubmit="return validate()">
    <table width="100%" id="general-table">
      <tr>
        <td class="label">分类名称:</td>
        <td>
        <input type="text" name='str_name' value=''><font color="red">*</font>
      </td>
      </tr>
	 <!-- <tr>
        <td class="label">前台显示样式类名:</td>
        <td><input type="text" name='str_style' value=''></td>
      </tr>-->
	  <tr>
        <td class="label">是否显示:</td>
        <td><input type="radio" name="is_show" value="1" checked="true"/>是
          <input type="radio" name="is_show" value="0" />否</td>
      </tr>
	 <!-- <tr>
        <td class="label">是否推荐:</td>
        <td><input type="radio" name="is_groom" value="1" checked/>是
          <input type="radio" name="is_groom" value="0"  />否 </td>
      </tr>-->
      <tr>
        <td class="label">排序:</td>
        <td><input type="text" name='sort_order' value='50' size="15" /></td>
      </tr>

    </table>
    <div class="button-div">
      <input type="submit" value="确定" />
      <input type="reset" value="重置" />
    </div>
   <input type="hidden" name="info" value="add"/>
   
  </form>
</div>

<script>
function validate(){
	var msg = '';
	var frm = document.forms['theForm'];
	var str_name =  frm.elements['str_name'] ? Utils.trim(frm.elements['str_name'].value) : '';
	var str_style =  frm.elements['str_style'] ? Utils.trim(frm.elements['str_style'].value) : '';
	var is_show =  frm.elements['is_show'] ? Utils.trim(frm.elements['is_show'].value) : 0;
	var sort_order =  frm.elements['sort_order'] ? Utils.trim(frm.elements['sort_order'].value) : 50;
	var info =  frm.elements['info'] ? Utils.trim(frm.elements['info'].value) : '';
	
	if (str_name.length == 0)
	{
		msg += "分类名称不能为空！" + '\n';
	}
	
	if (msg.length > 0)
	{
		alert(msg);
		return false;
	}
	else
	{
	
	$.post('supplier.php',{action:'ajax_supplier_street_category_add',str_name:str_name,str_style:str_style,is_show:is_show,sort_order:sort_order},function(data){
			if(data == ""){
				
				 window.location.href='supplier.php?type=supplier_street_category';
			}else{
				alert(data);
			}
		});
		
	}
	
	}
</script>