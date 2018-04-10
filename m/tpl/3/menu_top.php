<?php
$rt = $this->action('page','get_site_nav','top',6);
?>
<div class='nav'>
  <ul >
      <?php if(!empty($rt)){
          $i=0;
          foreach($rt as $row){
          $i++;
      
?>
      <a href="<?php echo $row['url'];?>"><li
              <?php if($i==1 || $i==4){ ?>
              style="margin-left:0;"
              	<?php  }?>
              ><img src="<?php echo SITE_URL.$row['img'];?>" /><p><?php echo $row['name'];?></p></li></a>
	<?php } }?>
   
    </ul>
   <div style="clear:both;"></div>
</div>