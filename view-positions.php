<?php
include_once("functions/functions.php");

if (isset($_POST['submit'])) {
  
  $academic_year = (int)$_POST['academic_year'];
  $term = (int)$_POST['term'];
  $sub_class = (int)$_POST['level'];

  $_SESSION['academic_year'] = $academic_year;
  $_SESSION['sub_class'] = $sub_class;
  $_SESSION['term'] = $term;

$getFinalPositions = new Staff();
$positions = $getFinalPositions->getFinalPositions($academic_year, $term, $sub_class);

$getAllSubclassesOnFilter = new Staff();
$sub_classes = $getAllSubclassesOnFilter->getAllSubclassesOnFilter();
}else{

  $academic_year = $_SESSION['academic_year'];
  $term = $_SESSION['term'];
  $sub_class = $_SESSION['sub_class'];

$getFinalPositions = new Staff();
$positions = $getFinalPositions->getFinalPositions($academic_year, $term, $sub_class);

}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Positions | Lilongwe Private School</title>
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
        Final Results
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="student-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="view-final-results.php">Final Results</a></li>
       
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
            if(isset($_SESSION["class_promoted"]) && $_SESSION["class_promoted"]==true)
            {
              echo "<div class='alert alert-success'>";
              echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
              echo "<strong>Success! </strong>"; echo "You have successfully Promoted and Demoted Students";
              unset($_SESSION["class_promoted"]);
              echo "</div>";
             header('Refresh: 5; URL= index.php');
              }
          ?>
        <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Position</th>
            <th>Student Name</th>
            <th>Class Name</th>
            <th>Grading Type</th>
            <th>Academic Year</th>
            <th>Term</th>
            <th>Marks</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
                  <?php
                $i = 0;
          if(isset($positions) && count($positions)>0){
          foreach($positions as $position){ 
            $i++;  ?>
          <tr>    
                  <td><?php echo $i; ?></td>
                  <td><?php echo $position['student_name']; ?></td>
                  <td><?php echo $position['class_name']; ?></td>
                  <td><?php echo "Average Marks on All Subjects" ?></td>
                  <td><?php echo $position['academic_year']; ?></td>
                  <td><?php echo $position['term']; ?></td>
                  <td><?php echo $position['mark']; ?> </td>
                  <td><a href="student-stats.php?id=<?php echo $position['student_no']; ?>"><button class="btn btn-success">Details</button></a></td>
                </tr>

          <?php
            }
          } ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Position</th>
                  <th>Student Name</th>
                  <th>Class Name</th>
                  <th>Grading Type</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Marks</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
        </div>
          
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
</body>
</html>
