<link rel="stylesheet" href="/m/style.css" type="text/css">

  <div>
  <img style="width:100%;" src="img/zfb_url.jpg"/>
  </div>
  
  <script>
  
  window.onload = function(){ 
　　 var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return false;
     } else {
       window.location.href="<? echo $qr_code;?>";
    }
} 



  </script>

