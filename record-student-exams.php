<?php
include_once("functions/functions.php");

if (isset($_POST['variables'])) {

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
  
}

		
  

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Exam Mark Edited | Lilongwe Private School</title>
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
        Success
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Exam Mark Edited</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-body">
            <form action="record-exams.php" method="POST">
      <!-- Start of the variables to passed on to the next page -->
<input type="text" hidden="" name="sub_class_id" value="<?php echo$_POST['sub_class_id']; ?>">
<input type="text" hidden="" name="subject_id" value="<?php echo$_POST['subject_id']; ?>">
        
        <!-- End of the variables to passed on to the next page -->
          <div class="alert alert-success">
            You have Added an Exam Mark. Click continue to view more Students
          </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block">Continue</button>
            </form>

            </div>
              <!-- /.box-body -->

              <div class="box-footer">
              </div>
          </div>
          <!-- /.box -->

        

        </div>
        <div class="col-md-6"></div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
