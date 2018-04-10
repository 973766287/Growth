<?php
echo $week= date("w");
echo $mshi=date('H');
if($week=="6" or $week=="7" or ($week=="5" and $mshi>=17) or ($week=="1" and $mshi<="9"))
{
	echo "buxing";
	}
?>