<?php
include_once("functions/functions.php");

if (isset($_POST['class_id'])) {
	$class_id = $_POST['class_id'];

	$getAllSubclasses = new Staff();
	$sub_class = $getAllSubclasses->getAllSubclasses($class_id);

            if(isset($sub_class) && count($sub_class)>0){
            echo "<option>Select Sub-Class</option>";
              foreach($sub_class as $sub_classes){ ?>
                <option value="<?php echo $sub_classes['sub_class_id']; ?>"><?php echo $sub_classes['sub_class_name']; ?></option>
              <?php
                
              }
            }
        

}

if (isset($_POST['sub_class_id'])) {
	$sub_class_id = $_POST['sub_class_id'];

	$getAllSubclassSubjects = new Staff();
	$subject = $getAllSubclassSubjects->getAllSubclassSubjects($sub_class_id);
		echo "<option>Select Subject</option>";
            if(isset($subject) && count($subject)>0){
              foreach($subject as $subjects){ ?>
                <option value="<?php echo $subjects['subject_id']; ?>"><?php echo $subjects['subject_name']; ?></option>
              <?php
                
              }
            }
        

}


//End of getting Drop own Lists for the Admin side

if (isset($_POST['sub_class'])) {
  $class_id = $_POST['sub_class'];

  $getSubjectsPerClassAndTeacher = new Staff();
  $subject = $getSubjectsPerClassAndTeacher->getSubjectsPerClassAndTeacher($class_id);

    echo "<option>Select Subject</option>";
            if(isset($subject) && count($subject)>0){
              foreach($subject as $subjects){ ?>
                <option value="<?php echo $subjects['subjects_id']; ?>"><?php echo $subjects['subject_name']; ?></option>
              <?php
                
              }
            }
        

}


if (isset($_POST['submit'])) {
	echo $class_id = $_POST['class_id']." class left";
  echo $sub_class_id = $_POST['sub_class_id']." sub class left";
  echo $subject_id = $_POST['subject_id']." subject left";
  echo $academic_year = $_POST['academic_year']. "aca year left";
  echo $term = $_POST['term'];
}


?>