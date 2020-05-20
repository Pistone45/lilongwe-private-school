<?php
include_once("functions/functions.php");
if(isset($_GET['id'])){
	$id = $_GET['id'];
	
	$disableSpecificStudent = new Students();
	$details = $disableSpecificStudent->disableSpecificStudent($id);

	header("location: view-students.php");
}

?>