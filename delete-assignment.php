<?php
include_once("functions/functions.php");
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $getSpecificAssignment = new Staff();
  $specific_assignment = $getSpecificAssignment->getSpecificAssignment($id);
  $url = $specific_assignment['assignment_url'];
  $assignment_url="assignments/".$url;

  $deleteAssignment = new Staff();
  $deleteAssignment->deleteAssignment($id, $assignment_url);

  header("location: view-assignments.php");

} else{
	echo "No such file exist";
}

?>