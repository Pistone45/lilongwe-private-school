<?php
include_once("functions/functions.php");

if (isset($_POST['submit'])) {
  $class_id = $_POST['class_id']." class left";
  $sub_class_id = $_POST['sub_class_id']." sub class left";
  $subject_id = $_POST['subject_id']." subject left";
  $academic_year = $_POST['academic_year']. "aca year left";
  $settings_id = $_POST['term'];


$getAllSubmittedAssignments = new Students();
$submitted = $getAllSubmittedAssignments->getAllSubmittedAssignments($class_id, $sub_class_id, $subject_id, $settings_id);
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Display Assignments| Lilongwe Private School</title>
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
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="submit.js"></script>
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
        Display Assignments
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Display Assignments</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
      <?php
      $i = 0;
        if(isset($submitted) && count($submitted)>0){ 
          ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Assignment Name</th>
                  <th>Marks</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody><?php

          foreach($submitted as $submit){ 
            $i++;  ?>
          <tr>
                  <td><?php echo $submit['students_student_no']; ?></td>
                  <td><?php echo $submit['firstname']; ?></td>
                  <td><?php echo $submit['lastname']; ?></td>
                  <td><?php echo $submit['subject_name']; ?></td>
                  <td><?php echo $submit['assignment_type_name']; ?></td>
                  <td><?php echo $submit['assignment_title']; ?></td>
                  <td><?php if($submit['marks'] == ""){echo "<i>Not Marked</i>";}else{echo$submit['marks'];} ?> </td>
                  
          <form action="submit.php?id=<?php echo $_POST['term']; ?>" method="POST">
        <input type="text" hidden="" id="assignments_id" value="<?php echo $submit['assignments_id']; ?>" name="assignments_id">

        <input type="text" hidden="" id="students_student_no" value="<?php echo $submit['students_student_no']; ?>" name="students_student_no">
        <!-- Start of the variables to passed on to the next page -->
        <input type="text" hidden="" name="class_id" value="<?php echo$_POST['class_id']; ?>">
        <input type="text" hidden="" name="sub_class_id" value="<?php echo$_POST['sub_class_id']; ?>">
        <input type="text" hidden="" name="subject_id" value="<?php echo$_POST['subject_id']; ?>">
        <input type="text" hidden="" name="academic_year" value="<?php echo$_POST['academic_year']; ?>">
        <input type="text" hidden="" name="settings_id" value="<?php echo$_POST['term']; ?>">

        <!-- End of the variables to passed on to the next page -->
          
          <td><button type="submit" name="variables" class="btn btn-info">Edit Marks</button></td>
          </form>

          <td><a href="assignments/students/<?php echo $submit['submitted_assignment']; ?>"><button class="btn btn-success">Download</button></a></td>

                </tr>



          <?php
            
          } ?>

                
                </tbody>
                <tfoot>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Subject</th>
                  <th>Assignment Name</th>
                  <th>Marks</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Assignments Available";
                      }
        ?>
           
            <!-- form start -->

          </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->

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
