<?php
include_once("functions/functions.php");
$f_pointer=fopen("numbers.csv","r"); // file pointer

while(! feof($f_pointer)){
$ar=fgetcsv($f_pointer);

$addNumbers = new Contact();
$addNumbers->addNumbers($ar);
}
if(isset($_SESSION['numbers-added']) && $_SESSION['numbers-added']==true){
	echo "done processing";
}


?>