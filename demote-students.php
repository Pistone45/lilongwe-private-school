<?php
include_once("functions/functions.php");
$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$_SESSION['academic_year'] = $settings['academic_year'];

if(isset($_POST['submit'])){
	$sub_class = $_POST['level'];

	$getAllStudentsPerSub_class = new Students();
	$students = $getAllStudentsPerSub_class->getAllStudentsPerSub_class($sub_class);

	$_SESSION['sub_class'] = $_POST['level'];
	
}

if(isset($_POST['demote'])){
	$students = $_POST['students'];
	
	$demoteStudents = new Staff();
	$demoteStudents->demoteStudents($students);

}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> students| Lilongwe Private School</title>
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
       students
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">students</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            
           
            <!-- form start -->
            <form role="form" action="demote-students.php" method="POST">
			<?php
				if(isset($_SESSION["students_demoted"]) && $_SESSION["students_demoted"]==true)
				{
					echo "<div class='alert alert-success'>";
					echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
					echo "<strong>Success! </strong>"; echo "You have successfully demoted Students";
					unset($_SESSION["students_demoted"]);
					echo "</div>";
					 header('Refresh: 5; URL= index.php');
				}
			?>
			<?php
				if(isset($_SESSION["failed"]) && $_SESSION["failed"]==true)
				{
					echo "<div class='alert alert-warning'>";
					echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
					echo "<strong>Failed! </strong>"; echo "You cant demote Form 1 Students";
					unset($_SESSION["failed"]);
					echo "</div>";
					 header('Refresh: 5; URL= filter-demote-students.php');
				}
			?>
              <div class="box-body">
				<input type="hidden" value="<?php if(isset($teacher_id)){ echo $teacher_id; }?>" name="teacher_id" />
				<input type="hidden" value="<?php if(isset($sub_class)){ echo $sub_class; }?>" name="sub_class" />
				
			     <div class="form-group">
                  <label>Select students </label>
				    <div class="checkbox">
					<?php
						if(isset($students) && count($students)>0){
							foreach($students as $student){ ?>
								  <label><input type="checkbox" name="students[]" value="<?php if(isset($student['student_no'])){echo $student['student_no'];} ?>"><?php if(isset($student['student_no'])){echo $student['student_no'].' | '.$student['firstname'].' '.$student['lastname'];} ?></label> <br>
							<?php
								
							}
						}else{
							echo "No Students found for this particular Class";
						}
					?>
					
					</div>
                </div>
				
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="demote" class="btn btn-primary">Demote Students</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->

        <!--/.col (right) -->
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
