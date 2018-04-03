<div id="wrap">
	<div class="clear7"></div>

	<div class="brand-con">


	<div class="brand-recommend">
            
		<h1 class='cattree_title'>全部分类</h1>
		<ul  class="cattree">
		<?php
        
	 
		 if(!empty($rt['catList'])){ foreach($rt['catList'] as $_v){
			
		?>
                    <li>  <a href="catalog.php?cid=<?php echo $_v['id'];?>" target="_blank">
                            <span><?php echo $_v['name'];?></span></a>
                        <ul class="cattree_son">
                                <?php 

                                           if(!empty($_v['cat_id'])){
                                          foreach($_v['cat_id'] as $_k1=>$_v1){ 
                            ?>
                                <li> <a href="catalog.php?cid=<?php echo $_v1['id'];?>"  target="_blank">  <span><?php echo $_v1['name'];?></span></a></li>
                            <?php
                            }
                            }
                            ?>
                                  </ul>  
                        <div class="clear"></div>
                    </li>
		
		<?php }} ?>
                    
	
		</ul>
                	<div class="clear"></div>
	</div>
	</div>
	
</div>