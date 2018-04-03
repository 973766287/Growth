<?php
$thisurl = ADMIN_URL . 'user.php';
?>
<?php
if($_POST[sss]!="sss"){

?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}
    .alink{padding:1px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#FF0000}
</style>
<div class="contentbox">
    <table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th colspan="16" align="left" style="text-align:left">提款申请</th>
        </tr>
        <tr><td colspan="16" align="left">
                <img src="<?php echo $this->img('icon_search.gif'); ?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
                商户号       <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ""; ?>">
                法人姓名     <input name="keyword2" size="15" type="text" value="<?php echo isset($_GET['keyword2']) ? $_GET['keyword2'] : ""; ?>">
                结账状态     <select name="keyword3" id="keyword3">
                                <option value="">结账状态</option>
                                <option value="1">已结账</option>
                                <option value="2">未结账</option>
                             </select>
                代付状态     <select name="keyword4" id="keyword4">
                                <option value="">代付状态</option>
                                <option value="1">代付已发送</option>
                                <option value="2">代付未发送</option>
                             </select>
                代付结果     <select name="keyword5" id="keyword5">
                                <option value="">代付结果</option>
                                <option value="1">交易成功</option>
                                <option value="2">交易失败</option>
                             </select>
                             
                             选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
        
                <input value=" 搜索 " class="cate_search" type="button">
                <label style="display: inline-block">
                    <form  method="post" name="dcs" action="user.php?type=drawmoney"    > <input type="hidden" name="chaxun" value="<?php echo $chaxun ?>">



                        <input type="hidden" name="sss" value="sss"/>



                        <input type="submit" value="确认导出" style="cursor:pointer; padding:3px;" onclick="document.dcs.submit();">
                    </form>
                </label>
            </td></tr>
        <tr>
            <th><label>商户号</label></th>
             <th>订单号</th>
             <th>代付单号</th>
             <th>业务类型</th>
              <th>结账金额</th>
                 <th width="80">结账申请时间</th>
            <th>结算账户名</th>
             <th>结算银行</th>
               <th width="120">结算账号</th>
            <th>结算状态</th>
           <!-- <th>代付状态</th>
           <th>代付系统返回码</th>
           <th>代付系统返回信息</th> -->

          
         
          
            <th>会员关系</th>
      
            <th>提现记录</th>
           
            <th width="80">操作</th>
        </tr>
        <?php
        if (!empty($rt)) {
            foreach ($rt as $row) {
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $row['uid']; ?></td>
                    <td><?php echo $row['order_sn']; ?>
                     <td><?php echo $row['INFO_REQ_SN']; ?>
                    </td>
                    <td>提款（提现）
                    </td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s', $row['addtime']) : '无知'; ?></td>
                     <td><?php echo $row['account_name']; ?></td>
                    <td><?php echo $row['bankname']; ?></td>
                    <td><?php echo $row['account_no']; ?></td>
                    <td><a href="<?php echo ADMIN_URL.'user.php?type=change_state&state='.$row['state'].'&id='.$row['id'];?>"><?php echo $row['state']?"已结账":"未结账"; ?></a><br><?php  echo !empty($row['upstate_time']) ? '时间:'.date('Y-m-d H:i:s', $row['upstate_time']) : ''; ?></td>
                    <!-- <td><?php  if($row['INFO_RET_CODE'] === "0000" ){echo "代付已发送";}else{echo "代付未发送"; }?></td> -->
                   <!--  <td><?php echo $row['RET_DETAILS_RET_CODE']; ?></td>
                    <td><?php echo $row['RET_DETAILS_ERR_MSG'] ;?></td> -->
           
                    <td> <a href="user.php?type=userrelate_own&id=<?php echo $row['uid']; ?>"  class="alink">会员关系</a></td>
                  
                    <td><a href="user.php?type=drawmoney&uid=<?php echo $row['uid']; ?>" class="alink">提现记录</a></td>


                  
                    <td >
                      
                        <a href="<?php echo ADMIN_URL . 'user.php?type=info&id=' . $row['uid']; ?>" class="alink">查看代理</a>&nbsp;&nbsp;<br/>
                        
                      <? if($row['gender'] == 1){?>
					  
					   <? if($row['key'] == 'yinlian_api'){?>
                            <a href="javascript:void(0);" onclick="ajax_yinlianapi_search(<? echo $row['id'];?>)">查询</a>
                            <? }else{?>
							 <a href="javascript:void(0);" onclick="ajax_pay_searchs(<? echo $row['id'];?>)">查询</a>
							 <? }?>
                      <? }else{?>
                      
                      <? if($row['key'] == 'yinlian' && ($row['uid'] == 3061 || $row['uid'] == 8076)){?>
                            <a href="javascript:void(0);" onclick="ajax_yinlianapi_search(<? echo $row['id'];?>)">查询</a>
                            <? }else{?>
                              <a href="javascript:void(0);" onclick="ajax_daifu_search(<? echo $row['id'];?>)">查询</a>
                             <? }?>
                      
                        <? }?>
                        
                        <? if($row['state'] != 1 and $row['gender'] == 1 and (!empty($row['INFO_RET_CODE']))){?>
						
						 <? if($row['key'] == 'yinlian_api'){?>
                         <a href="javascript:void(0);" onclick="ajax_yinlianapi_pay_daifu(<? echo $row['id'];?>)">提现</a>
						 <? } else{?>
						 
						   <a href="javascript:void(0);" onclick="ajax_pay_daifu(<? echo $row['id'];?>)">提现</a>
                        <? }}?>
                        
                          <? if($row['state'] != 1 and $row['key'] == 'weixinssssss' and $row['RET_DETAILS_RET_CODE'] != '0000' and (!empty($row['INFO_RET_CODE']))){?>
                         <a href="javascript:void(0);" onclick="ajax_wxpay_daifu(<? echo $row['id'];?>)">提现</a>
                        <? }?>
                        
                        
                         <?  if($row['key'] != 'weixin' and $row['state'] != 1 and $row['gender'] == 0){?>
                         <a href="javascript:void(0);" onclick="ajax_pay_daifu_new(<? echo $row['id'];?>)">提现</a>
                        <? }?>
                      
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
<script type="text/javascript">
    function ajax_import_order_data(obj) {
        ps = $('input[name="pagestart"]').val();
        pe = $('input[name="pageend"]').val();

        window.open('<?php echo ADMIN_URL; ?>user.php?type=ajax_import_order_data&pagestart=' + ps + '&pageend=' + pe);
    }

    function ajax_confirm_drawmoney(id) {
      var url = '<?php echo urlencode($url); ?>';
        if (confirm("确认吗")) {
            createwindow();
            $.post('<?php echo $thisurl; ?>', {action: 'ajax_confirm_drawmoney', id: id}, function (data) {
                removewindow();
                if (data == "") {
                    location.reload();
                } else {
                    alert(data);
                    location.href = 'user.php?type=priv_user&url=' + url;
                }
                // removewindow();
                // window.location.href = '<?php echo ADMIN_URL . 'user.php?type=drawmoney'; ?>';
            });
        }
        return false;
    }
//全选
    $('.quxuanall').click(function () {
        if (this.checked == true) {
            $("input[name='quanxuan']").each(function () {
                this.checked = true;
            });
            document.getElementById("bathdel").disabled = false;
        } else {
            $("input[name='quanxuan']").each(function () {
                this.checked = false;
            });
            document.getElementById("bathdel").disabled = true;
        }
    });

    //是删除按钮失效或者有效
    $('.gids').click(function () {
        var checked = false;
        $("input[name='quanxuan']").each(function () {
            if (this.checked == true) {
                checked = true;
            }
        });
        document.getElementById("bathdel").disabled = !checked;
    });

    //查询
    $('.cate_search').click(function () {

        keys = $('input[name="keyword"]').val();
        keys2 = $('input[name="keyword2"]').val();
        keys3 = $("#keyword3").val();
        keys4 = $("#keyword4").val();
        keys5 = $("#keyword5").val();
		
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		

        location.href = '<?php echo Import::basic()->thisurl(); ?>&keyword=' + keys + '&keyword2=' + keys2 + '&keyword3=' + keys3 + '&keyword4=' + keys4 + '&keyword5=' + keys5 +'&add_time1='+time1+'&add_time2='+time2;
    });
	
	
	 function ajax_pay_search(id) {
		 
		
       $.post('http://ws.weishuapay.com/m/daili.php', {action: 'query', id: id}, function (data) {
               
                                  alert(data);

            });
			
			
	 }
	  function ajax_yinlianapi_search(id) {
		 
		
        $.post('<?php echo $thisurl; ?>', {action: 'ajax_yinlianapi_query', id: id}, function (data) {
               
                                  alert(data);

            });
			
			
			
	 }
	 
	 
	 
	  function ajax_wxpay_search(id) {
		 
		
       $.post('http://ws.weishuapay.com/m/wefu.php', {action: 'query', id: id}, function (data) {
               
                                  alert(data);

            });
			
			
	 }
	 
	 function ajax_wxpay_daifu(id){
		 
		    $.post('http://ws.weishuapay.com/m/wefu.php', {action: 'repay', id: id}, function (data) {
               
                                  alert(data);

            });
		 }
	 
	 
	  function ajax_pay_searchs(id) {
		 
		
       $.post('<?php echo $thisurl; ?>', {action: 'ajax_query', id: id}, function (data) {
               
                                  alert(data);

            });
			
			
	 }
	 
	 
	  function ajax_pay_daifu(id) {
		 
		
       $.post('<?php echo $thisurl; ?>', {action: 'ajax_pay_daifu', id: id}, function (data) {
               
                                  alert(data);
								  
								  history.go(0);

            });
			
			
	 }
    //2018/03/09
    function ajax_pay_daifu_new(id) {
         
        
       $.post('<?php echo $thisurl; ?>', {action: 'ajax_pay_daifu_new', id: id}, function (data) {
                                
            var data = JSON.parse(data);
            if(data.status == 1){
                alert("处理成功");
            }else{
                alert(data.message.result_msg+",该订单已处理,请勿重复提交!");
                console.log(data.message);
            }
                                

        });
            
            
    }
    //2018/03/09
    function ajax_daifu_search(id) {
         
        
       $.post('<?php echo $thisurl; ?>', {action: 'ajax_daifu_search', id: id}, function (data) {
                                
            var data = JSON.parse(data);
            if(data.status == 00){
                alert("交易成功!");
            }else if(data.status == 10){
                alert("交易正在处理，请稍后!");
            }else if(data.status == 2002){
                alert('订单不存在或订单未提交!');
            }else{
                alert("交易失败!");
                console.log(data);
            }
                                

        });
            
            
    }
	
	
	
	 function ajax_yinlianapi_pay_daifu(id) {
		 
		
       $.post('<?php echo $thisurl; ?>', {action: 'ajax_yinlianapi_pay_daifu', id: id}, function (data) {
               
                                  alert(data);
								  
								  history.go(0);

            });
			
			
	 }
	

</script>
<? } else{


    header("Content-Type:text/html;charset=utf-8");
    header("Content-type:application/vnd.ms-excel");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Content-Disposition:filename=已付款订单(page:".($pagestart+1)."-page:".$pageend.").xls");


    ?>

    <div class="contentbox">
        <table cellspacing="2" cellpadding="5" width="100%">
            <tr>
                <th><label>商户号</label></th>
                <th>订单号</th>
                <th>代付单号</th>
                <th>业务类型</th>
                <th>结账金额</th>
                <th width="80">结账申请时间</th>
                <th>结算账户名</th>
                <th>结算银行</th>
                <th width="120">结算账号</th>
                <th>结算状态</th>
                <th>代付状态</th>
                <th>代付系统返回码</th>
                <th>代付系统返回信息</th>
            </tr>
            <?php
            if (!empty($rt)) {
                foreach ($rt as $row) {
                    ?>
                    <tr>
                        <td style="text-align: center"><?php echo $row['uid']; ?></td>
                        <td style='vnd.ms-excel.numberformat:@'><?php echo $row['order_sn']; ?>
                        <td style='vnd.ms-excel.numberformat:@'><?php echo $row['INFO_REQ_SN']; ?>
                        </td>
                        <td>提款（提现）
                        </td>
                        <td style='vnd.ms-excel.numberformat:@'><?php echo $row['amount']; ?></td>
                        <td ><?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s', $row['addtime']) : '无知'; ?></td>
                        <td><?php echo $row['account_name']; ?></td>
                        <td><?php echo $row['bankname']; ?></td>
                        <td style='vnd.ms-excel.numberformat:@'><?php echo $row['account_no']; ?></td>
                        <td><a href="<?php echo ADMIN_URL.'user.php?type=change_state&state='.$row['state'].'&id='.$row['id'];?>"><?php echo $row['state']?"已结账":"未结账"; ?></a><br><?php  echo !empty($row['upstate_time']) ? '时间:'.date('Y-m-d H:i:s', $row['upstate_time']) : ''; ?></td>
                        <td><?php  if($row['INFO_RET_CODE'] === "0000" ){echo "代付已发送";}else{echo "代付未发送"; }?></td>
                        <td><?php echo $row['RET_DETAILS_RET_CODE']; ?></td>
                        <td><?php echo $row['RET_DETAILS_ERR_MSG'] ;?></td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
    </div>

<?php } ?>