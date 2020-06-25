<?php
include_once("functions/functions.php");
if(isset($_GET['id'])){
	$id = $_GET['id'];
	
	$getSpecificStudent = new Students();
	$details = $getSpecificStudent->getSpecificStudent($id);

  $getLoginStatus = new Students();
  $loginstatus = $getLoginStatus->getLoginStatus($id);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Student Details | Lilongwe Private School</title>
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
        Student Details <!-- Trigger the modal with a button -->

      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
       
        <li class="active">Student Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
	<?php
		if(count($details)>0){ ?>
		
      <div class="row">
        <div class="col-md-5">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
			
				<img class="img-responsive" src="<?php echo $details['student_picture']; ?>" alt="User profile picture">

          

              <ul class="list-group list-group-unbordered">
			  <li class="list-group-item">
                  <b>Name</b> <a class="pull-right"><?php echo $details['firstname']. ' '. $details['middlename']. ' '.$details['lastname']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Class Level</b> <a class="pull-right"><?php echo $details['sub_class']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Gender</b> <a class="pull-right"><?php echo $details['gender']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Date of Birth</b> <a class="pull-right"><?php echo $details['dob']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Country of Birth</b> <a class="pull-right"><?php echo $details['country_of_birth']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Nationality</b> <a class="pull-right"><?php echo $details['nationality']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Home Language</b> <a class="pull-right"><?php echo $details['home_language']; ?></a>
                </li>
               
              </ul>
			<div class="row">
				<div class="col-lg-6">
					<a href="edit-student.php?id=<?php echo $details['student_no']; ?>" class="btn btn-success btn-block"><b>Edit Student</b></a>
				</div>
				<div class="col-lg-6">
<?phP
if ($loginstatus['user_status_id'] == 0) { ?>
  <a href="enable-student.php?id=<?php echo $details['student_no']; ?>" class="btn btn-success btn-block"><b>Enable</b></a><?php

} else { ?>
  <a href="disable-student.php?id=<?php echo $details['student_no']; ?>" class="btn btn-danger btn-block"><b>Disable</b></a><?php

}

          
          ?>

				</div>
			</div>
              
		
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        
        </div>
        <!-- /.col -->
        <div class="col-md-7">
			  <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Extra Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Other Interests</strong>
				<p class="text-muted"><?php echo $details['other_interests']; ?></p>
              
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Place of Birth</strong>

              <p class="text-muted"><?php echo $details['place_of_birth']; ?></p>

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Medical Information</strong>

               <p class="text-muted"><?php echo $details['medical_information']; ?></p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Sporting Interests</strong>

              <p class="text-muted"><?php echo $details['sporting_interests']; ?></p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Year of Entry</strong>

              <p class="text-muted"><?php echo $details['year_of_entry']; ?></p>

              <hr>

              <strong><i class="fa fa-tint" aria-hidden="true"></i> Blood Group Type</strong>

              <p class="text-muted"><?php echo $details['blood_type']; ?></p>
              <hr>

              <strong><i class="fa fa-graduation-cap" aria-hidden="true"></i> Other Schools Attended</strong>

              <p class="text-muted"><?php echo $details['other_schools_attended']; ?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
	  
	  	<?php
				
			}
			?>

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
