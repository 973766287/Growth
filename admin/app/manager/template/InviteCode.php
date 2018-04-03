<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
     
     <?php if($groupid == 1){?>
     <tr>
        	<th colspan="6"><input type="button" class="invite"  value="生成邀请码" />
			<input type="button" value="导出邀请码" style="cursor:pointer; padding:3px;" class="jumpExport">

			</th>
        </tr>
        <?php }else{?>
		<tr>
        	<th colspan="6">
		<input type="button" value="导出邀请码" style="cursor:pointer; padding:3px;" class="jumpExport">
</th></tr>
		<?php }?>
     	<tr>
        	<th>序号</th><th>邀请码</th><th>代理</th><th>状态</th><th>添加时间</th><th>操作</th>
        </tr>
        <?php 
		if(!empty($InviteCodeList)){
	foreach($InviteCodeList as $row){
		?>
		        <tr>
		        	<td><?php echo $row['id'];?></td>
                    <td><?php echo $row['InviteCode'];?></td>
                    
                    <?php if($groupid == 1){?>
                      <td><?php echo $row['adminname'];?></td>
                   
                    <?php }?>
                     <?php if($groupid == 16){?>
                       <td><?php echo $row['adminname'];?></td>
                    <?php }?>
                     <?php if($groupid == 17){?>
                       <td><?php echo $row['adminname'];?></td>
                    <?php }?>
                     <?php if($groupid == 18){?>
                      <td>无</td>
                    <?php }?>
                    
                                        <td><?php if($row['status'] == 1){ echo "已激活" ;}else{ echo "未激活";}?></td>


                    <td><?php echo date('Y-m-d H:i:s',$row['addtime']);?></td>
                    
        <td>
		
		<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['adminid'];?>" class="deladmin"/></td>
		        </tr>
		        <?php
					} 
				}
				?>
		     </table>
             
             
             <?php $this->element('page',array('pagelink'=>$pagelink));?>
             
             
		</div>
		<?php  $thisurl = ADMIN_URL.'manager.php';
		?>
		<script type="text/javascript">
		//jQuery(document).ready(function($){
		$('.invite').click(function(){
				createwindow();
								$.post('<?php echo $thisurl;?>',{action:'CreateInviteCode'},function(data){
					
										if(data == ""){
										removewindow();
										
										window.location.href="<?php echo $thisurl;?>?type=Invitecode";
							
					} else{
						alert(data);
					}
				});
			
		});
		//});
		
		
$('.jumpExport').click(function(){
		window.location.href = '<?php echo ADMIN_URL;?>manager.php?type=export_invitecode';
	});
	
	
		</script>