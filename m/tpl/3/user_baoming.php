<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<title>推广</title>
</head>

<body>
<div class="js_head">
  <div class="js_head_photo"><img src="img/img.jpg"> <div class="js_head_icon">-<? echo $rt['level_name'];?>-</div>
</div>
  <h3>我是：<? echo $rt['nickname'];?></h3>
  <p style="text-align: center;">便捷生活，轻松支付</p>
  <div class="js_head_foot">
    推广总人数：<? echo $rt['zcount']?>人 
   <span>|</span>
   推广可用人数：<? echo $rt['zcount']?>人           
  </div>
</div>
<div class="tabs">
	  <div class="tabs-header">
		<ul>
        
    
          
        <? $i=1; foreach($bm as $row){
			if($i == 1){
			?>
		  <li class="active"><a href="#tab-<? echo $i;?>" tab-id="<? echo $i;?>" ripple="ripple" ripple-color="#FFF"><img src="/<? echo $row['img'];?>" ><? echo $row['title'];?></a></li>
          <? }else{?>
          
            <li><a href="#tab-<? echo $i;?>" tab-id="<? echo $i;?>" ripple="ripple" ripple-color="#FFF"><img src="/<? echo $row['img'];?>" ><? echo $row['title'];?></a></li>
          
		<? }$i++; }?>
		</ul>
	  </div>
      <div class="js2_sj">您有<? echo $rt['tuiguang_money'];?>元推广基金，可用于升级<button class="real_sub" id="ClickMe">马上升级</button></div>
                   <div id="goodcover"></div>
<div id="code">
  <div class="close1"><a href="javascript:void(0)" id="closebt"><img src="img/close.gif"></a></div>
  <div class="goodtxt">
    选择您要升级的级别
  </div>
  <div class="bt_icon" >
  <form id="BAOMING" name="BAOMING" method="post" action="<?php echo ADMIN_URL . 'user.php?act=confirmpay'; ?> " enctype="multipart/form-data"   >
  
      <p>
       <? foreach($bm as $row){
		   if($row['id'] != 3){
		   ?>
       
        <label>
          <input type="radio" name="RadioGroup1" value="<? echo $row['id']?>" id="RadioGroup1_0">
         <img class="bt_icon_img" src="/<? echo $row['img'];?>" > <? echo $row['title'];?></label>
        <br>
        <? }}?>
      
      </p>
    </form>
  </div>
  <div class="code-img"><button class="real_sub" onClick="check_bm()">确认升级</button></div>
</div>

<div class="fixed tBor">

  <ul>

    <a href="<?php echo ADMIN_URL;?>user.php?act=baoming"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-home-g.png" height="25"><p>会员中心</p></li></a>

    <a href="<?php echo ADMIN_URL;?>daili.php?act=myusertype"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-promote-g.png" height="25"><p >推广</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php?act=Instead"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>还款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>mycart.php?type=shoukuan"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>收款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-member-b.png" height="25"><p class="on">我的</p></li></a>

    </ul>

