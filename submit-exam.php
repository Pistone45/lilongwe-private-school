<?php
include_once("functions/functions.php");

  $marks = $_POST['mark'];
  $student_no = $_POST['student_no'];


  if ($marks = $_POST['mark'] > 15) {
		?>
		<div class="alert alert-danger">
		<?php echo "Failed, The Mark should be between 0 to 15"; ?>
		</div> <?php

  } else {

  $marks = $_POST['mark'];
  $student_no = $_POST['student_no'];

  	$updateStudentExamMark = new Staff();
	$update = $updateStudentExamMark->updateStudentExamMark($marks, $student_no);
		
		?>
		<div class="alert alert-success">
		<?php echo "Exam Mark Updated Successfully. Click Close to dismiss this"; ?>
		</div> <?php
 
  }
  

?>