<?php
include_once("functions/functions.php");


if(isset($_POST['submit'])){
	
	$firstname = $_POST['firstname'];
	$middlename = $_POST['middlename'];
	$lastname = $_POST['lastname'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$dob = $_POST['dob'];
	$qualifications = $_POST['qualifications'];
	$no_of_years_experience = $_POST['no_of_years_experience'];
	$date_joined = $_POST['date_joined'];
	$marital_status = $_POST['marital_status'];
	$name_of_spouse = $_POST['name_of_spouse'];
	$number_of_children = $_POST['number_of_children'];
	
	$addTeacher = new Staff();
	$addTeacher->addTeacher($firstname,$middlename,$lastname,$phone,$email,$address,$dob,$qualifications,$no_of_years_experience,$date_joined,$marital_status,$name_of_spouse,$number_of_children);
	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Add Teacher | Lilongwe Private School</title>
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
        Teacher Registration
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Teacher Registration</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<!-- form start -->
            <form role="form" action="add-teacher.php" method="POST">
			<?php
                            if(isset($_SESSION["teacher-added"]) && $_SESSION["teacher-added"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully added a teacher";
                                unset($_SESSION["teacher-added"]);
                                echo "</div>";
								 header('Refresh: 5; URL= view-teacher.php');
                            }
							?>
      <div class="row box box-primary">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
              <div class="box-body">
                <div class="form-group">
                  <label for="fatherName">Firstname</label>
                  <input type="text" class="form-control" name="firstname" id="firstname" required>
                </div>
				
				<div class="form-group">
                  <label for="fatherMiddleName">Middlename</label>
                  <input type="text" class="form-control" name="middlename" id="middlename" >
                </div>
				
				<div class="form-group">
                  <label for="fatherLastname">Lastname</label>
                  <input type="text" class="form-control" name="lastname" id="lastname" required>
                </div>
				
				<div class="form-group">
                  <label for="fatherLastname">Date of Birth</label>
                  <input type="date" class="form-control" name="dob" id="dob" required>
                </div>
				
				<div class="form-group">
                  <label for="exampleInputPassword1">Phone</label>
                  <input type="text" class="form-control" name="phone" id="phone" required>
                  <small style="color: red;">This will be used as password</small>
                </div>
				
				<div class="form-group">
                  <label for="fatherEmail">Email</label>
                  <input type="text" class="form-control" name="email" id="email" required >
                </div>
				
				 <div class="form-group">
                  <label for="exampleInputPassword1">Address</label>
                  <input type="text" class="form-control" name="address" id="address">
                </div>
				
				
              
                
              </div>
              <!-- /.box-body -->
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            
			<div class="form-group">
                  <label for="exampleInputPassword1">Qualifications</label>
                  <input type="text" class="form-control" name="qualifications" id="qualifications">
                </div>
				
				<div class="form-group">
                  <label for="exampleInputPassword1">No of Years Experience</label>
                  <input type="number" min="0" max="80" class="form-control" name="no_of_years_experience" id="no_of_years_experience">
                </div>
				
				<div class="form-group">
                  <label for="exampleInputPassword1">Date Joined</label>
                  <input type="date" class="form-control" name="date_joined" id="date_joined">
                </div>
				
				<div class="form-group">
                  <label>Select Marital Status</label>
                  <select name="marital_status" class="form-control">
				  
							<option value="0">Single</option>
							<option value="1">Married</option>
							<option value="2">Divorced</option>
							
                  </select>
                </div>
				
				
				<div class="form-group">
                  <label for="exampleInputPassword1">Name of Spouse</label>
                  <input type="text" class="form-control" name="name_of_spouse" id="name_of_spouse">
                </div>
				
				<div class="form-group">
                  <label for="exampleInputPassword1">Number of Children</label>
                  <input type="text" class="form-control" name="number_of_children" id="number_of_children">
                </div>
			
			<div class="box-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-plus" aria-hidden="true"></i> Submit</button>
              </div>
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
	  </form>
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
