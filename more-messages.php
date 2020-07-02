<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

if (isset($_GET['id'])) {
$id = $_GET['id'];

$updateReadMessage = new Students();
$readMessage = $updateReadMessage->updateReadMessage($id);
}

$getMessages = new Students();
$messages = $getMessages->getMessages();

$getReadMessages = new Students();
$readmessages = $getReadMessages->getReadMessages();

$getBorrowedBookPerStudent = new Staff();
$books = $getBorrowedBookPerStudent->getBorrowedBookPerStudent();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>More Messages | Lilongwe Private School</title>
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
        Messages
      </h1>
      <ol class="breadcrumb">
        <li><a href="student-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Messages</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-12">
                    <div class="box box-primary">
            
           <div class="box-header">
            <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">READ MESSAGES</a></li>
  <li><a data-toggle="tab" href="#menu1">UNREAD MESSAGES</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
    <h3>READ MESSAGES</h3>
                <div class="box-body">
        <?php
        if(isset($readmessages) && count($readmessages)>0){   ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Subject</th>
                  <th>Message</th>
                  <th>Date Sent</th>
                </tr>
                </thead>
                <tbody>
          <?php
          foreach($readmessages as $readmessage){ ?>
            <tr>
              <td><?php echo $readmessage['subject']; ?></td>
              <td><?php echo $readmessage['message']; ?>
              <td><?php $date = date_create($readmessage['date_sent']); echo date_format($date,"d, M Y"); ?>
              </td>
               
            </tr>
          <?php
            
          }
        }else{
            ?>
          <div class="alert alert-info">
            <strong>No Messages!</strong> You dont have any messages at the moment.
          </div><?php
        }
        ?>
                
        
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
  </div>
  <div id="menu1" class="tab-pane fade">
    <h3>UNREAD MESSAGES</h3>
                <div class="box-body">
                      <?php
        if(isset($messages) && count($messages)>0){   ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Subject</th>
                  <th>Message</th>
                  <th>Date Sent</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
          <?php
          foreach($messages as $message){ ?>
            <tr>
              <td><?php echo $message['subject']; ?></td>
              <td><?php echo $message['message']; ?>
              <td><?php $date = date_create($message['date_sent']); echo date_format($date,"d, M Y"); ?>
              </td>
              <td><a href="more-messages.php?id=<?php echo $message['id']; ?>"><button class="btn btn-info btn-xs">Mard Read</button></a></td>
               
            </tr>
          <?php
            
          }
        }else{  ?>
          <div class="alert alert-info">
            <strong>No Messages!</strong> You dont have any messages at the moment.
          </div><?php
        }
        ?>
                
        
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
  </div>
</div>
            </div>
            <!-- /.box-header -->

            
        </div>
        </div>
        
        <!-- ./col -->
      </div>




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
