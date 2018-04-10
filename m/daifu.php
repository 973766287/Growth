<? 
$a =  $_REQUEST['encryptData'];

$b = $_REQUEST['encryptKey'];
$c = $_REQUEST['signData'];
$d = $_REQUEST['tranCode'];

 $handle =fopen('app/daili/bbbbbb.txt','a+'); 
           file_put_contents('app/daili/bbbbbb.txt','');
           fwrite($handle,$a.$b.$c.$d);
?>