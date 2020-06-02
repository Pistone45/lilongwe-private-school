<?php
include_once("functions/functions.php");

$getSubClasses = new Classes();
$sub_classes = $getSubClasses->getSubClasses();

$getGender = new Gender();
$gender = $getGender->getGender();

$getBloodType = new Blood();
$blood_type = $getBloodType->getBloodType();

if(isset($_GET['id'])){
 
  $id = $_GET['id'];
  $getSpecificStudent = new Students();
  $details = $getSpecificStudent->getSpecificStudent($id);
}

if(isset($_POST['submit'])){
  
    //validate ID attachment
  //validate  file
   if(isset($_FILES['id'])){
      $errors= array();
      $file_name = $_FILES['id']['name'];
      $file_size =$_FILES['id']['size'];
      $file_tmp =$_FILES['id']['tmp_name'];
      $file_type=$_FILES['id']['type'];
    $dot = ".";

     // $file_ext=strtolower(end(explode($dot,$file_name)));

    $imagePath = "students/";
    $imagePath = $imagePath . basename($file_name);
     $file_ext = pathinfo($imagePath,PATHINFO_EXTENSION);
      $expensions= array("JPG", "jpg","PNG","png","GIF","gif");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="This file extension is not allowed.";
      }

      if($file_size > 3007152){

         $errors[]='File size must be not more than 3 MB';

      }

      if(empty($errors)==true){
    move_uploaded_file($file_tmp, $imagePath);

      }else{
       $errors[]='Error Uploading file';

         //print_r($errors);
      }
     
    $student_picture = $imagePath;
    if(strlen($student_picture)==9){
      $student_picture = $_POST['url'];
    }
   }
   
  $student_no= $_POST['student_no'];
  $firstname= $_POST['firstname'];
  $middlename = $_POST['middlename'];
  $lastname = $_POST['lastname'];
  $gender = $_POST['gender'];
  $blood_type = $_POST['blood_type'];
  $dob = $_POST['dob'];
  $place_of_birth =$_POST['place_of_birth'];
  $country_of_birth =$_POST['country_of_birth'];
  $nationality = $_POST['nationality'];
  $home_language =$_POST['home_language'];
  $year_of_entry = $_POST['year_of_entry'];
  $sporting_interests = $_POST['sporting_interests'];
  $musical_interests = $_POST['musical_interests'];
  $other_interests = $_POST['other_interests'];
  $medical_information = $_POST['medical_information'];
  $other_schools_attended = $_POST['other_schools_attended'];
  $home_doctor = $_POST['home_doctor'];
  $admission_date = $_POST['admission_date'];
  $sub_class = $_POST['sub_class'];
  
  //echo $certificate; die();
  $editStudent = new Students();
  $editStudent->editStudent($student_no, $sub_class, $student_picture,$firstname,$middlename,$lastname,$gender,$blood_type,$dob,$place_of_birth,$country_of_birth,$nationality,$home_language,$year_of_entry,$sporting_interests,$musical_interests,$other_interests,$medical_information,$other_schools_attended,$home_doctor,$admission_date);
  
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Edit Student | Lilongwe Private School</title>
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
        Edit Student
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="edit-student.php">Edit Student</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     <div class="box box-primary">
      <div class="row">
        <!-- left column -->
       <!-- form start -->
            <form role="form" action="edit-student.php" method="POST" enctype="multipart/form-data">
      <?php
                            if(isset($_SESSION["student-edited"]) && $_SESSION["student-edited"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully edited a Student";
                                unset($_SESSION["student-edited"]);
                                echo "</div>";
                 header('Refresh: 5; URL= view-students.php');
                            }
              ?>
              <div class="box-body">
        
        <div class="col-md-6">
          <!-- general form elements -->
           
        <div class="form-group">
                  <label>Select Classes</label>
                  <select name="sub_class" class="form-control">
              <option value="<?php if(isset($details)){echo $details['sub_class_id'];} ?>"><?php if(isset($details)){echo $details['sub_class'];} ?></option>
          <?php
          if(count($sub_classes)>0){
            foreach($sub_classes as $row){ ?>
              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
              <?php
            }
          }else{
            echo "No classes Available";
          }
          ?>                    
                  
                  </select>
                </div>
                <input hidden="" type="text" value="<?php if(isset($_GET['id'])){echo $_GET['id'];} ?>" name="student_no">
                <div class="form-group">
                  <label for="firstname">Firstname</label>
                  <input type="text" class="form-control" id="firstname" name="firstname" value="<?php if(isset($details)){echo $details['firstname'];} ?>" required>
                </div>
                <div class="form-group">
                  <label for="Middlename">Middlename</label>
                  <input type="text" class="form-control" id="Middlename" name="middlename" value="<?php if(isset($details)){echo $details['middlename'];} ?>">
                </div>
        
         <div class="form-group">
                  <label for="Lastname">Lastname</label>
                  <input type="text" class="form-control" id="Lastname" name="lastname" value="<?php if(isset($details)){echo $details['lastname'];} ?>" required>
                </div>
        
          <div class="form-group">
                  <label>Select Gender</label>
                  <select name="gender" class="form-control">
                  <option value="<?php if(isset($details)){echo $details['gender_id'];} ?>"><?php if(isset($details)){echo $details['gender'];} ?></option>
          <?php
          if(count($gender)>0){
            foreach($gender as $row){ ?>
              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
              <?php
            }
          }else{
            echo "No Gender Available";
          }
          ?>                    
                  
                  </select>
                </div>
        
          <div class="form-group">
                  <label>Select Blood Type</label>
                  <select name="blood_type" class="form-control">
              <option value="<?php if(isset($details)){echo $details['blood_type_id'];} ?>"><?php if(isset($details)){echo $details['blood_type'];} ?></option>
          <?php
          if(count($blood_type)>0){
            foreach($blood_type as $row){ ?>
              <option value="<?php echo $row['id']; ?>"><?php echo $row['name']?></option>
              <?php
            }
          }else{
            echo "No Blood Type Available";
          }
          ?>                    
                  
                  </select>
                </div>
        
        <div class="form-group">
                  <label for="Lastname">Date of Birth</label>
                  <input type="date" class="form-control" id="dob" name="dob" value="<?php if(isset($details)){echo $details['dob'];} ?>" required>
                </div>
        
        <div class="form-group">
                  <label for="email">Place of Birth</label>
                  <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="<?php if(isset($details)){echo $details['place_of_birth'];} ?>" required>
                </div>
        
         <div class="form-group">
                  <label for="phone">Country of Birth</label>
                  <input type="text" class="form-control" id="country_of_birth" name="country_of_birth" value="<?php if(isset($details)){echo $details['country_of_birth'];} ?>" required>
                </div>
        <div class="form-group">
                  <label for="class">Nationality</label>
                  <input type="text" class="form-control" id="nationality" name="nationality" value="<?php if(isset($details)){echo $details['nationality'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">Home Language</label>
                  <input type="text" class="form-control" id="home_language" name="home_language" value="<?php if(isset($details)){echo $details['home_language'];} ?>">
                </div>
        
                
            
              
          
         
        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
        
        
        <div class="form-group">
                  <label for="class">Year of Entry</label>
                  <input type="text" class="form-control" id="year_of_entry" name="year_of_entry" value="<?php if(isset($details)){echo $details['year_of_entry'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">Sporting Interests</label>
                  <input type="text" class="form-control" id="sporting_interests" name="sporting_interests" value="<?php if(isset($details)){echo $details['sporting_interests'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">musical_interests</label>
                  <input type="text" class="form-control" id="musical_interests" name="musical_interests" value="<?php if(isset($details)){echo $details['musical_interests'];} ?>">
                </div>
        <div class="form-group">
                  <label for="class">Other Interests</label>
                  <input type="text" class="form-control" id="other_interests" name="other_interests" value="<?php if(isset($details)){echo $details['other_interests'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">Medical Information</label>
                  <input type="text" class="form-control" id="medical_information" name="medical_information" value="<?php if(isset($details)){echo $details['medical_information'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">Other Schools Attended</label>
                  <input type="text" class="form-control" id="other_schools_attended" name="other_schools_attended" value="<?php if(isset($details)){echo $details['other_schools_attended'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">Home Doctor</label>
                  <input type="text" class="form-control" id="home_doctor" name="home_doctor" value="<?php if(isset($details)){echo $details['home_doctor'];} ?>">
                </div>
        
        <div class="form-group">
                  <label for="class">Admission Date</label>
                  <input type="date" class="form-control" id="admission_date" name="admission_date" value="<?php if(isset($details)){echo $details['admission_date'];} ?>">
                </div>        
        
        <div class="form-group">
          <input type="hidden" value="<?php if(isset($details)){echo $details['student_picture'];} ?>" name="url" />
                <img width="100" height="100" src="<?php if(isset($details)){echo $details['student_picture'];} ?>">
                  <label for="cert">Student Picture</label>
                  <input type="file" id="id"  name="id">

                 
                </div>
        <br>
        <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary btn-block">Edit Student</button>
              </div>
        
        </div>
        <!--/.col (right) -->
    
      </div>
              <!-- /.box-body -->
        
       
    </form>

      </div>
      <!-- /.row -->
    
     </div>
          <!-- /.box -->

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
