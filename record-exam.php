<?php

include_once("functions/functions.php");

if(!empty($_POST)) {
$marks = $_POST['marks'];
  $academic_year = (int)$_POST['academic_year'];
  $term = (int)$_POST['term'];
  $students_student_no = $_POST['student_no'];
  $exam_type_id = 1;
  $sub_class_id = (int)$_POST['sub_class_id'];
  $subject_id = (int)$_POST['subject_id'];

    $recordStudentsExams = new Staff();
  $recordStudentsExams->recordStudentsExams($marks, $academic_year, $term, $students_student_no, $exam_type_id, $sub_class_id, $subject_id);
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