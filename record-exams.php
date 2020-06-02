<?php
include_once("functions/functions.php");

if (isset($_POST['submit'])) {
	$subject_id = $_POST['subject_id'];
	$sub_class_id = $_POST['sub_class_id'];

$getAllStudentsPerClassSubject = new Staff();
$student = $getAllStudentsPerClassSubject->getAllStudentsPerClassSubject($sub_class_id, $subject_id);

$getSubjectById = new Staff();
$subject = $getSubjectById->getSubjectById($subject_id);

$getClassesWithSubjects = new Staff();
$classsubject = $getClassesWithSubjects->getClassesWithSubjects($sub_class_id, $subject_id);
$classes_has_subjects_classes_id= $classsubject['linked_classes_id'];
$classes_has_subjects_subjects_id = $classsubject['subjects_id'];
}


if (isset($_POST['addMarks'])) {

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



$status = 1;
$getCurrentSettings = new settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$getExamTypes = new Staff();
$exam_type = $getExamTypes->getExamTypes();



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Display Students| Lilongwe Private School</title>
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
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="view-students-assignments.php">Display Assignments</a></li>
       
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
								 header('Refresh: 5; URL= record-exams.php');
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
                  <th>Action</th>
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
				   <form role="form" action="record-exams.php" method="POST">
                  <td>
					<input type="hidden" id="academic_year"  value="<?php echo $settings['academic_year']; ?>" name="academic_year">	
					<input type="hidden" id="term"  value="<?php echo $settings['term']; ?>" name="term">
					<input type="hidden" id="student_no"  value="<?php echo $students['student_no']; ?>" name="student_no">	
					<input type="hidden" id="subject_id" value="<?php echo $subject_id; ?>" name="subject_id">	
					<input type="hidden" id="sub_class_id"  value="<?php echo $sub_class_id; ?>" name="sub_class_id">						
					<input type="number" name="marks"  placeholder="Enter Marks" class="form-control"/>	 			 
				  </td>
				  <td>				 
					<button type="submit" name="addMarks" class="btn btn-info">Add Marks</button>	
					
				  </td>
				   </form>
               
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
                  <th>Action</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Students Available to record Exams";
                      }
        ?>


           
            <!-- form start -->

      
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    function RecordExams() {
    var mark = $("#mark").val();
    var academic_year = $("#academic_year").val();
    var term = $("#term").val();
    var students_student_no = $("#students_student_no").val();
    var exam_type_id = $("#exam_type_id").val();
    var staff_id = $("#staff_id").val();
    var classes_has_subjects_classes_id = $("#classes_has_subjects_classes_id").val();
    var classes_has_subjects_subjects_id = $("#classes_has_subjects_subjects_id").val();
    $.post("record-student-exams.php", { mark: mark, academic_year: academic_year,
     term: term, students_student_no: students_student_no, exam_type_id:exam_type_id, staff_id:staff_id, classes_has_subjects_classes_id: classes_has_subjects_classes_id, classes_has_subjects_subjects_id: classes_has_subjects_subjects_id},
    function(data) {
   $('#results').html(data);
   $('#myForm')[0].reset();
    });
}
  </script>
  <?php include_once("footer.html"); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

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
