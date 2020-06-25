<?php
include_once("functions/functions.php");

if (isset($_POST['submit'])) {
  
  $academic_year = (int)$_POST['academic_year'];
  $term = (int)$_POST['term'];
  $sub_class = (int)$_POST['level'];


$getFinalPositions = new Staff();
$positions = $getFinalPositions->getFinalPositions($academic_year, $term, $sub_class);

}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Positions| Lilongwe Private School</title>
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

       <h3>View Final Results</h3>
      <?php
      $i = 0;
        if(isset($positions) && count($positions)>0){ 
          ?>

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
                </tr>
                </thead>
                <tbody>
                  <?php

          foreach($positions as $position){ 
            $i++;  ?>
          <tr>    
                  <td><?php echo $i; ?></td>
                  <td><?php echo $position['student_name']; ?></td>
                  <td><?php echo $position['class_name']; ?></td>
                  <td><?php echo "CE1 + CE2 + Final Exam" ?></td>
                  <td><?php echo $position['academic_year']; ?></td>
                  <td><?php echo $position['term']; ?></td>
                  <td><?php echo $position['marks']; ?> </td>
                  <td></td>

                </tr>



          <?php

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
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Students Available in the class at the moment";
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
  <script type="text/javascript">
    function RecordExams() {
    var mark = $("#mark").val();
    var academic_year = $("#academic_year").val();
    var term = $("#term").val();
    var students_student_no = $("#students_student_no").val();
    var exam_type_id = $("#exam_type_id").val();
    var staff_id = $("#staff_id").val();
    var classes_has_subjects_classes_id = $("#classes_has_subjects_classes_id").val();
    var classes_has_subjects_subjects_id = $("#classes_has_subjects_subjects_id").val();
    $.post("record-student-exams.php", { mark: mark, academic_year: academic_year,
     term: term, students_student_no: students_student_no, exam_type_id:exam_type_id, staff_id:staff_id, classes_has_subjects_classes_id: classes_has_subjects_classes_id, classes_has_subjects_subjects_id: classes_has_subjects_subjects_id},
    function(data) {
   $('#results').html(data);
   $('#myForm')[0].reset();
    });
}
  </script>
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
