<?php
include_once("functions/functions.php");
$status = 1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);
$academic_year = $settings['id'];
$term = $settings['term'];

 if(isset($_POST["employee_id"]))  
 {  
      $output = ''; 

     $student_id = $_POST["employee_id"];

$getStudentsMarkPerSUbject = new Staff();
$mark = $getStudentsMarkPerSUbject->getStudentsMarkPerSUbject($academic_year, $term, $student_id);

      $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';

  if(isset($mark) && count($mark)>0){
    foreach($mark as $marks){
if(isset($mark) && count($mark)>0){ 
          ?>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>Subject</th>
                  <th>Grading Type</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Marks</th>
                </tr>
                </thead>
                <tbody>
                  <?php

          foreach($mark as $marks){ 
          ?>
          <tr>
                  <td><?php echo $marks['stu_no']; ?></td>
                  <td><?php echo $marks['subject_name']; ?></td>
                  <td><?php echo "CE1 + CE2 + Final Exam" ?></td>
                  <td><?php echo $marks['academic_year']; ?></td>
                  <td><?php echo $marks['term_name']; ?></td>

                  <td><?php if($marks['final_mark'] == 0 || $marks['exam_mark'] ==0){
                    echo "Not Marked";
                    }else{ 
                      echo $marks['final_mark'] + $marks['exam_mark']; }?> </td>
                  <td></td>

                </tr>



          <?php

          } ?>

                
                </tbody>
                <tfoot>
                <tr>
                  <th>Student ID</th>
                  <th>Subject</th>
                  <th>Grading Type</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Marks</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Marked Subjects Available at the moment";
                      }   
    }
  }
      $output .= "</table></div>";  
      echo $output;

 }  
 ?>