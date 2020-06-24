<?php
include_once("functions/functions.php");

if (isset($_POST['sub_class_id'])) {
  $sub_class_id = $_POST['sub_class_id'];

  $getAllSubclassSubjects = new Staff();
  $subject = $getAllSubclassSubjects->getAllSubclassSubjects($sub_class_id);
            if(isset($subject) && count($subject)>0){
              foreach($subject as $subjects){ ?>
                <option value="<?php echo $subjects['subject_id']; ?>"><?php echo $subjects['subject_name']; ?></option>
              <?php
                
              }
            }
        

}
?>