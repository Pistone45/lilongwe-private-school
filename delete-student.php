<?php
include_once("functions/functions.php");

if (isset($_POST['delete'])) {
	echo $student_no = $_POST['student_no'];

	$deleteStudent  = new Students();
	$deleteStudent->deleteStudent($student_no);

	header("location: view-students.php");
}


?>