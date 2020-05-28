<?php
include_once("functions/functions.php");

  $marks = $_POST['mark'];
  $academic_year = $_POST['academic_year'];
  $term = $_POST['term'];
  $students_student_no = $_POST['students_student_no'];
  $exam_type_id = $_POST['exam_type_id'];
  $staff_id = $_POST['staff_id'];
  $classes_has_subjects_classes_id = $_POST['classes_has_subjects_classes_id'];
  $classes_has_subjects_subjects_id = $_POST['classes_has_subjects_subjects_id'];

  if ($marks = $_POST['mark'] > 70) {
		?>
		<div class="alert alert-danger">
		<?php echo "Failed, The Mark should be between 0 to 70"; ?>
		</div> <?php

  } else {

  $marks = $_POST['mark'];
  $academic_year = $_POST['academic_year'];
  $term = $_POST['term'];
  $students_student_no = $_POST['students_student_no'];
  $exam_type_id = $_POST['exam_type_id'];
  $staff_id = $_POST['staff_id'];
  $classes_has_subjects_classes_id = $_POST['classes_has_subjects_classes_id'];
  $classes_has_subjects_subjects_id = $_POST['classes_has_subjects_subjects_id'];

  	$recordStudentsExams = new Staff();
	$record = $recordStudentsExams->recordStudentsExams($marks, $academic_year, $term, $students_student_no, $exam_type_id, $staff_id, $classes_has_subjects_classes_id, $classes_has_subjects_subjects_id);
		
		?>
		<div class="alert alert-success">
		<?php echo "Exam Mark Assigned Successfully. Click Close to close dismiss this"; ?>
		</div> <?php
 
  }
  

?>