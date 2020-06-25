<?php
include_once("functions/functions.php");
//get current settings
$getAcademicYear = new settings();
$settings = $getAcademicYear->getCurrentSettings();

$academic_year = $settings['academic_year'];
$period = $settings['period'];

if(isset($_POST['submit'])){
	$level = $_POST['level'];
	$course_code = $_POST['course'];
	
	
//get students registered for that subject
$getStudentsPerRegisteredExamsPerLevelPerAcademicYearPerExaminer = new Exams();
$students = $getStudentsPerRegisteredExamsPerLevelPerAcademicYearPerExaminer->getStudentsPerRegisteredExamsPerLevelPerAcademicYearPerExaminer($course_code,$level,$academic_year,$period);

//var_dump($students); die();
}


if(isset($_POST['exams'])){
	//echo $_POST['course_code']; die();
	$level = $_POST['level_'];
	$course_code = $_POST['course_code_'];
	$student_no = $_POST['student_id'];
	$marks = $_POST['marks'];
	$status = 1; //markerd - waiting for approval
	$addMarks = new Exams();
	$addMarks->addMarks($level,$course_code,$student_no,$marks,$status);
	
}



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Record Exams | Lilongwe Private School</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
		Record Results       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="record-results.php">Record Results</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		
         
          <div class="box">
            <form role="form" action="record-results.php" method="POST">
					<?php
						if(isset($_SESSION["marks-added"]) && $_SESSION["marks-added"]==true)
						{
							echo "<div class='alert alert-success'>";
							echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
							echo "<strong>Success! </strong>"; echo "You have successfully submitted results";
							unset($_SESSION["marks-added"]);
							echo "</div>";
							 header('Refresh: 5; URL= examiner-index.php');
						}
					?>
            <!-- /.box-header -->
            <div class="box-body">
			
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student Number</th>
                  <th>Marks</th>
				 
                </tr>
                </thead>
                <tbody>
				<tr>
					<td><input type="hidden" name="level_"  value="<?php if(isset($level)){echo $level;}  ?>" /></td>
					<td><input type="hidden" name="course_code_" value="<?php if(isset($course_code)){echo $course_code;} ?>" /></td>
				</tr>
				
				
				<?php
					if(isset($students) && count($students)>0){
						foreach($students as $student){ ?>
							<tr>
								<td><input type="hidden" name="student_id[]" value="<?php echo $student['student_id']; ?>" /><?php echo $student['student_no']; ?></td>
								<td>
									<input type="text" name="marks[]" />
								</td>					
							</tr>
						<?php
							
						}
					}
				?>
				
				 
               
                </tbody>
                
              </table>
			  <button type="submit" name="exams" class="btn btn-primary btn-block">Submit Results</button>
            </div>
            <!-- /.box-body -->
			</form>  
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
