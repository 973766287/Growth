<div id="wrap">
	<div class="clear7"></div>
    	<?php $this->element('user_menu');?>
 <div class="m_right" >
 	 <h2 class="con_title">佣金榜</h2>
     <div class="USERPOINTS jf">
      <?php $this->element('ajax_user_moneyrank',array('rt'=>$rt));?>
      </div>
  </div>
<?php
	$thisurl = SITE_URL.'user.php';
?>

    <div class="clear"></div>
  </div>
  <div class="clear7"></div>