</div>
    
    
<script>
$(function() {
    //alert($(window).height());
    $('#ClickMe').click(function() {
		
		<?php if($instead_plan){?>
		alert("智能还款计划执行中，请先终止还款计划再升级！");
		return false;
		<?php }?>
        $('#code').center();
        $('#goodcover').show();
        $('#code').fadeIn();
    });
    $('#closebt').click(function() {
        $('#code').hide();
        $('#goodcover').hide();
    });
	$('#goodcover').click(function() {
        $('#code').hide();
        $('#goodcover').hide();
    });
    /*var val=$(window).height();
	var codeheight=$("#code").height();
    var topheight=(val-codeheight)/2;
	$('#code').css('top',topheight);*/
    jQuery.fn.center = function(loaded) {
        var obj = this;
        body_width = parseInt($(window).width());
        body_height = parseInt($(window).height());
        block_width = parseInt(obj.width());
        block_height = parseInt(obj.height());

        left_position = parseInt((body_width / 2) - (block_width / 2) + $(window).scrollLeft());
        if (body_width < block_width) {
            left_position = 0 + $(window).scrollLeft();
        };

        top_position = parseInt((body_height / 2) - (block_height / 2) + $(window).scrollTop());
        if (body_height < block_height) {
            top_position = 0 + $(window).scrollTop();
        };

        if (!loaded) {

            obj.css({
                'position': 'absolute'
            });
            obj.css({
                'top': ($(window).height() - $('#code').height()) * 0.5,
            });
            $(window).bind('resize', function() {
                obj.center(!loaded);
            });
            $(window).bind('scroll', function() {
                obj.center(!loaded);
            });

        } else {
            obj.stop();
            obj.css({
                'position': 'absolute'
            });
            obj.animate({
                'top': top_position
            }, 200, 'linear');
        }
    }

})
</script>

 <script>
        function check_bm() {
		
		
                var rank = $('input[name="RadioGroup1"]:checked').val();
      
          if(rank == "" || typeof (rank) == 'undefined'){
			  alert("请选择要升级的会员");
			  return false;
			  }
            $("#BAOMING").submit();
        }
     
    </script>
	  <div class="tabs-content" style="margin-bottom: 44px;">
		<div tab-id="1" class="tab active"><dl>
              <dt>金牌会员权益简介</dt>
              <dd>
                <h3><img src="img/js_04.jpg" ></h3>
                <div><h4>升级金额（平台使用费）</h4>
        <? echo $bmx['price']['3'];?>
                 	 </div>
              </dd>
              <dd>
                <h3><img src="img/js_05.jpg" ></h3>
                <div><h4>扣率/ T+0</h4>
				
				<? echo $bmx['koulv']['3'];?>
                
                </div>
              </dd>
              <dd>
                <h3><img src="img/js_06.jpg" ></h3>
                <div><h4>手续费分润</h4>
        <? echo $bmx['description']['3'];?>
        	</div>
              </dd>
              <dd>
                <h3><img src="img/js_07.jpg" ></h3>
                <div><h4>升级奖励</h4>
        <? echo $bmx['content']['3'];?>
              </div>
              </dd>
             </dl>
</div>
		<div tab-id="2" class="tab"><dl>
              <dt>钻石会员权益简介</dt>
              <dd>
                <h3><img src="img/js_04.jpg" ></h3>
                <div><h4>升级金额（平台使用费）</h4>
        <? echo $bmx['price']['4'];?>
</div>
              </dd>
              <dd>
                <h3><img src="img/js_05.jpg" ></h3>
                <div><h4>扣率/ T+0</h4>
               <? echo $bmx['koulv']['4'];?>
               </div>
              </dd>
              <dd>
                <h3><img src="img/js_06.jpg" ></h3>
                <div><h4>手续费分润</h4> 
                <? echo $bmx['description']['4'];?>
               </div>
              </dd>
              <dd>
                <h3><img src="img/js_07.jpg" ></h3>
                <div><h4>升级奖励</h4> 
                <? echo $bmx['content']['4'];?>
                </div>
              </dd>
             </dl></div>
		<div tab-id="3" class="tab"><dl>
              <dt>皇冠会员权益简介</dt>
              <dd>
                <h3><img src="img/js_04.jpg" ></h3>
                <div><h4>升级金额（平台使用费）</h4>
        <? echo $bmx['price']['5'];?>
                 </div>
              </dd>
              <dd>
                <h3><img src="img/js_05.jpg" ></h3>
                <div><h4>扣率/ T+0</h4>
              <? echo $bmx['koulv']['5'];?>
              </div>
              </dd>
              <dd>
                <h3><img src="img/js_06.jpg" ></h3>
                <div><h4>手续费分润</h4>
                <? echo $bmx['description']['5'];?>
               
</div>
              </dd>
              <dd>
                <h3><img src="img/js_07.jpg" ></h3>
                <div><h4>升级奖励</h4>
                <? echo $bmx['content']['5'];?>
              
