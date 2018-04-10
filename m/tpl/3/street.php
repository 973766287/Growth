<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<?php $this->element('3/top',array('lang'=>$lang)); ?>
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>
<div id="main">
<style type="text/css">
.search_index {
margin: 5px 0px 3px 5px;
height: 46px;
border-radius: 2px;
background: url(<?php echo $this->img('input_bg.png');?>) repeat-x top left;
}
.search_index .right {
width: 64px;
float: right;
text-align: left;
}
.search_index .left {
text-align: center; height:46px;
}
.search_index .input1 {
height: 46px;
line-height: 46px;
text-indent: 5px;
color: #787575;
border: none;
background: url(<?php echo $this->img('611.png');?>) no-repeat center left;
display: block;
float: left;
width: 75%;
}
.goodslists{ min-height:300px;}
</style>
 
    <li><a href="?act=index&suppId=<? echo $shop['supplier_id'];?>">
       <div class="stlist_box_img"><img src="<? echo $shop['logo'];?>"></div>
      <!-- <div class="stlist_box_logo"><img src="img2/logo.png"></div>-->
       <p><? echo $shop['supplier_name'];?></p>
    </a></li>
  
    <? foreach($rt['categoodslist'] as $shop){?>
<div class="stor_list">
  <div class="stor_list_top">
   <a href="?act=index&suppId=<? echo $shop['supplier_id'];?>">进入店铺</a>
   <div><img src="../<? echo $shop['logo'];?>" ></div>
   <h2><? echo $shop['supplier_name'];?></h2>
  </div>
  <!--<div class="stor_list_cet"><img src="../img2/2.jpg" ></div>-->
  <? if (!empty($rt['goods'][$shop['supplier_id']])) {?>
  <div class="stor_list_bot">
    <ul>
     <?php foreach ($rt['goods'][$shop['supplier_id']] as $k => $rows) {?>
                         
                             <? if($k == 0){?>
    <li><a href="product.php?id=<? echo $rows['goods_id'];?>"><img src="../<? echo $rows['goods_img'];?>"></a>
    <p align="center"><? echo $rows['goods_name'];?> <span>￥<? echo $rows['pifa_price'];?> </span></p>
    </li>
    <? }else if($k == 1){?>
    <li  style="border-left:1px solid #e6e6e6;border-right:1px solid #e6e6e6;"><a href="product.php?id=<? echo $rows['goods_id'];?>"><img src="../<? echo $rows['goods_img'];?>"></a>
    <p align="center"><? echo $rows['goods_name'];?> <span>￥<? echo $rows['pifa_price'];?> </span></p>
    </li>
    <? }else{?>
    <li><a href="product.php?id=<? echo $rows['goods_id'];?>"><img src="../<? echo $rows['goods_img'];?>"></a>
    <p align="center"><? echo $rows['goods_name'];?> <span>￥<? echo $rows['pifa_price'];?> </span></p>
    </li>
    <? }}?>
    </ul>
  </div>
  <? }?>
</div>

 <? }?>



