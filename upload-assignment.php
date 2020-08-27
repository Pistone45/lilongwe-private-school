<?php
include_once("functions/functions.php");

//get the current academic_year and term
$status = 1;
$getCurrentSettings = new settings();
$settings = $getCurrentSettings->getCurrentSettings($status);

//get the assignment types
$getAssignmentType = new Staff();
$types = $getAssignmentType->getAssignmentType();


//upload thhe assignment to database
if(isset($_POST['submit'])){
	$level = $_POST['level'];
	
}

if(isset($_POST['upload'])){
  $assignment_type = $_POST['assignment_type'];
  $subjects_id = $_POST['subjects_id'];
  $title = $_POST['title'];
  $date = $_POST['due_date'];
  $academic_year = (int)$settings['academic_year'];
  $terms_id = (int)$settings['term'];
  $level = $_POST['level'];

  $due_date = date('Y-m-d h:i',strtotime($date . "+1 days"));

  $target = "assignments/";
  $target = $target . basename($_FILES['assignment']['name']);
  $ok = 1;
  if (move_uploaded_file($_FILES['assignment']['tmp_name'], $target))
  {

    $assignment_url= basename($_FILES['assignment']['name']);

    $uploadAssignment = new Staff();
    $uploadAssignment->uploadAssignment($title, $assignment_url, $due_date, $academic_year, $terms_id,$subjects_id,$level, $assignment_type);
  }
  else
  {
    echo "Sorry, there was a problem uploading your file.";
  }


}



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Select Subject Assignment | Lilongwe Private School</title>
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
        Subject Assignment
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="teacher-index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Subject Assignment</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            
           
            <!-- form start -->
            <form action="upload-assignment.php" enctype="multipart/form-data" method="post">
			     <?php
                            if(isset($_SESSION["uploaded"]) && $_SESSION["uploaded"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully uploaded an Assignment";
                                unset($_SESSION["uploaded"]);
                                echo "</div>";
                 header('Refresh: 5; URL= view-assignments.php');
                            }
              ?>
              <div class="box-body">
			   
                      <div class="form-group">
              <label for="exampleInputEmail1">Assignments Title</label>
              <input type="text" name="title" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Title">
            </div>

              <div class="form-group">
                <label for="exampleFormControlFile1">Assignment</label>
                <input type="file" name="assignment" class="form-control-file" id="exampleFormControlFile1">
              </div>

              <div class="form-group">
                  <label>Select Assignment Type </label>
                  <select name="assignment_type" class="form-control">
          <?php
            if(isset($types) && count($types)>0){
              foreach($types as $type){ ?>
                <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
              <?php
                
              }
            }
          ?>
        
                  </select>
              </div>

              <div class="form-group">
              <label for="exampleInputEmail1">Due Date</label>
              <input type="date" name="due_date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="">
              <small style="color: red;" id="emailHelp" class="form-text text-muted">Select the due date</small>
            </div>

            <input type="text" hidden="" value="<?php if(isset($_POST['subjects_id'])) {echo $_POST['subjects_id'];}?>" name="subjects_id">
            <input type="text" hidden="" value="<?php if(isset($level)){ echo $level;} ?>" name="level">
				
			
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
          
        </div>
        <!--/.col (right) -->
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
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
