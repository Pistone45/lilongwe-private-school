<?php
include_once("functions/functions.php");

unset($_SESSION['class_id']);
unset($_SESSION['sub_class']);
unset($_SESSION['guardian_id']);
unset($_SESSION['option_a']);
unset($_SESSION['option_b']);
unset($_SESSION['option_c']);
unset($_SESSION['option_d']);
unset($_SESSION['option_e']);
unset($_SESSION['option_f']);


$getStudents = new Students();
$students = $getStudents->getStudents();

if (isset($_POST['send_message'])) {
$subject = $_POST['subject'];
$message = $_POST['message'];
$student_no = $_POST['student_no'];
  
$sendMessage = new Staff();
$sendMessage = $sendMessage->sendMessage($subject, $message, $student_no);

}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>View Students | Lilongwe Private School</title>
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
        Student Details
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Student Details</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
                <?php
                  if(isset($_SESSION["message-sent"]) && $_SESSION["message-sent"]==true)
                  { ?>
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> You have successfully sent a Message
            </div>  <?php
            unset($_SESSION["message-sent"]);
            header('Refresh: 4; URL= view-students.php');
                      }
              ?>

          <?php
              if(isset($_SESSION["student_deleted"]) && $_SESSION["student_deleted"]==true)
                  { ?>
            <div class="alert alert-danger" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> You have successfully deleted a Student
            </div>  <?php
            unset($_SESSION["student_deleted"]);
            header('Refresh: 4; URL= view-students.php');
                      }
              ?>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Guardian</th>
                  <th>Student NO</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Class</th>
        				  <th>Status</th>
        				  <th>Action</th>
                  <th>Action</th>
                  <th>Delete</th>
                </tr>
                </thead>
                <tbody>
				<?php
        $i = 0;
        $delete = 0;
				if(isset($students) && count($students)>0){
					foreach($students as $student){ 
            $i++;  
            $delete++;  ?>
          
					<tr>
              <td><?php echo $student['guardian']; ?></td>
              <td><?php echo $student_no = $student['student_no']; ?></td>
              <td><?php echo $student['firstname']; ?></td>
              <td> <?php echo $student['lastname']; ?></td>
              <td><?php echo $student['sub_class']; ?></td>
    				  <td><?php echo $student['student_status']; ?> </td>
    				  <td><a href="student-details.php?id=<?php echo $student['student_no']; ?>"><i class="fa fa-eye" aria-hidden="true"></i> View Details</a></td>
              <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo $i; ?>"> Message</button></td>
              <td><a href="#"><i style="font-size: 28px; color: red;" data-toggle="modal" data-target="#my<?php echo$delete; ?>Modal" class="fa fa-trash-o" aria-hidden="true"></i></a></td>
          </tr>


<!-- Modal -->
<div id="my<?php echo$delete; ?>Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete <?php echo $student['firstname'].' '.$student['lastname']; ?></h4>
      </div>
      <div class="modal-body">
        <form action="delete-student.php" method="POST">
          <input type="text" hidden="" name="student_no" value="<?php echo $student['student_no']; ?>">
          <div class="alert alert-warning">
            <p>Are you sure you want to delete this student? All his data including his details, fees balances, borrowed books and exams results will be gone from the system.</p>
          </div>
          <button type="submit" name="delete" class="btn btn-danger">Delete</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Contact <?php echo $student['firstname'].' '.$student['lastname']; ?></h4>
      </div>
      <div class="modal-body">
            <form action="view-students.php" method="post">
                <div class="form-group">
                  <input type="text" hidden="" name="student_no" value="<?php echo $student_no?>">
                  <input type="text" name="subject" class="form-control" name="subject" placeholder="Subject" required="">
                </div>
                <div>
                  <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required=""></textarea>
                </div>
                    <div class="box-footer clearfix">
              <button type="submit" class="pull-right btn btn-default" name="send_message">Send
                <i class="fa fa-arrow-circle-right"></i></button>
            </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
					<?php
						
					}
				}
				?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Guardian</th>
                  <th>Student NO</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Class</th>
                  <th>Status</th>
                  <th>Action</th>
                  <th>Action</th>
                  <th>Delete</th>
                </tr>
                </tfoot>
              </table>
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
