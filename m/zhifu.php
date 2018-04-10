<? 
$a =  $_REQUEST['encryptData'];

$b = $_REQUEST['encryptKey'];
$c = $_REQUEST['signData'];
$d = $_REQUEST['tranCode'];

 $handle =fopen('app/shopping/bbbbbb.txt','a+'); 
           file_put_contents('app/shopping/bbbbbb.txt','');
           fwrite($handle,$a.$b.$c.$d);
?>