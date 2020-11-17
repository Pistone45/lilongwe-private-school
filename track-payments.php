<?php
include_once("functions/functions.php");

if (isset($_GET['id'])) {
$id = $_GET['id'];

$getSpecificStudent = new Students();
$student = $getSpecificStudent->getSpecificStudent($id);

}


$student_no = $_GET['id'];
$getSpecificFeesPerStudent = new Staff();
$payments = $getSpecificFeesPerStudent->getSpecificFeesPerStudent($student_no);

$status = 1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);
$settings['academic_year'];
$settings['term'];


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Track Payment | Lilongwe Private School</title>
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
      <h1 style="text-transform: uppercase;">
        Payment Details for <?php echo $student['firstname']." ".$student['lastname']; ?>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="accountant-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Track Payment</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
          <div class="row">
          <div class="col-md-12 col-xs-12">
            <div class="box">
            <div class="box-body">
            <h4>Payments for <?php echo $student['student_no']; ?></h4>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Class Name</th>
                  <th>Amount</th>
                  <th>Date Paid</th>
                  <th>Year</th>
                  <th>Term</th>
                  <th>Reference</th>
                </tr>
                </thead>
                <tbody>
        <?php
        $i = 0;
        if(isset($payments) && count($payments)>0){
          foreach($payments as $payment){ 
            $i++;   ?>
          <tr>
                  <td><?php echo $payment['sub_class_name']; ?></td>
                  <td>K<?php echo number_format($payment['amount']); ?></td>
                  <td><?php echo $payment['date_paid'];?></td>
                  <td><?php echo $payment['academic_year'];?></td>
                  <td><?php echo $payment['term'];?></td>
                  <td><?php echo $payment['ref_num'];?></td>

                </tr>


          <?php
            
          }
        }else{
          echo "No Payments Found";
        }
        ?>
                
                </tbody>
              </table>
            </div>
            </div>
        </div>
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
</body>
</html>
