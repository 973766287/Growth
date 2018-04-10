<header class="top_header">商家收款二维码</header>

<style>
  body{margin:0px; padding:0;background-color: #FFFFFF;}
 .codebg_box{ width:100%; position: fixed; bottom:0; top:50px; height:100%; }
 .codebg_box_img1{ height:13%; display:block;}
 .codebg_box_img1 img{ width:100%; display:block;}
 .codebg_box_img2{ height:60%; display:block; text-align:center; position:relative;}
 .codebg_box_img2 img{ width:70%; border:1px solid #e6e6e6; border-radius:6px; padding:6px; margin:0px auto; position:absolute; top:40%; margin-top:-32%; left:47%; margin-left:-32%;}
 .codebg_box_img3{ height:37%; display:block;}
 .codebg_box_img3 img{ width:100%; display:block; position:fixed; bottom:0;}
</style>

<div  class="codebg_box">
  <div class="codebg_box_img1"><img src="img/codebg_01.png"></div>
  <div class="codebg_box_img2"><img  src="http://qr.liantu.com/api.php?text=<? echo urlencode($url);?>"/></div>
  <div class="codebg_box_img3"><img src="img/codebg_03.png"></div>
</div>