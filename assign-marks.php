<?php
include_once("functions/functions.php");

if (isset($_POST['variables'])) {
  $level = $_POST['level'];
  $subject_id = $_POST['subject_id'];
}


if (isset($_GET['id'])) {
  $assignment_id=$_GET['id'];
}
  $getSpecificStudentId = new Students();
  $student = $getSpecificStudentId->getSpecificStudentId($assignment_id);
  $id = $student['students_student_no'];

  $getSpecificStudent = new Students();
  $student = $getSpecificStudent->getSpecificStudent($id);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Select Subject| Lilongwe Private School</title>
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
        Assigning a Mark to <?php echo $student['firstname']." ".$student['lastname']."'s"; ?> Assignment
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Subject Assignment</a></li>
       
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
            <form action="assigned-marks.php" method="POST">

            <div class="form-group">
              <label for="exampleInputEmail1">Marks</label>
              <input type="number" name="marks" max="15" required="" class="form-control" id="" aria-describedby="emailHelp" placeholder="Enter Marks">
              <small id="emailHelp" class="form-text text-muted">Decimals can also be added</small>
              <input type="text" hidden="" value="<?php if(isset($_GET['id'])){echo $_GET['id'];}  ?>" name="assignment_id">
            </div>
          <input type="text" hidden="" value="<?php echo $level = $_POST['level']; ?>" name="level">
          <input type="text" hidden="" value="<?php echo $students_student_no = $_POST['students_student_no']; ?>" name="students_student_no">
          <input type="text" hidden="" value="<?php echo $subject_id = $_POST['subject_id'];  ?>" name="subject_id">
            <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
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
