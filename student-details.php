<?php
include_once("functions/functions.php");
if(isset($_GET['id'])){
	$id = $_GET['id'];
	
	$getSpecificStudent = new Students();
	$details = $getSpecificStudent->getSpecificStudent($id);

  $getLoginStatus = new Students();
  $loginstatus = $getLoginStatus->getLoginStatus($id);
}

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
  <title>Student Details | Lilongwe Private School</title>
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
        Student Details <!-- Trigger the modal with a button -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> Contact Student</button>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
       
        <li class="active">Student Details</li>
      </ol>
    </section>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Contact Student</h4>
      </div>
      <div class="modal-body">
            <form action="student-details.php?id=<?php if(isset($_GET['id'])){ echo $_GET['id']; } ?>" method="post">
                <div class="form-group">
                  <input type="text" hidden="" name="student_no" value="<?php if(isset($_GET['id'])){ echo $_GET['id']; } ?>">
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
    <!-- Main content -->
    <section class="content">
	
	<?php
		if(count($details)>0){ ?>
		
      <div class="row">
        <div class="col-md-5">
              <?php
                  if(isset($_SESSION["message-sent"]) && $_SESSION["message-sent"]==true)
                  { ?>
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> You have successfully sent a Message
            </div>  <?php
            unset($_SESSION["message-sent"]);
                      }
              ?>
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
			
				<img class="img-responsive" src="<?php echo $details['student_picture']; ?>" alt="User profile picture">

          

              <ul class="list-group list-group-unbordered">
			  <li class="list-group-item">
                  <b>Name</b> <a class="pull-right"><?php echo $details['firstname']. ' '. $details['middlename']. ' '.$details['lastname']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Class Level</b> <a class="pull-right"><?php echo $details['sub_class']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Gender</b> <a class="pull-right"><?php echo $details['gender']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Date of Birth</b> <a class="pull-right"><?php echo $details['dob']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Country of Birth</b> <a class="pull-right"><?php echo $details['country_of_birth']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Nationality</b> <a class="pull-right"><?php echo $details['nationality']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Home Language</b> <a class="pull-right"><?php echo $details['home_language']; ?></a>
                </li>
               
              </ul>
			<div class="row">
				<div class="col-lg-6">
					<a href="edit-student.php?id=<?php echo $details['student_no']; ?>" class="btn btn-success btn-block"><b>Edit Student</b></a>
				</div>
				<div class="col-lg-6">
<?phP
if ($loginstatus['user_status_id'] == 0) { ?>
  <a href="enable-student.php?id=<?php echo $details['student_no']; ?>" class="btn btn-success btn-block"><b>Enable</b></a><?php

} else { ?>
  <a href="disable-student.php?id=<?php echo $details['student_no']; ?>" class="btn btn-danger btn-block"><b>Disable</b></a><?php

}

          
          ?>

				</div>
			</div>
              
		
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        
        </div>
        <!-- /.col -->
        <div class="col-md-7">
			  <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Extra Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Other Interests</strong>
				<p class="text-muted"><?php echo $details['other_interests']; ?></p>
              
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Place of Birth</strong>

              <p class="text-muted"><?php echo $details['place_of_birth']; ?></p>

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Medical Information</strong>

               <p class="text-muted"><?php echo $details['medical_information']; ?></p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Sporting Interests</strong>

              <p class="text-muted"><?php echo $details['sporting_interests']; ?></p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Year of Entry</strong>

              <p class="text-muted"><?php echo $details['year_of_entry']; ?></p>

              <hr>

              <strong><i class="fa fa-tint" aria-hidden="true"></i> Blood Group Type</strong>

              <p class="text-muted"><?php echo $details['blood_type']; ?></p>
              <hr>

              <strong><i class="fa fa-graduation-cap" aria-hidden="true"></i> Other Schools Attended</strong>

              <p class="text-muted"><?php echo $details['other_schools_attended']; ?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
	  
	  	<?php
				
			}
			?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <script type="text/javascript">
    window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
  </script>

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
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
