<?php
include_once("functions/functions.php");

if (isset($_POST['submit'])) {
$class_id = $_POST['level'];

$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$fees = $settings['fees'];
$academic_year = $settings['academic_year'];
$term = $settings['term'];

$getStudentsFeesBalancesPerClass = new Accountant();
$students = $getStudentsFeesBalancesPerClass->getStudentsFeesBalancesPerClass($class_id, $fees, $term, $academic_year);

$getStudentsWithNoPayment = new Accountant();
$noPayments = $getStudentsWithNoPayment->getStudentsWithNoPayment($class_id, $fees, $term, $academic_year);

}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>School Fees Reports | Lilongwe Private School</title>
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
        School Fees Reports
      </h1>
      <ol class="breadcrumb">
        <li><a href="accountant-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Fees Reports</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            <ul class="nav nav-tabs" style="padding-top:10px; padding-left: 10px;">
              <li class="active"><a data-toggle="tab" href="#home">Students with Balances</a></li>
              <li><a data-toggle="tab" href="#menu1">Students with no payment</a></li>
            </ul>

            <div class="tab-content" style="padding-left: 10px; padding-bottom: 10px;">
              <div id="home" class="tab-pane fade in active">
                <h3>
                  <form action="school-fee-balance-pdf.php" method="POST">
                  <input type="hidden" name="level" value="<?php if(isset($_POST['submit'])){ echo $_POST['level'];} ?>">
                  <input type="hidden" name="academic_year" value="<?php echo $academic_year; ?>">
                  <input type="hidden" name="term" value="<?php echo $term; ?>">
                  <input type="hidden" name="fees" value="<?php echo $fees; ?>">
                  <button type="submit" name="balance" class="btn btn-primary">Dowload Report</button>
                  </form>
                </h3>
              
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Balance</th>
                </tr>
                </thead>
                <tbody>
        <?php
        $i = 0;
        if(isset($students) && count($students)>0){
          foreach($students as $student){ 
            $i++;   ?>
          
          <tr>
          <td><?php echo $student['student_no']; ?></td>
          <td><?php echo $student['firstname']; ?></td>
          <td><?php echo $student['lastname']; ?></td>
          <td><?php echo $student['academic_year']; ?></td>
          <td><?php echo $student['term']; ?></td>
          <td>K<?php echo number_format($fees- $student['amount']); ?></td>
          </tr>

          <?php
            
          }
        }
        ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Student ID</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Balance</th>
                </tr>
                </tfoot>
              </table>

              </div>
              <div id="menu1" class="tab-pane fade">
                <h3>
                  <form action="unpaid-fee-pdf.php" method="POST">
                  <input type="hidden" name="level" value="<?php if(isset($_POST['submit'])){ echo $_POST['level'];} ?>">
                  <input type="hidden" name="fees" value="<?php echo $fees; ?>">
                  <input type="hidden" name="academic_year" value="<?php echo $academic_year; ?>">
                  <input type="hidden" name="term" value="<?php echo $term; ?>">
                  <button type="submit" name="unpaid" class="btn btn-primary">Dowload Report</button>
                  </form>
                </h3>
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Student ID</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Balance</th>
                </tr>
                </thead>
                <tbody>
        <?php
        $i = 0;
        if(isset($noPayments) && count($noPayments)>0){
          foreach($noPayments as $noPayment){ 
            $i++;   ?>
          
          <tr>
          <td><?php echo $noPayment['student_no']; ?></td>
          <td><?php echo $noPayment['firstname']; ?></td>
          <td><?php echo $noPayment['lastname']; ?></td>
          <td><?php echo $academic_year; ?></td>
          <td><?php echo $term; ?></td>
          <td>K<?php echo number_format($fees); ?></td>
          </tr>

          <?php
            
          }
        }
        ?>
                
                </tbody>
                <tfoot>
                <tr>
                <tr>
                  <th>Student ID</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Academic Year</th>
                  <th>Term</th>
                  <th>Balance</th>
                </tr>
                </tfoot>
              </table>
              </div>
            </div>

          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
      <script type="text/javascript">
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
  </script>
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
