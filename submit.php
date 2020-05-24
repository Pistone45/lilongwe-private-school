<?php
include_once("functions/functions.php");

  $marks = $_POST['mark'];
  $assignments_id = $_POST['assignments_id'];
  $students_student_no = $_POST['students_student_no'];


  $assignStudentMarks = new Staff();
  $marks = $assignStudentMarks->assignStudentMarks($marks, $assignments_id, $students_student_no);

 echo "Mark Assigned Successfully. You can close this window and refresh";
?>