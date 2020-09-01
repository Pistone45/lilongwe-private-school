<?php
include_once 'functions/functions.php';

if (isset($_POST['sub_class_id'])) {
	$sub_class_id = $_POST['sub_class_id'];

	$getStudentsPerSubClassName = new Staff();
	$students = $getStudentsPerSubClassName->getStudentsPerSubClassName($sub_class_id);

            if(isset($students) && count($students)>0){
              foreach($students as $student){ ?>
                <option value="<?php echo $student['student_no']; ?>"><?php echo $student['student_no']; ?></option>
              <?php
                
              }
            }
        

}



?>