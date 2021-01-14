<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

$status=1;
$getCurrentSettings = new Settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$fees = $settings['fees'];
$academic_year = $settings['academic_year'];
$term = $settings['term'];

$checkPassword = new User();
$checkPassword = $checkPassword->checkPassword();



if (isset($_POST['submit'])) {
$notice = $_POST['notice'];
$deadline = $_POST['deadline'];
  
$AddNotice = new Staff();
$AddNotice = $AddNotice->AddNotice($notice, $deadline);

}


if (isset($_GET['id'])) {
$id = $_GET['id'];

$deleteNotice = new Staff();
$deleteNotice = $deleteNotice->deleteNotice($id);


}


$getStudents = new Students();
$students = $getStudents->getStudents();

$countAllUsers = new User();
$users = $countAllUsers->countAllUsers();

$getNotices = new Staff();
$notice = $getNotices->getNotices();

$getFeesBalanceCount = new Staff();
$fees_balance_count = $getFeesBalanceCount->getFeesBalanceCount($fees, $academic_year, $term);

$getAllBookCount = new Staff();
$bookCount = $getAllBookCount->getAllBookCount();


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lilongwe Private School| Dashboard</title>
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
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

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
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo$fees_balance_count['fees_count']; ?></h3>

              <p>Students with Fees Balance</p>
            </div>
            <div class="icon">
              <i class="ion ion-cash"></i>
            </div>
            <a href="fees-balances.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $bookCount['book_count'];?></h3>

              <p>Book Count</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-document"></i>
            </div>
            <a href="admin-view-books.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php if(isset($students)){echo count($students);}else{echo "0";} ?></h3>

              <p>Registered Students</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="view-students.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php if(isset($users)){echo count($users);}else{echo "0";} ?></h3>

              <p>System Users</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="view-users.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#myModal">Add Notice</button>
          <div class="box box-primary">
           <div class="box-header">
              <h3 class="box-title">Notice Board</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Notice</th>
                  <th>Deadline</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($notice) && count($notice)>0){
          foreach($notice as $notices){ ?>
            <tr>
              <td><?php echo $notices['notice']; ?></td>
              <td><?php echo $notices['deadline']; ?></td>
              <td><a href="index.php?id=<?php echo $notices['id']; ?>"><button class="btn btn-danger">Delete</button></a></td>
              </td>
               
            </tr>
          <?php
            
          }
        }
        ?>
                
        
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
            
        </div>
          <!-- /.box -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Notice</h4>
      </div>
      <div class="modal-body">

        <form role="form" action="index.php" method="POST">
          <div class="form-group">
            <label for="notice">Notice:</label>
            <textarea placeholder="Type Notice here" name="notice" class="form-control" rows="5" id="notice" required=""></textarea>
          </div>
          <div class="form-group">
            <label for="pwd">Due Date:</label>
            <input type="date" required="" name="deadline" class="form-control" id="deadline">
          </div>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

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
