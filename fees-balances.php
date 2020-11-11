<?php
include_once("functions/functions.php");
$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$fees = $settings['fees'];
$academic_year = $settings['academic_year'];
$term = $settings['term'];

$getStudentsWithFeesBalances = new Staff();
$students = $getStudentsWithFeesBalances->getStudentsWithFeesBalances($fees, $academic_year, $term);
$getnonPaidStudents = new Staff();
$unpaidstudents = $getnonPaidStudents->getnonPaidStudents($fees, $academic_year, $term);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Fees Balances | Lilongwe Private School</title>
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
        Fees Balances
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Fees Balances</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <!-- Box -->
          <div class="box box-primary">
          <?php
            if(isset($_SESSION["reminder_sent"]) && $_SESSION["reminder_sent"]==true)
            {
                echo "<div class='alert alert-success'>";
                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                echo "<strong>Success! </strong>"; echo "You have successfully sent a reminder to Guardian";
                unset($_SESSION["reminder_sent"]);
                echo "</div>";
                //header('Refresh: 5; URL= accountant-index.php');
                }
            ?>

              <div class="box-body">
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Paid with Balances</a></li>
                <li><a data-toggle="tab" href="#menu1">Not Paid</a></li>
              </ul>

              <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
                                  <h3>Paid with Fees Balances</h3>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Guardian Name</th>
                  <th>Guardian Phone</th>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees Balance</th>
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($students) && count($students)>0){
          foreach($students as $student){ 
              if($fees - $student['amount'] == 0){}else{  ?>          <tr>
                  <td><?php echo $student['guardian_name']; ?></td>
                  <td><?php echo $student['phone']; ?></td>
                  <td><?php echo $student['student_no']; ?></td>
                  <td><?php echo $student['firstname']; ?></td>
                  <td><?php echo $student['lastname']; ?></td>
                  <td><?php echo $student['sub_class_name']; ?></td>
                  <td><?php echo $academic_year ?></td>
                  <td><?php echo $term = $settings['term']; ?></td>
                  <td><?php if($fees - $student['amount'] == $fees){ ?> <p style="color: red;">Not Paid</p> <?php }else{echo"K"; echo number_format($balance = $fees - $student['amount']);} ?></td>
                </tr><?php } ?>



          <?php
            
          }
        }else{
          echo "No Students with Fees Balances found";
        }
        ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Guardian Name</th>
                  <th>Guardian Phone</th>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees Balance</th>
                </tr>
                </tfoot>
              </table>
                </div>

                <div id="menu1" class="tab-pane fade">
              <h3>Not Paid</h3>
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Guardian Name</th>
                  <th>Guardian Phone</th>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees Balance</th>
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($unpaidstudents) && count($unpaidstudents)>0){
          foreach($unpaidstudents as $unpaidstudent){   ?>          <tr>
                  <td><?php echo $unpaidstudent['guardian_name']; ?></td>
                  <td><?php echo $unpaidstudent['phone']; ?></td>
                  <td><?php echo $unpaidstudent['student_no']; ?></td>
                  <td><?php echo $unpaidstudent['firstname']; ?></td>
                  <td><?php echo $unpaidstudent['lastname']; ?></td>
                  <td><?php echo $unpaidstudent['sub_class_name']; ?></td>
                  <td><?php echo $academic_year ?></td>
                  <td><?php echo $term = $settings['term']; ?></td>
                  <td style="color: red;">K<?php echo number_format($fees); ?></td>
                </tr>



          <?php
            
          }
        }else{
          echo "No Students with Fees Balances found";
        }
        ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Guardian Name</th>
                  <th>Guardian Phone</th>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Class Name</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Fees Balance</th>
                </tr>
                </tfoot>
              </table>
                </div>

              </div>

              </div>
            </div>
          </div>
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
