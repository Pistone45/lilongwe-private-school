<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

if(isset($_POST['submit'])){

	 $promoteStudents = new Staff();
	 $promoteStudents->promoteStudents();
	

	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Promote Students | Lilongwe Private School</title>
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
        Promote Students
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Promote Students</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<!-- form start -->
    <form role="form" action="promote-students.php" method="POST">
			<?php
        if(isset($_SESSION["students_promoted"]) && $_SESSION["students_promoted"]==true)
        {
            echo "<div class='alert alert-success'>";
            echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
            echo "<strong>Success! </strong>"; echo "You have successfully promoted all students. Go to demote to demote a single Student";
            unset($_SESSION["students_promoted"]);
            echo "</div>";
					header('Refresh: 6; URL= index.php');
        }
			?>
      <div class="row box box-primary">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
              <div class="box-body">
            <p>Click the button below to promote all students strating from Form 1 to form Form 7</p>
                
              </div>
			  
              <!-- /.box-body -->
			  <div class="box-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-plus" aria-hidden="true"></i> Promote Students</button>
              </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            
		
			
			
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
