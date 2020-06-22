<?php

include_once("functions/functions.php");

if(!empty($_POST)) {
$marks = $_POST['marks'];
  $academic_year = (int)$_POST['academic_year'];
  $term = (int)$_POST['term'];
  $students_student_no = $_POST['student_no'];
  $class_id = (int)$_POST['class_id'];
  $subject_id = (int)$_POST['subject_id'];

	switch ($class_id){
	  case $class_id==1:
		$exam_type_id=1;
		break;
	  case $class_id==2:
		$exam_type_id=1;
		break;
	  case $class_id==3:
		$exam_type_id=1;
		break;
	  case $class_id==4:
		$exam_type_id=1;
		break;
	  case $class_id==5:
		$exam_type_id=1;
		break;
	  case $class_id==6:
		$exam_type_id=2;
		break;
	  case $class_id==7:
		$exam_type_id=2;
		break;	  
	  default:
		$exam_type_id=1;
	}
		
		
    $recordStudentsExams = new Staff();
  $recordStudentsExams->recordStudentsExams($marks, $academic_year, $term, $students_student_no, $exam_type_id, $class_id, $subject_id);
}

?>

<div class="alert alert-success" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Success!</strong> You have successfully added a Mark
</div>

<script type="text/javascript">
	window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>