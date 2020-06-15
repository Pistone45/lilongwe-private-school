<?php
include_once("functions/functions.php");

if (isset($_GET['id'])) {
$id = $_GET['id'];

$getSpecificStudent = new Students();
$student = $getSpecificStudent->getSpecificStudent($id);

}



if (isset($_POST['record'])) {
$fees = $_POST['fees'];
$student_no = $_POST['student_no'];
$academic_year = $_POST['academic_year'];
$term = $_POST['term'];
$remarks = $_POST['remarks'];

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
//usage 
$ref_num = generateRandomString(40);

$RecordFees = new Staff();
$RecordFees = $RecordFees->RecordFees($fees, $student_no, $academic_year, $term, $remarks, $ref_num);
  
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
  <title>Track Payment| Lilongwe Private School</title>
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
          <div class="col-md-5 col-xs-12">
            <div class="box">
        <?php
          if(isset($_SESSION["fees-recorded"]) && $_SESSION["fees-recorded"]==true)
          {
              echo "<div class='alert alert-success'>";
              echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
              echo "<strong>Success! </strong>"; echo "You have successfully Recorded Fees for a Student";
              unset($_SESSION["fees-recorded"]);
              echo "</div>";
          //header('Refresh: 5; URL= view-books.php');
          }
        ?>
            <div class="box-body">
            <h4>Record Payment:</h4>
              <form role="form" action="track-payments.php?id=<?php echo$_GET['id']; ?>" method="POST">
              <div class="form-group">
                <label for="exampleInputEmail1">Fees</label>
                <input type="number" name="fees" class="form-control" placeholder="Enter Fees E.g 10000" required="">
                <small id="emailHelp" class="form-text text-muted">Do not put commas.</small>
              </div>

                <div class="form-group">
                  <label for="exampleFormControlTextarea1">Remarks</label>
                  <textarea name="remarks" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>

                <input type="text" hidden="" name="student_no" value="<?php echo $student['student_no']; ?>">
                <input type="text" hidden="" name="academic_year" value="<?php echo $settings['academic_year']; ?>">
                <input type="text" hidden="" name="term" value="<?php echo $settings['term']; ?>">
                
                <button type="submit" name="record" class="btn btn-primary">Record Fees</button>
            </form>
            </div>
            </div>
          </div>
          <div class="col-md-7 col-xs-12">
            <div class="box">
            <div class="box-body">
            <h4>Payments for <?php echo $student['student_no']; ?></h4>
        <?php
        $i = 0;
        if(isset($payments) && count($payments)>0){
          foreach($payments as $payment){ 
            $i++;   ?>
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
          <tr>
                  <td><?php echo $payment['sub_class_name']; ?></td>
                  <td><?php echo $payment['amount']; ?></td>
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


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

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
