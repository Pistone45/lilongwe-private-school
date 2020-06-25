<?php
include_once("functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

$checkPassword = new User();
$checkPassword = $checkPassword->checkPassword();

if (isset($_GET['id'])) {
$id = $_GET['id'];

$updateReadMessage = new Students();
$readMessage = $updateReadMessage->updateReadMessage($id);
}

$getStudentDetails = new Students();
$details = $getStudentDetails->getStudentDetails();
$sub_class_id = $details['sub_class_id'];//form 2 west = 5 

$getAllStudentsAssignment = new Students();
$assignments = $getAllStudentsAssignment->getAllStudentsAssignment($sub_class_id);
		
$getStudents = new Students();
$students = $getStudents->getStudents();

$countAllUsers = new User();
$users = $countAllUsers->countAllUsers();

$getNotices = new Staff();
$notice = $getNotices->getNotices();

$getMessages = new Students();
$messages = $getMessages->getMessages();

$getBorrowedBookPerStudent = new Staff();
$books = $getBorrowedBookPerStudent->getBorrowedBookPerStudent();

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
      <h1 style="text-transform: uppercase;">
        Welcome <?php echo $user_details['firstname'].' '.$user_details['middlename'].' '.$user_details['lastname']; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="student-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-6 col-xs-6">
          <div class="box box-primary">
            
           <div class="box-header">
              <h3 class="box-title">Notice Board</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                      <?php
        $i = 0;
        if(isset($notice) && count($notice)>0){  ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Notice</th>
                  <th>Dealine</th>
                </tr>
                </thead>
                <tbody>
<?php
          foreach($notice as $notices){ 
            $i++;   ?>
            <tr>
              <td><?php echo substr($notices['notice'],0, 50); ?>....</td>
              <td><?php echo $notices['deadline']; ?>.....<button class="btn btn-success btn-xs" data-toggle="modal" data-target="#notice<?php echo $i; ?>">Read More</button></td>
               
               <!-- Modal -->
<div id="notice<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Read More</h4>
      </div>
      <div class="modal-body">
        <div class="well well-sm"><h5><?php echo $notices['notice']; ?></h5></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

            </tr>
          <?php
            
          }
        }else{  ?>
          <div class="alert alert-info">
            <strong>No Notifications!</strong> You dont have any notifications at the moment.
          </div><?php
        }
        ?>
                
        
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
            
        </div>
        </div>

      <div class="col-lg-6 col-xs-6">
          <div class="box box-primary">
            
           <div class="box-header">
              <h3 class="box-title">Unread Messages</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                      <?php
        $i = 0;
        if(isset($messages) && count($messages)>0){  ?>
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
          foreach($messages as $message){ 
            $i++;   ?>
            <tr>
              <td><?php echo substr($message['subject'],0, 50); ?>....</td>
              <td><?php echo substr($message['message'],0, 50); ?>....<button class="btn btn-success btn-xs" data-toggle="modal" data-target="#<?php echo $i; ?>">Read More</button></td>
              <td><?php $date = date_create($message['date_sent']); echo date_format($date,"d, M Y"); ?>
              </td>
               
               <!-- Modal -->
<div id="<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Read More</h4>
      </div>
      <div class="modal-body">
        <h2><?php echo $message['subject']; ?></h2>
        <div class="well well-sm"><h5><?php echo $message['message']; ?></h5></div>
      </div>
      <div class="modal-footer">
        <a href="student-index.php?id=<?php echo $message['id']; ?>"><button class="pull-left btn btn-info">Mark Read</button></a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

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
               
              </table></i></p>
              <a href="more-messages.php"><button type="submit" class=" btn btn-default" name="send_message">See Read Messages
                <i class="fa fa-arrow-circle-right"></i></button></a>
            </div>
      </div>
    </div>        

</div>
<!-- ./col -->

      <div class="row">
          <div class="col-lg-6">
                    <div class="box box-primary">
            
           <div class="box-header">
              <h3 class="box-title">Assignment Deadlines:</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                      <?php
        if(isset($assignments) && count($assignments)>0){ ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Due Date</th>
                  <th>Subject</th>
                  <th>Assignment Type</th>
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
          <td><?php echo $assignment['subject_name']; ?> </td>
          <td><?php echo $assignment['assignment_type_name']; ?> </td>
          </tr>
          <?php
            
          } ?>

                
                </tbody>
              </table> <?php
                      }else{
                        echo "No assignments Available at the moment";
                      }
        ?>
            </div>
            <!-- /.box-body -->
            
        </div>
        </div>

      <div class="col-lg-6">
        <div class="box box-primary">
          
         <div class="box-header">
            <h3 class="box-title">Borrowed Books</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
                    <?php
      if(isset($books) && count($books)>0){ ?>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Date Borrowed</th>
                <th>Days Remaining</th>
              </tr>
              </thead>
              <tbody>
                <?php
        foreach($books as $book){ ?>
        <tr>
                <td><?php echo $book['book_id']; ?></td>
                <td><?php echo $book['title']; ?></td>
                <td><?php echo $book['author']; ?></td>
                <td><button class="btn btn-default"><?php $date = date_create($book['date_borrowed']); echo date_format($date,"d, M Y") ?></button></td>
              <td><?php  $date = round(abs(strtotime($book['due_date']) - strtotime($book['date_borrowed']))/86400); if($date <= 0){  ?>
                <button class="btn btn-danger">0 Days. Please Retun Book!</button>
              <?php

          }else{  ?>
              <p style="font-size: 20px;"><span class="label label-default">
              <?php echo$date = round(abs(strtotime($book['due_date']) - strtotime($book['date_borrowed']))/86400); ?></span> Days</p><?php
          } ?>

                
              </td>

        </tr>
        <?php
          
        } ?>

              
              </tbody>
            </table> <?php
                    }else{
                      echo "No Borrowed Books Available at the moment";
                    }
      ?>
          </div>
          <!-- /.box-body -->
          
      </div>
      </div>


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