<style>
.stor_list{  background:#fff; border-radius:3px; overflow:hidden; margin:5px;}
.stor_list_top{ height:55px; width:100%; position:relative;}
.stor_list_top a{ float:right; border:1px solid #dd2727; color:#dd2727; height:25px; padding:0 8px; line-height:25px; text-align:center; font-size:12px; border-radius:2px; margin-top:9px; margin-right:5px;}
.stor_list_top div{ width:52px; height:52px; position:absolute; top:0; left:5px;}
.stor_list_top div img{ width:100%;}
.stor_list_top h2{ padding-left:65px; overflow:hidden; font-size:14px; line-height:20px; padding-top:5px;}
.stor_list_top h2 p{ font-size:12px; font-weight:100; color:#777;}
.stor_list_cet{ width:100%; max-height:140px; overflow:hidden;}
.stor_list_cet img{ width:100%;}
.stor_list_bot{ width:100%; overflow:hidden;}
.stor_list_bot ul li{ float:left; width:33%;}
.stor_list_bot ul li img{ width:100%;}

body {
	margin: 0px;
	padding: 0px;
}

a {
	text-decoration: none;
}

h2,p,ul {
	margin: 0px;
	padding: 0px;
}

ul li {
	margin: 0px;
	padding: 0px;
	list-style: none;
}

.stor_img {
	margin: 0px auto;
	overflow: hidden;
}

.stor_img ul li {
	margin-left: 1%;
	margin-bottom: 10px;
	margin-right: 1%;
	width: 48%;
	position: relative;
	float: left;
	background: #fff;
	overflow: hidden;
}

.stor_img_top {
	width: 100%;
}

.stor_img_top img {
	width: 100%;
}

.stor_img_bot {
	padding: 5px;
	font-size: 12px;
	color: #333;
}

.stor_img_bot_tlt {
	line-height: 18px;
	color: #333;
	height: 36px;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	word-wrap: break-word;
	overflow: hidden;
}

.stor_img_bot_pri {
	font-size: 14px;
	font-weight: 700;
	color: #DD2727;
	margin-top: 5px;
	height: 14px;
	line-height: 14px;
}

.stor_img_bot_pri span {
	float: right;
	font-size: 12px;
	font-weight: 100;
	color: #999;
}

.stor_hot {
	width: 100%;
	overflow: hidden;
}

.stor_hot ul li {
	border-bottom: 1px solid #e6e6e6;
	overflow: hidden;
	padding: 0 5px 8px;
	margin-top: 10px;
}

.stor_hot_lf {
	border: 1px solid #e6e6e6;
	float: left;
	width: 40%;
}

.stor_hot_lf img {
	width: 100%;
}

.stor_hot_rg {
	float: right;
	width: 54%;
}

.stor_hot_rg h3 {
	line-height: 18px;
	font-size: 12px;
}

.stor_hot_rg  h2 {
	height: 28px;
	line-height: 28px;
	color: #fff;
	background: #333;
	width: 90%;
}

.stor_hot_rg  h2 span {
	background: #C00;
	display: inline-block;
	width: 60%;
	text-align: center;
	margin-right: 8%;
}

.stor_hot_rg h4 {
	margin-top: 8px;
	height: 20px;
	color: #096;
	font-size: 12px;
}

.stor_new {
	background: #fff;
	overflow: hidden;
	margin: 10px auto;
	padding-bottom: 5px;
}

.stor_new_top {
	height: 80px;
	overflow: hidden;
}

.stor_new_top_lf {
	float: left;
	width: 25%;
	display: block;
	height: 80px;
}

.stor_new_top_ct {
	background: url(img/s1.gif) no-repeat bottom;
	width: 50%;
	float: left;
	display: block;
	height: 80px;
	background-size: 100%;
	text-align: center;
	line-height: 50px;
	font-size: 18px;
}

.stor_new_top_rg {
	float: right;
	width: 25%;
}

.stor_new_top_rg img {
	width: 100%;
}

.stor_new_box LI {
	float: left;
	width: 32%;
	border: 1px solid #e6e6e6;
	padding: 1%;
	margin-left: 1%;
	font-size: 12px;
}

.stor_new_box li img {
	width: 100%;
}

.stor_new_box li p {
	margin: 5px auto;
	font-weight: bold;
	font-size: 14px;
	color: #6C6C6C;
}

.stor_new_box li p span {
	color: #999;
	font-size: 12px;
	font-weight: 100;
}

.stor_in_header {
	position: relative;
	width: 100%;
	height: 118px;
	background: rgba(0,0,0,.25);
	overflow: hidden;
}

.stor_in_img {
	background-color: rgba(0,0,0,1);
	width: 100%;
	height: 100%;
	position: absolute;
	left: 0;
	z-index: 9;
	top: 0;
}

.stor_in_img .back {
	position: absolute;
	height: 100%;
	width: 100%;
	top: 50%;
	left: 50%;
	-webkit-transform: translate3d(-50%,-50%,0);
	transform: translate3d(-50%,-50%,0);
	opacity: 0.5;
}

.stor_in_header-info {
	z-index: 99;
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
}

.stor_in_logo {
	width: 66px;
	height: 66px;
	position: relative;
	z-index: 9;
	float: left;
}

.stor_in_logo img {
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%,-50%);
	-ms-transform: translate(-50%,-50%);
	transform: translate(-50%,-50%);
	width: 66px;
	height: 66px;
}

.collect-wrapper {
	float: right;
	margin-top: 5px;
}

.collect-btn {
	font-size: 14px;
	line-height: 46px;
	background-color: #DD2727;
}

.collect-item {
	width: 58px;
	height: 46px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	float: left;
	color: #fff;
	text-align: center;
	border-radius: 8px;
}

.collect-counter {
	margin-top: 10px;
	margin-bottom: 2px;
}

.brand-tag {
	background-color: #DD2727;
	border-radius: 2px;
	color: #fff;
	position: absolute;
	padding: 2px 3px;
	font-size: 12px;
	line-height: 12px;
	height: 12px;
	margin: 0;
}

.ctn {
	display: inline-block;
	height: 27px;
	font-size: 14px;
	color: #fff;
	line-height: 27px;
	width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.contact {
	position: relative;
	height: 47px;
	margin-left: 75px;
	margin-top: 7px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.stor_in_nav {
	font-size: 12px;
}

.stor_in_nav_con {
	position: relative;
	width: 100%;
	height: 62px;
	z-index: 9999;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	box-shadow: 0 1px 1px rgba(0,0,0,.2);
	display: table;
	background-color: #fff;
	border-top: 1px solid rgba(0,0,0,.1);
	border-bottom: 1px solid rgba(0,0,0,.1);
}

.stor_in_nav_con_item {
	position: relative;
	display: table-cell;
	text-align: center;
	color: #051B28;
	padding-top: 10px;
}

.stor_in_nav_con_but img {
	width: 24px;
}

.swipe {
	width: 100%;
	overflow: hidden;
	position: relative
}

.swipe ul {
	-webkit-transition: left 800ms ease-in 0;
	-moz-transition: left 800ms ease-in 0;
	-o-transition: left 800ms ease-in 0;
	-ms-transition: left 800ms ease-in 0;
	transition: left 800ms ease-in 0
}

.swipe #pagenavi {
	position: absolute;
	left: 0;
	bottom: 10px;
	text-align: center;
	width: 100%
}

.swipe #pagenavi a {
	width: 10px;
	height: 10px;
	line-height: 99em;
	background: #fff;
	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	margin: 0 3px;
	overflow: hidden;
	cursor: pointer;
	display: inline-block
}

.swipe #pagenavi a.active {
	background: #656565;
}

.swiper-container {
	width: 100%;
	height: 100%;
}

.swiper-slide {
	text-align: center;
	font-size: 18px;
	background: #fff;
	border-bottom: 1px solid #d9d9d9;
        /* Center slide text vertically */
	display: -webkit-box;
	display: -ms-flexbox;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	-webkit-justify-content: center;
	justify-content: center;
	-webkit-box-align: center;
	-ms-flex-align: center;
	-webkit-align-items: center;
	align-items: center;
}

.swiper-container {
	margin: 0 auto;
	position: relative;
	overflow: hidden;
	z-index: 1
}

.swiper-container-no-flexbox .swiper-slide {
	float: left
}

.swiper-container-vertical>.swiper-wrapper {
	-webkit-box-orient: vertical;
	-moz-box-orient: vertical;
	-ms-flex-direction: column;
	-webkit-flex-direction: column;
	flex-direction: column
}

.swiper-wrapper {
	position: relative;
	width: 100%;
	height: 100%;
	z-index: 1;
	display: -webkit-box;
	display: -moz-box;
	display: -ms-flexbox;
	display: -webkit-flex;
	display: flex;
	-webkit-transition-property: -webkit-transform;
	-moz-transition-property: -moz-transform;
	-o-transition-property: -o-transform;
	-ms-transition-property: -ms-transform;
	transition-property: transform;
	-webkit-box-sizing: content-box;
	-moz-box-sizing: content-box;
	box-sizing: content-box
}

.swiper-container-android .swiper-slide,.swiper-wrapper {
	-webkit-transform: translate3d(0,0,0);
	-moz-transform: translate3d(0,0,0);
	-o-transform: translate(0,0);
	-ms-transform: translate3d(0,0,0);
	transform: translate3d(0,0,0)
}

.swiper-container-multirow>.swiper-wrapper {
	-webkit-box-lines: multiple;
	-moz-box-lines: multiple;
	-ms-flex-wrap: wrap;
	-webkit-flex-wrap: wrap;
	flex-wrap: wrap
}

.swiper-container-free-mode>.swiper-wrapper {
	-webkit-transition-timing-function: ease-out;
	-moz-transition-timing-function: ease-out;
	-ms-transition-timing-function: ease-out;
	-o-transition-timing-function: ease-out;
	transition-timing-function: ease-out;
	margin: 0 auto
}

.swiper-slide {
	-webkit-flex-shrink: 0;
	-ms-flex: 0 0 auto;
	flex-shrink: 0;
	width: 100%;
	height: 100%;
	position: relative
}

.swiper-container .swiper-notification {
	position: absolute;
	left: 0;
	top: 0;
	pointer-events: none;
	opacity: 0;
	z-index: -1000
}

.swiper-wp8-horizontal {
	-ms-touch-action: pan-y;
	touch-action: pan-y
}

.swiper-wp8-vertical {
	-ms-touch-action: pan-x;
	touch-action: pan-x
}

.swiper-button-next,.swiper-button-prev {
	position: absolute;
	top: 50%;
	width: 27px;
	height: 44px;
	margin-top: -22px;
	z-index: 10;
	cursor: pointer;
	-moz-background-size: 27px 44px;
	-webkit-background-size: 27px 44px;
	background-size: 27px 44px;
	background-position: center;
	background-repeat: no-repeat
}


.swiper-button-next.swiper-button-disabled,.swiper-button-prev.swiper-button-disabled {
	opacity: .35;
	cursor: auto;
	pointer-events: none
}

.swiper-button-prev,.swiper-container-rtl .swiper-button-next {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23007aff'%2F%3E%3C%2Fsvg%3E");
	left: 10px;
	right: auto
}

.swiper-button-prev.swiper-button-black,.swiper-container-rtl .swiper-button-next.swiper-button-black {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23000000'%2F%3E%3C%2Fsvg%3E")
}

.swiper-button-prev.swiper-button-white,.swiper-container-rtl .swiper-button-next.swiper-button-white {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23ffffff'%2F%3E%3C%2Fsvg%3E")
}

.swiper-button-next,.swiper-container-rtl .swiper-button-prev {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23007aff'%2F%3E%3C%2Fsvg%3E");
	right: 10px;
	left: auto
}

.swiper-button-next.swiper-button-black,.swiper-container-rtl .swiper-button-prev.swiper-button-black {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23000000'%2F%3E%3C%2Fsvg%3E")
}

.swiper-button-next.swiper-button-white,.swiper-container-rtl .swiper-button-prev.swiper-button-white {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23ffffff'%2F%3E%3C%2Fsvg%3E")
}

.swiper-pagination {
	position: absolute;
	text-align: center;
	-webkit-transition: 300ms;
	-moz-transition: 300ms;
	-o-transition: 300ms;
	transition: 300ms;
	-webkit-transform: translate3d(0,0,0);
	-ms-transform: translate3d(0,0,0);
	-o-transform: translate3d(0,0,0);
	transform: translate3d(0,0,0);
	z-index: 10
}

.swiper-pagination.swiper-pagination-hidden {
	opacity: 0
}

.swiper-pagination-bullet {
	width: 8px;
	height: 8px;
	display: inline-block;
	border-radius: 100%;
	background: #000;
	opacity: .2
}

button.swiper-pagination-bullet {
	border: none;
	margin: 0;
	padding: 0;
	box-shadow: none;
	-moz-appearance: none;
	-ms-appearance: none;
	-webkit-appearance: none;
	appearance: none
}

.swiper-pagination-clickable .swiper-pagination-bullet {
	cursor: pointer
}

.swiper-pagination-white .swiper-pagination-bullet {
	background: #fff
}

.swiper-pagination-bullet-active {
	opacity: 1;
	background: #007aff
}

.swiper-pagination-white .swiper-pagination-bullet-active {
	background: #fff
}

.swiper-pagination-black .swiper-pagination-bullet-active {
	background: #000
}

.swiper-container-vertical>.swiper-pagination {
	right: 10px;
	top: 50%;
	-webkit-transform: translate3d(0,-50%,0);
	-moz-transform: translate3d(0,-50%,0);
	-o-transform: translate(0,-50%);
	-ms-transform: translate3d(0,-50%,0);
	transform: translate3d(0,-50%,0)
}

.swiper-container-vertical>.swiper-pagination .swiper-pagination-bullet {
	margin: 5px 0;
	display: block
}

.swiper-container-horizontal>.swiper-pagination {
	bottom: 4px;
	left: 0;
	width: 100%
}

.swiper-container-horizontal>.swiper-pagination .swiper-pagination-bullet {
	margin: 0 5px
}

.swiper-container-3d {
	-webkit-perspective: 1200px;
	-moz-perspective: 1200px;
	-o-perspective: 1200px;
	perspective: 1200px
}

.swiper-container-3d .swiper-cube-shadow,.swiper-container-3d .swiper-slide,.swiper-container-3d .swiper-slide-shadow-bottom,.swiper-container-3d .swiper-slide-shadow-left,.swiper-container-3d .swiper-slide-shadow-right,.swiper-container-3d .swiper-slide-shadow-top,.swiper-container-3d .swiper-wrapper {
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	-ms-transform-style: preserve-3d;
	transform-style: preserve-3d
}

.swiper-container-3d .swiper-slide-shadow-bottom,.swiper-container-3d .swiper-slide-shadow-left,.swiper-container-3d .swiper-slide-shadow-right,.swiper-container-3d .swiper-slide-shadow-top {
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	pointer-events: none;
	z-index: 10
}

.swiper-container-3d .swiper-slide-shadow-left {
	background-image: -webkit-gradient(linear,left top,right top,from(rgba(0,0,0,.5)),to(rgba(0,0,0,0)));
	background-image: -webkit-linear-gradient(right,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -moz-linear-gradient(right,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -o-linear-gradient(right,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: linear-gradient(to left,rgba(0,0,0,.5),rgba(0,0,0,0))
}

.swiper-container-3d .swiper-slide-shadow-right {
	background-image: -webkit-gradient(linear,right top,left top,from(rgba(0,0,0,.5)),to(rgba(0,0,0,0)));
	background-image: -webkit-linear-gradient(left,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -moz-linear-gradient(left,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -o-linear-gradient(left,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: linear-gradient(to right,rgba(0,0,0,.5),rgba(0,0,0,0))
}

.swiper-container-3d .swiper-slide-shadow-top {
	background-image: -webkit-gradient(linear,left top,left bottom,from(rgba(0,0,0,.5)),to(rgba(0,0,0,0)));
	background-image: -webkit-linear-gradient(bottom,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -moz-linear-gradient(bottom,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -o-linear-gradient(bottom,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: linear-gradient(to top,rgba(0,0,0,.5),rgba(0,0,0,0))
}

.swiper-container-3d .swiper-slide-shadow-bottom {
	background-image: -webkit-gradient(linear,left bottom,left top,from(rgba(0,0,0,.5)),to(rgba(0,0,0,0)));
	background-image: -webkit-linear-gradient(top,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -moz-linear-gradient(top,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: -o-linear-gradient(top,rgba(0,0,0,.5),rgba(0,0,0,0));
	background-image: linear-gradient(to bottom,rgba(0,0,0,.5),rgba(0,0,0,0))
}

.swiper-container-coverflow .swiper-wrapper {
	-ms-perspective: 1200px
}

.swiper-container-fade.swiper-container-free-mode .swiper-slide {
	-webkit-transition-timing-function: ease-out;
	-moz-transition-timing-function: ease-out;
	-ms-transition-timing-function: ease-out;
	-o-transition-timing-function: ease-out;
	transition-timing-function: ease-out
}

.swiper-container-fade .swiper-slide {
	pointer-events: none
}

.swiper-container-fade .swiper-slide .swiper-slide {
	pointer-events: none
}

.swiper-container-fade .swiper-slide-active,.swiper-container-fade .swiper-slide-active .swiper-slide-active {
	pointer-events: auto
}

.swiper-container-cube {
	overflow: visible
}

.swiper-container-cube .swiper-slide {
	pointer-events: none;
	visibility: hidden;
	-webkit-transform-origin: 0 0;
	-moz-transform-origin: 0 0;
	-ms-transform-origin: 0 0;
	transform-origin: 0 0;
	-webkit-backface-visibility: hidden;
	-moz-backface-visibility: hidden;
	-ms-backface-visibility: hidden;
	backface-visibility: hidden;
	width: 100%;
	height: 100%;
	z-index: 1
}

.swiper-container-cube.swiper-container-rtl .swiper-slide {
	-webkit-transform-origin: 100% 0;
	-moz-transform-origin: 100% 0;
	-ms-transform-origin: 100% 0;
	transform-origin: 100% 0
}

.swiper-container-cube .swiper-slide-active,.swiper-container-cube .swiper-slide-next,.swiper-container-cube .swiper-slide-next+.swiper-slide,.swiper-container-cube .swiper-slide-prev {
	pointer-events: auto;
	visibility: visible
}

.swiper-container-cube .swiper-slide-shadow-bottom,.swiper-container-cube .swiper-slide-shadow-left,.swiper-container-cube .swiper-slide-shadow-right,.swiper-container-cube .swiper-slide-shadow-top {
	z-index: 0;
	-webkit-backface-visibility: hidden;
	-moz-backface-visibility: hidden;
	-ms-backface-visibility: hidden;
	backface-visibility: hidden
}

.swiper-container-cube .swiper-cube-shadow {
	position: absolute;
	left: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
	background: #000;
	opacity: .6;
	-webkit-filter: blur(50px);
	filter: blur(50px);
	z-index: 0
}

.swiper-scrollbar {
	border-radius: 10px;
	position: relative;
	-ms-touch-action: none;
	background: rgba(0,0,0,.1)
}

.swiper-container-horizontal>.swiper-scrollbar {
	position: absolute;
	left: 1%;
	bottom: 3px;
	z-index: 50;
	height: 5px;
	width: 98%
}

.swiper-container-vertical>.swiper-scrollbar {
	position: absolute;
	right: 3px;
	top: 1%;
	z-index: 50;
	width: 5px;
	height: 98%
}

.swiper-scrollbar-drag {
	height: 100%;
	width: 100%;
	position: relative;
	background: rgba(0,0,0,.5);
	border-radius: 10px;
	left: 0;
	top: 0
}

.swiper-scrollbar-cursor-drag {
	cursor: move
}

.swiper-lazy-preloader {
	width: 42px;
	height: 42px;
	position: absolute;
	left: 50%;
	top: 50%;
	margin-left: -21px;
	margin-top: -21px;
	z-index: 10;
	-webkit-transform-origin: 50%;
	-moz-transform-origin: 50%;
	transform-origin: 50%;
	-webkit-animation: swiper-preloader-spin 1s steps(12,end) infinite;
	-moz-animation: swiper-preloader-spin 1s steps(12,end) infinite;
	animation: swiper-preloader-spin 1s steps(12,end) infinite
}

.swiper-lazy-preloader:after {
	display: block;
	content: "";
	width: 100%;
	height: 100%;
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox%3D'0%200%20120%20120'%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20xmlns%3Axlink%3D'http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink'%3E%3Cdefs%3E%3Cline%20id%3D'l'%20x1%3D'60'%20x2%3D'60'%20y1%3D'7'%20y2%3D'27'%20stroke%3D'%236c6c6c'%20stroke-width%3D'11'%20stroke-linecap%3D'round'%2F%3E%3C%2Fdefs%3E%3Cg%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(30%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(60%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(90%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(120%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(150%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.37'%20transform%3D'rotate(180%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.46'%20transform%3D'rotate(210%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.56'%20transform%3D'rotate(240%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.66'%20transform%3D'rotate(270%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.75'%20transform%3D'rotate(300%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.85'%20transform%3D'rotate(330%2060%2C60)'%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");
	background-position: 50%;
	-webkit-background-size: 100%;
	background-size: 100%;
	background-repeat: no-repeat
}

.swiper-lazy-preloader-white:after {
	background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox%3D'0%200%20120%20120'%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20xmlns%3Axlink%3D'http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink'%3E%3Cdefs%3E%3Cline%20id%3D'l'%20x1%3D'60'%20x2%3D'60'%20y1%3D'7'%20y2%3D'27'%20stroke%3D'%23fff'%20stroke-width%3D'11'%20stroke-linecap%3D'round'%2F%3E%3C%2Fdefs%3E%3Cg%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(30%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(60%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(90%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(120%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(150%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.37'%20transform%3D'rotate(180%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.46'%20transform%3D'rotate(210%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.56'%20transform%3D'rotate(240%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.66'%20transform%3D'rotate(270%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.75'%20transform%3D'rotate(300%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.85'%20transform%3D'rotate(330%2060%2C60)'%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E")
}

@-webkit-keyframes swiper-preloader-spin {
	100% {
		-webkit-transform: rotate(360deg)
	}
}

@keyframes swiper-preloader-spin {
	100% {
		transform: rotate(360deg)
	}
}

.stor_box {
	border-bottom: 1px solid #d6d6d6;
	border-top: 1px solid #d6d6d6;
	background: #fff;
	padding: 10px;
	margin: 10px auto;
	overflow: hidden;
}

.stor_box ul li {
	float: left;
	width: 50%;
}

.stor_box ul li img {
	width: 98%;
}
</style>

</div>
<?php
$title = !empty($rt['cateinfo']['cat_title']) ? $rt['cateinfo']['cat_title'] : $rt['cateinfo']['cat_name'];
$imgs = $imgs[rand(0,count($imgs)-1)];
?>
<?php
 $thisurl = Import::basic()->thisurl();
 $rr = explode('?',$thisurl);
 $t2 = isset($rr[1])&&!empty($rr[1]) ? $rr[1] : "";
 $dd = array();
 if(!empty($t2)){
 	$rr2 = explode('&',$t2);
	if(!empty($rr2))foreach($rr2 as $v){
		$rr2 = explode('=',$v);
		if($rr2[0]=='from' || $rr2[0]=='isappinstalled'|| $rr2[0]=='code'|| $rr2[0]=='state') continue;
		$dd[] = $v;
	}
 }
 $thisurl = $rr[0].'?'.(!empty($dd) ? implode('&',$dd) : 'tid=0');
?>
<script type="text/javascript">
  function _report(a,c){
		$.post('<?php ADMIN_URL;?>product.php',{action:'ajax_share',type:a,msg:c,thisurl:'<?php echo Import::basic()->thisurl();?>',imgurl:'<?php echo $imgs;?>',title:'<?php echo $title;?>'},function(data){
		});
  }
  
  document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        window.shareData = {  
            "imgUrl": "<?php echo $imgs;?>",
            "LineLink": '<?php echo $thisurl;?>',
            "Title": "<?php echo $title;?>",
            "Content": "<?php echo !empty($rt['cateinfo']['cat_desc']) ? $rt['cateinfo']['cat_desc'] : $title;?>"
        };
        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            WeixinJSBridge.invoke('sendAppMessage', { 
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.LineLink,
                "desc": window.shareData.Content,
                "title": window.shareData.Title
            }, function (res) {
                _report('send_msg', res.err_msg);
            })
        });
        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            WeixinJSBridge.invoke('shareTimeline', {
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.LineLink,
                "desc": window.shareData.Content,
                "title": window.shareData.Title
            }, function (res) {
                _report('timeline', res.err_msg);
            });
        });
        // 分享到微博
        WeixinJSBridge.on('menu:share:weibo', function (argv) {
            WeixinJSBridge.invoke('shareWeibo', {
                "content": window.shareData.Content,
                "url": window.shareData.LineLink,
            }, function (res) {
                _report('weibo', res.err_msg);
            });
        });
        }, false)
</script>
<?php $this->element('3/footer',array('lang'=>$lang)); ?>