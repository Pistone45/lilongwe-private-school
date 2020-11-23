<?php
include_once("functions/functions.php");


if(isset($_POST['submit'])){
	
	$firstname = $_POST['firstname'];
	$middlename = $_POST['middlename'];
	$lastname = $_POST['lastname'];
	$primaryPhone = $_POST['primaryPhone'];
	$secondaryPhone = $_POST['secondaryPhone'];
	$address = $_POST['address'];
	$email = $_POST['email'];
	$occupation = $_POST['occupation'];
	$employer = $_POST['employer'];
	
	$addGuardian = new Guardian();
	$addGuardian->addGuardian($firstname,$middlename,$lastname,$primaryPhone,$secondaryPhone,$address,$email,$occupation,$employer);
	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Add Guardian | Lilongwe Private School</title>
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
        Guardian Registration
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="add-guardian.php">Guardian Registration</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<!-- form start -->
            <form role="form" action="add-guardian.php" method="POST">
			<?php
                            if(isset($_SESSION["guardian-added"]) && $_SESSION["guardian-added"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully added a guardian";
                                unset($_SESSION["guardian-added"]);
                                echo "</div>";
								 header('Refresh: 5; URL= view-guardian.php');
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
                  <label for="exampleInputPassword1">Primary Phone</label>
                  <input type="text" class="form-control" name="primaryPhone" id="primaryPhone" required>
                  <small style="color: red;">This will be used as password</small>
                </div>
				
				<div class="form-group">
                  <label for="fatherEmail">Secondary Phone</label>
                  <input type="text" class="form-control" name="secondaryPhone" id="secondaryPhone" >
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
            
			<div class="box-body">
				 <div class="form-group">
                  <label for="exampleInputPassword1">Email</label>
                  <input type="text" class="form-control" name="email" id="email" >
                </div>
               <div class="form-group">
                  <label for="exampleInputPassword1">Ocuppation</label>
                  <input type="text" class="form-control" name="occupation" id="occupation">
                </div>
				
				<div class="form-group">
                  <label for="exampleInputPassword1">Employer</label>
                  <input type="text" class="form-control" name="employer" id="employer">
                </div>
				
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
