<?php
include_once("functions/functions.php");

//get current academic_year and term
$status = 1;
$getCurrentSettings = new settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

//gett exam type
$getExamTypes = new Staff();
$exam_type = $getExamTypes->getExamTypes();



if (isset($_POST['submit'])) {
	$subject_id = $_POST['subject_id'];
	$sub_class_id = $_POST['sub_class_id'];
	
	//get all students taking the subject per class
	$getAllStudentsPerClassSubject = new Staff();
	$student = $getAllStudentsPerClassSubject->getAllStudentsPerClassSubject($sub_class_id, $subject_id);
	
}

if(isset($_POST['addMarks'])) {
  $marks = $_POST['marks'];
  $academic_year = (int)$settings['academic_year'];
  $term = (int)$settings['term'];
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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Record Exams| Lilongwe Private School</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="submit.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include_once("header.html"); ?>
  <!-- Left side column. contains the logo and sidebar -->
   <?php include_once('sidebar.html'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Display Assignments
       
      </h1>
    <div class="result">
    </div>
      <ol class="breadcrumb">
        <li><a href="teacher-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Assign Exams Results</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
		  <?php
                            if(isset($_SESSION["marks-added"]) && $_SESSION["marks-added"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully Added Marks";
                                unset($_SESSION["marks-added"]);
                                echo "</div>";
								 header('Refresh: 5; URL= filter-exam-results.php');
                            }
							?>

      <?php
      $i = 0;
        if(isset($student) && count($student)>0){ 
          ?>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Marks</th>
                 
                </tr>
                </thead>
                <tbody>
                  <?php

          foreach($student as $students){ 
            $i++;  ?>
          <tr>
                  <td><?php echo $students['student_no']; ?></td>
                  <td><?php echo $students['firstname']; ?></td>
                  <td><?php echo $students['lastname']; ?></td>
                  <td><?php echo $students['subject']; ?></td>
                  <td><?php echo "Final Exam"; ?></td>
				   <form role="form" action="record-exams.php"  method="POST">
                  <td>
					<input type="hidden" id="student_no"  value="<?php echo $students['student_no']; ?>" name="student_no[]">	
					<input type="number" id="marks" min="0" max="70" name="marks[]"  placeholder="Enter Marks" required="" class="form-control"/>	 			 
				  </td>
				 
				 
               
      </tr>



          


          <?php
            
          } ?>

                
                </tbody>
                <tfoot>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Marks</th>
                 
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Students Available to record Exams";
                      }
        ?>
					
					<input type="hidden" id="class_id"  value="<?php if(isset($students['classes_id'])){echo $students['classes_id'];} ?>" name="class_id">					
					<input type="hidden" id="subject_id" value="<?php if(isset($subject_id)) { echo $subject_id;} ?>" name="subject_id">						
					<button type="submit" name="addMarks" class="btn btn-block btn-success">Submit Students Marks</button>
				  </form>       
            <!-- form ends -->

      
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

<?php include_once("footer.html"); ?>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
