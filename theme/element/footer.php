<div class="foot c">
    <div class="foot_top">
	    <div class="foot_top_main">
		    <div class="foot_top_main_top">
                        
                        
                        <?php 
                        $a=12;
                        foreach($lang['about_article'] as $_k=>$_v){
                            $a++;
                                ?>
			    <div class="foot_top_main_top_main fl" style=" background:url(images/12.jpg) no-repeat top right;">
				    <div class="foot_top_main_top_main_top"><img src="images/<?php echo $a;?>.jpg" width="37" height="37" /></div>
					<div class="foot_top_main_top_main_bottom">
					    <div class="foot_top_main_top_main_bottom_top zt3"><?php echo $_v['cat_name'];?></div>
						<div class="foot_top_main_top_main_bottom_bottom zt4">
                                                    <?php  foreach($_v['article'] as $_k1=>$_v1){?>
						  <p><a href="about.php?id=<?php echo $_v1['article_id'];?>"><?php echo $_v1['article_title'];?></a></p>
						   <?php } ?>
						</div>
					</div>
				</div>
			
                        
                        <?php } ?>
				
                        
			
			</div>
			<div class="foot_top_main_bottom">
                            <ul class='foot_ad'>
                            
                                    <?php foreach($lang['foot_ad'] as $_k=>$_v){?>
                                <li><a href="<?php echo $_v['ad_url'];?>"><img src="<?php echo $_v['ad_img'];?>"/></a></li>
                                    <?php } ?>
                               
                            </ul>
			</div>
		</div>
	</div>
	<div class="foot_bottom"><?php echo $lang['copyright'];?></div>
</div>





