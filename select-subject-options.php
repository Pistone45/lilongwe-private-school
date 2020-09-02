<?php
include_once("functions/functions.php");

if (isset($_SESSION['class_id'])) {

  $class_id = $_SESSION['class_id'];

  $getSpecificClass = new Classes();
  $classes = $getSpecificClass->getSpecificClass($class_id);

  $getOptionA = new Classes();
  $optionA = $getOptionA->getOptionA($class_id);

  $getOptionB = new Classes();
  $optionB = $getOptionB->getOptionB($class_id);

  $getOptionC = new Classes();
  $optionC = $getOptionC->getOptionC($class_id);

  $getOptionD = new Classes();
  $optionD = $getOptionD->getOptionD($class_id);

  $getOptionE = new Classes();
  $optionE = $getOptionE->getOptionE($class_id);

  $getOptionF = new Classes();
  $optionF = $getOptionF->getOptionF($class_id);

}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Subject Options | Lilongwe Private School</title>
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
      <?php echo $classes['name']; ?> Subject Options
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Subject Options</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	   <div class="box box-primary">
      <div class="row">
        <!-- left column -->
		   <!-- form start -->
      <form role="form" action="add-student.php" method="POST"> 
      <div class="box-body">
			  
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="well">
          <h4><b>OPTION BLOCK A</b></h4>
          <label>Select one Subject</label>
            <div class="radio">
            <?php
              if(isset($optionA) && count($optionA)>0){
                foreach($optionA as $first_option){ ?>
                    <label><input required="" type="radio" name="option_a" value="<?php if(isset($first_option)){echo $first_option['subjects_id'];} ?>"><?php if(isset($first_option)){echo $first_option['subject_name'];} ?></label> <br>
                <?php
                  
                }
              }
            ?>
          </div>
          </div>
          
          <div class="well">
          <h4><b>OPTION BLOCK B</b></h4>
          <label>Select one Subject</label>
            <div class="radio">
            <?php
              if(isset($optionB) && count($optionB)>0){
                foreach($optionB as $second_option){ ?>
                    <label><input required="" type="radio" name="option_b" value="<?php if(isset($second_option)){echo $second_option['subjects_id'];} ?>"><?php if(isset($second_option)){echo $second_option['subject_name'];} ?></label> <br>
                <?php
                  
                }
              }
            ?>
          </div>
          </div>

          <div class="well">
          <h4><b>OPTION BLOCK C</b></h4>
          <label>Select one Subject</label>
            <div class="radio">
            <?php
              if(isset($optionC) && count($optionC)>0){
                foreach($optionC as $third_option){ ?>
                    <label><input required="" type="radio" name="option_c" value="<?php if(isset($third_option)){echo $third_option['subjects_id'];} ?>"><?php if(isset($third_option)){echo $third_option['subject_name'];} ?></label> <br>
                <?php
                  
                }
              }
            ?>
          </div>
          </div>

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
				
				          <div class="well">
          <h4><b>OPTION BLOCK D</b></h4>
          <label>Select one Subject</label>
            <div class="radio">
            <?php
              if(isset($optionD) && count($optionD)>0){
                foreach($optionD as $fourth_option){ ?>
                    <label><input required="" type="radio" name="option_d" value="<?php if(isset($fourth_option)){echo $fourth_option['subjects_id'];} ?>"><?php if(isset($fourth_option)){echo $fourth_option['subject_name'];} ?></label> <br>
                <?php
                  
                }
              }
            ?>
          </div>
          </div>
          
          <div class="well">
          <h4><b>OPTION BLOCK E</b></h4>
          <label>Select one Subject</label>
            <div class="radio">
            <?php
              if(isset($optionE) && count($optionE)>0){
                foreach($optionE as $fifth_option){ ?>
                    <label><input required="" type="radio" name="option_e" value="<?php if(isset($fifth_option)){echo $fifth_option['subjects_id'];} ?>"><?php if(isset($fifth_option)){echo $fifth_option['subject_name'];} ?></label> <br>
                <?php
                  
                }
              }
            ?>
          </div>
          </div>

          <div class="well">
          <h4><b>OPTION BLOCK F</b></h4>
          <label>Select one Subject</label>
            <div class="radio">
            <?php
              if(isset($optionF) && count($optionF)>0){
                foreach($optionF as $sixth_option){ ?>
                    <label><input required="" type="radio" name="option_f" value="<?php if(isset($sixth_option)){echo $sixth_option['subjects_id'];} ?>"><?php if(isset($sixth_option)){echo $sixth_option['subject_name'];} ?></label> <br>
                <?php
                  
                }
              }
            ?>
          </div>
          </div>
	
				<br>
				<div class="form-group">
          <button type="submit" name="subject_options" class="btn btn-block btn-primary">Continue <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
        </div>
			  
        </div>
        <!--/.col (right) -->
		
		  </div>
              <!-- /.box-body -->
			  
			 
		</form>

      </div>
      <!-- /.row -->
	  
	   </div>
          <!-- /.box -->

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
