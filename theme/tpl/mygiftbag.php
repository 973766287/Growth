<div id="wrap">
	<div class="clear7"></div>
	<?php $this->element('user_menu');?>
    <div class="m_right" >
    	<h2 class="con_title">我领取的礼包</h2>
		 <div class="right_top">
	
		 <div class="AJAXORDERLIST order">
			  <?php $this->element("ajax_giftbag",array('rt'=>$rt));?>
		 </div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear7"></div>
<?php
	$thisurl = SITE_URL.'user.php';
?>
<script type="text/javascript">
$('select[name="status"]').change(function(){
	get_order_page_list('1',$(this).val());
});
$('select[name="dt"]').change(function(){
	get_order_page_list('1',$('select[name="status"]').val());
});

$('input[name="kk"]').change(function(){
	get_order_page_list('1',$('select[name="status"]').val());
});

$('input[name="search"]').change(function(){
	get_order_page_list('1',$('select[name="status"]').val());
});

   $('.AJAXORDERLIST  .oporder').live('click',function(){
		if(confirm("确定吗？")){
			createwindow();
			id = $(this).attr('id');
			na = $(this).attr('name');
			$.post('<?php echo $thisurl;?>',{action:'order_op',id:id,type:na},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					alert(data);
				}
			});
		}
		return false;
   });
</script>


<script type="text/javascript">
function ajax_checkout(oid){
	var f = document.createElement("form");
	document.body.appendChild(f);
	var i = document.createElement("input");
	i.type = "hidden";
	f.appendChild(i);
	i.value = oid;
	i.name = "order_id";
	f.method = 'post';
	f.target = 'a';
	f.action = SITE_URL+"mycart.php?type=fastcheckout";
	f.submit();
	return false;
}


</script>