<?php
include_once("functions/functions.php");

if (isset($_POST['submit'])) {
  $level = $_POST['level'];
  $subject_id = $_POST['subjects_id'];
}

$getStudentsUploadedAssignments = new Staff();
$assignments = $getStudentsUploadedAssignments->getStudentsUploadedAssignments($level, $subject_id);

$status = 1;
$getCurrentSettings = new settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$getSubclass = new Students();
$getSubclass = $getSubclass->getSubclass($level);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Submitted Assignments | Lilongwe Private School</title>
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
        Viewing <?php echo $getSubclass['name']; ?> Assignments <a href="select-class.php"><button class="btn btn-primary">Change Class</button></a>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="teacher-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Submitted Assignments</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-body">
                      <?php
        if(isset($assignments) && count($assignments)>0){ ?>
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

          foreach($assignments as $assignment){ ?>
          <tr>
                  <td><?php echo $assignment['students_student_no']; ?></td>
                  <td><?php echo $assignment['student_firstname']; ?></td>
                  <td><?php echo $assignment['student_surname']; ?></td>
                  <td><?php echo $assignment['subject_name']; ?></td>
                  <td><?php echo $assignment['assignment_type_name']; ?></td>
                  <td><?php echo $assignment['assignment_title']; ?></td>
                  <td><?php if($assignment['marks'] == "" || $assignment['marks']==0){echo "<i>Not Marked</i>";}else{echo$assignment['marks'];} ?> </td>
          <?php
          if ($assignment['marks'] > 0) {
             ?><td></td><?php
          } else { ?>
          <form action="assign-marks.php?id=<?php echo $assignment['assignments_id']; ?>" method="POST">
          <input type="text" hidden="" value="<?php echo$level = $_POST['level']; ?>" name="level">
          <input type="text" hidden="" value="<?php echo$students_student_no = $assignment['students_student_no']; ?>" name="students_student_no">
          <input type="text" hidden="" value="<?php echo$subject_id = $_POST['subjects_id'];  ?>" name="subject_id">
          <td><button type="submit" name="variables" class="btn btn-info">Assign Marks</button></td>
          </form><?php
            
          }
 
          ?>

          <td><a href="assignments/students/<?php echo $assignment['submitted_assignment']; ?>"><button class="btn btn-success">Download</button></a></td>

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
                  <th>Assignment Type</th>
                  <th>Assignment Name</th>
                  <th>Marks</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else {
                        echo "No Assignments Available for ".$getSubclass['name'];
                      }
        ?>
            </div>
              <!-- /.box-body -->

              <div class="box-footer">
              </div>
            </form>
          </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
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
