<?php
include_once("functions/functions.php");

if(isset($_POST['upload'])){
  $assignments_id = $_POST['assignments_id'];

  $target = "assignments/students/";
  $target = $target . basename($_FILES['assignment']['name']);
  $ok = 1;
  if (move_uploaded_file($_FILES['assignment']['tmp_name'], $target))
  {

    $submitted_assignment= basename($_FILES['assignment']['name']);

    $uploadStudentAssignment = new Students();
    $uploadStudentAssignment->uploadStudentAssignment($assignments_id, $submitted_assignment);
  }
  else
  {
    echo "Sorry, there was a problem uploading your file.";
  }


}

$status = 1;
$getCurrentSettings = new settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

$getTerm = new Staff();
$term = $getTerm->getTerm();

  if (isset($_GET['id'])) {$assignments_id = $_GET['id'];}

  $getUploadedStudentAssignment = new Students();
  $uploaded = $getUploadedStudentAssignment->getUploadedStudentAssignment($assignments_id);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Upload Assignment| Lilongwe Private School</title>
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
        Upload Assignment
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Upload Assignment</a></li>
       
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <?php
        if(isset($uploaded) && count($uploaded)>0){
          foreach($uploaded as $upload){
          $due_date = $upload['due_date'];
          $date = DATE("Y-m-d h:i");

        if ($due_date < $date) { ?>
<!-- Execute this code when the assignment due date is passed -->
            <div class="col-md-6">
          <div class="box box-primary">
          <div class="box-body">
            <h4><b>Uploaded Assignments</b></h4>

                       <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Subject</th>
                  <th>Marks</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($uploaded) && count($uploaded)>0){
          foreach($uploaded as $upload){ ?>
          <tr>
                  <td><?php echo $upload['assignment_title']; ?></td>
          <td><?php echo $upload['subject_name']; ?> </td>
          <td><?php if($upload['marks'] == ""){echo "<b>Not Marked</b>";}else{echo$upload['marks'];} ?> </td>
          <td><a href="assignments/students/<?php echo $upload['submitted_assignment']; ?>"><i class="fa fa-edit"></i> Download</a></td>
                </tr>
          <?php
            
          }
        } else{
          echo "You have not Uploaded any Assignment";
        }
        ?>
                
                </tbody>
              </table>   

          </div>
          </div>
        </div>
<!-- End -->      
          <?php
          
        } else { ?>

<!-- Execute this code when the condition is false and allow the student to upload only according to date -->
          <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            
           
            <!-- form start -->
            <form action="upload-student-assignment" enctype="multipart/form-data" method="post">
           <?php
                            if(isset($_SESSION["uploaded"]) && $_SESSION["uploaded"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully uploaded an Assignment";
                                unset($_SESSION["uploaded"]);
                                echo "</div>";
                 header('Refresh: 5; URL= view-student-assignment.php');
                            }
              ?>
              <div class="box-body">

              <div class="form-group">
                <label for="exampleFormControlFile1">Assignment</label>
                <input type="file" name="assignment" required="" class="form-control-file" id="exampleFormControlFile1">
              </div>
          <input type="text" hidden="" value="<?php if (isset($_GET['id'])) {echo $assignments_id = $_GET['id'];} ?>" name="assignments_id">
      
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box box-primary">
          <div class="box-body">
            <h4><b>Uploaded Assignments</b></h4>

                       <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Subject</th>
                  <th>Marks</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($uploaded) && count($uploaded)>0){
          foreach($uploaded as $upload){ ?>
          <tr>
                  <td><?php echo $upload['assignment_title']; ?></td>
          <td><?php echo $upload['subject_name']; ?> </td>
          <td><?php if($upload['marks'] == ""){echo "<b>Not Marked</b>";}else{echo$upload['marks'];} ?> </td>
          <td><a href="assignments/students/<?php echo $upload['submitted_assignment']; ?>"><i class="fa fa-edit"></i> Download</a></td>
                </tr>
          <?php
            
          }
        } else{
          echo "You have not Uploaded any Assignment";
        }
        ?>
                
                </tbody>
              </table>   

          </div>
          </div>
        </div>
          <?php
          # code...
        }
        
            
          }
        } else{ ?>

 <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            
           
            <!-- form start -->
            <form action="upload-student-assignment" enctype="multipart/form-data" method="post">
           <?php
                            if(isset($_SESSION["uploaded"]) && $_SESSION["uploaded"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully uploaded an Assignment";
                                unset($_SESSION["uploaded"]);
                                echo "</div>";
                 header('Refresh: 5; URL= view-student-assignment.php');
                            }
              ?>
              <div class="box-body">

              <div class="form-group">
                <label for="exampleFormControlFile1">Assignment</label>
                <input type="file" name="assignment" required="" class="form-control-file" id="exampleFormControlFile1">
              </div>
          <input type="text" hidden="" value="<?php if (isset($_GET['id'])) {echo $assignments_id = $_GET['id'];} ?>" name="assignments_id">
      
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box box-primary">
          <div class="box-body">
            <h4><b>Uploaded Assignments</b></h4>

                       <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Title</th>
                  <th>Subject</th>
                  <th>Marks</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
        <?php
        if(isset($uploaded) && count($uploaded)>0){
          foreach($uploaded as $upload){ ?>
          <tr>
                  <td><?php echo $upload['assignment_title']; ?></td>
          <td><?php echo $upload['subject_name']; ?> </td>
          <td><?php if($upload['marks'] == ""){echo "<b>Not Marked</b>";}else{echo$upload['marks'];} ?> </td>
          <td><a href="assignments/students/<?php echo $upload['submitted_assignment']; ?>"><i class="fa fa-edit"></i> Download</a></td>
                </tr>
          <?php
            
          }
        } else{
          echo "You have not Uploaded any Assignment";
        }
        ?>
                
                </tbody>
              </table>   

          </div>
          </div>
        </div>
          <?php
          
        }
        ?>
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
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
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
