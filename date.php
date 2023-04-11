<?php
$date = date('Y-m-d', time());
$date2 = date('Y-m-d', time()-31536000);
$date3 = strtotime('today');
$date4 = strtotime('-1 Years');
echo $date."<br>";
echo $date2."<br>";
echo $date3."<br>";
echo $date4;
?>