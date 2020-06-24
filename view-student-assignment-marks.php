<?php
include_once("functions/functions.php");

$getStudents = new Students();
$students = $getStudents->getStudents();


  $getStudentDetails = new Students();
  $details = $getStudentDetails->getStudentDetails();
  $sub_class_id = $details['sub_class_id'];//form 2 west = 5 


  $getStudentAssignmentMarks = new Students();
  $assignments = $getStudentAssignmentMarks->getStudentAssignmentMarks($sub_class_id);


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Assignments | Lilongwe Private School</title>
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
        Assignments       
      </h1>
      <ol class="breadcrumb">
        <li><a href="student-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="view-assignments.php">Assignments</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
                      <?php
        if(isset($assignments) && count($assignments)>0){ ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Due Date</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Marks</th>
                </tr>
                </thead>
                <tbody>
                  <?php
					foreach($assignments as $assignment){ ?>
					<tr>
                  <td><?php echo $assignment['title']; ?></td>
                  <td><?php $date = DATE("Y-m-d h:i"); if ($assignment['due_date'] < $date){
                    echo "<b>Date Passed </b>(";$date = date_create($assignment['due_date']); echo date_format($date,"d, M Y").')';
                  } else {$date = date_create($assignment['due_date']); echo date_format($date,"d, M Y");}?></td>
                  <td><?php echo $assignment['academic_year']; ?></td>
                  <td><?php echo $assignment['terms_id']; ?></td>
				  <td><?php echo $assignment['subject_name']; ?> </td>
          <td><?php echo $assignment['assignment_type_name']; ?> </td>
        
				  <td><?php if($assignment['marks'] == ""){echo "Not Marked";}else{echo $assignment['marks'];}  ?> </td>
          
                </tr>
					<?php
						
					} ?>

                
                </tbody>
                <tfoot>
                <tr>
                  <th>Title</th>
                  <th>Due Date</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
                  <th>Marks</th>
                </tr>
                </tfoot>
              </table> <?php
                      }else{
                        echo "No assignments Available at the moment";
                      }
        ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
