<?php
include_once("functions/functions.php");
if(isset($_GET['id'])){
	$id = $_GET['id'];
	
	$enableSpecificStudent = new Students();
	$details = $enableSpecificStudent->enableSpecificStudent($id);

	header("location: view-students.php");
}

?>