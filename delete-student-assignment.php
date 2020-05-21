<?php
include_once("functions/functions.php");
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $getSpecificStudentAssignmentURL = new Staff();
  $specific_assignment = $getSpecificStudentAssignmentURL->getSpecificStudentAssignmentURL($id);

  $url = $specific_assignment['submitted_assignment'];
  $assignment_url="assignments/students/".$url;

  $deleteStudentAssignment = new Staff();
  $deleteStudentAssignment->deleteStudentAssignment($id, $assignment_url);

  header("location: view-assignments.php");

} else{
	echo "No such file exist";
}

?>