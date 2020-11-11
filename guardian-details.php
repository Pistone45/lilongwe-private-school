<?php
include_once("functions/functions.php");
if(isset($_GET['id'])){
  $id = $_GET['id'];
  
  $getSpecificGuardian = new Guardian();
  $details = $getSpecificGuardian->getSpecificGuardian($id);
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Guardian Details | Lilongwe Private School</title>
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
        Guardian Details <button onclick="print()" class="btn btn-primary">Print <i class="fa fa-print" aria-hidden="true"></i></button>

      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
       
        <li class="active">Guardian Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-7">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div id="" class="box-body box-profile">

              <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                  <b>First Name</b> <a class="pull-right"><?php echo $details['firstname']; ?></a>
                </li>

                <li class="list-group-item">
                  <b>Middle Name</b> <a class="pull-right"><?php echo $details['middlename']; ?></a>
                </li>

               <li class="list-group-item">
                  <b>Last Name</b> <a class="pull-right"><?php echo $details['lastname']; ?></a>
                </li>

                <li class="list-group-item">
                  <b>Email</b> <a class="pull-right"><?php echo $details['email']; ?></a>
                </li>

                <li class="list-group-item">
                  <b>Primary Phone</b> <a class="pull-right"><?php echo $details['primary_phone']; ?></a>
                </li>

                <li class="list-group-item">
                  <b>Sedondary Phone</b> <a class="pull-right"><?php echo $details['secondary_phone']; ?></a>
                </li>

                <li class="list-group-item">
                  <b>Address</b> <a class="pull-right"><?php echo $details['address']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Occupation</b> <a class="pull-right"><?php echo $details['occupation']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Employer</b> <a class="pull-right"><?php echo $details['employer']; ?></a>
                </li>
               
              </ul>

                          
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


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