</div>
              </dd>
             </dl></div>
		<div tab-id="4" class="tab"><dl>
              <dt>投资合伙人权益简介</dt>
              <dd>
                <h3><img src="img/js_04.jpg" ></h3>
                <div><h4>升级金额（平台使用费）</h4>
    <? echo $bmx['price']['6'];?>
                </div>
              </dd>
              <dd>
                <h3><img src="img/js_05.jpg" ></h3>
                <div><h4>扣率/ T+0</h4>
              <? echo $bmx['koulv']['6'];?>
              </div>
              </dd>
              <dd>
                <h3><img src="img/js_06.jpg" ></h3>
                <div><h4>手续费分润</h4>
    <? echo $bmx['description']['6'];?>
</div>
              </dd>
              <dd>
                <h3><img src="img/js_07.jpg" ></h3>
                <div><h4>升级奖励</h4>
    <? echo $bmx['content']['6'];?>
                
</div>
              </dd>
             </dl></div>
	  </div>
</div>
<script>
$(document).ready(function () {
	var activePos = $('.tabs-header .active').position();
	function changePos() {
		activePos = $('.tabs-header .active').position();
		$('.border').stop().css({
			left: activePos.left,
			width: $('.tabs-header .active').width()
		});
	}
	changePos();
	var tabHeight = $('.tab.active').height();
	function animateTabHeight() {
		tabHeight = $('.tab.active').height();
		$('.tabs-content').stop().css({ height: tabHeight + 'px' });
	}
	animateTabHeight();
	function changeTab() {
		var getTabId = $('.tabs-header .active a').attr('tab-id');
		$('.tab').stop().fadeOut(300, function () {
			$(this).removeClass('active');
		}).hide();
		$('.tab[tab-id=' + getTabId + ']').stop().fadeIn(300, function () {
			$(this).addClass('active');
			animateTabHeight();
		});
	}
	$('.tabs-header a').on('click', function (e) {
		e.preventDefault();
		var tabId = $(this).attr('tab-id');
		$('.tabs-header a').stop().parent().removeClass('active');
		$(this).stop().parent().addClass('active');
		changePos();
		tabCurrentItem = tabItems.filter('.active');
		$('.tab').stop().fadeOut(300, function () {
			$(this).removeClass('active');
		}).hide();
		$('.tab[tab-id="' + tabId + '"]').stop().fadeIn(300, function () {
			$(this).addClass('active');
			animateTabHeight();
		});
	});
	var tabItems = $('.tabs-header ul li');
	var tabCurrentItem = tabItems.filter('.active');
	$('#next').on('click', function (e) {
		e.preventDefault();
		var nextItem = tabCurrentItem.next();
		tabCurrentItem.removeClass('active');
		if (nextItem.length) {
			tabCurrentItem = nextItem.addClass('active');
		} else {
			tabCurrentItem = tabItems.first().addClass('active');
		}
		changePos();
		changeTab();
	});
	$('#prev').on('click', function (e) {
		e.preventDefault();
		var prevItem = tabCurrentItem.prev();
		tabCurrentItem.removeClass('active');
		if (prevItem.length) {
			tabCurrentItem = prevItem.addClass('active');
		} else {
			tabCurrentItem = tabItems.last().addClass('active');
		}
		changePos();
		changeTab();
	});
	$('[ripple]').on('click', function (e) {
		var rippleDiv = $('<div class="ripple" />'), rippleOffset = $(this).offset(), rippleY = e.pageY - rippleOffset.top, rippleX = e.pageX - rippleOffset.left, ripple = $('.ripple');
		rippleDiv.css({
			top: rippleY - ripple.height() / 2,
			left: rippleX - ripple.width() / 2,
			background: $(this).attr('ripple-color')
		}).appendTo($(this));
		window.setTimeout(function () {
			rippleDiv.remove();
		}, 1500);
	});
});
</script>
</body>
</html>
